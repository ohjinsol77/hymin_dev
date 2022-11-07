<?php
error_reporting( E_ALL );
ini_set( "display_errors", 1 );
include('function.php');
try{
	if(!isset($_POST['pw']) || empty($_POST['pw'])){
		throw new exception('비밀번호 넘어오지 않음');
	}

	#비밀번호 오류 확인 쿼리 시작
	$strPost = $_POST['pw'];
	$nNumber = $_GET['number'];
	$qryPw = "
		select pw from board 
		 where number = " . $nNumber . ";
	";
	$rstPw = mysqli_query($Conn, $qryPw);		//쿼리 실행
	
	if(mysqli_num_rows($rstPw) < 1){
		throw new exception('비밀번호 조회 오류.');
	}

	$rgPw = mysqli_fetch_array($rstPw);					//비밀번호 조회

	#비밀번호 오류시 예외처리
	if($strPost !== $rgPw["pw"]){
		throw new exception('비밀번호 오류111');
	}

	#수정하기 전 본문 불러오기
	$qrySelect = "
		select * from board 
		 where number = " . $nNumber . ";
	";

	$rstSelect = mysqli_query($Conn, $qrySelect);
	
	if(mysqli_num_rows($rstPw) < 1){
		throw new exception('본문 조회 오류.');
	}

	$rgBoard = mysqli_fetch_array($rstSelect);
	$strTitle = $rgBoard['title'];
	$strTitle = $rgBoard['text'];
	$strTitle = $rgBoard['writer'];
?>

<html>
	<title>게시판 수정</title>
	<body>
		<div id = "modify">
			<h1><a href = "/">익명게시판</a></h1>
			<h4> 글 수정 </h4>
			<div id = "write">
				<form action = "modifyOk.php? number=<?php echo $nNumber;?>" method = "post">
					<div id = "title">
						<textarea type = "text" name = "title" placeholder="제목">
						<?= $rgBoard['title']; ?> </textarea>
					</div>
					<div id = "name">
						<textarea type = "text" name = "writer" placeholder="작성자">
						<?= $rgBoard['writer']; ?> </textarea>
					</div>
					<div id = "text">
						 <textarea type="text" name="text" placeholder="내용" >
						 <?php echo $rgBoard['text']; ?></textarea>
					</div>
					<input type = "submit" value = "글 수정">
				</form>
			</div>
		</div>
	</body>
</html>

<?php
}catch(exception $e){
	$error= '에러발생 : ' . $e->getMessage();
	echo "<script>alert(\" $error \");</script>";
    echo ("<script>location.href='boardlist.php'</script>");

	if($Conn){
		mysqli_close($Conn);
		unset($Conn);
	}

	exit;
}
?>