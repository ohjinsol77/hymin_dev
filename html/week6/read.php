<?php
error_reporting( E_ALL );
ini_set( "display_errors", 1 );
include('function.php');
?>
<html>
<body>
<?php
try{
	$nNumber = $_GET['number'];
	$bTrans_check = true;
	$qryView = "
		select * 
		 from board 
		 where number = " . $nNumber . ";
	";
	$rstView = mysqli_query($Conn,$qryView);
	
	if(mysqli_num_rows($rstView) < 1){
		throw new exception('조회결과 없음');
	}

	$rgView = mysqli_fetch_array($rstView);


	$rgView = $rgView['view'] + 1;
	$qryUpdate = "
		update board 
		  set view = " . $rgView . " 
		  where number = " . $nNumber . ";
	";
	$bTrans_check = $Classdb->fnStart_trans();
	
	if($bTrans_check !== true){
		throw new exception('트랜잭션 시작 오류');
	}

	$rstUpdate = mysqli_query($Conn,$qryUpdate);

	if(mysqli_affected_rows($Conn) < 1){
		throw new exception('조회수 업데이트 결과 없음');
	}

	$qrySelectv = "
		select * 
		 from board 
		 where number = " . $nNumber . ";
	";
	$rstSelectv = mysqli_query($Conn,$qrySelectv);
	
	if(mysqli_num_rows($rstSelectv) < 1){
		throw new exception('목록 조회 결과 오류');
	}

	$rgBoard = $rstSelectv->fetch_array();
?>

<div id = "board_read">
	<h2><?php echo "제목 : ".$rgBoard['title']; ?></h2>
		<div id = "유저 정보">

			<?php echo "작성자 : ".$rgBoard['writer']."<br>".
					   "작성일 : ".$rgBoard['date']."<br>".
					   "조회수 : ".$rgBoard['view']."<br><br>";
			?>
			<div id = "board_line"></div>
		</div>
		<div id = "board content">
			<!--텍스트 내용 가져오기-->
			<?= nl2br("$rgBoard[text]"); ?>
		</div>
		<div id = "board modify adn delete">
			<ul>
				<input type = "button" value = "홈으로 돌아가기" 
					   onclick = "window.location='./boardlist.php'" >
				<form action = "./modifyPwcheck.php? number=<?php echo $nNumber; ?>" method = "post">
					<input type = "submit" value="수정하기">
				</form>
				<form action = "./deletePwcheck.php? number=<?php echo $nNumber; ?>" method = "post">
					<input type = "submit" value="삭제하기">
				</form>
			</ul>
		</div>
</div>
</body>
</html>
<?php
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
<!--
<input type = "hidden" name = "pw" value="<?=$rgBoard['pw']?>">
-->