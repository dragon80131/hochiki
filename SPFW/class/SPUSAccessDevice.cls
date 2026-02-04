<?php
/**
 * AccessDeviceクラス
 *
 * メニューマスタを扱うクラスです。
 *
 * @package 
 * @access  public
 * @author  MasahitoSAMEKAWA <masahito@souppot.jp>
 * @create  2003/01/31
 * @version 0.1
 **/

// 依存
include_once _CLS_DIR . "SPFWLog.cls";

class AccessDevice {
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
	 * 連番
	 */
	var $AccessDeviceIDCD;
	var $DeviceID;
	var $LastName;
	var $UserCD;
	var $ShortName;
	var $Notes;
	var $AppVer;
	var $MukouFlg;
	var $Created;
	var $Creator;
	var $Updated;
	var $Updater;

	function AccessDevice($myDB = "",$AccessDeviceIDCD = -1) {
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
		settype($AccessDeviceIDCD,"integer");
		// 整数チェック
		if (!is_long($AccessDeviceIDCD) or $AccessDeviceIDCD == 0) {
			$this->Err = "ユーザーコード が不正です";
			$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
			unset($myLog);
			return FALSE;
		}

		$this->myDB = $myDB;
		$this->TableName = "tAccessDeviceM";
		$this->AccessDeviceIDCD = $AccessDeviceIDCD;

		return TRUE;
	}

	function initialize(){
		$this->Err = "";

		$this->AccessDeviceIDCD = "";
		$this->DeviceID = "";
		$this->LastName = "";
		$this->UserCD = "";
		$this->ShortName = "";
		$this->Notes = "";
		$this->AppVer = "";
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
		$sqlMoji .= "AccessDeviceIDCD";
		$sqlMoji .= ", DeviceID";
		$sqlMoji .= ", LastName";
		$sqlMoji .= ", UserCD";
		$sqlMoji .= ", ShortName";
		$sqlMoji .= ", Notes";
		$sqlMoji .= ", AppVer";
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
				$this->AccessDeviceIDCD,
				$this->DeviceID,
				$this->LastName,
				$this->UserCD,
				$this->ShortName,
				$this->Notes,
				$this->AppVer,
				$this->MukouFlg,
				$this->Created,
				$this->Creator,
				$this->Updated,
				$this->Updater
			) = $this->myDB->fetchRow($this->tmprtn,$RowNo);

			$this->DeviceID = stripslashes($this->DeviceID);
			$this->LastName = stripslashes($this->LastName);
			$this->ShortName = stripslashes($this->ShortName);
			$this->Notes = stripslashes($this->Notes);
			$this->AppVer = stripslashes($this->AppVer);
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
		
		if ($this->AccessDeviceIDCD < 0) {
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
					$sqlMoji = "select AccessDeviceIDCD from $this->TableName order by AccessDeviceIDCD desc limit 1";
					$maxCD = $this->myDB->getOneValue($sqlMoji);
					$maxCD = ($maxCD == -1) ? 0 : $maxCD;
					$maxCD++;

					// step 4. インサート
					$sqlMoji = "Insert into $this->TableName (";
					$sqlMoji .= "AccessDeviceIDCD";
					if ($this->DeviceID != "") $sqlMoji .= ", DeviceID";
					if ($this->LastName != "") $sqlMoji .= ", LastName";
					if ($this->UserCD != "") $sqlMoji .= ", UserCD";
					if ($this->ShortName != "") $sqlMoji .= ", ShortName";
					if ($this->Notes != "") $sqlMoji .= ", Notes";
					if ($this->AppVer != "") $sqlMoji .= ", AppVer";
					if ($this->MukouFlg != "") $sqlMoji .= ", MukouFlg";
					$sqlMoji .= ", Created";
					if ($this->Creator != "") $sqlMoji .= ", Creator";
					$sqlMoji .= ", Updated";
					if ($this->Updater != "") $sqlMoji .= ", Updater";
					$sqlMoji .= ") values (";
					$sqlMoji .= "$maxCD";
					if ($this->DeviceID != "") $sqlMoji .= ", '" . addslashes($this->DeviceID) . "'";
					if ($this->LastName != "") $sqlMoji .= ", '" . addslashes($this->LastName) . "'";
					if ($this->UserCD != "") $sqlMoji .= ", '" . $this->UserCD . "'";
					if ($this->ShortName != "") $sqlMoji .= ", '" . addslashes($this->ShortName) . "'";
					if ($this->Notes != "") $sqlMoji .= ", '" . addslashes($this->Notes) . "'";
					if ($this->AppVer != "") $sqlMoji .= ", '" . addslashes($this->AppVer) . "'";
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
							$this->AccessDeviceIDCD = $maxCD;
						}
					}
				}
			}
		} else {
			// アップデート
			$sqlMoji = "Update $this->TableName set ";
			$sqlMoji .= "AccessDeviceIDCD = $this->AccessDeviceIDCD";
			$sqlMoji .= ", DeviceID = " . $this->convertNullStringValue($this->DeviceID);
			$sqlMoji .= ", LastName = " . $this->convertNullStringValue($this->LastName);
			$sqlMoji .= ", UserCD = " . $this->convertNullIntValue($this->UserCD);
			$sqlMoji .= ", ShortName = " . $this->convertNullStringValue($this->ShortName);
			$sqlMoji .= ", Notes = " . $this->convertNullStringValue($this->Notes);
			$sqlMoji .= ", AppVer = " . $this->convertNullStringValue($this->AppVer);
			$sqlMoji .= ", MukouFlg = " . $this->convertFlgToString($this->MukouFlg);
			$sqlMoji .= ", Created = " . $this->convertNullStringValue($this->Created);
			$sqlMoji .= ", Creator = " . $this->convertNullIntValue($this->Creator);
			$sqlMoji .= ", Updated = NOW()";
			$sqlMoji .= ", Updater = " . $this->convertNullIntValue($this->Updater);
			$sqlMoji .= " where AccessDeviceIDCD = $this->AccessDeviceIDCD";

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
		if ($flg == "t")
			return TRUE;
		else if ($flg == "f")
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
}
?>
