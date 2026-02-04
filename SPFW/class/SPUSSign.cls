<?php


// 依存
include_once _CLS_DIR . "SPFWLog.cls";

class Sign {
	/**
	 * データベースクラスのインスタンス
	 */
	Public $myDB;
	/**
	 * レコードカウント
	 */
	Public $RecCnt;
	/**
	 * クエリ実行結果セット
	 */
	Public $tmprtn;
	/**
	 * 対象テーブル名称
	 */
	Public $TableName;
	/**
	 * 予約コード
	 * 
	 * 連番
	 */
	Public $SignCD;
	/**
	 * クライアントコード
	 */
	Public $ClientCD;
	/**
	 * スタイリストコード
	 */
	Public $BukkenCD;
	Public $BuildingCD;
	/**
	 * 会員コード
	 */
	Public $UserCD;
	/**
	 * 予約開始時刻
	 */
	Public $ID;
	/**
	 * 予約終了時刻
	 */
	Public $FilePath;
	/**
	 * メニューコード
	 */
	Public $R001;
	/**
	 * ステータス
	 * 
	 * 1:正常 2:取消
	 */
	Public $R002;
	/**
	 * FREEフラグ
	 * 
	 * 指名でないことを示すフラグ
	 */
	Public $R003;
	/**
	 * メモ
	 */
	Public $R004;
	/**
	 * 備考
	 */
	/**
	 * 無効フラグ
	 */
	Public $MukouFlg;
	/**
	 * 作成日時
	 */
	Public $Created;
	/**
	 * 作成者
	 */
	Public $Creator;
	/**
	 * 更新日時
	 */
	Public $Updated;
	/**
	 * 更新者
	 */
	Public $Updater;


	Public $Err;
	function __construct($myDB = "",$SignCD = -1) {
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
		settype($SignCD,"integer");
		// 整数チェック
		if (!is_long($SignCD) or $SignCD == 0) {
			$this->Err = "ユーザーコード が不正です";
			$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
			unset($myLog);
			return FALSE;
		}

		$this->myDB = $myDB;
		$this->TableName = "tSignF";
		$this->SignCD = $SignCD;

		return TRUE;
	}

	function initialize(){
		$this->Err = "";

		$this->SignCD = "";
		$this->ClientCD = "";
		$this->BukkenCD = "";
		$this->BuildingCD = "";
		$this->UserCD = "";
		$this->ID = "";
		$this->FilePath = "";
		$this->R001 = "";
		$this->R002 = "";
		$this->R003 = "";
		$this->R004 = "";
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
		$sqlMoji .= "SignCD";
		$sqlMoji .= ", ClientCD";
		$sqlMoji .= ", BukkenCD";
		$sqlMoji .= ", BuildingCD";
		$sqlMoji .= ", UserCD";
		$sqlMoji .= ", ID";
		$sqlMoji .= ", FilePath";
		$sqlMoji .= ", R001";
		$sqlMoji .= ", R002";
		$sqlMoji .= ", R003";
		$sqlMoji .= ", R004";
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
// #調査ミサイル
// $fh = fopen("D:/xampp/htdocs/kotei3/httpdocs/aaa.txt", "a");
// fwrite($fh,"\n Signクラス内:END");
// fclose($fh);
// #調査ミサイルEnd
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
				$this->SignCD,
				$this->ClientCD,
				$this->BukkenCD,
				$this->BuildingCD,
				$this->UserCD,
				$this->ID,
				$this->FilePath,
				$this->R001,
				$this->R002,
				$this->R003,
				$this->R004,
				$this->MukouFlg,
				$this->Created,
				$this->Creator,
				$this->Updated,
				$this->Updater
			) = $this->myDB->fetchRow($this->tmprtn,$RowNo);

			$this->ClientCD = stripslashes($this->ClientCD ?? '');
			$this->BukkenCD = stripslashes($this->BukkenCD ?? '');
			$this->BuildingCD = stripslashes($this->BuildingCD ?? '');
			$this->UserCD = stripslashes($this->UserCD ?? '');
			$this->ID = stripslashes($this->ID ?? '');
			$this->FilePath = stripslashes($this->FilePath ?? '');
			$this->R001 = stripslashes($this->R001 ?? '');
			$this->R002 = stripslashes($this->R002 ?? '');
			$this->R003 = stripslashes($this->R003 ?? '');
			$this->R004 = stripslashes($this->R004 ?? '');
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
		
		if ($this->SignCD < 0) {
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
					$sqlMoji = "select SignCD from $this->TableName order by SignCD desc limit 1";
					$maxCD = $this->myDB->getOneValue($sqlMoji);
					$maxCD = ($maxCD == -1) ? 0 : $maxCD;
					$maxCD++;

					// step 4. インサート
					$sqlMoji = "Insert into $this->TableName (";

					$sqlMoji .= "SignCD";
					if ($this->ClientCD != "") $sqlMoji .= ", ClientCD";
					if ($this->BukkenCD != "") $sqlMoji .= ", BukkenCD";
					if ($this->BuildingCD != "") $sqlMoji .= ", BuildingCD";
					if ($this->UserCD != "") $sqlMoji .= ", UserCD";
					if ($this->ID != "") $sqlMoji .= ", ID";
					if ($this->FilePath != "") $sqlMoji .= ", FilePath";
					if ($this->R001 != "") $sqlMoji .= ", R001";
					if ($this->R002 != "") $sqlMoji .= ", R002";
					if ($this->R003 != "") $sqlMoji .= ", R003";
					if ($this->R004 != "") $sqlMoji .= ", R004";
					if ($this->MukouFlg != "") $sqlMoji .= ", MukouFlg";
					$sqlMoji .= ", Created";
					if ($this->Creator != "") $sqlMoji .= ", Creator";
					$sqlMoji .= ", Updated";
					if ($this->Updater != "") $sqlMoji .= ", Updater";

					$sqlMoji .= ") values (";

					$sqlMoji .= "$maxCD";
					if ($this->ClientCD != "") $sqlMoji .= ", $this->ClientCD";
					if ($this->BukkenCD != "") $sqlMoji .= ", $this->BukkenCD";
					if ($this->BuildingCD != "") $sqlMoji .= ", $this->BuildingCD";
					if ($this->UserCD != "") $sqlMoji .= ", $this->UserCD";
					if ($this->ID != "") $sqlMoji .= ", '" . addslashes($this->ID) . "'";
					if ($this->FilePath != "") $sqlMoji .= ", '" . addslashes($this->FilePath) . "'";
					if ($this->R001 != "") $sqlMoji .= ", '" . addslashes($this->R001) . "'";
					if ($this->R002 != "") $sqlMoji .= ", $this->R002";
					if ($this->R003 != "") $sqlMoji .= ", " . $this->convertFlgToString($this->R003);
					if ($this->R004 != "") $sqlMoji .= ", '" . addslashes($this->R004) . "'";
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
							$this->SignCD = $maxCD;
						}
					}
				}
			}
		} else {
			// アップデート
			$sqlMoji = "Update $this->TableName set ";
			$sqlMoji .= "SignCD = $this->SignCD";
			$sqlMoji .= ", ClientCD = " . $this->convertNullIntValue($this->ClientCD);
			$sqlMoji .= ", BukkenCD = " . $this->convertNullIntValue($this->BukkenCD);
			$sqlMoji .= ", BuildingCD = " . $this->convertNullIntValue($this->BuildingCD);
			$sqlMoji .= ", UserCD = " . $this->convertNullIntValue($this->UserCD);
			$sqlMoji .= ", ID = " . $this->convertNullStringValue($this->ID);
			$sqlMoji .= ", FilePath = " . $this->convertNullStringValue($this->FilePath);
			$sqlMoji .= ", R001 = " . $this->convertNullStringValue($this->R001);
			$sqlMoji .= ", R002 = " . $this->convertNullIntValue($this->R002);
			$sqlMoji .= ", R003 = " . $this->convertFlgToString($this->R003);
			$sqlMoji .= ", R004 = " . $this->convertNullStringValue($this->R004);
			$sqlMoji .= ", MukouFlg = " . $this->convertFlgToString($this->MukouFlg);
			$sqlMoji .= ", Created = " . $this->convertNullStringValue($this->Created);
			$sqlMoji .= ", Creator = " . $this->convertNullIntValue($this->Creator);
			$sqlMoji .= ", Updated = " . $this->convertNullStringValue($this->Updated);
			$sqlMoji .= ", Updater = " . $this->convertNullIntValue($this->Updater);

			$sqlMoji .= " where SignCD = $this->SignCD";

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

	function checkOpen($Obj, $Day, $BukkenCD, $QuickFlg = FALSE) {
		$myLog = new SPFWLog();

		$MyTimestamp = mktime(0, 0, 0, substr($Day, 5, 2), substr($Day, 8, 2), substr($Day, 0, 4));
		$MyWeekday = date('w', $MyTimestamp);

		$OpenTime = $Obj['OpenTime'];
		$CloseTime = $Obj['CloseTime'];
		$LunchID = $Obj['LunchID'];
		$LunchFilePath = $Obj['LunchFilePath'];
		$EveningID = $Obj['EveningID'];
		$EveningFilePath = $Obj['EveningFilePath'];
		$Holiday = $Obj['Holiday'];
		$MINUTEUNIT = $Obj['MINUTEUNIT'];
		$TempDate = $Obj['Day'];
		$HolidaySetting[$Day] = $Obj['HolidaySetting'][$Day];
		$Sign[$Day] = $Obj['Sign'][$Day];
		$LinesLoop = $Obj['LinesLoop'];
		$StylistLines = $Obj['StylistLines'];
		$BukkenCDs = $Obj['BukkenCDs'];
		$StylistOpenTime[$BukkenCD] = $Obj['StylistOpenTime'][$BukkenCD][$MyWeekday];
		$StylistCloseTime[$BukkenCD] = $Obj['StylistCloseTime'][$BukkenCD][$MyWeekday];
		$StylistLunchID[$BukkenCD] = $Obj['StylistLunchID'][$BukkenCD][$MyWeekday];
		$StylistLunchFilePath[$BukkenCD] = $Obj['StylistLunchFilePath'][$BukkenCD][$MyWeekday];
		$StylistEveningID[$BukkenCD] = $Obj['StylistEveningID'][$BukkenCD][$MyWeekday];
		$StylistEveningFilePath[$BukkenCD] = $Obj['StylistEveningFilePath'][$BukkenCD][$MyWeekday];
		$StylistHoliday[$BukkenCD] = $Obj['StylistHoliday'][$BukkenCD][$MyWeekday];
		$StylistNumberOfLines[$BukkenCD] = $Obj['StylistNumberOfLines'][$BukkenCD][$MyWeekday];

		$MyOpenTime = $OpenTime[$MyWeekday];
		$MyCloseTime = $CloseTime[$MyWeekday];
		$MyLunchID = $LunchID[$MyWeekday];
		$MyLunchFilePath = $LunchFilePath[$MyWeekday];
		$MyEveningID = $EveningID[$MyWeekday];
		$MyEveningFilePath = $EveningFilePath[$MyWeekday];
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
		unset($ReserveR002);

		/*$myLog->debug($Day);
		$myLog->debug($BukkenCD);*/

		for ($i = 0; $i < $TimesLoop; $i++) {
			$myLog->debug($Times[$i]);
			$ReserveR002[$Times[$i]] = 0;

			for ($j = 0; $j < $LinesLoop; $j++) {
				if ($BukkenCDs[$j] != $BukkenCD) {
					$Counter[$j] = 0;
					continue;
				}

				if ($MenuByStaffFlg && !$StylistMenuOK[$BukkenCDs[$j]]) {
					$Counters[] = $Counter[$j];
					$Counter[$j] = 0;

					// そのメニューを担当できない
					$myLog->debug($Day . " " . $BukkenCD . " " . "そのメニューを担当できない");
				}
				else if ($Sign[$Day][$Times[$i]][$BukkenCDs[$j]]['SignCD'][$StylistLines[$j]] > 0) {
					$Counters[] = $Counter[$j];
					$Counter[$j] = 0;

					// 予約が入っている
					$myLog->debug($Day . " " . $BukkenCD . " " . "予約が入っている");
				}
				else if ($Times[$i] >= $MyLunchID && $Times[$i] < $MyLunchFilePath) {
					$Counters[] = $Counter[$j];
					$Counter[$j] = 0;

					// 予約が入っている
					$myLog->debug($Day . " " . $BukkenCD . " " . "昼休み");
				}
				else if ($Times[$i] >= $MyEveningID && $Times[$i] < $MyEveningFilePath) {
					$Counters[] = $Counter[$j];
					$Counter[$j] = 0;

					// 予約が入っている
					$myLog->debug($Day . " " . $BukkenCD . " " . "夕方休み");
				}
				else if ($MyHoliday == 't') {
					$Counters[] = $Counter[$j];
					$Counter[$j] = 0;

					// 予約が入っている
					$myLog->debug($Day . " " . $BukkenCD . " " . "個人休日");
				}
				else if ($ShopHolidayFlg || $HolidaySetting[$Day]['all'][-1]) {
					$Counters[] = $Counter[$j];
					$Counter[$j] = 0;

					// 予約が入っている
					$myLog->debug($Day . " " . $BukkenCD . " " . "店舗休");
				}
				else if ($HolidaySetting[$Day][$Times[$i]][$BukkenCDs[$j]] || $HolidaySetting[$Day][$Times[$i]][-1] || $HolidaySetting[$Day]['all'][$BukkenCDs[$j]]) {
					$Counters[] = $Counter[$j];
					$Counter[$j] = 0;

					// 予約が入っている
					$myLog->debug($Day . " " . $BukkenCD . " " . "店舗休 または 設定休");
				}
				else if ($StylistOpenTime[$BukkenCDs[$j]] > $Times[$i] || $StylistCloseTime[$BukkenCDs[$j]] <= $Times[$i]) {
					$Counters[] = $Counter[$j];
					$Counter[$j] = 0;

					// 予約が入っている
					$myLog->debug($Day . " " . $BukkenCD . " " . "個人設定 勤務時間外");
				}
				else if ($StylistLunchID[$BukkenCDs[$j]] <= $Times[$i] && $StylistLunchFilePath[$BukkenCDs[$j]] > $Times[$i]) {
					$Counters[] = $Counter[$j];
					$Counter[$j] = 0;

					// 昼休み
					$myLog->debug($Day . " " . $BukkenCD . " " . "個人設定 昼休み");
				}
				else if ($StylistEveningID[$BukkenCDs[$j]] <= $Times[$i] && $StylistEveningFilePath[$BukkenCDs[$j]] > $Times[$i]) {
					$Counters[] = $Counter[$j];
					$Counter[$j] = 0;

					// 昼休み
					$myLog->debug($Day . " " . $BukkenCD . " " . "個人設定 夕方休み");
				}
				else if ($StylistHoliday[$BukkenCDs[$j]] == 't') {
					$Counters[] = $Counter[$j];
					$Counter[$j] = 0;

					// スタッフ週休
					$myLog->debug($Day . " " . $BukkenCD . " " . "スタッフ週休");
				}
				else {
					$Counter[$j]++;

					$ReserveR002[$BukkenCDs[$j]][$Times[$i]]++;
					$myLog->debug($Day . " " . $BukkenCD . " " . "予約可能");

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
