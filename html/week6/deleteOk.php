<?php
error_reporting( E_ALL );
ini_set( "display_errors", 1 );
include('function.php');
try{

	if(empty($_POST['pw'])){
		throw new exception('비밀번호 넘어오지 않음');
	}

	#비밀번호 오류 확인 쿼리 시작
	$strPost = $_POST['pw'];
	$nNumber = $_GET['number'];
	$bTrans_check = true;
	$qryPw = "select pw from board 
			  where number = " . $nNumber . ";
			 ";
	$rstPw = mysqli_query($Conn, $qryPw);		//쿼리 실행
	$rgPw = $rstPw->fetch_array();				//비밀번호 조회

	//비밀번호 오류시 예외처리
	if($strPost !== $rgPw["pw"]){
		throw new exception('비밀번호 오류111');
	}
	
	
	$bTrans_check = $Cclassdb->fnStarttrans();
	$qryDelete = "delete from board
				  where number = " . $nNumber . ";
				 ";
	$rstDelete = mysqli_query($Conn,$qryDelete);

	if(mysqli_affected_rows($Conn) < 1){
		throw new exception('삭제 오류');
	}



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