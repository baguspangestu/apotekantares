<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-hospital-user"></i> Data Suplier</h1>

  <a href="?page=datasuplier" class="btn btn-secondary btn-icon-split"><span class="icon text-white-50"><i
        class="fas fa-arrow-left"></i></span>
    <span class="text">Kembali</span>
  </a>
</div>

<div class="card shadow mb-4">
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-fw fa-plus"></i> Tambah Data Suplier</h6>
  </div>

  <form action="" method="POST">
    <div class="card-body">
      <div class="row">
        <div class="form-group col-md-6">
          <label class="font-weight-bold">Nama Suplier</label>
          <input autocomplete="off" type="text" name="nama" required class="form-control" />
        </div>

        <div class="form-group col-md-6">
          <label class="font-weight-bold">No. Telp</label>
          <input autocomplete="off" type="text" name="no_tlp" required class="form-control" />
        </div>

        <div class="form-group col-md-6">
          <label class="font-weight-bold">Email</label>
          <input autocomplete="off" type="email" name="email" required class="form-control" />
        </div>
      </div>
      <div class="form-group">
        <label class="font-weight-bold">Alamat</label>
        <textarea autocomplete="off" type="text" name="alamat" required class="form-control" /></textarea>
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
  $query2 = mysqli_query($konek, "SELECT * FROM suplier ORDER BY kd DESC");
  $data2 = mysqli_fetch_assoc($query2);
  $jml = mysqli_num_rows($query2);
  if ($jml == 0) {
    $kd = 'SP001';
  } else {
    $subid = substr($data2['kd'], 3);
    if ($subid > 0 && $subid <= 8) {
      $sub = $subid + 1;
      $kd = 'SP00' . $sub;
    } elseif ($subid >= 9 && $subid <= 100) {
      $sub = $subid + 1;
      $kd = 'SP0' . $sub;
    } elseif ($subid >= 99 && $subid <= 1000) {
      $sub = $subid + 1;
      $kd = 'SP' . $sub;
    }
  }

  $nama = $_POST['nama'];
  $alamat = $_POST['alamat'];
  $no_tlp = $_POST['no_tlp'];
  $email = $_POST['email'];

  mysqli_query($konek, "INSERT INTO suplier VALUES ('$kd','$nama','$alamat','$no_tlp','$email')");
  echo mysqli_error($konek);
  echo '<script>alert("Berhasil Menyimpan suplier!");</script>';
  echo '<script>window.location.href="index.php?page=datasuplier"</script>';
}
?>