<?php

class FamilyMemberModel extends BaseModel
{
    private $pdo;

    public function __construct()
    {
        include 'include\databaseLogin.php';
        try {
            $this->pdo = new PDO($attr, $user, $pass, $opts);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
            die();
        }
    }

    public function getFamilyMembers()
    {
        //!!! Jaar dynamisch maken !!!
        if (!empty($_POST['familyID'])) {
            $familyID = $this->sanitizeString($_POST['familyID']);

            $stmt = $this->pdo->prepare("SELECT FamilyMember.FamilyMemberID, FamilyMember.FamilyID, FamilyMember.Name, FamilyMember.DateOfBirth, Membership.Description, 
            FinancialYear.Cost, Contribution.Discount FROM FamilyMember
            LEFT JOIN Membership ON FamilyMember.MembershipID = Membership.MembershipID
            LEFT JOIN Contribution ON Membership.MembershipID = Contribution.MembershipID
            LEFT JOIN FinancialYear ON Contribution.FinancialYearID = FinancialYear.FinancialYearID
            WHERE FamilyMember.FamilyID = ?
            AND FinancialYear.Year = 2023");
            $stmt->bindParam(1, $familyID, PDO::PARAM_INT);
            $stmt->execute([$familyID]);
            return $stmt->fetchAll();
        }
        return "<p class='badMessage'>Kan de familie niet vinden.</p>";
    }

    public function getFamilyMember()
    {
        $familyMemberID = $this->sanitizeString($_POST['familyMemberID']);

        $stmt = $this->pdo->prepare("SELECT FamilyMember.FamilyMemberID, FamilyMember.Name, FamilyMember.DateOfBirth, FamilyMember.FamilyID
        FROM FamilyMember
        WHERE FamilyMember.FamilyMemberID = ?");
        $stmt->bindParam(1, $familyMemberID, PDO::PARAM_INT);
        $stmt->execute([$familyMemberID]);
        return $stmt->fetch();
    }

    public function createFamilyMember()
    {
        if (
            isset($_POST['name']) &&
            isset($_POST['dateOfBirth']) &&
            isset($_POST['familyID'])
        ) {
            $name = $this->sanitizeString($_POST['name']);
            $dateOfBirth = $this->sanitizeString($_POST['dateOfBirth']);
            $familyID = $this->sanitizeString($_POST['familyID']);
            $membershipID = $this->getMembership($dateOfBirth);

            $stmt = $this->pdo->prepare("INSERT INTO FamilyMember (FamilyMemberID, Name, DateOfBirth, MembershipID, FamilyID) VALUES (null, ?, ?, ?, ?)");
            $stmt->bindParam(1, $name, PDO::PARAM_STR, 128);
            $stmt->bindParam(2, $dateOfBirth, PDO::PARAM_STR, 10);
            $stmt->bindParam(3, $membershipID, PDO::PARAM_INT);
            $stmt->bindParam(4, $familyID, PDO::PARAM_INT);
            $stmt->execute([$name, $dateOfBirth, $membershipID, $familyID]);

            return "<p class='goodMessage'>Familielid toegevoegd.</p>";
        }
        return "<p class='badMessage'>Er is een fout opgetreden. Probeer het nog eens.</p>";
    }

    public function deleteFamilyMember()
    {
        $familyMemberID = $this->sanitizeString($_POST['familyMemberID']);
        $familyID = $this->sanitizeString($_POST['familyID']);

        $stmt = $this->pdo->prepare("DELETE FROM FamilyMember WHERE FamilyMember.FamilyMemberID = ?");
        $stmt->bindParam(1, $familyMemberID, PDO::PARAM_INT);
        $stmt->execute([$familyMemberID]);

        //Verwijder de familie als het laatste familielid wordt verwijderd.
        $stmt = $this->pdo->prepare("SELECT FamilyMemberID FROM FamilyMember WHERE FamilyMember.FamilyID = ?");
        $stmt->bindParam(1, $familyID, PDO::PARAM_INT);
        $stmt->execute([$familyID]);
        if($stmt->rowCount() == 0)
            $stmt = $this->pdo->prepare("DELETE FROM Family WHERE Family.FamilyID = ?");
            $stmt->bindParam(1, $familyID, PDO::PARAM_INT);
            $stmt->execute([$familyID]);
        return "<p class='badMessage'>Familielid verwijderd.</p>";
    }

    public function updateFamilyMember()
    {
        if (
            isset($_POST['name']) &&
            isset($_POST['dateOfBirth'])
        ) {
            $familyMemberID = $this->sanitizeString($_POST['familyMemberID']);
            $name = $this->sanitizeString($_POST['name']);
            $dateOfBirth = $this->sanitizeString($_POST['dateOfBirth']);
            $membershipID = $this->getMembership($dateOfBirth);

            $stmt = $this->pdo->prepare("UPDATE FamilyMember SET Name = ?, DateOfBirth = ?, MembershipID = ? WHERE FamilyMemberID = ?");
            $stmt->bindParam(1, $name, PDO::PARAM_INT);
            $stmt->bindParam(1, $dateOfBirth, PDO::PARAM_STR, 10);
            $stmt->bindParam(1, $membershipID, PDO::PARAM_INT);
            $stmt->bindParam(1, $familyMemberID, PDO::PARAM_INT);
            $stmt->execute([$name, $dateOfBirth, $membershipID, $familyMemberID]);

            return "<p class='goodMessage'>Wijziging succesvol opgeslagen.</p>";
        }
        return "<p class='badMessage'>Er is een fout opgetreden. Probeer het nog eens.</p>";
    }

    //!!! Verplaatsen naar ContributionModel !!!
    //Staat nu dubbel in FamilyModel
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
        if (!$membership) {
            return "<p class='badMessage'>Er is geen lidmaatschap bekend voor de leeftijd van $age jaar.</p>";
        } else {
            return $membershipID;
        }
    }
}
