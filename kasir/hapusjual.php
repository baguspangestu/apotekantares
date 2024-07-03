<?php
include("../config/koneksi.php");

$kd = $_GET['kd'];

$konek->begin_transaction();

try {
  $queryUpdateStok = "UPDATE detail_produk SET stok = ? WHERE kd_produk = ?";

  $detailTransaksiQuery =  "SELECT b.kd, c.stok, a.jumlah
                            FROM detail_transaksi_jual a 
                            LEFT JOIN produk b ON a.kd_produk = b.kd 
                            LEFT JOIN detail_produk c ON b.kd = c.kd_produk
                            WHERE a.kd_transaksi = '$kd'";

  $detailTransaksiResult = mysqli_query($konek, $detailTransaksiQuery);

  while ($data = mysqli_fetch_assoc($detailTransaksiResult)) {
    $kdProduk = $data['kd'];
    $stokBaru = $data['stok'] + $data['jumlah'];

    $stmtUpdateStok = $konek->prepare($queryUpdateStok);
    $stmtUpdateStok->bind_param("is", $stokBaru, $kdProduk);
    $stmtUpdateStok->execute();
    $stmtUpdateStok->close();
  }

  $deleteTransaksiQuery = "DELETE FROM transaksi_jual WHERE kd = ?";
  $stmtDeleteTransaksi = $konek->prepare($deleteTransaksiQuery);
  $stmtDeleteTransaksi->bind_param("s", $kd);
  $stmtDeleteTransaksi->execute();
  $stmtDeleteTransaksi->close();

  $konek->commit();

  echo '<script>alert("Berhasil Menghapus Transaksi!");</script>';
} catch (Exception $e) {
  echo '<script>alert("Gagal Menghapus Transaksi, silakan coba lagi!");</script>';
}

echo '<script>window.location.href="../kasir/index.php?page=datajual"</script>';

$konek->close();