<!DOCTYPE html>
<!--[if IE 8 ]><html class="no-js oldie ie8" lang="en"> <![endif]-->
<!--[if IE 9 ]><html class="no-js oldie ie9" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!-->
<html class="no-js" lang="en"> <!--<![endif]-->

<?php

include 'config.php';

session_start();

$idArticolo = $_GET['id'];

if (isset($_SESSION['account'])) {
	$account = json_decode(base64_decode($_SESSION['account']));
}
$logged = isset($account);

if ($logged) {

	$email = $account->email;
	$stmt = $mysqli->prepare('SELECT fotoProfilo FROM utenti_ss WHERE email = ?;');
	$stmt->bind_param('s', $email);
	$stmt->execute();
	$stmt->bind_result($foto);
	$stmt->fetch();
	$stmt->close();

	$stmt = $mysqli->prepare('SET FOREIGN_KEY_CHECKS=0;');
	$stmt->execute();
	$stmt->close();

	$stmt = $mysqli->prepare('INSERT INTO visualizzazioni (email, idArticolo) SELECT ?, ? WHERE NOT EXISTS (SELECT 1 FROM visualizzazioni WHERE email = ? AND idArticolo = ?);');
	$stmt->bind_param('sisi', $email, $idArticolo, $email, $idArticolo);
	$stmt->execute();
	$stmt->close();

	$stmt = $mysqli->prepare('SET FOREIGN_KEY_CHECKS=1;');
	$stmt->execute();
	$stmt->close();

	$stmt = $mysqli->prepare('UPDATE articoli SET visualizzazioni = (SELECT COUNT(idVisualizzazione) FROM visualizzazioni WHERE idArticolo = ?) WHERE idArticolo = ?;');
	$stmt->bind_param('ii', $idArticolo, $idArticolo);
	$stmt->execute();
	$stmt->close();

	$nomi = array();
	$stmt = $mysqli->prepare('SELECT nome FROM `categorie` WHERE codCategoria IN (SELECT codCategoria FROM articoli);');
	$stmt->execute();
	if ($stmt->bind_result($nome)) {
		while ($stmt->fetch()) {
			$nomi[] = $nome;
		}
		$stmt->close();
	}

	$idArticolo = $_GET['id'];

	$stmt = $mysqli->prepare('SELECT codCategoria, categorie.nome, contenuto, documento, visualizzazioni, utenti_ss.nome, utenti_ss.descrizione, utenti_ss.fotoProfilo, dataPubblicazione FROM `articoli` JOIN categorie USING (codCategoria) JOIN utenti_ss ON mailScrittore = email WHERE idArticolo = ?;');
	$stmt->bind_param('i', $idArticolo);
	$stmt->execute();
	if ($stmt->bind_result($codCategoria, $nomeCategoria, $contenuto, $documento, $visualizzazioni, $nomeScrittore, $descrizioneScrittore, $fotoScrittore, $dataPubblicazione)) {
		if ($stmt->fetch()) {
			$articolo = array(
				'idArticolo' => $idArticolo,
				'codCategoria' => $codCategoria,
				'nomeCategoria' => $nomeCategoria,
				'contenuto' => $contenuto,
				'documento' => $documento,
				'visualizzazioni' => $visualizzazioni,
				'nomeScrittore' => $nomeScrittore,
				'descrizioneScrittore' => $descrizioneScrittore,
				'fotoScrittore' => $fotoScrittore,
				'dataPubblicazione' => date("d/m/Y", strtotime($dataPubblicazione))
			);
		} else {
			die('<div style="position: absolute; top: 50%; right: 50%; transform: translate(50%, 50%);">Nessun articolo trovato</div>');
		}
		$stmt->close();
	}

	$stmt = $mysqli->prepare('SELECT MAX(idArticolo), MIN(idArticolo) FROM `articoli`;');
	$stmt->execute();
	if ($stmt->bind_result($maxIdArticolo, $minIdArticolo)) {
		$stmt->fetch();
		$stmt->close();
	}

	$i = 1;
	while ($idArticolo - $i >= $minIdArticolo) {
		$stmt = $mysqli->prepare('SELECT contenuto FROM `articoli` WHERE idArticolo = ? - ?;');
		$stmt->bind_param('ii', $idArticolo, $i);
		$stmt->execute();
		if ($stmt->bind_result($contenuto)) {
			if ($stmt->fetch()) {
				$paginaPrecedente = array('contenuto' => $contenuto, 'idArticolo' => $idArticolo - $i);
				$stmt->close();
				break;
			} else {
				$i++;
			}
			$stmt->close();
		}
	}


	$i = 1;
	while ($idArticolo + $i <= $maxIdArticolo) {
		$stmt = $mysqli->prepare('SELECT contenuto FROM `articoli` WHERE idArticolo = ? + ?;');
		$stmt->bind_param('ii', $idArticolo, $i);
		$stmt->execute();
		if ($stmt->bind_result($contenuto)) {
			if ($stmt->fetch()) {
				$paginaSuccessiva = array('contenuto' => $contenuto, 'idArticolo' => $idArticolo + $i);
				$stmt->close();
				break;
			} else {
				$i++;
			}
			$stmt->close();
		}
	}

	include 'fromSSFtoHTML.php';
} else {

	die('
        <html>
        <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
        <h1 style="text-align: center; font-weight: 400">Devi accedere per leggere l\'articolo completo</h1>
        <a href="login.php" style="text-align: center; font-weight: 500; display: flex; justify-content: center; font-size: 20px; margin-bottom: 30px; text-decoration: none; color: darkblue;">
			Clicca qui per accedere
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

?>

<head>

	<!--- basic page needs
   ================================================== -->
	<meta charset="utf-8">
	<title><?= getTitle($articolo) ?> | Saper sapere</title>
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
					<li class="current"><a href="index.php" title="">Home</a></li>									
					<li class="has-children">
						<a href="#" title="" style="cursor: default;">Categorie</a>
						<ul class="sub-menu">
							<?php foreach($nomi as $nome): ?>
								<li><a href="categorie.php?nome=<?=strtolower($nome)?>"><?=$nome?></a></li>
							<?php endforeach; ?>
						</ul>
					</li>
					<li><a href="about.php" title="">Chi siamo</a></li>
					<?php if($logged): ?>
						<li class="has-children">
						<a href="#" title="" style="cursor: default;">Account</a>
						<ul class="sub-menu">
							<li><a name="<?=$account->email?>" style="display: flex; align-items: center"><?= ($foto !== '') ? '<img width="50" height="50" style="width: 30px; height: 30px; border-radius: 50%; margin-left: -10px" id="fotoProfilo" src="data:image/*;base64,'.base64_encode($foto).'"></img>': '<i class="bi bi-person"></i>' ?>&nbsp;&nbsp;&nbsp;<?=$account->name?></a></li>
							<li><a href="api/logout.php"><i class="bi bi-box-arrow-right"></i>&nbsp;&nbsp;Logout</a></li>
						</ul>
					</li>
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

				<article class="format-standard">

					<div class="content-media">
						<div class="post-thumb">
							<?php
							$finfo = finfo_open(FILEINFO_MIME_TYPE); // Apre il gestore
							$mime_type = finfo_file($finfo, "documents/" . $articolo['documento']); // Restituisce il MIME type
							finfo_close($finfo); // Chiude il gestore
							?>

							<?php if (str_starts_with($mime_type, 'image')) : ?>
								<a href="articolo.php?id=<?= $articolo['idArticolo'] ?>" class="thumb-link">
									<img src="documents/<?= $articolo['documento'] ?>" alt="building">
								</a>
							<?php elseif (str_starts_with($mime_type, 'video')) : ?>
								<video width="320" height="240" controls>
									<source src="documents/<?= $articolo['documento'] ?>" type="video/mp4">
									Il tuo browser non supporta i video in formato MP4.
								</video>
							<?php endif; ?>
						</div>
					</div>

					<div class="primary-content">

						<h1 class="page-title"><?= getTitle($articolo) ?></h1>

						<ul class="entry-meta" style="margin-bottom: 2rem">
							<li class="date"><?= $articolo['dataPubblicazione'] ?></li>|
							<li class="date"><?= $articolo['nomeScrittore'] ?></li>|
							<li class="cat"><a href="categorie.php?nome=<?= strtolower($articolo['nomeCategoria']) ?>"><?= $articolo['nomeCategoria'] ?></a>|
								<?= $articolo['visualizzazioni'] ?> <i class="bi bi-eye-fill" style="color: grey"></i>
						</ul>

						<p class="lead" style="margin-bottom: 3rem"><?= getSummary($articolo) ?></p>

						<p>
							<?= getContent($articolo) ?>
						</p>

						<br>
						<hr>

						<div class="author-profile">
							<img src="data:image/*;base64,<?= base64_encode($articolo['fotoScrittore']) ?>" alt="">

							<div class="about">
								<h4><?= $articolo['nomeScrittore'] ?></h4>

								<p><?= $articolo['descrizioneScrittore'] ?></p>

							</div>
						</div> <!-- end author-profile -->

					</div> <!-- end entry-primary -->

					<div class="pagenav group">

						<?php if (isset($paginaPrecedente) and count($paginaPrecedente) > 0) : ?>
							<div class="prev-nav">
								<a href="articolo.php?id=<?= $paginaPrecedente['idArticolo'] ?>" rel="prev">
									<span>Articolo precedente</span>
									<?= getTitle($paginaPrecedente) ?>
								</a>
							</div>
						<?php endif; ?>

						<?php if (isset($paginaSuccessiva) and count($paginaSuccessiva) > 0) : ?>
							<div class="next-nav">
								<a href="articolo.php?id=<?= $paginaSuccessiva['idArticolo'] ?>" rel="next">
									<span>Articolo successivo</span>
									<?= getTitle($paginaSuccessiva) ?>
								</a>
							</div>
						<?php endif; ?>

					</div>

				</article>


			</div> <!-- end col-twelve -->
		</div> <!-- end row -->


		<?php
		$stmt = $mysqli->prepare('SELECT codVoto, valore, commento, data, utenti_ss.nome, utenti_ss.fotoProfilo FROM voti JOIN utenti_ss USING (email) WHERE idArticolo = ? ORDER BY data DESC;');
		$stmt->bind_param('i', $idArticolo);
		$stmt->bind_result($codVoto, $valore, $commento, $data, $nomeUtente, $fotoProfilo);
		$stmt->execute();
		$commenti = array();
		while ($stmt->fetch()) {
			$commenti[] = array(
				'codVoto' => $codVoto,
				'valore' => $valore,
				'commento' => $commento,
				'data' => date("d/m/Y H:i:s", strtotime($data)),
				'nomeUtente' => $nomeUtente,
				'fotoProfilo' => $fotoProfilo
			);
		}
		?>

		<div class="comments-wrap">
			<div id="comments" class="row">
				<div class="col-full">

					<!-- respond -->
					<div class="respond">

						<form name="contactForm" id="contactForm" method="post" action="">

							<fieldset>

								<h3 style="float: left">Commenta (<span id="nCaratteri">0</span>/255)</h3>

								<div class="rating">
									<input type="radio" id="star5" name="rating" value="5" /><label class="full" for="star5" title="Awesome - 5 stars"></label>
									<input type="radio" id="star4" name="rating" value="4" /><label class="full" for="star4" title="Pretty good - 4 stars"></label>
									<input type="radio" id="star3" name="rating" value="3" /><label class="full" for="star3" title="Meh - 3 stars"></label>
									<input type="radio" id="star2" name="rating" value="2" /><label class="full" for="star2" title="Kinda bad - 2 stars"></label>
									<input type="radio" id="star1" name="rating" value="1" /><label class="full" for="star1" title="Sucks big time - 1 star"></label>
								</div>

								<script>
									const countCaratteri = () => {
										document.querySelector('span#nCaratteri').innerHTML = document.querySelector('textarea#cMessage').value.length
									}
								</script>

								<div class="message form-field">
									<textarea name="cMessage" id="cMessage" class="full-width" placeholder="Commento" style="resize: none" maxlength="255" onkeyup="countCaratteri()" onkeypress="countCaratteri();"></textarea>
								</div>

								<button type="button" class="submit button-primary" onclick="sendComment();">Invia</button>

							</fieldset>

						</form> <!-- Form End -->

					</div> <!-- Respond End -->

					<h3 id="h3"><span id="nCommenti"><?= count($commenti) ?></span> comment<?= count($commenti) == 1 ? 'o' : 'i'; ?></h3>

					<!-- commentlist -->
					<ol class="commentlist">

						<?php foreach ($commenti as $i => $commento) : ?>

							<style>
								.rating<?= $i ?> {
									border: none;
								}

								.rating<?= $i ?>>input {
									display: none;
								}

								.rating<?= $i ?>>label:before {
									margin: 5px;
									font-size: 1.25em;
									font-family: FontAwesome;
									display: inline-block;
									content: "\f005";
								}

								.rating<?= $i ?>>label {
									color: #888;
									float: right;
								}

								/***** CSS Magic to Highlight Stars on Hover *****/

								.rating<?= $i ?>>input:checked~label {
									color: #FFD700;
								}

								/* hover previous stars in list */

								.rating<?= $i ?>:is(:disabled, :not(:checked))>label {
									color: #888;
								}
							</style>

							<li class="depth-1">

								<div class="avatar">
									<img width="50" height="50" class="avatar" src="<?= ($commento['fotoProfilo'] != '') ? 'data:image/*;base64,'.base64_encode($commento['fotoProfilo']) : 'images/avatar.jpeg' ?>" alt="">
								</div>

								<div class="comment-content">

									<div class="comment-info">

										<div class="rating<?= $i ?>">

											<?php $titoli = array(1 => 'Non mi piace per niente', 2 => 'Non mi piace', 3 => 'Decente', 4 => 'Carino', 5 => 'Fantastico'); ?>

											<?php for ($j = 5; $j > 0; $j--) : ?>
												<input type="radio" id="star<?= $j ?><?= $i ?>" name="rating<?= $i ?>" value="<?= $j ?>" <?= ($commento['valore'] === $j) ? 'checked' : '' ?> disabled /><label class="full" for="star<?= $j ?><?= $i ?>" title="<?= $titoli[$j] ?>"></label>
											<?php endfor; ?>
										</div>

										<div style="display: none" id="codVoto"><?= $commento['codVoto'] ?></div>

										<cite><?= $commento['nomeUtente'] ?>
											<?php if ($commento['nomeUtente'] === $account->name) : ?>
												&nbsp;&nbsp;
												<span class="trash" onclick="removeComment(<?= $i ?>);" title="Elimina commento"><i class="bi bi-trash"></i></span>
											<?php endif; ?>
										</cite>

										<div class="comment-meta">
											<time class="comment-time"><?= $commento['data'] ?></time>
										</div>
									</div>


									<div class="comment-text">
										<p><?= $commento['commento'] ?></p>
									</div>

								</div>

							</li>

						<?php endforeach; ?>

					</ol> <!-- Commentlist End -->

				</div> <!-- end col-full -->
			</div> <!-- end row comments -->
		</div> <!-- end comments-wrap -->

	</section> <!-- end content -->

	<script>
		async function sendComment() {
			commento = document.querySelector('textarea#cMessage').value
			valore = 1
			while (!document.querySelectorAll(`.rating > input`)[5 - valore].checked && valore <= 4) {
				valore++;
			}
			if (valore === 5 && !document.querySelectorAll(`.rating > input`)[0].checked) valore = 0;

			i = 0;
			while (document.querySelector(`.rating${i}`) !== null) {
				i++;
			}

			titoli = ['Non mi piace per niente', 'Non mi piace', 'Decente', 'Carino', 'Fantastico'];
			inputs = '';

			for (let j = 5; j > 0; j--) {
				inputs += `<input type="radio" id="star${j}${i}" name="rating${i}" value="${j}" ${(j === valore) ? 'checked' : ''} disabled /><label class="full" for="star${j}${i}" title="${titoli[j-1]}"></label>\n`;
			}

			nomeUtente = document.querySelector("#main-nav-wrap > ul > li:nth-child(4) > ul > li:nth-child(1) > a").text.trim()

			// crea un oggetto Date con la data corrente
			var oggi = new Date();

			var dataOggi = oggi.toLocaleString('it-IT', {
				year: 'numeric',
				month: '2-digit',
				day: '2-digit',
				hour: '2-digit',
				minute: '2-digit',
				second: '2-digit'
			})

			var fotoProfilo = document.getElementById('fotoProfilo')?.src

			textToAdd = `
										<style>
											.rating${i} {
												border: none;
											}

											.rating${i} >input {
												display: none;
											}

											.rating${i} >label:before {
												margin: 5px;
												font-size: 1.25em;
												font-family: FontAwesome;
												display: inline-block;
												content: "\\f005";
											}

											.rating${i} >label {
												color: #888;
												float: right;
											}

											/***** CSS Magic to Highlight Stars on Hover *****/

											.rating${i} >input:checked~label {
												color: #FFD700;
											}

											/* hover previous stars in list */

											.rating${i}:is(:disabled, :not(:checked))>label {
												color: #888;
											}
										</style>

										<li class="depth-1">

											<div class="avatar">
												<img width="50" height="50" class="avatar" src="${(fotoProfilo != null) ? fotoProfilo : 'images/avatar.jpeg'}" alt="">
											</div>

											<div class="comment-content">

												<div class="comment-info">

													<div class="rating${i}">

														${inputs}
														
													</div>

													<cite>${nomeUtente}</cite>

													<div class="comment-meta">
														<time class="comment-time">${dataOggi}</time>
													</div>
												</div>
												
												<div class="comment-text">
													<p>${commento}</p>
												</div>

											</div>

										</li>
									`;

			document.querySelector('ol').insertAdjacentHTML('afterbegin', textToAdd)

			document.querySelector('span#nCommenti').textContent = parseInt(document.querySelector('span#nCommenti').textContent) + 1
			if (document.querySelector('span#nCommenti').textContent == 1) document.querySelector("#comments > div > h3").innerHTML = `<span id="nCommenti">${document.querySelector('span#nCommenti').textContent}</span> commento`;
			else document.querySelector("#comments > div > h3").innerHTML = `<span id="nCommenti">${document.querySelector('span#nCommenti').textContent}</span> commenti`;
			// remove vote and comment

			if (valore !== 0) document.querySelectorAll('.rating > input')[5 - valore].checked = false
			document.querySelector('textarea#cMessage').value = ''
			countCaratteri();

			const response = await fetch('api/add-comment.php', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json',
				},
				//mode:"no-cors",
				body: JSON.stringify({
					valore: valore,
					commento: commento,
					data: oggi.getTime(),
					idArticolo: parseInt(document.location.href.split('?id=')[1]),
					email: document.querySelector("#main-nav-wrap > ul > li:nth-child(4) > ul > li:nth-child(1) > a").name
				}),
			});

		}

		async function removeComment(i) {
			codVoto = parseInt(document.querySelector(`.rating${i} ~ #codVoto`).textContent)

			const response = await fetch('api/remove-comment.php', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json',
				},
				//mode:"no-cors",
				body: JSON.stringify({
					codVoto: codVoto,
					email: document.querySelector("#main-nav-wrap > ul > li:nth-child(4) > ul > li:nth-child(1) > a").name
				}),
			});

			document.querySelector(`ol > li div.rating${i}`).parentElement.parentElement.parentElement.style.display = 'none';
			document.querySelector(`ol > li div.rating${i}`).parentElement.parentElement.parentElement.innerHTML = '';

			document.querySelector('span#nCommenti').textContent = parseInt(document.querySelector('span#nCommenti').textContent) - 1
			if (document.querySelector('span#nCommenti').textContent == 1) document.querySelector("#comments > div > h3").innerHTML = `<span id="nCommenti">${document.querySelector('span#nCommenti').textContent}</span> commento`;
			else document.querySelector("#comments > div > h3").innerHTML = `<span id="nCommenti">${document.querySelector('span#nCommenti').textContent}</span> commenti`;
		}
	</script>

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