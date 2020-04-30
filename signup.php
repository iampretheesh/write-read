<!DOCTYPE html>
<html>
<head>
	<title>READ&WRITE</title>


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



 $(document).ready(function() {

   

            $(document).ajaxStart(function () {

                                    $('.container-fluid').css( "grid-template-rows", "repeat(1, 100vh)" );
                                    $('.container-fluid').css( "font-size", "5vw !important" );
                                    $('.container-fluid').css( "font-weight", "900" );
                                    $('.container-fluid').css( "justify-items", "center" );
                                    $('.container-fluid').css( "align-items", "center" );


                                    $('.container-fluid').html('<blink>loading.....<blink>');


            });    




    var frm = $('.container-fluid');


                frm.submit(function (ev) {



                                    $.ajax({
                                        type: frm.attr('method'),
                                        url: frm.attr('action'),
                                        data: frm.serialize(),
                                        success: function (respo) {
                                            
                                                    $('.container-fluid').css( "grid-template-rows", "repeat(1, 100vh)" );
                                                    $('.container-fluid').css( "font-size", "4vw" );

                                        	$('.container-fluid').html(respo);
                            				$('.container-fluid').style.gridTemplateRows="20vh";


                                        }
                                    });
                                    ev.preventDefault();
                });

});





function validateMe(act){


                                            $('.email').css("outline", "none");
                                            $('input[type=password]').css("outline", "none");




                                let email = $('.email').val();
                                let password = $('.pword').val();
                                let un = $('#un').val();

                                let dotFirst = Number(email.indexOf('.'));
                                let dotLast = Number(email.lastIndexOf('.'));
                                let attherate = Number(email.indexOf("@"));

                                if(dotFirst==dotLast && dotLast>attherate) {   dotLast= Number(dotLast)+Number(dotFirst);  dotFirst = 0; }

                                console.log('@ : firstdot <->dotLast'+attherate+':'+dotFirst+' : '+dotLast);




                                if(email=='' || email.length<4 || email.indexOf('@') < 0 || email.indexOf('.') < 0 || ((dotLast-attherate)<2)|| ((attherate-dotFirst)<2) || email.indexOf(" ")>-1){


                                            $('.email').css("outline", "dashed red 8px");
                                            $('input[type=password]').css("outline", "none");

                                            return false;

                                }


                                else if(password==''){


                                            $('.email').css("outline", "none");
                                            $('input[type=password]').css("outline", "dashed red 8px");

                                            return false;

                                }


                                else if (un==''){

                                            $('.email').css("outline", "dashed red 8px");
                                            $('#un').css("outline", "none");
                                           
                                            $('input[type=password]').css("outline", "dashed red 8px");

                                            return false;

                                }


                                else if(email=='' && password==''){


                                            $('.email').css("outline", "solid red 8px");
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

<form action="ajax/saveThis.php" class="container-fluid" method="POST">

	<?php if(!(isset($_GET['email']))){ ?>


			<div class="heading form"><b><u>Sign Up</u></b></div>

<input type="text" style="display: none;" name="page" id="page" value="signup" />

			<div class="form"><input onmouseover="validateMe('pause');" onmouseout="validateMe('pause');" onclick="validateMe('pause');" onfocus="validateMe('pause');" onkeydown="validateMe('pause');" class="user" id="un" type="text" name="un" placeholder="provide an username" /></div>

			<div class="form"><input onmouseover="validateMe('pause');" onmouseout="validateMe('pause');" onclick="validateMe('pause');" onfocus="validateMe('pause');" onkeydown="validateMe('pause');" class="email" id="em" type="text" name="em" placeholder="provide an email" /></div>

			<div class="form"><input onmouseover="validateMe('pause');" onmouseout="validateMe('pause');" onclick="validateMe('pause');" onfocus="validateMe('pause');" onkeydown="validateMe('pause');" class="pword" id="pa" type="password" name="pa" placeholder="provide a password" /></div>

			<div class="form">



                <button type="button" onclick="validateMe('resume');" id="submit" name="btn">Take Me In</button>



            </div>

                <input style="display: none" class="submitButton" id="submit" type="submit" name="submit" value="Take Me In" />




			<div class="footer form">read-write application by pretheesh all rights reserved&copy;2019</div>

<?php 

}

else

echo "Please check your mail and click the confirmation link to login.";

?>

</div>


</body>
</html>