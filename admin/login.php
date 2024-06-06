<?php
require_once('../config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $qry = $conn->query("SELECT * FROM users WHERE username = '$username'");
    if ($qry->num_rows > 0) {
        $user = $qry->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['type'];
            header('Location: dashboard.php');
            exit();
        } else {
            $error = "Contraseña incorrecta.";
        }
    } else {
        $error = "Usuario no encontrado.";
    }
}
?>

<!DOCTYPE html>
<html lang="en" style="height: auto;">
<?php require_once('inc/header.php') ?>

<body class="hold-transition login-page">
    <script>
        start_loader()
    </script>
    <style>
        body {
            width: calc(100%);
            height: calc(100%);
            background-image: url('<?= validate_image($_settings->info('cover')) ?>');
            background-repeat: no-repeat;
            background-size: cover;
        }

        #logo-img {
            width: 15em;
            height: 15em;
            object-fit: scale-down;
            object-position: center center;
        }
    </style>
    <div class="login-box">
        <center><img src="<?= validate_image($_settings->info('logo')) ?>" alt="System Logo" class="img-thumbnail rounded-circle" id="logo-img"></center>
        <div class="clear-fix my-2"></div>
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="./" class="h1"><b>Admin Login</b></a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Ingresa como Administrador</p>
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>
                <form id="login-frm" action="" method="post">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="username" autofocus placeholder="Username">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" name="password" placeholder="Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-8">
                            <a href="<?php echo base_url ?>">Volver a la tienda</a>
                        </div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Ingresar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="plugins/jquery/jquery.min.js"></script>
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="dist/js/adminlte.min.js"></script>
    <script>
        $(document).ready(function() {
            end_loader();
        })
    </script>
</body>
</html>
