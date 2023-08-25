<?php
require_once 'include\authenticate.php';
include_once 'model\BaseModel.php';
include_once 'model\familyModel.php';
include_once 'model\familyMemberModel.php';
include_once 'model\contributionModel.php';
session_start();

class Controller
{
    private $familyModel;
    private $familyMemberModel;
    private $contributionModel;

    public function __construct()
    {
        $this->familyModel = new FamilyModel();
        $this->familyMemberModel = new FamilyMemberModel();
        $this->contributionModel = new ContributionModel();
    }

    public function handleRequest()
    {
        //Families
        if (isset($_POST['Family'])) {
            if (isset($_POST['manageFamilies'])) {
                $families = $this->familyModel->getFamilies();
                include('view\family\families.php');
            } else if (isset($_POST['addFamily'])) {
                include('view\family\addFamily.php');
            } else if (isset($_POST['editFamily'])) {
                $family = $this->familyModel->getFamily();
                include('view\family\editFamily.php');
            }
            //CRUDD
            else if (isset($_POST['createFamily'])) {
                echo $this->familyModel->createFamily();
                $families = $this->familyModel->getFamilies();
                include 'view\family\families.php';
            } else if (isset($_POST['deleteFamily'])) {
                echo $this->familyModel->deleteFamily();
                $families = $this->familyModel->getFamilies();
                include 'view\family\families.php';
            } else if (isset($_POST['updateFamily'])) {
                echo $this->familyModel->updateFamily();
                $families = $this->familyModel->getFamilies();
                include 'view\family\families.php';
            }
        }

        //FamilyMembers
        else if (isset($_POST['FamilyMember'])) {
            if (isset($_POST['manageFamilyMembers'])) {
                $familyMembers = $this->familyMemberModel->getFamilyMembers();
                include('view\familyMember\familyMembers.php');
            } else if (isset($_POST['addFamilyMember'])) {
                $familyID = $_POST['familyID'];
                include('view\familyMember\addFamilyMember.php');
            } else if (isset($_POST['editFamilyMember'])) {
                $familyMember = $this->familyMemberModel->getFamilyMember();
                include('view\familyMember\editFamilyMember.php');
            }
            //CRUDD
            else if (isset($_POST['createFamilyMember'])) {
                echo $this->familyMemberModel->createFamilyMember();
                $familyMembers = $this->familyMemberModel->getFamilyMembers();
                include('view\familyMember\familyMembers.php');
            } else if (isset($_POST['deleteFamilyMember'])) {
                echo $this->familyMemberModel->deleteFamilyMember();
                $familyMembers = $this->familyMemberModel->getFamilyMembers();
                include('view\familyMember\familyMembers.php');
            } else if (isset($_POST['updateFamilyMember'])) {
                echo $this->familyMemberModel->updateFamilyMember();
                $familyMembers = $this->familyMemberModel->getFamilyMembers();
                include('view\familyMember\familyMembers.php');
            }
        }

        //Contributions
        else if (isset($_POST['Contribution'])) {
            if (isset($_POST['manageContributions'])) {
                $financialYears = $this->contributionModel->getFinancialYears();
                $contributions = $this->contributionModel->getContributions();
                include('view\contribution\contributions.php');
            } else if (isset($_POST['addContribution'])) {
                include('view\contribution\addContribution.php');
            } else if (isset($_POST['editContribution'])) {
                $contribution = $this->contributionModel->getContribution();
                include('view\contribution\editContribution.php');
            }
            //CRUDD
            else if (isset($_POST['createContribution'])) {
                echo $this->contributionModel->createContribution();
                $financialYears = $this->contributionModel->getFinancialYears();
                $contributions = $this->contributionModel->getContributions();
                include('view\contribution\contributions.php');
            } else if (isset($_POST['deleteContribution'])) {
                echo $this->contributionModel->deleteContribution();
                $financialYears = $this->contributionModel->getFinancialYears();
                $contributions = $this->contributionModel->getContributions();
                include('view\contribution\contributions.php');
            } else if (isset($_POST['updateContribution'])) {
                echo $this->contributionModel->updateContribution();
                $financialYears = $this->contributionModel->getFinancialYears();
                $contributions = $this->contributionModel->getContributions();
                include('view\contribution\contributions.php');
            }

            //FinancialYear
            else if (isset($_POST['addFinancialYear'])) {
                include('view\contribution\addFinancialYear.php');
            } else if (isset($_POST['editFinancialYear'])) {
                $financialYear = $this->contributionModel->getFinancialYear();
                include('view\contribution\editFinancialYear.php');
            }
            //CRUD
            else if (isset($_POST['createFinancialYear'])) {
                echo $this->contributionModel->createFinancialYear();
                $contributions = $this->contributionModel->getContributions();
                $financialYears = $this->contributionModel->getFinancialYears();
                include('view\contribution\contributions.php');
            } else if (isset($_POST['deleteFinancialYear'])) {
                echo $this->contributionModel->deleteFinancialYear();
                $contributions = $this->contributionModel->getContributions();
                $financialYears = $this->contributionModel->getFinancialYears();
                include('view\contribution\contributions.php');
            } else if (isset($_POST['updateFinancialYear'])) {
                echo $this->contributionModel->updateFinancialYear();
                $contributions = $this->contributionModel->getContributions();
                $financialYears = $this->contributionModel->getFinancialYears();
                include('view\contribution\contributions.php');
            }
        }

        //Dashboard
        else {
            $families = $this->familyModel->getFamilies();
            include 'view\dashboard.php';
        }
    }
}

$controller = new Controller();
$controller->handleRequest();
