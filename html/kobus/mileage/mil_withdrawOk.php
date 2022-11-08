<?php
error_reporting( E_ALL );
ini_set( "display_errors", 1 );
include('../function/function.php');
session_start();
try {
	/*세션 및 포스트값 확인*/
	if (!isset($_SESSION['number'])) {
		throw new exception('로그인이 필요합니다.');
	}

	if (!isset($_SESSION['userid'])) {
		throw new exception('로그인이 필요합니다.');
	}

	if (!isset($_SESSION['name'])) {
		throw new exception('로그인이 필요합니다.');
	}

	if (!isset($_POST['withdraw_type'])) {
		throw new exception('출금 타입을 확인하세요.');
	}

	if (!isset($_POST['mil_withdraw'])) {
		throw new exception('금액 확인이 필요합니다.');
	}
	
	/* 변수 초기화 */
	$strNumber = $_SESSION['number'];
	$strUserid = $_SESSION['userid'];
	$strName = $_SESSION['name'];
	$nType = $_POST['withdraw_type'];
	$nMil_withdraw = $_POST['mil_withdraw']-1000;
	$bTrans_check = true;
	
	/* DB 연결 시작 */
	$Classdb = new database;
	$Conn = $Classdb->db;
	if (!$Conn) {
		throw new exception('데이터베이스 연결 실패');
	}
	/* 트랜잭션 시작 */
	$bTrans_check = $Classdb->fnStart_trans();
	if($bTrans_check === false){
		throw new exception('트랜잭션 실패');
	}

	/* 마일리지 정보 테이블에 정보 입력 */
	$qryInsert = "
		INSERT INTO mileage SET
			number =		" . $strNumber . ",
			id =			'" . $strUserid . "',
			name =			'" . $strName . "',
			mil_chargeday =	now(),
			mil_type =		" . $nType . ",
			mil_use =		" . $nMil_withdraw . "
	";	
	
	$rstInsert = mysqli_query($Conn, $qryInsert);
	if (!$rstInsert) {
		throw new exception('쿼리 실행 오류1');
	}

	if (mysqli_affected_rows($Conn) < 1) {
		throw new exception('데이터 입력 오류');
	}
	

	/* 마일리지 차감 데이터 업데이트 */
	$qryUpdate = "
		UPDATE userinfo
		  SET mil_amount = mil_amount - " . $nMil_withdraw . " 
		 WHERE id = '" . $strUserid . "'
	";
	$rstUpdate = mysqli_query($Conn, $qryUpdate);	#테이블에 unsigned 넣어서 양수만 받을 수 있음
	if (!$rstUpdate) {
		throw new exception('쿼리 오류');
	}
	
	if (mysqli_affected_rows($Conn) < 1) {
		throw new exception('업데이트 오류');
	}
	
	# 커밋
	$Classdb->fnCommit();

	$strAlert = '출금이 완료되었습니다.';
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