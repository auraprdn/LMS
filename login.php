<?php
// Memeriksa apakah sesi sudah dimulai
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Koneksi ke database
$servername = "localhost"; // Ganti sesuai dengan server Anda
$username = "root"; // Ganti sesuai dengan username database Anda
$password = ""; // Ganti sesuai dengan password database Anda
$dbname = "lms_db"; // Ganti sesuai dengan nama database Anda

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data dari form login
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Melakukan query ke database untuk memeriksa kecocokan data
    $sql = "SELECT * FROM pengguna WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Login berhasil
        // Ambil data pengguna
        $user = $result->fetch_assoc();
        // Simpan ID pengguna dan nama pengguna dalam sesi
        $_SESSION["user_id"] = $user['id'];
        $_SESSION["username"] = $user['username']; // Atur username di sini
        // Redirect ke halaman index.php
        header("Location: index.php");
        exit();
    } else {
        // Login gagal, tampilkan pesan kesalahan atau alihkan ke halaman login dengan pesan error
        header("Location: login.html?error=invalid_credentials");
        exit();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
    <meta charset="utf-8">
    <title>Bimbel-Ku.com</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Online Learning Management System" name="description">
    <meta content="LMS, Online Learning, Courses, Education, Bimbel" name="keywords">
    

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">
<style>
    body {
        background-image: url('login-background.jpg');
        background-size: cover;
        background-position: center;
        height: 100vh;
        margin: 0;
        padding: 0;
    }
</style>

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Nunito:wght@600;700;800&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Nunito:wght@600;700;800&display=swap" rel="stylesheet">


    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <!-- Navbar Start -->
    <nav class="navbar navbar-expand-lg bg-white navbar-light shadow sticky-top p-0">
        <a href="index.php" class="navbar-brand d-flex align-items-center px-4 px-lg-5">
            <h1 class="m-0 text-primary"><i class=""></i>BIMBEL-KU</h1>
        </a>
        <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto p-4 p-lg-0">
                <?php
                // Memeriksa apakah pengguna sudah login
                if (isset($_SESSION['username'])) {
                    // Mengambil nama pengguna dari sesi
                    $username = $_SESSION['username'];
                    // Menampilkan tautan ke profil.php dan nama pengguna dengan ikon pengguna
                    echo '<a href="profile.php" class="nav-item nav-link"><i class="fas fa-user"></i> ' . $username . '</a>';
                } else {
                    // Menampilkan tombol login jika pengguna belum login
                    echo '<a href="login.php" class="nav-item nav-link">Login</a>';
                }
                ?>
            </div>
        </div>
    </nav>
    <!-- Navbar End -->
    <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card mt-5 login-background">
                <div class="card-body">
                    <h1 class="card-title text-center">Login</h1>
                    <form action="login.php" method="post">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" name="username" class="form-control" placeholder="Username" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <div class="input-group">
                                <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                                <div class="input-group-append">
                                    <button type="button" id="togglePassword" class="btn btn-outline-secondary">
                                        <i class="fa fa-eye" aria-hidden="true"></i> <!-- Icon to show password -->
                                    </button>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-info btn-block">Login</button>
                    </form>
                    <div class="social-icons text-center mt-3">
                        <a href="#" class="icon"><i class="fab fa-google-plus-g"></i></a>
                        <a href="#" class="icon"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="icon"><i class="fab fa-github"></i></a>
                        <a href="#" class="icon"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                    <div class="text-center mt-3">
                        <h5>Don't have an account? <a href="register.html">Register here</a></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    document.getElementById("togglePassword").addEventListener("click", function() {
        var passwordInput = document.getElementById('password');
        var eyeIcon = document.querySelector('#togglePassword i');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        }
    });
</script>
</body>
</html>