<?php
$kd = $_GET['id'];
$query  = mysqli_query($konek, "SELECT * FROM suplier WHERE kd = '$kd'");
$data = mysqli_fetch_assoc($query);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $kd_produk = $_POST['kd_produk'];

  mysqli_query($konek, "INSERT INTO detail_suplier VALUES('','$kd','$kd_produk')");

  echo mysqli_error($konek);
  echo '<script>alert("Berhasil Menambahkan Produk!");</script>';
  echo "<script>window.location.href='index.php?page=detailsuplier&id=$kd'</script>";
}
?>

<div class="modal fade" id="listProdukModal" tabindex="-1" role="dialog" aria-labelledby="listProdukModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="listProdukModalLabel">Pilih Produk</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-inline justify-content-end">
          <input type="search" id="cariProduk" class="form-control mb-2" placeholder="Cari produk ..." />
        </div>
        <div class="table-responsive">
          <table class="table table-sm table-bordered table-striped table-hover" width="100%" cellspacing="0">
            <thead class="bg-primary text-white">
              <tr class="text-center">
                <th>No</th>
                <th>Kode</th>
                <th>Nama</th>
                <th>Tgl Exp</th>
                <th>Satuan</th>
                <th>Harga Beli</th>
                <th>Stok</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody id="tbodyListProduk"></tbody>
          </table>
        </div>
        <nav aria-label="Page navigation example">
          <ul id="pagingListProduk" class="pagination justify-content-end"></ul>
        </nav>
      </div>
    </div>
  </div>
</div>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-hospital-user"></i> Data Suplier</h1>
</div>

<div class="card shadow mb-4">
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary"><i class="fa fa-user"></i> Profil Suplier</h6>
  </div>
  <div class="card-body">
    <div class="row">
      <div class="col-md-6">
        <table class="table table-bordered font-weight-bold">
          <tr>
            <td width="25%">Kode Suplier</td>
            <td><?php echo $data['kd'] ?></td>
          </tr>
          <tr>
            <td width="25%">Nama Suplier</td>
            <td><?php echo $data['nama'] ?></td>
          </tr>
        </table>
      </div>
      <div class="col-md-6">
        <table class="table table-bordered font-weight-bold">
          <tr>
            <td width="25%">No Telp.</td>
            <td><?php echo $data['no_tlp'] ?></td>
          </tr>
          <tr>
            <td width="25%">Email</td>
            <td><?php echo $data['email'] ?></td>
          </tr>
        </table>
      </div>
    </div>
    <table class="table table-bordered font-weight-bold">
      <tr>
        <td width="12.4%">Alamat</td>
        <td><?php echo $data['alamat'] ?></td>
      </tr>
    </table>
  </div>
</div>

<form id="formTambahProduk" method="POST"><input type="hidden" id="kdProduk" name="kd_produk" /></form>
<div class="card shadow mb-4">
  <div class="card-header py-3 d-flex justify-content-between align-items-center">
    <h6 class="m-0 font-weight-bold text-primary"><i class="fa fa-table"></i> Data Produk</h6>
    <div id="listProdukButton" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Tambah Produk</div>
  </div>

  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead class="bg-primary text-white">
          <tr align="center">
            <th width="5%">No</th>
            <th>Kode Produk</th>
            <th>Nama Produk</th>
            <th>Tanggal Exp</th>
            <th>Kategori</th>
            <th>Satuan</th>
            <th>Harga Beli</th>
            <th>Stok</th>
            <th width="15%">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $no = 0;
          include("../config/koneksi.php");
          $query = "SELECT b.kd, b.nama, c.tgl_exp, d.nama as kategori, b.satuan, c.harga_beli, b.harga_jual, c.stok, a.kd_produk
                    FROM detail_suplier a
										LEFT JOIN produk b ON a.kd_produk = b.kd
										LEFT JOIN detail_produk c ON b.kd = c.kd_produk
										LEFT JOIN kategori d ON b.kd_kategori = d.kd
										WHERE a.kd_suplier = '$kd' ORDER BY b.nama ASC";
          $result  = mysqli_query($konek, $query);
          while ($dd = mysqli_fetch_assoc($result)) {
          ?>
          <tr class="text-center">
            <td><?php echo ++$no; ?></td>
            <td><?php echo $dd['kd']; ?></td>
            <td align="left"><?php echo $dd['nama']; ?></td>
            <td><?php echo $dd['tgl_exp']; ?></td>
            <td align="left"><?php echo $dd['kategori']; ?></td>
            <td><?php echo $dd['satuan']; ?></td>
            <td align="right"><?php echo "Rp " . number_format($dd['harga_beli'], 0, ',', '.'); ?></td>
            <td><?php echo $dd['stok']; ?></td>
            <td>
              <div class="btn-group" role="group">
                <a data-toggle="tooltip" data-placement="bottom" title="Hapus Data"
                  href="hapusdetailsuplier.php?id=<?php echo $data['kd']; ?>&kd_produk=<?php echo $dd['kd_produk']; ?>"
                  onclick="return confirm ('Apakah anda yakin untuk meghapus data ini')"
                  class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
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

<script src="../assets/js/helpers.js"></script>
<script src="../assets/vendor/jquery/jquery.min.js"></script>
<script>
const listProduk = [];

function formatTanggal(tanggal) {
  let bagian = tanggal.split('-');
  return bagian[2] + '-' + bagian[1] + '-' + bagian[0];
}

function getListProduk(q = '', p = 1) {
  $.ajax({
    url: 'data_produk.php',
    method: 'GET',
    data: {
      q,
      p
    },
    dataType: 'json',
    success: function(res) {
      listProduk.length = 0;
      $('#tbodyListProduk').empty();
      $('#pagingListProduk').empty();

      if (res.data.length) {
        res.data.forEach((e, index) => {
          listProduk.push(e);

          const row = $('<tr></tr>').appendTo('#tbodyListProduk');
          row.addClass("text-center");

          const cell = [];
          for (let i = 0; i < 8; i++) {
            const c = $('<td></td>').appendTo(row);
            c.addClass("align-middle");

            if (i === 2) c.addClass("text-left");
            if (i === 5) c.addClass("text-right");

            cell.push(c);
          }

          cell[0].text(index + 1);
          cell[1].text(e.kd);
          cell[2].text(e.nama);
          cell[3].text(formatTanggal(e.tgl_exp));
          cell[4].text(e.satuan);
          cell[5].text(formatRupiah(e.harga));
          cell[6].text(e.stok);
          cell[7].html(`<span class="btn btn-success btn-sm" onClick="pilihProduk('${e.kd}')">Pilih</span>`);
        });
      } else {
        const row = $('<tr></tr>').appendTo('#tbodyListProduk');
        row.addClass("text-center");
        const cell1 = $('<td></td>').appendTo(row);
        cell1.attr('colspan', 8);
        cell1.text("Produk tidak ditemukan");
      }

      $('#pagingListProduk').append(`
        <li class="page-item ${res.page === 1 ? 'disabled' : ''}">
          <a class="page-link" href="#" tabindex="-1" onclick="getListProduk('${q}', ${res.page - 1})">Previous</a>
        </li>
      `);

      let startPage = Math.max(res.page - 1, 1);
      let endPage = Math.min(res.page + 1, res.pages);
      for (let i = startPage; i <= endPage; i++) {
        $('#pagingListProduk').append(`
          <li class="page-item ${i === res.page ? 'active' : ''}">
            <a class="page-link" href="#" onclick="getListProduk('${q}', ${i})">${i}</a>
          </li>
        `);
      }

      $('#pagingListProduk').append(`
        <li class="page-item ${res.page >= res.pages ? 'disabled' : ''}">
          <a class="page-link" href="#" onclick="getListProduk('${q}', ${res.page + 1})">Next</a>
        </li>
      `);
    }
  });
}

function pilihProduk(kd) {
  $('#listProdukModal').modal('hide');
  selectedProduct = listProduk.find(e => e.kd === kd) || null;

  $("#kdProduk").val(selectedProduct.kd);
  $('#formTambahProduk').submit();
}

$('#listProdukButton').click(function() {
  $('#listProdukModal').modal('show');
  getListProduk();
});

$('#cariProduk').on('input', function() {
  getListProduk($(this).val());
});
</script>