<?php
error_reporting( E_ALL );
ini_set( "display_errors", 1 );
include('dbConn.php');
try{
	$strTitle = $_POST["title"];
	$strText = $_POST["text"];			//내용
	$strWriter = $_POST["writer"];		//익명작성자
	$strPw = $_POST["pw"];
	var_dump($strWriter);
	var_dump($strPw);
	$dtDateTime = 'now()';				//작성시간
	$bSet = true;
	$bTrs = true;

	////foreach로 바꿀 예정 POST문
	if(empty($_POST['title'])){
		throw new exception('title 없음');
	}

	if(empty($_POST['text'])){
		throw new exception('text 없음');
	}
	
	//자동 커밋을 0으로 만들어주고
	$bSet = mysqli_query($CMaster, 'set autocommit=0');
	//트랜잭션 시작
	$bTrs = mysqli_query($CMaster,'start transaction');
	if($bSet!==true || $bTrs!==true){
		throw new exception('트랜잭션 실패');
	}


	///쿼리문
	$qryInsert = "
				 Insert into board(title, text, date, writer, pw) values
				 (\"$strTitle\",\"$strText\",$dtDateTime,\"$strWriter\",\"$strPw\");
				 ";

	//쿼리문 실행
	$rstInsert = mysqli_query($CMaster,$qryInsert);
	//반영된 값 없으면 예외처리
	if(mysqli_affected_rows($CMaster) < 1){
		throw new exception('오류111');
	}

	///커밋
	$rstCommit = mysqli_query($CMaster,'COMMIT');
	echo "<script>alert(\"글 작성 완료\");</script>";
	echo("<script>location.href='./boardlist.php';</script>");




}catch(Exception $e){
	echo $e->getMessage()."<br>";

}
?>