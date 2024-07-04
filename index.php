<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta name="description" content="" />
  <meta name="author" content="" />

  <title>SISTEM INFORMASI PENJUALAN APOTEK ANTARES PRINGSEWU</title>

  <!-- Custom fonts for this template-->
  <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css" />
  <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet" />

  <!-- Custom styles for this template-->
  <link href="assets/css/sb-admin-2.min.css" rel="stylesheet" />
</head>

<body style="background-color:#0099FF">
  <div class="container py-5">
    <!-- Outer Row -->
    <div class="row justify-content-center">
      <div class="col-xl-5 col-lg-5 col-md-9">
        <div class="text-white text-center font-weight-bold" style="font-size: 60px;"><i
            class="fas fa-fw fa-hospital"></i></div>
        <h3 class="text-white text-center font-weight-bold">SISTEM INFORMASI PENJUALAN APOTEK ANTARES PRINGSEWU</h3>
        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
              <div class="col-lg-12">
                <div class="p-5">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">Login Account</h1>
                  </div>
                  <form class="user" action="" method="post">
                    <div class="form-group">
                      <input type="text" class="form-control form-control-user" name="username" placeholder="Username"
                        autocomplete="off" required />
                    </div>
                    <div class="form-group">
                      <input type="password" class="form-control form-control-user" name="password"
                        placeholder="Password" autocomplete="off" required />
                    </div>
                    <button type="submit" class="btn btn-primary btn-user btn-block" name="submit"><i
                        class="fas fa-fw fa-sign-in-alt mr-1"></i> Masuk</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="assets/vendor/jquery/jquery.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="assets/vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="assets/js/sb-admin-2.min.js"></script>
</body>

</html>

<?php
session_start();
include("config/koneksi.php");

if (isset($_POST['submit'])) {
  $query = mysqli_query($konek, "SELECT * FROM user WHERE username = '$_POST[username]' AND password = '$_POST[password]'");
  $row = mysqli_num_rows($query);
  if ($row > 0) {
    $data = mysqli_fetch_assoc($query);

    $_SESSION['id'] = $data["id"];
    $_SESSION['nama'] = $data["nama"];
    $_SESSION['username'] = $data["username"];

    if ($data['level'] == "admin") {
      $_SESSION['level'] = "admin";
      echo '<script>window.location.href="admin/index.php?page=home";</script>';
    } else if ($data['level'] == "kasir") {
      $_SESSION['level'] = "kasir";
      echo '<script>window.location.href="kasir/index.php?page=home";</script>';
    }
  } else {
    echo '<script>alert("Username atau password salah!");</script>';
    echo '<script>window.location.href="index.php";</script>';
  }
}
?>