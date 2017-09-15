<?php
/**
 * Created by PhpStorm.
 * User: hui
 * Date: 2017/9/16
 * Time: 1:02
 */
function testFunArray($testArray){
    $testArray['name'][] = 'SONG';
    $testArray['name'][] = 'Chaohui';
    foreach ($testArray['name'] as $t){
        echo '-'.$t.'<br>';
    }
    echo time();
    echo date_default_timezone_get();
}

$myArray = array(
    'name'=>array(),
    'sex'=>'Chaohui'
);

testFunArray($myArray);