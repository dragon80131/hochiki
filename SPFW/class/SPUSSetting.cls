<?php
/**
 * Settingクラス
 *
 * 設定ファイルを扱うクラスです。
 *
 * @package 
 * @access  public
 * @author  MasahitoSAMEKAWA <masahito@souppot.jp>
 * @create  2003/01/31
 * @version 0.1
 **/

// 依存
#include_once _CLS_DIR . "SPFWLog.cls";

class Setting {
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
	 * 設定コード
	 * 
	 * 連番
	 */
	var $SettingCD;
	/**
	 * クライアントコード
	 */
	var $ClientCD;
	/**
	 * 予約受け付け開始(N日前から)
	 */
	var $ReserveFrom;
	/**
	 * 予約受け付け終了(N日前まで)
	 */
	var $ReserveTo;
	/**
	 * 予約受け付け終了(N時間前まで)
	 */
	var $ReserveToHour;
	/**
	 * 取消受け付け終了(N日前まで)
	 */
	var $CancelTo;
	/**
	 * 営業時間開始
	 * 
	 * 七曜分を|で区切る
	 */
	var $OpenTime;
	/**
	 * 営業時間終了
	 * 
	 * 七曜分を|で区切る
	 */
	var $CloseTime;
	/**
	 * 昼休み開始
	 * 
	 * 七曜分を|で区切る
	 */
	var $LunchTimeFrom;
	/**
	 * 昼休み終了
	 * 
	 * 七曜分を|で区切る
	 */
	var $LunchTimeTo;
	/**
	 * 夕方休み開始
	 * 
	 * 七曜分を|で区切る
	 */
	var $EveningTimeFrom;
	/**
	 * 夕方休み終了
	 * 
	 * 七曜分を|で区切る
	 */
	var $EveningTimeTo;
	/**
	 * 休日設定
	 * 
	 * 七曜分を|で区切る
	 */
	var $Holiday;
	/**
	 * 確認メールタイミング
	 * 
	 * X日前
	 */
	var $ConfirmTiming;
	/**
	 * フリー枠フラグ
	 * 
	 * 1:利用する 2:利用しない
	 */
	var $FreeFlg;
	/**
	 * 時間単位
	 */
	var $MinuteUnit;
	/**
	 * スタッフ毎メニュー制限フラグ
	 * 
	 * 1:利用する 2:利用しない
	 */
	var $MenuByStaffFlg;
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
 * 20110616 add
 */
var $ReserveFrom2;


/**
 * 20110616 add
 */
var $ReserveTo2;

//20110718 add
var $Waku;

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

###20120519
	var $MansionName;
	var $AendTime;
	var $BendTime;
	var $CendTime;
#20130309 
	var $Company;
	var $Hansu;
	var $ConstTime;
	var $Kaijyo;
	var $Shiharai;
	var $KeteiDate;
	var $WakuRange;

#20130520
   var $MansionMemo;
#20130830
	var $KyoyuStartDate;
	var $KyoyuEndDate;
	var $DeliveryService;
        var $U001;
        var $BukkenKanriNo;
#20140309
	var $TatoFlg;


	function __construct($myDB = "",$SettingCD = -1) {
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
		settype($SettingCD,"integer");
		// 整数チェック
		if (!is_long($SettingCD) or $SettingCD == 0) {
			$this->Err = "ユーザーコード が不正です";
			$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
			unset($myLog);
			return FALSE;
		}

		$this->myDB = $myDB;
		$this->TableName = "tSettingM";
		$this->SettingCD = $SettingCD;

		return TRUE;
	}

	function initialize(){
		$this->Err = "";

		$this->SettingCD = "";
		$this->ClientCD = "";
		$this->ReserveFrom = "";
		$this->ReserveTo = "";
		$this->ReserveToHour = "";
		$this->CancelTo = "";
		$this->OpenTime = "";
		$this->CloseTime = "";
		$this->LunchTimeFrom = "";
		$this->LunchTimeTo = "";
		$this->EveningTimeFrom = "";
		$this->EveningTimeTo = "";
		$this->Holiday = "";
		$this->ConfirmTiming = "";
		$this->FreeFlg = "";
		$this->MinuteUnit = "";
		$this->MenuByStaffFlg = "";
		$this->Notes = "";
		$this->MukouFlg = "";
		$this->Created = "";
		$this->Creator = "";
		$this->Waku = "";
		$this->Updated = "";
		$this->Updater = "";
		$this->MansionName = "";
		$this->Waku = "";
		$this->ReserveFrom2 = "";
		$this->ReserveTo2 = "";
		$this->AendTime = "";
		$this->BendTime = "";
		$this->CendTime = "";
		$this->Company = "";
		$this->Hansu = "";
		$this->ConstTime = "";
		$this->Kaijyo = "";
		$this->Shiharai = "";
		$this->KeteiDate = "";
		$this->WakuRange = "";
		$this->MansionMemo = "";
		$this->KyoyuStartDate = "";
		$this->KyoyuEndDate = "";
		$this->DeliveryService = "";
		$this->U001 = "";
		$this->BukkenKanriNo = "";
		$this->TatoFlg = "";
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
		$sqlMoji .= "SettingCD";
		$sqlMoji .= ", ClientCD";
		$sqlMoji .= ", ReserveFrom";
		$sqlMoji .= ", ReserveTo";
		$sqlMoji .= ", ReserveToHour";
		$sqlMoji .= ", CancelTo";
		$sqlMoji .= ", OpenTime";
		$sqlMoji .= ", CloseTime";
		$sqlMoji .= ", LunchTimeFrom";
		$sqlMoji .= ", LunchTimeTo";
		$sqlMoji .= ", EveningTimeFrom";
		$sqlMoji .= ", EveningTimeTo";
		$sqlMoji .= ", Holiday";
		$sqlMoji .= ", ConfirmTiming";
		$sqlMoji .= ", FreeFlg";
		$sqlMoji .= ", MinuteUnit";
		$sqlMoji .= ", MenuByStaffFlg";
		$sqlMoji .= ", Notes";
		$sqlMoji .= ", MukouFlg";

		$sqlMoji .= ", Created";
		$sqlMoji .= ", Creator";
		$sqlMoji .= ", Updated";
		$sqlMoji .= ", Updater";
		$sqlMoji .= ", MansionName";
		$sqlMoji .= ", Waku";
                $sqlMoji .= ", ReserveFrom2";
                $sqlMoji .= ", ReserveTo2";
                $sqlMoji .= ", AendTime";
                $sqlMoji .= ", BendTime";
                $sqlMoji .= ", CendTime";

                $sqlMoji .= ", Company";
                $sqlMoji .= ", Hansu";
                $sqlMoji .= ", ConstTime";
                $sqlMoji .= ", Kaijyo";
                $sqlMoji .= ", Shiharai";
                $sqlMoji .= ", KeteiDate";
                $sqlMoji .= ", WakuRange";
                $sqlMoji .= ", MansionMemo";

                $sqlMoji .= ", KyoyuStartDate";
                $sqlMoji .= ", KyoyuEndDate";
                $sqlMoji .= ", DeliveryService";
                $sqlMoji .= ", U001";
                $sqlMoji .= ", BukkenKanriNo";
                $sqlMoji .= ", TatoFlg";

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
				$this->SettingCD,
				$this->ClientCD,
				$this->ReserveFrom,
				$this->ReserveTo,
				$this->ReserveToHour,
				$this->CancelTo,
				$this->OpenTime,
				$this->CloseTime,
				$this->LunchTimeFrom,
				$this->LunchTimeTo,
				$this->EveningTimeFrom,
				$this->EveningTimeTo,
				$this->Holiday,
				$this->ConfirmTiming,
				$this->FreeFlg,
				$this->MinuteUnit,
				$this->MenuByStaffFlg,
				$this->Notes,
				$this->MukouFlg,
				$this->Created,
				$this->Creator,

				$this->Updated,
				$this->Updater,
				$this->MansionName,
				$this->Waku,
                                $this->ReserveFrom2,
                                $this->ReserveTo2,
                                $this->AendTime,
                                $this->BendTime,
                                $this->CendTime,

                                $this->Company,
                                $this->Hansu,
                                $this->ConstTime,
                                $this->Kaijyo,
                                $this->Shiharai,
                                $this->KeteiDate,
                                $this->WakuRange,
                                $this->MansionMemo,

                                $this->KyoyuStartDate,
                                $this->KyoyuEndDate,
                                $this->DeliveryService,
                                $this->U001,
                                $this->BukkenKanriNo,
				$this->TatoFlg

			) = $this->myDB->fetchRow($this->tmprtn,$RowNo);

			$this->OpenTime = stripslashes($this->OpenTime);
			$this->CloseTime = stripslashes($this->CloseTime);
			$this->LunchTimeFrom = stripslashes($this->LunchTimeFrom);
			$this->LunchTimeTo = stripslashes($this->LunchTimeTo);
			$this->EveningTimeFrom = stripslashes($this->EveningTimeFrom);
			$this->EveningTimeTo = stripslashes($this->EveningTimeTo);
			$this->Holiday = stripslashes($this->Holiday);
			$this->Notes = stripslashes($this->Notes);
			$this->MukouFlg = $this->convertFlgToBoolean($this->MukouFlg);
			$this->MansionMemo = stripslashes($this->MansionMemo);
			$this->TatoFlg = stripslashes($this->TatoFlg);


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
		
		if ($this->SettingCD < 0) {
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
					$sqlMoji = "select SettingCD from $this->TableName order by SettingCD desc limit 1";
					$maxCD = $this->myDB->getOneValue($sqlMoji);
					$maxCD = ($maxCD == -1) ? 0 : $maxCD;
					$maxCD++;

					// step 4. インサート
					$sqlMoji = "Insert into $this->TableName (";
					$sqlMoji .= "SettingCD";
					if ($this->ClientCD != "") $sqlMoji .= ", ClientCD";
					if ($this->ReserveFrom != "") $sqlMoji .= ", ReserveFrom";
					if ($this->ReserveTo != "") $sqlMoji .= ", ReserveTo";
					if ($this->ReserveToHour != "") $sqlMoji .= ", ReserveToHour";
					if ($this->CancelTo != "") $sqlMoji .= ", CancelTo";
					if ($this->OpenTime != "") $sqlMoji .= ", OpenTime";
					if ($this->CloseTime != "") $sqlMoji .= ", CloseTime";
					if ($this->LunchTimeFrom != "") $sqlMoji .= ", LunchTimeFrom";
					if ($this->LunchTimeTo != "") $sqlMoji .= ", LunchTimeTo";
					if ($this->EveningTimeFrom != "") $sqlMoji .= ", EveningTimeFrom";
					if ($this->EveningTimeTo != "") $sqlMoji .= ", EveningTimeTo";
					if ($this->Holiday != "") $sqlMoji .= ", Holiday";
					if ($this->ConfirmTiming != "") $sqlMoji .= ", ConfirmTiming";
					if ($this->FreeFlg != "") $sqlMoji .= ", FreeFlg";
					if ($this->MinuteUnit != "") $sqlMoji .= ", MinuteUnit";
					if ($this->MenuByStaffFlg != "") $sqlMoji .= ", MenuByStaffFlg";
					if ($this->Notes != "") $sqlMoji .= ", Notes";
					if ($this->MukouFlg != "") $sqlMoji .= ", MukouFlg";
					$sqlMoji .= ", Created";
					if ($this->Creator != "") $sqlMoji .= ", Creator";
					$sqlMoji .= ", Updated";
					if ($this->Updater != "") $sqlMoji .= ", Updater";

					if ($this->MansionName != "") $sqlMoji .= ", MansionName";
					if ($this->Waku != "") $sqlMoji .= ", Waku";
					if ($this->ReserveFrom2 != "") $sqlMoji .= ", ReserveFrom2";
					if ($this->ReserveTo2 != "") $sqlMoji .= ", ReserveTo2";
					if ($this->AendTime != "") $sqlMoji .= ", AendTime";
					if ($this->BendTime != "") $sqlMoji .= ", BendTime";
					if ($this->CendTime != "") $sqlMoji .= ", CendTime";

					if ($this->Company != "") $sqlMoji .= ", Company";
					if ($this->Hansu != "") $sqlMoji .= ", Hansu";
					if ($this->ConstTime != "") $sqlMoji .= ", ConstTime";
					if ($this->Kaijyo != "") $sqlMoji .= ", Kaijyo";
					if ($this->Shiraharai != "") $sqlMoji .= ", Shiharai";
					if ($this->KeteiDate != "") $sqlMoji .= ", KeteiDate";
					if ($this->WakuRange != "") $sqlMoji .= ", WakuRange";
					if ($this->MansionMemo != "") $sqlMoji .= ", MansionMemo";

					if ($this->MansionMemo != "") $sqlMoji .= ", KyoyuStartDate";
					if ($this->MansionMemo != "") $sqlMoji .= ", KyoyuEndDate";
					if ($this->MansionMemo != "") $sqlMoji .= ", DeliveryService";
					if ($this->MansionMemo != "") $sqlMoji .= ", U001";
					if ($this->MansionMemo != "") $sqlMoji .= ", BukkenKanriNo";
					if ($this->TatoFlg != "") $sqlMoji .= ", TatoFlg";

					$sqlMoji .= ") values (";
					$sqlMoji .= "$maxCD";
					if ($this->ClientCD != "") $sqlMoji .= ", $this->ClientCD";
					if ($this->ReserveFrom != "") $sqlMoji .= ", $this->ReserveFrom";
					if ($this->ReserveTo != "") $sqlMoji .= ", $this->ReserveTo";
					if ($this->ReserveToHour != "") $sqlMoji .= ", $this->ReserveToHour";
					if ($this->CancelTo != "") $sqlMoji .= ", $this->CancelTo";
					if ($this->OpenTime != "") $sqlMoji .= ", '" . addslashes($this->OpenTime) . "'";
					if ($this->CloseTime != "") $sqlMoji .= ", '" . addslashes($this->CloseTime) . "'";
					if ($this->LunchTimeFrom != "") $sqlMoji .= ", '" . addslashes($this->LunchTimeFrom) . "'";
					if ($this->LunchTimeTo != "") $sqlMoji .= ", '" . addslashes($this->LunchTimeTo) . "'";
					if ($this->EveningTimeFrom != "") $sqlMoji .= ", '" . addslashes($this->EveningTimeFrom) . "'";
					if ($this->EveningTimeTo != "") $sqlMoji .= ", '" . addslashes($this->EveningTimeTo) . "'";
					if ($this->Holiday != "") $sqlMoji .= ", '" . addslashes($this->Holiday) . "'";
					if ($this->ConfirmTiming != "") $sqlMoji .= ", $this->ConfirmTiming";
					if ($this->FreeFlg != "") $sqlMoji .= ", $this->FreeFlg";
					if ($this->MinuteUnit != "") $sqlMoji .= ", $this->MinuteUnit";
					if ($this->MenuByStaffFlg != "") $sqlMoji .= ", $this->MenuByStaffFlg";
					if ($this->Notes != "") $sqlMoji .= ", '" . addslashes($this->Notes) . "'";
					if ($this->MukouFlg != "") $sqlMoji .= ", " . $this->convertFlgToString($this->MukouFlg);
					$sqlMoji .= ", NOW()";
					if ($this->Creator != "") $sqlMoji .= ", $this->Creator";
					$sqlMoji .= ", NOW()";
					if ($this->Updater != "") $sqlMoji .= ", $this->Updater";


					if ($this->MansionName != "") $sqlMoji .= ", '" . addslashes($this->MansionName) . "'";
					if ($this->Waku != "") $sqlMoji .= ", $this->Waku";
					if ($this->ReserveFrom2 != "") $sqlMoji .= ", $this->ReserveFrom2";
					if ($this->ReserveTo2 != "") $sqlMoji .= ", $this->ReserveTo2";
					if ($this->AendTime != "") $sqlMoji .= ", '" . addslashes($this->AendTime) . "'";
					if ($this->BendTime != "") $sqlMoji .= ", '" . addslashes($this->BendTime) . "'";
					if ($this->CendTime != "") $sqlMoji .= ", '" . addslashes($this->CendTime) . "'";

					if ($this->Company != "") $sqlMoji .= ", '" . addslashes($this->Company) . "'";
					if ($this->Hansu != "") $sqlMoji .= ", '" . addslashes($this->Hansu) . "'";
					if ($this->ConstTime != "") $sqlMoji .= ", '" . addslashes($this->ConstTime) . "'";
					if ($this->Kaijyo != "") $sqlMoji .= ", '" . addslashes($this->Kaijyo) . "'";
					if ($this->Shiharai != "") $sqlMoji .= ", '" . addslashes($this->Shiharai) . "'";
					if ($this->KeteiDate != "") $sqlMoji .= ", '" . addslashes($this->KeteiDate) . "'";
					if ($this->WakuRange != "") $sqlMoji .= ", '" . addslashes($this->WakuRange) . "'";

					if ($this->MansionMemo != "") $sqlMoji .= ", '" . addslashes($this->MansionMemo) . "'";

					if ($this->KyoyuStartDate != "") $sqlMoji .= ", '" . addslashes($this->KyoyuStartDate) . "'";
					if ($this->KyoyuEndDate != "") $sqlMoji .= ", '" . addslashes($this->KyoyuEndDate) . "'";
					if ($this->DeliveryService != "") $sqlMoji .= ", '" . addslashes($this->DeliveryService) . "'";
					if ($this->U001 != "") $sqlMoji .= ", '" . addslashes($this->U001) . "'";
					if ($this->BukkenKanriNo != "") $sqlMoji .= ", '" . addslashes($this->BukkenKanriNo) . "'";
					if ($this->TatoFlg != "") $sqlMoji .= ", '" . addslashes($this->Tato) . "'";

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
							$this->SettingCD = $maxCD;
						}
					}
				}
			}
		} else {
			// アップデート
			$sqlMoji = "Update $this->TableName set ";
			$sqlMoji .= "SettingCD = $this->SettingCD";
			$sqlMoji .= ", ClientCD = " . $this->convertNullIntValue($this->ClientCD);
			$sqlMoji .= ", ReserveFrom = " . $this->convertNullIntValue($this->ReserveFrom);
			$sqlMoji .= ", ReserveTo = " . $this->convertNullIntValue($this->ReserveTo);
			$sqlMoji .= ", ReserveToHour = " . $this->convertNullIntValue($this->ReserveToHour);
			$sqlMoji .= ", CancelTo = " . $this->convertNullIntValue($this->CancelTo);
			$sqlMoji .= ", OpenTime = " . $this->convertNullStringValue($this->OpenTime);
			$sqlMoji .= ", CloseTime = " . $this->convertNullStringValue($this->CloseTime);
			$sqlMoji .= ", LunchTimeFrom = " . $this->convertNullStringValue($this->LunchTimeFrom);
			$sqlMoji .= ", LunchTimeTo = " . $this->convertNullStringValue($this->LunchTimeTo);
			$sqlMoji .= ", EveningTimeFrom = " . $this->convertNullStringValue($this->EveningTimeFrom);
			$sqlMoji .= ", EveningTimeTo = " . $this->convertNullStringValue($this->EveningTimeTo);
			$sqlMoji .= ", Holiday = " . $this->convertNullStringValue($this->Holiday);
			$sqlMoji .= ", ConfirmTiming = " . $this->convertNullIntValue($this->ConfirmTiming);
			$sqlMoji .= ", FreeFlg = " . $this->convertNullIntValue($this->FreeFlg);
			$sqlMoji .= ", MinuteUnit = " . $this->convertNullIntValue($this->MinuteUnit);
			$sqlMoji .= ", MenuByStaffFlg = " . $this->convertNullIntValue($this->MenuByStaffFlg);
			$sqlMoji .= ", Notes = " . $this->convertNullStringValue($this->Notes);
			$sqlMoji .= ", MukouFlg = " . $this->convertFlgToString($this->MukouFlg);
			$sqlMoji .= ", Created = " . $this->convertNullStringValue($this->Created);
			$sqlMoji .= ", Creator = " . $this->convertNullIntValue($this->Creator);
			$sqlMoji .= ", Updated = NOW()";
			$sqlMoji .= ", Updater = " . $this->convertNullIntValue($this->Updater);


			$sqlMoji .= ", MansionName = " . $this->convertNullStringValue($this->MansionName);
			$sqlMoji .= ", Waku = " . $this->convertNullIntValue($this->Waku);
			$sqlMoji .= ", ReserveFrom2 = " . $this->convertNullIntValue($this->ReserveFrom2);
			$sqlMoji .= ", ReserveTo2 = " . $this->convertNullIntValue($this->ReserveTo2);
			$sqlMoji .= ", AendTime = " . $this->convertNullStringValue($this->AendTime);
			$sqlMoji .= ", BendTime = " . $this->convertNullStringValue($this->BendTime);
			$sqlMoji .= ", CendTime = " . $this->convertNullStringValue($this->CendTime);

			$sqlMoji .= ", Company = " . $this->convertNullStringValue($this->Company);
			$sqlMoji .= ", Hansu = " . $this->convertNullStringValue($this->Hansu);
			$sqlMoji .= ", ConstTime = " . $this->convertNullStringValue($this->ConstTime);
			$sqlMoji .= ", Kaijyo = " . $this->convertNullStringValue($this->Kaijyo);
			$sqlMoji .= ", Shiharai = " . $this->convertNullStringValue($this->Shiharai);
			$sqlMoji .= ", KeteiDate = " . $this->convertNullStringValue($this->KeteiDate);
			$sqlMoji .= ", WakuRange = " . $this->convertNullStringValue($this->WakuRange);
			$sqlMoji .= ", MansionMemo = " . $this->convertNullStringValue($this->MansionMemo);

			$sqlMoji .= ", KyoyuStartDate = " . $this->convertNullStringValue($this->KyoyuStartDate);
			$sqlMoji .= ", KyoyuEndDate = " . $this->convertNullStringValue($this->KyoyuEndDate);
			$sqlMoji .= ", DeliveryService = " . $this->convertNullStringValue($this->DeliveryService);
			$sqlMoji .= ", U001 = " . $this->convertNullStringValue($this->U001);
			$sqlMoji .= ", BukkenKanriNo = " . $this->convertNullStringValue($this->BukkenKanriNo);
			$sqlMoji .= ", TatoFlg = " . $this->convertNullStringValue($this->TatoFlg);

			$sqlMoji .= " where SettingCD = $this->SettingCD";
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

	function getTimeArray($MinuteUnit) {
		$MinuteType[] = NULL;
		$OKFlg = FALSE;
		$Counter = 1;
		while(!$OKFlg) {
			$MinuteType[] = $MinuteUnit * $Counter;
			$Counter++;
			if ($Counter * $MinuteUnit > $GLOBALS['MAXMINUTE'] || $Counter > 100)
				$OKFlg = TRUE;
		}

		return $MinuteType;
	}
}
?>
