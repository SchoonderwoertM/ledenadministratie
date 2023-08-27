<?php
class FinancialYear {
    public $financialYearID; 
    public $year;
    public $cost; 
    
    public function __construct($financialYearID, $year, $cost) {
        $this->financialYearID = $financialYearID;
        $this->year = $year;
        $this->cost = $cost;
    }
}
