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
            $query = ("SELECT FamilyMember.FamilyMemberID, FamilyMember.Name, FamilyMember.DateOfBirth, Membership.Description, Contribution.Cost FROM FamilyMember
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
    }

    public function deleteFamilyMember()
    {
        $familyMemberID = $_POST['FamilyMemberID'];
        $query = ("DELETE * FROM FamilyMember 
        WHERE FamilyMember.FamilyMemberID = $familyMemberID");
        $result = $this->pdo->query($query);
        return $result->fetchAll();
    }

    public function updateFamilyMember()
    {
    }
}
