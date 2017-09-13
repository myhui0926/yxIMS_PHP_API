<?php
/**
 * Created by PhpStorm.
 * User: hui
 * Date: 2017/9/12
 * Time: 22:40
 */
header('Access-Control-Allow-Origin:http://localhost:8080');
header('Content-Type:application/json;charset=UTF-8');
require '../mysqli_connect.php';
if ($_SERVER['REQUEST_METHOD']=='POST'){
    $errors = array();
    //验证工号：
    if (isset($_POST['job_number']) && preg_match('/^\d{3}$/',$_POST['job_number'])){
        $jn = mysqli_real_escape_string($dbc,htmlentities(trim($_POST['job_number'])));
    }else{
        $errors[] = "请输入正确的工号";
    }

    //验证用户类型:
    if (isset($_POST['user_type']) && preg_match('/^\w{2,5}$/',$_POST['user_type'])){
        $ut = mysqli_real_escape_string($dbc,htmlentities(trim($_POST['user_type'])));
    }else{
        $errors[] = "请输入正确的用户类型";
    }

    //验证用户部门:
    if(isset($_POST['user_depart']) && preg_match('/^.{1,15}$/',$_POST['user_depart'])){
        $ud = mysqli_real_escape_string($dbc,strip_tags(trim($_POST['user_depart'])));
    }else{
        $errors[] = "请选择则正确的所属部门";
    }

    //验证用户姓名：
    if (isset($_POST['username']) && preg_match('/^[\x{4e00}-\x{9fa5}]{2,4}$/u',$_POST['username'])){
        $un = mysqli_real_escape_string($dbc,htmlentities(trim($_POST['username'])));
    }else{
        $errors[] = "请输入正确的用户姓名";
    }

    //验证手机号码:
    if (isset($_POST['user_mobile']) && preg_match('/^1[34578]\d{9}$/',$_POST['user_mobile'])){
        $um = mysqli_real_escape_string($dbc,htmlentities(trim($_POST['user_mobile'])));
    }else{
        $errors[] = "请输入正确的手机号码";
    }

    //验证邮箱地址:
    if (isset($_POST['user_email']) && preg_match('/^[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$/',$_POST['user_email'])){
        $ue = mysqli_real_escape_string($dbc,htmlentities(trim($_POST['user_email'])));
    }else{
        $errors[] = "请输入正确的电子邮件地址";
    }

    //验证密码：
    if(isset($_POST['user_pass']) && preg_match('/^(((?=.*[0-9])(?=.*[a-zA-Z])|(?=.*[0-9])(?=.*[^\s0-9a-zA-Z])|(?=.*[a-zA-Z])(?=.*[^\s0-9a-zA-Z]))[^\s]+)$/',$_POST['user_pass'])){
        if(isset($_POST['user_pass_confirm']) && $_POST['user_pass_confirm']==$_POST['user_pass']){
            $up = mysqli_real_escape_string($dbc,trim($_POST['user_pass']));
        }else{
            $errors[] = "你两次输入的密码不匹配，请重新输入";
        }
    }else{
        $errors[] = "请输入符合要求的密码";
    }

    $register_msg=array(
        'reStatus'=>false,
        'errorMsg'=>array(),
        'successMsg'=>array()
    );
    if (empty($errors)){
        $q = "INSERT INTO users (user_job_number, user_type,department,username, user_mobile, user_email, user_pass, if_confirm) VALUES 
  ($jn,'$ut','$ud','$un','$um','$ue',SHA1('$up'),'yes')";
        $r = @mysqli_query($dbc,$q);
        if ($r){//插入成功
            $register_msg['reStatus'] = true;
            $register_msg['successMsg'][] = '注册成功！点击确认按钮登录。';
        }else{
            $register_msg['errorMsg'][] = '注册失败！请重试。';
        }
    }else{
        $register_msg['errorMsg'][] = '注册失败！错误信息：';
        foreach ($errors as $e){
            $register_msg['errorMsg'][] = $e;
        }
    }
    echo json_encode($register_msg);
}else{
    echo "欢迎使用优选广告库存资源管理系统API！";
}