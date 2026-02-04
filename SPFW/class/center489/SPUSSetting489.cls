<?php
/**
 * Settingクラス
 **/

// 依存
include_once _CLS_DIR . "SPFWLog.cls";

class Setting489 {
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
	var $MansionName;
	var $MansionMemo;
	var $TatoFlg;
	var $Juuko;
	var $Yomikata;
	var $HearingNo;
	var $Company;
	var $ConstTime;
	var $Tanto1;
	var $Tanto2;
	var $Tanto3;
	var $Tanto4;
	var $Tanto5;
	var $Tanto6;
	var $Tanto7;
	var $Tanto8;
	var $Tanto9;
	var $TantoMemo;
	var $Excel;
	var $SenyuStartDate;
	var $SenyuEndDate;
	var $YoteiTekyoDate;
	var $YoyakuStartDate;
	var $YoyakuEndDate;
	var $KetteiTeikyoDate;
	var $KetteiHaifuDate;
	var $KyoyuStartDate;
	var $KyoyuEndDate;
	var $OpEndDate;
	var $Shiharai;
	var $WakuPattern;
	var $Waku;
	var $PostingFlg;
	var $Kaijyo;
	var $KaijyoAnshoNo;
	var $DoorPostFlg;
	var $KeijiBasho;
	var $PostingBiko;

	var $PicAppSekoName;
	var $PicStatus;
	var $PicChu;
	var $PhotoTeikyoDate;
	var $PhotoPattern;
	var $KakuninTitle;
	var $KakuninFlg;
	var $KakuninFooter;
	var $MansionNextID;
	var $WakuRange;
	var $IraiMemo;
	var $Answer;
	var $WEBRecept;
	var $OptionPA;
	var $PosDateST;
	var $PosDateFN;
	var $SFBukkenCD;
	var $BoardTerop;
	var $BoardNews;
	var $Address;
	var $SmartFlg;
	var $SmartBoardSuu;
	var $SmartBoardStaff;
	var $OyaKataban;
	var $KokiKataban;
	var $HanSu;
	var $MongonCD;
	var $MongonTagName;
	var $MongonTagSuu;
	var $MongonShiken;
	var $MongonSetDateTime;
	var $HokanPicThumb;

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
		$this->MansionName = "";
		$this->MansionMemo = "";
		$this->TatoFlg = "";
		$this->Juuko = "";
		$this->Yomikata = "";
		$this->HearingNo = "";
		$this->Company = "";
		$this->ConstTime = "";
		$this->Tanto1 = "";
		$this->Tanto2 = "";
		$this->Tanto3 = "";
		$this->Tanto4 = "";
		$this->Tanto5 = "";
		$this->Tanto6 = "";
		$this->Tanto7 = "";
		$this->Tanto8 = "";
		$this->Tanto9 = "";
		$this->TantoMemo = "";
		$this->Excel = "";
		$this->SenyuStartDate = "";
		$this->SenyuEndDate = "";
		$this->YoteiTekyoDate = "";
		$this->YoyakuStartDate = "";
		$this->YoyakuEndDate = "";
		$this->KetteiTeikyoDate = "";
		$this->KetteiHaifuDate = "";
		$this->KyoyuStartDate = "";
		$this->KyoyuEndDate = "";
		$this->OpEndDate = "";
		$this->Shiharai = "";
		$this->WakuPattern = "";
		$this->Waku = "";
		$this->PostingFlg = "";
		$this->Kaijyo = "";
		$this->KaijyoAnshoNo = "";
		$this->DoorPostFlg = "";
		$this->KeijiBasho = "";
		$this->PostingBiko = "";

		$this->PicAppSekoName = "";
		$this->PicStatus = "";
		$this->PicChu = "";
		$this->PhotoTeikyoDate = "";
		$this->PhotoPattern = "";
		$this->KakuninTitle = "";
		$this->KakuninFlg = "";
		$this->KakuninFooter = "";
		$this->MansionNextID = "";
		$this->WakuRange = "";
		$this->IraiMemo = "";
		$this->Answer = "";
		$this->WEBRecept = "";
		$this->OptionPA = "";
		$this->PosDateST = "";
		$this->PosDateFN = "";
		$this->SFBukkenCD = "";
		$this->BoardTerop = "";
		$this->BoardNews = "";
		$this->Address = "";
		$this->SmartFlg = "";
		$this->SmartBoardSuu = "";
		$this->SmartBoardStaff = "";
		$this->OyaKataban = "";
		$this->KokiKataban = "";
		$this->HanSu = "";
		$this->MongonCD = "";
		$this->MongonTagName = "";
		$this->MongonTagSuu = "";
		$this->MongonShiken = "";
		$this->MongonSetDateTime = "";
		$this->HokanPicThumb = "";

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
		$sqlMoji .= ", MansionName ";
		$sqlMoji .= ", MansionMemo ";
		$sqlMoji .= ", TatoFlg ";
		$sqlMoji .= ", Juuko ";
		$sqlMoji .= ", Yomikata ";
		$sqlMoji .= ", HearingNo ";
		$sqlMoji .= ", Company ";
		$sqlMoji .= ", ConstTime ";
		$sqlMoji .= ", Tanto1 ";
		$sqlMoji .= ", Tanto2 ";
		$sqlMoji .= ", Tanto3 ";
		$sqlMoji .= ", Tanto4 ";
		$sqlMoji .= ", Tanto5 ";
		$sqlMoji .= ", Tanto6 ";
		$sqlMoji .= ", Tanto7 ";
		$sqlMoji .= ", Tanto8 ";
		$sqlMoji .= ", Tanto9 ";
		$sqlMoji .= ", TantoMemo ";
		$sqlMoji .= ", Excel ";
		$sqlMoji .= ", SenyuStartDate ";
		$sqlMoji .= ", SenyuEndDate ";
		$sqlMoji .= ", YoteiTekyoDate ";
		$sqlMoji .= ", YoyakuStartDate ";
		$sqlMoji .= ", YoyakuEndDate ";
		$sqlMoji .= ", KetteiTeikyoDate ";
		$sqlMoji .= ", KetteiHaifuDate ";
		$sqlMoji .= ", KyoyuStartDate ";
		$sqlMoji .= ", KyoyuEndDate ";
		$sqlMoji .= ", OpEndDate ";
		$sqlMoji .= ", Shiharai ";
		$sqlMoji .= ", WakuPattern ";
		$sqlMoji .= ", Waku ";
		$sqlMoji .= ", PostingFlg ";
		$sqlMoji .= ", Kaijyo ";
		$sqlMoji .= ", KaijyoAnshoNo ";
		$sqlMoji .= ", DoorPostFlg ";
		$sqlMoji .= ", KeijiBasho ";
		$sqlMoji .= ", PostingBiko ";

		$sqlMoji .= ", PicAppSekoName ";
		$sqlMoji .= ", PicStatus ";
		$sqlMoji .= ", PicChu ";
		$sqlMoji .= ", PhotoTeikyoDate ";
		$sqlMoji .= ", PhotoPattern ";
		$sqlMoji .= ", KakuninTitle ";
		$sqlMoji .= ", KakuninFlg ";
		$sqlMoji .= ", KakuninFooter ";
		$sqlMoji .= ", MansionNextID ";
		$sqlMoji .= ", WakuRange ";
		$sqlMoji .= ", IraiMemo ";
		$sqlMoji .= ", Answer ";
		$sqlMoji .= ", WEBRecept ";
		$sqlMoji .= ", OptionPA ";
		$sqlMoji .= ", PosDateST ";
		$sqlMoji .= ", PosDateFN ";
		$sqlMoji .= ", SFBukkenCD ";
		$sqlMoji .= ", BoardTerop ";
		$sqlMoji .= ", BoardNews ";
		$sqlMoji .= ", Address ";
		$sqlMoji .= ", SmartFlg ";
		$sqlMoji .= ", SmartBoardSuu ";
		$sqlMoji .= ", SmartBoardStaff ";
		$sqlMoji .= ", OyaKataban ";
		$sqlMoji .= ", KokiKataban ";
		$sqlMoji .= ", HanSu ";
		$sqlMoji .= ", MongonCD ";
		$sqlMoji .= ", MongonTagName ";
		$sqlMoji .= ", MongonTagSuu ";
		$sqlMoji .= ", MongonShiken ";
		$sqlMoji .= ", MongonSetDateTime ";
		$sqlMoji .= ", LENGTH(HokanPicThumb) ";

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
				$this->MansionName,
				$this->MansionMemo,
				$this->TatoFlg,
				$this->Juuko,
				$this->Yomikata,
				$this->HearingNo,
				$this->Company,
				$this->ConstTime,
				$this->Tanto1,
				$this->Tanto2,
				$this->Tanto3,
				$this->Tanto4,
				$this->Tanto5,
				$this->Tanto6,
				$this->Tanto7,
				$this->Tanto8,
				$this->Tanto9,
				$this->TantoMemo,
				$this->Excel,
				$this->SenyuStartDate,
				$this->SenyuEndDate,
				$this->YoteiTekyoDate,
				$this->YoyakuStartDate,
				$this->YoyakuEndDate,
				$this->KetteiTeikyoDate,
				$this->KetteiHaifuDate,
				$this->KyoyuStartDate,
				$this->KyoyuEndDate,
				$this->OpEndDate,
				$this->Shiharai,
				$this->WakuPattern,
				$this->Waku,
				$this->PostingFlg,
				$this->Kaijyo,
				$this->KaijyoAnshoNo,
				$this->DoorPostFlg,
				$this->KeijiBasho,
				$this->PostingBiko,

				$this->PicAppSekoName,
				$this->PicStatus,
				$this->PicChu,
				$this->PhotoTeikyoDate,
				$this->PhotoPattern,
				$this->KakuninTitle,
				$this->KakuninFlg,
				$this->KakuninFooter,
				$this->MansionNextID,
				$this->WakuRange,
				$this->IraiMemo,
				$this->Answer,
				$this->WEBRecept,
				$this->OptionPA,
				$this->PosDateST,
				$this->PosDateFN,
				$this->SFBukkenCD,
				$this->BoardTerop,
				$this->BoardNews,
				$this->Address,
				$this->SmartFlg,
				$this->SmartBoardSuu,
				$this->SmartBoardStaff,
				$this->OyaKataban,
				$this->KokiKataban,
				$this->HanSu,
				$this->MongonCD,
				$this->MongonTagName,
				$this->MongonTagSuu,
				$this->MongonShiken,
				$this->MongonSetDateTime,
				$this->HokanPicThumb,

				$this->MukouFlg,
				$this->Created,
				$this->Creator,
				$this->Updated,
				$this->Updater
			) = $this->myDB->fetchRow($this->tmprtn,$RowNo);

			$this->OpenTime = stripslashes($this->OpenTime);
			$this->CloseTime = stripslashes($this->CloseTime);
			$this->LunchTimeFrom = stripslashes($this->LunchTimeFrom);
			$this->LunchTimeTo = stripslashes($this->LunchTimeTo);
			$this->EveningTimeFrom = stripslashes($this->EveningTimeFrom);
			$this->EveningTimeTo = stripslashes($this->EveningTimeTo);
			$this->Holiday = stripslashes($this->Holiday);
			$this->Notes = stripslashes($this->Notes);
			$this->MansionName = stripslashes($this->MansionName);
			$this->MansionMemo = stripslashes($this->MansionMemo);
			$this->TatoFlg = stripslashes($this->TatoFlg);
			$this->Juuko = stripslashes($this->Juuko);
			$this->Yomikata = stripslashes($this->Yomikata);
			$this->HearingNo = stripslashes($this->HearingNo);
			$this->Company = stripslashes($this->Company);
			$this->ConstTime = stripslashes($this->ConstTime);
			$this->Tanto1 = stripslashes($this->Tanto1);
			$this->Tanto2 = stripslashes($this->Tanto2);
			$this->Tanto3 = stripslashes($this->Tanto3);
			$this->Tanto4 = stripslashes($this->Tanto4);
			$this->Tanto5 = stripslashes($this->Tanto5);
			$this->Tanto6 = stripslashes($this->Tanto6);
			$this->Tanto7 = stripslashes($this->Tanto7);
			$this->Tanto8 = stripslashes($this->Tanto8);
			$this->Tanto9 = stripslashes($this->Tanto9);
			$this->TantoMemo = stripslashes($this->TantoMemo);
			$this->Excel = stripslashes($this->Excel);
			$this->SenyuStartDate = stripslashes($this->SenyuStartDate);
			$this->SenyuEndDate = stripslashes($this->SenyuEndDate);
			$this->YoteiTekyoDate = stripslashes($this->YoteiTekyoDate);
			$this->YoyakuStartDate = stripslashes($this->YoyakuStartDate);
			$this->YoyakuEndDate = stripslashes($this->YoyakuEndDate);
			$this->KetteiTeikyoDate = stripslashes($this->KetteiTeikyoDate);
			$this->KetteiHaifuDate = stripslashes($this->KetteiHaifuDate);
			$this->KyoyuStartDate = stripslashes($this->KyoyuStartDate);
			$this->KyoyuEndDate = stripslashes($this->KyoyuEndDate);
			$this->OpEndDate = stripslashes($this->OpEndDate);
			$this->Shiharai = stripslashes($this->Shiharai);
			$this->WakuPattern = stripslashes($this->WakuPattern);
			$this->Waku = stripslashes($this->Waku);
			$this->PostingFlg = stripslashes($this->PostingFlg);
			$this->Kaijyo = stripslashes($this->Kaijyo);
			$this->KaijyoAnshoNo = stripslashes($this->KaijyoAnshoNo);
			$this->DoorPostFlg = stripslashes($this->DoorPostFlg);
			$this->KeijiBasho = stripslashes($this->KeijiBasho);
			$this->PostingBiko = stripslashes($this->PostingBiko);

			$this->PicAppSekoName = stripslashes($this->PicAppSekoName);
			$this->PicStatus = stripslashes($this->PicStatus);
			$this->PicChu = stripslashes($this->PicChu);
			$this->PhotoTeikyoDate = stripslashes($this->PhotoTeikyoDate);
			$this->PhotoPattern = stripslashes($this->PhotoPattern);
			$this->KakuninTitle = stripslashes($this->KakuninTitle);
			$this->KakuninFlg = stripslashes($this->KakuninFlg);
			$this->KakuninFooter = stripslashes($this->KakuninFooter);
			$this->MansionNextID = stripslashes($this->MansionNextID);
			$this->WakuRange = stripslashes($this->WakuRange);
			$this->IraiMemo = stripslashes($this->IraiMemo);
			$this->Answer = stripslashes($this->Answer);
			$this->WEBRecept = stripslashes($this->WEBRecept);
			$this->OptionPA = stripslashes($this->OptionPA);
			$this->PosDateST = stripslashes($this->PosDateST);
			$this->PosDateFN = stripslashes($this->PosDateFN);
			$this->SFBukkenCD = stripslashes($this->SFBukkenCD);
			$this->BoardTerop = stripslashes($this->BoardTerop);
			$this->BoardNews = stripslashes($this->BoardNews);
			$this->Address = stripslashes($this->Address);
			$this->SmartFlg = stripslashes($this->SmartFlg);
			$this->SmartBoardSuu = stripslashes($this->SmartBoardSuu);
			$this->SmartBoardStaff = stripslashes($this->SmartBoardStaff);
			$this->OyaKataban = stripslashes($this->OyaKataban);
			$this->KokiKataban = stripslashes($this->KokiKataban);
			$this->HanSu = stripslashes($this->HanSu);
			$this->MongonCD = stripslashes($this->MongonCD);
			$this->MongonTagName = stripslashes($this->MongonTagName);
			$this->MongonTagSuu = stripslashes($this->MongonTagSuu);
			$this->MongonShiken = stripslashes($this->MongonShiken);
			$this->MongonSetDateTime = stripslashes($this->MongonSetDateTime);
			$this->HokanPicThumb = stripslashes($this->HokanPicThumb);

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

					if ($this->MansionName != "") $sqlMoji .= ", MansionName";
					if ($this->MansionMemo != "") $sqlMoji .= ", MansionMemo";
					if ($this->TatoFlg != "") $sqlMoji .= ", TatoFlg";
					if ($this->Juuko != "") $sqlMoji .= ", Juuko";
					if ($this->Yomikata != "") $sqlMoji .= ", Yomikata";
					if ($this->HearingNo != "") $sqlMoji .= ", HearingNo";
					if ($this->Company != "") $sqlMoji .= ", Company";
					if ($this->ConstTime != "") $sqlMoji .= ", ConstTime";
					if ($this->Tanto1 != "") $sqlMoji .= ", Tanto1";
					if ($this->Tanto2 != "") $sqlMoji .= ", Tanto2";
					if ($this->Tanto3 != "") $sqlMoji .= ", Tanto3";
					if ($this->Tanto4 != "") $sqlMoji .= ", Tanto4";
					if ($this->Tanto5 != "") $sqlMoji .= ", Tanto5";
					if ($this->Tanto6 != "") $sqlMoji .= ", Tanto6";
					if ($this->Tanto7 != "") $sqlMoji .= ", Tanto7";
					if ($this->Tanto8 != "") $sqlMoji .= ", Tanto8";
					if ($this->Tanto9 != "") $sqlMoji .= ", Tanto9";
					if ($this->TantoMemo != "") $sqlMoji .= ", TantoMemo";
					if ($this->Excel != "") $sqlMoji .= ", Excel";
					if ($this->SenyuStartDate != "") $sqlMoji .= ", SenyuStartDate";
					if ($this->SenyuEndDate != "") $sqlMoji .= ", SenyuEndDate";
					if ($this->YoteiTekyoDate != "") $sqlMoji .= ", YoteiTekyoDate";
					if ($this->YoyakuStartDate != "") $sqlMoji .= ", YoyakuStartDate";
					if ($this->YoyakuEndDate != "") $sqlMoji .= ", YoyakuEndDate";
					if ($this->KetteiTeikyoDate != "") $sqlMoji .= ", KetteiTeikyoDate";
					if ($this->KetteiHaifuDate != "") $sqlMoji .= ", KetteiHaifuDate";
					if ($this->KyoyuStartDate != "") $sqlMoji .= ", KyoyuStartDate";
					if ($this->KyoyuEndDate != "") $sqlMoji .= ", KyoyuEndDate";
					if ($this->OpEndDate != "") $sqlMoji .= ", OpEndDate";
					if ($this->Shiharai != "") $sqlMoji .= ", Shiharai";
					if ($this->WakuPattern != "") $sqlMoji .= ", WakuPattern";
					if ($this->Waku != "") $sqlMoji .= ", Waku";
					if ($this->PostingFlg != "") $sqlMoji .= ", PostingFlg";
					if ($this->Kaijyo != "") $sqlMoji .= ", Kaijyo";
					if ($this->KaijyoAnshoNo != "") $sqlMoji .= ", KaijyoAnshoNo";
					if ($this->DoorPostFlg != "") $sqlMoji .= ", DoorPostFlg";
					if ($this->KeijiBasho != "") $sqlMoji .= ", KeijiBasho";
					if ($this->PostingBiko != "") $sqlMoji .= ", PostingBiko";

					if ($this->PicAppSekoName != "") $sqlMoji .= ", PicAppSekoName";
					if ($this->PicStatus != "") $sqlMoji .= ", PicStatus";
					if ($this->PicChu != "") $sqlMoji .= ", PicChu";
					if ($this->PhotoTeikyoDate != "") $sqlMoji .= ", PhotoTeikyoDate";
					if ($this->PhotoPattern != "") $sqlMoji .= ", PhotoPattern";
					if ($this->KakuninTitle != "") $sqlMoji .= ", KakuninTitle";
					if ($this->KakuninFlg != "") $sqlMoji .= ", KakuninFlg";
					if ($this->KakuninFooter != "") $sqlMoji .= ", KakuninFooter";
					if ($this->MansionNextID != "") $sqlMoji .= ", MansionNextID";
					if ($this->WakuRange != "") $sqlMoji .= ", WakuRange";
					if ($this->IraiMemo != "") $sqlMoji .= ", IraiMemo";
					if ($this->Answer != "") $sqlMoji .= ", Answer";
					if ($this->WEBRecept != "") $sqlMoji .= ", WEBRecept";
					if ($this->OptionPA != "") $sqlMoji .= ", OptionPA";
					if ($this->PosDateST != "") $sqlMoji .= ", PosDateST";
					if ($this->PosDateFN != "") $sqlMoji .= ", PosDateFN";
					if ($this->SFBukkenCD != "") $sqlMoji .= ", SFBukkenCD";
					if ($this->BoardTerop != "") $sqlMoji .= ", BoardTerop";
					if ($this->BoardNews != "") $sqlMoji .= ", BoardNews";
					if ($this->Address != "") $sqlMoji .= ", Address";
					if ($this->SmartFlg != "") $sqlMoji .= ", SmartFlg";
					if ($this->SmartBoardSuu != "") $sqlMoji .= ", SmartBoardSuu";
					if ($this->SmartBoardStaff != "") $sqlMoji .= ", SmartBoardStaff";
					if ($this->OyaKataban != "") $sqlMoji .= ", OyaKataban";
					if ($this->KokiKataban != "") $sqlMoji .= ", KokiKataban";
					if ($this->HanSu != "") $sqlMoji .= ", HanSu";
					if ($this->MongonCD != "") $sqlMoji .= ", MongonCD";
					if ($this->MongonTagName != "") $sqlMoji .= ", MongonTagName";
					if ($this->MongonTagSuu != "") $sqlMoji .= ", MongonTagSuu";
					if ($this->MongonShiken != "") $sqlMoji .= ", MongonShiken";
					if ($this->MongonSetDateTime != "") $sqlMoji .= ", MongonSetDateTime";
#					if ($this->HokanPicThumb != "") $sqlMoji .= ", HokanPicThumb";

					if ($this->MukouFlg != "") $sqlMoji .= ", MukouFlg";
					$sqlMoji .= ", Created";
					if ($this->Creator != "") $sqlMoji .= ", Creator";
					$sqlMoji .= ", Updated";
					if ($this->Updater != "") $sqlMoji .= ", Updater";
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

					if ($this->MansionName != "") $sqlMoji .= ", '" . addslashes($this->MansionName) . "'";
					if ($this->MansionMemo != "") $sqlMoji .= ", '" . addslashes($this->MansionMemo) . "'";
					if ($this->TatoFlg != "") $sqlMoji .= ", '" . addslashes($this->TatoFlg) . "'";
					if ($this->Juuko != "") $sqlMoji .= ", '" . addslashes($this->Juuko) . "'";
					if ($this->Yomikata != "") $sqlMoji .= ", '" . addslashes($this->Yomikata) . "'";
					if ($this->HearingNo != "") $sqlMoji .= ", '" . addslashes($this->HearingNo) . "'";
					if ($this->Company != "") $sqlMoji .= ", '" . addslashes($this->Company) . "'";
					if ($this->ConstTime != "") $sqlMoji .= ", '" . addslashes($this->ConstTime) . "'";
					if ($this->Tanto1 != "") $sqlMoji .= ", '" . addslashes($this->Tanto1) . "'";
					if ($this->Tanto2 != "") $sqlMoji .= ", '" . addslashes($this->Tanto2) . "'";
					if ($this->Tanto3 != "") $sqlMoji .= ", '" . addslashes($this->Tanto3) . "'";
					if ($this->Tanto4 != "") $sqlMoji .= ", '" . addslashes($this->Tanto4) . "'";
					if ($this->Tanto5 != "") $sqlMoji .= ", '" . addslashes($this->Tanto5) . "'";
					if ($this->Tanto6 != "") $sqlMoji .= ", '" . addslashes($this->Tanto6) . "'";
					if ($this->Tanto7 != "") $sqlMoji .= ", '" . addslashes($this->Tanto7) . "'";
					if ($this->Tanto8 != "") $sqlMoji .= ", '" . addslashes($this->Tanto8) . "'";
					if ($this->Tanto9 != "") $sqlMoji .= ", '" . addslashes($this->Tanto9) . "'";
					if ($this->TantoMemo != "") $sqlMoji .= ", '" . addslashes($this->TantoMemo) . "'";
					if ($this->Excel != "") $sqlMoji .= ", '" . addslashes($this->Excel) . "'";
					if ($this->SenyuStartDate != "") $sqlMoji .= ", '" . addslashes($this->SenyuStartDate) . "'";
					if ($this->SenyuEndDate != "") $sqlMoji .= ", '" . addslashes($this->SenyuEndDate) . "'";
					if ($this->YoteiTekyoDate != "") $sqlMoji .= ", '" . addslashes($this->YoteiTekyoDate) . "'";
					if ($this->YoyakuStartDate != "") $sqlMoji .= ", '" . addslashes($this->YoyakuStartDate) . "'";
					if ($this->YoyakuEndDate != "") $sqlMoji .= ", '" . addslashes($this->YoyakuEndDate) . "'";
					if ($this->KetteiTeikyoDate != "") $sqlMoji .= ", '" . addslashes($this->KetteiTeikyoDate) . "'";
					if ($this->KetteiHaifuDate != "") $sqlMoji .= ", '" . addslashes($this->KetteiHaifuDate) . "'";
					if ($this->KyoyuStartDate != "") $sqlMoji .= ", '" . addslashes($this->KyoyuStartDate) . "'";
					if ($this->KyoyuEndDate != "") $sqlMoji .= ", '" . addslashes($this->KyoyuEndDate) . "'";
					if ($this->OpEndDate != "") $sqlMoji .= ", '" . addslashes($this->OpEndDate) . "'";
					if ($this->Shiharai != "") $sqlMoji .= ", '" . addslashes($this->Shiharai) . "'";
					if ($this->WakuPattern != "") $sqlMoji .= ", '" . addslashes($this->WakuPattern) . "'";
					if ($this->Waku != "") $sqlMoji .= ", '" . addslashes($this->Waku) . "'";
					if ($this->PostingFlg != "") $sqlMoji .= ", '" . addslashes($this->PostingFlg) . "'";
					if ($this->Kaijyo != "") $sqlMoji .= ", '" . addslashes($this->Kaijyo) . "'";
					if ($this->KaijyoAnshoNo != "") $sqlMoji .= ", '" . addslashes($this->KaijyoAnshoNo) . "'";
					if ($this->DoorPostFlg != "") $sqlMoji .= ", '" . addslashes($this->DoorPostFlg) . "'";
					if ($this->KeijiBasho != "") $sqlMoji .= ", '" . addslashes($this->KeijiBasho) . "'";
					if ($this->PostingBiko != "") $sqlMoji .= ", '" . addslashes($this->PostingBiko) . "'";

					if ($this->PicAppSekoName != "") $sqlMoji .= ", '" . addslashes($this->PicAppSekoName) . "'";
					if ($this->PicStatus != "") $sqlMoji .= ", '" . addslashes($this->PicStatus) . "'";
					if ($this->PicChu != "") $sqlMoji .= ", '" . addslashes($this->PicChu) . "'";
					if ($this->PhotoTeikyoDate != "") $sqlMoji .= ", '" . addslashes($this->PhotoTeikyoDate) . "'";
					if ($this->PhotoPattern != "") $sqlMoji .= ", '" . addslashes($this->PhotoPattern) . "'";
					if ($this->KakuninTitle != "") $sqlMoji .= ", '" . addslashes($this->KakuninTitle) . "'";
					if ($this->KakuninFlg != "") $sqlMoji .= ", '" . addslashes($this->KakuninFlg) . "'";
					if ($this->KakuninFooter != "") $sqlMoji .= ", '" . addslashes($this->KakuninFooter) . "'";
					if ($this->MansionNextID != "") $sqlMoji .= ", '" . addslashes($this->MansionNextID) . "'";
					if ($this->WakuRange != "") $sqlMoji .= ", '" . addslashes($this->WakuRange) . "'";
					if ($this->IraiMemo != "") $sqlMoji .= ", '" . addslashes($this->IraiMemo) . "'";
					if ($this->Answer != "") $sqlMoji .= ", '" . addslashes($this->Answer) . "'";
					if ($this->WEBRecept != "") $sqlMoji .= ", '" . addslashes($this->WEBRecept) . "'";
					if ($this->OptionPA != "") $sqlMoji .= ", '" . addslashes($this->OptionPA) . "'";
					if ($this->PosDateST != "") $sqlMoji .= ", '" . addslashes($this->PosDateST) . "'";
					if ($this->PosDateFN != "") $sqlMoji .= ", '" . addslashes($this->PosDateFN) . "'";
					if ($this->SFBukkenCD != "") $sqlMoji .= ", '" . addslashes($this->SFBukkenCD) . "'";
					if ($this->BoardTerop != "") $sqlMoji .= ", '" . addslashes($this->BoardTerop) . "'";
					if ($this->BoardNews != "") $sqlMoji .= ", '" . addslashes($this->BoardNews) . "'";
					if ($this->Address != "") $sqlMoji .= ", '" . addslashes($this->Address) . "'";
					if ($this->SmartFlg != "") $sqlMoji .= ", '" . addslashes($this->SmartFlg) . "'";
					if ($this->SmartBoardSuu != "") $sqlMoji .= ", '" . addslashes($this->SmartBoardSuu) . "'";
					if ($this->SmartBoardStaff != "") $sqlMoji .= ", '" . addslashes($this->SmartBoardStaff) . "'";
					if ($this->OyaKataban != "") $sqlMoji .= ", '" . addslashes($this->OyaKataban) . "'";
					if ($this->KokiKataban != "") $sqlMoji .= ", '" . addslashes($this->KokiKataban) . "'";
					if ($this->HanSu != "") $sqlMoji .= ", '" . addslashes($this->HanSu) . "'";
					if ($this->MongonCD != "") $sqlMoji .= ", '" . addslashes($this->MongonCD) . "'";
					if ($this->MongonTagName != "") $sqlMoji .= ", '" . addslashes($this->MongonTagName) . "'";
					if ($this->MongonTagSuu != "") $sqlMoji .= ", '" . addslashes($this->MongonTagSuu) . "'";
					if ($this->MongonShiken != "") $sqlMoji .= ", '" . addslashes($this->MongonShiken) . "'";
					if ($this->MongonSetDateTime != "") $sqlMoji .= ", '" . addslashes($this->MongonSetDateTime) . "'";
#					if ($this->HokanPicThumb != "") $sqlMoji .= ", '" . addslashes($this->HokanPicThumb) . "'";

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


//20110530 kim modify	$sqlMoji .= ", ReserveFrom = " . $this->convertNullIntValue($this->ReserveFrom);

			$sqlMoji .= ", ReserveFrom = " . $this->convertNullStringValue($this->ReserveFrom);

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

			$sqlMoji .= ", MansionName = " . $this->convertNullStringValue($this->MansionName);
			$sqlMoji .= ", MansionMemo = " . $this->convertNullStringValue($this->MansionMemo);
			$sqlMoji .= ", TatoFlg = " . $this->convertNullStringValue($this->TatoFlg);
			$sqlMoji .= ", Juuko = " . $this->convertNullStringValue($this->Juuko);
			$sqlMoji .= ", Yomikata = " . $this->convertNullStringValue($this->Yomikata);
			$sqlMoji .= ", HearingNo = " . $this->convertNullStringValue($this->HearingNo);
			$sqlMoji .= ", Company = " . $this->convertNullStringValue($this->Company);
			$sqlMoji .= ", ConstTime = " . $this->convertNullStringValue($this->ConstTime);
			$sqlMoji .= ", Tanto1 = " . $this->convertNullStringValue($this->Tanto1);
			$sqlMoji .= ", Tanto2 = " . $this->convertNullStringValue($this->Tanto2);
			$sqlMoji .= ", Tanto3 = " . $this->convertNullStringValue($this->Tanto3);
			$sqlMoji .= ", Tanto4 = " . $this->convertNullStringValue($this->Tanto4);
			$sqlMoji .= ", Tanto5 = " . $this->convertNullStringValue($this->Tanto5);
			$sqlMoji .= ", Tanto6 = " . $this->convertNullStringValue($this->Tanto6);
			$sqlMoji .= ", Tanto7 = " . $this->convertNullStringValue($this->Tanto7);
			$sqlMoji .= ", Tanto8 = " . $this->convertNullStringValue($this->Tanto8);
			$sqlMoji .= ", Tanto9 = " . $this->convertNullStringValue($this->Tanto9);
			$sqlMoji .= ", TantoMemo = " . $this->convertNullStringValue($this->TantoMemo);
			$sqlMoji .= ", Excel = " . $this->convertNullStringValue($this->Excel);
			$sqlMoji .= ", SenyuStartDate = " . $this->convertNullStringValue($this->SenyuStartDate);
			$sqlMoji .= ", SenyuEndDate = " . $this->convertNullStringValue($this->SenyuEndDate);
			$sqlMoji .= ", YoteiTekyoDate = " . $this->convertNullStringValue($this->YoteiTekyoDate);
			$sqlMoji .= ", YoyakuStartDate = " . $this->convertNullStringValue($this->YoyakuStartDate);
			$sqlMoji .= ", YoyakuEndDate = " . $this->convertNullStringValue($this->YoyakuEndDate);
			$sqlMoji .= ", KetteiTeikyoDate = " . $this->convertNullStringValue($this->KetteiTeikyoDate);
			$sqlMoji .= ", KetteiHaifuDate = " . $this->convertNullStringValue($this->KetteiHaifuDate);
			$sqlMoji .= ", KyoyuStartDate = " . $this->convertNullStringValue($this->KyoyuStartDate);
			$sqlMoji .= ", KyoyuEndDate = " . $this->convertNullStringValue($this->KyoyuEndDate);
			$sqlMoji .= ", OpEndDate = " . $this->convertNullStringValue($this->OpEndDate);
			$sqlMoji .= ", Shiharai = " . $this->convertNullStringValue($this->Shiharai);
			$sqlMoji .= ", WakuPattern = " . $this->convertNullStringValue($this->WakuPattern);
			$sqlMoji .= ", Waku = " . $this->convertNullStringValue($this->Waku);
			$sqlMoji .= ", PostingFlg = " . $this->convertNullStringValue($this->PostingFlg);
			$sqlMoji .= ", Kaijyo = " . $this->convertNullStringValue($this->Kaijyo);
			$sqlMoji .= ", KaijyoAnshoNo = " . $this->convertNullStringValue($this->KaijyoAnshoNo);
			$sqlMoji .= ", DoorPostFlg = " . $this->convertNullStringValue($this->DoorPostFlg);
			$sqlMoji .= ", KeijiBasho = " . $this->convertNullStringValue($this->KeijiBasho);
			$sqlMoji .= ", PostingBiko = " . $this->convertNullStringValue($this->PostingBiko);

			$sqlMoji .= ", PicAppSekoName = " . $this->convertNullStringValue($this->PicAppSekoName);
			$sqlMoji .= ", PicStatus = " . $this->convertNullStringValue($this->PicStatus);
			$sqlMoji .= ", PicChu = " . $this->convertNullStringValue($this->PicChu);
			$sqlMoji .= ", PhotoTeikyoDate = " . $this->convertNullStringValue($this->PhotoTeikyoDate);
			$sqlMoji .= ", PhotoPattern = " . $this->convertNullStringValue($this->PhotoPattern);
			$sqlMoji .= ", KakuninTitle = " . $this->convertNullStringValue($this->KakuninTitle);
			$sqlMoji .= ", KakuninFlg = " . $this->convertNullStringValue($this->KakuninFlg);
			$sqlMoji .= ", KakuninFooter = " . $this->convertNullStringValue($this->KakuninFooter);
			$sqlMoji .= ", MansionNextID = " . $this->convertNullStringValue($this->MansionNextID);
			$sqlMoji .= ", WakuRange = " . $this->convertNullStringValue($this->WakuRange);
			$sqlMoji .= ", IraiMemo = " . $this->convertNullStringValue($this->IraiMemo);
			$sqlMoji .= ", Answer = " . $this->convertNullStringValue($this->Answer);
			$sqlMoji .= ", WEBRecept = " . $this->convertNullStringValue($this->WEBRecept);
			$sqlMoji .= ", OptionPA = " . $this->convertNullStringValue($this->OptionPA);
			$sqlMoji .= ", PosDateST = " . $this->convertNullStringValue($this->PosDateST);
			$sqlMoji .= ", PosDateFN = " . $this->convertNullStringValue($this->PosDateFN);
			$sqlMoji .= ", SFBukkenCD = " . $this->convertNullStringValue($this->SFBukkenCD);
			$sqlMoji .= ", BoardTerop = " . $this->convertNullStringValue($this->BoardTerop);
			$sqlMoji .= ", BoardNews = " . $this->convertNullStringValue($this->BoardNews);
			$sqlMoji .= ", Address = " . $this->convertNullStringValue($this->Address);
			$sqlMoji .= ", SmartFlg = " . $this->convertNullStringValue($this->SmartFlg);
			$sqlMoji .= ", SmartBoardSuu = " . $this->convertNullStringValue($this->SmartBoardSuu);
			$sqlMoji .= ", SmartBoardStaff = " . $this->convertNullStringValue($this->SmartBoardStaff);
			$sqlMoji .= ", OyaKataban = " . $this->convertNullStringValue($this->OyaKataban);
			$sqlMoji .= ", KokiKataban = " . $this->convertNullStringValue($this->KokiKataban);
			$sqlMoji .= ", HanSu = " . $this->convertNullStringValue($this->HanSu);
			$sqlMoji .= ", MongonCD = " . $this->convertNullStringValue($this->MongonCD);
			$sqlMoji .= ", MongonTagName = " . $this->convertNullStringValue($this->MongonTagName);
			$sqlMoji .= ", MongonTagSuu = " . $this->convertNullStringValue($this->MongonTagSuu);
			$sqlMoji .= ", MongonShiken = " . $this->convertNullStringValue($this->MongonShiken);
			$sqlMoji .= ", MongonSetDateTime = " . $this->convertNullStringValue($this->MongonSetDateTime);
#			$sqlMoji .= ", HokanPicThumb = " . $this->convertNullStringValue($this->HokanPicThumb);

			$sqlMoji .= ", MukouFlg = " . $this->convertFlgToString($this->MukouFlg);
			$sqlMoji .= ", Created = " . $this->convertNullStringValue($this->Created);
			$sqlMoji .= ", Creator = " . $this->convertNullIntValue($this->Creator);
			$sqlMoji .= ", Updated = NOW()";
			$sqlMoji .= ", Updater = " . $this->convertNullIntValue($this->Updater);
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
