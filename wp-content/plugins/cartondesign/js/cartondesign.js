/**
 * Created by hitanshu on 9/8/14.
 */
function cartondesignsavedata(firstname,lastname,companyname,email,url)

{

    jQuery.ajax({

        type: 'POST',

        url: ajaxcartondesign.ajaxurl,

        data: {

            action: 'cartondesign_save_data',

            cdfirstname: firstname,
            cdlastname: lastname,
            cdcompanyname: companyname,
            cdemail: email,
            url:url

        },



        success:function(data, textStatus, XMLHttpRequest){

            if(data=="success")
            {
                var _iframe_dl = jQuery('<iframe />')
                    .attr('src',url)
                    .hide()
                    .appendTo('body');

                jQuery.fancybox.close();
            }

            else
            {
                alert(data);
            }

        },

        error: function(MLHttpRequest, textStatus, errorThrown){

            console.log(errorThrown);
        }
    });

}

jQuery(document).ready(function(){
    jQuery(".cd-download").click(function(e){
        e.preventDefault();
        jQuery(".fancybox-inner #download_form #download_url_block").empty();
        var url=jQuery(this).attr("data-url");
        var formContent=jQuery("#cd_download_form").html();
        jQuery.fancybox({'content':formContent});
        jQuery(".fancybox-inner #download_form #download_url_block").append('<input type="hidden" id="download_url" data-id="'+url+'"/>');
    });


    jQuery(document).on("click","#download_form input[type='submit']", function(event){
        event.preventDefault();
        var error_div_selector=jQuery(".fancybox-inner #download_form #download_form_error");
        error_div_selector.empty();

        var firstname=jQuery.trim(jQuery(".fancybox-inner #download_form #cdfirstname").val());
        var lastname=jQuery.trim(jQuery(".fancybox-inner #download_form #cdlastname").val());
        var companyname=jQuery.trim(jQuery(".fancybox-inner #download_form #cdcompanyname").val());
        var email=jQuery.trim(jQuery(".fancybox-inner #download_form #cdemail").val());

        if(firstname.length==0)
        {
            error_div_selector.append("Please fill FirstName.");
            return false;
        }

        else if(lastname.length==0)
        {
            error_div_selector.append("Please fill LastName.");
            return false;
        }

        else if(companyname.length==0)
        {
            error_div_selector.append("Please fill CompanyName.");
            return false;
        }

        else
        {
            if(email.length==0)
            {
                error_div_selector.append("Please fill Email.");
                return false;
            }

            else if(!IsEmail(email))
            {
                error_div_selector.append("Please provide valid email id.");
                return false;
            }

            else
            {
                //submit form
                var url=jQuery(".fancybox-inner #download_form #download_url_block #download_url").attr('data-id');
                cartondesignsavedata(firstname,lastname,companyname,email,url);
            }
        }




    });


    function IsEmail(email) {
        var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if(!regex.test(email)) {
            return false;
        }else{
            return true;
        }
    }

});