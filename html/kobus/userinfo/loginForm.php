<html>
	<body align = 'center'>
		<h1>로그인 폼</h1>
		<form name = 'loginFrom' method = 'post' action = 'loginOk.php'>
			<li>아이디 : <input type = 'text' name = 'id' autofocus/></li>
			<li>비밀번호 : <input type = 'password' name = 'pw'></li>
			<li><input type = 'submit' value = '로그인'>
				<input type = 'reset' value = '다시 쓰기'></li>
			<li><input type = 'button' value = '회원가입' onclick = "window.location = 'joinForm.php'"></li>
		</form>
	</body>
</html>
