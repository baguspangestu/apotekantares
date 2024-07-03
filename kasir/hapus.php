<?php
include ("../config/koneksi.php");
$qqq=mysqli_query($konek,"DELETE FROM keranjang WHERE idkeranjang='$_GET[id]'");
if($qqq){
	echo "<script>window.location.href='index.php?page=datajual'</script>";
}