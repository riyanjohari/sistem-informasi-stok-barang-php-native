<?php
try {
    include("connection.php");

    // echo"berhasil:";
} catch (Exception $e) {
    echo "Gagal :" . $e->getMessage();
}
// echo"<pre>";
// print_r($_POST);
try {
    if (isset($_POST['simpan'])) {
        //tangkap data dari form
        $name = $_POST['name'];
        $email = $_POST['email'];
        $pass = $_POST['password'];
        // Enkripsi password sebelum disimpan ke database
        $hashed_password = password_hash($pass, PASSWORD_DEFAULT);
        //simpan ke database
        // penggunaan prepare bukannya query adalah untuk menghindari SQL injection(SQL injection merupakan salah satu teknik peretasan yang digunakan untuk memasukkan sebuah parameter pada website ataupun sebuah statement query secara sengaja dengan tujuan untuk mendapatkan data user) 
        $query_register = $db->prepare("INSERT INTO users(name, email, password) VALUES (:name, :email, :password)");
        $query_register->bindParam(':name', $name);
        $query_register->bindParam(':email', $email);
        $query_register->bindParam(':password', $hashed_password);
        if ($query_register->execute()) {
            $_SESSION['register'] = "Anda Berhasil Registrasi Akun, Silahkan Login!!";
            header("location:login.php");
            exit();
        } else {
            $_SESSION['error'] = "Registrasi Gagal, Silahkan Coba Lagi!!";
            header("location:register.php");
            exit();
        }
    }
} catch (Exception $e) {
    echo "Gagal :" . $e->getMessage();
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
    <title>Register</title>
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>

<body class="bg-primary">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-7">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header">
                                    <h3 class="text-center font-weight-light my-4">Create Account</h3>
                                </div>
                                <div class="card-body">
                                    <form method="POST">
                                        <div class="form-floating mb-3">
                                            <input class="form-control" name="name" id="name" type="text" placeholder="Marin" required htmlspecialchars />
                                            <label for="name">Name</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input class="form-control" name="email" id="email" type="email" placeholder="name@example.com" required htmlspecialchars />
                                            <label for="email">Email address</label>
                                        </div>
                                        <div class="form-floating mb-3 mb-md-0">
                                            <input class="form-control" name="password" id="password" type="password" placeholder="Create a password" required htmlspecialchars />
                                            <label for="password">Password</label>
                                        </div>
                                        <div class="mt-4 mb-0">
                                            <button type="submit" name="simpan" class="btn btn-primary btn-block">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer text-center py-3">
                                    <div class="small"><a href="login.php">Punya akun? Login</a></div>
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