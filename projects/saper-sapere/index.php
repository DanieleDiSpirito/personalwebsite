<!DOCTYPE html>
<!--[if IE 8 ]><html class="no-js oldie ie8" lang="en"> <![endif]-->
<!--[if IE 9 ]><html class="no-js oldie ie9" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html class="no-js" lang="en"> <!--<![endif]-->
<head>

   <!--- basic page needs
   ================================================== -->
   <meta charset="utf-8">
	<title>Home | Saper sapere</title>
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

	$articoli = array();
	$stmt = $mysqli->prepare('SELECT idArticolo, codCategoria, categorie.nome, contenuto, documento, visualizzazioni, utenti_ss.nome, dataPubblicazione FROM `articoli` JOIN categorie USING (codCategoria) JOIN utenti_ss ON mailScrittore = email ORDER BY dataPubblicazione DESC;');
	$stmt->execute();
	if ($stmt->bind_result($idArticolo, $codCategoria, $nomeCategoria, $contenuto, $documento, $visualizzazioni, $scrittore, $dataPubblicazione)) {
		while($stmt->fetch()) {
			$articoli[] = array(
				'idArticolo' => $idArticolo,
				'codCategoria' => $codCategoria,
				'nomeCategoria' => $nomeCategoria,
				'contenuto' => $contenuto,
				'documento' => $documento,
				'visualizzazioni' => $visualizzazioni,
				'scrittore' => $scrittore,
				'dataPubblicazione' => date("d/m/Y", strtotime($dataPubblicazione))
			);
		}
		$stmt->close();
	}

	include 'fromSSFtoHTML.php';
	
	session_start();

	if(isset($_SESSION['account'])) {
		$account = json_decode(base64_decode($_SESSION['account']));
	}
	$logged = isset($account);

	if($logged) {
		$email = $account->email;
		$stmt = $mysqli->prepare('SELECT fotoProfilo FROM utenti_ss WHERE email = ?;');
		$stmt->bind_param('s', $email);
		$stmt->execute();
		$stmt->bind_result($foto);
		$stmt->fetch();
		$stmt->close();
	}
?>


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


   <!-- masonry
   ================================================== -->
   <section id="bricks">

   	<div class="row masonry">

   		<!-- brick-wrapper -->
         <div class="bricks-wrapper">

         	<div class="grid-sizer"></div>

         	<div class="brick entry featured-grid animate-this">
         		<div class="entry-content">
         			<div id="featured-post-slider" class="flexslider">
			   			<ul class="slides">

							<?php $limit = 3; $videos = array(); ?>
							<?php for($i = 0; $i < $limit and isset($articoli[$i]); $i++): ?>

								<?php 
									$finfo = finfo_open(FILEINFO_MIME_TYPE); // Apre il gestore
									$mime_type = finfo_file($finfo, "documents/".$articoli[$i]['documento']); // Restituisce il MIME type
									finfo_close($finfo); // Chiude il gestore
								
									if(str_starts_with($mime_type, 'video')) {
										$videos[] = $i;
										$limit++;
										continue;
									}
								?>

								<li>
									<div class="featured-post-slide">

										<div class="post-background" style="background-image: url('./documents/<?=$articoli[$i]['documento']?>');"></div>

										<div class="overlay"></div>

										<div class="post-content">
											<ul class="entry-meta">
													<li style="color: white"><?=$articoli[$i]['dataPubblicazione']?></li> |
													<li style="color: white"><?=$articoli[$i]['scrittore']?></li> |
													<li><a href="categorie.php?nome=<?=strtolower($articoli[$i]['nomeCategoria'])?>" style="color: white"><?=$articoli[$i]['nomeCategoria']?></a></li> |
													<li style="color: white"><?=$articoli[$i]['visualizzazioni']?> <i class="bi bi-eye-fill"></i></li>
												</ul>	

											<h1 class="slide-title"><a href="articolo.php?id=<?=$articoli[$i]['idArticolo']?>" title=""><?= getTitle($articoli[$i]) ?></a></h1> 
										</div> 				   					  
								
									</div>
								</li>

							<?php endfor; ?>

				   		</ul> <!-- end slides -->
				   	</div> <!-- end featured-post-slider -->        			
         		</div> <!-- end entry content -->         		
         	</div>

			<?php foreach($videos as $video): ?>

				<article class="brick entry format-standard animate-this">

				<div class="entry-thumb">
					<video width="320" height="240" controls>
						<source src="documents/<?= $articoli[$video]['documento'] ?>" type="video/mp4">
						Il tuo browser non supporta i video in formato MP4.
					</video>
				</div>

				<div class="entry-text">
					<div class="entry-header">

						<div class="entry-meta">
							<span class="cat-links" style="font-size: 15px">
								<?=$articoli[$video]['dataPubblicazione']?> |
								<?=$articoli[$video]['scrittore']?> |
								<a href="categorie.php?nome=<?=strtolower($articoli[$video]['nomeCategoria'])?>"><?=$articoli[$video]['nomeCategoria']?></a> |
								<?=$articoli[$video]['visualizzazioni']?> <i class="bi bi-eye-fill" style="color: grey"></i>
							</span>
						</div>

						<h1 class="entry-title"><a href="articolo.php?id=<?=$articoli[$video]['idArticolo']?>"><?=getTitle($articoli[$video])?></a></h1>
						
					</div>
						<div class="entry-excerpt"><?=getSummary($articoli[$video])?></div>
				</div>

				</article> <!-- end article -->

			<?php endforeach; ?>


			<?php for($i = $limit; isset($articoli[$i]); $i++): ?>

				<article class="brick entry format-standard animate-this">

				<?php 
					$finfo = finfo_open(FILEINFO_MIME_TYPE); // Apre il gestore
					$mime_type = finfo_file($finfo, "documents/".$articoli[$i]['documento']); // Restituisce il MIME type
					finfo_close($finfo); // Chiude il gestore
				?>

				<div class="entry-thumb">
					<?php if(str_starts_with($mime_type, 'image')): ?>	
						<a href="articolo.php?id=<?=$articoli[$i]['idArticolo']?>" class="thumb-link">
							<img src="documents/<?=$articoli[$i]['documento']?>" alt="building">
						</a>
					<?php elseif(str_starts_with($mime_type, 'video')): ?>
						<video width="320" height="240" controls>
							<source src="documents/<?= $articoli[$i]['documento'] ?>" type="video/mp4">
							Il tuo browser non supporta i video in formato MP4.
						</video>
					<?php endif; ?>
				</div>

				<div class="entry-text">
					<div class="entry-header">

						<div class="entry-meta">
							<span class="cat-links" style="font-size: 15px">
								<?=$articoli[$i]['dataPubblicazione']?> |
								<?=$articoli[$i]['scrittore']?> |
								<a href="categorie.php?nome=<?=strtolower($articoli[$i]['nomeCategoria'])?>"><?=$articoli[$i]['nomeCategoria']?></a> |
								<?=$articoli[$i]['visualizzazioni']?> <i class="bi bi-eye-fill" style="color: grey"></i>
							</span>
						</div>

						<h1 class="entry-title"><a href="articolo.php?id=<?=$articoli[$i]['idArticolo']?>"><?=getTitle($articoli[$i])?></a></h1>
						
					</div>
						<div class="entry-excerpt"><?=getSummary($articoli[$i])?></div>
				</div>

				</article> <!-- end article -->

			<?php endfor; ?>

         </div> <!-- end brick-wrapper --> 

   	</div> <!-- end row -->

	<!--
   	<div class="row">
   		
   		<nav class="pagination">
		      <span class="page-numbers prev inactive">Prev</span>
		   	<span class="page-numbers current">1</span>
		   	<a href="#" class="page-numbers">2</a>
		      <a href="#" class="page-numbers">3</a>
		      <a href="#" class="page-numbers">4</a>
		      <a href="#" class="page-numbers">5</a>
		      <a href="#" class="page-numbers">6</a>
		      <a href="#" class="page-numbers">7</a>
		      <a href="#" class="page-numbers">8</a>
		      <a href="#" class="page-numbers">9</a>
		   	<a href="#" class="page-numbers next">Next</a>
	      </nav>

   	</div>
	-->

   </section>

   
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
   <script src="js/jquery.appear.js"></script>
   <script src="js/main.js"></script>

</body>

</html>