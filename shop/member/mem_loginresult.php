<?php

include("../_inc/header.php");
require("../adodb5/adodb.inc.php");
error_reporting(E_ALL);
ini_set("display_errors", 1);
?>
    <html>
<body>
<h2>로그인 페이지</h2>
<hr width="80%"/>
<div id="#contsRow">
    <?php
    $member_id = $_POST['strId'];
    $member_password = $_POST['strPw'];
    $nMember_number = 0;
    $nMember_admin = 0;

    try {
        $driver = 'mysqli';
        $db = newAdoConnection($driver);
        $db->debug = true;

        $db->connect('localhost', 'root', 'Kdkdldpadkdl123$%^', 'study');

        if(!$db){
            throw new Exception("db연결 오류");
        }

        $trans_check=$db->StartTrans();

    $rs = $db->Execute("select mem.member_num, mem.member_id, mem.member_password, mem.member_admin, mil.mileage_id  from member mem join mileage mil on mem.member_num=mil.member_num  where mem.member_id='$member_id' and mem.member_password=SHA1('$member_password') ");

    if (!$rs) {
        throw new Exception("회원정보조회 오류 ",3);
    } else {
        while (!$rs->EOF) {
            $_SESSION['member_Session_id'] = $rs->fields[1];
            $_SESSION['member_Session_number'] = $rs->fields[0];
            $_SESSION['member_Session_admin'] = $rs->fields[3];
            $_SESSION['member_Session_mileage'] = $rs->fields[4];

            $rs->MoveNext();
        }
    }
    print_r($_SESSION);

    /***************세션체크***************/
    if (isset($_SESSION['member_Session_id'])) {

        echo $_SESSION['member_Session_id'] . '살았음';

    } else {
        echo "죽음";
    }
    /***************세션체크***************/

    ?>
    <form name="memlogin" method="post" action="../index.php" class="formtag">

        <ul style="list-style-type:square">
            <?php
if (isset($_SESSION['member_Session_id'])){

/******************************* 구매포인트 7일경과 항목 삭제 S *******************************/
$user_Mileage=$_SESSION['member_Session_mileage'];

$db->Execute("call check_delDate($user_Mileage)");
if(!$db){
   throw new Exception("구매포인트 삭제 오류");
}
$db->CompleteTrans();

// member/procedure_test.txt에 프로시저 코드 첨부.
/******************************* 구매포인트 7일경과 항목 삭제 E *******************************/
            ?>
            <!-- 세션이 있을 경우 -->
            <h2 align="center">안녕하세요 <?php echo $_SESSION['member_Session_id']; ?>님 반갑습니다.</h2>

        </ul>
        <ul>
            <hr width="80%"/>

            <li align="center">


                <input type="submit" value="홈으로 가기"/>
                <input type="button" value="마이룸 가기" onclick="window.location='../mileage_View/view_myMileage.php'"/>
                <?php }else {
                ?>
                <h2 align="center">로그인 실패 다시 시도하세요..</h2>
        </ul>
        <ul>
            <hr width="80%"/>

            <li align="center">


                <input type="submit" value="홈으로 가기"/>
                <input type="button" value="회원가입" onclick="window.location='./mem_regForm.php'"/>

                <?php }
?>
            </li>
        </ul>

    </form>
</div>
<?php
include("../_inc/footer.php");

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

