<?php
/**
 * Created by PhpStorm.
 * User: hui
 * Date: 2017/9/15
 * Time: 19:33
 */
if($_SERVER['REQUEST_METHOD']=='POST'){
    if ($_COOKIE['user_id']){
        $uid = $_COOKIE['user_id'];
    }
    if ($_COOKIE['user_pass']){
        $up = $_COOKIE['user_pass'];
    }
    echo $uid;
    echo $up;
}