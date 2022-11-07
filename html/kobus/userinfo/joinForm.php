<html>
	<body align = 'center'>
		<h1>회원가입 폼</h1>
		<form name = 'joinFrom' method = 'post' action = 'joinOk.php'>
			<li>아이디 : <input type = 'text' name = 'id' autofocus/></li>
			<li>비밀번호 : <input type = 'password' name = 'pw'></li>
			<li>이름 : <input type = 'text' name = 'name'></li>
			<li>전화번호 : <input type = 'number' name = 'mobile'></li>
			<li>생년월일 : <input type = 'text' name = 'birthday'></li>
			<li>남<input type = 'radio' name = 'gender' value = '1'>
				여<input type = 'radio' name = 'gender' value = '2'></li>

			<li><input type = 'submit' value = '가입하기'>
				<input type = 'reset' value = '다시 작성하기'></li>
		</form>
	</body>
</html>
