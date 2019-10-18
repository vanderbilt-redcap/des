<?PHP

if(empty($_POST['draft'])){
    $draft_check = ($_SESSION['draft'] == "true") ? "checked" : "";
}else{
    $draft_check = ($_POST['draft '] == "true") ? "checked" : "";
}


if(empty($_POST['deprecated'])){
    $deprecated_check = ($_SESSION['deprecated'] == "true") ? "checked" : "";
}else{
    $deprecated_check = ($_POST['deprecated '] == "true") ? "checked" : "";
}
?>

<div>

    <script>
        var path = "<?=$path?>";
        var page = "<?=$_REQUEST['page']?>";
        $(window).bind("pageshow", function() {
            <?PHP
                if($_SESSION['deprecated'] == "true"){
                    ?>$('#deprecated_info').prop('checked', true);
            <?php
                        }else{
                            ?>$('#deprecated_info').prop('checked', false);<?php
            }?>
        });
    </script>

    <div style="float:right;padding-left:40px;">
        <span class="wiki_text_size" style="padding-right: 5px;"><span class="fa fa-exclamation-circle wiki_deprecated"></span> <span class="">Deprecated</span></span>
        <input value="" <?=$deprecated_check?> id="deprecated_info" class="auto-submit" style="width: 20px;height: 20px;vertical-align: -3px;" onclick="loadAjax('page='+page+'&deprecated='+this.checked+'&draft='+$('#draft_info').is(':checked')+path, 'options/deprecatedDraftAJAX.php', 'taskTableAJAX')" type="checkbox" name="deprecated_info">
    </div>
    <div style="float:right;">
        <span class="wiki_text_size" style="padding-right: 5px;"><span class="fa fa-clock-o wiki_draft"></span> <span class="">Draft</span></span>
        <input value="" <?=$draft_check?> id="draft_info" class="auto-submit" style="width: 20px;height: 20px;vertical-align: -3px;" onclick="loadAjax('page='+page+'&draft='+this.checked+'&deprecated='+$('#deprecated_info').is(':checked')+path, 'options/deprecatedDraftAJAX.php', 'taskTableAJAX')" type="checkbox" name="draft_info">
    </div>
</div>