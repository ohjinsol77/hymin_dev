<?php
//include("../db/dbconn.php");
require("../adodb5/adodb.inc.php");
error_reporting(E_ALL);

ini_set("display_errors", 1);


$strName = $_POST['strName'];
$strPassword = $_POST['strPassword'];
$nSex = $_POST['nSex'];
$nTel = $_POST['nTel'];
$strAdd = $_POST['strAdd'];
$member_num = $_SESSION['member_Session_number'];

try {
    $driver = 'mysqli';
    $db = newAdoConnection($driver);
    $db->debug = true;

    $db->connect('localhost', 'root', 'Kdkdldpadkdl123$%^', 'study');

    if(!$db){
        throw new Exception("db연결 오류");
    }

    $trans_check=$db->StartTrans();


$rs = $db->Execute("update member set member_name='$strName', member_password='$strPassword', member_gender='$nSex' , member_tel='$nTel', member_address='$strAdd', member_lastedit=now() where member_num=$member_num");

    if ($db->Affected_Rows() <1){
        throw new Exception("정보갱신 오류",5490);
    }
echo("<script>location.href='../index.php';</script>");

include("../_inc/footer.php");


$db->CompleteTrans();

}catch (Exception $e) {
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











