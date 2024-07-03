<div class="d-sm-flex align-items-center justify-content-between mb-4">
	<h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-print"></i> Data Laporan Pembelian Produk</h1>
</div>


<div class="card shadow mb-4">
	<!-- /.card-header -->
	<div class="card-header py-3">
		<h6 class="m-0 font-weight-bold text-primary"><i class="fa fa-table"></i> Pilih Bulan Pembelian</h6>
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
			<h6 class="m-0 font-weight-bold text-primary"><i class="fa fa-table"></i> Daftar Data Pembelian Produk Bulan </h6>

			<a target="_blank" href="cetak_bulan.php?bulan=<?php echo $bulan; ?>" class="btn btn-primary"> <i class="fa fa-print"></i> Cetak Data </a>
		</div>

		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
					<thead class="bg-primary text-white">
						<tr align="center">
							<th width="5%">No</th>
							<th>No Transaksi</th>
							<th>Tanggal Transaksi</th>
							<th>Suplier</th>
							<th>Jumlah Item</th>
							<th>Total Jumlah</th>
						</tr>
					</thead>
					<?php
					$no = 0;
					$total = 0;
					$query = mysqli_query($konek, "SELECT * FROM transaksi_beli a LEFT JOIN detail_transaksi b ON a.id_tr=b.id_tr LEFT JOIN suplier c ON a.kd_suplier=c.kd_suplier WHERE month(a.tgl_transaksi) = '$bulan'");
					?>
					<tbody>
						<?php
						while ($data = mysqli_fetch_assoc($query)) {
							$no++;
							$totals = $data['total_transaksi'];
						?>
							<tr>
								<td align="center"><?php echo $no; ?></td>
								<td align="center"><?php echo $data['no_transaksi']; ?></td>
								<td align="center"><?php echo $data['tgl_transaksi']; ?></td>
								<td><?php echo $data['nama_suplier']; ?></td>
								<td align="center"><?php echo $data['jml_beli']; ?></td>
								<td align="right"><?php echo "Rp " . number_format($data['total_transaksi'], 0, ',', '.'); ?></td>
							</tr>
						<?php
						}
						?>
					</tbody>
				</table>
				<?php
				$qwe = mysqli_query($konek, "SELECT SUM(total_transaksi) as ttl FROM transaksi_beli a LEFT JOIN detail_transaksi b ON a.id_tr=b.id_tr LEFT JOIN suplier c ON a.kd_suplier=c.kd_suplier WHERE month(a.tgl_transaksi) = '$bulan'");
				$dwe = mysqli_fetch_assoc($qwe);
				$total = $dwe['ttl'];
				?>
				<div class="alert alert-info mt-4">
					Total biaya pembelian adalah sebesar <b><?php echo "Rp " . number_format($total, 0, ',', '.'); ?></b>
				</div>
			</div>
		</div>
	</div>
<?php
}
?>