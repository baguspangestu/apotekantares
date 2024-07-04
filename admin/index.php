<?php
include "header.php";

// DATA PENGGUNA
if (@$_GET['page'] == 'datauser') {
	include("datauser.php");
} else if (@$_GET['page'] == 'inputuser') {
	include "inputuser.php";
} else if (@$_GET['page'] == 'edituser') {
	include "edituser.php";
}

// Data Produk
else if (@$_GET['page'] == 'dataproduk') {
	include "dataproduk.php";
} else if (@$_GET['page'] == 'inputproduk') {
	include "inputproduk.php";
} else if (@$_GET['page'] == 'editproduk') {
	include "editproduk.php";
}

// DATA KATEGORI
else if (@$_GET['page'] == 'datakategori') {
	include "datakategori.php";
} else if (@$_GET['page'] == 'inputkategori') {
	include "inputkategori.php";
} else if (@$_GET['page'] == 'editkategori') {
	include "editkategori.php";
}


// DATA SUPLIER	
else if (@$_GET['page'] == 'datasuplier') {
	include "datasuplier.php";
} else if (@$_GET['page'] == 'inputsuplier') {
	include "inputsuplier.php";
} else if (@$_GET['page'] == 'editsuplier') {
	include "editsuplier.php";
} else if (@$_GET['page'] == 'detailsuplier') {
	include "detailsuplier.php";
}

// DATA PEMBELIAN	
else if (@$_GET['page'] == 'databeli') {
	include "databeli.php";
} else if (@$_GET['page'] == 'inputbeli') {
	include "inputbeli.php";
}

// DATA LAPORAN	
else if (@$_GET['page'] == 'laporanproduk') {
	include "laporanproduk.php";
} else if (@$_GET['page'] == 'laporanprodukbulan') {
	include "laporanprodukbulan.php";
} else if (@$_GET['page'] == 'laporanjenisbulan') {
	include "laporanjenisbulan.php";
} else if (@$_GET['page'] == 'laporanjualbulan') {
	include "laporanjualbulan.php";
} else if (@$_GET['page'] == 'laporanjualjenis') {
	include "laporanjualjenis.php";
} else if (@$_GET['page'] == 'laporanseluruh') {
	include "laporanseluruh.php";
} else {
	include "home.php";
}

include "footer.php";