<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: auth.php");
    exit;
}

$user = $_SESSION['user'];
$profile_dir = "page/image/user/profile/";
$filename = strtolower(preg_replace("/[^a-z0-9]/", "_", $user['email'])) . ".jpg";
$profile_path = $profile_dir . $filename;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profile</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: #121212;
            color: #ddd;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }

        .card {
            background: #1f1f1f;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            width: 400px;
            text-align: center;
            animation: fadeIn 0.5s ease;
            max-width: 100%;
        }

        .card h2 {
            font-size: 24px;
            margin-bottom: 20px;
            font-weight: 700;
            color: #fff;
        }

        .profile-pic-container {
            position: relative;
            margin-bottom: 20px;
            width: 120px;
            height: 120px;
            overflow: hidden;
            border-radius: 50%;
            border: 3px solid dodgerblue;
            margin: 0 auto;
        }

        .profile-pic {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: all 0.3s ease;
        }

        .edit-icon {
            position: absolute;
            bottom: 5px;
            right: 5px;
            background: rgba(0, 0, 0, 0.6);
            color: white;
            border-radius: 50%;
            padding: 6px;
            cursor: pointer;
            transition: 0.3s;
            margin: 5px;
        }

        .edit-icon:hover {
            background: #4CAF50;
        }

        .btn, .btn-des {
            background: dodgerblue;
            color: white;
            border: none;
            padding: 12px 20px;
            margin-top: 20px;
            border-radius: 10px;
            cursor: pointer;
            transition: 0.3s;
            width: 100%;
            font-size: 16px;
        }

        .btn:hover, .btn-des:hover {
            background: cyan;
        }

        .upload-form {
            margin-top: 20px;
        }

        input[type="file"] {
            padding: 10px;
            border-radius: 8px;
            background: #333;
            color: #ddd;
            width: 100%;
            margin-bottom: 20px;
            border: 1px solid dodgerblue;
        }

        .error-msg {
            color: #f39c12;
            font-size: 14px;
            margin-top: 10px;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background: #2a2a2a;
            padding: 30px;
            border-radius: 15px;
            width: 450px;
            text-align: center;
        }

        .cropper-container {
            max-width: 100%;
            max-height: 400px;
            width: 100%;
            overflow: hidden;
            margin-bottom: 20px;
            display: flex;
            justify-content: center;
        }
        #cropCanvas {
            max-width: 100%;
            max-height: 400px;
            width: auto;
            height: auto;
            display: block;
        }

        /* Responsiveness */
        @media (max-width: 400px) {
            .card {
                width: 100%;
                padding: 20px;
            }
            .profile-pic-container {
                width: 100px;
                height: 100px;
            }
            .btn, .btn-des {
                padding: 10px 14px;
            }
        }
    </style>
</head>
<body>
    <div class="card">
        <h2>Zeydra Profile</h2>
        <div class="profile-pic-container">
            <img class="profile-pic" src="<?= file_exists($profile_path) ? $profile_path : 'page/image/user/user.png' ?>" alt="Foto Profil">
            <span class="edit-icon" onclick="openModal()">
                <i class="fas fa-pencil-alt"></i>
            </span>
        </div>
        <p>
            <strong>Name:</strong> <?= htmlspecialchars($user['name']) ?>
        </p>
        <p>
            <strong>Email:</strong> <?= htmlspecialchars($user['email']) ?>
        </p>

        <form method="post" action="logout.php">
            <button class="btn">Logout</button>
        </form>
        <button class="btn-des" onclick="history.back()">Back</button>
    </div>

    <!-- Modal untuk upload foto -->
    <div id="uploadModal" class="modal">
        <div class="modal-content">
            <h3>Upload Image</h3>
            <input type="file" id="imageInput" accept="image/*"><br><br>

            <div class="cropper-container">
                <canvas id="cropCanvas"></canvas>
            </div>

            <button class="btn" id="cropBtn">Upload</button>
            <button class="btn-des" onclick="closeModal()">Close</button>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
    <script>
        let cropper;
        function openModal() {
            // Sembunyikan semua kontainer aktif lainnya
            document.getElementById('uploadModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('uploadModal').style.display = 'none';
        }

        document.getElementById('imageInput').addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const image = new Image();
                    image.src = e.target.result;
                    image.onload = function () {
                        const canvas = document.getElementById('cropCanvas');
                        canvas.style.display = 'block';
                        canvas.width = image.width;
                        canvas.height = image.height;
                        const ctx = canvas.getContext('2d');
                        ctx.drawImage(image, 0, 0);

                        if (cropper) cropper.destroy();
                        cropper = new Cropper(canvas, {
                            aspectRatio: 4 / 5,  // Set minimal ratio 4:5
                            viewMode: 2,
                            autoCropArea: 0.8,
                            responsive: true
                        });
                    };
                };
                reader.readAsDataURL(file);
            }
        });

        document.getElementById('cropBtn').addEventListener('click', function () {
            if (cropper) {
                const croppedCanvas = cropper.getCroppedCanvas();
                const croppedImage = croppedCanvas.toDataURL('image/jpeg');
                
                // Kirim gambar yang sudah dipotong ke server
                const formData = new FormData();
                formData.append("profile_image", croppedImage);

                fetch("upload.php", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert("Photo uploaded successfully");
                        closeModal();
                        location.reload();
                    } else {
                        alert("Failed : " + data.message);
                    }
                })
                .catch(error => {
                    alert("Error Uploading photo.");
                });
            }
        });
    </script>
</body>
</html>
