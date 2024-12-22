<?php
// Koneksi ke database
$servername = "localhost"; // Ganti sesuai dengan server Anda
$username_db = "root"; // Ganti sesuai dengan username database Anda
$password_db = ""; // Ganti sesuai dengan password database Anda
$dbname = "lms_db"; // Ganti sesuai dengan nama database Anda

// Membuat koneksi
$conn = new mysqli($servername, $username_db, $password_db, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Memeriksa apakah data POST sudah diset
if (isset($_POST['username']) && isset($_POST['password'])) {
    // Ambil data dari form register
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Periksa apakah username sudah ada
    $stmt_check = $conn->prepare("SELECT username FROM pengguna WHERE username = ?");
    $stmt_check->bind_param("s", $username);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        // Jika username sudah ada
        echo '<!DOCTYPE html>
              <html lang="en">
              <head>
                  <meta charset="UTF-8">
                  <meta name="viewport" content="width=device-width, initial-scale=1.0">
                  <title>Notification</title>
                  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
                  <style>
                      body {
                          background-image: url("login-background.jpg");
                          background-size: cover;
                          height: 100vh;
                          display: flex;
                          justify-content: center;
                          align-items: center;
                      }
                      .alert {
                          max-width: 400px;
                          margin: auto;
                          text-align: center;
                      }
                  </style>
              </head>
              <body>
                  <div class="alert alert-danger" role="alert">
                      <h4 class="alert-heading">Pendaftaran Gagal!</h4>
                      <p>Username sudah terdaftar. Silakan gunakan username lain.</p>
                      <hr>
                      <p class="mb-0">Kembali ke <a href="register.html" class="alert-link">halaman pendaftaran</a></p>
                  </div>
              </body>
              </html>';
    } else {
        // Prepared statement untuk keamanan
        $stmt = $conn->prepare("INSERT INTO pengguna (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $password);

        if ($stmt->execute() === TRUE) {
            // Redirect to login page after successful registration
            echo '<!DOCTYPE html>
                  <html lang="en">
                  <head>
                      <meta charset="UTF-8">
                      <meta name="viewport" content="width=device-width, initial-scale=1.0">
                      <title>Notification</title>
                      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
                      <style>
                          body {
                              background-image: url("login-background.jpg");
                              background-size: cover;
                              height: 100vh;
                              display: flex;
                              justify-content: center;
                              align-items: center;
                          }
                          .alert {
                              max-width: 400px;
                              margin: auto;
                              text-align: center;
                          }
                      </style>
                  </head>
                  <body>
                      <div class="alert alert-info" role="alert">
                          <h4 class="alert-heading">Pendaftaran Berhasil!</h4>
                          <p>Selamat! Pendaftaran Anda berhasil.</p>
                          <hr>
                          <p class="mb-0">Anda akan dialihkan ke <a href="login.php" class="alert-link">halaman login</a></p>
                      </div>
                  </body>
                  </html>';
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }

    $stmt_check->close();
}

$conn->close();
?>
