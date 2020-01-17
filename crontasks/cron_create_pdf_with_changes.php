<?php
require_once dirname(dirname(__FILE__))."/base.php";
require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';


if(hasJsoncopyBeenUpdated('0a',$settings) || hasJsoncopyBeenUpdated('0b',$settings)){
    createAndSavePDFCron($settings,$secret_key,$secret_iv);
}


?>