<?php


include_once _CLS_DIR . "SPFWLog.cls";

class Client {
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
	 * クライアントコード
	 * 
	 * 連番
	 */
	var $ClientCD;
	/**
	 * クライアントID
	 */
	var $ID;
	var $Passwd;
	/**
	 * クライアント名称
	 */
	var $ClientName;
	var $MansionName;
	/**
	 * メールアドレス
	 */
	var $Yomikata;
	var $SekoSyozokuCD;
	/**
	 * 備考
	 */
	var $TantoMemo;
	var $YoteiDLFlg;
	var $KetteiDLFlg;
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

	function __construct($myDB = "",$ClientCD = -1) {
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
		settype($ClientCD,"integer");
		// 整数チェック
		if (!is_long($ClientCD) or $ClientCD == 0) {
			$this->Err = "ユーザーコード が不正です";
			$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
			unset($myLog);
			return FALSE;
		}

		$this->myDB = $myDB;
		$this->TableName = "tClientM";
		$this->ClientCD = $ClientCD;

		return TRUE;
	}

	function initialize(){
		$this->Err = "";

		$this->ClientCD = "";
		$this->ID = "";
		$this->Passwd = "";
		$this->ClientName = "";
		$this->MansionName = "";
		$this->Yomikata = "";
		$this->SekoSyozokuCD = "";
		$this->TantoMemo = "";
		$this->YoteiDLFlg = "";
		$this->KetteiDLFlg = "";
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
		$sqlMoji .= "ClientCD";
		$sqlMoji .= ", ID";
		$sqlMoji .= ", Passwd";
		$sqlMoji .= ", ClientName";
		$sqlMoji .= ", MansionName";
		$sqlMoji .= ", Yomikata";
		$sqlMoji .= ", SekoSyozokuCD";
		$sqlMoji .= ", TantoMemo";
		$sqlMoji .= ", YoteiDLFlg";
		$sqlMoji .= ", KetteiDLFlg";
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
				$this->ClientCD,
				$this->ID,
				$this->Passwd,
				$this->ClientName,
				$this->MansionName,
				$this->Yomikata,
				$this->SekoSyozokuCD,
				$this->TantoMemo,
				$this->YoteiDLFlg,
				$this->KetteiDLFlg,
				$this->MukouFlg,
				$this->Created,
				$this->Creator,
				$this->Updated,
				$this->Updater
			) = $this->myDB->fetchRow($this->tmprtn,$RowNo);

			$this->ID = stripslashes($this->ID);
			$this->Passwd = stripslashes($this->Passwd);
			$this->ClientName = stripslashes($this->ClientName);
			$this->MansionName = stripslashes($this->MansionName);
			$this->Yomikata = stripslashes($this->Yomikata);
			$this->SekoSyozokuCD = stripslashes($this->SekoSyozokuCD);
			$this->TantoMemo = stripslashes($this->TantoMemo);
			$this->YoteiDLFlg = stripslashes($this->YoteiDLFlg);
			$this->KetteiDLFlg = stripslashes($this->KetteiDLFlg);
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
		
		if ($this->ClientCD < 0) {
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
					$sqlMoji = "select ClientCD from $this->TableName order by ClientCD desc limit 1";
					$maxCD = $this->myDB->getOneValue($sqlMoji);
					$maxCD = ($maxCD == -1) ? 0 : $maxCD;
					$maxCD++;

					// step 4. インサート
					$sqlMoji = "Insert into $this->TableName (";
					$sqlMoji .= "ClientCD";
					if ($this->ID != "") $sqlMoji .= ", ID";
					if ($this->Passwd != "") $sqlMoji .= ", Passwd";
					if ($this->ClientName != "") $sqlMoji .= ", ClientName";
					if ($this->MansionName != "") $sqlMoji .= ", MansionName";
					if ($this->Yomikata != "") $sqlMoji .= ", Yomikata";
					if ($this->SekoSyozokuCD != "") $sqlMoji .= ", SekoSyozokuCD";
					if ($this->TantoMemo != "") $sqlMoji .= ", TantoMemo";
					if ($this->YoteiDLFlg != "") $sqlMoji .= ", YoteiDLFlg";
					if ($this->KetteiDLFlg != "") $sqlMoji .= ", KetteiDLFlg";
					if ($this->MukouFlg != "") $sqlMoji .= ", MukouFlg";
					$sqlMoji .= ", Created";
					if ($this->Creator != "") $sqlMoji .= ", Creator";
					$sqlMoji .= ", Updated";
					if ($this->Updater != "") $sqlMoji .= ", Updater";
					$sqlMoji .= ") values (";
					$sqlMoji .= "$maxCD";
					if ($this->ID != "") $sqlMoji .= ", '" . addslashes($this->ID) . "'";
					if ($this->Passwd != "") $sqlMoji .= ", '" . addslashes($this->Passwd) . "'";
					if ($this->ClientName != "") $sqlMoji .= ", '" . addslashes($this->ClientName) . "'";
					if ($this->MansionName != "") $sqlMoji .= ", '" . addslashes($this->MansionName) . "'";
					if ($this->Yomikata != "") $sqlMoji .= ", '" . addslashes($this->Yomikata) . "'";
					if ($this->SekoSyozokuCD != "") $sqlMoji .= ", '" . addslashes($this->SekoSyozokuCD) . "'";
					if ($this->TantoMemo != "") $sqlMoji .= ", '" . addslashes($this->TantoMemo) . "'";
					if ($this->YoteiDLFlg != "") $sqlMoji .= ", '" . addslashes($this->YoteiDLFlg) . "'";
					if ($this->KetteiDLFlg != "") $sqlMoji .= ", '" . addslashes($this->KetteiDLFlg) . "'";
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
							$this->ClientCD = $maxCD;
						}
					}
				}
			}
		} else {
			// アップデート
			$sqlMoji = "Update $this->TableName set ";
			$sqlMoji .= "ClientCD = $this->ClientCD";
			$sqlMoji .= ", ID = " . $this->convertNullStringValue($this->ID);
			$sqlMoji .= ", Passwd = " . $this->convertNullStringValue($this->Passwd);
			$sqlMoji .= ", ClientName = " . $this->convertNullStringValue($this->ClientName);
			$sqlMoji .= ", MansionName = " . $this->convertNullStringValue($this->MansionName);
			$sqlMoji .= ", Yomikata = " . $this->convertNullStringValue($this->Yomikata);
			$sqlMoji .= ", SekoSyozokuCD = " . $this->convertNullStringValue($this->SekoSyozokuCD);
			$sqlMoji .= ", TantoMemo = " . $this->convertNullStringValue($this->TantoMemo);
			$sqlMoji .= ", YoteiDLFlg = " . $this->convertNullStringValue($this->YoteiDLFlg);
			$sqlMoji .= ", KetteiDLFlg = " . $this->convertNullStringValue($this->KetteiDLFlg);
			$sqlMoji .= ", MukouFlg = " . $this->convertFlgToString($this->MukouFlg);
			$sqlMoji .= ", Created = " . $this->convertNullStringValue($this->Created);
			$sqlMoji .= ", Creator = " . $this->convertNullIntValue($this->Creator);
			$sqlMoji .= ", Updated = NOW()";
			$sqlMoji .= ", Updater = " . $this->convertNullIntValue($this->Updater);
			$sqlMoji .= " where ClientCD = $this->ClientCD";

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

	function setTransaction($lockLevel = "EXCLUSIVE"){
		// ログ準備
		$myLog = new SPFWLog();

		$lockString = "LOCK TABLE " . $this->TableName . " IN SHARE ROW " . $lockLevel . " MODE";
		$rtn = $this->myDB->setTransaction($lockString);
		if (!$rtn){
			$this->Err = $this->myDB->Err;
			$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
			unset($myLog);
			return FALSE;
		}
		return TRUE;
	}

	function rollbackTransaction(){
		// ログ準備
		$myLog = new SPFWLog();

		$rtn = $this->myDB->rollbackTransaction();
		if (!$rtn){
			$this->Err = $this->myDB->Err;
			$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
			unset($myLog);
			return FALSE;
		}
		return TRUE;
	}

	function commitTransaction(){
		// ログ準備
		$myLog = new SPFWLog();

		$rtn = $this->myDB->commitTransaction();
		if (!$rtn){
			$this->Err = $this->myDB->Err;
			$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
			unset($myLog);
			return FALSE;
		}
		return TRUE;
	}

static	function createSite($ID){
		if ($ID == NULL)
			return FALSE;
echo "<br>クラス内406行目:".$ID;
		$MyDir = _DOCUMENT_ROOT . $ID;
		if (file_exists($MyDir))
			return FALSE;

		$Command = 'mkdir ' . $MyDir . ' 2>&1';
		if (($this->Err = shell_exec($Command)) != NULL)
			return FALSE;

		$Command = 'chmod 777 ' . $MyDir . ' 2>&1';
		if (($this->Err = shell_exec($Command)) != NULL)
			return FALSE;
echo "<br>クラス内418行目:";
		$Command = 'ln -s ' . _DOCUMENT_ROOT . '*.php ' . $MyDir . '/ 2>&1';
		if (($this->Err = shell_exec($Command)) != NULL)
			return FALSE;
echo "<br>クラス内422行目:";
		$MyDir = _DOCUMENT_ROOT . $ID . '/upfile';
		if (file_exists($MyDir))
			return FALSE;

		$Command = 'mkdir ' . $MyDir . ' 2>&1';
		if (($this->Err = shell_exec($Command)) != NULL)
			return FALSE;

		$Command = 'chmod 777 ' . $MyDir . ' 2>&1';
		if (($this->Err = shell_exec($Command)) != NULL)
			return FALSE;

		return TRUE;

	}

	function destroySite($ID){
		if ($ID == NULL)
			return FALSE;

		$MyDir = _DOCUMENT_ROOT . $ID;
		if (!file_exists($MyDir))
			return FALSE;

		$Command = 'rm -Rf ' . $MyDir . ' 2>&1';
		if (($this->Err = shell_exec($Command)) != NULL)
			return FALSE;

		return TRUE;
	}
}
?>
