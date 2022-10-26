<?php
include("../_inc/header.php");
require("../adodb5/adodb.inc.php");
error_reporting(E_ALL);
ini_set("display_errors", 1);


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

?>
<html>
<body>
<h2>물품 정보 수정결과</h2>
<hr width="80%"/>
<div id="#contsRow">

    <?php


    /******************************   물품 수정관련 코드 Start   ***************************************************/
    $sel_id = $_POST['sel_id'];
    $sel_title = $_POST['strTitle'];
    $sel_price = $_POST['nPrice'];
    $sel_quantity = $_POST['nQuantity'];
    $sel_contents = $_POST['strConts'];
    $sel_imgname = $_POST['sel_img'];
    $real_name = "";

    $strAuthor = $_SESSION['member_Session_id'];

    $rs = $db->Execute("update sel set sel_title='$sel_title', sel_price='$sel_price', sel_quantity='$sel_quantity', sel_contents='$sel_contents' where sel_id=$sel_id ");


    /******************************   물품 수정 관련 코드 End  ***************************************************/


    if (($_FILES['strImg']['error']) != 4) {             // 이미지를 등록하지 않으면 업데이트를 하지 않는다.

        // 이미지를 등록했으면

        /******************************   이미지 등록 관련 코드 Start  ***************************************************/
        $image_dir = "./image/";
        $error_checker = 1;     // 에러 감지 코드


        /*** 파일명 변경 ***/

        $filename = $_FILES['strImg']['name'];

        $ext = substr(strrchr($filename, "."), 1);

        $ext = strtolower($ext);            // 확장자 추출


        $tmp_file = explode(' ', microtime());

        $tmp_file[0] = substr($tmp_file[0], 2, 6);

        $filename = $tmp_file[1] . $tmp_file[0] . '.' . $ext;


        //$_FILES['strImg']['name'] = $filename;
        $tmp_name = $filename;
        /*** 파일명 변경 ***/

        $target_file = $image_dir . $filename;


        if (isset($_POST["submit"])) {
            $check = getimagesize($_FILES["strImg"]["tmp_name"]);
            if ($check !== false) {
                echo "File is an image - " . $check["mime"] . ".";
                $error_checker = 1;
            } else {
                echo "File is not an image.";           // 파일 형식 제한.
                $error_checker = 0;
            }
        }
        // Check if file already exists
        if (file_exists($target_file)) {
            echo "exists error.";
            $error_checker = 0;                         // 중복이미지 등록 제한
        }
        // Check file size
        if ($_FILES["strImg"]["size"] > 3000000) {      // 이미지의 크기 제한
            echo "over size.";
            $error_checker = 0;
        }

        if ($error_checker == 0) {
            echo "E R R O R !!!!!!!!!!!!!!!!!!";        // 오류안내


        } else {// 문제없음 .


            if (move_uploaded_file($_FILES["strImg"]["tmp_name"], $target_file)) {  // 임시파일을 실제 경로로 이동

                $real_name = $_FILES["strImg"]["name"];
                $imgurl = "http://192.168.56.13/sel/image/" . $tmp_name;
                $size = $_FILES["strImg"]["size"];


                // DB에 image 정보를 입력
                $rs = $db->Execute("update sel_image set image_filename='$real_name', image_url='$imgurl', image_size='$size' where sel_id=$sel_id ");


            }
        }
        echo "<p>Sorry, there was an error uploading your file.</p>";
        echo "<br><button type='button' onclick=\"location.href='sel_list.php'\">돌아가기</button>";
    }
    /******************************   이미지 등록 관련 코드 End  ***************************************************/

    ?>


    <hr width="80%"/>

    <ul>

        <li>title :<?= $sel_title ?></li>
        <br/>
        <li>price :<?= $sel_price ?></li>
        <br/>
        <li>quantity :<?= $sel_quantity ?></li>
        <br/>
        <li>contents :<?= $sel_contents ?></li>
        <br/>

        <?php if (($_FILES['strImg']['error']) != 4) { ?>
            <li>image :<?= $real_name ?></li><br/>
        <?php } else { ?>
            <li>image :<?= $sel_imgname ?></li><br/>


            <?php
            echo "<p>The file " . basename($_FILES["strImg"]["name"]) . " has been uploaded.</p>";
            echo "<br><button type='button' onclick=\"location.href='sel_list.php'\">돌아가기</button>";
        } ?>


    </ul>


</div>


</body>
</html>
<?php
include("../_inc/footer.php");