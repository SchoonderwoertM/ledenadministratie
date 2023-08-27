<?php
class Contribution {
    public $contributionID; 
    public $age;
    public $discount;
    public $membershipID;
    public $description;
    
    public function __construct($contributionID, $age, $discount, $membershipID, $description) {
        $this->contributionID = $contributionID;
        $this->age = $age;
        $this->discount = $discount;
        $this->membershipID = $membershipID;
        $this->description = $description;
    }
}