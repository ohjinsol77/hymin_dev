<?php
error_reporting( E_ALL );
ini_set( "display_errors", 1 );
include('../function/function.php');
session_start();
try {
	/* 포스트 및 세션 값 체크*/
	if (!isset($_SESSION['usernumber'])) {
		throw new exception('로그인이 필요합니다.');
	}

	if (!isset($_SESSION['userid'])) {
		throw new exception('로그인이 필요합니다.');
	}

	if (!isset($_SESSION['username'])) {
		throw new exception('로그인이 필요합니다.');
	}

	if (!isset($_POST['charge_type']) || empty($_POST['charge_type'])) {
		throw new exception('충전 타입을 확인하세요.');
	}

	if (!isset($_POST['mil_charge'])) {
		throw new exception('금액 확인이 필요합니다.');
	}
	
	/* 변수 초기화 */
	$nNumber = $_SESSION['usernumber'];
	$strUserid = $_SESSION['userid'];
	$strName = $_SESSION['username'];
	$nType = $_POST['charge_type'];
	$nMil_charge = $_POST['mil_charge'] * 0.9;
	$strMil_use = '마일리지 충전';
	

	/* DB 연결 시작 */
	$Classdb = new database;
	$Conn = $Classdb->db;
	if (!$Conn) {
		throw new exception('데이터베이스 연결 실패');
	}

	/* 트랜잭션 시작 */
	$bTrans_check = $Classdb->fnStart_trans();
	if($bTrans_check !== true){
		throw new exception('트랜잭션 실패');
	}
	/* 마일리지 충전 테이블에 데이터 입력 */
	$qryInsert = "
		INSERT INTO mileage set
			usernumber =	" . $nNumber . ",
			userid =		'" . $strUserid . "',
			username =		'" . $strName . "',
			mil_chargeday = now(),
			mil_type =		" . $nType . ",
			mil_charge =	" . $nMil_charge . ",
			mil_use =		'" . $strMil_use . "'
	";
	$rstInsert = mysqli_query($Conn, $qryInsert);
	if (!$rstInsert) {
		throw new exception('쿼리 실행 오류1');
	}

	if (mysqli_affected_rows($Conn) < 1) {
		throw new exception('데이터 입력 오류');
	}
	
	/* 유저 정보에 마일리지 충전 업데이트 */
	$qryUpdate = "
		UPDATE userinfo
		  SET amount = amount  + " . $nMil_charge . "
		 WHERE userid = '" . $strUserid . "'
	";

	$rstUpdate = mysqli_query($Conn, $qryUpdate);
	if (!$rstUpdate) {
		throw new exception('쿼리 실행 오류2');
	}
	
	if (mysqli_affected_rows($Conn) < 1) {
		throw new exception('업데이트 오류');
	}

	#커밋
	$Classdb->fnCommit();

	$strAlert = '예매 취소 완료';
	$strLocation = '../userinfo/mainPage.php';
	fnAlert($strAlert,$strLocation);
} catch(exception $e) {
	$strAlert= '에러발생 : ' . $e->getMessage();
	$strLocation = '../userinfo/mainPage.php';
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