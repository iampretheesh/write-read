<?php
/* this file needs to be completely modified or removed and linked with theClass.php file functions through showthis_oop.php file */

session_start();
include_once("../dsn.php");

if(!isset($_SESSION["userid"])) {


echo "Session Expire. Please Login.....";
exit;

}


$page= $_POST['page'];

$limit='10';

if(isset($_POST['limit'])){
								

								$limit = $_POST['limit'];
}

$word='%%';

if(isset($_POST['word'])){

								$word = '%'.$_POST['word'].'%';


}


if($page=='index'){



					$id = $_POST['id'];

					//prepare statement



					$stmt = $pdo->prepare("SELECT * from writings where id=?");

					$stmt->execute([$id]);

					$result = $stmt->fetch(PDO::FETCH_ASSOC);

					$content = $result['content'];
					$title   = $result['title'];
					$cat_id  = $result['cat_id'];


									$stmt_cat = $pdo->prepare('SELECT name from category where id=?');
									//execute query

									$stmt_cat->execute([$cat_id]);
									//fetch result

									$result_cat = $stmt_cat->fetch(PDO::FETCH_ASSOC);

									$cat = $result_cat['name'];


					$content = preg_replace('#<br\s*/?>#i', "\n", $content);



					$ajax_response = array($title,$cat,$content);

					print_r(json_encode($ajax_response));
					exit;

}



else if($page=='title-category'){



									$id = $_POST['id'];

									//prepare statement



									$stmt = $pdo->prepare("SELECT title, cat_id from writings where id=?");

									$stmt->execute([$id]);

									$result = $stmt->fetch(PDO::FETCH_ASSOC);

									$title = $result['title'];

									$cat_id = $result['cat_id'];


									$stmt_cat = $pdo->prepare('SELECT name from category where id=?');
									//execute query

									$stmt_cat->execute([$cat_id]);
									//fetch result

									$result_cat = $stmt_cat->fetch(PDO::FETCH_ASSOC);

									$cat = $result_cat['name'];


									echo $title."-".$cat;

}


else if($page == 'myFiles'){




							$stmt = $pdo->prepare("SELECT id, title FROM writings FORCE INDEX (PRIMARY) where user_id=? and content LIKE ? order by id desc limit ".$limit);

							//execute insert statement query

							$stmt->execute([$_SESSION["userid"],$word]);

							$myFiles = '';

							while($result = $stmt->fetch(PDO::FETCH_ASSOC)){

							$title = preg_replace('#<br\s*/?>#i', "\n", $result["title"]);

							$contentId = $result["id"];

							


							$myFiles.='<div id="col2">'.$title.'</div>

							<div id="col2">dd-mm-yyyy</div>

							<div class="view" onclick="showThis('.$contentId.');" id="col2"><u>View</u></div>

							<div onclick="deleteThis('.$contentId.');" id="col2">Delete</div>';


							}

							echo $myFiles;

}


else if ($page=="autocomplete"){


							//statement query prepare

								$stmt = $pdo->prepare("SELECT * from category");
							//execute statement

								$stmt->execute();

								$autoc = array();

								while($result = $stmt->fetch(PDO::FETCH_ASSOC)){



								array_push($autoc,$result['name']);


								}


								print(json_encode($autoc));


}


else if($page=="category"){


							$id = $_SESSION["userid"];
							$catid = $_POST['catid'];
							//prepare statement



							$stmt = $pdo->prepare("SELECT id, title FROM writings where user_id=? and cat_id=? and content LIKE ? order by id desc limit ".$limit);

							//execute insert statement query

							$stmt->execute([$_SESSION["userid"],$catid,$word]);

							$myFiles = '';

							while($result = $stmt->fetch(PDO::FETCH_ASSOC)){

							$title = preg_replace('#<br\s*/?>#i', "\n", $result["title"]);

							$contentId = $result["id"];



							}

							echo $myFiles;



}

//pagination

else if($page == 'pagination'){


			$limit = $_POST['limit'];
			$offset = $_POST['offset'];
			$userid = $_SESSION["userid"];
			$catid = $_POST['cat'];

			if($catid>-1){ 



							//prepare statement

							$stmt = $pdo->prepare('SELECT id, title from writings FORCE INDEX (PRIMARY) where user_id=? and cat_id=? and content LIKE ?  order by id desc LIMIT '.$limit.' OFFSET '.$offset);			


/*echo "SELECT `id`, `title` from `writings` FORCE INDEX (PRIMARY) where `user_id`=26 and `cat_id`=".$catid." and `content` LIKE ".$word."   order by id desc LIMIT ".$limit." OFFSET ".$offset; exit;*/

							//execute stmt

							$stmt->execute([$userid,$catid,$word]);


			 }

		    else{


					//prepare statement

					$stmt = $pdo->prepare('SELECT id, title from writings FORCE INDEX (PRIMARY) where user_id=? and content LIKE ?  order by id desc LIMIT '.$limit.' OFFSET '.$offset);			


/*echo "SELECT `id`, `title` from `writings` FORCE INDEX (PRIMARY) where `user_id`=26 and `content` LIKE ".$word."   order by id desc LIMIT ".$limit." OFFSET ".$offset; exit;*/

					//execute stmt

					$stmt->execute([$userid,$word]);

			}


							$myFiles = '';
							$count = 0;

							while($result = $stmt->fetch(PDO::FETCH_ASSOC)){

							$title = preg_replace('#<br\s*/?>#i', "\n", $result["title"]);

							$contentId = $result["id"];
							


							$myFiles.='<div id="col2">'.$title.'</div>

							<div id="col2">20-03-2019</div>

							<div class="view" onclick="showThis('.$contentId.');" id="col2"><u>View</u></div>

							<div onclick="deleteThis('.$contentId.');" id="col2">Delete</div>';

							$count++;

							}

							$myFiles = $myFiles.'<input type="hidden" name="count" id="count" value="'.$count.'" />';

							echo $myFiles;

}



else{

							header("location:404.php");
}


?>