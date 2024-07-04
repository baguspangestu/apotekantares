<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-users"></i> Data Pengguna</h1>

  <a href="?page=inputuser" class="btn btn-success"> <i class="fa fa-plus"></i> Tambah Data </a>
</div>

<div class="card shadow mb-4">
  <!-- /.card-header -->
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary"><i class="fa fa-table"></i> Daftar Data Pengguna</h6>
  </div>

  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead class="bg-primary text-white">
          <tr align="center">
            <th width="5%">No</th>
            <th>Username</th>
            <th>Password</th>
            <th>Level</th>
            <th>Nama</th>
            <th width="15%">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $no = 0;
          $query  = mysqli_query($konek, "SELECT * FROM user");
          while ($data = mysqli_fetch_assoc($query)) {
            $no++;
          ?>
          <tr align="center">
            <td><?php echo $no; ?></td>
            <td><?php echo $data['username']; ?></td>
            <td><?php echo $data['password']; ?></td>
            <td><?php echo $data['level']; ?></td>
            <td align="left"><?php echo $data['nama']; ?></td>
            <td>
              <div class="btn-group" role="group">
                <a data-toggle="tooltip" data-placement="bottom" title="Edit Data"
                  href="?page=edituser&id=<?php echo $data['id']; ?>" class="btn btn-warning btn-sm"><i
                    class="fa fa-edit"></i></a>
                <?php
                  if ($_SESSION['id'] == $data['id']) {
                  ?>
                <div class="btn btn-danger btn-sm disabled"><i class="fa fa-trash"></i></div>
                <?php
                  } else {
                  ?>
                <a data-toggle="tooltip" data-placement="bottom" title="Hapus Data"
                  href="hapususer.php?id=<?php echo $data['id']; ?>"
                  onclick="return confirm ('Apakah anda yakin untuk meghapus data ini')"
                  class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                <?php
                  }
                  ?>
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