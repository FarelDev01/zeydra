<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['profile_image'])) {
        // Ambil data base64
        $data = $_POST['profile_image'];

        // Hapus prefix base64 jika ada
        $data = str_replace('data:image/jpeg;base64,', '', $data);
        $data = str_replace(' ', '+', $data);

        // Dekode base64 menjadi gambar biner
        $imageData = base64_decode($data);

        // Tentukan path untuk menyimpan gambar
        $profile_dir = 'page/image/user/profile/';
        $filename = strtolower(preg_replace("/[^a-z0-9]/", "_", $_SESSION['user']['email'])) . ".jpg";
        $filePath = $profile_dir . $filename;

        // Coba simpan gambar
        if (file_put_contents($filePath, $imageData)) {
            echo json_encode(['success' => true, 'message' => 'Foto berhasil diunggah']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal mengunggah gambar']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Data gambar tidak ditemukan']);
    }
}
?>
