<!-- alert untuk tambah data -->
<?php if (isset($_SESSION['add']) && $_SESSION['add'] != null) : ?>
  <div class="alert alert-success" role="alert" style="display: block !important;">
    <?= $_SESSION['add']; ?>
  </div>
<?php endif;
$_SESSION['add'] = null; ?>

<!-- alert untuk edit data -->
<?php if (isset($_SESSION['edit']) && $_SESSION['edit'] != null) : ?>
  <div class="alert alert-warning" role="alert" style="display: block !important;">
    <?= $_SESSION['edit']; ?>
  </div>
<?php endif;
$_SESSION['edit'] = null; ?>

<!-- alert untuk hapus data -->
<?php if (isset($_SESSION['delete']) && $_SESSION['delete'] != null) : ?>
  <div class="alert alert-danger" role="alert" style="display: block !important;">
    <?= $_SESSION['delete']; ?>
  </div>
<?php endif;
$_SESSION['delete'] = null; ?>

<!-- Pesan alert jika jumlah barang keluar lebih banyak dari stok-->
<?php if (isset($_SESSION['error']) && $_SESSION['error'] != null) : ?>
  <div class="alert alert-danger alert-dismissible">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <?= $_SESSION['error']; ?>
  </div>
<?php endif;
$_SESSION['error'] = null; ?>

<!-- Pesan Alert Bila Berhasil Registrasi Akun -->
<?php if (isset($_SESSION['register']) && $_SESSION['register'] != null) : ?>
  <div class="alert alert-success alert-dismissible">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <?= $_SESSION['register']; ?>
  </div>
<?php endif;
$_SESSION['register'] = null; ?>

<!-- pesan alert massage jika belum Login memaksa masuk -->
<?php if (isset($_SESSION['error']) && $_SESSION['error'] != null) : ?>
  <div class="alert alert-danger" role="alert" style="display: block !important;">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <?= $_SESSION['error']; ?>
  </div>
<?php endif;
$_SESSION['error'] = null; ?>
<!-- pesan alert massage jika sudah Login mencoba mengakses laman login lagi -->
<?php if (isset($_SESSION['alreadyLogin']) && $_SESSION['alreadyLogin'] != null) : ?>
  <div class="alert alert-danger" role="alert" style="display: block !important;">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <?= $_SESSION['alreadyLogin']; ?>
  </div>
<?php endif;
$_SESSION['alreadyLogin'] = null; ?>