<?php
include_once 'classes\contribution.class.php';
include_once 'classes\financialYear.class.php';

class ContributionModel extends BaseModel
{
    public function GetMemberships()
    {
        //Haal het geselecteerde boekjaar op
        if (isset($_POST['financialYear'])) {
            $financialYear = $_POST['financialYear'];

            //Haal alle lidmaatschappen op.
            $stmt = $this->pdo->prepare("SELECT Contribution.ContributionID, Contribution.Age, Contribution.Discount, Membership.MembershipID, Membership.Description 
            FROM Contribution
            INNER JOIN Membership ON Contribution.MembershipID = Membership.MembershipID
            INNER JOIN FinancialYear ON Contribution.FinancialYearID = FinancialYear.FinancialYearID
            WHERE FinancialYear.Year = ?");
            $stmt->bindParam(1, $financialYear, PDO::PARAM_INT);
            $stmt->execute([$financialYear]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $memberships = [];
            foreach ($rows as $row) {
                $membership = new Contribution($row['ContributionID'], $row['Age'], $row['Discount'], $row['MembershipID'], $row['Description'], $financialYear);
                $memberships[] = $membership;
            }
            return $memberships;
        }
    }

    public function GetMembership()
    {
        //Haal de details van een lidmaatschap op aan de hand van het contributieID.
        $contributionID = $this->sanitizeString($_POST['contributionID']);

        $stmt = $this->pdo->prepare("SELECT * FROM Contribution
        INNER JOIN Membership ON Contribution.MembershipID = Membership.MembershipID
        INNER JOIN FinancialYear ON Contribution.FinancialYearID = FinancialYear.FinancialYearID
        WHERE Contribution.ContributionID = ?");
        $stmt->bindParam(1, $contributionID, PDO::PARAM_INT);
        $stmt->execute([$contributionID]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return new Contribution($row['ContributionID'], $row['Age'], $row['Discount'], $row['MembershipID'], $row['Description'], $row['Year']);
    }

    public function UpdateMembership()
    {
        //Controleer of de invoervelden een waarde hebben.
        if (
            isset($_POST['description']) &&
            isset($_POST['age']) &&
            isset($_POST['discount'])
        ) {
            //Ontdoe de ingevoerde waarde van ongeweste slashes en html.
            $contributionID = $this->sanitizeString($_POST['contributionID']);
            $age = intval($this->sanitizeString($_POST['age']));
            $discount = $this->sanitizeString($_POST['discount']);
            $membershipID = $this->sanitizeString($_POST['membershipID']);
            $description = $this->sanitizeString($_POST['description']);

            //Sla de ingevoerde waarden op in de database.
            $stmt = $this->pdo->prepare("UPDATE Contribution SET Age = ?, Discount = ? WHERE ContributionID = ?");
            $stmt->bindParam(1, $age, PDO::PARAM_INT);
            $stmt->bindParam(2, $discount, PDO::PARAM_INT);
            $stmt->bindParam(3, $contributionID, PDO::PARAM_INT);
            $stmt->execute([$age, $discount, $contributionID]);

            //Sla de ingevoerde waarden op in de database.
            $stmt = $this->pdo->prepare("UPDATE Membership SET Description = ? WHERE MembershipID = ?");
            $stmt->bindParam(1, $description, PDO::PARAM_STR, 100);
            $stmt->bindParam(2, $membershipID, PDO::PARAM_INT);
            $stmt->execute([$description, $membershipID]);

            return "<p class='goodMessage'>Wijziging succesvol opgeslagen.</p>";
        }
        return "<p class='badMessage'>Er is een fout opgetreden. Probeer het nog eens.</p>";
    }

    public function GetFinancialYears()
    {
        //Haal alle boekjaren op.
        $query = ("SELECT * FROM FinancialYear ORDER BY Year DESC");
        $result = $this->pdo->query($query);
        $rows = $result->fetchAll(PDO::FETCH_ASSOC);

        $financialYears = [];
        foreach ($rows as $row) {
            $financialYear = new FinancialYear($row['FinancialYearID'], $row['Year'], $row['Contribution']);
            $financialYears[] = $financialYear;
        }

        return $financialYears;
    }

    public function GetFinancialYear()
    {
        //Haal de details van het geselecteerd boekjaar op.
        $financialYearID = $this->sanitizeString($_POST['financialYearID']);

        $stmt = $this->pdo->prepare("SELECT * FROM FinancialYear
        WHERE FinancialYear.FinancialYearID = ?");
        $stmt->bindParam(1, $financialYearID, PDO::PARAM_INT);
        $stmt->execute([$financialYearID]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return new FinancialYear($row['FinancialYearID'], $row['Year'], $row['Contribution']);
    }

    public function CreateFinancialYear()
    {
        //Controleer of de invoervelden een waarde hebben.
        if (
            isset($_POST['year']) &&
            isset($_POST['contribution'])
        ) {
            //Ontdoe de ingevoerde waarde van ongeweste slashes en html.
            $year = $this->sanitizeString($_POST['year']);
            $contribution = $this->sanitizeString($_POST['contribution']);

            //Check of het boekjaar bestaat.
            $financialYearID = $this->GetFinancialYearID($year);
            if ($financialYearID) {
                return "<p class='badMessage'>Het boekjaar kon niet worden aangemaakt. Het boekjaar bestaat al.</p>";
            }
            //Als het boekjaar nog niet bestaat, maak deze dan aan.
            else {
                $stmt = $this->pdo->prepare("INSERT INTO FinancialYear (FinancialYearID, Year, Contribution) 
            VALUES (null, ?, ?)");
                $stmt->bindParam(1, $year, PDO::PARAM_INT);
                $stmt->bindParam(2, $contribution, PDO::PARAM_INT);
                $stmt->execute([$year, $contribution]);
                $financialYearID = $this->pdo->lastInsertId();

                //Haal alle lidmaatschappen op
                $query = ("SELECT * FROM Membership");
                $result = $this->pdo->query($query);
                $rows = $result->fetchAll(PDO::FETCH_ASSOC);

                //Maak voor alle lidmaatschappen een record aan in de Contribution tabel met als korting en leeftijd 0.
                foreach ($rows as $row) {
                    $membershipID = $row['MembershipID'];
                    $stmt = $this->pdo->prepare("INSERT INTO Contribution (ContributionID, Age, Discount, FinancialYearID, MembershipID) 
                    VALUES (null, 0, 0, ?, ?)");
                        $stmt->bindParam(1, $financialYearID, PDO::PARAM_INT);
                        $stmt->bindParam(2, $membershipID, PDO::PARAM_INT);
                        $stmt->execute([$financialYearID, $membershipID]);
                }
                return "<p class='goodMessage'>Boekjaar succesvol toegevoegd.</p>";
            }
        }
        return "<p class='badMessage'>Er is een fout opgetreden. Probeer het nog eens.</p>";
    }

    public function DeleteFinancialYear()
    {
        $financialYearID = $this->sanitizeString($_POST['financialYearID']);

        //Verwijder alle contributies van het betreffende boekjaar.
        $stmt = $this->pdo->prepare("DELETE FROM Contribution WHERE FinancialYearID = ?");
        $stmt->bindParam(1, $financialYearID, PDO::PARAM_INT);
        $stmt->execute([$financialYearID]);

        //Verwijder het boekjaar zelf.
        $stmt = $this->pdo->prepare("DELETE FROM FinancialYear WHERE FinancialYearID = ?");
        $stmt->bindParam(1, $financialYearID, PDO::PARAM_INT);
        $stmt->execute([$financialYearID]);

        return "<p class='goodMessage'>Boekjaar en lidmaatschappen van het betreffende jaar succesvol verwijderd.</p>";
    }

    public function UpdateFinancialYear()
    {
        //Controleer of de invoervelden een waarde hebben.
        if (
            isset($_POST['contribution'])
        ) {
            //Ontdoe de ingevoerde waarde van ongeweste slashes en html.
            $financialYearID = $this->sanitizeString($_POST['financialYearID']);
            $contribution = $this->sanitizeString($_POST['contribution']);

            //Sla de ingevoerde waarde op in de database.
            $stmt = $this->pdo->prepare("UPDATE FinancialYear SET Contribution = ? 
            WHERE FinancialYearID = ?");
            $stmt->bindParam(1, $contribution, PDO::PARAM_INT);
            $stmt->bindParam(2, $financialYearID, PDO::PARAM_INT);
            $stmt->execute([$contribution, $financialYearID]);
            return "<p class='goodMessage'>Wijziging succesvol opgeslagen.</p>";
        }

        return "<p class='bad'>Er is een fout opgetreden. Probeer het nog eens.<p>";
    }

    //Check of het boekjaar bestaat.
    private function GetFinancialYearID($year)
    {
        $stmt = $this->pdo->prepare("SELECT FinancialYearID FROM FinancialYear WHERE Year = ?");
        $stmt->execute([$year]);

        //Als het boekjaar bestaat, zet het FinancialYearID dan in een variabele.
        if ($stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $financialYearID = $row['FinancialYearID'];
                return $financialYearID;
            }
            return null;
        }
    }

    //Check of het lidmaatschap aan een lid is gekoppeld.
    private function MembershipAssociated($membershipID)
    {
        $stmt = $this->pdo->prepare("SELECT FamilyMemberID FROM FamilyMember WHERE MembershipID = ?");
        $stmt->bindParam(1, $membershipID, PDO::PARAM_INT);
        $stmt->execute([$membershipID]);

        if ($stmt->rowCount() > 0) {
            return true;
        }
        return false;
    }

    //Update het membershipID van een lid.
    public function UpdateMembershipID($familyMemberID, $membershipID)
    {
        $stmt = $this->pdo->prepare("UPDATE FamilyMember SET MembershipID = ? WHERE FamilyMemberID = ?");
        $stmt->bindParam(1, $membershipID, PDO::PARAM_INT);
        $stmt->bindParam(1, $familyMemberID, PDO::PARAM_INT);
        $stmt->execute([$membershipID, $familyMemberID]);
    }

    //Hercalculeer het membership van alle leden.
    public function RecalculateMemberships()
    {
        //Haal alle leden op
        $query = ("SELECT FamilyMemberID, MembershipID, DateOfBirth FROM FamilyMember");
        $result = $this->pdo->query($query);
        $rows = $result->fetchAll(PDO::FETCH_ASSOC);

        //Pas het lidmaatschap van de leden aan als deze afwijkt van het huidige lidmaatschap
        foreach ($rows as $row) {
            $membershipID = $this->getMembershipByDateOfBirth($row['DateOfBirth']);
            if ($row['MembershipID'] !== $membershipID)
                $this->UpdateMembershipID($row['FamilyMemberID'], $membershipID);
        }
        return '<p class="goodMessage">De lidmaatschappen zijn succesvol geüpdate.</p>';
    }
}
