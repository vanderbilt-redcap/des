<?php
define('NOAUTH',true);
require_once "../base.php";
session_start();

$varsearch = $_POST['varsearch'];
$search_table = '';
$dataTable = getTablesInfo(DES_DATAMODEL,$tid,"table_name");
$found = false;
foreach( $dataTable as $tid => $data ){
    if(!empty($data['record_id'])) {
        foreach ($data['variable_order'] as $id => $value) {
            if(strtolower($data['variable_name'][$id]) == strtolower($varsearch)) {
                $found = true;
                $variable_display = "";
                $variable_text = "";
                $deprecated_text = "";
                $variable_class = "";
                if (array_key_exists('variable_status', $data) && array_key_exists($id, $data['variable_status'])) {
                    if ($data['variable_status'][$id] == "0") {//DRAFT
                        if ($draft == 'false') {//DEPRECATED
                            $variable_display = "display:none";
                        }
                        $variable_text = "<span><em class='fa fa-clock-o wiki_draft'></em> <em>Draft</em></span><br/>";
                        $variable_class = "draft";
                    } else if ($data['variable_status'][$id] == "2") {
                        if ($deprecated == 'false') {//DEPRECATED
                            $variable_display = "display:none";
                        }
                        $variable_text = "<span><em class='fa fa-exclamation-circle wiki_deprecated'></em> <em>Deprecated</em></span><br/>";
                        $variable_class = "deprecated";

                        if ($data['variable_replacedby'][$id] != "") {
                            $variable_replacedby = explode("|", $data['variable_replacedby'][$id]);
                            $table = getProjectInfoArray(DES_DATAMODEL, array('record_id' => $variable_replacedby[0]), "simple");
                            $table_name = $table['table_name'];
                            $var_name = $table['variable_name'][$variable_replacedby[1]];

                            $deprecated_text .= "<div><em>This variable was deprecated on " . date("d M Y", strtotime($data['variable_deprecated_d'][$id])) . " and replaced with " . $table_name . " | " . $var_name . ".</em></div>";
                        } else if ($data['variable_replacedby'][$id] == "" && $data['variable_deprecated_d'][$id] != "") {
                            $deprecated_text .= "<div><em>This variable was deprecated on " . date("d M Y", strtotime($data['variable_deprecated_d'][$id])) . ".</em></div>";
                        } else if ($data['variable_replacedby'][$id] == "" && $data['variable_deprecated_d'][$id] == "") {
                            $deprecated_text .= "<div><em>This variable was deprecated.</div>";
                        }
                        $deprecated_text .= "<div><em>" . $data['variable_deprecatedinfo'][$id] . "</em></div>";
                    }
                }

                $required_class = '';
                $required_text = '';
                if ($data['variable_required'][$id][0] == '1') {
                    $required_class = 'required_des';
                    $required_text = "<div style='color:red'><em>*Required</em></div>";
                }

                $record_var_aux = empty($id) ? '1' : $id;
                $record_var = $id;
                $name = $data['variable_name'][$id];
                $nametid = $data['table_name'];
                $url = 'index.php?pid=' . DES_DATAMODEL . '&tid=' . $tid . '&vid=' . $record_var . '&page=variableInfo';
                $urltid = 'index.php?pid=' . DES_DATAMODEL . '&tid=' . $data['record_id'] . '&page=variables';
                $search_table .= '<tr class="' . $required_class . " " . $variable_class . '" style="' . $variable_display . '"" id="' . $record_var_aux . '_row">' .
                    '<td style="width:130px">' . '<a href="' . $urltid . '">' . $nametid . '</a>' . '</td>' .
                    '<td style="width:130px">' . '<a href="' . $url . '">' . $name . '</a>' . '</td>' .
                    '<td style="width:350px">';

                $dataFormat = $dataTable['data_format_label'][$data['data_format'][$id]];
                if ($data['has_codes'][$id] != '1') {
                    $search_table .= $dataFormat;
                } else if ($data['has_codes'][$id] == '1') {
                    if (!empty($data['code_list_ref'][$id])) {
                        $codeformat = getProjectInfoArray(DES_CODELIST, array('record_id' => $data['code_list_ref'][$id]), 'simple');

                        if ($codeformat['code_format'] == '1') {
                            $codeOptions = empty($codeformat['code_list']) ? $data['code_text'][$id] : explode(" | ", $codeformat['code_list']);
                            if (!empty($codeOptions[0])) {
                                $dataFormat .= "<div style='padding-left:15px'>";
                            }
                            foreach ($codeOptions as $option) {
                                $dataFormat .= $option . "<br/>";
                            }
                            if (!empty($codeOptions[0])) {
                                $dataFormat .= "</div>";
                            }
                            $search_table .= $dataFormat;

                        } else if ($codeformat['code_format'] == '3') {
                            $search_table .= $dataFormat . '<br/>';

                            if (array_key_exists('code_file', $codeformat)) {
                                $search_table .= '<a href="#codesModal' . $codeformat['code_file'] . '_' . $name . '" id="btnViewCodes" type="button" class="btn_code_modal open-codesModal" data-toggle="modal" data-target="#codesModal' . $codeformat['code_file'] . '_' . $name . '">See Code List</a>';

                                #modal window with the updates
                                $search_table .= '<div class="modal fade" id="codesModal' . $codeformat['code_file'] . '_' . $name . '" role="dialog" aria-labelledby="Codes">' .
                                    '<div class="modal-dialog" role="document">' .
                                    '<div class="modal-content">' .
                                    '<div class="modal-header">' .
                                    '<button type="button" class="close closeCustomModal" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' .
                                    '<h4 class="modal-title">Codes</h4>' .
                                    '</div>' .
                                    '<div class="modal-body">' .
                                    '<div class="row" style="padding:30px;">' .
                                    '<div class="panel panel-default">' .
                                    '<div class="panel-heading">' . $name . '</div>' .
                                    '<div class="table-responsive panel-collapse collapse in">' .
                                    '<table border="1" class="code_modal_table">';
                                $csv = parseCSVtoArray($codeformat['code_file']);
                                if (empty($csv)) {
                                    $search_table .= '<div style="text-align: center;color:red;">No Codes found for file:' . $codeformat['code_file'] . '</div>';
                                }
                                foreach ($csv as $header => $content) {
                                    if ($header == 0) {
                                        $search_table .= '<tr>';
                                    } else {
                                        $search_table .= '<tr>';
                                    }
                                    foreach ($content as $col => $value) {
                                        //Convert to UTF-8 to avoid weird characters
                                        $value = mb_convert_encoding($value, 'UTF-8','HTML-ENTITIES');
                                        if ($header == 0) {
                                            $search_table .= '<td class="code_modal_td">' . $col . '</td>';
                                        } else {
                                            $search_table .= '<td class="code_modal_td">' . $value . '</td>';
                                        }
                                    }
                                    $search_table .= '</tr>';
                                }
                                $search_table .= '</table></div></div>' .
                                    '</div>' .
                                    '</div>' .
                                    '<div class="modal-footer">' .
                                    '<button type="button" class="btn btn-default" id="btnCloseCodesModal" data-dismiss="modal">CLOSE</button>' .
                                    '</div>' .
                                    '</div></div></div>';
                            }
                        } else if ($codeformat['code_format'] == '4') {
                            $search_table .= "<a href='https://bioportal.bioontology.org/ontologies/" . $codeformat['code_ontology'] . "' target='_blank'>See Ontology Link</a><br/>";
                        }
                    } else {
                        $search_table .= $dataFormat;
                    }
                }
                $search_table .= '</td><td id="' . $record_var_aux . '_description"><div style="padding-bottom: 8px;padding-top: 8px">' . $required_text;
                $search_table .= "<div>" . $variable_text . mb_convert_encoding($data['description'][$id], 'UTF-8','HTML-ENTITIES') . "</div>";
                if (!empty($data['description_extra'][$id])) {
                    $search_table .= "<div><i>" . $data['description_extra'][$id] . "</i></div>";
                }
                if (!empty($data['code_text'][$id])) {
                    $search_table .= "<div><i>" . $data['code_text'][$id] . "</i></div>";
                }
                $search_table .= $deprecated_text;
                $search_table .= '</div></td>';
                $search_table .= '</tr>';
            }
        }
    }
}
if(!$found){
    $search_table = '<tr><td>No results found</td><td></td><td></td><td></td></tr>';
}
echo json_encode($search_table);

?>