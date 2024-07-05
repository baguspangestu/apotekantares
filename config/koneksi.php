<?php
$konek = mysqli_connect("localhost", "root", "", "db_apotek");
if (mysqli_connect_errno()) {
	echo "Koneksi GAGAL!";
}
