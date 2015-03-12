<?php
$a = array(1,2,3,5);
$b = array(1,2);
$c = array(1,4,2,5);

$wrkArray = array($a, $b, $c);
var_dump($wrkArray);
$result = call_user_func_array('array_intersect',$wrkArray);
var_dump($result);

?>