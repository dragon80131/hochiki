<?php
/**
 * Siteクラス
 *
 * 受付マスタを扱うクラスです。
 *
 * @package 
 * @access  public
 * @author  MasahitoSAMEKAWA <masahito@souppot.jp>
 * @create  2003/01/31
 * @version 0.1
 **/

// 依存
include_once _CLS_DIR . "SPFWLog.cls";
include_once _CLS_DIR . "SPFWDatabase_MYSQL.cls";

class Site {
	/**
	 * データベースクラスのインスタンス
	 */
	var $myDB2;
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
	 * 予約コード
	 * 
	 * 連番
	 */
	var $SiteNo;
	var $SiteName;
	var $StartDate;
	var $EndDate;
	var $S001;
	var $S002;
	var $S003;
	var $S004;
	var $S005;
	var $S006;
	var $Created;
	var $Updated;





	function __construct($myDB2 = "",$Site = -1) {
		// ログ準備
		$myLog = new SPFWLog();

		$this->Err = "";
		
		if ($myDB2 == "") {
			$this->Err = "データベースへのコネクションがありません";
			$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
			unset($myLog);
			return FALSE;
		}

		// 整数変換
		settype($SiteNo,"integer");
		// 整数チェック
#		if (!is_long($SiteNo) or $SiteNo == 0) {
		if (!is_long($SiteNo)) {
			$this->Err = "ユーザーコード が不正です".$SiteNo ;
			$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
			unset($myLog);
			return FALSE;
		}

		$this->myDB2 = $myDB2;
		$this->TableName = "tSiteM";
		$this->SiteNo = $SiteNo;

		return TRUE;
	}

	function initialize(){
		$this->Err = "";

		$this->SiteNo = "";
		$this->SiteName = "";
		$this->StartDate = "";
		$this->EndDate = "";
		$this->S001 = "";
		$this->S002 = "";
		$this->S003 = "";
		$this->S004 = "";
		$this->S005 = "";
		$this->S006 = "";
		$this->Created = "";
		$this->Updated = "";

	}

	//抽出
	function executeSelect($WhereStr = "", $OrderByStr = ""){
		// ログ準備
		$myLog = new SPFWLog();

		$this->Err = "";

		//設定
		if ($this->myDB2 == "") {
			$this->Err = "データベースへのコネクションがありません";
			$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
			unset($myLog);
			return FALSE;
		}
		
		// ページプロパティ取得
		$sqlMoji = "select ";
		$sqlMoji .= "SiteNo";
		$sqlMoji .= ", SiteName";
		$sqlMoji .= ", StartDate";
		$sqlMoji .= ", EndDate";
		$sqlMoji .= ", S001";
		$sqlMoji .= ", S002";
		$sqlMoji .= ", S003";
		$sqlMoji .= ", S004";
		$sqlMoji .= ", S005";
		$sqlMoji .= ", S006";
		$sqlMoji .= ", Created";
		$sqlMoji .= ", Updated";
		$sqlMoji .= " from $this->TableName";

		if($WhereStr != "")
			$sqlMoji = $sqlMoji . " where " . $WhereStr;

		if($OrderByStr != "")
			$sqlMoji = $sqlMoji . " order by " . $OrderByStr;

		$rtn = $this->myDB2->executeQuery($sqlMoji);
		if (!$rtn){
			$this->Err = "レコード取得に失敗しました";
			$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
			unset($myLog);
			return FALSE;
		}
		else{
			$this->RecCnt = $this->myDB2->getNumberOfRows($rtn);
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
				$this->SiteNo,
				$this->SiteName,
				$this->StartDate,
				$this->EndDate,
				$this->S001,
				$this->S002,
				$this->S003,
				$this->S004,
				$this->S005,
				$this->S006,
				$this->Created,
				$this->Updated
			) = $this->myDB2->fetchRow($this->tmprtn,$RowNo);

		}
	}

	/* アップデート */
	function executeUpdate() {
		// ログ準備
		$myLog = new SPFWLog();

		// エラークリア
		$this->Err = "";

		//設定
		if ($this->myDB2 == "") {
			$this->Err = "データベースへのコネクションがありません";
			$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
			unset($myLog);
			return FALSE;
		}
		
		if ($this->SiteNo < 0) {
			// 新規
			// step 1. 新規番号取得のためトランザクション開始
			if (!($this->myDB2->executeQuery("BEGIN"))) {
				$this->Err = "追加処理に失敗しました(BEGIN)";
				$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
				unset($myLog);
				return FALSE;
			} else {
				// step 2. ロック
				if (!($this->myDB2->lockTable($this->TableName))) {
					$this->Err = "追加処理に失敗しました(LOCK)";
					$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
					$this->myDB2->executeQuery("ROLLBACK");
					unset($myLog);
					return FALSE;
				} else {
					// step 3. 取得
					$sqlMoji = "select SiteNo from $this->TableName order by SiteNo desc limit 1";
					$maxCD = $this->myDB2->getOneValue($sqlMoji);
					$maxCD = ($maxCD == -1) ? 0 : $maxCD;
					$maxCD++;

					// step 4. インサート
					$sqlMoji = "Insert into $this->TableName (";

					$sqlMoji .= "SiteNo";
					if ($this->SiteName != "") $sqlMoji .= ", SiteName";
					if ($this->StartDate != "") $sqlMoji .= ", StartDate";
					if ($this->EndDate != "") $sqlMoji .= ", EndDate";
					if ($this->S001 != "") $sqlMoji .= ", S001";
					if ($this->S002 != "") $sqlMoji .= ", S002";
					if ($this->S003 != "") $sqlMoji .= ", S003";
					if ($this->S004 != "") $sqlMoji .= ", S004";
					if ($this->S005 != "") $sqlMoji .= ", S005";
					if ($this->S006 != "") $sqlMoji .= ", S006";
					$sqlMoji .= ", Created";
					$sqlMoji .= ", Updated";
					$sqlMoji .= ") values (";

					$sqlMoji .= "$maxCD";
					if ($this->SiteName != "") $sqlMoji .= ", '" . addslashes($this->SiteName) . "'";
					if ($this->StartDate != "")    $sqlMoji .= ", '" . addslashes($this->StartDate) . "'";
					if ($this->EndDate != "")    $sqlMoji .= ", '" . addslashes($this->EndDate) . "'";
					if ($this->S001 != "") $sqlMoji .= ", '" . addslashes($this->S001) . "'";
					if ($this->S002 != "") $sqlMoji .= ", '" . addslashes($this->S002) . "'";
					if ($this->S003 != "") $sqlMoji .= ", '" . addslashes($this->S003) . "'";
					if ($this->S004 != "") $sqlMoji .= ", '" . addslashes($this->S004) . "'";
					if ($this->S005 != "") $sqlMoji .= ", '" . addslashes($this->S005) . "'";
					if ($this->S006 != "") $sqlMoji .= ", '" . addslashes($this->S006) . "'";
					$sqlMoji .= ", NOW()";
					$sqlMoji .= ", NOW()";
					$sqlMoji .= ")";
					$rtn = $this->myDB2->executeQuery($sqlMoji);
					if (!$rtn){
						$this->Err = "追加処理に失敗しました";
						$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
						unset($myLog);
						return FALSE;
					}
					else{
						// step 5. コミット
						if (!($this->myDB2->executeQuery("COMMIT"))) {
							$this->Err = "追加処理に失敗しました(COMMIT)";
							$this->myDB2->executeQuery("ROLLBACK");
							return FALSE;
						}
						else {
							$this->SiteNo = $maxCD;
						}
					}
				}
			}

		} else {
###Update
			// アップデート
			$sqlMoji = "Update $this->TableName set ";
			$sqlMoji .= "SiteNo = $this->SiteNo";
			$sqlMoji .= ", SiteName = " . $this->convertNullStringValue($this->SiteName);
			$sqlMoji .= ", StartDate = " . $this->convertNullStringValue($this->StartDate);
			$sqlMoji .= ", EndDate = " . $this->convertNullStringValue($this->EndDate);
			$sqlMoji .= ", S001 = " . $this->convertNullStringValue($this->S001);
			$sqlMoji .= ", S002 = " . $this->convertNullStringValue($this->S002);
			$sqlMoji .= ", S003 = " . $this->convertNullStringValue($this->S003);
			$sqlMoji .= ", S004 = " . $this->convertNullStringValue($this->S004);
			$sqlMoji .= ", S005 = " . $this->convertNullStringValue($this->S005);
			$sqlMoji .= ", S006 = " . $this->convertNullStringValue($this->S006);
			$sqlMoji .= ", Updated = NOW()";
			$sqlMoji .= " where SiteNo = $this->SiteNo";

			$rtn = $this->myDB2->executeQuery($sqlMoji);
			if (!$rtn){
				$this->Err = "更新処理に失敗しました";
				$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
				unset($myLog);
				return FALSE;
			}
		} #ElseのEnd


		return TRUE;
	}


	/* アップデート */
	function executeUpdate3($where) {
		// ログ準備
		$myLog = new SPFWLog();

		// エラークリア
		$this->Err = "";

		//設定
		if ($this->myDB2 == "") {
			$this->Err = "データベースへのコネクションがありません";
			$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
			unset($myLog);
			return FALSE;
		}
		

				// step 2. ロック
				if (!($this->myDB2->lockTable($this->TableName))) {
					$this->Err = "追加処理に失敗しました(LOCK)";
					$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
					$this->myDB2->executeQuery("ROLLBACK");
					unset($myLog);
					return FALSE;
				} 

			// アップデート
			$sqlMoji = "Update $this->TableName set ";
			$sqlMoji .= "CheckName = $this->CheckName ";
			$sqlMoji .= " where ".$where ;

			$rtn = $this->myDB2->executeQuery($sqlMoji);
			if (!$rtn){
				$this->Err = "更新処理に失敗しました";
				$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
				unset($myLog);
				return FALSE;
			}else{
			// step 5. コミット
				if (!($this->myDB2->executeQuery("COMMIT"))) {
					$this->Err = "追加処理に失敗しました(COMMIT)";
					$this->myDB->executeQuery("ROLLBACK");
					return FALSE;
				}else {
				#	$this->ReservationCD = $maxCD;
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
		$rtn = $this->myDB2->setTransaction($lockString);
		if (!$rtn){
			$this->Err = $this->myDB2->Err;
			$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
			unset($myLog);
			return FALSE;
		}
		return TRUE;
	}

	function rollbackTransaction(){
		// ログ準備
		$myLog = new SPFWLog();

		$rtn = $this->myDB2->rollbackTransaction();
		if (!$rtn){
			$this->Err = $this->myDB2->Err;
			$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
			unset($myLog);
			return FALSE;
		}
		return TRUE;
	}

	function commitTransaction(){
		// ログ準備
		$myLog = new SPFWLog();

		$rtn = $this->myDB2->commitTransaction();
		if (!$rtn){
			$this->Err = $this->myDB2->Err;
			$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
			unset($myLog);
			return FALSE;
		}
		return TRUE;
	}

}
?>
