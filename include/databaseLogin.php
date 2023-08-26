<?php
//Zet alle waarden voor het opzetten van de database connectie.
$host = 'localhost';
$data = 'membershipAdministration';
$user = 'root';
$pass = 'mysql';
$chrs = 'utf8mb4';
$attr = "mysql:host=$host;dbname=$data;charset=$chrs";
$opts =
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
