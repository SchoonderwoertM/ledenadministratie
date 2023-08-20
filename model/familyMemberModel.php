<?php

class FamilyMemberModel
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

    public function getFamilyMembers()
    {
    }

    public function getFamilyMember()
    {
    }

    public function createFamilyMember()
    {
    }

    public function deleteFamilyMember()
    {
    }

    public function updateFamilyMember()
    {
    }
}
