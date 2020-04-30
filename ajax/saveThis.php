<?php
if(isset($_SESSION['cart']) || isset($_SESSION['access_token'])) {

               session_destroy();

}
session_start();
include_once('../dsn.php');
include_once('../PHPMailer/PHPMailer.php');

$page = $_POST['page'];



if(!isset($_SESSION["userid"]) && $page!='signup'  && $page!='login') {


echo "Session Expire. Please Login..... <a href='login.html'>Login</a>";
exit;

}


if($page == "signup"){


//prepare statement
$stmt = $pdo->prepare("INSERT INTO user (username, email, password, confirm, confirmkey) VALUES (?, ?, ?, ?, ?)");

$confirm=0;
$confirmkey=md5(microtime(true).mt_Rand());

$un=$_POST['un'];
$em=$_POST['em'];
$pa=md5($_POST['pa']);


$subject = "Registration Confirmation Link";
$body ='<p>Congratulations!</p>';
$body .="<p>Hello ".$un.", Welcome to 'write-read', click this link http://pretheeshpresannan.com/write-read?key=".$confirmkey." to confirm</p>";
// Enter Your Email Address Here To Receive Email
/*$em = "pretheeshgp@gmail.com"; */

$email_to = $em;

$email_from = "admin@pretheeshpresannan.com"; // Enter Sender Email
$sender_name = "Read and Write Space Admin"; // Enter Sender Name
$mail = new PHPMailer();


$mail->IsSMTP();
$mail->Host = "mail.pretheeshpresannan.com"; // Enter Your Host/Mail Server
$mail->SMTPAuth = true;
$mail->Username = "admin@pretheeshpresannan.com"; // Enter Sender Email
$mail->Password = "adavinakam_75";
//If SMTP requires TLS encryption then remove comment from below
//$mail->SMTPSecure = "tls";
$mail->Port = 587;
$mail->IsHTML(true);
$mail->From = $email_from;
$mail->FromName = $sender_name;
$mail->Sender = $email_from; // indicates ReturnPath header
$mail->AddReplyTo($email_from, "No Reply"); // indicates ReplyTo headers
$mail->Subject = $subject;
$mail->Body = $body;
$mail->AddAddress($email_to);
// If you know receiver name use following
//$mail->AddAddress($email_to, "Recepient Name");
// To send CC remove comment from below
//$mail->AddCC('username@email.com', "Recepient Name");
// To send attachment remove comment from below
//$mail->AddAttachment('files/readme.txt');
/*
Please note file must be available on your
host to be attached with this email.
*/
if (!$mail->send()){
    echo "Mailer Error: " . $mail->ErrorInfo.". Please try signup again <a href='signup.php'>here</a>";
    }else{

//executing insert statement

    	$stmt->execute([$un, $em, $pa, $confirm, $confirmkey]);


   		echo "An email has been sent to your email address ".$em.". Please click on the confirmation link on email to login.";


}

}



else if($page=="login"){

//prepare select statement


	$prep = $pdo->prepare("SELECT * FROM user WHERE email=? AND  password=? AND confirm=?");

	$email = $_POST['em'];
	$password = md5($_POST['pa']);
	$prep->execute([$email, $password, 1]);


	$result = $prep->fetch(PDO::FETCH_ASSOC);
	$un = $result["username"];
	$uid = $result["id"];

	if(count($result)>1){

					$_SESSION["username"] = $un;
					$_SESSION["userid"] = $uid;
					echo "login-success";
	}

	else {

					echo "login-fail";
		}




}




else if($page == "index"){



$content = $_POST['inputTextToSave'];
$title = $_POST['title'];
$fileid = $_POST['fileid'];
$userid = $_SESSION["userid"];

$cat_id = 0;

//prepare statement

$stmt_cat = $pdo->prepare('SELECT id from category where name=?');
//execute query

$stmt_cat->execute([$_POST['category']]);
//fetch result

$result_cat = $stmt_cat->fetch(PDO::FETCH_ASSOC);



$cat_id = $result_cat['id'];


if($cat_id==0 || $cat_id==''){


	$stmt = $pdo->prepare("INSERT INTO category (name) VALUES(?)");
	$stmt->execute([$_POST['category']]);

	$cat_id = $pdo->lastInsertId();


}

	if($fileid==''){
	//prepare statement

	$stmt = $pdo->prepare("INSERT INTO writings (user_id, cat_id, title, content) VALUES(?, ?, ?, ?)");
	$stmt->execute([$userid, $cat_id, $title, $content]);

	$fileid=$pdo->lastInsertId();


	$Istmt = $pdo->prepare("SELECT * FROM writings where user_id=? order by id desc limit 1");
	$execId=$userid;



	//execute insert statement query

	$Istmt->execute([$execId]);





	$result = $Istmt->fetch(PDO::FETCH_ASSOC);

	$content = $result["content"];


	$content = preg_replace('#<br\s*/?>#i', "\n", $content);

	$fileId = $result["id"];

	}

	else {


	$stmt = $pdo->prepare("UPDATE writings SET cat_id=?, title=?, content=? WHERE id=?");
	$stmt->execute([$cat_id, $title, $content, $fileid]);



	$Istmt = $pdo->prepare("SELECT * FROM writings where id=?");
	$execId=$fileid;


	//execute insert statement query

	$Istmt->execute([$execId]);





	$result = $Istmt->fetch(PDO::FETCH_ASSOC);

	$content = $result["content"];


	$content = preg_replace('#<br\s*/?>#i', "\n", $content);

	$fileId = $result["id"];

	}


$ajaxArray = array($fileid,$content);



print(json_encode($ajaxArray));


exit;

}



else {

header("location:404.php");

}

?>