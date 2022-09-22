<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="Home page" />
        <meta name="author" content="Daniele Di Spirito" />
        <!-- Title -->
        <title>Daniele Di Spirito | </title>
        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="favicon.ico" />
        <!-- Bootstrap icons -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css" rel="stylesheet" />
        <!-- Bootstrap core JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme CSS (includes Bootstrap) -->
        <link href="home.css" rel="stylesheet" />
        <!-- js -->
        <script src="home.js"></script>
    </head>
    <body>
        <?php echo getDistance(100, 50, 100, 49); ?>
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

