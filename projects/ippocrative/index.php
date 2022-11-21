<!DOCTYPE html>
<html>

<?php
    session_start();
    if(isset($_SESSION['session'])) {
        $account = json_decode(base64_decode($_SESSION['session']));

    } else header('Location: login.php');
    
    include 'config.php';
    $stmt = $mysqli->prepare('SELECT idDomanda FROM utenti_ippocrative WHERE email=?');
    $stmt->bind_param('s', $account->email);
    $stmt->execute();

    if ($stmt->bind_result($idDomanda)) {
        $stmt->fetch();
        $stmt->close();
    }
    $scritta = 'Inizia';
    if(!is_null($idDomanda)) {
        $scritta = 'Continua';
    }
?>

</html>

<head>
    <title>Ippocrative | @<?= $account->username ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="quiz.css">
</head>

<body>
    <a href="index.php"><i class="bi bi-house"></i></a>
    <table id="info_table">
        <tr>
            <td>Username</td>    
            <td><?= $account->username ?></td>
        </tr>
        <tr>
            <td>Email</td>
            <td><?= $account->email ?></td>
        </tr>
    </table>
    <div class="home">
        <button class="home" onclick="document.location.href='quiz.php'"><?=$scritta?> partita</button>
        <button class="home" onclick="document.location.href='api/logout.php'">Logout</button>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/1.20.2/TweenMax.min.js"></script>
    <script src="form.js"></script>

</body>

</html>