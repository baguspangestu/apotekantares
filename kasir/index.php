<?php
include "header.php";

if (@$_GET['page'] == 'dataproduk') {
	include "dataproduk.php";
} else if (@$_GET['page'] == 'datajual') {
	include "datajual.php";
} else if (@$_GET['page'] == 'inputjual') {
	include "inputjual.php";
} else if (@$_GET['page'] == 'editjual') {
	include "editjual.php";
} else {
	include "home.php";
}

include "footer.php";
