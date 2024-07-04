<?php
include("../config/koneksi.php");
session_start();
if ($_SESSION['level'] != 'kasir') {
  echo '<script>alert("Anda Harus Login Sebagai Kasir!");</script>';
  echo '<script>window.location.href="../index.php";</script>';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Aplikasi Manajemen Pengelolaan Transaksi Produk Apotek</title>

  <!-- Custom fonts for this template-->
  <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">

  <!-- Custom styles for this page -->
  <link href="../assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

  <style>
  .nav-bg {
    background-color: #CCCCCC;
    color: #000000;
  }
  </style>
</head>

<body id="page-top">
  <!-- Topbar -->
  <nav class="navbar navbar-expand navbar-light topbar static-top shadow" style="background-color:#085394;">
    <div class="container-fluid">

      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="?page=home">
        <div class="sidebar-brand-icon rotate-n-15">
          <i class="fas fa-briefcase-medical fa-2x text-white"></i>
        </div>
        <div class="sidebar-brand-text mx-3 text-white text-lg font-weight-bold">ANTARES</div>
      </a>

      <!-- Sidebar Toggle (Topbar) -->
      <button id="sidebarToggleTop" class="btn text-primary d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
      </button>

      <!-- Topbar Navbar -->
      <ul class="navbar-nav ml-auto">

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
          <?php
          $queryStok = "SELECT COUNT(*) AS total FROM detail_produk WHERE stok <= 1";
          $queryExp = "SELECT COUNT(*) AS total FROM detail_produk WHERE tgl_exp <= DATE_ADD(CURDATE(), INTERVAL 7 DAY)";

          $resultStok = mysqli_query($konek, $queryStok);
          if (!$resultStok) {
            die("Query stok gagal: " . mysqli_error($konek));
          }

          $resultExp = mysqli_query($konek, $queryExp);
          if (!$resultExp) {
            die("Query tanggal kedaluwarsa gagal: " . mysqli_error($konek));
          }

          $dataStok = mysqli_fetch_assoc($resultStok);
          $totalStok = $dataStok['total'];

          $dataExp = mysqli_fetch_assoc($resultExp);
          $totalExp = $dataExp['total'];

          $total = $totalStok + $totalExp;
          ?>
          <a class="nav-link dropdown-toggle" href="#" id="notifDropdown" role="button" data-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-bell fa-2x"></i>
            <?php if ($total > 0) { ?>
            <span class="badge badge-pill badge-danger"><?php echo $total; ?></span>
            <?php } ?>
          </a>
          <div class="dropdown-menu" aria-labelledby="notifDropdown">
            <a class="dropdown-item" href="?page=dataproduk"><?php echo $totalStok; ?> Hampir Habis.</a>
            <a class="dropdown-item" href="?page=dataproduk"><?php echo $totalExp; ?> Hampir Expired.</a>
          </div>
        </li>
        <li class="nav-item dropdown no-arrow">
          <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">
            <span class="text-uppercase mr-2 d-none d-lg-inline text-gray-600 small">
              <?php echo $_SESSION['nama']; ?>
            </span>
            <img class="img-profile rounded-circle" src="../assets/img/user.png">
          </a>
          <!-- Dropdown - User Information -->
          <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
              <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
              Keluar
            </a>
          </div>
        </li>

      </ul>
    </div>
  </nav>

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav sidebar sidebar-dark accordion" style="background-color:#349beb;" id="accordionSidebar">

      <!-- Nav Item - Dashboard -->
      <br>
      <li class="nav-item nav-bg <?php $p = $_GET['page'];
                                  if ($p == 'home') {
                                    echo "active";
                                  } ?>">
        <a class="nav-link  text-dark" href="?page=home">
          <i class="fas fa-fw fa-home text-dark"></i>
          <span>Dashboard</span></a>
      </li>
      <br>
      <li class="nav-item nav-bg <?php $p = $_GET['page'];
                                  if (($p == 'dataproduk')) echo "active"; ?>">
        <a class="nav-link  text-dark" href="?page=dataproduk">
          <i class="fas fa-boxes text-dark"></i>
          <span>Data Produk</span></a>
      </li>
      <br>
      <li class="nav-item nav-bg <?php $p = $_GET['page'];
                                  if (($p == 'datajual') || ($p == 'inputjual') || ($p == 'editjual')) {
                                    echo "active";
                                  } ?>">
        <a class="nav-link  text-dark" href="?page=datajual">
          <i class="fas fa-fw fa-dolly-flatbed text-dark"></i>
          <span>Transaksi Penjualan</span></a>
      </li>
      <br>
      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>

    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">
        <!-- End of Topbar -->

        <div class="container-fluid mt-4">