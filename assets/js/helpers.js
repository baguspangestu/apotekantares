function formatRupiah(angka) {
  var isNegative = false;

  if (angka < 0) {
    isNegative = true;
    angka = Math.abs(angka);
  }

  var number_string = angka
    .toString()
    .replace(/[^,\d]/g, "")
    .toString();

  number_string = number_string.replace(/^0+/, "");

  if (number_string === "" || number_string === "0") {
    return "Rp 0";
  }

  var split = number_string.split(","),
    sisa = split[0].length % 3,
    rupiah = split[0].substr(0, sisa),
    ribuan = split[0].substr(sisa).match(/\d{3}/gi);

  if (ribuan) {
    var separator = sisa ? "." : "";
    rupiah += separator + ribuan.join(".");
  }

  rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;

  return (isNegative ? "-Rp " : "Rp ") + rupiah;
}

function rupiahToNumber(rupiah) {
  return parseFloat(rupiah.replace(/[^,\d]/g, "").replace(",", "."));
}
