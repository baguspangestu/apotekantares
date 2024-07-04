<?php
include("../config/koneksi.php");

$q = isset($_GET['q']) ? $_GET['q'] : '';
$p = isset($_GET['p']) ? (int)$_GET['p'] : 1;
$limit = 20;
$offset = ($p - 1) * $limit;

$searchQuery = "";
if (!empty($q)) {
  $q = mysqli_real_escape_string($konek, $q);
  $searchQuery = "WHERE a.nama LIKE '%$q%'";
}

$query = "SELECT a.kd, a.nama, b.tgl_exp, a.satuan, a.harga_jual as harga, b.stok
  FROM produk a 
  LEFT JOIN detail_produk b ON a.kd = b.kd_produk
  $searchQuery 
  ORDER BY b.tgl_exp ASC
  LIMIT $limit OFFSET $offset";

$result = mysqli_query($konek, $query);

if (!$result) die("Query failed: " . mysqli_error($konek));

$data = array();
while ($d = mysqli_fetch_assoc($result)) {
  $data[] = array(
    'kd' => $d['kd'],
    'nama' => $d['nama'],
    'tgl_exp' => $d['tgl_exp'],
    'satuan' => $d['satuan'],
    'harga' => $d['harga'],
    'stok' => $d['stok']
  );
}

$totalQuery = "SELECT COUNT(*) as total FROM produk a 
  LEFT JOIN detail_produk b ON a.kd = b.kd_produk 
  $searchQuery";

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