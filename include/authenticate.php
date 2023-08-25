<?php
require_once 'include\databaseLogin.php';

if (
    isset($_SERVER['PHP_AUTH_USER']) &&
    isset($_SERVER['PHP_AUTH_PW'])
) {
    $un_temp = sanitize($pdo, $_SERVER['PHP_AUTH_USER']);
    $pw_temp = sanitize($pdo, $_SERVER['PHP_AUTH_PW']);
    $query = "SELECT * FROM User WHERE Username = $un_temp";
    $result = $pdo->query($query);

    if (!$result->rowCount()) {
        die("Gebruikersnaam en/of wachtwoord onjuist.");
    }

    $row = $result->fetch();
    $un = $row['username'];
    $pw = $row['password'];
    $role = $row['role'];

    if (password_verify(str_replace("'", "", $pw_temp), $pw)) {
        session_start();
        $_SESSION['username'] = $un;
        $_SESSION['role'] = $role;
    } else {
        die("Gebruikersnaam en/of wachtwoord onjuist.");
    }
} else {
    header('WWW-Authenticate: Basic realm="Resticted Area"');
    header('HTTP/1.1 401 Unauthorized');
    die("Voer uw gebruikers naam en wachtwoord in.");
}

function sanitize($pdo, $str)
{
    $str = htmlentities($str);
    return $pdo->qoute($str);
}
