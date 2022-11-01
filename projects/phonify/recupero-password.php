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
    if (isset($_POST['email'])) {
        $email = $_POST['email'];
        // database connection
        include 'config.php';
        // query
        $stmt = $mysqli->prepare("SELECT idUtente, password FROM utenti WHERE email=?");
        $stmt->bind_param('s', $email); // pass password and username as string (s)
        $stmt->execute();
        if ($stmt->bind_result($id, $password)) { // there is at least one result
            $stmt->fetch(); // insert the result in the $email variable
            if (isset($id) and isset($password)) {
                send_mail();
            } else {
                $error = 'Nessun account è registrato con questa mail!';
            }
        }
    }

    function send_mail() {
        global $email, $id, $password, $alert, $error;
        $codice = base64_encode($password.$id);
        $message = "
        	<html>
        		<head>
        			<title>Recupero password</title>
        		</head>
        		<body>
        			<h3>Link per il recupero password</h3>
        			<p>Clicca su questo link per recuperare la tua password: https://dispiritodaniele.altervista.org/projects/phonify/nuova-password.php?cod=$codice</p>
        		</body>
        	</html>
        ";
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=utf-8';
        $headers[] = 'From: "Daniele Di Spirito" <dispiritodaniele.noreply@gmail.com>';
        if(mail($email, 'Codice di conferma', $message, implode("\r\n", $headers))) {
            $alert = 'Mail mandata con successo!';
        } else {
            $error = 'Invio mail fallito!';
        }
    }

    ?>
    <div class="limiter">
        <div class="container-login100" style="background: #666666;">

            <a href="index.php">
                <i class="bi bi-arrow-left" style="position: absolute; left: 5%; top: 5%; color: white; font-size: 2.5rem; "></i>
            </a>

            <div class="wrap-login100 p-t-30 p-b-50">
                <span class="login100-form-title p-b-41">
                    RECUPERO PASSWORD
                </span>
                <form class="login100-form validate-form p-b-33 p-t-5" name="form" method="POST" action="">

                    <div class="wrap-input100 validate-input">
                        <input class="input100" type="email" name="email" placeholder="Email">
                        <span class="focus-input100" data-placeholder="@"></span>
                    </div>

                    <div class="container-login100-form-btn m-t-32">
                        <button class="login100-form-btn" type="submit" name="submit">
                            Invia link
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

</body>

</html>