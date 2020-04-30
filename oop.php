<?php
session_start();
print_R($_SESSION);

include_once("queryClass.php");



$writer = new readWrite();

$postArr = array('26','3','%%');
$table ='writings';
$fieldArr = array('id','title','content');
$forceIndex = 'PRIMARY';
$whereArr = 'where user_id=? and cat_id=? and content LIKE ?  order by id desc LIMIT 1';


//$writer->selectmyFiles($table,$fieldArr,$postArr,$forceIndex,$whereArr);



$limit = 40;
$offset = 1;

$whereArr = 'order by id desc LIMIT '.$limit;

$done=$writer->selectmyFiles($table,$fieldArr,$postArr,$forceIndex,$whereArr);



$ik=0; $do='';
foreach ($done as $value) {


$do=$do.$done[$ik]['content']." <br/><br/>|| ";


$ik++;
}

//echo $do;


if(isset($_POST['sub'])){


$writer->insert();


}


?>


<form method="POST" action="">

				<input type="text" name="title" id="title"/>

				<input type="submit" name="sub" value="Submit">


</form>