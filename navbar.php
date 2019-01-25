
    <div>
        <div class="nav nav-pills" style="margin-top: 100px;padding-left: 60px;">
            <?PHP
            $active = "";
            $path = "";
            if( !array_key_exists('page', $_REQUEST)) {
                $active = "class='wiki_active'";
            }
            ?>
            <?php if( array_key_exists('page', $_REQUEST)){?>
            <a href="index.php?pid=<?= DES_DATAMODEL; ?>" <?=$active?> onclick="addURL('index.php?pid=<?= DES_DATAMODEL; ?>', '&deprecated='+$('#deprecated_info').is(':checked')+'&draft='+$('#draft_info').is(':checked'))" style="padding-top: 40px;">DES Main</a>
            <?php } ?>
            <?php if( array_key_exists('page', $_REQUEST) && ($_REQUEST['page'] === 'variables' || $_REQUEST['page'] === 'variableInfo')){
                $tid = $_REQUEST['tid'];
                $vid = isset($_REQUEST['vid']) ? $_REQUEST['vid']:"";
                $path = "&tid=".$tid."&vid=".$vid;
                $dataTable = getTablesInfo(DES_DATAMODEL,$tid);
                $active = "";
                foreach( $dataTable as $data ) {
                    if (!empty($data['record_id'])) {
                        if ($_REQUEST['page'] === 'variables' || $_REQUEST['page'] === 'variableInfo') {
                            if($_REQUEST['page'] === 'variables') {
                                $active = "class='wiki_active'";
                            }
                            $url = "index.php?pid=".DES_DATAMODEL."&tid=".$data['record_id']."&page=variables";
                            ?>
                            <span> > </span>
                            <a href="<?=$url?>" <?=$active?> onclick="addURL('<?=$url?>', '&deprecated='+$('#deprecated_info').is(':checked')+'&draft='+$('#draft_info').is(':checked'))"><?= $data['table_name'] ?></a>
                        <?php }

                        if ($_REQUEST['page'] === 'variableInfo') {
                            $active = "class='wiki_active'";
                            $url = "index.php?pid=".DES_DATAMODEL."&tid=".$tid ."&vid=". $vid ."&page=variableInfo";
                            ?>
                            <span> > </span>
                           <a href="<?=$url?>" <?=$active?> onclick="addURL('<?=$url?>', '&deprecated='+$('#deprecated_info').is(':checked')+'&draft='+$('#draft_info').is(':checked'))"><?= $data['variable_name'][$vid] ?></a>
                        <?php }
                    }
                }
            }?>

    </div>