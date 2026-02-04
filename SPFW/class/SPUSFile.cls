<?php
/**
 * Fileクラス
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

class File {
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

	var $FileCD;
	var $BukkenCD;
	var $ServerFileName;
	var $RealFileName;
	var $FileKind;
	var $P001;
	var $P002;
	var $P003;
	var $P004;
	var $File;
	var $Memo;

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

	function __construct($myDB = "",$FileCD = -1) {
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
		settype($FileCD,"integer");
		// 整数チェック
		if (!is_long($FileCD) or $FileCD == 0) {
			$this->Err = "ユーザーコード が不正です";
			$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
			unset($myLog);
			return FALSE;
		}

		$this->myDB = $myDB;
		$this->TableName = "tFileF";
		$this->FileCD = $FileCD;

		return TRUE;
	}

	function initialize(){
		$this->Err = "";

		$this->FileCD = "";
		$this->BukkenCD = "";
		$this->ServerFileName = "";
		$this->RealFileName = "";
		$this->FileKind = "";
		$this->P001 = "";
		$this->P002 = "";
		$this->P003 = "";
		$this->P004 = "";
		$this->File = "";
		$this->Memo = "";
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
		$sqlMoji .= "FileCD";
		$sqlMoji .= ", BukkenCD";
		$sqlMoji .= ", ServerFileName";
		$sqlMoji .= ", RealFileName";
		$sqlMoji .= ", FileKind";
		$sqlMoji .= ", P001";
		$sqlMoji .= ", P002";
		$sqlMoji .= ", P003";
		$sqlMoji .= ", P004";
		$sqlMoji .= ", File";
		$sqlMoji .= ", Memo";
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
				$this->FileCD,
				$this->BukkenCD,
				$this->ServerFileName,
				$this->RealFileName,
				$this->FileKind,
				$this->P001,
				$this->P002,
				$this->P003,
				$this->P004,
				$this->File,
				$this->Memo,
				$this->MukouFlg,
				$this->Created,
				$this->Creator,
				$this->Updated,
				$this->Updater
			) = $this->myDB->fetchRow($this->tmprtn,$RowNo);

			$this->Memo = stripslashes($this->Memo);
			$this->FileName = stripslashes($this->FileName);
			$this->File = stripslashes($this->File);
			$this->Memo = stripslashes($this->Memo);
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
		
		if ($this->FileCD < 0) {
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
					$sqlMoji = "select FileCD from $this->TableName order by FileCD desc limit 1";
					$maxCD = $this->myDB->getOneValue($sqlMoji);
					$maxCD = ($maxCD == -1) ? 0 : $maxCD;
					$maxCD++;

					// step 4. インサート





					$sqlMoji = "Insert into $this->TableName (";
					$sqlMoji .= "FileCD";
					if ($this->BukkenCD != "") $sqlMoji .= ", BukkenCD";
					if ($this->ServerFileName != "") $sqlMoji .= ", ServerFileName";
					if ($this->RealFileName != "") $sqlMoji .= ", RealFileName";
					if ($this->FileKind != "") $sqlMoji .= ", FileKind";
					if ($this->P001 != "") $sqlMoji .= ", P001";
					if ($this->P002 != "") $sqlMoji .= ", P002";
					if ($this->P003 != "") $sqlMoji .= ", P003";
					if ($this->P004 != "") $sqlMoji .= ", P004";
					if ($this->File != "") $sqlMoji .= ", File";
					if ($this->Memo != "") $sqlMoji .= ", Memo";
					if ($this->MukouFlg != "") $sqlMoji .= ", MukouFlg";
					$sqlMoji .= ", Created";
					if ($this->Creator != "") $sqlMoji .= ", Creator";
					$sqlMoji .= ", Updated";
					if ($this->Updater != "") $sqlMoji .= ", Updater";
					$sqlMoji .= ") values (";
					$sqlMoji .= "$maxCD";
					if ($this->BukkenCD != "") $sqlMoji .= ", '" . addslashes($this->BukkenCD) . "'";
					if ($this->ServerFileName != "") $sqlMoji .= ", '" . addslashes($this->ServerFileName) . "'";
					if ($this->RealFileName != "") $sqlMoji .= ", '" . addslashes($this->RealFileName) . "'";
					if ($this->FileKind != "") $sqlMoji .= ", '" . addslashes($this->FileKind) . "'";
					if ($this->P001 != "") $sqlMoji .= ", '" . addslashes($this->P001) . "'";
					if ($this->P002 != "") $sqlMoji .= ", '" . addslashes($this->P002) . "'";
					if ($this->P003 != "") $sqlMoji .= ", '" . addslashes($this->P003) . "'";
					if ($this->P004 != "") $sqlMoji .= ", '" . addslashes($this->P004) . "'";
					if ($this->File != "") $sqlMoji .= ", '" . addslashes($this->File) . "'";
					if ($this->Memo != "") $sqlMoji .= ", '" . addslashes($this->Memo) . "'";
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
							$this->FileCD = $maxCD;
						}
					}
				}
			}
		} else {
			// アップデート
			$sqlMoji = "Update $this->TableName set ";
			$sqlMoji .= "FileCD = $this->FileCD";

			$sqlMoji .= ", BukkenCD = " . $this->convertNullStringValue($this->BukkenCD);
			$sqlMoji .= ", ServerFileName = " . $this->convertNullStringValue($this->ServerFileName);
			$sqlMoji .= ", RealFileName = " . $this->convertNullStringValue($this->RealFileName);
			$sqlMoji .= ", FileKind = " . $this->convertNullStringValue($this->FileKind);
			$sqlMoji .= ", P001 = " . $this->convertNullStringValue($this->P001);
			$sqlMoji .= ", P002 = " . $this->convertNullStringValue($this->P002);
			$sqlMoji .= ", P003 = " . $this->convertNullStringValue($this->P003);
			$sqlMoji .= ", P004 = " . $this->convertNullStringValue($this->P004);
			$sqlMoji .= ", File = " . $this->convertNullStringValue($this->File);
			$sqlMoji .= ", Memo = " . $this->convertNullStringValue($this->Memo);

			$sqlMoji .= ", MukouFlg = " . $this->convertFlgToString($this->MukouFlg);
			$sqlMoji .= ", Created = " . $this->convertNullStringValue($this->Created);
			$sqlMoji .= ", Creator = " . $this->convertNullIntValue($this->Creator);
			$sqlMoji .= ", Updated = NOW()";
			$sqlMoji .= ", Updater = " . $this->convertNullIntValue($this->Updater);
			$sqlMoji .= " where FileCD = $this->FileCD";

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
