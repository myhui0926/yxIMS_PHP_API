<?php
/**
 * Created by PhpStorm.
 * User: hui
 * Date: 2017/9/14
 * Time: 18:07
 */
header('Access-Control-Allow-Origin:http://localhost:8080');
header('Content-Type:application/json;charset=UTF-8');
require '../mysqli_connect.php';
if ($_SERVER['REQUEST_METHOD']=='POST') {
    //存储错误信息：
    $errors = array();
    //登录后返回给用户的信息：
    $login_msg=array(
        'loStatus'=>true,
        'errorMsg'=>array(),
        'successMsg'=>array()
    );
    if (!empty($_COOKIE['user_id']) && !empty($_COOKIE['user_job_number']) && !empty($_COOKIE['username']) && !empty($_COOKIE['user_pass'])){
        //检查用户是否已经登陆，查看是否为cookie欺骗：
        $uid = $_COOKIE['user_id'];
        $ujn = $_COOKIE['user_job_number'];
        $un = $_COOKIE['username'];
        $up = $_COOKIE['user_pass'];
        $q = "SELECT user_id FROM users WHERE user_id=$uid && user_job_number='$ujn' && username='$un' && user_pass='$up'";
        $r = mysqli_query($dbc,$q);
        if (mysqli_num_rows($r)==1){
            $login_msg['successMsg'][] = "您已经登陆";
        }else{
            $login_msg['loStatus'] = false;
            $login_msg['errorMsg'][] = "无效的cookie，请重新登录。";
        }
        mysqli_free_result($r);
        mysqli_close($dbc);
    }else{//如果不存在cookie，需要登录：
        //根据登录类型选择验证方式：
        switch ($_POST['login_type']){
            case 'job_num':
                //验证工号：
                if (isset($_POST['job_number']) && preg_match('/^\d{3}$/', $_POST['job_number'])) {
                    $jn = mysqli_real_escape_string($dbc, htmlentities(trim($_POST['job_number'])));
                } else {
                    $errors[] = "请输入正确的工号";
                }
                break;
            case 'mobile':
                //验证手机号码:
                if (isset($_POST['user_mobile']) && preg_match('/^1[34578]\d{9}$/', $_POST['user_mobile'])) {
                    $um = mysqli_real_escape_string($dbc, htmlentities(trim($_POST['user_mobile'])));
                } else {
                    $errors[] = "请输入正确的手机号码";
                }
                break;
            case 'email':
                //验证邮箱地址:
                if (isset($_POST['user_email']) && preg_match('/^[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$/', $_POST['user_email'])) {
                    $ue = mysqli_real_escape_string($dbc, htmlentities(trim($_POST['user_email'])));
                } else {
                    $errors[] = "请输入正确的电子邮件地址";
                }
                break;
            default:
                $errors[] = "必须使用正确的登录方式";
                break;
        }

        //验证密码：
        if (isset($_POST['user_pass']) && preg_match('/^(((?=.*[0-9])(?=.*[a-zA-Z])|(?=.*[0-9])(?=.*[^\s0-9a-zA-Z])|(?=.*[a-zA-Z])(?=.*[^\s0-9a-zA-Z]))[^\s]+)$/', $_POST['user_pass'])) {
            $up = mysqli_real_escape_string($dbc, trim($_POST['user_pass']));
        } else {
            $errors[] = "请输入符合要求的密码";
        }

        if (empty($errors)){// 所有状态都正确
            if (isset($up)){
                if(isset($jn)){
                    $login_msg = loginHandler($dbc,'user_job_number',$jn,$up,$login_msg);
                }elseif (isset($um)){
                    $login_msg = loginHandler($dbc,'user_mobile',$um,$up,$login_msg);
                }elseif (isset($ue)){
                    $login_msg = loginHandler($dbc,'user_email',$ue,$up,$login_msg);
                }
            }
        }else{
            $login_msg['loStatus'] = false;
            $login_msg['errorMsg'][] = "登录失败，请查看错误信息：";
            foreach ($errors as $e){
                $login_msg['errorMsg'][] = $e;
            }
        }
    }
    echo json_encode($login_msg);//打印登录信息
}else{
    echo "欢迎使用优选广告库存资源管理系统用户登录API！请使用Ajax调用。";
}

function loginHandler($dataBase,$loginType,$loginValue,$userPass,$loginMsg){
    $q = "SELECT user_id,user_job_number,username,user_pass FROM users WHERE $loginType='$loginValue' && user_pass=sha1('$userPass')";
    $r = mysqli_query($dataBase,$q);
    if (mysqli_num_rows($r)==1){
        $loginMsg['successMsg'][] = '登录成功，正在跳转！';
        $row = mysqli_fetch_array($r,MYSQLI_ASSOC);
        foreach ($row as $key=>$value){
            setcookie($key,$value,time()+3600,'/','',0,0);
        }
        return $loginMsg;
    }else{
        $loginMsg['loStatus'] = false;
        $loginMsg['errorMsg'][] = "账号或密码错误，请重试！";
        return $loginMsg;
    }
    mysqli_free_result($r);
    mysqli_close($dataBase);
}