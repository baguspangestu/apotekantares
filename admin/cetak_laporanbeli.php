<?php
session_start();
include("../config/koneksi.php");

$dari_tanggal = !empty($_GET['dari-tanggal']) ? $_GET['dari-tanggal'] : date('Y-m-d');
$sampai_tanggal = !empty($_GET['sampai-tanggal']) ? $_GET['sampai-tanggal'] : date('Y-m-d');

$date = date("d-m-Y");

function formatTanggal($ddd)
{
  return date('d-m-Y', strtotime($ddd));
}
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
        <th style="padding:20px 0;text-transform:uppercase">LAPORAN PEMBELIAN PRODUK
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
            <th>Suplier</th>
            <th>Produk</th>
            <th>Harga</th>
            <th>Jumlah</th>
            <th>Total</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $no = 0;
          $total = 0;
          $query = mysqli_query($konek, "SELECT b.kd, b.tanggal, c.nama as suplier, d.nama as produk, a.jumlah, a.harga FROM detail_transaksi_beli a LEFT JOIN transaksi_beli b ON a.kd_transaksi=b.kd LEFT JOIN suplier c ON b.kd_suplier=c.kd LEFT JOIN produk d ON a.kd_produk=d.kd WHERE b.tanggal BETWEEN '$dari_tanggal' AND '$sampai_tanggal' ORDER BY b.tanggal DESC, d.nama ASC");
          ?>
          <?php
          $total = 0;
          while ($data = mysqli_fetch_assoc($query)) {
            $total += $data['harga'] * $data['jumlah'];
          ?>
          <tr>
            <td align="center"><?php echo ++$no; ?></td>
            <td align="center"><?php echo $data['kd']; ?></td>
            <td align="center"><?php echo $data['tanggal']; ?></td>
            <td><?php echo $data['suplier']; ?></td>
            <td><?php echo $data['produk']; ?></td>
            <td align="right"><?php echo "Rp " . number_format($data['harga'], 0, ',', '.'); ?></td>
            <td align="center"><?php echo $data['jumlah']; ?></td>
            <td align="right"><?php echo "Rp " . number_format($data['harga'] * $data['jumlah'], 0, ',', '.'); ?></td>
          </tr>
          <?php
          }
          ?>
          <tr align="center">
            <td colspan="7" align="center"><b>Total Pembelian</b></td>
            <td align="right"><b><?php echo "Rp " . number_format($total, 0, ',', '.'); ?></b></td>
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