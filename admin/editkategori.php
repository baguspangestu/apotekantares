<?php
$kd = $_GET['id'];
$sql = mysqli_query($konek, "SELECT * FROM kategori WHERE kd='$kd'");
$data = mysqli_fetch_assoc($sql);
?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-cubes"></i> Data Kategori</h1>

  <a href="?page=datakategori" class="btn btn-secondary btn-icon-split"><span class="icon text-white-50"><i
        class="fas fa-arrow-left"></i></span>
    <span class="text">Kembali</span>
  </a>
</div>

<div class="card shadow mb-4">
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-fw fa-edit"></i> Edit Data Kategori</h6>
  </div>

  <form action="" method="POST">
    <div class="card-body">
      <div class="row">
        <div class="form-group col-md-6">
          <label class="font-weight-bold">Kode Kategori</label>
          <input autocomplete="off" type="text" name="kd" value="<?php echo $data['kd']; ?>" readonly=""
            class="form-control" />
        </div>
        <div class="form-group col-md-6">
          <label class="font-weight-bold">Nama Kategori</label>
          <input autocomplete="off" type="text" name="nama" value="<?php echo $data['nama']; ?>" required
            class="form-control" />
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
  $kd = $_POST['kd'];
  $nama = $_POST['nama'];

  $edit = mysqli_query($konek, "UPDATE kategori SET kd = '$kd', nama = '$nama' WHERE kd = '$kd'");
  echo mysqli_error($konek);

  if ($edit) {
    echo '<script>alert("Edit Kategori Berhasil!");</script>';
    echo '<script>window.location.href="index.php?page=datakategori"</script>';
  }
  echo '<script>alert("Edit Kategori Gagal!");</script>';
  echo mysqli_error($konek);
}
?>