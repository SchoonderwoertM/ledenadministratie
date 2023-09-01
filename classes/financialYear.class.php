<?php
class FinancialYear {
    public $financialYearID; 
    public $year;
    public $contribution; 
    
    public function __construct($financialYearID, $year, $contribution) {
        $this->financialYearID = $financialYearID;
        $this->year = $year;
        $this->contribution = $contribution;
    }
}
