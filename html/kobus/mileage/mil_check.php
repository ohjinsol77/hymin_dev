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
	if (!isset($_SESSION['name'])) {
		throw new exception('로그인이 필요합니다.');
	}

	/* 변수 초기화 */
	$strUserid = $_SESSION['userid'];
	$strName = $_SESSION['name'];

	/* DB 연결 시작 */
	$Classdb = new database;
	$Conn = $Classdb->db;
	if (!$Conn) {
		throw new exception('데이터베이스 연결 실패');
	}
	
	/* 유저 마일리지 보유액 조회 */
	$qrySelect = "
		SELECT mil_amount 
		  FROM userinfo 
		 WHERE id = " . $strUserid . "
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
	if ($rgSelect === false) {
		throw new exception('배열 입력에 실패했습니다.');
	}
	if ($rgSelect == null) {
		throw new exception('배열이 빈값입니다.');
	}
	
	/* 마일리지 충전,사용 내역 조회 */
	$qryCheck = "
		SELECT mil_charge, mil_use, mil_chargeday
		  FROM mileage 
		 WHERE id = " . $strUserid . "
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
		<h2>보유 마일리지</h2>
		<div>
			<p>아이디 : <?=$strUserid?></p>
			<p>이름 :	<?=$strName?></p>
			<p>보유 마일리지 : <?=$rgSelect['mil_amount']?></p>
			<input type = 'button' value = '홈으로 돌아가기' onclick = "window.location= '../userinfo/mainPage.php'">
			<input type = 'button' value = '로그아웃' onclick = "window.location= '../userinfo/logout.php'">
		</div>
		<div>
			<p>충전 마일리지 내역</p>

			<?php
			/* 사용 충전 내역 출력 */
			while ($rgRow = mysqli_fetch_array($rstCheck)) {
				if (!empty($rgRow['mil_charge'])) {
					echo "++충전금액 : " . $rgRow['mil_charge'] . " 발생일시 : " . $rgRow['mil_chargeday'] . "<br><br>";
				} elseif(!empty($rgRow['mil_use'])) {
					echo "--사용금액 : " . $rgRow['mil_use'] . " 발생일시 : " . $rgRow['mil_chargeday'] . "<br><br>";
				}
			}	
			?>
		</div>
	</body>
</html>

