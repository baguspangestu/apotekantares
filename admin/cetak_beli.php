<?php
include("../config/helpers.php");
include("../config/koneksi.php");

session_start();

$date = date("d-m-Y");
$kd = $_GET['id'];
?>

<body onload="window.print();">
  <style>
    table.table {
      border-collapse: collapse;
    }

    table.table,
    table.table th,
    table.table td {
      border: 1px solid black;
    }
  </style>
  <table width="90%" style="padding-left: 10%;" celpadding="8">
    <tr align="center">
      <th>APOTEK ANTARES PRINGSEWU</th>
    </tr>
    <tr align="center">
      <td style="border-bottom:2px solid #333;padding:0 0 5px 0;"><i>Jl. Pringadi No.128, RT.001, Pringsewu Utara, Kec.
          Pringsewu, Kabupaten Pringsewu, Lampung 35373</i></td>
    </tr>
    <tr align="center">
      <th style="padding:20px 0">NOTA PEMBELIAN PRODUK</th>
    </tr>
  </table>


  <?php
  $sqlq = mysqli_query($konek, "SELECT a.kd, b.nama as suplier, a.tanggal, b.alamat, b.no_tlp, a.total FROM transaksi_beli a LEFT JOIN suplier b ON a.kd_suplier = b.kd WHERE a.kd='$kd'");
  $dataq = mysqli_fetch_assoc($sqlq);
  ?>
  <div class="container">
    <table width="90%" style="padding-left: 10%;">
      <tr>
        <td>Kode Transaksi</td>
        <td>:</td>
        <td><?php echo $dataq['kd']; ?></td>
        <td>Nama Suplier</td>
        <td>:</td>
        <td><?php echo $dataq['suplier']; ?></td>
      </tr>
      <tr>
        <td>Tanggal Transaksi</td>
        <td>:</td>
        <td><?php echo formatTanggal($dataq['tanggal']); ?></td>
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
      <table width="80%" border="1" cellpadding="5" class="table">
        <thead>
          <tr align="center">
            <td><b>No</b></td>
            <td><b>Kode Produk</b></td>
            <td><b>Nama Produk</b></td>
            <td><b>Kategori</b></td>
            <td><b>Harga Beli</b></td>
            <td><b>Jumlah</b></td>
            <td><b>Total</b></td>
          </tr>
        </thead>
        <tbody>
          <?php
          $no = 0;
          $total = 0;
          $query = "SELECT c.kd, c.nama, d.nama as kategori, a.jumlah, a.harga
                  FROM detail_transaksi_beli a
                  LEFT JOIN detail_produk b ON a.kd_produk = b.kd_produk 
                  LEFT JOIN produk c ON a.kd_produk = c.kd
                  LEFT JOIN kategori d ON c.kd_kategori = d.kd
                  WHERE a.kd_transaksi = '$kd' ORDER BY a.id ASC";
          $sqlqw = mysqli_query($konek, $query);
          while ($dataqw = mysqli_fetch_assoc($sqlqw)) {
            $no++;
          ?>
            <tr>
              <td align="center"><?php echo $no; ?></td>
              <td><?php echo $dataqw['kd']; ?></td>
              <td><?php echo $dataqw['nama']; ?></td>
              <td><?php echo $dataqw['kategori']; ?></td>
              <td align="right"><?php echo formatRupiah($dataqw['harga']); ?></td>
              <td align="center"><?php echo $dataqw['jumlah']; ?></td>
              <td align="right">
                <?php echo formatRupiah($dataqw['harga'] * $dataqw['jumlah']); ?>
              </td>
            </tr>
          <?php
          }
          ?>
          <tr>
            <td colspan="6" align="center"><b>Total</b></td>
            <td align="right"><b><?php echo formatRupiah($dataq['total']); ?></b></td>
          </tr>
        </tbody>
      </table>
      <div style="width: 80%;">
        <div style="display: flex; justify-content:space-between; align-items: flex-end">
          <div style="text-align:center">
            <p>ADMIN</p>
            <br />
            <br />
            <p>
              <?php echo $_SESSION['nama']; ?>
            </p>
          </div>
          <div style="text-align:center">
            <p>PRINGSEWU, <?php echo $date; ?><br />PIMPINAN</p>
            <br />
            <br />
            <p>MUHAMMAD ARIF PRADANA</p>
          </div>
        </div>
      </div>
  </center>
</body>