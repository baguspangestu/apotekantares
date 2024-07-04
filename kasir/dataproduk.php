<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-boxes"></i> Data Produk</h1>
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
            <th>Tanggal Expired</th>
            <th>Kategori</th>
            <th>Satuan</th>
            <th>Harga</th>
            <th>Stok</th>
          </tr>
        </thead>
        <tbody>
          <?php
          function formatRupiah($n)
          {
            return "Rp " . number_format($n, 0, ',', '.');
          }

          $query = "SELECT a.kd, a.nama, b.tgl_exp, c.nama as kategori, a.satuan, a.harga_jual as harga, b.stok
                    FROM produk a 
                    LEFT JOIN detail_produk b ON a.kd = b.kd_produk
                    LEFT JOIN kategori c ON a.kd_kategori = c.kd
                    ORDER BY a.nama ASC";
          $result  = mysqli_query($konek, $query);
          $no = 0;
          while ($data = mysqli_fetch_assoc($result)) {
          ?>
          <tr align="center">
            <td><?php echo ++$no; ?></td>
            <td><?php echo $data['kd']; ?></td>
            <td align="left"><?php echo $data['nama']; ?></td>
            <td><?php echo date('d-m-Y', strtotime($data['tgl_exp'])); ?></td>
            <td align="left"><?php echo $data['kategori']; ?></td>
            <td><?php echo $data['satuan']; ?></td>
            <td align="right"><?php echo formatRupiah($data['harga']); ?></td>
            <td><?php echo $data['stok']; ?></td>
          </tr>
          <?php
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>