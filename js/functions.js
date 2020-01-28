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
 * Function that changes the button appearance, loads the new value in session and shows/hides content
 * @param status, deprecated or draft
 * @param statvalue, true or false
 * @param option, if its loading option or button click
 */


function loadStatus(status,statvalue,option) {
    if(option == ''){
        if (statvalue != "" && statvalue != null && $('.'+status).length > 0){
            statvalue = "false";
            $('.'+status).filter(function() {
                if($(this).css("display") == "none"){
                    statvalue = "true";
                    $(this).show();
                    $("#"+status+"-icon").addClass("wiki_"+status);
                    $("#"+status+"_info").addClass("wiki_"+status+"_btn");
                    $("#"+status+"_info").removeClass("btn-default-reverse");
                } else{
                    $(this).hide();
                    $("#"+status+"-icon").removeClass("wiki_"+status);
                    $("#"+status+"_info").removeClass("wiki_"+status+"_btn");
                    $("#"+status+"_info").addClass("btn-default-reverse");
                }
            });
        }else if($('#'+status+'_info').hasClass('btn-default-reverse') && (statvalue == "" || statvalue == null)){
            statvalue = loadStatusButton(status,"true");
        } else{
            statvalue = loadStatusButton(status,"false");
        }

        $.ajax({
            type: "POST",
            url: "options/changeStatus.php",
            data: "&status=" + status + "&value=" + statvalue
            ,
            error: function (xhr, status, error) {
                alert(xhr.responseText);
            },
            success: function (result) {

            }
        });
    }else if(option == "0"){
        if(statvalue == "" || statvalue == null){
            if($('#'+status+'_info').hasClass('btn-default-reverse')){
                statvalue = "false";
            }
        }else{
            if($('#'+status+'_info').hasClass('btn-default-reverse') && (statvalue == "" || statvalue == null)){
                statvalue = "false";
            }

            if($('#'+status+'_info').hasClass('btn-default-reverse') && ((option == "0" && statvalue == "true") || (option == "" && statvalue == "false"))){
                statvalue = loadStatusButton(status,"true");
            } else{
                statvalue = loadStatusButton(status,"false");

            }
        }
    }
}

function loadStatusButton(status, option){
    if(option == "true"){
        $("."+status).show();
        $("#"+status+"-icon").addClass("wiki_"+status);
        $("#"+status+"_info").addClass("wiki_"+status+"_btn");
        $("#"+status+"_info").removeClass("btn-default-reverse");
    }else{
        $("."+status).hide();
        $("#"+status+"-icon").removeClass("wiki_"+status);
        $("#"+status+"_info").removeClass("wiki_"+status+"_btn");
        $("#"+status+"_info").addClass("btn-default-reverse");
    }
    return option;
}