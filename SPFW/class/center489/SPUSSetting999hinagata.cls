<?php
/**
 * Setting999hinagataクラス
 **/

// 依存
include_once _CLS_DIR . "SPFWLog.cls";

class Setting999hinagata {
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
	 * 作成者
	 */
	var $Creator;

#20110616 add
	var $ReserveFrom2;
#20110616 add
	var $ReserveTo2;
#20110718 add
	var $Waku;

	/**
	 * 更新日時
	 */
	var $Updated;
	/**
	 * 更新者
	 */
	var $Updater;

#20120519
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
	var $U002;
#20140309
	var $TatoFlg;
#20140511
	var $Map;#GoogleMapのURL

	var $YoyakuStart; #受付開始日
	var $TekyoDate; #決定案内提供日

#20140602
	var $YoyakuEnd; #受付締切日
	var $YoteTekyoDate; #予定案内提供日
	var $PicStatus; #写真撮影ステータス
	var $PicChu; #親機子機施工中写真ステータス
#	var $PicAppSekoName; #電子看板アプリ用施工会社

#20140921
	var $HearingNo; #ヒアリングNO tReservationFのR009に書き込む。

#20150422
 	var $SekoCD;
 	var $SekoCompany;
	var $OPEnd;
	var $PostingFlg;
	var $KakuninFlg;
#	var $SmartFlg;
#	var $SmartBoardSuu;
#	var $SmartBoardStaff;
#	var $KakuninTitle;
#	var $KakuninFooter;

#20150525 いそだ
	var $PhotoTekyoDate; #写真台帳提供日
	var $PhotoPattern; #写真台帳提供日
	var $Juuko; #全住戸数

#20160511 磯田
	var $karibangou; #仮暗証番号
	var $Yomikata; #読み方

	var $Answer;
#20140530
	var $Tanto1; #担当者1
#20150526磯田
	var $Tanto2; #担当者2
	var $Tanto3; #担当者3
	var $Tanto4; #担当者4
	var $Tanto5; #担当者5
#	var $TantoMemo; #担当メモ

#	var $Sdevice; #標準機器
#	var $OptionPA; #オプションの有無

#	var $PosDateST; #ポスティングメール開始日
#	var $PosDateFN; #ポスティングメール終了日

###20150609磯田
	var $Excel;
###20160405kimura
	var $SFBukkenCD;
###20160928kimura
	var $BoardTerop;
	var $BoardNews;
	var $Sashikae;
	var $Address;

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
		$this->ReserveFrom2 = "";
		$this->ReserveTo2 = "";
		$this->Waku = "";
		$this->Updated = "";
		$this->Updater = "";
		$this->MansionName = "";
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
		$this->U002 = "";
		$this->TatoFlg = "";
		$this->Map = "";

		$this->YoyakuStart = "";
		$this->TekyoDate = "";
		$this->YoyakuEnd = "";
		$this->YoteTekyoDate = "";
		$this->PicStatus = "";
		$this->PicChu = "";
#		$this->PicAppSekoName = "";
		$this->HearingNo = "";
   		$this->SekoCD = "";
   		$this->SekoCompany = "";
  		$this->OPEnd = "";
  		$this->PostingFlg = "";
  		$this->KakuninFlg = "";
#  		$this->SmartFlg = "";
#  		$this->SmartBoardSuu = "";
#  		$this->SmartBoardStaff = "";
#		$this->KakuninTitle = "";
#		$this->KakuninFooter = "";
		$this->PhotoTekyoDate = "";
		$this->PhotoPattern = "";
		$this->Juuko = "";
		$this->karibangou = "";
		$this->Yomikata = "";
		$this->Answer = "";
		$this->Tanto1 = "";
		$this->Tanto2 = "";
		$this->Tanto3 = "";
		$this->Tanto4 = "";
		$this->Tanto5 = "";
#		$this->TantoMemo = "";
#		$this->Sdevice = "";
#		$this->OptionPA = "";
#		$this->PosDateST = "";
#		$this->PosDateFN = "";
  		$this->Excel = "";
  		$this->SFBukkenCD = "";
  		$this->BoardTerop = "";
  		$this->BoardNews = "";
  		$this->Sashikae = "";
  		$this->Address = "";
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
		$sqlMoji .= ", ReserveFrom2";
		$sqlMoji .= ", ReserveTo2";
		$sqlMoji .= ", Waku";
		$sqlMoji .= ", Updated";
		$sqlMoji .= ", Updater";
		$sqlMoji .= ", MansionName";
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
		$sqlMoji .= ", U002";
		$sqlMoji .= ", TatoFlg";
		$sqlMoji .= ", Map";

		$sqlMoji .= ", YoyakuStart";
		$sqlMoji .= ", TekyoDate";
		$sqlMoji .= ", YoyakuEnd";
		$sqlMoji .= ", YoteTekyoDate";
		$sqlMoji .= ", PicStatus";
		$sqlMoji .= ", PicChu";
#		$sqlMoji .= ", PicAppSekoName";
		$sqlMoji .= ", HearingNo";
  		$sqlMoji .= ", SekoCD ";
  		$sqlMoji .= ", SekoCompany ";
  		$sqlMoji .= ", OPEnd ";
  		$sqlMoji .= ", PostingFlg ";
  		$sqlMoji .= ", KakuninFlg ";
 # 		$sqlMoji .= ", SmartFlg ";
#  		$sqlMoji .= ", SmartBoardSuu ";
#  		$sqlMoji .= ", SmartBoardStaff ";
#		$sqlMoji .= ", KakuninTitle ";
#		$sqlMoji .= ", KakuninFooter ";
		$sqlMoji .= ", PhotoTekyoDate";
		$sqlMoji .= ", PhotoPattern";
		$sqlMoji .= ", Juuko";
		$sqlMoji .= ", karibangou";
		$sqlMoji .= ", Yomikata";
		$sqlMoji .= ", Answer";
		$sqlMoji .= ", Tanto1";
		$sqlMoji .= ", Tanto2";
		$sqlMoji .= ", Tanto3";
		$sqlMoji .= ", Tanto4";
		$sqlMoji .= ", Tanto5";
#		$sqlMoji .= ", TantoMemo";
#		$sqlMoji .= ", Sdevice";
#		$sqlMoji .= ", OptionPA";
#		$sqlMoji .= ", PosDateST";
#		$sqlMoji .= ", PosDateFN";
  		$sqlMoji .= ", Excel ";
  		$sqlMoji .= ", SFBukkenCD ";
  		$sqlMoji .= ", BoardTerop ";
  		$sqlMoji .= ", BoardNews ";
  		$sqlMoji .= ", Sashikae ";
  		$sqlMoji .= ", Address ";

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
				$this->ReserveFrom2,
				$this->ReserveTo2,
				$this->Waku,
				$this->Updated,
				$this->Updater,
				$this->MansionName,
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
				$this->U002,
				$this->TatoFlg,
				$this->Map,

				$this->YoyakuStart,
				$this->TekyoDate,
				$this->YoyakuEnd,
				$this->YoteTekyoDate,
				$this->PicStatus,
				$this->PicChu,
#				$this->PicAppSekoName,
				$this->HearingNo,
				$this->SekoCD,
				$this->SekoCompany,
				$this->OPEnd,
				$this->PostingFlg,
				$this->KakuninFlg,
#				$this->SmartFlg,
#				$this->SmartBoardSuu,
#				$this->SmartBoardStaff,
#				$this->KakuninTitle,
#				$this->KakuninFooter,
				$this->PhotoTekyoDate,
				$this->PhotoPattern,
				$this->Juuko,
				$this->karibangou,
				$this->Yomikata,
				$this->Answer,
				$this->Tanto1,
				$this->Tanto2,
				$this->Tanto3,
				$this->Tanto4,
				$this->Tanto5,
#				$this->TantoMemo,
#				$this->Sdevice,
#				$this->OptionPA,
##				$this->PosDateST,
#				$this->PosDateFN,
    			$this->Excel,
    			$this->SFBukkenCD,
    			$this->BoardTerop,
    			$this->BoardNews,
    			$this->Sashikae,
    			$this->Address

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

			$this->MansionName = stripslashes($this->MansionName);
			$this->AendTime = stripslashes($this->AendTime);
			$this->BendTime = stripslashes($this->BendTime);
			$this->CendTime = stripslashes($this->CendTime);
			$this->Company = stripslashes($this->Company);
			$this->Hansu = stripslashes($this->Hansu);
			$this->ConstTime = stripslashes($this->ConstTime);
			$this->Kaijyo = stripslashes($this->Kaijyo);
			$this->Shiharai = stripslashes($this->Shiharai);
			$this->KeteiDate = stripslashes($this->KeteiDate);
			$this->WakuRange = stripslashes($this->WakuRange);
			$this->MansionMemo = stripslashes($this->MansionMemo);
			$this->KyoyuStartDate = stripslashes($this->KyoyuStartDate);
			$this->KyoyuEndDate = stripslashes($this->KyoyuEndDate);
			$this->DeliveryService = stripslashes($this->DeliveryService);
			$this->U001 = stripslashes($this->U001);
			$this->U002 = stripslashes($this->U002);
			$this->TatoFlg = stripslashes($this->TatoFlg);
			$this->Map = stripslashes($this->Map);

			$this->YoyakuStart = stripslashes($this->YoyakuStart);
			$this->TekyoDate = stripslashes($this->TekyoDate);
			$this->YoyakuEnd = stripslashes($this->YoyakuEnd);
			$this->YoteTekyoDate = stripslashes($this->YoteTekyoDate);
			$this->PicStatus = stripslashes($this->PicStatus);
			$this->PicChu = stripslashes($this->PicChu);
#			$this->PicAppSekoName = stripslashes($this->PicAppSekoName);
			$this->HearingNo = stripslashes($this->HearingNo);
			$this->SekoCD = stripslashes($this->SekoCD);
			$this->SekoCompany = stripslashes($this->SekoCompany);
			$this->OPEnd = stripslashes($this->OPEnd);
			$this->PostingFlg = stripslashes($this->PostingFlg);
			$this->KakuninFlg = stripslashes($this->KakuninFlg);
#			$this->SmartFlg = stripslashes($this->SmartFlg);
#			$this->SmartBoardSuu = stripslashes($this->SmartBoardSuu);
#			$this->SmartBoardStaff = stripslashes($this->SmartBoardStaff);
#			$this->KakuninTitle = stripslashes($this->KakuninTitle);
#			$this->KakuninFooter = stripslashes($this->KakuninFooter);
			$this->PhotoTekyoDate = stripslashes($this->PhotoTekyoDate);
			$this->PhotoPattern = stripslashes($this->PhotoPattern);
			$this->Juuko = stripslashes($this->Juuko);
			$this->karibangou = stripslashes($this->karibangou);
			$this->Yomikata = stripslashes($this->Yomikata);
			$this->Answer = stripslashes($this->Answer);
			$this->Tanto1 = stripslashes($this->Tanto1);
			$this->Tanto2 = stripslashes($this->Tanto2);
			$this->Tanto3 = stripslashes($this->Tanto3);
			$this->Tanto4 = stripslashes($this->Tanto4);
			$this->Tanto5 = stripslashes($this->Tanto5);
#			$this->TantoMemo = stripslashes($this->TantoMemo);
#			$this->Sdevice = stripslashes($this->Sdevice);
#			$this->OptionPA = stripslashes($this->OptionPA);
#			$this->PosDateST = stripslashes($this->PosDateST);
#			$this->PosDateFN = stripslashes($this->PosDateFN);
   			$this->Excel = stripslashes($this->Excel);
   			$this->SFBukkenCD = stripslashes($this->SFBukkenCD);
   			$this->BoardTerop = stripslashes($this->BoardTerop);
   			$this->BoardNews = stripslashes($this->BoardNews);
   			$this->Sashikae = stripslashes($this->Sashikae);
   			$this->Address = stripslashes($this->Address);
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
					if ($this->ReserveFrom2 != "") $sqlMoji .= ", ReserveFrom2";
					if ($this->ReserveTo2 != "") $sqlMoji .= ", ReserveTo2";
					if ($this->Waku != "") $sqlMoji .= ", Waku";
					$sqlMoji .= ", Updated";
					if ($this->Updater != "") $sqlMoji .= ", Updater";
					if ($this->MansionName != "") $sqlMoji .= ", MansionName";
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
					if ($this->KyoyuStartDate != "") $sqlMoji .= ", KyoyuStartDate";
					if ($this->KyoyuEndDate != "") $sqlMoji .= ", KyoyuEndDate";
					if ($this->DeliveryService != "") $sqlMoji .= ", DeliveryService";
					if ($this->U001 != "") $sqlMoji .= ", U001";
					if ($this->U002 != "") $sqlMoji .= ", U002";
					if ($this->TatoFlg != "") $sqlMoji .= ", TatoFlg";
					if ($this->Map != "") $sqlMoji .= ", Map";

					if ($this->YoyakuStart != "") $sqlMoji .= ", YoyakuStart";
					if ($this->TekyoDate != "") $sqlMoji .= ", TekyoDate";
					if ($this->YoyakuEnd != "") $sqlMoji .= ", YoyakuEnd";
					if ($this->YoteTekyoDate != "") $sqlMoji .= ", YoteTekyoDate";
					if ($this->PicStatus != "") $sqlMoji .= ", PicStatus";
					if ($this->PicChu != "") $sqlMoji .= ", PicChu";
#					if ($this->PicAppSekoName != "") $sqlMoji .= ", PicAppSekoName";
					if ($this->HearingNo != "") $sqlMoji .= ", HearingNo";
					if ($this->SekoCD != "") $sqlMoji .= ", SekoCD";
					if ($this->SekoCompany != "") $sqlMoji .= ", SekoCompany";
					if ($this->OPEnd != "") $sqlMoji .= ", OPEnd";
					if ($this->PostingFlg != "") $sqlMoji .= ", PostingFlg";
					if ($this->KakuninFlg != "") $sqlMoji .= ", KakuninFlg";
#					if ($this->SmartFlg != "") $sqlMoji .= ", SmartFlg";
#					if ($this->SmartBoardSuu != "") $sqlMoji .= ", SmartBoardSuu";
#					if ($this->SmartBoardStaff != "") $sqlMoji .= ", SmartBoardStaff";
#					if ($this->KakuninTitle != "") $sqlMoji .= ", KakuninTitle";
#					if ($this->KakuninFooter != "") $sqlMoji .= ", KakuninFooter";
					if ($this->PhotoTekyoDate != "") $sqlMoji .= ", PhotoTekyoDate";
					if ($this->PhotoPattern != "") $sqlMoji .= ", PhotoPattern";
					if ($this->Juuko != "") $sqlMoji .= ", Juuko";
					if ($this->karibangou != "") $sqlMoji .= ", karibangou";
					if ($this->Yomikata != "") $sqlMoji .= ", Yomikata";
					if ($this->Answer != "") $sqlMoji .= ", Answer";
					if ($this->Tanto1 != "") $sqlMoji .= ", Tanto1";
					if ($this->Tanto2 != "") $sqlMoji .= ", Tanto2";
					if ($this->Tanto3 != "") $sqlMoji .= ", Tanto3";
					if ($this->Tanto4 != "") $sqlMoji .= ", Tanto4";
					if ($this->Tanto5 != "") $sqlMoji .= ", Tanto5";
#					if ($this->TantoMemo != "") $sqlMoji .= ", TantoMemo";
#					if ($this->Sdevice != "") $sqlMoji .= ", Sdevice";
#					if ($this->OptionPA != "") $sqlMoji .= ", OptionPA";
#					if ($this->PosDateST != "") $sqlMoji .= ", PosDateST";
#					if ($this->PosDateFN != "") $sqlMoji .= ", PosDateFN";
					if ($this->Excel != "") $sqlMoji .= ", Excel";
					if ($this->SFBukkenCD != "") $sqlMoji .= ", SFBukkenCD";
					if ($this->BoardTerop != "") $sqlMoji .= ", BoardTerop";
					if ($this->BoardNews != "") $sqlMoji .= ", BoardNews";
					if ($this->Sashikae != "") $sqlMoji .= ", Sashikae";
					if ($this->Address != "") $sqlMoji .= ", Address";
##values
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
					if ($this->ReserveFrom2 != "") $sqlMoji .= ", $this->ReserveFrom2";
					if ($this->ReserveTo2 != "") $sqlMoji .= ", $this->ReserveTo2";
					if ($this->Waku != "") $sqlMoji .= ", $this->Waku";
					$sqlMoji .= ", NOW()";
					if ($this->Updater != "") $sqlMoji .= ", $this->Updater";
					if ($this->MansionName != "") $sqlMoji .= ", '" . addslashes($this->MansionName) . "'";
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
					if ($this->U002 != "") $sqlMoji .= ", '" . addslashes($this->U002) . "'";
					if ($this->TatoFlg != "") $sqlMoji .= ", '" . addslashes($this->TatoFlg) . "'";
					if ($this->Map != "") $sqlMoji .= ", '" . addslashes($this->Map) . "'";

					if ($this->YoyakuStart != "") $sqlMoji .= ", '" . addslashes($this->YoyakuStart) . "'";
					if ($this->TekyoDate != "") $sqlMoji .= ", '" . addslashes($this->TekyoDate) . "'";
					if ($this->YoyakuEnd != "") $sqlMoji .= ", '" . addslashes($this->YoyakuEnd) . "'";
					if ($this->YoteTekyoDate != "") $sqlMoji .= ", '" . addslashes($this->YoteTekyoDate) . "'";
					if ($this->PicStatus != "") $sqlMoji .= ", '" . addslashes($this->PicStatus) . "'";
					if ($this->PicChu != "") $sqlMoji .= ", '" . addslashes($this->PicChu) . "'";
#					if ($this->PicAppSekoName != "") $sqlMoji .= ", '" . addslashes($this->PicAppSekoName) . "'";
					if ($this->HearingNo != "") $sqlMoji .= ", '" . addslashes($this->HearingNo) . "'";
					if ($this->SekoCD != "") $sqlMoji .= ", '" . addslashes($this->SekoCD) . "'";
					if ($this->SekoCompany != "") $sqlMoji .= ", '" . addslashes($this->SekoCompany) . "'";
					if ($this->OPEnd != "") $sqlMoji .= ", '" . addslashes($this->OPEnd) . "'";
					if ($this->PostingFlg != "") $sqlMoji .= ", '" . addslashes($this->PostingFlg) . "'";
					if ($this->KakuninFlg != "") $sqlMoji .= ", '" . addslashes($this->KakuninFlg) . "'";
#					if ($this->SmartFlg != "") $sqlMoji .= ", '" . addslashes($this->SmartFlg) . "'";
#					if ($this->SmartBoardSuu != "") $sqlMoji .= ", '" . addslashes($this->SmartBoardSuu) . "'";
#					if ($this->SmartBoardStaff != "") $sqlMoji .= ", '" . addslashes($this->SmartBoardStaff) . "'";
#					if ($this->KakuninTitle != "") $sqlMoji .= ", '" . addslashes($this->KakuninTitle) . "'";
#					if ($this->KakuninFooter != "") $sqlMoji .= ", '" . addslashes($this->KakuninFooter) . "'";
					if ($this->PhotoTekyoDate != "") $sqlMoji .= ", '" . addslashes($this->PhotoTekyoDate) . "'";
					if ($this->PhotoPattern != "") $sqlMoji .= ", '" . addslashes($this->PhotoPattern) . "'";
					if ($this->Juuko != "") $sqlMoji .= ", '" . addslashes($this->Juuko) . "'";
					if ($this->karibangou != "") $sqlMoji .= ", '" . addslashes($this->karibangou) . "'";
					if ($this->Yomikata != "") $sqlMoji .= ", '" . addslashes($this->Yomikata) . "'";
					if ($this->Answer != "") $sqlMoji .= ", '" . addslashes($this->Answer) . "'";
					if ($this->Tanto1 != "") $sqlMoji .= ", '" . addslashes($this->Tanto1) . "'";
					if ($this->Tanto2 != "") $sqlMoji .= ", '" . addslashes($this->Tanto2) . "'";
					if ($this->Tanto3 != "") $sqlMoji .= ", '" . addslashes($this->Tanto3) . "'";
					if ($this->Tanto4 != "") $sqlMoji .= ", '" . addslashes($this->Tanto4) . "'";
					if ($this->Tanto5 != "") $sqlMoji .= ", '" . addslashes($this->Tanto5) . "'";
#					if ($this->TantoMemo != "") $sqlMoji .= ", '" . addslashes($this->TantoMemo) . "'";
#					if ($this->Sdevice != "") $sqlMoji .= ", '" . addslashes($this->Sdevice) . "'";
#					if ($this->OptionPA != "") $sqlMoji .= ", '" . addslashes($this->OptionPA) . "'";
#					if ($this->PosDateST != "") $sqlMoji .= ", '" . addslashes($this->PosDateST) . "'";
#					if ($this->PosDateFN != "") $sqlMoji .= ", '" . addslashes($this->PosDateFN) . "'";
					if ($this->Excel != "") $sqlMoji .= ", '" . addslashes($this->Excel) . "'";
					if ($this->SFBukkenCD != "") $sqlMoji .= ", '" . addslashes($this->SFBukkenCD) . "'";
					if ($this->BoardTerop != "") $sqlMoji .= ", '" . addslashes($this->BoardTerop) . "'";
					if ($this->BoardNews != "") $sqlMoji .= ", '" . addslashes($this->BoardNews) . "'";
					if ($this->Sashikae != "") $sqlMoji .= ", '" . addslashes($this->Sashikae) . "'";
					if ($this->Address != "") $sqlMoji .= ", '" . addslashes($this->Address) . "'";

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
			$sqlMoji .= ", ReserveFrom2 = " . $this->convertNullIntValue($this->ReserveFrom2);
			$sqlMoji .= ", ReserveTo2 = " . $this->convertNullIntValue($this->ReserveTo2);
			$sqlMoji .= ", Waku = " . $this->convertNullIntValue($this->Waku);
			$sqlMoji .= ", Updated = NOW()";
			$sqlMoji .= ", Updater = " . $this->convertNullIntValue($this->Updater);
			$sqlMoji .= ", MansionName = " . $this->convertNullStringValue($this->MansionName);
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
			$sqlMoji .= ", U002 = " . $this->convertNullStringValue($this->U002);
			$sqlMoji .= ", TatoFlg = " . $this->convertNullStringValue($this->TatoFlg);
			$sqlMoji .= ", Map = " . $this->convertNullStringValue($this->Map);

			$sqlMoji .= ", YoyakuStart = " . $this->convertNullStringValue($this->YoyakuStart);
			$sqlMoji .= ", TekyoDate = " . $this->convertNullStringValue($this->TekyoDate);
			$sqlMoji .= ", YoyakuEnd = " . $this->convertNullStringValue($this->YoyakuEnd);
			$sqlMoji .= ", YoteTekyoDate = " . $this->convertNullStringValue($this->YoteTekyoDate);
			$sqlMoji .= ", PicStatus = " . $this->convertNullStringValue($this->PicStatus);
			$sqlMoji .= ", PicChu = " . $this->convertNullStringValue($this->PicChu);
#			$sqlMoji .= ", PicAppSekoName = " . $this->convertNullStringValue($this->PicAppSekoName);
			$sqlMoji .= ", HearingNo = " . $this->convertNullStringValue($this->HearingNo);
			$sqlMoji .= ", SekoCD = " . $this->convertNullIntValue($this->SekoCD);
			$sqlMoji .= ", SekoCompany = " . $this->convertNullStringValue($this->SekoCompany);
			$sqlMoji .= ", OPEnd = " . $this->convertNullStringValue($this->OPEnd);
			$sqlMoji .= ", PostingFlg = " . $this->convertNullStringValue($this->PostingFlg);
			$sqlMoji .= ", KakuninFlg = " . $this->convertNullIntValue($this->KakuninFlg);
#			$sqlMoji .= ", SmartFlg = " . $this->convertNullIntValue($this->SmartFlg);
#			$sqlMoji .= ", SmartBoardSuu = " . $this->convertNullIntValue($this->SmartBoardSuu);
#			$sqlMoji .= ", SmartBoardStaff = " . $this->convertNullStringValue($this->SmartBoardStaff);
#			$sqlMoji .= ", KakuninTitle = " . $this->convertNullStringValue($this->KakuninTitle);
#			$sqlMoji .= ", KakuninFooter = " . $this->convertNullStringValue($this->KakuninFooter);
			$sqlMoji .= ", PhotoTekyoDate = " . $this->convertNullStringValue($this->PhotoTekyoDate);
			$sqlMoji .= ", PhotoPattern = " . $this->convertNullStringValue($this->PhotoPattern);
			$sqlMoji .= ", Juuko = " . $this->convertNullStringValue($this->Juuko);
			$sqlMoji .= ", karibangou = " . $this->convertNullStringValue($this->karibangou);
			$sqlMoji .= ", Yomikata = " . $this->convertNullStringValue($this->Yomikata);
			$sqlMoji .= ", Answer = " . $this->convertNullStringValue($this->Answer);
			$sqlMoji .= ", Tanto1 = " . $this->convertNullStringValue($this->Tanto1);
			$sqlMoji .= ", Tanto2 = " . $this->convertNullStringValue($this->Tanto2);
			$sqlMoji .= ", Tanto3 = " . $this->convertNullStringValue($this->Tanto3);
			$sqlMoji .= ", Tanto4 = " . $this->convertNullStringValue($this->Tanto4);
			$sqlMoji .= ", Tanto5 = " . $this->convertNullStringValue($this->Tanto5);
#			$sqlMoji .= ", TantoMemo = " . $this->convertNullStringValue($this->TantoMemo);
#			$sqlMoji .= ", Sdevice = " . $this->convertNullStringValue($this->Sdevice);
#			$sqlMoji .= ", OptionPA = " . $this->convertNullStringValue($this->OptionPA);
#			$sqlMoji .= ", PosDateST = " . $this->convertNullStringValue($this->PosDateST);
#			$sqlMoji .= ", PosDateFN = " . $this->convertNullStringValue($this->PosDateFN);
			$sqlMoji .= ", Excel = " . $this->convertNullStringValue($this->Excel);
			$sqlMoji .= ", SFBukkenCD = " . $this->convertNullStringValue($this->SFBukkenCD);
			$sqlMoji .= ", BoardTerop = " . $this->convertNullStringValue($this->BoardTerop);
			$sqlMoji .= ", BoardNews = " . $this->convertNullStringValue($this->BoardNews);
			$sqlMoji .= ", Sashikae = " . $this->convertNullStringValue($this->Sashikae);
			$sqlMoji .= ", Address = " . $this->convertNullStringValue($this->Address);

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
