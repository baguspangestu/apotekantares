<?php
$id = $_GET['id'];
$sql = mysqli_query($konek, "SELECT * FROM user WHERE id='$id'");
$data = mysqli_fetch_assoc($sql);
?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-users"></i> Data Pengguna</h1>

  <a href="?page=datauser" class="btn btn-secondary btn-icon-split"><span class="icon text-white-50"><i
        class="fas fa-arrow-left"></i></span>
    <span class="text">Kembali</span>
  </a>
</div>

<div class="card shadow mb-4">
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-fw fa-plus"></i> Tambah Data Pengguna</h6>
  </div>

  <form action="" method="POST">
    <div class="card-body">
      <div class="row">
        <div class="form-group col-md-6">
          <label class="font-weight-bold">ID Pengguna</label>
          <input autocomplete="off" type="text" name="id" value="<?php echo $data['id']; ?>" readonly=""
            class="form-control" />
        </div>

        <div class="form-group col-md-6">
          <label class="font-weight-bold">Username</label>
          <input autocomplete="off" type="text" name="username" required class="form-control"
            value="<?php echo $data['username']; ?>" />
        </div>

        <div class="form-group col-md-6">
          <label class="font-weight-bold">Password</label>
          <input autocomplete="off" type="text" name="password" required class="form-control"
            value="<?php echo $data['password']; ?>" />
        </div>

        <div class="form-group col-md-6">
          <label class="font-weight-bold">Level</label>
          <select class="form-control" name="level" required>
            <option value="">--Pilih Level--</optiom>
            <option value="admin" <?php if ('admin' == $data['level']) {
                                    echo "selected";
                                  } ?>>Admin</option>
            <option value="kasir" <?php if ('kasir' == $data['level']) {
                                    echo "selected";
                                  } ?>>Kasir</option>
          </select>
        </div>

        <div class="form-group col-md-6">
          <label class="font-weight-bold">Nama</label>
          <input autocomplete="off" type="text" name="nama" required class="form-control"
            value="<?php echo $data['nama']; ?>" />
        </div>
      </div>
    </div>

    <div class="card-footer text-right">
      <button name="submit" type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan</button>
      <button type="reset" class="btn btn-info"><i class="fa fa-sync-alt"></i> Reset</button>
    </div>
  </form>
</div>

<?php
if (isset($_POST['submit'])) {
  $id = $_POST['id'];
  $username = $_POST['username'];
  $password = $_POST['password'];
  $level = $_POST['level'];
  $nama = $_POST['nama'];

  $edit = mysqli_query($konek, "UPDATE user SET id = '$id', username = '$username', password = '$password', level = '$level', nama = '$nama' WHERE id = '$id'");
  echo mysqli_error($konek);

  if ($edit) {
    echo '<script>alert("Edit User Berhasil!");</script>';
    echo '<script>window.location.href="index.php?page=datauser"</script>';
  }
  echo '<script>alert("Edit User Gagal!");</script>';
  echo mysqli_error($konek);
}
?>