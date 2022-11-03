<?php
error_reporting( E_ALL );
ini_set( "display_errors", 1 );
include('function.php');
try{
	if(!isset($_POST['writer'])){
		echo "<script>alert(\"익명 작성하세요.\");</script>";
		echo("<script>location.href='boardForm.php';</script>");
		exit;
	}

	$strTitle = $_POST["title"];
	$strText = $_POST["text"];			//내용
	$strWriter = $_POST["writer"];		//익명작성자
	$strPw = $_POST["pw"];
	$dtDateTime = 'now()';				//작성시간
	$bTrans_check = true;

	////foreach로 바꿀 예정 POST문
	if(empty($_POST['writer'])){
		echo "<script>alert(\"익명 작성하세요.\");</script>";
		echo("<script>location.href='boardForm.php';</script>");
		exit;
	}

	if(empty($_POST['pw'])){
		   echo "<script>alert(\"비밀번호 체크하세요.\");</script>";
		echo("<script>location.href='boardForm.php';</script>");
		exit;
	}

	if(empty($_POST['title'])){
		echo "<script>alert(\"제목 체크하세요.\");</script>";
		echo("<script>location.href='boardForm.php';</script>");
		exit;
	}

	if(empty($_POST['text'])){
		echo "<script>alert(\"내용 체크하세요.\");</script>";
		echo("<script>location.href='boardForm.php';</script>");
		exit;
	}


	/********테이블 정보***************
	CREATE TABLE `board` (
	`number` int unsigned NOT NULL AUTO_INCREMENT,
	`title` varchar(100) NOT NULL,
	`text` text NOT NULL,
	`date` datetime NOT NULL,
	`writer` varchar(50) NOT NULL,
    `pw` varchar(50) NOT NULL,
    `view` int DEFAULT '0',
    `modify` datetime DEFAULT NULL,
    PRIMARY KEY (`number`)
	) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci
	*****************/

	///쿼리문
	$qryInsert = "
				 Insert into board(title, text, date, writer, pw) values
				 (\"$strTitle\",\"$strText\",$dtDateTime,\"$strWriter\",\"$strPw\");
				 ";


	$rstInsert = mysqli_query($Conn,$qryInsert);
	if(mysqli_affected_rows($Conn) < 1){
		throw new exception('데이터 입력 오류');
	}

	///커밋


	echo "<script>alert(\"글 작성 완료\");</script>";
	echo("<script>location.href='./boardlist.php';</script>");




}catch(Exception $e){
	echo $e->getMessage()."<br>";

}
?>