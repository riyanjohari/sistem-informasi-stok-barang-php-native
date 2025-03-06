<?php
include("connection.php");
try {
    //menangkap data dari form
    if (isset($_POST['simpan'])) {
        $email = $_POST['email'];
        $pass = $_POST['password'];
        //pengecekan data Users di Tabel users penggunakan prepare karena perlindungan dari serangan SQL Injection dan membuat kode lebih mudah dikelola
        $queryLogin = $db->prepare("SELECT * FROM users WHERE email = :email");
        $queryLogin->bindParam(':email', $email);
        $queryLogin->execute();
        $user_data = $queryLogin->fetch();
        $nameUser = $user_data['name'];
        // pengecekan apakah data users memang ada dan verifikasi dengan password 
        if ($user_data && password_verify($pass, $user_data['password'])) {
            // jika data ada maka akan di alihkan ke goodsList.php
            $_SESSION['name'] = $user_data['name'];
            $_SESSION['login'] = 'true';
            header("location:index.php");
            exit();
        } else {
            // jika email atau password ada yang salah tidak tampil pesan alert error
            $_SESSION['error'] = "<b>Email</b> Atau <b>Password</b> Salah <br>Silahkan Coba Lagi";
            header("location:login.php");
            exit();
        }
    }
    //fungsi pengecekan bila sudah login, jika ingin mengakses halaman login akan dilempar lagi ke halaman index.php
    if (isset($_SESSION['login'])) {
        $nameUser = $_SESSION['name'];
        $_SESSION['alreadyLogin'] = "<b>$nameUser</b>, Anda Sudah Login! <b>Logout</b> Terlebih Dahulu Jika Ingin mengakses Halaman Login ";
        header("location:index.php");
        exit();
    }
} catch (Exception $e) {
    echo "gagal :" . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Login</title>
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <!-- cdn for alert error -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>

<body class="bg-primary">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-5">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header">
                                    <h3 class="text-center font-weight-light my-4">Login</h3>
                                </div>
                                <div class="card-body">
                                    <?php include("alertMassage.php"); ?>
                                    <form method="post">
                                        <div class="form-floating mb-3">
                                            <input class="form-control" name="email" id="Email" type="email" placeholder="name@example.com" required htmlspecialchars />
                                            <label for="Email">Email address</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input class="form-control" name="password" id="Password" type="password" placeholder="Password" required htmlspecialchars />
                                            <label for="Password">Password</label>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                            <button type="submit" name="simpan" class="btn btn-primary">Login</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer text-center py-3">
                                    <div class="small"><a href="register.php">belum punya akun? Register</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <div id="layoutAuthentication_footer">
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Made By &copy; Riyan</div>
                        <!-- <div>
                                <a href="#">Privacy Policy</a>
                                &middot;
                                <a href="#">Terms &amp; Conditions</a>
                            </div> -->
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
</body>

</html>