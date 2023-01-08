<!DOCTYPE html>
<!--[if IE 8 ]><html class="no-js oldie ie8" lang="en"> <![endif]-->
<!--[if IE 9 ]><html class="no-js oldie ie9" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html class="no-js" lang="en"> <!--<![endif]-->
<head>

   <!--- basic page needs
   ================================================== -->
   <meta charset="utf-8">
	<title>Chi siamo | Saper sapere</title>
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
	         <a href="index.php">Author</a>
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
					<li class="current"><a href="about.php" title="">Chi siamo</a></li>
					<?php if($logged): ?>
						<li class="has-children">
						<a href="#" title="" style="cursor: default;">Account</a>
						<ul class="sub-menu">
							<li><a name="<?=$account->email?>" href="account.php" style="display: flex; align-items: center"><?= (!($foto === '' or is_null($foto))) ? '<img width="50" height="50" style="width: 30px; height: 30px; border-radius: 50%; margin-left: -10px" id="fotoProfilo" src="data:image/*;base64,'.base64_encode($foto).'"></img>': '<i class="bi bi-person"></i>' ?>&nbsp;&nbsp;&nbsp;<?=$account->name?></a></li>
							<li><a href="api/logout.php"><i class="bi bi-box-arrow-right"></i>&nbsp;&nbsp;Logout</a></li>
						</ul>
					</li>
					<?php if($account->role === 1): ?>
						<li><a href="nuovo-articolo.php">Scrivi articolo</a></li>
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
   <section id="content-wrap" class="site-page">
   	<div class="row">
   		<div class="col-twelve">

   			<section>  

   				<div class="content-media">						
						<img src="../images/saper_sapere.jpg" style="margin: 0 auto; width: 90%; display: flex; justify-content: center;">
					</div>

					<div class="primary-content">

						<h1 class="entry-title add-bottom">Chi siamo</h1>	

						<p class="lead">Il giornale che presentiamo oggi è il frutto di un lavoro accurato e attento, realizzato da un team di giornalisti esperti e appassionati. Ogni giorno ci impegniamo a offrire ai nostri lettori notizie aggiornate, approfondimenti e inchieste, con l'obiettivo di fornire loro una panoramica completa e accurata sui temi di maggior rilevanza. Siamo convinti che informare in modo corretto e indipendente sia un dovere etico, e per questo ci impegniamo a garantire la massima trasparenza e imparzialità nella nostra attività. Speriamo che il nostro giornale possa diventare un punto di riferimento per chi cerca notizie di qualità e una visione critica sui fatti del mondo. Benvenuti alla lettura!</p>

					</div>						

				</section>  		

			</div> <!-- end col-twelve -->
   	</div> <!-- end row -->
		
   </section> <!-- end content -->

   
   <!-- footer
   ================================================== -->
   <footer>

      <div class="footer-bottom" style="margin-top: 0rem !important;">
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