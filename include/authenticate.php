<?php
require_once 'include\databaseLogin.php';

try {
    $pdo = new PDO($attr, $user, $pass, $opts);
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int)$e->getCode());
    die();
}

//Beëindig de sessie automatisch na 1 dag geen activiteit.
ini_set('session.gc_maxlifetime', 60 * 60 * 24);
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
            $stmt->bindParam(1, $username, PDO::PARAM_STR, 100);
            $stmt->execute([$username]);
            //Als de gebruiker bekend is, zet dan het wachtwoord in een variabele.
            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch();
                $pw = $row['Password'];
            } else {
                include_once 'view/login.php';
                echo "<p class='badMessage'>Gebruikersnaam en/of wachtwoord onjuist.</p>";
                die();
            }

            //Check of het gehashte wachtwoord in de database overeen komt met het ingevoerde wachtwoord.
            if (password_verify(str_replace("'", "", $password), $pw)) {
                //Zet sessie waarden.
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $username;
                return true;
            } else {
                include_once 'view/login.php';
                echo "<p class='badMessage'>Gebruikersnaam en/of wachtwoord onjuist.</p>";
                die();
            }
        } else {
            include_once 'view/login.php';
            echo "<p class='badMessage'>U dient een gebruikersnaam en wachtwoord in te vullen.</p>";
            die();
        }
    } else {
        include_once 'view\login.php';
        die();
    }
}

function sanitizeString($str)
{
    $str = stripslashes($str);
    $str = strip_tags($str);
    $str = htmlentities($str);
    return $str;
}
