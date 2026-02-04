<?php
/**
 * ZanScheduleクラス
 *
 * メニューマスタを扱うクラスです。
 *
 **/

// 依存
include_once _CLS_DIR . "SPFWLog.cls";

class ZanSchedule {
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
	var $ZanScheduleCD;
	/**
	 * クライアントコード
	 */
	var $ClientCD;

 var $SagyoDate;
 var $SagyoinCD;
 var $ZanMenuCD;
 var $ZanScheduleNotes;
 var $ZanSagyoGroupCD;
 var $S002;
 var $S003;
 var $S004;
 var $S005;
 var $S006;
 var $S007;
 var $S008;
 var $S009;
 var $S010;


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

	function __construct($myDB = "",$ZanScheduleCD = -1) {
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
		settype($ZanScheduleCD,"integer");
		// 整数チェック
		if (!is_long($ZanScheduleCD) or $ZanScheduleCD == 0) {
			$this->Err = "ユーザーコード が不正です";
			$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
			unset($myLog);
			return FALSE;
		}

		$this->myDB = $myDB;
		$this->TableName = "tZanScheduleM";
		$this->ZanScheduleCD = $ZanScheduleCD;

		return TRUE;
	}

	function initialize(){
		$this->Err = "";


  $this->ZanScheduleCD = "";
  $this->SagyoDate = "";
  $this->SagyoinCD = "";
  $this->ZanMenuCD = "";
  $this->ZanScheduleNotes = "";
  $this->ZanSagyoGroupCD = "";
  $this->S002 = "";
  $this->S003 = "";
  $this->S004 = "";
  $this->S005 = "";
  $this->S006 = "";
  $this->S007 = "";
  $this->S008 = "";
  $this->S009 = "";
  $this->S010 = "";


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
		$sqlMoji .= "ZanScheduleCD";

  $sqlMoji .= ", SagyoDate ";
  $sqlMoji .= ", SagyoinCD ";
  $sqlMoji .= ", ZanMenuCD ";
  $sqlMoji .= ", ZanScheduleNotes ";
  $sqlMoji .= ", ZanSagyoGroupCD ";
  $sqlMoji .= ", S002 ";
  $sqlMoji .= ", S003 ";
  $sqlMoji .= ", S004 ";
  $sqlMoji .= ", S005 ";
  $sqlMoji .= ", S006 ";
  $sqlMoji .= ", S007 ";
  $sqlMoji .= ", S008 ";
  $sqlMoji .= ", S009 ";
  $sqlMoji .= ", S010 ";



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
    $this->ZanScheduleCD,
    $this->SagyoDate,
    $this->SagyoinCD,
    $this->ZanMenuCD,
    $this->ZanScheduleNotes,
    $this->ZanSagyoGroupCD,
    $this->S002,
    $this->S003,
    $this->S004,
    $this->S005,
    $this->S006,
    $this->S007,
    $this->S008,
    $this->S009,
    $this->S010,

				$this->MukouFlg,
				$this->Created,
				$this->Creator,
				$this->Updated,
				$this->Updater
			) = $this->myDB->fetchRow($this->tmprtn,$RowNo);


   $this->SagyoDate = stripslashes($this->SagyoDate);
   $this->SagyoinCD = stripslashes($this->SagyoinCD);
   $this->ZanMenuCD = stripslashes($this->ZanMenuCD);
   $this->ZanScheduleNotes = stripslashes($this->ZanScheduleNotes);
   $this->ZanSagyoGroupCD = stripslashes($this->ZanSagyoGroupCD);
   $this->S002 = stripslashes($this->S002);
   $this->S003 = stripslashes($this->S003);
   $this->S004 = stripslashes($this->S004);
   $this->S005 = stripslashes($this->S005);
   $this->S006 = stripslashes($this->S006);
   $this->S007 = stripslashes($this->S007);
   $this->S008 = stripslashes($this->S008);
   $this->S009 = stripslashes($this->S009);
   $this->S010 = stripslashes($this->S010);


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
		
		if ($this->ZanScheduleCD < 0) {
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
					$sqlMoji = "select ZanScheduleCD from $this->TableName order by ZanScheduleCD desc limit 1";
					$maxCD = $this->myDB->getOneValue($sqlMoji);
					$maxCD = ($maxCD == -1) ? 0 : $maxCD;
					$maxCD++;

					// step 4. インサート
					$sqlMoji = "Insert into $this->TableName (";
					$sqlMoji .= "ZanScheduleCD";

     if ($this->SagyoDate != "") $sqlMoji .= ", SagyoDate";
     if ($this->SagyoinCD != "") $sqlMoji .= ", SagyoinCD";
     if ($this->ZanMenuCD != "") $sqlMoji .= ", ZanMenuCD";
     if ($this->ZanScheduleNotes != "") $sqlMoji .= ", ZanScheduleNotes";
     if ($this->ZanSagyoGroupCD != "") $sqlMoji .= ", ZanSagyoGroupCD";
     if ($this->S002 != "") $sqlMoji .= ", S002";
     if ($this->S003 != "") $sqlMoji .= ", S003";
     if ($this->S004 != "") $sqlMoji .= ", S004";
     if ($this->S005 != "") $sqlMoji .= ", S005";
     if ($this->S006 != "") $sqlMoji .= ", S006";
     if ($this->S007 != "") $sqlMoji .= ", S007";
     if ($this->S008 != "") $sqlMoji .= ", S008";
     if ($this->S009 != "") $sqlMoji .= ", S009";
     if ($this->S010 != "") $sqlMoji .= ", S010";



					if ($this->MukouFlg != "") $sqlMoji .= ", MukouFlg";
					$sqlMoji .= ", Created";
					if ($this->Creator != "") $sqlMoji .= ", Creator";
					$sqlMoji .= ", Updated";
					if ($this->Updater != "") $sqlMoji .= ", Updater";
					$sqlMoji .= ") values (";
					$sqlMoji .= "$maxCD";
     if ($this->SagyoDate != "") $sqlMoji .= ", '" . addslashes($this->SagyoDate) . "'";
     if ($this->SagyoinCD != "") $sqlMoji .= ", '" . addslashes($this->SagyoinCD) . "'";
     if ($this->ZanMenuCD != "") $sqlMoji .= ", '" . addslashes($this->ZanMenuCD) . "'";
     if ($this->ZanScheduleNotes != "") $sqlMoji .= ", '" . addslashes($this->ZanScheduleNotes) . "'";
     if ($this->ZanSagyoGroupCD != "") $sqlMoji .= ", '" . addslashes($this->ZanSagyoGroupCD) . "'";
     if ($this->S002 != "") $sqlMoji .= ", '" . addslashes($this->S002) . "'";
     if ($this->S003 != "") $sqlMoji .= ", '" . addslashes($this->S003) . "'";
     if ($this->S004 != "") $sqlMoji .= ", '" . addslashes($this->S004) . "'";
     if ($this->S005 != "") $sqlMoji .= ", '" . addslashes($this->S005) . "'";
     if ($this->S006 != "") $sqlMoji .= ", '" . addslashes($this->S006) . "'";
     if ($this->S007 != "") $sqlMoji .= ", '" . addslashes($this->S007) . "'";
     if ($this->S008 != "") $sqlMoji .= ", '" . addslashes($this->S008) . "'";
     if ($this->S009 != "") $sqlMoji .= ", '" . addslashes($this->S009) . "'";
     if ($this->S010 != "") $sqlMoji .= ", '" . addslashes($this->S010) . "'";



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
							$this->ZanScheduleCD = $maxCD;
						}
					}
				}
			}
		} else {
			// アップデート
			$sqlMoji = "Update $this->TableName set ";
			$sqlMoji .= "ZanScheduleCD = $this->ZanScheduleCD";
   $sqlMoji .= ", SagyoDate = " . $this->convertNullStringValue($this->SagyoDate);
   $sqlMoji .= ", SagyoinCD = " . $this->convertNullIntValue($this->SagyoinCD);
   $sqlMoji .= ", ZanMenuCD = " . $this->convertNullIntValue($this->ZanMenuCD);
   $sqlMoji .= ", ZanScheduleNotes = " . $this->convertNullStringValue($this->ZanScheduleNotes);
   $sqlMoji .= ", ZanSagyoGroupCD = " . $this->convertNullStringValue($this->ZanSagyoGroupCD);
   $sqlMoji .= ", S002 = " . $this->convertNullStringValue($this->S002);
   $sqlMoji .= ", S003 = " . $this->convertNullStringValue($this->S003);
   $sqlMoji .= ", S004 = " . $this->convertNullStringValue($this->S004);
   $sqlMoji .= ", S005 = " . $this->convertNullStringValue($this->S005);
   $sqlMoji .= ", S006 = " . $this->convertNullStringValue($this->S006);
   $sqlMoji .= ", S007 = " . $this->convertNullStringValue($this->S007);
   $sqlMoji .= ", S008 = " . $this->convertNullStringValue($this->S008);
   $sqlMoji .= ", S009 = " . $this->convertNullStringValue($this->S009);
   $sqlMoji .= ", S010 = " . $this->convertNullStringValue($this->S010);




			$sqlMoji .= ", MukouFlg = " . $this->convertFlgToString($this->MukouFlg);
			$sqlMoji .= ", Created = " . $this->convertNullStringValue($this->Created);
			$sqlMoji .= ", Creator = " . $this->convertNullIntValue($this->Creator);
			$sqlMoji .= ", Updated = NOW()";
			$sqlMoji .= ", Updater = " . $this->convertNullIntValue($this->Updater);
			$sqlMoji .= " where ZanScheduleCD = $this->ZanScheduleCD";

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
