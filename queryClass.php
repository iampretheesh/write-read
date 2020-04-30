<?php
class readWrite {


                                    private $dbDriver;
                                    private $dbHost;
                                    private $dbName;
                                    private $dbUser;
                                    private $dbPass;
                                    private $pdo;
                                    private $dsn;

                                    private $id;
                                    private $userId;
                                    private $content;
                                    private $categoryId;
                                    private $title; 
                                    //$charset='';





                public function __construct() {       





                                    $this->dbDriver='mysql';
                                    $this->dbHost='www.db4free.net';
                                    $this->dbName='essays';
                                    $this->dbUser='pinku123';
                                    $this->dbPass='pinku123';



                                    $this->userId = $_SESSION['user_id'];
                                    $this->title = $_POST['title'];

        

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
               


                public function selectmyFiles($table,$fieldArr,$postArr,$forceIndex,$whereArr){



                                        $forceIndex = 'PRIMARY';
                                        $fields = implode (",", $fieldArr);

                                        $stmt = $this->pdo->prepare('SELECT '.$fields.' from '.$table.' FORCE INDEX ('.$forceIndex.') '.$whereArr);           

//echo 'SELECT '.$fields.' from '.$table.' FORCE INDEX ('.$forceIndex.') '.$whereArr;
//exit;

                /*echo "SELECT `id`, `title` from `writings` FORCE INDEX (PRIMARY) where `user_id`=26 and `cat_id`=".$catid." and `content` LIKE ".$word."   order by id desc LIMIT ".$limit." OFFSET ".$offset; exit;*/

                                            //execute stmt


                                            $stmt->execute();

                                            $selectmyFiles = array();

                                            $titles='';

                                            $key=0;

                                            while($result = $stmt->fetch(PDO::FETCH_ASSOC)){


                                                    foreach($result as $value){  


                                                                for($i=0;$i<count($fieldArr);$i++){

                                                                    $field = $fieldArr[$i];

                                                                    $selectmyFiles[$key][$field]=$result[$field];
                                                                }


                                                    }


                                            $key++;

                                            }


                                            return $selectmyFiles;

                }



             public function insert(){


                            echo "Title is: ".$this->title;
                            exit;

             }   


}


?>