<?php
include("connection.php");
// logika hapus barang masuk dan update stock barang
try{
    if(isset($_POST['deleteOut'])){
      $idBarang = $_POST['goods_id'];
      $idkeluar = $_POST['id_out'];
      $jumlah = $_POST['qty'];
      $namaBarang = $_POST['goods_name'];

      $queryGoods = $db->query("SELECT * FROM goods WHERE id_goods ='$idBarang'");
      $getStockGoods = $queryGoods->fetch();
      $stockGoods = $getStockGoods['stock'];

      $selisih = $stockGoods + $jumlah;
      $queryUpdateStockGoods = $db->query("UPDATE goods SET stock = '$selisih' WHERE id_goods = '$idBarang'");

      $queryDeleteOut = $db->query("DELETE FROM goods_out WHERE id_out ='$idkeluar'");
        if($queryUpdateStockGoods&&$queryDeleteOut){
          $_SESSION['delete'] = "Berhasil Hapus Data <b>$namaBarang</b> Dan Mengembalikan Stok Barang!!";
          header("location:goodsOut.php");
          exit;
        }else{
          header("location:goodsOut .php");
          exit;
        }
      }
}catch(Exception $e){
  echo"Gagal :" . $e->getMessage();
}
?>