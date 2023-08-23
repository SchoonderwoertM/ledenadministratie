<?php

class FamilyModel extends BaseModel
{
    private $pdo;

    public function __construct()
    {
        include 'include\databaseLogin.php';

        try {
            $this->pdo = new PDO($attr, $user, $pass, $opts);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public function getFamilies()
    {
        //!!! Contributie per gezin berekenen !!!
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
        if (
            isset($_POST['firstName']) &&
            isset($_POST['lastName']) &&
            isset($_POST['dateOfBirth']) &&
            isset($_POST['address']) &&
            isset($_POST['postalCode']) &&
            isset($_POST['city'])
        ) {
            $firstName = $this->sanitizeString($_POST['firstName']);
            $lastName = $this->sanitizeString($_POST['lastName']);
            $dateOfBirth = $this->sanitizeString($_POST['dateOfBirth']);
            $address = $this->sanitizeString($_POST['address']);
            $postalCode = $this->sanitizeString($_POST['postalCode']);
            $city = $this->sanitizeString($_POST['city']);
            $membershipID = $this->getMembership($dateOfBirth);

            //!!!checken of adres al bestaat!!!
            $stmt = $this->pdo->prepare("INSERT INTO Address (AddressID, Address, PostalCode, City) VALUES (null, ?, ?, ?)");
            $stmt->bindParam(1, $address, PDO::PARAM_STR, 128);
            $stmt->bindParam(2, $postalCode, PDO::PARAM_STR, 10);
            $stmt->bindParam(3, $city, PDO::PARAM_STR, 128);
            $stmt->execute([$address, $postalCode, $city]);
            $addressID = $this->pdo->lastInsertId();

            $stmt = $this->pdo->prepare("INSERT INTO Family (FamilyID, Name, AddressID) VALUES (null, ?, ?)");
            $stmt->bindParam(1, $lastName, PDO::PARAM_STR, 128);
            $stmt->bindParam(2, $addressID, PDO::PARAM_INT);
            $stmt->execute([$lastName, $addressID]);
            $familyID = $this->pdo->lastInsertId();

            $stmt = $this->pdo->prepare("INSERT INTO FamilyMember (FamilyMemberID, Name, DateOfBirth, MembershipID, FamilyID) VALUES (null, ?, ?, ?, ?)");
            $stmt->bindParam(1, $firstName, PDO::PARAM_STR, 128);
            $stmt->bindParam(2, $dateOfBirth, PDO::PARAM_STR, 10);
            $stmt->bindParam(3, $membershipID, PDO::PARAM_INT);
            $stmt->bindParam(4, $familyID, PDO::PARAM_INT);
            $stmt->execute([$firstName, $dateOfBirth, $membershipID, $familyID]);

            return "Familie aangemaakt";
        }
    }

    public function deleteFamily()
    {
        $familyID = $this->sanitizeString($_POST['familyID']);

        $stmt = $this->pdo->prepare("DELETE FROM Family WHERE Family.FamilyID = ?");
        $stmt->bindParam(1, $familyID, PDO::PARAM_INT);
        $stmt->execute([$familyID]);

        return "Familie verwijderd.";
    }

    public function updateFamily()
    {
        if (
            isset($_POST['familyID']) &&
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

            $stmt = $this->pdo->prepare("UPDATE Family SET Name = ? WHERE FamilyID = ?");
            $stmt->bindParam(1, $name, PDO::PARAM_STR, 128);
            $stmt->bindParam(2, $familyID, PDO::PARAM_INT);
            $stmt->execute([$name, $familyID]);

            $stmt = $this->pdo->prepare("SELECT AddressID FROM Family
            WHERE FamilyID = ?");
            $stmt->bindParam(1, $familyID, PDO::PARAM_INT);
            $stmt->execute([$familyID]);
            $addressID = $stmt->fetch();
            $addressID = reset($addressID);

            $stmt = $this->pdo->prepare("UPDATE Address SET Address = ?, PostalCode = ?, City = ? WHERE AddressID = ?");
            $stmt->bindParam(1, $address, PDO::PARAM_STR, 128);
            $stmt->bindParam(2, $postalCode, PDO::PARAM_STR, 6);
            $stmt->bindParam(3, $city, PDO::PARAM_STR, 128);
            $stmt->bindParam(4, $addressID, PDO::PARAM_INT);
            $stmt->execute([$address, $postalCode, $city, $addressID]);

            return "familie succesvol aangepast.";
        }
    }

    //!!! Verplaatsen naar ContributionModel !!!
    //Staat nu dubbel in FammilyMemberModel
    public function getMembership($date)
    {
        $dateOfBirth = new DateTime($date);
        $currectDate = new DateTime(date('y.m.d'));
        $age = $currectDate->diff($dateOfBirth);
        $age = $age->y;

        $query = ("SELECT Contribution.MembershipID, Contribution.Age FROM Contribution
        LEFT JOIN FinancialYear ON Contribution.FinancialYearID = FinancialYear.FinancialYearID");
        $result = $this->pdo->query($query);
        $membershipsByAge = $result->fetchAll();
        $membershipID = null;

        foreach ($membershipsByAge as $membership) {
            if ($age < $membership['Age']) {
                $membershipID = $membership['MembershipID'];
                break;
            }
        }
        return $membershipID;
    }
}
