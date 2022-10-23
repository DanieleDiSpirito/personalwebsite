<!DOCTYPE html>
<html lang="en">

<?php
session_start();
if(isset($_SESSION['session']) && (!isset($_SESSION['codice']) or $_SESSION['codice'] === -1)) {
    $account = json_decode(base64_decode($_SESSION['session']));
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
                        <li class="scroll-to-section"><a href="index.php">Home</a></li>
                        <li class="scroll-to-section"><a href="cart.php"><i class="bi bi-cart" style="font-size: 1.5rem"></i> 0</a></li>
                        <li class="submenu">
                            <a>Informazioni</a>
                            <ul>
                                <li><a href="../..">Chi sono&nbsp;&nbsp;<i class="bi bi-question-square" style="font-size: 1rem"></i></a></li>
                                <li><a href="#" class="active" style="color: #00000080 !important;">Dove siamo&nbsp;&nbsp;<i class="bi bi-geo" style="font-size: 1rem"></i></a></li>
                            </ul>
                        </li>
                        <?php
                        if(isset($account)) {
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
<main>
    <div class="container text-center" style="margin-top: 10rem;">
        <div class="row align-items-center">
            <div class="col-lg-6" style="margin-bottom: 1rem">
            <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d188.09122237827876!2d16.9910398!3d41.0370783!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1347c15b10ec3065%3A0x5996f9ba9240a946!2sVia%20Giovanni%20Pascoli%2C%2057%2C%2070016%20Noicattaro%20BA%2C%20Italia!5e0!3m2!1sit!2sus!4v1666520731451!5m2!1sit!2sus" width="100%" height="300px" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
            <div class="col-lg-6" style="display: grid;justify-items: center;justify-content: center;">
                <h3>Dove siamo</h3>
                <div>
                    <table style="margin-top: 1rem;">
                        <tr>
                            <td><i class="bi bi-geo-alt"></i></td><td>&nbsp;&nbsp;&nbsp;Via Giovanni Pascoli, 57</td>
                        </tr>
                        <tr>
                            <td><i class="bi bi-map"></i></td><td>Noicattaro (BA)</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

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
                    <li><a href="index.php">Home</a></li>
                    <li><a href="cart.php">Carrello</a></li>
                    <li><a href="../..">Chi sono</a></li>
                    <li><a href="#" class="active">Dove siamo</a></li>
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
