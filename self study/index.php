<?php 

echo "Hello World!";

//Not case Sensitive in constructs, function names, and class names
function mycount(){
    return 5+19;
}

eCho("<br> my count Function =>".MycoUnt()."<br>");


///Case sensitive
$count =10;
$Count=20;
echo $count ."".$Count;

?>



<? ///the next not need to write ; because the closing tag of php enough ?>
<?php echo $Count?>


<?= $count ?>


<?php 

//Two way to define constant variable

define('PI',3.14);

const NAME = "Abdullah Shokr";

//define can define in condition
if(true){
   define('PI2',3.14); 
}
//const can't define in condition
if(true){
   //const NAME = "Abdullah Shokr";
}
echo("<br><br>===================================");
define('PREFIX', 'OPTION');
define(PREFIX . '_1', 1);
define(PREFIX . '_2', 2);
define(PREFIX . '_3', 3);
echo PREFIX; //OPTION
echo OPTION_1; // 1
echo OPTION_2; // 2
echo OPTION_3; // 3

echo("<br><br>===================================");
$balance = 100;
$message = 'Insufficient balance';

var_dump($balance);
var_dump($message);
die($message);
////this will not do becausse die exit the app
echo("After calling the die function");