<?php
/**
 * Administratorクラス
 *
 * 会員マスタを扱うクラスです。
 *
 * @package 
 * @access  public
 * @author  MasahitoSAMEKAWA <masahito@souppot.jp>
 * @create  2003/01/31
 * @version 0.1
 **/

class Administrator {
	/**
	 * データベースクラスのインスタンス
	 */
	var $myDB;
	/**
	 * レコードカウント
	 */
	var $RecCnt;
	/**
	 * クエリ実行結果セット
	 */
	var $tmprtn;
	/**
	 * 対象テーブル名称
	 */
	var $TableName;
	/**
	 * 管理者コード
	 * 
	 * 連番
	 */
	var $AdminCD;
	/**
	 * クライアントコード
	 */
	var $ClientCD;
	/**
	 * 管理者ID
	 */
	var $ID;
	/**
	 * パスワード
	 */
	var $Passwd;
	/**
	 * 姓
	 */
	var $LastName;
	/**
	 * 名
	 */
	var $FirstName;
	/**
	 * 最終ログイン
	 */
	var $LastLogin;
	/**
	 * レジストキー
	 */
	var $RegistKey;
	/**
	 * 備考
	 */
	var $Notes;
	/**
	 * 無効フラグ
	 */
	var $MukouFlg;
	/**
	 * 作成日時
	 */
	var $Created;
	/**
	 * 作成者
	 */
	var $Creator;
	/**
	 * 更新日時
	 */
	var $Updated;
	/**
	 * 更新者
	 */
	var $Updater;

	function __construct($myDB = "",$AdminCD = -1) {
		// ログ準備
		$myLog = new SPFWLog();

		$this->Err = "";
		
		if ($myDB == "") {
			$this->Err = "データベースへのコネクションがありません";
			$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
			unset($myLog);
			return FALSE;
		}

		// 整数変換
		settype($AdminCD,"integer");
		// 整数チェック
		if (!is_long($AdminCD) or $AdminCD == 0) {
			$this->Err = "ユーザーコード が不正です";
			$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
			unset($myLog);
			return FALSE;
		}

		$this->myDB = $myDB;
		$this->TableName = "tAdministratorM";
		$this->AdminCD = $AdminCD;

		return TRUE;
	}

	function initialize(){
		$this->Err = "";

		$this->AdminCD = "";
		$this->ClientCD = "";
		$this->ID = "";
		$this->Passwd = "";
		$this->LastName = "";
		$this->FirstName = "";
		$this->LastLogin = "";
		$this->RegistKey = "";
		$this->Notes = "";
		$this->MukouFlg = "";
		$this->Created = "";
		$this->Creator = "";
		$this->Updated = "";
		$this->Updater = "";
	}

	//抽出
	function executeSelect($WhereStr = "", $OrderByStr = ""){
		// ログ準備
		$myLog = new SPFWLog();

		$this->Err = "";

		//設定
		if ($this->myDB == "") {
			$this->Err = "データベースへのコネクションがありません";
			$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
			unset($myLog);
			return FALSE;
		}
		
		// ページプロパティ取得
		$sqlMoji = "select ";
		$sqlMoji .= "AdminCD";
		$sqlMoji .= ", ClientCD";
		$sqlMoji .= ", ID";
		$sqlMoji .= ", Passwd";
		$sqlMoji .= ", LastName";
		$sqlMoji .= ", FirstName";
		$sqlMoji .= ", LastLogin";
		$sqlMoji .= ", RegistKey";
		$sqlMoji .= ", Notes";
		$sqlMoji .= ", MukouFlg";
		$sqlMoji .= ", Created";
		$sqlMoji .= ", Creator";
		$sqlMoji .= ", Updated";
		$sqlMoji .= ", Updater";
		$sqlMoji .= " from $this->TableName";

		if($WhereStr != "")
			$sqlMoji = $sqlMoji . " where " . $WhereStr;

		if($OrderByStr != "")
			$sqlMoji = $sqlMoji . " order by " . $OrderByStr;

		$rtn = $this->myDB->executeQuery($sqlMoji);
		if (!$rtn){
			$this->Err = "レコード取得に失敗しました";
			$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
			unset($myLog);
			return FALSE;
		}
		else{
			$this->RecCnt = $this->myDB->getNumberOfRows($rtn);
			if ($this->RecCnt == 1){
				$this->tmprtn = $rtn;
				$this->getDataSet(0);
			}
			else if($this->RecCnt > 1){
				$this->tmprtn = $rtn;
			}
		}

		return TRUE;
	}

	//タプル取得
	function getDataSet($RowNo){
		if($RowNo >= $this->RecCnt){
			$this->Err = "行番号が不正です";
			return FALSE;
		}
		else{
			list(
				$this->AdminCD,
				$this->ClientCD,
				$this->ID,
				$this->Passwd,
				$this->LastName,
				$this->FirstName,
				$this->LastLogin,
				$this->RegistKey,
				$this->Notes,
				$this->MukouFlg,
				$this->Created,
				$this->Creator,
				$this->Updated,
				$this->Updater
			) = $this->myDB->fetchRow($this->tmprtn,$RowNo);

			$this->ID = stripslashes($this->ID);
			$this->Passwd = stripslashes($this->Passwd);
			$this->LastName = stripslashes($this->LastName);
			$this->FirstName = stripslashes($this->FirstName);
			$this->RegistKey = stripslashes($this->RegistKey);
			$this->Notes = stripslashes($this->Notes);
			$this->MukouFlg = $this->convertFlgToBoolean($this->MukouFlg);
		}
	}

	/* アップデート */
	function executeUpdate() {
		// ログ準備
		$myLog = new SPFWLog();

		// エラークリア
		$this->Err = "";

		//設定
		if ($this->myDB == "") {
			$this->Err = "データベースへのコネクションがありません";
			$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
			unset($myLog);
			return FALSE;
		}
		
		if ($this->AdminCD < 0) {
			// 新規
			// step 1. 新規番号取得のためトランザクション開始
			if (!($this->myDB->executeQuery("BEGIN"))) {
				$this->Err = "追加処理に失敗しました(BEGIN)";
				$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
				unset($myLog);
				return FALSE;
			} else {
				// step 2. ロック
				if (!($this->myDB->lockTable($this->TableName))) {
					$this->Err = "追加処理に失敗しました(LOCK)";
					$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
					$this->myDB->executeQuery("ROLLBACK");
					unset($myLog);
					return FALSE;
				} else {
					// step 3. 取得
					$sqlMoji = "select AdminCD from $this->TableName order by AdminCD desc limit 1";
					$maxCD = $this->myDB->getOneValue($sqlMoji);
					$maxCD = ($maxCD == -1) ? 0 : $maxCD;
					$maxCD++;

					// step 4. インサート
					$sqlMoji = "Insert into $this->TableName (";
					$sqlMoji .= "AdminCD";
					if ($this->ClientCD != "") $sqlMoji .= ", ClientCD";
					if ($this->ID != "") $sqlMoji .= ", ID";
					if ($this->Passwd != "") $sqlMoji .= ", Passwd";
					if ($this->LastName != "") $sqlMoji .= ", LastName";
					if ($this->FirstName != "") $sqlMoji .= ", FirstName";
					if ($this->LastLogin != "") $sqlMoji .= ", LastLogin";
					if ($this->RegistKey != "") $sqlMoji .= ", RegistKey";
					if ($this->Notes != "") $sqlMoji .= ", Notes";
					if ($this->MukouFlg != "") $sqlMoji .= ", MukouFlg";
					$sqlMoji .= ", Created";
					if ($this->Creator != "") $sqlMoji .= ", Creator";
					$sqlMoji .= ", Updated";
					if ($this->Updater != "") $sqlMoji .= ", Updater";
					$sqlMoji .= ") values (";
					$sqlMoji .= "$maxCD";
					if ($this->ClientCD != "") $sqlMoji .= ", $this->ClientCD";
					if ($this->ID != "") $sqlMoji .= ", '" . addslashes($this->ID) . "'";
					if ($this->Passwd != "") $sqlMoji .= ", '" . addslashes($this->Passwd) . "'";
					if ($this->LastName != "") $sqlMoji .= ", '" . addslashes($this->LastName) . "'";
					if ($this->FirstName != "") $sqlMoji .= ", '" . addslashes($this->FirstName) . "'";
					if ($this->LastLogin != "") $sqlMoji .= ", '" . addslashes($this->LastLogin) . "'";
					if ($this->RegistKey != "") $sqlMoji .= ", '" . addslashes($this->RegistKey) . "'";
					if ($this->Notes != "") $sqlMoji .= ", '" . addslashes($this->Notes) . "'";
					if ($this->MukouFlg != "") $sqlMoji .= ", " . $this->convertFlgToString($this->MukouFlg);
					$sqlMoji .= ", NOW()";
					if ($this->Creator != "") $sqlMoji .= ", $this->Creator";
					$sqlMoji .= ", NOW()";
					if ($this->Updater != "") $sqlMoji .= ", $this->Updater";
					$sqlMoji .= ")";

					$rtn = $this->myDB->executeQuery($sqlMoji);
					if (!$rtn){
						$this->Err = "追加処理に失敗しました";
						$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
						unset($myLog);
						return FALSE;
					}
					else{
						// step 5. コミット
						if (!($this->myDB->executeQuery("COMMIT"))) {
							$this->Err = "追加処理に失敗しました(COMMIT)";
							$this->myDB->executeQuery("ROLLBACK");
							return FALSE;
						}
						else {
							$this->AdminCD = $maxCD;
						}
					}
				}
			}
		} else {
			// アップデート
			$sqlMoji = "Update $this->TableName set ";
			$sqlMoji .= "AdminCD = $this->AdminCD";
			$sqlMoji .= ", ClientCD = " . $this->convertNullIntValue($this->ClientCD);
			$sqlMoji .= ", ID = " . $this->convertNullStringValue($this->ID);
			$sqlMoji .= ", Passwd = " . $this->convertNullStringValue($this->Passwd);
			$sqlMoji .= ", LastName = " . $this->convertNullStringValue($this->LastName);
			$sqlMoji .= ", FirstName = " . $this->convertNullStringValue($this->FirstName);
			$sqlMoji .= ", LastLogin = " . $this->convertNullStringValue($this->LastLogin);
			$sqlMoji .= ", RegistKey = " . $this->convertNullStringValue($this->RegistKey);
			$sqlMoji .= ", Notes = " . $this->convertNullStringValue($this->Notes);
			$sqlMoji .= ", MukouFlg = " . $this->convertFlgToString($this->MukouFlg);
			$sqlMoji .= ", Created = " . $this->convertNullStringValue($this->Created);
			$sqlMoji .= ", Creator = " . $this->convertNullIntValue($this->Creator);
			$sqlMoji .= ", Updated = NOW()";
			$sqlMoji .= ", Updater = " . $this->convertNullIntValue($this->Updater);
			$sqlMoji .= " where AdminCD = $this->AdminCD";

			$rtn = $this->myDB->executeQuery($sqlMoji);
			if (!$rtn){
				$this->Err = "更新処理に失敗しました";
				$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
				unset($myLog);
				return FALSE;
			}
		}

		return TRUE;
	}

	function convertFlgToString($flg){
		if ($flg == "true" || $flg == "t")
			return "true";
		else if ($flg == "false" || $flg == "f")
			return "false";

		if ($flg == true || $flg == 1)
			return "true";
		else if ($flg == false || $flg == 0)
			return "false";

		return "false";
	}

	function convertFlgToBoolean($flg){
		if ($flg == "t" || $flg == 1)
			return TRUE;
		else if ($flg == "f" || $flg == 0)
			return FALSE;
	}

	function convertNullIntValue($value){
		if ($value == "")
			return "NULL";
		else
			return $value;
	}

	function convertNullStringValue($value){
		if ($value == "")
			return "NULL";
		else
			return "'" . addslashes($value) . "'";
	}

	function doAuthenticationByID($ID, $Passwd){
		if (!$this->executeSelect("ID = '" . $this->myDB->escapeString($ID) . "' AND Passwd = '" . $this->myDB->escapeString($Passwd) . "' AND MukouFlg = FALSE", NULL)){
			$this->Err = "管理者認証動作に失敗しました";
			$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
			unset($myLog);
			return false;
		}

		if ($this->RecCnt == 0){
			$this->AdminCD = -1;
			return true;
		}

		// 最終ログイン日時をupdate
		$this->LastLogin = "now()";
		$this->RegistKey = $this->getNewKey();

		if (!$this->executeUpdate()){
			$this->Err = "管理者認証動作に失敗しました";
			$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
			unset($myLog);
			return false;
		}

		return true;
	}

	function doAuthentication(){
		global $PHP_AUTH_USER, $PHP_AUTH_PW;

		$PreLogin = $GLOBALS['_COOKIE']['AdminPreLogin'];

		if ($_POST['wAdminID'] != NULL && $_POST['wAdminPasswd'] != NULL) {
			$wAdminID = $_POST['wAdminID'];
			$wAdminPasswd = $_POST['wAdminPasswd'];
			setcookie('wAdminID', $wAdminID);
			setcookie('wAdminPasswd', $wAdminPasswd);
		}
		else if ($_COOKIE['wAdminID'] != NULL && $_COOKIE['wAdminPasswd'] != NULL) {
			$wAdminID = $_COOKIE['wAdminID'];
			$wAdminPasswd = $_COOKIE['wAdminPasswd'];
		}

		if ($wAdminID == NULL || $wAdminPasswd == NULL)
			return false;

		if (!$this->executeSelect("ID = '" . $this->myDB->escapeString($wAdminID) . "' AND Passwd = '" . $this->myDB->escapeString($wAdminPasswd) . "' AND MukouFlg = false","")){
			$this->Err = "管理者認証動作に失敗しました";
			$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
			unset($myLog);
			return false;
		}

		if ($this->RecCnt <> 1){
			// Header("WWW-authenticate: basic realm=\"Logon\"");
			return false;
		}

		// 最終ログイン日時をupdate
		if ($PreLogin == NULL) {
			setcookie('AdminPreLogin', $this->LastLogin);
			$GLOBALS['PreLogin'] = $this->LastLogin;

			$this->LastLogin = "now()";
			if (!$this->executeUpdate()){
				$this->Err = "管理者認証動作に失敗しました";
				$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
				unset($myLog);
				return false;
			}
		}
		else
			$GLOBALS['PreLogin'] = $PreLogin;

		$GLOBALS['PreLogin'] = substr($GLOBALS['PreLogin'], 0, 19);

		return true;
	}

	function doAuthenticationByRegistKey($rKey){
		// 認証動作
		if (!$this->executeSelect("RegistKey = '" . $this->myDB->escapeString($rKey) . "' AND MukouFlg = false","")){
			$this->Err = "認証動作に失敗しました";
			$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
			unset($myLog);
			return false;
		}

		// セッションタイムアウトチェック
		if ($this->LastLogin != NULL && $GLOBALS['Timeout'] > 0) {
			$LastLoginYear = substr($this->LastLogin, 0, 4);
			$LastLoginMonth = substr($this->LastLogin, 5, 2);
			$LastLoginDay = substr($this->LastLogin, 8, 2);
			$LastLoginHour = substr($this->LastLogin, 11, 2);
			$LastLoginMinute = substr($this->LastLogin, 14, 2);
			$LastLoginSecond = substr($this->LastLogin, 17, 2);
			$LastLogin = mktime($LastLoginHour, $LastLoginMinute, $LastLoginSecond, $LastLoginMonth, $LastLoginDay, $LastLoginYear);
			$Now = mktime();

			if ($Now - $LastLogin > $GLOBALS['Timeout'] * 60) {
				$sql = "UPDATE " . $this->TableName . " SET RegistKey = '" . $this->getNewKey() . "' WHERE AdminCD = " . $this->AdminCD;

				if (!($rtn = $this->myDB->executeQuery($sql))){
					$this->Err = "管理者情報更新処理に失敗しました。" . $this->myDB->Err;
					return FALSE;
				}

				$this->AdminCD = -1;
				return TRUE;
			}
		}

		if ($this->RecCnt <> 1)
			$this->AdminCD = -1;

		return true;
	}

	function getNameByAdminCD($AdminCD){
		if (!is_numeric($AdminCD))
			trigger_error('Illeagl Value Detected.');

		// 抽出動作
		if (!$this->executeSelect("AdminCD = " . $AdminCD, "")){
			$this->Err = "管理者抽出動作に失敗しました";
			$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
			unset($myLog);
			return false;
		}

		if ($this->RecCnt <> 1){
			return false;
		}

		$this->Name = $this->LastName . " " . $this->FirstName;

		return true;
	}

	function isExistByID($ID, $ClientCD = NULL, $OperatorCD = NULL){
		$sql = "SELECT COUNT(*) FROM " . $this->TableName . " WHERE ID = '" . $ID . "'";
		if ($ClientCD != NULL)
			$sql .= " AND ClientCD != " . $ClientCD . "";
		if ($OperatorCD != NULL)
			$sql .= " AND OperatorCD != " . $OperatorCD . "";

		$number = $this->myDB->getOneValue($sql);

		if ($number > 0)
			return TRUE;

		return FALSE;
	}

	function getNewKey() {
		// ログ準備
		$myLog = new SPFWLog();

		// エラークリア
		$this->Err = "";

		//設定
		if ($this->myDB == "") {
			$this->Err = "データベースへのコネクションがありません";
			$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
			unset($myLog);
			return FALSE;
		}

		// 候補文字配列を作る
		// 英小文字
		for ($i = ord("a"); $i < ord("z") + 1; $i++)
			$charArray[] = chr($i);
		// 英大文字
		for ($i = ord("A"); $i < ord("Z") + 1; $i++)
			$charArray[] = chr($i);
		// 数字
		for ($i = ord("0"); $i < ord("9") + 1; $i++)
			$charArray[] = chr($i);

		$FlgOK = false;
		while ($FlgOK == false){
			$cnt = 10;
			
			// ランダム文字列生成
			srand((double)microtime()*1000000);
			// $cnt 文字を小文字で生成
			for ($i = 0; $i < $cnt; $i++)
				$strW[$i] = $charArray[rand(0, count($charArray) - 1)];

			// シャッフル
			srand((double)microtime()*1000000);
			shuffle($strW);
	
			// 出力
			$strKey = "";
			for ($i = 0; $i < $cnt; $i++)
				$strKey .= $strW[$i];

			$SqlStr = "select count(*) from " . $this->TableName . " where RegistKey = '" . $this->myDB->escapeString($strKey) . "'";

			$FlgOK = ($this->myDB->getOneValue($SqlStr) == 0) ? true : false;
		}

		unset($myLog);

		return $strKey;
	}
}
?>
