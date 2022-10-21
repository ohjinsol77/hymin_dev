<?php
	include("../_inc/header.php");
?>
<html>
<body>
<h2>회원 탈퇴</h2>
<hr width="80%" />
<div id="#contsRow">

<form name="memEditForm" method="post" action="mem_EditOk.php" class="formtag">

	<ul style="list-style-type:square">

	
		
		<li> 정말 탈퇴하시겠습니까? 남은 잔액은 : ********이며 ,  절대 환불되어지지 않습니다.</li>

        <?php


        ?>

	</ul>
<ul>
<hr width="80%" />

		<li align="center">
		<input type="submit" value="Yes! bye bye~" />
		
		<input type="button" value="Sorry .. go Home" onclick="window.location='./mem_regForm.php'"

>
		</li>
</ul>

</form>
</div>
<?php
include("../_inc/footer.php");