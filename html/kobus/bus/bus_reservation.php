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
	
	if (!isset($_POST['route']) || empty($_POST['route'])) {
		throw new exception('포스트값 오류');
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
	$nRoute = $_POST['route'];
	$bTrans_check = true;
	
	if ($nRoute == 11 || $nRoute == 22) {
		$nMil_charge = 15900 + floor(15900*0.05);
	}else{
		$nMil_charge = 15600 + floor(15600*0.05);
	}
	
	switch ($nRoute) {
		case 11:
			$strMil_use = '하행 서울 -> 전주';
			break;

		case 22:
			$strMil_use = '상행 전주 -> 서울';
			break;

		case 33:
			$strMil_use = '하행 인천 -> 전주';
			break;

		case 44:
			$strMil_use = '상행 전주 -> 인천';
			break;

		default:
			echo
				$strAlert= '에러발생 : 결제 방식 오류';
				$strLocation = '../bus/bus_selectForm.php';
				/* 에러발생 함수 */
				fnAlert($strAlert,$strLocation);
			break;
	}

	$bTrans_check = $Classdb->fnStart_trans();
	if($bTrans_check === false){
		throw new exception('트랜잭션 실패');
	}
	$qryInsert = "
		INSERT INTO mileage SET
			usernumber =		" . $nUsernumber . ",
			userid =			'" . $strUserid . "',
			username =			'" . $strUsername . "',
			mil_chargeday =		now(),
			mil_type =			" . $nRoute . ",
			mil_charge =		" . $nMil_charge . ",
			mil_use =			'" . $strMil_use . "'
	";
	$rstInsert = mysqli_query($Conn, $qryInsert);
	if (!$rstInsert) {
		throw new exception('입력 과정 오류');
	}

	if (mysqli_affected_rows($Conn) < 1) {
		throw new exception('데이터 입력 오류');
	}

	$qryUpdate = "
		UPDATE userinfo 
		  SET amount = amount - " . $nMil_charge . " 
		 WHERE userid = " . $strUserid . "
		";
	$rstUpdate = mysqli_query($Conn,$qryUpdate);
	if(!$rstUpdate){
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
	$strLocation = '../bus/bus_selectForm.php';
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