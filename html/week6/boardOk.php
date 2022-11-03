<?php
error_reporting( E_ALL );
ini_set( "display_errors", 1 );
include('function.php');
try{
	#post 확인
	foreach($_POST as $key=>$value){
		if(!isset($_POST[$key]) || empty($_POST[$key])){
			echo "<script>alert(\"빈칸이거나 포스트값이 없습니다..\");</script>";
			echo("<script>location.href='boardForm.php';</script>");
			exit;
		}	
	}
	
	$strTitle = $_POST["title"];
	$strText = $_POST["text"];			#내용
	$strWriter = $_POST["writer"];		#익명작성자
	$strPw = $_POST["pw"];				#비밀번호
	$dtDateTime = 'now()';				#작성시간
	$bTrans_check = true;				#트랜잭션 체커

	/******************************************테이블 정보******************************************
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
	******************************************************************************************/

	#쿼리문
	$qryInsert = "
		Insert into board(title, text, date, writer, pw) values
		 (\"$strTitle\",\"$strText\",$dtDateTime,\"$strWriter\",\"$strPw\");
	";
	$bTrans_check = $Classdb->fnStart_trans();
	$rstInsert = mysqli_query($Conn,$qryInsert);

	if(mysqli_affected_rows($Conn) < 1){
		throw new exception('데이터 입력 오류');
	}

	$Classdb->fnCommit();
	echo "<script>alert(\"글 작성 완료\");</script>";
	echo("<script>location.href='./boardlist.php';</script>");

}catch(exception $e){
	$error= '에러발생 : ' . $e->getMessage();
	echo "<script>alert(\" $error \");</script>";
    echo ("<script>location.href='boardlist.php'</script>");

	if($Conn){
		if($bTrans_check == true){
			$Classdb->fnRollback();
			$Classdb->fnCommit();
			unset($bTrans_check);
		}
		mysqli_close($Conn);
		unset($Conn);
	}
	exit;
}
?>