<?php
$dari_tanggal = !empty($_GET['dari-tanggal']) ? $_GET['dari-tanggal'] : date('Y-m-d');
$sampai_tanggal = !empty($_GET['sampai-tanggal']) ? $_GET['sampai-tanggal'] : date('Y-m-d');
?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-print"></i> Data Laporan Pendapatan</h1>
</div>

<div class="card shadow mb-4">
  <!-- /.card-header -->
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary"><i class="fa fa-table"></i> Pilih Tanggal</h6>
  </div>

  <div class="card-body">
    <form id="filter" role="form" method="GET">
      <input type="hidden" name="page" value="<?php echo $_GET['page']; ?>" />
      <div class="row">
        <div class="form-group col-md-3">
          <label class="font-weight-bold">Dari Tanggal</label>
          <input autocomplete="off" type="date" name="dari-tanggal" value="<?php echo $dari_tanggal; ?>" class="form-control" required />
        </div>
        <div class="form-group col-md-3">
          <label class="font-weight-bold">Sampai Tanggal</label>
          <input autocomplete="off" type="date" name="sampai-tanggal" value="<?php echo $sampai_tanggal; ?>" class="form-control" required />
        </div>
      </div>
    </form>
  </div>
</div>

<div class="card shadow mb-4">
  <!-- /.card-header -->
  <div class="card-header d-sm-flex align-items-center justify-content-between py-3">
    <h6 class="m-0 font-weight-bold text-primary"><i class="fa fa-table"></i> Data Pendapatan </h6>

    <a target="_blank" href="cetak_laporanpendapatan.php?dari-tanggal=<?php echo $dari_tanggal; ?>&sampai-tanggal=<?php echo $sampai_tanggal; ?>" class="btn btn-primary"> <i class="fa fa-print"></i> Cetak Data </a>
  </div>

  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead class="bg-primary text-white">
          <tr align="center">
            <th width="5%">No</th>
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
          $total = 0;
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
              $total += $tjual;
            } elseif ($type == 2) {
              $tbeli = $data['subtotal'];
              $total -= $tbeli;
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
        </tbody>
      </table>
      <div class="alert alert-info mt-4 text-right">
        Total pendapatan adalah sebesar <b><?php echo formatRupiah($total); ?></b>
      </div>
    </div>
  </div>
</div>


<script src="../assets/vendor/jquery/jquery.min.js"></script>
<script>
  $('#filter').change(() => $('#filter').submit());
</script>