<?php
require_once dirname(dirname(__FILE__))."/base.php";
require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';


if(hasJsoncopyBeenUpdated('0a') || hasJsoncopyBeenUpdated('0b')){
    createAndSavePDFCron($settings,$secret_key,$secret_iv);
}


?>