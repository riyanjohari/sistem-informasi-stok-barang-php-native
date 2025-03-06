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
    <a class="navbar-brand ps-3" href="index.php"> Selamat Datang <br>
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
              <div class="sb-nav-link-icon"><i class="fa-solid fa-house-user"></i></div>
              Dashboard
            </a>
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
          <h1 class="mt-2">Dashboard</h1>
          <?php include("alertMassage.php"); ?>
          <div class="row mt-2">
            <div class="col-xl-3 col-md-6">
              <div class="card bg-info text-white mb-4">
                <div class="card-body">
                  <h5><i class="fas fa-user-circle"></i> ADMIN : <u><?= $countUser; ?></u> </h5>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                  <a class="small text-white stretched-link" href="usersList.php">View Details</a>
                  <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-md-6">
              <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                  <h5><i class="fa-solid fa-boxes-stacked"></i> Jumlah Barang : <u><?= $countGoods; ?></u> </h5>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                  <a class="small text-white stretched-link" href="goodsList.php">View Details</a>
                  <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-md-6">
              <div class="card bg-success text-white mb-4">
                <div class="card-body">
                  <h5><i class="fa-solid fa-arrow-trend-down "></i> Barang Masuk : <u><?= $countGoodsEnter; ?></u> </h5>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                  <a class="small text-white stretched-link" href="goodsEnter.php">View Details</a>
                  <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-md-6">
              <div class="card bg-danger text-white mb-4">
                <div class="card-body">

                  <h5><i class="fa-solid fa-arrow-trend-up"></i> Barang Keluar : <u><?= $countGoodsOut; ?></u> </h5>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                  <a class="small text-white stretched-link" href="goodsOut.php">View Details</a>
                  <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
              </div>
            </div>
          </div>
          <div class="card mb-4">
            <div class="card-body">
              <table id="datatablesSimple">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Gambar</th>
                    <th>Nama Barang </th>
                    <th>Deskripsi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($dataBarang as $index => $data) : ?>
                    <tr>
                      <!-- dapatkan data nama gambar dan gambar yang ada fi folder image -->
                      <?php $gambar = $data['image'];
                      if ($gambar == null) {
                        $image = 'No Photo';
                      } else {
                        $image = '<img src ="image/' . $gambar . ' " class = "imageSizeGoodsList">';
                      }
                      ?>
                      <td> <?= $index + 1; ?> </td>
                      <td><?= $image; ?></td>
                      <td> <a href="detailGoods.php?id_goods=<?= $data['id_goods']; ?>"><b><?= $data['goods_name'] ?></b></a></td>
                      <td><?= $data['description'] ?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
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