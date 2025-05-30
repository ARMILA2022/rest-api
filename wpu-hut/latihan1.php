<?php 
$data = file_get_contents('data/pizza.json');
$menu = json_decode($data, true);
$menu = $menu["menu"];

// Ambil daftar kategori unik
$kategori_unik = array_unique(array_column($menu, 'kategori'));

// Susun kategori sesuai urutan yang diinginkan
$urutan_kategori = ['pizza', 'pasta', 'nasi', 'minuman'];
$kategori = array_values(array_intersect($urutan_kategori, $kategori_unik));
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>WPU Hut</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.2.1/dist/css/bootstrap.min.css">
  <style>
    .nav-kategori .nav-link {
      text-transform: capitalize;
    }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="#"><img src="img/logo.png" width="120"></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
      <div class="navbar-nav nav-kategori" id="kategori-nav">
        <a class="nav-item nav-link active" href="#" data-filter="all">All</a>
        <?php foreach ($kategori as $kat) : ?>
          <a class="nav-item nav-link" href="#" data-filter="<?= $kat; ?>"><?= $kat; ?></a>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</nav>

<div class="container mt-4">
  <div class="row" id="menu-container">
    <?php foreach ($menu as $row) : ?>
      <div class="col-md-4 mb-3 menu-item" data-kategori="<?= $row['kategori']; ?>">
        <div class="card">
          <img src="img/menu/<?= $row['gambar']; ?>" class="card-img-top" alt="<?= $row['nama']; ?>">
          <div class="card-body">
            <h5 class="card-title"><?= $row['nama']; ?></h5>
            <p class="card-text"><?= $row['deskripsi']; ?></p>
            <h5 class="card-title">Rp. <?= number_format($row['harga'], 0, ',', '.'); ?></h5>
            <a href="#" class="btn btn-primary">Pesan Sekarang</a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
  $(document).ready(function () {
    $('.menu-item').hide().fadeIn(300); // animasi awal

    $('#kategori-nav a').click(function (e) {
      e.preventDefault();

      $('#kategori-nav a').removeClass('active');
      $(this).addClass('active');

      const filter = $(this).data('filter');

      // Sembunyikan semua dulu, baru tampilkan yang sesuai filter
      $('.menu-item').stop(true, true).fadeOut(200, function () {
        $('.menu-item').each(function () {
          const kategori = $(this).data('kategori');
          if (filter === 'all' || kategori === filter) {
            $(this).stop(true, true).fadeIn(300);
          }
        });
      });
    });
  });
</script>
</body>
</html>
