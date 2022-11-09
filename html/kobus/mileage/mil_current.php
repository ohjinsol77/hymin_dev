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

	/* 변수 초기화 */
	$strUserid = $_SESSION['userid'];

	/* DB 연결 시작 */
	$Classdb = new database;
	$Conn = $Classdb->db;
	if (!$Conn) {
		throw new exception('데이터베이스 연결 실패');
	}

	/* 마일리지 충전,사용 내역 조회 */
	$qryCheck = "
		SELECT mil_charge, mil_use, mil_chargeday
		  FROM mileage 
		 WHERE userid = '" . $strUserid . "'
		 order by mil_chargeday desc
	";
	$rstCheck = mysqli_query($Conn, $qryCheck);
	if (!$rstCheck) {
		throw new exception('체크 쿼리 오류');
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
		<h2>마일리지 사용 및 충전 내역</h2>
			<p>마일리지 내역</p>
			<p><table border=1 align = 'center'>
				<?php
					/* 사용 충전 내역 출력 */
					while ($rgRow = mysqli_fetch_array($rstCheck)) {
						?>
						<tr><th>금액 = <?=$rgRow['mil_charge']?>/ 유동 내역 = <?=$rgRow['mil_use']?>/ 일시 = <?=$rgRow['mil_chargeday']?></th></tr>
						<?php
					}
				?>
			</table><p>
		<div>
			<input type = 'button' value = '홈으로 돌아가기' onclick = "window.location= '../userinfo/mainPage.php'">
			<input type = 'button' value = '로그아웃' onclick = "window.location= '../userinfo/logout.php'">
		</div>
	</body>
</html>


