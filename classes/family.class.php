<?php
class Family {
    public $familyID; 
    public $name;
    public $address; 
    public $postalCode;
    public $city; 
    public $numberOfFamilyMembers; 
    public $totalContribution; 
    
    public function __construct($familyID, $name, $address, $postalCode, $city, $numberOfFamilyMembers, $totalContribution) {
        $this->familyID = $familyID;
        $this->name = $name;
        $this->address = $address;
        $this->postalCode = $postalCode;
        $this->city = $city;
        $this->numberOfFamilyMembers = $numberOfFamilyMembers;
        $this->totalContribution = $totalContribution;
    }
}

