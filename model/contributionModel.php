<?php
include_once 'classes\contribution.class.php';
include_once 'classes\financialYear.class.php';

class ContributionModel extends BaseModel
{
    public function getMemberships()
    {
        //Haal alle lidmaatschappen op.
        $query = ("SELECT Contribution.ContributionID, Contribution.Age, Contribution.Discount, Membership.MembershipID, Membership.Description 
            FROM Contribution
            INNER JOIN Membership ON Contribution.MembershipID = Membership.MembershipID
            INNER JOIN FinancialYear ON Contribution.FinancialYearID = FinancialYear.FinancialYearID");
        $result = $this->pdo->query($query);
        $rows = $result->fetchAll(PDO::FETCH_ASSOC);

        $memberships = [];
        foreach ($rows as $row) {
            $membership = new Contribution($row['ContributionID'], $row['Age'], $row['Discount'], $row['MembershipID'], $row['Description']);
            $memberships[] = $membership;
        }
        return $memberships;
    }

    public function getMembership()
    {
        //Haal de details van een lidmaatschap op aan de hand van het contributieID.
        $contributionID = $this->sanitizeString($_POST['contributionID']);

        $stmt = $this->pdo->prepare("SELECT Contribution.ContributionID, Contribution.Age, Contribution.Discount, Membership.MembershipID, Membership.Description 
        FROM Contribution
        INNER JOIN Membership ON Contribution.MembershipID = Membership.MembershipID
        WHERE Contribution.ContributionID = ?");
        $stmt->bindParam(1, $contributionID, PDO::PARAM_INT);
        $stmt->execute([$contributionID]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return new Contribution($row['ContributionID'], $row['Age'], $row['Discount'], $row['MembershipID'], $row['Description']);
    }

    public function recalculateMemberships()
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

    public function createMembership()
    {
        //Controleer of de invoervelden een waarde hebben.
        if (
            isset($_POST['description']) &&
            isset($_POST['age']) &&
            isset($_POST['discount'])
        ) {
            //Ontdoe de ingevoerde waarde van ongeweste slashes en html.
            $membership = $this->sanitizeString($_POST['description']);
            $age = $this->sanitizeString($_POST['age']);
            $discount = $this->sanitizeString($_POST['discount']);
            //Haal huidig jaar op.
            $financialYear = date('Y');

            //Check of het boekjaar bestaat.
            $financialYearID = $this->GetFinancialYearID($financialYear);
            if ($financialYearID) {
                //Sla het Membership op in de database.
                $stmt = $this->pdo->prepare("INSERT INTO Membership (MembershipID, Description) 
            VALUES (null, ?)");
                $stmt->bindParam(1, $membership, PDO::PARAM_STR, 100);
                $stmt->execute([$membership]);
                $membershipID = $stmt->fetch();
                //Onthou het MembershipID van het zojuist toegevoegde record.
                $membershipID = $this->pdo->lastInsertId();

                //Sla de contributie op in de database.
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

    public function deleteMembership()
    {
        $contributionID = $this->sanitizeString($_POST['contributionID']);
        $membershipID = $this->sanitizeString($_POST['membershipID']);

        //Check of het lidmaatschap aan een lid is gekoppeld.
        if ($this->MembershipAssociated($membershipID)) {
            return "<p class='badMessage'>Het is niet mogelijk het lidmaatschap te verwijderen. Er zijn nog leden aan gekoppeld.</p>";
        }

        //Verwijder het record uit de Contribution tabel.
        $stmt = $this->pdo->prepare("DELETE FROM Contribution WHERE ContributionID = ?");
        $stmt->bindParam(1, $contributionID, PDO::PARAM_INT);
        $stmt->execute([$contributionID]);

        //Verwijder het record uit de Membership tabel.
        $stmt = $this->pdo->prepare("DELETE FROM Membership WHERE MembershipID = ?");
        $stmt->bindParam(1, $membershipID, PDO::PARAM_INT);
        $stmt->execute([$membershipID]);

        //Koppel het verwijderde membership los van de familieleden.
        $stmt = $this->pdo->prepare("UPDATE FamilyMember SET MembershipID = NULL WHERE MembershipID = ?");
        $stmt->bindParam(1, $membershipID, PDO::PARAM_INT);
        $stmt->execute([$membershipID]);

        return "<p class='goodMessage'>Lidmaatschap succesvol verwijderd.</p>";
    }

    public function updateMembership()
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

    public function getFinancialYears()
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

    public function getFinancialYear()
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

    public function createFinancialYear()
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
            //Als het boekjaar nog niet bestaat, maak deze dan toe.
            else {
                $stmt = $this->pdo->prepare("INSERT INTO FinancialYear (FinancialYearID, Year, Contribution) 
            VALUES (null, ?, ?)");
                $stmt->bindParam(1, $year, PDO::PARAM_INT);
                $stmt->bindParam(2, $contribution, PDO::PARAM_INT);
                $stmt->execute([$year, $contribution]);
                return "<p class='goodMessage'>Boekjaar succesvol toegevoegd.</p>";
            }
        }
        return "<p class='badMessage'>Er is een fout opgetreden. Probeer het nog eens.</p>";
    }

    public function deleteFinancialYear()
    {
        $financialYearID = $this->sanitizeString($_POST['financialYearID']);

        //Haal alle membershipID's op die zijn gekoppeld aan het betreffende boekjaar.
        $stmt = $this->pdo->prepare("SELECT MembershipID FROM Contribution WHERE FinancialYearID = ?");
        $stmt->bindParam(1, $financialYearID, PDO::PARAM_INT);
        $stmt->execute([$financialYearID]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rows as $row) {
            $membershipID = $row['MembershipID'];
            //Check of het lidmaatschap aan een lid is gekoppeld.
            if ($this->MembershipAssociated($membershipID)) {
                return "<p class='badMessage'>Het is niet mogelijk het boekjaar te verwijderen. Er zijn nog leden aan gekoppeld.</p>";
            }
        }

        //Verwijder alle contributies van het betreffende boekjaar.
        $stmt = $this->pdo->prepare("DELETE FROM Contribution WHERE FinancialYearID = ?");
        $stmt->bindParam(1, $financialYearID, PDO::PARAM_INT);
        $stmt->execute([$financialYearID]);

        //Verwijder het boekjaar zelf.
        $stmt = $this->pdo->prepare("DELETE FROM FinancialYear WHERE FinancialYearID = ?");
        $stmt->bindParam(1, $financialYearID, PDO::PARAM_INT);
        $stmt->execute([$financialYearID]);

        //Verwijder alle memberships van het betreffende boekjaar.
        foreach ($rows as $row) {
            $membershipID = $row['MembershipID'];
            $stmt = $this->pdo->prepare("DELETE FROM Membership WHERE MembershipID = ?");
            $stmt->bindParam(1, $membershipID, PDO::PARAM_INT);
            $stmt->execute([$membershipID]);
        }

        //Koppel de verwijdere membershipID's los van de familieleden.
        $stmt = $this->pdo->prepare("UPDATE FamilyMember SET MembershipID = NULL WHERE MembershipID = ?");
        $stmt->bindParam(1, $membershipID, PDO::PARAM_INT);
        $stmt->execute([$membershipID]);

        return "<p class='goodMessage'>Boekjaar en lidmaatschappen van het betreffende jaar succesvol verwijderd.</p>";
    }

    public function updateFinancialYear()
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

    public function UpdateMembershipID($familyMemberID, $membershipID)
    {
        $stmt = $this->pdo->prepare("UPDATE FamilyMember SET MembershipID = ? WHERE FamilyMemberID = ?");
        $stmt->bindParam(1, $membershipID, PDO::PARAM_INT);
        $stmt->bindParam(1, $familyMemberID, PDO::PARAM_INT);
        $stmt->execute([$membershipID, $familyMemberID]);
    }
}
