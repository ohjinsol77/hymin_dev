<?php
error_reporting( E_ALL );
ini_set( "display_errors", 1 );
include('../function/function.php');
session_start();
try {
	
	if (!isset($_SESSION['userid'])) {
		$strAlert = $strAlert= '에러발생 : 로그인이 필요합니다' ;
		$strLocation = '../userinfo/loginForm.php';
		fnAlert($strAlert,$strLocation);
	}
		/* DB 연결 시작 */
	$Classdb = new database;
	$Conn = $Classdb->db;
	if (!$Conn) {
		throw new exception('데이터베이스 연결 실패');
	}
	
	$strUserid = $_SESSION['userid'];
	$qrySelect_Reservation = "
		SELECT ordernumber, leave_day, leave_time, route
		FROM bus_reservation
		WHERE userid = " . $strUserid . " and cancell_day is null
	";
	$rstSelect_Reservation = mysqli_query($Conn,$qrySelect_Reservation);
	if(!$rstSelect_Reservation){
		throw new exception('쿼리 오류');
	}

	if(mysqli_num_rows($rstSelect_Reservation) < 1) {
		throw new exception('조회 가능한 내역이 없습니다.');
	}
}catch(exception $e) {
	$strAlert= '에러발생 : ' . $e->getMessage();
	$strLocation = '../userinfo/mainPage.php';
	/* 에러발생 함수 */
	fnAlert($strAlert,$strLocation);
}
?>
<html>
	<h2>버스 예매 취소 폼</h2>
	<body align = 'center'>
		<p>버스 예매 취소 홈.</p>
		<p>반환 수수료는 3%입니다.</p>
		<form name = "bus_selectForm" method = "post" action = "../bus/bus_cancellOk.php">
			<select name = 'cancell'>
				<?php
				while($rgReservation = mysqli_fetch_array($rstSelect_Reservation)){
					?>									
					<option value = '<?=$rgReservation['ordernumber']?>'>
							출발 날짜	<?=$rgReservation['leave_day']?>
							출발 시간	<?=$rgReservation['leave_time']?>
							루트		<?=$rgReservation['route']?>
					</option>
					<?
				}		
				?>
			</select>
			<input type = 'submit' value = '예매 취소하기'>
		</form>
		<li>
			<input type = 'button' value = '홈으로 돌아가기' onclick = "window.location= '../userinfo/mainPage.php'">
		</li>
	</body>
</html>