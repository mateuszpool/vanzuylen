<?php
session_start();
//przejdz do gry jak juz jestes zalogowany i zakoncz ladowanie exit();
if ((!isset($_SESSION['udanarejestracja'])))
{
header('Location: index.php');
exit();
}
else
{
unset($_SESSION['udanarejestracja']);
}

?>
<!DOCTYPE HTML>
<html>
<head>
    <title>Van Zuylen</title>
</head>
<body>
<h2>Bedankt voor je registratie</h2>
<a href="index.php">Click hier om aan te melden</a>
</body>
</head>
</html>
