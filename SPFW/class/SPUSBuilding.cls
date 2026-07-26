<?php
/**
 * 棟クラス
 *
 * 
 * 
 * 
 * 
 **/

// 依存
include_once _CLS_DIR . "SPFWLog.cls";

class Building {
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

	var $BuildingCD;
	var $BukkenCD;
	var $BuildingName;
	var $Kosu;
	var $Kaidaka;
	var $Hansu;
	var $WakuPattern;
	var $MaxWakuSu;
	var $Holiday1;
	var $ReserveDay;
	var $FirstDateFeature;
	var $Kojijun;
	var $SenyuStartDate;
	var $SenyuEndDate;
	var $YoyakuEndDate;
	var $FrameOverflow;
	var $ArrangeType;
	var $FloorReserveInfo;

	/**
	 * 無効フラグ
	 */
	public $MukouFlg;

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

	// function UploadFile($myDB = "",$BuildingCD = -1) {
	function __construct($myDB = "",$BuildingCD = -1) {
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
		settype($BuildingCD,"integer");
		// 整数チェック
		if (!is_long($BuildingCD) or $BuildingCD == 0) {
			$this->Err = "ユーザーコード が不正です";
			$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
			unset($myLog);
			return FALSE;
		}

		$this->myDB = $myDB;
		$this->TableName = "tBuildingM";
		$this->BuildingCD = $BuildingCD;

		return TRUE;
	}

	function initialize(){
		$this->Err = "";

		$this->BuildingCD = "";
		$this->BukkenCD = "";
		$this->BuildingName = "";
		$this->Kosu = "";
		$this->Kaidaka = "";
		$this->Hansu = "";
		$this->WakuPattern = "";
		$this->MaxWakuSu = "";
		$this->Holiday1 = "";
		$this->ReserveDay = "";
		$this->FirstDateFeature = "";
		$this->Kojijun = "";
		$this->SenyuStartDate = "";
		$this->SenyuEndDate = "";
		$this->YoyakuEndDate = "";
		$this->FrameOverflow = "";
		$this->ArrangeType = "";
		$this->FloorReserveInfo = "";

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
		$sqlMoji .= "BuildingCD";
		$sqlMoji .= ", BukkenCD";
		$sqlMoji .= ", BuildingName";
		$sqlMoji .= ", Kosu";
		$sqlMoji .= ", Kaidaka";
		$sqlMoji .= ", Hansu";
		$sqlMoji .= ", WakuPattern";
		$sqlMoji .= ", MaxWakuSu";
		$sqlMoji .= ", Holiday1";
		$sqlMoji .= ", ReserveDay";
		$sqlMoji .= ", FirstDateFeature";
		$sqlMoji .= ", Kojijun";
		$sqlMoji .= ", SenyuStartDate";
		$sqlMoji .= ", SenyuEndDate";
		$sqlMoji .= ", YoyakuEndDate";
		$sqlMoji .= ", FrameOverflow";
		$sqlMoji .= ", ArrangeType";
		$sqlMoji .= ", FloorReserveInfo";

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
				$this->BuildingCD,
				$this->BukkenCD,
				$this->BuildingName,
				$this->Kosu,
				$this->Kaidaka,
				$this->Hansu,
				$this->WakuPattern,
				$this->MaxWakuSu,
				$this->Holiday1,
				$this->ReserveDay,
				$this->FirstDateFeature,
				$this->Kojijun,
				$this->SenyuStartDate,
				$this->SenyuEndDate,
				$this->YoyakuEndDate,
				$this->FrameOverflow,
				$this->ArrangeType,
				$this->FloorReserveInfo,

				$this->MukouFlg,
				$this->Created,
				$this->Creator,
				$this->Updated,
				$this->Updater
			) = $this->myDB->fetchRow($this->tmprtn,$RowNo);
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
		
		if ($this->BuildingCD < 0) {
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
					$sqlMoji = "select BuildingCD from $this->TableName order by BuildingCD desc limit 1";
					$maxCD = $this->myDB->getOneValue($sqlMoji);
					$maxCD = ($maxCD == -1) ? 0 : $maxCD;
					$maxCD++;

					// step 4. インサート

					$sqlMoji = "Insert into $this->TableName (";
					$sqlMoji .= "BuildingCD";
					if ($this->BukkenCD != "") $sqlMoji .= ", BukkenCD";
					if ($this->BuildingName != "") $sqlMoji .= ", BuildingName";
					if ($this->Kosu != "") $sqlMoji .= ", Kosu";
					if ($this->Kaidaka != "") $sqlMoji .= ", Kaidaka";

					if ($this->Hansu != "") $sqlMoji .= ", Hansu";
					if ($this->WakuPattern != "") $sqlMoji .= ", WakuPattern";
					if ($this->MaxWakuSu != "") $sqlMoji .= ", MaxWakuSu";
					if ($this->Holiday1 != "") $sqlMoji .= ", Holiday1";
					if ($this->ReserveDay != "") $sqlMoji .= ", ReserveDay";
					if ($this->FirstDateFeature != "") $sqlMoji .= ", FirstDateFeature";
					if ($this->Kojijun != "") $sqlMoji .= ", Kojijun";
					if ($this->SenyuStartDate != "") $sqlMoji .= ", SenyuStartDate";
					if ($this->SenyuEndDate != "") $sqlMoji .= ", SenyuEndDate";
					if ($this->YoyakuEndDate != "") $sqlMoji .= ", YoyakuEndDate";
					if ($this->FrameOverflow != "") $sqlMoji .= ", FrameOverflow";
					if ($this->ArrangeType != "") $sqlMoji .= ", ArrangeType";
					if ($this->FloorReserveInfo != "") $sqlMoji .= ", FloorReserveInfo";

					if ($this->MukouFlg != "") $sqlMoji .= ", MukouFlg";
					$sqlMoji .= ", Created";
					if ($this->Creator != "") $sqlMoji .= ", Creator";
					$sqlMoji .= ", Updated";
					if ($this->Updater != "") $sqlMoji .= ", Updater";
					$sqlMoji .= ") values (";
					$sqlMoji .= "$maxCD";
					if ($this->BukkenCD != "") $sqlMoji .= ", '" . addslashes($this->BukkenCD) . "'";
					if ($this->BuildingName != "") $sqlMoji .= ", '" . addslashes($this->BuildingName) . "'";
					if ($this->Kosu != "") $sqlMoji .= ", '" . addslashes($this->Kosu) . "'";
					if ($this->Kaidaka != "") $sqlMoji .= ", '" . addslashes($this->Kaidaka) . "'";
					if ($this->Hansu != "") $sqlMoji .= ", '" . addslashes($this->Hansu) . "'";
					if ($this->WakuPattern != "") $sqlMoji .= ", '" . addslashes($this->WakuPattern) . "'";
					if ($this->MaxWakuSu != "") $sqlMoji .= ", '" . addslashes($this->MaxWakuSu) . "'";
					if ($this->Holiday1 != "") $sqlMoji .= ", '" . addslashes($this->Holiday1) . "'";
					if ($this->ReserveDay != "") $sqlMoji .= ", '" . addslashes($this->ReserveDay) . "'";
					if ($this->FirstDateFeature != "") $sqlMoji .= ", '" . addslashes($this->FirstDateFeature) . "'";
					if ($this->Kojijun != "") $sqlMoji .= ", '" . addslashes($this->Kojijun) . "'";
					if ($this->SenyuStartDate != "") $sqlMoji .= ", '" . addslashes($this->SenyuStartDate) . "'";
					if ($this->SenyuEndDate != "") $sqlMoji .= ", '" . addslashes($this->SenyuEndDate) . "'";
					if ($this->YoyakuEndDate != "") $sqlMoji .= ", '" . addslashes($this->YoyakuEndDate) . "'";
					if ($this->FrameOverflow != "") $sqlMoji .= ", '" . addslashes($this->FrameOverflow) . "'";
					if ($this->ArrangeType != "") $sqlMoji .= ", '" . addslashes($this->ArrangeType) . "'";
					if ($this->FloorReserveInfo != "") $sqlMoji .= ", '" . addslashes($this->FloorReserveInfo) . "'";

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
							$this->BuildingCD = $maxCD;
						}
					}
				}
			}
		} else {
			// アップデート
			$sqlMoji = "Update $this->TableName set ";
			$sqlMoji .= "BuildingCD = $this->BuildingCD";

			$sqlMoji .= ", BukkenCD = " . $this->convertNullStringValue($this->BukkenCD);
			$sqlMoji .= ", BuildingName = " . $this->convertNullStringValue($this->BuildingName);
			$sqlMoji .= ", Kosu = " . $this->convertNullStringValue($this->Kosu);
			$sqlMoji .= ", Kaidaka = " . $this->convertNullStringValue($this->Kaidaka);
			$sqlMoji .= ", Hansu = " . $this->convertNullStringValue($this->Hansu);
			$sqlMoji .= ", WakuPattern = " . $this->convertNullStringValue($this->WakuPattern);
			$sqlMoji .= ", MaxWakuSu = " . $this->convertNullStringValue($this->MaxWakuSu);
			$sqlMoji .= ", Holiday1 = " . $this->convertNullStringValue($this->Holiday1);
			$sqlMoji .= ", ReserveDay = " . $this->convertNullStringValue($this->ReserveDay);
			$sqlMoji .= ", FirstDateFeature = " . $this->convertNullStringValue($this->FirstDateFeature);
			$sqlMoji .= ", Kojijun = " . $this->convertNullStringValue($this->Kojijun);
			$sqlMoji .= ", SenyuStartDate = " . $this->convertNullStringValue($this->SenyuStartDate);
			$sqlMoji .= ", SenyuEndDate = " . $this->convertNullStringValue($this->SenyuEndDate);
			$sqlMoji .= ", YoyakuEndDate = " . $this->convertNullStringValue($this->YoyakuEndDate);
			$sqlMoji .= ", FrameOverflow = " . $this->convertNullStringValue($this->FrameOverflow);
			$sqlMoji .= ", ArrangeType = " . $this->convertNullStringValue($this->ArrangeType);
			$sqlMoji .= ", FloorReserveInfo = " . $this->convertNullStringValue($this->FloorReserveInfo);

			$sqlMoji .= ", MukouFlg = " . $this->convertNullStringValue($this->MukouFlg);
			$sqlMoji .= ", Created = " . $this->convertNullStringValue($this->Created);
			$sqlMoji .= ", Creator = " . $this->convertNullIntValue($this->Creator);
			$sqlMoji .= ", Updated = NOW()";
			$sqlMoji .= ", Updater = " . $this->convertNullIntValue($this->Updater);
			$sqlMoji .= " where BuildingCD = $this->BuildingCD";

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

	/* 削除 */
	function executeDelete($WhereStr = "") {
		$sqlMoji = "delete from $this->TableName ";
		if($WhereStr)
			$sqlMoji .= " where $WhereStr";

		$rtn = $this->myDB->executeQuery($sqlMoji);
		if (!$rtn){
			$this->Err = "削除処理に失敗しました";
			$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
			unset($myLog);
			return FALSE;
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
