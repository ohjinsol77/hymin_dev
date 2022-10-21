<?php
/************
 * 회원 포인트 충전 페이지
 *
 ************/


define("_STATE_","charge");


    include("../_inc/header.php");

    if(!isset($_SESSION['member_Session_id'])){          // 로그인을 하지 않았다면 충전 페이지 진입 금지.
    echo "<script>
    alert(\"Login first.  go back.\");
    window.open('./dr2.html','drdr','width=600,height=600,top=100,left=100');
</script>";
    echo("<script>location.href='../index.php';</script>");

    }

    ?>
    <html>
<body>
<h2>마일리지 충전소</h2>
<hr width="100%" />
<div id="#contsRow">

    <form name="milchargingform" method="post" action="mileage_chargingOk.php" class="formtag">

            <ul style="list-style-type:square">



                <p> 원하시는 충전 방법을 고르세요 마일리지의 사용 우선 순위는 아래와 같습니다.</p>
                <p>1) 현금          2) 신용카드          3) 휴대폰       4) 구매포인트 마일리지 // 모든 구매는 100만원 한도입니다.</p><br/>
                <p>휴대폰으로 충전한 마일리지는 사용만 가능하고 출금은 불가능한 마일리지로 충전됩니다.</p></br>
                <li> 충전 방법을 정해주세요 :
                    <SELECT name="mileage_type">
                        <option value="0">choose method</option>
                        <option value="1">현금</option>
                        <option value="2">신용카드</option>
                        <option value="3">휴대폰</option>

                    </select></li><br/>
		<li>금액을 입력하세요 : <input type="number"  name="mileage_price" max=1000000  placeholder="충전금액 입력" "/></li>
                <br/>
              


        <li>
            <input type="button" value="구매포인트 전환하기" onclick="window.location='/mileage/mileage_PointChange.php'">
            <input type="button" value="보유 마일리지 확인" onclick="window.location='/member/mem_mymil.php'">
            <input type="hidden" name="step" value="1"/>

        </li>



	</ul>
<ul>
<hr width="100%" />

		<li align="center">
		<input type="submit" value="Charging!!" />
		
		<input type="button" value="go home" onclick="window.location='/mem_regForm.php'">
		</li>
</ul>

</form>
</div>
<?php
include("../_inc/footer.php");