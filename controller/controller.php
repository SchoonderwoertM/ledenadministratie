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
                $families = $this->familyModel->GetFamilies();
                include('view\family\manageFamilies.php');
            } else if (isset($_POST['addFamily'])) {
                include('view\family\addFamily.php');
            } else if (isset($_POST['editFamily'])) {
                $family = $this->familyModel->GetFamily();
                include('view\family\editFamily.php');
            } else if (isset($_POST['deleteFamilyMessage'])) {
                $familyID = $_POST['familyID'];
                include 'view\family\deleteFamily.php';
            }
            //CRUD operaties van acties die vallen onder de categorie Family
            else if (isset($_POST['createFamily'])) {
                echo $this->familyModel->CreateFamily();
                $families = $this->familyModel->GetFamilies();
                include 'view\family\manageFamilies.php';
            } else if (isset($_POST['deleteFamily'])) {
                echo $this->familyModel->DeleteFamily();
                $families = $this->familyModel->GetFamilies();
                include 'view\family\manageFamilies.php';
            } else if (isset($_POST['updateFamily'])) {
                echo $this->familyModel->UpdateFamily();
                $families = $this->familyModel->GetFamilies();
                include 'view\family\manageFamilies.php';
            }
        }

        //Check of actie valt onder de categorie FamilyMembers
        else if (isset($_POST['FamilyMember'])) {
            if (isset($_POST['manageFamilyMembers'])) {
                $familyMembers = $this->familyMemberModel->GetFamilyMembers();
                include('view\familyMember\manageFamilyMembers.php');
            } else if (isset($_POST['addFamilyMember'])) {
                $familyID = $_POST['familyID'];
                include('view\familyMember\addFamilyMember.php');
            } else if (isset($_POST['editFamilyMember'])) {
                $familyMember = $this->familyMemberModel->GetFamilyMember();
                include('view\familyMember\editFamilyMember.php');
            }
            //CRUDD operaties van acties die vallen onder de categorie FamilyMembers
            else if (isset($_POST['createFamilyMember'])) {
                echo $this->familyMemberModel->CreateFamilyMember();
                $familyMembers = $this->familyMemberModel->GetFamilyMembers();
                include('view\familyMember\manageFamilyMembers.php');
            } else if (isset($_POST['deleteFamilyMember'])) {
                echo $this->familyMemberModel->DeleteFamilyMember();
                $familyMembers = $this->familyMemberModel->GetFamilyMembers();
                if ($familyMembers) {
                    include('view\familyMember\manageFamilyMembers.php');
                } else {
                    $families = $this->familyModel->GetFamilies();
                    include 'view\family\manageFamilies.php';
                }
            } else if (isset($_POST['updateFamilyMember'])) {
                echo $this->familyMemberModel->UpdateFamilyMember();
                $familyMembers = $this->familyMemberModel->GetFamilyMembers();
                include('view\familyMember\manageFamilyMembers.php');
            }
        }

        //Check of actie valt onder de categorie Contribution
        else if (isset($_POST['Contribution'])) {
            if (isset($_POST['manageContributions'])) {
                //Controleer of gebruiker toegang heeft tot de pagina.
                $this->baseModel->CheckUserRole(2);
                $financialYears = $this->contributionModel->GetFinancialYears();
                $memberships = $this->contributionModel->GetMemberships();
                include('view\contribution\manageContributions.php');
            } else if (isset($_POST['addMembership'])) {
                include('view\contribution\addMembership.php');
            } else if (isset($_POST['editMembership'])) {
                $membership = $this->contributionModel->GetMembership();
                include('view\contribution\editMembership.php');
            }
            else if(isset($_POST['recalculateMemberships'])){
                echo $this->contributionModel->RecalculateMemberships();
                $financialYears = $this->contributionModel->GetFinancialYears();
                $memberships = $this->contributionModel->GetMemberships();
                include('view\contribution\manageContributions.php');
            }
            //CRUD operaties van acties die vallen onder de categorie Contribution
            else if (isset($_POST['createMembership'])) {
                echo $this->contributionModel->CreateMembership();
                $financialYears = $this->contributionModel->GetFinancialYears();
                $memberships = $this->contributionModel->GetMemberships();
                include('view\contribution\manageContributions.php');
            } else if (isset($_POST['deleteMembership'])) {
                echo $this->contributionModel->DeleteMembership();
                $financialYears = $this->contributionModel->GetFinancialYears();
                $memberships = $this->contributionModel->GetMemberships();
                include('view\contribution\manageContributions.php');
            } else if (isset($_POST['updateMembership'])) {
                echo $this->contributionModel->UpdateMembership();
                $financialYears = $this->contributionModel->GetFinancialYears();
                $memberships = $this->contributionModel->GetMemberships();
                include('view\contribution\manageContributions.php');
            }

            //Boekjaren
            else if (isset($_POST['addFinancialYear'])) {
                include('view\contribution\addFinancialYear.php');
            } else if (isset($_POST['editFinancialYear'])) {
                $financialYear = $this->contributionModel->GetFinancialYear();
                include('view\contribution\editFinancialYear.php');
            } else if (isset($_POST['deleteFinancialYearMessage'])) {
                $financialYearID = $_POST['financialYearID'];
                include('view\contribution\deleteFinancialYear.php');
            }
            //CRUD operaties boekjaren
            else if (isset($_POST['createFinancialYear'])) {
                echo $this->contributionModel->CreateFinancialYear();
                $memberships = $this->contributionModel->GetMemberships();
                $financialYears = $this->contributionModel->GetFinancialYears();
                include('view\contribution\manageContributions.php');
            } else if (isset($_POST['deleteFinancialYear'])) {
                echo $this->contributionModel->DeleteFinancialYear();
                $memberships = $this->contributionModel->GetMemberships();
                $financialYears = $this->contributionModel->GetFinancialYears();
                include('view\contribution\manageContributions.php');
            } else if (isset($_POST['updateFinancialYear'])) {
                echo $this->contributionModel->UpdateFinancialYear();
                $memberships = $this->contributionModel->GetMemberships();
                $financialYears = $this->contributionModel->GetFinancialYears();
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
