<script>
    $(document).ready(function() {

        $('#searchForm').on('submit', function(event) {
            loadSearch($('#varsearch').val());
            event.preventDefault();
        });

    });
</script>
<div class="container-fluid col-md-12 wiki">
    <div class='row'>
        <div>
            <h3>Search Variable</h3>
            <p>Enter the variable name to search for it and obtain all information related to that variable.</p>
        </div>
        <div style="padding-top: 10px;display: inline-block">
            <form class="form-inline" id="searchForm">
                <div class="form-group">
                    <input class="form-control" id="varsearch" placeholder="insert variable name">
                </div>
                <button type="submit" class="btn btn-default">Search variable</button>
            </form>
        </div>
    </div>
    <div class="row" style="padding-top: 20px">
        <div id="options_wrapper"></div>
    </div>
    <div class='row'>
        <div>
            <div class="panel panel-default" >
                <div class="panel-heading">
                    <h3 class="panel-title" id="seatch-title">
                        Search results
                    </h3>
                </div>
                <div id="collapse3" class="table-responsive panel-collapse collapse in" aria-expanded="true">
                    <table class="table table_requests sortable-theme-bootstrap" data-sortable id="table_search" style="margin-bottom: 0">
                        <thead>
                        <tr>
                            <th>Table</th>
                            <th>Field Name</th>
                            <th>Format</th>
                            <th>Description</th>
                        </tr>
                        </thead>
                        <tbody id="loadSearch"><tr><td>No results found</td><td></td><td></td><td></td></tr></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div id="options_wrapper_bottom" class="dataTables_wrapper"></div>
    </div>
</div>