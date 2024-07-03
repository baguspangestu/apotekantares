<div class="d-sm-flex align-items-center justify-content-between mb-4">
	<h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-dolly-flatbed"></i> Data Pembelian</h1>

	<a href="?page=inputbeli" class="btn btn-success"> <i class="fa fa-plus"></i> Tambah Data </a>
</div>

<?php
error_reporting(0);
$qq1 = mysqli_query($konek, "SELECT * FROM keranjang_beli");
$jml1 = mysqli_num_rows($qq1);
if ($jml1 < 1) {
} else {
?>
	<div class="card shadow mb-4">
		<!-- /.card-header -->
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-primary"><i class="fa fa-table"></i> Daftar Data Keranjang</h6>
		</div>

		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered" width="100%" cellspacing="0">
					<thead class="bg-primary text-white">
						<tr align="center">
							<th width="5%">No</th>
							<th>Kode Keranjang</th>
							<th>Kode Suplier</th>
							<th>Kode Produk</th>
							<th>Tanggal Transaksi</th>
							<th>Jumlah Beli</th>
							<th>Total Biaya</th>
							<th>Aksi</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = 0;
						$query  = mysqli_query($konek, "SELECT * FROM keranjang_beli 
					");
						while ($data = mysqli_fetch_assoc($query)) {
							$no++;
						?>
							<tr align="center">
								<td><?php echo $no; ?></td>
								<td><?php echo $data['idbeli']; ?></td>
								<td><?php echo $data['kd_suplier']; ?></td>
								<td><?php echo $data['kd_produk']; ?></td>
								<td><?php echo $data['tgl_transaksi']; ?></td>
								<td><?php echo $data['jml_beli']; ?></td>
								<td align="right"><?php echo "Rp " . number_format($data['total_transaksi'], 0, ',', '.'); ?></td>
								<td>
									<a data-toggle="tooltip" data-placement="bottom" title="Hapus Data" href="hapus.php?id=<?php echo $data['idbeli']; ?>" onclick="return confirm ('Apakah anda yakin untuk meghapus data ini')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
								</td>
							</tr>
						<?php
						}
						echo mysqli_error($konek);
						?>
					</tbody>
				</table>
			</div>
		</div>
		<div class="card-footer text-right">

			<a href="selesai.php" class="btn btn-success"><i class="fa fa-check"></i> Selesai Belanja</a>
		</div>
	</div>
<?php
}
?>


<div class="card shadow mb-4">
	<!-- /.card-header -->
	<div class="card-header py-3">
		<h6 class="m-0 font-weight-bold text-primary"><i class="fa fa-table"></i> Daftar Data Pembelian</h6>
	</div>

	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
				<thead class="bg-primary text-white">
					<tr align="center">
						<th width="5%">No</th>
						<th>No Transaksi</th>
						<th>Tanggal Transaksi</th>
						<th>Nama Suplier</th>
						<th>Nama Produk</th>
						<th>Tanggal EXP</th>
						<th>Harga</th>
						<th>Jumlah Beli</th>
						<th>Total Biaya</th>
						<th>Aksi</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$no = 0;
					$query  = mysqli_query($konek, "SELECT * FROM transaksi_beli a LEFT JOIN detail_transaksi b ON a.id_tr=b.id_tr LEFT JOIN detail_produk c ON b.kd_produk=c.kd_produk LEFT JOIN produk d ON b.kd_produk=d.kd_produk AND c.kd_produk=d.kd_produk LEFT JOIN suplier e ON a.kd_suplier=e.kd_suplier ORDER BY a.no_transaksi ASC");
					while ($data = mysqli_fetch_assoc($query)) {
						$no++;
					?>
						<tr align="center">
							<td><?php echo $no; ?></td>
							<td><?php echo $data['no_transaksi']; ?></td>
							<td><?php echo $data['tgl_transaksi']; ?></td>
							<td><?php echo $data['nama_suplier']; ?></td>
							<td><?php echo $data['nama_produk']; ?></td>
							<td><?php echo $data['tgl_exp']; ?></td>
							<td align="right"><?php echo "Rp " . number_format($data['harga_modal'], 0, ',', '.'); ?></td>
							<td><?php echo $data['jml_beli']; ?></td>
							<td align="right"><?php echo "Rp " . number_format($data['total_transaksi'], 0, ',', '.'); ?></td>
							<td>
								<div class="btn-group" role="group">
									<a target="_blank" data-toggle="tooltip" data-placement="bottom" title="Cetak Data" href="cetak_beli.php?id=<?php echo $data['no_transaksi']; ?>" class="btn btn-primary btn-sm"><i class="fa fa-print"></i></a>
									<a data-toggle="tooltip" data-placement="bottom" title="Hapus Data" href="hapusbeli.php?id=<?php echo $data['no_transaksi']; ?>&sub=<?php echo $data['sub_transaksi']; ?>" onclick="return confirm ('Apakah anda yakin untuk meghapus data ini')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
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