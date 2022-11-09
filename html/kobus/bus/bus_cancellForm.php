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
	$qrySelect = "
		SELECT mil_use, mil_chargeday, mil_charge
		FROM mileage
		WHERE userid = " . $strUserid . " AND mil_type = 11 OR mil_type = 22 OR mil_type = 33 OR mil_type = 44 
	";


	$rstSelect = mysqli_query($Conn,$qrySelect);
	if(!$rstSelect){
		throw new exception('쿼리 오류');
	}

	if(mysqli_num_rows($rstSelect) < 1) {
		throw new exception('조회 오류');
	}

} catch(exception $e) {
	$strAlert= '에러발생 : ' . $e->getMessage();
	$strLocation = '../bus/bus_cancellForm.php';
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
			<?php
			while($rgSelect = mysqli_fetch_array($rstSelect)){
				?>
				<th><tr>
				<input type = 'hidden' name = 'mil_use' value = '<?=$rgSelect['mil_use']?>' >
				<input type = 'hidden' name = 'mil_chargeday' value = '<?=$rgSelect['mil_chargeday']?>' >
				<input type = 'hidden' name = 'mil_charge' value = '<?=$rgSelect['mil_charge']?>' >
				<input type = 'submit' value = '1' />
				</th></tr>
				
				<?php
			}
					
			?>

	
		</form>
		<li>
			<input type = 'button' value = '홈으로 돌아가기' onclick = "window.location= '../userinfo/mainPage.php'">
		</li>
	</body>
</html>