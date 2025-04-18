<?php
// Ambil data user dari file JSON
$data = json_decode(file_get_contents('user.json'), true);
$total_users = count($data);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Daftar User</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body class="bg-gray-900 text-white font-sans">

  <div class="max-w-4xl mx-auto px-4 py-10">
    <h1 class="text-3xl font-bold mb-6 text-center">Daftar User Terdaftar</h1>

    <div class="overflow-x-auto bg-gray-800 rounded-lg shadow">
      <table class="min-w-full table-auto">
        <thead>
          <tr class="bg-orange-600 text-white text-left">
            <th class="px-6 py-3">No</th>
            <th class="px-6 py-3">Nama</th>
            <th class="px-6 py-3">Email</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($data as $index => $user): ?>
            <tr class="border-b border-gray-700 hover:bg-gray-700 transition">
              <td class="px-6 py-4"><?= $index + 1 ?></td>
              <td class="px-6 py-4"><?= htmlspecialchars($user['name']) ?></td>
              <td class="px-6 py-4"><?= htmlspecialchars($user['email']) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <div class="mt-6 text-center">
      <p class="text-gray-300">Total Pengguna: <span class="text-orange-400 font-semibold"><?= $total_users ?></span></p>
    </div>
  </div>

</body>
</html>
