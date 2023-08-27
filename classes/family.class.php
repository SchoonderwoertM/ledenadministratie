<?php
class Family {
    public $familyID; 
    public $name;
    public $address; 
    public $city; 
    public $numberOfFamilyMembers; 
    public $totalContribution; 
    
    public function __construct($familyID, $name, $address, $city, $numberOfFamilyMembers, $totalContribution) {
        $this->familyID = $familyID;
        $this->name = $name;
        $this->address = $address;
        $this->city = $city;
        $this->numberOfFamilyMembers = $numberOfFamilyMembers;
        $this->totalContribution = $totalContribution;
    }
}

