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


function cartondesign_enqueuescripts()
{

    wp_enqueue_script('cartondesign', CDURL . '/js/cartondesign.js', array('jquery'));
    wp_enqueue_script('fancybox', CDURL . '/js/source/jquery.fancybox.js');
    wp_enqueue_script('fancybox-pack', CDURL . '/js/source/jquery.fancybox.pack.js');
    wp_enqueue_script('fancybox-wheel', CDURL . '/js/lib/jquery.mousewheel-3.0.6.pack.js');

    wp_enqueue_style( 'fancybox-css', CDURL . '/js/source/jquery.fancybox.css' );
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
    $url="#";

    if (isset($_POST['cdfirstname']))
        $firstName = trim($_POST['cdfirstname']);

    if(isset($_POST['cdlastname']))
        $lastName=trim($_POST['cdlastname']);

    if(isset($_POST['cdcompanyname']))
        $companyName=trim($_POST['cdcompanyname']);

    if(isset($_POST['cdemail']))
        $email=trim($_POST['cdemail']);

    if(isset($_POST['url']))
        $url=trim($_POST['url']);

    if (empty($firstName) && empty($lastName) && empty($companyName) && empty($email))
    {
        $results="Please fill require fields";
        $error=1;
    }

    else
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $results = $email . " :email address is not valid.";
            $error = 1;
        }
    }


    if(!$error)
    {
        //submit data to database
        global $wpdb;
        $table_name = $wpdb->prefix . "carton_design";
        $wpdb->insert( $table_name, array( 'firstname' => $firstName, 'lastname' =>$lastName,'email'=>$email,'companyname'=>$companyName,'url'=>$url ));
        die("success");

    }
    else
    {
        die($results);
    }


}


add_action( 'admin_menu', 'my_plugin_menu' );

/** Step 1. */
function my_plugin_menu() {
    add_options_page( 'Carton Design Entries', 'Carton Design Entries', 'manage_options', 'list-all', 'my_plugin_options' );
}

/** Step 3. */
function my_plugin_options() {
    if ( !current_user_can( 'manage_options' ) )  {
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }
    echo '<div class="wrap">';
    echo getAllEntries();
    echo '</div>';
}




// creating Ajax call for WordPress

add_action( 'wp_ajax_nopriv_cartondesign_save_data', 'cartondesign_save_data' );

add_action( 'wp_ajax_cartondesign_save_data', 'cartondesign_save_data' );


function getAllEntries()
{
global $wpdb;
$table_name = $wpdb->prefix . "carton_design";
$result = $wpdb->get_results ( "SELECT * FROM $table_name" );
$output="<table class='wp-list-table fixed widefat'><thead><tr><td>S.No</td><td>FirstName</td><td>LastName</td><td>CompanyName</td><td>Email</td><td>Added Date</td><td>URL</td></tr></thead><tbody></tbody>";
foreach ( $result as $print )   {
$output.="<tr><td>$print->id</td><td>$print->firstname</td><td>$print->lastname</td><td>$print->companyname</td><td>$print->email</td><td>$print->time</td><td><a href='$print->url'>Link</a></td></tr>";
}

    return $output.'</tbody></table>';
}





