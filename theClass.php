<?php

class dbOperations {


            private $id;
            private $userId;
            private $content;
            private $title; 
			private $limit;
			private $offset;
			private $catid;
			private $catName;
			private $fileid;
			private $word;
			private $imageName;
			private $fileVal;

            private $dbUser;
            private $dbPass;
            private $pdo;
            private $dsn;
            private $dbDriver;
            private $dbHost;
            private $dbName;

            private $key;







            public function __construct() {       



/*

                                    $this->dbDriver='mysql';
                                    $this->dbHost='www.db4free.net';
                                    $this->dbName='essays';
                                    $this->dbUser='pinku123';
                                    $this->dbPass='pinku123';


*/



                                    $this->dbDriver='mysql';
                                    $this->dbHost='localhost';
                                    $this->dbName='essays';
                                    $this->dbUser='root';
                                    $this->dbPass=''; 



                                    if(isset($_SESSION['userid'])) {   $this->userId = $_SESSION['userid'];  }   

                                    if(isset($_POST['id'])) {   $this->id = $_POST['id'];  }   
                                    if(isset($_POST['inputTextToSave'])) {   $this->content = $_POST['inputTextToSave'];  }   
                                    if(isset($_POST['textArea'])) {   $this->textArea = htmlspecialchars($_POST['textArea']);  }   
                                    if(isset($_POST['title'])) {   $this->title = $_POST['title'];  }   
                                    if(isset($_POST['fileid'])) {   $this->fileid = $_POST['fileid'];  }   
                                    if(isset($_POST['insert'])) {   $this->insert = $_POST['insert'];  }   
                                    if(isset($_POST['limit'])) {   $this->limit = $_POST['limit'];  }   
                                    if(isset($_POST['offset'])) {   $this->offset = $_POST['offset'];  }   
                                    if(isset($_POST['cat'])) {   $this->catid = $_POST['cat'];  }   
                                    if(isset($_POST['word'])) {   $this->word = '%'.$_POST['word'].'%';  }   
                                    if(isset($_POST['category'])) {   $this->catName = $_POST['category'];  }   
                                    if(isset($_POST['counter'])) {   $this->catid = $_POST['counter'];  }   
                                    if(isset($_POST['cat'])) {   $this->postCounter = $_POST['cat'];  }  
                                    if(isset($_POST['imageName'])) {   $this->imageName = $_POST['imageName'];  }   
                                    if(isset($_POST['fileVal'])) {   $this->fileVal = $_POST['fileVal'];  }   

                                    if(isset($_SESSION['counter'])) {   $this->sessionCounter = $_SESSION['counter'];  }   
                                    if(isset($_SESSION['key'])) {   $this->key = $_SESSION['key'];  }   

/*
                                    $this->userId = $_SESSION['userid'];
                                    $this->content = $_POST['inputTextToSave'];
                                    $this->textArea = htmlspecialchars($_POST['textArea']);
                                    $this->title = $_POST['title'];
									$this->fileid = $_POST['fileid'];

									$this->insert = $_POST['insert'];


                                    $this->limit = $_POST['limit'];
                                    $this->offset = $_POST['offset'];
                                    $this->catid = $_POST['cat'];
                                    $this->word = '%'.$_POST['word'].'%';
                                    $this->catName = $_POST['category'];

                                    $this->sessionCounter = $_SESSION['counter'];
                                    $this->postCounter = $_POST['counter'];



                                    $this->key = $_SESSION['key'];
        
*/
                                    /*$dsn= "mysql:host=$host;dbname=$db;charset=$charSet";
                                    */
                                    $this->dsn = $this->dbDriver.":host=".$this->dbHost.";dbname=".$this->dbName;

                                    try{
                                     // create a PDO connection with the configuration data
                                     $this->pdo = new PDO($this->dsn, $this->dbUser, $this->dbPass);
                                     
                                     // display a message if connected to database successfully
                                     if($this->pdo){
                                     //echo "Connected to the <strong>$dbName</strong> database successfully!";
                                            }
                                    }catch (PDOException $e){
                                     // report error message
                                     echo $e->getMessage();

                                    }


            }




           public function selectForPagination(){




					$arrayOfResults = array();

						if(!isset($this->userId)) {


						echo "Session Expire. Please Login.....<a href='login.php'>Login</a>";
						exit;

						}

						if($this->limit=='') {  $this->limit=10; }




						if($this->catid>-1){ 


									$query='SELECT id, title from writings FORCE INDEX (PRIMARY) where user_id=? and cat_id=? and content LIKE ?  order by id desc LIMIT '.$this->limit.' OFFSET '.$this->offset;

							/*		$query_play="SELECT id, title from writings FORCE INDEX (PRIMARY) where user_id='".$this->userId."' and cat_id='".$this->catid."' and content LIKE '".$this->word."'  order by id desc LIMIT ".$this->limit." OFFSET ".$this->offset;
							*/

									$execute=[$this->userId,$this->catid,$this->word];


						 }

					    else{


								$query='SELECT id, title from writings FORCE INDEX (PRIMARY) where user_id=? and content LIKE ?  order by id desc LIMIT '.$this->limit.' OFFSET '.$this->offset;

							/*		$query_play="SELECT id, title from writings FORCE INDEX (PRIMARY) where user_id='".$this->userId."' and content LIKE '".$this->word."'  order by id desc LIMIT ".$this->limit." OFFSET ".$this->offset;
							*/


								$execute=[$this->userId,$this->word];

						}


//echo $query_play; exit;


								$stmt = $this->pdo->prepare($query);			
								$stmt->execute($execute);




                                            $key1=0;






                                            while($result = $stmt->fetch(PDO::FETCH_ASSOC)){


                                            					foreach ($result as $key2 => $value) {

                                            									$arrayOfResults[$key1][$key2]=$value;
                                            					}


                                            $key1++;

                                            }

                    if(!empty($arrayOfResults)){       

            									return $arrayOfResults;


                     }


                     else {

                     		return array();

                     }





           } 




           public function updateLogin(){







           }




         public function selectForSignup($key){





								$query='SELECT * from user where confirmkey=?';

								$execute=[$key];



								$stmt = $this->pdo->prepare($query);			
								$stmt->execute($execute);


								$result = $stmt->fetch(PDO::FETCH_ASSOC);


								return $result;


         }  



       public function updateAfterSignUp($uid,$un){





       					//update confirm =1 and confirmkey=''
						//prepare statement for execution

						$stmt = $this->pdo->prepare("UPDATE user SET confirm=?,confirmkey=? where id=?");


						//execute statement

						$stmt->execute([1,'',$uid]);


						$_SESSION["username"] = $un;
						$_SESSION["userid"] = $uid;




						header("location:index.php");



       }  



      public function selectLastResult(){


						//prepare select statement


						$stmt = $this->pdo->prepare("SELECT * FROM writings where user_id=? order by id desc limit 1");



						//execute insert statement query

						$stmt->execute([$this->userId]);






						$result = $stmt->fetch(PDO::FETCH_ASSOC);


						return $result;



      } 


    public function selectLastResultByFileId($lastfileId){


							$Istmt = $this->pdo->prepare("SELECT * FROM writings where id=?");


							//execute insert statement query

							$Istmt->execute([$lastfileId]);


							$result = $Istmt->fetch(PDO::FETCH_ASSOC);

							return $result;


    }  



    public function selectCatName($cat_id){


				/*		$stmt_cat = $this->pdo->prepare('SELECT name from category where id=?');
						//execute query

						$stmt_cat->execute([$cat_id]);
						//fetch result

						$result_cat = $stmt_cat->fetch(PDO::FETCH_ASSOC);

						return $result_cat['name'];
				*/

				return 0;

    }  



    public function selectCatId(){


						$stmt_cat = $this->pdo->prepare('SELECT id from category where name=?');
						//execute query


						$stmt_cat->execute([$this->catName]);
						//fetch result

						$result_cat = $stmt_cat->fetch(PDO::FETCH_ASSOC);

						if(isset($result_cat['id'])){ return $result_cat['id'];  }



    }  



    public function selectAllCat(){


									//statement prepare

									$sel = $this->pdo->prepare("SELECT name,id from category");

									//execute 

									$sel->execute();

									//fetch 

									$catList = array();
									$count = 0;

									while($catg = $sel->fetch(PDO::FETCH_ASSOC)){


												foreach ($catg as $key => $value) {


															$catList[$count][$key]=$value;


												}

									$count++;
									}


									return $catList;





	}


	public function insertCategory() {



						$stmt = $this->pdo->prepare("INSERT INTO category (name) VALUES(?)");
						$stmt->execute([$this->catName]);

						$cat_id = $this->pdo->lastInsertId();

						return $cat_id;

	}



	public 	function insertWriting($catid) {	


//echo "session insert variable: ".$_SESSION['insert'];
//exit;
						if($this->insert==1){

							$stmt = $this->pdo->prepare("INSERT INTO writings (user_id, cat_id, title, content, file, now) VALUES(?, ?, ?, ?, ?, ?)");

							$now = time();

							$stmt->execute([$this->userId, $catid, $this->title, $this->textArea, $this->fileVal, $now]);

							$fileid = $this->pdo->lastInsertId();



							$catName = $this->selectCatName($catid);

							$result = $this->selectLastResultByFileId($fileid);


							$content = preg_replace('#<br\s*/?>#i', "\n", $result['content']);

							$result['content'] = $content;
							

							$result["catName"] = $catName;

							$_SESSION['insert']=0;

							$result['sessionInsert']=0;


							$result['action'] = 'insert';


							return $result;
							}


							else {


									echo "trapped in bugs";
									exit;
							}

	}



	public function updateWriting($catid,$fileid){

//echo "(update)session insert variable: ".$_SESSION['insert'];
//exit;

							if($fileid!='' && $this->insert==0){

							$stmt = $this->pdo->prepare("UPDATE writings SET cat_id=?, title=?, content=? WHERE id=?");

							$stmt->execute([$catid, $this->title, $this->textArea, $this->fileid]);


							$catName = $this->selectCatName($catid);

							$result = $this->selectLastResultByFileId($this->fileid);

							$content = preg_replace('#<br\s*/?>#i', "\n", $result['content']);

							$result['content'] = $content;

							$result["catName"] = $catName;

							$_SESSION['insert']=0;

							$result['sessionInsert']=0;

							$result['action'] = 'update';


							return $result;
							}

	}	



public function viewThisFile(){



					$id = $this->id;

					//prepare statement



					$stmt = $this->pdo->prepare("SELECT * from writings where id=?");

					$stmt->execute([$id]);

					$result = $stmt->fetch(PDO::FETCH_ASSOC);

					$content = $result['content'];
					$title   = $result['title'];
					$cat_id  = $result['cat_id'];
					$file    = $result['file'];


									$stmt_cat = $this->pdo->prepare('SELECT name from category where id=?');
									//execute query

									$stmt_cat->execute([$cat_id]);
									//fetch result

									$result_cat = $stmt_cat->fetch(PDO::FETCH_ASSOC);

									$cat = $result_cat['name'];


					$content = preg_replace('#<br\s*/?>#i', "\n", $content);



					$ajax_response = array($title,$cat,$content,$file);

					return $ajax_response;



	}


}
?>