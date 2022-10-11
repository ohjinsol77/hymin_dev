<?php
 error_reporting( E_ALL );
  ini_set( "display_errors", 1 );



class Cparam
{
	function __construct($param)
	{
		echo "constructor called with parameter ".$param."<br>";
	}
}
$Ca = new Cparam("first");
$Cb = new Cparam("second");
$Cc = new Cparam("1");
echo "<br>";
echo "Line - ";
echo __LINE__;
echo "<br><br>";




Class Cclassname
{
	public $attribute;
	function operation($param)
	{
		$this->attribute = $param;
		echo $this->attribute;
	}
}


//클래스 밖에서 바로 함수 접근 좋은 방법 아님
Class Classname
{
	public $attribute;
}
$a = new Cclassname();
$a -> attribute = "value";
echo $a->attribute;

echo "<br>";
echo "Line - ";
echo __LINE__;
echo "<br><br>";




Class Cclassname1
{
	public $attribute;
	function __get($name)
	{
		return $this->name;
	}
	function __set($name, $value)
	{
		//attribute 값이 0~100
		if(($name="attribute")&&(value>=0)&&(value<=100))
		//attribute = value
		$this->attribute = $value;
		$this->name = $value;
	}
}

$a = new Cclassname1();
$a->attribute = 5;
var_dump ($a);
echo "<br>";
echo "Line - ";
echo __LINE__;
echo "<br><br>";


//모든 클래스 멤버 앞에 접근 제한자가 붙어야 좋음(기본값 public)
//다른 접근 제한자와 구분위해서
Class Cclassname2
{
	public $attribute;
	public function __get($name)
	{
		return $this->name;
	}
	public function __set($name, $value)
	{
		$this->name = $value;
	}
}



//연산 호출
Class Cclassname3
{
	function operation1()
	{
		echo "operation1<br>";
	}
	function operation2($param1, $param2)
	{
		echo "operation2 $param1 : $param2";
	}
}
$a = new Cclassname3();
$a->operation1();
$a->operation2(12, "test");
echo "<br>";
echo "Line - ";
echo __LINE__;
echo "<br><br>";

$x = $a->operation1();
$y = $a->operation2(12, "test");
echo "<br>";
echo "Line - ";
echo __LINE__;
echo "<br><br>";


//상속구현
Class CclassB extends CclassA
{
	public $attribute2;
	function operation2()
	{
	}
}
Class CclassA
{
	//final function ~ 을 사용하면 상속과 오버라이딩을 막을 수 있다.
	public $attribute1;
	function operation1()
	{
	}
}
$b = new CclassB();
$b-> operation1();
$b-> attribute1 = 10;
$b-> operation2();
$b-> attribute2 = 10;

//class B가 A를 상속받았기 때문에 operation1,attribute1을 사용 할 수 있으나
//부모 클래스에서 자식 클래스를 상속받지 못함 //operation2,attribute2 사용 불가
//$a = new A();
//$a-> operation1();
//$a-> attribute1 = 10;
//$a-> operation2();		오류
//$a-> attribute2 = 10;		오류




//변수 범위 제어
//private = 자식클래스에게 상속 불가
//protected = 외부에서 사용 불가/ 자식 클래스에서는 사용 가능
Class Cprivate1
{
	public function operation1()
	{
		echo "operation called";
	}
	protected function operation2()
	{
		echo "operation2 called";
	}
	public function operation3()
	{
		echo "operation3 called";
	}
}


//Class Cprivate2 extends Cprivate1
//{
//	function __construct()
//	{
//		$this->operation1();
//		$this->operation2();
//		$this->operation3();
//	}
//}
//$Cprivate2 = new Cprivate2;






echo "<br>--------------------------------";
echo "<br>";

//클래스 상수 사용

Class Math{
	const pi = 3.14159;
}
//클래스당 상수에 접근하려면 ::연산자를 사용해 명시하면 됨
echo " Math::pi = ".Math::pi."\n";
echo "<br>";
echo "Line - ";
echo __LINE__;
echo "<br><br>";



//정적 메소드 구현
//static 사용하게 되면 인스턴스 없이 호출 가능
Class Cstatic
{
	//정적 메소드 작성
	static function fnSquared($nInput)
	{
		return $nInput*$nInput;
	}
}
echo Cstatic::fnSquared(8);
echo "<br>";
echo "Line - ";
echo __LINE__;
echo "<br><br>";








class CPlus
{
    // member variable
    private $strName;
    private $nAge;

    // constructor
    public function __construct()
    {
        $this->strName = "yse";
        $this->nAge = "10";
    }

    // method
    public function tell()
    {
        echo "my name is {$this->strName} .";
        echo " and my age is {$this->nAge} .";
    }

    // method. return $this
    public function fnAdd_age($nAge)
    {
        $this->nAge += $nAge;
        return $this;
    }

    // static method
    public static function fnFactory()
    {
        return new CPlus();
    }

    public static function fnFactory2()
    {
        return self::fnFactory();
    }    
}

//
$sample = new CPlus();
$sample->fnAdd_age(3);
$sample->tell();


//static을 calss 내에서 변수나 함수에 적용 시 다른 곳에서도 호출 가능
//클래스 밖에서 해당하는 함수나 변수에 접근하기 위해서는 인스턴스를 생성해서 접근 해야하는데
//static을 사용하면 클래스명::접근변수,함수 작성하 시 접근 가능
echo "<br />";
CPlus::fnFactory()->fnAdd_age(2)->tell();
CPlus::fnFactory2()->fnAdd_age(3)->tell();






?>