<?php

class FamilyModel
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
        $query = ("SELECT Family.FamilyID, Family.Name, Address.Street, COUNT(FamilyMember.FamilyID) AS NumberOfFamilyMembers FROM Family
        LEFT JOIN Address ON Family.AddressID = Address.AddressID
        LEFT JOIN FamilyMember ON Family.FamilyID = FamilyMember.FamilyID
        LEFT JOIN Membership ON FamilyMember.MembershipID = Membership.MembershipID
        LEFT JOIN Contribution ON Membership.MembershipID = Contribution.MembershipID
        LEFT JOIN FinancialYear ON Contribution.FinancialYearID = FinancialYear.FinancialYearID
        -- !!! maak jaar dynamisch !!!)
        WHERE FinancialYear.Year = 2023
        GROUP BY Family.FamilyID
        ORDER BY Family.Name");
        $result = $this->pdo->query($query);
        return $result->fetchAll();
    }

    public function getFamily(){
        
    }

    public function createFamily()
    {
        // $query = "INSERT INTO family VALUES(null, '$familyName', '$address'"
        // $result = $pdo->query($query);
    }

    public function deleteFamily()
    {
        // $query = "DELETE * FROM family WHERE familyID = '$familyID'"
        // $result = $pdo->query($query);
    }

    public function updateFamily()
    {
        // $query = "UPDATE family SET name='$name', address='$address' WHERE familyID='$familyID'"
        // $result = $pdo->query($query);
    }



  
}
