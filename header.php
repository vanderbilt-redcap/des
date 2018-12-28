<?PHP

$img_logo = "img/iedea_logo-100x40.png";

if($settings['des_logo'] != ''){
    $sql = "SELECT stored_name,doc_name,doc_size FROM redcap_edocs_metadata WHERE doc_id=" . $settings['des_logo'];
    $q = db_query($sql);

    if ($error = db_error()) {
        die($sql . ': ' . $error);
    }

    while ($row = db_fetch_assoc($q)) {
        $img_logo = 'options/downloadFile.php?sname=' . $row['stored_name'] . '&file=' . $row['doc_name'];
    }
}

?>

<div class="navbar navbar-default navbar-fixed-top wiki_header" role="navigation">
    <div>
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <div class="imgNavbar">
                <a href="index.php" style="text-decoration: none;float:left">
                    <img src='<?=$img_logo;?>' class='wiki_logo_img'  alt="IeDEA Logo">
                </a>

                <a href="index.php" style="text-decoration: none;float:left" class="hub_header_title">
                    <span class="">DES Browser</span>
                </a>

            </div>
        </div>
    </div>
</div>