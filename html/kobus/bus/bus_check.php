<?php
error_reporting( E_ALL );
ini_set( "display_errors", 1 );
include('../function/function.php');
session_start();
try {
	if (!isset($_SESSION['userid'])) {
		throw new exception('로그인이 필요합니다.');
	}

	/* DB 연결 시작 */
	$Classdb = new database;
	$Conn = $Classdb->db;
	if (!$Conn) {
		throw new exception('데이터베이스 연결 실패');
	}

	$strUserid = $_SESSION['userid'];
	
	/* 예매 정보 조회 */
	$qrySelect_Reservation = "
		Select userid, username, leave_day, leave_time, route, buy_day, bus_amount
		  from bus_reservation
		 where userid = '" . $strUserid . "'
	";
	$rstSelect_Reservation = mysqli_query($Conn, $qrySelect_Reservation);
	if (!$rstSelect_Reservation) {
		throw new exception('조회 쿼리 오류');
	}

	if (mysqli_affected_rows($Conn) < 1) {
		throw new exception('조회 정보가 없습니다.');
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
		<h1>예매 정보 확인</h2>
		<table border = 1 align = 'center'>
			<?php
			while ($rgReservation = mysqli_fetch_array($rstSelect_Reservation)) {
				?>
				<tr><th>
					아이디		: <?=$rgReservation['userid']?>		이름 : <?=$rgReservation['username']?>	출발일자	: <?=$rgReservation['leave_day']?>
					출발시간	: <?=$rgReservation['leave_time']?>	루트 : <?=$rgReservation['route']?>		구매일		: <?=$rgReservation['buy_day']?>
					사용금액	: <?=$rgReservation['bus_amount']?>
				</th></tr>
				<?php
			}
			?>
		</table>
	</body>
</html>