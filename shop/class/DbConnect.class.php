<?php


interface DbInterface {
    function getDbType();
    function getDbHost();
    function getDbName();
    function getDbUser();
    function getDbPassword();
}

interface DbInterface2 {
    function getDbType();
    function getDbHost();
    function getDbName();
    function getDbUser();
    function getDbPassword();
}


class DbConnection extends ADODB_mysqli {
    public $databaseType = NULL;
    public $database = NULL;
    public $host = NULL;
    public $user = NULL;
    public $password = NULL;

    function __construct(&$objDb)	{
        if($objDb instanceof DbInterface) {
            $this->databaseType = $objDb->getDbType();
            $this->database = $objDb->getDbName();
            $this->host = $objDb->getDbHost();
            $this->user = $objDb->getDbUser();
            $this->password = $objDb->getDbPassword();

            $objDb = null;
            unset($objDb);
        } else if ($objDb instanceof DbInterface2) {
            $this->databaseType = $objDb->getDbType();
            $this->database = $objDb->getDbName();
            $this->host = $objDb->getDbHost();
            $this->user = $objDb->getDbUser();
            $this->password = $objDb->getDbPassword();

            $objDb = null;
            unset($objDb);
        }
    }

    function __destruct() {
        if($this->IsConnected())
            $this->Close();
    }

    function dbClose(&$objDb) {
        $objDb->Close();
        $objDb = NULL;
    }
}

class ConnectionInfo {
    private function parse_ini_string_m( $string  ) {
        $array = Array();

        $lines = explode("\n",  $string );

        foreach( $lines  as $line  ) {

            $statement  = preg_match("/^(?!;)(?P<key>[\w+\.\-]+?)\s*=\s*(?P<value>.+?)\s*$/", $line, $match  );

            if( $statement  ) {
                $key     = $match[ 'key'  ];
                $value     = $match[ 'value'  ];

                if(  preg_match(  "/^\".*\"$/", $value ) || preg_match( "/^'.*'$/", $value  ) ) {
                    $value  = mb_substr( $value, 1, mb_strlen( $value  ) - 2  );
                }

                $array[ $key ] =  $value;
            }
        }
        return $array;
    }

    public function getDbInfo($ConnectionName) {
        // 특정 connection 을 다른 connection으로 변경해야 하는 경우 아래와 같이 getDbInfo 함수에서만 $ConnectionName 을 변경해주면 된다
        if($ConnectionName == "centerDB_SlaveConnection") {
            $ConnectionName = "centerDbConnection";
        }

        $dbPath = "/var/itemmania/".$ConnectionName;

        $enc_string = file_get_contents($dbPath);

        $ini_string = itm_decrypt($enc_string);

        $rgInfo = $this->parse_ini_string_m($ini_string);

        return $rgInfo;

    }
}


$rgConnection = scandir("/var/itemmania/");
foreach($rgConnection as $key => $connection) {
    if(in_array($connection, array('.', '..', 'enc.php'))) {
        continue;
    }

    eval("
	class ".$connection." implements DbInterface {
		public \$strDbType = '';
		public \$strDbName = '';
		public \$strDbHost = '';
		public \$strDbUser = '';
		public \$strDbPassword = '';

		function __construct()	{
            \$connectionInfo = new ConnectionInfo();
            \$rgInfo =  \$connectionInfo->getDbInfo( '".$connection."');
			\$this->strDbType        = \$rgInfo['dbtype'];
			\$this->strDbName        = \$rgInfo['dbname'];
			\$this->strDbHost        = \$rgInfo['dbhost'];
			\$this->strDbUser        = \$rgInfo['dbuser'];
			\$this->strDbPassword    = \$rgInfo['dbpass'];

		}

		public function getDbType() {
			return \$this->strDbType;
		}

		public function getDbHost() {
			return \$this->strDbHost;
		}

		public function getDbName() {
			return \$this->strDbName;
		}

		public function getDbUser() {
			return \$this->strDbUser;
		}

		public function getDbPassword() {
			return \$this->strDbPassword;
		}
	}
	");
}


?>