<!DOCTYPE html>

<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="Home page" />
        <meta name="author" content="Daniele Di Spirito" />
        <!-- Title -->
        <title>IMC Calculator</title>
        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="favicon.ico" />
        <!-- Bootstrap icons -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css" rel="stylesheet" />
        <!-- Bootstrap core JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme CSS (includes Bootstrap) -->
        <link href="style.css" rel="stylesheet" />
        <!-- js -->
        <script src="script.js"></script>
    </head>
    <body style="color: white">
        <div align="left">
            <a href=".." style="color: #476ae8"><i class="bi bi-arrow-left-circle" style="font-size: 2.5rem"></i></a>
        </div>
        <br><br>
        <form action='' class='form' method="POST">
            <p class='field'>
                <label class='label' for='email'>Altezza (cm)</label>
                <input class='text-input' name='altezza' required type='number' min="50" max="275" value="<?php if(isset($_POST['altezza'])) { echo $_POST['altezza']; } ?>">
            </p>
            <p class='field'>
                <label class='label' for='phone'>Peso (Kg)</label>
                <input class='text-input' name='peso' type='number' min="25" max="650" value="<?php if(isset($_POST['peso'])) { echo $_POST['peso']; } ?>">
            </p>
            <p class='field' style="padding-top: 20px;">
                <input class='button' type='submit' value='Invia'>
            </p>
        </form>
        <?php
            if(isset($_POST['altezza']) && $_POST['peso']) {
                $altezza = $_POST['altezza'] / 100; // in metri
                $peso = $_POST['peso'];
                $imc = $peso / ($altezza ** 2);
                echo '<p class="field" style="text-align: center"><b>Il tuo IMC è '. round($imc, 2) .' Kg/m²</b></p>';
                $categoria = '';
                if($imc < 16.5) {
                    $categoria = '<span style="color: red">Sottopeso severo</span>';
                } elseif ($imc >= 16.5 && $imc < 18.5) {
                    $categoria = '<span style="color: indianred">Sottopeso</span>';
                } elseif ($imc >= 18.5 && $imc < 25) {
                    $categoria = '<span style="color: greenyellow">Normopeso</span>';
                } elseif ($imc >= 25 && $imc <= 30) {
                    $categoria = '<span style="color: indianred">Sovrappeso</span>';
                } elseif ($imc > 30 && $imc < 35) {
                    $categoria = '<span style="color: #b02a37">Obesità di 1° grado</span>';
                } elseif($imc >= 35 && $imc < 40) {
                    $categoria = '<span style="color: #aa0000">Obesità di 2° grado</span>';
                } else { // >= 40
                    $categoria = '<span style="color: #ff0000">Obesità di 3° grado</span>';
                }
                $min = round(18.5 * $altezza ** 2, 2);
                $max = round(25.0 * $altezza ** 2, 2);
                echo '
                <p class="field" style="text-align: center"><b>'. $categoria . '</b></p>
                <p class="field" style="text-align: center"><b>Il tuo peso forma è compreso tra '. $min .' e ' . $max . ' kg</b></p>
                ';
            }
        ?>
    </body>
</html>