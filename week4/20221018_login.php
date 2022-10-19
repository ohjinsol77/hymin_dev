<?php


error_reporting( E_ALL );
ini_set( "display_errors", 1 );


$CMasterdbMaster = mysqli_connect('localhost', 'root', 'Itemmania1324%^', 'dev_test', '3306', '/var/run/mysqld/mysql_3306.sock');

//userinfo에서 모든 열을 10정보를 가져온다
$qryUserinfo = "select * from dev_user_info limit 10;";
//accountbook에서 모든 열에서 money 값이 >50000인 정보를 가져온다.
$qryAccount = "select * from dev_account_book where money >50000 limit 5;";

$qryAssoc = "select * from dev_account book where quantity>7 limit 5;";


//연결된 객체를 이용하여 쿼리를 실행
$rstUserinfo = mysqli_query( $CMasterdbMaster, $qryUserinfo );
$rstAccount = mysqli_query($CMasterdbMaster, $qryAccount);
$rstAssoc = mysqli_query($CMasterdbMaster, $qryAssoc);

//일반 배열 row 사용해서 1,2,3열 출력
while( $rgRow = mysqli_fetch_row( $rstAccount ) ) {
  echo '<p>'
    . $rgRow[ 1 ]." "
    . $rgRow[ 2 ]." "
	. $rgRow[ 3 ].
	
	'</p>';
}

echo '유저 정보 <br>';
//row = 일반배열
//assoc = 연관배열
//array = 일반 배열 + 연관배열
while( $rgRow = mysqli_fetch_array( $rstUserinfo ) ) {
  echo 
    $rgRow[ 'user_no' ]." "
    . $rgRow[ 'user_id' ]." "
    . $rgRow[ 'user_birth' ]." "
    . $rgRow[ 'user_gender' ]." "
    . $rgRow[ 'user_city' ]." "
	. $rgRow[ 'user_reg_date' ].
	"<br>";
}

// sql to create table
$qryCreate = "CREATE TABLE hm (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
email VARCHAR(50),
age = int(3) NOT NULL;
)";

$qryCreate = "INSERT INTO hm (id, email, age)
			 VALUES ('1','1@naver.com','15')";
var_dump($qryCreate);




?>

--------------------------------------------------------------------sp

<?php

error_reporting( E_ALL );
ini_set( "display_errors", 1 );


session_start();
require_once('config.php');
require_once('functions.php');

ensure_user_is_authenticated();

echo $_SESSION['email'];
//<a>태그의 href의 속성은 링크된 페이지의 URL을 명시
?>
<a href="logout.php">로그아웃</a>
<?php

?>

------------------------------------------------------admin

<?php
const USER_NAME = $user_id;
const PASSWORD = $user_passwd;
?>

------------------------------------------------------config

<?php

function output($value){
	echo '<pre>';
	print_r($value);
	echo '<pre>';
}

function authenticate_user($email, $password){
	if($email = USER_NAME && $password ==PASSWORD){
		return true;
	}
}

function redirect($url){
	header("Location:$url");
}
function is_user_authenticated(){
	return isset($_SESSION['email']);
}

function ensure_user_is_authenticated(){
	if(!is_user_authenticated()){
			redirect('login.php');
		die();
	}
}
?>

-------------------------------------------------------functions

<?php


session_start();
$CMasterdbMaster = mysqli_connect('localhost', 'root', 'Itemmania1324%^', 'dev_test', '3306', '/var/run/mysqld/mysql_3306.sock');

//
include ('config.php');
require_once('functions.php');
if(is_user_authenticated()){
	
	redirect('admin.php');
	die();
}
//@초 후에 admin페이지로 이동
sleep(0.5);



if(isset($_POST['login'])){
	$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
	$password = $_POST['password'];

	if($email == false){
		$status = '이메일 형식 맞게 입력';
	}
	if(authenticate_user($email, $password)){
		$_SESSION['email'] = $email;
		redirect('admin.php');
	}else{
		$status = '비번이 맞지 않습니다';
	}

}

?>

<form action = "" method="POST">
<p>
<label for="email">Email:</label>
<input type="text" name="email" id="email">
</p>
<p>
<label for="password">Password:</label>
<input type="password" name="password" id="password">
</p>
<p>
<input type="submit" name="login" value="Login">
</p>
</form>
<div calss="error">
<p>

<?php
if(isset($status)){
	echo $status;
}
?>
</p>
</div>

---------------------------------------------------------------login

<?php

error_reporting( E_ALL );
ini_set( "display_errors", 1 );

session_start();
session_unset();
session_destroy();

require_once('functions.php');
redirect('login.php');
die();

?>

--------------------------------------------------------------logout