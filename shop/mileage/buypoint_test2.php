<?php
include("../_inc/header.php");
require("../adodb5/adodb.inc.php");
include_once('../adodb5/adodb-pager.inc.php');

ini_set('display_errors', true);
error_reporting(E_ALL);
$price = 5700;
$buyPoint_result = 0;
$buypoint_regdateuy = array();
$buypoint_id = array();
$buypoint_price = array();
$count_array = 0;
try {
    $driver = 'mysqli';
    $db = newAdoConnection($driver);
    $db->debug = true;

    $db->connect('localhost', 'root', 'Kdkdldpadkdl123$%^', 'study');
} catch (Exception $e) {
    die($e->getMessage());   // 에러메세지 출력
}

?>
    <html>
    <body>
    <h2>회원 리스트</h2>
    <hr width="80%"/>
    <div>
        <?php
        $rs = $db->Execute("select buypoint_id, buypoint_amount, buypoint_regdate from test2 where buypoint_type=500 or 502 order by buypoint_regdate");

        while (!$rs->EOF) {
            $buypoint_id[] = $rs->fields[0];
            $buypoint_amount[] = $rs->fields[1];
            $buypoint_regdate[] = $rs->fields[2];
            $rs->MoveNext();
            $count_array++;


        }
        // var_dump($rs);


        $totalNum = 0;


        for ($i = 0; $i <= $count_array; $i++) {


            if (($buypoint_amount[$i] - $price) > 0) {
                $buyPoint_resutl = $buypoint_amount[$i] - $price;
                $price = 0;
                $rs = $db->Execute("update test2 set buypoint_amount=$buyPoint_result where buypoint_id=$buypoint_id[$i]");
                break;
            } else if ($buypoint_amount == 0) {
                continue;
            } else {
                $buyPoint_result = $price - $buypoint_amount[$i];
                $price = $buyPoint_result;
                $rs = $db->Execute("update test2 set buypoint_amount=0 where buypoint_id=$buypoint_id[$i]");

            }
            echo "id :" . $buypoint_id[$i];
            echo " price : " . $buypoint_amount[$i];
            echo " regdate :" . $buypoint_regdate[$i] . "<br/>";

        }
        ?>


    </div>
    </body>
    </html>
<?php
include("../_inc/footer.php");
