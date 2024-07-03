<?php

include("../config/koneksi.php");
$kd = $_GET['id'];

mysqli_query($konek, "DELETE FROM produk WHERE kd = '$kd'");
mysqli_query($konek, "DELETE FROM detail_produk WHERE kd_produk = '$kd'");

echo mysqli_error($konek);
echo '<script>alert("Data Produk Berhasil Dihapus!");</script>';
echo '<script>window.location.href="index.php?page=dataproduk";</script>';
