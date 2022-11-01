<?php
error_reporting( E_ALL );
ini_set( "display_errors", 1 );
include('dbConn.php');
?>

<!doctype html>
<head>
<meta charset="UTF-8">
<title>게시판</title>
</head>
<body>
<div id="board_area"> 
  <h1>자유게시판</h1>
  <h4>익명 게시판</h4>
    <table class="list-table">
      <thead>
          <tr>
              <th width="70">번호</th>
                <th width="500">제목</th>
                <th width="120">작성자</th>
                <th width="100">작성일</th>
                <!-- 추천수 항목 추가 -->
                <th width="100">조회수</th>
                <!-- <th width="100">추천수</th>-->
          </tr>
      </thead>

        <?php

		  $qrySelect = "select * from board order by number limit 0,10";
          $rstSelect = mysqli_query($CMaster,$qrySelect); 
            while($board = $rstSelect->fetch_array())
            {
              //title변수에 DB에서 가져온 title을 선택
              $title=$board["title"]; 
              if(strlen($title)>30)
              { 
                //title이 30을 넘어서면 ...표시
                $title=str_replace($board["title"],mb_substr($board["title"],0,30,"utf-8")."...",$board["title"]);
              }
        ?>
      <tbody>
        <tr>
          <td style="text-align:center" width="10">
						<?php echo $board['number']; ?></td>

          <td style="text-align:center" width="500">
					<a href="./read.php? number=<?php echo $board['number'];?>">
						<?php echo $title;?></a></td>

          <td style="text-align:center" width="120">
						<?php echo $board['writer']?></td>

          <td style="text-align:center" width="150">
						<?php echo $board['date']?></td>

          <td style="text-align:center" width="100">
						<?php echo $board['view']; ?></td>

          <!-- 추천수 표시해주기 위해 추가한 부분 -->
          <!--<td width="100"><?php echo $board['thumbup']?></td>-->

        </tr>
      </tbody>
      <?php } ?>
    </table>
     <p align="center"><a href="./boardForm.php"><button>글쓰기</button></a></p>
  </div>
</body>
</html>