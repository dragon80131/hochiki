<?php
/**
 * Clientクラス
 *
 * クライアントマスタを扱うクラスです。
 *
 * @package 
 * @access  public
 * @author  MasahitoSAMEKAWA <masahito@souppot.jp>
 * @create  2003/01/31
 * @version 0.1
 **/

// 依存
include_once _CLS_DIR . "SPFWLog.cls";

class KanriCompany {
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
	var $KanriCompanyCD;
	/**
	 * クライアントID
	 */
	var $ID;
	/**
	 * クライアント名称
	 */
	var $KanriCompanyName;
	/**
	 * メールアドレス
	 */
	var $ClientCD;
	/**
	 * 備考
	 */
	var $Notes;
	/**
	 * 管理会社名かな
	 */
	var $KanriCompanyNameKana;
	/**
	 * 電話番号
	 */
	var $CompanyTEL;



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

	function __construct($myDB = "",$KanriCompanyCD = -1) {
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
		settype($KanriCompanyCD,"integer");
		// 整数チェック
		if (!is_long($KanriCompanyCD) or $KanriCompanyCD == 0) {
			$this->Err = "ユーザーコード が不正です";
			$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
			unset($myLog);
			return FALSE;
		}

		$this->myDB = $myDB;
		$this->TableName = "tKanriCompanyM";
		$this->KanriCompanyCD = $KanriCompanyCD;

		return TRUE;
	}

	function initialize(){
		$this->Err = "";

		$this->KanriCompanyCD = "";
		$this->ID = "";
		$this->KanriCompanyName = "";
		$this->ClientCD = "";
		$this->Notes = "";
		$this->KanriCompanyNameKana = "";
		$this->CompanyTEL = "";
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
		$sqlMoji .= "KanriCompanyCD";
		$sqlMoji .= ", ID";
		$sqlMoji .= ", KanriCompanyName";
		$sqlMoji .= ", ClientCD";
		$sqlMoji .= ", Notes";
		$sqlMoji .= ", KanriCompanyNameKana";
		$sqlMoji .= ", CompanyTEL";
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
				$this->KanriCompanyCD,
				$this->ID,
				$this->KanriCompanyName,
				$this->ClientCD,
				$this->Notes,
				$this->KanriCompanyNameKana,
				$this->CompanyTEL,
				$this->MukouFlg,
				$this->Created,
				$this->Creator,
				$this->Updated,
				$this->Updater
			) = $this->myDB->fetchRow($this->tmprtn,$RowNo);

			$this->ID = stripslashes($this->ID);
			$this->KanriCompanyName = stripslashes($this->KanriCompanyName);
			$this->ClientCD = stripslashes($this->ClientCD);
			$this->Notes = stripslashes($this->Notes);
			$this->KanriCompanyNameKana = stripslashes($this->KanriCompanyNameKana);
			$this->CompanyTEL = stripslashes($this->CompanyTEL);
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
		
		if ($this->KanriCompanyCD < 0) {
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
					$sqlMoji = "select KanriCompanyCD from $this->TableName order by KanriCompanyCD desc limit 1";
					$maxCD = $this->myDB->getOneValue($sqlMoji);
					$maxCD = ($maxCD == -1) ? 0 : $maxCD;
					$maxCD++;

					// step 4. インサート
					$sqlMoji = "Insert into $this->TableName (";
					$sqlMoji .= "KanriCompanyCD";
					if ($this->ID != "") $sqlMoji .= ", ID";
					if ($this->KanriCompanyName != "") $sqlMoji .= ", KanriCompanyName";
					if ($this->ClientCD != "") $sqlMoji .= ", ClientCD";
					if ($this->Notes != "") $sqlMoji .= ", Notes";
					if ($this->KanriCompanyNameKana != "") $sqlMoji .= ", KanriCompanyNameKana";
					if ($this->CompanyTEL != "") $sqlMoji .= ", CompanyTEL";
					if ($this->MukouFlg != "") $sqlMoji .= ", MukouFlg";
					$sqlMoji .= ", Created";
					if ($this->Creator != "") $sqlMoji .= ", Creator";
					$sqlMoji .= ", Updated";
					if ($this->Updater != "") $sqlMoji .= ", Updater";
					$sqlMoji .= ") values (";
					$sqlMoji .= "$maxCD";
					if ($this->ID != "") $sqlMoji .= ", '" . addslashes($this->ID) . "'";
					if ($this->KanriCompanyName != "") $sqlMoji .= ", '" . addslashes($this->KanriCompanyName) . "'";
					if ($this->ClientCD != "") $sqlMoji .= ", '" . addslashes($this->ClientCD) . "'";
					if ($this->Notes != "") $sqlMoji .= ", '" . addslashes($this->Notes) . "'";
					if ($this->KanriCompanyNameKana != "") $sqlMoji .= ", '" . addslashes($this->KanriCompanyNameKana) . "'";
					if ($this->CompanyTEL != "") $sqlMoji .= ", '" . addslashes($this->CompanyTEL) . "'";
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
							$this->KanriCompanyCD = $maxCD;
						}
					}
				}
			}
		} else {
			// アップデート
			$sqlMoji = "Update $this->TableName set ";
			$sqlMoji .= "KanriCompanyCD = $this->KanriCompanyCD";
			$sqlMoji .= ", ID = " . $this->convertNullStringValue($this->ID);
			$sqlMoji .= ", KanriCompanyName = " . $this->convertNullStringValue($this->KanriCompanyName);
			$sqlMoji .= ", ClientCD = " . $this->convertNullStringValue($this->ClientCD);
			$sqlMoji .= ", Notes = " . $this->convertNullStringValue($this->Notes);
			$sqlMoji .= ", KanriCompanyNameKana = " . $this->convertNullStringValue($this->KanriCompanyNameKana);
			$sqlMoji .= ", CompanyTEL = " . $this->convertNullStringValue($this->CompanyTEL);
			$sqlMoji .= ", MukouFlg = " . $this->convertFlgToString($this->MukouFlg);
			$sqlMoji .= ", Created = " . $this->convertNullStringValue($this->Created);
			$sqlMoji .= ", Creator = " . $this->convertNullIntValue($this->Creator);
			$sqlMoji .= ", Updated = NOW()";
			$sqlMoji .= ", Updater = " . $this->convertNullIntValue($this->Updater);
			$sqlMoji .= " where KanriCompanyCD = $this->KanriCompanyCD";

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

	function createSite($ID){
		if ($ID == NULL)
			return FALSE;

		$MyDir = _DOCUMENT_ROOT . $ID;
		if (file_exists($MyDir))
			return FALSE;

		$Command = 'mkdir ' . $MyDir . ' 2>&1';
		if (($this->Err = shell_exec($Command)) != NULL)
			return FALSE;

		$Command = 'chmod 777 ' . $MyDir . ' 2>&1';
		if (($this->Err = shell_exec($Command)) != NULL)
			return FALSE;

		$Command = 'ln -s ' . _DOCUMENT_ROOT . '*.php ' . $MyDir . '/ 2>&1';
		if (($this->Err = shell_exec($Command)) != NULL)
			return FALSE;

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
