<div class="mb-4">
  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-home"></i> Dashboard</h1>
  </div>

  <!-- Content Row -->
  <div class="alert alert-success">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    Selamat datang <span class="text-uppercase"><b><?php echo $_SESSION['username']; ?>!</b></span> Anda bisa
    mengoperasikan sistem dengan wewenang tertentu melalui pilihan menu di bawah.
  </div>
  <div class="row">
    <div class="col-xl-6 col-md-6 mb-4">
      <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="h5 mb-0 font-weight-bold text-gray-800"><a href="?page=home"
                  class="text-secondary text-decoration-none">Dashboard</a></div>
            </div>
            <div class="col-auto">
              <i class="fas fa-home fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-6 col-md-6 mb-4">
      <div class="card border-left-info shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="h5 mb-0 font-weight-bold text-gray-800"><a href="?page=datajual"
                  class="text-secondary text-decoration-none">Transaksi Penjualan</a></div>
            </div>
            <div class="col-auto">
              <i class="fas fa-dolly-flatbed fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>