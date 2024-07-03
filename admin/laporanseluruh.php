<div class="d-sm-flex align-items-center justify-content-between mb-4">
	<h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-print"></i> Data Laporan Penjualan Seluruh Produk</h1>
</div>


<div class="card shadow mb-4">
	<!-- /.card-header -->
	<div class="card-header py-3">
		<h6 class="m-0 font-weight-bold text-primary"><i class="fa fa-table"></i> Pilih Bulan Penjualan</h6>
	</div>

	<div class="card-body">
		<form role="form" action="" method="post">
			<div class="input-group">
				<select class="custom-select" name="bulan" required>
					<option value="" selected>--Pilih Bulan--</option>
					<option value="01">Januari</option>
					<option value="02">Februari</option>
					<option value="03">Maret</option>
					<option value="04">April</option>
					<option value="05">Mei</option>
					<option value="06">Juni</option>
					<option value="07">Juli</option>
					<option value="08">Agustus</option>
					<option value="09">September</option>
					<option value="10">Oktober</option>
					<option value="11">November</option>
					<option value="12">Desember</option>
				</select>
				<div class="input-group-append">
					<button class="btn btn-primary" type="submit" name="check">Check Data</button>
				</div>
			</div>
		</form>
	</div>
</div>

<?php
if (isset($_POST['check'])) {
	$bulan = $_POST['bulan'];
?>

	<div class="card shadow mb-4">
		<!-- /.card-header -->
		<div class="card-header d-sm-flex align-items-center justify-content-between py-3">
			<h6 class="m-0 font-weight-bold text-primary"><i class="fa fa-table"></i> Daftar Data Penjualan Seluruh Produk </h6>

			<a target="_blank" href="cetak_seluruh.php?bulan=<?php echo $bulan; ?>" class="btn btn-primary"> <i class="fa fa-print"></i> Cetak Data </a>
		</div>

		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
					<thead class="bg-primary text-white">
						<tr align="center">
							<th width="5%">No</th>
							<th>Kode Produk</th>
							<th>Nama Produk</th>
							<th>Nama Kategori</th>
							<th>Harga Jual</th>
							<th>Jumlah Item</th>
							<th>Total Penjualan</th>
						</tr>
					</thead>
					<?php
					$no = 0;
					$total = 0;
					include("../config/koneksi.php");
					$query = mysqli_query($konek, "SELECT * FROM transaksi_jual a LEFT JOIN detail_jual b ON a.id_tr=b.id_tr LEFT JOIN produk d ON a.kd_produk=d.kd_produk LEFT JOIN kategori f ON d.kd_kategori=f.kd_kategori WHERE month(a.tgl_jual) = '$bulan' GROUP BY a.kd_produk");
					?>
					<tbody>
						<?php
						while ($data = mysqli_fetch_assoc($query)) {
							$no++;
						?>
							<tr>
								<td align="center"><?php echo $no; ?></td>
								<td align="center"><?php echo $data['kd_produk']; ?></td>
								<td><?php echo $data['nama_produk']; ?></td>
								<td align="center"><?php echo $data['nama_kategori']; ?></td>
								<td align="right"><?php echo "Rp " . number_format($data['harga_jual'], 0, ',', '.'); ?></td>
								<?php
								$qwe = mysqli_query($konek, "SELECT SUM(total_jual) as ttl, COUNT(*) as jumlah FROM transaksi_jual WHERE kd_produk='$data[kd_produk]' AND month(tgl_jual) = '$bulan'");
								$dwe = mysqli_fetch_assoc($qwe);
								$total = $total + $dwe['ttl'];
								?>
								<td align="center"><?php echo $dwe['jumlah']; ?></td>
								<td align="right"><?php echo "Rp " . number_format($dwe['ttl'], 0, ',', '.'); ?></td>
							</tr>
						<?php
						}
						echo mysqli_error($konek);
						?>
					</tbody>
				</table>
				<div class="alert alert-info mt-4">
					Total biaya seluruh penjualan adalah sebesar <b><?php echo "Rp " . number_format($total, 0, ',', '.'); ?></b>
				</div>
			</div>
		</div>
	</div>

<?php
}
?>