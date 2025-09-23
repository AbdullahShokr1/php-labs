<?php 

//Open Connection
//$myConnection = new mysqli('localhost','root',null,'blog');
$myConnection = new pdo('mysql:host=localhost;dbname=blog','root',null);


///Write The Query
// if($myConnection->connect_error){
//     die(''. $myConnection->connect_error);
// }else{
//     var_dump($myConnection->query('SELECT * FROM `users`'));
// }

try{
    $myConnection->query("Insert into `users` (name,email,password) values ('Abdullah','abdulla;h@gmail.com','dvfdvdfvdfvdfv')");
}catch(PDOException $e){
    $e->getMessage();
}


///Close the Connection
//$myConnection->close();