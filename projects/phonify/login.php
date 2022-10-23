<!DOCTYPE html>
<html lang="en">
<head>
	<title>Daniele Di Spirito | Login</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="../../favicon.ico"/>
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
        if(isset($_POST['username']) and isset($_POST['password'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];
            controllo(serialize(array($username, $password)));

            // database connection
            $db_host = 'localhost';
            $db_user = 'root';
            $db_password = '';
            $db_name = 'my_dispiritodaniele';
            $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
            if ($conn->connect_errno) {
                echo "Failed to connect to MySQL: " . $conn->connect_error;
                exit();
            }

            // query
            $password = hash('sha256', $password); //SHA256 password
            $stmt = $conn->prepare("SELECT email FROM utenti WHERE password=? AND username=?");
            $stmt->bind_param('ss', $password, $username); // pass password and username as string (s)
            $stmt->execute();
            if ($stmt->bind_result($email)) { // there is at least one result
                $stmt->fetch(); // insert the result in the $email variable
                if (isset($email)) {
                    session_start();
                    $_SESSION['session'] = base64_encode(json_encode(array('username' => $username, 'email' => $email, 'password' => $password)));
                    header('Location: index.php');
                } else {
                    $error = 'Credenziali errate';
                }
            }
        }

        function controllo($array) {
            global $error;
            $array = unserialize($array);
            foreach ($array as $element) {
                if (strlen($element) < 7) {
                    $error = "L'username e/o la password devono avere almeno 7 caratteri";
                    return;
                }
            }
            if (strlen($array[0]) > 30) {
                $error = "L'username deve avere meno di 30 caratteri";
                return;
            }
            foreach (str_split($array[0], 1) as $char) {
                if(!(ctype_alpha($char) or ctype_alnum($char) or $char === '_' or $char === '.')) {
                    $error = "L'username può contenere solo lettere, numeri, underscore e punto.";
                    return;
                }
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
					LOGIN
				</span>
				<form class="login100-form validate-form p-b-33 p-t-5" name="form" method="POST" action="">

					<div class="wrap-input100 validate-input">
						<input class="input100" type="text" name="username" placeholder="Username">
						<span class="focus-input100" data-placeholder="&#xe82a;"></span>
					</div>

					<div class="wrap-input100 validate-input">
						<input class="input100" type="password" name="password" placeholder="Password">
						<span class="focus-input100" data-placeholder="&#xe80f;"></span>
					</div>

					<div class="container-login100-form-btn m-t-32">
						<button class="login100-form-btn" type="submit" name="submit">
							Accedi
						</button>
					</div>
                    <br>
                    <div class="text-center" style="color: red">
                        <?=$error?>
                    </div>
                    <br>
                    <p class="text-center">
                        Clicca <a href="../phonify/register.php">qui</a> per registrarsi
                    </p>

				</form>

			</div>
		</div>
	</div>
	

	<div id="dropDownSelect1"></div>

</body>
</html>