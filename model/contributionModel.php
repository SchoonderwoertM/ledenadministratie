<?php

class ContributionModel
{
    private $pdo;

    public function __construct()
    {
        require 'include\databaseLogin.php';
    }

    public function getContributions()
    {
        if (!empty($_POST['financialYearID'])) {
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

    public function getContribution()
    {
        $contributionID = $_POST['contributionID'];
        $query = ("SELECT Contribution.ContributionID, Contribution.Age, Contribution.Cost, Membership.MembershipID, Membership.Description 
        FROM Contribution
        LEFT JOIN Membership ON Contribution.MembershipID = Membership.MembershipID
        WHERE Contribution.ContributionID = $contributionID");
        $result = $this->pdo->query($query);
        return $result->fetch();
    }

    public function createContribution()
    {
        $year = $this->$_POST['year'];
        $cost = $this->$_POST['cost'];
        $query = "SELECT financialYear.year FROM financialYear WHERE financialYear.year = $year";
        $result = $this->pdo->query($query);
        if ($result) {
            $message = "Het boekjaar $year bestaat al";
        } else {
            $query = "INSERT INTO FinancialYear (FinancialYear.Year, Contribution.Cost
            INNER JOIN Contribution ON FinancialYear.ContributionID) VALUES ()";
            $message = "Het boekjaar is toegevoegd.";
        }
    }

    public function deleteContribution()
    {
        $contributionID = $_POST['ContributionID'];
        $query = ("DELETE * FROM Contribution 
        WHERE Contribution.ContributionID = $contributionID");
        $result = $this->pdo->query($query);
    }

    public function updateContribution()
    {
        if (
            isset($_POST['contributionID']) &&
            isset($_POST['membershipID']) &&
            isset($_POST['description']) &&
            isset($_POST['age']) &&
            isset($_POST['cost'])
        ) {
            $descriptionID = $_POST['contributionID'];
            $membershipID = $_POST['membershipID'];
            $description = $_POST['description'];
            $age = $_POST['age'];
            $cost = $_POST['cost'];

            $query = "UPDATE Contribution SET Age='$age', Cost='$cost' WHERE ContributionID=$descriptionID";
            $result = $this->pdo->query($query);
            $result->fetch();

            $query = "UPDATE Membership SET Description='$description' WHERE MembershipID = $membershipID";
            $result = $this->pdo->query($query);
            $result->fetch();
        }
    }

    public function getFinancialYears()
    {
        $query = ("SELECT * FROM FinancialYear");
        $result = $this->pdo->query($query);
        return $result->fetchAll();
    }
}
