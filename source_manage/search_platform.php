<?php
/**
 * Created by PhpStorm.
 * User: hui
 * Date: 2017/9/30
 * Time: 15:14
 */
require '../common/set_header_mysql.php';
if ($_SERVER['REQUEST_METHOD']=='GET'){
    //存储错误信息：
    $errors = array();
    //返回给用户的信息：
    $response_msg=array(
        'searchStatus'=>true,
        'errorMsg'=>array(),
        'successData'=>array()
    );
    if (isset($_GET['source_type']) && is_numeric($_GET['source_type'])){
         $sid = mysqli_real_escape_string($dbc,$_GET['source_type']);
         $q = "SELECT * FROM ad_platform WHERE source_type_id=$sid";
         $r = mysqli_query($dbc,$q);
             if (mysqli_num_rows($r)>0){
                 while($row = mysqli_fetch_array($r,MYSQLI_ASSOC)) {
                     $response_msg['successData'][] = $row;
                 }
             }else{
                 $errors[] = "没有返回数据";
             }
         }else{
            $errors[] = "数据类型有误";
        }
    if (!empty($errors)){
        $response_msg['searchStatus'] = false;
        foreach ($errors as $e){
            $response_msg['errorMsg'][] = $e;
        }
    }
    echo json_encode($response_msg);
}