<?php

include_once _CLS_DIR . "SPFWLog.cls";

class GyosyaTanto {
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
	var $GyosyaTantoCD;
	/**
	 * クライアントコード
	 */
	var $ClientCD;
	var $GyosyaTantoName;

	var $GyosyaCD;
	var $GyosyaTantoTEL;
	var $GyosyaTantoKeitai;
 	var $GyosyaTantoMail;
	var $GyosyaTantoMail2;

	/**
	 * 備考
	 */
	var $GyosyaTantoNotes;
 	var $GT01;
 	var $GT02;
 	var $GT03;
 	var $GT04;
 	var $GT05;

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

	function GyosyaTanto($myDB = "",$GyosyaTantoCD = -1) {
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
		settype($GyosyaTantoCD,"integer");
		// 整数チェック
		if (!is_long($GyosyaTantoCD) or $GyosyaTantoCD == 0) {
			$this->Err = "ユーザーコード が不正です";
			$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
			unset($myLog);
			return FALSE;
		}

		$this->myDB = $myDB;
		$this->TableName = "tGyosyaTantoM";
		$this->GyosyaTantoCD = $GyosyaTantoCD;

		return TRUE;
	}

	function initialize(){
		$this->Err = "";

		$this->GyosyaTantoCD = "";
		$this->ClientCD = "";
		$this->GyosyaTantoName = "";
		$this->GyosyaCD = "";
		$this->GyosyaTantoTEL = "";
		$this->GyosyaTantoKeitai = "";
  		$this->GyosyaTantoMail = "";
  		$this->GyosyaTantoMail2 = "";

		$this->GyosyaTantoNotes = "";

  		$this->GT01 = "";
  		$this->GT02 = "";
  		$this->GT03 = "";
  		$this->GT04 = "";
  		$this->GT05 = "";

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
		$sqlMoji .= "GyosyaTantoCD";
		$sqlMoji .= ", ClientCD";
		$sqlMoji .= ", GyosyaTantoName";
		$sqlMoji .= ", GyosyaCD";
		$sqlMoji .= ", GyosyaTantoTEL";
		$sqlMoji .= ", GyosyaTantoKeitai";
  		$sqlMoji .= ", GyosyaTantoMail ";
  		$sqlMoji .= ", GyosyaTantoMail2 ";
		$sqlMoji .= ", GyosyaTantoNotes";
  		$sqlMoji .= ", GT01 ";
  		$sqlMoji .= ", GT02 ";
  		$sqlMoji .= ", GT03 ";
  		$sqlMoji .= ", GT04 ";
  		$sqlMoji .= ", GT05 ";

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
				$this->GyosyaTantoCD,
				$this->ClientCD,
				$this->GyosyaTantoName,
				$this->GyosyaCD,
				$this->GyosyaTantoTEL,
				$this->GyosyaTantoKeitai,
    				$this->GyosyaTantoMail,
    				$this->GyosyaTantoMail2,

				$this->GyosyaTantoNotes,

    				$this->GT01,
    				$this->GT02,
    				$this->GT03,
    				$this->GT04,
    				$this->GT05,

				$this->MukouFlg,
				$this->Created,
				$this->Creator,
				$this->Updated,
				$this->Updater
			) = $this->myDB->fetchRow($this->tmprtn,$RowNo);

			$this->GyosyaTantoName = stripslashes($this->GyosyaTantoName);
			$this->GyosyaTanto = stripslashes($this->GyosyaCD);
			$this->GyosyaTantoTEL = stripslashes($this->GyosyaTantoTEL);
			$this->GyosyaTantoKeitai = stripslashes($this->GyosyaTantoKeitai);
   			$this->GyosyaTantoMail = stripslashes($this->GyosyaTantoMail);
   			$this->GyosyaTantoMail2 = stripslashes($this->GyosyaTantoMail2);

			$this->GyosyaTantoNotes = stripslashes($this->GyosyaTantoNotes);
   			$this->GT01 = stripslashes($this->GT01);
   			$this->GT02 = stripslashes($this->GT02);
   			$this->GT03 = stripslashes($this->GT03);
   			$this->GT04 = stripslashes($this->GT04);
   			$this->GT05 = stripslashes($this->GT05);
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
		
		if ($this->GyosyaTantoCD < 0) {
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
					$sqlMoji = "select GyosyaTantoCD from $this->TableName order by GyosyaTantoCD desc limit 1";
					$maxCD = $this->myDB->getOneValue($sqlMoji);
					$maxCD = ($maxCD == -1) ? 0 : $maxCD;
					$maxCD++;

					// step 4. インサート
					$sqlMoji = "Insert into $this->TableName (";
					$sqlMoji .= "GyosyaTantoCD";
					if ($this->ClientCD != "") $sqlMoji .= ", ClientCD";
					if ($this->GyosyaTantoName != "") $sqlMoji .= ", GyosyaTantoName";
					if ($this->GyosyaCD != "") $sqlMoji .= ", GyosyaCD";
					if ($this->GyosyaTantoTEL != "") $sqlMoji .= ", GyosyaTantoTEL";
					if ($this->GyosyaTantoKeitai != "") $sqlMoji .= ", GyosyaTantoKeitai";
     					if ($this->GyosyaTantoMail != "") $sqlMoji .= ", GyosyaTantoMail";
     					if ($this->GyosyaTantoMail2 != "") $sqlMoji .= ", GyosyaTantoMail2";

					if ($this->GyosyaTantoNotes != "") $sqlMoji .= ", GyosyaTantoNotes";
     					if ($this->GT01 != "") $sqlMoji .= ", GT01";
     					if ($this->GT02 != "") $sqlMoji .= ", GT02";
     					if ($this->GT03 != "") $sqlMoji .= ", GT03";
     					if ($this->GT04 != "") $sqlMoji .= ", GT04";
     					if ($this->GT05 != "") $sqlMoji .= ", GT05";

					if ($this->MukouFlg != "") $sqlMoji .= ", MukouFlg";
					$sqlMoji .= ", Created";
					if ($this->Creator != "") $sqlMoji .= ", Creator";
					$sqlMoji .= ", Updated";
					if ($this->Updater != "") $sqlMoji .= ", Updater";
					$sqlMoji .= ") values (";
					$sqlMoji .= "$maxCD";
					if ($this->ClientCD != "") $sqlMoji .= ", $this->ClientCD";
					if ($this->GyosyaTantoName != "") $sqlMoji .= ", '" . addslashes($this->GyosyaTantoName) . "'";
					if ($this->GyosyaCD != "") $sqlMoji .= ", '" . addslashes($this->GyosyaCD) . "'";

					if ($this->GyosyaTantoTEL != "") $sqlMoji .= ", '" . addslashes($this->GyosyaTantoTEL) . "'";
					if ($this->GyosyaTantoKeitai != "") $sqlMoji .= ", '" . addslashes($this->GyosyaTantoKeitai) . "'";
     					if ($this->GyosyaTantoMail != "") $sqlMoji .= ", '" . addslashes($this->GyosyaTantoMail) . "'";
     					if ($this->GyosyaTantoMail2 != "") $sqlMoji .= ", '" . addslashes($this->GyosyaTantoMail2) . "'";

					if ($this->GyosyaTantoNotes != "") $sqlMoji .= ", '" . addslashes($this->GyosyaTantoNotes) . "'";
     					if ($this->GT01 != "") $sqlMoji .= ", '" . addslashes($this->GT01) . "'";
     					if ($this->GT02 != "") $sqlMoji .= ", '" . addslashes($this->GT02) . "'";
     					if ($this->GT03 != "") $sqlMoji .= ", '" . addslashes($this->GT03) . "'";
     					if ($this->GT04 != "") $sqlMoji .= ", '" . addslashes($this->GT04) . "'";
     					if ($this->GT05 != "") $sqlMoji .= ", '" . addslashes($this->GT05) . "'";

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
							$this->GyosyaTantoCD = $maxCD;
						}
					}
				}
			}
		} else {
			// アップデート
			$sqlMoji = "Update $this->TableName set ";
			$sqlMoji .= "GyosyaTantoCD = $this->GyosyaTantoCD";
			$sqlMoji .= ", ClientCD = " . $this->convertNullIntValue($this->ClientCD);
			$sqlMoji .= ", GyosyaTantoName = " . $this->convertNullStringValue($this->GyosyaTantoName);
			$sqlMoji .= ", GyosyaCD = " . $this->convertNullStringValue($this->GyosyaCD);
			$sqlMoji .= ", GyosyaTantoTEL = " . $this->convertNullStringValue($this->GyosyaTantoTEL);
			$sqlMoji .= ", GyosyaTantoKeitai = " . $this->convertNullStringValue($this->GyosyaTantoKeitai);
   			$sqlMoji .= ", GyosyaTantoMail = " . $this->convertNullStringValue($this->GyosyaTantoMail);
   			$sqlMoji .= ", GyosyaTantoMail2 = " . $this->convertNullStringValue($this->GyosyaTantoMail2);
			$sqlMoji .= ", GyosyaTantoNotes = " . $this->convertNullStringValue($this->GyosyaTantoNotes);
   			$sqlMoji .= ", GT01 = " . $this->convertNullStringValue($this->GT01);
   			$sqlMoji .= ", GT02 = " . $this->convertNullStringValue($this->GT02);
   			$sqlMoji .= ", GT03 = " . $this->convertNullStringValue($this->GT03);
   			$sqlMoji .= ", GT04 = " . $this->convertNullStringValue($this->GT04);
   			$sqlMoji .= ", GT05 = " . $this->convertNullStringValue($this->GT05);

			$sqlMoji .= ", MukouFlg = " . $this->convertFlgToString($this->MukouFlg);
			$sqlMoji .= ", Created = " . $this->convertNullStringValue($this->Created);
			$sqlMoji .= ", Creator = " . $this->convertNullIntValue($this->Creator);
			$sqlMoji .= ", Updated = NOW()";
			$sqlMoji .= ", Updater = " . $this->convertNullIntValue($this->Updater);
			$sqlMoji .= " where GyosyaTantoCD = $this->GyosyaTantoCD";

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
