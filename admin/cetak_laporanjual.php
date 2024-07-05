<?php
include("../config/helpers.php");
include("../config/koneksi.php");

session_start();

$dari_tanggal = !empty($_GET['dari-tanggal']) ? $_GET['dari-tanggal'] : date('Y-m-d');
$sampai_tanggal = !empty($_GET['sampai-tanggal']) ? $_GET['sampai-tanggal'] : date('Y-m-d');

$date = date("d-m-Y");
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
  <center>
    <table width="80%" celpadding="8">
      <tr align="center">
        <th>APOTEK ANTARES PRINGSEWU</th>
      </tr>
      <tr align="center">
        <td style="border-bottom:2px solid #333;padding:0 0 5px 0;"><i>Jl. Pringadi No.128, RT.001, Pringsewu Utara,
            Kec.
            Pringsewu, Kabupaten Pringsewu, Lampung 35373</i></td>
      </tr>
      <tr align="center">
        <th style="padding:20px 0;text-transform:uppercase">CETAK LAPORAN PENJUALAN
          PRODUK
        </th>
      </tr>
    </table>

    <div style="width:80%; text-align:left">
      <table>
        <tr>
          <td style="padding:5px 25px 5px 0">Dari Tanggal</td>
          <td>: <?php echo formatTanggal($dari_tanggal); ?></td>
        </tr>
        <tr>
          <td style="padding:5px 25px 5px 0">Sampai Tanggal</td>
          <td>: <?php echo formatTanggal($sampai_tanggal); ?></td>
        </tr>
      </table>
    </div>

    <div class="table-responsive">
      <table width="80%" border="1" cellpadding="5" class="table">
        <thead>
          <tr align="center">
            <th>No</th>
            <th>Kode</th>
            <th>Tanggal</th>
            <th>Nama</th>
            <th>Kategori</th>
            <th>Harga Jual</th>
            <th>Jumlah</th>
            <th>Total</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $no = 0;
          $total = 0;
          $query = mysqli_query($konek, "SELECT b.kd, b.tanggal, c.nama, d.nama as kategori, a.jumlah, a.harga FROM detail_transaksi_jual a LEFT JOIN transaksi_jual b ON a.kd_transaksi=b.kd LEFT JOIN produk c ON a.kd_produk=c.kd LEFt JOIN kategori d ON c.kd_kategori=d.kd WHERE b.tanggal BETWEEN '$dari_tanggal' AND '$sampai_tanggal' ORDER BY b.tanggal ASC, b.kd ASC, c.nama ASC");
          ?>
          <?php
          while ($data = mysqli_fetch_assoc($query)) {
            $total += $data['harga'] * $data['jumlah'];
          ?>
            <tr align="center">
              <td><?php echo ++$no; ?></td>
              <td><?php echo $data['kd']; ?></td>
              <td><?php echo formatTanggal($data['tanggal']); ?></td>
              <td align="left"><?php echo $data['nama']; ?></td>
              <td align="left"><?php echo $data['kategori']; ?></td>
              <td align="right"><?php echo formatRupiah($data['harga']); ?></td>
              <td><?php echo $data['jumlah']; ?></td>
              <td align="right"><?php echo formatRupiah($data['harga'] * $data['jumlah']); ?></td>
            </tr>
          <?php
          }
          echo mysqli_error($konek);
          ?>
          <tr align="center">
            <td colspan="7" align="center"><b>Total Penjualan</b></td>
            <td align="right"><b><?php echo formatRupiah($total); ?></b></td>
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