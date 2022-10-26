<?php
//include("../db/dbconn.php");
require("../adodb5/adodb.inc.php");
error_reporting(E_ALL);

ini_set("display_errors", 1);


$strId = $_POST['strId'];
$strName = $_POST['strName'];
$strPassword = $_POST['strPassword'];
$strPassword_check = $_POST['strPassword_check'];
$nSex = $_POST['nSex'];
$strTel = $_POST['strTel'];
$strAdd = $_POST['strAdd'];
//  미입력 체크 및 비밀번호 미스매칭 체크
///입력받은 아이디가 존재하지 않으면 경고창에 check id! 출력하고 dr.2html브라우저탭 연다.
///regForm으로 이동 후 종료
if (!isset($strId)) {
    echo "<script>
		alert(\"check id!.\");
		window.open('./dr2.html','drdr','width=600,height=600,top=100,left=100');
		</script>";
    echo("<script>location.href='mem_regForm.php';</script>");
    exit;
}
// 이름 미입력 체크
///입력받은 이름이 존재하지 않으면 경고창에 check name! 출력하고 새 브라우저탭 연다.
///위와 동일
if (!isset($strName)) {
    echo "<script>
		alert(\"check name!.\");
		window.open('./dr2.html','drdr','width=600,height=600,top=100,left=100');
		</script>";
    echo("<script>location.href='mem_regForm.php';</script>");
    exit;
}
// 비밀번호 미입력 체크
///입력받은 비밀번호 존재하지 않으면 check pw! 출력하고 새 브라우저탭 연다.
///위와 동일
if (!isset($strPassword)) {
    echo "<script>
		alert(\"check pw!.\");
		window.open('./dr2.html','drdr','width=600,height=600,top=100,left=100');
		</script>";

    echo("<script>location.href='mem_regForm.php';</script>");
}
// 비밀번호체크 미입력 체크
/// 위에서 입력한 비밀번호와 동일하지 않으면 not equal pw 출력하고 새 브라우저탭 연다.
/// 위와 동일
if ($strPassword !== $strPassword_check) {
    echo "<script>
		alert(\"not equal pw.  plz check!.\");
		window.open('./dr2.html','drdr','width=600,height=600,top=100,left=100');
		</script>";
    echo("<script>location.href='mem_regForm.php';</script>");
    exit;
}
// 성별 미입력 체크
///입력받은 nSex값이 없으면 check your gender! 출력하고 새 브라우저 탭 연다
///위와 동일
if (!isset($nSex)) {
    echo "<script>
		alert(\"check your gender!.\");
		window.open('./dr2.html','drdr','width=600,height=600,top=100,left=100');
		</script>";
    echo("<script>location.href='mem_regForm.php';</script>");
    exit;
}
//전화번호 미입력 체크
///위와 동일
if (!isset($strTel)) {
    echo "<script>
		alert(\"check Tel!.\");
		window.open('./dr2.html','drdr','width=600,height=600,top=100,left=100');
		</script>";
    echo("<script>location.href='mem_regForm.php';</script>");
    exit;
}
// 주소 미입력 체크
///위와 동일
if (!isset($strAdd)) {
    echo "<script>
		alert(\"check add!.\");
		window.open('./dr2.html','drdr','width=600,height=600,top=100,left=100');
		</script>";
    echo("<script>location.href='mem_regForm.php';</script>");
    exit;
}
echo $strId;
echo $strName;
echo $strPassword;
echo $nSex;
echo $strTel;
echo $strAdd;
try {
	$driver = 'mysqli';
    $db = newAdoConnection($driver);
    $db->debug = false;
    $db->socket = '/var/run/mysqld/mysql_3306.sock';
	///db 연결
    $db->connect('localhost', 'root', 'Itemmania1324%^', 'study');
    /// db 연결되지 않으면
	if(!$db){
		///예외처리되고 db연결 오류가 뜨고 코드번호 94 출력
        throw new Exception("db연결 오류",94);
    }
	///트랜잭션 시작
    $trans_check=$db->StartTrans();

//rs는 member테이블에 post로 입력받아온 값과 현재 시간 데이터를 추가한다.
$rs = $db->Execute("insert into member(member_id, member_password, member_name, member_tel, member_address, member_gender, member_regdate, member_lastedit) VALUES ('$strId',SHA1('$strPassword'),'$strName','$strTel','$strAdd',$nSex,now(), now())");
///insert_ID는 인서트로 입력된 $db값의 pk로 입력된 값을 가져온다.
///mysql 구문에서는 last_inser_id()로 사용
$mem_number = $db->Insert_ID();
///mileage테이블에 멤버번호를 입력한다
$rs1 = $db->Execute("insert into mileage (member_num) values ($mem_number)");


	// 만약 rs가
	///rs가 db에서 변경된 횟수가 1보다 작으면
    if ($rs=$db->Affected_Rows() <1){
		///예외처리로 등록오류와 코드번호5490 출력
        throw new Exception("등록 오류",5490);
    }
	/// rs1이 db에서 변경된 횟수가 1보다 작으면
    if ($rs1=$db->Affected_Rows() <1){
		///예외처리로 등록오류와 코드번호5490 출력
        throw new Exception("등록 오류",5490);
    }
	///변수제거
    unset($rs);
    unset($rs1);
    unset($mem_number);

///트랜잭션 commit
$db->CompleteTrans();
echo "<script>
		alert(\"welcom shoes shop~.\");
		window.open('./dr2.html','drdr','width=600,height=600,top=100,left=100');
		</script>";
echo("<script>location.href='mem_login.php';</script>");
///footer.php 정보를 가져온다.
include("../_inc/footer.php");

}catch (Exception $e) {
	/// 예외처리 메시지, 코드 출력
    $error_msg = '에러발생 : ' . $e->getMessage() . $e->getCode();
    echo "<script>
        alert(\" $error_msg \");
        </script>";
    echo("<script>location.href='../index.php';</script>");
	
	///만약 db가 존재하고 db가 연결되면
    if (isset($db) && $db->IsConnected() == true) {
        ///trans_check가 되면
		if ($trans_check == true) {
			///db는 rollback
			$db->FailTrans();
			///db는 commit
            $db->CompleteTrans();
            ///변수삭제
			unset($trans_check);
        }
		///db 연결을 종료한다
        $db->Close();
		///변수제거
        unset($db);
    }
	///종료
    exit;
}

