<?php
session_start();
include_once("../dsn.php");

$id = $_POST['id'];

//prepare statement

$stmt = $pdo->prepare("DELETE FROM writings WHERE id=?");

//execute

$stmt->execute([$id]);



$stmt = $pdo->prepare("SELECT id, title FROM writings where user_id=? order by id desc limit 10");

			//execute insert statement query

			$stmt->execute([$_SESSION["userid"]]);

			$myFiles = '';

while($result = $stmt->fetch(PDO::FETCH_ASSOC)){

			$title = preg_replace('#<br\s*/?>#i', "\n", $result["title"]);

			$contentId = $result["id"];

			


			$myFiles.='<div id="col2">'.$title.'</div>

			<div id="col2">20-03-2019</div>

			<div class="view" onclick="showThis('.$contentId.');" id="col2"><u>View</u></div>

			<div onclick="deleteThis('.$contentId.');" id="col2">Delete</div>';


			

}



echo $myFiles;

?>