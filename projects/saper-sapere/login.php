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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(!(isset($_POST['email'], $_POST['password']) and $_POST['email'] != '' and $_POST['password'] != '')) {
        $errore = 'Riempi tutti i campi!';
    }

    if(!(isset($errore))) {
        $email = strtolower($_POST['email']);
        $password = hash('sha256', $_POST['password']);
        
        $stmt = $mysqli->prepare('SELECT 1 FROM utenti_ss WHERE email = ? AND password = ?;');
        $stmt->bind_param('ss', $email, $password);
        $stmt->execute();
        $stmt->bind_result($accountExists);
        $stmt->fetch();

        if($accountExists !== 1) {
            $errore = 'Le credenziali non sono corrette!';
        }

        $stmt->close();

        if(!isset($errore)) {
            $stmt = $mysqli->prepare('SELECT nome, ruolo FROM utenti_ss WHERE email = ?;');
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $stmt->bind_result($name, $role);
            $stmt->fetch();

            $_SESSION['account'] = 
            base64_encode(
                json_encode([
                    'email' => $email,
                    'name' => $name,
                    'role' => $role
                ])
            );

            $stmt->close();

            header('Location: index.php');
        }
    }
}

?>

<head>

    <!--- basic page needs
   ================================================== -->
    <meta charset="utf-8">
    <title>Login | Saper sapere</title>
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
                    <li class="current"><a href="#" title="" style="cursor: default;">Accedi</a></li>
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

                    <form name="contactForm" id="contactForm" method="post" action="">

                        <fieldset>
                            
                            <div class="form-field">
                                <span style="float: left; width: 6rem; height: 6rem; border: black 1.7px solid; text-align: center; font-size: 25px; line-height: 2.3; background-color: rgba(0,0,0,0.1);"><i class="bi bi-at" style="font-size: 25px"></i></span>
                                <input name="email" type="email" id="cEmail" class="full-width" required placeholder="Email" value="" style="width: 90%;  text-transform: lowercase;">
                            </div>

                            <script>
                                const seeUnseePassword = () => {
                                    if(document.querySelector(`input[name="password"]`).type == 'password') {
                                        document.querySelectorAll(`i.bi`)[1].classList.replace('bi-eye', 'bi-eye-slash');
                                        document.querySelector(`input[name="password"]`).type = 'text'
                                    } else {
                                        document.querySelectorAll(`i.bi`)[1]?.classList.replace('bi-eye-slash', 'bi-eye');
                                        document.querySelector(`input[name="password"]`).type = 'password'
                                    }
                                }
                            </script>

                            <div class="form-field">
                                <span style="float: left; width: 6rem; height: 6rem; border: black 1.7px solid; text-align: center; font-size: 25px; line-height: 2.3; background-color: rgba(0,0,0,0.1);"><i class="bi bi-eye" style="font-size: 25px; cursor: pointer" onclick="seeUnseePassword(0);"></i></i></span>
                                <input name="password" type="password" id="cEmail" class="full-width" required placeholder="Password" value="" style="width: 90%;">
                            </div>

                            <?php if(isset($errore)): ?>
                                <div class="errore"><?= $errore ?></div>
                            <?php endif; ?>

                            <button type="submit" class="submit button-primary">Accedi</button>
                            <div style="margin-top: 15px">Non hai un account? <a href="signup.php">Registrati</a></div>
                            <div style="margin-top: 0px">Non ricordi la tua password? <a href="recupero-password.php">Recupera password</a></div>

                        </fieldset>

                    </form> <!-- Form End -->

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