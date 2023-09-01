<?php
include_once 'classes\family.class.php';

class FamilyModel extends BaseModel
{
    public function getFamilies()
    {
        //Haal het huidige jaar op.
        $currentYear = date('Y');

        //Haal de contributie van het huidge jaar op.
        $stmt = $this->pdo->prepare("SELECT Cost FROM FinancialYear WHERE Year = ? ");
        $stmt->bindParam(1, $currentYear, PDO::PARAM_INT);
        $stmt->execute([$currentYear]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $yearContribution = $row['Cost'];
        }

        //Haal details van de families op.
        $stmt = $this->pdo->prepare("SELECT Family.FamilyID, Family.Name, Address.Street, Address.Housenumber, Address.PostalCode, Address.City, 
        COUNT(FamilyMember.FamilyID) AS NumberOfFamilyMembers, SUM(Contribution.Discount) TotalDiscount FROM Family
        INNER JOIN Address ON Family.AddressID = Address.AddressID
        INNER JOIN FamilyMember ON Family.FamilyID = FamilyMember.FamilyID
        LEFT OUTER JOIN Membership ON FamilyMember.MembershipID = Membership.MembershipID
        LEFT OUTER JOIN Contribution ON Membership.MembershipID = Contribution.MembershipID
        LEFT OUTER JOIN FinancialYear ON Contribution.FinancialYearID = FinancialYear.FinancialYearID
        WHERE FinancialYear.Year = ? OR Membership.MembershipID IS NULL
        GROUP BY Family.FamilyID");
        $stmt->bindParam(1, $currentYear, PDO::PARAM_INT);
        $stmt->execute([$currentYear]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $families = [];
        foreach ($rows as $row) {
            $family = new Family($row['FamilyID'], $row['Name'], $row['Street'], $row['Housenumber'], $row['PostalCode'], $row['City'], $row['NumberOfFamilyMembers'], $yearContribution, $row['TotalDiscount']);
            $families[] = $family;
        }

        return $families;
    }


    public function getFamily()
    {
        //Haal de details van een familie op aan de hand van het FamilyID.
        $familyID = $this->sanitizeString($_POST['familyID']);

        $stmt = $this->pdo->prepare("SELECT Family.FamilyID, Family.Name, Address.Street, Address.Housenumber, Address.PostalCode, Address.City 
        FROM Family
        INNER JOIN Address ON Family.AddressID = Address.AddressID
        WHERE Family.FamilyID = ?");
        $stmt->bindParam(1, $familyID, PDO::PARAM_INT);
        $stmt->execute([$familyID]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return new Family($row['FamilyID'], $row['Name'], $row['Street'], $row['Housenumber'], $row['PostalCode'], $row['City'], null, null, null);
    }

    public function createFamily()
    {
        //Controleer of de invoervelden een waarde hebben.
        if (
            isset($_POST['firstName']) &&
            isset($_POST['lastName']) &&
            isset($_POST['dateOfBirth']) &&
            isset($_POST['street']) &&
            isset($_POST['housenumber']) &&
            isset($_POST['postalCode']) &&
            isset($_POST['city'])
        ) {
            //Ontdoe de ingevoerde waarden van ongeweste slashes en html.
            $firstName = $this->sanitizeString($_POST['firstName']);
            $lastName = $this->sanitizeString($_POST['lastName']);
            $dateOfBirth = $this->sanitizeString($_POST['dateOfBirth']);
            $street = $this->sanitizeString($_POST['street']);
            $housenumber = $this->sanitizeString($_POST['housenumber']);
            $postalCode = $this->sanitizeString($_POST['postalCode']);
            $postalCode = str_replace(' ', '', $postalCode);
            $city = $this->sanitizeString($_POST['city']);
            $membershipID = $this->getMembership($dateOfBirth);

            //Check of er een passend membership beschikbaar is.
            if (empty($membershipID)) {
                return "<p class='badMessage'>Er dient eerst een passend lidmaatschap te worden aangemaakt.<p>";
            } else {
                //Nagaan of er al een familie op het adres bekend is.
                if ($this->AddressAlreadyExists($housenumber, $postalCode)) {
                    return "<p class='badMessage'>Er is al een familie bekend op dit adres.<p>";
                } else {
                    //Is er geen familie bekend sla dan het ingevoegde adres op in de database.
                    $stmt = $this->pdo->prepare("INSERT INTO Address (AddressID, Street, Housenumber, PostalCode, City) VALUES (null, ?, ?, ?, ?)");
                    $stmt->bindParam(1, $street, PDO::PARAM_STR, 100);
                    $stmt->bindParam(2, $housenumber, PDO::PARAM_INT);
                    $stmt->bindParam(3, $postalCode, PDO::PARAM_STR, 7);
                    $stmt->bindParam(4, $city, PDO::PARAM_STR, 100);
                    $stmt->execute([$street, $housenumber, $postalCode, $city]);
                    $addressID = $this->pdo->lastInsertId();

                    //Sla de familie gegevens op in de database.
                    $stmt = $this->pdo->prepare("INSERT INTO Family (FamilyID, Name, AddressID) VALUES (null, ?, ?)");
                    $stmt->bindParam(1, $lastName, PDO::PARAM_STR, 100);
                    $stmt->bindParam(2, $addressID, PDO::PARAM_INT);
                    $stmt->execute([$lastName, $addressID]);
                    $familyID = $this->pdo->lastInsertId();

                    //Sla het familielid op in de database.
                    $stmt = $this->pdo->prepare("INSERT INTO FamilyMember (FamilyMemberID, Name, DateOfBirth, MembershipID, FamilyID) VALUES (null, ?, ?, ?, ?)");
                    $stmt->bindParam(1, $firstName, PDO::PARAM_STR, 50);
                    $stmt->bindParam(2, $dateOfBirth, PDO::PARAM_STR, 10);
                    $stmt->bindParam(3, $membershipID, PDO::PARAM_INT);
                    $stmt->bindParam(4, $familyID, PDO::PARAM_INT);
                    $stmt->execute([$firstName, $dateOfBirth, $membershipID, $familyID]);

                    return "<p class='goodMessage'>Familie aangemaakt.<p>";
                }
            }
        }
        return "<p class='badMessage'>Er is een fout opgetreden. Probeer het nog eens.</p>";
    }

    public function deleteFamily()
    {
        $familyID = $this->sanitizeString($_POST['familyID']);

        //Haal het AddressID van de familie op.
        $addressID = $this->GetAddressID($familyID);

        //Verwijder alle familieleden die bekend zijn onder het betreffende FamilyID.
        $stmt = $this->pdo->prepare("DELETE FROM FamilyMember WHERE FamilyID = ?");
        $stmt->bindParam(1, $familyID, PDO::PARAM_INT);
        $stmt->execute([$familyID]);

        //Verwijder de betreffende familie.
        $stmt = $this->pdo->prepare("DELETE FROM Family WHERE FamilyID = ?");
        $stmt->bindParam(1, $familyID, PDO::PARAM_INT);
        $stmt->execute([$familyID]);

        //Verwijder het woonadres.
        $stmt = $this->pdo->prepare("DELETE FROM Address WHERE AddressID = ?");
        $stmt->bindParam(1, $addressID, PDO::PARAM_INT);
        $stmt->execute([$addressID]);

        return "<p class='goodMessage'>Familie verwijderd.</p>";
    }

    public function updateFamily()
    {
        //Controleer of de invoervelden een waarde hebben.
        if (
            isset($_POST['name']) &&
            isset($_POST['street']) &&
            isset($_POST['housenumber']) &&
            isset($_POST['postalCode']) &&
            isset($_POST['city'])
        ) {
            //Ontdoe de ingevoerde waarden van ongeweste slashes en html.
            $familyID = $this->sanitizeString($_POST['familyID']);
            $name = $this->sanitizeString($_POST['name']);
            $street = $this->sanitizeString($_POST['street']);
            $housenumber = $this->sanitizeString($_POST['housenumber']);
            $postalCode = $this->sanitizeString($_POST['postalCode']);
            $postalCode = str_replace(' ', '', $postalCode); //Verwijder spaties
            $city = $this->sanitizeString($_POST['city']);

            //Nagaan of er al een familie op het adres bekend is.
            if ($this->AddressAlreadyExists($housenumber, $postalCode)) {
                return "<p class='badMessage'>Er is al een familie bekend op dit adres.<p>";
            } else {
                //Sla de ingevoerde waarden betreft de familie op in de database.
                $stmt = $this->pdo->prepare("UPDATE Family SET Name = ? WHERE FamilyID = ?");
                $stmt->bindParam(1, $name, PDO::PARAM_STR, 100);
                $stmt->bindParam(2, $familyID, PDO::PARAM_INT);
                $stmt->execute([$name, $familyID]);

                //Haal het AddressID op van de te update family.
                $addressID = $addressID = $this->GetAddressID($familyID);

                //Sla de ingevoerde waarden betreft het adres op in de database.
                $stmt = $this->pdo->prepare("UPDATE Address SET Street = ?, Housenumber = ?, PostalCode = ?, City = ? WHERE AddressID = ?");
                $stmt->bindParam(1, $street, PDO::PARAM_STR, 100);
                $stmt->bindParam(2, $housenumber, PDO::PARAM_INT);
                $stmt->bindParam(3, $postalCode, PDO::PARAM_STR, 7);
                $stmt->bindParam(4, $city, PDO::PARAM_STR, 100);
                $stmt->bindParam(5, $addressID, PDO::PARAM_INT);
                $stmt->execute([$street, $housenumber, $postalCode, $city, $addressID]);

                return "<p class='goodMessage'>Wijziging succesvol opgeslagen.</p>";
            }
        }
        return "<p class='badMessage'>Er is een fout opgetreden. Probeer het nog eens.</p>";
    }

    private function AddressAlreadyExists($housenumber, $postalCode)
    {
        //Nagaan of er al een familie op het adres bekend is.
        $stmt = $this->pdo->prepare("SELECT AddressID FROM Address WHERE Housenumber = ? AND PostalCode = ?");
        $stmt->bindParam(2, $housenumber, PDO::PARAM_INT);
        $stmt->bindParam(3, $postalCode, PDO::PARAM_STR, 7);
        $stmt->execute([$housenumber, $postalCode]);

        if ($stmt->rowCount() > 0) {
            return true;
        }
        return false;
    }

    private function GetAddressID($familyID)
    {
        //Haal het AddressID van de familie op.
        $stmt = $this->pdo->prepare("SELECT AddressID FROM Family WHERE FamilyID = ?");
        $stmt->bindParam(1, $familyID, PDO::PARAM_INT);
        $stmt->execute([$familyID]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['AddressID'];
    }
}
