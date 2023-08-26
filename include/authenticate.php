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
        if (
            isset($_POST['username']) &&
            isset($_POST['password'])
        ) {
            $username = sanitizeString($_POST['username']);
            $password = sanitizeString($_POST['password']);

            $stmt = $pdo->prepare("SELECT * FROM User WHERE Username = ?");
            $stmt->bindParam(1, $username, PDO::PARAM_STR, 128);
            $stmt->execute([$username]);

            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch();
                $pw = $row['Password'];
            } else {
                include_once 'view/login.php';
                echo "Gebruikersnaam en/of wachtwoord onjuist.";
                die();
            }

            if (password_verify(str_replace("'", "", $password), $pw)) {
                $_SESSION['loggedin'] = true;
                return true;
            } else {
                include_once 'view/login.php';
                echo "Gebruikersnaam en/of wachtwoord onjuist.";
                die();
            }
        } else {
            include_once 'view/login.php';
            echo "U dient een gebruikersnaam en wachtwoord in te vullen.";
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
