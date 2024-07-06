<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-cubes"></i> Data Kategori</h1>

  <a href="?page=datakategori" class="btn btn-secondary btn-icon-split"><span class="icon text-white-50"><i class="fas fa-arrow-left"></i></span>
    <span class="text">Kembali</span>
  </a>
</div>

<div class="card shadow mb-4">
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-fw fa-plus"></i> Tambah Data Kategori</h6>
  </div>

  <form action="" method="POST">
    <div class="card-body">
      <div class="form-group">
        <label class="font-weight-bold">Nama Kategori</label>
        <input autocomplete="off" type="text" name="nama" required class="form-control" />
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
  $query2 = mysqli_query($konek, "SELECT * FROM kategori ORDER BY kd DESC");
  $data2 = mysqli_fetch_assoc($query2);
  $jml = mysqli_num_rows($query2);

  $kd = generateKd('KT', $data2);
  $nama = $_POST['nama'];

  $simpan = mysqli_query($konek, "INSERT INTO kategori VALUES ('$kd','$nama')");
  echo mysqli_error($konek);
  if ($simpan) {
    echo '<script>alert("Berhasil Menyimpan Kategori!");</script>';
    echo '<script>window.location.href="index.php?page=datakategori"</script>';
  } else {
    echo '<script>alert("Gagal Menyimpan Kategori!");</script>';
    echo mysqli_error($konek);
  }
}
?>