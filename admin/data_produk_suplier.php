<?php
include("../config/koneksi.php");

$kd_suplier = isset($_GET['kd_suplier']) ? $_GET['kd_suplier'] : '';
$q = isset($_GET['q']) ? $_GET['q'] : '';
$p = isset($_GET['p']) ? (int)$_GET['p'] : 1;
$limit = 20;
$offset = ($p - 1) * $limit;

$searchQuery = "";
if (!empty($q)) {
  $q = mysqli_real_escape_string($konek, $q);
  $searchQuery = "AND b.nama LIKE '%$q%'";
}

$query = "SELECT b.kd, b.nama, c.tgl_exp, c.harga_beli as harga, b.satuan, c.stok
  FROM detail_suplier a
  LEFT JOIN produk b ON a.kd_produk = b.kd
  LEFT JOIN detail_produk c ON a.kd_produk = c.kd_produk
  WHERE a.kd_suplier = '$kd_suplier' $searchQuery 
  ORDER BY c.tgl_exp ASC
  LIMIT $limit OFFSET $offset";

$result = mysqli_query($konek, $query);

if (!$result) die("Query failed: " . mysqli_error($konek));

$data = array();
while ($d = mysqli_fetch_assoc($result)) {
  $data[] = array(
    'kd' => $d['kd'],
    'nama' => $d['nama'],
    'tgl_exp' => $d['tgl_exp'],
    'harga' => $d['harga'],
    'satuan' => $d['satuan'],
    'stok' => $d['stok']
  );
}

$totalQuery = "SELECT COUNT(*) as total FROM detail_suplier a
  LEFT JOIN produk b ON a.kd_produk = b.kd
  LEFT JOIN detail_produk c ON a.kd_produk = c.kd_produk
  WHERE a.kd_suplier = '$kd_suplier' $searchQuery";

$totalResult = mysqli_query($konek, $totalQuery);
$totalRow = mysqli_fetch_assoc($totalResult);
$total = intval($totalRow['total']);
$pages = ceil($total / $limit);

$response = array(
  'data' => $data,
  'total' => $total,
  'page' => $p,
  'pages' => $pages
);

echo json_encode($response);