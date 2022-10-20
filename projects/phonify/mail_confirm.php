<!DOCTYPE html>
<html lang="en">
<head>
    <title>Daniele Di Spirito | Sign Up</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--===============================================================================================-->
    <link rel="icon" type="image/png" href="../../favicon.ico"/>
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
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
session_start();
if(isset($_SESSION['session']) and isset($_SESSION['codice'])) {
    $account = json_decode(base64_decode($_SESSION['session']));
    $codice = $_SESSION['codice'];
    if($codice === -1) {
        header('Location: index.php');
    }
} else {
    header('Location: login.php');
}

$error = '';
$alert = '';
if(isset($_POST['numero'])) {
    if ('' . $_POST['numero'] === '' . $_SESSION['codice']) {
        $_SESSION['codice'] = -1;
        header('Location: index.php');
    } else {
        $error = 'Codice di conferma non valido!';
    }
} else {
    $message = '
        	<html>
        		<head>
        			<title>Codice di conferma</title>
        		</head>
        		<body>
        			<h3>Codice di conferma</h3>
        			<p>Inserisci questo codice di conferma: ' . $codice . '</p>
        		</body>
        	</html>
        ';
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-type: text/html; charset=utf-8';
    $headers[] = 'From: "Daniele Di Spirito" <dispiritodaniele.noreply@gmail.com>';
    if(mail($account->email, 'Codice di conferma', $message, implode("\r\n", $headers))) {
        $alert = 'Mail mandata con successo!';
    } else {
        $error = 'Invio mail fallito!';
    }
}
?>
<div class="limiter">
    <div class="container-login100" style="background: #666666;">
        <div class="wrap-login100 p-t-30 p-b-50">
				<span class="login100-form-title p-b-41">
					EMAIL DI CONFERMA
				</span>
            <form class="login100-form validate-form p-b-33 p-t-5" name="form" method="POST" action="">

                <?php
                $message = '
                    <div class="input100" style="margin-top: 2rem; margin-bottom: 3rem; text-align: center; padding: 0 35px 0 35px;">
                        È stata inviata una mail a <b>' .  $account->email . '</b> con un codice di conferma
                    </div>
                    <input type="text" maxlength="4" name="numero" class="input100" style="border: 2px solid black; width: 80%; margin: 0 auto; text-align: center; padding: 0;">
                    <div class="container-login100-form-btn m-t-32">
                        <button class="login100-form-btn" type="submit" name="submit">
                            CONFERMA
                        </button>
                    </div>';
                if($alert !== '') {
                    $message .= '<div class="text-center input100" style="color: blue; padding: 0 35px 0 35px; margin-top: 2rem;">
                    	' . $alert . '
                	</div>';
                }
                if($error !== '') {
                    $message .= '<div class="text-center input100" style="color: red; padding: 0 35px 0 35px; margin-top: 2rem;">
                        ' . $error . '
                	</div>';
                }
                echo $message;
                ?>


            </form>

        </div>
    </div>
</div>

<div id="dropDownSelect1"></div>

</body>
</html>