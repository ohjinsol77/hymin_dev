<?php

session_destroy();                  // 살아있는 세션을 모두 없앤다.

echo "<script>
alert(\"Good Bye!.\");
window.open('./dr2.html','drdr','width=600,height=600,top=100,left=100');
</script>";




echo("<script>location.href='../index.php';</script>");





?>