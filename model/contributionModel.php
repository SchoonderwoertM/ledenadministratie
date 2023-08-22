<?php

class ContributionModel
{
    private $pdo;

    public function __construct()
    {
        require 'include\databaseLogin.php';
    }

    public function getContributions(){
        if(!empty($_POST['financialYearID'])){
        $financialYearID = $_POST['financialYearID'];
        $query = ("SELECT Contribution.ContributionID, Contribution.Age, Contribution.Cost, Membership.Description 
        FROM Contribution
        LEFT JOIN Membership ON Contribution.MembershipID = Membership.MembershipID
        LEFT JOIN FinancialYear ON Contribution.FinancialYearID = FinancialYear.FinancialYearID
        WHERE FinancialYear.FinancialYearID = $financialYearID");
        $result = $this->pdo->query($query);
        return $result->fetchAll();
        }
    }

    public function getContribution(){
        $contributionID = $_POST['contributionID'];
        $query = ("SELECT Contribution.ContributionID, Contribution.Age, Contribution.Cost, Membership.Description 
        FROM Contribution
        LEFT JOIN Membership ON Contribution.MembershipID = Membership.MembershipID
        WHERE Contribution.ContributionID = $contributionID");
        $result = $this->pdo->query($query);
        return $result->fetch();
    }

    public function createContribution(){
        $year = $this->$_POST['year'];
        $cost = $this->$_POST['cost'];
        $query = "SELECT financialYear.year FROM financialYear WHERE financialYear.year = $year";
        $result = $this->pdo->query($query);
        if($result){
            $message = "Het boekjaar $year bestaat al";
        }
        else{
            $query = "INSERT INTO FinancialYear (FinancialYear.Year, Contribution.Cost
            INNER JOIN Contribution ON FinancialYear.ContributionID) VALUES ()";
            $message = "Het boekjaar is toegevoegd.";
        }
    }

    public function deleteContribution(){
        $contributionID = $_POST['ContributionID'];
        $query = ("DELETE * FROM Contribution 
        WHERE Contribution.ContributionID = $contributionID");
        $result = $this->pdo->query($query);
    }

    public function updateContribution(){
        // $query = "INSERT INTO ";
        // $result = $pdo->query($query);
    }

    public function getFinancialYears(){
        $query = ("SELECT * FROM FinancialYear");
        $result = $this->pdo->query($query);
        return $result->fetchAll();
    }
}
