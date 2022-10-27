<!DOCTYPE html>
<html lang="en">

<?php
session_start();
if (isset($_SESSION['session']) && (!isset($_SESSION['codice']) or $_SESSION['codice'] === -1)) {
    $account = json_decode(base64_decode($_SESSION['session']));
} else {
    echo 'Devi essere loggato';
    exit(0);
}

switch(count($_POST)) {
    case 0:
        echo 'Nessun prodotto comprato';
        exit(0);
    case 1:
        $title = 'Acquisto effettuato';
    default:
        $title = 'Acquisti effettuati';
}

include 'config.php';

$result = $mysqli->query('SELECT idProdotto, nomeProdotto, prezzo, immagine FROM cellulari');
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $prodotti[] = $row;
    }
} else {
    printf('No record found.<br />');
}
mysqli_free_result($result);

// INVIO MAIL

foreach($prodotti as $prodotto) {
    if(isset($_POST['quantita'.$prodotto['idProdotto']]) and $_POST['quantita'.$prodotto['idProdotto']] > 0) {
        $prodottiAcquistati[] = array('id' => $prodotto['idProdotto'], 'quantita' => $_POST['quantita'.$prodotto['idProdotto']]);
    }
}

// prendo l'id dell'utente
$result = $mysqli->query('SELECT idUtente FROM utenti WHERE username = "' . $account->username . '"');
if ($result->num_rows > 0) {
    if($row = $result->fetch_assoc()) {
        $id_account = $row['idUtente'];
    }
}

// controllo che le quantità richieste non superino le quantità disponibili 
foreach($prodottiAcquistati as $prodottoAcquistato) {
    $stmt = $mysqli->prepare('SELECT quantita FROM cellulari WHERE idProdotto = ?');
    $stmt->bind_param('i', $prodottoAcquistato['id']);
    $stmt->execute();
    if ($stmt->bind_result($quantitaDisponibile)) {
        $stmt->fetch();
        $quantitaDisponibili[$prodottoAcquistato['id']] = $quantitaDisponibile;
        $stmt->close();
    }

    if($quantitaDisponibile < $prodottoAcquistato['quantita']) {
        echo 'Uno dei prodotti nel carrello non è più disponibile';
        exit(1);
    }
}

// inserisco dentro la tabella acquisti i prodotti acquistati con le relative quantità
foreach($prodottiAcquistati as $prodottoAcquistato) {
    $stmt = $mysqli->prepare('INSERT INTO acquisti(idUtente, idProdotto, quantita, dataAcquisto) VALUES (?, ?, ?, ?)');
    $data = date('Y-m-d H:i:s');
    $stmt->bind_param('iiis', $id_account, $prodottoAcquistato['id'], $prodottoAcquistato['quantita'],  $data);
    $stmt->execute();
    $stmt->close();
}

// modifico le quantità nella tabella dei prodotti
foreach($prodottiAcquistati as $prodottoAcquistato) {
    $stmt = $mysqli->prepare('UPDATE cellulari SET quantita = ? WHERE idProdotto = ?');
    $nuovaQuantita = intval($quantitaDisponibili[$prodottoAcquistato['id']]) - intval($prodottoAcquistato['quantita']);
    $stmt->bind_param('is', $nuovaQuantita, $prodottoAcquistato['id']);
    $stmt->execute();
    $stmt->close();
}

// svuotare il carrello
$stmt = $mysqli->prepare('DELETE FROM carrelli  WHERE carrelli.idUtente = ?');
$stmt->bind_param('i', $id_account);
$stmt->execute();
$stmt->close();

// EMAIL CON IMMAGINE (NON FUNZIONA)
/*
$boundary = "==String_Boundary_x" .md5(time()). "x";

$riga = '';
foreach($prodotti as $prodotto) {
    foreach($prodottiAcquistati as $prodottoAcquistato) {
        if($prodotto['idProdotto'] === $prodottoAcquistato['id']) {
            $riga .= '
            <tr>
                <td style="width: 150px">'.$prodotto["nomeProdotto"].'</td>
                <td style="width: 100px">'.$prodotto["prezzo"].'€</td>
                <td style="width: 60px">'.$prodottoAcquistato["quantita"].'</td>
                <td style="width: 300px"><img src="cid:'.$prodotto["idProdotto"].'" style="width: 95%"></img></td>
            </tr>
            --'.$boundary.'
            Content-ID: <'.$prodotto["idProdotto"].'>
            Content-Type: image/jpeg
            Content-Transfer-Encoding: base64
            '.base64_encode($prodotto['immagine']).'
            --'.$boundary.'--
            ';
            break;
        }
    }
}

$message = '
<html>
    <head>
        <title>Acquisti</title>
    </head>
    <body>
        <h1 style="text-align: center">Acquisti</h1>
        <table border="1" style="text-align: center; width: 500px; padding: 0 !important; margin: 0 auto; border: 0.5px solid #000; border-top-left-radius: 15px; border-top-right-radius: 15px;">
            <thread>
                <td style="border: 0">Nome</td>
                <td style="border: 0">Prezzo</td>
                <td style="border: 0">Quantita</td>
                <td style="border: 0">Immagine</td>
            </thread>
            '.$riga.'
        </table>
    </body>
</html>
';

$headers[] = 'MIME-Version: 1.0';
$headers[] = 'Content-type: multipart/related; charset=utf-8';
$headers[] = 'type="multipart/alternative";';
$headers[] = 'boundary="'.$boundary.'"';
$headers[] = 'From: "Daniele Di Spirito" <dispiritodaniele.noreply@gmail.com>';

mail($account->email, 'Phonify | Ecco i tuoi acquisti', $message, implode("\r\n", $headers));
*/

// EMAIL SENZA IMMAGINE

$riga = '';
foreach($prodotti as $prodotto) {
    foreach($prodottiAcquistati as $prodottoAcquistato) {
        if($prodotto['idProdotto'] === $prodottoAcquistato['id']) {
            $riga .= '
            <tr>
                <td style="width: 180px"><a href="https://dispiritodaniele.altervista.org/projects/phonify/single-product.php?id='.$prodotto["idProdotto"].'">Clicca qui per vederlo</a></td>
                <td style="width: 180px">'.$prodotto["nomeProdotto"].'</td>
                <td style="width: 180px">'.$prodotto["prezzo"].'€</td>
                <td style="width: 180px">'.$prodottoAcquistato["quantita"].'</td>
            </tr>
            ';
            break;
        }
    }
}

$message = '
        <h1 style="text-align: center">Acquisti</h1>
        <br>
        <table border="1" style="text-align: center; font-size:  15px; height: 100px; padding: 0 !important; margin: 0 auto; border: 0.5px solid #000; border-top-left-radius: 15px; border-top-right-radius: 15px;">
            <tr>
                <td style="border: 0"><b>Link</b></td>    
                <td style="border: 0"><b>Nome</b></td>
                <td style="border: 0"><b>Prezzo</b></td>
                <td style="border: 0"><b>Quantita</b></td>
            </tr>
            '.$riga.'
        </table>
        <br>
';

$headers[] = 'MIME-Version: 1.0';
$headers[] = 'Content-type: text/html; charset=utf-8';
$headers[] = 'From: "Daniele Di Spirito" <dispiritodaniele.noreply@gmail.com>';

mail($account->email, 'Phonify | Ecco i tuoi acquisti', $message, implode("\r\n", $headers));

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
                <div class="col-lg-12">
                    <div class="section-heading">
                        <h1><?=$title?> <i class="bi bi-check-all" style="font-size: 50px"></i></h1>
                        <br><div style="font-size: 1.25rem">Controlla la tua mail (<u><?=$account->email?></u>)</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ***** Products Area Ends ***** -->

    <!-- ***** Footer Start ***** -->
    <footer style="margin-top: 43px !important">
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
                        <li><a href="../..">Crediti</a></li>
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