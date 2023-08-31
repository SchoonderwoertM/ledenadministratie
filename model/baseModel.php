<?php

class BaseModel
{
    public $pdo;

    public function __construct()
    {
        //Maak connectie met de database.
        require 'include\databaseLogin.php';
        try {
            $this->pdo = new PDO($attr, $user, $pass, $opts);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
            die();
        }
    }

    //Ontdoet een string van ongewenste slashes en html
    public function sanitizeString($str)
    {
        $str = stripslashes($str);
        $str = strip_tags($str);
        $str = htmlentities($str);
        return $str;
    }
    
    //Logt gebruiker uit, leegt alle sessie variabelen en beëindigd de sessie.
    public function logout(){
        $_SESSION = array();
        setcookie(session_name(), '', time() - 2592000, '/');
        session_destroy();
        header('Location:index.php');
    }

    //Bepaal het membership aan de hand van de leeftijd.
    public function getMembership($date)
    {
        include 'include\databaseLogin.php';
        try {
            $pdo = new PDO($attr, $user, $pass, $opts);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
            die();
        }
        //Bereken de leeftijd van het familielid door het verschil op te halen tussen de datum van vandaag en de geboortedatum
        $dateOfBirth = new DateTime($date);
        $currectDate = new DateTime(date('y.m.d'));
        $age = $currectDate->diff($dateOfBirth);
        $age = $age->y;

        //Haal de contributies op per leeftijd
        $query = ("SELECT Contribution.MembershipID, Contribution.Age FROM Contribution
        INNER JOIN FinancialYear ON Contribution.FinancialYearID = FinancialYear.FinancialYearID");
        $result = $pdo->query($query);
        $membershipsByAge = $result->fetchAll();
        $membershipID = null;

        //Check welke soort lid bij de leeftijd hoort.
        foreach ($membershipsByAge as $membership) {
            if ($age < $membership['Age']) {
                $membershipID = $membership['MembershipID'];
                break;
            }
        }
        if (empty($membership)) {
            return null;
        } else {
            return $membershipID;
        }
    }
}