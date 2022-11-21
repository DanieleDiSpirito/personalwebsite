<!DOCTYPE html>

<?php
session_start();
include 'config.php';

if(isset($_SESSION['session'])) {
  $account = json_decode(base64_decode($_SESSION['session']));
  $stmt = $mysqli->prepare('SELECT idDomanda, nAiuti FROM utenti_ippocrative WHERE email=?');
  $stmt->bind_param('s', $account->email);
  $stmt->execute();
  if ($stmt->bind_result($idDomanda, $nAiuti)) {
    $stmt->fetch();
    $stmt->close();
  }
  $_SESSION['aiuti'] = $nAiuti;
} else {
  if(!isset($_SESSION['aiuti'])) {
    $_SESSION['aiuti'] = 10;
    $_SESSION['idx_aiuti'] = array();
  }
}


$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  if(!isset($idDomanda)) $idDomanda = 1;
  $_SESSION['domanda'] = $idDomanda;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if(!isset($_SESSION['domanda']) or $_SESSION['domanda'] != intval($_POST['id_domanda'] - 1)) {
    die('Non barare!');
  }
  $idDomanda = intval($_POST['id_domanda']) - 1;
  $_SESSION['domanda'] = intval($_POST['id_domanda']);
}

$stmt = $mysqli->prepare('SELECT id, domanda, risposta FROM domande WHERE id=?');
$stmt->bind_param('i', $idDomanda);
$stmt->execute();

if ($stmt->bind_result($id, $domanda, $risposta)) {
  $stmt->fetch();
  $stmt->close();
}

if (!(isset($id) || isset($domanda) || isset($risposta))) {
  die('Errore');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $rispostaRaw = strtolower($risposta);
  $rispostaRaw = str_replace(' ', '', $rispostaRaw);
  $rispostaRaw = str_replace('-', '', $rispostaRaw);
  $rispostaRaw = str_replace('\'', '', $rispostaRaw);
  $rispostaData = str_replace('à', 'a', strtolower(implode('', $_POST['res'])));
  
  if($rispostaRaw !== $rispostaData) {
    $error = 'Risposta sbagliata!';
    $riscrivi = true;
    $_SESSION['domanda']--;
  } else {
    // svuota tabella aiuti dell'utente (svuota la session con gli aiuti per gli anonymous)
    include 'api/svuota_db_aiuti.php';

    if($idDomanda === 36) {
      session_start();
      $_SESSION['end'] = true;
      header('Location: finetest.php');
    }

    if(isset($_SESSION['session'])) {
      $stmt = $mysqli->prepare('UPDATE utenti_ippocrative SET idDomanda = ? WHERE utenti_ippocrative.email = ?;');
      $idDomanda += 1;
      $stmt->bind_param('is', $idDomanda, $account->email);
      $stmt->execute();
      $stmt->close();
      $idDomanda -= 1;
    }

    $stmt = $mysqli->prepare('SELECT id, domanda, risposta FROM domande WHERE id=?');
    $idDomanda += 1;
    $stmt->bind_param('i', $idDomanda);
    $stmt->execute();

    if ($stmt->bind_result($id, $domanda, $risposta)) {
      $stmt->fetch();
      $stmt->close();
    }

    if (!(isset($id) || isset($domanda) || isset($risposta))) {
      die('Errore');
    }
  }
}

?>

<html>

<head>
  <title>Ippocrative | Quiz</title>
  <meta charset="utf-8">
  <script src="script.js" type="text/javascript"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css" rel="stylesheet" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR&display=swap" rel="stylesheet">
  <link href="quiz.css" rel="stylesheet" />

  <script>
    
    document.addEventListener('DOMContentLoaded', () => {
      const hint = async() => {
        risposta = await fetch('api/hint.php').then(response => response.json());
        // se la risposta è '' [facendo die('')]
        if(risposta === '') return;
        document.querySelectorAll('div[id="carattere"], input[type=text][maxlength="1"]')[risposta['indice']].outerHTML = `<input type="hidden" name="res[]" value="${risposta['lettera']}"><div id="carattere">${risposta['lettera']}</div>`;
        document.querySelector('span#punteggio').innerHTML = (parseInt(document.querySelector('span#punteggio').innerHTML.split('/')[0]) - 1).toString() + '/10';
      }
      
      document.querySelector('button[name=hint]').addEventListener('click', () => {
        hint();
      })
    });
    
  </script>
</head>

<body>
  <a href="index.php"><i class="bi bi-house"></i></a>
  <nav>
    <img src="assets/logo-nobg.png"></img>
  </nav>
  <main>
    <div id="domanda">Domanda #<?= $idDomanda ?></div>
    <progress value="<?= $idDomanda ?>" max="36"></progress>
    <div id="title"><?= $domanda ?></div>
    <form method="POST" action="quiz.php">
      <fieldset class="container">  
        <?php if(isset($riscrivi) and $riscrivi === true) : ?>
          <?php
            if(isset($_SESSION['session'])) {
              $stmt = $mysqli->prepare('SELECT indice FROM aiuti WHERE mail_utente=?');
              $stmt->bind_param('s', $account->email);
              $stmt->execute();
              $stmt->bind_result($indice);
              $indici = array();
              while($stmt->fetch()) {
                  $indici[] = $indice;
              }
              $stmt->close();
            } else {
                $indici = $_SESSION['idx_aiuti'];
            }
          ?>
          <?php for ($i = 0; $i < strlen($risposta); $i++) : ?>
            <?php if(in_array($i, $indici)) : ?>
              <input type="hidden" name="res[]" value="<?=$risposta[$i]?>">
              <div id="carattere"><?=$risposta[$i]?></div>
            <?php elseif(preg_match('/[A-Z0-9a-z]/', $risposta[$i])) : ?>
              <input type="text" name="res[]" maxlength="1" size="1" />
            <?php else : ?>
              <div id="carattereSAT"><?=$risposta[$i]?></div>
            <?php endif; ?>
          <?php endfor; ?>
        <?php else : ?>
          <?php for($i = 0; $i < strlen($risposta); $i++) : ?>
            <?php if(preg_match('/[A-Z0-9a-z]/', $risposta[$i])) : ?>
              <input type="text" name="res[]" maxlength="1" size="1" />
            <?php else : ?>
              <div id="carattereSAT"><?=$risposta[$i]?></div>
            <?php endif; ?>
          <?php endfor; ?>
        <?php endif; ?>
      </fieldset>
      <div id="error"><?=$error?></div>
      <div id="submit">
        <?php if ($idDomanda !== 36) : ?>
          <button type="submit" name="id_domanda" value="<?= $idDomanda + 1 ?>" class="invia" id="row"><i class="bi bi-arrow-right-short"></i></button>
        <?php else : ?>
          <button type="submit" name="id_domanda" value="<?= $idDomanda + 1 ?>" class="invia">Invia</button>
        <?php endif; ?>
        <button type="button" name="hint" value="hint" class="invia" id="row"><i class="bi bi-question"></i></button><span id="punteggio"><?=$_SESSION['aiuti']?>/10</span>
      </div>
    </form>
  </main>

</body>

</html>