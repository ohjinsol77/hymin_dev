<?php
error_reporting( E_ALL );
ini_set( "display_errors", 1 );
session_start();

$CMasterdb = mysqli_connect('localhost', 'root', 'Itemmania1324%^', 'dev_test', '3306', '/var/run/mysqld/mysql_3306.sock');
$strUserid = $_SESSION['user_id'];
$rstInfo = $CMasterdb->query($_SESSION["info"]);

?>
<body>
    <div class="base">
        <h2><?php echo $strUserid."님 반갑습니다.";
			
				while( $rgRow = mysqli_fetch_array( $rstInfo ) ) {
				echo "<br> 번호 : " . $rgRow[ 'user_no' ] .
					 "<br> 생일 : " . $rgRow[ 'user_birth' ] .
					 "<br> 지역 : " . $rgRow[ 'user_city' ] . "<br>";
				}
			?>
		</h2>
        <button type="button" class="btn" onclick="location.href='logout.php'">
            LOGOUT
        </button>
    </div>
</body>