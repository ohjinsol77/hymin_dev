<?php
error_reporting( E_ALL );
ini_set( "display_errors", 1 );
include('../function/function.php');
try{

	if(!isset($_POST['userid']) & empty($_POST['userid'])){
		throw new exception('p값 오류');
	}

	if(!isset($_POST['userpw']) & empty($_POST['userpw'])){
		throw new exception('p값 오류');
	}
	
	$strUserid = $_POST['userid'];
	$strUserpw = $_POST['userpw'];


	/* DB 연결 시작 */
	$Classdb = new database;
	$Conn = $Classdb->db;
	if (!$Conn) {
		throw new exception('데이터베이스 연결 실패');
	}
	
	$qrySelect = "
		SELECT usernumber, userid, userpw, username 
		  FROM userinfo 
		 WHERE userid = " . $strUserid . "  and userpw = " . $strUserpw . "
	";
	$rstSelect = mysqli_query($Conn,$qrySelect);
	if (!$rstSelect) {
		throw new exception('정보 조회 오류');
	}

	if (mysqli_num_rows($rstSelect) < 1) {
		throw new exception('로그인 정보 일치하지 않음');
	}
	
	$rgSelect = mysqli_fetch_array($rstSelect);
	if (!$rgSelect) {
		throw new exception('배열 입력에 실패했습니다.');
	}

	if ($rgSelect == null) {
		throw new exception('빈값입니다.');
	}

	session_start();
	$_SESSION['usernumber'] = $rgSelect['usernumber'];
	$_SESSION['userid'] = $rgSelect['userid'];
	$_SESSION['userpw'] = $rgSelect['userpw'];
	$_SESSION['username'] =  $rgSelect['username'];

	$strAlert = '로그인 성공';
	$strLocation = 'mainPage.php';
	fnAlert($strAlert,$strLocation);
} catch(exception $e) {
	$strAlert= '에러발생 : ' . $e->getMessage();
	$strLocation = 'loginForm.php';
	/* 에러발생 함수 */
	fnAlert($strAlert,$strLocation);
	if ($Conn) {
		mysqli_close($Conn);
		unset($Conn);
	}
	exit;
}
?>