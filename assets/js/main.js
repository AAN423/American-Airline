// Batasi tanggal "open until" agar tidak bisa pilih tanggal yang sudah lewat
document.addEventListener('DOMContentLoaded', function () {
  var dateInput = document.getElementById('open_until');
  if (dateInput) {
    var today = new Date().toISOString().split('T')[0];
    dateInput.setAttribute('min', today);
  }
});
