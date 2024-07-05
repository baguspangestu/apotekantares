<?php
$dari_tanggal = !empty($_GET['dari-tanggal']) ? $_GET['dari-tanggal'] : date('Y-m-d');
$sampai_tanggal = !empty($_GET['sampai-tanggal']) ? $_GET['sampai-tanggal'] : date('Y-m-d');
?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-print"></i> Data Laporan Penjualan Produk</h1>
</div>

<div class="card shadow mb-4">
  <!-- /.card-header -->
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary"><i class="fa fa-table"></i> Pilih Tanggal Penjualan</h6>
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
    <h6 class="m-0 font-weight-bold text-primary"><i class="fa fa-table"></i> Data Penjualan Produk </h6>

    <a target="_blank" href="cetak_laporanjual.php?dari-tanggal=<?php echo $dari_tanggal; ?>&sampai-tanggal=<?php echo $sampai_tanggal; ?>" class="btn btn-primary"> <i class="fa fa-print"></i> Cetak Data </a>
  </div>

  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead class="bg-primary text-white">
          <tr align="center">
            <th width="5%">No</th>
            <th>Kode</th>
            <th>Tanggal</th>
            <th>Nama</th>
            <th>Kategori</th>
            <th>Harga</th>
            <th>Jumlah</th>
            <th>Total</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $no = 0;
          $total = 0;
          $query = mysqli_query($konek, "SELECT b.kd, b.tanggal, c.nama, d.nama as kategori, a.jumlah, a.harga FROM detail_transaksi_jual a LEFT JOIN transaksi_jual b ON a.kd_transaksi=b.kd LEFT JOIN produk c ON a.kd_produk=c.kd LEFt JOIN kategori d ON c.kd_kategori=d.kd WHERE b.tanggal BETWEEN '$dari_tanggal' AND '$sampai_tanggal' ORDER BY b.tanggal DESC, c.nama ASC");
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
              <td alegn="right"><?php echo formatRupiah($data['harga']); ?></td>
              <td><?php echo $data['jumlah']; ?></td>
              <td align="right"><?php echo formatRupiah($data['harga'] * $data['jumlah']); ?></td>
            </tr>
          <?php
          }
          echo mysqli_error($konek);
          ?>
        </tbody>
      </table>
      <div class="alert alert-info mt-4 text-right">
        Total biaya seluruh penjualan adalah sebesar <b><?php echo formatRupiah($total); ?></b>
      </div>
    </div>
  </div>
</div>


<script src="../assets/vendor/jquery/jquery.min.js"></script>
<script>
  $('#filter').change(() => $('#filter').submit());
</script>