<?php
$kd = $_GET['id'];
$query  = "SELECT * FROM produk a LEFT JOIN detail_produk b ON a.kd=b.kd_produk WHERE a.kd='$kd'";
$result  = mysqli_query($konek, $query);
$data = mysqli_fetch_assoc($result);
?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
	<h1 class="h3 mb-0 text-gray-800"><i class="fas fa-boxes"></i> Data Produk</h1>

	<a href="?page=dataproduk" class="btn btn-secondary btn-icon-split"><span class="icon text-white-50"><i class="fas fa-arrow-left"></i></span>
		<span class="text">Kembali</span>
	</a>
</div>

<div class="card shadow mb-4">
	<div class="card-header py-3">
		<h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-fw fa-edit"></i> Edit Data Produk</h6>
	</div>

	<form action="" method="POST">
		<div class="card-body">
			<div class="row">
				<div class="form-group col-md-6">
					<label class="font-weight-bold">Kode Produk</label>
					<input autocomplete="off" type="text" name="kd" class="form-control" value="<?php echo $data['kd']; ?>" readonly="" />
				</div>

				<div class="form-group col-md-6">
					<label class="font-weight-bold">Nama Produk</label>
					<input autocomplete="off" type="text" name="nama" required class="form-control" value="<?php echo $data['nama']; ?>" />
				</div>

				<div class="form-group col-md-6">
					<label class="font-weight-bold">Tanggal Expired</label>
					<input type="date" name="tgl_exp" required class="form-control" value="<?php echo $data['tgl_exp']; ?>" />
				</div>

				<div class="form-group col-md-6">
					<label class="font-weight-bold">Kategori</label>
					<select name="kd_kategori" class="form-control">
						<option value="">--Pilih Kategori--</optiom>
							<?php
							$qq = mysqli_query($konek, "SELECT * FROM kategori");
							while ($dd = mysqli_fetch_assoc($qq)) {
							?>
						<option value="<?php echo $dd['kd'] ?>" <?php if ($dd['kd'] == $data['kd_kategori']) {
																											echo "selected";
																										} ?>><?php echo $dd['nama'] ?></option>
					<?php
							}
					?>
					</select>
				</div>

				<div class="form-group col-md-6">
					<label class="font-weight-bold">Satuan</label>
					<input autocomplete="off" type="text" name="satuan" required class="form-control" value="<?php echo $data['satuan']; ?>" />
				</div>

				<div class="form-group col-md-6">
					<label class="font-weight-bold">Harga Jual</label>
					<input autocomplete="off" type="text" name="harga_beli" required class="form-control" value="<?php echo $data['harga_beli']; ?>" />
				</div>

				<div class="form-group col-md-6">
					<label class="font-weight-bold">Harga Jual</label>
					<input autocomplete="off" type="text" name="harga_jual" required class="form-control" value="<?php echo $data['harga_jual']; ?>" />
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
	$tgl_exp = $_POST['tgl_exp'];
	$kd_kategori = $_POST['kd_kategori'];
	$harga_beli = $_POST['harga_beli'];
	$harga_jual = $_POST['harga_jual'];
	$satuan = $_POST['satuan'];

	mysqli_query($konek, "UPDATE produk SET kd_kategori = '$kd_kategori', nama = '$nama', harga_jual = '$harga_jual', satuan = '$satuan' WHERE kd = '$kd'");
	mysqli_query($konek, "UPDATE detail_produk SET tgl_exp = '$tgl_exp', harga_beli = '$harga_beli' WHERE kd_produk = '$kd'");
	echo mysqli_error($konek);
	echo '<script>alert("Edit Produk Berhasil!");</script>';
	echo '<script>window.location.href="index.php?page=dataproduk"</script>';
}
?>