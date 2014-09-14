<?php
/*
Plugin Name: Folder Download Manager
Plugin URI: #
Description: Download Folder Design and filling form entries
Author: Hitanshu (hitanshumalhotra@gmail.com, +919958205181)
Author URI: http://hitanshumalhotra.com
Version: 1.0.0
Text Domain: fabfolder
License: GPLv2
*/

define('FFURL', WP_PLUGIN_URL . "/" . dirname(plugin_basename(__FILE__)));

define('FFPATH', WP_PLUGIN_DIR . "/" . dirname(plugin_basename(__FILE__)));

$d = str_replace('\\', '/', dirname(__FILE__));
$d = explode("/", $d);
array_pop($d);
array_pop($d);
$d = implode('/', $d);

define('UPLOAD_DIR_FAB_FOLDER', $d . '/uploads/fab-folder/');
define('UPLOAD_BASE_FAB_FOLDER', $d . '/uploads/');



function fabfolder_enqueuescripts()
{

    wp_enqueue_script('fabfolder', FFURL . '/js/fabfolder.js', array('jquery'));
    wp_localize_script('fabfolder', 'ajaxfabfolder', array('ajaxurl' => admin_url('admin-ajax.php')));

}

add_action('wp_enqueue_scripts', 'fabfolder_enqueuescripts');


function fabfolder_save_data()

{
    $results = '';
    $error = 0;
    $firstName = "";
    $lastName = "";
    $companyName = "";
    $email = "";
    $url = "#";
    $fab='111';


    if (isset($_POST['cdfirstname']))
        $firstName = trim($_POST['cdfirstname']);

    if (isset($_POST['cdlastname']))
        $lastName = trim($_POST['cdlastname']);

    if (isset($_POST['cdcompanyname']))
        $companyName = trim($_POST['cdcompanyname']);

    if (isset($_POST['cdemail']))
        $email = trim($_POST['cdemail']);

    if (isset($_POST['url']))
        $url = trim($_POST['url']);

    if (isset($_POST['fab']))
        $fab = trim($_POST['fab']);

    $filterFab=explode(',',$fab);

    if (empty($firstName) && empty($lastName) && empty($companyName) && empty($email)) {
        $results = "Please fill require fields";
        $error = 1;
    } else {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $results = $email . " :email address is not valid.";
            $error = 1;
        }
    }


    if (!$error) {
        //submit data to database
        global $wpdb;
        $table_name = $wpdb->prefix . "fab_folder_logs";
        $wpdb->insert($table_name, array('firstname' => $firstName, 'lastname' => $lastName,
            'email' => $email, 'companyname' => $companyName, 'url' => $url,
            'no_of_packets'=>$filterFab[0],'gussett'=>$filterFab[1],'assembly_type'=>$filterFab[2]));
        die("success");

    } else {
        die($results);
    }


}



function fabfolder_get_data()
{
    $page=1;
    $pageItems=1;
    $previousPage = 0;
    $nextPage = 0;


    if(isset($_POST['page']) && $_POST['page'])
    $page=$_POST['page'];

    $whereCond='wp_fab_folder_design_files.id >0';
    if(isset($_POST['no_of_packet']) && !empty($_POST['no_of_packet']))
    {
        $whereCond.=' AND number_of_pocket='.$_POST['no_of_packet'];
    }

    if(isset($_POST['gussett']) && !empty($_POST['gussett']))
    {
        $whereCond.=' AND gussett='.$_POST['gussett'];
    }

    if(isset($_POST['assembly_type']) && !empty($_POST['assembly_type']))
    {
        $whereCond.=' AND assembly_type='.$_POST['assembly_type'];
    }


    global $wpdb;
    $table_name = $wpdb->prefix . "fab_folder_design_files";


    $count = $wpdb->get_var("SELECT count(*) as total FROM $table_name where $whereCond");

    if (($page * $pageItems) < (int)$count) {
        $nextPage = $page + 1;
        }
    if ($page > 1)
        $previousPage = $page - 1;

    $limit=($page-1)*$pageItems;
    $results = $wpdb->get_results("SELECT *,$table_name.id as fileId FROM $table_name join wp_carton_design_types on $table_name.type_id=wp_carton_design_types.id where $whereCond LIMIT $limit,$pageItems ");

    if(!empty($results))
    {
        echo "
        <table width='100%'>
        <thead>
        <th>&nbsp;</th>
        <th>Product Code</th>
        <th>Type</th>
        <th>No. Of Packets</th>
        <th>Gussett</th>
        <th>Assembly Type</th>
        <th>Download</th>

        </thead>
        <tbody>
        ";
        foreach($results as $row)
        {
           $no_of_packets='Single Pocket';
           $gusset='Yes';
           $assembly_type='Glued';
            if($row->number_of_pocket==2)
                $no_of_packets='Double Pocket';

            if($row->gussett==2)
             $gusset='No';

            if($row->assembly_type==2)
                $assembly_type='Interlock';

            echo "<tr>
            <td><img src='".site_url()."/wp-content".$row->product_image."' width='120px' height='120px'/></td>
            <td>".$row->product_code."</td>
            <td>".$row->name."</td>
            <td>".$no_of_packets."</td>
            <td>".$gusset."</td>
            <td>".$assembly_type."</td>
            <td><a href='#' class='ff-download download-btn' data-fab='$row->number_of_pocket,$row->gussett,$row->assembly_type' data-type-id='$row->name' data-url='".$row->folder_url."'>Download Layout</a></td>
            ";
        }

        echo "</tbody></table> <div class='page' style='margin-top: 20px;'>";

        if ($previousPage>0)
                    echo "<a class='orange-btn margin-right-10' id='page_btn' data-id='$previousPage'><span>Prev</span></a>";

                if ($nextPage>0)
                    echo "<a class='orange-btn' id='page_btn' data-id='$nextPage'><span>Next</span></a>";


            echo "</div>";
    }
    else
    {
        echo "<table width='100%'>
        <thead>
        <tr>
        <th>&nbsp;</th>
        <th>Product Code</th>
        <th>Type</th>
        <th>No. Of Packets</th>
        <th>Gussett</th>
        <th>Assembly Type</th>
        <th>Download</th>
        </tr>
        </thead>
        <tbody>
        <tr>
        <td colspan='7' style='text-align: center'>
        No Fabolous Folder Desings Found
        </td>
        </tr>
        </tbody>
        </table>";
    }


    exit;
}

//
//add_action('admin_menu', 'my_plugin_menu');
//
///** Step 1. */
//function my_plugin_menu()
//{
//    add_options_page('Carton Design Entries', 'Carton Design Entries', 'manage_options', 'list-all', 'my_plugin_options');
//}

/** Step 3. */
//function my_plugin_options()
//{
//    if (!current_user_can('manage_options')) {
//        wp_die(__('You do not have sufficient permissions to access this page.'));
//    }
//    echo '<div class="wrap">';
//    echo getAllEntries();
//    echo '</div>';
//}


// creating Ajax call for WordPress

add_action('wp_ajax_nopriv_fabfolder_save_data', 'fabfolder_save_data');

add_action('wp_ajax_fabfolder_save_data', 'fabfolder_save_data');
add_action('wp_ajax_fabfolder_get_data', 'fabfolder_get_data');
add_action('wp_ajax_nopriv_fabfolder_get_data', 'fabfolder_get_data');


//function getAllEntries()
//{
//    global $wpdb;
//    $table_name = $wpdb->prefix . "fab_folder_logs";
//    $result = $wpdb->get_results("SELECT * FROM $table_name");
//    $output = "<table class='wp-list-table fixed widefat'><thead><tr><td>S.No</td><td>FirstName</td><td>LastName</td><td>CompanyName</td><td>Email</td><td>Added Date</td><td>URL</td></tr></thead><tbody></tbody>";
//    foreach ($result as $print) {
//        $output .= "<tr><td>$print->id</td><td>$print->firstname</td><td>$print->lastname</td><td>$print->companyname</td><td>$print->email</td><td>$print->time</td><td><a href='$print->url'>Link</a></td></tr>";
//    }
//
//    return $output . '</tbody></table>';
//}

function fabfolder_delete_file(){
    global $wpdb;
    if(isset($_GET['task']) && $_GET['task'] == 'DeleteFolder' && is_admin()){
        if(is_array($_GET['id'])){
            foreach($_GET['id'] as $id){
                $qry[] = "id='".(int)$id."'";
            }
            $cond = implode(" or ", $qry);
        } else
            $cond = "id='".(int)$_GET['id']."'";

        $wpdb->query("delete from wp_fab_folder_design_files where ". $cond);
        wp_redirect('admin.php?page=folder-manager');
        die();
    }
}


function fabfolder_add_new_file(){
    global $wpdb;

    $fabFolderDesign=array();
    $fabFolderDesign['type_id']='';
    $fabFolderDesign['product_code']='';
    $fabFolderDesign['assembly_type']=1;
    $fabFolderDesign['no_of_pockets']=1;
    $fabFolderDesign['gusset']=1;

    if(isset($_POST['action']))
    {
        $errorMsg=array();
     if(isset($_POST['type_id']))
         $fabFolderDesign['type_id']=$_POST['type_id'];

     if(isset($_POST['assembly_type']))
         $fabFolderDesign['assembly_type']=trim($_POST['assembly_type']);

        if(isset($_POST['no_of_pockets']))
            $fabFolderDesign['no_of_pockets']=trim($_POST['no_of_pockets']);

        if(isset($_POST['gusset']))
            $fabFolderDesign['gusset']=trim($_POST['gusset']);

        if(isset($_POST['product_code']))
            $fabFolderDesign['product_code']=trim($_POST['product_code']);

        if(empty($fabFolderDesign['type_id'])||empty($fabFolderDesign['assembly_type'])||
            empty($fabFolderDesign['no_of_pockets'])||empty($fabFolderDesign['gusset'])||
            empty($fabFolderDesign['product_code']))
        {
            $errorMsg[]='Please fill required fields';
        }

            //checking design_image file exist or not
            $is_design_image_exist=false;
            if($_FILES['design_image']['error']!=UPLOAD_ERR_NO_FILE)
            {
                if($_FILES['design_image']['error']===UPLOAD_ERR_OK )
                {
                    $fileExt = pathinfo($_FILES['design_image']['name'], PATHINFO_EXTENSION);
                    if(!in_array($fileExt,array('png','jpg','jpeg')))
                    {
                        $errorMsg[]='Please upload valid file format for design image (jpeg,png,jpg)!';
                    }
                    else
                    {
                        $is_design_image_exist=true;
                    }

                }

                else
                {
                    // find the error in the uploaded file and sent to user
                    $errorMsg[]=getFileErrorMessageFolder($_FILES['design_image']['error'],'Folder Image');
                }
            }

            if($_FILES['design_layout']['error']===UPLOAD_ERR_OK )
            {
                $fileExt = pathinfo($_FILES['design_layout']['name'], PATHINFO_EXTENSION);
                if(!in_array($fileExt,array('pdf','zip','tar')))
                {
                   $errorMsg[]='Please upload valid file format for design layout (pdf, zip, tar )!';
                }
            }

            else
            {
                // find the error in the uploaded file and sent to user
                $errorMsg[]=getFileErrorMessageFolder($_FILES['design_layout']['error'],'Folder Layout');
            }


        if(empty($errorMsg))
        {
//            include('cartondesign-add-new-file.php');
            //success and save data to db and redirect user to edit screen or list see

            if($is_design_image_exist)
            {
                //move the file to the upload location
                if(file_exists(UPLOAD_DIR_CARTON_DESIGN.$_FILES['design_image']['name']))
                    $designImageFileName = time().'fab_folder_'.$_FILES['design_image']['name'];
                else
                    $designImageFileName = $_FILES['design_image']['name'];
                move_uploaded_file($_FILES['design_image']['tmp_name'],UPLOAD_DIR_CARTON_DESIGN.$designImageFileName);
            }

            else{
                $designImageFileName='no-image.jpg';
            }

            //move the file to the upload location
            if(file_exists(UPLOAD_DIR_FAB_FOLDER.$_FILES['design_layout']['name']))
                $designLayoutFileName = time().'fab_folder_'.$_FILES['design_layout']['name'];
            else
                $designLayoutFileName = $_FILES['design_layout']['name'];
            move_uploaded_file($_FILES['design_layout']['tmp_name'],UPLOAD_DIR_FAB_FOLDER.$designLayoutFileName);


            global $wpdb;
            $table_name = $wpdb->prefix . "fab_folder_design_files";
            $wpdb->insert($table_name, array('product_code' => $fabFolderDesign['product_code'],
                'type_id'=>$fabFolderDesign['type_id'],
                'gussett'=>$fabFolderDesign['gusset'],
                'number_of_pocket'=>$fabFolderDesign['no_of_pockets'],
                'assembly_type'=>$fabFolderDesign['assembly_type'],
                'product_image'=>'/uploads/fab-folder/'.$designImageFileName,
                'folder_url'=>'/uploads/fab-folder/'.$designLayoutFileName
             ));
            //header redirect the user to edit page
            echo "<br/><br/> <h5>file added successfully.</h5>";
            wp_redirect('admin.php?page=folder-manager');
            die();
        }

    }


    if(!file_exists(UPLOAD_DIR_FAB_FOLDER)){

//        carton_design_create_dir();
        echo "
        <div id=\"warning\" class=\"error fade\"><p>
        Automatic dir creation failed! [ <a href='admin.php?page=file-manager&task=wpdm_create_dir&re=1'>Try again to create dir automatically</a> ]<br><br>
        Please create dir <strong>" . UPLOAD_DIR_FAB_FOLDER . "</strong> manualy and set permision to <strong>777</strong><br><br>
        Otherwise you will not be able to upload files.
        </p></div>";
        exit();
    }

    if($_GET['success']==1){
        echo "
        <div id=\"message\" class=\"updated fade\"><p>
        Congratulation! Plugin is ready to use now.
        </div>
        ";
    }


    include('fabfolder-add-new-file.php');
}


function fabfolder_logs()
{
    include('fabfolder-logs-list.php');
}

function getFileErrorMessageFolder($code,$fileName)
{

    switch ($code) {
        case UPLOAD_ERR_INI_SIZE:
            $message = "$fileName - The uploaded file exceeds the upload_max_filesize directive in php.ini";
            break;
        case UPLOAD_ERR_FORM_SIZE:
            $message = "$fileName - The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
            break;
        case UPLOAD_ERR_PARTIAL:
            $message = "$fileName - The uploaded file was only partially uploaded";
            break;
        case UPLOAD_ERR_NO_FILE:
            $message = "$fileName - No file was uploaded";
            break;
        case UPLOAD_ERR_NO_TMP_DIR:
            $message = "$fileName - Missing a temporary folder";
            break;
        case UPLOAD_ERR_CANT_WRITE:
            $message = "$fileName - Failed to write file to disk";
            break;
        case UPLOAD_ERR_EXTENSION:
            $message = "$fileName - File upload stopped by extension";
            break;

        default:
            $message = "$fileName - Unknown upload error";
            break;
    }
    return $message;

}
function fabfolder_menu()
{
    //echo get_option('wpdm_access_level','manage_options');die();
    add_menu_page("Fab Folder Manager","Fab Folder Manager",get_option('wpdm_access_level','manage_options'),'folder-manager','fabfolder_admin_options',plugins_url('fabfolder/img/donwloadmanager-16.png'));
    add_submenu_page( 'folder-manager', 'Fab Folder Manager', 'Listing', get_option('wpdm_access_level','manage_options'), 'folder-manager', 'fabfolder_admin_options');
    add_submenu_page( 'folder-manager', 'Add New File &lsaquo; Fab Folder Manager', 'Add New File', get_option('wpdm_access_level','manage_options'), 'folder-manager/add-new-file', 'fabfolder_add_new_file');
    add_submenu_page( 'folder-manager', 'Logs &lsaquo; Fab Folder Manager', 'Logs', get_option('wpdm_access_level','manage_options'), 'folder-manager/logs', 'fabfolder_logs');
}


//function cartondesign_edit_file()
//{
//    global $wpdb;
//    //todo: function to edit the design layouts
//}

/*
 * function to get carton design types
 */
function fabfolder_types_dropdown_tree()
{
    global $wpdb;
    $table_name = $wpdb->prefix . "carton_design_types";
    $cats = $wpdb->get_results("SELECT * FROM $table_name");
    $typeSelectBoxHtml = '<select><option>Select Type</option>';
    foreach ($cats as $catRow) {
        $typeSelectBoxHtml .= '<option value='.$catRow['id'].'>'.$catRow['name'].'</option>';
    }

    return $typeSelectBoxHtml;
}

function get_fab_carton_types()
{
    global $wpdb;
    $table_name = $wpdb->prefix . "carton_design_types";
    $types = $wpdb->get_results("SELECT * FROM $table_name");
    return $types;
}


function fabfolder_admin_options(){

    if(isset($_GET['success'])&&$_GET['success']==1){
        echo "
        <div id=\"message\" class=\"updated fade\"><p>
        Congratulation! Plugin is ready to use now.
        </div>
        ";
    }
    //if(isset($_GET['task'])) die($_GET['task']);
    if(isset($_GET['task'])&&$_GET['task']=='cartondesign_edit_file')
        return cartondesign_edit_file();
    else
        include('fabfolder-list-files.php');
}


function fabfolder_form()
{
return "
<div class='form_groupfolder_form '>

    <div class='part33'>
    <div class='form_item'>
    <h5>Number Of Pocket?</h5>

    <input type='radio' class='folderRadio' value=1 name='no_of_pockets'> Single Pocket <br/>
    <input type='radio' class='folderRadio' value=2 name='no_of_pockets'> Double Pocket

    </div>
    </div>
    <div class='part33'>
    <div class='form_item'>
    <h5>Gussett?</h5>

    <input type='radio' class='folderRadio' value=1 name='gusset'> Yes <br/>
    <input type='radio' class='folderRadio' value=2 name='gusset'> No

    </div>
    </div>
    <div class='part33'>
    <div class='form_item'>
    <h5>Assembly Type?</h5>

    <input type='radio' class='folderRadio' value=1 name='assembly_type'> Glued <br/>
    <input type='radio' class='folderRadio' value=2 name='assembly_type'> Interlock

    </div>
    </div>




<input type='button' class='search-btn' style='margin-top:20px;' value='Search' id='search_folders'>



</div>
<div id='ajax_loading_content' style='display:none;text-align:center'>
Loading Please Wait <img src='../../img/ajax-loader.gif'/>
</div>
<div id='fab_folder_files' style='display:none'>
Please wait while data is loading................
</div>
<div id='ff_download_form' style='display:none'>
<form id='folder_download_form' method='post' action=''>
            <h2>Register</h2>
            <p style='margin:0; color:#919191;'>Please enter your details and download layout.</p>
            <hr class='hr'>
            <p id='folder_download_form_error'></p>
            <p>
                
                <input class='inputbox' type='text' id='cdfirstname' name='cdfirstname' placeholder='*First Name'/>
            </p>

            <p>
                
                <input class='inputbox' type='text' id='cdlastname' name='cdlastname' placeholder='*Last Name' />
            </p>
            <p>
                
                <input class='inputbox' type='text' id='cdcompanyname' name='cdcompanyname' placeholder='*Company' />
            </p>
            <p>
                
                <input class='inputbox' type='text' id='cdemail' name='cdemail' placeholder='*Email' />
            </p>
            
            <div id='folder_download_url_block'></div>
            <p>
                <input class='submit-btn' type='submit' value='Submit'/>
            </p>


        </form>
</div>
<script>
var folderFirstTime=1;
jQuery(document).ready(function(){
if(folderFirstTime==1)
getFilteredFolderData('','','',1);
folderFirstTime++;
});
</script>
";
}


add_shortcode( 'fabfolder_form_shortcode', 'fabfolder_form' );

add_action("admin_menu","fabfolder_menu");

add_action("init","fabfolder_delete_file");



