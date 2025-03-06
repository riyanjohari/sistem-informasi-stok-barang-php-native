<?php
include("connection.php");

// LEBIH TELITI LAGI DALAM MENULIS QUERY NYA!!
//tambah list barang di goodsList.php
try {
  if (isset($_POST['simpanBarang'])) {
    $barangName = strtoupper($_POST['goods_name']);
    $deskripsi =  strtoupper($_POST['description']);
    $stok = $_POST['stock'];

    // Data file gambar
    // sebelum menangkap file, tambahkan enctype="multypart/form-data untuk dapat menangkap data dengan method $_files"
    $gambar = $_FILES['image']['name']; //dapatkan nama file gambarnya
    $size = $_FILES['image']['size']; //dapatkan size gambarnya
    $file_tmp = $_FILES['image']['tmp_name']; //lokasi sementara gambar sebelum dicopy ke folder image

    // pattinfo untuk mengembalikan beberapa bagian dari path, seperti nama file, ekstensi, direktori, dan nama file tanpa ekstensi dan PATHINFO_EXTNSION untuk mendapatkan extensi dari file gambar, jadi nilai dari $extention adalah extensi dari file gambar yang dimasukan cth : gambar.jpg, maka yang diambil adalah jpg nya
    $extention = strtolower(pathinfo($gambar, PATHINFO_EXTENSION));

    // Daftar ekstensi yang diizinkan
    $allowedExtension = ['png', 'jpg', 'jpeg'];

    // Periksa apakah barang sudah ada
    $check = $db->prepare("SELECT COUNT(*) FROM goods WHERE description = ?");
    $check->execute([$deskripsi]);
    $calculating = $check->fetchColumn();

    if ($calculating < 1) {
      if ($gambar && in_array($extention, $allowedExtension)) {
        if ($size < 10000000) {
          // jika size dibawah 10 mb jalankan kode
          // penjelasan :gambar di enkripsi dengan md5 lalu di ubah lagi dengan string unik dari function uniqid dan ditambah lagi function time agar kemungkinan nama yang sama semakin kecil karena waktu detik selalu berbeda setiap detiknya memungkinkan kemungkinan nama duplikasi semakin kecil lagi lalu ditambah string '.' dan ditambah ekstensi dari gambarnya
          $theImage = md5(uniqid($gambar, true) . time()) . '.' . $extention;
          // salin gambarnya ke dalam folder image yang namanya sudah di enkripsi
          move_uploaded_file($file_tmp, 'image/' . $theImage);
        } else {
          // jika melebihi kode dibawah dijalankan
          $_SESSION['error'] = "Ukuran gambar <b>$barangName</b> terlalu besar!";
          header("location:goodsList.php");
          exit;
        }
      } else {
        $_SESSION['error'] = "Ekstensi gambar tidak valid!, yang diperbolehkan hanya <b>JPG, JPEG, PNG</b> ";
        header("location:goodsList.php");
        exit;
      }
      // Insert data ke database
      $queryTambah = $db->prepare("INSERT INTO goods (goods_name, description, stock, image) VALUES (?, ?, ?, ?)");
      if ($queryTambah->execute([$barangName, $deskripsi, $stok, $theImage])) {
        $_SESSION['add'] = "Berhasil menambahkan <b>$barangName</b> dengan jumlah <b>$stok</b>!";
        header("location:goodsList.php");
        exit;
      } else {
        $_SESSION['error'] = "Gagal menambahkan barang!";
        header("location:goodsList.php");
        exit;
      }
    } else {
      $_SESSION['error'] = "Nama Barang <b>$deskripsi</b> sudah ada di database!";
      header("location:goodsList.php");
      exit;
    }
  }
} catch (Exception $e) {
  echo "Gagal: " . $e->getMessage();
}
// edit list data barang
//note, PELAJARI LAGI KODE EDIT DIBAWAH INI
try {
  if (isset($_POST['editGoods'])) {
    $idBarang = $_POST['id_goods'];
    $namaBarang = strtoupper($_POST['goods_name']);
    $deskripsi = strtoupper($_POST['description']);
    $stok = $_POST['stock'];

    // Tangkap data gambar baru
    $gambar = $_FILES['image']['name'];
    $ukuranGambar = $_FILES['image']['size'];
    $asalGambar = $_FILES['image']['tmp_name'];

    // Mengambil ekstensi dari gambar
    $imageExtensionName = strtolower(pathinfo($gambar, PATHINFO_EXTENSION));
    $allowedExtension = ['png', 'jpg', 'jpeg']; //ekstensi gambar yang diperbolehkan
    // query untuk menghitung nama desripsi yang sama
    $checkGoods = $db->prepare("SELECT COUNT(*) FROM goods WHERE description = :deskripsi");
    $checkGoods->execute([':deskripsi' => $deskripsi]);
    $calculating = $checkGoods->fetchColumn();

    // Ambil nama gambar lama dari database
    $queryGetOldImage = $db->prepare("SELECT image FROM goods WHERE id_goods = :idBarang");
    $queryGetOldImage->execute([':idBarang' => $idBarang]);
    $oldImage = $queryGetOldImage->fetchColumn();

    if ($ukuranGambar > 0 && $gambar) { // Jika ada gambar yang diunggah
      if (in_array($imageExtensionName, $allowedExtension)) {
        if ($ukuranGambar < 10000000) {
          // Hapus gambar lama jika ada
          if ($oldImage && file_exists('image/' . $oldImage)) {
            //pengecekan jika ada gambar yang diupload maka hapus gambar yang lama
            unlink('image/' . $oldImage);
          }

          // Simpan gambar baru
          // nama gambar di enkripsi menggunakan md5 dan dienkripsi lagi menggunakan sting unik uniqid dan ditambah function time agar nama lebih unik lagi karena detik terus berjalan meminimalisir nama yang sama lalu ditambah '.' dan ekstensi gambarnya
          $theImage = md5(uniqid($gambar, true) . time()) . '.' . $imageExtensionName;
          // $asalGambar digunakan untuk menyimpan sementara file gambar, lalu setelahnya lokasi yang dituju untuk memindahkan file gambar beserta nama baru nya yang sudah di enkripsi
          move_uploaded_file($asalGambar, 'image/' . $theImage);

          // Update data barang beserta gambar baru 
          //menggunakan prepare bertujuan menghindari sql injection(serangan query sql)
          $queryUpdateBarang = $db->prepare("UPDATE goods SET goods_name = :namaBarang, description = :deskripsi, stock = :stok, image = :gambar WHERE id_goods = :id_goods");
          $queryUpdateBarang->bindParam(':namaBarang', $namaBarang);
          $queryUpdateBarang->bindParam(':deskripsi', $deskripsi);
          $queryUpdateBarang->bindParam(':stok', $stok);
          $queryUpdateBarang->bindParam(':gambar', $theImage);
          $queryUpdateBarang->bindParam(':id_goods', $idBarang);

          if ($queryUpdateBarang->execute()) { //jika query berhasil dijalankan maka jalankan code dibawah
            $_SESSION['edit'] = "Berhasil Update Data <b>$namaBarang</b>!!";
            header("location:goodsList.php");
            exit;
          } else { //jika  query gagal maka jalankan kode dibawah
            $_SESSION['error'] = "Gagal Update Data <b>$namaBarang</b>!";
            header("location:goodsList.php");
            exit;
          }
        } else {
          $_SESSION['error'] = "Ukuran gambar terlalu besar! yang di perbolehkan hanya dibawah <b>10 MB</b>";
          header("location:goodsList.php");
          exit;
        }
      } else {
        $_SESSION['error'] = "Format gambar tidak didukung!";
        header("location:goodsList.php");
        exit;
      }
    } else {
      // Jika tidak ingin mengubah gambar, update tanpa mengubah field image
      $queryUpdateBarang = $db->prepare("UPDATE goods SET goods_name = :namaBarang , description = :deskripsi, stock = :stok  WHERE id_goods = :id_goods");
      $queryUpdateBarang->bindParam(':namaBarang', $namaBarang);
      $queryUpdateBarang->bindParam(':deskripsi', $deskripsi);
      $queryUpdateBarang->bindParam(':stok', $stok);
      $queryUpdateBarang->bindParam(':id_goods', $idBarang);
      if ($queryUpdateBarang->execute()) { //jika query berhasil jalankan ini
        $_SESSION['edit'] = "Berhasil Update Data <b>$namaBarang</b>!!";
        header("location:goodsList.php");
        exit;
      } else { //jika query gagal jalankan ini
        $_SESSION['error'] = "Gagal Update Data <b>$namaBarang</b>!";
        header("location:goodsList.php");
        exit;
      }
    }
  }
} catch (Exception $e) {
  echo "Gagal : " . $e->getMessage();
}

// Fungsi query tambah barang masuk dan update stok barang
try {
  if (isset($_POST['barangMasuk'])) {
    $idBarang = $_POST['goods_id'];
    $penerima = $_POST['accepted'];
    $jumlah = $_POST['qty'];
    //   query untuk mendapatkan data stok barang untuk diupdate
    $queryStokBarang = $db->query("SELECT * FROM goods WHERE id_goods = '$idBarang'");
    $dataStok = $queryStokBarang->fetch();
    $stokBarang = $dataStok['stock'];
    $namaBarang = $dataStok['goods_name'];
    // operasi matematika untuk mendapatkan data stok sekarang dengan jumlah yang diinputkan
    $stokUpdate = $stokBarang + $jumlah;
    //   query untuk menambah barang masuk
    $queryBarangMasuk = $db->query("INSERT INTO goods_enter (goods_id,accepted,qty) VALUES ('$idBarang','$penerima','$jumlah')");
    //   query untuk mengupdate data stok barang
    $queryUpdateStock = $db->query("UPDATE goods SET stock = '$stokUpdate' 
      WHERE id_goods ='$idBarang'");
    // jika kedua query berhasil dijalankan maka pengkondisian dibawah di jalankan
    if ($queryBarangMasuk && $queryUpdateStock) {
      $_SESSION['add'] = "berhasil menambahkan <b>$namaBarang</b> Masuk Sebanyak <b>$jumlah</b>, Stok Menjadi <b>$stokUpdate</b>!!";
      header('location:goodsEnter.php');
      exit();
    }
  }
} catch (Exception $e) {
  echo 'Gagal :' . $e->getMessage();
}
// fungsi query edit barang masuk dan update stock barang
if (isset($_POST['editEnter'])) {
  $id_masuk = $_POST['id_enter'];
  $id_barang = $_POST['goods_id'];
  $penerima = $_POST['accepted'];
  $jumlah = $_POST['qty'];
  // dapatkan data dari stok barang nya dulu
  $goodsData = $db->query("SELECT * FROM goods WHERE id_goods = '$id_barang'");
  echo "<pre>";
  $catchGoodsData = $goodsData->fetch();
  $goodsStockNow = $catchGoodsData['stock'];
  $goodsNameForEdit = $catchGoodsData['goods_name'];
  // lalu data qty dari goods_enter
  $dataGoodsEnter = $db->query("SELECT * FROM goods_enter WHERE id_enter = '$id_masuk'");
  $catchGoodsEnterData = $dataGoodsEnter->fetch();
  $enterStockNow = $catchGoodsEnterData['qty'];
  if ($jumlah > $enterStockNow) {
    // jika jumlah yang dimasukan lebih dari stok yang ada di dalam Qty tabel goods_enter maka jalankan code dibawah
    $selisih = $jumlah - $enterStockNow;
    $hasilSelisih = $goodsStockNow + $selisih;
    $queryUpdateStock  = $db->query("UPDATE goods SET stock = '$hasilSelisih' WHERE id_goods = '$id_barang'");
    $quetyUpdateEnter = $db->query("UPDATE goods_enter SET accepted = '$penerima', qty = '$jumlah' WHERE id_enter = '$id_masuk'");
    if ($queryUpdateStock && $quetyUpdateEnter) {
      $_SESSION['edit'] = "Berhasil Update Data <b>$goodsNameForEdit</b>!! menjadi $jumlah";
      header("location:goodsEnter.php");
    } else {
      header("location:goodsEnter.php");
    }
  } else {
    // jika jumlah yang diinputkan lebih kecil dari data qty pada tabel goods_enter code dibawah akan dijalankan
    $selisih = $enterStockNow - $jumlah;
    $hasilSelisih = $goodsStockNow - $selisih;
    $queryUpdateStock  = $db->query("UPDATE goods SET stock = '$hasilSelisih' WHERE id_goods = '$id_barang'");
    $quetyUpdateEnter = $db->query("UPDATE goods_enter SET accepted = '$penerima', qty = '$jumlah' WHERE id_enter = '$id_masuk'");
    if ($queryUpdateStock && $quetyUpdateEnter) {
      $_SESSION['edit'] = "Berhasil Update Data <b>$goodsNameForEdit</b>!! menjadi $jumlah";
      header("location:goodsEnter.php");
    } else {
      header("location:goodsEnter.php");
    }
  }
}
//Tambah barang keluar dan update stok barang
try {
  if (isset($_POST['barangKeluar'])) {
    // tangkap data didalam form yang menggunakan method POST
    $idBarang = $_POST['goods_id'];
    $idBarangKeluar = $_POST['id_out'];
    $penerima = $_POST['receiver'];
    $jumlah = $_POST['qty'];
    // tangkap data barang untuk mendapatkan field stok yang nanti akan digunakan dalam update stok
    $queryGoodsData = $db->query("SELECT * FROM goods WHERE id_goods = '$idBarang'");
    $getdataStok = $queryGoodsData->fetch();
    $namaBarang = $getdataStok['goods_name'];
    $dataStock = $getdataStok['stock'];
    // bila stok mencukupi jalankan ini
    if ($dataStock >= $jumlah) {
      $updateStockGoods = $dataStock - $jumlah;
      // query untuk menambahkan data kedalam tabel goods_out
      $queryGoodsOut = $db->query("INSERT INTO goods_out (goods_id,receiver,qty) 
      VALUES ('$idBarang','$penerima','$jumlah')");
      // query untuk mengupdate data stok pada tabel barang/goods
      $queryUpdateStokGoods = $db->query("UPDATE goods SET stock = '$updateStockGoods' WHERE id_goods = '$idBarang'");
      if ($queryGoodsOut && $queryUpdateStokGoods) {
        $_SESSION['add'] = "Stok <b>$namaBarang</b> berhasil <b>Keluar</b> dengan jumlah <b>$jumlah</b> Stok Barang Sekarang <b>$updateStockGoods</b>!";
        header("location:goodsOut.php");
        exit;
      }
    } else {
      // bila stok tidak mencukupi Akan tampil Pesan Alert ini
      $_SESSION['error'] = "Maaf Stok <b>$namaBarang</b> Tidak Mencukupi, Stok tersedia <b>$dataStock</b> yang ada Inputkan <b>$jumlah</b>  !!";
      header("location:goodsOut.php");
      exit();
    }
  }
} catch (Exception $e) {
  echo "Gagal :" . $e->getMessage();
}

// edit barang keluar dan update stok barang
if (isset($_POST['editOut'])) {
  // tangkap data didalam form modals nya
  $idBarang = $_POST['goods_id'];
  $id_keluar = $_POST['id_out'];
  $penerima = $_POST['receiver'];
  $namaBarang = $_POST['goods_name'];
  $jumlah = $_POST['qty'];
  // panggil data goods untuk mengambil data yang diperlukan
  $queryGoods = $db->query("SELECT * FROM goods WHERE id_goods = '$idBarang'");
  $getStockGoods = $queryGoods->fetch();
  $stockGoods = $getStockGoods['stock'];
  if ($stockGoods >= $jumlah) {
    // jika stok barang mencukupi jalankan code dibawah
    $queryGoodsOut = $db->query("SELECT * FROM goods_out WHERE id_out = '$id_keluar'");
    $getQtyOut = $queryGoodsOut->fetch();
    $qtyOut = $getQtyOut['qty'];

    if ($jumlah >= $qtyOut) {
      // jika jumlah yang di input lebih besar atau sama dengan qty pada tabel goods_out code dibawah dijalankan
      $selisih = $jumlah - $qtyOut;
      $hasilSelisih = $stockGoods - $selisih;
      $queryUpdateStockGoods = $db->query("UPDATE goods SET stock = '$hasilSelisih' WHERE id_goods = '$idBarang'");
      $queryUpdateQtyOut = $db->query("UPDATE goods_out SET receiver = '$penerima', qty = '$jumlah' WHERE id_out = '$id_keluar'");
      if ($queryUpdateStockGoods && $queryUpdateQtyOut) {
        $_SESSION['edit'] = "Berhasil Edit Data <b>$namaBarang</b> Menjadi <b>$jumlah</b>!!";
        header("location:goodsOut.php");
      } else {
        header("location:goodsOut.php");
      }
    } else {
      // jika jumlah yang diinputkan lebih kecil dari dari Qty pada tabel goods_out code dibawah dijalankan
      $selisih = $qtyOut - $jumlah;
      $hasilSelisih = $stockGoods + $selisih;
      $queryUpdateStockGoods = $db->query("UPDATE goods SET stock = '$hasilSelisih' WHERE id_goods = '$idBarang'");
      $queryUpdateQtyOut = $db->query("UPDATE goods_out SET receiver = '$penerima', qty = '$jumlah' WHERE id_out = '$idkeluar'");
      if ($queryUpdateStockGoods && $queryUpdateQtyOut) {
        $_SESSION['edit'] = "Berhasil Edit Data <b>$namaBarang</b> Menjadi <b>$jumlah</b>!!";
        header("location:goodsOut.php");
      } else {
        header("location:goodsOut.php");
      }
    }
  } else {
    // bila stok tidak mencukupi
    $_SESSION['error'] = "Maaf Stok <b>$namaBarang</b> Tidak Mencukupi, Stok Tersedia <b>$stockGoods</b> Yang Diinputkan <b>$jumlah</b>!!";
    header("location:goodsOut.php");
    exit();
  }
}
// Simpan Data userList.php
if (isset($_POST['simpanUser'])) {
  //tangkap data dari form
  $name = $_POST['name'];
  $email = $_POST['email'];
  $pass = $_POST['password'];
  // Enkripsi password sebelum disimpan ke database
  $hashed_password = password_hash($pass, PASSWORD_DEFAULT);
  //simpan ke database
  // penggunaan prepare bukannya query adalah untuk menghindari SQL injection(SQL injection merupakan salah satu teknik peretasan yang digunakan untuk memasukkan sebuah parameter pada website ataupun sebuah statement query secara sengaja dengan tujuan untuk mendapatkan data user) 
  $query_add_user = $db->prepare("INSERT INTO users(name, email, password) VALUES (:name, :email, :password)");
  $query_add_user->bindParam(':name', $name);
  $query_add_user->bindParam(':email', $email);
  $query_add_user->bindParam(':password', $hashed_password);
  if ($query_add_user->execute()) {
    $_SESSION['register'] = "Berhasil Menambah Data Admin : <b>$name</b>! ";
    header("location:usersList.php");
    exit();
  } else {
    $_SESSION['error'] = "Menambah Akun Baru Gagal, Silahkan Coba Lagi!!";
    header("location:usersList.php");
    exit();
  }
}
// Edit Data UsersList.php
if (isset($_POST['editUser'])) {
  $idUser = $_POST['id_user'];
  $namaUser = $_POST['name'];
  $password = $_POST['password'];
  // enkripsi data password
  if (empty($password)) {
    // jika password tidak di ingin diubah

    $GetOldPassword = $db->query("SELECT password FROM users WHERE id_user = $idUser");
    $getIndexPass = $GetOldPassword->fetch(); //dapatkan index password
    $thePassword = $getIndexPass['password']; //dapatkan data password
    $queryEditUser = $db->prepare("UPDATE users SET name = :name ,password = :password
    WHERE id_user = :idUser");
    $queryEditUser->bindParam(':name', $namaUser);
    $queryEditUser->bindParam(':password', $thePassword);
    $queryEditUser->bindParam(':idUser', $idUser);
    // pengkondisian 
    if ($queryEditUser->execute()) {
      // jika query berhasil maka code dibawah di jalankan
      $_SESSION['edit'] = "User Data: <b>$namaUser</b> Berhasil <b>Diupdate</b>!";
      header("location:usersList.php");
      exit();
    } else {
      // jika gagal maka code dibawah dijalankan
      $_SESSION['delete'] = "User Data: <b>$namaUser</b> Gagal <b>Diupdate</b>!";
      header("location:usersList.php");
      exit();
    }
  } else {
    // jika password dimasukan jalankan code dibawah
    $hash_pass = password_hash($password, PASSWORD_DEFAULT);
    $queryEditUser = $db->prepare("UPDATE users SET name = :name, password = :password 
    WHERE id_user = :idUser");
    $queryEditUser->bindParam(':name', $namaUser);
    $queryEditUser->bindParam(':password', $hash_pass);
    $queryEditUser->bindParam(':idUser', $idUser);
    // pengkondisian 
    if ($queryEditUser->execute()) {
      // jika query berhasil maka code dibawah di jalankan
      $_SESSION['edit'] = "User Data: <b>$namaUser</b> Berhasil <b>Diupdate</b>!";
      header("location:usersList.php");
      exit();
    } else {
      // jika gagal maka code dibawah dijalankan
      $_SESSION['delete'] = "User Data: <b>$namaUser</b> Gagal <b>Diupdate</b>!";
      header("location:usersList.php");
      exit();
    }
  }
}
