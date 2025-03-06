<?php
include("connection.php");
include("loginCheck.php");
// query untuk menampilkan barang dalam model form tambah  barang masuk
try {
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
      $query = $db->prepare("SELECT goods_enter.*, goods.goods_name, goods.image
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
      $query = $db->query("SELECT goods_enter.*, goods.goods_name, goods.image
                                  FROM goods_enter
                                  INNER JOIN goods ON goods_enter.goods_id = goods.id_goods
                                  ORDER BY time_in DESC");

      $dataTransaksi = $query->fetchAll();
    }
  } else {
    // Default: Tampilkan semua data saat pertama kali dibuka
    $query = $db->query("SELECT goods_enter.*, goods.goods_name, goods.image
                                  FROM goods_enter
                                  INNER JOIN goods ON goods_enter.goods_id = goods.id_goods
                                  ORDER BY time_in DESC");

    $dataTransaksi = $query->fetchAll();
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
          <input type="date" name="filterDateUntil" class="form-control ml-3">
          <button type="submit" name="dateFilter" class="btn btn-info ml-3">Search</button>
        </form>
      </div>
    </div>
    <div class="data-tables datatable-dark">
      <table id="datatablesGoodsEnter">
        <thead>
          <tr>
            <th>No</th>
            <th>Waktu Transaksi</th>
            <th>Nama Barang</th>
            <th>Penerima</th>
            <th>Jumlah Masuk</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($dataTransaksi as $index => $data) : ?>
            <?php include("timeFormat.php"); ?>
            <tr>
              <td> <?= $index + 1 ?> </td>
              <td> <?= $format_tanggal; ?> </td>
              <td><?= $data['goods_name'] ?></td>
              <td><?= $data['accepted'] ?></td>
              <td><?= $data['qty'] ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

    </div>
  </div>

  <script>
    $(document).ready(function() {
      $('#datatablesGoodsEnter').DataTable({
        dom: 'Bfrtip',
        buttons: ['excel', 'pdf', 'print']
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