<?php
error_reporting( E_ALL );
ini_set( "display_errors", 1 );
include('../function/function.php');
session_start();
try {
	if(!isset($_SESSION['userid'])) {
		throw new exception('세션값 오류');
	}

} catch(exception $e) {
	$strAlert= '에러발생 : ' . $e->getMessage();
	$strLocation = 'loginForm.php';
	/* 에러발생 함수 */
	fnAlert($strAlert,$strLocation);
}
?>
<html>
	<body align = 'center'>
		<h1>로그인 폼</h1>
		<form name = 'memoutForm' method = 'post' action = 'memoutOk.php'>
			<li>비밀번호 : <input type = 'password' name = 'userpw'autofocus/></li>
			<li>전화번호 : <input type = 'number' name = 'mobile'/></li>
			<li>생년월일 : <input type = 'number' name = 'birthday'/></li>
			<p>
				<li>
					<input type = 'submit' value = '회원탈퇴'/>
					<input type = 'reset' value = '다시 쓰기'/>
					<input type = 'button' value = '홈으로 돌아가기' onclick = "window.location= '../userinfo/mainPage.php'">
				</li>
			</p>
		</form>
	</body>
</html>
