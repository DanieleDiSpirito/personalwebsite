<!DOCTYPE html>

<?php
include 'config.php';
// $idDomanda = @$_POST['domandaSuccessiva'] || 1;
$idDomanda = 1;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $idDomanda = intval($_POST['id_domanda']);
}
$stmt = $mysqli->prepare('SELECT id, domanda, risposta FROM domande WHERE id=?');
$stmt->bind_param('i', $idDomanda);
$stmt->execute();
if ($stmt->bind_result($id, $domanda, $risposta)) {
  $stmt->fetch();
  $stmt->close();
}
if (!(isset($id) || isset($domanda) || isset($risposta))) {
  die('Test finito');
}

// risposte nel db
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  //die(strtoupper(implode($_POST['res'])));
}

?>

<html>

<head>
  <meta charset="utf-8">
  <link href="style.css" rel="stylesheet" />
  <script src="script.js" type="text/javascript"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css" rel="stylesheet" />
</head>

<body>
  <a href=".."><i class="bi bi-arrow-left-circle"></i></a>
  <nav>
    <img src="assets/logo-nobg.png"></img>
  </nav>
  <main>
    <div id="domanda">Domanda #<?= $idDomanda ?></div>
    <div id="title"><?= $domanda ?></div>
    <form method="POST" action="index.php">
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
      <div id="submit">
        <button type="submit" name="id_domanda" value="<?= $idDomanda + 1 ?>" class="invia">&nbsp;&nbsp;&nbsp;Invia&nbsp;&nbsp;&nbsp;</button>
        <?php if ($idDomanda !== 1) : ?>
          <button type="submit" name="id_domanda" value="<?= $idDomanda - 1 ?>" class="arrow"><i class="bi bi-arrow-left"></i></button>
        <?php endif; ?>
      </div>
    </form>
  </main>
</body>

</html>