<?php
class Family
{
    public $familyID;
    public $name;
    public $street;
    public $housenumber;
    public $postalCode;
    public $city;
    public $numberOfFamilyMembers;
    public $totalContribution;

    public function __construct($familyID, $name, $street, $housenumber, $postalCode, $city, $numberOfFamilyMembers, $yearContribution, $totalDiscount)
    {
        $this->familyID = $familyID;
        $this->name = $name;
        $this->street = $street;
        $this->housenumber = $housenumber;
        $this->postalCode = $postalCode;
        $this->city = $city;
        $this->numberOfFamilyMembers = $numberOfFamilyMembers;
        if ($totalDiscount != 0) {
            $this->totalContribution = ($yearContribution - ($yearContribution * ($totalDiscount / $numberOfFamilyMembers)) / 100) * $numberOfFamilyMembers;
        }
        else $this->totalContribution = $yearContribution * $numberOfFamilyMembers;
    }
}
