<?php
/**
 * Created by PhpStorm.
 * User: hui
 * Date: 2017/9/29
 * Time: 8:36
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
    if (isset($_GET['st_id']) && is_numeric($_GET['st_id'])){
        $stid = mysqli_real_escape_string($dbc,$_GET['st_id']);
    }else{
        $errors[] = "数据类型有误";
    }
    if (empty($errors)){
        $q = "SELECT platform_id,platform FROM ad_platform WHERE source_type_id=$stid";
        $r = mysqli_query($dbc,$q);
        if (mysqli_num_rows($r)>0){
            while($row = mysqli_fetch_array($r,MYSQLI_ASSOC)){
                $response_msg['successData'][] = $row;
            }
        }else{
            $response_msg['searchStatus'] = false;
            $errors[] = "没有找到记录";
        }
        mysqli_free_result($r);
        mysqli_close($dbc);
    }else{
        $response_msg['searchStatus'] = false;
        foreach ($errors as $e){
            $response_msg['errorMsg'][] = $e;
        }
    }
    echo json_encode($response_msg);
}