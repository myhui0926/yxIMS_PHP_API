<?php
/**
 * Created by PhpStorm.
 * User: hui
 * Date: 2017/9/27
 * Time: 19:04
 */
require '../common/set_header_mysql.php';
$q = "INSERT INTO ad_platform (source_type_id, platform,other_info) VALUES (?,?,?)";
$stmt = mysqli_prepare($dbc,$q);
mysqli_stmt_bind_param($stmt,'iss',$st,$pf,$oi);
if ($_SERVER['REQUEST_METHOD']=='POST'){
    //存储错误信息：
    $errors = array();
    //返回给用户的信息：
    $response_msg=array(
        'addStatus'=>true,
        'errorMsg'=>array(),
        'successMsg'=>array()
    );
    $plat_json_str = file_get_contents('php://input');
    $plat_data_obj = json_decode($plat_json_str);
    foreach ($plat_data_obj as $pd_item){
        $st = (int) $pd_item->source_type;
        $pf = $pd_item->plat_name;
        $oi = $pd_item->other_info;
        mysqli_stmt_execute($stmt);
        if (mysqli_stmt_affected_rows($stmt) != 1){
            $errors[] = '你插入的广告平台可能已经存在';
            $errors[] = '重复的平台名为：'.$pd_item->plat_name;
            continue;
        }
    }
    if (empty($errors)){
        $response_msg['successMsg'][] = "广告平台添加成功";
        $response_msg['successMsg'][] = "本次共添加 ".count($plat_data_obj)." 个广告平台";
    }else{
        $response_msg['addStatus'] = false;
        foreach ($errors as $e){
            $response_msg['errorMsg'][] = $e;
        }
    }
    mysqli_stmt_close($stmt);
    mysqli_close($dbc);
    echo json_encode($response_msg);
}else{
    echo "欢迎使用优选广告资源库存管理系统-广告平台管理API，请使用ajax调用";
}