<?php

class BaseModel
{
    public $pdo;

    public function __construct()
    {
        //Zet connectie met de database op.
        require 'include\databaseLogin.php';
        try {
            $this->pdo = new PDO($attr, $user, $pass, $opts);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
            die();
        }
    }

    //Check of de gebruiker rechten heeft om de pagina te bekijken
    public function CheckUserRole($roleID)
    {
        if (isset($_SESSION['roleID']) && $_SESSION['roleID'] == $roleID) {
            echo "<p class='badMessage'>U heeft onvoldoende rechten voor deze pagina.</p>";
            die();
        }
    }

    //Ontdoe een string van ongewenste slashes en html
    public function SanitizeString($str)
    {
        $str = stripslashes($str);
        $str = strip_tags($str);
        $str = htmlentities($str);
        return $str;
    }

    //Log gebruiker uit door alle sessie variabelen te legen en beëindig de sessie.
    public function Logout()
    {
        //Gooi sessie variabelen leeg
        $_SESSION = array();
        //Verwijder alle cookies die zijn gekoppeld aan de sessie door de vervaltijd in het verleden te zetten.
        setcookie(session_name(), '', time() - 2592000, '/');
        //Beëindig de huidige sessie.
        session_destroy();
        header('Location:index.php');
    }

    //Bepaal het membership aan de hand van de leeftijd.
    public function GetMembershipByDateOfBirth($date)
    {
        //Bereken de leeftijd van het familielid door het verschil te berekenen tussen de datum van vandaag en de geboortedatum
        $dateOfBirth = new DateTime($date);
        $currectDate = new DateTime(date('y.m.d'));
        $age = $currectDate->diff($dateOfBirth);
        $age = $age->y;

        //Haal huidig jaar op
        $currentYear = date('Y');
        $membershipID = null;

        //Haal de contributies op per leeftijd van het huidige jaar
        $stmt = $this->pdo->prepare("SELECT Contribution.MembershipID, Contribution.Age FROM Contribution
        INNER JOIN FinancialYear ON Contribution.FinancialYearID = FinancialYear.FinancialYearID
        WHERE FinancialYear.Year = ?");
        $stmt->bindParam(1, $currentYear, PDO::PARAM_INT);
        $stmt->execute([$currentYear]);
        $membershipsByAge = $stmt->fetchAll(PDO::FETCH_ASSOC);

        //Check welke soort lid bij de leeftijd hoort.
        foreach ($membershipsByAge as $membership) {
            if ($age < $membership['Age']) {
                $membershipID = $membership['MembershipID'];
                break;
            }
        }
        //Als er geen passend lidmaatschap kan worden gevonden return dan null
        if (empty($membership)) {
            return null;
        } else {
            return $membershipID;
        }
    }

    //Haal de contributies van het huidige jaar op
    public function GetContributionCurrentYear()
    {
        //Haal het huidige jaar op
        $currentYear = date('Y');

        $stmt = $this->pdo->prepare("SELECT Contribution FROM FinancialYear WHERE FinancialYear.Year = ?");
        $stmt->bindParam(1, $currentYear, PDO::PARAM_INT);
        $stmt->execute([$currentYear]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['Contribution'];
    }
}
