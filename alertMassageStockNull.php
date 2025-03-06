<!-- pesan alert Bila  stok Barang Pada Tabel goods kosong -->
<?php
$dataGoodsForAlertMassage = $db->query("SELECT * FROM goods WHERE stock < 1");
while ($getDataForAlert = $dataGoodsForAlertMassage->fetch()) :
  $theData = $getDataForAlert['goods_name'];
?>
  <div class="alert alert-danger" role="alert" style="display: block !important;">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <p>
      Stok barang <strong> <?= $theData; ?> </strong> Sudah Habis!!
    </p>
  </div>
<?php endwhile; ?>