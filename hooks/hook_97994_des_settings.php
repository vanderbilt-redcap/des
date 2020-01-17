<?php
require_once dirname(dirname(__FILE__))."/base.php";
require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';

$sql = "SELECT ts,data_values FROM `redcap_log_event` where project_id='".db_escape(DES_SETTINGS)."' AND pk='".db_escape("1")."' AND data_values like '".db_escape("des_pdf_text = %")."' ORDER BY `log_event_id` DESC";
$q = db_query($sql);

$old_text = "";
$new_text = "";
$count = 0;
$new_date = "";
while ($row = db_fetch_assoc($q)){
    if ($count == 0){
        $data = explode("=",$row['data_values']);
        $new_text = trim(str_replace("'", "", $data[1]));
        $new_date = date("Y-m-d",strtotime($row['ts']));
    }else if ($count == 1){
        $data = explode("=",$row['data_values']);
        $old_text = trim(str_replace("'", "", $data[1]));
    }
    $count++;
}

if($old_text != $new_text && strtotime($new_date) == strtotime(date("Y-m-d"))){
    createAndSavePDFCron($settings,$secret_key,$secret_iv);
}

?>