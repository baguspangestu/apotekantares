<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-dolly-flatbed"></i> Data Pembelian</h1>

  <a href="?page=inputbeli" class="btn btn-success"> <i class="fa fa-plus"></i> Tambah Data </a>
</div>

<?php
// error_reporting(0);
$qq1 = mysqli_query($konek, "SELECT * FROM keranjang_beli");
$jml1 = mysqli_num_rows($qq1);
if ($jml1 < 1) {
} else {
?>
<div class="card shadow mb-4">
  <!-- /.card-header -->
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary"><i class="fa fa-table"></i> Daftar Data Keranjang</h6>
  </div>

  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered" width="100%" cellspacing="0">
        <thead class="bg-primary text-white">
          <tr align="center">
            <th width="5%">No</th>
            <th>Kode Keranjang</th>
            <th>Nama Suplier</th>
            <th>Nama Produk</th>
            <th>Harga Beli</th>
            <th>Jumlah</th>
            <th>Total</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php
						$total = 0;
						$no = 0;
						$query  = mysqli_query($konek, "SELECT a.kd, b.nama as nama_suplier, c.nama as nama_produk, a.jumlah, d.harga_modal, a.total FROM keranjang_beli a LEFT JOIN suplier b ON a.kd_suplier=b.kd LEFT JOIN produk c ON a.kd_produk=c.kd LEFT JOIN detail_produk d ON a.kd_produk=d.kd_produk ORDER BY a.kd ASC");
						while ($data = mysqli_fetch_assoc($query)) {
							$total += $data['total'];
						?>
          <tr align="center">
            <td><?php echo ++$no; ?></td>
            <td><?php echo $data['kd']; ?></td>
            <td align="left"><?php echo $data['nama_suplier']; ?></td>
            <td align="left"><?php echo $data['nama_produk']; ?></td>
            <td align="right"><?php echo "Rp " . number_format($data['harga_modal'], 0, ',', '.'); ?></td>
            <td><?php echo $data['jumlah']; ?></td>
            <td align="right"><?php echo "Rp " . number_format($data['total'], 0, ',', '.'); ?>
            </td>
            <td>
              <a data-toggle="tooltip" data-placement="bottom" title="Hapus Data"
                href="hapus.php?id=<?php echo $data['kd']; ?>"
                onclick="return confirm ('Apakah anda yakin untuk meghapus data ini')" class="btn btn-danger btn-sm"><i
                  class="fa fa-trash"></i></a>
            </td>
          </tr>
          <?php
						}
						echo mysqli_error($konek);
						?>
          <tr align="center">
            <td colspan="6"><b>Total</b></td>
            <td align="right"><b><?php echo "Rp " . number_format($total, 0, ',', '.'); ?></b></td>
            <td><b>-</b></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  <div class="card-footer text-right">

    <a href="selesai.php" class="btn btn-success"><i class="fa fa-check"></i> Selesai Belanja</a>
  </div>
</div>
<?php
}
?>

<div class="card shadow mb-4">
  <!-- /.card-header -->
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary"><i class="fa fa-table"></i> Daftar Data Pembelian</h6>
  </div>

  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead class="bg-primary text-white">
          <tr align="center">
            <th width="5%">No</th>
            <th>Kode</th>
            <th>Nama Suplier</th>
            <th>Nama Produk</th>
            <th>Tanggal Exp</th>
            <th>Tanggal Transaksi</th>
            <th>Harga Bali</th>
            <th>Jumlah</th>
            <th>Total</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php
					$no = 0;
					$query  = mysqli_query($konek, "SELECT a.kd, e.nama as nama_suplier, d.nama as nama_produk, a.tanggal, c.tgl_exp, c.harga_modal, b.jumlah, a.total FROM transaksi_beli a LEFT JOIN detail_transaksi_beli b ON a.kd=b.kd_transaksi LEFT JOIN detail_produk c ON b.kd_produk=c.kd_produk LEFT JOIN produk d ON b.kd_produk=d.kd AND c.kd_produk=d.kd LEFT JOIN suplier e ON a.kd_suplier=e.kd ORDER BY a.kd ASC");
					while ($data = mysqli_fetch_assoc($query)) {
					?>
          <tr align="center">
            <td><?php echo ++$no; ?></td>
            <td><?php echo $data['kd']; ?></td>
            <td align="left"><?php echo $data['nama_suplier']; ?></td>
            <td align="left"><?php echo $data['nama_produk']; ?></td>
            <td><?php echo $data['tgl_exp']; ?></td>
            <td><?php echo $data['tanggal']; ?></td>
            <td align="right"><?php echo "Rp " . number_format($data['harga_modal'], 0, ',', '.'); ?></td>
            <td><?php echo $data['jumlah']; ?></td>
            <td align="right"><?php echo "Rp " . number_format($data['total'], 0, ',', '.'); ?></td>
            <td>
              <div class="btn-group" role="group">
                <a target="_blank" data-toggle="tooltip" data-placement="bottom" title="Cetak Data"
                  href="cetak_beli.php?id=<?php echo $data['kd']; ?>" class="btn btn-primary btn-sm"><i
                    class="fa fa-print"></i></a>
                <a data-toggle="tooltip" data-placement="bottom" title="Hapus Data"
                  href="hapusbeli.php?id=<?php echo $data['kd']; ?>"
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

<div class="card shadow mb-4">
  <!-- /.card-header -->
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary"><i class="fa fa-table"></i> Daftar Data Pembelian</h6>
  </div>

  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead class="bg-primary text-white">
          <tr align="center">
            <th width="5%">No</th>
            <th>Kode</th>
            <th>Suplier</th>
            <th>Tanggal</th>
            <th>Total</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php
					function formatRupiah($n)
					{
						return "Rp " . number_format($n, 0, ',', '.');
					}

					$query = "SELECT a.kd, b.nama as suplier, a.tanggal, a.total FROM transaksi_beli a LEFT JOIN suplier b ON a.kd_suplier = b.kd ORDER BY kd DESC";
					$result  = mysqli_query($konek, $query);
					$no = 0;
					while ($data = mysqli_fetch_assoc($result)) {
					?>
          <tr align="center">
            <td><?php echo ++$no; ?></td>
            <td><?php echo $data['kd']; ?></td>
            <td align="left"><?php echo $data['suplier']; ?></td>
            <td><?php echo $data['tanggal']; ?></td>
            <td align="right"><?php echo formatRupiah($data['total']); ?></td>
            <td>
              <div class="btn-group" role="group">
                <a target="_blank" data-toggle="tooltip" data-placement="bottom" title="Cetak Data"
                  href="cetak_beli.php?id=<?php echo $data['kd']; ?>" class="btn btn-primary btn-sm"><i
                    class="fa fa-print"></i></a>
                <a data-toggle="tooltip" data-placement="bottom" title="Edit Data"
                  href="?page=editbeli&id=<?php echo $data['kd']; ?>" class="btn btn-warning btn-sm"><i
                    class="fa fa-edit"></i></a>
                <a data-toggle="tooltip" data-placement="bottom" title="Hapus Data"
                  href="hapusbeli.php?id=<?php echo $data['kd']; ?>"
                  onclick="return confirm('Anda yakin ingin menghapus transaksi ini?');"
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