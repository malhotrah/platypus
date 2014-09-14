<?php
/*
Plugin Name: Carton Design Download Manager
Plugin URI: #
Description: Download Carton Design and filling form enteries
Author: Hitanshu (hitanshumalhotra@gmail.com, +919958205181)
Author URI: http://hitanshumalhotra.com
Version: 1.0.0
Text Domain: cartondesign
License: GPLv2
*/

define('CDURL', WP_PLUGIN_URL . "/" . dirname(plugin_basename(__FILE__)));

define('CDPATH', WP_PLUGIN_DIR . "/" . dirname(plugin_basename(__FILE__)));

$d = str_replace('\\', '/', dirname(__FILE__));
$d = explode("/", $d);
array_pop($d);
array_pop($d);
$d = implode('/', $d);

define('UPLOAD_DIR_CARTON_DESIGN', $d . '/uploads/carton-designs-files/');
define('UPLOAD_BASE_CARTON_DESIGN', $d . '/uploads/');

function cartondesign_free_install()
{
    global $wpdb;

    $cartonTypesSql = "
    CREATE TABLE IF NOT EXISTS `wp_carton_design` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `companyname` varchar(255) NOT NULL,
  `email` varchar(1000) NOT NULL,
  `url` varchar(1000) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
    ";

    $cartonDesignFilesSql = "CREATE TABLE IF NOT EXISTS `wp_carton_design_layout_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_code` varchar(255) NOT NULL,
  `product_image` varchar(1000) DEFAULT NULL,
  `type_id` int(11) NOT NULL,
  `length` int(11) NOT NULL,
  `width` int(11) NOT NULL,
  `depth` float NOT NULL,
  `layout_url` varchar(1000) NOT NULL,
  `is_active` int(11) NOT NULL DEFAULT '1',
  `added_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

    $cartonDesignLogsTableSql = "
    CREATE TABLE IF NOT EXISTS `wp_carton_design_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `companyname` varchar(255) NOT NULL,
  `email` varchar(1000) NOT NULL,
  `url` varchar(1000) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `type` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
    ";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    $wpdb->query($cartonTypesSql);
    $wpdb->query($cartonDesignFilesSql);
    $wpdb->query($cartonDesignLogsTableSql);


    delete_option('cartondesign_first_install');

    $tf = $wpdb->get_var("select count(*) from `wp_carton_design_layout_files`");
    if ($tf == 0) {
        update_option('cartondesign_install_time', time());
        update_option('cartondesign_first_install', 1);
    }
    carton_design_create_dir();

}


function carton_design_create_dir()
{
    if (!file_exists(UPLOAD_BASE_CARTON_DESIGN)) {
        @mkdir(UPLOAD_BASE_CARTON_DESIGN, 0777);
    }
    @chmod(UPLOAD_BASE_CARTON_DESIGN, 0777);
    @mkdir(UPLOAD_DIR_CARTON_DESIGN, 0777);
    @chmod(UPLOAD_DIR_CARTON_DESIGN, 0777);
    @chmod(dir(__FILE__) . '/cache/', 0777);
    if (isset($_GET['re']) && $_GET['re'] == 1) {
        if (file_exists(UPLOAD_DIR_CARTON_DESIGN)) $s = 1;
        else $s = 0;
        echo "<script>
        location.href='{$_SERVER[HTTP_REFERER]}&success={$s}';
        </script>";
        die();
    }
}

function cartondesign_enqueuescripts()
{

    wp_enqueue_script('cartondesign', CDURL . '/js/cartondesign.js', array('jquery'));
    wp_enqueue_script('fancybox', CDURL . '/js/source/jquery.fancybox.js');
    wp_enqueue_script('fancybox-pack', CDURL . '/js/source/jquery.fancybox.pack.js');
    wp_enqueue_script('fancybox-wheel', CDURL . '/js/lib/jquery.mousewheel-3.0.6.pack.js');

    wp_enqueue_style('fancybox-css', CDURL . '/js/source/jquery.fancybox.css');
    wp_localize_script('cartondesign', 'ajaxcartondesign', array('ajaxurl' => admin_url('admin-ajax.php')));

}

add_action('wp_enqueue_scripts', 'cartondesign_enqueuescripts');


function cartondesign_save_data()

{

    $results = '';

    $error = 0;

    $firstName = "";
    $lastName = "";
    $companyName = "";
    $email = "";
    $url = "#";
    $type='';

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

    if (isset($_POST['type']))
        $type = trim($_POST['type']);

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
        $table_name = $wpdb->prefix . "carton_design_logs";
        $wpdb->insert($table_name, array('firstname' => $firstName, 'lastname' => $lastName, 'email' => $email, 'companyname' => $companyName, 'url' => $url,'type'=>$type));
        die("success");

    } else {
        die($results);
    }


}



function cartondesign_get_data()
{
    $cartonDesignFilter=array();
    $page=1;
    $lengthMin=0;
    $lengthMax=PHP_INT_MAX;
    $widthMin=0;
    $widthMax=PHP_INT_MAX;
    $depthMin=0;
    $depthMax=PHP_INT_MAX;
    $type_id='';
    $pageItems=1;
    $previousPage = 0;
    $nextPage = 0;


    if(isset($_POST['page']) && $_POST['page'])
    $page=$_POST['page'];

    if(isset($_POST['lengthMin']) && trim($_POST['lengthMin'])!='' && is_numeric(trim($_POST['lengthMin'])))
        $lengthMin=trim($_POST['lengthMin']);
    if(isset($_POST['lengthMax']) && trim($_POST['lengthMax'])!='' && is_numeric(trim($_POST['lengthMax'])))
    $lengthMax=trim($_POST['lengthMax']);
    if(isset($_POST['widthMin']) && trim($_POST['widthMin'])!='' && is_numeric(trim($_POST['widthMin'])))
    $widthMin=trim($_POST['widthMin']);
    if(isset($_POST['widthMax']) && trim($_POST['widthMax'])!='' && is_numeric(trim($_POST['widthMax'])))
        $widthMax=trim($_POST['widthMax']);
    if(isset($_POST['depthMin']) && trim($_POST['depthMin'])!='' && is_numeric(trim($_POST['depthMin'])))
        $depthMin=trim($_POST['depthMin']);
    if(isset($_POST['depthMax']) && trim($_POST['depthMax'])!='' && is_numeric(trim($_POST['depthMax'])))
        $depthMax=trim($_POST['depthMax']);
    if(isset($_POST['type_id']) && trim($_POST['type_id'])!='' && is_numeric(trim($_POST['type_id'])))
        $type_id=trim($_POST['type_id']);
    //counting the carton design

    if(!empty($type_id))
    $cond="type_id=$type_id";
    else
    $cond="type_id!=9999";

    $cond.=" and length>=$lengthMin and length<=$lengthMax and width>=$widthMin and width<=$widthMax and depth>=$depthMin and depth<=$depthMax";

    global $wpdb;
    $table_name = $wpdb->prefix . "carton_design_layout_files";


    $count = $wpdb->get_var("SELECT count(*) as total FROM $table_name where $cond");

    if (($page * $pageItems) < (int)$count) {
        $nextPage = $page + 1;
        }
    if ($page > 1)
        $previousPage = $page - 1;

    $limit=($page-1)*$pageItems;
    $results = $wpdb->get_results("SELECT *,$table_name.id as fileId FROM $table_name join wp_carton_design_types on $table_name.type_id=wp_carton_design_types.id where $cond LIMIT $limit,$pageItems ");

    if(!empty($results))
    {
        echo "
        <table width='100%'>
        <thead>
        <th>&nbsp;</th>
        <th>Product Code</th>
        <th>Type</th>
        <th>Dimensions</th>
        <th>Download</th>

        </thead>
        <tbody>
        ";
        foreach($results as $row)
        {
            echo "<tr>
            <td><img src='".site_url()."/wp-content".$row->product_image."' width='120px' height='120px'/></td>
            <td>".$row->product_code."</td>
            <td>".$row->name."</td>
            <td> L: ".$row->length."mm W: ".$row->width."mm D: ".$row->depth."mm </td>
            <td><a href='#' class='cd-download download-btn' data-type-id='$row->name' data-url='".$row->layout_url."'>Download Layout</a></td>
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
        <th>Dimensions</th>
        <th>Download</th>
        </tr>
        </thead>
        <tbody>
        <tr>
        <td colspan='4' style='text-align: center'>
        No Design Layout Found
        </td>
        </tr>
        </tbody>
        </table>";
    }


    exit;
}


add_action('admin_menu', 'my_plugin_menu');

/** Step 1. */
function my_plugin_menu()
{
    add_options_page('Carton Design Entries', 'Carton Design Entries', 'manage_options', 'list-all', 'my_plugin_options');
}

/** Step 3. */
function my_plugin_options()
{
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }
    echo '<div class="wrap">';
    echo getAllEntries();
    echo '</div>';
}


// creating Ajax call for WordPress

add_action('wp_ajax_nopriv_cartondesign_save_data', 'cartondesign_save_data');

add_action('wp_ajax_cartondesign_save_data', 'cartondesign_save_data');
add_action('wp_ajax_cartondesign_get_data', 'cartondesign_get_data');
add_action('wp_ajax_nopriv_cartondesign_get_data', 'cartondesign_get_data');


function getAllEntries()
{
    global $wpdb;
    $table_name = $wpdb->prefix . "carton_design_logs";
    $result = $wpdb->get_results("SELECT * FROM $table_name");
    $output = "<table class='wp-list-table fixed widefat'><thead><tr><td>S.No</td><td>FirstName</td><td>LastName</td><td>CompanyName</td><td>Email</td><td>Added Date</td><td>URL</td></tr></thead><tbody></tbody>";
    foreach ($result as $print) {
        $output .= "<tr><td>$print->id</td><td>$print->firstname</td><td>$print->lastname</td><td>$print->companyname</td><td>$print->email</td><td>$print->time</td><td><a href='$print->url'>Link</a></td></tr>";
    }

    return $output . '</tbody></table>';
}

function cartondesign_delete_file(){
    global $wpdb;
    if(isset($_GET['task']) && $_GET['task'] == 'DeleteFile' && is_admin()){
        if(is_array($_GET['id'])){
            foreach($_GET['id'] as $id){
                $qry[] = "id='".(int)$id."'";
            }
            $cond = implode(" or ", $qry);
        } else
            $cond = "id='".(int)$_GET['id']."'";

        $wpdb->query("delete from wp_carton_design_layout_files where ". $cond);
        wp_redirect('admin.php?page=design-manager');
        die();
    }
}


function cartondesign_add_new_file(){
    global $wpdb;

    $cartonDesignLayout=array();
    $cartonDesignLayout['type_id']='';
    $cartonDesignLayout['product_code']='';
    $cartonDesignLayout['design_width']='';
    $cartonDesignLayout['design_length']='';
    $cartonDesignLayout['design_depth']='';
    if(isset($_POST['action']))
    {
        $errorMsg=array();
     if(isset($_POST['type_id']))
         $cartonDesignLayout['type_id']=$_POST['type_id'];

     if(isset($_POST['design_length']))
         $cartonDesignLayout['design_length']=trim($_POST['design_length']);

        if(isset($_POST['design_width']))
            $cartonDesignLayout['design_width']=trim($_POST['design_width']);

        if(isset($_POST['design_depth']))
            $cartonDesignLayout['design_depth']=trim($_POST['design_depth']);

        if(isset($_POST['product_code']))
            $cartonDesignLayout['product_code']=trim($_POST['product_code']);

        if(empty($cartonDesignLayout['type_id'])||empty($cartonDesignLayout['design_length'])||empty($cartonDesignLayout['design_width'])||empty($cartonDesignLayout['design_depth'])||empty($cartonDesignLayout['product_code']))
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
                    $errorMsg[]=getFileErrorMessage($_FILES['design_image']['error'],'Design Image');
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
                $errorMsg[]=getFileErrorMessage($_FILES['design_layout']['error'],'Design Layout');
            }


            if(!(is_numeric($cartonDesignLayout['design_length']))||!(is_numeric($cartonDesignLayout['design_width']))||!(is_numeric($cartonDesignLayout['design_depth'])))
            {
                $errorMsg[]='Carton Design dimensions(length, width and depth) must be numeric value(decimal allowed)';
            }


        if(empty($errorMsg))
        {
//            include('cartondesign-add-new-file.php');
            //success and save data to db and redirect user to edit screen or list see

            if($is_design_image_exist)
            {
                //move the file to the upload location
                if(file_exists(UPLOAD_DIR_CARTON_DESIGN.$_FILES['design_image']['name']))
                    $designImageFileName = time().'carton_design_'.$_FILES['design_image']['name'];
                else
                    $designImageFileName = $_FILES['design_image']['name'];
                move_uploaded_file($_FILES['design_image']['tmp_name'],UPLOAD_DIR_CARTON_DESIGN.$designImageFileName);
            }

            else{
                $designImageFileName='no-image.jpg';
            }

            //move the file to the upload location
            if(file_exists(UPLOAD_DIR_CARTON_DESIGN.$_FILES['design_layout']['name']))
                $designLayoutFileName = time().'carton_design_'.$_FILES['design_layout']['name'];
            else
                $designLayoutFileName = $_FILES['design_layout']['name'];
            move_uploaded_file($_FILES['design_layout']['tmp_name'],UPLOAD_DIR_CARTON_DESIGN.$designLayoutFileName);


            global $wpdb;
            $table_name = $wpdb->prefix . "carton_design_layout_files";
            $wpdb->insert($table_name, array('product_code' => $cartonDesignLayout['product_code'],
                'type_id'=>$cartonDesignLayout['type_id'],
                'length'=>$cartonDesignLayout['design_length'],
                'width'=>$cartonDesignLayout['design_width'],
                'depth'=>$cartonDesignLayout['design_depth'],
                'product_image'=>'/uploads/carton-designs-files/'.$designImageFileName,
                'layout_url'=>'/uploads/carton-designs-files/'.$designLayoutFileName
             ));
            //header redirect the user to edit page
            wp_redirect('admin.php?page=design-manager');
            die();
        }

    }

    if(!file_exists(UPLOAD_DIR_CARTON_DESIGN)){
        carton_design_create_dir();
    }

    if(!file_exists(UPLOAD_DIR_CARTON_DESIGN)){

//        carton_design_create_dir();
        echo "
        <div id=\"warning\" class=\"error fade\"><p>
        Automatic dir creation failed! [ <a href='admin.php?page=file-manager&task=wpdm_create_dir&re=1'>Try again to create dir automatically</a> ]<br><br>
        Please create dir <strong>" . UPLOAD_DIR_CARTON_DESIGN . "</strong> manualy and set permision to <strong>777</strong><br><br>
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


    include('cartondesign-add-new-file.php');
}


function cartondesign_logs()
{
    include('cartondesign-logs-list.php');
}

function getFileErrorMessage($code,$fileName)
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
function cartondesign_menu()
{
    //echo get_option('wpdm_access_level','manage_options');die();
    add_menu_page("Carton Design Manager","Carton Design Manager",get_option('wpdm_access_level','manage_options'),'design-manager','cartondesign_admin_options',plugins_url('cartondesign/img/donwloadmanager-16.png'));
    add_submenu_page( 'design-manager', 'Carton Design Manager', 'Listing', get_option('wpdm_access_level','manage_options'), 'design-manager', 'cartondesign_admin_options');
    add_submenu_page( 'design-manager', 'Add New File &lsaquo; Carton Design Manager', 'Add New File', get_option('wpdm_access_level','manage_options'), 'design-manager/add-new-file', 'cartondesign_add_new_file');
    add_submenu_page( 'design-manager', 'Logs &lsaquo; Carton Design Manager', 'Logs', get_option('wpdm_access_level','manage_options'), 'design-manager/logs', 'cartondesign_logs');
}


function cartondesign_edit_file()
{
    global $wpdb;
    //todo: function to edit the design layouts
}

/*
 * function to get carton design types
 */
function cartondesign_types_dropdown_tree()
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

function get_carton_types()
{
    global $wpdb;
    $table_name = $wpdb->prefix . "carton_design_types";
    $types = $wpdb->get_results("SELECT * FROM $table_name");
    return $types;
}


function cartondesign_admin_options(){

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
        include('cartondesign-list-files.php');
}


function cartondesign_form()
{
return "
<script>
var cartonDesignFirstTime=1;
jQuery(document).ready(function(){
if(cartonDesignFirstTime==1)
getFilteredData('','','','','','','',1);
cartonDesignFirstTime++;
});
</script>
<div class='form_group'>
	<div class='part60'>
	<div class='form_item'>
			<label><strong>Type</strong></label>	
			<select name='type_id' id='type_id'>
                        <option value=''>-- All --</option>
                        <option value='1'>Crash Lock</option>
                        <option value='2'>H Lock</option>
                        <option value='3'>Mailers</option>
                        <option value='4'>Straight Line</option>
                        <option value='5'>Trays &amp; Lids</option>
                        </select>

	</div>	

	<div class='form_item'>

<label>LENGTH MIN: </label><input type='text' id='length_min'><label>LENGTH MAX:  </label><input type='text' id='length_max'>
	</div>	
		
	<div class='form_item'>

<label>WIDTH MIN: </label><input type='text' id='width_min'><label>WIDTH MAX:  </label><input type='text' id='width_max'>

	</div>	


<div class='form_item'>

<label>DEPTH MIN: </label><input type='text' id='depth_min'><label>DEPTH MAX:  </label><input type='text' id='depth_max'>

	</div>

<div class='form_item'>
<input type='button' class='search-btn' value='Search' id='search_layout'>
</div>
 



	</div>
	<div class='part40'>
			<div style='float:left;width:30%;'>
			Carton dimensions are always given as <em><strong>Length x Width x Depth</strong></em>,
                        these are more commonly refered to as <em><strong>A x B x C.</strong></em> 

			</div>
			<div style='float:right'>
			<img alt='' src='".site_url()."/img/carton_designs/carton-dimensions.jpg' style='border: 0px none;'>
			</div>

	</div>
</div>

<div id='ajax_loading_content' style='display:none;text-align:center'>
Loading Please Wait <img src='../../img/ajax-loader.gif'/>
</div>
<div id='carton_design_files' style='display:none'>
Please wait while data is loading................
</div>
<div id='cd_download_form' style='display:none'>
<form id='download_form' method='post' action=''>
            <h2>Register</h2>
            <p style='margin:0; color:#919191;'>Please enter your details and download layout.</p>
            <hr class='hr'>
            <p id='download_form_error'></p>
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
            
            <div id='download_url_block'></div>
            <p>
                <input class='submit-btn' type='submit' value='Submit'/>
            </p>


        </form>
</div>";
}


add_shortcode( 'cartondesign_form_shortcode', 'cartondesign_form' );

add_action("admin_menu","cartondesign_menu");

add_action("init","cartondesign_delete_file");



