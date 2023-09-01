<?php
include_once 'classes\familyMember.class.php';

class FamilyMemberModel extends BaseModel
{
    public function getFamilyMembers()
    {
        //Haal het huidige jaar op.
        $currentYear = date('Y');

        //Haal de familieleden op van de geselecteerde familie.
        if (!empty($_POST['familyID'])) {
            $familyID = $this->sanitizeString($_POST['familyID']);

            $stmt = $this->pdo->prepare("SELECT FamilyMember.FamilyMemberID, FamilyMember.FamilyID, FamilyMember.Name, FamilyMember.DateOfBirth, Membership.Description, 
            FinancialYear.Contribution, Contribution.Discount FROM FamilyMember
            LEFT OUTER JOIN Membership ON FamilyMember.MembershipID = Membership.MembershipID
            LEFT OUTER JOIN Contribution ON Membership.MembershipID = Contribution.MembershipID
            LEFT OUTER JOIN FinancialYear ON Contribution.FinancialYearID = FinancialYear.FinancialYearID
            WHERE FamilyMember.FamilyID = ? AND FinancialYear.Year = $currentYear OR Membership.MembershipID IS NULL");
            $stmt->bindParam(1, $familyID, PDO::PARAM_INT);
            $stmt->execute([$familyID]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $familyMembers = [];
            foreach ($rows as $row) {
                if(empty($row['Contribution'])){
                    $contributon = $this->getContributionCurrentYear();
                    $row['Contribution'] = $contributon;
                }
                $familyMember = new FamilyMember($row['FamilyMemberID'], $row['Name'], $row['DateOfBirth'], $row['FamilyID'], $row['Description'], $row['Contribution'], $row['Discount']);
                $familyMembers[] = $familyMember;
            }
            return $familyMembers;
        }
        return "<p class='badMessage'>Kan de familie niet vinden.</p>";
    }

    public function getFamilyMember()
    {
        //Haal het familielid op aan de hand van het FamilyMemberID.
        $familyMemberID = $this->sanitizeString($_POST['familyMemberID']);

        $stmt = $this->pdo->prepare("SELECT * FROM FamilyMember
        WHERE FamilyMember.FamilyMemberID = ?");
        $stmt->bindParam(1, $familyMemberID, PDO::PARAM_INT);
        $stmt->execute([$familyMemberID]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return new FamilyMember($row['FamilyMemberID'], $row['Name'], $row['DateOfBirth'], $row['FamilyID'], null, null, null);
    }

    public function createFamilyMember()
    {
        //Controleer of de invoervelden een waarde hebben.
        if (
            isset($_POST['name']) &&
            isset($_POST['dateOfBirth']) &&
            isset($_POST['familyID'])
        ) {
            //Ontdoe de ingevoerde waarde van ongeweste slashes en html.
            $name = $this->sanitizeString($_POST['name']);
            $dateOfBirth = $this->sanitizeString($_POST['dateOfBirth']);
            $familyID = $this->sanitizeString($_POST['familyID']);
            $membershipID = $this->getMembershipByDateOfBirth($dateOfBirth);

            if (!$membershipID) {
                return "<p class='badMessage'>Er is geen lidmaatschap bekend voor deze leeftijd.</p>";
            }

            //Sla het familid op in de database.
            $stmt = $this->pdo->prepare("INSERT INTO FamilyMember (FamilyMemberID, Name, DateOfBirth, MembershipID, FamilyID) VALUES (null, ?, ?, ?, ?)");
            $stmt->bindParam(1, $name, PDO::PARAM_STR, 50);
            $stmt->bindParam(2, $dateOfBirth, PDO::PARAM_STR, 10);
            $stmt->bindParam(3, $membershipID, PDO::PARAM_INT);
            $stmt->bindParam(4, $familyID, PDO::PARAM_INT);
            $stmt->execute([$name, $dateOfBirth, $membershipID, $familyID]);

            return "<p class='goodMessage'>Familielid toegevoegd.</p>";
        }
        return "<p class='badMessage'>Er is een fout opgetreden. Probeer het nog eens.</p>";
    }

    public function deleteFamilyMember()
    {
        $familyMemberID = $this->sanitizeString($_POST['familyMemberID']);
        $familyID = $this->sanitizeString($_POST['familyID']);

        //Haal het AddressID van de familie op.
        $stmt = $this->pdo->prepare("SELECT AddressID FROM Family WHERE FamilyID = ?");
        $stmt->bindParam(1, $familyID, PDO::PARAM_INT);
        $stmt->execute([$familyID]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $addressID = $row['AddressID'];

        //Verwijder het het familielid.
        $stmt = $this->pdo->prepare("DELETE FROM FamilyMember WHERE FamilyMember.FamilyMemberID = ?");
        $stmt->bindParam(1, $familyMemberID, PDO::PARAM_INT);
        $stmt->execute([$familyMemberID]);

        //Check of er nog familieleden zijn gekoppeld aan de betreffende familie.
        $stmt = $this->pdo->prepare("SELECT FamilyMemberID FROM FamilyMember WHERE FamilyMember.FamilyID = ?");
        $stmt->bindParam(1, $familyID, PDO::PARAM_INT);
        $stmt->execute([$familyID]);
        //Als er geen familiedelen meer zijn, verwijder dan de familie uit de database.
        if ($stmt->rowCount() == 0) {
            $stmt = $this->pdo->prepare("DELETE FROM Family WHERE Family.FamilyID = ?");
            $stmt->bindParam(1, $familyID, PDO::PARAM_INT);
            $stmt->execute([$familyID]);

            //Verwijder het woonadres.
            $stmt = $this->pdo->prepare("DELETE FROM Address WHERE AddressID = ?");
            $stmt->bindParam(1, $addressID, PDO::PARAM_INT);
            $stmt->execute([$addressID]);
        }

        return "<p class='badMessage'>Familielid verwijderd.</p>";
    }

    public function updateFamilyMember()
    {
        if (
            isset($_POST['name']) &&
            isset($_POST['dateOfBirth'])
        ) {
            $familyMemberID = $this->sanitizeString($_POST['familyMemberID']);
            $name = $this->sanitizeString($_POST['name']);
            $dateOfBirth = $this->sanitizeString($_POST['dateOfBirth']);
            $membershipID = $this->getMembershipByDateOfBirth($dateOfBirth);

            if (!$membershipID) {
                return "<p class='badMessage'>Er is geen lidmaatschap bekend voor deze leeftijd.</p>";
            }

            //Sla de ingevoerde waarden op in de database.
            $stmt = $this->pdo->prepare("UPDATE FamilyMember SET Name = ?, DateOfBirth = ?, MembershipID = ? WHERE FamilyMemberID = ?");
            $stmt->bindParam(1, $name, PDO::PARAM_INT);
            $stmt->bindParam(2, $dateOfBirth, PDO::PARAM_STR, 10);
            $stmt->bindParam(3, $membershipID, PDO::PARAM_INT);
            $stmt->bindParam(4, $familyMemberID, PDO::PARAM_INT);
            $stmt->execute([$name, $dateOfBirth, $membershipID, $familyMemberID]);

            return "<p class='goodMessage'>Wijziging succesvol opgeslagen.</p>";
        }
        return "<p class='badMessage'>Er is een fout opgetreden. Probeer het nog eens.</p>";
    }
}
