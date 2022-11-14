<?php
error_reporting( E_ALL );
ini_set( "display_errors", 1 );
include('../function/function.php');
session_start();
try {
	if (!isset($_SESSION['userid'])) {
		throw new exception('로그인이 필요합니다.');
	}

	if (!isset($_SESSION['usernumber'])) {
		throw new exception('로그인이 필요합니다.');
	}

	if (!isset($_SESSION['username'])) {
		throw new exception('로그인이 필요합니다.');
	}
	
	if (!isset($_POST['time'])) {
		throw new exception('출발 시간을 선택해주세요');
	}
	if (!isset($_POST['day'])) {
		throw new exception('날짜 포스트값 오류');
	}
	
	if (!isset($_POST['route'])) {
		throw new exception('루트 포스트값 오류');
	}

		/* DB 연결 시작 */
	$Classdb = new database;
	$Conn = $Classdb->db;
	if (!$Conn) {
		throw new exception('데이터베이스 연결 실패');
	}

	$strUserid = $_SESSION['userid'];
	$nUsernumber = $_SESSION['usernumber'];
	$strUsername = $_SESSION['username'];
	$dtTime = $_POST['time'];
	$strRoute = $_POST['route'];
	$dtDay = $_POST['day'];
	$bTrans_check = true;
	$strMil_use = '버스 예매';

	/* 목적지에 따라 버스 예매 금액 설정 */
	if ($strRoute == 'js_upbus' || $strRoute == 'js_downbus') {
		$nMil_minus = 15900 + floor(15900*0.05);
	} else {
		$nMil_minus = 15600 + floor(15600*0.05);
	}
	/* 트랜잭션 체크 */
	$bTrans_check = $Classdb->fnStart_trans();
	if($bTrans_check === false){
		throw new exception('트랜잭션 실패');
	}

	/* 예매 테이블에 마일리지 사용 내역 입력 */
	$qryInsert_Reservation = "
		insert into bus_reservation set
			userid =			'" . $strUserid ."',
			username =			'" . $strUsername . "',
			leave_day =			'" . $dtDay . "',
			leave_time =		'" . $dtTime . "',
			route =				'" . $strRoute . "',
			buy_day =			now(),
			bus_amount =		" . $nMil_minus . "
	";
	$rstInsert_Reservation = mysqli_query($Conn, $qryInsert_Reservation);
	if (!$rstInsert_Reservation) {
		throw new exception('입력 쿼리 오류');
	}

	if (mysqli_affected_rows($Conn) < 1) {
		throw new exception('데이터 입력 오류');
	}
	
	/* 예매로 사용된 마일리지 금액 마일리지 테이블에 데이터 입력 */
	$qryInsert_Mileage = "
		insert into mileage set
			usernumber =	" . $nUsernumber . ",
			userid =		'" . $strUserid . "',
			username =		'" . $strUsername . "',
			mil_changeday = now(),
			mil_type = 		'" . $strRoute . "',
			mil_use = 		'" . $strMil_use . "',
			mil_minus =		" . $nMil_minus . "
	";
	$rstInsert_Mileage = mysqli_query($Conn, $qryInsert_Mileage);
	if(!$rstInsert_Mileage){
		throw new exception('입력 쿼리 오류 2');
	}

	if (mysqli_affected_rows($Conn) < 1) {
		throw new exception('마일리지 테이블에 데이터 입력 오류');
	}

	/* 유저 정보 마일리지 금액 업데이트 */
	$qryUpdate_Userinfo = "
		UPDATE userinfo 
		  SET amount = amount - " . $nMil_minus . " 
		 WHERE userid = " . $strUserid . "
		";

	$rstUpdate_Userinfo = mysqli_query($Conn,$qryUpdate_Userinfo);
	if(!$rstUpdate_Userinfo){
		throw new exception('업데이트 과정 오류');
	}

	if(mysqli_affected_rows($Conn) < 1){
		throw new exception('업데이트 오류');
	}


	$Classdb->fnCommit();
	$strAlert= '예매 완료';
	$strLocation = '../userinfo/mainPage.php';
	/* 에러발생 함수 */
	fnAlert($strAlert,$strLocation);


	
} catch(exception $e) {
	$strAlert= '에러발생 : ' . $e->getMessage();
	$strLocation = '../bus/bus_reservation.php';
	/* 에러발생 함수 */
	fnAlert($strAlert,$strLocation);
	if ($Conn) {
		if ($bTrans_check == true) {
			$Classdb->fnRollback();
			$Classdb->fnCommit();
			unset($bTrans_check);
		}

		mysqli_close($Conn);
		unset($Conn);
	}
	exit;
}
?>