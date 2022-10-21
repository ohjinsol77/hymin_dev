<?php
include("../_inc/header.php");
include_once('../adodb5/adodb-pager.inc.php');
require('../adodb5/adodb.inc.php');
?>
<html>
<body>
<h2>회원 리스트</h2>
<hr width="80%"/>
<div>

    <?php
        try {
        $driver = 'mysqli';
        $db = newAdoConnection($driver);
        $db->debug = false;

        $db->connect('localhost', 'root', 'Kdkdldpadkdl123$%^', 'study');
    } catch (Exception $e) {
        die($e->getMessage());   // 에러메세지 출력
    }

    $sql = "select member_num, member_id, member_name, member_tel, member_address, member_admin, member_gender, member_regdate, member_lastedit from member ";

    $pager = new ADODB_Pager($db, $sql);
    $pager->Render($rows_per_page = 15);


    ?>

</div>
</body>
</html>
<?php
include("../_inc/footer.php");