<?php
// Mulai session hanya jika belum aktif
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
ob_start();

$dbPath = __DIR__ . '/private/database/user.json';
$json = file_exists($dbPath) ? file_get_contents($dbPath) : '[]';
$db = json_decode($json, true);
if (!is_array($db)) $db = [];

$success = $error = "";

// Endpoint ambil data user untuk frontend
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['get_user'])) {
    header('Content-Type: application/json');
    echo isset($_SESSION['user']) ? json_encode($_SESSION['user']) : json_encode(['error' => 'Belum login']);
    exit;
}

// Proses form login / register
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'register') {
        $name = trim($_POST['name'] ?? '');
        $email = strtolower(trim($_POST['email'] ?? ''));
        $password = $_POST['password'] ?? '';
        $verify_code = $_POST['verify_code'] ?? '';
        $real_code = $_POST['real_code'] ?? '';

        if (!$name || !$email || !$password || !$verify_code || !$real_code) {
            $error = "Semua field harus diisi.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Format email tidak valid.";
        } elseif ($verify_code !== $real_code) {
            $error = "Kode verifikasi salah.";
        } else {
            foreach ($db as $user) {
                if (isset($user['email']) && $user['email'] === $email) {
                    $error = "Email sudah digunakan.";
                    break;
                }
            }
            if (!$error) {
                $db[] = [
                    "name" => htmlspecialchars($name),
                    "email" => $email,
                    "password" => password_hash($password, PASSWORD_BCRYPT)
                ];
                file_put_contents($dbPath, json_encode($db, JSON_PRETTY_PRINT));
                $success = "Pendaftaran berhasil. Silakan login.";
            }
        }
    } elseif ($action === 'login') {
        $email = strtolower(trim($_POST['email'] ?? ''));
        $password = $_POST['password'] ?? '';

        if ($email && $password) {
            foreach ($db as $user) {
                if ($user['email'] === $email && password_verify($password, $user['password'])) {
                    $_SESSION['user'] = [
                        'name' => $user['name'],
                        'email' => $user['email']
                    ];
                    header("Location: ../index.php");
                    exit;
                }
            }
            $error = "Email atau password salah.";
        } else {
            $error = "Email dan password harus diisi.";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Daftar</title>
    <script src="https://cdn.jsdelivr.net/npm/@emailjs/browser@4/dist/email.min.js"></script>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #1a1a40, #2e86de);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            flex-direction: column;
        }
        .container {
            width: 350px;
            background: #2c3e50;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 0 15px rgba(0,0,0,0.4);
            animation: fadeIn 0.6s ease;
        }
        .container h2 { text-align: center; margin-bottom: 20px; }
        input, button {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: none;
            border-radius: 10px;
        }
        input { background: #ecf0f1; color: #333; }
        button {
            background: #3498db;
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.2s ease;
        }
        button:hover {
            background: #2980b9;
            transform: scale(1.03);
        }
        .switch-btn {
            background: none;
            border: none;
            color: #ddd;
            text-decoration: underline;
            cursor: pointer;
            margin-top: 10px;
        }
        #notif {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #2ecc71;
            color: white;
            padding: 15px 20px;
            border-radius: 10px;
            animation: bounce 0.5s ease;
            display: none;
            z-index: 999;
        }
        @keyframes bounce {
            0% { transform: translateY(-50px); opacity: 0; }
            50% { transform: translateY(10px); opacity: 1; }
            100% { transform: translateY(0); }
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }
    </style>
</head>
<body>

    <div id="notif"></div>

    <div class="container" id="loginBox" style="<?= isset($_POST['action']) && $_POST['action'] == 'register' ? 'display:none;' : '' ?>">
        <h2>Login</h2>
        <form method="POST">
            <input type="hidden" name="action" value="login">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <button class="switch-btn" onclick="switchForm('register')">Belum punya akun? Daftar</button>
    </div>

    <div class="container" id="registerBox" style="<?= isset($_POST['action']) && $_POST['action'] == 'register' ? '' : 'display:none;' ?>">
        <h2>Daftar</h2>
        <form method="POST">
            <input type="hidden" name="action" value="register">
            <input type="text" name="name" placeholder="Nama Lengkap" required>
            <input type="email" id="email" name="email" placeholder="Email" required>
            <button type="button" onclick="sendCode()">Kirim Kode Verifikasi</button>
            <input type="number" name="verify_code" placeholder="Kode Verifikasi" required>
            <input type="hidden" id="real_code" name="real_code">
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Daftar</button>
        </form>
        <button class="switch-btn" onclick="switchForm('login')">Sudah punya akun? Login</button>
    </div>

    <script>
        // Inisialisasi EmailJS
        emailjs.init('-74AhVg_5O6x8R4CW'); // Ganti dengan User ID EmailJS kamu

        // Fungsi kirim kode verifikasi
        function sendCode() {
            const email = document.getElementById("email").value;
            if (!email) {
                showNotif("Masukkan email terlebih dahulu.", true);
                return;
            }

            // Generate kode 4 digit
            const code = Math.floor(1000 + Math.random() * 9000).toString();
            document.getElementById("real_code").value = code;

            // Kirim email via EmailJS
            emailjs.send("service_y6ub11m", "template_wbjlpgs", {
                to_email: email,
                code: code
            }).then(() => {
                showNotif("Kode verifikasi dikirim ke email.");
            }, (err) => {
                showNotif("Gagal mengirim kode: " + JSON.stringify(err), true);
            });
        }

        // Fungsi notifikasi popup dengan animasi bounce
        function showNotif(msg, isError = false) {
            const notif = document.getElementById("notif");
            notif.innerText = msg;
            notif.style.background = isError ? "#e74c3c": "#2ecc71";
            notif.style.display = "block";
            setTimeout(() => notif.style.display = "none", 3000);
        }

        function switchForm(form) {
            document.getElementById('loginBox').style.display = form === 'login' ? 'block': 'none';
            document.getElementById('registerBox').style.display = form === 'register' ? 'block': 'none';
        }

        <?php if ($success): ?> showNotif("<?= $success ?>"); <?php endif; ?>
        <?php if ($error): ?> showNotif("<?= $error ?>", true); <?php endif; ?>
    </script>
</body>
</html>