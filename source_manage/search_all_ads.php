<?php
/**
 * Created by PhpStorm.
 * User: hui
 * Date: 2017/10/7
 * Time: 12:54
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
    if (isset($_GET['platform_id']) && is_numeric($_GET['platform_id'])){
        $pid = mysqli_real_escape_string($dbc,$_GET['platform_id']);
        $q = "SELECT * FROM ads WHERE platform_id = $pid";
        $r = mysqli_query($dbc,$q);
        if (mysqli_num_rows($r)>0){
            while ($row = mysqli_fetch_array($r,MYSQLI_ASSOC)){
                $response_msg['successData'][] = $row;
            }
        }else{
            $errors[] = '没有返回结果，请重试';
        }
        mysqli_free_result($r);
        mysqli_close($dbc);
    }else{
        $errors[] = '参数错误！';
    }

    if (!empty($errors)){
        $response_msg['searchStatus'] = false;
        foreach ($errors as $e){
            $response_msg['errorMsg'][] = $e;
        }
    }

    echo json_encode($response_msg);
}