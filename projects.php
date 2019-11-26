<?php


if(defined(ENVIRONMENT."_DES_PROJECTS")) {
    define("DES_PROJECTS", constant(ENVIRONMENT."_DES_PROJECTS"));
}

# Define the projects stored in DES_PROJECTS
$projectProjects = new \Plugin\Project(DES_PROJECTS);
$RecordSetProjects = new \Plugin\RecordSet($projectProjects, array(\Plugin\RecordSet::getKeyComparatorPair($projectProjects->getFirstFieldName(),"!=") => ""));
$projects = $RecordSetProjects->getDetails();
$linkedProjects = array();

foreach ($projects as $project){
    define(ENVIRONMENT . '_DES_' . $project['project_constant'], $project['project_id']);
    array_push($linkedProjects,"DES_".$project['project_constant']);
}

# Define the environment for each project
foreach($linkedProjects as $projectTitle) {
    if(defined(ENVIRONMENT."_".$projectTitle)) {
        define($projectTitle, constant(ENVIRONMENT."_".$projectTitle));
    }
}