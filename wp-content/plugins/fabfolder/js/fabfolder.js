/**
 * Created by hitanshu on 9/8/14.
 */
function fabfoldersavedata(firstname,lastname,companyname,email,url,type,fab)

{

    jQuery.ajax({

        type: 'POST',

        url: ajaxfabfolder.ajaxurl,

        data: {

            action: 'fabfolder_save_data',

            cdfirstname: firstname,
            cdlastname: lastname,
            cdcompanyname: companyname,
            cdemail: email,
            url:url,
            type:type,
            fab:fab

        },



        success:function(data, textStatus, XMLHttpRequest){

            var fileDownloadUrl='/platypus/process.php';
            if(data=="success")
            {
                var _iframe_dl = jQuery('<iframe />')
                    .attr('src',fileDownloadUrl+'?url='+url)
                    .hide()
                    .appendTo('body');

                jQuery.fancybox.close();
            }

            else
            {
                alert("File not available.");
            }

        },

        error: function(MLHttpRequest, textStatus, errorThrown){
            console.log(errorThrown);
        }
    });

}


function getFilteredFolderData(no_of_pocket,gussett,assembly_type,page)
{
    jQuery.ajax({

        type: 'POST',

        url: ajaxfabfolder.ajaxurl,

        data: {

            action: 'fabfolder_get_data',
            no_of_packet: no_of_pocket,
            gussett: gussett,
            assembly_type: assembly_type,
            page:page
            
        },

        beforeSend: function(){
            jQuery("#fab_folder_files").css('opacity', '0');
            jQuery("#ajax_loading_content").show();
        },

        success:function(data, textStatus, XMLHttpRequest){
            jQuery('#fab_folder_files').html(data).show();
            jQuery("#fab_folder_files").css('opacity', '1');
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
//    getFilteredFolderData('','','',1);

    jQuery(document).on("click",".ff-download", function(e){
        e.preventDefault();
        jQuery(".fancybox-inner #folder_download_form #folder_download_url_block").empty();
        var url=jQuery(this).attr("data-url");
        var typeId=jQuery(this).attr("data-type-id");
        var fab=jQuery(this).attr("data-fab");
        var formContent=jQuery("#ff_download_form").html();
        jQuery.fancybox({'content':formContent});
        jQuery(".fancybox-inner #folder_download_form #folder_download_url_block").append('<input type="hidden" id="download_url" data-fab="'+fab+'"data-type-id="'+typeId+'" data-id="'+url+'"/>');
    });


    jQuery(document).on("click","#folder_download_form input[type='submit']", function(event){
        event.preventDefault();
        var error_div_selector=jQuery(".fancybox-inner #folder_download_form #folder_download_form_error");
        error_div_selector.empty();

        var firstname=jQuery.trim(jQuery(".fancybox-inner #folder_download_form #cdfirstname").val());
        var lastname=jQuery.trim(jQuery(".fancybox-inner #folder_download_form #cdlastname").val());
        var companyname=jQuery.trim(jQuery(".fancybox-inner #folder_download_form #cdcompanyname").val());
        var email=jQuery.trim(jQuery(".fancybox-inner #folder_download_form #cdemail").val());

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
                var url=jQuery(".fancybox-inner #folder_download_form #folder_download_url_block #download_url").attr('data-id');
                var type=jQuery(".fancybox-inner #folder_download_form #folder_download_url_block #download_url").attr('data-type-id');
                var fab=jQuery(".fancybox-inner #folder_download_form #folder_download_url_block #download_url").attr('data-fab');
                fabfoldersavedata(firstname,lastname,companyname,email,url,type,fab);
            }
        }


    });

    jQuery(document).on("click","#search_folders", function(e){

        var no_of_pockets= jQuery.trim(jQuery('input[name=no_of_pockets]:radio:checked').val());
        var gusset= jQuery.trim(jQuery('input[name=gusset]:radio:checked').val());
        var assembly_type= jQuery.trim(jQuery('input[name=assembly_type]:radio:checked').val());
        getFilteredFolderData(no_of_pockets,gusset,assembly_type,1);
    });


    jQuery(document).on("click","#page_btn", function(e){
        var no_of_pockets= jQuery.trim(jQuery('input[name=no_of_pockets]').val());
        var gusset= jQuery.trim(jQuery('input[name=gusset]').val());
        var assembly_type= jQuery.trim(jQuery('input[name=assembly_type]').val());

        var page=jQuery(this).attr('data-id');
        getFilteredFolderData(no_of_pockets,gusset,assembly_type,page);
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
