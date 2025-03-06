<?php
include("connection.php");
include("loginCheck.php");

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta name="description" content="" />
  <meta name="author" content="" />
  <title>Web App Stok Barang</title>
  <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
  <link href="css/styles.css" rel="stylesheet" />
  <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <!-- cdn for alert barang kosong -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="css/imageStyle.css">
</head>

<body class="sb-nav-fixed">
  <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
    <!-- Navbar Brand-->
    <a class="navbar-brand ps-3" href="goodsList.php"> Selamat Datang <br>
      <b><?= $_SESSION['name']; ?></b>
      <i class="fas fa-cash-register"></i>

    </a>
    <!-- Sidebar Toggle-->
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
    <!-- Navbar-->

  </nav>
  <div id="layoutSidenav">
    <div id="layoutSidenav_nav">
      <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
          <div class="nav">
            <div class="sb-sidenav-menu-heading">Menu</div>
            <a class="nav-link" href="goodsList.php">
              <div class="sb-nav-link-icon"><i class="fas fa-warehouse"></i></div>
              Stock Barang
            </a>
            <a class="nav-link" href="goodsEnter.php">
              <div class="sb-nav-link-icon"><i class="fas fa-dolly-flatbed"></i></div>
              Barang Masuk
            </a>
            <a class="nav-link" href="goodsOut.php">
              <div class="sb-nav-link-icon"><i class="fas fa-dolly-flatbed"></i></div>
              barang Keluar
            </a>
            <a class="nav-link" href="usersList.php">
              <div class="sb-nav-link-icon"><i class="fas fa-user-circle"></i></div>
              Kelola Data Admin
            </a>
            <a class="nav-link" href="logout.php">
              <div class="sb-nav-link-icon"><i class="fas fa-power-off"></class=>></i></div>
              Keluar
            </a>
          </div>
        </div>
        <div class="sb-sidenav-footer">
          <div class="small">Web App</div>
          Stok Barang
        </div>
      </nav>
    </div>
    <div id="layoutSidenav_content">
      <main>
        <div class="container-fluid px-4">
          <h3 class="mt-4"><b><?= $dataMoreGoods['description'] ?></b></h3>
          <div class="card mb-4">
            <div class="card-header">
              <a href="goodsList.php" class="btn btn-secondary">Kembali Ke Stok Barang</a>
            </div>
            <div class="table-responsive">
              <!-- tabel Informasi Barang -->
              <table class="table table-bordered" id="GoodsData">
                <thead>
                  <tr>
                    <th>Gambar </th>
                    <th>Nama Barang</th>
                    <th>Deskripsi</th>
                    <th>Stok Tersedia</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <!-- dapatkan data nama gambar dan gambar yang ada fi folder image -->
                    <?php $gambar = $dataMoreGoods['image'];
                    if ($gambar == null) {
                      $image = 'No Photo';
                    } else {
                      $image = '<img src ="image/' . $gambar . ' " class = "imageSize">';
                    }
                    ?>
                    <td><?= $image; ?></td>
                    <td><?= $dataMoreGoods['goods_name'] ?></td>
                    <td><?= $dataMoreGoods['description'] ?></td>
                    <td><?= $dataMoreGoods['stock'] ?></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <!-- tabel barang masuk -->
          <a href="goodsEnter.php">
            <h4 class="mt-4">Stok Barang Masuk</h4>
          </a>
          <div class="card mb-4">
            <div class="table-responsive">
              <table class="table table-bordered" id="GoodsData">
                <thead>
                  <tr>
                    <th>Penerima</th>
                    <th>Waktu</th>
                    <th>Stok Masuk</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($dataMoreGoodsEnter as $index => $data) : ?>
                    <?php include("timeFormat.php");  ?>
                    <tr>
                      <td><?= $data['accepted'] ?></td>
                      <td> <?= $format_tanggal; ?> </td>
                      <td><?= $data['qty'] ?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
          <!-- tabel Barang Keluar -->
          <a href="goodsOut.php">
            <h4 class="mt-4">Stok Barang Keluar</h4>
          </a>
          <div class="card mb-4">
            <div class="table-responsive">
              <table class="table table-bordered" id="GoodsDataout">
                <thead>
                  <tr>
                    <th>Penerima</th>
                    <th>Waktu</th>
                    <th>Stok Keluar</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($dataMoreGoodsOut as $index => $data) : ?>
                    <tr>
                      <td><?= $data['receiver'] ?></td>
                      <td> <?= date("l H:i / j F Y", strtotime($data['out_time'])); ?> </td>
                      <td><?= $data['qty'] ?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <br>

      </main>
      <footer class="py-4 bg-light mt-auto">
        <div class="container-fluid px-4">
          <div class="d-flex align-items-center justify-content-between small">
            <div class="text-muted">Made By &copy; Riyan</div>
            <!-- <div>
                                <a href="#">Privacy Policy</a>
                                &middot;
                                <a href="#">Terms &amp; Conditions</a>
                            </div> -->
          </div>
        </div>
      </footer>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  <script src="js/scripts.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
  <script src="assets/demo/chart-area-demo.js"></script>
  <script src="assets/demo/chart-bar-demo.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
  <script src="js/datatables-simple-demo.js"></script>
</body>


</html>