<?php
error_reporting( E_ALL );
ini_set( "display_errors", 1 );
include('../function/function.php');
session_start();
try {
	$strUserid = $_SESSION['userid'];
	if (!isset($_SESSION['userid'])) {
		throw new exception('로그인이 필요합니다.');
	}
	
} catch(exception $e) {
	$strAlert= '에러발생 : ' . $e->getMessage();
	$strLocation = '../userinfo/mainPage.php';
	/* 에러발생 함수 */
	fnAlert($strAlert,$strLocation);
}
?>
<html>
	<h2>버스 조회 폼</h2>
	<body align = 'center'>
		<p>버스 조회 홈.</p>
		<p>예매 수수료는 5%입니다.</p>
		<form name = "bus_selectForm" method = "post" action = "../bus/bus_reservation.php">
			<li>충전 방법 :<Select name = 'route'>
						   <option value = '0'> 방향 선택하세요 </option>
						   <option value = '11'> 하행 서울 -> 전주 요금 = 15900원</option>
						   <option value = '22'> 상행 전주 -> 서울 요금 = 15900원</option>
						   <option value = '33'> 하행 인천 -> 전주 요금 = 15600원</option>
						   <option value = '44'> 상행 전주 -> 인천 요금 = 15600원</option>
						</Select>
			</li>
			<li>
				<input type = 'submit' value = '선택하기'>
				<input type = 'button' value = '홈으로 돌아가기' onclick = "window.location= '../userinfo/mainPage.php'">
			</li>
		</form>
	</body>
</html>