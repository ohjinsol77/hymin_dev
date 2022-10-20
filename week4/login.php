<?php
error_reporting( E_ALL );
ini_set( "display_errors", 1 );


$CMasterdb = mysqli_connect('localhost', 'root', 'Itemmania1324%^', 'dev_test', '3306', '/var/run/mysqld/mysql_3306.sock');
try{
	$strUserid = $_POST['id'];
	$strUserpasswd = $_POST['pw'];
	//empty는 전혀 없는지 확인
	if(empty($_POST['id'])){
		throw new Exception('아이디를 입력해주세요.');
	}
	else if(empty($_POST['pw'])){
		throw new Exception('비밀번호를 입력해주세요.');
	}
}catch(Exception $e){
	echo $e->getMessage();

}
//아이디 검사
$qry = "select * from dev_user_info where user_id = '" . $strUserid . "'";
$qryInfo = "select user_no, user_birth, user_city from dev_user_info where user_id= '" . $strUserid . "'";



$rst = $CMasterdb->query($qry);

//아이디 있으면 비밀번호 검사
if(mysqli_num_rows($rst)==1){
	$rgRow=mysqli_fetch_assoc($rst);

	//비밀번호 맞으면 세션 생성
	if($rgRow['user_passwd']==$strUserpasswd){
		session_start();
		$_SESSION['user_id'] = $strUserid;
		$_SESSION['info']=$qryInfo;
		if(isset($_SESSION['user_id'])){
			//아이디와 비밀번호가 일치하면 index페이지로 이동
			echo "<script>location.replace('index.php');</script>";
		}
	}else{
		//비밀번호 오류일 때
		echo "비밀번호 error"; 
}
//아이디가 오류일 때
}else{
	echo "로그인 정보 오류";
}
?>