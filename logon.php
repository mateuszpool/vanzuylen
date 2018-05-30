<?php
  session_start();
  if (isset($_SESSION['login']))
  {
    header('Location: management.php');
  }
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>Login Van Zuylen</title>
</head>
<body>
  <form action="login.php" method="post">
            Email: </br> <input type="text" name="email"/> </br>
            Wachtwoord: </br> <input type="password" name="wachtwoord"/> </br>
            <input type="submit" value="Login" />
            <a href="register.php">Maak een account aan</a></br>
  </form>

<?php
    if (isset($_SESSION['blad']))
    {
        echo $_SESSION['blad'];
    }
?>
</body>
</html>
