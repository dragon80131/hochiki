<?php
/**
 * DBConnectionクラス
 *
 * MySQLへの接続とSQL実行を担うクラスです。
 *
 * @package 
 * @access  public
 * @author  MasahitoSAMEKAWA
 * @create  2004/05/20
 * @version 0.1
 **/

define ("EXIST_CLASS_DBCONNECTION","yes");

class SPFWDatabase {
	/**
	 * コネクションID
	 */
	var $Connection;
	/**
	 * データベース名称
	 */
        var $DBNAME;	
         /**
	 * ホスト名
	 */
	var $HOSTNAME;
	/**
	 * 持続的接続の指定
	 */
	var $PERSISTENT;
	/**
	 * エラー文字列
	 */
	var $Err;
	
	/**
	 * コンストラクタ
	 * @access		public
	 * @param		String		$DBNAME		データベース名称
	 * @param		String		$HOSTNAME	接続先ホスト名称
	 * @param		String		$USERNAME	接続ユーザ名
	 * @param		String		$PASSWORD	接続パスワード
	 * @param		String		$PERSISTENT	持続的接続の指定
	 */
	function __construct ($DBNAME = "", $HOSTNAME = "", $USERNAME = "", $PASSWORD = "", $PERSISTENT = FALSE) {
		// プロパティセット
		$this->DBNAME = $DBNAME;
//              $this->DBNAME = koji999k;
		$this->HOSTNAME = $HOSTNAME;
		$this->PERSISTENT = $PERSISTENT;
		
		// エラー初期化
		$this->Err = "";
		
		// データベースの指定なし
		if ($DBNAME == "") {
			$this->Err = "データベースが指定されていません";
		} else {
			$this->open($USERNAME, $PASSWORD);
		}
	}
	
	/**
	 * データベースへの接続を開き、その結果を返します。
	 * @access		public
	 * @param		String		$USERNAME	接続ユーザ名
	 * @param		String		$PASSWORD	接続パスワード
	 * @return		bool		接続処理の結果
	 */
	function open($USERNAME = "", $PASSWORD = "") {
		// プロパティファイル読込
		include_once _CLS_DIR . "SPFWLog.cls";

		// ログ準備
		$myLog = new SPFWLog();
		$myLog->db("Open Connection for Database \"" . $this->DBNAME . "\".");

		// データベース名称チェック
		if ($this->DBNAME == "") {
			$this->Err = "データベースが指定されていません";
			$myLog->db("Open Connection for \"" . $this->DBNAME . "\" Failed.\n" . $this->Err);
			unset ($myLog);
			return FALSE;
		}
		
		// USERNAME あり？(認証あり？)
		if ($USERNAME != "") {
			// 認証あり
			// HOSTNAME チェック
			if ($this->HOSTNAME == "")
				$this->HOSTNAME = "localhost";

			// Persistent or Normal Connect
#			$con = mysql_connect($this->HOSTNAME, $USERNAME, $PASSWORD) or die(mysql_error());
            $con = mysqli_connect($this->HOSTNAME, $USERNAME, $PASSWORD, $DBNAME );




		}
		else {
			$con = mysqli_connect($this->HOSTNAME) or die(mysqli_error());
		}

		if (!$con) {
			$this->Err = "データベースに接続できませんでした";
			$myLog->db("Open Connection for \"" . $this->DBNAME . "\" Failed.\n" . $this->Err);
			unset ($myLog);
			$this->Connection = FALSE;
			return FALSE;
		} else {
			$this->Connection = $con;
			$myLog->db("Open Connection for \"" . $this->DBNAME . "\" Successful.");
		}

		if (!mysqli_select_db($con,$this->DBNAME)) {
			echo $this->Err = "データベースに接続できませんでした" . mysqli_error();
			$myLog->db("Choose Database \"" . $this->DBNAME . "\" Failed.\n" . $this->Err);
			unset ($myLog);
			$this->Connection = FALSE;
			return FALSE;
		} else {
			$this->Connection = $con;
			$myLog->db("Choose Database \"" . $this->DBNAME . "\" Successful.");
			unset ($myLog);
			return TRUE;
		}
	} // end of function Open()

	/**
	 * データベースへの接続を閉じ、その結果を返します。
	 * @access		public
	 * @return		bool		接続クローズ処理の結果
	 */
	function close() {
		// プロパティファイル読込
		include_once _CLS_DIR . "SPFWLog.cls";

		// ログ準備
		$myLog = new SPFWLog();
		$myLog->db("Close Connection for Database \"" . $this->DBNAME . "\".");

		mysqli_close($this->Connection);

		$myLog->db("Close Connection for \"" . $this->DBNAME . "\" Successful.");
		unset ($myLog);

		return TRUE;
	}

	/**
	 * 与えられたSQLを実行し、その結果を返します。
	 * @access		public
	 * @param		String		$sql	実行対象SQLステートメント
	 * @return		resource	クエリの実行結果
	 */
	function executeQuery($sql) {
		// プロパティファイル読込
		include_once _CLS_DIR . "SPFWLog.cls";

		// ログ準備
		$myLog = new SPFWLog();
		$myLog->sql("executeQuery : " . $sql);

		// SQLチェック
		if ($sql == "") {
			$this->Err = "SQLが指定されていません";
			$myLog->sql("executeQuery Failed : " . $sql . "\n" . $this->Err);
			unset ($myLog);
			return FALSE;
		}

		$sql = $this->adjustQuery($sql);

		if ($sql == 'COMMIT') {
#			if (!($rtn = mysqli_query('UNLOCK TABLES'))){

			if (!($rtn = mysqli_query($this->Connection , "UNLOCK TABLES;"))){

				$this->Err = "SQL実行に失敗しました\n";
				$myLog->sql("executeQuery Failed : " . $sql . "\n" . mysqli_error());
				unset ($myLog);
				trigger_error("executeQuery Failed : " . $sql . "\n" . mysqli_error(), E_USER_ERROR);
				return FALSE;
			}
		}

		$myLog->sql("executeQuery : " . $sql);
		// 実行
		if (!($rtn = mysqli_query($this->Connection, $sql))){
			$this->Err = "SQL実行に失敗しました\n";
			$myLog->sql("executeQuery Failed : " . $sql . "\n" . mysqli_error($this->Connection));
			unset ($myLog);
			trigger_error("executeQuery Failed : " . $sql . "\n" . mysqli_error($this->Connection), E_USER_ERROR);
			return FALSE;
		}

		//$myLog->sql("executeQuery Successful : " . $sql);
		unset ($myLog);

		return $rtn;
	}


###
function mysqli_result($res,$row=0,$col=0){ 
    $numrows = mysqli_num_rows($res); 
    if ($numrows && $row <= ($numrows-1) && $row >=0){
        mysqli_data_seek($res,$row);
        $resrow = (is_numeric($col)) ? mysqli_fetch_row($res) : mysqli_fetch_assoc($res);
        if (isset($resrow[$col])){
            return $resrow[$col];
        }
    }
    return false;
}
###
	// MaxやCountを使うときに利用 1件だけ値を返す
	function getOneValue($sql) {
		$sql = $this->adjustQuery($sql);

		$rtn = $this->executeQuery($sql);

		if (!$rtn) {
			$this->Err = "レコード取得に失敗しました";
			$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
			unset($myLog);
			return FALSE;
		}

#		if (mysql_numrows($rtn) == 0) {
		if (mysqli_num_rows($rtn) == 0) {

			return -1;
		}

#		$tmp = mysql_result($rtn,0,0);

#		if ($tmp == NULL) {
#			return -1;
#		} else {
			//return mysql_result($rtn);

			mysqli_data_seek($rtn,0);
			$fields = (is_int(0)) ? mysqli_fetch_row($rtn) : mysqli_fetch_assoc($rtn);
			return (is_null($fields[0])) ? false : $fields[0];

#		}



	}

	/**
	 * 与えられたPostgreSQL用記法のSQLをMySQLで実行可能なよう調整します
	 *
	 * @access		private
	 * @param		String		$sql	実行対象SQLステートメント
	 * @return		String		加工後SQLステートメント
	 */
	function adjustQuery($sql) {
		$sql = str_replace(" = FALSE", " = 0", $sql);
		$sql = str_replace(" = false", " = 0", $sql);
		$sql = str_replace(", false,", ", 0,", $sql);
		$sql = str_replace(", false)", ", 0)", $sql);

		$sql = str_replace(" = TRUE", " = 1", $sql);
		$sql = str_replace(" = true", " = 1", $sql);
		$sql = str_replace(", true,", ", 1,", $sql);
		$sql = str_replace(", true)", ", 1)", $sql);

		$sql = str_replace("'now()'", "NOW()", $sql);
		$sql = str_replace("'NOW()'", "NOW()", $sql);

		return $sql;
	}

	// 行数を返します
	function getNumberOfRows($rtn) {
		return mysqli_num_rows($rtn);
	}

	// カラム数を返します
	function getNumberOfColumns($rtn) {
#		return mysql_numfields($rtn);
		return mysqli_num_fields($rtn);



	}

	// データ取り出しを行います
	function fetchRow($rtn, $RowNo) {
		mysqli_data_seek($rtn,$RowNo);
		return mysqli_fetch_row($rtn);
	}

	// 文字列をエスケープします
	function escapeString($Value) {
		return mysqli_real_escape_string($this->Connection, $Value);
	}

	// 結果を返します
	function getResult($rtn, $col, $row) {
		//return mysql_result($rtn, $col, $row);
		mysqli_data_seek($rtn, $col);
		$fields = (is_int($row)) ? mysqli_fetch_row($rtn) : mysqli_fetch_assoc($rtn);
		return (is_null($fields[$row])) ? false : $fields[$row];
	}

	// 結果を空にします
	function freeResult($rtn) {
#		return mysql_free_result($rtn);
		return mysqli_free_result($rtn);
	}

	// テーブルをロックします
	function lockTable($TableName) {
		$sql = "LOCK TABLE " . $TableName . " WRITE";
		return $this->executeQuery($sql);
	}
} // end of class

?>
