<?php

class ContributionModel
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

    public function getContributions(){
        $query = ("SELECT Contribution.ContributionID, Contribution.Cost, Contribution.Age, FinancialYear.Year 
        FROM Contribution
        LEFT JOIN FinancialYear on Contribution.ContributionID = FinancialYear.ContributionID");
        $result = $this->pdo->query($query);
        return $result->fetchAll();
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

    public function saveContribution(){
        // require_once 'include\databaseLogin.php';
        // try {
        //     $pdo = new PDO($attr, $user, $pass, $opts);
        // } catch (PDOException $e) {
        //     throw new PDOException($e->getMessage(), (int)$e->getCode());
        // }
        // $financialYear = get_post($pdo , 'financielYear');
        // $cost = get_post($pdo, 'cost');

        // $query = "INSERT INTO ";
        // $result = $pdo->query($query);

    }
}
