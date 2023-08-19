<?php
require_once 'model/familyModel.php';
require_once 'model/contributionModel.php';
session_start();

class Controller
{
    private $familyModel;
    private $contributionModel;

    public function __construct()
    {
        $this->familyModel = new FamilyModel();
        $this->contributionModel = new ContributionModel();
    }

    public function handleRequest()
    {
        //Families
        if(isset($_POST['Family']))
        {
            if (isset($_POST['manageFamilies'])) {
                $families = $this->familyModel->getFamilies();
                include('view/family/families.php');
            } 
            else if (isset($_POST['addFamilies'])) {
            }
            //CRUDD
            else if (isset($_POST['createFamilie'])) {
            } 
            else if (isset($_POST['deleteFamilie'])) {
            } 
            else if (isset($_POST['updateFamilie'])) {
            }
        }

        //Contributions
        else if(isset($_POST['Contribution']))
        {
            if (isset($_POST['manageContributions'])) {
                $contributions = $this->contributionModel->getContributions();
                $_SESSION['contributions'] = $contributions;
                include('view/contribution/contributions.php');
            } 
            else if (isset($_POST['addContribution'])) {
                include('view/contribution/addContribution.php');
            } 
            //CRUDD
            else if (isset($_POST['createContribution'])) {
            }
            else if (isset($_POST['deleteContribution'])) {
            } 
            else if (isset($_POST['updateContribution'])) {
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
