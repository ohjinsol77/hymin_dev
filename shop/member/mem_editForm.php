<?php
include("../_inc/header.php");
include("../adodb5/adodb.inc.php");
ini_set('display_errors', true);
error_reporting(E_ALL);
try {
    $driver = 'mysqli';
    $db = newAdoConnection($driver);
    $db->debug = true;

    $db->connect('localhost', 'root', 'Kdkdldpadkdl123$%^', 'study');
} catch (Exception $e) {
    die($e->getMessage());   // 에러메세지 출력
}

$member_id = "";
$member_tel = 0;
$member_name = "";
$member_address = "";
$member_gender = 0;


$mem_num = $_SESSION['member_Session_number'];



    $rs = $db->Execute("select member_id, member_name, member_gender, member_tel, member_address from member where member_num='$mem_num'");

    while (!$rs->EOF) {


        //  print_r($rs);
        $member_id = $rs->fields[0];
        $member_name = $rs->fields[1];
        $member_gender = $rs->fields[2];
        $member_tel = $rs->fields[3];
        $member_address = $rs->fields[4];

        $rs->MoveNext();


    }

    if ($member_gender == 1) {
        $member_gender = "남";
    } else if ($member_gender == 2) {
        $member_gender = "여";
    } else
        $member_gender = "??";


    ?>

    <html>
<body>
<h2>회원 수정</h2>
<hr width="80%"/>
<div id="#contsRow">

    <form name="memEditForm" method="post" action="mem_editOk.php" class="formtag">

        <ul style="list-style-type:square">

            <li><label for="strId">id</label> <input type="text" name="strId"
                                                     class="strId" value="<?php echo $member_id; ?>"
                                                     disabled="readonly"/></li>

            <li><label for="strId">password</label> <input type="text" name="strId"
                                                           class="strId"/></li>

            <li><label for="strName">이름</label> <input type="text"
                                                       name="strName" class="strName"
                                                       value="<?php echo $member_name; ?>"/></li>


            <li><label for="nSex">성별</label> 남<input type="radio"
                                                     name="nSex" value="1"/> 여<input type="radio" name="nSex"
                                                                                     value="2"/> ???<input type="radio"
                                                                                                           name="nSex"
                                                                                                           value="3"/>기존
                성별 :<?php echo $member_gender; ?></li>

            <li><label for="nTel">전화번호</label> <input type="number"
                                                      name="nTel" class="nTel" value="<?php echo $member_tel; ?>"/></li>


            <li><label for="strAdd">주소</label> <input type="text"
                                                      name="strAdd" class="strAdd" size="50"
                                                      value="<?php echo $member_address; ?>"/></li>


        </ul>
        <ul>
            <hr width="80%"/>

            <li align="center">
                <input type="submit" value="수정하기"/>
                <input type="reset" value="다시작성하기"/>
                <input type="button" value="탈퇴하기" onclick="window.location='./mem_regForm.php'"

                >
            </li>
        </ul>

    </form>
</div>
<?php
include("../_inc/footer.php");