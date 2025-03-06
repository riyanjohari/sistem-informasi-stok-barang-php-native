<?php
//jika belum login
if (!isset($_SESSION['login'])) {
  $_SESSION['error'] = "Silahkan <b>Login</b> Terlebih Dahulu!!!!";
  header("location:login.php");
  exit();
}
