<?php
session_start();

// Periksa apakah pengguna telah login
if (!isset($_SESSION['user_id'])) {
    // Jika belum login, redirect ke halaman login
    header("Location: login.html");
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

// Query untuk mengambil informasi pengguna dari database
$sql = "SELECT nama, profile_picture, bio, tanggal_lahir, hobi, status FROM pengguna WHERE id = $user_id";
$result = $conn->query($sql);

// Periksa apakah query berhasil
if ($result->num_rows > 0) {
    // Jika berhasil, ambil baris data
    $row = $result->fetch_assoc();
    $nama = $row['nama'];
    $profile_picture = $row['profile_picture'];
    $bio = $row['bio'];
    $tanggal_lahir = $row['tanggal_lahir'];
    $hobi = $row['hobi'];
    $status = $row['status'];
} else {
    // Jika tidak ada hasil, beri nilai default pada variabel
    $nama = "Pengguna";
    $profile_picture = "img2/default-profile.png";
    $bio = "Belum ada biografi.";
    $tanggal_lahir = "Belum diatur";
    $hobi = "Belum diatur";
    $status = "Belum diatur";
}

// Tutup koneksi database
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Bimbel-Ku.com</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Nunito:wght@600;700;800&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <!-- Spinner Start -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <!-- Spinner End -->

    <!-- Navbar Start -->
    <nav class="navbar navbar-expand-lg bg-white navbar-light shadow sticky-top p-0">
        <a href="index.php" class="navbar-brand d-flex align-items-center px-4 px-lg-5">
        <h1 class="m-0 text-primary"><i class="fa fa-arrow-left text-primary me-2"></i></>BIMBEL-KU</h1>
        </a>
        <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto p-4 p-lg-0">
                <a href="index.php" class="nav-item nav-link">Beranda</a>
                <a href="kuis.php" class="nav-item nav-link">Soal</a>
                <a href="contact.php" class="nav-item nav-link">Kontak</a>
                <?php
                // Memeriksa apakah pengguna sudah login
                if (isset($_SESSION['username'])) {
                    // Mengambil nama pengguna dari sesi
                    $username = $_SESSION['username'];
                    // Menampilkan tautan ke profil.php dengan ikon pengguna
                    echo '<a href="profile.php" class="nav-item nav-link active"><i class="fas fa-user"></i> ' . $username . '</a>';
                } else {
                    // Menampilkan tombol login jika pengguna belum login
                    echo '<a href="login.html" class="nav-item nav-link">Login</a>';
                }
                ?>
            </div>
        </div>
    </nav>
    <!-- Navbar End -->

    <!-- Header Start -->
    <div class="container-fluid bg-primary py-5 mb-5 page-header">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-10 text-center">
                    <h1 class="display-3 text-white animated slideInDown">Profile</h1>
                    <nav aria-label="breadcrumb">
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- Header End -->

    <!-- Profile Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 text-center">
                    <img id="profile-img" class="img-fluid rounded-circle mb-4" src="<?php echo $profile_picture; ?>" alt="User Photo" style="width: 200px; height: 200px;">
                    <h2 id="username"><?php echo $nama; ?></h2>
                    <p id="bio" class="mb-4"><?php echo $bio; ?></p>
                    <p id="tanggal_lahir" class="mb-2"><strong>Tanggal Lahir: </strong><?php echo $tanggal_lahir; ?></p>
                    <p id="hobi" class="mb-2"><strong>Hobi: </strong><?php echo $hobi; ?></p>
                    <p id="status" class="mb-4"><strong>Status: </strong><?php echo $status; ?></p>
                    <button id="edit-profile" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">Edit Profile</button>
                    <a href="logout.php" class="btn btn-danger">Logout</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Profile End -->

    <!-- Edit Profile Modal -->
    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editProfileForm" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="newNama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="newNama" name="newNama">
                        </div>

                        <div class="mb-3">
                            <label for="newProfilePicture" class="form-label">Foto Profil</label>
                            <input type="file" class="form-control" id="newProfilePicture" name="newProfilePicture" onchange="previewImage(this)">
                            <img id="preview" src="#" alt="Preview" style="display: none; max-width: 200px; margin-top: 10px;">
                        </div>

                        <div class="mb-3">
                            <label for="newBio" class="form-label">Bio</label>
                            <textarea class="form-control" id="newBio" name="newBio" rows="3"></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="newTanggalLahir" class="form-label">Tanggal Lahir</label>
                            <input type="date" class="form-control" id="newTanggalLahir" name="newTanggalLahir">
                        </div>

                        <div class="mb-3">
                            <label for="newHobi" class="form-label">Hobi</label>
                            <input type="text" class="form-control" id="newHobi" name="newHobi">
                        </div>
                        
                        <div class="mb-3">
                            <label for="newStatus" class="form-label">Status</label>
                            <div>
                                <input type="radio" id="mahasiswa" name="newStatus" value="Mahasiswa" <?php if($status == 'Mahasiswa') echo 'checked'; ?>>
                                <label for="mahasiswa">Mahasiswa</label>
                            </div>
                            <div>
                                <input type="radio" id="pelajar" name="newStatus" value="Pelajar" <?php if($status == 'Pelajar') echo 'checked'; ?>>
                                <label for="pelajar">Pelajar</label>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary" id="saveChangesBtn">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit Profile Modal End -->

    <script>
        function previewImage(input) {
            var preview = document.getElementById('preview');
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    preview.style.display = 'block';
                    preview.src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        document.getElementById('editProfileForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent default form submission
            
            // Create a new FormData object to hold the form data
            var formData = new FormData(this);

            // Send the form data to the server using AJAX
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'update_profile.php', true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    // Assuming the changes are successfully saved, update the UI accordingly
                    var response = JSON.parse(xhr.responseText);
                    document.getElementById('username').innerText = response.username;
                    document.getElementById('bio').innerText = response.bio;
                    document.getElementById('profile-img').src = response.profile_picture;
                    document.getElementById('tanggal_lahir').innerText = response.tanggal_lahir;
                    document.getElementById('hobi').innerText = response.hobi;
                    document.getElementById('status').innerText = response.status;

                    // Close the modal
                    $('#editProfileModal').modal('hide'); 
                } else {
                    // Handle error
                    alert('Failed to save changes. Please try again.');
                }
            };
            xhr.send(formData);
        });
    </script>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>
</html>
