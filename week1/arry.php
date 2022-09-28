<?php
print '<br><h1>지역 인원수 출력</h1>';$city= array('경기수원'=>1194313,            '경남창원'=>1059241,            '경기고양'=>990073,            '경기용인'=>971327,            '충북청주'=>833276,            '전북전주'=>658172,            '충남천안'=>629062,            '경남김해'=>534124,            '경북포항'=>511124,            '경남진주'=>349788);print '<table>';
print '<tr><td>지역</td><td>인구수</td></tr>';$city['total']=0;
foreach($city as $key =>$value){     
	$city['total'] += $value;    
print "<tr><td>$key</td><td>$value</td></tr>";    }


?>