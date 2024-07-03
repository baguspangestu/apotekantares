<?php
include("../config/koneksi.php");

$bulan = $_GET['bulan'];
$produk = $_GET['produk'];
$date = date("d-m-Y");
$hari = date("D");

if ($bulan == "01") {
  $bln = "Januari";
} else if ($bulan == "02") {
  $bln == "Februari";
} else if ($bulan == "03") {
  $bln = "Maret";
} else if ($bulan == "04") {
  $bln = "April";
} else if ($bulan == "05") {
  $bln = "Mei";
} else if ($bulan == "06") {
  $bln = "Juni";
} else if ($bulan == "07") {
  $bln = "Juli";
} else if ($bulan == "08") {
  $bln = "Agustus";
} else if ($bulan == "09") {
  $bln = "September";
} else if ($bulan == "10") {
  $bln = "Oktober";
} else if ($bulan == "11") {
  $bln = "November";
} else if ($bulan == "12") {
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
        <th style="padding:20px 0;text-transform:uppercase">LAPORAN PENJUALAN OBAT PERJENIS OBAT BULAN <?php echo $bln; ?></th>
      </tr>
    </table>
  </center>


  <?php
  $sqlq = mysqli_query($konek, "SELECT * FROM produk a LEFT JOIN detail_produk b ON a.kd_produk=b.kd_produk LEFT JOIN kategori c ON a.kd_kategori=c.kd_kategori WHERE a.kd_produk='$produk'");
  $dataq = mysqli_fetch_assoc($sqlq);
  ?>
  <div class="container">
    <div class="row">
      <div class="col-md-6">
        <td>
          <table style="padding-left: 10%;">
            <tr>
              <th>
              <td>Kode Produk</td>
              <td>:</td>
              <td><?php echo $produk; ?></td>
              </th>
            </tr>
            <tr>
              <th>
              <td>Nama Produk</td>
              <td>:</td>
              <td><?php echo $dataq['nama_produk']; ?></td>
              </th>
            </tr>
            <tr>
              <th>
              <td>Kategori</td>
              <td>:</td>
              <td><?php echo $dataq['nama_kategori']; ?></td>
              </th>
            </tr>
          </table>
        </td>
      </div>
    </div>
  </div>
  <center>
    <div class="table-responsive">
      <table width="80%" border="1" cellpadding="5">
        <thead>
          <tr align="center">
            <td><b>No</b></td>
            <td><b>No Faktur</b></td>
            <td><b>Tanggal Faktur</b></td>
            <td><b>Jumlah</b></td>
            <td><b>Total Penjualan</b></td>
          </tr>
        </thead>
        <?php
        $no = 0;
        $total = 0;
        $totalbiaya = 0;
        include("../config/koneksi.php");
        $query = mysqli_query($konek, "SELECT * FROM transaksi_jual a LEFT JOIN detail_jual b ON a.id_tr=b.id_tr LEFT JOIN produk d ON a.kd_produk=d.kd_produk LEFT JOIN detail_produk e ON d.kd_produk=e.kd_produk WHERE month(a.tgl_jual) = '$bulan' AND a.kd_produk= '$produk' GROUP BY a.no_faktur");
        ?>
        <tbody>
          <?php
          while ($data = mysqli_fetch_assoc($query)) {
            $no++;
          ?>
            <tr>
              <td align="center"><?php echo $no; ?></td>
              <td align="center"><?php echo $data['no_faktur']; ?></td>
              <td align="center"><?php echo $data['tgl_jual']; ?></td>
              <?php
              $qwe = mysqli_query($konek, "SELECT SUM(total_jual) as ttl, COUNT(*) as jumlah FROM transaksi_jual WHERE no_faktur='$data[no_faktur]' AND month(tgl_jual) = '$bulan' GROUP BY no_faktur ASC");
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
          <tr>
            <td colspan="4" align="center"> Total Jumlah </td>
            <td align="right"><?php echo "Rp " . number_format($total, 0, ',', '.'); ?></td>
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