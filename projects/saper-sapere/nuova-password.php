<!DOCTYPE html>
<!--[if IE 8 ]><html class="no-js oldie ie8" lang="en"> <![endif]-->
<!--[if IE 9 ]><html class="no-js oldie ie9" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!-->
<html class="no-js" lang="en"> <!--<![endif]-->

<?php

include 'config.php';

$nomi = array();
$stmt = $mysqli->prepare('SELECT nome FROM `categorie` WHERE codCategoria IN (SELECT codCategoria FROM articoli);');
$stmt->execute();
if ($stmt->bind_result($nome)) {
    while ($stmt->fetch()) {
        $nomi[] = $nome;
    }
    $stmt->close();
}

include 'fromSSFtoHTML.php';

session_start();

if($_SERVER['REQUEST_METHOD'] === 'GET') {
    if(isset($_GET['cod'])) {
        $codice = $_GET['cod'];
        if(!isset($_SESSION['codice']) or $codice !== $_SESSION['codice']) {
            $errore = 'Non esiste una richiesta di recupero per questa mail';
        }
    } else {
        die('
        <html>
        <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
        <h1 style="text-align: center; font-weight: 400">URL non valido</h1>
        <a href="index.php" style="text-align: center; font-weight: 500; display: flex; justify-content: center; font-size: 20px; margin-bottom: 30px; text-decoration: none; color: darkblue;">
            Visita la home
        </a>
        <div style="text-align: center; font-size: 20px;">Reindirizzamento automatico tra <span id="secondi">5</span></div>
        <script>
        setInterval(() => {
            document.location.href = "index.php";
        }, document.querySelector("span#secondi").innerHTML * 1000);
        setInterval(() => {
            document.querySelector("span#secondi").innerHTML = document.querySelector("span#secondi").innerHTML - 1;
        }, 1000);
        </script>
        </html>
        ');
    }
} else {
    if(isset($_POST['password'], $_POST['confermaPassword'], $_POST['email']) and $_POST['password'] !== '' and $_POST['confermaPassword'] !== '' and $_POST['password'] === $_POST['confermaPassword'] and $_POST['email'] !== '') {
        $email = $_POST['email'];
        $password = hash('sha256', $_POST['password']);
        $stmt = $mysqli->prepare('UPDATE utenti_ss SET password = ? WHERE email = ?');
        $stmt->bind_param('ss', $password, $email);
        $stmt->execute();

        unset($_SESSION['codice']);
        die('
        <html>
        <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
        <h1 style="text-align: center; font-weight: 400">Password modificata!</h1>
        <a href="login.php" style="text-align: center; font-weight: 500; display: flex; justify-content: center; font-size: 20px; margin-bottom: 30px; text-decoration: none; color: darkblue;">
            Accedi
        </a>
        <div style="text-align: center; font-size: 20px;">Reindirizzamento automatico tra <span id="secondi">5</span></div>
        <script>
        setInterval(() => {
            document.location.href = "login.php";
        }, document.querySelector("span#secondi").innerHTML * 1000);
        setInterval(() => {
            document.querySelector("span#secondi").innerHTML = document.querySelector("span#secondi").innerHTML - 1;
        }, 1000);
        </script>
        </html>
        ');
    } else {
        $errore = 'Le password non coincidono';
    }
}


?>

<head>

    <!--- basic page needs
   ================================================== -->
    <meta charset="utf-8">
    <title>Reset password | Saper sapere</title>
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- mobile specific metas
   ================================================== -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <!-- CSS
   ================================================== -->
    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/vendor.css">
    <link rel="stylesheet" href="css/main.css">

    <!-- Bootstrap icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css" rel="stylesheet" />

    <!-- script
   ================================================== -->
    <script src="js/modernizr.js"></script>
    <script src="js/pace.min.js"></script>

    <!-- favicons
	================================================== -->
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link rel="icon" href="favicon.ico" type="image/x-icon">

</head>

<body id="top">

    <!-- header 
   ================================================== -->
    <header class="short-header">

        <div class="gradient-block"></div>

        <div class="row header-content">

            <div class="logo">
                <a href="index.php">Daniele Di Spirito</a>
            </div>

            <nav id="main-nav-wrap">
                <ul class="main-navigation sf-menu">
                    <li><a href="index.php" title="">Home</a></li>
                    <li class="has-children">
                        <a href="#" title="" style="cursor: default;">Categorie</a>
                        <ul class="sub-menu">
                            <?php foreach ($nomi as $nome) : ?>
                                <li><a href="categorie.php?nome=<?= strtolower($nome) ?>"><?= $nome ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                    <li><a href="about.php" title="">Chi siamo</a></li>
                    <li class="current"><a href="#" title="" style="cursor: default;">Reset password</a></li>
                    <li style="height: 10rem">
                    <!-- Translate -->
                    <div id="google_translate_element" class="text-white pl-3"></div>
                    <script type="text/javascript">
                        function googleTranslateElementInit() {
                            new google.translate.TranslateElement(
                                {pageLanguage: 'it'},
                                'google_translate_element'
                            );
                        }
                    </script>
                    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
                    <!-- Translate -->
                    </li>
                </ul>
            </nav> <!-- end main-nav-wrap -->

            <div class="triggers">
                <a class="menu-toggle" href="#"><span>Menu</span></a>
            </div> <!-- end triggers -->

        </div>

    </header> <!-- end header -->


    <!-- content
   ================================================== -->
    <section id="content-wrap" class="blog-single">

        <div class="row">

            <div class="col-twelve">

                <div class="respond">

                    <?php if(isset($errore)): ?>

                        <div class="errore"><?= $errore ?></div>

                    <?php else: ?>

                        <form name="contactForm" id="contactForm" method="post" action="">

                            <fieldset>
                                
                                <script>
                                    const seeUnseePassword = (i) => {
                                        name = (i === 0) ? 'password' : 'confermaPassword'
                                        if(document.querySelector(`input[name="${name}"]`).type == 'password') {
                                            document.querySelectorAll(`i.bi`)[i].classList.replace('bi-eye', 'bi-eye-slash');
                                            document.querySelector(`input[name="${name}"]`).type = 'text'
                                        } else {
                                            document.querySelectorAll(`i.bi`)[i]?.classList.replace('bi-eye-slash', 'bi-eye');
                                            document.querySelector(`input[name="${name}"]`).type = 'password'
                                        }
                                    }
                                </script>

                                <input name="email" value="<?=json_decode(base64_decode($codice))->email?>" type="hidden">

                                <div class="form-field">
                                    <span style="float: left; width: 6rem; height: 6rem; border: black 1.7px solid; text-align: center; font-size: 25px; line-height: 2.3; background-color: rgba(0,0,0,0.1);"><i class="bi bi-eye" style="font-size: 25px; cursor: pointer" onclick="seeUnseePassword(0);"></i></i></span>
                                    <input name="password" type="password" id="cEmail" class="full-width" required placeholder="Password" value="" style="width: 90%;">
                                </div>

                                <div class="form-field">
                                    <span style="float: left; width: 6rem; height: 6rem; border: black 1.7px solid; text-align: center; font-size: 25px; line-height: 2.3; background-color: rgba(0,0,0,0.1);"><i class="bi bi-eye" style="font-size: 25px; cursor: pointer" onclick="seeUnseePassword(1);"></i></i></span>
                                    <input name="confermaPassword" type="password" id="cEmail" class="full-width" required placeholder="Conferma password" value="" style="width: 90%;">
                                </div>

                                

                                <button type="submit" class="submit button-primary">Ripristina</button>

                            </fieldset>

                        </form> <!-- Form End -->

                    <?php endif; ?>

                </div> <!-- Respond End -->

            </div> <!-- end col-twelve -->

        </div> <!-- end row -->

    </section> <!-- end content -->


    <!-- footer
   ================================================== -->
    <footer>

        <div class="footer-bottom">
            <div class="row">

                <div class="col-twelve">
                    <div class="copyright">
                        <span>© Copyright <b>Saper sapere</b> 2023</span>
                        <span>Sviluppato da <a href="../../">Daniele Di Spirito</a></span>
                    </div>

                    <div id="go-top">
                        <a class="smoothscroll" title="Back to Top" href="#top"><i class="icon icon-arrow-up"></i></a>
                    </div>
                </div>

            </div>
        </div> <!-- end footer-bottom -->

    </footer>

    <div id="preloader">
        <div id="loader"></div>
    </div>

    <!-- Java Script
   ================================================== -->
    <script src="js/jquery-2.1.3.min.js"></script>
    <script src="js/plugins.js"></script>
    <script src="js/main.js"></script>

</body>

</html>