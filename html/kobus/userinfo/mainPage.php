<html>
	<head>
		<title>home</title>
	</head>
	<body>
		<h2>kobus</h2>
		<div id = 'header'>
		<ul>
			<li><a href = '../userinfo/mainPage.php' id = 'current'>home</a></li>
			<li><a href = '../mileage/mil_ChargeForm.php' id = 'current'>마일리지 충전</a></li>
			<li><a href = '../mileage/mil_withdrawForm.php' id = 'current'>마일리지 출금</a></li>
			<li><a href = '../mileage/mil_check.php' id = 'current'>내 정보</a></li>
			<li><a href = '../bus/bus_select.php' id = 'current'>버스 예매</a></li>
			
		</ul>
		<ul>
			<?php
			session_Start();		
			if (isset($_SESSION['userid'])) {
				?>
				<li><a href = '../userinfo/logout.php' id = 'current'>로그아웃</a></li>
				<li><a href = '../userinfo/memoutForm.php' id = 'current'>회원 탈퇴</a></li>
				<?php
			} else {
				?>
				<li><a href = '../userinfo/loginForm.php' id = 'current'>로그인</a></li>
				<li><a href = '../userinfo/joinForm.php' id = 'current'>회원가입</a></li>
				<?php
			}
			?>
		</ul>
	</body>
</html>