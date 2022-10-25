<!DOCTYPE html>
<html lang="en">

<?php
session_start();
if (isset($_SESSION['session']) && (!isset($_SESSION['codice']) or $_SESSION['codice'] === -1)) {
    $account = json_decode(base64_decode($_SESSION['session']));
} else {
    header('Location: index.php/..');
}

include 'config.php';

$stmt = $mysqli->prepare('SELECT cellulari.idProdotto, nomeProdotto, prezzo, descrizione, immagine, quantita FROM cellulari, utenti, carrelli WHERE utenti.username = ? AND utenti.idUtente = carrelli.idUtente AND cellulari.idProdotto = carrelli.idProdotto');
$stmt->bind_param('s', $account->username);
$stmt->execute();
if ($stmt->bind_result($idProdotto, $nomeProdotto, $prezzo, $descrizione, $immagine, $quantitaMax)) {
    while($stmt->fetch()) {
        $prodotti[] = array('idProdotto' => $idProdotto, 'nomeProdotto' => $nomeProdotto, 'prezzo' => $prezzo, 'descrizione' => $descrizione, 'immagine' => $immagine, 'quantita' => 1, 'quantitaMax' => $quantitaMax);
    }
    $stmt->close();
}

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
                            <li class="scroll-to-section"><a href="index.php/..">Home</a></li>
                            <li class="submenu">
                                <a>Informazioni</a>
                                <ul>
                                    <li><a href="orari.php">Orari&nbsp;&nbsp;<i class="bi bi-clock" style="font-size: 1rem"></i></a></li>
                                    <li><a href="dovesiamo.php">Dove siamo&nbsp;&nbsp;<i class="bi bi-geo" style="font-size: 1rem"></i></a></li>
                                    <li><a href="../.." target="_blank">Crediti &nbsp;<i class="bi bi-code-slash" style="font-size: 1rem"></i></a></li>
                                </ul>
                            </li>
                            <?php
                            if (isset($account)) {
                                echo '
                            <li class="submenu">
                                <a><i class="bi bi-person-circle" style="font-size: 1.5rem"></i></a>
                                <ul>
                                    <li><a class="active" style="color: black"><b>' . $account->username . '</b></a></li>
                                    <li><a href="api/logout.php">Logout <i class="bi bi-box-arrow-left"></i> </a></li>
                                </ul>
                            </li>';
                            } else {
                                echo '
                                <li class="scroll-to-section"><a href="login.php">Accedi</a></li>
                                ';
                            }
                            ?>
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
                        <h2>Carrello</h2>
                        <span style="font-size: 0.75rem">Ecco il tuo carrello</span>
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
            <div class="row row_on_phone" style="justify-content: center">
                <?php
                if(isset($prodotti)) {
                    foreach ($prodotti as $prodotto) {
                        echo '
                        <div class="colonna">
                            <div class="item">
                                <div class="thumb" style="width: fit-content">
                                    <div class="hover-content">
                                        <ul>
                                            <li style="margin: 0 auto;"><a href="single-product.php?id=' . $prodotto["idProdotto"] . '"><i class="fa fa-eye"></i></a></li>
                                            <li style="margin: 0 auto;"><a href="api/cart.php?id=' . $prodotto["idProdotto"] . '"><i class="bi bi-cart-x-fill"></i></a></li>
                                        </ul>
                                    </div>
                                    <img class="img_on_phone" src="data:image/jpg;base64,' . base64_encode($prodotto["immagine"]) . '" alt="">
                                </div>
                                <div class="down-content">
                                    <a href="single-product.php?id=' . $prodotto["idProdotto"] . '" class="nomeprodotto" id="' . $prodotto["idProdotto"] . '"><h4>' . $prodotto["nomeProdotto"] . '</h4></a>
                                    <span>' . $prodotto["prezzo"] . '€</span>
                                </div>
                                <input type="number" min="1" max="'.$prodotto["quantitaMax"].'" value="'.$prodotto['quantita'].'" class="input_quantita">
                            </div>
                        </div>
                        ';
                    }
                } else {
                    echo '
                    <div>
                        <h5>Il carrello è vuoto</h5><br>
                    </div>';
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
                        <li><a href="index.php/..">Home</a></li>
                        <li><a href="cart.php">Carrello</a></li>
                        <li><a href="orari.php">Orari</a></li>
                        <li><a href="dovesiamo.php">Dove siamo</a></li>
                        <li><a href="../.." target="_blank">Crediti</a></li>
                        <li><a href="api/logout.php">Logout</a></li>
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
            $("p").click(function() {
                selectedClass = $(this).attr("data-rel");
                $("#portfolio").fadeTo(50, 0.1);
                $("#portfolio div").not("." + selectedClass).fadeOut();
                setTimeout(function() {
                    $("." + selectedClass).fadeIn();
                    $("#portfolio").fadeTo(50, 1);
                }, 500);

            });
        });
    </script>

</body>

<?php $mysqli->close(); ?>

</html>