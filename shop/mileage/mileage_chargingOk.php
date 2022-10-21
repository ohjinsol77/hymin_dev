<?php
/*마일리지 충전*/
include("../_inc/header.php");
include_once("../adodb5/adodb.inc.php");
//include("../db/dbconn.php");
//include_once ("../class/ShopFunction.php");

ini_set('display_errors', true);
error_reporting(E_ALL);
try {
    $driver = 'mysqli';
    $db = newAdoConnection($driver);
    $db->debug = true;

    $db->connect('localhost', 'root', 'Kdkdldpadkdl123$%^', 'study');

    ?>

    <html>
<body>
<h2>충전 결과 확인 페이지</h2>
<hr width="80%"/>
<div id="#contsRow">


    <?php


    //$ShopFunction = new ShopFunction();


    //    if($ShopFunction==null){
    //        throw new Exception("클래스 호출 오류",3993);
    //    }

    // 기본 변수 설정 ▼ //
    $nType = $_POST['mileage_type'];
    $nMilprice = $_POST['mileage_price'];
    $mile_id = $_SESSION['member_Session_mileage'];
    $member_num = $_SESSION['member_Session_number'];
    $step = $_POST['step'];
    $mil_tax = ($nMilprice) * 0.15;
    $mil_sum = $nMilprice - $mil_tax;
    $order_number = date("YmdHis") . $mile_id;
    $order_num = 'charge' . $order_number;                    //신규 충전주문번호(oid)
    $account_num = 'account_book' . $order_number;             //신규 장부코드 번호
    $trans_check = null;                                          // 트랜젝션 체크
    $before_money = 0;                                             // 현재 보유 마일리지


    // 기본 변수 설정 ▲ //


    echo $nMilprice . "원 충전 프로세스";

    //스텝체크
    if ($step != 1 & empty($step) & !isset($step)) {
        throw new Exception("비 정상적인 접근입니다.", 999);
    }
    // 충전금액 체크
    if ($nMilprice <= 0 || empty($_POST['mileage_price']) || !isset($nMilprice)) {
        throw new Exception("충전금액 오류가 발생했습니다.", 999);
    }
    // 충전타입 체크
    if ($nType > 3 || empty($nType) || !isset($nType)) {
        throw new Exception("충전방법 오류가 발생했습니다.", 999);
    }
    // 충전방식 체크
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("충전오류가 발생했습니다.", 999);
    }

    // 충전 전 회원정보 조회 & 저장 ▼ //
    echo "회원 정보 조회 \n";

    $rs = $db->Execute("select member_num, member_id, member_name, member_tel from member where member_num='" . $member_num . "'");

    if ($rs == null) {
        throw new Exception ("정보조회 오류 다시 시도하세요", 81);
    }

    // 회원조회 결과
    while (!$rs->EOF) {
        $mem_num = $rs->fields[0];
        $mem_id = $rs->fields[1];
        $mem_name = $rs->fields[2];
        $mem_tel = $rs->fields[3];
        $rs->MoveNext();
    }

    unset($rs);

    echo "user_id=$mem_id";
    if (empty($mem_num) & !isset($member_num)) {
        throw new Exception("정보조회 오류", 4956);
    }
    if (empty($mem_id) && !isset($mem_id) && $_SESSION['member_Session_id'] != $mem_id) {
        throw new Exception("정보조회 오류", 4956);
    }
    if (empty($mem_name) & !isset($member_name)) {
        throw new Exception("정보조회 오류", 4956);
    }
    if (empty($mem_tel) & !isset($member_tel)) {
        throw new Exception("정보조회 오류", 4956);
    }


    //$ShopFunction->fnSetUser($user_info);
    // 충전 전 회원정보 조회 & 저장 ▲ //


    // 중복충전 체크 ▼ //
    echo "중복충전 체크 \n";
    $duplicated_check = $db->Execute("select oid from mileage_fill_succ where oid = '" . $order_num . "'");

    if ($db->Affected_Rows() > 0) {

        throw new Exception("중복충전 발생", 41);
    }
    // 중복충전 체크 ▲ //


    echo "트랜젝션 시작 \n";
    $trans_check = $db->StartTrans();
    if ($trans_check == null) {
        throw new Exception("트랜젝션오류", 44);
    }

    //$ShopFunction->fnGetMileage(1);
    // 사용가능한 전체 마일리지 조회
    echo "모든 마일리지 조회 \n";
    $rs = $db->Execute("select (mil.cash_amount +mil.credit_amount+mil.phone_amount+mil.buymileage_amount) as `Amount` from mileage mil join member mem on mil.member_num=mem.member_num where mem.member_num=$mem_num for update ");
    $before_money = $rs->fields['Amount'];
    $after_money = $before_money + $nMilprice;
    echo "before_money=$before_money";
    echo "after_money=$after_money \n";

    unset($rs);
    if ($after_money <= 0 && $after_money < $before_money) {
        throw new Exception("충전금액오류", 4);
    }

    //충전 거래번호 입력 (trade_id)

    echo "order_num=" . $order_num . "\n";

    $sql = "insert into trade_id (oid,reg_date) values('" . $order_num . "', now())";
    $db->Execute($sql);
    unset($sql);

    if ($db->Affected_Rows() < 1) {
        throw new Exception("거래번호 등록 오류", 557);
    }

    // 충전 시작 등록

    echo " 충전시작 등록 \n";
    $sql = "insert into mileage_fill 
                        (oid,state,account_code,mile_code,price,before_money,after_money,user_id,user_name,message,response_date)
            values('" . $order_num . "','connect','" . $account_num . "','" . $mile_id . "','" . $nMilprice . "','" . $before_money . "','" . $after_money . "','" . $mem_id . "','" . $mem_name . "','회원 충전시작',now())";

    $rs = $db->Execute($sql);
    if ($db->Affected_Rows() < 1) {

        $sql = "insert into mileage_fill 
                        (oid,state,account_code,mile_code,price,before_money,after_money,user_id,user_name,message,response_date)
            values('" . $order_num . "','fail','" . $account_num . "','" . $mile_id . "','" . $nMilprice . "','" . $before_money . "','" . $after_money . "','" . $mem_id . "','" . $mem_name . "','회원 충전실패',now())";
        $fail_fill = $db->Execute($sql);

    }

    unset($sql);


    echo "충전 타입별 코드 설정 \n";
    switch ($nType)              // 충전방식에 따라 3가지의 경우로 나눠짐
    {
        case 1:  //현금 충전
            $mil_type = 100;
            $mil_name = 'cash';
            $sql = "insert into cash_mileage (mileage_id, member_num, cash_type, cash_price, cash_amount, cash_tax) values ($mile_id,$member_num,$mil_type,$mil_sum,(select ifnull((select A.cash_amount from cash_mileage A where A.member_num=$member_num order by A.cash_regdate desc limit 1  ),0)+$mil_sum),$mil_tax )";
            break;
        case 2: //카드 충전
            $mil_type = 200;
            $mil_name = 'credit';
            $sql = "insert into credit_mileage (mileage_id, member_num, credit_type, credit_price, credit_amount, credit_tax) values ($mile_id,$member_num,$mil_type,$mil_sum,(select ifnull((select  A.credit_amount from credit_mileage A where A.member_num=$member_num order by A.credit_regdate desc limit 1 ),0)+$mil_sum),$mil_tax )";
            break;
        case 3: //휴대폰 충전
            $mil_type = 300;
            $mil_name = 'phone';
            $sql = "insert into phone_mileage (mileage_id, member_num, phone_type, phone_price, phone_amount, phone_tax) values ($mile_id,$member_num,$mil_type,$mil_sum,(select ifnull((select  A.phone_amount from phone_mileage A where A.member_num=$member_num order by A.phone_regdate desc limit 1 ),0)+$mil_sum),$mil_tax )";
            break;

        default:
            // 충전 방법을 설정하지 않으면 전화면으로 돌려보냄
            echo "<script> alert(\"please choose method. go back.\");
			window.open('./dr2.html','drdr','width=600,height=600,top=100,left=100');
			</script>";
            echo("<script>location.href='mileage_charging.php';</script>");
            break;
    }
    // 충전금액 입력 //

    $db->Execute($sql);

    unset($sql);
    if ($db->Affected_Rows() < 1) {
        throw new Exception("충전 입력 실패 ", 9999);
    }
    // 충전금액 입력 //

    // 디테일 리스트 작성
    echo "디테일 리스트 작성 \n";

    $detail_list = "insert into mile_detail_list set user_no='" . $mem_num . "', user_id='" . $mem_id . "', account_code='" . $account_num . "', mile_code='" . $mile_id . "',payment_money='" . $mil_sum . "',remain_money='" . $after_money . "', payment_date=now()";
    $db->Execute($detail_list);
    if ($db->Affected_Rows() < 0) {
        throw new Exception("디테일리스트 작성오류", 305);
    }
    $detail_id = $db->Insert_id();

    // 디테일 리스트 작성
    echo "디테일 로그 작성 \n";

    $detail_list = "insert into mile_detail_log set detail_id='" . $detail_id . "', user_no='" . $mem_num . "', user_id='" . $mem_id . "', mile_code='" . $mile_id . "',mile_money='" . $mil_sum . "', trade_id='" . $order_num . "', ins_type='s',ins_result='2',ins_date=now(),mile_state='sucess'";
    $db->Execute($detail_list);
    if ($db->Affected_Rows() < 0) {
        $detail_list = "insert into mile_detail_log set detail_id='" . $detail_id . "', user_no='" . $mem_num . "', user_id='" . $mem_id . "', mile_code='" . $mile_id . "',mile_money='" . $mil_sum . "', trade_id='" . $order_num . "', ins_type='s',ins_result='2',ins_date=now(),mile_state='fail'";

    }
    unset($detail_list);


    // 충전 중 상태 등록

    echo "충전 상태 등록 \n";
    $after_money = $before_money + $nMilprice;
    $sql = "update mileage_fill set oid = '" . $order_num . "', state='process', message='충전중',response_date=now() where oid='" . $order_num . "'";

    $db->Execute($sql);


    if ($db->Affected_Rows() < 1) {
        if (!empty($fail_fill)) {
            $db->Execute("update mileage_fill set state='fail', message='충전 중 갱신 실패', response_date=now() where oid='" . $order_num . "'");
        }
    }


    //장부작성
    // type c = 충전
    echo "장부등록 \n";
    $account_book = $db->Execute("insert into account_book set account_code='" . $account_num . "',trade_type='c',seller_id='" . $mem_id . "', seller_mile_before='" . $before_money . "',seller_mile_after='" . $after_money . "',total_money='" . $mil_sum . "',mile_money='" . $nMilprice . "',admin_memo='회원 마일리지 충전' ,ins_date=now() ");

    if ($account_book == null || $db->Affected_Rows() < 1) {
        throw new Exception("장부 입력오류", 4624);
    }

    unset($account_book);


    echo "충전 테이블에 입력 \n";
    $db->Execute("insert into charge (member_num, charge_price, charge_type,charge_regdate) values ($member_num,$nMilprice,$mil_type,now())");

    echo '당신은 ' . $nType . '번을 선택 하여서 ' . $mil_type . '(으)로' . $mil_sum . '원 입금 되었습니다 감사합니다.';

    if ($db->Affected_Rows() < 1) {

        //throw new Exception("충전 중 오류발생");
        $sql = "update mileage_fill set state='fail', message='회원 충전 실패', response_date=now() where oid='" . $order_num . "'";
        $rs = $db->Execute($sql);
    } else {
        // 충전 성공 상태 등록


        echo "충전 성공정보저장 \n";
        $sql = "update mileage_fill set state='success',response_date=now() ,message='충전완료', response_date=now() where oid='" . $order_num . "'";
        $db->Execute($sql);

        // 충전성공 테이블 등록

        $rs = $db->Execute("insert into mileage_fill_succ (select * from mileage_fill where oid='" . $order_num . "' and state='success' limit 1)");  // 성공한 기록만..

        if (!$rs) {
            throw new Exception("충전 테이블 등록 실패", 5959);
        }
    }

    unset($sql);

    echo "디테일로그 상태변경";

    $db->Execute("update mile_detail_log set mile_state='sucess' where detail_id='" . $detail_id . "' ");

    if ($db->Affected_Rows() <1){
        throw new Exception("상태변경 오류",5490);
    }





    $db->CompleteTrans();
    echo "충전  끝 \n";

    ?>
    <ul>
        <hr width="80%"/>

            <li align="center">

            <button type='button' onclick="location.href='mileage_charging.php'">더 충전하기</button>

            <button type='button' onclick="location.href='../mileage_View/view_myMileage.php'"> 보유 마일리지 확인</button>

        </li>

    </ul>
</div>
    <?php
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



