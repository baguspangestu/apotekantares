<?php
include("../config/koneksi.php");
$qqq = mysqli_query($konek, "DELETE FROM detail_suplier WHERE kd_suplier='$_GET[id]' AND kd_produk='$_GET[kd_produk]'");
if ($qqq) {
	echo mysqli_error($konek);
	echo '<script>alert("Berhasil Menghapus Produk untuk suplier ini!");</script>';
	echo "<script>window.location.href='index.php?page=detailsuplier&id=$_GET[id]'</script>";
}