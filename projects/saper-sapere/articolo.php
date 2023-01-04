<!DOCTYPE html>
<!--[if IE 8 ]><html class="no-js oldie ie8" lang="en"> <![endif]-->
<!--[if IE 9 ]><html class="no-js oldie ie9" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html class="no-js" lang="en"> <!--<![endif]-->

<?php
	
	include 'config.php';
	
	$nomi = array();
	$stmt = $mysqli->prepare('SELECT nome FROM `categorie` WHERE codCategoria IN (SELECT codCategoria FROM articoli);');
	$stmt->execute();
	if ($stmt->bind_result($nome)) {
		while($stmt->fetch()) {
			$nomi[] = $nome;
		}
		$stmt->close();
	}

	$idArticolo = $_GET['id'];

	$stmt = $mysqli->prepare('SELECT codCategoria, categorie.nome, contenuto, documento, visualizzazioni, scrittori.nome, scrittori.descrizione, scrittori.fotoProfilo, dataPubblicazione FROM `articoli` JOIN categorie USING (codCategoria) JOIN scrittori USING (idScrittore) WHERE idArticolo = ?;');
	$stmt->bind_param('i', $idArticolo);
	$stmt->execute();
	if ($stmt->bind_result($codCategoria, $nomeCategoria, $contenuto, $documento, $visualizzazioni, $nomeScrittore, $descrizioneScrittore, $fotoScrittore, $dataPubblicazione)) {
		if($stmt->fetch()) {
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
	while($idArticolo - $i >= $minIdArticolo) {
		$stmt = $mysqli->prepare('SELECT contenuto FROM `articoli` WHERE idArticolo = ? - ?;');
		$stmt->bind_param('ii', $idArticolo, $i);
		$stmt->execute();
		if ($stmt->bind_result($contenuto)) {
			if($stmt->fetch()) {
				$paginaPrecedente = array('contenuto' => $contenuto);
				$stmt->close();
				break;
			} else {
				$i++;
			}
			$stmt->close();
		}
	}


	$i = 1;
	while($idArticolo + $i <= $maxIdArticolo) {
		$stmt = $mysqli->prepare('SELECT contenuto FROM `articoli` WHERE idArticolo = ? + ?;');
		$stmt->bind_param('ii', $idArticolo, $i);
		$stmt->execute();
		if ($stmt->bind_result($contenuto)) {
			if($stmt->fetch()) {
				$paginaSuccessiva = array('contenuto' => $contenuto);
				$stmt->close();
				break;
			} else {
				$i++;
			}
			$stmt->close();
		}
	}

	include 'fromSSFtoHTML.php';
	
	session_start();

	if(isset($_SESSION['account'])) {
		$account = json_decode(base64_decode($_SESSION['account']));
	}
	$logged = isset($account);

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
				<li><a href="index.php" title="">Home</a></li>									
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
						<li><a><i class="bi bi-person"></i>&nbsp;&nbsp;<?=$account->name?></a></li>
						<li><a href="api/logout.php"><i class="bi bi-box-arrow-right"></i>&nbsp;&nbsp;Logout</a></li>
					</ul>
					</li>
				<?php else: ?>
					<li><a href="login.php" title="">Accedi</a></li>
				<?php endif; ?>
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
								$mime_type = finfo_file($finfo, "documents/".$articolo['documento']); // Restituisce il MIME type
								finfo_close($finfo); // Chiude il gestore
							?>

							<?php if(str_starts_with($mime_type, 'image')): ?>	
								<a href="articolo.php?id=<?=$articolo['idArticolo']?>" class="thumb-link">
									<img src="documents/<?=$articolo['documento']?>" alt="building">
								</a>
							<?php elseif(str_starts_with($mime_type, 'video')): ?>
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
							<li class="cat"><a href="categorie.php?nome=<?=strtolower($articolo['nomeCategoria'])?>"><?= $articolo['nomeCategoria'] ?></a>|
							<?=$articolo['visualizzazioni']?> <i class="bi bi-eye-fill" style="color: grey"></i>
						</ul>						

						<p class="lead" style="margin-bottom: 3rem"><?= getSummary($articolo) ?></p>
						
						<p>
							<?= getContent($articolo) ?>
						</p>

						<br><hr>

		  			   <div class="author-profile">
		  			   	<img src="data:image/*;base64,<?=base64_encode($articolo['fotoScrittore'])?>" alt="">

		  			   	<div class="about">
		  			   		<h4><?= $articolo['nomeScrittore'] ?></h4>
		  			   	
		  			   		<p><?= $articolo['descrizioneScrittore'] ?></p>

		  			   	</div>
		  			   </div> <!-- end author-profile -->						

					</div> <!-- end entry-primary -->		  			   

	  			   <div class="pagenav group">
					
				  	 	<?php if(isset($paginaPrecedente) and count($paginaPrecedente) > 0): ?>
				  	 	<div class="prev-nav">
		  			   		<a href="articolo.php?id=<?=$idArticolo-1?>" rel="prev">
		  			   			<span>Articolo precedente</span>
		  			   			<?= getTitle($paginaPrecedente) ?>
		  			   		</a>
		  			   	</div>
						<?php endif; ?>

						<?php if(isset($paginaSuccessiva) and count($paginaSuccessiva) > 0): ?>
		  				<div class="next-nav">
		  					<a href="articolo.php?id=<?=$idArticolo+1?>" rel="next">
		  						<span>Articolo successivo</span>
								<?= getTitle($paginaSuccessiva) ?>
							</a>
		  				</div>
						<?php endif; ?>

	  				</div>

				</article>
   		

			</div> <!-- end col-twelve -->
   	</div> <!-- end row -->

		<div class="comments-wrap">
			<div id="comments" class="row">
				<div class="col-full">

               <h3>5 Comments</h3>

               <!-- commentlist -->
               <ol class="commentlist">

                  <li class="depth-1">

                     <div class="avatar">
                        <img width="50" height="50" class="avatar" src="images/avatars/user-01.jpg" alt="">
                     </div>

                     <div class="comment-content">

	                     <div class="comment-info">
	                        <cite>Itachi Uchiha</cite>

	                        <div class="comment-meta">
	                           <time class="comment-time" datetime="2014-07-12T23:05">Jul 12, 2014 @ 23:05</time>
	                           <span class="sep">/</span><a class="reply" href="#">Reply</a>
	                        </div>
	                     </div>

	                     <div class="comment-text">
	                        <p>Adhuc quaerendum est ne, vis ut harum tantas noluisse, id suas iisque mei. Nec te inani ponderum vulputate,
	                        facilisi expetenda has et. Iudico dictas scriptorem an vim, ei alia mentitum est, ne has voluptua praesent.</p>
	                     </div>

	                  </div>

                  </li>

                  <li class="thread-alt depth-1">

                     <div class="avatar">
                        <img width="50" height="50" class="avatar" src="images/avatars/user-04.jpg" alt="">
                     </div>

                     <div class="comment-content">

	                     <div class="comment-info">
	                        <cite>John Doe</cite>

	                        <div class="comment-meta">
	                           <time class="comment-time" datetime="2014-07-12T24:05">Jul 12, 2014 @ 24:05</time>
	                           <span class="sep">/</span><a class="reply" href="#">Reply</a>
	                        </div>
	                     </div>

	                     <div class="comment-text">
	                        <p>Sumo euismod dissentiunt ne sit, ad eos iudico qualisque adversarium, tota falli et mei. Esse euismod
	                        urbanitas ut sed, et duo scaevola pericula splendide. Primis veritus contentiones nec ad, nec et
	                        tantas semper delicatissimi.</p>                        
	                     </div>

	                  </div>

                     <ul class="children">

                        <li class="depth-2">

                           <div class="avatar">
                              <img width="50" height="50" class="avatar" src="images/avatars/user-03.jpg" alt="">
                           </div>

                           <div class="comment-content">

	                           <div class="comment-info">
	                              <cite>Kakashi Hatake</cite>

	                              <div class="comment-meta">
	                                 <time class="comment-time" datetime="2014-07-12T25:05">Jul 12, 2014 @ 25:05</time>
	                                 <span class="sep">/</span><a class="reply" href="#">Reply</a>
	                              </div>
	                           </div>

	                           <div class="comment-text">
	                              <p>Duis sed odio sit amet nibh vulputate
	                              cursus a sit amet mauris. Morbi accumsan ipsum velit. Duis sed odio sit amet nibh vulputate
	                              cursus a sit amet mauris</p>
	                           </div>

                           </div>

                           <ul class="children">

                              <li class="depth-3">

                                 <div class="avatar">
                                    <img width="50" height="50" class="avatar" src="images/avatars/user-04.jpg" alt="">
                                 </div>

                                 <div class="comment-content">

	                                 <div class="comment-info">
	                                    <cite>John Doe</cite>

	                                    <div class="comment-meta">
	                                       <time class="comment-time" datetime="2014-07-12T25:15">July 12, 2014 @ 25:15</time>
	                                       <span class="sep">/</span><a class="reply" href="#">Reply</a>
	                                    </div>
	                                 </div>

	                                 <div class="comment-text">
	                                    <p>Investigationes demonstraverunt lectores legere me lius quod ii legunt saepius. Claritas est
	                                    etiam processus dynamicus, qui sequitur mutationem consuetudium lectorum.</p>
	                                 </div>

                                 </div>

                              </li>

                           </ul>

                        </li>

                     </ul>

                  </li>

                  <li class="depth-1">

                     <div class="avatar">
                        <img width="50" height="50" class="avatar" src="images/avatars/user-02.jpg" alt="">
                     </div>

                     <div class="comment-content">

	                     <div class="comment-info">
	                        <cite>Shikamaru Nara</cite>

	                        <div class="comment-meta">
	                           <time class="comment-time" datetime="2014-07-12T25:15">July 12, 2014 @ 25:15</time>
	                           <span class="sep">/</span><a class="reply" href="#">Reply</a>
	                        </div>
	                     </div>

	                     <div class="comment-text">
	                        <p>Typi non habent claritatem insitam; est usus legentis in iis qui facit eorum claritatem.</p>
	                     </div>

                     </div>

                  </li>

               </ol> <!-- Commentlist End -->					

               <!-- respond -->
               <div class="respond">

                  	<form name="contactForm" id="contactForm" method="post" action="">

						<fieldset>

							<h3 style="float: left">Commenta (<span id="nCaratteri">0</span>/255)</h3>
							<!--
								<div class="form-field">
									<input name="cEmail" type="text" id="cEmail" class="full-width" placeholder="Your Email" value="">
								</div>
							-->
							
							<div class="rating">
								<input type="radio" id="star5" name="rating" value="5" /><label class = "full" for="star5" title="Awesome - 5 stars"></label>
								<input type="radio" id="star4" name="rating" value="4" /><label class = "full" for="star4" title="Pretty good - 4 stars"></label>
								<input type="radio" id="star3" name="rating" value="3" /><label class = "full" for="star3" title="Meh - 3 stars"></label>
								<input type="radio" id="star2" name="rating" value="2" /><label class = "full" for="star2" title="Kinda bad - 2 stars"></label>
								<input type="radio" id="star1" name="rating" value="1" /><label class = "full" for="star1" title="Sucks big time - 1 star"></label>
							</div>

							<script>
								const countCaratteri = () => {
									document.querySelector('span#nCaratteri').innerHTML = document.querySelector('textarea#cMessage').value.length
								}
							</script>

							<div class="message form-field">
								<textarea name="cMessage" id="cMessage" class="full-width" placeholder="Commento" style="resize: none" maxlength="255" onkeyup="countCaratteri()" onkeypress="countCaratteri();"></textarea>
							</div>

							<button type="submit" class="submit button-primary">Invia</button>

						</fieldset>

					</form> <!-- Form End -->

               </div> <!-- Respond End -->

         	</div> <!-- end col-full -->
         </div> <!-- end row comments -->
		</div> <!-- end comments-wrap -->

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