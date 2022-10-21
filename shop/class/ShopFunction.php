<?php
/*****************************************
 *
 * 사용되는 모든 기능...
 * 클래스
 *****************************************/

ini_set('display_errors', true);
error_reporting(E_ALL);

//require("../adodb5/adodb.inc.php");
//include_once ("../db/dbconn.php");






Class ShopFunction
{

    private $db;
    private $user_id = null;
    private $user_num = null;

    public function __construct()
    {
//        //$this->db= new DB_conn();
//        $this->db=new Mysqli_conn();



    }



        //회원정보 저장
    public function fnSetUser($user_info){

        $this ->user_id = $user_info['user_id'];
        $this ->user_num = $user_info['user_num'];


    }
      // 타입별 마일리지 조회
    public function fnGetMileage($get_type)
    {


        $sql = "";
        $result="";
        $ResultMoney =0;
        if($get_type!=null){
            var_dump($result);

        if ($get_type == 1) {                // 사용가능한 전체 마일리지 조회
            $sql ="select (mil.cash_amount +mil.credit_amount+mil.phone_amount+mil.buymileage_amount) as `Amount` from mileage mil join member mem on mil.member_num=mem.member_num where mem.member_num=1";
        }

        if ($get_type == 2) {                // 사용가능한 전체 현금충전 마일리지 조회
            $sql = "select mil.cash_amount as `Amount` from mileage mil join member mem on mil.member_num=mem.member_num where mem.member_num=$this->user_num";
        }

        if ($get_type == 3) {                // 사용가능한 전체 신용카드충전 마일리지 조회
            $sql = "select mil.credit_amount as `Amount` from mileage mil join member mem on mil.member_num=mem.member_num where mem.member_num=$this->user_num";
        }

        if ($get_type == 4) {                // 사용가능한 전체 휴대폰충전 마일리지 조회
            $sql = "select mil.phone_amount as `Amount` from mileage mil join member mem on mil.member_num=mem.member_num where mem.member_num=$this->user_num";
        }

        if ($get_type == 5) {                // 사용가능한 전체 쇼핑포인트 조회
            $sql = "select mil.buypoint_amount as `Amount` from mileage mil join member mem on mil.member_num=mem.member_num where mem.member_num=$this->user_num";
        }

        if ($get_type == 6) {                // 사용가능한 전체 쇼핑마일리지 조회
            $sql = "select mil.buymileage_amount as `Amount` from mileage mil join member mem on mil.member_num=mem.member_num where mem.member_num=$this->user_num";
        }

        if ($get_type == 7) {                // $etc 코드의 포인트 조회
            $sql = "SELECT mil.cash_amount as `Amount` from mileage mil join member mem on mil.member_num=mem.member_num where mem.member_num=$this->user_num";
        }

        }
        else{
            echo "타입에러";
            return 0;
           }

        if($this->db==null) {
            echo "db연결 안되어있음";
        }else{
            echo "db 연결됨";
        }

        var_dump($result);
        $result=$this->db->Execute($sql);
        while(!$result->EOF)
        {
            $ResultMoney= $result->fields['Amount'];
            $result->MoveNext();
        }

        var_dump($result);
        echo "test_result =>";
        if($result == null){
            echo "쿼리 동작 실패";
            return 0;
        }


        return $ResultMoney;
    }


    public function testInsert($num){
        $result=null;
        if($num===1){
        $result=$this->db->Execute("insert into new_table (data1,data2,data3,data4) values(1,2,3,4)");

         return $result;


        }
    }




    // 마일리지 충전
     public function fnAddMileage(){



    }

    public function fnSubtract(){

    }


}
