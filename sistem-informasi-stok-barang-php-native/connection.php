<?php
session_start();
try {
  $db = new PDO("mysql:host=localhost;dbname=stokbarang_db;", "root", "");
  // echo"berhasil";
} catch (Exception $e) {
  echo "Gagal :" . $e->getMessage();
}
// query tampilakn stok barang di index.php
try {
  $queryBarang = $db->query("SELECT * FROM goods");
  $dataBarang = $queryBarang->fetchAll();
} catch (Exception $e) {
  echo "Gagal :" . $e->getMessage();
}

// inner join table untuk menampikan isi tabel di goodsEnter
try {
  $queryTabel = $db->query("SELECT goods_enter.*, goods.goods_name as goods_name, goods.description as description, goods.image as image
  FROM goods_enter INNER JOIN goods ON goods_enter.goods_id=goods.id_goods ORDER BY time_in DESC");
  $dataTransaksi = $queryTabel->fetchAll();
} catch (Exception $e) {
  echo 'Gagal :' . $e->getMessage();
}
// Menampilkan data pada tabel goodsOut menggunakan inner join
try {
  $queryBarangKeluar = $db->query("SELECT goods_out.*, goods.goods_name AS goods_name, goods.image AS image FROM goods_out INNER JOIN goods ON goods_out.goods_id = goods.id_goods ORDER BY out_time DESC");
  $dataBarangKeluar = $queryBarangKeluar->fetchAll();
} catch (Exception $e) {
  echo "Gagal :" . $e->getMessage();
}
// tampilkan data users
try {
  $queryUsers = $db->query("SELECT * FROM users");
  $dataUsers = $queryUsers->fetchAll();
} catch (Exception $e) {
  echo "Gagal :" . $e->getMessage();
}

try {
  if (isset($_GET['id_goods'])) {
    $idGoodsMore = $_GET['id_goods'];
    $queryMoreGoods = $db->prepare("SELECT * FROM goods WHERE id_goods = :id_goods");
    $queryMoreGoods->bindParam(':id_goods', $idGoodsMore);
    $queryMoreGoods->execute();
    $dataMoreGoods = $queryMoreGoods->fetch();
    //data barang masuk
    $queryMoreGoodsEnter = $db->prepare("SELECT * FROM goods_enter WHERE goods_id = :goods_id");
    $queryMoreGoodsEnter->bindParam(':goods_id', $idGoodsMore);
    $queryMoreGoodsEnter->execute();
    $dataMoreGoodsEnter = $queryMoreGoodsEnter->fetchAll();
    //data barang masuk
    $queryMoreGoodsOut = $db->prepare("SELECT * FROM goods_out WHERE goods_id = :goods_id");
    $queryMoreGoodsOut->bindParam(':goods_id', $idGoodsMore);
    $queryMoreGoodsOut->execute();
    $dataMoreGoodsOut = $queryMoreGoodsOut->fetchAll();
  }
} catch (Exception $e) {
  echo "Gagal : " . $e->getMessage();
}
// index.php informasi
try {
  // Hitung Stok Barang Keluar
  $queryGoodsOut = $db->query("SELECT COUNT(*) AS total FROM goods_out");
  $dataGoodsOut = $queryGoodsOut->fetch();
  $countGoodsOut = $dataGoodsOut['total'];
} catch (Exception $e) {
  echo "Gagal :" . $e->getMessage();
}
try {
  // hitung total user
  $queryUsersDashboard = $db->query("SELECT COUNT(*) AS total FROM users");
  $dataUsersDashoard = $queryUsersDashboard->fetch();
  $countUser = $dataUsersDashoard['total'];
} catch (Exception $e) {
  echo "Gagal :" . $e->getMessage();
}
try {
  // hitung total stok barang
  $queryGoods = $db->query("SELECT COUNT(*) AS total FROM goods");
  $dataGoods = $queryGoods->fetch();
  $countGoods = $dataGoods['total'];
} catch (Exception $e) {
  echo "Gagal :" . $e->getMessage();
}
try {
  // hitung total Stok Barang Masuk
  $queryGoodsEnter = $db->query("SELECT COUNT(*) AS total FROM goods_enter");
  $dataGoodsEnter = $queryGoodsEnter->fetch();
  $countGoodsEnter = $dataGoodsEnter['total'];
} catch (Exception $e) {
  echo "Gagal :" . $e->getMessage();
}
// contoh penulisan code jika menggunakan inner join lebih dari 1 tabel
// try {
//   $queryBarang = $db->query(
//     "SELECT goods.*, 
//           goods_enter.qty AS qty_in, 
//           goods_out.qty AS qty_out
//         FROM goods
//         INNER JOIN goods_enter ON goods.id_goods = goods_enter.goods_id
//         INNER JOIN goods_out ON goods.id_goods = goods_out.goods_id
//   "
//   );
//   $dataBarang = $queryBarang->fetchAll();
// } catch (Exception $e) {
//   echo "Gagal :" . $e->getMessage();
// }
