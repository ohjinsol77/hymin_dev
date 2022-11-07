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

	/* 포스트값 확인 */
	if (!isset($_POST['title']) & empty($_POST['title'])) {
		throw new exception('타이틀 값 없음');
	}

	if (!isset($_POST['writer']) & empty($_POST['writer'])) {
		throw new exception('작성자 값 없음');
	}
	
	if (!isset($_POST['text']) & empty($_POST['text'])) {
		throw new exception('텍스트 값 없음');
	}
	
	/* 변수 초기화 */
	$nNumber = $_GET['number'];
	$strTitle = $_POST['title'];
	$strWriter = $_POST['writer'];
	$strText = $_POST['text'];
	$bTrans_check = true;

	/* 게시판 내용 수정 업데이트 */
	$qryUpdate = "
		UPDATE board 
		   SET title = \"$strTitle\", writer = \"$strWriter\", text = \"$strText\", modify = now() 
		 WHERE number = $nNumber
	";
	#트랜잭션 시작
	$bTrans_check = $Classdb->fnStart_trans();
	if ($bTrans_check !== true) {
		throw new exception('트랜잭션 시작 오류');
	}

	$rstUpdate = mysqli_query($Conn, $qryUpdate);
	if (mysqli_affected_rows($Conn) < 1) {
		throw new exception('수정 오류');
	}
	
	/* 커밋 */
	$Classdb->fnCommit();		
	echo "<script>location.href = './read.php? number= " . $nNumber . "'</script>";
} catch(exception $e) {
	$strAlert= '에러발생 : ' . $e->getMessage();
	/* 에러발생 함수 */
	fnAlert($strAlert);
	if ($Conn) {
		if ($bTrans_check == true) {
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