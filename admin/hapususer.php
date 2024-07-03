<?php

include("../config/koneksi.php");
$id = $_GET['id'];

mysqli_query($konek, "DELETE FROM user WHERE id = '$id'");

echo mysqli_error($konek);
echo '<script>alert("Data User Berhasil Dihapus!");</script>';
echo '<script>window.location.href="index.php?page=datauser";</script>';