<?php

#We get the Tables and Variables information
$tid = $_REQUEST['tid'];
$vid = $_REQUEST['vid'];

$dataTable = getTablesInfo(DES_DATAMODEL,$tid);
?>

<br/>
<div class="container-fluid">
    <div class='row' style=''>
        <?PHP foreach( $dataTable as $data ){
            if(!empty($data['record_id'])) {
                $url = "index.php?pid=".DES_DATAMODEL."&tid=".$tid."&vid=".$record_var."&page=variables";
                ?>
                <div class="col-md-12">
                    <span class="wiki_title"><?PHP echo $data['variable_name'][$vid];?></span>
                    <?php
                    if (array_key_exists('variable_status', $data) && array_key_exists($vid, $data['variable_status'])) {
                        if ($data['variable_status'][$vid] == "2") {
                            ?><span class="wiki_deprecated wiki_deprecated_draft_message"><em class='fa fa-exclamation-circle'></em> DEPRECATED</span><?php
                        }
                        if ($data['variable_status'][$vid] == "0") {
                            ?><span class="wiki_draft wiki_deprecated_draft_message"><em class='fa fa-clock-o'></em> DRAFT</span><?php
                        }
                    }
                    ?>
                </div>
                <div class="col-md-12 wiki_text wiki_text_size">
                    <span style="display:block;"><?PHP echo $data['description'][$vid]; ?></span>
                    <?php if (!empty($data['description_extra'][$vid])) {
                        ?><span style="display:block;"><i><?PHP echo $data['description_extra'][$vid]; ?></i></span><?php
                    }?>
                </div>

                <div class="col-md-12">
                    <span class="wiki_title_small">Format</span>
                    <div class="wiki_text_inside wiki_text_size">
                    <?php
                        $codeTable = "";
                        $dataFormat = $dataTable['data_format_label'][$data['data_format'][$vid]];
                        if ($data['has_codes'][$vid] == '0') {
                            echo $dataFormat;
                            if (!empty($data['code_text'][$vid])) {
                                echo "<br/>".$data['code_text'][$vid];
                            }
                        } else if ($data['has_codes'][$vid] == '1') {
                            if(!empty($data['code_list_ref'][$vid])){
                                $codeformat = getProjectInfoArray(DES_CODELIST,array('record_id' => $data['code_list_ref'][$vid]),'simple');

                                if ($codeformat['code_format'] == '1') {
                                    $dataFormat .= " <span><i>(coded)</i></span><br/><br/>";

                                    $codeOptions = empty($codeformat['code_list']) ? $data['code_text'][$vid] : explode(" | ", $codeformat['code_list']);

                                    if (!empty($codeOptions[0])) {
                                        $dataFormat .= "<div class='wiki'><div class='panel panel-default'><table border='1' class='table table-bordered table-hover' style='font-size: 13px;'><div class='table-responsive panel-collapse collapse in'>";
                                        $dataFormat .= "<tbody><tr><td class=''>Code</td>";
                                        $dataFormat .= "<td class=''>Definition</td></tr>";
                                    }
                                    foreach ($codeOptions as $option) {
                                        list($key, $val) = explode("=", $option);
                                        $dataFormat .= "<tr><td style='text-align: center;'>".trim($key)."</td><td>".trim($val)."</td></tr>";
                                    }
                                    if (!empty($codeOptions[0])) {
                                        $dataFormat .= "</tbody></table></div></div></div>";
                                    }

                                    echo $dataFormat;

                                } else if ($codeformat['code_format'] == '3') {
                                    echo "Numeric <span><i>(coded)</i></span>";
                                    if (array_key_exists('code_file', $codeformat)) {
                                        $codeTable = "true";
                                    }
                                } else {
                                    echo $dataFormat." <span><i>(coded)</i></span>";
                                }
                            }
                        }
                    ?>
                    </div>
                </div>

                <div class="col-md-12">
                    <span class="wiki_title_small">Variable status</span>
                    <div class="wiki_text_inside wiki_text_size">
                        <?php if($data['variable_required'][$vid][0] == '1'){
                            ?><span style="display:block;">Required</span><?php
                        }
                        if (array_key_exists('variable_status', $data) && array_key_exists($vid, $data['variable_status'])) {
                            if ($data['variable_status'][$vid] == "0") {
                                $date_d = "";
                                if(array_key_exists('variable_added_d', $data) && !empty($data['variable_added_d'][$vid])){
                                    $date_d = "(".$data['variable_added_d'][$vid].")";
                                }
                                ?><span style="display:block;">Draft <?=$date_d?></span><?php
                            }
                            if ($data['variable_status'][$vid] == "1") {
                                $date_d = "";
                                if(array_key_exists('variable_added_d', $data) && !empty($data['variable_added_d'][$vid])){
                                    $date_d = "(".$data['variable_added_d'][$vid].")";
                                }
                                ?><span style="display:block;">Active <?=$date_d?></span><?php
                            }
                            if ($data['variable_status'][$vid] == "2") {
                                $date_d = "";
                                if(array_key_exists('variable_deprecated_d', $data) && !empty($data['variable_deprecated_d'][$vid])){
                                    $date_d = "(".$data['variable_deprecated_d'][$vid].")";
                                }
                                ?><span style="display:block;">Deprecated <?=$date_d?></span><?php

                            }
                        }else if (empty($data['variable_status'])) {
                            ?><span style="display:block;">Status Unknown</span><?php
                        }
                        ?>
                    </div>
                </div>

                <?php if(!empty($codeTable)){?>
                    <div class="col-md-12">
                        <span class="wiki_title_small">Code list</span>
                        <div class="wiki_text_inside wiki_text_size">
                            <span style="display:block;"><?PHP echo $data['variable_name'][$vid];?> codes ( <img src="img/download-arrow.png" width="15px" alt="arrow"/> <a href="options/downloadFile.php?<?= parseCSVtoLink($codeformat['code_file']);?>" target="_blank">Download CSV</a> )</span>
                            <?php if(!empty($codeformat) && array_key_exists('codelist_update_d', $codeformat) && !empty($codeformat['codelist_update_d'])) {
                                ?><span style="display:block;"><i>Last code list update: <?=$codeformat['codelist_update_d']?></i></span><?php
                            }else{
                                ?><span style="display:block;"><i>Unknown Date</i></span><?php
                            }
                            ?>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="wiki wiki_text_inside" style="padding-top: 0;">
                            <div class="panel panel-default">
                                <div class="panel-heading"><?PHP echo $data['variable_name'][$vid];?></div>
                                <div class="table-responsive panel-collapse collapse in">
                                    <table class="table table-bordered table-hover code_modal_table">
                                        <?PHP if(!empty($codeTable)){ ?>
                                            <?PHP
                                            $csv = parseCSVtoArray($codeformat['code_file']);
                                            if(empty($csv)){
                                                ?><div style="text-align: center;color:red;">No Codes found for file: <?=$codeformat['code_file']?></div><?PHP
                                            }
                                            foreach ($csv as $header=>$content){
                                                ?><tr><?PHP
                                                $counter = 1;
                                                foreach ($content as $col=>$value) {
                                                    $style = "";
                                                    if($counter % 2){
                                                        $style = "text-align: center";
                                                    }
                                                    $value = mb_convert_encoding($value,'UTF-8');
                                                    if($header == 0){
                                                        ?>
                                                        <td class=""><?=$col;?></td>
                                                        <?PHP
                                                    }else{
                                                        ?>
                                                        <td style="<?=$style?>"><?=$value;?></td>
                                                        <?PHP
                                                    }
                                                    $counter++;
                                                }
                                                ?></tr><?PHP
                                            }
                                            ?>
                                        <?PHP } ?>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            <?php } ?>
        <?php } ?>
    </div>
</div>