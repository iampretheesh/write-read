<?php
session_start();
include_once("theClass.php");

$dbo = new dbOperations();


$catList=$dbo->selectAllCat();




if(isset($_GET['key'])){


						session_destroy();

						$key = $_GET['key'];


						$result=$dbo->selectForSignup($key);

								$un = $result["username"];
								$uid = $result["id"];


						if(isset($uid) && isset($un)){


								session_start();
								$dbo->updateAfterSignUp($uid,$un);

						}


						else {

								session_destroy();
								header("location:404.php");

						}

}


if(!isset($_SESSION['userid'])) { header("location:login.html");  }


/* form submit of textarea writings begins*/

/* form submit of textarea writings ends*/

$content = "";
$fileId = "";


if(isset($_SESSION["username"])){


$_SESSION['insert']=1;

$imageName='';
$title='';
$content='';
$fileId='';
$fileVal = '';

//prepare select statement

$result = $dbo->selectLastResult();

			if(isset($result['id'])) {

			$_SESSION['insert']=0;

			$content = $result["content"];
			$title = $result["title"];

			$cat_id = $result["cat_id"];

			if($result['file']!=''){

			$fileVal = $result['file'];

			$imageName = './images/'.$result["file"];

			}

			//prepare statement

			$catName=$dbo->selectCatName($cat_id);

			$category = $catName;

			$content = preg_replace('#<br\s*/?>#i', "\n", $content);

			$fileId = $result["id"];

			}


}



?>
<!DOCTYPE html>
<html>
<head>
	<title>READ&WRITE</title>

 <meta name="viewport" content="width=device-width, initial-scale=1">



<link rel="stylesheet" type="text/css" href="css/style.css"/>

  <link rel="icon" href="images/write.png" type="image/png" sizes="16x16">



<link href="https://fonts.googleapis.com/css?family=Michroma&display=swap" rel="stylesheet">

<link href="https://fonts.googleapis.com/css?family=Lato:300&display=swap" rel="stylesheet">

<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.1/css/all.css" integrity="sha384-O8whS3fhG2OnA5Kas0Y9l3cfpmYjapjI0E4theH4iuMD+pLhbf6JI0jIMfYcK3yZ" crossorigin="anonymous">  

<link rel="stylesheet" href="css/jquery.ui.css">


<script src="js/jquery.min.js"></script>


  <script src="js/jquery-1.12.4.js"></script>
  <script src="js/jquery.ui.js"></script>



<link href="alert/css/alert.css" rel="stylesheet" />
<link href="alert/themes/default/theme.css" rel="stylesheet" />

<script src="alert/js/alert.js"></script>



<script type="text/javascript">
	



let globalRequest = 'ToCancelPrevReq';
let saveRequest;
let counter = 0;
let icA = []
let max;
let timerCount = 0;
let page = 1;
let currentOff;
let offset;
let count;
let limit;

let globalLoadingMessage = '<div id="col2">Please</div>'+

							'<div id="col2">Wait</div>'+

							'<div id="col2">Loading</div>'+

							'<div id="col2"><blink>....</blink></div>';


let globalFetchingMessage = '<div id="col2">Fetching</div>'+

							'<div id="col2">Your</div>'+

							'<div id="col2">Results</div>'+

							'<div id="col2"><blink>....</blink></div>';		



function showThis(id){

	
						    var $myFiles = $('#my-files');
						    $myFiles.hide();
						    $(".writer-grid").show();


			  				$('#title').val('');
			  				$('#category').val('');

			  				$('#my-title').val('loading....');
			  				$('#tag').val('loading....');						    

			 				$('.inputTextToSave').val('loading....');



							var request = $.ajax({
							  url: "ajax/showThis_oop.php",
							  method: "POST",
							  data: { id : id, page: 'viewThisFile' },
							  dataType: "json"
							});
							 
							request.done(function( array ) {



							  				$('#title').val( array[0] );
							  				$('#category').val( array[1] );

							  				$('#my-title').val( array[0] );
							  				$('#tag').val( array[1] );

							  				$('.image').attr('src','./images/'+array[3]);

				
							  				$('.inputTextToSave').val( array[2] );

										    var $myFiles = $('#my-files');
										    $myFiles.hide();
										    $(".writer-grid").show();
							  				var $fileId = $('#fileid');
							  				$fileId.val(id);

							  				$('.myFile').val(array[3]);

							  				$('.insert').val(0);




							});
			 
							request.fail(function( jqXHR, textStatus ) {
							  console.log( "Request failed: " + textStatus );
							});


}



function showTitleCat(id){

	


							var request = $.ajax({
							  url: "ajax/showThis.php",
							  method: "POST",
							  data: { id : id, page: 'title-category' },
							  dataType: "html"
							});
							 
							request.done(function( msg ) {


											let array = msg.split('-');


							  				$('#title').val( array[0] );
							  				$('#category').val( array[1] );

							  				$('#my-title').val( array[0] );
							  				$('#tag').val( array[1] );


										    var $myFiles = $('#my-files');
										    $myFiles.hide();
										    $(".writer-grid").show();
							  				var $fileId = $('#fileid');
							  				$fileId.val(id);

							});
							 
							request.fail(function( jqXHR, textStatus ) {
							  alert( "Request failed: " + 'titleCat'+textStatus );
							});


}


function clearFilters(){


										page = 1;
										$('#currentPage').html(page);
										$('#search').val('');
										$('#perpage').val(10);
}


function filterResults(){



										var $myFiles = $('#my-files');
										$myFiles.show();
										$(".writer-grid").hide();



										let catId = $('#catg').val();

									    let word = $('#search').val();

									    console.log(word);


 										let limit = $('#perpage').val();



										$('#search').val(word);
										$('#perpage').val(limit);
										$('#offset').val(0);

									   	let currentOff = $('#offset').val();

									   	let offset = 0;


										console.log('filter=> limit='+limit+' offset:'+offset+' word:'+word+ 'cat:'+catId);


									   	$('#offset').val(offset);

									   				$('.g2').html('');



													

								
														if(globalRequest!='ToCancelPrevReq'){


																globalRequest.abort();

																globalRequest='ToCancelPrevReq';


														}



															 globalRequest = $.ajax({
															  url: "ajax/showThis_oop.php",
															  method: "POST",
															  data: { word: word, limit: limit, cat: catId, offset: offset, page: 'pagination' },
															  dataType: "html",



															});
															 
															globalRequest.done(function( msg ) {


																			console.log(msg);

																
															  				$('.g2').html( msg );

																			  				if($('#count').val()==0) { 


																			  				 		$('#perpage').val('10');  

																			  				}

																			  				else{

															  									$('#perpage').val($('#count').val());


															  								}

															});
															 
															globalRequest.fail(function( jqXHR, textStatus ) {
															  console.log( "Request failed: " + textStatus );
															});
}


function deleteThis(id) {


	alert('Do Not Delete');

/*
							let postid = $('#fileid').val();

							if(postid == id){ $('#newFile').click();  }

							var request = $.ajax({
							  url: "ajax/deleteThis.php",
							  method: "POST",
							  data: { id : id},
							  dataType: "html"
							});


							 
							request.done(function( msg ) {
								
							$.alert.open('info', 'Message', 'One file had been deleted.');

											$('.g2').html(msg);

							});
							 
							request.fail(function( jqXHR, textStatus ) {
							  console.log( "Request failed: " + textStatus );
							});

*/

}



function autoC(){


												var autoC = $.ajax({
												  url: "ajax/showThis.php",
												  method: "POST",
												  data: { page: 'autocomplete' },
												  dataType: "json"
												});
												 
												autoC.done(function( msg ) {


													let tags = [msg];


													$( "#tag" ).autocomplete({


																source: msg



													});




												});
												 
												autoC.fail(function( jqXHR, textStatus ) {
												  alert( "Request failed: " + textStatus );
												});


}



		function clickNext(count,limit,offset) {

										console.log('next.ing');




									   	let word = $('#search').val();
										let catId = $('#catg').val();

										console.log('count: '+count);
										console.log('limit='+limit);
										console.log('next.ing');





										console.log('next=> limit='+limit+' offset:'+offset+' word:'+word+ 'cat:'+catId);











														if(globalRequest!='ToCancelPrevReq'){


																globalRequest.abort();

																globalRequest='ToCancelPrevReq';


														}



															 globalRequest  = $.ajax({
															  url: "ajax/showThis_oop.php",
															  method: "POST",
															  data: { word: word, cat: catId, limit: limit, offset: offset, page: 'pagination' },
															  dataType: "html",

															});
															 
															globalRequest.done(function( msg ) {
																
															  				$('.g2').html( msg );

																			$('.g2').css('color','#000000');

																			$('#currentPage').html(page);


															  				if(Number($('#perpage').val())!=Number($('#count').val())){

																			document.getElementById('next').style.pointerEvents = 'none';

																			$('#next').css('color','#000');

															  				}

															  				else {

																			document.getElementById('next').style.pointerEvents = 'auto';

																			$('#next').css('color','#65f6f5');

															  				}

																		$('.g1').css('border-bottom','solid var(--textColor)');
																		$('.g3').css('border-top','solid var(--textColor)');
																		$('.g2').css('font-weight','900');

															});
															 
																globalRequest.fail(function( jqXHR, textStatus, errorThrown,) {

															      console.log(jqXHR.statusText);
															      console.log(textStatus);
															      console.log(errorThrown);

																		$('.g1').css('border-bottom','dashed var(--textColor)');
																		$('.g3').css('border-top','dashed var(--textColor)');

																});




		}




/*timer setting for textarea typing and saving*/


												let typingTimer;                //timer identifier
												let doneTypingInterval = 2500;  //time in ms, 5 second for example
												var $input = $('#myInput');

												//on keyup, start the countdown
												function writingText(){



																		clearTimeout(typingTimer);
																	  	typingTimer = setTimeout(doneTyping, doneTypingInterval);

												}


												function doneTyping(){


		
															$('#inputTextToSave').css('font-weight','100');

															console.log('go save');

															$('#hiddenFields :submit').click();
												}



/* time setting for pagination */


												let paginationTimer;                //timer identifier
												let donePaginationInterval = 1000;  //time in ms, 5 second for example


												//on click next/prev, start the countdown
												function paginationClick(direction){


																		
																		$('.g1').css('border-bottom','dotted var(--textColor)');
																		$('.g3').css('border-top','dotted var(--textColor)');
																		$('.g2').css('font-weight','100');

																		count = $('#count').val();
																	    limit = $('#perpage').val();
																	   	currentOff = $('#offset').val();

																	   	if(direction=='next'){

																	   		page = Number(page)+1;	
																			offset = Number(limit) + Number(currentOff);
																		}

																		else {

																	   		page = Number(page)-1;	

																			offset = Number(currentOff) - Number(limit);

																		}

									   									$('#offset').val(offset);


																		clearTimeout(paginationTimer);
																	  	paginationTimer = setTimeout(donePaginationClick, donePaginationInterval);

												}


												function donePaginationClick(){

														console.log('CLICKNEXT');
																		
																		$('.g1').css('border-bottom','dashed #7dfafa');
																		$('.g3').css('border-top','dashed var(--textColor)');

																		$('.g2').css('color','var(--textColor)');

																		$('.g2').css('font-weight','300');

														clickNext(count,limit,offset);

												}



</script>

</head>
<body>

<div id="top" class="writer-grid">

				<div id="showmyfiles" onclick="clearFilters(); filterResults();"  class="m labels menu1">



						Open
				</div>

				<?php if(isset($_SESSION["username"])){ echo '<div id="newFile" class="m labels menu2">

				New

				</div>'; } 


				else { echo '<div onclick="window.location.href=\'login.html\'" class="m labels menu2"><input id="chalkboard1" class="chalkboard1" type="text" value="Login" /></div>'; } ?>


<button onclick="doneTyping();" id="save" class="m sp1 saving">Save & Publish</button>

				<div onclick="window.location.href='logout.php'" class="m labels logout">Logout</div>

			<!--	<div class="labels" id="label1">Title:</div>

				<div id="myb-title" class="m menup"><input onkeyup="document.getElementById('title').value=this.value" type="text" value="<?php // echo $title; ?>" name="my-title" id="my-title" data-id="chalkboard1" class="chalkboard1" placeholder="type title here" /></div>

			-->

				<!--<div class="labels" id="label2">Category:</div>


				<div id="my-cat" class="m menuq"><input onchange="document.getElementById('category').value=this.value" value="<?php // echo $category; ?>" placeholder="type category here" type="text" name="my-cat" id="tag" data-id="chalkboard1" class="chalkboard1" /></div>

			-->

					

<input onkeyup="document.getElementById('title').value=this.value" type="text" value="<?php  echo $title; ?>" name="my-title" id="my-title" data-id="" class="title" placeholder="type title here" />

<input class="labels upload" onclick="$('#myFile').click();" type="button" id="" name="fil" value="Upload An Image">

<input style="display: none" class="labels upload" onchange="upImage(this.value);" type="file" id="myFile" name="filename" />


<img <?php if($imageName=='') { echo 'style="display: none;"';  }  ?> class="image" src="<?php echo $imageName; ?>" />


					<TEXTAREA onkeyup="writingText();" name="inputTextToSave" class="inputTextToSave" placeholder="Start Typing Here" id="inputTextToSave"><?php echo htmlspecialchars_decode($content); ?></TEXTAREA>


					<div id="ajaxload"><blink><!--loading results.....--></blink></div>




				<?php //if(isset($_SESSION["username"])){ echo '<div onclick="window.location.href=\'logout.php\'" class="m labels menu4">Logout</div>'; } 


			//	else { echo '<div onclick="window.location.href=\'signup.php\'" class="m labels menu4"><input id="chalkboard2" class="chalkboard2" type="text" value="SignUp" /></div>'; } ?>


<!--
				<div id="scrollUp" class="m labels menu5">Up</div>


				<div class="m labels menu6"><input onchange="upImage(this.value);" type="file" id="myFile" name="filename"></div>








				<div onclick="doneTyping();" id="save" class="m labels saving menu3"><input id="chalkboard2" data-id="saving" class="chalkboard2" type="text" value="SAVE" /></div>

-->
			<div class="wfooter">all.rights.reserved&copy;2020</div>

</div>


<form onsubmit="return false;"  id="hiddenFields" class="hiddenFields" method="POST">



<input type="hidden" id="userid" name="userid" value="<?php echo $_SESSION["userid"]; ?>">

<input type="hidden" class="fileid" id="fileid" name="fileid" value="<?php echo $fileId; ?>">
<input type="hidden" id="insert" name="insert" class="insert" value="1" />

<input type="hidden" name="page" id="page" value="index" />


<input type="hidden" value="<?php echo $title; ?>" name="title" id="title" />
<input type="hidden" value="<?php echo $category; ?>" name="category" id="category" />

<input type="hidden" class="textArea" name="textArea" id="textArea" />

<input type="hidden" name="imageName" id="imageName" value="<?php echo $imageName; ?>" />

<input type="hidden" class="myFile" name="fileVal" value="<?php echo $fileVal; ?>" />



<input type="submit" id="submit" name="submit" style="display: none;" />


</form>	



<form id="my-files" autocomplete="off" method="POST">

<div class="g1">




			<div id="col1"><div class="closebtn">BACK</div></div>

			<div id="col1"><input type="text" autocomplete="false" id="search" onkeyup="filterResults();"  placeholder="Search" /></div>
			<div id="col1"><input type="number" min="1" max="10" id="perpage" onchange="filterResults();"  value="10"/>&nbsp;&nbsp;results/page</div>
	<!--		<div id="col1"><select onchange="filterResults();" id="catg" name="catg"><option selected="selected" value="-1">Select A Category</option> -->
				

	<?php
/*


	foreach($catList as $key => $val){ ?>





								<option value="<?php echo $catList[$key]['id']; ?>"><?php echo ucfirst($catList[$key]['name']); ?></option>


	<?php 


	}

*/
	?>

<!--

			</select></div>

-->


			<div id="col1"><div onclick="window.location.href='logout.php'" id="log" >LOGOUT</div></div>


</div>

<div class="g2">
	

</div>


<div class="g3">
	
			<div class="pagination" onclick="paginationClick('prev');" id="prev"><div class="pagebtn">Previous</div></div>
			<div class="currentPage" id="currentPage">1</div>

			<div class="pagination" id="next" onclick="paginationClick('next');"><div class="pagebtn">Next</div></div>



</div>

<input type="hidden" id="offset" name="offset" value="0" />	

<input type="hidden" id="totalRows" value="<?php echo $totalRows; ?>" />

</form>





<script type="text/javascript">


					



 $(document).ready(function(){


															    if ($('#fileid').val()==''){

															    	$('#insert').val(1);
															    }


															    else {


															    	$('#insert').val(0);

															    }


				$("[type='number']").keypress(function (evt) {
				    evt.preventDefault();
				});


 	        $(document).ajaxStart(function () {


												window.setInterval(function() {

												timerCount=Number(timerCount)+1;

															if(timerCount==1){


																console.log('GREEN-1');

															}


															else if(timerCount==2){


																console.log('GREEN-2');

															}	


															else{


																console.log('GREEN-3');



															}																													


												}, 500);



										console.log('ready'+$('#offset').val());







										if($('#offset').val()==0){  


															$('#prev').css('color','#fff');

															document.getElementById('prev').style.pointerEvents = 'none';


										 }

										 else{

										  	$('#prev').css('color','#000');
										 	document.getElementById('prev').style.pointerEvents = 'auto';

										 }





										$('#ajaxload').show();


        });

        $(document).ajaxStop(function () {


        								$('#ajaxload').hide();


										$('.g2').css('color','#000');


        });


        									autoC();








												$("#scrollUp").on("click", function() {
												    var $textarea = $('.inputTextToSave');
												    $textarea.scrollTop(0);
												});


												$("#save").on("click", function(ev) {



												    var $submit = $('#hiddenFields :submit');
												    $submit.click();


												});




											    $('.hiddenFields').submit(function (ev) {

															    ev.preventDefault();


															    let me  = $(this);


															    let frm = $('.hiddenFields');


																		$('input[id=chalkboard1]').css('border-bottom','dashed 2.5px var(--textColor)');
																		$('input[data-id=chalkboard1]').css('border-bottom','dashed 2.5px var(--textColor)');
																		$('input[id=chalkboard2]').css('border-top','dashed 2.5px var(--textColor)');		
																		$('#inputTextToSave').css('font-weight','300');


															    $('.textArea').val($('.inputTextToSave').val());

															    let str = $('.textArea').val();
															    let strLength = str.length;

															    counter = Number(counter)+ 1;

															    if ($('#fileid').val()==''){

															    	$('#insert').val(1);
															    }

															    let sessionInsert = $('#insert').val();


															    console.log('sessInsert'+sessionInsert);


																	saveRequest  = $.ajax({
															            type: frm.attr('method'),
															            url: 'ajax/showThis_oop.php',
															            method: 'POST',
															            dataType: 'json',
															            data: frm.serialize(),

															            success: function( respo ) {
															                

															            	console.log('inserted file:'+respo.k1);

																			$('#insert').val(respo.k5);
															    	
															                $('#fileid').val(respo.k1);
															            	$('.textArea').val(respo.k2);
																			$('#title').val(respo.k3);
																			$('#category').val(respo.k4);


																			

																		$('input[id=chalkboard1]').css('border-bottom','solid 5px var(--textColor)');
																		$('input[data-id=chalkboard1]').css('border-bottom','solid 5px var(--textColor)');
																		$('input[id=chalkboard2]').css('border-top','solid 5px var(--textColor)');																		
																		$('#inputTextToSave').css('font-weight','900');

																		 me.data('requestRunning', false);


																		},
																		error: function( jqXHR, textStatus, errorThrown,) {


																				console.log('error:'+textStatus+errorThrown);


																		}

																});

																			console.log('2'+saveRequest);


															        ev.preventDefault();
											    });



					

												$("#newFile").on("click", function() {


															$.ajax({
															  url: "ajax/setSession.php",
															  method: "POST",
															  dataType: "html",
															  success: function( insert ) {
															                

															    	
															                $('#insert').val(insert);
															                console.log('INSERT VALUE: '+$('#insert').val());


																},
																error: function( jqXHR, textStatus, errorThrown,) {




																}

															});


													counter = 0;
													autoC();
												    var $textarea = $('.inputTextToSave');
												    $textarea.val('');

												    $('#my-title').val('');
												    $('#title').val('');

												    $('.image').attr('src','');
												    $('.image').css('display','none');

												    $('#imageName').val('');

												    $('#myFile').val('');
												    $('.myFile').val('');

												    $('#tag').val('');
												    $('#category').val('');


												   var $fileId = $('#fileid');
												   $fileId.val('');
												});








		$("#prev").on("click", function() {

/*

if($('#offset').val()==0){  return false; }


										page = Number(page) - 1;

										$('#currentPage').html(page);

										let count = $('#count').val();
									    let limit = $('#perpage').val();

									   	let word = $('#search').val();
										let catId = $('#catg').val();







									   	let currentOff = $('#offset').val();

									   	let offset = Number(currentOff) - Number(limit);

										console.log('prev=> limit='+limit+' offset:'+offset+' word:'+word+ 'cat:'+catId);



									   	$('#offset').val(offset);



							$('.g2').html(globalLoadingMessage);

														if(globalRequest!='ToCancelPrevReq'){


																globalRequest.abort();

																globalRequest='ToCancelPrevReq';


														}



															 globalRequest  = $.ajax({
															  url: "ajax/showThis_oop.php",
															  method: "POST",
															  data: { word: word, cat: catId, limit: limit, offset: offset, page: 'pagination' },
															  dataType: "html"
															});
															 
															globalRequest.done(function( msg ) {
																
															  				$('.g2').html( msg );

															  				if(Number($('#perpage').val())!=Number($('#count').val())){

																			document.getElementById('next').style.pointerEvents = 'none';
																			$('#next').css('color','#000');
															  				}

															  				else {

																			document.getElementById('next').style.pointerEvents = 'auto';
																			$('#next').css('color','#65f6f5');


															  				}



															  				if($('#offset').val()==0){

																			document.getElementById('prev').style.pointerEvents = 'none';
																			return false;

															  				}

															  				else {

																			document.getElementById('prev').style.pointerEvents = 'auto';


															  				}



															});
															 

																globalRequest.fail(function( jqXHR, textStatus, errorThrown,) {

															      console.log(jqXHR.statusText);
															      console.log(textStatus);
															      console.log(errorThrown);


																});



*/
		});	




		$(".closebtn").on("click", function() {


											    var $myFiles = $('#my-files');
											    $myFiles.hide();
											    $(".writer-grid").show();


		});	

			


});


function upImage(value){



					value = value.replace(/.*[\/\\]/, '');

					let fileObj = $('#myFile')[0].files[0];

					let fileId = $('.fileid').val();

					alert('fileId:'+fileId+' fileObj:'+fileObj);



																let formData = new FormData();

																formData.append('file', fileObj);
																formData.append('fileId', fileId);

													$.ajax({
																       url : 'ajax/upImage.php',
																       type : 'POST',
																       data: formData,
																       processData: false,
																       contentType: false, 

																       dataType: 'json',  
																       success : function(data) {
																       console.log(data);

																       		let image = './images/'+data.fileName;



																           $('.image').attr('src',image);

																           	$('.image').css('display','grid');

																           	$('.insert').val(0);
																           	$('.fileid').val(data.fileId);


																       },
																		error: function( jqXHR, textStatus, errorThrown,) {


																				console.log('error:'+textStatus+errorThrown);


																		}
													});



}


 </script>

</body>
</html>