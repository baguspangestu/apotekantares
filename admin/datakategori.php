<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-cubes"></i> Data Kategori</h1>

  <a href="?page=inputkategori" class="btn btn-success"> <i class="fa fa-plus"></i> Tambah Data </a>
</div>

<div class="card shadow mb-4">
  <!-- /.card-header -->
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary"><i class="fa fa-table"></i> Daftar Data Kategori</h6>
  </div>

  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead class="bg-primary text-white">
          <tr align="center">
            <th width="5%">No</th>
            <th>Kode Kategori</th>
            <th>Nama Kategori</th>
            <th width="15%">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php
					$no = 0;
					$query  = mysqli_query($konek, "SELECT * FROM kategori");
					while ($data = mysqli_fetch_assoc($query)) {
						$no++;
					?>
          <tr align="center">
            <td><?php echo $no; ?></td>
            <td><?php echo $data['kd']; ?></td>
            <td><?php echo $data['nama']; ?></td>
            <td>
              <div class="btn-group" role="group">
                <a data-toggle="tooltip" data-placement="bottom" title="Edit Data"
                  href="?page=editkategori&id=<?php echo $data['kd']; ?>" class="btn btn-warning btn-sm"><i
                    class="fa fa-edit"></i></a>
                <a data-toggle="tooltip" data-placement="bottom" title="Hapus Data"
                  href="hapuskategori.php?id=<?php echo $data['kd']; ?>"
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