<?php

class ContributionModel extends BaseModel
{
    private $pdo;

    public function __construct()
    {
        require 'include\databaseLogin.php';
        try {
            $this->pdo = new PDO($attr, $user, $pass, $opts);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
            die();
        }
    }

    public function getContributions()
    {
        if (isset($_POST['financialYear'])) {
            $financialYear = $this->sanitizeString($_POST['financialYear']);
            $stmt = $this->pdo->prepare("SELECT Contribution.ContributionID, Contribution.Age, Contribution.Discount, Membership.MembershipID, Membership.Description 
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
            isset($_POST['discount']) &&
            isset($_POST['financialYear'])
        ) {
            $membership = $this->sanitizeString($_POST['description']);
            $age = $this->sanitizeString($_POST['age']);
            $discount = $this->sanitizeString($_POST['discount']);
            $financialYear = $this->sanitizeString($_POST['financialYear']);

            //Checken of boekjaar wel bestaat
            $stmt = $this->pdo->prepare("SELECT FinancialYearID FROM FinancialYear
            WHERE FinancialYear.Year = ?");
            $stmt->execute([$financialYear]);
            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $financialYearID = $row['FinancialYearID'];
                }
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

                return "<p class='goodMessage'>Lidmaatschap succesvol aangemaakt.</p>";
            } else {
                return "<p class='badMessage'>U dient eerst het boekjaar aan te maken.</p>";
            }
        }
        return "<p class='badMessage'>Er is iets fout gegaan. Probeer het nog eens.</p>";
    }

    public function deleteContribution()
    {
        $contributionID = $this->sanitizeString($_POST['contributionID']);
        $membershipID = $this->sanitizeString($_POST['membershipID']);

        $stmt = $this->pdo->prepare("DELETE FROM Contribution WHERE ContributionID = ?");
        $stmt->bindParam(1, $contributionID, PDO::PARAM_INT);
        $stmt->execute([$contributionID]);

        $stmt = $this->pdo->prepare("DELETE FROM Membership WHERE MembershipID = ?");
        $stmt->bindParam(1, $membershipID, PDO::PARAM_INT);
        $stmt->execute([$membershipID]);

        //Koppel het verwijderde membership los van de familieleden.
        $stmt = $this->pdo->prepare("UPDATE FamilyMember SET MembershipID = null WHERE MembershipID = ?");
        $stmt->bindParam(1, $membershipID, PDO::PARAM_INT);
        $stmt->execute([$membershipID]);

        return "<p class='goodMessage'>Lidmaatschap succesvol verwijderd.</p>";
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

            return "<p class='goodMessage'>Wijziging succesvol opgeslagen.</p>";
        }
        return "<p class='badMessage'>Er is een fout opgetreden. Probeer het nog eens.</p>";
    }

    public function getFinancialYears()
    {
        $query = ("SELECT FinancialYear.FinancialYearID, FinancialYear.Year, FinancialYear.Cost, Contribution.ContributionID FROM FinancialYear
        LEFT JOIN Contribution ON FinancialYear.FinancialYearID = Contribution.FinancialYearID");
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
            if ($stmt->rowCount() > 0) {
                return "<p class='badMessage'>Het boekjaar kon niet worden aangemaakt. Het boekjaar bestaat al.</p>";
            } else {
                $stmt = $this->pdo->prepare("INSERT INTO FinancialYear (FinancialYearID, Year, Cost) 
            VALUES (null, ?, ?)");
                $stmt->bindParam(1, $year, PDO::PARAM_INT);
                $stmt->bindParam(1, $cost, PDO::PARAM_INT);
                $stmt->execute([$year, $cost]);
                return "<p class='goodMessage'>Boekjaar succesvol toegevoegd.</p>";
            }
        }
        return "<p class='badMessage'>Er is een fout opgetreden. Probeer het nog eens.</p>";
    }

    public function deleteFinancialYear()
    {
        $financialYearID = $this->sanitizeString($_POST['financialYearID']);
        $contributionID = $this->sanitizeString($_POST['contributionID']);

        //Verwijder ook alle contributies van het betreffende boekjaar.
        $stmt = $this->pdo->prepare("DELETE FROM Contribution WHERE ContributionID = ?");
        $stmt->bindParam(1, $contributionID, PDO::PARAM_INT);
        $stmt->execute([$contributionID]);

        $stmt = $this->pdo->prepare("DELETE FROM FinancialYear WHERE FinancialYearID = ?");
        $stmt->bindParam(1, $financialYearID, PDO::PARAM_INT);
        $stmt->execute([$financialYearID]);

        return "<p class='goodMessage'>Boekjaar en contributies van het betreffende jaar succesvol verwijderd.</p>";
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
            return "<p class='goodMessage'>Wijziging succesvol opgeslagen.</p>";
        }
        return "<p class='bad'>Er is een fout opgetreden. Probeer het nog eens.<p>";
    }
}
