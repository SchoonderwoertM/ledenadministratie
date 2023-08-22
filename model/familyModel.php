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
        $currentYear = date('Y');
        $query = ("SELECT Family.FamilyID, Family.Name, Address.Address, Address.City, COUNT(FamilyMember.FamilyID) AS NumberOfFamilyMembers, SUM(Contribution.Cost) TotalContribution FROM Family
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

    public function getFamily(){
        $familyID = $_POST['familyID'];
        $query = ("SELECT Family.FamilyID, Family.Name, Address.Address, Address.PostalCode, Address.City 
        FROM Family
        LEFT JOIN Address ON Family.AddressID = Address.AddressID
        WHERE Family.FamilyID = 1");
        $result = $this->pdo->query($query);
        return $result->fetch();
    }

    public function createFamily()
    {
        // $address = $_POST['Address'];
        // $postalCode = $_POST['PostalCode'];
        // $city = $_POST['City'];
        // $familyName = $_POST['FamilyName'];

        // $stmt1 = $this->pdo->prepare('INSERT INTO Address VALUES(?,?,?)');
        // $stmt1->bindParam($address, $postalCode, $city);
        
        // $stmt1->execute([$address, $postalCode, $city]);

        // $addressID = $stmt1->lastInsertId();

        // $stmt2 = $this->pdo->prepare('INSERT INTO Family VALUES(?,?)');
        // $stmt2->bindParam($familyName, $addressID);
        // $query = "INSERT INTO family VALUES(null, '$familyName', '$address'"
        // $result = $pdo->query($query);
    }

    public function deleteFamily()
    {
        $familyID = $_POST['FamilyID'];
        $query = ("DELETE * FROM Family 
        WHERE Family.FamilyID = $familyID");
    }

    public function updateFamily()
    {
        if(isset($_POST['FamilyID']) &&
        isset($_POST['Name']) &&
        isset($_POST['Address']) &&
        isset($_POST['PostalCode']) &&
        isset($_POST['City'])){

            $id = $_POST['FamilyID'];
            $name = $_POST['Name'];
            $address = $_POST['Address'];
            $postalCode = $_POST['PostalCode'];
            $city = $_POST['City'];
    
            $query = "UPDATE Family
            SET Name=$name WHERE FamilyID=$id";
            $result = $this->pdo->query($query);

            $query = "UPDATE Address 
            SET Address=$address, PostalCode=$postalCode, City=$city WHERE AddressID=1";
            $result = $this->pdo->query($query);
        }
    }



  
}
