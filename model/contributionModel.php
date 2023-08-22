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
            $query = ("SELECT Contribution.ContributionID, Contribution.Age, Contribution.Discount, Membership.Description 
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
        $query = ("SELECT Contribution.ContributionID, Contribution.Age, Contribution.Discount, Membership.MembershipID, Membership.Description 
        FROM Contribution
        LEFT JOIN Membership ON Contribution.MembershipID = Membership.MembershipID
        WHERE Contribution.ContributionID = $contributionID");
        $result = $this->pdo->query($query);
        return $result->fetch();
    }

    public function createContribution()
    {
        $year = $this->$_POST['year'];
        $discount = $this->$_POST['discount'];
        $query = "SELECT financialYear.year FROM financialYear WHERE financialYear.year = $year";
        $result = $this->pdo->query($query);
        if ($result) {
            $message = "Het boekjaar $year bestaat al";
        } else {
            $query = "INSERT INTO FinancialYear (FinancialYear.Year, Contribution.Discount
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
            isset($_POST['discount'])
        ) {
            $descriptionID = $_POST['contributionID'];
            $membershipID = $_POST['membershipID'];
            $description = $_POST['description'];
            $age = $_POST['age'];
            $discount = $_POST['discount'];

            $query = "UPDATE Contribution SET Age='$age', Discount='$discount' WHERE ContributionID=$descriptionID";
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
