<?php
if(!defined('ABSPATH')) die('Direct Access is not Allowed!');
global $wpdb;
$limit = 2;

$start = isset($_GET['paged'])?(($_GET['paged']-1)*$limit):0;
$carton_design_files_table_name = $wpdb->prefix . "carton_design_logs";
//$res = $wpdb->get_results("select * from ahm_files $cond order by id desc limit $start, $limit",ARRAY_A);
$res = $wpdb->get_results("select *from $carton_design_files_table_name limit $start, $limit",ARRAY_A);

$row = $wpdb->get_row("select count(*) as total from $carton_design_files_table_name $cond",ARRAY_A);
?>


<div class="wrap">
    <div class="icon32" id="icon-upload"><br></div>

    <h2>Logs

        <br />

    </h2>
    <form method="get" action="" id="posts-filter">

        <div class="clear"></div>

        <table cellspacing="0" class="widefat fixed">
            <thead>
            <tr>

                <th>First Name</th>
                <th>Last Name</th>
                <th>Company Name</th>
                <th>Email</th>
                <th>Design Link</th>
                <th>Added Date</th>

            </tr>
            </thead>

            <tbody class="list:post" id="the-list">
            <?php foreach($res as $logRow) {
                ?>
                <tr valign="top" class="alternate author-self status-inherit" id="post-8">

                    <td class="author column-author"><?php echo $logRow['firstname']; ?></td>
                    <td class="author column-author"><?php echo $logRow['lastname']; ?></td>
                    <td class="author column-author"><?php echo $logRow['companyname']; ?></td>
                    <td class="author column-author"><?php echo $logRow['email']; ?></td>

                    <td class="author column-author"><?php
                        echo '<a href=\''.site_url().'/wp-content'.$logRow['url'].'\' target="_blank"/>Link</a>';?></td>
                    <td class="author column-author"><?php echo $logRow['time']; ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>

        <?php
        $paged = isset($_GET['paged']) ?$_GET['paged'] :1;

        $page_links = paginate_links( array(
            'base' => add_query_arg( 'paged', '%#%' ),
            'format' => '',
            'prev_text' => __('&laquo;'),
            'next_text' => __('&raquo;'),
            'total' => ceil($row['total']/$limit),
            'current' => $paged
        ));


        ?>

        <div id="ajax-response"></div>

        <div class="tablenav">

            <?php
            if ( $page_links ) {

                ?>
                <div class="tablenav-pages"><?php $page_links_text = sprintf( '<span class="displaying-num">' . __( 'Displaying %s&#8211;%s of %s' ) . '</span>%s',
                        number_format_i18n( ( $paged - 1 ) * $limit + 1 ),
                        number_format_i18n( min( $paged * $limit, $row['total'] ) ),
                        number_format_i18n( $row['total'] ),
                        $page_links
                    ); echo $page_links_text; ?></div>
            <?php } ?>

            <br class="clear">
        </div>

    </form>
    <br class="clear">

</div>

 