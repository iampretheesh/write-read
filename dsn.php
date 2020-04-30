<?php

/*$dbDriver='mysql';
$dbHost='www.db4free.net';
$dbName='essays';
$dbUser='pinku123';
$dbPass='pinku123';

*/


                                    $dbDriver='mysql';
                                    $dbHost='localhost';
                                    $dbName='essays';
                                    $dbUser='root';
                                    $dbPass='';
//$charset='';


/*$dsn= "mysql:host=$host;dbname=$db;charset=$charSet";
*/
$dsn = $dbDriver.":host=".$dbHost.";dbname=".$dbName;

try{
 // create a PDO connection with the configuration data
 $pdo = new PDO($dsn, $dbUser, $dbPass);
 
 // display a message if connected to database successfully
 if($pdo){
 //echo "Connected to the <strong>$dbName</strong> database successfully!";
        }
}catch (PDOException $e){
 // report error message
 echo $e->getMessage();
}




?>