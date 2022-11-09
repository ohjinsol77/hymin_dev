<html>
	<body align = 'center'>
		<h1>로그인 폼</h1>
		<form name = 'loginFrom' method = 'post' action = 'loginOk.php'/>
			<p><li>아이디 : <input type = 'text' name = 'userid' autofocus/></li></p>
			<p><li>비밀번호 : <input type = 'password' name = 'userpw'/></li></p>
			<p><li><input type = 'submit' value = '로그인'/>
				<input type = 'reset' value = '다시 쓰기'/>
				<input type = 'button' value = '회원가입' onclick = "window.location = 'joinForm.php'"/>
				<input type = 'button' value = '홈으로 돌아가기' onclick = "window.location= '../userinfo/mainPage.php'"/>
			</li></p>
		</form>
	</body>
</html>
