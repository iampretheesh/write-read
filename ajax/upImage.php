<?php
if(isset($_SESSION['cart']) || isset($_SESSION['access_token'])) {

               session_destroy();

}
session_start();
include_once('../dsn.php');




if(!isset($_SESSION["userid"]) && $page!='signup'  && $page!='login') {


echo "Session Expire. Please Login..... <a href='login.html'>Login</a>";
exit;

}

$allowedExts = array("gif", "jpeg", "jpg", "png");
$label=time();

$temp = explode(".", $_FILES["file"]["name"]);
$extension = end($temp);

$fileId = $_POST['fileId'];

$time = time();

if ((($_FILES["file"]["type"] == "image/gif")
|| ($_FILES["file"]["type"] == "image/jpeg")
|| ($_FILES["file"]["type"] == "image/jpg")
|| ($_FILES["file"]["type"] == "image/pjpeg")
|| ($_FILES["file"]["type"] == "image/x-png")
|| ($_FILES["file"]["type"] == "image/png"))
&& ($_FILES["file"]["size"] < 2000000)
&& in_array($extension, $allowedExts)) {
    if ($_FILES["file"]["error"] > 0) {
        echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
    } else {
        $filename = $label.$_FILES["file"]["name"];

        if (file_exists("../images/" . $filename)) {
            echo $filename . " already exists. ";
        } else {
            move_uploaded_file($_FILES["file"]["tmp_name"],
            "../images/" . $filename);
         //   echo '<div id="singleImage"><img id="'.$imageId.'" class="'.$imageClass.'" width="100%" height="100%" src="./gallery/'.$filename.'"/><a onclick="this.parentElement.remove();" href="javascript:void(0);">[X]</a></div>';'


            if($_SESSION['insert']==0){
			$stmt = $pdo->prepare("UPDATE writings SET file=?, now=? WHERE id=?");
			$stmt->execute([$filename, $time, $fileId]);

			}

			else {


				$stmt = $pdo->prepare("INSERT INTO writings (user_id, file, now) VALUES(?, ?, ?)");
				$stmt->execute([$_SESSION["userid"], $filename, $time]);
				$fileId = $pdo->lastInsertId();



			}



			$return_array = array('fileName'=>$filename, 'fileId'=>$fileId);

			echo json_encode($return_array);

			exit;

        }
    }
} else {
    echo 'Invalid File';
}
?>