<?php
error_reporting( E_ALL );
ini_set( "display_errors", 1 );
include('../function/function.php');
session_start();
try {
	/* 포스트 및 세션 값 검사 */ 
	if(!isset($_SESSION['userid'])) {
		throw new exception('세션값 오류');
	}
              
	if (!isset($_POST['userpw']) & empty($_POST['userpw'])) {
		throw new exception('p값 오류');
	}

	if (!isset($_POST['mobile']) & empty($_POST['mobile'])) {
		throw new exception('p값 오류');
	}
	if (!isset($_POST['birthday']) & empty($_POST['birthday'])) {
		throw new exception('p값 오류');
	}

	/* 변수 초기화 */
	$strUserid = $_SESSION['userid'];
	$strUserpw = $_POST['userpw'];
	$strMobile = $_POST['mobile'];
	$strBirthday = $_POST['birthday'];
	$bTrans_check = true;
	

	$Classdb = new database;
	$Conn = $Classdb->db;
	if (!$Conn) {
		throw new exception('데이터베이스 연결 실패');
	}

	$qrySelect = "
		SELECT userid, userpw, mobile, birthday 
		  FROM userinfo 
		 WHERE userid = '" . $strUserid . "' and userpw = '" . $strUserpw . "' and mobile =  " . $strMobile . " and birthday = " . $strBirthday . "
	";
	$rstSelect = mysqli_query($Conn, $qrySelect);
	if (!$rstSelect) {
		throw new exception('조회 오류');
	}

	if (mysqli_num_rows($rstSelect) < 1) {
		throw new exception('회원 조회 오류.');
	}

	$bTrans_check = $Classdb->fnStart_trans();
	if($bTrans_check === false){
		throw new exception('트랜잭션 실패');
	}

	$qryInsert = "
		INSERT INTO deluserinfo 
		  SELECT * 
		 FROM userinfo where userid = '" . $strUserid . "'
	";
	$rstInsert = mysqli_query($Conn,$qryInsert);
	if (!$rstInsert) {
		throw new exception('회원 삭제 정보 입력 오류1');
	}
	if (mysqli_affected_rows($Conn) < 1) {
		throw new exception('회원 삭제 정보 입력 오류2');
	}
	
	$qryDelete = "
		DELETE FROM userinfo 
		 WHERE userid = '" . $strUserid . "'
	";
	$rstDelete = mysqli_query($Conn,$qryDelete);
	if (!$rstDelete) {
		throw new exception('정보 입력 오류1');
	}

	if (mysqli_affected_rows($Conn) < 1) {
		throw new exception('정보 입력 오류2');
	}
	
	$Classdb->fnCommit();
	session_destroy();

	$strAlert = '탈퇴완료.';
	$strLocation = 'mainPage.php';
	fnAlert($strAlert,$strLocation);
} catch(exception $e) {
	$strAlert= '에러발생 : ' . $e->getMessage();
	$strLocation = 'mainPage.php';
	fnAlert($strAlert,$strLocation);
	/* 에러발생 함수 */
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