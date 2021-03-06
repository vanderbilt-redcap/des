<?php
/*** Created by Eva Bascompte */
use Vanderbilt\Victrlib\Env;
# Define the environment: options include "DEV", "TEST" or "PROD"
if (is_file('/app001/www/redcap/plugins/victrlib/src/Env.php'))
    include_once('/app001/www/redcap/plugins/victrlib/src/Env.php');

if (class_exists("\\Vanderbilt\\Victrlib\\Env")) {

    if (Env::isProd()) {
        define("ENVIRONMENT", "PROD");
    } else if (Env::isStaging()) {
        define("ENVIRONMENT", "TEST");
    }else{
        define("ENVIRONMENT", "DEV");
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

define('FILE_PATH_LOCALHOST', 'C:/UniServerZ-new/www/edocs/');

require_once(__DIR__ . "/../../redcap_connect.php");
require_once(__DIR__ . "/../Core/bootstrap.php");
include_once(__DIR__ . "/functions.php");

ini_set('display_errors',1);
error_reporting(E_ALL);
global $Core;
$Core->Helpers(array('Debug', 'Array','parseCSVtoArray', 'createArrayFromCSV','getRandomIdentifier'));
$Core->Libraries(array('Project','RecordSet', 'Record',"UserRights", "Passthru"),false);

if(APP_PATH_WEBROOT[0] == '/'){
    $APP_PATH_WEBROOT_ALL = substr(APP_PATH_WEBROOT, 1);
}
define('APP_PATH_WEBROOT_ALL',APP_PATH_WEBROOT_FULL.$APP_PATH_WEBROOT_ALL);
define('APP_PATH_PLUGIN',APP_PATH_WEBROOT_FULL."plugins/".substr(__DIR__,strlen(dirname(__DIR__))+1));

include("configuration.php");
include_once("projects.php");

require_once 'vendor/autoload.php';

$projectDESSettings = new \Plugin\Project(DES_SETTINGS);
$RecordSetSettings= new \Plugin\RecordSet($projectDESSettings, array(\Plugin\RecordSet::getKeyComparatorPair($projectDESSettings->getFirstFieldName(),"!=") => ""));
$settings = $RecordSetSettings->getDetails()[0];