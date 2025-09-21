<?php

//Basic syntax

function add($x,$y){
    return $x+$y;
}

echo add(5,6);

//$_COOKIE=" jjjkbkj";
echo "<pre>";
print_r($_SERVER);
var_dump($GLOBALS);
print_r($_ENV);
echo "</pre>";

function count_(){
    static $count = 0;
    $count++;
    echo $count;
}
count_();
count_();
?>
