<?php
require_once 'include\databaseLogin.php';

try {
    $pdo = new PDO($attr, $user, $pass, $opts);
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int)$e->getCode());
    die();
}

session_start();

if (!isset($_SESSION['loggedin'])) {
    if (isset($_POST['authenticateUser'])) {
        //Controleer of de invoervelden een waarde hebben.
        if (
            isset($_POST['username']) &&
            isset($_POST['password'])
        ) {
            //Ontdoe de ingevoerde waarden van ongeweste slashes en html.
            $username = sanitizeString($_POST['username']);
            $password = sanitizeString($_POST['password']);

            //Check of de gebruikersnaam bekend is in de database.
            $stmt = $pdo->prepare("SELECT * FROM User WHERE Username = ?");
            $stmt->bindParam(1, $username, PDO::PARAM_STR, 128);
            $stmt->execute([$username]);
            //Als de gebruiker bekend is, zet dan het wachtwoord in een variabele.
            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch();
                $pw = $row['Password'];
            } else {
                include_once 'view/login.php';
                echo "<p class='badMessage'>Gebruikersnaam en/of wachtwoord onjuist.</p>";
            }

            //Check of het gehashte wachtwoord overeen komt met het ingevoerde wachtwoord.
            if (password_verify(str_replace("'", "", $password), $pw)) {
                $_SESSION['loggedin'] = true;
                return true;
            } else {
                include_once 'view/login.php';
                echo "<p class='badMessage'>Gebruikersnaam en/of wachtwoord onjuist.</p>";
            }
        } else {
            include_once 'view/login.php';
            echo "<p class='badMessage'>U dient een gebruikersnaam en wachtwoord in te vullen.</p>";
        }
    } else {
        include_once 'view\login.php';
    }
}

//Ontdoe een string van ongewenste slashes en html.
function sanitizeString($str)
{
    $str = stripslashes($str);
    $str = strip_tags($str);
    $str = htmlentities($str);
    return $str;
}
