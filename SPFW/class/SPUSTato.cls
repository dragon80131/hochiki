<?php
/**
 * Tatoクラス
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

class Tato {
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
	var $TatoCD;


	/**
	 * 棟記号
	 */
	var $ToName;
	/**
	 * 共用期間　開始日
	 */
	var $KyoyoStartDate;

	/**
	 * 共用期間　終了日
	 */
	var $KyoyoEndDate;
	var $ReserveTo2;
	var $ReserveFrom2;
	var $KeteiDate;

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

	function Tato($myDB = "",$TatoCD = -1) {
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
		settype($TatoCD,"integer");
		// 整数チェック
		if (!is_long($TatoCD) or $TatoCD == 0) {
			$this->Err = "棟コード が不正です";
			$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
			unset($myLog);
			return FALSE;
		}

		$this->myDB = $myDB;
		$this->TableName = "tTatoM";
		$this->TatoCD = $TatoCD;

		return TRUE;
	}

	function initialize(){
		$this->Err = "";
		$this->TatoCD = "";
		$this->ToName = "";
		$this->KyoyoStartDate = "";
		$this->KyoyoEndDate = "";
		$this->ReserveTo2 = "";
		$this->ReserveFrom2 = "";
		$this->KeteiDate = "";
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
		$sqlMoji .= "TatoCD";
		$sqlMoji .= ", ToName";
		$sqlMoji .= ", KyoyoStartDate";
		$sqlMoji .= ", KyoyoEndDate";
		$sqlMoji .= ", ReserveTo2";
		$sqlMoji .= ", ReserveFrom2";
		$sqlMoji .= ", KeteiDate";
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
				$this->TatoCD,
				$this->ToName,
				$this->KyoyoStartDate,
				$this->KyoyoEndDate,
				$this->ReserveTo2,
				$this->ReserveFrom2,
				$this->KeteiDate,
				$this->MukouFlg,
				$this->Created,
				$this->Creator,
				$this->Updated,
				$this->Updater
			) = $this->myDB->fetchRow($this->tmprtn,$RowNo);

			$this->ToName = stripslashes($this->ToName);
			$this->KyoyoStartDate = stripslashes($this->KyoyoStartDate);
			$this->KyoyoEndDate = stripslashes($this->KyoyoEndDate);
			$this->ReserveTo2 = stripslashes($this->ReserveTo2);
			$this->ReserveFrom2 = stripslashes($this->ReserveFrom2);
			$this->KeteiDate = stripslashes($this->KeteiDate);
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
		
		if ($this->TatoCD < 0) {
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


					// step 4. インサート
					$sqlMoji = "Insert into $this->TableName (";
					#$sqlMoji .= "TatoCD"; AutoIncrementなので不要
					if ($this->ToName != "") $sqlMoji .= "ToName";
					if ($this->KyoyoStartDate != "") $sqlMoji .= ", KyoyoStartDate";
					if ($this->KyoyoEndDate != "") $sqlMoji .= ", KyoyoEndDate";
					if ($this->ReserveTo2 != "") $sqlMoji .= ", ReserveTo2";
					if ($this->ReserveFrom2 != "") $sqlMoji .= ", ReserveFrom2";
					if ($this->KeteiDate != "") $sqlMoji .= ", KeteiDate";
					if ($this->MukouFlg != "") $sqlMoji .= ", MukouFlg";
					$sqlMoji .= ", Created";
					if ($this->Creator != "") $sqlMoji .= ", Creator";
					$sqlMoji .= ", Updated";
					if ($this->Updater != "") $sqlMoji .= ", Updater";
					$sqlMoji .= ") values (";
					if ($this->ToName != "") $sqlMoji .= "'" . addslashes($this->ToName) . "'";
					if ($this->KyoyoStartDate != "") $sqlMoji .= ", '" . addslashes($this->KyoyoStartDate) . "'";
					if ($this->KyoyoEndDate != "") $sqlMoji .= ", '" . addslashes($this->KyoyoEndDate) . "'";
					if ($this->ReserveTo2 != "") $sqlMoji .= ", '" . addslashes($this->ReserveTo2) . "'";
					if ($this->ReserveFrom2 != "") $sqlMoji .= ", '" . addslashes($this->ReserveFrom2) . "'";
					if ($this->KeteiDate != "") $sqlMoji .= ", '" . addslashes($this->KeteiDate) . "'";
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
							$this->TatoCD = $maxCD;
						}
					}
				}
			}
		} else {
			// アップデート
			$sqlMoji = "Update $this->TableName set ";
			$sqlMoji .= "TatoCD = $this->TatoCD";
			$sqlMoji .= ", ToName = " . $this->convertNullStringValue($this->ToName);
			$sqlMoji .= ", KyoyoStartDate = " . $this->convertNullStringValue($this->KyoyoStartDate);
			$sqlMoji .= ", KyoyoEndDate = " . $this->convertNullStringValue($this->KyoyoEndDate);

			$sqlMoji .= ", ReserveTo2 = " . $this->convertNullStringValue($this->ReserveTo2);
			$sqlMoji .= ", ReserveFrom2 = " . $this->convertNullStringValue($this->ReserveFrom2);
			$sqlMoji .= ", KeteiDate = " . $this->convertNullStringValue($this->KeteiDate);

			$sqlMoji .= ", MukouFlg = " . $this->convertFlgToString($this->MukouFlg);
			$sqlMoji .= ", Created = " . $this->convertNullStringValue($this->Created);
			$sqlMoji .= ", Creator = " . $this->convertNullIntValue($this->Creator);
			$sqlMoji .= ", Updated = NOW()";
			$sqlMoji .= ", Updater = " . $this->convertNullIntValue($this->Updater);
			$sqlMoji .= " where TatoCD = $this->TatoCD";

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
