<?php

include_once _CLS_DIR . "SPFWLog.cls";

class ResidentsForm {
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
	var $FormCD;
	var $RoomNo;
	var $Name;
	var $Contents;
	
	var $BukkenCD;
	var $TaioLog;
	var $IsInRoom;
	var $TEL;
	
	var $DemandDate;
	var $AMPM;
	var $Dates;
	var $MailAddress;


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

	function __construct($myDB = "",$FormCD = -1) {
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
		settype($FormCD,"integer");
		// 整数チェック
		if (!is_long($FormCD) or $FormCD == 0) {
			$this->Err = "ユーザーコード が不正です";
			$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
			unset($myLog);
			return FALSE;
		}

		$this->myDB = $myDB;
		$this->TableName = "tResidentsFormF";
		$this->FormCD = $FormCD;

		return TRUE;
	}

	function initialize(){
		$this->Err = "";

		$this->FormCD = "";
		$this->RoomNo = "";
		$this->Name = "";

		$this->Contents = "";
		$this->BukkenCD = "";
		$this->TaioLog = "";
		$this->IsInRoom = "";
		$this->TEL = "";
		$this->DemandDate = "";
		$this->AMPM = "";
		$this->Dates = "";
		$this->MailAddress = "";

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
		$sqlMoji .= "FormCD";
		$sqlMoji .= ", RoomNo";
		$sqlMoji .= ", Name";

		$sqlMoji .= ", Contents ";
		$sqlMoji .= ", BukkenCD ";
		$sqlMoji .= ", TaioLog ";
		$sqlMoji .= ", IsInRoom ";
		$sqlMoji .= ", TEL ";
		$sqlMoji .= ", DemandDate";
		$sqlMoji .= ", AMPM ";
		$sqlMoji .= ", Dates ";
		$sqlMoji .= ", MailAddress ";

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
				$this->FormCD,
				$this->RoomNo,
				$this->Name,

				$this->Contents,
				$this->BukkenCD,
				$this->TaioLog,
				$this->IsInRoom,
				$this->TEL,
				$this->DemandDate,
				$this->AMPM,
				$this->Dates,
				$this->MailAddress,


				$this->MukouFlg,
				$this->Created,
				$this->Creator,
				$this->Updated,
				$this->Updater
			) = $this->myDB->fetchRow($this->tmprtn,$RowNo);

			$this->RoomNo = stripslashes($this->RoomNo);
			$this->Name = stripslashes($this->Name);

			$this->Contents = stripslashes($this->Contents);
			$this->BukkenCD = stripslashes($this->BukkenCD);
			$this->TaioLog = stripslashes($this->TaioLog);
			$this->IsInRoom = stripslashes($this->IsInRoom);
			$this->TEL = stripslashes($this->TEL);
			$this->DemandDate = stripslashes($this->DemandDate);
			$this->AMPM = stripslashes($this->AMPM);
			$this->Dates = stripslashes($this->Dates);
			$this->MailAddress = stripslashes($this->MailAddress);
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
		
		if ($this->FormCD < 0) {
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
					$sqlMoji = "select FormCD from $this->TableName order by FormCD desc limit 1";
					$maxCD = $this->myDB->getOneValue($sqlMoji);
					$maxCD = ($maxCD == -1) ? 0 : $maxCD;
					$maxCD++;

					// step 4. インサート
					$sqlMoji = "Insert into $this->TableName (";
					$sqlMoji .= "FormCD";
					if ($this->RoomNo != "") $sqlMoji .= ", RoomNo";
					if ($this->Name != "") $sqlMoji .= ", Name";
					if ($this->Contents != "") $sqlMoji .= ", Contents";
					if ($this->BukkenCD != "") $sqlMoji .= ", BukkenCD";
					if ($this->TaioLog != "") $sqlMoji .= ", TaioLog";
					if ($this->IsInRoom != "") $sqlMoji .= ", IsInRoom";
					if ($this->TEL != "") $sqlMoji .= ", TEL";
					if ($this->DemandDate != "") $sqlMoji .= ",DemandDate";
					if ($this->AMPM != "") $sqlMoji .= ", AMPM";
					if ($this->Dates != "") $sqlMoji .= ", Dates";
					if ($this->MailAddress != "") $sqlMoji .= ", MailAddress";

					if ($this->MukouFlg != "") $sqlMoji .= ", MukouFlg";
					$sqlMoji .= ", Created";
					if ($this->Creator != "") $sqlMoji .= ", Creator";
					$sqlMoji .= ", Updated";
					if ($this->Updater != "") $sqlMoji .= ", Updater";
					$sqlMoji .= ") values (";
					$sqlMoji .= "$maxCD";
					if ($this->RoomNo != "") $sqlMoji .= ", '" . addslashes($this->RoomNo) . "'";
					if ($this->Name != "") $sqlMoji .= ", '" . addslashes($this->Name) . "'";
					if ($this->Contents != "") $sqlMoji .= ", '" . addslashes($this->Contents) . "'";
					if ($this->BukkenCD != "") $sqlMoji .= ", '" . addslashes($this->BukkenCD) . "'";
					if ($this->TaioLog != "") $sqlMoji .= ", '" . addslashes($this->TaioLog) . "'";
     				if ($this->IsInRoom != "") $sqlMoji .= ", '" . addslashes($this->IsInRoom) . "'";
					if ($this->TEL != "") $sqlMoji .= ", '" . addslashes($this->TEL) . "'";
					if ($this->DemandDate != "") $sqlMoji .= ", '" . addslashes($this->DemandDate) . "'";
					if ($this->AMPM != "") $sqlMoji .= ", '" . addslashes($this->AMPM) . "'";
					if ($this->Dates != "") $sqlMoji .= ", '" . addslashes($this->Dates) . "'";
					if ($this->MailAddress != "") $sqlMoji .= ", '" . addslashes($this->MailAddress) . "'";

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
							$this->FormCD = $maxCD;
						}
					}
				}
			}
		} else {
			// アップデート
			$sqlMoji = "Update $this->TableName set ";
			$sqlMoji .= "FormCD = $this->FormCD";
			$sqlMoji .= ", RoomNo = " . $this->convertNullStringValue($this->RoomNo);
			$sqlMoji .= ", Name = " . $this->convertNullStringValue($this->Name);
			$sqlMoji .= ", Contents = " . $this->convertNullStringValue($this->Contents);
			$sqlMoji .= ", BukkenCD = " . $this->convertNullStringValue($this->BukkenCD);
			$sqlMoji .= ", TaioLog = " . $this->convertNullStringValue($this->TaioLog);
			$sqlMoji .= ", IsInRoom = " . $this->convertNullStringValue($this->IsInRoom);
			$sqlMoji .= ", TEL = " . $this->convertNullStringValue($this->TEL);
			$sqlMoji .= ", DemandDate = " . $this->convertNullStringValue($this->DemandDate);
			$sqlMoji .= ", AMPM = " . $this->convertNullStringValue($this->AMPM);
			$sqlMoji .= ", Dates = " . $this->convertNullStringValue($this->Dates);
			$sqlMoji .= ", MailAddress = " . $this->convertNullStringValue($this->MailAddress);

			$sqlMoji .= ", MukouFlg = " . $this->convertFlgToString($this->MukouFlg);
			$sqlMoji .= ", Created = " . $this->convertNullStringValue($this->Created);
			$sqlMoji .= ", Creator = " . $this->convertNullIntValue($this->Creator);
			$sqlMoji .= ", Updated = NOW()";
			$sqlMoji .= ", Updater = " . $this->convertNullIntValue($this->Updater);
			$sqlMoji .= " where FormCD = $this->FormCD";

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
