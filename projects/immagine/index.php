<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>
    <?php
      $db_host = 'localhost';
      $db_user = 'root';
      $db_password = '';
      $db_name = 'my_dispiritodaniele';
      $conn = mysqli_connect($db_host, $db_user, $db_password, $db_name);

      $stmt = mysqli_query($conn, 'SELECT image FROM immagine');
      while($row = mysqli_fetch_array($stmt)) {
          $image = $row['image'];
          echo '<img src="data:image/jpg;base64,'.$image.'">';
      }


      if(isset($_POST['nome'])) {
          $nome = $_POST['nome'];
          $image = base64_encode(file_get_contents($_FILES['immagine']['tmp_name']));
          $stmt = mysqli_query($conn, "INSERT into immagine (nome, image) VALUES ('".$nome."', '".$image."')");
          //mysqli_commit();
      }
    ?>

    <form name="insert" method="POST" action="" enctype="multipart/form-data">
        <input type="text" name="nome"><br>
        <input type="file" name="immagine" accept="image/*"><br>
        <input type="submit">
    </form>
</body>
</html>