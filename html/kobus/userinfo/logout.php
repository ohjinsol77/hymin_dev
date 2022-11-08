<?php
error_reporting( E_ALL );
ini_set( "display_errors", 1 );
include('../function/function.php');
session_start();
session_destroy();
$strAlert = '로그아웃 되셨습니다.';
$strLocation = '../userinfo/mainPage.php';
fnAlert($strAlert,$strLocation);
?>