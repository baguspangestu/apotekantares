<?php
include("../config/koneksi.php");
function formatRupiah($n)
{
  return "Rp " . number_format($n, 0, ',', '.');
}

$kd = $_GET['kd'];

$query = "SELECT a.kd, a.tanggal, b.nama as nama_kasir, a.pembeli, a.subtotal, a.diskon, a.tunai FROM transaksi_jual a LEFT JOIN user b ON a.id_kasir = b.id WHERE kd='$kd'";
$result = mysqli_query($konek, $query);
$data = mysqli_fetch_assoc($result);

$subTotal = $data['subtotal'];
$diskon = ($data['diskon'] / 100) * $subTotal;
$total = $subTotal - $diskon;
?>

<body onload="window.print();">
  <style>
  .bt {
    border-top: 1px dashed #000;
    padding-top: 5px;
  }

  .bb {
    border-bottom: 1px dashed #000;
    padding-bottom: 5px;
  }
  </style>

  <table width="80%" celpadding="8" align="center">
    <tr align="center">
      <th colspan="4">APOTEK ANTARES PRINGSEWU</th>
    </tr>
    <tr align="center">
      <td colspan="4" class="bb">
        Jl. Pringadi No.128, RT.001, Pringsewu Utara, Kec. Pringsewu, Kabupaten Pringsewu, Lampung 35373
      </td>
    </tr>
    <tr>
      <td colspan="4">
        <div style="display: flex; justify-content: space-between;">
          <div>Nomor Transaksi: <?php echo $data['kd']; ?></div>
          <div>Kasir: <?php echo $data['nama_kasir']; ?></div>
        </div>
      </td>
    </tr>
    <tr>
      <td colspan="4" class="bb">
        <div style="display: flex; justify-content: space-between;">
          <div>Tanggal: <?php echo date('d-m-Y', strtotime($data['tanggal'])); ?></div>
          <div>Pembeli: <?php echo $data['pembeli'] ?></div>
        </div>
      </td>
    </tr>
    <tr>
      <td class="bb">Nama Produk</td>
      <td class="bb" align="right">Jumlah</td>
      <td class="bb" align="right">Harga</td>
      <td class="bb" align="right">Total</td>
    </tr>
    <?php
    $query = "SELECT b.nama, a.jumlah, a.harga 
              FROM detail_transaksi_jual a 
              LEFT JOIN produk b ON a.kd_produk = b.kd 
              WHERE a.kd_transaksi = '" . $data['kd'] . "'";

    $result = mysqli_query($konek, $query);

    while ($d = mysqli_fetch_assoc($result)) {
    ?>
    <tr>
      <td><?php echo $d['nama']; ?></td>
      <td align="right"><?php echo $d['jumlah']; ?></td>
      <td align="right"><?php echo formatRupiah($d['harga']); ?></td>
      <td align="right"><?php echo formatRupiah($d['harga'] * $d['jumlah']); ?></td>
    </tr>
    <?php
    }
    ?>
    <tr>
      <td colspan="3" align="right">HARGA JUAL :</td>
      <td class="bt bb" align="right"><?php echo formatRupiah($data['subtotal']); ?></td>
    </tr>
    <tr>
      <td colspan="3" align="right">DISKON :</td>
      <td align="right"><?php echo $data['diskon']; ?>%</td>
    </tr>
    <tr>
      <td colspan="3" align="right">TOTAL :</td>
      <td align="right"><?php echo formatRupiah($total); ?></td>
    </tr>
    <tr>
      <td colspan="3" align="right">TUNAI :</td>
      <td align="right"><?php echo formatRupiah($data['tunai']); ?></td>
    </tr>
    <tr>
      <td colspan="3" align="right">KEMBALI :</td>
      <td align="right"><?php echo formatRupiah($data['tunai'] - $total); ?></td>
    </tr>
    <?php if ($diskon > 0) { ?>
    <tr>
      <td colspan="3" align="right">ANDA HEMAT :</td>
      <td align="right"><?php echo formatRupiah($diskon); ?></td>
    </tr>
    <?php } ?>
    <tr align="center">
      <td class="bt bb" colspan="4">
        LAYANAN KONSUMEN SMS/WA: 081274171090<br>
        BELANJA PRODUK KESEHATAN HANYA DI APOTEK ANTARES
      </td>
    </tr>
  </table>
</body>