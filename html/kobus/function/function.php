<?php
error_reporting( E_ALL );
ini_set( "display_errors", 1 );



/* mysqli 쿼리로 바로 실행시키는건 부적합 -> 교체해야함 */
class database{
	private $strHost = 'localhost';
	private $strId = 'root';
	private $strPw = 'Itemmania1324%^';
	private $strTable = 'dev_test';
	private $strPort = '3306';
	private $strSock = '/var/run/mysqld/mysql_3306.sock';
	
	public function __construct(){
		$this->db = mysqli_connect($this->strHost, $this->strId, $this->strPw, $this->strTable, $this->strPort, $this->strSock);
	}

	public function fnCommit(){
		if(mysqli_query($this->db,'commit')){
			return true;
		}else{
			return false;
		}

	}

	public function fnRollback(){
		if(mysqli_query($this->db, 'rollback')){
			return true;
		}else{
			return false;
		}
	}
	
	public function fnStart_trans(){
		if(!mysqli_query($this -> db, 'set autocommit = 0') || !mysqli_query($this -> db, 'start transaction')) {
			return false;
		}else{
			return true;
		}
	}

}

function fnAlert($strAlert , $strLocation) {
	$strAlrt = "<script>alert(\"$strAlert\");</script>";
	$strAlrt .= ("<script>location.href='./$strLocation';</script>");

	echo $strAlrt;
}
?>