<?php
error_reporting( E_ALL );
ini_set( "display_errors", 1 );
$number = $_GET['number'];
?>
<html>
	<body>
	<form action = "./modifyOk.php? number=<?php echo $number; ?>" method = "post">
			비밀번호 입력 : <input type = "password" name = "pw">
			<input type = "submit" value = "비밀번호 제출">
		</form>
	</body>
</html>
