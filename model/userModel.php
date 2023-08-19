<?php

class ContributionModel
{
    private $pdo;
    public function __construct()
    {
        require_once 'include\databaseLogin.php';

        try {
            $this->pdo = new PDO($attr, $user, $pass, $opts);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public function getUser(){

    }
}
?>