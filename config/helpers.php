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
