<?php
    session_start();
    $token = '';
    if(isset($_POST['username']) && isset($_POST['password'])) {
        //ini_set('session.gc_maxlifetime', 10); // session time
        //session_set_cookie_params(10);
        //session_start();
        $username = $_POST['username'];
        $password = $_POST['password'];
        $token = base64_encode(json_encode($username . ';' . md5($password)));
    }
    if($token === $_SESSION['account']) {
        echo 'Ciao ' . $_SESSION['username'] . '!';
    } else {
        echo 'Login fallito!';
    }
    unset($_SESSION);
?>