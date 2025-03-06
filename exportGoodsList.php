<?php
include("connection.php");
include("loginCheck.php");
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
  <style>
    .imageSize {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      /* Membuat gambar bulat */
      transition: all 0.3s ease;
      object-fit: cover;
      /* Agar gambar tidak terdistorsi */
    }

    .imageSize:hover {
      transform: scale(4);
      /* Membesarkan gambar */
      border-radius: 12px;
      /* Mengubah bentuk menjadi persegi dengan sudut tumpul */
    }
  </style>
</head>

<body>
  <div class="container">
    <h2>Stock Barang</h2>
    <h4>(Inventory)</h4>
    <div class="data-tables datatable-dark">
      <table id="dataGoodsListTables">
        <thead>
          <tr>
            <th>No</th>
            <th>Nama Barang </th>
            <th>Deskripsi</th>
            <th>Stok</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($dataBarang as $index => $data) : ?>
            <tr>
              <!-- <?php
                    $image = $data['image'];
                    if ($image == null) {
                      $theImage = 'No Photo';
                    } else {
                      $theImage = '<img src ="image/' . $image . ' " class = "imageSize">';
                    }
                    ?> -->
              <td> <?= $index + 1; ?> </td>
              <!-- <td> <?= $theImage; ?> </td> -->
              <td><?= $data['goods_name'] ?></td>
              <td><?= $data['description'] ?></td>
              <td><?= $data['stock'] ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

    </div>
  </div>

  <script>
    $(document).ready(function() {
      $('#dataGoodsListTables').DataTable({
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