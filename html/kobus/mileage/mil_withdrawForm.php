<?php
error_reporting( E_ALL );
ini_set( "display_errors", 1 );
include('../function/function.php');
session_start();
try {
	/* 세션 확인 */
	$strUserid = $_SESSION['userid'];
	if (!isset($_SESSION['userid'])) {
		throw new exception('로그인이 필요합니다.');
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
	<h2 align = 'center'>마일리지 출금 폼</h2>
	<body align = 'center'>
		<p>출금 방법을 고르신 후 금액을 입력하세요.</p>
		<p>출금 수수료는 1000원 입니다.</p>
		<p>최소 출금액은 1000원 입니다.</p>
		<form name = "mil_withdrawForm" method = "post" action = "../mileage/mil_withdrawOk.php">
			<li>출금 방법 :<Select name = 'withdraw_type'>
						   <option value = '0'> 출금 방법 선택하세요 </option>
						   <option value = '1'> 카드 </option>
						   <option value = '2'> 계좌이체 </option>
						   <option value = '3'> 휴대폰 </option>
						</Select>
			</li>
			<li>출금 금액 :<input type = 'number' min = '1000' name = 'mil_withdraw' placeholder="출금 금액 입력"/></li>
			</br>
			<li><input type = 'submit' value = '출금하기'/>
				<input type = 'button' value = '보유 마일리지 체크' onclick = "window.location= '../mileage/mil_check.php'"/>
				<input type = 'button' value = '홈으로 돌아가기' onclick = "window.location= '../userinfo/mainPage.php'">
			</li>
		</form>
	</body>
</html>