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
	$bTrans_check = true;

	$qryPw = "
		select pw from board 
		 where number = " . $nNumber . ";
	";

	$rstPw = mysqli_query($Conn, $qryPw);		//쿼리 실행
	
	if(mysqli_num_rows($rstPw) < 1){
		throw new exception('비밀번호 조회 오류');
	}

	$rgPw = mysqli_fetch_array($rstPw);				//비밀번호 조회

	//비밀번호 오류시 예외처리
	if($strPost !== $rgPw["pw"]){
		throw new exception('비밀번호 오류');
	}
	
	$bTrans_check = $Classdb->fnStart_trans();

	if($bTrans_check !== true){
		throw new exception('트랜잭션 시작 오류');
	}

	$qrySelect = "
		select title, text, date, writer, pw, modify from board where number = " . $nNumber . ";
	";

	$rstSelect = mysqli_query($Conn,$qrySelect);

	if(mysqli_num_rows($rstSelect) < 1){
		throw new exception('delboard 테이블에 입력할 데이터 조회 오류');
	}

	$rgSelect = mysqli_fetch_assoc($rstSelect);
	$strTitle = $rgSelect['title'];
	$strText = $rgSelect['text'];
	$dtDate = $rgSelect['date'];
	$strWriter = $rgSelect['writer'];
	$strPw = $rgSelect['pw'];
	$dtModify = $rgSelect['modify'];
	$dtDeldate = 'now()';

	$qryInsert = "
		insert into delboard(title, text ,date ,writer, pw, modify, deldate) values
		 (\"$strTitle\",\"$strText\",\"$dtDate\",\"$strWriter\",\"$strPw\",\"$dtModify\",$dtDeldate);
	";

	$rstInsert = mysqli_query($Conn,$qryInsert);
	if(mysqli_affected_rows($Conn) < 1){
		throw new exception('삭제한 데이터 입력 오류');
	}

	$qryDelete = "
		delete from board
		 where number = " . $nNumber . ";
	";

	$rstDelete = mysqli_query($Conn,$qryDelete);

	if(mysqli_affected_rows($Conn) < 1){
		throw new exception('삭제 오류');
	}

	$Classdb->fnCommit();
	echo "<script>alert(\" 삭제되었습니다. \");</script>";
	echo ("<script>location.href='boardlist.php'</script>");
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