<?php
/**
 * Created by PhpStorm.
 * User: hui
 * Date: 2017/9/12
 * Time: 22:19
 */
define('DB_HOST','localhost');
define('DB_USER','root');
define('DB_PASSWORD','root');
define('DB_NAME','yxims');
$dbc = @mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME) OR die('Could not connect to mysql'.mysqli_connect_error());
mysqli_set_charset($dbc,'utf8');