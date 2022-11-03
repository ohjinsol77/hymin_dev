<?php
error_reporting( E_ALL );
ini_set( "display_errors", 1 );
include('dbConn.php');
?>
<html>
<body>
<?php
try{
	$nNumber = $_GET['number'];
	$qryView = "select * from board 
				where number = " . $nNumber . ";
			   ";
	$rstView = mysqli_query($CMaster,$qryView);
	$rgView = mysqli_fetch_array($rstView);

	if(mysqli_num_rows($rstView) < 1){
		throw new exception('조회결과 없음');
	}

	$rgView = $rgView['view'] + 1;
	$qryUpdate = "update board set view = " . $rgView . 
				 " where number = " . $nNumber . ";
				 ";
	$rstUpdate = mysqli_query($CMaster,$qryUpdate);
	if(mysqli_affected_rows($CMaster) < 1){
		throw new exception('조회수 업데이트 결과 없음');
	}

	$qrySelectv = "select * from board 
				   where number = " . $nNumber . ";
				  ";

	$rstSelectv = mysqli_query($CMaster,$qrySelectv);
	$rgBoard = $rstSelectv->fetch_array();

?>

<div id = "board_read">
	<h2><?php echo "제목 : ".$rgBoard['title']; ?></h2>
		<div id = "유저 정보">
		
			<?php echo "작성자 : ".$rgBoard['writer']."<br>".
					   "작성일 : ".$rgBoard['date']."<br>".
					   "조회수 : ".$rgBoard['view']."<br><br>"; ?>
		
			<div id = "bo_line"></div>
		</div>
		<div id = "bo content">
			<!--텍스트 내용 가져오기-->
			<?= nl2br("$rgBoard[text]"); ?>
		</div>
		<div id = "bo delete">
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
	echo $e->getMessage();
}	

?>
<!--
<input type = "hidden" name = "pw" value="<?=$rgBoard['pw']?>">
-->