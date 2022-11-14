<?php
error_reporting( E_ALL );
ini_set( "display_errors", 1 );
include('../function/function.php');
session_start();
try {
	if (!isset($_SESSION['userid'])) {
		throw new exception('로그인이 필요합니다.3');
	}

	if (!isset($_SESSION['username'])) {
		throw new exception('로그인이 필요합니다.1');
	}

	if (!isset($_SESSION['usernumber'])) {
		throw new exception('로그인이 필요합니다.2');
	}

	if (!isset($_POST['cancell'])) {
		throw new exception('use 오류.');
	}

	$strUserid = $_SESSION['userid'];
	$strUsername = $_SESSION['username'];
	$nUsernumber = $_SESSION['usernumber'];
	$nCancell = $_POST['cancell'];
	
	/* DB 연결 시작 */
	$Classdb = new database;
	$Conn = $Classdb->db;
	if (!$Conn) {
		throw new exception('데이터베이스 연결 실패');
	}
	/*마일리지 mil_type에 입력할 루트 값 가져오기*/
	$qrySelect_Reservation = "
		SELECT route, bus_amount, route
		  FROM bus_reservation
		 WHERE ordernumber = " . $nCancell ."
	";
	$rstSelect_Reservation = mysqli_query($Conn, $qrySelect_Reservation);
	if (!$rstSelect_Reservation) {
		throw new exception('루트 조회 쿼리 오류');
	}

	if (mysqli_num_rows($rstSelect_Reservation) < 1) {
		throw new exception('루트 조회 오류');
	}
	
	$rgRoute = mysqli_fetch_array($rstSelect_Reservation);
	if (!$rgRoute) {
		throw new exception('루트 배열 대입 오류');
	}
	if($rgRoute == null) {
		throw new exception('루트 배열 빈값');
	}

	/* 취소금액 반환 위해 조회 */
	$qrySelect_Pay = "
		SELECT * 
		  FROM bus_pay
		 WHERE route = '" . $rgRoute['route'] . "'
	";
	$rstSelect_Pay = mysqli_query($Conn, $qrySelect_Pay);
	if (!$rstSelect_Pay) {
		throw new exception('페이 조회 쿼리 오류');
	}

	if (mysqli_num_rows($rstSelect_Pay) < 1) {
		throw new exception('페이 조회 오류');
	}
	
	$rgPay_Route = mysqli_fetch_array($rstSelect_Pay);
	if (!$rgPay_Route) {
		throw new exception('페이 배열 대입 오류1');
	}

	/* 예매 수수료 5%중 3%를 뺀 나머지 값을 반환하기 위한 변수 초기화*/
	$nPay_Route = floor($rgPay_Route['pay']*0.02);
	$nMil_charge = $rgRoute['bus_amount'] - $nPay_Route;

	/* 트랜잭션 체크 */
	$bTrans_check = $Classdb->fnStart_trans();
	if ($bTrans_check === false) {
		throw new exception('트랜잭션 실패');
	}

	/* 예매 취소 정보 마일리지 사용 내역에 데이터 입력 */
	$qryInsert_mileage = "
		INSERT INTO mileage SET
		  usernumber =		'" . $nUsernumber . "',
		  userid =			'" . $strUserid . "',
		  username =		'" . $strUsername . "',
		  mil_changeday =	now(),
		  mil_type =		'" . $rgRoute['route'] . "',
		  mil_charge =		" . $nMil_charge . ",
		  mil_use =			'버스 예매 취소'
	";	
	$rstInsert_mileage = mysqli_query($Conn, $qryInsert_mileage);
	if (!$rstInsert_mileage) {
		throw new exception('데이터 입력 쿼리 오류');
	}

	if (mysqli_affected_rows($Conn) < 1) {
		throw new exception('데이터 입력 오류');
	}
	
	/* 취소금액 반환 위한 userinfo 마일리지 업데이트*/
	$qryUpdate_userinfo = "
		UPDATE userinfo 
		  SET amount = amount + " . $nMil_charge . " 
		 WHERE usernumber = " . $nUsernumber . "
	";
	$rstUpdate_userinfo = mysqli_query($Conn, $qryUpdate_userinfo);
	if (!$rstUpdate_userinfo) {
		throw new exception('업데이트 쿼리 오류');
	}
	if (mysqli_affected_rows($Conn) < 1) {
		throw new exception('업데이트 오류');
	}
	
	$qryDelete_Reservation = "
		DELETE FROM bus_reservation 
		 WHERE ordernumber = " . $nCancell . "
	";
	$rstDelete_Reservation = mysqli_query($Conn, $qryDelete_Reservation);
	if (!$rstDelete_Reservation) {
		throw new exception('삭제 쿼리 오류');
	}

	if (mysqli_affected_rows($Conn) < 1) {
		throw new exception('데이터 삭제 오류');
	}

	$Classdb->fnCommit();
	$strAlert= '예매 취소 완료';
	$strLocation = '../userinfo/mainPage.php';
	/* 에러발생 함수 */
	fnAlert($strAlert,$strLocation);

	
} catch(exception $e) {
	$strAlert= '에러발생 : ' . $e->getMessage();
	$strLocation = '../bus/bus_cancellForm.php';
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