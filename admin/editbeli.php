<?php
$kd = $_GET['id'];

$transaksiAwalQuery =  "SELECT kd, tanggal, kd_suplier, total FROM transaksi_beli WHERE kd='$kd'";
$transaksiAwalResult = mysqli_query($konek, $transaksiAwalQuery);
$transaksiAwalData = mysqli_fetch_assoc($transaksiAwalResult);

$detailTransaksiAwalQuery =  "SELECT b.kd, b.nama, c.tgl_exp, b.satuan, a.harga, c.stok, a.jumlah
                              FROM detail_transaksi_beli a 
                              LEFT JOIN produk b ON a.kd_produk = b.kd 
                              LEFT JOIN detail_produk c ON b.kd = c.kd_produk
                              WHERE a.kd_transaksi = '$kd'";

$detailTransaksiAwalResult = mysqli_query($konek, $detailTransaksiAwalQuery);

$detailTransaksiAwalData = array();
while ($data = mysqli_fetch_assoc($detailTransaksiAwalResult)) {
  $data['stok'] -= $data['jumlah'];
  $detailTransaksiAwalData[] = $data;
}

$jsonListPembelianAwal = json_encode($detailTransaksiAwalData);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $kdSuplier = $transaksiAwalData['kd_suplier'];
  $tanggal = !empty($_POST['tanggal']) ? $_POST['tanggal'] : date('Y-m-d');
  $total = !empty($_POST['subTotal']) ? $_POST['subTotal'] : 0;

  $listPembelianAwal = json_decode($jsonListPembelianAwal, true);
  $listPembelianEdit = json_decode($_POST['dataListPembelian'], true);

  $listPembelianKeys = array_column($listPembelianEdit, null, 'kd');

  foreach ($listPembelianAwal as &$itemAwal) {
    $key = $itemAwal['kd'];

    if (!isset($listPembelianKeys[$key])) {
      $itemAwal['jumlah'] = 0;
    }
  }

  $listPembelian = array_merge($listPembelianAwal, $listPembelianEdit);

  usort($listPembelian, function ($a, $b) {
    return $a['kd'] <=> $b['kd'];
  });

  $konek->begin_transaction();

  try {
    $queryTransaksi = "UPDATE transaksi_beli SET kd_suplier = ?, tanggal = ?, total = ? WHERE kd = ?";
    $stmtTransaksi = $konek->prepare($queryTransaksi);
    $stmtTransaksi->bind_param("ssis", $kdSuplier, $tanggal, $total, $kd);
    $stmtTransaksi->execute();
    $stmtTransaksi->close();

    $queryUpdateStok = "UPDATE detail_produk SET stok = ? WHERE kd_produk = ?";
    $queryDetailUpdate = "INSERT INTO detail_transaksi_beli (kd_transaksi, kd_produk, harga, jumlah) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE harga = VALUES(harga), jumlah = VALUES(jumlah)";
    $queryDetailDelete = "DELETE FROM detail_transaksi_beli WHERE kd_transaksi = ? AND kd_produk = ?";

    foreach ($listPembelian as $data) {
      $kdProduk = $data['kd'];
      $harga = $data['harga'];
      $jumlah = $data['jumlah'];
      $stok = $data['stok'];

      $stokBaru = $stok + $jumlah;

      $stmtUpdateStok = $konek->prepare($queryUpdateStok);
      $stmtUpdateStok->bind_param("is", $stokBaru, $kdProduk);
      $stmtUpdateStok->execute();
      $stmtUpdateStok->close();

      if ($jumlah == 0) {
        $stmtDetailDelete = $konek->prepare($queryDetailDelete);
        $stmtDetailDelete->bind_param("ss", $kd, $kdProduk);
        $stmtDetailDelete->execute();
        $stmtDetailDelete->close();
      } else {
        $stmtDetailUpdate = $konek->prepare($queryDetailUpdate);
        $stmtDetailUpdate->bind_param("ssii", $kd, $kdProduk, $harga, $jumlah);
        $stmtDetailUpdate->execute();
        $stmtDetailUpdate->close();
      }
    }

    $konek->commit();

    echo '<script>alert("Berhasil Mengubah Transaksi!");</script>';
    echo '<script>window.location.href="index.php?page=databeli"</script>';
  } catch (Exception $e) {
    $konek->rollback();

    echo "Eksekusi gagal: " . $e->getMessage();
  }

  $konek->close();
}
?>

<div class="modal fade" id="listProdukModal" tabindex="-1" role="dialog" aria-labelledby="listProdukModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="listProdukModalLabel">Pilih</h5>
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
                <th>Harga </th>
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
  <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-dolly-flatbed"></i> Data Pembelian</h1>

  <a href="?page=databeli" class="btn btn-secondary btn-icon-split"><span class="icon text-white-50"><i
        class="fas fa-arrow-left"></i></span>
    <span class="text">Kembali</span>
  </a>
</div>

<div class="card shadow mb-4">
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-fw fa-plus"></i> Tambah Data Pembelian</h6>
  </div>

  <form id="transaksiForm" method="POST">
    <div class="card-body">
      <div class="row">
        <div class="form-group col-md-6">
          <label class="font-weight-bold">Nama Suplier</label>
          <select name="kd_suplier" id="kd_suplier" class="form-control" disabled required>
            <option value="">--Pilih Suplier--</option>
            <?php
            $qq = mysqli_query($konek, "SELECT * FROM suplier ORDER BY nama ASC");
            while ($dd = mysqli_fetch_assoc($qq)) {
            ?>
            <option value="<?php echo $dd['kd']; ?>"
              <?php echo $transaksiAwalData['kd_suplier'] == $dd['kd'] ? 'selected' : '' ?>>
              <?php echo $dd['nama'] ?></option>
            <?php
            }
            ?>
          </select>
        </div>
        <div class="form-group col-md-6">
          <label class="font-weight-bold">Tanggal Transaksi</label>
          <input autocomplete="off" type="date" name="tanggal" value="<?php echo $transaksiAwalData['tanggal']; ?>"
            class="form-control" required />
        </div>
      </div>
      <hr />
      <div class="row">
        <div class="col-md-3">
          <h3>Tambah Pembelian</h3>
          <div class="form-group">
            <label class="font-weight-bold">Kode</label>
            <div class="d-flex">
              <span id="listProdukButton" class="btn btn-primary btn-block mr-2"><i class="fas fa-search"></i> Pilih
                Produk
              </span>
              <input autocomplete="off" type="text" id="tpKdInput" class="form-control" readonly />
            </div>
          </div>

          <div class="form-group">
            <label class="font-weight-bold">Nama</label>
            <input autocomplete="off" type="text" id="tpNamaInput" class="form-control" readonly />
          </div>

          <div class="form-group">
            <label class="font-weight-bold">Tgl Exp</label>
            <input autocomplete="off" type="date" id="tpTglExp" class="form-control" readonly />
          </div>

          <div class="form-group">
            <label class="font-weight-bold">Harga</label>
            <input autocomplete="off" type="text" id="tpHargaInput" class="form-control" readonly />
          </div>

          <div class="form-group">
            <label class="font-weight-bold">Jumlah</label>
            <div class="input-group">
              <input autocomplete="off" type="number" id="tpJumlahInput" class="form-control" />
              <div class="input-group-append">
                <span class="input-group-text" id="tpSatuan"></span>
              </div>
              <div class="invalid-feedback">Jumlah tidak valid!</div>
            </div>
          </div>

          <div class="form-group">
            <label class="font-weight-bold">Total</label>
            <input autocomplete="off" type="text" id="tpTotalInput" class="form-control" readonly />
          </div>

          <span id="tpSubmitButton" class="btn btn-info btn-block disabled">Tambahkan Pembelian</span>
        </div>

        <div class="col-md-9">
          <h3>Daftar Pembelian</h3>
          <input type="hidden" id="dataListPembelian" name="dataListPembelian" />
          <div class="table-responsive">
            <table class="table table-bordered table-striped" width="100%" cellspacing="0">
              <thead class="bg-primary text-white">
                <tr class="text-center">
                  <th>No</th>
                  <th>Kode</th>
                  <th>Nama</th>
                  <th>Tgl Exp</th>
                  <th>Satuan</th>
                  <th>Harga</th>
                  <th>Jumlah</th>
                  <th>Total</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody id="tbodyListPembelian"></tbody>
              <tfoot>
                <tr class="text-center bg-light font-weight-bold">
                  <td colspan="7">Total</td>
                  <input type="hidden" id="subTotalHidden" name="subTotal" />
                  <td id="subTotal" align="right"></td>
                  <td>-</td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="card-footer text-right">
      <span id="submit" class="btn btn-success disabled"><i class="fa fa-save"></i>
        Simpan
      </span>
      <span id="reset" class="btn btn-info"><i class="fa fa-sync-alt"></i> Reset</span>
    </div>
  </form>
</div>

<!-- REQUIRED JS SCRIPTS -->
<script src="../assets/js/helpers.js"></script>
<script src="../assets/vendor/jquery/jquery.min.js"></script>
<script>
const listProduk = [];
const listPembelianAwal = <?php echo $jsonListPembelianAwal ?>;
const listPembelian = <?php echo $jsonListPembelianAwal ?>;

let selectedProduct = null;
let subTotal = 0;
let grandTotal = 0;

function formatTanggal(tanggal) {
  let bagian = tanggal.split('-');
  return bagian[2] + '-' + bagian[1] + '-' + bagian[0];
}

function getListProduk(q = '', p = 1) {
  $.ajax({
    url: 'data_produk_suplier.php',
    method: 'GET',
    data: {
      kd_suplier: $("#kd_suplier").val(),
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

function resetTPForm() {
  $("#tpKdInput").val('');
  $("#tpNamaInput").val('');
  $("#tpTglExp").val('');
  $("#tpSatuan").text('');
  $("#tpHargaInput").val('');
  $("#tpJumlahInput").val('');
  $("#tpTotalInput").val('');
  $('#tpSubmitButton').addClass('disabled');
}

function pilihProduk(kd) {
  $('#listProdukModal').modal('hide');
  selectedProduct = listProduk.find(e => e.kd === kd) || null;

  $("#tpKdInput").val(selectedProduct?.kd ?? '-');
  $("#tpNamaInput").val(selectedProduct?.nama ?? '-');
  $("#tpTglExp").val(selectedProduct?.tgl_exp ?? '');
  $("#tpSatuan").text(selectedProduct?.satuan ?? '');
  $("#tpHargaInput").val(formatRupiah(selectedProduct?.harga ?? 0));
  $("#tpJumlahInput").val(selectedProduct?.harga ? 1 : 0);

  tpHitungTotal();
}

function tpHitungTotal() {
  const jumlah = parseInt($("#tpJumlahInput").val()) || 0;
  const total = jumlah * selectedProduct?.harga ?? 0;
  $("#tpTotalInput").val(formatRupiah(!isNaN(total) ? total : 0));

  if (jumlah >= 0) {
    $("#tpJumlahInput").removeClass("is-invalid");
  } else {
    $("#tpJumlahInput").addClass('is-invalid');
  }

  if (selectedProduct && jumlah >= 1) {
    $('#tpSubmitButton').removeClass('disabled');
  } else {
    $('#tpSubmitButton').addClass('disabled');
  }
}

function tambahkanPembelian() {
  if ($('#tpSubmitButton').hasClass('disabled')) return;

  const jumlah = parseInt($("#tpJumlahInput").val());
  const i = listPembelian.findIndex(item => item.kd === selectedProduct.kd);

  if (i !== -1) {
    listPembelian[i].jumlah = parseInt(listPembelian[i].jumlah) + jumlah;
  } else {
    listPembelian.push({
      ...selectedProduct,
      jumlah
    });
  }

  selectedProduct = null;
  resetTPForm();
  updateDaftarPembelian();
}

function updatePembelian(kd, jumlah) {
  const index = listPembelian.findIndex(item => item.kd === kd);
  if (index !== -1) listPembelian[index].jumlah = jumlah;

  updateDaftarPembelian();
}

function removePembelian(kd) {
  const confirmed = confirm("Apakah Anda yakin ingin menghapus data ini?")
  if (!confirmed) return;

  const index = listPembelian.findIndex(item => item.kd === kd);
  if (index !== -1) listPembelian.splice(index, 1);

  updateDaftarPembelian();
}

function updateDaftarPembelian() {
  $('#tbodyListPembelian').empty();
  subTotal = 0;

  $('#dataListPembelian').val(JSON.stringify(listPembelian));

  if (listPembelian.length) {
    $("#submit").removeClass('disabled');

    listPembelian.forEach((e, index) => {
      const total = e.harga * e.jumlah;

      const row = $('<tr></tr>').appendTo('#tbodyListPembelian');
      row.addClass("text-center");

      const cell = [];
      for (let i = 0; i < 9; i++) {
        const c = $('<td></td>').appendTo(row);
        c.addClass("align-middle");

        if (i === 2) {
          c.addClass("text-left");
        } else if (i === 5 || i === 7) {
          c.addClass("text-right");
        } else if (i === 6) {
          c.addClass("d-flex justify-content-between align-items-center");
        }

        cell.push(c);
      }

      cell[0].text(index + 1);
      cell[1].text(e.kd);
      cell[2].text(e.nama);
      cell[3].text(formatTanggal(e.tgl_exp));
      cell[4].text(e.satuan);
      cell[5].text(formatRupiah(e.harga));
      cell[6].html(`
          <span class="btn btn-outline-success btn-sm ${parseInt(e.jumlah) - 1 <= 0 ? 'disabled' : ''}" 
                onClick="${parseInt(e.jumlah) - 1 <= 0 ? '' : `updatePembelian('${e.kd}','${parseInt(e.jumlah) - 1}')`}">
              <i class="fas fa-chevron-left"></i>
          </span>
          ${e.jumlah}
          <span class="btn btn-outline-success btn-sm" 
                onClick="${`updatePembelian('${e.kd}','${parseInt(e.jumlah) + 1}')`}">
              <i class="fas fa-chevron-right"></i>
          </span>
        `);
      cell[7].text(formatRupiah(total));
      cell[8].html(`
            <span style="cursor: pointer" class="text-danger" onClick="removePembelian('${e.kd}')"><i class="fas fa-trash-alt"></i></span>
        `);

      subTotal += total;
    });
  } else {
    $("#submit").addClass('disabled');
    const tbody = $('#tbodyListPembelian');
    const row = $('<tr></tr>').appendTo(tbody);
    row.addClass("text-center");
    const cell1 = $('<td></td>').appendTo(row);
    cell1.attr('colspan', 9);
    cell1.text("Belum ada data pembelian");
  }

  $("#subTotalHidden").val(subTotal);
  $("#subTotal").text(formatRupiah(subTotal));
}

$('#listProdukButton').click(function() {
  if ($('#listProdukButton').hasClass('disabled')) return;
  $('#listProdukModal').modal('show');
  getListProduk();
});

$('#cariProduk').on('input', function() {
  getListProduk($(this).val());
});

$("#tpJumlahInput").on('input', tpHitungTotal);

$('#tpSubmitButton').click(tambahkanPembelian);

$("#submit").click(function(e) {
  if ($('#submit').hasClass('disabled')) return;
  const confirmed = confirm("Apakah yakin ingin menyimpan data ini?")
  if (confirmed) $('#transaksiForm').off('submit').submit();
})

$("#reset").click(function(e) {
  if (!listPembelian.length || JSON.stringify(listPembelianAwal) === JSON.stringify(listPembelian))
    return location.reload();
  const confirmed = confirm("Kamu yakin ingin mereset semua perubahan?")
  if (confirmed) location.reload();
})


updateDaftarPembelian();
</script>