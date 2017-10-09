<?php
/**
 * Created by PhpStorm.
 * User: hui
 * Date: 2017/10/1
 * Time: 13:47
 */
require '../common/set_header_mysql.php';
if($_SERVER['REQUEST_METHOD']=='POST'){
    //存储错误信息：
    $errors = array();
    //返回给用户的信息：
    $response_msg=array(
        'updateStatus'=>true,
        'errorMsg'=>array(),
        'successData'=>array()
    );
    $data_json_str = file_get_contents("php://input");
    if (!empty($data_json_str)){
        $data_obj = json_decode($data_json_str);
        $st_id = mysqli_real_escape_string($dbc,htmlentities(trim($data_obj->source_type)));
        $pf = mysqli_real_escape_string($dbc,htmlentities(trim($data_obj->platform)));
        $oi = mysqli_real_escape_string($dbc,htmlentities(trim($data_obj->other_info)));
        $pid = mysqli_real_escape_string($dbc,htmlentities(trim($data_obj->platform_id)));
        $q = "UPDATE ad_platform SET source_type_id=$st_id,platform='$pf',other_info='$oi' WHERE platform_id=$pid";
        $r = mysqli_query($dbc,$q);
        if (mysqli_affected_rows($dbc)==1){
            $response_msg['successData']['msg_text'] = "修改成功";
            $response_msg['successData']['source_type_id'] = (int) $st_id;
        }else{
            $errors[] = "系统错误，修改失败！";
        }
    }else{
        $errors[] = "传入的参数有误！";
    }
    if (!empty($errors)){
        $response_msg['updateStatus'] = false;
        $response_msg['errorMsg'][] = "修改失败，错误原因：";
        foreach ($errors as $e){
            $response_msg['errorMsg'][] = $e;
        }
    }
    echo json_encode($response_msg);
}else{
    echo "欢迎使用优选广告资源库存管理系统-修改广告平台API，请使用ajax调用";
}