<!DOCTYPE html>
<html lang="en">

<?php

session_start();
if (isset($_SESSION['session']) && (!isset($_SESSION['codice']) or $_SESSION['codice'] === -1)) {
    $account = json_decode(base64_decode($_SESSION['session']));
}

if (!isset($account) or $account->username !== 'admin') {
    header('Location: ../');
}

include '../config.php';

$result = $mysqli->query('SELECT idProdotto, nomeProdotto, prezzo, immagine, quantita FROM cellulari ORDER BY idProdotto ASC');
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $prodotti[] = $row;
    }
} else {
    printf('No record found.<br />');
}
mysqli_free_result($result);

?>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900&display=swap" rel="stylesheet">

    <title>Phonify</title>
    <link rel="icon" type="image/x-icon" href="../assets/favicon.ico" />

    <!-- Additional CSS Files -->
    <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css" rel="stylesheet" />

    <link rel="stylesheet" type="text/css" href="../assets/css/font-awesome.css">

    <link rel="stylesheet" href="../assets/css/style.css">

    <link rel="stylesheet" href="../assets/css/owl-carousel.css">

    <link rel="stylesheet" href="../assets/css/lightbox.css">
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
                        <img src="../assets/images/logo.png" width="132rem">

                        <!-- ***** Logo End ***** -->
                        <!-- ***** Menu Start ***** -->
                        <ul class="nav">
                            <li class="scroll-to-section"><a href="#" class="active">Modifica</a></li>
                            <li class="scroll-to-section"><a href="aggiungi.php">Aggiungi</a></li>
                            <li class="submenu">
                                <a><i class="bi bi-person-circle" style="font-size: 1.5rem"></i></a>
                                <ul>
                                    <li><a class="active" style="color: black"><b>Admin</b></a></li>
                                    <li><a href="../api/logout.php">Logout <i class="bi bi-box-arrow-left"></i> </a></li>
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
                <div class="col-lg-12">
                    <div class="section-heading">
                        <h2>Modifica</h2>
                        <span style="font-size: 0.75rem">Modifica i prodotti</span>
                    </div>
                </div>
            </div>
        </div>
        <form method="POST" action="../api/modifica-quantita.php">
            <div class="container">
                <div class="row row_on_phone" style="justify-content: center">
                    <?php
                    $i = 0;
                    foreach ($prodotti as $prodotto) {
                        echo '
                        <div class="colonna">
                            <div class="item">
                                <div class="thumb" style="width: fit-content">
                                    <div class="hover-content">
                                        <ul>
                                            <li style="margin: 0 auto;"><a href="edit-product.php?id='.$prodotto["idProdotto"].'"><i class="bi bi-pencil"></i></a></li>
                                        </ul>
                                    </div>
                                    <img class="img_on_phone" src="data:image/jpg;base64,' . base64_encode($prodotto["immagine"]) . '" alt="">
                                </div>
                                <div class="down-content">
                                    <a href="edit-product.php?id=' . $prodotto["idProdotto"] . '" class="nomeprodotto" id="' . $prodotto["idProdotto"] . '"><h4>' . $prodotto["nomeProdotto"] . '</h4></a>
                                    <span>' . $prodotto["prezzo"] . '€</span>
                                </div>
                                <div class="quantity-content">
                                    <div class="right-content">
                                        <div class="quantity buttons_added">
                                            <input type="button" value="-" class="minus" onclick="diminuzioneQuantita('.$i.')">
                                            <input type="number" name="quantita'.$prodotto["idProdotto"].'" min="0" step="1" value="'.$prodotto['quantita'].'" title="Qty" class="input-text qty text" size="4" pattern="" inputmode="" onchange="calcoloPrezzoTotale();" readonly>
                                            <input type="button" value="+" class="plus" onclick="aumentoQuantita('.$i.')">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        ';
                        $i++;
                    }
                    ?>
                </div>
            </div>
            <br><br>
            <div class="total div_acquisto">
                <button type="submit" class="main-border-button bottone_acquisto">
                    <a style="font-size: 20px !important;">
                        Salva modifiche
                    </a>
                </button>
            </div>
        </form>
    </section>
    <!-- ***** Products Area Ends ***** -->

    <!-- ***** Footer Start ***** -->
    <footer>
        <div class="container">
            <div class="row" style="align-items: center;">
                <div class="col-lg-3">
                    <div class="first-item">
                        <div class="logo" style="justify-content: center;display: flex;margin-bottom: 2rem;">
                            <img src="../assets/images/logo_no_bg.png" style="width: 20rem">
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
                        <li><a href="#" class="active">Modifica</a></li>
                        <li><a href="aggiungi.php">Aggiungi</a></li>
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

    <script>
        const diminuzioneQuantita = (id) => {
            listaBottoni = document.querySelectorAll('input.input-text');
            if(listaBottoni[id].value >= 1) {
                listaBottoni[id].value--;
            }
        }

        const aumentoQuantita = (id) => {
            listaBottoni = document.querySelectorAll('input.input-text');
            listaBottoni[id].value++;
        }
    </script>

    <!-- jQuery -->
    <script src="../assets/js/jquery-2.1.0.min.js"></script>

    <!-- Bootstrap -->
    <script src="../assets/js/popper.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>

    <!-- Plugins -->
    <script src="../assets/js/owl-carousel.js"></script>
    <script src="../assets/js/accordions.js"></script>
    <script src="../assets/js/datepicker.js"></script>
    <script src="../assets/js/scrollreveal.min.js"></script>
    <script src="../assets/js/waypoints.min.js"></script>
    <script src="../assets/js/jquery.counterup.min.js"></script>
    <script src="../assets/js/imgfix.min.js"></script>
    <script src="../assets/js/slick.js"></script>
    <script src="../assets/js/lightbox.js"></script>
    <script src="../assets/js/isotope.js"></script>

    <!-- Global Init -->
    <script src="../assets/js/custom.js"></script>
    <script src="../assets/js/searchbar.js"></script>

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