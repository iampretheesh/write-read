<?php
session_start();
include_once("../theClass.php");
$db = new dbOperations();


if(isset($_SESSION['counter'])){

				unset($_SESSION['counter']);
				$_SESSION['counter']=$_POST['counter'];

}



if(!isset($_SESSION["userid"])) {


echo "Session Expire. Please Login.....";
exit;

}


$page= $_POST['page'];









//pagination

if($page == 'pagination'){


							$result=$db->selectForPagination();
							$myFiles = '';
							$count = count($result);




if($count==0) { $myFiles.="No More Results Found. Please Go Back";   } 


							foreach ($result as $key1 => $value1) {



																	$title=$value1['title'];
																	$contentId=$value1['id'];

																	$myFiles.='<div id="col2">'.$title.'</div>

																	<div class="view" onclick="showThis('.$contentId.');" id="col2"><u>View</u></div>

																	<div onclick="deleteThis('.$contentId.');" id="col2">Delete</div>';





							}


							$myFiles = $myFiles.'<input type="hidden" name="count" id="count" value="'.$count.'" />';

							echo $myFiles;


}



else if($page == "index"){



$content = $_POST['textArea'];
$title = $_POST['title'];
$fileid = $_POST['fileid'];
$imageName= $_POST['imageName'];
$userid = $_SESSION["userid"];
$fileVal = $_POST["fileVal"];


$cat_id = 0;


$cat_id = $db->selectCatId();



					if($cat_id==0 || $cat_id==''){



						$cat_id = $db->insertCategory();


					}






					if($fileid=='' && $_SESSION['insert']==1){
					//prepare statement

//echo "I AM INSERTING"; exit;

					$result = $db->insertWriting($cat_id);	

					$content = $result["content"];



					$fileId = $result["id"];

					$catName = $result["catName"];

					$title = $result["title"];

					$sessionInsert = $result['sessionInsert'];



						if($sessionInsert==0){

						$action = 'update';



						}

						else {

						$action = 'insert';

						}



					}


					else {


//echo "I AM UPDATING"; exit;


							$result = $db->updateWriting($cat_id,$fileid);

							$content = $result["content"];


							$fileId = $result["id"];

							$catName = $result["catName"];

							$title = $result["title"];
					
							$sessionInsert = $result['sessionInsert'];

							$action = 'update';


					}


$ajaxArray = array("k1"=>$fileId,"k2"=>$content,"k3"=>$title,"k4"=>$catName,"k5"=>$sessionInsert,"k6"=>$action);



print(json_encode($ajaxArray));


exit;

}


else if($page == 'viewThisFile'){




					$ajax_response = $db->viewThisFile();

					print_r(json_encode($ajax_response));
					exit;


}




else{

							header("location:404.php");
}

?>