<?php
error_reporting( E_ALL );
ini_set( "display_errors", 1 );
include('../function/function.php');
try {

	/* 변수 초기화 */
	$strUserid = $_POST['id'];
	$strUserpw = $_POST['pw'];
	$strName = $_POST['name'];
	$strMobile = $_POST['mobile'];
	$strBirthday = $_POST['birthday'];
	$nGender = $_POST['gender'];
	
	/* 포스트 값 검사 */               /* 아이디 중복체크 나중에 해야함 */
	if (!isset($strUserid) & empty($struserid)) {
		throw new exception('p값 오류');
	}

	if (!isset($strUserpw) & empty($struserpw)) {
		throw new exception('p값 오류');
	}

	if (!isset($strName) & empty($strName)) {
		throw new exception('p값 오류');
	}

	if (!isset($strMobile) & empty($strMobile)) {
		throw new exception('p값 오류');
	}

	if (!isset($strBirthday) & empty($strBirthday)) {
		throw new exception('p값 오류');
	}
	
	/* DB 연결 */
	$Classdb = new database;
	$Conn = $Classdb->db;
	if (!$Conn) {
		throw new exception('데이터베이스 연결 실패');
	}
	
	/* 중복 쿼리 조회 */
	$qrySelect = "
		SELECT id 
		  FROM userinfo 
		 WHERE id = " .$strUserid. "
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
			id =			'" . $strUserid . "',
			pw =			'" . $strUserpw . "',
			name =			'" . $strUserpw . "',
			mobile =		'" . $strName . "',
			birthday =		'" . $strBirthday . "',
			memday =		now(),
			gender =		" . $nGender . ",
			mil_amount =	0
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