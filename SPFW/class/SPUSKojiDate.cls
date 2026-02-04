<?php
/**
 * Yobi6クラス
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

class KojiDate {
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
	 * メニューコード
	 * 
	 * 連番
	 */

	var $KojiDateCD;
	var $BukkenCD;
	var $BuildingCD;
	var $RoomID;
	var $RoomDate;
	var $Yobi1;
	var $Yobi2;
	var $Yobi3;
	var $Yobi4;
	var $Yobi5;
	var $Yobi6;
	var $Yobi7;

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

	function __construct($myDB = "",$KojiDateCD = -1) {
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
		settype($KojiDateCD,"integer");
		// 整数チェック
		if (!is_long($KojiDateCD) or $KojiDateCD == 0) {
			$this->Err = "ユーザーコード が不正です";
			$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
			unset($myLog);
			return FALSE;
		}

		$this->myDB = $myDB;
		$this->TableName = "tKojiDateF";
		$this->KojiDateCD = $KojiDateCD;

		return TRUE;
	}

	function initialize(){
		$this->Err = "";

		$this->KojiDateCD = "";
		$this->BukkenCD = "";
		$this->BuildingCD = "";
		$this->RoomID = "";
		$this->RoomDate = "";
		$this->Yobi1 = "";
		$this->Yobi2 = "";
		$this->Yobi3 = "";
		$this->Yobi4 = "";
		$this->Yobi5 = "";
		$this->Yobi6 = "";
		$this->Yobi7 = "";
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
		$sqlMoji .= "KojiDateCD";
		$sqlMoji .= ", BukkenCD";
		$sqlMoji .= ", BuildingCD";
		$sqlMoji .= ", RoomID";
		$sqlMoji .= ", RoomDate";
		$sqlMoji .= ", Yobi1";
		$sqlMoji .= ", Yobi2";
		$sqlMoji .= ", Yobi3";
		$sqlMoji .= ", Yobi4";
		$sqlMoji .= ", Yobi5";
		$sqlMoji .= ", Yobi6";
		$sqlMoji .= ", Yobi7";
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
				$this->KojiDateCD,
				$this->BukkenCD,
				$this->BuildingCD,
				$this->RoomID,
				$this->RoomDate,
				$this->Yobi1,
				$this->Yobi2,
				$this->Yobi3,
				$this->Yobi4,
				$this->Yobi5,
				$this->Yobi6,
				$this->Yobi7,
				$this->MukouFlg,
				$this->Created,
				$this->Creator,
				$this->Updated,
				$this->Updater
			) = $this->myDB->fetchRow($this->tmprtn,$RowNo);

			$this->RoomID = stripslashes($this->RoomID);
			$this->RoomDate = stripslashes($this->RoomDate);
			$this->Yobi1 = stripslashes($this->Yobi1);
			$this->Yobi2 = stripslashes($this->Yobi2);
			$this->Yobi3 = stripslashes($this->Yobi3);
			$this->Yobi4 = stripslashes($this->Yobi4);
			$this->Yobi5 = stripslashes($this->Yobi5);
			$this->Yobi6 = stripslashes($this->Yobi6);
			$this->Yobi7 = stripslashes($this->Yobi7);
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
		
		if ($this->KojiDateCD < 0) {
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
					$sqlMoji = "select KojiDateCD from $this->TableName order by KojiDateCD desc limit 1";
					$maxCD = $this->myDB->getOneValue($sqlMoji);
					$maxCD = ($maxCD == -1) ? 0 : $maxCD;
					$maxCD++;

					// step 4. インサート





					$sqlMoji = "Insert into $this->TableName (";
					$sqlMoji .= "KojiDateCD";
					if ($this->BukkenCD != "") $sqlMoji .= ", BukkenCD";
					if ($this->BuildingCD != "") $sqlMoji .= ", BuildingCD";
					if ($this->RoomID != "") $sqlMoji .= ", RoomID";
					if ($this->RoomDate != "") $sqlMoji .= ", RoomDate";
					if ($this->Yobi1 != "") $sqlMoji .= ", Yobi1";
					if ($this->Yobi2 != "") $sqlMoji .= ", Yobi2";
					if ($this->Yobi3 != "") $sqlMoji .= ", Yobi3";
					if ($this->Yobi4 != "") $sqlMoji .= ", Yobi4";
					if ($this->Yobi5 != "") $sqlMoji .= ", Yobi5";
					if ($this->Yobi6 != "") $sqlMoji .= ", Yobi6";
					if ($this->Yobi7 != "") $sqlMoji .= ", Yobi7";
					if ($this->MukouFlg != "") $sqlMoji .= ", MukouFlg";
					$sqlMoji .= ", Created";
					if ($this->Creator != "") $sqlMoji .= ", Creator";
					$sqlMoji .= ", Updated";
					if ($this->Updater != "") $sqlMoji .= ", Updater";
					$sqlMoji .= ") values (";
					$sqlMoji .= "$maxCD";
					if ($this->BukkenCD != "") $sqlMoji .= ", '" . addslashes($this->BukkenCD) . "'";
					if ($this->BuildingCD != "") $sqlMoji .= ", '" . addslashes($this->BuildingCD) . "'";
					if ($this->RoomID != "") $sqlMoji .= ", '" . addslashes($this->RoomID) . "'";
					if ($this->RoomDate != "") $sqlMoji .= ", '" . addslashes($this->RoomDate) . "'";
					if ($this->Yobi1 != "") $sqlMoji .= ", '" . addslashes($this->Yobi1) . "'";
					if ($this->Yobi2 != "") $sqlMoji .= ", '" . addslashes($this->Yobi2) . "'";
					if ($this->Yobi3 != "") $sqlMoji .= ", '" . addslashes($this->Yobi3) . "'";
					if ($this->Yobi4 != "") $sqlMoji .= ", '" . addslashes($this->Yobi4) . "'";
					if ($this->Yobi5 != "") $sqlMoji .= ", '" . addslashes($this->Yobi5) . "'";
					if ($this->Yobi6 != "") $sqlMoji .= ", '" . addslashes($this->Yobi6) . "'";
					if ($this->Yobi7 != "") $sqlMoji .= ", '" . addslashes($this->Yobi7) . "'";
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
							$this->KojiDateCD = $maxCD;
						}
					}
				}
			}
		} else {
			// アップデート
			$sqlMoji = "Update $this->TableName set ";
			$sqlMoji .= "KojiDateCD = $this->KojiDateCD";

			$sqlMoji .= ", BukkenCD = " . $this->convertNullStringValue($this->BukkenCD);
			$sqlMoji .= ", BuildingCD = " . $this->convertNullStringValue($this->BuildingCD);
			$sqlMoji .= ", RoomID = " . $this->convertNullStringValue($this->RoomID);
			$sqlMoji .= ", RoomDate = " . $this->convertNullStringValue($this->RoomDate);
			$sqlMoji .= ", Yobi1 = " . $this->convertNullStringValue($this->Yobi1);
			$sqlMoji .= ", Yobi2 = " . $this->convertNullStringValue($this->Yobi2);
			$sqlMoji .= ", Yobi3 = " . $this->convertNullStringValue($this->Yobi3);
			$sqlMoji .= ", Yobi4 = " . $this->convertNullStringValue($this->Yobi4);
			$sqlMoji .= ", Yobi5 = " . $this->convertNullStringValue($this->Yobi5);
			$sqlMoji .= ", Yobi6 = " . $this->convertNullStringValue($this->Yobi6);
			$sqlMoji .= ", Yobi7 = " . $this->convertNullStringValue($this->Yobi7);

			$sqlMoji .= ", MukouFlg = " . $this->convertFlgToString($this->MukouFlg);
			$sqlMoji .= ", Created = " . $this->convertNullStringValue($this->Created);
			$sqlMoji .= ", Creator = " . $this->convertNullIntValue($this->Creator);
			$sqlMoji .= ", Updated = NOW()";
			$sqlMoji .= ", Updater = " . $this->convertNullIntValue($this->Updater);
			$sqlMoji .= " where KojiDateCD = $this->KojiDateCD";

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
