<?php
error_reporting( E_ALL );
ini_set( "display_errors", 1 );
include('../function/function.php');
session_start();
try {
	if (!isset($_SESSION['userid'])) {
		throw new exception('로그인이 필요합니다.');
	}

	if (!isset($_SESSION['usernumber'])) {
		throw new exception('로그인이 필요합니다.');
	}
	if (!isset($_SESSION['username'])) {
		throw new exception('로그인이 필요합니다.');
	}
	
	if (!isset($_POST['route']) || empty($_POST['route'])) {
		throw new exception('루트 포스트값 오류');
	}

	if (!isset($_POST['day']) || empty($_POST['day'])) {
		throw new exception('날짜 포스트값 오류');
	}

	if (!isset($_POST['bustime']) || empty($_POST['bustime'])) {
		throw new exception('날짜 포스트값 오류');
	}

		/* DB 연결 시작 */
	$Classdb = new database;
	$Conn = $Classdb->db;
	if (!$Conn) {
		throw new exception('데이터베이스 연결 실패');
	}

	$strUserid = $_SESSION['userid'];
	$nUsernumber = $_SESSION['usernumber'];
	$strUsername = $_SESSION['username'];
	$strRoute = $_POST['route'];
	$dtDay = $_POST['day'];
	$dtTime = $_POST['bustime'];



	/* 짝수,홀수 날짜에 따라서 버스 시간표 출력 */
	$qryBus_time = "
		SELECT IF( ". $dtDay ." % 2 = 0, even, odd) AS bus_time, bus_num
		  FROM " . $strRoute . "
		 HAVING bus_time >= '" . $dtTime . "'
	";
	$rstBus_time = mysqli_query($Conn,$qryBus_time);
	if(mysqli_num_rows($rstBus_time) < 1){
		throw new exception('버스 시간 조회 오류');
	}
	
} catch(exception $e) {
	$strAlert= '에러발생 : ' . $e->getMessage();
	$strLocation = '../bus/bus_selectForm.php';
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
		<h2>버스 출발 시간</h2>
		날짜 : <?=$dtDay?>
		루트 : <?=$strRoute?>
		<form name = 'bus_reservationOk' method = 'POST' action = '../bus/bus_reservationOk.php'>
			<select name = 'time'>
				<?php
				while($rgTime = mysqli_fetch_array($rstBus_time)){
					?>									
					<option value = '<?=$rgTime['bus_time']?>'>출발<?=$rgTime['bus_time']?>
					</option>
					<?
				}		
				?>
			</select>
			<input type = 'hidden' name = 'day' value = '<?=$dtDay?>'>
			<input type = 'hidden' name = 'route' value = '<?=$strRoute?>'>
			<p><input type = 'submit' value = '선택 완료'></p>
		</form>
	</body>
</html>