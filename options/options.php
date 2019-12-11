
<div class="row">

    <script>
        var path = "<?=$path?>";
        var page = "<?=$_REQUEST['page']?>";
        $(window).bind("pageshow", function() {
            var deprecated = <?=json_encode($_SESSION['deprecated_'.$settings['des_wkname']])?>;
            var draft = <?=json_encode($_SESSION['draft_'.$settings['des_wkname']])?>;
            loadStatus('deprecated',deprecated,"0");
            loadStatus('draft',draft,"0");
        });
    </script>

    <div class="option-search">
        <a href="index.php?page=search">Variable Search</a>
    </div>
    <div class="option-btn">
        <button href="#" id="deprecated_info" class="btn-default-reverse btn" onclick="loadStatus('deprecated','<?=$_SESSION['deprecated_'.$settings['des_wkname']]?>','');" type="checkbox" name="deprecated_info">
            <span class="fa fa-exclamation-circle" id="deprecated-icon"></span> Show Deprecated
        </button>
    </div>
    <div class="option-btn">
        <button href="#" id="draft_info" class="btn-default-reverse btn" onclick="loadStatus('draft','<?=$_SESSION['draft_'.$settings['des_wkname']]?>','');" type="checkbox" name="draft_info">
            <span class="fa fa-clock-o" id="draft-icon"></span> Show Draft
        </button>
    </div>
</div>