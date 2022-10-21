<?php
include("../_inc/header.php");
require("../adodb5/adodb.inc.php");
ini_set('display_errors', true);
error_reporting(E_ALL);

try {
    $driver = 'mysqli';
    $db = newAdoConnection($driver);
    $db->debug = true;

    $db->connect('localhost', 'root', 'Kdkdldpadkdl123$%^', 'study');
    if(!$db){
        throw new Exception("DB연결 오류",1);
    }

    $member_milId = $_SESSION['member_Session_mileage'];    // 멤버의 마일리지 번호를 저장
    $member_num = $_SESSION['member_Session_number'];    // 멤버의 멤버 번호를 저장
    $nPoint = $_POST['pointPrice'] * (1000);    // 1000원 단위로 받았기 때문에 1000곱해준다.
    $change_tax = $nPoint * 0.15;    // 포인트 충전의 경우 15%의 수수료가 추가된다.
    $nPoint_sum = $nPoint - $change_tax;    // 실제 충전 포인트는 구매금액-수수료
    $buyPoint_result = 0;    // 계산결과
    $count_array = 0;    // 배열 카운트
    $buypoint_id = array();    // 구매 포인트 id를 저장하는 배열
    $buypoint_amount = array();    // 구매 포인트 보유 가격을 저장하는 배열

    if(!isset($member_num)){
        throw new Exception("회원정보 오류",243);
    }
    if(!isset($member_milId)){
        throw new Exception("회원정보 오류",9531);
    }
    if($nPoint<0 || $nPoint_sum < $nPoint){

    }


    $trans_check=$db->StartTrans();
// buypoint 테이블에서 사용가능한 값들만 불러와 삭제일자가 가장 빠른 순으로 배열에 넣는다.
    $rs = $db->Execute("select buypoint_id, buypoint_amount from buypoint where buypoint_type=500 or buypoint_type=502 and mileage_id=$member_milId order by buypoint_deldate");
    while (!$rs->EOF) { // 배열에 데이터 삽입
        $buypoint_id[] = $rs->fields[0];
        $buypoint_amount[] = $rs->fields[1];
        $rs->MoveNext();
        $count_array++;    // 카운트를 더해준다.
    }

// 구매 마일리지로 변동 사항을 입력한다.
    $rs = $db->Execute("insert into buy_mileage (mileage_id, buymileage_type, member_num, buymileage_price, buymileage_amount, buymileage_tax, buymileage_regdate) values($member_milId,400,$member_num,$nPoint_sum,(select ifnull((select A.buymileage_amount from buy_mileage A where A.member_num=$member_num order by A.buymileage_regdate desc limit 1 ),0)+$nPoint_sum), $change_tax, now() )");
    if (!$rs) {
        throw new Exception("변동사항 입력 오류",993);
    }
// 구매포인트 차감 프로세스
    for ($i = 0; $i <= $count_array; $i++) {


        // 해당 컬럼의 구매포인트가 변경값보다 크다면 바로 변경을 하고 break를 이용해 for문을 빠져나온다.
        if (($buypoint_amount[$i] - $nPoint_sum) > 0) {
            $buyPoint_result = $buypoint_amount[$i] - $nPoint_sum;
            $nPoint_sum = 0;    // 변경값을 0으로 만든다.
            $rs = $db->Execute("update buypoint set buypoint_amount=$buyPoint_result where buypoint_id=$buypoint_id[$i]");
            if ($rs=$db->Affected_Rows() < 1){
                throw new Exception("정보갱신 오류",305);
            }
            break;
        }
        // 해당컬럼이 0원이라면 넘어간다.
        else if ($buypoint_amount[$i] == 0) {
            continue;
            // 해당 컬럼 값이 변경값 보다 작다면, 변경값=변경값-해당컬럼 계산을 한 후  해당 컬럼의 총액을 0으로 바꾼다.
        }else {
            $buyPoint_result = $nPoint_sum - $buypoint_amount[$i];
            $nPoint_sum = $buyPoint_result;
            $rs = $db->Execute("update buypoint set buypoint_amount=0 where buypoint_id=$buypoint_id[$i]");
            if ($rs=$db->Affected_Rows() < 1){
                throw new Exception("정보갱신 오류",305);
            }
        }

    }
// 문제가 없다면 구매포인트 중 사용가능한 포인트의 합을 mileage 테이블에 업데이트 한다.
    $db->Execute("update mileage set buypoint_amount=(select sum(buypoint_amount) from buypoint where buypoint_type=500 or buypoint_type= 502) where mileage_id=$member_milId");

    if ($db->Affected_Rows() < 1){
        throw new Exception("마일리지 갱신 오류",306);
    }
    $db->Execute("insert into buypoint_log (mileage_id, buypoint_type,buypoint_price,buypoint_regdate) values ($member_num,501,$nPoint,now())");

    if ($db->Affected_Rows() < 1){
        throw new Exception("테이블 등록 오륲",308);
    }

    $db->CompleteTrans();
    ?>

    <button type='button' onclick="location.href='../mileage_View/view_myMileage.php'"> 보유 마일리지 확인</button>

    <?php
    include("../_inc/footer.php");

} catch (Exception $e) {
    $error_msg = '에러발생 : ' . $e->getMessage() . $e->getCode();
    echo "<script>
        alert(\" $error_msg \");
        </script>";
    echo("<script>location.href='../index.php';</script>");

    if (isset($db) && $db->IsConnected() == true) {
        if($trans_check==true){
            $db->FailTrans();
            $db->CompleteTrans();
        }
        $db->Close();
        unset($db);
    }
    exit;
}