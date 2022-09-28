<?php

//문자열함수
$test='     아이템 매니아 Itemmania ';

$$strTest1 = trim($test); //문자열 처음과 끝에 있는 공백을 지운다
echo $$strTest1."<br>";


$strTest2 = ltrim($test);  //문자열 처음에 있는 공백을 지운다
echo $strTest2.'<br>';


$strTest3 = chop($test); //문자열 끝에 있는 공백을 지운다
echo $strTest3.'<br>';


$strTest4 = strtoupper($test); //모든 알파벳 대문자로
echo $strTest4.'<br>';


$strTest5 = strtolower($test); //모든 알파벳 소문자로
echo $strTest5.'<br>';

$strTest6 = ucfirst($test); //문장의 처음이 알파벳이면 그것만 대문자로
echo $strTest6.'<br>';


$strTest7='Change';
$res = str_replace('g','c',$strTest7);  //$strTest2 테스트 변수의 g문자열을 c로 바꾸어 리턴
echo $res.'<br>';

$strTest8 = strlen($test); //문자열의 길이를 정수값으로 리턴
echo $strTest8;



?>
