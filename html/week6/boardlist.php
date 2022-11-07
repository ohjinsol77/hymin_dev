<?php
error_reporting( E_ALL );
ini_set( "display_errors", 1 );
include('function.php');
try {
	/* DB 연결 시작 */
	$Classdb = new database;
	$Conn = $Classdb->db;
	if (!$Conn) {
		throw new exception('데이터베이스 연결 실패');
	}

	/* 변수 초기화 시작 */
	$page = (isset($_GET['page']) & !empty($_GET['page'])) ? $_GET["page"] : 1;
	#가져올 글 개수
	$onepage = 10;
	#몇 번째의 글부터 가져오는지
	$nLimit = (int)($onepage * $page) - $onepage;

	/* 페이징 - 페이지당 글 출력 관련 쿼리 실행 시작 */
	$qrySelect = "
		SELECT * 
		  FROM board
		 ORDER BY number LIMIT " . $nLimit. "," . $onepage . "
	";
	$rstSelect = mysqli_query($Conn, $qrySelect);
	if (mysqli_num_rows($rstSelect) < 1) {
		throw new exception('리스트 조회 불가');
	}

	/* 페이징 - 페이지당 번호 관련 쿼리 실행 시작 */
	$qryCount = "
		SELECT COUNT(*) AS count
		  FROM board 
		 ORDER BY number
	";
	$rstCount = mysqli_query($Conn,$qryCount);
	if (mysqli_num_rows($rstCount) < 1) {
		throw new exception('글 개수 조회 불가');
	}

	/* 조회된 Count값 배열에 대입 */
	$rgRow = mysqli_fetch_assoc($rstCount);
	if (empty($rgRow['count'])) {
		throw new exception('count값이 비어있습니다.');
	}

	$rgCount = $rgRow['count'];

	/* 페이징 관련 변수 초기화 시작 */
	#페이지 개수
	$nPagecount = ceil($rgCount / $onepage);
	#한 번에 보여줄 페이지 수
	$onesection = 10;
	#현재 섹션
	$currentsection = ceil($page / $onesection);
	#전체 섹션
	$allsection = ceil($nPagecount / $onesection);
	#섹션 처음 페이지
	$firstpage = ($currentsection * $onesection) - ($onesection -1);

	/* 페이징 ... 체크 시작 */
	if ($currentsection == $allsection) {
	  $lastpage = $nPagecount; #현 섹션이 마지막 페이지면 allpage가 마지막
	} else {
	  $lastpage = $currentsection * $onesection; #현 섹션의 마지막 페이지
	}

	$paging = '<ul>';
	#첫 페이지 아니면 처음 버튼 생성
	if ($page != 1) {
	  $paging .='<a href = "./boardlist.php?page=1"> 처음 </a>';
	}
	
	#이전 버튼 생성
	if ($page >1) {
	  $paging .= '<a href = "./boardlist.php?page=' . $page -1 . '"> 이전 </a>';
	}

	#번호 생성
	for ($i = $firstpage; $i<=$lastpage; $i++) {
	  if ($i == $page) {
		  $paging .= "[" . $i . "]";
	  } else {
		  $paging .= '<a href = "./boardlist.php?page=' . $i . '"> [' . $i . '] </a>';
	  }
	}
	
	#다음 버튼 생성
	if ($page < $nPagecount) {
	  $paging .= '<a href = "./boardlist.php?page=' . $page + 1 . '"> 다음 </a>';
	}
	
	#끝 버튼 생성
	if ($page != $allsection & $page !=$nPagecount) {
	  $paging .= '<a href = "./boardlist.php?page=' . $nPagecount . '"> 끝 </a>';
	}
	
	$paging .= '</ul>';
} catch(exception $e) {
	$error= '에러발생 : ' . $e->getMessage();
	echo "<script>alert(\" $error \");</script>";
	if ($Conn) {
		mysqli_close($Conn);
		unset($Conn);
	}
	exit;
}

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
		<table align = "center" border =1>
			<thead>
				<tr>
				<th width="80">번호</th>
				<th width="500">제목</th>
				<th width="120">작성자</th>
				<th width="100">작성일</th>
				<th width="100">조회수</th>
				<th width="100">수정일</th>
				</tr>
			</thead>
			<?php
				/* 제목 관련 while문 시작 */
				while ($board = $rstSelect->fetch_array()) {
					#title변수에 DB에서 가져온 title을 선택
					$title=$board["title"];
					if (strlen($title)>30) {
						#title이 30을 넘어서면 ...표시
						$title=str_replace($board["title"],mb_substr($board["title"],0,30)."...",$board["title"]);
					}
					?>
					<tbody>
						<tr>
						<td style="text-align:center" width="5"><?=$board['number']?></td>
						<td style="text-align:center" width="500"><a href="./read.php? number=<?=$board['number']?>"><?=$title?></a></td>
						<td style="text-align:center" width="120"><?=$board['writer']?></td>
						<td style="text-align:center" width="150"><?=$board['date']?></td>
						<td style="text-align:center" width="100"><?=$board['view']?></td>
						<td style="text-align:center" width="150"><?=$board['modify']?></td>
						</tr>
					</tbody>
					<?php
				}
				/* 제목 관련 while문 종료 */
			?>
		</table>
		<p align="center"><a href="./boardForm.php"><button>글쓰기</button></a></p>
	</div>
	<ul class="pager">
	<div align = "center"><?=$paging?></div>
	</ul>
</body>
</html>