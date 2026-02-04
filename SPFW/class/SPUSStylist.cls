<?php
/**
 * Stylistクラス
 *
 * スタイリストマスタを扱うクラスです。
 *
 * @package 
 * @access  public
 * @author  MasahitoSAMEKAWA <masahito@souppot.jp>
 * @create  2003/01/31
 * @version 0.1
 **/

// 依存
include_once _CLS_DIR . "SPFWLog.cls";

class Stylist {
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
	 * スタイリストコード
	 * 
	 * 連番
	 */
	var $StylistCD;
	/**
	 * クライアントコード
	 */
	var $ClientCD;
	/**
	 * スタイリスト名称
	 */
	var $StylistName;
	/**
	 * 稼動ライン数
	 */
	var $NumberOfLines;
	/**
	 * 自由ライン数
	 */
	var $NumberOfFreeLines;
	/**
	 * 自由枠フラグ
	 * 
	 * 1:通常 2:自由枠
	 */
	var $FreeFlg;
	/**
	 * 勤務時間開始
	 * 
	 * 七曜分を|で区切る
	 */
	var $OpenTime;
	/**
	 * 勤務時間終了
	 * 
	 * 七曜分を|で区切る
	 */
	var $CloseTime;
	/**
	 * 昼休み開始
	 * 
	 * 七曜分を|で区切る
	 */
	var $LunchTimeFrom;
	/**
	 * 昼休み終了
	 * 
	 * 七曜分を|で区切る
	 */
	var $LunchTimeTo;
	/**
	 * 夕方休み開始
	 * 
	 * 七曜分を|で区切る
	 */
	var $EveningTimeFrom;
	/**
	 * 夕方休み終了
	 * 
	 * 七曜分を|で区切る
	 */
	var $EveningTimeTo;
	/**
	 * 休日設定
	 * 
	 * 七曜分を|で区切る
	 */
	var $Holiday;
	/**
	 * 対応可能メニューコード
	 * 
	 * 複数を|で区切る
	 */
	var $MenuCD;
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

	function __construct($myDB = "",$StylistCD = -1) {
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
		settype($StylistCD,"integer");
		// 整数チェック
		if (!is_long($StylistCD) or $StylistCD == 0) {
			$this->Err = "ユーザーコード が不正です";
			$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
			unset($myLog);
			return FALSE;
		}

		$this->myDB = $myDB;
		$this->TableName = "tStylistM";
		$this->StylistCD = $StylistCD;

		return TRUE;
	}

	function initialize(){
		$this->Err = "";

		$this->StylistCD = "";
		$this->ClientCD = "";
		$this->StylistName = "";
		$this->NumberOfLines = "";
		$this->NumberOfFreeLines = "";
		$this->FreeFlg = "";
		$this->OpenTime = "";
		$this->CloseTime = "";
		$this->LunchTimeFrom = "";
		$this->LunchTimeTo = "";
		$this->EveningTimeFrom = "";
		$this->EveningTimeTo = "";
		$this->Holiday = "";
		$this->MenuCD = "";
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
		$sqlMoji .= "StylistCD";
		$sqlMoji .= ", ClientCD";
		$sqlMoji .= ", StylistName";
		$sqlMoji .= ", NumberOfLines";
		$sqlMoji .= ", NumberOfFreeLines";
		$sqlMoji .= ", FreeFlg";
		$sqlMoji .= ", OpenTime";
		$sqlMoji .= ", CloseTime";
		$sqlMoji .= ", LunchTimeFrom";
		$sqlMoji .= ", LunchTimeTo";
		$sqlMoji .= ", EveningTimeFrom";
		$sqlMoji .= ", EveningTimeTo";
		$sqlMoji .= ", Holiday";
		$sqlMoji .= ", MenuCD";
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
				$this->StylistCD,
				$this->ClientCD,
				$this->StylistName,
				$this->NumberOfLines,
				$this->NumberOfFreeLines,
				$this->FreeFlg,
				$this->OpenTime,
				$this->CloseTime,
				$this->LunchTimeFrom,
				$this->LunchTimeTo,
				$this->EveningTimeFrom,
				$this->EveningTimeTo,
				$this->Holiday,
				$this->MenuCD,
				$this->Notes,
				$this->MukouFlg,
				$this->Created,
				$this->Creator,
				$this->Updated,
				$this->Updater
			) = $this->myDB->fetchRow($this->tmprtn,$RowNo);

			$this->StylistName = stripslashes($this->StylistName);
			$this->OpenTime = stripslashes($this->OpenTime);
			$this->CloseTime = stripslashes($this->CloseTime);
			$this->LunchTimeFrom = stripslashes($this->LunchTimeFrom);
			$this->LunchTimeTo = stripslashes($this->LunchTimeTo);
			$this->EveningTimeFrom = stripslashes($this->EveningTimeFrom);
			$this->EveningTimeTo = stripslashes($this->EveningTimeTo);
			$this->Holiday = stripslashes($this->Holiday);
			$this->MenuCD = stripslashes($this->MenuCD);
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
		
		if ($this->StylistCD < 0) {
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
					$sqlMoji = "select StylistCD from $this->TableName order by StylistCD desc limit 1";
					$maxCD = $this->myDB->getOneValue($sqlMoji);
					$maxCD = ($maxCD == -1) ? 0 : $maxCD;
					$maxCD++;

					// step 4. インサート
					$sqlMoji = "Insert into $this->TableName (";
					$sqlMoji .= "StylistCD";
					if ($this->ClientCD != "") $sqlMoji .= ", ClientCD";
					if ($this->StylistName != "") $sqlMoji .= ", StylistName";
					if ($this->NumberOfLines != "") $sqlMoji .= ", NumberOfLines";
					if ($this->NumberOfFreeLines != "") $sqlMoji .= ", NumberOfFreeLines";
					if ($this->FreeFlg != "") $sqlMoji .= ", FreeFlg";
					if ($this->OpenTime != "") $sqlMoji .= ", OpenTime";
					if ($this->CloseTime != "") $sqlMoji .= ", CloseTime";
					if ($this->LunchTimeFrom != "") $sqlMoji .= ", LunchTimeFrom";
					if ($this->LunchTimeTo != "") $sqlMoji .= ", LunchTimeTo";
					if ($this->EveningTimeFrom != "") $sqlMoji .= ", EveningTimeFrom";
					if ($this->EveningTimeTo != "") $sqlMoji .= ", EveningTimeTo";
					if ($this->Holiday != "") $sqlMoji .= ", Holiday";
					if ($this->MenuCD != "") $sqlMoji .= ", MenuCD";
					if ($this->Notes != "") $sqlMoji .= ", Notes";
					if ($this->MukouFlg != "") $sqlMoji .= ", MukouFlg";
					$sqlMoji .= ", Created";
					if ($this->Creator != "") $sqlMoji .= ", Creator";
					$sqlMoji .= ", Updated";
					if ($this->Updater != "") $sqlMoji .= ", Updater";
					$sqlMoji .= ") values (";
					$sqlMoji .= "$maxCD";
					if ($this->ClientCD != "") $sqlMoji .= ", $this->ClientCD";
					if ($this->StylistName != "") $sqlMoji .= ", '" . addslashes($this->StylistName) . "'";
					if ($this->NumberOfLines != "") $sqlMoji .= ", $this->NumberOfLines";
					if ($this->NumberOfFreeLines != "") $sqlMoji .= ", $this->NumberOfFreeLines";
					if ($this->FreeFlg != "") $sqlMoji .= ", $this->FreeFlg";
					if ($this->OpenTime != "") $sqlMoji .= ", '" . addslashes($this->OpenTime) . "'";
					if ($this->CloseTime != "") $sqlMoji .= ", '" . addslashes($this->CloseTime) . "'";
					if ($this->LunchTimeFrom != "") $sqlMoji .= ", '" . addslashes($this->LunchTimeFrom) . "'";
					if ($this->LunchTimeTo != "") $sqlMoji .= ", '" . addslashes($this->LunchTimeTo) . "'";
					if ($this->EveningTimeFrom != "") $sqlMoji .= ", '" . addslashes($this->EveningTimeFrom) . "'";
					if ($this->EveningTimeTo != "") $sqlMoji .= ", '" . addslashes($this->EveningTimeTo) . "'";
					if ($this->Holiday != "") $sqlMoji .= ", '" . addslashes($this->Holiday) . "'";
					if ($this->MenuCD != "") $sqlMoji .= ", '" . addslashes($this->MenuCD) . "'";
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
							$this->StylistCD = $maxCD;
						}
					}
				}
			}
		} else {
			// アップデート
			$sqlMoji = "Update $this->TableName set ";
			$sqlMoji .= "StylistCD = $this->StylistCD";
			$sqlMoji .= ", ClientCD = " . $this->convertNullIntValue($this->ClientCD);
			$sqlMoji .= ", StylistName = " . $this->convertNullStringValue($this->StylistName);
			$sqlMoji .= ", NumberOfLines = " . $this->convertNullIntValue($this->NumberOfLines);
			$sqlMoji .= ", NumberOfFreeLines = " . $this->convertNullIntValue($this->NumberOfFreeLines);
			$sqlMoji .= ", FreeFlg = " . $this->convertNullIntValue($this->FreeFlg);
			$sqlMoji .= ", OpenTime = " . $this->convertNullStringValue($this->OpenTime);
			$sqlMoji .= ", CloseTime = " . $this->convertNullStringValue($this->CloseTime);
			$sqlMoji .= ", LunchTimeFrom = " . $this->convertNullStringValue($this->LunchTimeFrom);
			$sqlMoji .= ", LunchTimeTo = " . $this->convertNullStringValue($this->LunchTimeTo);
			$sqlMoji .= ", EveningTimeFrom = " . $this->convertNullStringValue($this->EveningTimeFrom);
			$sqlMoji .= ", EveningTimeTo = " . $this->convertNullStringValue($this->EveningTimeTo);
			$sqlMoji .= ", Holiday = " . $this->convertNullStringValue($this->Holiday);
			$sqlMoji .= ", MenuCD = " . $this->convertNullStringValue($this->MenuCD);
			$sqlMoji .= ", Notes = " . $this->convertNullStringValue($this->Notes);
			$sqlMoji .= ", MukouFlg = " . $this->convertFlgToString($this->MukouFlg);
			$sqlMoji .= ", Created = " . $this->convertNullStringValue($this->Created);
			$sqlMoji .= ", Creator = " . $this->convertNullIntValue($this->Creator);
			$sqlMoji .= ", Updated = NOW()";
			$sqlMoji .= ", Updater = " . $this->convertNullIntValue($this->Updater);
			$sqlMoji .= " where StylistCD = $this->StylistCD";

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
