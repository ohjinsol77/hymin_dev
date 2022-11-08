<?php
error_reporting( E_ALL );
ini_set( "display_errors", 1 );
include('../function/function.php');
session_start();
try {
	if (!isset($_SESSION['number'])) {
		throw new exception('로그인이 필요합니다.');
	}

	if (!isset($_SESSION['userid'])) {
		throw new exception('로그인이 필요합니다.');
	}

	if (!isset($_SESSION['name'])) {
		throw new exception('로그인이 필요합니다.');
	}

	if (!isset($_POST['charge_type']) || empty($_POST['charge_type'])) {
		throw new exception('충전 타입을 확인하세요.');
	}

	if (!isset($_POST['mil_charge'])) {
		throw new exception('금액 확인이 필요합니다.');
	}

	$strNumber = $_SESSION['number'];
	$strUserid = $_SESSION['userid'];
	$strName = $_SESSION['name'];
	$nType = $_POST['charge_type'];
	$nMil_charge = $_POST['mil_charge'];

	
	/* DB 연결 시작 */
	$Classdb = new database;
	$Conn = $Classdb->db;
	if (!$Conn) {
		throw new exception('데이터베이스 연결 실패');
	}
	
	$qryInsert = "
		INSERT INTO mileage (number, id ,name, mil_chargeday, mil_type, mil_charge)
		VALUES (\"" . $strNumber . "\", \"" . $strUserid . "\", \"" . $strName . "\", now(), \"" . $nType . "\", \"" . $nMil_charge . "\")
	";

	$bTrans_check = $Classdb->fnStart_trans();
	if($bTrans_check !== true){
		throw new exception('트랜잭션 실패');
	}

	$rstInsert = mysqli_query($Conn, $qryInsert);
	if (!$rstInsert) {
		throw new exception('쿼리 실행 오류1');
	}

	if (mysqli_affected_rows($Conn) < 1) {
		throw new exception('데이터 입력 오류');
	}
	
	$qryUpdate = "
		UPDATE userinfo
		  SET mil_amount = mil_amount + " . $nMil_charge . " * 0.9
		 WHERE id = " . $strUserid . "
	";
	$rstUpdate = mysqli_query($Conn, $qryUpdate);
	if (!$rstUpdate) {
		throw new exception('쿼리 실행 오류2');
	}
	
	if (mysqli_affected_rows($Conn) < 1) {
		throw new exception('업데이트 오류');
	}
	
	$Classdb->fnCommit();

	$strAlert = '충전이 완료되었습니다.';
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