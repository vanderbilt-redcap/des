<?php
#Harmonist -1: Projects
define('PROD_DES_PROJECTS', 71550);
define('TEST_DES_PROJECTS', 1202);
define('DEV_DES_PROJECTS', 113);

if(defined(ENVIRONMENT."_DES_PROJECTS")) {
    define("DES_PROJECTS", constant(ENVIRONMENT."_DES_PROJECTS"));
}

# Define the projects stored in DES_PROJECTS
$projectProjects = new \Plugin\Project(DES_PROJECTS);
$RecordSetProjects = new \Plugin\RecordSet($projectProjects, array(\Plugin\RecordSet::getKeyComparatorPair($projectProjects->getFirstFieldName(),"!=") => ""));
$projects = $RecordSetProjects->getDetails();
$linkedProjects = array();

foreach ($projects as $project){
    define('PROD_DES_'.$project['project_constant'],$project['project_prod_id']);
    define('TEST_DES_'.$project['project_constant'],$project['project_test_id']);
    define('DEV_DES_'.$project['project_constant'],$project['project_dev_id']);
    array_push($linkedProjects,"DES_".$project['project_constant']);
}

# Define the environment for each project
foreach($linkedProjects as $projectTitle) {
    if(defined(ENVIRONMENT."_".$projectTitle)) {
        define($projectTitle, constant(ENVIRONMENT."_".$projectTitle));
    }
}