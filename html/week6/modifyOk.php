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
	$qryUpdate = "update board set title = \"$strTitle\", writer = \"$strWriter\", text = \"$strText\", modify = $dtModify 
				  where number = $nNumber;
				 ";

	$Cclassdb->fnStarttrans();

	$rstUpdate = mysqli_query($Conn, $qryUpdate);
	
	if(mysqli_affected_rows($Conn) < 1){
		throw new exception('수정 오류');
	}
	
	#read창으로 이동
	echo "<script>location.href = './read.php? number= " . $nNumber . "'</script>";
	
	$Cclassdb->fnCommit();
}catch(exception $e){
	$error= '에러발생 : ' . $e->getMessage();
	echo "<script>alert(\" $error \");</script>";
    echo ("<script>location.href='boardlist.php'</script>");

	if($Conn){
		if($bTrans_check == true){
			$Cclassdb->fnRollback();
			$Cclassdb->fnCommit();
		}
	}
	mysqli_close($Conn);
}
?>