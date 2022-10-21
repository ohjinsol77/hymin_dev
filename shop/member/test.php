<?php
ini_set('display_errors', true);
error_reporting(E_ALL);
//include_once ("../db/dbconn.php");
include_once ("../class/ShopFunction.php");
echo "fnGetMileage test //////";

//$db = new DB_conn();

//if($db==null){
  //  echo "연결실패";
 //   exit;

//}
$function = new ShopFunction();

if($function==null){
    echo "펑션실패";
    exit;
}


$user_info=Array();
$user_info['user_id']=$_SESSION['member_Session_id'];
$user_info['user_num']=$_SESSION['member_Session_number'];
//$function->fnSetUser($user_info);
//$result = $function->fnGetMileage(1);
$result=$function->testInsert(1);
if($result==null){
   echo"///  쿼리결과 안들어옴";
}
//
//
//
var_dump($result);


