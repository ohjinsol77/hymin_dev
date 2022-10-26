<?php
include('../_inc/header.php');
require('../adodb5/adodb.inc.php');
?>

<html>
<body>
<h2>로그인 페이지</h2>
<hr width="80%"/>
<div id="#contsRow">
	<!-- 페이지 이름은 memlogin이고 메서드 방식은 post, loginresult.php로 정보 전송 클래스는 formtag-->
    <form name="memlogin" method="post" action="mem_loginresult.php" class="formtag">
		<!--사각형 UI로 생성-->
        <ul style="list-style-type:square">


            <?php
			
            /***************세션체크***************/
			///member_Session_id가 존재하면

			if (isset($_SESSION['member_Session_id'])) {
				/// member_Session_id정보와 세션살음 출력
                echo $_SESSION['member_Session_id'] . '세션살음';
				///아니라면 세션 죽음이 출력
            } else {
                echo "세션죽음";
            }
            /***************세션체크***************/
			///만약 member_Session_id가 존재하면
            if (isset($_SESSION['member_Session_id']))
            {
                ?>
				<!--you aready login. go back!을 클릭하면 strId가 있는 곳으로 이동하는 라벨 생성-->
                <li><label for="strId">you aready login. go back!</label></li>


                <?php
			///아니라면
            }else{
            ?>
            <!--아이디 / 비밀번호 입력 폼-->
            <li><label for="strId">id</label> <input type="text" name="strId"
                                                     class="strId"/></li>
            <li><label for="strPw">pw</label> <input type="password"
                                                     name="strPw" class="strPw"/></li>
        </ul>
        <ul>
            <hr width="80%"/>
			<!--가운데 정렬-->
            <li align="center">

				<!--로그인하기-->
                <input type="submit" value="로그인하기"/>
                <!--회원 가입 버튼이 생기고, 누르게 되면 mem_regForm으로 이동-->
				<input type="button" value="회원가입" onclick="window.location='./mem_regForm.php'">
            </li>
            <?php } ?>
        </ul>

    </form>

</div>
<?php
/// footer정보 가져옴
include("../_inc/footer.php");