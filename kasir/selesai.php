<?php
include("../config/koneksi.php");
$qq = mysqli_query($konek, "SELECT * FROM keranjang");

$query2 = mysqli_query($konek, "SELECT * FROM transaksi_jual ORDER BY no_faktur DESC");
$data2 = mysqli_fetch_assoc($query2);
$jml = mysqli_num_rows($query2);
if ($jml == 0) {
  $no_faktur = 'FK001';
} else {
  $subid = substr($data2['no_faktur'], 3);
  if ($subid > 0 && $subid <= 8) {
    $sub = $subid + 1;
    $no_faktur = 'FK00' . $sub;
  } elseif ($subid >= 9 && $subid <= 100) {
    $sub = $subid + 1;
    $no_faktur = 'FK0' . $sub;
  } elseif ($subid >= 99 && $subid <= 1000) {
    $sub = $subid + 1;
    $no_faktur = 'FK' . $sub;
  }
}

$q3 = mysqli_query($konek, "SELECT * FROM detail_jual ORDER BY sub_faktur DESC");
$dt2 = mysqli_fetch_assoc($q3);
$hml2 = mysqli_num_rows($q3);
if ($hml2 == 0) {
  $sub_faktur = 'SF001';
} else {
  $suid = substr($dt2['sub_faktur'], 3);
  if ($suid > 0 && $suid <= 8) {
    $su = $suid + 1;
    $sub_faktur = 'SF00' . $su;
  } elseif ($suid >= 9 && $suid <= 100) {
    $su = $suid + 1;
    $sub_faktur = 'SF0' . $su;
  } elseif ($suid >= 99 && $suid <= 1000) {
    $su = $suid + 1;
    $sub_faktur = 'SF' . $su;
  }
}

while ($dd = mysqli_fetch_array($qq)) {
  mysqli_query($konek, "INSERT INTO transaksi_jual VALUES ('','$no_faktur','$dd[kd_produk]','$dd[tgl_jual]','$dd[total_jual]')");
  $id = mysqli_insert_id($konek);
  mysqli_query($konek, "INSERT INTO detail_jual VALUES ('$sub_faktur','$id','$dd[kd_exp]','$dd[jml_jual]')");
  mysqli_query($konek, "DELETE FROM keranjang WHERE idkeranjang='$dd[idkeranjang]'");
}

echo "<script>window.location.href='index.php?page=datajual'</script>";
