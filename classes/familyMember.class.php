<?php
class FamilyMember
{
    public $familyMemberID;
    public $name;
    public $dateOfBirth;
    public $membershipType;
    public $familyID;
    public $contributionWithDiscount;

    public function __construct($familyMemberID, $name, $dateOfBirth, $familyID, $membership, $contribution, $discount)
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
            $this->contributionWithDiscount = $contribution - (($contribution * $discount) / 100);
        } else {
            $this->contributionWithDiscount = $contribution;
        }   
    }
}
