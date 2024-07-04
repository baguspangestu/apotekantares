<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-tablets"></i> Data Produk</h1>

  <a href="?page=dataproduk" class="btn btn-secondary btn-icon-split"><span class="icon text-white-50"><i
        class="fas fa-arrow-left"></i></span>
    <span class="text">Kembali</span>
  </a>
</div>

<div class="card shadow mb-4">
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-fw fa-plus"></i> Tambah Data Produk</h6>
  </div>

  <form action="" method="POST">
    <div class="card-body">
      <div class="row">
        <div class="form-group col-md-6">
          <label class="font-weight-bold">Nama Produk</label>
          <input autocomplete="off" type="text" name="nama" required class="form-control" />
        </div>

        <div class="form-group col-md-6">
          <label class="font-weight-bold">Tanggal Exp</label>
          <input type="date" name="tgl_exp" required class="form-control" />
        </div>

        <div class="form-group col-md-6">
          <label class="font-weight-bold">Kategori</label>
          <select name="kd_kategori" class="form-control" required>
            <option value="">--Pilih Kategori--</optiom>
              <?php
              $qq = mysqli_query($konek, "SELECT * FROM kategori");
              while ($dd = mysqli_fetch_assoc($qq)) {
              ?>
            <option value="<?php echo $dd['kd'] ?>"><?php echo $dd['kd'] ?> -
              <?php echo $dd['nama'] ?></option>
            <?php
              }
          ?>
          </select>
        </div>

        <div class="form-group col-md-6">
          <label class="font-weight-bold">Satuan</label>
          <input autocomplete="off" type="text" name="satuan" required class="form-control" />
        </div>

        <div class="form-group col-md-6">
          <label class="font-weight-bold">Harga Beli</label>
          <input autocomplete="off" type="text" name="harga_beli" required class="form-control" />
        </div>

        <div class="form-group col-md-6">
          <label class="font-weight-bold">Harga Jual</label>
          <input autocomplete="off" type="text" name="harga_jual" required class="form-control" />
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
  $query2 = mysqli_query($konek, "SELECT * FROM produk ORDER BY kd DESC");
  $data2 = mysqli_fetch_assoc($query2);
  $jml = mysqli_num_rows($query2);
  if ($jml == 0) {
    $kd = 'PD001';
  } else {
    $subid = substr($data2['kd'], 3);
    if ($subid > 0 && $subid <= 8) {
      $sub = $subid + 1;
      $kd = 'PD00' . $sub;
    } elseif ($subid >= 9 && $subid <= 100) {
      $sub = $subid + 1;
      $kd = 'PD0' . $sub;
    } elseif ($subid >= 99 && $subid <= 1000) {
      $sub = $subid + 1;
      $kd = 'PD' . $sub;
    }
  }

  $nama = $_POST['nama'];
  $kd_kategori = $_POST['kd_kategori'];
  $tgl_exp = $_POST['tgl_exp'];
  $stok = 0;
  $harga_beli = $_POST['harga_beli'];
  $harga_jual = $_POST['harga_jual'];
  $satuan = $_POST['satuan'];

  mysqli_query($konek, "INSERT INTO produk (kd, kd_kategori, nama, harga_jual, satuan) VALUES ('$kd','$kd_kategori','$nama','$harga_jual','$satuan')");
  mysqli_query($konek, "INSERT INTO detail_produk (kd_produk, tgl_exp, harga_beli, stok) VALUES('$kd','$tgl_exp','$harga_beli','$stok')");
  echo mysqli_error($konek);
  echo '<script>alert("Berhasil Menyimpan Produk!");</script>';
  echo '<script>window.location.href="index.php?page=dataproduk"</script>';
}
?>