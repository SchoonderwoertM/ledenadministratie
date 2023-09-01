<?php
class FamilyMember
{
    public $familyMemberID;
    public $name;
    public $dateOfBirth;
    public $membershipType;
    public $familyID;
    public $contributionWithDiscount;

    public function __construct($familyMemberID, $name, $dateOfBirth, $familyID, $membership, $cost, $discount)
    {
        $this->familyMemberID = $familyMemberID;
        $this->name = $name;
        $this->dateOfBirth = $dateOfBirth;
        $this->familyID = $familyID;
        if(!is_null($membership)){
            $this->membershipType = $membership;
        }
        else{
            $this->membershipType = "-";
        }

        if ($discount != 0) {
            $this->contributionWithDiscount = $cost - (($cost * $discount) / 100);
        } else {
            $this->contributionWithDiscount = $cost;
        }   
    }
}
