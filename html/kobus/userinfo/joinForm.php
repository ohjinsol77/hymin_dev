<html>
	<body align = 'center'>
		<h1>회원가입 폼</h1>
		<form name = 'joinFrom' method = 'post' action = 'joinOk.php'>
			<li>아이디 : <input type = 'text' name = 'userid' autofocus/></li>
			<li>비밀번호 : <input type = 'password' name = 'userpw'></li>
			<li>이름 : <input type = 'text' name = 'username'></li>
			<li>전화번호 : <input type = 'number' name = 'mobile'></li>
			<li>생년월일 : <input type = 'text' name = 'birthday'></li>
			<li>나이 : <input type = 'text' name = 'age'></li>
		

			<li><input type = 'submit' value = '가입하기'>
				<input type = 'reset' value = '다시 작성하기'>
				<input type = 'button' value = '홈으로 돌아가기' onclick = "window.location= '../userinfo/mainPage.php'">
			</li>

		</form>
	</body>
</html>
