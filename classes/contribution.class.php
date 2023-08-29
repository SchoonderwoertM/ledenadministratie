<?php
class Contribution {
    public $contributionID; 
    public $membershipID;
    public $membershipType;
    public $age;
    public $discount;
    
    public function __construct($contributionID, $age, $discount, $membershipID, $description) {
        $this->contributionID = $contributionID;
        $this->membershipID = $membershipID;
        $this->age = $age;
        $this->discount = $discount;
        $this->membershipType = $description;
    }
}