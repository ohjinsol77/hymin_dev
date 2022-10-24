<?php

include("../_inc/header.php");
require("../adodb5/adodb.inc.php");
error_reporting(E_ALL);
ini_set("display_errors", 1);
?>
    <html>
<body>
<h2>로그인 페이지</h2>
<hr width="80%"/>
<div id="#contsRow">
    <?php
    $member_id = $_POST['strId'];
    $member_password = $_POST['strPw'];
    $nMember_number = 0;
    $nMember_admin = 0;

    try {
        $driver = 'mysqli';
        $db = newAdoConnection($driver);
        $db->debug = true;
		///db 연결
        $db->connect('localhost', 'root', 'Kdkdldpadkdl123$%^', 'study');
		///만약 db연결이 안되면
        if(!$db){
			///예외처리
            throw new Exception("db연결 오류");
        }
		///db 트랜잭션 시작
        $trans_check=$db->StartTrans();
	///rs에 mem에서 번호 아이디 비밀번호 사용자정보와 mil에서 아이디를 검색 / member mem과 mileage mil 조인하고 mem과 mil의 멤버번호가 같을때 조인하고
	///멤버 아이디는 $member_id와 같고 member_password=Sha1에서 member_password와 같은 값을 가져온다.
	///sha1 = 암호화 알고리즘으로 입력받은 문자열을 160bit의 digest로 변환하는 해쉬 알고리즘
    $rs = $db->Execute("select mem.member_num, mem.member_id, mem.member_password, mem.member_admin, mil.mileage_id  
						from member mem join mileage mil 
						on mem.member_num=mil.member_num  
						where mem.member_id='$member_id' and mem.member_password=SHA1('$member_password') ");
	///만약 rs값이 아닐 때
    if (!$rs) {
		///예외처리
        throw new Exception("회원정보조회 오류 ",3);
	///
	} else {
		///rs값이 모두 반환되면 while문 나가기
		///field함수는 찾는 문자열 위치 반환 함수
		///세션값에 rs값의 1번 0번 3번 4번 대입
        while (!$rs->EOF) {
            $_SESSION['member_Session_id'] = $rs->fields[1];
            $_SESSION['member_Session_number'] = $rs->fields[0];
            $_SESSION['member_Session_admin'] = $rs->fields[3];
            $_SESSION['member_Session_mileage'] = $rs->fields[4];
            $rs->MoveNext();
        }
    }
    print_r($_SESSION);

    /***************세션체크***************/
	///만약 member_Session_id가 존재한다면
    if (isset($_SESSION['member_Session_id'])) {
		///아이디와 살았음 출력
        echo $_SESSION['member_Session_id'] . '살았음';
	///아니면
    } else {
		///죽음 출력
        echo "죽음";
    }
    /***************세션체크***************/

    ?>
	<!--이름은 memlogin/메소드는 포스트/인덱스 페이지로 보내고/클래스는 formtag-->
    <form name="memlogin" method="post" action="../index.php" class="formtag">

        <ul style="list-style-type:square">
            <?php
/// member_Session_id가 존재하면
if (isset($_SESSION['member_Session_id'])){

/******************************* 구매포인트 7일경과 항목 삭제 S *******************************/
///member_Session_mileage를 변수에 대입
$user_Mileage=$_SESSION['member_Session_mileage'];
/// call check_delDate($user_Mileage) 실행
$db->Execute("call check_delDate($user_Mileage)");
///만약 db가 연결되지 않으면
if(!$db){
	///예외처리
   throw new Exception("구매포인트 삭제 오류");
}
///commit
$db->CompleteTrans();

// member/procedure_test.txt에 프로시저 코드 첨부.
/******************************* 구매포인트 7일경과 항목 삭제 E *******************************/
            ?>
            <!-- 세션이 있을 경우 -->
			<!--중앙으로 정렬하고 member_Session_id를 출력하고 반갑습니다 출력-->
            <h2 align="center">안녕하세요 <?php echo $_SESSION['member_Session_id']; ?>님 반갑습니다.</h2>

        </ul>
        <ul>
			<!--넓이 80%-->
            <hr width="80%"/>
			<!--중간 정렬-->
            <li align="center">
				<!--버튼 형식으로 홈으로가기가 생성-->
                <input type="submit" value="홈으로 가기"/>
				<!-- 버튼형식으로 마이룸 가기 생성 한 번 클릭하면 정해진 php로 이동-->
                <input type="button" value="마이룸 가기" onclick="window.location='../mileage_View/view_myMileage.php'"/>
				<!-- 아니면-->
				<?php }else {
                ?>
				<!--중앙 정렬-->
                <h2 align="center">로그인 실패 다시 시도하세요..</h2>
        </ul>
        <ul>
			<!--넓이 80%-->
            <hr width="80%"/>
			<!--중앙 정렬-->
            <li align="center">

				<!--버튼형식에 홈으로가기 생성-->
                <input type="submit" value="홈으로 가기"/>
				<!--버튼형식에 회원가입 생성하고 클릭하면 regForm으로 이동-->
                <input type="button" value="회원가입" onclick="window.location='./mem_regForm.php'"/>
                <?php }
?>
            </li>
        </ul>

    </form>
</div>
<?php
///footer.php 정보를 가져온다
include("../_inc/footer.php");

}catch (Exception $e) {
	/// 예외처리 문자와 코드 출력
    $error_msg = '에러발생 : ' . $e->getMessage() . $e->getCode();
    /// error_msg에 해당하는 경고창 출력
	echo "<script>
        alert(\" $error_msg \");
        </script>";
	/// 인덱스php로 이동
    echo("<script>location.href='../index.php';</script>");


	///만약 연결db가 존재하고 db가 연결되었으면 true 반환
    if (isset($db) && $db->IsConnected() == true) {
		///트랜잭션
        if ($trans_check == true) {
			///rollbakc
            $db->FailTrans();
			///commit
            $db->CompleteTrans();
            ///변수 삭제
			unset($trans_check);
        }
		///db연결 종료
        $db->Close();
		///db 변수 삭제
        unset($db);
    }
    exit;
}

