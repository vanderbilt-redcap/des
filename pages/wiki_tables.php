<?php

$filerepo = getProjectInfoArray(DES_FILEREPO,"");


$deprecated = empty($_POST['deprecated']) ? $_SESSION['deprecated'] : $_POST['deprecated'];
$draft = empty($_POST['draft']) ? $_SESSION['draft'] : $_POST['draft'];
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

$dataTable = getTablesInfo(DES_DATAMODEL,$tid,"table_name");
?>
<script language="JavaScript">
    $(document).ready(function() {
        var path = "<?=$path?>";
        var page = "<?=$_REQUEST['page']?>";
    } );
</script>

<br/>
<div class="col-md-12">
    <span class="wiki_title"><?=$settings['des_doc_subtitle']?></span>
</div>

<div class="col-md-12 wiki wiki_text wiki_text_size">
    <?php echo $settings['des_doc_fronttext']?>
</div>
<div class="col-md-12 wiki_text wiki_text_size" style="padding-top: 0;padding-bottom: 30px;">
    <?php if($filerepo != ""){
        foreach ($filerepo as $file){
            echo '<span style="display: block">'.$file['upload_name'].' (<i class="fa fa-arrow-down" style="color:#5cb85c"></i> <a href="options/downloadFile.php?'.parseCSVtoLink($file['upload_file']).'" target="_blank">'.$file['upload_text'].'</a>, last updated '.$file['upload_date'].')</span>';
        }
       }
       ?>
</div>
<div class="container-fluid wiki">
    <div class='row' style=''>
        <div class="col-md-12" style="padding-bottom: 10px">
            <?php include('options/options.php'); ?>
            <br>
        </div>
        <div class="col-md-12">
            <div class="panel panel-default" >
                <div class="panel-heading">
                    <h3 class="panel-title">
                        Data Tables
                    </h3>
                </div>
                <div id="collapse3" class="table-responsive panel-collapse collapse in" aria-expanded="true">
                    <table class="table table_requests sortable-theme-bootstrap" data-sortable>
                        <?php
                            echo '<thead>'.'
                                    <tr>'.'
                                        <th>Table</th>'.'
                                        <th>Content</th>'.'
                                    </tr>'.'
                                    </thead>';

                        foreach( $dataTable as $data ) {
                            if (!empty($data['record_id'])) {
                                $variable_display = "";
                                $variable_text = "";
                                $variable_class = "";
                                if (array_key_exists('table_status', $data)) {
                                    if($data['table_status'] == "0"){//DRAFT
                                        $variable_text = "<span class=''><em class='fa fa-clock-o wiki_draft'></em> <em>Draft</em></span><br/>";
                                        $variable_class = "draft";
                                        if($draft == 'false') {
                                            $variable_display = "display:none";
                                        }
                                    }else if($data['table_status'] == "2"){
                                        $variable_text = "<span class=''><em class='fa fa-exclamation-circle wiki_deprecated'></em> <em>Deprecated</em></span><br/>";
                                        $variable_class = "deprecated";
                                        if($deprecated == 'false') {//DEPRECATED
                                            $variable_display = "display:none";
                                        }
                                    }
                                }

                                $required_class = '';
                                $required_text = '';
                                if($data['table_required'][0] == '1'){
                                    $required_class = 'required_des';
                                    $required_text="<div style='color:red'><em>*Required</em></div>";
                                }

                                $record_var_aux = empty($data['record_id']) ? '1' : $data['record_id'];
                                $definition = mb_convert_encoding(array_key_exists('table_definition',$data)?$data['table_definition']:"",'UTF-8');
                                $url = "index.php?pid=".DES_DATAMODEL."&tid=".$data['record_id']."&page=variables";
                                echo '<tr class="'.$required_class." ".$variable_class.'" style="' . $variable_display . '" id="'.$record_var_aux.'_row">'.
                                    '<td class="'.$required_class.'">'.
                                    '<a href="'.$url.'" id="tables_link" onclick="addURL(\''.$url.'\', \'&deprecated=\'+$(\'#deprecated_info\').is(\':checked\')+\'&draft=\'+$(\'#draft_info\').is(\':checked\'));">'.$data['table_name'].'</a>'.
                                    '</td>'.
                                    '<td id="'.$record_var_aux.'_description">'.$required_text.$variable_text.$definition.'</td>'.
                                    '</tr>';
                            }
                        }
                           ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>