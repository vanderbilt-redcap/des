<?php
#this avoids asking to log in in RedCap
define('NOAUTH',true);

require_once "base.php";


session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="<?=getImageToDisplay($settings['des_favicon']);?>">

    <title>IeDEA - DES Browser</title>

    <script type='text/javascript'>
        var app_path_webroot = '<?=APP_PATH_WEBROOT?>';
        var app_path_webroot_full = '<?=APP_PATH_WEBROOT?>';
        var app_path_images = '<?=APP_PATH_IMAGES?>';
    </script>


    <script type="text/javascript" src="js/jquery-3.7.1.min.js"></script>
    <script type="text/javascript" src="js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="bootstrap-3.3.7/js/bootstrap.min.js"></script>

    <script type="text/javascript" src="js/jquery.tablesorter.min.js"></script>
    <script type="text/javascript" src="js/functions.js"></script>
    <script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="js/dataTables.select.min.js"></script>
    <script type="text/javascript" src="js/dataTables.buttons.min.js"></script>

    <link type='text/css' href='js/fonts-awesome/css/font-awesome.min.css' rel='stylesheet' media='screen' />
    <link type='text/css' href='css/style.css' rel='stylesheet' media='screen' />
    <link type='text/css' href='css/sortable-theme-bootstrap.css' rel='stylesheet' media='screen' />
    <link type='text/css' href='bootstrap-3.3.7/css/bootstrap.min.css' rel='stylesheet' media='screen' />
    <link type='text/css' href='css/tabs-steps-menu.css' rel='stylesheet' media='screen' />
    <link type='text/css' href='css/jquery-ui.min.css' rel='stylesheet' media='screen' />

    <style>
        table thead .glyphicon{ color: blue; }
    </style>
    <?php include('header.php'); ?>
    <?php include('navbar.php'); ?>
    <?php
    if($_REQUEST['page'] !== 'search' && $_REQUEST['page'] !== 'variableInfo'  ) {
        include('downloadButtons.php');
    }
    ?>
</head>

<body>
    <div class="container-fluid wiki_container">
         <?PHP
        if( !array_key_exists('page', $_REQUEST) )
        {
            include('pages/wiki_tables.php');
        }else if( array_key_exists('page', $_REQUEST) && $_REQUEST['page'] === 'variables' )
        {
            include('pages/wiki_variables.php');
        }else if( array_key_exists('page', $_REQUEST) && $_REQUEST['page'] === 'variableInfo' )
        {
            include('pages/wiki_variable_info.php');
        }else if( array_key_exists('page', $_REQUEST) && $_REQUEST['page'] === 'search' )
        {
            include('pages/wiki_variable_search.php');
        }
         ?>
    </div>
<br/>
</body>
</html>