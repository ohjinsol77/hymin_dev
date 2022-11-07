<html>
	<body>
		<h3>글 작성 폼</h3>
			<form action = 'boardOk.php' method = 'post'>
				<td><p align="left">작성자 : </p></td><td><input type = "text"		name = "writer" size = "15" autofocus></td></br></li>
				<td><p align="left">pw : </p></td><td><input type = "password"  name = "pw"		size = "15"></td></br></li>
				<td><p align="left">제목 : </p></td><td><input type = "text"		name = "text"	size = "30"></td> </br></li>
				<td><p align="left">내용 : </p></td><td><textarea type = "text"   name = "title"	cols = "100" rows = "10"></textarea></td></br>
				<input type="submit" value="글 올리기" />
				<input type="button" value ="홈으로 돌아가기" onclick = "window.location='./boardlist.php'">
			</form>
	</body>
</html>