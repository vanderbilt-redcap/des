
<div>

    <script>
        var path = "<?=$path?>";
        var page = "<?=$_REQUEST['page']?>";
        $(window).bind("pageshow", function() {
            var deprecated = <?=json_encode($_SESSION['deprecated'])?>;
            var draft = <?=json_encode($_SESSION['draft'])?>;
            loadStatus('deprecated',deprecated,"0");
            loadStatus('draft',draft,"0");
        });
    </script>

    <div style="float:right;padding-left:20px;">
        <button href="#" id="deprecated_info" class="btn-default btn" onclick="loadStatus('deprecated','<?=$_SESSION['deprecated']?>','');" type="checkbox" name="deprecated_info">
            <span class="fa fa-exclamation-circle" id="deprecated-icon"></span> Deprecated
        </button>
    </div>
    <div style="float:right;padding-left:20px;">
        <button href="#" id="draft_info" class="btn-default btn" onclick="loadStatus('draft','<?=$_SESSION['draft']?>','');" type="checkbox" name="draft_info">
            <span class="fa fa-clock-o" id="draft-icon"></span> Draft
        </button>
    </div>
</div>