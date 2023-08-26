<?php

class FamilyModel extends BaseModel
{
    public $pdo;

    public function __construct()
    {
        //Maak connectie met de database.
        include 'include\databaseLogin.php';
        try {
            $this->pdo = new PDO($attr, $user, $pass, $opts);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
            die();
        }
    }

    public function getFamilies()
    {
        //!!! Contributie per gezin berekenen !!!
        //Haal het huidige jaar op.
        $currentYear = date('Y');

        $stmt = $this->pdo->prepare("SELECT Family.FamilyID, Family.Name, Address.Address, Address.City, COUNT(FamilyMember.FamilyID) AS NumberOfFamilyMembers, SUM(Contribution.Discount) TotalContribution FROM Family
        LEFT JOIN Address ON Family.AddressID = Address.AddressID
        LEFT JOIN FamilyMember ON Family.FamilyID = FamilyMember.FamilyID
        LEFT JOIN Membership ON FamilyMember.MembershipID = Membership.MembershipID
        LEFT JOIN Contribution ON Membership.MembershipID = Contribution.MembershipID
        LEFT JOIN FinancialYear ON Contribution.FinancialYearID = FinancialYear.FinancialYearID
        WHERE FinancialYear.Year = ?
        GROUP BY Family.FamilyID");
        $stmt->bindParam(1, $currentYear, PDO::PARAM_INT);
        $stmt->execute([$currentYear]);
        return $stmt->fetchAll();
    }

    public function getFamily()
    {
        //Haal de details van een familie op aan de hand van het FamilyID.
        $familyID = $this->sanitizeString($_POST['familyID']);
        $stmt = $this->pdo->prepare("SELECT Family.FamilyID, Family.Name, Address.Address, Address.PostalCode, Address.City 
        FROM Family
        LEFT JOIN Address ON Family.AddressID = Address.AddressID
        WHERE Family.FamilyID = ?");
        $stmt->bindParam(1, $familyID, PDO::PARAM_INT);
        $stmt->execute([$familyID]);
        return $stmt->fetch();
    }

    public function createFamily()
    {
        //Controleer of de invoervelden een waarde hebben.
        if (
            isset($_POST['firstName']) &&
            isset($_POST['lastName']) &&
            isset($_POST['dateOfBirth']) &&
            isset($_POST['address']) &&
            isset($_POST['postalCode']) &&
            isset($_POST['city'])
        ) {
            //Ontdoe de ingevoerde waarden van ongeweste slashes en html.
            $firstName = $this->sanitizeString($_POST['firstName']);
            $lastName = $this->sanitizeString($_POST['lastName']);
            $dateOfBirth = $this->sanitizeString($_POST['dateOfBirth']);
            $address = $this->sanitizeString($_POST['address']);
            $postalCode = $this->sanitizeString($_POST['postalCode']);
            $city = $this->sanitizeString($_POST['city']);
            $membershipID = $this->getMembership($dateOfBirth);

            //Ga na of er al een familie op het adres bekend is.
            $stmt = $this->pdo->prepare("SELECT AddressID FROM Address WHERE Address = ? AND PostalCode = ? ");
            $stmt->bindParam(1, $address, PDO::PARAM_STR, 128);
            $stmt->bindParam(2, $postalCode, PDO::PARAM_STR, 10);
            $stmt->execute([$address, $postalCode]);
            //Als er al een familie bekend is, toon een foutmelding.
            if ($stmt->rowCount() > 0) {
                return "<p class='badMessage'>Er is al een familie bekend op dit adres.<p>";
            } else {
                //Is er geen familie bekend sla dan het ingevoegde adres op in de database.
                $stmt = $this->pdo->prepare("INSERT INTO Address (AddressID, Address, PostalCode, City) VALUES (null, ?, ?, ?)");
                $stmt->bindParam(1, $address, PDO::PARAM_STR, 128);
                $stmt->bindParam(2, $postalCode, PDO::PARAM_STR, 10);
                $stmt->bindParam(3, $city, PDO::PARAM_STR, 128);
                $stmt->execute([$address, $postalCode, $city]);
                $addressID = $this->pdo->lastInsertId();

                //Sla de familie gegevens op in de database.
                $stmt = $this->pdo->prepare("INSERT INTO Family (FamilyID, Name, AddressID) VALUES (null, ?, ?)");
                $stmt->bindParam(1, $lastName, PDO::PARAM_STR, 128);
                $stmt->bindParam(2, $addressID, PDO::PARAM_INT);
                $stmt->execute([$lastName, $addressID]);
                $familyID = $this->pdo->lastInsertId();

                //Sla het familielid op in de database.
                $stmt = $this->pdo->prepare("INSERT INTO FamilyMember (FamilyMemberID, Name, DateOfBirth, MembershipID, FamilyID) VALUES (null, ?, ?, ?, ?)");
                $stmt->bindParam(1, $firstName, PDO::PARAM_STR, 128);
                $stmt->bindParam(2, $dateOfBirth, PDO::PARAM_STR, 10);
                $stmt->bindParam(3, $membershipID, PDO::PARAM_INT);
                $stmt->bindParam(4, $familyID, PDO::PARAM_INT);
                $stmt->execute([$firstName, $dateOfBirth, $membershipID, $familyID]);

                return "<p class='goodMessage'>Familie aangemaakt.<p>";
            }
        }
        return "<p class='badMessage'>Er is een fout opgetreden. Probeer het nog eens.</p>";
    }

    public function deleteFamily()
    {
        $familyID = $this->sanitizeString($_POST['familyID']);

        //Verwijder alle familieleden die bekend zijn onder het betreffende FamilyID.
        $stmt = $this->pdo->prepare("DELETE FROM FamilyMember WHERE FamilyID = ?");
        $stmt->bindParam(1, $familyID, PDO::PARAM_INT);
        $stmt->execute([$familyID]);

        //Verwijder de betreffende familie.
        $stmt = $this->pdo->prepare("DELETE FROM Family WHERE FamilyID = ?");
        $stmt->bindParam(1, $familyID, PDO::PARAM_INT);
        $stmt->execute([$familyID]);

        return "<p class='badMessage'>Familie en bijbehorende familieleden verwijderd.</p>";
    }

    public function updateFamily()
    {
        if (
            isset($_POST['name']) &&
            isset($_POST['address']) &&
            isset($_POST['postalCode']) &&
            isset($_POST['city'])
        ) {
            $familyID = $this->sanitizeString($_POST['familyID']);
            $name = $this->sanitizeString($_POST['name']);
            $address = $this->sanitizeString($_POST['address']);
            $postalCode = $this->sanitizeString($_POST['postalCode']);
            $city = $this->sanitizeString($_POST['city']);

            //Nagaan of er al een familie op het adres bekend is.
            $stmt = $this->pdo->prepare("SELECT AddressID FROM Address WHERE Address = ? AND PostalCode = ? ");
            $stmt->bindParam(1, $address, PDO::PARAM_STR, 128);
            $stmt->bindParam(2, $postalCode, PDO::PARAM_STR, 10);
            $stmt->execute([$address, $postalCode]);
            if ($stmt->rowCount() > 0) {
                return "<p class='badMessage'>Er is al een familie bekend op dit adres.</p>";
            } else {
                //Sla de ingevoerde waarden betreft de familie op in de database.
                $stmt = $this->pdo->prepare("UPDATE Family SET Name = ? WHERE FamilyID = ?");
                $stmt->bindParam(1, $name, PDO::PARAM_STR, 128);
                $stmt->bindParam(2, $familyID, PDO::PARAM_INT);
                $stmt->execute([$name, $familyID]);

                //Haal het AddressID op van de te update family.
                $stmt = $this->pdo->prepare("SELECT AddressID FROM Family
            WHERE FamilyID = ?");
                $stmt->bindParam(1, $familyID, PDO::PARAM_INT);
                $stmt->execute([$familyID]);
                $addressID = $stmt->fetch();
                $addressID = reset($addressID);

                //Sla de ingevoerde waarden betreft het adres op in de database.
                $stmt = $this->pdo->prepare("UPDATE Address SET Address = ?, PostalCode = ?, City = ? WHERE AddressID = ?");
                $stmt->bindParam(1, $address, PDO::PARAM_STR, 128);
                $stmt->bindParam(2, $postalCode, PDO::PARAM_STR, 6);
                $stmt->bindParam(3, $city, PDO::PARAM_STR, 128);
                $stmt->bindParam(4, $addressID, PDO::PARAM_INT);
                $stmt->execute([$address, $postalCode, $city, $addressID]);

                return "<p class='goodMessage'>Wijziging succesvol opgeslagen.</p>";
            }
        }
        return "<p class='badMessage'>Er is een fout opgetreden. Probeer het nog eens.</p>";
    }

    //!!! Verplaatsen naar ContributionModel !!!
    //Staat nu dubbel in FammilyMemberModel
    public function getMembership($date)
    {
        //Bereken de leeftijd van het familielid door het verschil op te halen tussen de datum van vandaag en de geboortedatum
        $dateOfBirth = new DateTime($date);
        $currectDate = new DateTime(date('y.m.d'));
        $age = $currectDate->diff($dateOfBirth);
        $age = $age->y;

        //Haal de contributies op per leeftijd
        $query = ("SELECT Contribution.MembershipID, Contribution.Age FROM Contribution
        LEFT JOIN FinancialYear ON Contribution.FinancialYearID = FinancialYear.FinancialYearID");
        $result = $this->pdo->query($query);
        $membershipsByAge = $result->fetchAll();
        $membershipID = null;

        //Check welke soort lid bij de leeftijd hoort.
        foreach ($membershipsByAge as $membership) {
            if ($age < $membership['Age']) {
                $membershipID = $membership['MembershipID'];
                break;
            }
        }
        if (!$membership) {
            return "<p class='badMessage'>Er is geen lidmaatschap bekend voor de leeftijd van $age jaar.</p>";
        } else {
            return $membershipID;
        }
    }
}
