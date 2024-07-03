<?php
include("../config/koneksi.php");
$qq = mysqli_query($konek, "SELECT * FROM keranjang_beli");

$query2 = mysqli_query($konek, "SELECT * FROM transaksi_beli ORDER BY no_transaksi DESC");
$data2 = mysqli_fetch_assoc($query2);
$jml = mysqli_num_rows($query2);
if ($jml == 0) {
  $no_transaksi = 'TB001';
} else {
  $subid = substr($data2['no_transaksi'], 3);
  if ($subid > 0 && $subid <= 8) {
    $sub = $subid + 1;
    $no_transaksi = 'TB00' . $sub;
  } elseif ($subid >= 9 && $subid <= 100) {
    $sub = $subid + 1;
    $no_transaksi = 'TB0' . $sub;
  } elseif ($subid >= 99 && $subid <= 1000) {
    $sub = $subid + 1;
    $no_transaksi = 'TB' . $sub;
  }
}

$qqq = mysqli_query($konek, "SELECT * FROM detail_transaksi ORDER BY sub_transaksi DESC");
$ddd = mysqli_fetch_assoc($qqq);
$jmlh = mysqli_num_rows($qqq);
if ($jmlh == 0) {
  $sub_transaksi = 'SB001';
} else {
  $suid = substr($ddd['sub_transaksi'], 3);
  if ($suid > 0 && $suid <= 8) {
    $su = $suid + 1;
    $sub_transaksi = 'SB00' . $su;
  } elseif ($suid >= 9 && $suid <= 100) {
    $su = $suid + 1;
    $sub_transaksi = 'SB0' . $su;
  } elseif ($suid >= 99 && $suid <= 1000) {
    $su = $suid + 1;
    $sub_transaksi = 'SB' . $su;
  }
}

while ($dd = mysqli_fetch_array($qq)) {
  mysqli_query($konek, "INSERT INTO transaksi_beli VALUES ('','$no_transaksi','$dd[kd_suplier]','$dd[tgl_transaksi]','$dd[total_transaksi]')");
  $id = mysqli_insert_id($konek);
  mysqli_query($konek, "INSERT INTO detail_transaksi VALUES ('$sub_transaksi','$id','$dd[kd_produk]','$dd[jml_beli]')");
  mysqli_query($konek, "DELETE FROM keranjang_beli WHERE idbeli='$dd[idbeli]'");
}
echo "<script>window.location.href='index.php?page=databeli'</script>";
