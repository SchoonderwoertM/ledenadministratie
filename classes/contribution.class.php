<?php
class Contribution {
    public $contributionID; 
    public $age;
    public $discount;
    public $membershipID;
    public $membershipType;
    public $financialYear;
    
    public function __construct($contributionID, $age, $discount, $membershipID, $description, $financialYear) {
        $this->contributionID = $contributionID;
        $this->membershipID = $membershipID;
        $this->age = $age;
        $this->discount = $discount;
        $this->membershipType = $description;
        $this->financialYear = $financialYear;
    }
}