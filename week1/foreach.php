
<?php
$a = array("1", "2", "3", "4", "5", "6", "7", "8", "9");
$b = array("1", "2", "3", "4", "5", "6", "7", "8", "9");
foreach($a as $key => $val)
{
	echo "".$val." 단<br>";
	foreach($b as $key2=>$val2)
	{
		echo $val ." X ". $val2 ." = ".($val*$val2)."<br>";
	}
}
?>