<?php
error_reporting( E_ALL );
ini_set( "display_errors", 1 );
include('function.php');
try {
	/* DB 연결 시작 */
	$Classdb = new database;
	$Conn = $Classdb->db;
	if (!$Conn) {
		throw new exception('데이터베이스 연결 실패');
	}
	/* 포스트값 확인 시작 */
	if (!isset($_POST['pw']) || empty($_POST['pw'])) {
		throw new exception('비밀번호 넘어오지 않음');
	}

	/* 변수 초기화 시작*/
	$strPost = $_POST['pw'];
	$nNumber = $_GET['number'];

	/* 포스트로 넘어온 비밀번호 체크 */
	$qryPw = "
		SELECT pw 
		  FROM board 
		 WHERE number = " . $nNumber . "
	";
	$rstPw = mysqli_query($Conn, $qryPw);		
	if (mysqli_num_rows($rstPw) < 1) {
		throw new exception('비밀번호 조회 오류.');
	}
	
	/* 수정 전 본문 조회 시작*/
	$qrySelect = "
		SELECT title, text ,writer 
		  FROM board 
		 WHERE number = " . $nNumber . "
	";
	$rstSelect = mysqli_query($Conn, $qrySelect);
	if (mysqli_num_rows($rstPw) < 1) {
		throw new exception('본문 조회 오류.');
	}
	
	/*본문에 있는 값 배열에 대입*/
	$rgBoard = mysqli_fetch_array($rstSelect);
	if (empty($rgBoard)) {
		throw new exception('본문 배열이 비었습니다.');
	}

	/*변수 초기화 시작*/
	$strTitle = $rgBoard['title'];
	$strTitle = $rgBoard['text'];
	$strTitle = $rgBoard['writer'];

}catch(exception $e){
	$strAlert= '에러발생 : ' . $e->getMessage();
	/* 에러발생 함수 */
	fnAlert($strAlert);
	if ($Conn) {
		mysqli_close($Conn);
		unset($Conn);
	}

	exit;
}
?>
<html>
	<title>게시판 수정</title>
	<body align = "center">
		<div id = "modify">
			<h1><a href = "/">익명게시판</a></h1>
			<h4> 글 수정 </h4>
			<div id = "write">
				<form action = "modifyOk.php? number=<?=$nNumber?>" method = "post">
					<div id = "name">
						<p>작성자 :<td><input type = "text" name = "writer" size = "15" value = "<?= $rgBoard['title'] ?>"readonly></td><p>
					</div>
					<div id = "title">
						<p>제목 :<td><textarea type = "text"   name = "title"	cols = "50" rows = "1"><?= $rgBoard['title'] ?></textarea></td></p>
					</div>
					<div id = "text">
						내용<p><td><textarea type = "text"   name = "text"	cols = "100" rows = "10"><?= $rgBoard['text'] ?></textarea></td></p>
					</div>
					<input type = "submit" value = "글 수정">
				</form>
			</div>
		</div>
	</body>
</html>