<?php

$deprecated = empty($_REQUEST['deprecated']) ? $_SESSION['draft'] : $_REQUEST['deprecated'];
$draft = empty($_REQUEST['draft']) ? $_SESSION['draft'] : $_REQUEST['draft'];
$tid = empty($_REQUEST['tid']) ? "" : $_REQUEST['tid'];
$vid = empty($_REQUEST['vid']) ? "" : $_REQUEST['vid'];

if(!empty($_POST['deprecated'])){
    $_SESSION['deprecated'] = $_POST['deprecated'];
}

if(!empty($_POST['draft'])){
    $_SESSION['draft'] = $_POST['draft'];
}

if(empty($draft)){
    $draft = 'false';
}
if(empty($deprecated)){
    $deprecated = 'false';
}

#We get the Tables and Variables information
$dataTable = getTablesInfo(DES_DATAMODEL,$tid);
?>

<br/>
<br/>
<div class="container-fluid wiki">
    <div class='row' style=''>
            <?PHP foreach( $dataTable as $data ){
                   if(!empty($data['record_id'])) {?>
                    <div class="col-md-12">
                        <span class="wiki_title"><?PHP echo $data['table_name'];?></span>
                        <?php
                        if (array_key_exists('table_status', $data)) {
                            if ($data['table_status'] == "2") {
                                ?><span class="wiki_deprecated wiki_deprecated_draft_message">DEPRECATED</span><?php
                            }
                            if ($data['table_status'] == "0") {
                                ?><span class="wiki_draft wiki_deprecated_draft_message">DRAFT</span><?php
                            }
                        }
                        ?>
                    </div>
                    <div class="col-md-12 wiki_text wiki_text_size">
                        <span style="display:block;"><?PHP echo mb_convert_encoding($data['table_definition'],'UTF-8'); ?></span>
                        <span style="display:block;"><i><?PHP echo $data['text_top']; ?></i></span>
                    </div>

                    <div class="col-md-12">
                        <span class="wiki_title_small">Table status</span>
                    </div>
                    <div class="col-md-12 wiki_text wiki_text_size">
                        <?php
                            if (array_key_exists('table_status', $data)) {
                                if ($data['table_status'] == "0") {
                                    $date_d = "";
                                    if(array_key_exists('table_added_d', $data) && !empty($data['table_added_d'])){
                                        $date_d = "(created ".$data['table_added_d'].")";
                                    }
                                    ?><span style="display:block;">Draft <?=$date_d?></span><?php
                                }
                                if ($data['table_status'] == "1") {
                                    $date_d = "";
                                    if(array_key_exists('table_added_d', $data) && !empty($data['table_added_d'])){
                                        $date_d = "(as of ".$data['table_added_d'].")";
                                    }
                                    ?><span style="display:block;">Active <?=$date_d?></span><?php
                                }
                                if ($data['table_status'] == "2") {
                                    $date_d = "";
                                    if(array_key_exists('table_deprecated_d', $data) && !empty($data['table_deprecated_d'])){
                                        $date_d = "(as of ".$data['table_deprecated_d'].")";
                                    }
                                    ?><span style="display:block;">Deprecated <?=$date_d?></span><?php

                                }
                            }else if (empty($data['table_status'])) {
                                ?><span style="display:block;">Status Unknown</span><?php
                            }
                        ?>
                    </div>

                    <div class="col-md-12">
                        <?php include('options/options.php'); ?>
                        <br/>
                    </div>
                    <div class="col-md-12">
                        <div class="panel panel-default" >
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    Variables
                                </h3>
                            </div>
                            <div id="collapse3" class="table-responsive panel-collapse collapse in" aria-expanded="true">
                                <table class="table table_requests sortable-theme-bootstrap" data-sortable>
                                    <?php
                                    echo '<thead>'.'
                                    <tr>'.'
                                        <th>Field Name</th>'.'
                                        <th>Format</th>'.'
                                        <th>Description</th>'.'
                                    </tr>'.'
                                    </thead>';

                                    foreach ($data['variable_order'] as $id => $value) {
                                        $variable_display = "";
                                        $variable_text = "";
                                        if (array_key_exists('variable_status', $data) && array_key_exists($id, $data['variable_status'])) {
                                            if($data['variable_status'][$id] == "0"){//DRAFT
                                                if($draft == 'false') {//DEPRECATED
                                                    $variable_display = "display:none";
                                                }
                                                $variable_text = "<span class='wiki_draft'><strong>DRAFT</strong></span><br/>";
                                            }else if($data['variable_status'][$id] == "2"){
                                                if($deprecated == 'false') {//DEPRECATED
                                                    $variable_display = "display:none";
                                                }
                                                $variable_text = "<span class='wiki_deprecated'><strong>DEPRECATED</strong></span><br/>";
                                            }
                                        }

                                        $required_class = '';
                                        $required_text = '';
                                        if($data['variable_required'][$id][0] == '1'){
                                            $required_class = 'required_des';
                                            $required_text="<div style='color:red'>*Required</div>";
                                        }

                                        $record_var_aux = empty($id) ? '1' : $id;
                                        $record_var = $id;
                                        $name = $data['variable_name'][$id];
                                        $url = 'index.php?pid=' . DES_DATAMODEL . '&tid=' . $tid . '&vid=' . $record_var . '&page=variableInfo';
                                        echo '<tr class="'.$required_class.'" style="' . $variable_display . '"" id="'.$record_var_aux.'_row">' .
                                            '<td style="width:130px">' .
                                            '<a href="'.$url.'" onclick="addURL(\''.$url.'\', \'&deprecated=\'+$(\'#deprecated_info\').is(\':checked\')+\'&draft=\'+$(\'#draft_info\').is(\':checked\'));">' . $name . '</a>' .
                                            '</td>' .
                                            '<td style="width:350px">';

                                        $dataFormat = $dataTable['data_format_label'][$data['data_format'][$id]];
                                        if ($data['has_codes'][$id] != '1') {
                                            echo $dataFormat;
                                        } else if ($data['has_codes'][$id] == '1') {
                                            if(!empty($data['code_list_ref'][$id])){
                                                $codeformat = getProjectInfoArray(DES_CODELIST,array('record_id' => $data['code_list_ref'][$id]),'simple');

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
                                                    echo $dataFormat;

                                                } else if ($codeformat['code_format'] == '3') {
                                                    echo 'Numeric<br/>';

                                                    if (array_key_exists('code_file', $codeformat)) {
                                                        echo '<a href="#codesModal'.$codeformat['code_file'].'_'.$name.'" id="btnViewCodes" type="button" class="btn_code_modal open-codesModal" data-toggle="modal" data-target="#codesModal'.$codeformat['code_file'].'_'.$name.'">See Code List</a>';

                                                        #modal window with the updates
                                                        echo '<div class="modal fade" id="codesModal'.$codeformat['code_file'].'_'.$name.'" tabindex="-1" role="dialog" aria-labelledby="Codes">' .
                                                            '<div class="modal-dialog" role="document">' .
                                                            '<div class="modal-content">' .
                                                            '<div class="modal-header">' .
                                                            '<button type="button" class="close closeCustomModal" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' .
                                                            '<h4 class="modal-title">Codes</h4>'.
                                                            '</div>'.
                                                            '<div class="modal-body">'.
                                                            '<div class="row" style="padding:30px;">'.
                                                            '<div class="panel panel-default">'.
                                                            '<div class="panel-heading">'.$name.'</div>'.
                                                            '<div class="table-responsive panel-collapse collapse in">'.
                                                            '<table border="1" class="code_modal_table">';
                                                        $csv = parseCSVtoArray($codeformat['code_file']);
                                                        if(empty($csv)){
                                                            echo '<div style="text-align: center;color:red;">No Codes found for file:'.$codeformat['code_file'].'</div>';
                                                        }
                                                        foreach ($csv as $header=>$content){
                                                            if($header == 0){
                                                                echo '<tr>';
                                                            }else{
                                                                echo '<tr>';
                                                            }
                                                            foreach ($content as $col=>$value) {
                                                                //Convert to UTF-8 to avoid weird characters
                                                                $value = mb_convert_encoding($value,'UTF-8');
                                                                if($header == 0){
                                                                    echo '<td class="code_modal_td">'.$col.'</td>';
                                                                }else{
                                                                    echo '<td class="code_modal_td">'.$value.'</td>';
                                                                }
                                                            }
                                                            echo '</tr>';
                                                        }
                                                        echo '</table></div></div>'.
                                                            '</div>'.
                                                            '</div>'.
                                                            '<div class="modal-footer">' .
                                                            '<button type="button" class="btn btn-default" id="btnCloseCodesModal" data-dismiss="modal">CLOSE</button>' .
                                                            '</div>'.
                                                            '</div></div></div>';
                                                    }
                                                }
                                            } else {
                                                echo $dataFormat;
                                            }
                                        }
                                        echo '</td><td id="'.$record_var_aux.'_description">'.$required_text;
                                        echo $variable_text.mb_convert_encoding($data['description'][$id], 'UTF-8');
                                        if (!empty($data['description_extra'][$id])) {
                                            echo "<br/><i>" . $data['description_extra'][$id] . "</i>";
                                        }
                                        if (!empty($data['code_text'][$id])) {
                                            echo "<br/><i>" . $data['code_text'][$id] . "</i>";
                                        }
                                        echo '</td>';
                                        echo '</tr>';
                                    }
                                    ?>
                                </table>
                            </div>
                        </div>
                    </div>
                <?PHP }

            }?>
    </div>
</div>