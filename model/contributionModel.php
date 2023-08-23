<?php

class ContributionModel extends BaseModel
{
    private $pdo;

    public function __construct()
    {
        require 'include\databaseLogin.php';
    }

    public function getContributions()
    {
        if (!empty($_POST['financialYearID'])) {
            $financialYearID = $this->sanitizeString($_POST['financialYearID']);
            $stmt = $this->pdo->prepare("SELECT Contribution.ContributionID, Contribution.Age, Contribution.Discount, Membership.Description 
            FROM Contribution
            LEFT JOIN Membership ON Contribution.MembershipID = Membership.MembershipID
            LEFT JOIN FinancialYear ON Contribution.FinancialYearID = FinancialYear.FinancialYearID
            WHERE FinancialYear.FinancialYearID = ?");
            $stmt->bindParam(1, $financialYearID, PDO::PARAM_INT);
            $stmt->execute([$financialYearID]);
            return $stmt->fetchAll();
        }
    }

    public function getContribution()
    {
        $contributionID = $this->sanitizeString($_POST['contributionID']);
        $stmt = $this->pdo->prepare("SELECT Contribution.ContributionID, Contribution.Age, Contribution.Discount, Membership.MembershipID, Membership.Description 
        FROM Contribution
        LEFT JOIN Membership ON Contribution.MembershipID = Membership.MembershipID
        WHERE Contribution.ContributionID = ?");
        $stmt->bindParam(1, $contributionID, PDO::PARAM_INT);
        $stmt->execute([$contributionID]);
        return $stmt->fetch();
    }

    // !!! Query nog ombouwen !!!
    public function createContribution()
    {
        // $year = $this->sanitizeString($_POST['year']);
        // $discount = $this->sanitizeString($_POST['discount']);

        // $stmt = $this->pdo->prepare("INSERT INTO Membership (Membership.Description, Contribution.Age, Contribution.Discount
        // INNER JOIN Contribution ON Membership.MembershipID, Contribution.Membership
        // ) VALUES (?, ?, ?)");
        // $stmt->bindParam(1, $year, PDO::PARAM_INT);
        // $stmt->execute([$contributionID]);

        include 'view\contribution\contributions.php';
    }

    public function deleteContribution()
    {
        $contributionID = $this->sanitizeString($_POST['contributionID']);
        $stmt = $this->pdo->prepare("DELETE FROM Contribution WHERE Contribution.ContributionID = ?");
        $stmt->bindParam(1, $contributionID, PDO::PARAM_INT);
        $stmt->execute([$contributionID]);
        include 'view\contribution\contributions.php';
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
            $contributionID = $this->sanitizeString($_POST['contributionID']);
            $age = $this->sanitizeString($_POST['age']);
            $discount = $this->sanitizeString($_POST['discount']);
            $membershipID = $this->sanitizeString($_POST['membershipID']);
            $description = $this->sanitizeString($_POST['description']);

            $stmt = $this->pdo->prepare("UPDATE Contribution SET Age = ?, Discount = ? WHERE ContributionID = ?");
            $stmt->bindParam(1, $age, PDO::PARAM_INT);
            $stmt->bindParam(2, $discount, PDO::PARAM_INT);
            $stmt->bindParam(3, $contributionID, PDO::PARAM_INT);
            $stmt->execute([$age, $discount, $contributionID]);

            $stmt = $this->pdo->prepare("UPDATE Membership SET Description = ? WHERE MembershipID = ?");
            $stmt->bindParam(1, $description, PDO::PARAM_STR, 128);
            $stmt->bindParam(2, $membershipID, PDO::PARAM_INT);
            $stmt->execute([$description, $membershipID]);
            include 'view\contribution\contributions.php';
        }
    }

    public function getFinancialYears()
    {
        $query = ("SELECT * FROM FinancialYear");
        $result = $this->pdo->query($query);
        return $result->fetchAll();
    }

    public function getFinancialYear()
    {
        $financialYearID = $this->sanitizeString($_POST['financialYearID']);
        $stmt = $this->pdo->prepare("SELECT * FROM FinancialYear
        WHERE FinancialYear.FinancialYearID = ?");
        $stmt->bindParam(1, $financialYearID, PDO::PARAM_INT);
        $stmt->execute([$financialYearID]);
        return $stmt->fetch();
    }

    public function createFiancialYear(){

    }

    public function deleteFiancialYear(){
        
    }

    public function updateFiancialYear(){
        
    }
}
