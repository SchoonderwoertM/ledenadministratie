<?php
class FamilyMember {
    public $familyMemberID; 
    public $name;
    public $dateOfBirth; 
    public $familyID; 
    
    public function __construct($familyMemberID, $name, $dateOfBirth, $familyID) {
        $this->familyMemberID = $familyMemberID;
        $this->name = $name;
        $this->dateOfBirth = $dateOfBirth;
        $this->familyID = $familyID;
    }
}
