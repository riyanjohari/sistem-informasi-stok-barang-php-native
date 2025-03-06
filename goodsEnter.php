<?php

use FFI\Exception;

include 'connection.php';
include 'loginCheck.php';
// NOTE : LEBIH BERHATI HATI SAAT MENULIS QUERY!!!!!!!!!!
try {
    // query untuk menampilkan barang dalam form tambah/edit/hapus barang masuk
    $queryBarang = $db->query('SELECT * FROM goods');
    $barang = $queryBarang->fetchAll();
} catch (Exception $e) {
    echo 'Gagal :' . $e->getMessage();
}
try {
    // jika user ingin mencari data berdasarkan filter tanggal maka code ini dijalankan 
    if (isset($_POST['dateFilter'])) {
        $startDate = $_POST['filterDateStart'];
        $endDate = $_POST['filterDateUntil'];

        if (!empty($startDate) && !empty($endDate)) {
            // Query filter dengan INNER JOIN agar data goods_name dan image tetap ada
            $query = $db->prepare("SELECT goods_enter.*, goods.goods_name, goods.image,goods.description
                                    FROM goods_enter
                                    INNER JOIN goods ON goods_enter.goods_id = goods.id_goods
                                    WHERE time_in BETWEEN :startDate AND DATE_ADD(:endDate, INTERVAL 1 DAY)
                                    ORDER BY time_in DESC");

            $query->bindParam(':startDate', $startDate);
            $query->bindParam(':endDate', $endDate);
            $query->execute();
            $dataTransaksi = $query->fetchAll();
        } else {
            // Jika form kosong, tampilkan semua data dengan INNER JOIN
            $query = $db->query("SELECT goods_enter.*, goods.goods_name, goods.image,goods.description
                                    FROM goods_enter
                                    INNER JOIN goods ON goods_enter.goods_id = goods.id_goods
                                    ORDER BY time_in DESC");

            $dataTransaksi = $query->fetchAll();
        }
    } else {
        // Default: Tampilkan semua data saat pertama kali dibuka
        $query = $db->query("SELECT goods_enter.*, goods.goods_name, goods.image, goods.description
                                    FROM goods_enter
                                    INNER JOIN goods ON goods_enter.goods_id = goods.id_goods
                                    ORDER BY time_in DESC");

        $dataTransaksi = $query->fetchAll();
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
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i
                class="fas fa-bars"></i></button>
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
                    <h1 class="mt-4">Barang Masuk </h1>
                    <form action="">

                    </form>
                    <div class="card mb-4">
                        <div class="card-header">
                            <!-- Button to Open the Modal -->
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#tambahBarang">
                                Tambah
                            </button>
                            <a href="exportGoodsEnter.php" class="btn btn-secondary">Cetak</a>
                            <br>
                            <!-- form untuk mencari data berdasarkan tanggal yang diinput -->
                            <div class="row mt-2">
                                <div class="col">
                                    <form method="POST" class="form-inline">
                                        <input type="date" name="filterDateStart" class="form-control">
                                        <input type="date" name="filterDateUntil" class="form-control ml-3">
                                        <button type="submit" name="dateFilter" class="btn btn-info ml-3">Search</button>
                                    </form>
                                </div>
                            </div>
                            <?php include("alertMassage.php"); ?>
                            <!-- <a href="add.php" class="btn btn-primary">Tambah</a> -->
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Waktu Transaksi</th>
                                        <th>Penerima</th>
                                        <th>Nama Barang</th>
                                        <th>Jumlah Masuk</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($dataTransaksi as $index => $data) : ?>
                                        <!-- format tanggal bahasa indonesia -->
                                        <?php include("timeFormat.php"); ?>
                                        <!-- code untuk memanggil gambar -->
                                        <!-- <?php
                                                $image = $data['image'];
                                                if ($image == null) {
                                                    $images = "No Photo";
                                                } else {
                                                    $images = '<img src = "image/' . $image . '" class ="imageSizeGoodsList">';
                                                }
                                                ?> -->
                                        <tr>
                                            <td> <?= $index + 1 ?> </td>
                                            <td> <?= $format_tanggal; ?> </td>
                                            <td><?= $data['accepted'] ?></td>
                                            <td><a href="detailGoods.php?id_goods=<?= $data['goods_id'] ?>" style="color: black; text-decoration: true;"><b><?= $data['description'] ?></b></a></td>
                                            <!-- <td> <?= $images; ?> </td> -->
                                            <td><?= $data['qty'] ?></td>
                                            <td>
                                                <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                                    data-bs-target="#editMasuk<?= $data['id_enter'] ?>">
                                                    Edit
                                                </button>
                                                <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                                    data-bs-target="#hapusMasuk<?= $data['id_enter'] ?>">
                                                    hapus
                                                </button>
                                            </td>
                                        </tr>
                                        <!-- Modal Edit Barang Masuk -->
                                        <div class="modal fade" id="editMasuk<?= $data['id_enter'] ?>">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Edit Barang</h4>
                                                    </div>
                                                    <form method="POST" action="function.php">
                                                        <div class="modal-body">
                                                            <input type="hidden" name="id_enter" value="<?= $data['id_enter'] ?>">
                                                            <input type="hidden" name="goods_id" value="<?= $data['goods_id'] ?>">
                                                            <label for="accepted"><b>Penerima</b></label>
                                                            <input id="accepted" type="text" name="accepted" placeholder="Penerima" class="form-control"
                                                                value="<?= $data['accepted'] ?>" required>
                                                            <br>
                                                            <label for="qty"><b>Jumlah Stok</b></label>
                                                            <input id="qty" type="number" name="qty" placeholder="Jumlah" class="form-control"
                                                                value="<?= $data['qty'] ?>" required>
                                                            <br>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" name="editEnter" class="btn btn-primary">Simpan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal Hapus Barang Masuk -->
                                        <div class="modal fade" id="hapusMasuk<?= $data['id_enter'] ?>">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Hapus Barang</h4>
                                                    </div>
                                                    <form method="POST" action="goodsEnterDelete.php">
                                                        <div class="modal-body">
                                                            <input type="hidden" name="id_enter" value="<?= $data['id_enter'] ?>">
                                                            <input type="hidden" name="goods_id" value="<?= $data['goods_id'] ?>">
                                                            <input type="hidden" name="qty" value="<?= $data['qty'] ?>">
                                                            <input type="hidden" name="goods_name" value="<?= $data['goods_name'] ?>">
                                                            <br>
                                                            Anda yakin ingin menghapus <b><?= $data['description'] ?></b> dan jumlah <b><?= $data['qty'] ?></b>??
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" name="deleteEnter" class="btn btn-primary">Hapus</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Modal liat selengkapnya Barang Masuk -->
                                        <div class="modal fade" id="more<?= $data['goods_id'] ?>">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Edit Barang</h4>
                                                    </div>
                                                    <form method="POST" action="function.php">
                                                        <div class="modal-body">
                                                            <input type="hidden" name="id_enter" value="<?= $data['id_enter'] ?>">
                                                            <input type="hidden" name="goods_id" value="<?= $data['goods_id'] ?>">
                                                            <label for="accepted"><b>Penerima</b></label>
                                                            <input id="accepted" type="text" name="accepted" placeholder="Penerima" class="form-control"
                                                                value="<?= $data['accepted'] ?>" required>
                                                            <br>
                                                            <label for="qty"><b>Jumlah Stok</b></label>
                                                            <input id="qty" type="number" name="qty" placeholder="Jumlah" class="form-control"
                                                                value="<?= $data['qty'] ?>" required>
                                                            <br>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" name="editEnter" class="btn btn-primary">Simpan</button>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
    <script src="js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="assets/demo/chart-area-demo.js"></script>
    <script src="assets/demo/chart-bar-demo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
        crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>

</body>
<!-- modal tambah barang -->
<div class="modal fade" id="tambahBarang">
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
                    <input type="hidden" value="<?= $data['goods_name'] ?>" name="goods_name">
                    <select name="goods_id" id="goods_id" class="form-control">
                        <?php foreach ($barang as $index => $data) : ?>
                            <option value="<?= $data['id_goods'] ?>"><?= $data['description'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <br>
                    <input type="text" name="accepted" placeholder="penerima" class="form-control" required
                        htmlspecialchars>
                    <br>
                    <input type="number" name="qty" placeholder="Jumlah" class="form-control" required
                        htmlspecialchars>
                    <br>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="barangMasuk" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>


</html>