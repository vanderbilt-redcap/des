<?php
define('NOAUTH',true);
require_once dirname(dirname(__FILE__)) . "/base.php";
require_once dirname(dirname(__FILE__)) . "/dompdf/autoload.inc.php";


$projectDESSettings = new \Plugin\Project(DES_SETTINGS);
$RecordSetSettings= new \Plugin\RecordSet($projectDESSettings, array(\Plugin\RecordSet::getKeyComparatorPair($projectDESSettings->getFirstFieldName(),"!=") => ""));
$settings = $RecordSetSettings->getDetails()[0];

$option = $_REQUEST['option'];

$dataTable = getTablesInfo(DES_DATAMODEL);
if(!empty($dataTable)) {
    # Get selected rows
    $tableHtml = generateTablesHTML_pdf($dataTable,$option);
    $requested_tables = generateRequestedTablesList_pdf($dataTable,$option);
}

#FIRST PAGE
$first_page = "<tr><td align='center'>";
$first_page .= "<p><span style='font-size: 16pt;font-weight: bold;'>International Epidemiology Databases to Evaluate AIDS (IeDEA)</span></p>";
$first_page .= "<p><span style='font-size: 16pt;font-weight: bold;'>STANDARD OPERATING PROCEDURES FOR DATA TRANSFER</span></p><br/>";
$first_page .= "<p><span style='font-size: 14pt;font-weight: bold;'>Request Version: ".date('d F Y')."</span></p><br/>";
$first_page .= "<span style='font-size: 12pt'>";
$first_page .= "</span></td></tr></table>";

#SECOND PAGE
$second_page .= "<p><span style='font-size: 12pt'>".$requested_tables."</span></p>";

$page_num = '<style>.footer .page-number:after { content: counter(page); } .footer { position: fixed; bottom: 0px;color:grey }</style>';


$html_pdf = "<html><body style='font-family:\"Calibri\";font-size:10pt;'>".$page_num
    ."<div class='footer' style='left: 600px;'><span class='page-number'>Page </span></div>"
    ."<div class='mainPDF'><table style='width: 100%;'><tr><td align='center'><img src='../img/IeDEA-logo-200px.png' style='width:200px;padding-bottom: 30px;'></td></tr></table></div>"
    ."<div class='mainPDF' id='page_html_style'><table style='width: 100%;'>".$first_page."<div style='page-break-before: always;'></div>"
    ."<div class='mainPDF'>".$second_page."<div style='page-break-before: always;'></div>"
    ."<p><span style='font-size:16pt'><strong>6. Requested DES Tables</strong></span></p>"
    .$tableHtml
    ."</div></div>"
    ."</body></html>";

$text_option = "active_";
if($option == "0"){
    $text_option = "all_";
}

$filename = $settings['des_wkname']."_DES_".$text_option.date("Y-m-d_hi",time());

//DOMPDF
$dompdf = new \Dompdf\Dompdf();
$dompdf->loadHtml($html_pdf);
$dompdf->setPaper('A4', 'portrait');
$dompdf->stream($filename);

?>