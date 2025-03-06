<?php
include("connection.php");
try {
  if (isset($_POST['id_goods'])) {
    $id_goods = $_POST['id_goods'];
    $goods_name = $_POST['goods_name'];
    // logika untuk menghapus gambar didalam folder
    $imageData = $db->query("SELECT * FROM goods WHERE id_goods = $id_goods");
    $getDataImage = $imageData->fetch();
    $image = 'image/' . $getDataImage['image'];
    unlink($image);
    $queryDeleteGoods = $db->query("DELETE FROM goods WHERE id_goods = '$id_goods'");
    if ($queryDeleteGoods) {
      $_SESSION['delete'] = "berhasil Menghapus " . "<b>$goods_name</b> dari Stok Barang!!";
      header("location:goodsList.php");
      exit();
    }
  }
} catch (Exception $e) {
  echo "Gagal :" . $e->getMessage();
}
