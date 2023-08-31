<?php
// require_once 'include\authenticate.php';
include_once 'model\baseModel.php';
include_once 'model\familyModel.php';
include_once 'model\familyMemberModel.php';
include_once 'model\contributionModel.php';

class Controller
{
    private $baseModel;
    private $familyModel;
    private $familyMemberModel;
    private $contributionModel;

    public function __construct()
    {
        $this->baseModel = new BaseModel();
        $this->familyModel = new FamilyModel();
        $this->familyMemberModel = new FamilyMemberModel();
        $this->contributionModel = new ContributionModel();
    }

    public function handleRequest()
    {
        if (isset($_POST['logout'])) {
            $this->baseModel->logout();
        }

        //Check of actie valt onder de categorie Family.
        if (isset($_POST['Family'])) {
            if (isset($_POST['manageFamilies'])) {
                //Controleer of gebruiker toegang heeft tot de pagina.
                $this->baseModel->CheckUserRole(3);
                $families = $this->familyModel->getFamilies();
                include('view\family\manageFamilies.php');
            } else if (isset($_POST['addFamily'])) {
                include('view\family\addFamily.php');
            } else if (isset($_POST['editFamily'])) {
                $family = $this->familyModel->getFamily();
                include('view\family\editFamily.php');
            } else if (isset($_POST['deleteFamilyMessage'])) {
                $familyID = $_POST['familyID'];
                include 'view\family\deleteFamily.php';
            }
            //CRUD operaties van acties die vallen onder de categorie Family
            else if (isset($_POST['createFamily'])) {
                echo $this->familyModel->createFamily();
                $families = $this->familyModel->getFamilies();
                include 'view\family\manageFamilies.php';
            } else if (isset($_POST['deleteFamily'])) {
                echo $this->familyModel->deleteFamily();
                $families = $this->familyModel->getFamilies();
                include 'view\family\manageFamilies.php';
            } else if (isset($_POST['updateFamily'])) {
                echo $this->familyModel->updateFamily();
                $families = $this->familyModel->getFamilies();
                include 'view\family\manageFamilies.php';
            }
        }

        //Check of actie valt onder de categorie FamilyMembers
        else if (isset($_POST['FamilyMember'])) {
            if (isset($_POST['manageFamilyMembers'])) {
                $familyMembers = $this->familyMemberModel->getFamilyMembers();
                include('view\familyMember\manageFamilyMembers.php');
            } else if (isset($_POST['addFamilyMember'])) {
                $familyID = $_POST['familyID'];
                include('view\familyMember\addFamilyMember.php');
            } else if (isset($_POST['editFamilyMember'])) {
                $familyMember = $this->familyMemberModel->getFamilyMember();
                include('view\familyMember\editFamilyMember.php');
            }
            //CRUDD operaties van acties die vallen onder de categorie FamilyMembers
            else if (isset($_POST['createFamilyMember'])) {
                echo $this->familyMemberModel->createFamilyMember();
                $familyMembers = $this->familyMemberModel->getFamilyMembers();
                include('view\familyMember\manageFamilyMembers.php');
            } else if (isset($_POST['deleteFamilyMember'])) {
                echo $this->familyMemberModel->deleteFamilyMember();
                $familyMembers = $this->familyMemberModel->getFamilyMembers();
                if ($familyMembers) {
                    include('view\familyMember\manageFamilyMembers.php');
                } else {
                    $families = $this->familyModel->getFamilies();
                    include 'view\family\manageFamilies.php';
                }
            } else if (isset($_POST['updateFamilyMember'])) {
                echo $this->familyMemberModel->updateFamilyMember();
                $familyMembers = $this->familyMemberModel->getFamilyMembers();
                include('view\familyMember\manageFamilyMembers.php');
            }
        }

        //Check of actie valt onder de categorie Contribution
        else if (isset($_POST['Contribution'])) {
            if (isset($_POST['manageContributions'])) {
                //Controleer of gebruiker toegang heeft tot de pagina.
                $this->baseModel->CheckUserRole(2);
                $financialYears = $this->contributionModel->getFinancialYears();
                $contributions = $this->contributionModel->getContributions();
                include('view\contribution\manageContributions.php');
            } else if (isset($_POST['addContribution'])) {
                include('view\contribution\addContribution.php');
            } else if (isset($_POST['editContribution'])) {
                $contribution = $this->contributionModel->getContribution();
                include('view\contribution\editContribution.php');
            }
            //CRUD operaties van acties die vallen onder de categorie Contribution
            else if (isset($_POST['createContribution'])) {
                echo $this->contributionModel->createContribution();
                $financialYears = $this->contributionModel->getFinancialYears();
                $contributions = $this->contributionModel->getContributions();
                include('view\contribution\manageContributions.php');
            } else if (isset($_POST['deleteContribution'])) {
                echo $this->contributionModel->deleteContribution();
                $financialYears = $this->contributionModel->getFinancialYears();
                $contributions = $this->contributionModel->getContributions();
                include('view\contribution\manageContributions.php');
            } else if (isset($_POST['updateContribution'])) {
                echo $this->contributionModel->updateContribution();
                $financialYears = $this->contributionModel->getFinancialYears();
                $contributions = $this->contributionModel->getContributions();
                include('view\contribution\manageContributions.php');
            }

            //Boekjaren
            else if (isset($_POST['addFinancialYear'])) {
                include('view\contribution\addFinancialYear.php');
            } else if (isset($_POST['editFinancialYear'])) {
                $financialYear = $this->contributionModel->getFinancialYear();
                include('view\contribution\editFinancialYear.php');
            } else if (isset($_POST['deleteFinancialYearMessage'])) {
                $financialYearID = $_POST['financialYearID'];
                include('view\contribution\deleteFinancialYear.php');
            }
            //CRUD operaties boekjaren
            else if (isset($_POST['createFinancialYear'])) {
                echo $this->contributionModel->createFinancialYear();
                $contributions = $this->contributionModel->getContributions();
                $financialYears = $this->contributionModel->getFinancialYears();
                include('view\contribution\manageContributions.php');
            } else if (isset($_POST['deleteFinancialYear'])) {
                echo $this->contributionModel->deleteFinancialYear();
                $contributions = $this->contributionModel->getContributions();
                $financialYears = $this->contributionModel->getFinancialYears();
                include('view\contribution\manageContributions.php');
            } else if (isset($_POST['updateFinancialYear'])) {
                echo $this->contributionModel->updateFinancialYear();
                $contributions = $this->contributionModel->getContributions();
                $financialYears = $this->contributionModel->getFinancialYears();
                include('view\contribution\manageContributions.php');
            }
        } else {
            $families = $this->familyModel->getFamilies();
            include 'view\dashboard.php';
        }
    }
}

$controller = new Controller();
$controller->handleRequest();
