<?php
include("../_inc/header.php");
require("../adodb5/adodb.inc.php");
include_once('../adodb5/adodb-pager.inc.php');
ini_set('display_errors', true);
error_reporting(E_ALL);
try {
	$driver = 'mysqli';
    $db = newAdoConnection($driver);
    $db->debug = false;
    $db->socket = '/var/run/mysqld/mysql_3306.sock';
	///db 연결
    $db->connect('localhost', 'root', 'Itemmania1324%^', 'study');
} catch (Exception $e) {
    die($e->getMessage());   // 에러메세지 출력
}

$mileage_id = $_SESSION['member_Session_mileage'];
$member_type = "보유일경과 삭제";
$font_color = "RED";
?>
    <html>
    <body>
    <h2>삭제된 구매포인트</h2>

    <hr width="80%"/>
    <div>

        <?php
       $sql="select buypoint_deldate, buypoint_type, buypoint_price from buypoint where mileage_id=$mileage_id and buypoint_type=503 order by buypoint_delDate desc ";


        $pager = new ADODB_Pager($db, $sql);
        $pager->Render($rows_per_page = 15);
            ?>




    </div>
    <hr width="100%"/>

    </div>
    <button type='button' onclick="location.href='view_buyPoint.php'">뒤로가기</button>



    </body>
    </html>
<?php
include("../_inc/footer.php");
