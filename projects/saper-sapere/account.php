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

if (isset($_SESSION['account'])) {
	$account = json_decode(base64_decode($_SESSION['account']));
}
$logged = isset($account);

if(!$logged) {
    die('
        <html>
        <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
        <h1 style="text-align: center; font-weight: 400">Devi essere loggato per accedere a questa pagina</h1>
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

if($logged) {
	$email = $account->email;
	$stmt = $mysqli->prepare('SELECT fotoProfilo, descrizione FROM utenti_ss WHERE email = ?;');
	$stmt->bind_param('s', $email);
	$stmt->execute();
	$stmt->bind_result($foto, $descrizione);
	$stmt->fetch();
	$stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if(!isset($_POST['name'])) die('Ci sono campi mancanti');
    
    if(isset($_FILES['fotoProfilo']) and str_starts_with($_FILES['fotoProfilo']['type'], 'image/')) {
        $fotoProfilo = file_get_contents($_FILES['fotoProfilo']['tmp_name']);
    }

    if(isset($_POST['descrizione'])) {
        $descrizione = $_POST['descrizione'];
    }
    
    $nome = $_POST['name'];

    // inserimento
    if(isset($descrizione)) {
        if(isset($fotoProfilo)) {
            $stmt = $mysqli->prepare('UPDATE utenti_ss SET nome = ?, descrizione = ?, fotoProfilo = ? WHERE email = ?;');
            $stmt->bind_param('ssbs', $nome, $descrizione, $fotoProfilo, $email);
            $stmt->send_long_data(2, $fotoProfilo);
            $stmt->execute();
            $stmt->close();
        } else {
            $stmt = $mysqli->prepare('UPDATE utenti_ss SET nome = ?, descrizione = ? WHERE email = ?;');
            $stmt->bind_param('sss', $nome, $descrizione, $email);
            $stmt->execute();
            $stmt->close();
        }
    } else {
        if(isset($fotoProfilo)) {
            $stmt = $mysqli->prepare('UPDATE utenti_ss SET nome = ?, fotoProfilo = ? WHERE email = ?;');
            $stmt->bind_param('sbs', $nome, $fotoProfilo, $email);
            $stmt->send_long_data(1, $fotoProfilo);
            $stmt->execute();
            $stmt->close();
        } else {
            $stmt = $mysqli->prepare('UPDATE utenti_ss SET nome = ? WHERE email = ?;');
            $stmt->bind_param('ss', $nome, $email);
            $stmt->execute();
            $stmt->close();
        }
    }
    

}

?>

<head>

    <!--- basic page needs
   ================================================== -->
    <meta charset="utf-8">
    <title>Account | Saper sapere</title>
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
					<li class=""><a href="index.php" title="">Home</a></li>									
					<li class="has-children">
						<a href="#" title="" style="cursor: default;">Categorie</a>
						<ul class="sub-menu">
							<?php foreach($nomi as $nome): ?>
								<li><a href="categorie.php?nome=<?=strtolower($nome)?>"><?=$nome?></a></li>
							<?php endforeach; ?>
						</ul>
					</li>
					<li class=""><a href="about.php" title="">Chi siamo</a></li>
					<?php if($logged): ?>
						<li class="has-children current">
						<a href="#" title="" style="cursor: default;">Account</a>
						<ul class="sub-menu">
							<li><a name="<?=$account->email?>" href="account.php" style="display: flex; align-items: center"><?= (!($foto === '' or is_null($foto))) ? '<img width="50" height="50" style="width: 30px; height: 30px; border-radius: 50%; margin-left: -10px" id="fotoProfilo" src="data:image/*;base64,'.base64_encode($foto).'"></img>': '<i class="bi bi-person"></i>' ?>&nbsp;&nbsp;&nbsp;<?=$account->name?></a></li>
							<li><a href="api/logout.php"><i class="bi bi-box-arrow-right"></i>&nbsp;&nbsp;Logout</a></li>
						</ul>
					</li>
					<?php if($account->role === 1): ?>
						<li class=""><a href="nuovo-articolo.php">Scrivi articolo</a></li>
					<?php endif; ?>
					<?php else: ?>
						<li><a href="login.php" title="">Accedi</a></li>
					<?php endif; ?>
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

                    <form name="contactForm" id="contactForm" method="post" action="" enctype='multipart/form-data'>

                        <fieldset>
                            
                            <?php if(isset($errore)): ?>
                                <div class="errore"><?= $errore ?></div>
                            <?php endif; ?>

                            <div class="form-field">
                                <label for="name">Nome utente</label>
                                <input name="name" type="text" class="full-width" required placeholder="Inserire il proprio nome utente" value="<?= $account->name ?>">
                            </div>

                            <div class="form-field">
                                <label for="email">Email (non modificabile)</label>
                                <input name="email" type="text" class="full-width" readonly placeholder="" value="<?= $account->email ?>">
                            </div>
                            

                            <script>
                                const countCaratteri = (i) => {
                                    document.querySelectorAll('span#nCaratteri')[i].innerHTML = document.querySelectorAll('textarea')[i].value.length
                                }
                            </script>

                            <div class="form-field" style="margin-top: 3rem">
                                <label for="descrizione">Descrizione</label>
                                <textarea name="descrizione" type="text" class="full-width" placeholder="Scrivi in breve chi sei" style="resize: vertical; min-height: 24rem !important;" maxlength="500" onkeyup="countCaratteri(0);" onkeypress="countCaratteri(0);"><?= $descrizione ?></textarea>
                            </div>

                            <div class="form-field">
                                <label for="fotoProfilo">Foto profilo</label>
                                <input name="fotoProfilo" type="file" class="full-width" accept="image/*"></input>
                            </div>

                            <button type="submit" class="submit button-primary" style="margin-top: 5rem">Aggiorna account</button>
                            
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