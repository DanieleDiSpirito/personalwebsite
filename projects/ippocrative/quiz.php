<!DOCTYPE html>

<?php
session_start();
include 'config.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  $idDomanda = 1;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $idDomanda = intval($_POST['id_domanda']) - 1;
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
  $rispostaData = strtolower(implode('', $_POST['res']));

  if($rispostaRaw !== $rispostaData) {
    $error = 'Risposta sbagliata!';
  } else {
    if($idDomanda === 36) {
      session_start();
      $_SESSION['end'] = true;
      header('Location: finetest.php');
    }

    if(isset($_SESSION['session'])) {
      $account = json_decode(base64_decode($_SESSION['session']));
      $stmt = $mysqli->prepare('UPDATE utenti_ippocrative SET idDomanda = ? WHERE utenti_ippocrative.email = ?;');
      $idDomanda += 1;
      $stmt->bind_param('is', $idDomanda, $account->email);
      $idDomanda -= 1;
      $stmt->execute();
      $stmt->close();
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
  <link href="quiz.css" rel="stylesheet" />
  <script src="script.js" type="text/javascript"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css" rel="stylesheet" />
</head>

<body>
  <a href="home.php"><i class="bi bi-house"></i></a>
  <nav>
    <img src="assets/logo-nobg.png"></img>
  </nav>
  <main>
    <div id="domanda">Domanda #<?= $idDomanda ?></div>
    <progress value="<?= $idDomanda ?>" max="36"></progress>
    <div id="title"><?= $domanda ?></div>
    <form method="POST" action="quiz.php">
      <?= '<script>console.log("' . $risposta . '")</script>' ?>
      <fieldset class="container">
        <?php for ($i = 0; $i < strlen($risposta); $i++) : ?>
          <?php if (preg_match('/[A-Z0-9a-z]/', $risposta[$i])) : ?>
            <input type="text" name="res[]" maxlength="1" size="1" />
          <?php else : ?>
            <div id="carattere"><?= $risposta[$i] ?></div>
          <?php endif; ?>
        <?php endfor; ?>
      </fieldset>
      <div id="error"><?=$error?></div>
      <div id="submit">
        <?php if ($idDomanda !== 36) : ?>
          <button type="submit" name="id_domanda" value="<?= $idDomanda + 1 ?>" class="invia" id="row"><i class="bi bi-arrow-right-short"></i></button>
        <?php else : ?>
          <button type="submit" name="id_domanda" value="<?= $idDomanda + 1 ?>" class="invia">Invia</button>
        <?php endif; ?>
        <button type="button" name="hint" value="hint" class="invia" id="row"><i class="bi bi-question"></i></button><span id="punteggio">10/10</span>
      </div>
    </form>
  </main>
</body>

</html>