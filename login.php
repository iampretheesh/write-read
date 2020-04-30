<?php
session_start();
include_once("dsn.php");

if(isset($_POST['submit'])){

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
					header("location:index.php");
					exit;
	}

	else {


					header("location:signup.php");
					exit;
		}



	exit;

}

?>
<!DOCTYPE html>
<html>
<head>
	<title>write-read-login</title>

	<link href="https://fonts.googleapis.com/css?family=Michroma&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Lato:300&display=swap" rel="stylesheet">

	<link rel="stylesheet" href="css/style.css" />



  <link rel="stylesheet" href="css/jquery.ui.css">

<script src="js/jquery.min.js"></script>


  <script src="js/jquery-1.12.4.js"></script>
  <script src="js/jquery.ui.js"></script>



<link href="alert/css/alert.css" rel="stylesheet" />
<link href="alert/themes/default/theme.css" rel="stylesheet" />
<script src="alert/js/alert.js"></script>


<script type="text/javascript">
	

function validateMe(act){


											$('input[type=text]').css("outline", "none");
											$('input[type=password]').css("outline", "none");


								let email = $('.email').val();
								let password = $('.pword').val();

								let dotFirst = Number(email.indexOf('.'));
								let dotLast = Number(email.lastIndexOf('.'));
								let attherate = Number(email.indexOf("@"));

								if(dotFirst==dotLast && dotLast>attherate) {   dotLast= Number(dotLast)+Number(dotFirst);  dotFirst = 0; }

								console.log('@ : firstdot <->dotLast'+attherate+':'+dotFirst+' : '+dotLast);




								if(email=='' || email.length<4 || email.indexOf('@') < 0 || email.indexOf('.') < 0 || ((dotLast-attherate)<2)|| ((attherate-dotFirst)<2) || email.indexOf(" ")>-1){


											$('input[type=text]').css("outline", "dashed red 8px");
											$('input[type=password]').css("outline", "none");

											return false;

								}


								else if(password==''){


											$('input[type=text]').css("outline", "none");
											$('input[type=password]').css("outline", "dashed red 8px");

											return false;

								}


								else if(email=='' && password==''){


											$('input[type=text]').css("outline", "solid red 8px");
											$('input[type=password]').css("outline", "solid red 8px");

											return false;

								}




								else {

											if(act=='resume') { $('.submitButton').click(); }

								}


}



</script>


</head>
<body>
<div class="header-grid">
	

				<div class="login-header">

										<span id="header1">Login</span>

				</div>	


				<div onclick="window.location.href='signup.php'" class="login-header">

										<span id="header2">SignUp</span>

				</div>


</div>



<form class="loginForm-grid" method="POST" action="">


<div class="login"><input onmouseover="validateMe('pause');" onmouseout="validateMe('pause');" onclick="validateMe('pause');" onfocus="validateMe('pause');" onkeydown="validateMe('pause');" type="text" class="email" id="loginUser" value="hellovisitor@readwrite.com" name="em" placeholder="type login email here" /></div>

<div class="login"><input onmouseover="validateMe('pause');" onmouseout="validateMe('pause');" onclick="validateMe('pause');" onfocus="validateMe('pause');" onkeydown="validateMe('pause');" type="password" class="pword" id="loginUser" name="pa" value="hellovisitor@readwrite.com" placeholder="type login password here" /></div>

<div class="login"><button type="button" onclick="validateMe('resume');" id="loginBtn" name="btn">Here I Go</button></div>

<input style="display: none" class="submitButton" id="loginBtn" name="submit" type="submit" value="Here I Go" />


<div class="footer">read-write application by pretheesh all rights reserved&copy;2019</div>


</form>	


</body>
</html>