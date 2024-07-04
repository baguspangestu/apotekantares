<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-hospital-user"></i> Data Suplier</h1>

  <a href="?page=inputsuplier" class="btn btn-success"> <i class="fa fa-plus"></i> Tambah Data </a>
</div>

<div class="card shadow mb-4">
  <!-- /.card-header -->
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary"><i class="fa fa-table"></i> Daftar Data Suplier</h6>
  </div>

  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead class="bg-primary text-white">
          <tr align="center">
            <th width="5%">No</th>
            <th>Kode Suplier</th>
            <th>Nama Suplier</th>
            <th>Alamat</th>
            <th>Email</th>
            <th>No. Telp</th>
            <th>Produk</th>
            <th width="5%">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php
					$no = 0;
					$query  = mysqli_query($konek, "SELECT * FROM suplier ORDER BY kd ASC");
					while ($data = mysqli_fetch_assoc($query)) {
						$detailQuery = mysqli_query($konek, "SELECT COUNT(*) as produk FROM detail_suplier WHERE kd_suplier='$data[kd]'");
						$detailData = mysqli_fetch_assoc($detailQuery);
					?>
          <tr align="center">
            <td><?php echo ++$no; ?></td>
            <td><?php echo $data['kd']; ?></td>
            <td align="left"><?php echo $data['nama']; ?></td>
            <td align="left"><?php echo $data['alamat']; ?></td>
            <td align="left"><?php echo $data['email']; ?></td>
            <td><?php echo $data['no_tlp']; ?></td>
            <td><?php echo $detailData['produk']; ?></td>
            <td>
              <div class="btn-group" role="group">
                <a data-toggle="tooltip" data-placement="bottom" title="Lihat Data"
                  href="?page=detailsuplier&id=<?php echo $data['kd']; ?>" class="btn btn-success btn-sm"><i
                    class="fa fa-eye"></i></a>
                <a data-toggle="tooltip" data-placement="bottom" title="Edit Data"
                  href="?page=editsuplier&id=<?php echo $data['kd']; ?>" class="btn btn-warning btn-sm"><i
                    class="fa fa-edit"></i></a>
                <a data-toggle="tooltip" data-placement="bottom" title="Hapus Data"
                  href="hapussuplier.php?id=<?php echo $data['kd']; ?>"
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