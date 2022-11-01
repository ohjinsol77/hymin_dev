<?php
error_reporting( E_ALL );
ini_set( "display_errors", 1 );
include('dbConn.php');
try{
	if(empty($_POST['pw'])){
		throw new exception('포스트값 존재하지 않음');
	}
	#비밀번호 오류 확인 쿼리 시작
	$strPost = $_POST['pw'];
	var_dump($strPost);
	$number = $_GET['number'];
	var_dump($number);
	$qryPw = "select pw from board where number = " . $number . ";";
	$rstPw = mysqli_query($CMaster, $qryPw);
	$rgPw = $rstPw->fetch_array();					//비밀번호 조회
	#비밀번호 오류 확인 쿼리문 끝
	$nCount =mysqli_num_rows($rstPw);

	if($strPost !== $rgPw["pw"]){
		throw new exception('비밀번호 오류');
	}

	

	#수정 항목 입력 쿼리
	$qrySelect = "select * from board where number = " . $number . ";";
	$rstSelect = mysqli_query($CMaster, $qrySelect);
	$rgBoard = $rstSelect->fetch_array();
	$strTitle = $rgBoard['title'];
	$strTitle = $rgBoard['text'];
	$strDatetime = 'now()';
	$strTitle = $rgBoard['writer'];
	$strPw = $rgBoard['pw'];


	#수정 일시랑 적는 쿼리문 필요 2022_1101
	#$qryInsert = "~"

	#수정 항목 입력  -->update문으로 다시 바꿔야함 2022_1101
	$qryUpdate = "update modtalbe(title, text, date ,writer) values
				  (\"$strTitle\",\"$strTitle\",$strDatetime,\"$strTitle\");
				 ";
	//mysqli_query($CMaster, $qryUpdate);






	
?>
<html>
<title>게시판 수정</title>
<body>
	<div id = "modify">
		<h1><a href = "/">익명게시판</a></h1>
		<h4> 글 수정 </h4>
		<div id = "write">
			<form action = "modifyForm.php?number=<?php echo $number;?>" method = "post">
				<div id = "in_title">
					<textarea type = "text" name = "title" placeholder="제목">
					<?= $rgBoard['title']; ?> </textarea>
				</div>
				<div id = "in_name">
					<textarea type = "text" name = "writer" placeholder="작성자">
					<?= $rgBoard['writer']; ?> </textarea>
				</div>
				<div>
					 <textarea type="text" name="text" placeholder="내용" >
					 <?php echo $rgBoard['text']; ?></textarea>
				</div>
				<div>
					 <textarea type="password" name="pw" placeholder="비밀번호를 입력하세요." ></textarea>
				</div>
			</form>
<input type = "submit" value = "글 수정">
</body>
</html>
<?php
}catch(exception $e){
    $error= '에러발생 : ' . $e->getMessage();
	echo "<script>alert(\" $error \");</script>";
    echo("<script>location.href='/modifyForm.php;</script>");
	if($CMaster){
		mysqli_Close($CMaster);
	}
	exit;
}

?>
