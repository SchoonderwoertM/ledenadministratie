<?php
class FamilyMember {
    public $familyMemberID; 
    public $name;
    public $dateOfBirth; 
    public $familyID; 
    public $membership;
    public $cost;
    public $discount;
    
    public function __construct($familyMemberID, $name, $dateOfBirth, $familyID, $membership, $cost, $discount) {
        $this->familyMemberID = $familyMemberID;
        $this->name = $name;
        $this->dateOfBirth = $dateOfBirth;
        $this->familyID = $familyID;
        $this->membership = $membership;
        $this->cost = $cost;
        $this->discount = $discount;
    }
}
