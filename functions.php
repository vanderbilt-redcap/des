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


/**
 * Function that creates HTML tables with the Tables and Variables information to print on the PDF after the information has been selected
 * @param $dataTable, Tables and Variables information
 * @param $fieldsSelected, the selected fields
 * @return string, the html content
 */
function generateTablesHTML_pdf($dataTable,$mode){
    $tableHtml = "";
    $table_counter = 0;
    foreach ($dataTable as $data) {
        if (!empty($data['record_id']) && (($mode == '1' && ($data['table_status'] != "0" && $data['table_status'] != "2")) || $mode == '0')) {
            $found = false;
            $htmlCodes = '';
            foreach ($data['variable_order'] as $id=>$value) {
                    $record_varname = !array_key_exists($id,$data['variable_name'])?$data['variable_name']['']:$data['variable_name'][$id];
                    $record_varname_id = empty($id) ? $data['record_id'] . '_1' : $data['record_id'] . '_' . $id;
                    #We add the new Header table tags
                    if($found == false){
                        $table_draft= "background-color: #f0f0f5";
                        $table_draft_tdcolor= "background-color: lightgray";
                        $table_draft_text= "";

                        switch ($data['table_category']){
                            case 'main': $table_draft = "background-color: #FFC000"; break;
                            case 'labs': $table_draft = "background-color: #9cce77"; break;
                            case 'dis': $table_draft = "background-color: #87C1E9"; break;
                            case 'meds': $table_draft = "background-color: #FB8153"; break;
                            case 'preg': $table_draft = "background-color: #D7AEFF"; break;
                            case 'meta': $table_draft = "background-color: #BEBEBE"; break;
                            default:$table_draft = "background-color: #f0f0f5"; break;
                        }
                        if (array_key_exists('table_status', $data) ) {
                            if($data['table_status'] == 0){
                                $table_draft = "background-color: #ffffcc;";
                            }
                            $table_draft_tdcolor = ($data['table_status'] == 0) ? "background-color: #999999;" : "background-color: lightgray";
                            $table_draft_text = ($data['table_status'] == 0) ?'<span style="color: red;font-style: italic">(DRAFT)</span>':"";
                        }

                        $breakLine = '';
                        if($table_counter >0){
                            $breakLine = '<div style="page-break-before: always;"></div>';
                        }
                        $table_counter++;

                        $htmlHeader = $breakLine.'<p style="'.$table_draft.'"><span style="font-size:16px"><strong><a href="'.APP_PATH_WEBROOT_FULL.'/plugins/iedea/des/index.php?tid='.$data['record_id'].'&page=variables" name="anchor_'.$data['record_id'].'" target="_blank" style="text-decoration:none">'.$data["table_name"].'</a></span> '.$table_draft_text.'</strong> - '.$data['table_definition'].'</p>';
                        if (array_key_exists('text_top', $data) && !empty($data['text_top']) && $data['text_top'] != ""){
                            $htmlHeader .= '<div  style="border-color: white;font-style: italic">'.$data["text_top"].'</div>';
                        }
                        $htmlHeader .= '<table border ="1px" style="border-collapse: collapse;width: 100%;">
                        <tr style="'.$table_draft_tdcolor.'">
                            <td style="padding: 5px;width:30%">Field</td>
                            <td style="padding: 5px">Format</td>
                            <td style="padding: 5px">Description</td>
                        </tr>';
                        $found = true;
                        $tableHtml .= $htmlHeader;
                    }

                    if(($mode == '1' && ($data['variable_status'][$id] != "0" && $data['variable_status'][$id] != "2")) || $mode == '0') {
                        $variable_status = "";
                        $variable_text = "";
                        if (array_key_exists('variable_status', $data) && array_key_exists($id, $data['variable_status'])) {
                            if ($data['variable_status'][$id] == "0") {//DRAFT
                                $variable_status = "style='background-color: #ffffe6;'";
                                $variable_text = "<span style='color:red;font-weight:bold'>DRAFT</span><br/>";
                            } else if ($data['variable_status'][$id] == "2") {//DEPRECATED
                                $variable_status = "style=''";
                                $variable_text = "<span style='color:red;font-weight:bold'>DEPRECATED</span><br/>";
                            }
                        }

                        #We add the Content rows
                        $tableHtml .= '<tr record_id="' . $record_varname_id . '" ' . $variable_status . '>
                                <td style="padding: 5px"><a href="'.APP_PATH_WEBROOT_FULL.'/plugins/iedea/des/index.php?tid=' . $data['record_id'] . '&vid=' . $id . '&page=variableInfo" target="_blank" style="text-decoration:none">' . $record_varname . '</a></td>
                                <td style="width:160px;padding: 5px">';

                        $dataFormat = $dataTable['data_format_label'][$data['data_format'][$id]];
                        if ($data['has_codes'][$id] == '0') {
                            if (!empty($data['code_text'][$id])) {
                                $dataFormat .= "<br/>" . $data['code_text'][$id];
                            }
                        } else if ($data['has_codes'][$id] == '1') {
                            if (!empty($data['code_list_ref'][$id])) {
                                $codeformat = getProjectInfoArray(DES_CODELIST, array('record_id' => $data['code_list_ref'][$id]), 'simple');

                                if ($codeformat['code_format'] == '1') {
                                    $codeOptions = empty($codeformat['code_list']) ? $data['code_text'][$id] : explode(" | ", $codeformat['code_list']);
                                    if (!empty($codeOptions[0])) {
                                        $dataFormat .= "<div style='padding-left:15px'>";
                                    }
                                    foreach ($codeOptions as $option) {
                                        $dataFormat .= $option . "<br/>";
                                    }
                                    if (!empty($codeOptions[0])) {
                                        $dataFormat .= "</div>";
                                    }
                                } else if ($codeformat['code_format'] == '3') {
                                    $dataFormat = "Numeric<br/>";
                                    if (array_key_exists('code_file', $codeformat) && $data['codes_print'][$id] == '1') {
                                        $htmlCodes .= "<table  border ='0' style='width: 100%;display:none' record_id='" . $record_varname . "'><tr><td><strong>" . $data['variable_name'][$id] . " code list:</strong><br/></td></tr></table>" . getHtmlCodesTable($codeformat['code_file'], $htmlCodes, $record_varname);
                                    }
                                }
                            }
                        }

                        $description = empty($data["description"][$id]) ? $data["description"][''] : $data["description"][$id];
                        if (!empty($data['description_extra'][$id])) {
                            $description .= "<br/><i>" . $data['description_extra'][$id] . "</i>";
                        }

                        $tableHtml .= $dataFormat . '</td><td style="padding: 5px">' . $variable_text . $description . '</td></tr>';
                    }

            }
            if($found) {
                $tableHtml .= "</table><br/>";
                if (array_key_exists('text_bottom', $data) && !empty($data['text_bottom']) && $data['text_bottom'] != ""){
                    $tableHtml .= '<p  style="border-color: white;font-style: italic">'.$data["text_bottom"].'</p><br/>';
                }
            }
            if(!empty($htmlCodes))
                $tableHtml .= $htmlCodes.'<br/>';
        }
    }
    return $tableHtml;
}


/**
 * Function that parses the CVS file and transforms the content into a table
 * @param $code_file, the code in the db of the csv file
 * @param $htmlCodes, the html table with the content
 * @return string, the html table with the content
 */
function getHtmlCodesTable($code_file,$htmlCodes,$id){
    $csv = parseCSVtoArray($code_file);
    if(!empty($csv)) {
        $htmlCodes = '<table border="1px" style="border-collapse: collapse;display:none;" record_id="'. $id .'">';
        foreach ($csv as $header => $content) {
            $htmlCodes .= '<tr style="border: 1px solid #000;">';
            foreach ($content as $col => $value) {
                #Convert to UTF-8 to avoid weird characters
                $value = mb_convert_encoding($value,'UTF-8');
                if ($header == 0) {
                    $htmlCodes .= '<td>' . $col . '</td>';
                } else {
                    $htmlCodes .= '<td>' . $value . '</td>';
                }
            }
            $htmlCodes .= '</tr>';
        }
        $htmlCodes .= '</table>';
    }
    return $htmlCodes;
}


/**
 * Table list with anchor links for the PDF
 * @param $dataTable
 * @return string
 */
function generateRequestedTablesList_pdf($dataTable,$mode){
    $requested_tables = "<ol>";
    foreach ($dataTable as $data) {
        if (!empty($data['record_id']) && (($mode == '1' && ($data['table_status'] != "0" && $data['table_status'] != "2")) || $mode == '0')) {
            $requested_tables .= "<li><a href='#anchor_" . $data['record_id'] . "'>" . $data["table_name"] . "</a></li>";
        }
    }
    $requested_tables .= "</ol>";
    return $requested_tables;
}

function getImageToDisplay($edoc){
    $img_logo = '';
    if($edoc != ''){
        $sql = "SELECT stored_name,doc_name,doc_size FROM redcap_edocs_metadata WHERE doc_id=" . $edoc;
        $q = db_query($sql);

        if ($error = db_error()) {
            die($sql . ': ' . $error);
        }

        while ($row = db_fetch_assoc($q)) {
            $img_logo = 'options/downloadFile.php?sname=' . $row['stored_name'] . '&file=' . $row['doc_name'];
        }
    }

    return $img_logo;
}
?>