<?php
 session_start();
 //isset betekent verzonden, maar niet perse ingevuld
 if (isset($_POST['email']))
 {
     //Stworzenie zmienniej do pozniejszego sprawdzania
     $wszystko_OK = true;
      //sprawdz E-mail
     $email = $_POST['email'];
     $emailb = filter_var($email, FILTER_SANITIZE_EMAIL);
     if ((filter_var($emailb, FILTER_VALIDATE_EMAIL)==false) || ($emailb!=$email)){
         $wszystko_OK = false;
         $_SESSION['e_email']="Check je E-mail";
     }
     //spradzanie hasel
     $wachtwoord1 = $_POST['wachtwoord1'];
     $wachtwoord2 = $_POST['wachtwoord2'];
     if ((strlen($wachtwoord1)<8) || (strlen($wachtwoord1)>20)){
         $wszystko_OK=false;
         $_SESSION['e_wachtwoord']="Check je wachtwoord";
     }
     if ($wachtwoord1!=$wachtwoord2)
     {
         $wszystko_OK=false;
         $_SESSION['e_wachtwoord']="Wachtwoorden zijn niet gelijk";
     }
     //hashowanie hasel
     //$hashed = password_hash($wachtwoord1, PASSWORD_BCRYPT);

     //achternaam en voornaam
     $achternaam = $_POST['achternaam'];
     $voornaam = $_POST['voornaam'];
     //regulamin
     if(isset($_POST['voorwaarden'])==false)
     {
         $wszystko_OK = false;
         $_SESSION['e_voorwaarden']="Je moet de algemene voorwaarden accepteren";
     }
     //Bot or Not
     $secretkey = "6LdhdB4UAAAAAN0YV7y8QAPbg7Vpo4WkpdjMEmqZ";
     $check = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secretkey.'&response='.$_POST['g-recaptcha-response']);
     $answer = json_decode($check);
     if($answer->success==false)
     {
         $wszystko_OK = false;
         $_SESSION['e_bot']="Check de Captcha";
     }
     //Sprawdzanie, czy dane nie istnieja w bazie danych
     require_once "connect.php";
     //wylaczanie info o loginie do mysql dla uzytkownikow
     mysqli_report(MYSQLI_REPORT_STRICT);
     try
     {
         $connection = new mysqli($host, $db_user, $db_password, $db_name);
         if ($connection->connect_errno!=0)
         {
           throw new Exception(mysqli_connect_errno());
         }
         else
         {
             //czy email istnieje
             $rezultat = $connection->query("SELECT id FROM gebruikers WHERE email='$email'");
             if (!$rezultat) throw new Exception($connection->error);
             $ile_takich_maili = $rezultat->num_rows;
             if($ile_takich_maili>0)
             {
                 $wszystko_OK = false;
                 $_SESSION['e_email']="Dit mailadres bestaat al in onze database";
             }
             //jak wszystko sie udalo dodajemy uzytkownika
             if ($wszystko_OK == true)
             {
                if ($connection->query("INSERT INTO gebruikers VALUES (NULL,'$email', '$achternaam', '$wachtwoord1', '$voornaam')"))
                {
                    $_SESSION['udanarejestracja']=true;
                    header("Location: welkom.php");
                }
                else
                {
                    throw new Exception($connection->error);
                }
             }
             $connection->close();
         }
     }
     catch(Exception $e)
     {
         echo '<span style="color:red;">Fout, probeer later</span>';
         echo '</br>Info voor developer: '.$e;
     }
}
?>
<!DOCTYPE HTML>
<html>
<head>
  <title>Van Zuylen Registratie</title>
  <script src='https://www.google.com/recaptcha/api.js'></script>
  <style>
    .error{
      color:red;
      margin-top: 10px;
      margin-bottom: 10px;
    }
  </style>
</head>
<body>

  <form method="POST">
      E-mail: </br> <input type="text" name="email" /> <br/>
      <?php
        if(isset($_SESSION['e_email']))
        {
            echo '<div class="error">'.$_SESSION['e_email'].'</div>';
            unset($_SESSION['e_email']);
        }
      ?>
      Voornaam: </br> <input type="text" name="voornaam" /> </br>
      <?php
        if(isset($_SESSION['e_voornaam']))
        {
            echo '<div class="error">'.$_SESSION['e_voornaam'].'</div>';
            unset($_SESSION['e_voornaam']);
        }
      ?>
      Achternaam: </br> <input type="text" name="achternaam" /> </br>
      <?php
      if(isset($_SESSION['e_achternaam']))
      {
          echo '<div class="error">'.$_SESSION['e_achternaam'].'</div>';
          unset($_SESSION['e_achternaam']);
      }
      ?>
      Wachtwoord: </br> <input type="password" name="wachtwoord1" /> </br>
      Herhaal wachtwoord: </br> <input type="password" name="wachtwoord2" /> </br>
      <?php
        if(isset($_SESSION['e_wachtwoord']))
        {
            echo '<div class="error">'.$_SESSION['e_wachtwoord'].'</div>';
            unset($_SESSION['e_wachtwoord']);
        }
      ?>
      <label>
        <input type="checkbox" name="voorwaarden" /> Ik accepteer de algemene voorwaarden
      </label>
      <?php
        if(isset($_SESSION['e_voorwaarden']))
        {
            echo '<div class="error">'.$_SESSION['e_voorwaarden'].'</div>';
            unset($_SESSION['e_voorwaarden']);
        }
      ?>
      <div class="g-recaptcha" data-sitekey="6LdhdB4UAAAAACYnJND-1DRbKW0z9xeW_qiT1U8L"></div></br>
      <?php
        if(isset($_SESSION['e_bot']))
        {
            echo '<div class="error">'.$_SESSION['e_bot'].'</div>';
            unset($_SESSION['e_bot']);
        }
      ?>
      <input type="submit" value="Doorgaan!" />
  </form>

</body>
</html>
