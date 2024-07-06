<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $query = "SELECT * FROM transaksi_jual ORDER BY kd DESC";
  $result = mysqli_query($konek, $query);

  if (!$result) die("Query failed: " . mysqli_error($konek));

  $data = mysqli_fetch_assoc($result);
  $kd = generateKd('TJ', $data);

  $idKasir = $_SESSION['id'];
  $pembeli = !empty($_POST['pembeli']) ? $_POST['pembeli'] : 'Pembeli ' . substr($kd, 2);
  $tanggal = !empty($_POST['tanggal']) ? $_POST['tanggal'] : date('Y-m-d');
  $subTotal = !empty($_POST['subTotal']) ? $_POST['subTotal'] : 0;
  $diskon = !empty($_POST['diskon']) ? $_POST['diskon'] : 0;
  $tunai = !empty($_POST['tunai']) ? $_POST['tunai'] : 0;

  $dataListPembelian  = json_decode($_POST['dataListPembelian'], true);

  $konek->begin_transaction();

  try {
    $queryTransaksi = "INSERT INTO transaksi_jual (kd, id_kasir, pembeli, tanggal, subtotal, diskon, tunai) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmtTransaksi = $konek->prepare($queryTransaksi);
    $stmtTransaksi->bind_param("sissiii", $kd, $idKasir, $pembeli, $tanggal, $subTotal, $diskon, $tunai);
    $stmtTransaksi->execute();
    $stmtTransaksi->close();

    $queryUpdateStok = "UPDATE detail_produk SET stok = ? WHERE kd_produk = ?";
    $queryDetail = "INSERT INTO detail_transaksi_jual (kd_transaksi, kd_produk, harga, jumlah) VALUES (?, ?, ?, ?)";

    foreach ($dataListPembelian as $data) {
      $kdProduk = $data['kd'];
      $harga = $data['harga'];
      $jumlah = $data['jumlah'];
      $stok = $data['stok'];

      $stokBaru = $stok - $jumlah;

      $stmtUpdateStok = $konek->prepare($queryUpdateStok);
      $stmtUpdateStok->bind_param("is", $stokBaru, $kdProduk);
      $stmtUpdateStok->execute();
      $stmtUpdateStok->close();

      $stmtDetail = $konek->prepare($queryDetail);
      $stmtDetail->bind_param("ssii", $kd, $kdProduk, $harga, $jumlah);
      $stmtDetail->execute();
      $stmtDetail->close();
    }

    $konek->commit();

    echo '<script>alert("Berhasil Menambahkan Transaksi!");</script>';
    echo '<script>window.location.href="../kasir/index.php?page=datajual"</script>';
  } catch (Exception $e) {
    $konek->rollback();

    echo "Eksekusi gagal: " . $e->getMessage();
  }
}
?>

<div class="modal fade" id="listProdukModal" tabindex="-1" role="dialog" aria-labelledby="listProdukModalLabel" aria-hidden="true">
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
  <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-dolly-flatbed"></i> Data Penjualan</h1>

  <a href="?page=datajual" class="btn btn-secondary btn-icon-split"><span class="icon text-white-50"><i class="fas fa-arrow-left"></i></span>
    <span class="text">Kembali</span>
  </a>
</div>

<div class="card shadow mb-4">
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-fw fa-plus"></i> Tambah Data Penjualan</h6>
  </div>

  <form id="transaksiForm" method="POST">
    <div class="card-body">
      <div class="row">
        <div class="form-group col-md-6">
          <label class="font-weight-bold">Nama Pembeli</label>
          <input autocomplete="off" type="text" name="pembeli" class="form-control" />
        </div>
        <div class="form-group col-md-6">
          <label class="font-weight-bold">Tanggal Transaksi</label>
          <input autocomplete="off" type="date" name="tanggal" value="<?php echo date('Y-m-d'); ?>" class="form-control" required>
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
            <label class="font-weight-bold">Stok</label>
            <input autocomplete="off" type="text" id="tpStokInput" class="form-control" readonly />
          </div>

          <div class="form-group">
            <label class="font-weight-bold">Jumlah</label>
            <div class="input-group">
              <input autocomplete="off" type="number" id="tpJumlahInput" class="form-control" />
              <div class="input-group-append">
                <span class="input-group-text" id="tpSatuan"></span>
              </div>
              <div class="invalid-feedback">Jumlah melebihi stok!</div>
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
          <div class="row justify-content-end">
            <div class="col-md-6">
              <table class="table table-bordered table-striped">
                <tbody class="font-weight-bold">
                  <tr>
                    <td class="align-middle">Diskon</td>
                    <td>
                      <div class="input-group">
                        <input autocomplete="off" type="number" name="diskon" id="diskon" class="form-control" placeholder="0">
                        <div class="input-group-append">
                          <span class="input-group-text">%</span>
                        </div>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td class="align-middle">Total</td>
                    <td><span id="grandTotal" class="input-group-text font-weight-bold"></span></td>
                  </tr>
                  <tr>
                    <td class="align-middle">Tunai</td>
                    <td>
                      <input type="hidden" id="tunaiHidden" name="tunai" />
                      <input autocomplete="off" type="text" value="Rp 0" id="tunai" class="form-control" />
                      <div class="invalid-feedback">Uang Pembayaran masih kurang!</div>
                    </td>
                  </tr>
                  <tr>
                    <td class="align-middle">Kembali</td>
                    <td><span id="kembali" class="input-group-text"></span></td>
                  </tr>
                </tbody>
              </table>
            </div>
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
  const listPembelian = [];

  let selectedProduct = null;
  let subTotal = 0;
  let grandTotal = 0;

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

  function resetTPForm() {
    $("#tpKdInput").val('');
    $("#tpNamaInput").val('');
    $("#tpTglExp").val('');
    $("#tpSatuan").text('');
    $("#tpHargaInput").val('');
    $("#tpStokInput").val('');
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
    $("#tpStokInput").val(selectedProduct?.stok ?? 0);
    $("#tpJumlahInput").val(selectedProduct?.harga ? 1 : 0);

    sinkronStok(kd);
  }

  function sinkronStok(kd) {
    if (selectedProduct?.kd !== kd) return;
    const index = listPembelian.findIndex(item => item.kd === kd);
    $("#tpStokInput").val(selectedProduct?.stok - (index !== -1 ? listPembelian[index].jumlah : 0));

    tpHitungTotal();
  }

  function tpHitungTotal() {
    const stok = parseInt($("#tpStokInput").val()) || 0;
    const jumlah = parseInt($("#tpJumlahInput").val()) || 0;
    const total = jumlah * selectedProduct?.harga ?? 0;
    $("#tpTotalInput").val(formatRupiah(!isNaN(total) ? total : 0));

    if (jumlah > stok) {
      $("#tpJumlahInput").addClass('is-invalid');
    } else {
      $("#tpJumlahInput").removeClass("is-invalid");
    }

    if (selectedProduct) {
      if ((jumlah > stok) || (jumlah < 1)) {
        $('#tpSubmitButton').addClass('disabled');
      } else {
        $('#tpSubmitButton').removeClass("disabled");
      }
    } else {
      $('#tpSubmitButton').addClass("disabled");
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

    sinkronStok(kd);
    updateDaftarPembelian();
  }

  function removePembelian(kd) {
    const confirmed = confirm("Apakah Anda yakin ingin menghapus data ini?")
    if (!confirmed) return;

    const index = listPembelian.findIndex(item => item.kd === kd);
    if (index !== -1) listPembelian.splice(index, 1);

    sinkronStok(kd);
    updateDaftarPembelian();
  }

  function updateDaftarPembelian() {
    $('#tbodyListPembelian').empty();
    subTotal = 0;

    $('#dataListPembelian').val(JSON.stringify(listPembelian));

    if (listPembelian.length) {
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
          <span class="btn btn-outline-success btn-sm ${parseInt(e.jumlah) - 1 === 0 ? 'disabled' : ''}" 
                onClick="${parseInt(e.jumlah) - 1 === 0 ? '' : `updatePembelian('${e.kd}','${parseInt(e.jumlah) - 1}')`}">
              <i class="fas fa-chevron-left"></i>
          </span>
          ${e.jumlah}
          <span class="btn btn-outline-success btn-sm ${parseInt(e.jumlah) >= parseInt(e.stok) ? 'disabled' : ''}" 
                onClick="${parseInt(e.jumlah) >= parseInt(e.stok) ? '' : `updatePembelian('${e.kd}','${parseInt(e.jumlah) + 1}')`}">
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
      const tbody = $('#tbodyListPembelian');
      const row = $('<tr></tr>').appendTo(tbody);
      row.addClass("text-center");
      const cell1 = $('<td></td>').appendTo(row);
      cell1.attr('colspan', 9);
      cell1.text("Belum ada data pembelian");
    }

    $("#subTotalHidden").val(subTotal);
    $("#subTotal").text(formatRupiah(subTotal));

    hitungGrandTotal();
  }


  function hitungGrandTotal() {
    grandTotal = subTotal - (subTotal * ($("#diskon").val() / 100));
    $("#grandTotal").text(formatRupiah(grandTotal));

    hitungKembali();
  }

  function hitungKembali() {
    $("#tunaiHidden").val(rupiahToNumber($("#tunai").val()));

    const kembali = $("#tunaiHidden").val() - grandTotal;

    if (kembali >= 0) {
      $("#tunai").removeClass("is-invalid");
      if (listPembelian.length) {
        $("#submit").removeClass("disabled");
      } else {
        $("#submit").addClass("disabled");
      }
    } else {
      $("#submit").addClass("disabled", "true");
      $("#tunai").addClass("is-invalid");
    }

    $("#kembali").text(formatRupiah(kembali >= 0 ? kembali : 0));
  }

  $('#listProdukButton').click(function() {
    $('#listProdukModal').modal('show');
    getListProduk();
  });

  $('#cariProduk').on('input', function() {
    getListProduk($(this).val());
  });

  $("#tpJumlahInput").on('input', tpHitungTotal);

  $('#tpSubmitButton').click(tambahkanPembelian);

  $("#diskon").on('input', hitungGrandTotal);

  $("#tunai").on('input', function() {
    $("#tunai").val(formatRupiah($(this).val()));
    hitungKembali();
  });

  $("#submit").click(function(e) {
    if ($('#submit').hasClass('disabled')) return;
    const confirmed = confirm("Apakah yakin ingin menyimpan data ini?")
    if (confirmed) $('#transaksiForm').off('submit').submit();
  })

  $("#reset").click(function(e) {
    if (!listPembelian.length) return location.reload();
    const confirmed = confirm("Kamu yakin ingin mereset semua perubahan?")
    if (confirmed) location.reload();
  })

  updateDaftarPembelian();
</script>