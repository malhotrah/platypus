<?php
/**
 * Created by PhpStorm.
 * User: hitanshu
 * Date: 31/8/14
 * Time: 3:25 PM
 */
echo "hello";
$fileUrl='';
if(isset($_GET['url']))
$fileUrl=$_GET['url'];

if(!empty($fileUrl))
{

    $d = str_replace('\\', '/', dirname(__FILE__));
    $d = explode("/", $d);
    array_pop($d);
    array_pop($d);
    $d = implode('/', $d);

    $data=$d.$fileUrl;

    if(!empty($data))
    {
        header('Content-Description: File Transfer');
        header('Content-Type: application/pdf');
        header("Content-Transfer-Encoding: Binary");
        header('Content-Disposition: attachment; filename='.basename($data));
        readfile($data);
    }
}
?>
