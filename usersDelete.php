<?php
include("connection.php");
// LEBIH TELITI LAGI SAAT MENULIS QUERY!!
try {
  if (isset($_POST['deleteUser'])) {
    $idUser = $_POST['id_user'];
    $nameUser = $_POST['name'];
    $queryDeleteUser = $db->query("DELETE FROM users WHERE id_user = '$idUser'");
    if ($queryDeleteUser) {
      $_SESSION['delete'] = "Data Admin (<b>$nameUser</b>) Berhasil Dihapus!";
      header("location:usersList.php");
      exit();
    } else {
      $_SESSION['delete'] = "Data <b>$nameUser</b> Gagal Dihapus Dihapus!";
      header("location:usersList.php");
    }
  }
} catch (Exception $e) {
  echo "Gagal : " . $e->getMessage();
}
