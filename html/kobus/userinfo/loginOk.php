<?php
error_reporting( E_ALL );
ini_set( "display_errors", 1 );
include('function.php');
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
		SELECT id, pw 
		  FROM userinfo 
		 WHERE id = \"$strUserid\"  and pw = \"$strUserpw\"
	";
	$rstSelect = mysqli_query($Conn,$qrySelect);

	if(!$rstSelect){
		throw new exception('정보 조회 오류');
	}
	
	if (mysqli_num_rows($rstSelect) < 1) {
		throw new exception('로그인 정보 일치하지 않음');
	}
	session_start();
	$_SESSION['id'] = $strUserid;
} catch(exception $e) {
	$strAlert= '에러발생 : ' . $e->getMessage();
	/* 에러발생 함수 */
	fnAlert($strAlert);
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