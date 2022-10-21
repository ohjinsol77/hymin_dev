<?php
include("../_inc/header.php");
require("../adodb5/adodb.inc.php");

if(!isset($_SESSION['member_Session_id'])){          // 로그인을 하지 않았다면 충전 페이지 진입 금지.
    echo "<script>
alert(\"Login first.  go back.\");
window.open('./dr2.html','drdr','width=600,height=600,top=100,left=100');
</script>";
    echo("<script>location.href='../index.php';</script>");

}
ini_set('display_errors', true);
error_reporting(E_ALL);
try {
    $driver = 'mysqli';
    $db = newAdoConnection($driver);
    $db->debug = true;

    $db->connect('localhost', 'root', 'Kdkdldpadkdl123$%^', 'study');
    if(!$db){
        throw new Exception("db연결 실패");
    }

$mileage_id = $_SESSION['member_Session_mileage'];

    $trans_check=$db->StartTrans();

$rs = $db->Execute("select ifnull(buypoint_amount,0) from mileage where mileage_id=$mileage_id  for update");

while (!$rs->EOF) {

    $mypoint_amount = $rs->fields[0];
    $rs->MoveNext();

}
    if ($db->Affected_Rows() <1){
        throw new Exception("정보조회 오류",590);
    }
?>

<html>
<body>
<h2>구매포인트 교환소</h2>
<hr width="80%"/>
<div id="#contsRow">

    <form name="milchargingform" method="post" action="mileage_PointChargingOk.php" class="formtag">

        <ul style="list-style-type:square">


            <p> 1000 포인트 단위로 마일리지로 교환 가능합니다.</p>
            <p>충전 수수료는 충전 금액의 15% 입니다.</p><br/>
            <li> 당신의 현재 구매 포인트는:<?= $mypoint_amount ?>원 입니다.</li>
            <br/><br/>

            <li>금액을 입력하세요 : <input type="number" name="pointPrice" max="<?= (int)(($mypoint_amount) / 1000) ?>"/>천원</li>

        </ul>
        <ul>
            <hr width="80%"/>

            <li align="center">
                <input type="submit" value="change"/>

                <input type="button" value="go home" onclick="window.location='../index.php'">
            </li>
        </ul>

    </form>
</div>
<?php
include("../_inc/footer.php");

$db->CompleteTrans();

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
