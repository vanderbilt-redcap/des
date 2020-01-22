<script>
    $(document).ready(function() {
        $('#searchForm').on('submit', function(event) {
            loadSearch($('#varsearch').val());
            event.preventDefault();
        });

        $(document).ready(function() {
            $('.table_search').dataTable( {"pageLength": 50,"order": [0, "asc"]});

            $('#search_btn').click( function() {
                var table = $('#table_archive').DataTable();
                table.draw();
            } );

            $('#table_archive_filter').appendTo( '#options_wrapper' );
            $('#table_archive_length').appendTo( '#options_wrapper' );
            $('#table_archive_filter').attr( 'style','float: right;padding-left: 170px;padding-top: 5px;' );
        } );

        //To filter the data
        $.fn.dataTable.ext.search.push(
            function( settings, data, dataIndex ) {
                var variable = $('#varsearch').val().toUpperCase();
                var column_variable = data[1];

                if(variable != '' && column_variable.match(variable) != null){
                    return true;
                }else if(variable == ''){
                    return true;
                }

                return false;
            }
        );
    });
</script>
<div class="wiki_main">
        <div class='row'>
            <div class='col-md-12'>
                <div>
                    <h3>Variable Search</h3>
                    <p>Enter the variable name to search for it and obtain information related to that variable.</p>
                    <p>To see more information click on the variable or table name.</p>
                </div>
<!--                <div style="padding-top: 10px;display: inline-block">-->
<!--                    <form class="form-inline" id="searchForm">-->
<!--                        <div class="form-group">-->
<!--                            <input class="form-control" id="varsearch" placeholder="insert variable name">-->
<!--                        </div>-->
<!--                        <button type="submit" class="btn btn-default">Search</button>-->
<!--                    </form>-->
<!--                </div>-->
                <div style="padding-top: 10px;display: inline-block">
                    <div class="form-group">
                        <input class="search-input" id="varsearch" placeholder="insert variable name">
                        <button type="submit" class="btn btn-default" id="search_btn">Search</button>
                    </div>
                </div>
                <div style="padding-top: 10px;">
                        <div id="options_wrapper"></div>
                </div>
            </div>
<!--                <div class="col-md-12" style="padding-top: 10px">-->
<!--                    <div class="panel panel-default" >-->
<!--                        <div class="panel-heading">-->
<!--                            <h3 class="panel-title" id="seatch-title">-->
<!--                                Search results-->
<!--                            </h3>-->
<!--                        </div>-->
<!--                        <div id="collapse3" class="table-responsive panel-collapse collapse in" aria-expanded="true">-->
<!--                            <table class="table table_requests sortable-theme-bootstrap" data-sortable id="table_search" style="margin-bottom: 0">-->
<!--                                <thead>-->
<!--                                <tr>-->
<!--                                    <th>Table</th>-->
<!--                                    <th>Field Name</th>-->
<!--                                    <th>Format</th>-->
<!--                                    <th>Description</th>-->
<!--                                </tr>-->
<!--                                </thead>-->
<!--                                <tbody id="loadSearch"><tr><td>No results found</td><td></td><td></td><td></td></tr></tbody>-->
<!--                            </table>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--            <div class="col-md-12">-->
<!--                <div id="options_wrapper_bottom" class="dataTables_wrapper"></div>-->
<!--            </div>-->

            <div class="col-md-12" style="padding-top: 10px">
                <div class="panel panel-default" >
                    <div class="panel-heading">
                        <h3 class="panel-title" id="seatch-title">
                            Search results
                        </h3>
                    </div>
                    <table class="table table_search sortable-theme-bootstrap" data-sortable id="table_archive">
                        <thead>
                            <th>Table</th>
                            <th>Field Name</th>
                            <th>Format</th>
                            <th>Description</th>
                        </thead>
                        <tbody>
                        <?php
                            if($settings["des_variable_search"] != "") {
                                $table = "";
                                $sql = "SELECT stored_name,doc_name,doc_size,mime_type FROM redcap_edocs_metadata WHERE doc_id='" . db_escape($settings["des_variable_search"]) . "'";
                                $q = db_query($sql);
                                while ($row = db_fetch_assoc($q)) {
                                    $path = EDOC_PATH.$row['stored_name'];
                                    $strJsonFileContents = file_get_contents($path);
                                    $json_array = json_decode($strJsonFileContents, true);
                                    foreach ($json_array as $tablename=>$variables){
                                        foreach ($variables as $varname=>$data){
                                            if($varname != "table_link")
                                            $table .= "<tr>".
                                                        "<td style='width:130px'><a href='".$variables['table_link']."'>".$tablename."</a></td>".
                                                        "<td style='width:130px'><a href='".$data['variable_link']."'>".$varname."</a></td>".
                                                        "<td style='width:130px'>".$data['data_format']."</td>".
                                                        "<td><div>".$data['description']."</div><div><i>".$data['description_extra']."</i></div><div><i>".$data['code_text']."</i></div></td>".
                                                "</tr>";
                                        }
                                    }
                                }
                                echo $table;
                            }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>