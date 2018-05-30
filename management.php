<?php
  session_start();
  if ((!isset($_SESSION['login'])))
  {
    header('Location: index.php');
    exit();
  }
?>
<!DOCTYPE HTML>
<html>
<head>
  <title>Management</title>
</head>
<body>
  <?php
    echo "<center><p>Welkom, ".$_SESSION['voornaam'].'! [ <a href="logout.php">Uitloggen</a> ] </p>';
    echo "<p><b>E-mail: </b>".$_SESSION['email']."</p>";
   ?>
</body>
</head>
</html>
