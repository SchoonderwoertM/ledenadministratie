<?php
require_once 'model\familyModel.php';
require_once 'model\familyMemberModel.php';
require_once 'model\contributionModel.php';
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
        if(isset($_POST['Family']))
        {
            if (isset($_POST['manageFamilies'])) {
                $families = $this->familyModel->getFamilies();
                include('view\family\families.php');
            } 
            else if (isset($_POST['addFamily'])) {
                include('view\family\addFamily.php');
            }
            else if (isset($_POST['editFamily'])) {
                $family = $this->familyModel->getFamily();
                include('view\family\editFamily.php');
            }
            //CRUDD
            else if (isset($_POST['createFamily'])) {
            } 
            else if (isset($_POST['deleteFamily'])) {
            } 
            else if (isset($_POST['updateFamily'])) {
            }
        }

        //FamilyMembers
        else if (isset($_POST['FamilyMembers'])){
            if (isset($_POST['manageFamilyMembers'])){
                $familieMember = $this->familyMemberModel->getFamilyMembers();
                include('view\family\families.php');
            }
            else if (isset($_POST['addFamilyMember'])) {
                include('view\family\addFamilyMember.php');
            }
            else if (isset($_POST['editFamilyMember'])) {
                $familyMember = $this->familyMemberModel->getFamilyMember();
                include('view\family\editFamily.php');}
            //CRUDD
            else if (isset($_POST['createFamilyMember'])) {
            } 
            else if (isset($_POST['deleteFamilyMember'])) {
            } 
            else if (isset($_POST['updateFamilyMember'])) {
            }
        }

        //Contributions
        else if(isset($_POST['Contribution']))
        {
            if (isset($_POST['manageContributions'])) {
                $contributions = $this->contributionModel->getContributions();
                $_SESSION['contributions'] = $contributions;
                include('view\contribution\contributions.php');
            } 
            else if (isset($_POST['addContribution'])) {
                include('view\contribution\addContribution.php');
            } 
            else if (isset($_POST['editContribution'])){
                $contribution = $this->contributionModel->getContribution();
                include('view\contribution\editContribution.php');
            }
            //CRUDD
            else if (isset($_POST['createContribution'])) {
            }
            else if (isset($_POST['deleteContribution'])) {
                $this->contributionModel->deleteContribution();
            } 
            else if (isset($_POST['updateContribution'])) {
                $this->contributionModel->updateContribution();
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
