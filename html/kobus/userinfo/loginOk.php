<?php
error_reporting( E_ALL );
ini_set( "display_errors", 1 );
include('../function/function.php');
try{
	$strUserid = $_POST['id'];
	$strUserpw = $_POST['pw'];
	if(!isset($strUserid) & empty($struserid)){
		throw new exception('p값 오류');
	}
	if(!isset($strUserpw) & empty($struserpw)){
		throw new exception('p값 오류');
	}

	/* DB 연결 시작 */
	$Classdb = new database;
	$Conn = $Classdb->db;
	if (!$Conn) {
		throw new exception('데이터베이스 연결 실패');
	}
	
	$qrySelect = "
		SELECT number, id, pw, name 
		  FROM userinfo 
		 WHERE id = " . $strUserid . "  and pw = " . $strUserpw . "
	";
	$rstSelect = mysqli_query($Conn,$qrySelect);

	if (!$rstSelect) {
		throw new exception('정보 조회 오류');
	}

	if (mysqli_num_rows($rstSelect) < 1) {
		throw new exception('로그인 정보 일치하지 않음');
	}
	
	$rgSelect = mysqli_fetch_array($rstSelect);

	session_start();
	$_SESSION['number'] = $rgSelect['number'];
	$_SESSION['userid'] = $rgSelect['id'];
	$_SESSION['userpw'] = $rgSelect['pw'];
	$_SESSION['name'] =  $rgSelect['name'];

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