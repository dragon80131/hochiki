<?php
/**
 * Reservationクラス
 *
 * 予約マスタを扱うクラスです。
 *
 * @package 
 * @access  public
 * @author  MasahitoSAMEKAWA <masahito@souppot.jp>
 * @create  2003/01/31
 * @version 0.1
 **/

// 依存
include_once _CLS_DIR . "SPFWLog.cls";

class Reservation {
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
	 * 予約コード
	 * 
	 * 連番
	 */
	var $ReservationCD;
	/**
	 * クライアントコード
	 */
	var $ClientCD;
	/**
	 * スタイリストコード
	 */
	var $StylistCD;
	/**
	 * 会員コード
	 */
	var $UserCD;
	/**
	 * 予約開始時刻
	 */
	var $TimeFrom;
	/**
	 * 予約終了時刻
	 */
	var $TimeTo;
	/**
	 * メニューコード
	 */
	var $MenuCD;
	/**
	 * ステータス
	 * 
	 * 1:正常 2:取消
	 */
	var $Status;
	/**
	 * FREEフラグ
	 * 
	 * 指名でないことを示すフラグ
	 */
	var $FreeFlg;
	/**
	 * メモ
	 */
	var $Memo;
	/**
	 * 備考
	 */
	var $Notes;
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

	/**
	 * 会員ID　いわば　部屋番号　を増やす。20110626
	 */
	var $ID;


	var $SecondTimeFrom;
	/**
	 * 第2希望予約終了時刻
	 */
	var $SecondTimeTo;



	function Reservation($myDB = "",$ReservationCD = -1) {
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
		settype($ReservationCD,"integer");
		// 整数チェック
		if (!is_long($ReservationCD) or $ReservationCD == 0) {
			$this->Err = "ユーザーコード が不正です";
			$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
			unset($myLog);
			return FALSE;
		}

		$this->myDB = $myDB;
		$this->TableName = "tReservationF";
		$this->ReservationCD = $ReservationCD;

		return TRUE;
	}

	function initialize(){
		$this->Err = "";

		$this->ReservationCD = "";
		$this->ClientCD = "";
		$this->StylistCD = "";
		$this->UserCD = "";
		$this->TimeFrom = "";
		$this->TimeTo = "";
		$this->MenuCD = "";
		$this->Status = "";
		$this->FreeFlg = "";
		$this->Memo = "";
		$this->Notes = "";
		$this->MukouFlg = "";
		$this->Created = "";
		$this->Creator = "";
		$this->Updated = "";
		$this->Updater = "";
		$this->SecondTimeFrom = "";
		$this->SecondTimeTo = "";
		$this->ID = "";
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
		$sqlMoji .= "ReservationCD";
		$sqlMoji .= ", ClientCD";
		$sqlMoji .= ", StylistCD";
		$sqlMoji .= ", UserCD";
		$sqlMoji .= ", TimeFrom";
		$sqlMoji .= ", TimeTo";
		$sqlMoji .= ", MenuCD";
		$sqlMoji .= ", Status";
		$sqlMoji .= ", FreeFlg";
		$sqlMoji .= ", Memo";
		$sqlMoji .= ", Notes";
		$sqlMoji .= ", MukouFlg";
		$sqlMoji .= ", Created";
		$sqlMoji .= ", Creator";
		$sqlMoji .= ", Updated";
		$sqlMoji .= ", Updater";
		$sqlMoji .= ", SecondTimeFrom";
		$sqlMoji .= ", SecondTimeTo";
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
				$this->ReservationCD,
				$this->ClientCD,
				$this->StylistCD,
				$this->UserCD,
				$this->TimeFrom,
				$this->TimeTo,
				$this->MenuCD,
				$this->Status,
				$this->FreeFlg,
				$this->Memo,
				$this->Notes,
				$this->MukouFlg,
				$this->Created,
				$this->Creator,
				$this->Updated,
				$this->Updater,
				$this->SecondTimeFrom,
				$this->SecondTimeTo
			) = $this->myDB->fetchRow($this->tmprtn,$RowNo);

			$this->MenuCD = stripslashes($this->MenuCD);
			$this->FreeFlg = $this->convertFlgToBoolean($this->FreeFlg);
			$this->Memo = stripslashes($this->Memo);
			$this->Notes = stripslashes($this->Notes);
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
		
#		if ($this->ReservationCD < 0) {
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
					$sqlMoji = "select ReservationCD from $this->TableName order by ReservationCD desc limit 1";
					$maxCD = $this->myDB->getOneValue($sqlMoji);
					$maxCD = ($maxCD == -1) ? 0 : $maxCD;
					$maxCD++;

					// step 4. インサート
					$sqlMoji = "Insert into $this->TableName (";
					$sqlMoji .= "ReservationCD";
					if ($this->ClientCD != "") $sqlMoji .= ", ClientCD";
					if ($this->StylistCD != "") $sqlMoji .= ", StylistCD";
					if ($this->UserCD != "") $sqlMoji .= ", UserCD";
					if ($this->TimeFrom != "") $sqlMoji .= ", TimeFrom";
					if ($this->TimeTo != "") $sqlMoji .= ", TimeTo";
					if ($this->MenuCD != "") $sqlMoji .= ", MenuCD";
					if ($this->Status != "") $sqlMoji .= ", Status";
					if ($this->FreeFlg != "") $sqlMoji .= ", FreeFlg";
					if ($this->Memo != "") $sqlMoji .= ", Memo";
					if ($this->Notes != "") $sqlMoji .= ", Notes";
					if ($this->MukouFlg != "") $sqlMoji .= ", MukouFlg";
					$sqlMoji .= ", Created";
					if ($this->Creator != "") $sqlMoji .= ", Creator";
					$sqlMoji .= ", Updated";
					if ($this->Updater != "") $sqlMoji .= ", Updater";
					if ($this->SecondTimeFrom != "") $sqlMoji .= ", SecondTimeFrom";
					if ($this->SecondTimeTo != "") $sqlMoji .= ", SecondTimeTo";
					$sqlMoji .= ") values (";
					$sqlMoji .= "$maxCD";
					if ($this->ClientCD != "") $sqlMoji .= ", $this->ClientCD";
					if ($this->StylistCD != "") $sqlMoji .= ", $this->StylistCD";
					if ($this->UserCD != "") $sqlMoji .= ", $this->UserCD";
					if ($this->TimeFrom != "") $sqlMoji .= ", '" . addslashes($this->TimeFrom) . "'";
					if ($this->TimeTo != "") $sqlMoji .= ", '" . addslashes($this->TimeTo) . "'";
					if ($this->MenuCD != "") $sqlMoji .= ", '" . addslashes($this->MenuCD) . "'";
					if ($this->Status != "") $sqlMoji .= ", $this->Status";
					if ($this->FreeFlg != "") $sqlMoji .= ", " . $this->convertFlgToString($this->FreeFlg);
					if ($this->Memo != "") $sqlMoji .= ", '" . addslashes($this->Memo) . "'";
					if ($this->Notes != "") $sqlMoji .= ", '" . addslashes($this->Notes) . "'";
					if ($this->MukouFlg != "") $sqlMoji .= ", " . $this->convertFlgToString($this->MukouFlg);
					$sqlMoji .= ", '0000-00-00 00:00:00' ";
					if ($this->Creator != "") $sqlMoji .= ", $this->Creator";
					$sqlMoji .= ", '0000-00-00 00:00:00' ";
					if ($this->Updater != "") $sqlMoji .= ", $this->Updater";
					if ($this->SecondTimeFrom != "") $sqlMoji .= ", '" . addslashes($this->SecondTimeFrom) . "'";
					if ($this->SecondTimeTo != "") $sqlMoji .= ", '" . addslashes($this->SecondTimeTo) . "'";

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
							$this->ReservationCD = $maxCD;
						}
					}
				}
			}

		return TRUE;
	}

###20120517　修正時もInsertにする
	/* アップデート */
	function executeUpdate2() {
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

		if ($this->ReservationCD < 0) {
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
					$sqlMoji = "select ReservationCD from $this->TableName order by ReservationCD desc limit 1";
					$maxCD = $this->myDB->getOneValue($sqlMoji);
					$maxCD = ($maxCD == -1) ? 0 : $maxCD;
					$maxCD++;

					// step 4. インサート
					$sqlMoji = "Insert into $this->TableName (";
					$sqlMoji .= "ReservationCD";
					if ($this->ClientCD != "") $sqlMoji .= ", ClientCD";
					if ($this->StylistCD != "") $sqlMoji .= ", StylistCD";
					if ($this->UserCD != "") $sqlMoji .= ", UserCD";
					if ($this->TimeFrom != "") $sqlMoji .= ", TimeFrom";
					if ($this->TimeTo != "") $sqlMoji .= ", TimeTo";
					if ($this->MenuCD != "") $sqlMoji .= ", MenuCD";
					if ($this->Status != "") $sqlMoji .= ", Status";
					if ($this->FreeFlg != "") $sqlMoji .= ", FreeFlg";
					if ($this->Memo != "") $sqlMoji .= ", Memo";
					if ($this->Notes != "") $sqlMoji .= ", Notes";
					if ($this->MukouFlg != "") $sqlMoji .= ", MukouFlg";
					$sqlMoji .= ", Created";
					if ($this->Creator != "") $sqlMoji .= ", Creator";
					$sqlMoji .= ", Updated";
					if ($this->Updater != "") $sqlMoji .= ", Updater";
					if ($this->SecondTimeFrom != "") $sqlMoji .= ", SecondTimeFrom";
					if ($this->SecondTimeTo != "") $sqlMoji .= ", SecondTimeTo";
					$sqlMoji .= ") values (";
					$sqlMoji .= "$maxCD";
					if ($this->ClientCD != "") $sqlMoji .= ", $this->ClientCD";
					if ($this->StylistCD != "") $sqlMoji .= ", $this->StylistCD";
					if ($this->UserCD != "") $sqlMoji .= ", $this->UserCD";
					if ($this->TimeFrom != "") $sqlMoji .= ", '" . addslashes($this->TimeFrom) . "'";
					if ($this->TimeTo != "") $sqlMoji .= ", '" . addslashes($this->TimeTo) . "'";
					if ($this->MenuCD != "") $sqlMoji .= ", '" . addslashes($this->MenuCD) . "'";
					if ($this->Status != "") $sqlMoji .= ", $this->Status";
					if ($this->FreeFlg != "") $sqlMoji .= ", " . $this->convertFlgToString($this->FreeFlg);
					if ($this->Memo != "") $sqlMoji .= ", '" . addslashes($this->Memo) . "'";
					if ($this->Notes != "") $sqlMoji .= ", '" . addslashes($this->Notes) . "'";
					if ($this->MukouFlg != "") $sqlMoji .= ", " . $this->convertFlgToString($this->MukouFlg);
					$sqlMoji .= ", NOW()";
					if ($this->Creator != "") $sqlMoji .= ", $this->Creator";
					$sqlMoji .= ", NOW()";
					if ($this->Updater != "") $sqlMoji .= ", $this->Updater";
					if ($this->SecondTimeFrom != "") $sqlMoji .= ", '" . addslashes($this->SecondTimeFrom) . "'";
					if ($this->SecondTimeTo != "") $sqlMoji .= ", '" . addslashes($this->SecondTimeTo) . "'";
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
							$this->ReservationCD = $maxCD;
						}
					}
				}
			}
		} else {
###修正の場合は、ステータスを１→２にして、さらに、新規と同じようにInsert
			// アップデート ステータスだけをアップデートする。Update日付は害がなさそうなのでこれもUPDATE
			$sqlMoji = "Update $this->TableName set ";
			$sqlMoji .= " Status = 2";
			$sqlMoji .= ", Updated = NOW()";
			$sqlMoji .= ", Updater = " . $this->convertNullIntValue($this->Updater);
			$sqlMoji .= " where ReservationCD = $this->ReservationCD";

			$rtn = $this->myDB->executeQuery($sqlMoji);
			if (!$rtn){
				$this->Err = "更新処理に失敗しました";
				$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
				unset($myLog);
				return FALSE;
			}
###Insert部分
			// 新規　と同じなのでコピーした
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
					$sqlMoji = "select ReservationCD from $this->TableName order by ReservationCD desc limit 1";
					$maxCD = $this->myDB->getOneValue($sqlMoji);
					$maxCD = ($maxCD == -1) ? 0 : $maxCD;
					$maxCD++;

					// step 4. インサート
					$sqlMoji = "Insert into $this->TableName (";
					$sqlMoji .= "ReservationCD";
					if ($this->ClientCD != "") $sqlMoji .= ", ClientCD";
					if ($this->StylistCD != "") $sqlMoji .= ", StylistCD";
					if ($this->UserCD != "") $sqlMoji .= ", UserCD";
					if ($this->TimeFrom != "") $sqlMoji .= ", TimeFrom";
					if ($this->TimeTo != "") $sqlMoji .= ", TimeTo";
					if ($this->MenuCD != "") $sqlMoji .= ", MenuCD";
					if ($this->Status != "") $sqlMoji .= ", Status";
					if ($this->FreeFlg != "") $sqlMoji .= ", FreeFlg";
					if ($this->Memo != "") $sqlMoji .= ", Memo";
					if ($this->Notes != "") $sqlMoji .= ", Notes";
					if ($this->MukouFlg != "") $sqlMoji .= ", MukouFlg";
					$sqlMoji .= ", Created";
					if ($this->Creator != "") $sqlMoji .= ", Creator";
					$sqlMoji .= ", Updated";
					if ($this->Updater != "") $sqlMoji .= ", Updater";
					if ($this->SecondTimeFrom != "") $sqlMoji .= ", SecondTimeFrom";
					if ($this->SecondTimeTo != "") $sqlMoji .= ", SecondTimeTo";
					$sqlMoji .= ") values (";
					$sqlMoji .= "$maxCD";
					if ($this->ClientCD != "") $sqlMoji .= ", $this->ClientCD";
					if ($this->StylistCD != "") $sqlMoji .= ", $this->StylistCD";
					if ($this->UserCD != "") $sqlMoji .= ", $this->UserCD";
					if ($this->TimeFrom != "") $sqlMoji .= ", '" . addslashes($this->TimeFrom) . "'";
					if ($this->TimeTo != "") $sqlMoji .= ", '" . addslashes($this->TimeTo) . "'";
					if ($this->MenuCD != "") $sqlMoji .= ", '" . addslashes($this->MenuCD) . "'";
					if ($this->Status != "") $sqlMoji .= ", $this->Status";
					if ($this->FreeFlg != "") $sqlMoji .= ", " . $this->convertFlgToString($this->FreeFlg);
					if ($this->Memo != "") $sqlMoji .= ", '" . addslashes($this->Memo) . "'";
					if ($this->Notes != "") $sqlMoji .= ", '" . addslashes($this->Notes) . "'";
					if ($this->MukouFlg != "") $sqlMoji .= ", " . $this->convertFlgToString($this->MukouFlg);
					$sqlMoji .= ", NOW()";
					if ($this->Creator != "") $sqlMoji .= ", $this->Creator";
					$sqlMoji .= ", NOW()";
					if ($this->Updater != "") $sqlMoji .= ", $this->Updater";
					if ($this->SecondTimeFrom != "") $sqlMoji .= ", '" . addslashes($this->SecondTimeFrom) . "'";
					if ($this->SecondTimeTo != "") $sqlMoji .= ", '" . addslashes($this->SecondTimeTo) . "'";
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
							$this->ReservationCD = $maxCD;
						}
					}
				}
			}
###

		}

		return TRUE;
	}
####20120517　END


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

	function checkOpen($Obj, $Day, $StylistCD, $QuickFlg = FALSE) {
		$myLog = new SPFWLog();

		$MyTimestamp = mktime(0, 0, 0, substr($Day, 5, 2), substr($Day, 8, 2), substr($Day, 0, 4));
		$MyWeekday = date('w', $MyTimestamp);

		$OpenTime = $Obj['OpenTime'];
		$CloseTime = $Obj['CloseTime'];
		$LunchTimeFrom = $Obj['LunchTimeFrom'];
		$LunchTimeTo = $Obj['LunchTimeTo'];
		$EveningTimeFrom = $Obj['EveningTimeFrom'];
		$EveningTimeTo = $Obj['EveningTimeTo'];
		$Holiday = $Obj['Holiday'];
		$MINUTEUNIT = $Obj['MINUTEUNIT'];
		$TempDate = $Obj['Day'];
		$HolidaySetting[$Day] = $Obj['HolidaySetting'][$Day];
		$Reservation[$Day] = $Obj['Reservation'][$Day];
		$LinesLoop = $Obj['LinesLoop'];
		$StylistLines = $Obj['StylistLines'];
		$StylistCDs = $Obj['StylistCDs'];
		$StylistOpenTime[$StylistCD] = $Obj['StylistOpenTime'][$StylistCD][$MyWeekday];
		$StylistCloseTime[$StylistCD] = $Obj['StylistCloseTime'][$StylistCD][$MyWeekday];
		$StylistLunchTimeFrom[$StylistCD] = $Obj['StylistLunchTimeFrom'][$StylistCD][$MyWeekday];
		$StylistLunchTimeTo[$StylistCD] = $Obj['StylistLunchTimeTo'][$StylistCD][$MyWeekday];
		$StylistEveningTimeFrom[$StylistCD] = $Obj['StylistEveningTimeFrom'][$StylistCD][$MyWeekday];
		$StylistEveningTimeTo[$StylistCD] = $Obj['StylistEveningTimeTo'][$StylistCD][$MyWeekday];
		$StylistHoliday[$StylistCD] = $Obj['StylistHoliday'][$StylistCD][$MyWeekday];
		$StylistNumberOfLines[$StylistCD] = $Obj['StylistNumberOfLines'][$StylistCD][$MyWeekday];

		$MyOpenTime = $OpenTime[$MyWeekday];
		$MyCloseTime = $CloseTime[$MyWeekday];
		$MyLunchTimeFrom = $LunchTimeFrom[$MyWeekday];
		$MyLunchTimeTo = $LunchTimeTo[$MyWeekday];
		$MyEveningTimeFrom = $EveningTimeFrom[$MyWeekday];
		$MyEveningTimeTo = $EveningTimeTo[$MyWeekday];
		$MyHoliday = $Holiday[$MyWeekday];

		$MyOpenTimeHour = intval(substr($MyOpenTime, 0, 2));
		$MyOpenTimeMinute = intval(substr($MyOpenTime, 3, 2));
		$MyCloseTimeHour = intval(substr($MyCloseTime, 0, 2));
		$MyCloseTimeMinute = intval(substr($MyCloseTime, 3, 2));

		for ($i = $MyOpenTimeHour; $i <= $MyCloseTimeHour; $i++) {
			for ($j = 0; $j < 60; $j += $MINUTEUNIT) {
				if ($i == $MyCloseTimeHour && $MyCloseTimeMinute >= $j)
					break;

				$TimesHour[] = $i;
				$TimesMinute[] = $j;
				$Times[] = sprintf("%02d:%02d", $i, $j);
			}
		}
		$TimesLoop = count($Times);

		// 時間別の予約ライン数をカウント
		unset($ReserveStatus);

		/*$myLog->debug($Day);
		$myLog->debug($StylistCD);*/

		for ($i = 0; $i < $TimesLoop; $i++) {
			$myLog->debug($Times[$i]);
			$ReserveStatus[$Times[$i]] = 0;

			for ($j = 0; $j < $LinesLoop; $j++) {
				if ($StylistCDs[$j] != $StylistCD) {
					$Counter[$j] = 0;
					continue;
				}

				if ($MenuByStaffFlg && !$StylistMenuOK[$StylistCDs[$j]]) {
					$Counters[] = $Counter[$j];
					$Counter[$j] = 0;

					// そのメニューを担当できない
					$myLog->debug($Day . " " . $StylistCD . " " . "そのメニューを担当できない");
				}
				else if ($Reservation[$Day][$Times[$i]][$StylistCDs[$j]]['ReservationCD'][$StylistLines[$j]] > 0) {
					$Counters[] = $Counter[$j];
					$Counter[$j] = 0;

					// 予約が入っている
					$myLog->debug($Day . " " . $StylistCD . " " . "予約が入っている");
				}
				else if ($Times[$i] >= $MyLunchTimeFrom && $Times[$i] < $MyLunchTimeTo) {
					$Counters[] = $Counter[$j];
					$Counter[$j] = 0;

					// 予約が入っている
					$myLog->debug($Day . " " . $StylistCD . " " . "昼休み");
				}
				else if ($Times[$i] >= $MyEveningTimeFrom && $Times[$i] < $MyEveningTimeTo) {
					$Counters[] = $Counter[$j];
					$Counter[$j] = 0;

					// 予約が入っている
					$myLog->debug($Day . " " . $StylistCD . " " . "夕方休み");
				}
				else if ($MyHoliday == 't') {
					$Counters[] = $Counter[$j];
					$Counter[$j] = 0;

					// 予約が入っている
					$myLog->debug($Day . " " . $StylistCD . " " . "個人休日");
				}
				else if ($ShopHolidayFlg || $HolidaySetting[$Day]['all'][-1]) {
					$Counters[] = $Counter[$j];
					$Counter[$j] = 0;

					// 予約が入っている
					$myLog->debug($Day . " " . $StylistCD . " " . "店舗休");
				}
				else if ($HolidaySetting[$Day][$Times[$i]][$StylistCDs[$j]] || $HolidaySetting[$Day][$Times[$i]][-1] || $HolidaySetting[$Day]['all'][$StylistCDs[$j]]) {
					$Counters[] = $Counter[$j];
					$Counter[$j] = 0;

					// 予約が入っている
					$myLog->debug($Day . " " . $StylistCD . " " . "店舗休 または 設定休");
				}
				else if ($StylistOpenTime[$StylistCDs[$j]] > $Times[$i] || $StylistCloseTime[$StylistCDs[$j]] <= $Times[$i]) {
					$Counters[] = $Counter[$j];
					$Counter[$j] = 0;

					// 予約が入っている
					$myLog->debug($Day . " " . $StylistCD . " " . "個人設定 勤務時間外");
				}
				else if ($StylistLunchTimeFrom[$StylistCDs[$j]] <= $Times[$i] && $StylistLunchTimeTo[$StylistCDs[$j]] > $Times[$i]) {
					$Counters[] = $Counter[$j];
					$Counter[$j] = 0;

					// 昼休み
					$myLog->debug($Day . " " . $StylistCD . " " . "個人設定 昼休み");
				}
				else if ($StylistEveningTimeFrom[$StylistCDs[$j]] <= $Times[$i] && $StylistEveningTimeTo[$StylistCDs[$j]] > $Times[$i]) {
					$Counters[] = $Counter[$j];
					$Counter[$j] = 0;

					// 昼休み
					$myLog->debug($Day . " " . $StylistCD . " " . "個人設定 夕方休み");
				}
				else if ($StylistHoliday[$StylistCDs[$j]] == 't') {
					$Counters[] = $Counter[$j];
					$Counter[$j] = 0;

					// スタッフ週休
					$myLog->debug($Day . " " . $StylistCD . " " . "スタッフ週休");
				}
				else {
					$Counter[$j]++;

					$ReserveStatus[$StylistCDs[$j]][$Times[$i]]++;
					$myLog->debug($Day . " " . $StylistCD . " " . "予約可能");

					if ($QuickFlg)
						return TRUE;
				}
			}
		}

		$Maximum = max($Counters);
		if ($Maximum > 0)
			return $Maximum;
		else
			return FALSE;
	}
}
?>
