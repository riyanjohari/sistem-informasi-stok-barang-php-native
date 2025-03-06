<?php 
include("connection.php");
// echo"<pre>";
// print_r($_GET['id']);
// NOTE : lebih teliti lagi saat menuliskan query
// logika menghapus barang keluar
try{ 
    if(isset($_POST['deleteEnter'])){
      $idMasuk = $_POST['id_enter'];
      $idBarang = $_POST['goods_id'];
      $jumlah  = $_POST['qty'];
      $namaBarang  = $_POST['goods_name'];
      // query untuk mendapatkan data stok barang
      $querygetStokGoods = $db->query("SELECT * FROM goods WHERE id_goods = '$idBarang'");
      $getStokGoods = $querygetStokGoods->fetch();
      $dataStokGoods = $getStokGoods['stock'];
      // query logika bila barang masuk dihapus
      $selisih  = $dataStokGoods - $jumlah;
      $queryUpdateGoodsStock = $db->query("UPDATE goods SET stock = '$selisih' WHERE id_goods = '$idBarang'");
      $queryDeleteEnter = $db->query("DELETE FROM goods_enter WHERE id_enter = $idMasuk");
      if($queryUpdateGoodsStock&&$queryDeleteEnter){
        $_SESSION['delete'] = "Berhasil Hapus Data <b>$namaBarang</b> Dan Mengembalikan Jumlah Stock!! ";
        header("location:goodsEnter.php");
        exit;
      }else{
        header("location:goodsEnter.php");
        exit;
      }
    }
}catch(Exception $e){
  echo"<pre>";
  echo"Gagalll :" . $e->getMessage();
}
?>