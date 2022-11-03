<?php
error_reporting( E_ALL );
ini_set( "display_errors", 1 );
include('function.php');
try{
	##POST값 확인
	if(isset($_POST['title'])){
		throw new exception('타이틀 값 없음');
	}

	if(empty($_POST['writer'])){
		throw new exception('작성자 값 없음');
	}
	
	if(empty($_POST['text'])){
		throw new exception('텍스트 값 없음');
	}
	#포스트값 확인 끝

	$nNumber = $_GET['number'];
	$strTitle = $_POST['title'];
	$strWriter = $_POST['writer'];
	$strText = $_POST['text'];
	$dtModify = "now()";
	$bTrans_check = true;
	$qryUpdate = "
				 update board set title = \"$strTitle\", writer = \"$strWriter\", text = \"$strText\", modify = $dtModify 
				 where number = $nNumber;
				 ";
	$bTrans_check = $Classdb->fnStarttrans();
	if($bTrans_check !== true){
		throw new exception('트랜잭션 시작 오류');

	$rstUpdate = mysqli_query($Conn, $qryUpdate);
	if(mysqli_affected_rows($rstUpdate) < 1){
		throw new exception('수정 오류');
	}

	$Classdb->fnCommit();		
	echo "<script>location.href = './read.php? number= " . $nNumber . "'</script>";
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