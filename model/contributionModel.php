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
        if (isset($_POST['financialYear'])) {
            $financialYear = $this->sanitizeString($_POST['financialYear']);
            $stmt = $this->pdo->prepare("SELECT Contribution.ContributionID, Contribution.Age, Contribution.Discount, Membership.Description 
            FROM Contribution
            LEFT JOIN Membership ON Contribution.MembershipID = Membership.MembershipID
            LEFT JOIN FinancialYear ON Contribution.FinancialYearID = FinancialYear.FinancialYearID
            WHERE FinancialYear.Year = ?");
            $stmt->bindParam(1, $financialYear, PDO::PARAM_INT);
            $stmt->execute([$financialYear]);

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

    public function createContribution()
    {
        if (
            isset($_POST['description']) &&
            isset($_POST['age']) &&
            isset($_POST['discount'])
        ) {
            $membership = $this->sanitizeString($_POST['description']);
            $age = $this->sanitizeString($_POST['age']);
            $discount = $this->sanitizeString($_POST['discount']);

            //!!! Dynamisch maken !!!
            $stmt = $this->pdo->prepare("SELECT FinancialYearID FROM FinancialYear
            WHERE FinancialYear.Year = 2023");
            $stmt->execute();
            $financialYearID = $stmt->fetch();
            $financialYearID = reset($financialYearID);

            $stmt = $this->pdo->prepare("INSERT INTO Membership (MembershipID, Description) 
            VALUES (null, ?)");
            $stmt->bindParam(1, $membership, PDO::PARAM_STR, 128);
            $stmt->execute([$membership]);
            $membershipID = $stmt->fetch();
            $membershipID = $this->pdo->lastInsertId();

            $stmt = $this->pdo->prepare("INSERT INTO Contribution (ContributionID, Age, Discount, MembershipID, FinancialYearID) 
            VALUES (null, ?, ?, ?, ?)");
            $stmt->bindParam(1, $age, PDO::PARAM_INT);
            $stmt->bindParam(2, $discount, PDO::PARAM_INT);
            $stmt->bindParam(3, $membershipID, PDO::PARAM_INT);
            $stmt->bindParam(4, $financialYearID, PDO::PARAM_INT);
            $stmt->execute([$age, $discount, $membershipID, $financialYearID]);

            return "Lidmaatschap succesvol aangemaakt.";
        }
        return "Er is iets fout gegaan. Probeer het nog eens.";
    }

    public function deleteContribution()
    {
        $contributionID = $this->sanitizeString($_POST['contributionID']);
        $stmt = $this->pdo->prepare("DELETE FROM Contribution WHERE Contribution.ContributionID = ?");
        $stmt->bindParam(1, $contributionID, PDO::PARAM_INT);
        $stmt->execute([$contributionID]);

        return "Lidmaatschap succesvol verwijderd.";
    }

    public function updateContribution()
    {
        if (
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

            return "Wijziging succesvol opgeslagen.";
        }
        return "Er is een fout opgetreden. Probeer het nog eens.";
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

    public function createFinancialYear()
    {
        if (
            isset($_POST['year']) &&
            isset($_POST['cost'])
        ) {
            $year = $this->sanitizeString($_POST['year']);
            $cost = $this->sanitizeString($_POST['cost']);

            //Check of boekjaar al bestaat.
            $stmt = $this->pdo->prepare("SELECT FinancialYearID FROM FinancialYear WHERE Year = ?");
            $stmt->bindParam(1, $year, PDO::PARAM_INT);
            $stmt->execute([$year]);
            $checkForFinancialYear = $stmt->fetch();

            if ($checkForFinancialYear) {
                return "Dit boekjaar bestaat al.";
            } else {
                $stmt = $this->pdo->prepare("INSERT INTO FinancialYear (FinancialYearID, Year, Cost) 
            VALUES (null, ?, ?)");
                $stmt->bindParam(1, $year, PDO::PARAM_INT);
                $stmt->bindParam(1, $cost, PDO::PARAM_INT);
                $stmt->execute([$year, $cost]);
                return "Boekjaar succesvol toegevoegd.";
            }
        }
        return "Er is een fout opgetreden. Probeer het nog eens.";
    }

    public function deleteFinancialYear()
    {
        $financialYearID = $this->sanitizeString($_POST['financialYearID']);
        $stmt = $this->pdo->prepare("DELETE FROM FinancialYear WHERE FinancialYear.FinancialYearID = ?");
        $stmt->bindParam(1, $financialYearID, PDO::PARAM_INT);
        $stmt->execute([$financialYearID]);

        return "Boekjaar succesvol verwijderd.";
    }

    public function updateFinancialYear()
    {
        if (
            isset($_POST['cost'])
        ) {
            $financialYearID = $this->sanitizeString($_POST['financialYearID']);
            $cost = $this->sanitizeString($_POST['cost']);

            $stmt = $this->pdo->prepare("UPDATE FinancialYear SET Cost = ? 
            WHERE FinancialYearID = ?");
            $stmt->bindParam(1, $cost, PDO::PARAM_INT);
            $stmt->bindParam(2, $financialYearID, PDO::PARAM_INT);
            $stmt->execute([$cost, $financialYearID]);
            return "Boekjaar succesvol aangepast.";
        }
        return "Er is een fout opgetreden. Probeer het nog eens.";
    }
}
