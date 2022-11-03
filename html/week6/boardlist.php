<?php
error_reporting( E_ALL );
ini_set( "display_errors", 1 );
include('function.php');
?>

<!doctype html>
<head>
<meta charset="UTF-8">
<title>게시판</title>
</head>
<body align = "center">
<div id="board_area"> 
  <h1>자유게시판</h1>
  <h4>익명 게시판</h4>
    <table align = "center">
      <thead>
          <tr>
              <th width="300">번호</th>
                <th width="500">제목</th>
                <th width="120">작성자</th>
                <th width="100">작성일</th>
                <th width="100">조회수</th>
          </tr>
      </thead>

      <?php
	  if(isset($_GET['page'])) {
		  $page = $_GET['page'];
	  }else{
		  $page = 1;
	  }

	  $onepage = 10; #가져올 글 개수
	  $nLimit = (int)($onepage * $page) - $onepage; #몇 번째의 글부터 가져오는지
	  $qrySelect = "
			select * 
			 from board
			  order by number limit " . $nLimit. "," . $onepage . "; 
	  ";
	  $rstSelect = mysqli_query($Conn, $qrySelect);
	  try{
		  if(mysqli_num_rows($rstSelect) < 1){
			  throw new exception('리스트 조회 불가');
		  }

	  $qryCount = "
		select count(*) as count
	     from board 
		  order by number ;
	  ";
	  $rstCount = mysqli_query($Conn,$qryCount);
	  $rgRow = mysqli_fetch_assoc($rstCount);
	  $rgCount = $rgRow['count'];
  
	  #페이지 개수
	  $nPagecount = ceil($rgCount / $onepage);


	  $onesection = 10;	#한 번에 보여줄 페이지 수
	  $currentsection = ceil((int)$page / $onesection); #현재 섹션 -> 1번 /5개중 
	  $allsection = ceil($nPagecount / $onesection); #전체 섹션 ->22개
	  
	  $firstpage = ($currentsection * $onesection) - ($onesection -1); #섹션 처음 페이지
	  
	  if($currentsection == $allsection){
		  $lastpage = $nPagecount; #현 섹션이 마지막 페이지면 allpage가 마지막이 됨
	  }else{
		  $lastpage = $currentsection * $onesection; #현 섹션의 마지막 페이지
	  }
	  
	  $paging = '<ul>';
	  #첫 페이지 아니면 처음 버튼 생성
	  if($page != 1){
		  $paging .='<a href = "./page.php?page=1">첫 페이지</a>';
	  }

	  if($currentsection != $allsection){
		  $paging .= '<a href = "./page.php?page= ' . $page -1 . '">이전</a>';
	  }
	  
	  ##번호 띄울 for문
	  for($i = $firstpage; $i<=$lastpage; $i++){
		  if($i == $page){
			  $paging .= $i;
		  }else{
			  $paging .= '<a href = "./page.php?page='.$i.'">' . $i . '</a>';
		  }
	  }
	
	  if($currentsection != $allsection){
		  $paging .= '<a href = "./page.php?page= ' . $page + 1 . '">다음</a>';
	  }

	  if($currentsection != $allsection){
		  $paging .= '<a href = "./page.php?page=' . $nPagecount . '">끝</a>';
	  }

	  $paging .= '</ul>';



		  #while문 시작
		  while($board = $rstSelect->fetch_array()){
			  #title변수에 DB에서 가져온 title을 선택
			  $title=$board["title"];
			  if(strlen($title)>30){
				  #title이 30을 넘어서면 ...표시
				  $title=str_replace($board["title"],mb_substr($board["title"],0,30,"utf-8")."...",$board["title"]);
			  }

      ?>


      <tbody>
        <tr>

          <td  style="text-align:center" width="10">
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
	  <?php
	      } #while문 끝
	  }catch(exception $e){
		  $error= '에러발생 : ' . $e->getMessage();
		  echo "<script>alert(\" $error \");</script>";

		  if($Conn){
			  mysqli_close($Conn);
			  unset($Conn);
		  }

		  exit;
	  }
	  ?>
    </table>
  <p align="center"><a href="./boardForm.php"><button>글쓰기</button></a></p>
</div>
</body>
<ul class="pager">
<div align = "center">
<?php
echo $paging;
?>
</div>
</ul> 
</html>