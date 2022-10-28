<!DOCTYPE html>

<?php
session_start();
if (isset($_SESSION['session']) && (!isset($_SESSION['codice']) or $_SESSION['codice'] === -1)) {
    $account = json_decode(base64_decode($_SESSION['session']));
}

if(!isset($account) or $account->username !== 'admin') {
    echo 'Devi essere admin';
    exit(0);
}

include '../config.php';

$result = $mysqli->query('SELECT * FROM cellulari WHERE idProdotto = ' . intval($_GET['id']));
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $prodotto = $row;
    }
} else {
    echo 'Nessun prodotto con quell\'ID trovato';
    exit();
}
mysqli_free_result($result);

?>

<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900&display=swap" rel="stylesheet">

    <title>Phonify - <?= $prodotto['nomeProdotto'] ?></title>


    <!-- Additional CSS Files -->
    <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css" rel="stylesheet" />

    <link rel="stylesheet" type="text/css" href="../assets/css/font-awesome.css">

    <link rel="stylesheet" href="../assets/css/style.css">

    <link rel="stylesheet" href="../assets/css/owl-carousel.css">

    <link rel="stylesheet" href="../assets/css/lightbox.css">

    <link rel="stylesheet" href="../css_table/main.css">
    <link rel="stylesheet" href="../css_table/util.css">
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

    <br><br><br>
    <!-- ***** Product Area Starts ***** -->
    <section class="section" id="product">
        <form method="POST" action="../api/modifica-prodotto.php">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="left-images" style="display: flex; justify-content: center;">
                            <?= '<img src="data:image/jpg;base64,' . base64_encode($prodotto['immagine']) . '" alt="" style="width: 25rem; height: auto;">' ?>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="right-content">
                            <h4><?= $prodotto['nomeProdotto'] ?></h4>
                            <span class="price"><?= $prodotto['prezzo'] ?> €</span>
                            <br>
                            <div class="table100">
                                <table style="border: 2px">
                                    <tbody>
                                        <tr>
                                            <td class="column1"><b>Marca</b></td>
                                            <td class="column2">
                                                <select name="marca">
                                                    <?php 
                                                        foreach(['Apple', 'Samsung', 'Xiaomi', 'Huawei', 'Oppo', 'Sony', 'Google', 'Wiko'] as $marca) {
                                                            if($prodotto['marca'] === $marca) {
                                                                $selected = 'selected';
                                                            } else {
                                                                $selected = '';
                                                            }
                                                            echo '<option value="'.$marca.'" ' . $selected . '>'.$marca.'</option>';
                                                        }
                                                    ?>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="column1"><b>RAM</b></td>
                                            <td class="column2">
                                                <select name="RAM">
                                                    <?php 
                                                        foreach(['1 GB', '2 GB', '4 GB', '6 GB', '8 GB', '16 GB'] as $ram) {
                                                            if($prodotto['RAM'] === $ram) {
                                                                $selected = 'selected';
                                                            } else {
                                                                $selected = '';
                                                            }
                                                            echo '<option value="'.$ram.'" ' . $selected . '>'.$ram.'</option>';
                                                        }
                                                    ?>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="column1"><b>Capacità</b></td>
                                            <td class="column2">
                                                <select name="capacita">
                                                    <?php 
                                                        foreach(['16 GB', '32 GB', '64 GB', '128 GB', '256 GB', '512 GB', '1 TB', '2 TB'] as $capacita) {
                                                            if($prodotto['capacita'] === $capacita) {
                                                                $selected = 'selected';
                                                            } else {
                                                                $selected = '';
                                                            }
                                                            echo '<option value="'.$capacita.'" ' . $selected . '>'.$capacita.'</option>';
                                                        }
                                                    ?>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="column1"><b>Colore</b></td>
                                            <td class="column2">
                                                <input type="text" name="color" value="<?= $prodotto['colore']?>" style="width: 60%; height: 22px;">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="column1"><b>OS</b></td>
                                            <td class="column2">
                                                <select name="os">
                                                    <?php 
                                                        foreach(['iOS', 'iOS 7', 'iOS 8', 'iOS 9', 'iOS 10', 'iOS 11', 'iOS 12', 'iOS 13', 'iOS 14', 'iOS 15', 'iOS 16', 'Android 10', 'Android 11', 'Android 12', 'Android Oreo', 'Android Pie'] as $os) {
                                                            if($prodotto['os'] === $os) {
                                                                $selected = 'selected';
                                                            } else {
                                                                $selected = '';
                                                            }
                                                            echo '<option value="'.$os.'" ' . $selected . '>'.$os.'</option>';
                                                        }
                                                    ?>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="column1"><b>Dimensioni</b></td>
                                            <td class="column2">
                                                <input type="number" name="color" value="<?= str_replace(' pollici', '', str_replace(',', '.', $prodotto['dimensioni'])) ?>" step='0.1' min='0' style="width: 60%; height: 22px">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <br>
                            <div class="total" style="margin-top: 20px">
                                <div class="main-border-button">
                                    <a href="api/cart.php?id=<?php echo $_GET['id']; ?>">
                                        Salva modifiche
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <textarea name="descrizione" style="margin-top: 40px;margin-left: 30px; margin-right: 30px; width: 100%; height: 200px"><?= $prodotto['descrizione'] ?></textarea>
                </div>
            </div>
        </form>
    </section>
    <!-- ***** Product Area Ends ***** -->

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
    <script src="../assets/js/quantity.js"></script>

    <!-- Global Init -->
    <script src="../assets/js/custom.js"></script>

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

</html>