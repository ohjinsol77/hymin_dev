<?php
include("../_inc/header.php");
require("../adodb5/adodb.inc.php");


$sel_id = $_POST['sel_id'];
$step = $_POST['step'];
try {
	$driver = 'mysqli';
    $db = newAdoConnection($driver);
    $db->debug = false;
    $db->socket = '/var/run/mysqld/mysql_3306.sock';
	///db 연결
    $db->connect('localhost', 'root', 'Itemmania1324%^', 'study');

    if(!$db){
        throw new Exception("db연결 오류",1);
    }

$img_url = "";
$sel_status="판매중";




if(!empty($step) & $step !=1 & !isset($step) ){
    throw new Exception("비정상적인 접근 입니다",999);
}
if(!empty($sel_id) & !isset($sel_id)){
    throw new Exception("판매정보 오류",999);
}




if (isset($_SESSION['member_Session_number'])) {  // 로그인에 성공한 유저만 사용할 수 있도록.
$rs = $db->Execute("select sel.sel_title, sel.sel_author, sel.sel_price, sel.sel_quantity, sel.sel_contents, img.image_url, sel.sel_id from sel sel join sel_image img on sel.sel_id = img.sel_id where sel.sel_id='$sel_id' for update");

if(!$rs){
    throw new Exception("정보조회 오류",44);
}

while (!$rs->EOF) {


//  print_r($rs);
    $sel_title = $rs->fields[0];
    $sel_author = $rs->fields[1];
    $sel_price = $rs->fields[2];
    $sel_quantity = $rs->fields[3];
    $sel_contents = $rs->fields[4];
    $img_url = $rs->fields[5];
    $sel_id = $rs->fields[6];

    $rs->MoveNext();


    if(!empty($sel_id) & !isset($sel_id)){
        throw new DBException("판매정보 오류",999);
    }
    if(!empty($sel_title) & !isset($sel_title)){
        throw new DBException("판매정보 오류",999);
    }
    if(!empty($sel_price) & !isset($sel_price) & $sel_price<0){
        throw new DBException("판매정보 오류",999);
    }
    if(!empty($img_url) & !isset($img_url)){
        throw new DBException("판매정보 오류",999);
    }
    if(!empty($sel_author) & !isset($sel_author)){
        throw new DBException("판매정보 오류",999);
    }

    /******************************* 이미지 리사이징 Start **********************************************/
    $img_w = 150;                //width

    $img_h = 150;                // height


    $imgsize = GetImageSize($img_url);

    $img_width = $imgsize[0];       // 가로사이즈 선언
    $img_height = $imgsize[1];     // 세로사이즈 선언

    if ($img_width > $img_w) {
        $re_w_size = $img_w; // 가로사이즈가 지정사이즈보다 크면 지정사이즈로 고정
    } else {
        $re_w_size = $img_width; // 가로사이즈가 지정사이즈보다 작거나 같으면 기존사이즈 유지
    }
    if ($img_height > $img_h) {
        $re_h_size = $img_h; // 세로사이즈가 지정사이즈보다 크면 지정사이즈로 고정
    } else {
        $re_h_size = $img_height; // 세로사이즈가 지정사이즈보다 작거나 같으면 기존사이즈 유지}
    }

    /******************************* 이미지 리사이징 end **********************************************/

//판매상태 체크

    if($sel_quantity<=0){
        $sel_status="품절입니다";
    }
}



?>
<html>
<body>
<h2>[ <?= $sel_title ?> ]의 상세정보</h2>
<hr width="80%"/>
<div id="#contsRow">

    <form name="memRegForm" method="post" action="../buy/buy_buyForm.php" class="formtag">

        <ul style="list-style-type:none">

            <li><label for="nSelNum">제품사진 : </label><img src="<?= $img_url ?>" width="<?= $re_w_size ?>"
                                                         height="<?= $re_h_size ?>" border=0/></li>
            <br/>


            <li><label for="nPrice">가 격 : </label> <?= $sel_price ?>(원/개당)</li>
            <br/>

            <li><label for="nQuantity">잔여 수량 : </label> <?= $sel_quantity ?></li>
            <br/>

            <li><label for="nQuantity">내 용 : </label> <?= $sel_contents ?></li>
            <br/>
            <li><label for="nQuantity">판매상태 </label> 분할거래 //  <?= $sel_status ?></li>
            <br/>
            <input type="hidden" name="sel_id" value="<?= $sel_id ?>"/>
            <input type="hidden" name="step" value="2"/>


        </ul>
        <ul>
            <hr width="80%"/>

            <li align="center"><input type="submit" value="구매하기"/>
                <button type='button' onclick="location.href='sel_list.php'">돌아가기</button>
            </li>
            <?php // admin전용 메뉴
            $member_admin = $_SESSION['member_Session_admin'];
            if ($member_admin == 1) {
                ?>
                <li> - for admin menu - <br/>
                    <button type='button' onclick="location.href='sel_editForm.php?sel_id=<?= $sel_id ?>'">수정하기</button>
                    <button type='button' onclick="location.href='sel_delForm.php?sel_id=<?= $sel_id ?>'">삭제하기</button>
                </li>
            <?php } ?>
        </ul>

    </form>
</div>


</body>
</html>
<?php
} else {
    echo "<script>
        alert(\"Login first.\");
        </script>";


    echo("<script>location.href='../member/mem_login.php';</script>");

}

}  catch (Exception $e) {
    $error_msg = '에러발생 : ' . $e->getMessage() . $e->getCode();
    echo "<script>
        alert(\" $error_msg \");
        </script>";
    echo("<script>location.href='../index.php';</script>");

    if (isset($db) && $DB->IsConnected() === true) {
        $db->FailTrans();
        $db->Close();
        unset($db);
    }
    exit;
}
include("../_inc/footer.php");