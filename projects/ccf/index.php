<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="Home page" />
        <meta name="author" content="Daniele Di Spirito" />
        <!-- Title -->
        <title>CCF | Calcolo codice fiscale</title>
        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="favicon.ico" />
        <!-- Bootstrap icons -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css" rel="stylesheet" />
        <!-- Bootstrap core JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap-dark.bundle.min.js"></script>
        <!-- Core theme CSS (includes Bootstrap) -->
        <link href="../home.css" rel="stylesheet" />
        <link href="style.css" rel="stylesheet" />
        <!-- js -->
        <script src="../home.js"></script>
        <script src="script.js"></script>
    </head>
    <body style="background-color: #1E1F23 !important" onresize="hideHouse()">
        <!-- Header -->
        <nav class="navbar bg-dark" style="background-color: #1E1F23 !important; height: 160px">
            <div class="container" style="display: flex; flex-direction: row; gap: 0%">
                <a href=".." style="color: white !important; text-decoration: none">&nbsp;&nbsp;<i class="bi bi-arrow-left-circle" style="font-size: 2.5rem"></i></a>
                <a href="">
               	<img src="logo.jpg" alt="Bootstrap" width="80%">
               </a>
            </div>
        </nav>
        <!-- PHP -->
        <?php

            // database connection
            $db_host = 'localhost';
            $db_user = 'root';
            $db_password = '';
            $db_name = 'my_dispiritodaniele';
            $conn = mysqli_connect($db_host, $db_user, $db_password, $db_name);

            function isValid($stringa) {
                if($stringa === '') { return false; }
                $stringa = strtoupper($stringa);
                for($i = 0; $i < strlen($stringa); $i++) {
                    if(!preg_match('/[A-Z \-\'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõö÷øùúûüýþÿ]/i', $stringa[$i])) { return false; }
                }
                return true;
            }

            function istatCity($stringa) {

                global $conn;
                $query = "SELECT ISTAT FROM COMUNI WHERE NOME='$stringa'";
                $result = mysqli_query($conn, $query);
                return mysqli_fetch_array($result)[0];

/*
                $stringa = strtoupper($stringa);
                $handler = fopen('comuni.csv', 'r');
                while(!feof($handler)) {
                    $row = fgetcsv($handler, null, ';');
                    if($stringa === strtoupper($row[1])) { return $row[0]; }
                }
                return false;
*/
            }

            function controlloEta($stringa) {
                if ($stringa === '0' || $stringa === '40') { return true; }
                return false;
            }

            function controlloDataNascita($stringa) {
                if(preg_match('/^\d{4}(\-)(((0)[0-9])|((1)[0-2]))(\-)([0-2][0-9]|(3)[0-1])$/i', $stringa)) { return true; }
                return false;
            }
        ?>
        <!-- Page Content -->
        <div class="bg-dark text-white text-left" style="background-color: #1E1F23 !important" align="center">
            <form class="row g-2 container col-md-6 uppercase" style="margin: auto 0" action='' name="form" method="POST">

                <div class="col-md-6">
                    <label for="validationServer01" class="form-label">COGNOME</label>
                    <input type="text" name="cognome" value="<?php if(isset($_POST['cognome'])) { echo $_POST['cognome']; } ?>" placeholder="ROSSI" class="form-control <?php if(isset($_POST['cognome']) && !isValid($_POST['cognome'])) { echo 'is-invalid'; } ?>" id="validationServer01" value="" required onkeyup="controlloForm('cognome')">
                    <div id="validationServer01Feedback" class="invalid-feedback">
                    Sono consentiti solo lettere, spazio, ' e -
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="validationServer02" class="form-label">NOME</label>
                    <input type="text" name="nome" value="<?php if(isset($_POST['nome'])) { echo $_POST['nome']; } ?>" placeholder="MARIO" class="form-control <?php if(isset($_POST['nome']) && !isValid($_POST['nome'])) { echo 'is-invalid'; } ?>" id="validationServer02" value="" required onkeyup="controlloForm('nome')">
                    <div id="validationServer02Feedback" class="invalid-feedback">
                    Sono consentiti solo lettere, spazio, ' e -
                    </div>
                </div>

                <div class="py-2 col-md-12">
                    <label for="citta" class="form-label">LUOGO DI NASCITA</label>
                    <input type="text" name="nascita" value="<?php if(isset($_POST['nascita'])) { echo $_POST['nascita']; } ?>" placeholder="CITTÀ / NAZIONE" list="lista-comuni" class="form-control <?php if(isset($_POST['nascita']) && istatCity($_POST['nascita']) === false) { echo 'is-invalid'; } ?>" id="citta" required>
                    <datalist id="lista-comuni">
                        <?php

                        $query = 'SELECT * FROM COMUNI';
                        $result = mysqli_query($conn, $query);
                        while($row = mysqli_fetch_array($result)) {
                            echo '<option data-value=' . $row["ISTAT"] . '>' . $row["NOME"] . '</option>' . PHP_EOL;
                        }

                        /*
                        $handler = fopen('comuni.csv', 'r');
                        while(!feof($handler)) {
                            $row = fgetcsv($handler, null, ';'); // explode() incorporated
                            echo '<option data-value=' . $row[0] . '>' . $row[1] . '</option>' . PHP_EOL;
                        }
                        */

                        ?>
                    </datalist>

                    <div id="validationServer03Feedback" class="invalid-feedback">
                    Inserire una città valida
                    </div>
                </div>
                <div class="col-md-3">
                    <label for="validationServer04" class="form-label">SESSO</label>
                    <select class="form-select" name="sesso" id="validationServer04" aria-describedby="validationServer04Feedback" required>
                    <option <?php if(isset($_POST['sesso']) and $_POST['sesso'] === "0") { echo 'selected'; } ?> value="0">MASCHIO</option>
                    <option <?php if(isset($_POST['sesso']) and $_POST['sesso'] === "40") { echo 'selected'; } ?> value="40">FEMMINA</option>
                    </select>
                </div>
                <div class="col-md-9">
                    <label for="validationServer05" class="form-label">DATA DI NASCITA</label>
                    <input type="date" name="data" value="<?php if(isset($_POST['data'])) { echo $_POST['data']; } ?>" min="1900-01-01" max=<?php echo date("Y-m-d")?> class="form-control" id="validationServer05" aria-describedby="validationServer05Feedback" required>
                </div>
                <div class="py-5 col-12">
                    <button class="btn btn-primary" type="submit" name="invio" onclick="//controlloFinale()">CALCOLA</button>
                    <span style="width: 2rem"><input type="button" class="btn btn-danger" value="RESET" onclick="reset();"></span>
                </div>
            </form>
            <?php 

                $codice_fiscale = array();
                if(isset($_POST['invio'])) {

                    $istat = istatCity($_POST['nascita']);

                    if(isValid($_POST['cognome']) && isValid($_POST['nome']) && $istat !== false && controlloEta($_POST['sesso']) && controlloDataNascita($_POST['data'])) {

                        // COGNOME
                        $cognome = strtoupper($_POST['cognome']);
                        $cognome = strtoupper(iconv('utf-8', 'ASCII//TRANSLIT', $cognome));
                        $fine = false;

                        for($i = 0; $i < strlen($cognome); $i++) {
                            if(!preg_match('/[` \-\'AEIOU]/i', $cognome[$i])) {
                                $codice_fiscale[] = $cognome[$i];
                                if(count($codice_fiscale) === 3) { $fine = true; break; }
                            }
                        }
                        if (!$fine) {
                            for($i = 0; $i < strlen($cognome); $i++) {
                                if(preg_match('/[AEIOU]/i', $cognome[$i])) {
                                    $codice_fiscale[] = $cognome[$i];
                                    if(count($codice_fiscale) === 3) { $fine = true; break; }
                                }
                            }
                        }
                        if (!$fine) {
                            while(count($codice_fiscale) < 3) {
                                $codice_fiscale[] = 'X';
                            }
                        }

                        // NOME
                        $nome = strtoupper($_POST['nome']);
                        $nome = strtoupper(iconv('utf-8', 'ASCII//TRANSLIT', $nome));
                        $consonanti_nome = array();
                        for($i = 0; $i < strlen($nome); $i++) {
                            if(!preg_match('/[` \-\'AEIOU]/i', $nome[$i])) {
                                $consonanti_nome[] = $nome[$i];
                            }
                        }

                        switch (count($consonanti_nome)) {
                            case 0:
                                break;
                            case 1:
                                $codice_fiscale[] = $consonanti_nome[0];
                                break;
                            case 2:
                                array_push($codice_fiscale, $consonanti_nome[0], $consonanti_nome[1]);
                                break;
                            case 3:
                                array_push($codice_fiscale, $consonanti_nome[0], $consonanti_nome[1], $consonanti_nome[2]);
                                break;
                            default:
                                array_push($codice_fiscale, $consonanti_nome[0], $consonanti_nome[2], $consonanti_nome[3]);
                        }
                        if(count($codice_fiscale) !== 6) {
                            $fine = false;
                            for($i = 0; $i < strlen($nome); $i++) {
                                if(preg_match('/[AEIOU]/i', $nome[$i])) {
                                    $codice_fiscale[] = $nome[$i];
                                    if(count($codice_fiscale) === 6) { $fine = true; break; }
                                }
                            }
                            if(!$fine) {
                                while(count($codice_fiscale) < 6) {
                                    $codice_fiscale[] = 'X';
                                }
                            }
                        }

                        // ANNO
                        $data = $_POST['data'];
                        // $anno = $data[2] . $data[3];
                        $codice_fiscale[] = $data[2];
                        $codice_fiscale[] = $data[3];

                        // MESE
                        $array_mesi = [
                                "A", "B", "C", "D", "E", "H", "L", "M", "P", "R", "S", "T"
                        ];
                        $mese = intval($data[5] . $data[6]);
                        $codice_fiscale[] = $array_mesi[$mese - 1];

                        // GIORNO
                        $giorno = intval($data[8] . $data[9]) + intval($_POST['sesso']); // giorno + 0 se maschio, + 40 se femmina
                        if(strlen('' . $giorno) === 1) {
                            $codice_fiscale[] = '0';
                            $codice_fiscale[] = ('' . $giorno)[0];
                        } else {
                            $codice_fiscale[] = ('' . $giorno)[0];
                            $codice_fiscale[] = ('' . $giorno)[1];
                        }

                        // ISTAT
                        $codice_fiscale[] = $istat[0];
                        $codice_fiscale[] = $istat[1];
                        $codice_fiscale[] = $istat[2];
                        $codice_fiscale[] = $istat[3];

                        // CODICE DI CONTROLLO
                        $contatore = 0;
                        $dict_dispari = "BAKPLCQDREVOSFTGUHMINJWZYX";
                        for($i = 0; $i < count($codice_fiscale); $i++) {
                            if($i % 2 != 0) { // posizioni pari
                                if(preg_match('/[A-Z]/i', $codice_fiscale[$i])) {
                                    $contatore += ord($codice_fiscale[$i]) - ord('A');
                                } else {
                                    $contatore += intval($codice_fiscale[$i]);
                                }
                            } else { // posizioni dispari
                                if(preg_match('/[A-Z]/i', $codice_fiscale[$i])) {
                                    $contatore += strrpos($dict_dispari, $codice_fiscale[$i], 0);
                                } else {
                                    $contatore += strrpos($dict_dispari, chr(intval($codice_fiscale[$i]) + ord('A')), 0);
                                }
                            }
                        }
                        $codice_fiscale[] = chr($contatore % 26 + ord('A'));

                        // STAMPA
                        echo "
                        <div class='container'>
                            <h2>IL TUO CODICE FISCALE: " . implode('', $codice_fiscale) . "</h2>
                        </div>
                        ";
                    } else {
                        echo "
                        <div class='container'>
                            <h2 style='color: rgba(238, 69, 69, 0.8)'>DATI INSERITI ERRATI</h2>
                        </div>
                        ";
                    }
                }
            ?>

        </div>
    </body>
</html>
