<?php

include ("../config/koneksi.php");
$no_transaksi= $_GET['id'];
$sub_transaksi = $_GET['sub'];

mysqli_query($konek,"DELETE FROM transaksi_beli WHERE no_transaksi = '$no_transaksi'");
mysqli_query($konek,"DELETE FROM detail_transaksi WHERE sub_transaksi = '$sub_transaksi'");

echo mysqli_error($konek);
echo '<script>alert("Data Beli Berhasil Dihapus!");</script>';
echo '<script>window.location.href="index.php?page=databeli";</script>';

?>