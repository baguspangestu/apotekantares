<?php
function formatTanggal($ddd)
{
  return date('d-m-Y', strtotime($ddd));
}

function formatRupiah($n)
{
  if ($n < 0) {
    return "-Rp " . number_format(abs($n), 0, ',', '.');
  } else {
    return "Rp " . number_format($n, 0, ',', '.');
  }
}

function generateKd($kd, $data)
{
  if (!$data) {
    return $kd . '001';
  } else {
    $suid4 = substr($data['kd'], 3);
    $su4 = (int)$suid4 + 1;
    $newId = str_pad($su4, 3, '0', STR_PAD_LEFT);
    return $kd . $newId;
  }
}
