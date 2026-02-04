<?php


// 依存
include_once _CLS_DIR . "SPFWLog.cls";

class BukkenKiki {
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
	var $BukkenKikiCD;
	/**
	 * クライアントコード
	 */
	var $JUC_NO;
	/**
	 * メニュータイトル
	 */
	var $JUC_GYO_NO;
	/**
	 * 略称
	 */
	var $MEISAI_KIKI_KBN;
	/**
	 * 所要ライン数
	 */
	var $SYHN_CD;
	/**
	 * 所要時間区分
	 */
	var $HINBAN;
	/**
	 * 備考
	 */
	var $HINMEI_SIYO;
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
	var $JUC_SURYO;
	var $KenmeiNo;
	var $D002;
	var $D001;
	var $D005;
	var $D004;
	var $D003;



	function __construct($myDB = "",$BukkenKikiCD = -1) {
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
		settype($BukkenKikiCD,"integer");
		// 整数チェック
		if (!is_long($BukkenKikiCD) or $BukkenKikiCD == 0) {
			$this->Err = "ユーザーコード が不正です";
			$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
			unset($myLog);
			return FALSE;
		}

		$this->myDB = $myDB;
		$this->TableName = "tBukkenKikiM";
		$this->BukkenKikiCD = $BukkenKikiCD;

		return TRUE;
	}

	function initialize(){
		$this->Err = "";

		$this->BukkenKikiCD = "";
		$this->JUC_NO = "";
		$this->JUC_GYO_NO = "";
		$this->MEISAI_KIKI_KBN = "";
		$this->SYHN_CD = "";
		$this->HINBAN = "";
		$this->HINMEI_SIYO = "";
		$this->MukouFlg = "";
		$this->Created = "";
		$this->Creator = "";
		$this->Updated = "";
		$this->Updater = "";
		$this->JUC_SURYO = "";
		$this->KenmeiNo = "";
		$this->D005 = "";
		$this->D004 = "";
		$this->D003 = "";
		$this->D002 = "";
		$this->D001 = "";
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
		$sqlMoji .= "BukkenKikiCD";
		$sqlMoji .= ", JUC_NO";
		$sqlMoji .= ", JUC_GYO_NO";
		$sqlMoji .= ", MEISAI_KIKI_KBN";
		$sqlMoji .= ", SYHN_CD";
		$sqlMoji .= ", HINBAN";
		$sqlMoji .= ", HINMEI_SIYO";
		$sqlMoji .= ", MukouFlg";
		$sqlMoji .= ", Created";
		$sqlMoji .= ", Creator";
		$sqlMoji .= ", Updated";
		$sqlMoji .= ", Updater";
		$sqlMoji .= ", JUC_SURYO";
		$sqlMoji .= ", KenmeiNo";
		$sqlMoji .= ", D002";
		$sqlMoji .= ", D001";
		$sqlMoji .= ", D005";
		$sqlMoji .= ", D004";
		$sqlMoji .= ", D003";
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
				$this->BukkenKikiCD,
				$this->JUC_NO,
				$this->JUC_GYO_NO,
				$this->MEISAI_KIKI_KBN,
				$this->SYHN_CD,
				$this->HINBAN,
				$this->HINMEI_SIYO,
				$this->MukouFlg,
				$this->Created,
				$this->Creator,
				$this->Updated,
				$this->Updater,
				$this->JUC_SURYO,
				$this->KenmeiNo,
				$this->D002,
				$this->D001,
				$this->D005,
				$this->D004,
				$this->D003
			) = $this->myDB->fetchRow($this->tmprtn,$RowNo);

			$this->JUC_NO = stripslashes($this->JUC_NO);
			$this->JUC_GYO_NO = stripslashes($this->JUC_GYO_NO);
			$this->MEISAI_KIKI_KBN = stripslashes($this->MEISAI_KIKI_KBN);
			$this->SYHN_CD = stripslashes($this->SYHN_CD);
			$this->HINBAN = stripslashes($this->HINBAN);
			$this->HINMEI_SIYO = stripslashes($this->HINMEI_SIYO);
			$this->JUC_SURYO = stripslashes($this->JUC_SURYO);
			$this->KenmeiNo = stripslashes($this->KenmeiNo);
			$this->D001 = stripslashes($this->D001);
			$this->D002 = stripslashes($this->D002);
			$this->D003 = stripslashes($this->D003);
			$this->D004 = stripslashes($this->D004);
			$this->D005 = stripslashes($this->D005);
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
		
		if ($this->BukkenKikiCD < 0) {
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
					$sqlMoji = "select BukkenKikiCD from $this->TableName order by BukkenKikiCD desc limit 1";
					$maxCD = $this->myDB->getOneValue($sqlMoji);
					$maxCD = ($maxCD == -1) ? 0 : $maxCD;
					$maxCD++;

					// step 4. インサート
					$sqlMoji = "Insert into $this->TableName (";
					$sqlMoji .= "BukkenKikiCD";
					if ($this->JUC_NO != "") $sqlMoji .= ", JUC_NO";
					if ($this->JUC_GYO_NO != "") $sqlMoji .= ", JUC_GYO_NO";
					if ($this->MEISAI_KIKI_KBN != "") $sqlMoji .= ", MEISAI_KIKI_KBN";
					if ($this->SYHN_CD != "") $sqlMoji .= ", SYHN_CD";
					if ($this->HINBAN != "") $sqlMoji .= ", HINBAN";
					if ($this->HINMEI_SIYO != "") $sqlMoji .= ", HINMEI_SIYO";
					if ($this->MukouFlg != "") $sqlMoji .= ", MukouFlg";
					$sqlMoji .= ", Created";
					if ($this->Creator != "") $sqlMoji .= ", Creator";
					$sqlMoji .= ", Updated";
					if ($this->Updater != "") $sqlMoji .= ", Updater";
					if ($this->JUC_SURYO != "") $sqlMoji .= ", JUC_SURYO";
					if ($this->KenmeiNo != "") $sqlMoji .= ", KenmeiNo";
					if ($this->D005 != "") $sqlMoji .= ", D005";
					if ($this->D004 != "") $sqlMoji .= ", D004";
					if ($this->D003 != "") $sqlMoji .= ", D003";
					if ($this->D002 != "") $sqlMoji .= ", D002";
					if ($this->D001 != "") $sqlMoji .= ", D001";
					$sqlMoji .= ") values (";
					$sqlMoji .= "$maxCD";
					if ($this->JUC_NO != "") $sqlMoji 			.= ", '" . addslashes($this->JUC_NO) . "'";
					if ($this->JUC_GYO_NO != "") $sqlMoji 		.= ", '" . addslashes($this->JUC_GYO_NO) . "'";
					if ($this->MEISAI_KIKI_KBN != "") $sqlMoji 	.= ", '" . addslashes($this->MEISAI_KIKI_KBN) . "'";
					if ($this->SYHN_CD != "") $sqlMoji 			.= ", '" . addslashes($this->SYHN_CD) . "'";
					if ($this->HINBAN != "") $sqlMoji 			.= ", '" . addslashes($this->HINBAN) . "'";
					if ($this->HINMEI_SIYO != "") $sqlMoji 		.= ", '" . addslashes($this->HINMEI_SIYO) . "'";
					if ($this->MukouFlg != "") $sqlMoji 		.= ", " . $this->convertFlgToString($this->MukouFlg);
					$sqlMoji 									.= ", NOW()";
					if ($this->Creator != "") $sqlMoji 			.= ", $this->Creator";
					$sqlMoji 									.= ", NOW()";
					if ($this->Updater != "") $sqlMoji 			.= ", $this->Updater";
					if ($this->JUC_SURYO != "") $sqlMoji 		.= ", '" . addslashes($this->JUC_SURYO) . "'";
					if ($this->KenmeiNo != "") $sqlMoji 		.= ", '" . addslashes($this->KenmeiNo) . "'";
					if ($this->D005 != "") $sqlMoji 			.= ", '" . addslashes($this->D005) . "'";
					if ($this->D004 != "") $sqlMoji 			.= ", '" . addslashes($this->D004) . "'";
					if ($this->D003 != "") $sqlMoji 			.= ", '" . addslashes($this->D003) . "'";
					if ($this->D002 != "") $sqlMoji 			.= ", '" . addslashes($this->D002) . "'";
					if ($this->D001 != "") $sqlMoji 			.= ", '" . addslashes($this->D001) . "'";
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
							$this->BukkenKikiCD = $maxCD;
						}
					}
				}
			}
		} else {
			// アップデート
			$sqlMoji = "Update $this->TableName set ";
			$sqlMoji .= "BukkenKikiCD = $this->BukkenKikiCD";
			$sqlMoji .= ", JUC_NO = " . $this->convertNullIntValue($this->JUC_NO);
			$sqlMoji .= ", JUC_GYO_NO = " . $this->convertNullStringValue($this->JUC_GYO_NO);
			$sqlMoji .= ", MEISAI_KIKI_KBN = " . $this->convertNullStringValue($this->MEISAI_KIKI_KBN);
			$sqlMoji .= ", SYHN_CD = " . $this->convertNullStringValue($this->SYHN_CD);
			$sqlMoji .= ", HINBAN = " . $this->convertNullStringValue($this->HINBAN);
			$sqlMoji .= ", HINMEI_SIYO = " . $this->convertNullStringValue($this->HINMEI_SIYO);
			$sqlMoji .= ", MukouFlg = " . $this->convertFlgToString($this->MukouFlg);
			$sqlMoji .= ", Created = " . $this->convertNullStringValue($this->Created);
			$sqlMoji .= ", Creator = " . $this->convertNullIntValue($this->Creator);
			$sqlMoji .= ", Updated = NOW()";
			$sqlMoji .= ", Updater = " . $this->convertNullIntValue($this->Updater);
			$sqlMoji .= ", JUC_SURYO = " . $this->convertNullStringValue($this->JUC_SURYO);
			$sqlMoji .= ", KenmeiNo = " . $this->convertNullStringValue($this->KenmeiNo);
			$sqlMoji .= ", D005 = " . $this->convertNullStringValue($this->D005);
			$sqlMoji .= ", D004 = " . $this->convertNullStringValue($this->D004);
			$sqlMoji .= ", D003 = " . $this->convertNullStringValue($this->D003);
			$sqlMoji .= ", D002 = " . $this->convertNullStringValue($this->D002);
			$sqlMoji .= ", D001 = " . $this->convertNullStringValue($this->D001);
			$sqlMoji .= " where BukkenKikiCD = $this->BukkenKikiCD";

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
