<?php
/**
 * Created by PhpStorm.
 * User: hui
 * Date: 2017/9/29
 * Time: 21:51
 */
require '../common/set_header_mysql.php';
$q = "INSERT INTO ads (platform_id, ads_name, ads_total,ads_price,price_unit,remark,create_date) VALUES (?,?,?,?,?,?,NOW())";
$stmt = mysqli_prepare($dbc,$q);
mysqli_stmt_bind_param($stmt,'isiiss',$pfid,$ads,$at,$ap,$pu,$rm);
if ($_SERVER['REQUEST_METHOD']=='POST'){
    //存储错误信息：
    $errors = array();
    //返回给用户的信息：
    $response_msg=array(
        'addStatus'=>true,
        'errorMsg'=>array(),
        'successMsg'=>array()
    );
    $ads_json_str = file_get_contents('php://input');
    $ads_data_obj = json_decode($ads_json_str);
    foreach ($ads_data_obj as $ad_item){
        $pfid = (int) $ad_item->ads_platform;
        $ads = $ad_item->ads_name;
        $at = (int) $ad_item->ads_total;
        $ap = (int) $ad_item->ads_price;
        $pu = $ad_item->price_unit;
        $rm = $ad_item->ads_remark;
        mysqli_stmt_execute($stmt);
        if (mysqli_stmt_affected_rows($stmt) != 1){
            $errors[] = '系统错误，请重试！出错资源：'.$ad_item->ads_name;
            continue;
        }
    }
    //Close the statement:
    mysqli_stmt_close($stmt);
    //Close the connection:
    mysqli_close($dbc);
    if (empty($errors)){
        $response_msg['successMsg'][] = "广告平台添加成功";
        $response_msg['successMsg'][] = "本次共添加 ".count($ads_data_obj)." 个广告资源";
    }else{
        $response_msg['addStatus'] = false;
        foreach ($errors as $e){
            $response_msg['errorMsg'][] = $e;
        }
    }
    echo json_encode($response_msg);
}else{
    echo "欢迎使用优选广告资源库存管理系统-广告资源管理API，请使用ajax调用";
}