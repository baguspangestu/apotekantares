<?php

include("../config/koneksi.php");
$kd = $_GET['id'];

mysqli_query($konek, "DELETE FROM suplier WHERE kd = '$kd'");

echo mysqli_error($konek);
echo '<script>alert("Data Suplier Berhasil Dihapus!");</script>';
echo '<script>window.location.href="index.php?page=datasuplier";</script>';