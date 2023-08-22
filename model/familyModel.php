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
        $query = ("SELECT Family.FamilyID, Family.Name, Address.Address, Address.City, COUNT(FamilyMember.FamilyID) AS NumberOfFamilyMembers, SUM(Contribution.Discount) TotalContribution FROM Family
        LEFT JOIN Address ON Family.AddressID = Address.AddressID
        LEFT JOIN FamilyMember ON Family.FamilyID = FamilyMember.FamilyID
        LEFT JOIN Membership ON FamilyMember.MembershipID = Membership.MembershipID
        LEFT JOIN Contribution ON Membership.MembershipID = Contribution.MembershipID
        LEFT JOIN FinancialYear ON Contribution.FinancialYearID = FinancialYear.FinancialYearID
        WHERE FinancialYear.Year = $currentYear
        GROUP BY Family.FamilyID");
        $result = $this->pdo->query($query);
        return $result->fetchAll();
    }

    public function getFamily()
    {
        $familyID = $this->sanitizeString($_POST['familyID']);
        $query = ("SELECT Family.FamilyID, Family.Name, Address.Address, Address.PostalCode, Address.City 
        FROM Family
        LEFT JOIN Address ON Family.AddressID = Address.AddressID
        WHERE Family.FamilyID = $familyID");
        $result = $this->pdo->query($query);
        return $result->fetch();
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
            $membershipId = $this->getMembership($dateOfBirth);

            //!!!checken of adres al bestaat!!!
            $query = ("INSERT INTO Address (AddressID, Address, PostalCode, City) VALUES (null, '$address', '$postalCode', '$city');");
            $result = $this->pdo->query($query);
            $addressID = $this->pdo->lastInsertId();
            $addressID = (int)$addressID;
            $result->fetch();

            $query = ("INSERT INTO Family (FamilyID, Name, AddressID) VALUES (null, '$lastName', $addressID);");
            $result = $this->pdo->query($query);
            $familyID = $this->pdo->lastInsertId();
            $result->fetch();

            $query = ("INSERT INTO FamilyMember (FamilyMemberID, Name, DateOfBirth, MembershipID, FamilyID) VALUES (null, '$firstName', '$dateOfBirth', $membershipId, $familyID);");
            $result = $this->pdo->query($query);
            $result->fetch();
            include 'view\family\families.php';
        }
    }

    public function deleteFamily()
    {
        $familyID = $this->sanitizeString($_POST['familyID']);
        $stmt = $this->pdo->prepare("DELETE FROM Family WHERE Family.FamilyID = ?");
        $stmt->bindParam(1, $familyID, PDO::PARAM_INT);
        $stmt->execute([$familyID]);
        include 'view\family\families.php';
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

            $query = "UPDATE Family SET Name='$name' WHERE FamilyID=$familyID";
            $result = $this->pdo->query($query);
            $result->fetch();

            $query = "UPDATE Address SET Address='$address', PostalCode='$postalCode', City='$city' WHERE AddressID=1";
            $result = $this->pdo->query($query);
            $result->fetch();
            include 'view\family\families.php';
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
