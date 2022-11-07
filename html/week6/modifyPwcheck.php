<?php
error_reporting( E_ALL );
ini_set( "display_errors", 1 );
include('function.php');
try {
	/* 겟값 확인*/
	if (!isset($_GET['number'])) {
	throw new exception('GET값이 존재하지 않습니다');
	}

$number = $_GET['number'];

} catch(exception $e) {
	$strAlert= '에러발생 : ' . $e->getMessage();
	/* 에러발생 함수 */
	fnAlert($strAlert);
	exit;
}
?>
<html>
	<body>
		<form action = "./modifyForm.php? number=<?=$number?>" method = "post">
			비밀번호 입력 : <input type = "password" name = "pw">
			<input type = "submit" value = "비밀번호 제출">
		</form>
	</body>
</html>