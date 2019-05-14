<?php
#this avoids asking to log in in RedCap
define('NOAUTH',true);
require_once "../base.php";
session_start();

$deprecated = empty($_POST['deprecated']) ? $_SESSION['draft'] : $_POST['deprecated'];
$draft = empty($_POST['draft']) ? $_SESSION['draft'] : $_POST['draft'];
$tid = empty($_REQUEST['tid']) ? "" : $_REQUEST['tid'];
$vid = empty($_REQUEST['vid']) ? "" : $_REQUEST['vid'];
$result = array();
$result['html'] = '';
$result['variablesInfo'] = '';

if(!empty($_POST['deprecated'])){
    $_SESSION['deprecated'] = $_POST['deprecated'];
}

if(!empty($_POST['draft'])){
    $_SESSION['draft'] = $_POST['draft'];
}

#Tables ordered alphabetically by table name
$dataTable = getTablesInfo(DES_DATAMODEL,$tid,"table_name");

foreach( $dataTable as $data ){
    if(!empty($data['record_id'])) {
        //TABLES
        if(empty($tid)){
            $variable_display = "";
            $variable_text = "";
            if (array_key_exists('table_status', $data)) {
                if($data['table_status'] == "0"){//DRAFT
                    if($draft == 'false') {
                        $variable_display = "none";
                    }
                    $result['variablesInfo'][$data['record_id']]['display']= $variable_display;
                }else if($data['table_status'] == "2"){
                    if($deprecated == 'false') {//DEPRECATED
                        $variable_display = "none";
                    }
                    $result['variablesInfo'][$data['record_id']]['display']= $variable_display;
                }
            }

        }else if($_POST['page'] === 'variables' ){
        //VARIABLES
            foreach ($data['variable_order'] as $id => $value) {
            $record_var = empty($id) ? '1' : $id;
                $variable_display = "";
                $variable_text = "";
                if (array_key_exists('variable_status', $data) && array_key_exists($id, $data['variable_status'])) {
                    if($data['variable_status'][$id] == "0"){//DRAFT
                        if($draft == 'false') {//DEPRECATED
                            $variable_display = "none";
                        }
                        $variable_text = "<span class='wiki_draft'><strong>DRAFT</strong></span><br/>";
                        $result['variablesInfo'][$record_var]['display']= $variable_display;
                    }else if($data['variable_status'][$id] == "2"){
                        if($deprecated == 'false') {//DEPRECATED
                            $variable_display = "none";
                        }
                        $variable_text = "<span class='wiki_deprecated'><strong>DEPRECATED</strong></span><br/>";
                        $result['variablesInfo'][$record_var]['display']= $variable_display;
                    }
                }
                $result['variablesInfo'][$record_var]['description']= $variable_text.mb_convert_encoding($data['description'][$id], 'UTF-8');

                if (!empty($data['description_extra'][$id])) {
                    $result['variablesInfo'][$record_var]['description'] .= $data['description_extra'][$id];
                }
                if (!empty($data['code_text'][$id])) {
                    $result['variablesInfo'][$record_var]['description'] .= "<br/><i>" . $data['code_text'][$id] . "</i>";
                }
            }
        }
    }
}

echo json_encode($result);

?>