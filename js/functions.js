/**
 * Function to add parameters to the URL and redirect
 * @param url, the current URL
 * @param parameter, the new parameter
 */
function addURL(url, parameter)
{
    window.location  = url+parameter;
}

/**
 * Function that loads the SOP table
 * @param data, data we send to the ajax
 * @param url, url of the ajax file
 * @param loadAJAX, where we load our content
 */
function loadAjax(data, url, loadAJAX){
    $('#errMsgContainer').hide();
    $('#succMsgContainer').hide();
    $('#warnMsgContainer').hide();
    if(data != '') {
        $('.divModalLoading').show();
        $.ajax({
            type: "POST",
            url: url,
            data:data
            ,
            error: function (xhr, status, error) {
                alert(xhr.responseText);
            },
            success: function (result) {
                jsonAjax = jQuery.parseJSON(result);
                // console.log('**jsonAjax: '+JSON.stringify(jsonAjax));

                if(jsonAjax.html != '' && jsonAjax.html != undefined) {
                    $("#" + loadAJAX).html(jsonAjax.html);
                }

                if(jsonAjax.number_updates != '' && jsonAjax.number_updates != undefined && jsonAjax.number_updates != "0"){
                    $('#succMsgContainer').show();
                    $('#succMsgContainer').html(' <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> <strong>Success!</strong> '+jsonAjax.number_updates+' NEW Latest update/s were saved.');
                }

                if(jsonAjax.variablesInfo != '' && jsonAjax.variablesInfo != undefined){
                    var value = jsonAjax.variablesInfo;
                    $.each(jsonAjax.variablesInfo, function (i, object) {;
                        if(object.display == "none"){
                            $("#"+i+"_row").hide();
                        }else{
                            $("#"+i+"_row").show();
                        };
                    });
                }

                //If table sortable add function
                if(jsonAjax.sortable == "true"){
                    $("#"+loadAJAX+"_table").tablesorter();
                }

                //Error Messages (Successful, Warning and Error)
                if(jsonAjax.succmessage != '' && jsonAjax.succmessage != undefined ){
                    $('#succMsgContainer').show();
                    $('#succMsgContainer').html(jsonAjax.succmessage);
                }else if(jsonAjax.warnmessage != '' && jsonAjax.warnmessage != undefined ){
                    $('#warnMsgContainer').show();
                    $('#warnMsgContainer').html(jsonAjax.warnmessage);
                }else if(jsonAjax.errmessage != '' && jsonAjax.errmessage != undefined ){
                    $('#errMsgContainer').show();
                    $('#errMsgContainer').html(jsonAjax.errmessage);
                }

                $('.divModalLoading').hide();
            }
        });
    }
}