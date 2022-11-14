<?php
error_reporting( E_ALL );
ini_set( "display_errors", 1 );
include('../function/function.php');
session_start();
try {
	/* 세션 확인 */
	if (!isset($_SESSION['userid'])) {
		throw new exception('로그인이 필요합니다.');
	}
	if (!isset($_SESSION['username'])) {
		throw new exception('로그인이 필요합니다.');
	}

	/* 변수 초기화 */
	$strUserid = $_SESSION['userid'];
	$strName = $_SESSION['username'];

	/* DB 연결 시작 */
	$Classdb = new database;
	$Conn = $Classdb->db;
	if (!$Conn) {
		throw new exception('데이터베이스 연결 실패');
	}
	
	/* 유저 마일리지 보유액 조회 */
	$qrySelect = "
		SELECT amount 
		  FROM userinfo 
		 WHERE userid = " . $strUserid . "
	";
	$rstSelect = mysqli_query($Conn,$qrySelect);
	if (!$rstSelect) {
		throw new exception('쿼리 오류');
	}

	if (mysqli_num_rows($rstSelect) < 1) {
		throw new exception('조회 오류');
	}

	/* 배열에 정보 대입 */
	$rgSelect = mysqli_fetch_array($rstSelect);
	if ($rgSelect == false) {
		throw new exception('배열 입력에 실패했습니다.');
	}
	if ($rgSelect == null) {
		throw new exception('배열이 빈값입니다.');
	}

} catch(exception $e) {
	$strAlert= '에러발생 : ' . $e->getMessage();
	$strLocation = '../userinfo/mainPage.php';
	/* 에러발생 함수 */
	fnAlert($strAlert,$strLocation);
	if ($Conn) {
		mysqli_close($Conn);
		unset($Conn);
	}
	exit;
}
?>
<html>
	<body align = 'center'>
		<h2>보유 마일리지</h2>
		<div>
			<p>아이디 : <?=$strUserid?></p>
			<p>이름 :	<?=$strName?></p>
			<p>보유 마일리지 : <?=$rgSelect['amount']?></p>
			<input type = 'button' value = '홈으로 돌아가기' onclick = "window.location= '../userinfo/mainPage.php'">
			<input type = 'button' value = '로그아웃' onclick = "window.location= '../userinfo/logout.php'">
			<input type = 'button' value = '사용 및 충전 내역' onclick = "window.location= '../mileage/mil_current.php'">
			<input type = 'button' value = '예매 정보' onclick = "window.location= '../bus/bus_check.php'">
		</div>
	</body>
</html>

