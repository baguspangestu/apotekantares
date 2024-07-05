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
        <th style="padding:20px 0;text-transform:uppercase">CETAK LAPORAN PENDAPATAN
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
            <th>Kode Transaksi</th>
            <th>Tanggal</th>
            <th>Jenis Transaksi</th>
            <th>Suplier/Pembeli</th>
            <th>Total Penjualan</th>
            <th>Total Pembelian</th>
            <th>Total Pendapatan</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $no = 0;
          $tj = 0;
          $tb = 0;
          $query = mysqli_query($konek, "SELECT 'transaksi_jual' AS transaksi, a.kd, a.tanggal, 1 AS type, a.pembeli AS pihak, a.subtotal, a.diskon
                                        FROM transaksi_jual a
                                        WHERE a.tanggal BETWEEN '$dari_tanggal' AND '$sampai_tanggal'
                                        UNION ALL
                                        SELECT 'transaksi_beli' AS transaksi, b.kd, b.tanggal, 2 AS type, c.nama AS pihak, b.total AS subtotal, 0 AS diskon
                                        FROM transaksi_beli b
                                        LEFT JOIN suplier c ON b.kd_suplier=c.kd
                                        WHERE b.tanggal BETWEEN '$dari_tanggal' AND '$sampai_tanggal'
                                        ORDER BY tanggal ASC, kd ASC");
          ?>
          <?php
          while ($data = mysqli_fetch_assoc($query)) {
            $type = $data['type'];
            $tjual = 0;
            $tbeli = 0;

            if ($type == 1) {
              $tjual = $data['subtotal'] - ($data['diskon'] / 100) * $data['subtotal'];
              $tj += $tjual;
            } elseif ($type == 2) {
              $tbeli = $data['subtotal'];
              $tb += $tbeli;
            }
          ?>
            <tr align="center">
              <td><?php echo ++$no; ?></td>
              <td><?php echo $data['kd']; ?></td>
              <td><?php echo formatTanggal($data['tanggal']); ?></td>
              <td><?php echo $data['type'] == 1 ? 'Penjualan' : 'Pembelian'; ?></td>
              <td align="left"><?php echo $data['pihak']; ?></td>
              <td align="right"><?php echo formatRupiah($tjual); ?></td>
              <td align="right"><?php echo formatRupiah($tbeli); ?></td>
              <td align="right"><?php echo $type == 1 ? formatRupiah($tjual) : '-' . formatRupiah($tbeli); ?></td>
            </tr>
          <?php
          }
          echo mysqli_error($konek);
          ?>
          <tr align="center">
            <td colspan="5" align="center"><b>Total</b></td>
            <td align="right"><b><?php echo formatRupiah($tj); ?></b></td>
            <td align="right"><b><?php echo formatRupiah($tb); ?></b></td>
            <td align="right"><b><?php echo formatRupiah($tj - $tb); ?></b></td>
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