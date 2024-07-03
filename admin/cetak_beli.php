<?php
include("../config/koneksi.php");
$date = date("d-m-Y");
$no_transaksi = $_GET['id'];
?>

<body onload="window.print();">
  <table width="90%" style="padding-left: 10%;" celpadding="8">
    <tr align="center">
      <th>APOTEK SOURCECODEKU.COM</th>
    </tr>
    <tr align="center">
      <td style="border-bottom:2px solid #333;padding:0 0 5px 0;"><i>Jl. Raya Lubuk Begalung, Kota Padang, Sumatera Barat, Indonesia</i></td>
    </tr>
    <tr align="center">
      <th style="padding:20px 0">NOTA PEMBELIAN OBAT</th>
    </tr>
  </table>


  <?php
  $sqlq = mysqli_query($konek, "SELECT * FROM transaksi_beli a LEFT JOIN detail_transaksi b ON a.id_tr=b.id_tr LEFT JOIN suplier c ON a.kd_suplier=c.kd_suplier LEFT JOIN detail_produk d ON b.kd_produk=d.kd_produk LEFT JOIN produk e ON d.kd_produk=e.kd_produk WHERE a.no_transaksi='$no_transaksi'");
  $dataq = mysqli_fetch_assoc($sqlq);
  ?>
  <div class="container">
    <table width="90%" style="padding-left: 10%;">
      <tr>
        <td>No Transaksi</td>
        <td>:</td>
        <td><?php echo $dataq['no_transaksi']; ?></td>
        <td>Nama Suplier</td>
        <td>:</td>
        <td><?php echo $dataq['nama_suplier']; ?></td>
      </tr>
      <tr>
        <td>Tanggal Transaksi</td>
        <td>:</td>
        <td><?php echo $dataq['tgl_transaksi']; ?></td>
        <td>Alamat Suplier</td>
        <td>:</td>
        <td><?php echo $dataq['alamat']; ?></td>
      </tr>
      <tr>
        <td></td>
        <td></td>
        <td></td>
        <td>No. Telp</td>
        <td>:</td>
        <td><?php echo $dataq['no_tlp']; ?></td>
      </tr>
    </table>
  </div>
  <center>
    <div class="table-responsive">
      <table width="80%" border="1" cellpadding="5">
        <thead>
          <tr align="center">
            <td><b>No</b></td>
            <td><b>Kode Produk</b></td>
            <td><b>Nama Produk</b></td>
            <td><b>Nama Kategori</b></td>
            <td><b>Jumlah</b></td>
            <td><b>Harga Beli</b></td>
          </tr>
        </thead>
        <?php
        $no = 0;
        $total = 0;
        $sqlqw = mysqli_query($konek, "SELECT * FROM transaksi_beli a LEFT JOIN detail_transaksi b ON a.id_tr=b.id_tr LEFT JOIN detail_produk d ON b.kd_produk=d.kd_produk LEFT JOIN produk e ON d.kd_produk=e.kd_produk LEFT JOIN kategori f ON e.kd_kategori=f.kd_kategori WHERE a.no_transaksi='$no_transaksi'");
        while ($dataqw = mysqli_fetch_assoc($sqlqw)) {
          $no++;
        ?>
          <tbody>
            <td align="right"><?php echo $no; ?></td>
            <td><?php echo $dataqw['kd_produk']; ?></td>
            <td><?php echo $dataqw['nama_produk']; ?></td>
            <td><?php echo $dataqw['nama_kategori']; ?></td>
            <td align="right"><?php echo $dataqw['jml_beli']; ?></td>
            <td align="right"><?php echo "Rp " . number_format($dataq['harga_modal'], 0, ',', '.'); ?></td>
            </tr>
          <?php
        }
          ?>
          </tbody>
      </table>
      <table style="padding-top:10px" width="80%" cellpadding="5">
        <tr>
          <td align="right" style="padding-right:60px;">PADANG, <?php echo $date; ?></td>
        </tr>
        <tr>
          <td align="right" style="padding-right:95px;">PIMPINAN</td>
        </tr>
        <tr>
          <td align="right" style="padding-top:50px;">APOTEK SOURCECODEKU.COM</td>
        </tr>
      </table>
    </div>
  </center>
</body>