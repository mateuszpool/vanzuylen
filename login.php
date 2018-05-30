<?php
  session_start();
  if ((isset($_SESSION['login'])))
  {
    header('Location: management.php');
    exit();
  }
  else
  {
    require_once "connect.php";

    $connection = @new mysqli($host, $db_user, $db_password, $db_name);

    if($connection->connect_errno!=0)
    {
      echo "Error: ".$connection->connect_errno;
    }
    else
    {
      $email = $_POST['email'];
      $wachtwoord = $_POST['wachtwoord'];

      $sql = "SELECT * FROM gebruikers WHERE email='$email' AND wachtwoord='$wachtwoord'";
      if($result = @$connection->query($sql))
      {
        //aantal opgehaalde resultaten
        $num_users = $result->num_rows;

        if($num_users>0)
        {
          //ophalen van gegevens en toekennen aan een tabel
          $table = $result->fetch_assoc();
          $_SESSION['email'] = $table['email'];
          $_SESSION['voornaam'] = $table['voornaam'];

          $result->free();
          $_SESSION['login'] = true;
          header('Location: management.php');
        }
        else
        {
          $_SESSION['blad'] = '<span style="color:red">Wrong email or password!</span>';
          header('Location: logon.php');
        }
      }
      else
      {
        $_SESSION['blad'] = '<span style="color:red">There is no connection with the database</span>';
        header('Location: logon.php');
      }
    }
    $connection->close();
  }
?>
