<?php
include("../_inc/header.php");
require("../adodb5/adodb.inc.php");
ini_set('display_errors', true);
error_reporting(E_ALL);
try {
	$driver = 'mysqli';
    $db = newAdoConnection($driver);
    $db->debug = false;
    $db->socket = '/var/run/mysqld/mysql_3306.sock';
	///db 연결
    $db->connect('localhost', 'root', 'Itemmania1324%^', 'study');
    if(!$db){
        throw new Exception("db연결 오류",1);
    }

$sel_id = $_POST['sel_id'];


    $trans_check=$db->StartTrans();
    $db->Execute("update sel set sel_quantity= -999 where sel_id=$sel_id ");            // delete가 아닌 update를 이용해 리스트에서만 노출안되도록..

    if ($db->Affected_Rows() <1){
        throw new Exception("삭제실패 다시 시도해 주세요",142);
    }


$db->CompleteTrans();


echo "<script>
alert(\"del ok!.\");
window.open('./dr2.html','drdr','width=600,height=600,top=100,left=100');
</script>";

echo("<script>location.href='../sel/sel_list.php';</script>");


include("../_inc/footer.php");


} catch (Exception $e) {
    $error_msg = '에러발생 : ' . $e->getMessage() . $e->getCode();
    echo "<script>
        alert(\" $error_msg \");
        </script>";
    echo("<script>location.href='../index.php';</script>");

    if (isset($db) && $db->IsConnected() == true) {
        if ($trans_check == true) {
            $db->FailTrans();
            $db->CompleteTrans();
            unset($trans_check);
        }
        $db->Close();
        unset($db);
    }
    exit;
}


