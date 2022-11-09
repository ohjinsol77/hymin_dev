<?php
error_reporting( E_ALL );
ini_set( "display_errors", 1 );
include('../function/function.php');
try {
		/* 포스트 값 검사 */               
	if (!isset($_POST['userid']) & empty($_POST['userid'])) {
		throw new exception('p값 오류');
	}

	if (!isset($_POST['userpw']) & empty($_POST['userpw'])) {
		throw new exception('p값 오류');
	}

	if (!isset($_POST['username']) & empty($_POST['username'])) {
		throw new exception('p값 오류');
	}

	if (!isset($_POST['mobile']) & empty($_POST['mobile'])) {
		throw new exception('p값 오류');
	}

	if (!isset($_POST['bitrhday']) & empty($_POST['birthday'])) {
		throw new exception('p값 오류');
	}

	if (!isset($_POST['gender']) & empty($_POST['gender'])) {
		throw new exception('p값 오류');
	}

	/* 변수 초기화 */
	$strUserid = $_POST['userid'];
	$strUserpw = $_POST['userpw'];
	$strName = $_POST['username'];
	$nMobile = $_POST['mobile'];
	$strBirthday = $_POST['birthday'];
	$nGender = $_POST['gender'];
	

	/* DB 연결 */
	$Classdb = new database;
	$Conn = $Classdb->db;
	if (!$Conn) {
		throw new exception('데이터베이스 연결 실패');
	}
	
	/* 중복 쿼리 조회 */
	$qrySelect = "
		SELECT userid 
		  FROM userinfo 
		 WHERE userid = '" .$strUserid. "'
	";
	$rstSelect = mysqli_query($Conn, $qrySelect);
	if (!$rstSelect) {
		throw new exception('조회 오류');
	}

	if (mysqli_num_rows($rstSelect) > 0) {
		throw new exception('중복된 아이디입니다.');
	}

	$qryInsert = "
		INSERT INTO userinfo set
			userid =		'" . $strUserid . "',
			userpw =		'" . $strUserpw . "',
			username =		'" . $strName . "',
			mobile =		'" . $nMobile . "',
			birthday =		'" . $strBirthday . "',
			regday =		now(),
			gender =		" . $nGender . ",
			amount =	0
	";
	$rstInsert = mysqli_query($Conn,$qryInsert);
	if (!$rstInsert) {
		throw new exception('정보 입력 오류1');
	}

	if (mysqli_affected_rows($Conn) < 1) {
		throw new exception('정보 입력 오류2');
	}

	$strAlert = '가입을 축하합니다.';
	$strLocation = 'mainPage.php';
	fnAlert($strAlert,$strLocation);
} catch(exception $e) {
	$strAlert= '에러발생 : ' . $e->getMessage();
	$strLocation = 'mainPage.php';
	/* 에러발생 함수 */
	fnAlert($strAlert,$strLocation);
	if ($Conn) {
		mysqli_close($Conn);
		unset($Conn);
	}
	exit;
}
?>