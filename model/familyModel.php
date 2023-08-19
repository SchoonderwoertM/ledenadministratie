<?php

class FamilyModel
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

    public function getFamilies()
    {
        $query = ("SELECT * FROM Family");
        $result = $this->pdo->query($query);
        return $result->fetchAll();
    }

    public function getFamilyMembers()
    {
    }

    public function createFamily()
    {
        // $query = "INSERT INTO family VALUES(null, '$familyName', '$address'"
        // $result = $pdo->query($query);
    }

    public function deleteFamily()
    {
        // $query = "DELETE * FROM family WHERE familyID = '$familyID'"
        // $result = $pdo->query($query);
    }

    public function updateFamily()
    {
        // $query = "UPDATE family SET name='$name', address='$address' WHERE familyID='$familyID'"
        // $result = $pdo->query($query);
    }

    public function getFamilyMember()
    {
    }

    public function createFamilyMember()
    {
    }

    public function delteFamilyMember()
    {
    }

    public function updateFamilyMember()
    {
    }
}
