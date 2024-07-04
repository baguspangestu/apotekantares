<?php
include("../config/koneksi.php");
$qq = mysqli_query($konek, "SELECT * FROM keranjang_beli");

$query2 = mysqli_query($konek, "SELECT * FROM transaksi_beli ORDER BY kd DESC");
$data2 = mysqli_fetch_assoc($query2);
$jml = mysqli_num_rows($query2);
if ($jml == 0) {
  $kd = 'TR001';
} else {
  $subid = substr($data2['kd'], 3);
  if ($subid > 0 && $subid <= 8) {
    $sub = $subid + 1;
    $kd = 'TR00' . $sub;
  } elseif ($subid >= 9 && $subid <= 100) {
    $sub = $subid + 1;
    $kd = 'TR0' . $sub;
  } elseif ($subid >= 99 && $subid <= 1000) {
    $sub = $subid + 1;
    $kd = 'TR' . $sub;
  }
}

$tanggal = date('Y-m-d');

while ($dd = mysqli_fetch_array($qq)) {
  mysqli_begin_transaction($konek);

  try {
    mysqli_query($konek, "INSERT INTO transaksi_beli VALUES ('$kd', '{$dd['kd_suplier']}', '$tanggal', '{$dd['total']}')");
    mysqli_query($konek, "INSERT INTO detail_transaksi_beli VALUES ('', '$kd', '{$dd['kd_produk']}', '{$dd['jumlah']}')");

    $result = mysqli_query($konek, "SELECT stok FROM detail_produk WHERE kd_produk = '{$dd['kd_produk']}'");
    $row = mysqli_fetch_assoc($result);
    $stok_sekarang = $row['stok'];
    $stok_baru = $stok_sekarang + $dd['jumlah'];

    mysqli_query($konek, "UPDATE detail_produk SET stok = '$stok_baru' WHERE kd_produk = '{$dd['kd_produk']}'");
    mysqli_query($konek, "DELETE FROM keranjang_beli WHERE kd = '{$dd['kd']}'");

    mysqli_commit($konek);
  } catch (Exception $e) {
    mysqli_rollback($konek);
    echo "Terjadi kesalahan: " . $e->getMessage();
  }
}

echo "<script>window.location.href='index.php?page=databeli'</script>";