/**
 * Created by hitanshu on 9/8/14.
 */
function cartondesignsavedata(firstname,lastname,companyname,email,url,type)

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
            url:url,
            type:type

        },



        success:function(data, textStatus, XMLHttpRequest){

//            var fileDownloadUrl='/platypus/wp-content/plugins/cartondesign/process.php';
            if(data=="success")
            {
                var _iframe_dl = jQuery('<iframe />')
                    .attr('src','/platypus/wp-content'+url)
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


function getFilteredData(lengthMin,lengthMax,widthMin,widthMax,depthMin,depthMax,type,page)
{
    jQuery.ajax({

        type: 'POST',

        url: ajaxcartondesign.ajaxurl,

        data: {

            action: 'cartondesign_get_data',
            lengthMin: lengthMin,
            lengthMax: lengthMax,
            widthMin: widthMin,
            widthMax: widthMax,
            depthMin: depthMin,
            depthMax: depthMax,
            page:page,
            type_id:type
        },

        beforeSend: function(){
            jQuery("#carton_design_files").css('opacity', '0');
            jQuery("#ajax_loading_content").show();
        },

        success:function(data, textStatus, XMLHttpRequest){
            jQuery('#carton_design_files').html(data).show();
            jQuery("#carton_design_files").css('opacity', '1');
            jQuery("#ajax_loading_content").hide();
        },

        error: function(MLHttpRequest, textStatus, errorThrown){
            console.log(errorThrown);
            jQuery("#ajax_loading_content").hide();
        }
    });
}

jQuery(document).ready(function(){
    //calling for first time
    getFilteredData('','','','','','','',1);

    jQuery(document).on("click",".cd-download", function(e){
        e.preventDefault();
        jQuery(".fancybox-inner #download_form #download_url_block").empty();
        var url=jQuery(this).attr("data-url");
        var typeId=jQuery(this).attr("data-type-id");
        var formContent=jQuery("#cd_download_form").html();
        jQuery.fancybox({'content':formContent});
        jQuery(".fancybox-inner #download_form #download_url_block").append('<input type="hidden" id="download_url" data-type-id="'+typeId+'" data-id="'+url+'"/>');
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
                var type=jQuery(".fancybox-inner #download_form #download_url_block #download_url").attr('data-type-id');
                cartondesignsavedata(firstname,lastname,companyname,email,url,type);
            }
        }


    });

    jQuery(document).on("click","#search_layout", function(e){
        var lengthMin= jQuery.trim(jQuery('#length_min').val());
        var lengthMax= jQuery.trim(jQuery('#length_max').val());

        var widthMin= jQuery.trim(jQuery('#width_min').val());
        var widthMax= jQuery.trim(jQuery('#width_max').val());

        var depthMin= jQuery.trim(jQuery('#depth_min').val());
        var depthMax= jQuery.trim(jQuery('#depth_max').val());
        var type=jQuery('#type_id').val();
        getFilteredData(lengthMin,lengthMax,widthMin,widthMax,depthMin,depthMax,type,1);
    });


    jQuery(document).on("click","#page_btn", function(e){
        var lengthMin= jQuery.trim(jQuery('#length_min').val());
        var lengthMax= jQuery.trim(jQuery('#length_max').val());

        var widthMin= jQuery.trim(jQuery('#width_min').val());
        var widthMax= jQuery.trim(jQuery('#width_max').val());

        var depthMin= jQuery.trim(jQuery('#depth_min').val());
        var depthMax= jQuery.trim(jQuery('#depth_max').val());
        var type=jQuery('#type_id').val();
        var page=jQuery(this).attr('data-id');
        getFilteredData(lengthMin,lengthMax,widthMin,widthMax,depthMin,depthMax,type,page);
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