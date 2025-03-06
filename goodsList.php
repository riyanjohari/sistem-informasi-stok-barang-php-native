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
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading">Menu</div>
                            <a class="nav-link" href="index.php">
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
                    <h1 class="mt-4">Stok Barang</h1>
                    <?php include("alertMassage.php"); ?>
                    <div class="card mb-4">
                        <div class="card-header">
                            <!-- Button to Open the Modal -->
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal">
                                Tambah
                            </button>
                            <a href="exportGoodsList.php" class="btn btn-secondary">Cetak</a>
                            <?php include("alertMassageStockNull.php"); ?>
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Gambar</th>
                                        <th>Nama Barang </th>
                                        <th>Deskripsi</th>
                                        <th>Stok</th>
                                        <th>Aksi</th>
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
                                            <td><?= $data['stock'] ?></td>

                                            <td>
                                                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalsEdit<?= $data['id_goods'] ?>">
                                                    Edit
                                                </button>
                                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalsDelete<?= $data['id_goods'] ?>">
                                                    Hapus
                                                </button>
                                            </td>
                                        </tr>
                                        <!-- The Edit Modals -->
                                        <div class="modal fade" id="modalsEdit<?= $data['id_goods'] ?>">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <!-- Modal Header -->
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Silahkan Tambah Barang</h4>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <!-- Modal body -->
                                                    <form method="POST" action="function.php" enctype="multipart/form-data">
                                                        <div class="modal-body">
                                                            <input type="hidden" value="<?= $data['id_goods'] ?>" name="id_goods">
                                                            <label for="name"><b>Nama Barang</b></label>
                                                            <input type="text" id="name" name="goods_name" placeholder="Nama Barang" class="form-control" value="<?= $data['goods_name'] ?>" required htmlspecialchars>
                                                            <br>
                                                            <label for="description"><b>Deskripsi</b></label>
                                                            <input type="text" id="description" name="description" placeholder="Deskripsi Barang" class="form-control" value="<?= $data['description'] ?>" required htmlspecialchars>
                                                            <br>
                                                            <label for="stock"><b>Stok</b></label>
                                                            <input type="text" id="stock" name="stock" placeholder="Jumlah" value="<?= $data['stock'] ?>" class="form-control" required htmlspecialchars>
                                                            <br>
                                                            <label for="image"><b>Upload Gambar</b></label>
                                                            <input type="file" id="image" name="image" class="form-control" htmlspecialchars>
                                                            <br>
                                                        </div>
                                                        <!-- Modal footer -->
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" name="editGoods" class="btn btn-primary">Simpan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- The Delete Modals -->
                                        <div class="modal fade" id="modalsDelete<?= $data['id_goods'] ?>">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <!-- Modal Header -->
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Yakin Ingin Menghapus data??</h4>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <!-- Modal body -->
                                                    <form method="POST" action="goodsListDelete.php">
                                                        <div class="modal-body">
                                                            <input type="hidden" value="<?= $data['id_goods'] ?>" name="id_goods">
                                                            <input type="hidden" value="<?= $data['goods_name'] ?>" name="goods_name">

                                                            <p>Anda Yakin Ingin Menghapus: <br>
                                                                <b><?= $data['description'] ?></b> dengan Jumlah stock <b><?= $data['stock'] ?></b> ??
                                                            </p>
                                                        </div>
                                                        <!-- Modal footer -->
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" name="editGoods" class="btn btn-danger">Hapus</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
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
<!-- The Modal for add Goods -->
<div class="modal fade" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Silahkan Tambah Barang</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <!-- Modal body -->
            <form method="POST" action="function.php" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="text" name="goods_name" placeholder="Nama Barang" class="form-control" required htmlspecialchars>
                    <br>
                    <input type="text" name="description" placeholder="Deskripsi Barang" class="form-control" required htmlspecialchars>
                    <br>
                    <input type="text" name="stock" placeholder="Jumlah" class="form-control" required htmlspecialchars>
                    <br>
                    <input type="file" name="image" class="form-control" required htmlspecialchars>
                    <br>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="simpanBarang" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

</html>