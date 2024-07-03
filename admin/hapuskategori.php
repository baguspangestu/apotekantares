<?php

include("../config/koneksi.php");
$kd = $_GET['id'];

mysqli_query($konek, "DELETE FROM kategori WHERE kd = '$kd'");

echo mysqli_error($konek);
echo '<script>alert("Data Kategori Berhasil Dihapus!");</script>';
echo '<script>window.location.href="index.php?page=datakategori";</script>';