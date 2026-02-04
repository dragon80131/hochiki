<?php

include_once _CLS_DIR . "SPFWLog.cls";

class BukkenMatrix {
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
	var $BukkenMatrixCD;
	var $BukkenCD;
	var $BuildingCD;
	var $KaiRoom;

 var $G01;
 var $G02;
 var $G03;
 var $G04;
 var $G05;


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

	function __construct($myDB = "",$BukkenMatrixCD = -1) {
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
		settype($BukkenMatrixCD,"integer");
		// 整数チェック
		if (!is_long($BukkenMatrixCD) or $BukkenMatrixCD == 0) {
			$this->Err = "ユーザーコード が不正です";
			$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
			unset($myLog);
			return FALSE;
		}

		$this->myDB = $myDB;
		$this->TableName = "tBukkenMatrixM";
		$this->BukkenMatrixCD = $BukkenMatrixCD;

		return TRUE;
	}

	function initialize(){
		$this->Err = "";

		$this->BukkenMatrixCD = "";
		$this->BukkenCD = "";
		$this->BuildingCD = "";
		$this->KaiRoom = "";

#  $this->G01 = "";
#  $this->G02 = "";
#  $this->G03 = "";
#  $this->G04 = "";
#  $this->G05 = "";

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
		$sqlMoji .= "BukkenMatrixCD";
		$sqlMoji .= ", BukkenCD";
		$sqlMoji .= ", BuildingCD";
		$sqlMoji .= ", KaiRoom";

#  $sqlMoji .= ", G01 ";
#  $sqlMoji .= ", G02 ";
#  $sqlMoji .= ", G03 ";
#  $sqlMoji .= ", G04 ";
#  $sqlMoji .= ", G05 ";

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
				$this->BukkenMatrixCD,
				$this->BukkenCD,
				$this->BuildingCD,
				$this->KaiRoom,

 #   $this->G01,
#    $this->G02,
#    $this->G03,
#    $this->G04,
#    $this->G05,


				$this->MukouFlg,
				$this->Created,
				$this->Creator,
				$this->Updated,
				$this->Updater
			) = $this->myDB->fetchRow($this->tmprtn,$RowNo);

			$this->BukkenCD = stripslashes($this->BukkenCD);
			$this->BuildingCD = stripslashes($this->BuildingCD);
			$this->KaiRoom = stripslashes($this->KaiRoom);

#   $this->G01 = stripslashes($this->G01);
#   $this->G02 = stripslashes($this->G02);
#   $this->G03 = stripslashes($this->G03);
#   $this->G04 = stripslashes($this->G04);
#   $this->G05 = stripslashes($this->G05);
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
		
		if ($this->BukkenMatrixCD < 0) {
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
					$sqlMoji = "select BukkenMatrixCD from $this->TableName order by BukkenMatrixCD desc limit 1";
					$maxCD = $this->myDB->getOneValue($sqlMoji);
					$maxCD = ($maxCD == -1) ? 0 : $maxCD;
					$maxCD++;

					// step 4. インサート
					$sqlMoji = "Insert into $this->TableName (";
					$sqlMoji .= "BukkenMatrixCD";
					if ($this->BukkenCD != "") $sqlMoji .= ", BukkenCD";
					if ($this->BuildingCD != "") $sqlMoji .= ", BuildingCD";
					if ($this->KaiRoom != "") $sqlMoji .= ", KaiRoom";
#     if ($this->G01 != "") $sqlMoji .= ", G01";
#     if ($this->G02 != "") $sqlMoji .= ", G02";
#     if ($this->G03 != "") $sqlMoji .= ", G03";
#     if ($this->G04 != "") $sqlMoji .= ", G04";
#     if ($this->G05 != "") $sqlMoji .= ", G05";

					if ($this->MukouFlg != "") $sqlMoji .= ", MukouFlg";
					$sqlMoji .= ", Created";
					if ($this->Creator != "") $sqlMoji .= ", Creator";
					$sqlMoji .= ", Updated";
					if ($this->Updater != "") $sqlMoji .= ", Updater";
					$sqlMoji .= ") values (";
					$sqlMoji .= "$maxCD";
					if ($this->BukkenCD != "") $sqlMoji .= ", '" . addslashes($this->BukkenCD) . "'";
					if ($this->BuildingCD != "") $sqlMoji .= ", '" . addslashes($this->BuildingCD) . "'";
					if ($this->KaiRoom != "") $sqlMoji .= ", '" . addslashes($this->KaiRoom) . "'";
#     if ($this->G01 != "") $sqlMoji .= ", '" . addslashes($this->G01) . "'";
#     if ($this->G02 != "") $sqlMoji .= ", '" . addslashes($this->G02) . "'";
#     if ($this->G03 != "") $sqlMoji .= ", '" . addslashes($this->G03) . "'";
#     if ($this->G04 != "") $sqlMoji .= ", '" . addslashes($this->G04) . "'";
#     if ($this->G05 != "") $sqlMoji .= ", '" . addslashes($this->G05) . "'";

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
							$this->BukkenMatrixCD = $maxCD;
						}
					}
				}
			}
		} else {
			// アップデート
			$sqlMoji = "Update $this->TableName set ";
			$sqlMoji .= "BukkenMatrixCD = $this->BukkenMatrixCD";
			$sqlMoji .= ", BukkenCD = " . $this->convertNullStringValue($this->BukkenCD);
			$sqlMoji .= ", BuildingCD = " . $this->convertNullStringValue($this->BuildingCD);
			$sqlMoji .= ", KaiRoom = " . $this->convertNullStringValue($this->KaiRoom);
#   $sqlMoji .= ", G01 = " . $this->convertNullStringValue($this->G01);
#   $sqlMoji .= ", G02 = " . $this->convertNullStringValue($this->G02);
#   $sqlMoji .= ", G03 = " . $this->convertNullStringValue($this->G03);
#   $sqlMoji .= ", G04 = " . $this->convertNullStringValue($this->G04);
#   $sqlMoji .= ", G05 = " . $this->convertNullStringValue($this->G05);

			$sqlMoji .= ", MukouFlg = " . $this->convertFlgToString($this->MukouFlg);
			$sqlMoji .= ", Created = " . $this->convertNullStringValue($this->Created);
			$sqlMoji .= ", Creator = " . $this->convertNullIntValue($this->Creator);
			$sqlMoji .= ", Updated = NOW()";
			$sqlMoji .= ", Updater = " . $this->convertNullIntValue($this->Updater);
			$sqlMoji .= " where BukkenMatrixCD = $this->BukkenMatrixCD";

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
