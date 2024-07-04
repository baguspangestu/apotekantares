<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-print"></i> Laporan Data Produk</h1>

  <a target="_blank" href="cetak_produk.php" class="btn btn-primary"> <i class="fa fa-print"></i> Cetak Data </a>
</div>

<div class="card shadow mb-4">
  <!-- /.card-header -->
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary"><i class="fa fa-table"></i> Daftar Data Produk</h6>
  </div>

  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead class="bg-primary text-white">
          <tr align="center">
            <th width="5%">No</th>
            <th>Kode Produk</th>
            <th>Nama Produk</th>
            <th>Nama Kategori</th>
            <th>Harga Beli</th>
            <th>Harga Jual</th>
          </tr>
        </thead>
        <?php
        $no = 0;
        $total = 0;
        $query = mysqli_query($konek, "SELECT a.kd, a.nama, c.nama as kategori, b.harga_beli, a.harga_jual FROM produk a LEFT JOIN detail_produk b ON a.kd=b.kd_produk LEFT JOIN kategori c ON a.kd_kategori=c.kd WHERE a.kd=b.kd_produk GROUP BY a.kd");
        ?>
        <tbody>
          <?php
          while ($data = mysqli_fetch_assoc($query)) {
            $no++;
          ?>
            <tr>
              <td align="center"><?php echo $no; ?></td>
              <td align="center"><?php echo $data['kd']; ?></td>
              <td><?php echo $data['nama']; ?></td>
              <td><?php echo $data['kategori']; ?></td>
              <td align="right"><?php echo "Rp " . number_format($data['harga_beli'], 0, ',', '.'); ?></td>
              <td align="right"><?php echo "Rp " . number_format($data['harga_jual'], 0, ',', '.'); ?></td>
            </tr>
          <?php
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>