<?php
/**
 * Created by PhpStorm.
 * User: hui
 * Date: 2017/10/8
 * Time: 17:43
 */
require '../common/set_header_mysql.php';
if ($_SERVER['REQUEST_METHOD']=='GET'){
    //存储错误信息：
    $errors = array();
    //返回给用户的信息：
    $response_msg=array(
        'searchStatus'=>true,
        'errorMsg'=>array(),
        'successMsg'=>array()
    );
    if (isset($_GET['ads_id']) && is_numeric($_GET['ads_id'])){
        $ads_id = (int)$_GET['ads_id'];
        $q = "DELETE FROM ads WHERE ads_id=$ads_id LIMIT 1";
        $r = mysqli_query($dbc,$q);
        if (mysqli_affected_rows($dbc)!=1){
            $errors[] = '系统错误，请重试！';
        }
    }else{
        $errors[] = '参数错误';
    }

    if (empty($errors)){
        $response_msg['successMsg'][] = '删除成功';
    }else{
        $response_msg['searchStatus'] = false;
        foreach ($errors as $e){
            $response_msg['errorMsg'][] = $e;
        }
    }

    echo json_encode($response_msg);
}