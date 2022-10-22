<!DOCTYPE html>
<html lang="en">

<?php
session_start();
if(isset($_SESSION['session']) && (!isset($_SESSION['codice']) or $_SESSION['codice'] === -1)) {
    $account = json_decode(base64_decode($_SESSION['session']));
    //echo 'BENVENUTO/A @<i>' .  $account->username . '</i>';
} else {
    header('Location: login.php');
}

$db_host = 'localhost';
$db_user = 'root';
$db_password = '';
$db_name = 'my_dispiritodaniele';
$mysqli = new mysqli($db_host, $db_user, $db_password, $db_name);
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

$result = $mysqli->query('SELECT * FROM prodotti ORDER BY idProdotto ASC');
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $prodotti[] = array($row["idProdotto"], $row["nomeProdotto"], $row["descrizione"], $row["quantita"], $row["prezzo"], $row["immagine"]);
    }
} else {
    printf('No record found.<br />');
}
mysqli_free_result($result);
$mysqli->close();

?>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900&display=swap" rel="stylesheet">

    <title>Phonify</title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />

    <!-- Additional CSS Files -->
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css" rel="stylesheet" />

    <link rel="stylesheet" type="text/css" href="assets/css/font-awesome.css">

    <link rel="stylesheet" href="assets/css/style.css">

    <link rel="stylesheet" href="assets/css/owl-carousel.css">

    <link rel="stylesheet" href="assets/css/lightbox.css">
    <!--

    TemplateMo 571 Hexashop

    https://templatemo.com/tm-571-hexashop

    -->
</head>

<body>

<!-- ***** Preloader Start ***** -->
<div id="preloader">
    <div class="jumper">
        <div></div>
        <div></div>
        <div></div>
    </div>
</div>
<!-- ***** Preloader End ***** -->


<!-- ***** Header Area Start ***** -->
<header class="header-area header-sticky background-header">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav class="main-nav">

                    <!-- ***** Logo Start ***** -->
                    <img src="assets/images/logo.png" width="132rem">

                    <!-- ***** Logo End ***** -->
                    <!-- ***** Menu Start ***** -->
                    <ul class="nav">
                        <li class="scroll-to-section"><a href="#" class="active">Home</a></li>
                        <li class="scroll-to-section"><a href="cart.php"><i class="bi bi-cart" style="font-size: 1.5rem"></i> 0</a></li>
                        <li class="submenu">
                            <a>Informazioni</a>
                            <ul>
                                <li><a href="../..">Chi sono&nbsp;&nbsp;<i class="bi bi-question-square" style="font-size: 1rem"></i></a></li>
                                <li><a href="dovesiamo.html">Dove siamo&nbsp;&nbsp;<i class="bi bi-geo" style="font-size: 1rem"></i></a></li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a><i class="bi bi-person-circle" style="font-size: 1.5rem"></i></a>
                            <ul>
                                <li><a class="active" style="color: black"><b><?php echo $account->username; ?></b></a></li>
                                <li><a href="logout.php">Logout <i class="bi bi-box-arrow-left"></i> </a></li>
                            </ul>
                        </li>
                    </ul>

                    <a class='menu-trigger'>
                        <span>Menu</span>
                    </a>
                    <!-- ***** Menu End ***** -->
                </nav>
            </div>
        </div>
    </div>
</header>
<!-- ***** Header Area End ***** -->

<!-- ***** Products Area Starts ***** -->
<section class="section" id="products" style="padding-top: 3rem;">
    <div class="container">
        <div class="row">
            <div class="col-lg-5"></div>
            <div class="col-lg-2">
                <div class="section-heading">
                    <h2>Home</h2>
                    <span style="font-size: 0.75rem">Scopri tutti i nostri prodotti</span>
                </div>
            </div>
            <div class="col-lg-5 colonna5" style="justify-content: right; display: flex; align-items: center">
                <div class="search-box">
                    <input type="text" placeholder=" " onkeyup="search();">
                    <button type="reset" onclick="removeContent()"></button>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row" style="justify-content: center">
            <?php
                foreach($prodotti as $prodotto) {
                    echo '
                    <div class="colonna">
                        <div class="item">
                            <div class="thumb" style="width: fit-content">
                                <div class="hover-content">
                                    <ul>
                                        <li style="margin: 0 auto;"><a href="single-product.html"><i class="fa fa-eye"></i></a></li>
                                        <li style="margin: 0 auto;"><a href="single-product.html"><i class="fa fa-star"></i></a></li>
                                        <li style="margin: 0 auto;"><a href="single-product.html"><i class="fa fa-shopping-cart"></i></a></li>
                                    </ul>
                                </div>
                                <img style="height: 18rem; width: auto;" src="data:image/jpg;base64,'.$prodotto[5].'" alt="">
                            </div>
                            <div class="down-content">
                                <a href="single-product.html" class="nomeprodotto"><h4>'.$prodotto[1].'</h4></a>
                                <span>$'.$prodotto[4].'</span>
                            </div>
                        </div>
                    </div>
                    ';
                }
            ?>
        </div>
    </div>
</section>
<!-- ***** Products Area Ends ***** -->

<!-- ***** Footer Start ***** -->
<footer>
    <div class="container">
        <div class="row" style="align-items: center;">
            <div class="col-lg-3">
                <div class="first-item">
                    <div class="logo" style="justify-content: center;display: flex;margin-bottom: 2rem;">
                        <img src="assets/images/logo_no_bg.png" style="width: 20rem">
                    </div>
                </div>
            </div>
            <div class="col-lg-6" style="text-align: center; color: white !important; margin-top: 20px">
                <ul>
                    <li><i class="bi bi-geo"></i>&nbsp;&nbsp;Via Giovanni Pascoli, 57 | Noicattaro (BA)</li>
                    <li><i class="bi bi-at"></i>&nbsp;&nbsp;dispiritodaniele.noreply@gmail.com</li>
                </ul>
            </div>
            <div class="col-lg-3 colonna3">
                <h4>Pagine</h4>
                <ul>
                    <li><a href="#" class="active">Home</a></li>
                    <li><a href="cart.php">Carrello</a></li>
                    <li><a href="../..">Chi sono</a></li>
                    <li><a href="dovesiamo.html">Dove siamo</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </div>
            <div class="col-lg-12">
                <div class="under-footer">
                    <p>Copyright © 2022 Phonify Co., Ltd. All Rights Reserved.
                </div>
            </div>
        </div>
    </div>
</footer>


<!-- jQuery -->
<script src="assets/js/jquery-2.1.0.min.js"></script>

<!-- Bootstrap -->
<script src="assets/js/popper.js"></script>
<script src="assets/js/bootstrap.min.js"></script>

<!-- Plugins -->
<script src="assets/js/owl-carousel.js"></script>
<script src="assets/js/accordions.js"></script>
<script src="assets/js/datepicker.js"></script>
<script src="assets/js/scrollreveal.min.js"></script>
<script src="assets/js/waypoints.min.js"></script>
<script src="assets/js/jquery.counterup.min.js"></script>
<script src="assets/js/imgfix.min.js"></script>
<script src="assets/js/slick.js"></script>
<script src="assets/js/lightbox.js"></script>
<script src="assets/js/isotope.js"></script>

<!-- Global Init -->
<script src="assets/js/custom.js"></script>
<script src="assets/js/searchbar.js"></script>

<script>

    $(function() {
        var selectedClass = "";
        $("p").click(function(){
            selectedClass = $(this).attr("data-rel");
            $("#portfolio").fadeTo(50, 0.1);
            $("#portfolio div").not("."+selectedClass).fadeOut();
            setTimeout(function() {
                $("."+selectedClass).fadeIn();
                $("#portfolio").fadeTo(50, 1);
            }, 500);

        });
    });

</script>

</body>

</html>
