<?php
session_start();

// Periksa apakah pengguna telah login
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit();
}

// Koneksi ke database
$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "lms_db";
$conn = new mysqli($servername, $username_db, $password_db, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil user_id dari sesi
$user_id = $_SESSION['user_id'];

// Ambil data dari form
$newNama = isset($_POST['newNama']) ? $_POST['newNama'] : '';
$newBio = isset($_POST['newBio']) ? $_POST['newBio'] : '';
$newTanggalLahir = isset($_POST['newTanggalLahir']) ? $_POST['newTanggalLahir'] : '';
$newHobi = isset($_POST['newHobi']) ? $_POST['newHobi'] : '';
$newStatus = isset($_POST['newStatus']) ? $_POST['newStatus'] : '';
$profile_picture = '';

// Validasi tanggal lahir
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $newTanggalLahir)) {
    $newTanggalLahir = NULL;  // Atur nilai NULL jika format tanggal tidak valid
}

// Jika ada gambar profil baru
if (isset($_FILES['newProfilePicture']) && $_FILES['newProfilePicture']['error'] == 0) {
    $target_dir = "img2/";
    $target_file = $target_dir . basename($_FILES["newProfilePicture"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Periksa apakah file adalah gambar
    $check = getimagesize($_FILES["newProfilePicture"]["tmp_name"]);
    if($check !== false) {
        // Periksa ukuran file
        if ($_FILES["newProfilePicture"]["size"] <= 500000) { // 500KB
            // Periksa format file
            if ($imageFileType == "jpg" || $imageFileType == "png" || $imageFileType == "jpeg" || $imageFileType == "gif") {
                if (move_uploaded_file($_FILES["newProfilePicture"]["tmp_name"], $target_file)) {
                    $profile_picture = $target_file;
                } else {
                    echo json_encode(['error' => 'Error uploading file']);
                    exit();
                }
            } else {
                echo json_encode(['error' => 'Only JPG, JPEG, PNG & GIF files are allowed']);
                exit();
            }
        } else {
            echo json_encode(['error' => 'Sorry, your file is too large']);
            exit();
        }
    } else {
        echo json_encode(['error' => 'File is not an image']);
        exit();
    }
}

// Update data di database
$sql = "UPDATE pengguna SET nama = ?, bio = ?, tanggal_lahir = ?, hobi = ?, status = ?";
if ($profile_picture) {
    $sql .= ", profile_picture = ?";
}
$sql .= " WHERE id = ?";
$stmt = $conn->prepare($sql);

if ($profile_picture) {
    $stmt->bind_param("ssssssi", $newNama, $newBio, $newTanggalLahir, $newHobi, $newStatus, $profile_picture, $user_id);
} else {
    $stmt->bind_param("sssssi", $newNama, $newBio, $newTanggalLahir, $newHobi, $newStatus, $user_id);
}

if (!$stmt->execute()) {
    echo json_encode(['error' => 'Error updating profile']);
    exit();
}

// Ambil data terbaru untuk dikirim kembali sebagai respons
$sql = "SELECT nama, profile_picture, bio, tanggal_lahir, hobi, status FROM pengguna WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

// Tutup koneksi database
$conn->close();

// Kirim respons JSON kembali ke AJAX
echo json_encode($row);
?>
