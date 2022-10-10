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
        $error = '';
        if(isset($_POST['username']) and isset($_POST['email']) and isset($_POST['password'])) {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            controllo(serialize(array($username, $email, $password)));

            if(empty($error)) {
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


                // control query username
                $stmt = $conn->prepare("SELECT 1 FROM accesso WHERE username = ?");
                $stmt->bind_param('s', $username);
                if ($stmt->execute()) {
                    $stmt->bind_result($result);
                    $stmt->fetch();
                    if ($result === 1) {
                        $error = 'Questo username esiste già!';
                    }
                }
                $stmt->close();

                // control query email
                if (empty($error)) {
                    $stmt = $conn->prepare("SELECT 1 FROM accesso WHERE email = ?");
                    $stmt->bind_param('s', $email);
                    if ($stmt->execute()) {
                        $stmt->bind_result($result);
                        $stmt->fetch();
                        if ($result === 1) {
                            $error = 'Questa email è già utilizzata!';
                        }
                    }
                    $stmt->close();
                }

                // query
                $password = hash('sha256', $password); //SHA256 password
                if (empty($error)) {
                    $stmt = $conn->prepare("INSERT INTO accesso(username, email, password) VALUES (?,?,?)");
                    $stmt->bind_param('sss', $username, $email, $password); // pass password and username as string (s)
                    if ($stmt->execute()) {
                        session_start();
                        $_SESSION['session'] = base64_encode(json_encode(array('username' => $username, 'email' => $email, 'password' => $password)));
                        header('Location: index.php');
                    } else {
                        $error = 'Inserimento non avvenuto';
                    }
                }
            }
        }

        function controllo($array) {
            global $error;
            $array = unserialize($array);
            for($i = 0; $i < count($array); $i++) {
                if($i == 1) { continue; }
                if(strlen($array[$i]) < 7) {
                    $error = "L'username e/o la password devono avere almeno 7 caratteri";
                    return;
                }
            }
            if (strlen($array[0]) > 30) {
                $error = "L'username deve avere meno di 30 caratteri";
                return;
            }
            if (strlen($array[1]) > 100) {
                $error = "L'email deve avere meno di 100 caratteri";
                return;
            }
            foreach (str_split($array[0], 1) as $char) {
                if(!(ctype_alpha($char) or ctype_alnum($char) or $char === '_' or $char === '.')) {
                    $error = "L'username può contenere solo lettere, numeri, underscore e punto.";
                    return;
                }
            }
            if(!preg_match('/^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-z]+)$/i', $array[1])) {
                $error = "L'email deve essere del tipo local-part@doma.in";
                return;
            }
        }
    ?>
<div class="limiter">
    <div class="container-login100" style="background: -webkit-linear-gradient(left, #a445b2, #d41872, #fa4299);">
        <div class="wrap-login100 p-t-30 p-b-50">
				<span class="login100-form-title p-b-41">
					SIGN UP
				</span>
            <form class="login100-form validate-form p-b-33 p-t-5" name="form" method="POST" action="">

                <div class="wrap-input100 validate-input">
                    <input class="input100" type="text" name="username" placeholder="Username">
                    <span class="focus-input100" data-placeholder="&#xe82a;"></span>
                </div>

                <div class="wrap-input100 validate-input">
                    <input class="input100" type="email" name="email" placeholder="Email">
                    <span class="focus-input100" data-placeholder="@"></span>
                </div>

                <div class="wrap-input100 validate-input">
                    <input class="input100" type="password" name="password" placeholder="Password">
                    <span class="focus-input100" data-placeholder="&#xe80f;"></span>
                </div>

                <div class="container-login100-form-btn m-t-32">
                    <button class="login100-form-btn" type="submit" name="submit">
                        Registrati
                    </button>
                </div>
                <br>
                <div class="text-center" style="color: red">
                    <?=$error?>
                </div>
                <br>
                <p class="text-center">
                    Clicca <a href="login.php">qui</a> per accedere
                </p>

            </form>

        </div>
    </div>
</div>


<div id="dropDownSelect1"></div>

<!--===============================================================================================-->
<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
<script src="vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
<script src="vendor/bootstrap/js/popper.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
<script src="vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
<script src="vendor/daterangepicker/moment.min.js"></script>
<script src="vendor/daterangepicker/daterangepicker.js"></script>
<!--===============================================================================================-->
<script src="vendor/countdowntime/countdowntime.js"></script>
<!--===============================================================================================-->
<script src="js/main.js"></script>

</body>
</html>