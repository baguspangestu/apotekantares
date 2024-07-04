<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-dolly-flatbed"></i> Data Pembelian</h1>

  <a href="?page=inputbeli" class="btn btn-success"> <i class="fa fa-plus"></i> Tambah Data </a>
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