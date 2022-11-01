<!DOCTYPE html>
<html lang="en">

<head>
    <title>Phonify | Recupero password</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--===============================================================================================-->
    <link rel="icon" type="image/png" href="../../favicon.ico" />
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css" rel="stylesheet" />
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/daterangepicker/daterangepicker.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="css/util.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <!--===============================================================================================-->
</head>

<body>
    <?php
    $error = '';
    $alert = '';
    if (isset($_GET['cod'])) {
        $cod = base64_decode($_GET['cod']);
        $get_password = substr($cod, 0, 64);
        $id = intval(substr($cod, 64));
        // database connection
        include 'config.php';
        // query
        $stmt = $mysqli->prepare("SELECT password, username, email FROM utenti WHERE idUtente = ?");
        $stmt->bind_param('i', $id); // pass password and username as string (s)
        $stmt->execute();
        if ($stmt->bind_result($real_password, $username, $email)) { // there is at least one result
            $stmt->fetch(); // insert the result in the $email variable
            if (!(isset($real_password) and $real_password === $get_password)) {
                die('Codice non valido');
            } else {
                ob_start();
                session_start();
                $_SESSION['recupero_password'] = $_GET['cod'];
                session_write_close();
                ob_end_flush();
            }
        }
    } else {
        die('Copiare tutto il link (manca il codice di controllo)');
    }

    ?>
    <div class="limiter">
        <div class="container-login100" style="background: #666666;">

            <a href="index.php">
                <i class="bi bi-arrow-left" style="position: absolute; left: 5%; top: 5%; color: white; font-size: 2.5rem; "></i>
            </a>

            <div class="wrap-login100 p-t-30 p-b-50">
                <span class="login100-form-title p-b-41">
                    RIPRISTINO PASSWORD
                </span>
                <form class="login100-form validate-form p-b-33 p-t-5" name="form" method="POST" action="api/nuova-password.php">

                    <input style="display: none" value="<?=$_GET["cod"]?>" name="cod">
                    <input style="display: none" value="<?=$id?>" name="id">
                    
                    <div class="wrap-input100" style="text-align: center">Username:&nbsp;<b><?= $username ?></b></div>
                    <div class="wrap-input100" style="text-align: center">Email:&nbsp;<b><?= $email ?></b></div>
                    <div class="wrap-input100 validate-input">
						<input class="input100" type="password" name="new_password" placeholder="Password">
						<span class="focus-input100" data-placeholder="&#xe80f;"></span>
                        <button type="button" style="position: absolute; right: 5%; top: 3rem;" onclick="togglePassword()"><i class="bi bi-eye" style="font-size: 20px"></i></button>
					</div>

                    <div class="container-login100-form-btn m-t-32">
                        <button class="login100-form-btn" type="submit" name="submit">
                            Reimposta password
                        </button>
                    </div>
                    <br>
                    <div class="text-center" style="color: red">
                        <?= $error ?>
                    </div>
                    <div class="text-center" style="color: blue">
                        <?= $alert ?>
                    </div>
                </form>

            </div>
        </div>
    </div>


    <div id="dropDownSelect1"></div>

    <script>
        const togglePassword = () => {
            eyeIcon = document.getElementsByTagName('i')[1]
            if(eyeIcon.classList.contains('bi-eye')) {
                eyeIcon.classList.add('bi-eye-slash')
                eyeIcon.classList.remove('bi-eye')
                document.querySelector('input[name=new_password]').type = 'text'
            } else {
                eyeIcon.classList.add('bi-eye')
                eyeIcon.classList.remove('bi-eye-slash')
                document.querySelector('input[name=new_password]').type = 'password'
            }
        }

    </script>

</body>

</html>