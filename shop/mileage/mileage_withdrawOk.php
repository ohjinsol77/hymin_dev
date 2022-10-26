<?php
/** 출금 완료 페이지**/
include("../_inc/header.php");
require("../adodb5/adodb.inc.php");

ini_set('display_errors', true);
error_reporting(E_ALL);

?>

    <html>
<body>
    <h2>출금 결과 페이지</h2>
    <hr width="80%"/>
<div id="#contsRow">
<?php
echo "출금 프로세스 시작\n\n";
try {
	///mysql과 db연결과정
	$driver = 'mysqli';
    $db = newAdoConnection($driver);
    $db->debug = false;
    $db->socket = '/var/run/mysqld/mysql_3306.sock';
	///db 연결
    $db->connect('localhost', 'root', 'Itemmania1324%^', 'study');
	///db가 연결되지 않으면
    if(!$db){
		///예외처리
        throw new Exception("데이터 연결오류",1);
    }

    /** 사용 변수  **/
    $pointPrice = $_POST['pointPrice'];     // 유저가 입력한 금액
    $member_bank = $_POST['bank_type'];    //유저가 입력한 은행타입
    $member_bankNum = $_POST['nbankNum'];    // 유저가 입력한 계좌번호
    $member_num = $_SESSION['member_Session_number'];    //로그인되어있는 멤버의 멤버번호
    $mileage_num = $_SESSION['member_Session_mileage'];    //로그인되어있는 멤버의 마일리지번호
    $withdraw_price = 900;    //출금 수수료
    $sel_sum = $pointPrice + $withdraw_price;    //사용자 입력금액+수수료
    $mem_cash = 0;
    $mem_credit = 0;                                                      // 사용변수 초기화
    $mem_phone = 0;
    $trans_check=null;                                          // 트랜젝션 체크
    $order_number = date("YmdHis").$mileage_num;        // 지금시간 + 회원 마일리지 번호
    $order_num = 'withdraw'.$order_number;                    //신규 충전주문번호(oid)// (상태명+주문번호)
    $account_num = 'account_book' . $order_number;             //신규 장부코드 번호
    $step = $_POST['step'];                                     // 충전 스텝 체크



    //변수체크

    echo "변수 체크\n";
	///만약 member_bank가 빈값이거나, member_bank가 존재하지 않으면
    if($member_bank==null || !isset($member_bank)){
		///예외처리(메시지,코드)
        throw new Exception("비정상 출금/ 다시 시도해주세요",8898);
    }///만약 pointPrice빈값이고 $pointPrice가 0이하이면
    if ($pointPrice==null && $pointPrice<=0){
		///예외처리
        throw new Exception("비정상 출금/ 다시 시도해주세요",8898);
    }///member_bank가 빈값이거나 member_bank가 0이면
    if ($member_bank==null || empty($member_bank)){
		///예외처리
        throw new Exception("비정상 출금/ 다시 시도해주세요",8898);
    }///membeR_bankNum이 빈값이거나 0원이면
    if ($member_bankNum==null || empty($member_bankNum)){
		///예외처리
        throw new Exception("비정상 출금/ 다시 시도해주세요",8898);
    }///sel_sum이 0원 이하이거나 withdraw_price보다 작다면
    if($sel_sum<=0 || $sel_sum<$withdraw_price){
		///예외처리
        throw new Exception("비정상 출금/ 다시 시도해주세요",8898);
    }

    // 충전 전 회원정보 조회 & 저장 ▼ //
    echo"회원 정보 조회 \n";
	///member테이블에서 num,id,name,tel을 조회하는데 조건은 member_num과 폼에서 받은 member_num값이 같을 때
    $rs = $db->Execute("select member_num, member_id, member_name, member_tel from member where member_num='".$member_num."'");
	///ㅁ나약 rs가 빈값이면
    if ($rs == null) {
		///예외처리
        throw new Exception ("정보조회 오류 다시 시도하세요", 81);
    }
	
    // 회원조회 결과
    ///rs가 아닌값이 EOF를 만나면 종료
	while (!$rs->EOF) {
        $mem_num = $rs->fields[0];
        $mem_id = $rs->fields[1];
        $mem_name = $rs->fields[2];
        $mem_tel= $rs->fields[3];
		///다음 커서로 이동
        $rs->MoveNext();
    }

    echo "user_id=$mem_id";
	///만약 mem_num이 빈겂이고 member_num변수가 존재하지 않으면
    if (empty($mem_num) & !isset($member_num)) {
		///예외처리
        throw new Exception("정보조회 오류",4956);
    }///mem_id가 존재하면 true이고 오른쪽으로 넘어가서 mem_id가 존재하지 않는지 확인하고 true이면 다시 오른쪽으로 넘어가서 세션값이 mem_id와 일치하지 않는지 확인
    if (empty($mem_id) && !isset($mem_id) && $_SESSION['member_Session_id']!=$mem_id) {
		///예외처리
        throw new Exception("정보조회 오류",4956);
    }///mem_nam이 빈값이고 member_name변수가 없으면
    if (empty($mem_name) & !isset($member_name)) {
		///예외처리
        throw new Exception("정보조회 오류",4956);
    }///mem_tel이 빈값이고 member_tel변수가 존재하지 않으면
    if (empty($mem_tel) & !isset($member_tel)) {
		///예외처리
        throw new Exception("정보조회 오류",4956);
    }


    //$ShopFunction->fnSetUser($user_info);
    // 충전 전 회원정보 조회 & 저장 ▲ //

    echo "트랜젝션 시작\n";
	///트랜잭션 시작
    $trans_check=$db->StartTrans();
	///trans_check가 빈값이면
    if ($trans_check == null) {
		///예외처리
        throw new Exception("트랜젝션오류", 44);
    }


    echo "보유 마일리지 조회\n";
	// 기존에 멤버가 가지고 있는 마일리지 조회
	///mileage테이블에서 id,cash_amount,credit_amount,phone_amount를 조회하는데 조건은 meeber_num이 $member_num과 같고 레코드 락을 걸어 다른 사람이 이 행을 건들지 못하도록 막음
    $rs = $db->Execute("select mileage_id, cash_amount, credit_amount, phone_amount from mileage where member_num=$member_num  for update");
	///rs가 아닐때까지 루프를 돌린다
    while (!$rs->EOF) {
        $mileage_id = $rs->fields[0];
        $mem_cash = $rs->fields[1];
        $mem_credit = $rs->fields[2];
        $mem_phone = $rs->fields[3];
        $rs->MoveNext();
    }
	///만약 db에서 변경된 횟수가 1이이면
    if ($db->Affected_Rows() <1){
        throw new Exception("정보조회 오류",5490);
    }



    //출금 거래번호 입력 (trade_id)

    echo "order_num=".$order_num ."\n";

    $sql = "insert into trade_id (oid,reg_date) values('".$order_num."', now())";
    $db->Execute($sql);

    if($db->Affected_Rows() <1){
        throw new Exception("거래번호 등록 오류",557);
    }

    echo "출금내역 입력\n";
    /******************************출금 db 테이블 입력 S******************************************/
    $rs = $db->Execute("insert into withdraw(member_num,withdraw_price,withdraw_banknum,withdraw_bank,withdraw_regdate) values($member_num,$sel_sum,$member_bankNum,$member_bank,now())");
    /******************************출금 db 테이블 입력 E******************************************/

    if($db->Affected_Rows() <1){
        throw new Exception("출금정보 입력 오류",4445);
    }

    echo "금액차감 프로세스 시작\n";
    /******************************포인트별 출금 금액 차감 프로세스*******************************************/

    if ($sel_sum > $mem_cash && $mem_cash >= 0) {           // 현금이 총 금액보다 적고 0원보단 많이 있을 때
        $sel_sum = $sel_sum - $mem_cash;
        $recod_cash = $mem_cash;
        $mem_cash = 0;
        if ($sel_sum > $mem_credit && $mem_credit >= 0) {   // 신용카드가 총 금액보다 적고 0원보단 많이 있을 때
            $sel_sum = $sel_sum - $mem_credit;
            $recod_credit = $mem_credit;
            $mem_credit = 0;
            if ($sel_sum > $mem_phone || $mem_phone <= 0) { // 핸드폰 총 금액보다 결제 총액이 더 많을 때 ==> 오류발생
                throw new Exception("잔액부족",1588);
            } else {                                      // 핸드폰이 총 금액으로 마무리 계산,
                $mem_phone = $mem_phone - $sel_sum;
                $recod_phone = $sel_sum;
           
            }
        } else {                                          // 신용카드가 총 금액보다 클 때
            $mem_credit = $mem_credit - $sel_sum;
            $recod_credit = $sel_sum;
			///recod_phone변수를 정해주지 않으면 변수가 없다는 오류 출력
			$recod_phone = 0;
            $sel_sum=0;

			/**************************************
			$mem_credit = $mem_credit - $sel_sum;
            $recod_credit = $sel_sum;
			///recod_phone변수를 정해주지 않으면 변수가 없다는 오류 출력
			$recod_phone = 0;
            $sel_sum = 0;
			**************************************/
        }
    } else {
		$mem_cash = $mem_cash - $sel_sum;
		$recod_cash = $sel_sum;
		///credit,phone부분 변수 정해주지 않았을 때 변수 없다는 오류 출력
		$recod_credit = 0;
		$recod_phone = 0;
		$sel_sum=0;
		
		
		/**************************************
		// 현금이 총 금액보다 클 때
        $mem_cash = $mem_cash - $sel_sum;
        $recod_cash = $sel_sum;
        $sel_sum = 0;
		********************/
    }

    echo "// 캐쉬 :" . $mem_cash;
    echo "// 카드:" . $mem_credit;
    echo "// 폰:" . $mem_phone;
	///sel_sum으로 하게되면 초기화된 값으로 0이 나오게 돼서 총 출금 값을 알 수 없음
	echo "// 출금 값:" . $recod_cash + $recod_credit + $recod_phone . "<br/>" . "<br/>";
	/***********************************************
    echo "// 출금 값:" . $sel_sum . "<br/>" . "<br/>";
	************************************************/



    echo $recod_cash . "<br/>";
    echo $recod_credit . "<br/>";
    echo $recod_phone . "<br/>";
    /******************************포인트별 금액 차감 프로세스 E*******************************************/

    echo "마일리지 사용 내역 입력\n";
    /*******************마일리지 사용 쿼리문작성*********************/
    if (isset($recod_cash) && $recod_cash > 0) {
        $rs = $db->Execute("insert into cash_mileage(mileage_id,member_num, cash_type, cash_price, cash_amount) values ($mileage_num, $member_num, 102, $recod_cash, (select ifnull((select  A.cash_amount from cash_mileage A where A.member_num=$member_num order by A.cash_regdate desc limit 1 ),0)-$recod_cash))");
    }
    if (isset($recod_credit) && $recod_credit > 0) {
        $rs = $db->Execute("insert into credit_mileage(mileage_id, member_num, credit_type, credit_price, credit_amount) values ($mileage_num, $member_num, 202, $recod_credit, (select ifnull((select  A.credit_amount from credit_mileage A where A.member_num=$member_num order by A.credit_regdate desc limit 1 ),0)-$recod_credit))");
    }
    if (isset($recod_phone) && $recod_phone > 0) {
        $rs = $db->Execute("insert into phone_mileage(mileage_id, member_num, phone_type, phone_price, phone_amount) values ($mileage_num, $member_num, 302, $recod_phone, (select ifnull((select  A.phone_amount from phone_mileage A where A.member_num=$member_num order by A.phone_regdate desc limit 1 ),0)-$recod_phone))");
    }

    if (!$rs) {
        throw new Exception("쿼리입력 오류",4);
    }

    // 사용가능한 전체 마일리지 조회
    echo"모든 마일리지 조회 \n";
    $rs = $db->Execute("select (mil.cash_amount +mil.credit_amount+mil.phone_amount+mil.buymileage_amount) as `Amount` from mileage mil join member mem on mil.member_num=mem.member_num where mem.member_num=$mem_num for update ");
    $after_money=$rs->fields['Amount'];
    if($after_money <=0){
        throw new Exception("충전금액오류",4);
    }

    // 디테일 리스트 작성
    echo"디테일 리스트 작성 \n";

    $detail_list = "insert into mile_detail_list set user_no='".$mem_num."', user_id='".$mem_id."', account_code='".$account_num."', mile_code='".$mileage_num."',payment_money='".$sel_sum."',remain_money='".$after_money."', payment_date=now()";
    $db->Execute($detail_list);
    if ($db->Affected_Rows() < 0){
        throw new Exception("디테일리스트 작성오류",305);
    }
    $detail_id = $db->Insert_id();

    // 디테일 리스트 작성
    echo"디테일 로그 작성 \n";

    $detail_list = "insert into mile_detail_log set detail_id='".$detail_id."', user_no='".$mem_num."', user_id='".$mem_id."', mile_code='".$mileage_num."',mile_money='".$sel_sum."', trade_id='".$order_num."', ins_type='w',ins_result='1',ins_date=now(),mile_state='sucess'";
    $db->Execute($detail_list);
    if ($db->Affected_Rows() < 0){
        throw new Exception("디테일리스트 작성오류",305);
    }


    $db->CompleteTrans();
    /*******************마일리지 사용 쿼리문작성 E*********************/

    echo "출금처리 끝\n";
    ?>


    </div><input type="button" value="go home" onclick="window.location='../index.php'">
    </body>
    </html>
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