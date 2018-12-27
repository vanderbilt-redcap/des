<?php

/**
 * Function that returns the info array from a specific project
 * @param $project, the project id
 * @param $info_array, array that contains the conditionals
 * @param string $type, if its single or a multidimensional array
 * @return array, the info array
 */
function getProjectInfoArray($project, $info_array="", $type="", $fieldName="", $order=false){
    $Project = new \Plugin\Project($project);
    if(empty($info_array)){
        $RecordSet = new \Plugin\RecordSet($Project, array(\Plugin\RecordSet::getKeyComparatorPair($Project->getFirstFieldName(),"!=") => ""));
    }else{
        $RecordSet = new \Plugin\RecordSet($Project, $info_array);
    }

    if(!empty($fieldName)) {
        #Order by fieldname
        $RecordSet->mergeSortRecords($fieldName,$order);
    }

    if($type == "simple"){
        $wgroup = $RecordSet->getDetails()[0];
    }else{
        $wgroup = $RecordSet->getDetails();
    }

    return $wgroup;
}

/**
 * Function that searches the armID from a project and returns the data
 * @param $projectID
 * @return array|mixed
 */
function getTablesInfo($projectID, $tableID="", $tableOrderParam="table_order"){
    $sql = "SELECT * FROM `redcap_events_arms` WHERE project_id =".$projectID;
    $q = db_query($sql);

    $dataTable = array();
    while ($row = db_fetch_assoc($q)){
        $sqlTable = "SELECT * FROM `redcap_events_metadata` WHERE arm_id =".$row['arm_id'];
        $qTable = db_query($sqlTable);
        while ($rowTable = db_fetch_assoc($qTable)){
            $dataTable = generateTableArray($rowTable['event_id'], $projectID,$dataTable,$tableID,$tableOrderParam);
        }
    }
    return $dataTable;
}

/**
 * Function that generates an array with the table name and event information
 * @param $event_id, the event identificator
 * @param $projectID, the project we want to search in
 * @param $dataTable, the array we are going to fill up
 * @return mixed, the array $dataTable we are going to fill up
 */
function generateTableArray($event_id, $projectID, $dataTable,$tableID,$tableOrderParam){
    $ProjectTable = new \Plugin\Project($projectID, $event_id);
    $dataFormat = \Plugin\Project::convertEnumToArray($ProjectTable->getMetadata('data_format')->getElementEnum());
    if(empty($tableID)){
        $RecordSetTable= new \Plugin\RecordSet($ProjectTable,array(\Plugin\RecordSet::getKeyComparatorPair($ProjectTable->getFirstFieldName(),"!=") => ""));//gives you a record set for all records in project
    }else{
        $RecordSetTable= new \Plugin\RecordSet($ProjectTable,array('record_id' => $tableID));
    }
    $recordsTable = $RecordSetTable->getRecords();
    $dataTable['data_format_label'] = $dataFormat;
    foreach( $recordsTable as $record ){
        $data = $record->getDetails();

        #we sort the variables by value and keep key
        asort($data['variable_order']);

        if(!empty($data['record_id'])){//Variables
            $dataTable[$data['record_id']] = $data;
        }
    }
    #We order the tables
    array_sort_by_column($dataTable, $tableOrderParam);

    return $dataTable;
}

function array_sort_by_column(&$arr, $col, $dir = SORT_ASC) {
    $sort_col = array();
    foreach ($arr as $key=> $row) {
        $sort_col[$key] = $row[$col];
    }
    array_multisort($sort_col, $dir, $arr);
}

?>