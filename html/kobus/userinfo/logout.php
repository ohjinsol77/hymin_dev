<?php
error_reporting( E_ALL );
ini_set( "display_errors", 1 );
session_start();
session_destroy();


echo "<script>
alert(\"Good Bye!.\");
window.open('./dr2.html','drdr','width=600,height=600,top=100,left=100');
</script>";



///인덱스 페이지로 이동
echo("<script>location.href='../loginForm.php';</script>");

?>