<?php

class FamilyMemberModel
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
        if(!empty($_POST['familyID'])){
            $familyID = $_POST['familyID'];
            $query = ("SELECT FamilyMember.FamilyMemberID, FamilyMember.FamilyID, FamilyMember.Name, FamilyMember.DateOfBirth, Membership.Description, Contribution.Discount FROM FamilyMember
            LEFT JOIN Membership ON FamilyMember.MembershipID = Membership.MembershipID
            LEFT JOIN Contribution ON Membership.MembershipID = Contribution.MembershipID
            LEFT JOIN FinancialYear ON Contribution.FinancialYearID = FinancialYear.FinancialYearID
            WHERE FamilyMember.FamilyID = $familyID
            AND FinancialYear.Year = 2023");
            $result = $this->pdo->query($query);
            return $result->fetchAll();
            }
    }

    public function getFamilyMember()
    {
        $familyMemberID = $_POST['familyMemberID'];
        $query = ("SELECT FamilyMember.FamilyMemberID, FamilyMember.Name, FamilyMember.DateOfBirth
        FROM FamilyMember
        WHERE FamilyMember.FamilyMemberID = $familyMemberID");
        $result = $this->pdo->query($query);
        return $result->fetch();
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
            $name = $_POST['name'];
            $dateOfBirth = $_POST['dateOfBirth'];
            $familyID = $_POST['familyID'];
            $membershipId = $this->getMembership($dateOfBirth);

            $query = ("INSERT INTO FamilyMember (FamilyMemberID, Name, DateOfBirth, MembershipID, FamilyID) VALUES (null, '$name', '$dateOfBirth', $membershipId, $familyID);");
            $result = $this->pdo->query($query);
            $result->fetch();
        }
    }

    public function deleteFamilyMember()
    {
        $familyMemberID = $_POST['familyMemberID'];
        $query = ("DELETE FROM FamilyMember WHERE FamilyMember.FamilyMemberID = $familyMemberID");
        $result = $this->pdo->query($query);
        return $result->fetchAll();
    }

    public function updateFamilyMember()
    {
        if (
            isset($_POST['familyMemberID']) &&
            isset($_POST['name']) &&
            isset($_POST['dateOfBirth'])
        ) {
            $id = $_POST['familyMemberID'];
            $name = $_POST['name'];
            $dateOfBirth = $_POST['dateOfBirth'];
            $membershipID = $this->getMembership($dateOfBirth);

            $query = "UPDATE FamilyMember SET Name='$name', DateOfBirth='$dateOfBirth', MembershipID='$membershipID' WHERE FamilyMemberID=$id";
            $result = $this->pdo->query($query);
            $result->fetch();
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
