<?php

    if(isset($_POST['username']) && isset($_POST['password'])) {
        //ini_set('session.gc_maxlifetime', 10); // session time
        //session_set_cookie_params(10);
        session_start();
        $username = $_POST['username'];
        $password = $_POST['password'];
        $_SESSION["account"] = base64_encode(json_encode($username . ';' . md5($password)));
        echo '<p>Registrazione avvenuta con successo</p>';
        echo 'Account token: ' . $_SESSION['account'];
        echo '
        <br><br>
        <form action="login.php" method="POST">
           <button type="submit">FAI LOGIN</button>
        </form>';
    } else {
        echo '<p>Registrazione non avvenuta</p>';
    }
?>