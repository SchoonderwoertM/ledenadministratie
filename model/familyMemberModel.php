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
    }

    public function getFamilyMember()
    {
        $familyMemberID = $this->sanitizeString($_POST['familyMemberID']);

        $stmt = $this->pdo->prepare("SELECT FamilyMember.FamilyMemberID, FamilyMember.Name, FamilyMember.DateOfBirth
        FROM FamilyMember
        WHERE FamilyMember.FamilyMemberID = ?");
        $stmt->bindParam(1, $familyMemberID, PDO::PARAM_INT);
        $stmt->execute([$familyMemberID]);
        return $stmt->fetch();
    }

    public function createFamilyMember()
    {
        //!!! familyID dynamisch maken !!!
        $_POST['familyID'] = 1;
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

            include 'view\familyMember\familyMembers.php';
        }
    }

    public function deleteFamilyMember()
    {
        $familyMemberID = $this->sanitizeString($_POST['familyMemberID']);
        
        $stmt = $this->pdo->prepare("DELETE FROM FamilyMember WHERE FamilyMember.FamilyMemberID = ?");
        $stmt->bindParam(1, $familyMemberID, PDO::PARAM_INT);
        $stmt->execute([$familyMemberID]);
    }

    public function updateFamilyMember()
    {
        if (
            isset($_POST['familyMemberID']) &&
            isset($_POST['name']) &&
            isset($_POST['dateOfBirth'])
        ) {
            $familyMemberID = $this->sanitizeString($_POST['familyMemberID']);
            $name = $this->sanitizeString($_POST['name']);
            $dateOfBirth = $this->sanitizeString($_POST['dateOfBirth']);
            $membershipID = $this->getMembership($dateOfBirth);

            //!!! Wat te doen als er geen passen membership is !!!

            $stmt = $this->pdo->prepare("UPDATE FamilyMember SET Name = ?, DateOfBirth = ?, MembershipID = ? WHERE FamilyMemberID = ?");
            $stmt->bindParam(1, $name, PDO::PARAM_INT);
            $stmt->bindParam(1, $dateOfBirth, PDO::PARAM_STR, 10);
            $stmt->bindParam(1, $membershipID, PDO::PARAM_INT);
            $stmt->bindParam(1, $familyMemberID, PDO::PARAM_INT);
            $stmt->execute([$name, $dateOfBirth, $membershipID, $familyMemberID]);

            include 'view\familyMember\familyMembers.php';
        }
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
        return $membershipID;
    }
}
