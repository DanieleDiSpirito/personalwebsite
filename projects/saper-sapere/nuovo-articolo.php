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

if(!($logged and $account->role === 1)) {
    die('
        <html>
        <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
        <h1 style="text-align: center; font-weight: 400">Devi essere uno scrittore per poter scrivere un tuo articolo</h1>
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
	$stmt = $mysqli->prepare('SELECT fotoProfilo FROM utenti_ss WHERE email = ?;');
	$stmt->bind_param('s', $email);
	$stmt->execute();
	$stmt->bind_result($foto);
	$stmt->fetch();
	$stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['title'], $_POST['summary'], $_POST['content'], $_POST['data'], $_POST['categoria'], $_POST['newCat'], $_POST['documento'])) {
        if($_POST['categoria'] === 'nuovaCategoria' and $_POST['newCat'] === '') die('Nome nuova categoria non specificato');
        foreach($_POST as $key => $value) {
            if($value === '' and $key !== 'newCat') die('Non sono ammessi parametri vuoti');
        }
    } else {
        die('Ci sono campi mancanti');
    }
    /*
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
    }*/
}

?>

<head>

    <!--- basic page needs
   ================================================== -->
    <meta charset="utf-8">
    <title>Nuovo articolo | Saper sapere</title>
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
						<li class="has-children">
						<a href="#" title="" style="cursor: default;">Account</a>
						<ul class="sub-menu">
							<li><a name="<?=$account->email?>" href="account.php" style="display: flex; align-items: center"><?= ($foto !== '') ? '<img width="50" height="50" style="width: 30px; height: 30px; border-radius: 50%; margin-left: -10px" id="fotoProfilo" src="data:image/*;base64,'.base64_encode($foto).'"></img>': '<i class="bi bi-person"></i>' ?>&nbsp;&nbsp;&nbsp;<?=$account->name?></a></li>
							<li><a href="api/logout.php"><i class="bi bi-box-arrow-right"></i>&nbsp;&nbsp;Logout</a></li>
						</ul>
					</li>
					<?php if($account->role === 1): ?>
						<li class="current"><a href="nuovo-articolo.php">Scrivi articolo</a></li>
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

                    <form name="contactForm" id="contactForm" method="post" action="">

                        <fieldset>
                            
                            <?php if(isset($errore)): ?>
                                <div class="errore"><?= $errore ?></div>
                            <?php endif; ?>

                            <div class="form-field">
                                <label for="title">Titolo</label>
                                <input name="title" type="text" class="full-width" required placeholder="Inserire titolo dell'articolo" value="">
                            </div>

                            <script>
                                const countCaratteri = (i) => {
                                    document.querySelectorAll('span#nCaratteri')[i].innerHTML = document.querySelectorAll('textarea')[i].value.length
                                }
                            </script>

                            <div class="form-field">
                                <label for="summary">Riassunto&nbsp;(<span id="nCaratteri">0</span>/500)&nbsp;&nbsp;<i class="bi bi-lightbulb" title="Questo riassunto è visibile anche a chi non ha effettuato l'accesso" style="cursor: help"></i></label>
                                <textarea name="summary" type="text" class="full-width" required placeholder="Scrivi in breve di cosa parlerà il tuo articolo" value="" style="resize: vertical; min-height: 24rem !important;" maxlength="500" onkeyup="countCaratteri(0);" onkeypress="countCaratteri(0);"></textarea>
                            </div>

                            <div class="form-field">
                                <label for="content">Contenuto&nbsp;(<span id="nCaratteri">0</span>/3500)&nbsp;&nbsp;<i class="bi bi-lightbulb" title="Inserire i nomi dei paragrafi tra &2{<sostituire il nome del paragrafo>}" style="cursor: help"></i></label>
                                <textarea name="content" type="text" class="full-width" required placeholder="Inserisci il tuo articolo" value="" style="resize: vertical; min-height: 30rem !important;" maxlength="3500" onkeyup="countCaratteri(1);" onkeypress="countCaratteri(1);"></textarea>
                            </div>

                            <div class="form-field">
                                <label for="data">Data di pubblicazione</label>
                                <input name="data" type="date" class="full-width" required></input>
                                <script>
                                    document.querySelector('input[name="data"]').value = (new Date()).toISOString().substr(0, 10);
                                </script>
                            </div>

                            <div class="form-field">
                                <label for="categoria">Categoria</label>
                                <select name="categoria" class="full-width">
                                </select>
                                <script>
                                    options = ''
                                    document.querySelectorAll("#main-nav-wrap > ul > li:nth-child(2) > ul > li").forEach((el) => {
                                        options += `<option value="${el.textContent}">${el.textContent}</option>`
                                    })
                                    options += '<option value="nuovaCategoria">Nuova categoria</option>'
                                    document.querySelector('select[name="categoria"]').insertAdjacentHTML('afterbegin', options)
                                </script>
                            </div>

                            <div class="form-field" id="nuovaCat" style="display: none">
                                <label for="newCat">Nuova categoria&nbsp;&nbsp;<i class="bi bi-lightbulb" title="Massimo 20 caratteri" style="cursor: help"></i></label>
                                <input name="newCat" type="text" class="full-width" maxlength="20" value="" placeholder="Inserire il nome della nuova categoria"></input>
                            </div>

                            <script>
                                document.querySelector('select[name="categoria"]').addEventListener('change', () => {
                                    if(document.querySelector('select[name="categoria"]').value === 'nuovaCategoria') {
                                        document.querySelector('#nuovaCat').style.display = 'initial'
                                        document.querySelector('input[name="newCat"]').required = true
                                    } else {
                                        document.querySelector('#nuovaCat').style.display = 'none'
                                        document.querySelector('input[name="newCat"]').required = false
                                    }
                                })
                            </script>

                            <div class="form-field">
                                <label for="documento">Documento</label>
                                <input name="documento" type="file" class="full-width" required accept="image/*,video/mp4"></input>
                            </div>

                            <button type="submit" class="submit button-primary" style="margin-top: 5rem">Pubblica l'articolo</button>
                            
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