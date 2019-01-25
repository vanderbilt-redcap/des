<div id="loader" style="display:none;"></div>

<div class="col-md-12 container-fluid wiki_container">
    <div class="col-md-12 wiki wiki_text wiki_text_size">
        <div style="display: inline-block;float: left;">
            <div id="load_message" class="alert alert-info fade in col-md-12" style="display: none">
                <div id="loader-mini" style="float:left"></div>
                <div style="float:left;padding-left: 20px;line-height: 50px;">Please wait while the file is generated. It may take a few minutes.</div>
            </div>
        </div>
    <div style="display: inline-block;float: right;">
        <form method="POST" action="options/downloadPDF_AJAX.php" id='downloadPDF' >
            <a onclick="$('#downloadPDF').submit()" class="btn btn-default btn-md"><i class="fa fa-arrow-down"></i> Active PDF</a>
            <input type="hidden" id="option" name="option" value="1">
        </form>
    </div>
        <div style="display: inline-block;float: right;">
        <form method="POST" action="options/downloadPDF_AJAX.php" id='downloadPDF' style="padding-right: 10px">
            <a onclick="$('#downloadPDF').submit();" class="btn btn-default btn-md"><i class="fa fa-arrow-down"></i> All PDF</a>
            <input type="hidden" id="option" name="option" value="0">
        </form>
        </div>
    </div>
</div>
<script>
    jQuery(document).ready(function($){
        $('#downloadPDF').submit(function () {
            $('#load_message').show();
            //After 15 seconds hide message
            setTimeout(function(){ $('#load_message').hide(); }, 15000);
        });
    });
</script>