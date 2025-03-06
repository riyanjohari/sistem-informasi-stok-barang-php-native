<?php
include("connection.php");
include("loginCheck.php");
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
      $queryBarangKeluar = $db->prepare("SELECT goods_out.*, goods.goods_name, goods.image
                                      FROM goods_out
                                      INNER JOIN goods ON goods_out.goods_id = goods.id_goods
                                      WHERE out_time BETWEEN :startDate AND DATE_ADD(:endDate, INTERVAL 1 DAY)
                                      ORDER BY out_time DESC");
      $queryBarangKeluar->bindParam(':startDate', $startDate);
      $queryBarangKeluar->bindParam(':endDate', $endDate);
      $queryBarangKeluar->execute();
      $dataBarangKeluar = $queryBarangKeluar->fetchAll();
    } else {
      $queryBarangKeluar = $db->prepare("SELECT goods_out.*, goods.goods_name, goods.image
                                      FROM goods_out
                                      INNER JOIN goods ON goods_out.goods_id = goods.id_goods
                                      ORDER BY out_time DESC");
      $queryBarangKeluar->execute();
      $dataBarangKeluar = $queryBarangKeluar->fetchAll();
    }
  } else {
    $queryBarangKeluar = $db->prepare("SELECT goods_out.*, goods.goods_name, goods.image
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
<html>

<head>
  <title>Stock Barang</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
</head>

<body>
  <div class="container">
    <h2>Stock Barang</h2>
    <h4>(Inventory)</h4>
    <div class="row mt-2">
      <div class="col">
        <form method="POST" class="form-inline">
          <input type="date" name="filterDateStart" class="form-control">
          <input type="date" name="filterDateEnd" class="form-control ml-2">
          <button type="submit" name="dateFilter" class="btn btn-info ml-2">Search</button>
        </form>
      </div>
    </div>
    <div class="data-tables datatable-dark">
      <table id="datatablesGoodsOut">
        <thead>
          <tr>
            <th>No</th>
            <th>Waktu Transaksi</th>
            <th>Nama Barang</th>
            <th>Penerima</th>
            <th>Stok Keluar</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($dataBarangKeluar as $index => $data) : ?>
            <tr>
              <td><?= $index + 1; ?></td>
              <td> <?= date("l H:i j-M-Y", strtotime($data['out_time'])); ?> </td>
              <td> <?= $data['goods_name']; ?> </td>
              <td> <?= $data['receiver']; ?> </td>
              <td> <?= $data['qty']; ?> </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

    </div>
  </div>

  <script>
    $(document).ready(function() {
      $('#datatablesGoodsOut').DataTable({
        dom: 'Bfrtip',
        buttons: [
          'excel', 'pdf', 'print'
        ]
      });
    });
  </script>

  <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
  <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.flash.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
  <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.print.min.js"></script>



</body>

</html>