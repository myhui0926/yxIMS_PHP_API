<?php
/**
 * Created by PhpStorm.
 * User: hui
 * Date: 2017/10/8
 * Time: 0:23
 */
require '../common/set_header_mysql.php';
if ($_SERVER['REQUEST_METHOD']=='POST'){
    //存储错误信息：
    $errors = array();
    //返回给用户的信息：
    $response_msg=array(
        'updateStatus'=>true,
        'errorMsg'=>array(),
        'successData'=>array()
    );
    $data_json_str = file_get_contents('php://input');
    if (!empty($data_json_str)){
        $data_obj = json_decode($data_json_str);
        $aid = (int)$data_obj->ads_id;
        $an = mysqli_real_escape_string($dbc,htmlentities(trim($data_obj->ads_name)));
        $at = (int)$data_obj->ads_total;
        $ap = (int)$data_obj->ads_price;
        $pu = mysqli_real_escape_string($dbc,htmlentities(trim($data_obj->price_unit)));
        $rm = mysqli_real_escape_string($dbc,htmlentities(trim($data_obj->remark)));

        $q = "UPDATE ads SET ads_name='$an',ads_total=$at,ads_price=$ap, price_unit='$pu',remark='$rm' WHERE ads_id = $aid";
        $r = mysqli_query($dbc,$q);
        if (mysqli_affected_rows($dbc)==1){
            $response_msg['successData'][] = '修改成功！';
            mysqli_close($dbc);
        }else{
            $errors[] = '系统错误，请重试！';
        }
    }else{
        $errors[] = '传入的参数有误！';
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
    echo "欢迎使用优选广告资源库存管理系统-修改广告资源API，请使用ajax调用";
}