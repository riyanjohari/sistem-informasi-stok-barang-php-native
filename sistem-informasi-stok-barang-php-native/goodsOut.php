<?php
include("connection.php");
include("loginCheck.php");
// menangkap data barang yang akan ditampilkan kedalam select form modelnya
try {
    $dataBarang = $db->query("SELECT * FROM goods");
    $dataBarangnya = $dataBarang->fetchAll();
} catch (Exception $e) {
    echo "Gagal :" . $e->getMessage();
}
try {
    // jika user ingin mencari data sesuai filter tanggal maka code ini dijalankan
    if (isset($_POST['dateFilter'])) {
        $startDate = $_POST['filterDateStart'];
        $endDate = $_POST['filterDateEnd'];
        if (!empty($startDate) && !empty($endDate)) {
            $queryBarangKeluar = $db->prepare("SELECT goods_out.*, goods.goods_name, goods.image, 
                                        goods.description
                                        FROM goods_out
                                        INNER JOIN goods ON goods_out.goods_id = goods.id_goods
                                        WHERE out_time BETWEEN :startDate AND DATE_ADD(:endDate, INTERVAL 1 DAY)
                                        ORDER BY out_time DESC");
            $queryBarangKeluar->bindParam(':startDate', $startDate);
            $queryBarangKeluar->bindParam(':endDate', $endDate);
            $queryBarangKeluar->execute();
            $dataBarangKeluar = $queryBarangKeluar->fetchAll();
        } else {
            $queryBarangKeluar = $db->prepare("SELECT goods_out.*, goods.goods_name, goods.image, goods.description
                                        FROM goods_out
                                        INNER JOIN goods ON goods_out.goods_id = goods.id_goods
                                        ORDER BY out_time DESC");
            $queryBarangKeluar->execute();
            $dataBarangKeluar = $queryBarangKeluar->fetchAll();
        }
    } else {
        $queryBarangKeluar = $db->prepare("SELECT goods_out.*, goods.goods_name, goods.image,goods.description
                                        FROM goods_out
                                        INNER JOIN goods ON goods_out.goods_id = goods.id_goods
                                        ORDER BY out_time DESC");
        $queryBarangKeluar->execute();
        $dataBarangKeluar = $queryBarangKeluar->fetchAll();
    }
} catch (Exception $e) {
    echo "Gagal :" . $e->getMessage();
}

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
    <!-- cdn for alert error -->
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
                    <h1 class="mt-4">Barang Keluar</h1>
                    <div class="card mb-4">
                        <div class="card-header">
                            <!-- jangan lupa tambahkan pesan alert -->
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal">
                                Tambah
                            </button>
                            <a href="exportGoodsOut.php" class="btn btn-secondary">Cetak</a>
                            <div class="row mt-2">
                                <div class="col">
                                    <form method="POST" class="form-inline">
                                        <input type="date" name="filterDateStart" class="form-control">
                                        <input type="date" name="filterDateEnd" class="form-control ml-2">
                                        <button type="submit" name="dateFilter" class="btn btn-info ml-2">Search</button>
                                    </form>
                                </div>
                            </div>
                            <?php include("alertMassage.php"); ?>
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Waktu Masuk</th>
                                        <th>Penerima</th>
                                        <th>Nama Barang</th>
                                        <th>Stok Keluar</th>
                                        <th>aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($dataBarangKeluar as $index => $data) : ?>
                                        <!-- untuk mendapatkan data gambar dari database dan folder image -->
                                        <!-- <?php
                                                $image = $data['image'];
                                                if ($image == null) {
                                                    $images = "No Photo";
                                                } else {
                                                    $images = '<img src = "image/' . $image . '" class = "imageSizeGoodsList">';
                                                }
                                                ?> -->
                                        <tr>
                                            <td><?= $index + 1; ?></td>
                                            <td> <?= date("l H:i / j F Y", strtotime($data['out_time'])); ?> </td>
                                            <td> <?= $data['receiver']; ?> </td>
                                            <td><a href="detailGoods.php?id_goods=<?= $data['goods_id'] ?>" style="color: black; text-decoration: true;"><b><?= $data['description'] ?></b></a></td>
                                            <td> <?= $data['qty']; ?> </td>
                                            <td>
                                                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editModals<?= $data['id_out'] ?>">
                                                    Edit
                                                </button>
                                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModals<?= $data['id_out'] ?>">
                                                    Hapus
                                                </button>
                                            </td>

                                        </tr>
                                        <!-- form modals delete barang keluar -->
                                        <div class="modal fade" id="deleteModals<?= $data['id_out'] ?>">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <!-- Modal Header -->
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Silahkan edit barang keluar</h4>
                                                    </div>
                                                    <!-- Modal body -->
                                                    <form method="POST" action="goodsOutDelete.php">
                                                        <div class="modal-body">
                                                            <br>
                                                            <input type="hidden" value="<?= $data['goods_id'] ?>" name="goods_id">
                                                            <input type="hidden" value="<?= $data['id_out'] ?>" name="id_out">
                                                            <input type="hidden" value="<?= $data['goods_name'] ?>" name="goods_name">
                                                            <input type="hidden" value="<?= $data['qty'] ?>" name="qty">
                                                            <p>Anda Yakin Ingin menghapus data <br> <b><?= $data['goods_name'] ?></b> dengan jumlah Stok <b><?= $data['qty'] ?></b>??</p>
                                                        </div>
                                                        <!-- Modal footer -->
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" name="deleteOut" class="btn btn-danger">Hapus</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- form modals edit barang keluar -->
                                        <div class="modal fade" id="editModals<?= $data['id_out'] ?>">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <!-- Modal Header -->
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Silahkan edit barang keluar</h4>
                                                    </div>
                                                    <!-- Modal body -->
                                                    <form method="POST" action="function.php">
                                                        <div class="modal-body">
                                                            <br>
                                                            <input type="hidden" value="<?= $data['goods_id'] ?>" name="goods_id">
                                                            <input type="hidden" value="<?= $data['id_out'] ?>" name="id_out">
                                                            <input type="hidden" value="<?= $data['goods_name'] ?>" name="goods_name">
                                                            <input type="text" name="receiver" placeholder="Penerima" class="form-control" value="<?= $data['receiver'] ?>" required htmlspecialchars>
                                                            <br>
                                                            <input type="number" name="qty" placeholder="Jumlah" value="<?= $data['qty'] ?>" class="form-control" required htmlspecialchars>
                                                            <br>
                                                        </div>
                                                        <!-- Modal footer -->
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" name="editOut" class="btn btn-primary">Simpan</button>
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
<!-- form modals tambah barang keluar -->
<div class="modal fade" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Silahkan Tambah Transaksi</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <!-- Modal body -->
            <form method="POST" action="function.php">
                <div class="modal-body">
                    <select name="goods_id" id="goods_id" class="form-control">
                        <?php foreach ($dataBarangnya as $index => $data) : ?>
                            <option value="<?= $data['id_goods'] ?>"><?= $data['description'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <br>
                    <input type="text" name="receiver" placeholder="Penerima" class="form-control" required htmlspecialchars>
                    <br>
                    <input type="number" name="qty" placeholder="Jumlah" class="form-control" required htmlspecialchars>
                    <br>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="barangKeluar" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

</html>