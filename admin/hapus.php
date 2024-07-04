<?php
include("../config/koneksi.php");
$qqq = mysqli_query($konek, "DELETE FROM keranjang_beli WHERE kd='$_GET[id]'");
if ($qqq) {
	echo "<script>window.location.href='index.php?page=databeli'</script>";
}