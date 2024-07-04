<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-boxes"></i> Data Produk</h1>

  <a href="?page=inputproduk" class="btn btn-success"> <i class="fa fa-plus"></i> Tambah Data </a>
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
            <th>Tanggal Exp</th>
            <th>Kategori</th>
            <th>Satuan</th>
            <th>Stok</th>
            <th>Harga Beli</th>
            <th>Harga Jual</th>
            <th width="15%">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php
          function formatRupiah($n)
          {
            return "Rp " . number_format($n, 0, ',', '.');
          }

          $sort = isset($_GET['sort']) ? $_GET['sort'] : '';

          if (!empty($sort) && $sort == "stok") {
            $order = "ORDER BY b.stok ASC";
          } else if (!empty($sort) && $sort == "exp") {
            $order = "ORDER BY b.tgl_exp ASC";
          } else {
            $order = "ORDER BY a.nama ASC";
          }

          $query = "SELECT a.kd, a.nama, b.tgl_exp, c.nama as kategori, a.satuan, b.harga_beli, a.harga_jual, b.stok
										FROM produk a 
										LEFT JOIN detail_produk b ON a.kd=b.kd_produk
										LEFT JOIN kategori c ON a.kd_kategori=c.kd
										$order";
          $result  = mysqli_query($konek, $query);
          $no = 0;
          while ($data = mysqli_fetch_assoc($result)) {
          ?>
          <tr class="text-center">
            <td><?php echo ++$no; ?></td>
            <td><?php echo $data['kd']; ?></td>
            <td align="left"><?php echo $data['nama']; ?></td>
            <td><?php echo $data['tgl_exp']; ?></td>
            <td align="left"><?php echo $data['kategori']; ?></td>
            <td><?php echo $data['satuan']; ?></td>
            <td><?php echo $data['stok']; ?></td>
            <td align="right"><?php echo "Rp " . number_format($data['harga_beli'], 0, ',', '.'); ?></td>
            <td align="right"><?php echo "Rp " . number_format($data['harga_jual'], 0, ',', '.'); ?></td>
            <td>
              <div class="btn-group" role="group">
                <a data-toggle="tooltip" data-placement="bottom" title="Edit Data"
                  href="?page=editproduk&id=<?php echo $data['kd']; ?>" class="btn btn-warning btn-sm"><i
                    class="fa fa-edit"></i></a>
                <a data-toggle="tooltip" data-placement="bottom" title="Hapus Data"
                  href="hapusproduk.php?id=<?php echo $data['kd']; ?>"
                  onclick="return confirm ('Apakah anda yakin untuk meghapus data ini')"
                  class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
              </div>
            </td>
          </tr>
          <?php
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>