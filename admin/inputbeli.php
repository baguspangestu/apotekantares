<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-dolly-flatbed"></i> Data Pembelian</h1>

  <a href="?page=databeli" class="btn btn-secondary btn-icon-split"><span class="icon text-white-50"><i class="fas fa-arrow-left"></i></span>
    <span class="text">Kembali</span>
  </a>
</div>

<div class="card shadow mb-4">
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-fw fa-plus"></i> Tambah Data Pembelian</h6>
  </div>

  <form action="" method="POST">
    <div class="card-body">
      <div class="row">
        <div class="form-group col-md-6">
          <label class="font-weight-bold">Nama Suplier</label>
          <select name="nama_suplier" id="nama_suplier" class="form-control" required>
            <option value="">--Pilih Suplier--</option>
            <?php
            $daftar_produk = array();
            $qq = mysqli_query($konek, "SELECT * FROM suplier a LEFT JOIN produk b ON a.kd_produk=b.kd_produk LEFT JOIN detail_produk c ON b.kd_produk=c.kd_produk ORDER BY kd_suplier ASC");
            while ($dd = mysqli_fetch_assoc($qq)) {
              $daftar_produk[] = $dd;
            ?>
              <option value="<?php echo $dd['kd_suplier']; ?>"><?php echo $dd['nama_suplier'] ?></option>
            <?php
            }
            ?>
          </select>
        </div>

        <div class="form-group col-md-6">
          <label class="font-weight-bold">Kode Produk</label>
          <input autocomplete="off" type="text" id="kd_produk" name="kd_produk" required class="form-control" readonly="readonly" />
        </div>

        <div class="form-group col-md-6">
          <label class="font-weight-bold">Nama Produk</label>
          <input autocomplete="off" type="text" id="nama_produk" name="nama_produk" required class="form-control" readonly="readonly" />
        </div>

        <div class="form-group col-md-6">
          <label class="font-weight-bold">Tanggal EXP</label>
          <input autocomplete="off" type="date" id="tgl_exp" name="tgl_exp" required class="form-control" readonly="readonly" />
        </div>

        <div class="form-group col-md-6">
          <label class="font-weight-bold">Harga</label>
          <input autocomplete="off" type="text" id="harga_modal" name="harga_modal" required class="form-control" readonly="readonly" />
        </div>

        <div class="form-group col-md-6">
          <label class="font-weight-bold">Jumlah</label>
          <input autocomplete="off" type="number" name="jml_beli" required class="form-control" />
        </div>

        <div class="form-group col-md-6">
          <label class="font-weight-bold">Total Biaya</label>
          <input autocomplete="off" type="text" id="total_transaksi" name="total_transaksi" required class="form-control" readonly="readonly" />
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

  //kode keranjang//
  $q4 = mysqli_query($konek, "SELECT * FROM keranjang_beli ORDER BY idbeli DESC");
  $dt4 = mysqli_fetch_assoc($q4);
  $hml4 = mysqli_num_rows($q4);
  if ($hml4 == 0) {
    $sub_faktur4 = 'BL001';
  } else {
    $suid4 = substr($dt4['idbeli'], 3);
    if ($suid4 > 0 && $suid4 <= 8) {
      $su4 = $suid4 + 1;
      $sub_faktur4 = 'BL00' . $su4;
    } elseif ($suid4 >= 9 && $suid4 <= 100) {
      $su4 = $suid4 + 1;
      $sub_faktur4 = 'BL0' . $su4;
    } elseif ($suid4 >= 99 && $suid4 <= 1000) {
      $su4 = $suid4 + 1;
      $sub_faktur4 = 'BL' . $su4;
    }
  }
  //kode keranjang//

  $tgl_transaksi = date('Y-m-d');
  $total_transaksi = $_POST['total_transaksi'];
  $jml_beli = $_POST['jml_beli'];
  $harga_modal = $_POST['harga_modal'];
  $kd_suplier = $_POST['nama_suplier'];
  $kd_produk = $_POST['kd_produk'];

  mysqli_query($konek, "INSERT INTO keranjang_beli VALUES('$sub_faktur4','$kd_suplier','$kd_produk','$tgl_transaksi','$jml_beli','$total_transaksi')");

  echo mysqli_error($konek);
  echo '<script>alert("Berhasil Menyimpan Transaksi!");</script>';
  echo '<script>window.location.href="../admin/index.php?page=databeli"</script>';
}
?>

<!-- REQUIRED JS SCRIPTS -->
<script>
  function resetDetailProduk() {
    // Nilai default untuk form
    document.getElementById("kd_produk").value = "-";
    document.getElementById("nama_produk").value = "-";
    document.getElementById("tgl_exp").value = "-";
    document.getElementById("harga_modal").value = "0";
    document.getElementsByName("jml_beli")[0].value = "0";
    document.getElementById("total_transaksi").value = "0";
  }

  function tampilDetailProduk() {
    var daftar_produk = <?php echo json_encode($daftar_produk); ?>;
    var produk_terpilih = document.getElementById("nama_suplier").selectedIndex;
    if (produk_terpilih != 0) {
      document.getElementById("kd_produk").value = daftar_produk[produk_terpilih - 1].kd_produk;
      document.getElementById("nama_produk").value = daftar_produk[produk_terpilih - 1].nama_produk;
      document.getElementById("tgl_exp").value = daftar_produk[produk_terpilih - 1].tgl_exp;
      document.getElementById("harga_modal").value = daftar_produk[produk_terpilih - 1].harga_modal;
    } else {
      resetDetailProduk();
    }
  }

  function hitungTotalHarga() {
    var harga = document.getElementById("harga_modal").value;
    var jumlah_beli = document.getElementsByName("jml_beli")[0].value;
    var total_harga = harga * jumlah_beli;

    if (isNaN(total_harga)) {
      total_harga = 0;
    }
    document.getElementById("total_transaksi").value = total_harga;
  }
  // daftarkan fungsi ke event element html
  document.getElementById("nama_suplier").addEventListener("change", tampilDetailProduk);
  document.getElementsByName("jml_beli")[0].addEventListener("keyup", hitungTotalHarga);


  // reset form saat halaman diakses
  resetDetailProduk()
</script>