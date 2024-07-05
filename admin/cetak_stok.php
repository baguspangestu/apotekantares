<?php
include("../config/helpers.php");
include("../config/koneksi.php");

session_start();

$date = date("d-m-Y");
?>

<body onload="window.print();">
  <style>
    table.table {
      border-collapse: collapse;
    }

    table.table,
    table.table th,
    table.table td {
      border: 1px solid black;
    }
  </style>
  <center>
    <table width="80%" celpadding="8">
      <tr align="center">
        <th>APOTEK ANTARES PRINGSEWU</th>
      </tr>
      <tr align="center">
        <td style="border-bottom:2px solid black;padding:0 0 5px 0;"><i>Jl. Pringadi No.128, RT.001, Pringsewu Utara,
            Kec. Pringsewu, Kabupaten Pringsewu, Lampung 35373</i></td>
      </tr>
      <tr align="center">
        <th style="padding:20px 0">CETAK LAPORAN STOK PTODUK</th>
      </tr>
    </table>


    <div class="table-responsive">
      <table width="80%" border="1" cellpadding="5" class="table">
        <thead>
          <tr align="center">
            <td><b>No</b></td>
            <td><b>Kode Produk</b></td>
            <td><b>Nama Produk</b></td>
            <td><b>Nama Kategori</b></td>
            <td><b>Stok</b></td>
          </tr>
        </thead>
        <?php
        $no = 0;
        $total = 0;
        $query = mysqli_query($konek, "SELECT a.kd, a.nama, c.nama as kategori, b.stok FROM produk a LEFT JOIN detail_produk b ON a.kd=b.kd_produk LEFT JOIN kategori c ON a.kd_kategori=c.kd WHERE a.kd=b.kd_produk GROUP BY a.kd");
        ?>
        <tbody>
          <?php
          while ($data = mysqli_fetch_assoc($query)) {
            $no++;
          ?>
            <tr>
              <td align="center"><?php echo $no; ?></td>
              <td align="center"><?php echo $data['kd']; ?></td>
              <td><?php echo $data['nama']; ?></td>
              <td><?php echo $data['kategori']; ?></td>
              <td align="center"><?php echo $data['stok']; ?></td>
            </tr>
          <?php
          }
          ?>
        </tbody>
      </table>
      <div style="width: 80%;">
        <div style="display: flex; justify-content:space-between; align-items: flex-end">
          <div style="text-align:center">
            <p>ADMIN</p>
            <br />
            <br />
            <p>
              <?php echo $_SESSION['nama']; ?>
            </p>
          </div>
          <div style="text-align:center">
            <p>PRINGSEWU, <?php echo $date; ?><br />PIMPINAN</p>
            <br />
            <br />
            <p>MUHAMMAD ARIF PRADANA</p>
          </div>
        </div>
      </div>
  </center>
</body>