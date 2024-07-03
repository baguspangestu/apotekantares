<?php
	include("../config/koneksi.php");
	
	$bulan = $_GET['bulan'];
	$date = date("d-m-Y");
	$hari = date("D");
	
if($bulan == "01")
{
	$bln = "Januari";
}
else if($bulan == "02")
{
	$bln == "Februari";
}
else if($bulan == "03")
{
	$bln = "Maret";
}
else if($bulan == "04")
{
	$bln = "April";
}
else if($bulan == "05")
{
	$bln = "Mei";
}
else if($bulan == "06")
{
	$bln = "Juni";
}
else if($bulan == "07")
{
	$bln = "Juli";
}
else if($bulan == "08")
{
	$bln = "Agustus";
}
else if($bulan == "09")
{
	$bln = "September";
}
else if($bulan == "10")
{
	$bln = "Oktober";
}
else if($bulan == "11")
{
	$bln = "November";
}
else if($bulan == "12")
{
	$bln = "Desember";
}
		
?>
<body onload="window.print();">
<center>
<table width="80%" celpadding="8">
	<tr align="center">
		<th>APOTEK SOURCECODEKU.COM</th>
	</tr>
	<tr align="center">
		<td style="border-bottom:2px solid #333;padding:0 0 5px 0;"><i>Jl. Raya Lubuk Begalung, Kota Padang, Sumatera Barat, Indonesia</i></td>
	</tr>
	<tr align="center">
		<th style="padding:20px 0;text-transform:uppercase">LAPORAN PEMBELIAN OBAT BULAN <?php echo $bln; ?></th>
	</tr>
</table>

			<div class="table-responsive">
              <table width="80%" border="1" cellpadding="5">
                  <thead>
                    <tr align="center">
                      <td><b>No</b></td>
                      <td><b>No Transaksi</b></td>
                      <td><b>Tanggal Transaksi</b></td>
                      <td><b>Suplier</b></td>
                      <td><b>Jumlah Item</b></td>
                      <td><b>Total Jumlah</b></td>
                    </tr>
                  </thead>
               <?php
                    $no = 0;
                    $total = 0;
                    $query=mysqli_query($konek,"SELECT * FROM transaksi_beli a LEFT JOIN detail_transaksi b ON a.id_tr=b.id_tr LEFT JOIN suplier c ON a.kd_suplier=c.kd_suplier WHERE month(a.tgl_transaksi) = '$bulan'");
                    ?>
                 <tbody>
                  <?php
                    while($data=mysqli_fetch_assoc($query)){
                    $no++;
                    $totals = $data['total_transaksi'];      
                  ?>
                  <tr>
                    <td align="center"><?php echo $no; ?></td>
                    <td align="center"><?php echo $data['no_transaksi']; ?></td>
                    <td align="center"><?php echo $data['tgl_transaksi']; ?></td>
                    <td><?php echo $data['nama_suplier']; ?></td>
                    <td align="center"><?php echo $data['jml_beli']; ?></td>
                    <td align="right"><?php echo "Rp ".number_format($data['total_transaksi'],0,',','.'); ?></td>
                  </tr>
                  <?php
                    } 
					  $qwe = mysqli_query($konek,"SELECT SUM(total_transaksi) as ttl FROM transaksi_beli a LEFT JOIN detail_transaksi b ON a.id_tr=b.id_tr LEFT JOIN suplier c ON a.kd_suplier=c.kd_suplier WHERE month(a.tgl_transaksi) = '$bulan'");
					  $dwe=mysqli_fetch_assoc($qwe);
					  $total=$dwe['ttl'];
					?>
                  <tr align="center">
                    <td colspan="5" align="center"> Total Jumlah </td>
                    <td align="right"><?php echo "Rp ".number_format($total,0,',','.'); ?></td>
                  </tr>
                </tbody>
              </table>
              <table style="padding-top:10px" width="80%" cellpadding="5">
				<tr>
          <td align="right" style="padding-right:60px;">PADANG, <?php echo $date; ?></td>
        </tr>
        <tr>
          <td align="right" style="padding-right:95px;">PIMPINAN</td>
        </tr>
        <tr>
          <td align="right" style="padding-top:50px;">APOTEK SOURCECODEKU.COM</td>
        </tr>
			 </table>
			</div>
</center>			  
</body>			  