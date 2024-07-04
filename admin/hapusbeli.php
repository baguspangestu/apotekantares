<?php

include("../config/koneksi.php");
$kd = $_GET['id'];

$resultJumlah = mysqli_query($konek, "SELECT kd_produk, jumlah FROM detail_transaksi_beli WHERE kd_transaksi = '$kd'");
$rowJumlah = mysqli_fetch_assoc($resultJumlah);
$kd_produk = $rowJumlah['kd_produk'];
$jumlah = $rowJumlah['jumlah'];

$resultStok = mysqli_query($konek, "SELECT stok FROM detail_produk WHERE kd_produk = '$kd_produk'");
$rowStok = mysqli_fetch_assoc($resultStok);
$stok = $rowStok['stok'];

$stok_baru = $stok - $jumlah;

mysqli_query($konek, "DELETE FROM transaksi_beli WHERE kd = '$kd'");
mysqli_query($konek, "DELETE FROM detail_transaksi_beli WHERE kd_transaksi = '$kd'");
mysqli_query($konek, "UPDATE detail_produk SET stok = '$stok_baru' WHERE kd_produk = '$kd_produk'");

echo mysqli_error($konek);
echo '<script>alert("Data Beli Berhasil Dihapus!");</script>';
echo '<script>window.location.href="index.php?page=databeli";</script>';