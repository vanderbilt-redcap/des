
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

    <div style="float:left;font-size: 16px;text-decoration: underline;position: relative;top: 16px;left: 3px;">
        <a href="index.php?page=search">Search variable</a>
    </div>
    <div style="float:right;padding-left:20px;">
        <button href="#" id="deprecated_info" class="btn-default-reverse btn" onclick="loadStatus('deprecated','<?=$_SESSION['deprecated']?>','');" type="checkbox" name="deprecated_info">
            <span class="fa fa-exclamation-circle" id="deprecated-icon"></span> Show Deprecated
        </button>
    </div>
    <div style="float:right;padding-left:20px;">
        <button href="#" id="draft_info" class="btn-default-reverse btn" onclick="loadStatus('draft','<?=$_SESSION['draft']?>','');" type="checkbox" name="draft_info">
            <span class="fa fa-clock-o" id="draft-icon"></span> Show Draft
        </button>
    </div>
</div>