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
        <link rel="icon" type="image/x-icon" href="favicon.ico" />
        <!-- Bootstrap icons -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css" rel="stylesheet" />
        <!-- Bootstrap core JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme CSS (includes Bootstrap) -->
        <link href="style.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Anonymous+Pro">
        <!-- js -->
        <script src="home.js"></script>
    </head>
    <body>
        <div style="margin: 10%" align="center">
            <form action="" method="GET">
                <p>
                    <div style="color: white; font-size: 24px; margin-top: 20px; margin-bottom: 2px">PARTENZA</div>
                    <input type="text" name="from" list="lista-citta" class="font"><br>
                    <div style="color: white; font-size: 24px; margin-top: 20px; margin-bottom: 2px">ARRIVO</div>
                    <input type="text" name="to"   list="lista-citta" class="font">
                </p><br>
                <datalist id="lista-citta">
                    <?php
                        $handler = fopen('coordinate.csv', 'r');
                        fgets($handler); // the first line is useless
                        while(!feof($handler)) {
                            $row = fgetcsv($handler, null, ',');
                            echo '<option>' . $row[1] . '</option>' . PHP_EOL;
                        }
                    ?>
                </datalist>
                <p><button type="submit" class="bn5">INVIA</button></p>
            </form>

            <?php
            if(isset($_GET['from']) && isset($_GET['to'])) {
                if($_GET['from'] == '' || $_GET['to'] == '') {
                    echo '<div class="risultato errore">Inserire una partenza ed una destinazione</div>';
                } else {
                    $handler = fopen('coordinate.csv', 'r');
                    fgets($handler); // the first line is useless
                    $i = 0;
                    $latfrom = 0;
                    $lngfrom = 0;
                    $latto = 0;
                    $lngto = 0;
                    $from = strtoupper($_GET['from']);
                    $to = strtoupper($_GET['to']);
                    while (!feof($handler)) {
                        $row = fgetcsv($handler, null, ',');
                        if (!isset($row[1])) break;
                        if ($from === strtoupper($row[1])) {
                            $latfrom = $row[3];
                            $lngfrom = $row[2];
                            $i++;
                        }
                        if ($to === strtoupper($row[1])) {
                            $latto = $row[3];
                            $lngto = $row[2];
                            $i++;
                        }
                        if ($i === 2) {
                            break;
                        }
                    }
                    if($i === 2) {
                        echo '<div class="risultato">La distanza è di ' . round(getDistance($latfrom, $lngfrom, $latto, $lngto), 2) . ' km</div>';
                    } else {
                        echo '<div class="risultato errore">La/e città inserita/e non è/sono valida/e</div>';
                    }
                }
            }
            ?>

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

