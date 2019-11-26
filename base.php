<?php
/*** Created by Eva Bascompte */

# Define the environment: options include "DEV", "TEST" or "PROD"
if (is_file('/app001/victrcore/lib/Victr/Env.php'))
    include_once('/app001/victrcore/lib/Victr/Env.php');

if(class_exists("Victr_Env")) {
    $envConf = Victr_Env::getEnvConf();

    if ($envConf[Victr_Env::ENV_CURRENT] === Victr_Env::ENV_PROD) {
        define("ENVIRONMENT", "PROD");
    }
    elseif ($envConf[Victr_Env::ENV_CURRENT] === Victr_Env::ENV_DEV) {
        define("ENVIRONMENT", "TEST");
    }
}
else {
    define("ENVIRONMENT", "DEV");
}

# Define REDCap path
if (ENVIRONMENT == "DEV") {
    define("CONNECT_FILE_PATH", "../../");
}
else {
    define("CONNECT_FILE_PATH", dirname(dirname(dirname(__FILE__))));
}

define('imgPath','/plugins/resin_reg/img/down-arrow.png');

$superUsers = array(
    'site_admin' => 1,
    'bascome' => 1,
    'mcguffk' => 1
);

define('FILE_PATH_LOCALHOST', 'C:/UniServerZ-new/www/edocs/');

require_once(__DIR__ . "/../../redcap_connect.php");
require_once(__DIR__ . "/../Core/bootstrap.php");
include_once(__DIR__ . "/functions.php");

ini_set('display_errors',1);
error_reporting(E_ALL);
global $Core;
$Core->Helpers(array('Debug', 'Array','parseCSVtoArray', 'createArrayFromCSV'));
$Core->Libraries(array('Project','RecordSet', 'Record',"UserRights", "Passthru"));

if(APP_PATH_WEBROOT[0] == '/'){
    $APP_PATH_WEBROOT_ALL = substr(APP_PATH_WEBROOT, 1);
}
define('APP_PATH_WEBROOT_ALL',APP_PATH_WEBROOT_FULL.$APP_PATH_WEBROOT_ALL);

include("configuration.php");
include_once("projects.php");