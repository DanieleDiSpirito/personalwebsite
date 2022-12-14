<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="Home page" />
        <meta name="author" content="Daniele Di Spirito" />
        <!-- Title -->
        <title>Daniele Di Spirito | Map</title>
        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="favicon.png" />
        <!-- Bootstrap icons -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css" rel="stylesheet" />
        <!-- Bootstrap core JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
        <!-- Core theme CSS (includes Bootstrap) -->
        <link href="style.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Anonymous+Pro">
        <!-- js -->
        <script src="script.js"></script>
    </head>
    <body>
        <br>&nbsp;&nbsp;
        <a href=".."><i class="bi bi-arrow-left-circle" style="font-size: 3rem; color: white" align="right"></i></a>
        <div class="container">
            <div class="row align-items-center">
                <div style="margin-top: 0%" align="center" class="col-6">
                    <form action="" method="GET">
                        <p>
                            <div style="color: white; font-size: 24px; margin-top: 20px; margin-bottom: 2px">PARTENZA</div>
                            <input type="text" name="from" list="lista-citta" class="font" value="<?php if(isset($_GET['from'])) { echo $_GET['from']; } ?>"><br>
                            <div style="color: white; font-size: 24px; margin-top: 20px; margin-bottom: 2px">ARRIVO</div>
                            <input type="text" name="to"   list="lista-citta" class="font" value="<?php if(isset($_GET['to'])) { echo $_GET['to']; } ?>">
                        </p>
                        <datalist id="lista-citta">
                            <?php
                                $db_host = 'localhost';
                                $db_user = 'root';
                                $db_password = '';
                                $db_name = 'my_dispiritodaniele';
                                $conn = mysqli_connect($db_host, $db_user, $db_password, $db_name);

                                $query = 'SELECT * FROM COORDINATE';
                                $result = mysqli_query($conn, $query);
                                while($row = mysqli_fetch_array($result)) {
                                    echo '<option>' . $row["comune"] . '</option>' . PHP_EOL;
                                }
                            ?>
                        </datalist><br>
                        <p><button type="submit" class="bn5">INVIA</button></p>
                    </form>
                    <br>
                    <?php
                    if(isset($_GET['from']) && isset($_GET['to'])) {
                        if($_GET['from'] == '' || $_GET['to'] == '') {
                            echo '<div class="risultato errore">Inserire una partenza ed una destinazione</div>';
                        } elseif (strtolower($_GET['from']) == strtolower($_GET['to'])) {
                            echo '<div class="risultato errore">Inserire una partenza ed una destinazione diversa</div>';
                        } else {
                            $i = 0;
                            $latfrom = 0;
                            $lngfrom = 0;
                            $latto = 0;
                            $lngto = 0;
                            $from = $_GET['from'];
                            $to = $_GET['to'];

                            $query = "SELECT latitudine, longitudine FROM COORDINATE WHERE comune='$from'";
                            $result = mysqli_fetch_array(mysqli_query($conn, $query));
                            if(!is_null($result)) {
                                $latfrom = $result[0];
                                $lngfrom = $result[1];
                                $i++;
                            }
                            $query = "SELECT latitudine, longitudine FROM COORDINATE WHERE comune='$to'";
                            $result = mysqli_fetch_array(mysqli_query($conn, $query));
                            if(!is_null($result)) {
                                $latto = $result[0];
                                $lngto = $result[1];
                                $i++;
                            }

                            if($i == 2) {
                                $EQUATORE = 40076;
                                $latCentro = ($latfrom + $latto) / 2;
                                $lngCentro = ($lngfrom + $lngto) / 2;
                                $zoom = 0;
                                if ($latfrom !== $latto and $lngfrom !== $lngto) {
                                    $zoom = floor(log($EQUATORE / getDistance($latfrom, $lngfrom, $latto, $lngto), 2));
                                }
                                echo '
                                <i class="bi bi-geo-alt"></i> _________________________ <i class="bi bi-geo-alt"></i>
                                <p>'. ucfirst(strtolower($_GET['from'])).' - '.ucfirst(strtolower($_GET['to'])).'</p>
                                <div class="risultato">La distanza ?? di ' . round(getDistance($latfrom, $lngfrom, $latto, $lngto), 2) . ' km</div></div>';
                                if ($latfrom !== $latto and $lngfrom !== $lngto) {
                                    echo '<div class="col-6"><iframe src="https://maps.google.com/maps?q='.$latCentro.','.$lngCentro.'&z='.$zoom.'&output=embed&language=it" width="550" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe></div>';
                                    $printed = true;
                                }

                            } else {
                                echo '<div class="risultato errore">La/e citt?? inserita/e non ??/sono valida/e</div>';
                            }
                        }
                    }

                    if(!isset($printed) || !$printed) {
                        echo '</div><br><div class="col-6" style="height: 500px; margin-top: 5%"><i   frame src="https://maps.google.com/maps?q=Italy&z=5&output=embed&language=it" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>';
                    }

                    mysqli_close($conn);
                    ?>


            </div>
        </div>
    </body>
    <?php

    function getDistance($lat1, $lon1, $lat2, $lon2) {

        $lat1r = $lat1 * pi() / 180;
        $lon1r = $lon1 * pi() / 180;
        $lat2r = $lat2 * pi() / 180;
        $lon2r = $lon2 * pi() / 180;
        $r = 6376.5; // return km
        $x1 = $r * cos($lat1r) * cos($lon1r);
        $y1 = $r * cos($lat1r) * sin($lon1r);
        $z1 = $r * sin($lat1r);
        $x2 = $r * cos($lat2r) * cos($lon2r);
        $y2 = $r * cos($lat2r) * sin($lon2r);
        $z2 = $r * sin($lat2r);

        return sqrt(($x2 - $x1)**2 + ($y2 - $y1)**2 + ($z2 - $z1)**2);
    }

    ?>

</html>

