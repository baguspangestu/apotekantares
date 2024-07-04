<?php
$dari_tanggal = !empty($_GET['dari-tanggal']) ? $_GET['dari-tanggal'] : date('Y-m-d');
$sampai_tanggal = !empty($_GET['sampai-tanggal']) ? $_GET['sampai-tanggal'] : date('Y-m-d');
?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-print"></i> Data Laporan Pembelian Produk</h1>
</div>

<div class="card shadow mb-4">
  <!-- /.card-header -->
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary"><i class="fa fa-table"></i> Pilih Tanggal Pembelian</h6>
  </div>

  <div class="card-body">
    <form id="filter" role="form" method="GET">
      <input type="hidden" name="page" value="<?php echo $_GET['page']; ?>" />
      <div class="row">
        <div class="form-group col-md-3">
          <label class="font-weight-bold">Dari Tanggal</label>
          <input autocomplete="off" type="date" name="dari-tanggal" value="<?php echo $dari_tanggal; ?>"
            class="form-control" required />
        </div>
        <div class="form-group col-md-3">
          <label class="font-weight-bold">Sampai Tanggal</label>
          <input autocomplete="off" type="date" name="sampai-tanggal" value="<?php echo $sampai_tanggal; ?>"
            class="form-control" required />
        </div>
      </div>
    </form>
  </div>
</div>

<div class="card shadow mb-4">
  <!-- /.card-header -->
  <div class="card-header d-sm-flex align-items-center justify-content-between py-3">
    <h6 class="m-0 font-weight-bold text-primary"><i class="fa fa-table"></i> Data Pembelian Produk</h6>

    <a target="_blank"
      href="cetak_laporanbeli.php?dari-tanggal=<?php echo $dari_tanggal; ?>&sampai-tanggal=<?php echo $sampai_tanggal; ?>"
      class="btn btn-primary"> <i class="fa fa-print"></i> Cetak Data </a>
  </div>

  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead class="bg-primary text-white">
          <tr align="center">
            <th width="5%">No</th>
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
        </tbody>
      </table>
      <div class="alert alert-info mt-4 text-right">
        Total biaya pembelian adalah sebesar <b><?php echo "Rp " . number_format($total, 0, ',', '.'); ?></b>
      </div>
    </div>
  </div>
</div>

<script src="../assets/vendor/jquery/jquery.min.js"></script>
<script>
$('#filter').change(() => $('#filter').submit());
</script>