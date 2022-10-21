<?php
include("../_inc/header.php");
require("../adodb5/adodb.inc.php");
if (!isset($_SESSION['member_Session_id'])) {          // 로그인을 하지 않았다면 충전 페이지 진입 금지.
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
} catch (Exception $e) {
    die($e->getMessage());   // 에러메세지 출력
}

$member_num = $_SESSION['member_Session_number'];

$rs = $db->Execute("select ifnull((cash_amount+credit_amount+phone_amount + buymileage_amount),0)  from mileage where member_num=$member_num");

while (!$rs->EOF) {

    $mypoint_amount = $rs->fields[0];
    $rs->MoveNext();
}
$mypoint_amount -= 900;
?>

    <html>
<body>
    <h2>포인트 출금소</h2>
    <hr width="80%"/>
<div id="#contsRow">

    <form name="milchargingform" method="post" action="mileage_withdrawOk.php" class="formtag">

        <ul style="list-style-type:square">

            <p> 1회 출금당 900원의 수수료가 합산됩니다.</p>
            <br/>
            <li> 구매를 통해 획득한 마일리지는 출금할 수 없습니다.</li>
            <li> 최소 출금 금액은 1,000원 입니다.</li>
            <li> 당신의 현재 출금가능 포인트는 수수료 포함: <?= $mypoint_amount ?>원 입니다.</li>
            <br/><br/>

            <li> 은행 선택 :
                <SELECT name="bank_type">
                    <option value="0">choose bank</option>
                    <option value="1">카카오 뱅크</option>
                    <option value="2">농협</option>
                    <option value="3">우체국</option>
                </select></li>
            <br/>
            <li>계좌번호 입력 :<input type="number" name="nbankNum" placeholder="-를 제외한 숫자만 입력" </li>
            <br/>
            <li>출금금액 입력 :<input type="number" name="pointPrice" min="1000" max="<?=$mypoint_amount?>" placeholder="금액"></li>
            <input type="hidden" name="step" value="1"/>
        </ul>
        <ul>
            <hr width="80%"/>

            <li align="center">
                <input type="submit" value="withdraw"/>

                <input type="button" value="go home" onclick="window.location='../index.php'">
            </li>
        </ul>

    </form>
</div><?php
include("../_inc/footer.php");