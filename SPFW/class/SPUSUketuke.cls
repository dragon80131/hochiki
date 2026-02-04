<?php
/**
 * Uketukeクラス
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

class Uketuke {
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
	var $UketukeNo;
	var $UserName;
	var $CheckName;
	var $MansionName;
	var $TimeFrom;
	var $MenuName1;
	var $MenuName2;
	var $MenuName3;
	var $MenuName4;
	var $MenuName5;
	var $MenuName6;
	var $MenuName7;
	var $Yobi1;
	var $Yobi2;
	var $Yobi3;
	var $Yobi4;
	var $Yobi5;
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




	function Uketuke($myDB2 = "",$Uketuke = -1) {
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
		settype($UketukeNo,"integer");
		// 整数チェック
#		if (!is_long($UketukeNo) or $UketukeNo == 0) {
		if (!is_long($UketukeNo)) {
			$this->Err = "ユーザーコード が不正です".$UketukeNo ;
			$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
			unset($myLog);
			return FALSE;
		}

		$this->myDB2 = $myDB2;
		$this->TableName = "tUketukeF";
		$this->UketukeNo = $UketukeNo;

		return TRUE;
	}

	function initialize(){
		$this->Err = "";

		$this->UketukeNo = "";
		$this->UserName = "";
		$this->CheckName = "";
		$this->MansionName = "";
		$this->TimeFrom = "";
		$this->MenuName1 = "";
		$this->MenuName2 = "";
		$this->MenuName3 = "";
		$this->MenuName4 = "";
		$this->MenuName5 = "";
		$this->MenuName6 = "";
		$this->MenuName7 = "";
		$this->Created = "";
		$this->Creator = "";
		$this->Updated = "";
		$this->Updater = "";
		$this->Yobi1 = "";
		$this->Yobi2 = "";
		$this->Yobi3 = "";
		$this->Yobi4 = "";
		$this->Yobi5 = "";
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
		$sqlMoji .= "UketukeNo";
		$sqlMoji .= ", UserName";
		$sqlMoji .= ", CheckName";
		$sqlMoji .= ", MansionName";
		$sqlMoji .= ", TimeFrom";
		$sqlMoji .= ", Memo";
		$sqlMoji .= ", MenuName1";
		$sqlMoji .= ", MenuName2";
		$sqlMoji .= ", MenuName3";
		$sqlMoji .= ", MenuName4";
		$sqlMoji .= ", MenuName5";
		$sqlMoji .= ", MenuName6";
		$sqlMoji .= ", MenuName7";
		$sqlMoji .= ", Created";
		$sqlMoji .= ", Creator";
		$sqlMoji .= ", Updated";
		$sqlMoji .= ", Updater";
		$sqlMoji .= ", Yobi1";
		$sqlMoji .= ", Yobi2";
		$sqlMoji .= ", Yobi3";
		$sqlMoji .= ", Yobi4";
		$sqlMoji .= ", Yobi5";
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
				$this->UketukeNo,
				$this->UserName,
				$this->CheckName,
				$this->MansionName,
				$this->TimeFrom,
				$this->Memo,
				$this->MenuName1,
				$this->MenuName2,
				$this->MenuName3,
				$this->MenuName4,
				$this->MenuName5,
				$this->MenuName6,
				$this->MenuName7,
				$this->Created,
				$this->Creator,
				$this->Updated,
				$this->Updater,
				$this->Yobi1,
				$this->Yobi2,
				$this->Yobi3,
				$this->Yobi4,
				$this->Yobi5
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
		
#		if ($this->UketukeNo < 0) {
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
					$sqlMoji = "select UketukeNo from $this->TableName order by UketukeNo desc limit 1";
					$maxCD = $this->myDB2->getOneValue($sqlMoji);
					$maxCD = ($maxCD == -1) ? 0 : $maxCD;
					$maxCD++;

					// step 4. インサート
					$sqlMoji = "Insert into $this->TableName (";

					$sqlMoji .= "UketukeNo";
					if ($this->UserName != "") $sqlMoji .= ", UserName";
					if ($this->CheckName != "") $sqlMoji .= ", CheckName";
					if ($this->MansionName != "") $sqlMoji .= ", MansionName";
					if ($this->TimeFrom != "") $sqlMoji .= ", TimeFrom";
					if ($this->Memo != "") $sqlMoji .= ", Memo";
					if ($this->MenuName1 != "") $sqlMoji .= ", MenuName1";
					if ($this->MenuName2 != "") $sqlMoji .= ", MenuName2";
					if ($this->MenuName3 != "") $sqlMoji .= ", MenuName3";
					if ($this->MenuName4 != "") $sqlMoji .= ", MenuName4";
					if ($this->MenuName5 != "") $sqlMoji .= ", MenuName5";
					if ($this->MenuName6 != "") $sqlMoji .= ", MenuName6";
					if ($this->MenuName7 != "") $sqlMoji .= ", MenuName7";
					$sqlMoji .= ", Created";
					if ($this->Creator != "") $sqlMoji .= ", Creator";
					$sqlMoji .= ", Updated";
					if ($this->Updater != "") $sqlMoji .= ", Updater";
					if ($this->Yobi1 != "") $sqlMoji .= ", Yobi1";
					if ($this->Yobi2 != "") $sqlMoji .= ", Yobi2";
					if ($this->Yobi3 != "") $sqlMoji .= ", Yobi3";
					if ($this->Yobi4 != "") $sqlMoji .= ", Yobi4";
					if ($this->Yobi5 != "") $sqlMoji .= ", Yobi5";
					$sqlMoji .= ") values (";

					$sqlMoji .= "$maxCD";
					if ($this->UserName != "") $sqlMoji .= ", '" . addslashes($this->UserName) . "'";
					if ($this->CheckName != "") $sqlMoji .= ", '" . addslashes($this->CheckName) . "'";
					if ($this->MansionName != "") $sqlMoji .= ", '" . addslashes($this->MansionName) . "'";
					if ($this->TimeFrom != "")    $sqlMoji .= ", '" . addslashes($this->TimeFrom) . "'";
					if ($this->Memo != "")    $sqlMoji .= ", '" . addslashes($this->Memo) . "'";
					if ($this->MenuName1 != "") $sqlMoji .= ", '" . addslashes($this->MenuName1) . "'";
					if ($this->MenuName2 != "") $sqlMoji .= ", '" . addslashes($this->MenuName2) . "'";
					if ($this->MenuName3 != "") $sqlMoji .= ", '" . addslashes($this->MenuName3) . "'";
					if ($this->MenuName4 != "") $sqlMoji .= ", '" . addslashes($this->MenuName4) . "'";
					if ($this->MenuName5 != "") $sqlMoji .= ", '" . addslashes($this->MenuName5) . "'";
					if ($this->MenuName6 != "") $sqlMoji .= ", '" . addslashes($this->MenuName6) . "'";
					if ($this->MenuName7 != "") $sqlMoji .= ", '" . addslashes($this->MenuName7) . "'";
					$sqlMoji .= ", NOW()";
					if ($this->Creator != "")   $sqlMoji .= ", '" . addslashes($this->Creator) . "'";
					$sqlMoji .= ", NOW()";
					if ($this->Updater != "")   $sqlMoji .= ", '" . addslashes($this->Updater) . "'";
					if ($this->Yobi1 != "") $sqlMoji .= ", '" . addslashes($this->Yobi1) . "'";
					if ($this->Yobi2 != "") $sqlMoji .= ", '" . addslashes($this->Yobi2) . "'";
					if ($this->Yobi3 != "") $sqlMoji .= ", '" . addslashes($this->Yobi3) . "'";
					if ($this->Yobi4 != "") $sqlMoji .= ", '" . addslashes($this->Yobi4) . "'";
					if ($this->Yobi5 != "") $sqlMoji .= ", '" . addslashes($this->Yobi5) . "'";
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
							$this->UketukeNo = $maxCD;
						}
					}
				}
			}
#		} else {
#		}

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

	function checkOpen($Obj, $Day, $CheckName, $QuickFlg = FALSE) {
		$myLog = new SPFWLog();

		$MyTimestamp = mktime(0, 0, 0, substr($Day, 5, 2), substr($Day, 8, 2), substr($Day, 0, 4));
		$MyWeekday = date('w', $MyTimestamp);

		$OpenTime = $Obj['OpenTime'];
		$CloseTime = $Obj['CloseTime'];
		$LunchTimeFrom = $Obj['LunchTimeFrom'];
		$LunchMenuName1 = $Obj['LunchMenuName1'];
		$EveningTimeFrom = $Obj['EveningTimeFrom'];
		$EveningMenuName1 = $Obj['EveningMenuName1'];
		$Holiday = $Obj['Holiday'];
		$MINUTEUNIT = $Obj['MINUTEUNIT'];
		$TempDate = $Obj['Day'];
		$HolidaySetting[$Day] = $Obj['HolidaySetting'][$Day];
		$Reservation[$Day] = $Obj['Reservation'][$Day];
		$LinesLoop = $Obj['LinesLoop'];
		$StylistLines = $Obj['StylistLines'];
		$CheckNames = $Obj['CheckNames'];
		$StylistOpenTime[$CheckName] = $Obj['StylistOpenTime'][$CheckName][$MyWeekday];
		$StylistCloseTime[$CheckName] = $Obj['StylistCloseTime'][$CheckName][$MyWeekday];
		$StylistLunchTimeFrom[$CheckName] = $Obj['StylistLunchTimeFrom'][$CheckName][$MyWeekday];
		$StylistLunchMenuName1[$CheckName] = $Obj['StylistLunchMenuName1'][$CheckName][$MyWeekday];
		$StylistEveningTimeFrom[$CheckName] = $Obj['StylistEveningTimeFrom'][$CheckName][$MyWeekday];
		$StylistEveningMenuName1[$CheckName] = $Obj['StylistEveningMenuName1'][$CheckName][$MyWeekday];
		$StylistHoliday[$CheckName] = $Obj['StylistHoliday'][$CheckName][$MyWeekday];
		$StylistNumberOfLines[$CheckName] = $Obj['StylistNumberOfLines'][$CheckName][$MyWeekday];

		$MyOpenTime = $OpenTime[$MyWeekday];
		$MyCloseTime = $CloseTime[$MyWeekday];
		$MyLunchTimeFrom = $LunchTimeFrom[$MyWeekday];
		$MyLunchMenuName1 = $LunchMenuName1[$MyWeekday];
		$MyEveningTimeFrom = $EveningTimeFrom[$MyWeekday];
		$MyEveningMenuName1 = $EveningMenuName1[$MyWeekday];
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
		unset($ReserveMenuName3);

		/*$myLog->debug($Day);
		$myLog->debug($CheckName);*/

		for ($i = 0; $i < $TimesLoop; $i++) {
			$myLog->debug($Times[$i]);
			$ReserveMenuName3[$Times[$i]] = 0;

			for ($j = 0; $j < $LinesLoop; $j++) {
				if ($CheckNames[$j] != $CheckName) {
					$Counter[$j] = 0;
					continue;
				}

				if ($MenuByStaffFlg && !$StylistMenuOK[$CheckNames[$j]]) {
					$Counters[] = $Counter[$j];
					$Counter[$j] = 0;

					// そのメニューを担当できない
					$myLog->debug($Day . " " . $CheckName . " " . "そのメニューを担当できない");
				}
				else if ($Reservation[$Day][$Times[$i]][$CheckNames[$j]]['UketukeNo'][$StylistLines[$j]] > 0) {
					$Counters[] = $Counter[$j];
					$Counter[$j] = 0;

					// 予約が入っている
					$myLog->debug($Day . " " . $CheckName . " " . "予約が入っている");
				}
				else if ($Times[$i] >= $MyLunchTimeFrom && $Times[$i] < $MyLunchMenuName1) {
					$Counters[] = $Counter[$j];
					$Counter[$j] = 0;

					// 予約が入っている
					$myLog->debug($Day . " " . $CheckName . " " . "昼休み");
				}
				else if ($Times[$i] >= $MyEveningTimeFrom && $Times[$i] < $MyEveningMenuName1) {
					$Counters[] = $Counter[$j];
					$Counter[$j] = 0;

					// 予約が入っている
					$myLog->debug($Day . " " . $CheckName . " " . "夕方休み");
				}
				else if ($MyHoliday == 't') {
					$Counters[] = $Counter[$j];
					$Counter[$j] = 0;

					// 予約が入っている
					$myLog->debug($Day . " " . $CheckName . " " . "個人休日");
				}
				else if ($ShopHolidayFlg || $HolidaySetting[$Day]['all'][-1]) {
					$Counters[] = $Counter[$j];
					$Counter[$j] = 0;

					// 予約が入っている
					$myLog->debug($Day . " " . $CheckName . " " . "店舗休");
				}
				else if ($HolidaySetting[$Day][$Times[$i]][$CheckNames[$j]] || $HolidaySetting[$Day][$Times[$i]][-1] || $HolidaySetting[$Day]['all'][$CheckNames[$j]]) {
					$Counters[] = $Counter[$j];
					$Counter[$j] = 0;

					// 予約が入っている
					$myLog->debug($Day . " " . $CheckName . " " . "店舗休 または 設定休");
				}
				else if ($StylistOpenTime[$CheckNames[$j]] > $Times[$i] || $StylistCloseTime[$CheckNames[$j]] <= $Times[$i]) {
					$Counters[] = $Counter[$j];
					$Counter[$j] = 0;

					// 予約が入っている
					$myLog->debug($Day . " " . $CheckName . " " . "個人設定 勤務時間外");
				}
				else if ($StylistLunchTimeFrom[$CheckNames[$j]] <= $Times[$i] && $StylistLunchMenuName1[$CheckNames[$j]] > $Times[$i]) {
					$Counters[] = $Counter[$j];
					$Counter[$j] = 0;

					// 昼休み
					$myLog->debug($Day . " " . $CheckName . " " . "個人設定 昼休み");
				}
				else if ($StylistEveningTimeFrom[$CheckNames[$j]] <= $Times[$i] && $StylistEveningMenuName1[$CheckNames[$j]] > $Times[$i]) {
					$Counters[] = $Counter[$j];
					$Counter[$j] = 0;

					// 昼休み
					$myLog->debug($Day . " " . $CheckName . " " . "個人設定 夕方休み");
				}
				else if ($StylistHoliday[$CheckNames[$j]] == 't') {
					$Counters[] = $Counter[$j];
					$Counter[$j] = 0;

					// スタッフ週休
					$myLog->debug($Day . " " . $CheckName . " " . "スタッフ週休");
				}
				else {
					$Counter[$j]++;

					$ReserveMenuName3[$CheckNames[$j]][$Times[$i]]++;
					$myLog->debug($Day . " " . $CheckName . " " . "予約可能");

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
