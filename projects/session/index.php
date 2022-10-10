<!DOCTYPE html>
<html>
    <head>
        <title>LOGIN</title>
    </head>
    <body align="center">
        <?php
            if(isset($_SESSION['account'])) {
                echo '
                <form name="register" action="signup.php" method="POST">
                    <input type="text" name="username">
                    <input type="password" name="password">
                    <button type="submit" name="type" value="signup">REGISTRATI</button>
                </form>';
            } else {
                echo '
                <form name="login" action="login.php" method="POST">
                    <input type="text" name="username">
                    <input type="password" name="password">
                    <button type="submit" name="type" value="login">ACCEDI</button>
                </form>';
            }
        ?>
    </body>
</html>