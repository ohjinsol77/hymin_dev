<?php
error_reporting( E_ALL );
ini_set( "display_errors", 1 );
include('function.php');
try{
	/* DB 연결 시작 */
	$Classdb = new database;
	$Conn = $Classdb->db;
	if (!$Conn) {
		throw new exception('데이터베이스 연결 실패');
	}

	/* post값 확인 시작 */
	if (!isset($_POST['pw']) || empty($_POST['pw'])) {
		throw new exception('비밀번호 넘어오지 않음');
	}
	/* 변수 초기화 시작 */
	$strPost = $_POST['pw'];
	$nNumber = $_GET['number'];
	$bTrans_check = true;

	/* 비밀번호 조회 시작 */
	$qryPw = "
		SELECT pw 
		  FROM board 
		 WHERE number = " . $nNumber . "
	";
	$rstPw = mysqli_query($Conn, $qryPw);		
	if (mysqli_num_rows($rstPw) < 1) {
		throw new exception('비밀번호 조회 오류');
	}	

	/* 비밀번호 체크 시작 */
	$rgPw = mysqli_fetch_array($rstPw);				
	if ($strPost !== $rgPw["pw"]) {
		throw new exception('비밀번호 오류');
	}

	/* 트랜잭션 체크 시작 */
	$bTrans_check = $Classdb->fnStart_trans();
	if ($bTrans_check !== true) {
		throw new exception('트랜잭션 시작 오류');
	}

	/* 삭제 테이블에 입력할 정보 조회 시작*/
	$qrySelect = "
		SELECT title, text, date, writer, pw, modify 
		  FROM board 
		 WHERE number = " . $nNumber . "
	";
	$rstSelect = mysqli_query($Conn,$qrySelect);
	if (mysqli_num_rows($rstSelect) < 1) {
		throw new exception('delboard 테이블에 입력할 데이터 조회 오류');
	}

	/* 변수 초기화 시작 */
	$rgSelect = mysqli_fetch_assoc($rstSelect);
	$strTitle = $rgSelect['title'];
	$strText = $rgSelect['text'];
	$dtDate = $rgSelect['date'];
	$strWriter = $rgSelect['writer'];
	$strPw = $rgSelect['pw'];
	$dtModify = $rgSelect['modify'];
	
	/* 삭제 테이블에 데이터 입력 시작*/
	$qryInsert = "
		INSERT INTO delboard(title, text ,date ,writer, pw, modify, deldate) 
		 VALUES (\"$strTitle\",\"$strText\",\"$dtDate\",\"$strWriter\",\"$strPw\",\"$dtModify\",now())
	";
	$rstInsert = mysqli_query($Conn,$qryInsert);
	if (mysqli_affected_rows($Conn) < 1) {
		throw new exception('삭제한 데이터 입력 오류');
	}

	/* 삭제한 데이터 게시판 테이블에서 삭제 시작*/
	$qryDelete = "
		DELETE FROM board
		 WHERE number = " . $nNumber . ";
	";
	$rstDelete = mysqli_query($Conn,$qryDelete);
	if (mysqli_affected_rows($Conn) < 1) {
		throw new exception('삭제 오류');
	}
	
	/* 커밋 후 리스트로 돌아가기*/
	$Classdb->fnCommit();
	$strAlert = '삭제되었습니다.';
	fnAlert($strAlert);
	unset($strAlert);
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