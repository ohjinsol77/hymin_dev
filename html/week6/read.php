<?php
error_reporting( E_ALL );
ini_set( "display_errors", 1 );
include('dbConn.php');
?>
<html>
<body>
<?php
	$number = $_GET['number'];
	$qrySelect = "select * from board where number = " .$number. ";";
	$rstView = mysqli_fetch_array(mysqli_query($CMaster,$qrySelect));
	$rstView = $rstView['view'] + 1;
	$qryUpdate = "update board set view = " . $rstView . " where number = " . $number . ";";
	$rstUpdate = mysqli_query($CMaster,$qryUpdate);
	$qrySelectv = "select * from board where number = " . $number . ";";
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
				<form action = "./modifyForm.php? number=<?php echo $rgBoard['number']; ?>" method = "post">
					<input type = "submit" value="수정하기">
				</form>
				<form action = "./delete.php? number=<?php echo $rgBoard['number']; ?>" method = "post">
					<input type = "hidden" name = "pw" value="<?=$rgBoard['pw']?>">
					<input type = "submit" value="삭제하기">
				</form>
			</ul>

			
		</div>
</div>
</body>
</html>