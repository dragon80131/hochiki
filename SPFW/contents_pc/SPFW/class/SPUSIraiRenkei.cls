<?php

include_once _CLS_DIR . "SPFWLog.cls";

class IraiRenkei
{
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
	var $IraiRenkeiCD;
	var $IraiRenkeiStatus;
	var $BukkenCD;
	var $IraiDate;
	var $SekoShutai;
	var $Answer;
	var $Answer489;
	var $WEBRecept;
	var $YoshikiYotei;
	var $YoshikiKakutei;
	var $OPUketuke;
	var $KakuteiCompanyName;
	var $PicStatus;
	var $PicAppSekoName;
	var $PicAppKojiName;
	var $PostingFlg;
	var $PostingName;
	var $PostingBasho;
	var $PostType;
	var $WanshoFlg;
	var $PostingBiko;

	var $KakuninFlg;		//
	var $KakuninTitle;
	var $KakuninFooter;
	var $YoteTekyoDate;		//
	var $YoteDate;			//
	var $YoyakuEnd;			//
	var $TekyoDate;			//
	var $KeteiDate;			//20
	var $TantoCD1;			//
	var $TantoCD2;			//
	var $GyosyaTantoCD1;	//
	var $GyosyaTantoCD2;	//
	var $WakuRange;			//
	var $TimeAStart;		//
	var $TimeBStart;		//
	var $TimeCStart;		//
	var $TimeAEnd;			//
	var $TimeBEnd;			//30
	var $TimeCEnd;			//
	var $TimeASu;			//
	var $TimeBSu;			//
	var $TimeCSu;			//

	var $OyaKataban;		//
	var $KokiKataban;		//
	var $OyaDeviceCD;
	var $KokiDeviceCD;
	var $KokiPanelKataban;	//

	var $OP1Name;		//
	var $OP2Name;		//
	var $OP3Name;		//40
	var $OP4Name;		//
	var $OP5Name;
	var $OP6Name;
	var $OP7Name;
	var $OP8Name;
	var $OP9Name;
	var $OP10Name;

	var $OPPrice1;			//
	var $OPPrice2;			//
	var $OPPrice3;			//
	var $OPPrice4;			//
	var $OPPrice5;
	var $OPPrice6;
	var $OPPrice7;
	var $OPPrice8;
	var $OPPrice9;
	var $OPPrice10;

	var $OPCategory1;
	var $OPCategory2;
	var $OPCategory3;
	var $OPCategory4;
	var $OPCategory5;
	var $OPCategory6;
	var $OPCategory7;
	var $OPCategory8;
	var $OPCategory9;
	var $OPCategory10;

	var $OPNotes;			//
	var $Shiharai;			//
	var $Hansu;				//
	var $ConstTime;			//
	var $KyukoDate;			//50
	var $Kaijyo;			//
	var $Isetu;				//
	var $PhotoTekyoDate;	//

	var $PhotoPattern;		//
	var $PhotoSenyu1;		//
	var $PhotoSenyu2;		//
	var $PhotoSenyu3;		//
	var $PhotoSenyu4;		//
	var $PhotoSenyu5;		//
	var $PhotoSenyu6;		//
	var $PhotoKyoyo1;		//
	var $PhotoKyoyo2;		//60
	var $PhotoKyoyo3;		//
	var $PhotoKyoyo4;		//
	var $PhotoKyoyo5;		//
	var $PhotoKyoyo6;		//
	var $PhotoKyoyo7;		//
	var $PhotoKyoyo8;		//
	var $PhotoKyoyo9;		//
	var $PSScene1;			//
	var $PSScene2;			//
	var $PSScene3;			//70
	var $PSScene4;			//
	var $PSScene5;			//
	var $PSScene6;			//
	var $PKScene1;			//
	var $PKScene2;			//
	var $PKScene3;			//
	var $PKScene4;			//
	var $PKScene5;			//
	var $PKScene6;			//
	var $PKScene7;			//
	var $PKScene8;			//
	var $PKScene9;			//80

	var $PhotoKyoyoNotes;	//
	var $Notes;				//
	var $RenewalType;		//
	var $HaisenType;		//
	var $KanrishituCall;	//
	var $PicKSu;			//
	var $PicKType;			//
	var $PicSu;				//
	var $PicType;			//
	var $Shobo;				//90
	var $TeishutuDoc;		//
	var $SonotaShiji;		//
	var $KyoyuStartDate;	//
	var $SenyuEndDate;		//
	var $BukkenCD489;		//
	var $IraiUpdated;		//96

	var $KyoyoEndDate;
	var $SenyuStartDate;
	var $Otherwise6;
	var $DateS6;
	var $DateE6;
	var $Otherwise7;
	var $DateS7;
	var $DateE7;
	var $Otherwise8;
	var $DateS8;
	var $DateE8;
	var $Otherwise9;
	var $DateS9;
	var $DateE9;
	var $Otherwise10;
	var $DateS10;
	var $DateE10;

	var $IR01;
	var $IR02;
	var $IR03;
	var $IR04;
	var $IR05;
	var $IR06;
	var $IR07;
	var $OpEndDate;
	var $KeijiBasho;
	var $SmartFlg;
	var $TantoCD3;
	var $TantoCD4;
	var $TantoMemo;
	var $GyosyaTantoCD3;
	var $GyosyaTantoCD4;
	var $GyosyaTantoCD5;
	var $GyosyaTantoMemo;





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

	function __construct($myDB = "", $IraiRenkeiCD = -1)
	{
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
		settype($IraiRenkeiCD, "integer");
		// 整数チェック
		if (!is_long($IraiRenkeiCD) or $IraiRenkeiCD == 0) {
			$this->Err = "ユーザーコード が不正です";
			$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
			unset($myLog);
			return FALSE;
		}

		$this->myDB = $myDB;
		$this->TableName = "tIraiRenkeiF";
		$this->IraiRenkeiCD = $IraiRenkeiCD;

		return TRUE;
	}

	function initialize()
	{
		$this->Err = "";

		$this->IraiRenkeiCD = "";
		$this->IraiRenkeiStatus = "";
		$this->BukkenCD = "";
		$this->IraiDate = "";
		$this->SekoShutai = "";
		$this->Answer = "";
		$this->Answer489 = "";
		$this->WEBRecept = "";
		$this->YoshikiYotei = "";
		$this->YoshikiKakutei = "";
		$this->OPUketuke = "";
		$this->KakuteiCompanyName = "";
		$this->PicStatus = "";
		$this->PicAppSekoNames = "";
		$this->PicAppKojiName = "";
		$this->PostingName = "";
		$this->PostingBasho = "";
		$this->PostType = "";
		$this->WanshoFlg = "";
		$this->PostingBiko = "";

		$this->KakuninFlg = "";
		$this->KakuninTitle = "";
		$this->KakuninFooter = "";
		$this->YoteTekyoDate = "";
		$this->YoteDate = "";
		$this->YoyakuEnd = "";
		$this->TekyoDate = "";
		$this->KeteiDate = "";
		$this->TantoCD1 = "";
		$this->TantoCD2 = "";
		$this->GyosyaTantoCD1 = "";
		$this->GyosyaTantoCD2 = "";
		$this->WakuRange = "";
		$this->TimeAStart = "";
		$this->TimeBStart = "";
		$this->TimeCStart = "";
		$this->TimeAEnd = "";
		$this->TimeBEnd = "";
		$this->TimeCEnd = "";

		$this->TimeASu = "";
		$this->TimeBSu = "";
		$this->TimeCSu = "";
		$this->OyaKataban = "";
		$this->KokiKataban = "";
		$this->OyaDeviceCD = "";
		$this->KokiDeviceCD = "";
		$this->KokiPanelKataban = "";
		$this->OP1Name = "";
		$this->OP2Name = "";
		$this->OP3Name = "";
		$this->OP4Name = "";
		$this->OP5Name = "";
		$this->OP6Name = "";
		$this->OP7Name = "";
		$this->OP8Name = "";
		$this->OP9Name = "";
		$this->OP10Name = "";
		$this->OPPrice1 = "";
		$this->OPPrice2 = "";
		$this->OPPrice3 = "";
		$this->OPPrice4 = "";
		$this->OPPrice5 = "";
		$this->OPPrice6 = "";
		$this->OPPrice7 = "";
		$this->OPPrice8 = "";
		$this->OPPrice9 = "";
		$this->OPPrice10 = "";
		$this->OPCategory1 = "";
		$this->OPCategory2 = "";
		$this->OPCategory3 = "";
		$this->OPCategory4 = "";
		$this->OPCategory5 = "";
		$this->OPCategory6 = "";
		$this->OPCategory7 = "";
		$this->OPCategory8 = "";
		$this->OPCategory9 = "";
		$this->OPCategory10 = "";

		$this->OPNotes = "";
		$this->Shiharai = "";
		$this->Hansu = "";
		$this->ConstTime = "";
		$this->KyukoDate = "";
		$this->Kaijyo = "";
		$this->Isetu = "";
		$this->PhotoTekyoDate = "";
		$this->PhotoPattern = "";
		$this->PhotoSenyu1 = "";
		$this->PSScene1 = "";
		$this->PhotoSenyu2 = "";
		$this->PSScene2 = "";
		$this->PhotoSenyu3 = "";
		$this->PSScene3 = "";
		$this->PhotoSenyu4 = "";
		$this->PSScene4 = "";
		$this->PhotoSenyu5 = "";
		$this->PSScene5 = "";
		$this->PhotoSenyu6 = "";
		$this->PSScene6 = "";
		$this->PhotoKyoyo1 = "";
		$this->PKScene1 = "";
		$this->PhotoKyoyo2 = "";
		$this->PKScene2 = "";
		$this->PhotoKyoyo3 = "";
		$this->PKScene3 = "";
		$this->PhotoKyoyo4 = "";
		$this->PKScene4 = "";
		$this->PhotoKyoyo5 = "";
		$this->PKScene5 = "";
		$this->PhotoKyoyo6 = "";
		$this->PKScene6 = "";
		$this->PhotoKyoyo7 = "";
		$this->PKScene7 = "";
		$this->PhotoKyoyo8 = "";
		$this->PKScene8 = "";
		$this->PhotoKyoyo9 = "";
		$this->PKScene9 = "";
		$this->PhotoKyoyoNotes = "";
		$this->Notes = "";
		$this->RenewalType = "";
		$this->HaisenType = "";
		$this->KanrishituCall = "";
		$this->PicKSu = "";
		$this->PicKType = "";
		$this->PicSu = "";
		$this->PicType = "";
		$this->Shobo = "";
		$this->TeishutuDoc = "";
		$this->SonotaShiji = "";

		$this->KyoyuStartDate = "";
		$this->SenyuEndDate = "";
		$this->BukkenCD489 = "";
		$this->IraiUpdated = "";
		$this->KyoyoEndDate = "";
		$this->SenyuStartDate = "";
		$this->Otherwise6 = "";
		$this->DateS6 = "";
		$this->DateE6 = "";
		$this->Otherwise7 = "";
		$this->DateS7 = "";
		$this->DateE7 = "";
		$this->Otherwise8 = "";
		$this->DateS8 = "";
		$this->DateE8 = "";
		$this->Otherwise9 = "";
		$this->DateS9 = "";
		$this->DateE9 = "";
		$this->Otherwise10 = "";
		$this->DateS10 = "";
		$this->DateE10 = "";

		$this->IR01 = "";
		$this->IR02 = "";
		$this->IR03 = "";
		$this->IR04 = "";
		$this->IR05 = "";
		$this->IR06 = "";
		$this->IR07 = "";
		$this->OpEndDate = "";
		$this->KeijiBasho = "";
		$this->SmartFlg = "";
		$this->TantoCD3 = "";
		$this->TantoCD4 = "";
		$this->TantoMemo = "";
		$this->GyosyaTantoCD3 = "";
		$this->GyosyaTantoCD4 = "";
		$this->GyosyaTantoCD5 = "";
		$this->GyosyaTantoMemo = "";


		$this->MukouFlg = "";
		$this->Created = "";
		$this->Creator = "";
		$this->Updated = "";
		$this->Updater = "";
	}

	//抽出
	function executeSelect($WhereStr = "", $OrderByStr = "")
	{
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
		$sqlMoji .= "IraiRenkeiCD ";
		$sqlMoji .= ", IraiRenkeiStatus ";
		$sqlMoji .= ", BukkenCD ";
		$sqlMoji .= ", IraiDate ";
		$sqlMoji .= ", SekoShutai ";
		$sqlMoji .= ", Answer ";
		$sqlMoji .= ", Answer489 ";
		$sqlMoji .= ", WEBRecept ";
		$sqlMoji .= ", YoshikiYotei ";
		$sqlMoji .= ", YoshikiKakutei ";
		$sqlMoji .= ", OPUketuke ";
		$sqlMoji .= ", KakuteiCompanyName ";
		$sqlMoji .= ", PicStatus ";
		$sqlMoji .= ", PicAppSekoName ";
		$sqlMoji .= ", PicAppKojiName ";
		$sqlMoji .= ", PostingFlg ";
		$sqlMoji .= ", PostingName ";
		$sqlMoji .= ", PostingBasho ";
		$sqlMoji .= ", PostType ";
		$sqlMoji .= ", WanshoFlg ";
		$sqlMoji .= ", PostingBiko ";

		$sqlMoji .= ", KakuninFlg ";
		$sqlMoji .= ", KakuninTitle ";
		$sqlMoji .= ", KakuninFooter ";
		$sqlMoji .= ", YoteTekyoDate ";
		$sqlMoji .= ", YoteDate ";
		$sqlMoji .= ", YoyakuEnd ";
		$sqlMoji .= ", TekyoDate ";
		$sqlMoji .= ", KeteiDate ";
		$sqlMoji .= ", TantoCD1 ";
		$sqlMoji .= ", TantoCD2 ";
		$sqlMoji .= ", GyosyaTantoCD1 ";
		$sqlMoji .= ", GyosyaTantoCD2 ";
		$sqlMoji .= ", WakuRange ";
		$sqlMoji .= ", TimeAStart ";
		$sqlMoji .= ", TimeBStart ";
		$sqlMoji .= ", TimeCStart ";
		$sqlMoji .= ", TimeAEnd ";
		$sqlMoji .= ", TimeBEnd ";
		$sqlMoji .= ", TimeCEnd ";

		$sqlMoji .= ", TimeASu ";
		$sqlMoji .= ", TimeBSu ";
		$sqlMoji .= ", TimeCSu ";
		$sqlMoji .= ", OyaKataban ";
		$sqlMoji .= ", KokiKataban ";
		$sqlMoji .= ", OyaDeviceCD ";
		$sqlMoji .= ", KokiDeviceCD ";
		$sqlMoji .= ", KokiPanelKataban ";
		$sqlMoji .= ", OP1Name ";
		$sqlMoji .= ", OP2Name ";
		$sqlMoji .= ", OP3Name ";
		$sqlMoji .= ", OP4Name ";
		$sqlMoji .= ", OP5Name ";
		$sqlMoji .= ", OP6Name ";
		$sqlMoji .= ", OP7Name ";
		$sqlMoji .= ", OP8Name ";
		$sqlMoji .= ", OP9Name ";
		$sqlMoji .= ", OP10Name ";
		$sqlMoji .= ", OPPrice1 ";
		$sqlMoji .= ", OPPrice2 ";
		$sqlMoji .= ", OPPrice3 ";
		$sqlMoji .= ", OPPrice4 ";
		$sqlMoji .= ", OPPrice5 ";
		$sqlMoji .= ", OPPrice6 ";
		$sqlMoji .= ", OPPrice7 ";
		$sqlMoji .= ", OPPrice8 ";
		$sqlMoji .= ", OPPrice9 ";
		$sqlMoji .= ", OPPrice10 ";
		$sqlMoji .= ", OPCategory1 ";
		$sqlMoji .= ", OPCategory2 ";
		$sqlMoji .= ", OPCategory3 ";
		$sqlMoji .= ", OPCategory4 ";
		$sqlMoji .= ", OPCategory5 ";
		$sqlMoji .= ", OPCategory6 ";
		$sqlMoji .= ", OPCategory7 ";
		$sqlMoji .= ", OPCategory8 ";
		$sqlMoji .= ", OPCategory9 ";
		$sqlMoji .= ", OPCategory10 ";

		$sqlMoji .= ", OPNotes ";
		$sqlMoji .= ", Shiharai ";
		$sqlMoji .= ", Hansu ";
		$sqlMoji .= ", ConstTime ";
		$sqlMoji .= ", KyukoDate ";
		$sqlMoji .= ", Kaijyo ";
		$sqlMoji .= ", Isetu ";
		$sqlMoji .= ", PhotoTekyoDate ";
		$sqlMoji .= ", PhotoPattern ";
		$sqlMoji .= ", PhotoSenyu1 ";
		$sqlMoji .= ", PSScene1 ";
		$sqlMoji .= ", PhotoSenyu2 ";
		$sqlMoji .= ", PSScene2 ";
		$sqlMoji .= ", PhotoSenyu3 ";
		$sqlMoji .= ", PSScene3 ";
		$sqlMoji .= ", PhotoSenyu4 ";
		$sqlMoji .= ", PSScene4 ";
		$sqlMoji .= ", PhotoSenyu5 ";
		$sqlMoji .= ", PSScene5 ";
		$sqlMoji .= ", PhotoSenyu6 ";
		$sqlMoji .= ", PSScene6 ";
		$sqlMoji .= ", PhotoKyoyo1 ";
		$sqlMoji .= ", PKScene1 ";
		$sqlMoji .= ", PhotoKyoyo2 ";
		$sqlMoji .= ", PKScene2 ";
		$sqlMoji .= ", PhotoKyoyo3 ";
		$sqlMoji .= ", PKScene3 ";
		$sqlMoji .= ", PhotoKyoyo4 ";
		$sqlMoji .= ", PKScene4 ";
		$sqlMoji .= ", PhotoKyoyo5 ";
		$sqlMoji .= ", PKScene5 ";
		$sqlMoji .= ", PhotoKyoyo6 ";
		$sqlMoji .= ", PKScene6 ";
		$sqlMoji .= ", PhotoKyoyo7 ";
		$sqlMoji .= ", PKScene7 ";
		$sqlMoji .= ", PhotoKyoyo8 ";
		$sqlMoji .= ", PKScene8 ";
		$sqlMoji .= ", PhotoKyoyo9 ";
		$sqlMoji .= ", PKScene9 ";
		$sqlMoji .= ", PhotoKyoyoNotes ";
		$sqlMoji .= ", Notes ";

		$sqlMoji .= ", RenewalType ";
		$sqlMoji .= ", HaisenType ";
		$sqlMoji .= ", KanrishituCall ";
		$sqlMoji .= ", PicKSu ";
		$sqlMoji .= ", PicKType ";
		$sqlMoji .= ", PicSu ";
		$sqlMoji .= ", PicType ";
		$sqlMoji .= ", Shobo ";
		$sqlMoji .= ", TeishutuDoc ";
		$sqlMoji .= ", SonotaShiji ";

		$sqlMoji .= ", KyoyuStartDate ";
		$sqlMoji .= ", SenyuEndDate ";
		$sqlMoji .= ", BukkenCD489 ";
		$sqlMoji .= ", IraiUpdated ";
		$sqlMoji .= ", KyoyoEndDate ";
		$sqlMoji .= ", SenyuStartDate ";
		$sqlMoji .= ", Otherwise6 ";
		$sqlMoji .= ", DateS6 ";
		$sqlMoji .= ", DateE6 ";
		$sqlMoji .= ", Otherwise7 ";
		$sqlMoji .= ", DateS7 ";
		$sqlMoji .= ", DateE7 ";
		$sqlMoji .= ", Otherwise8 ";
		$sqlMoji .= ", DateS8 ";
		$sqlMoji .= ", DateE8 ";
		$sqlMoji .= ", Otherwise9 ";
		$sqlMoji .= ", DateS9 ";
		$sqlMoji .= ", DateE9 ";
		$sqlMoji .= ", Otherwise10 ";
		$sqlMoji .= ", DateS10 ";
		$sqlMoji .= ", DateE10 ";

		$sqlMoji .= ", IR01 ";
		$sqlMoji .= ", IR02 ";
		$sqlMoji .= ", IR03 ";
		$sqlMoji .= ", IR04 ";
		$sqlMoji .= ", IR05 ";
		$sqlMoji .= ", IR06 ";
		$sqlMoji .= ", IR07 ";
		$sqlMoji .= ", OpEndDate ";
		$sqlMoji .= ", KeijiBasho ";
		$sqlMoji .= ", SmartFlg ";
		$sqlMoji .= ", TantoCD3 ";
		$sqlMoji .= ", TantoCD4 ";
		$sqlMoji .= ", TantoMemo ";
		$sqlMoji .= ", GyosyaTantoCD3 ";
		$sqlMoji .= ", GyosyaTantoCD4 ";
		$sqlMoji .= ", GyosyaTantoCD5 ";
		$sqlMoji .= ", GyosyaTantoMemo ";


		$sqlMoji .= ", MukouFlg";
		$sqlMoji .= ", Created";
		$sqlMoji .= ", Creator";
		$sqlMoji .= ", Updated";
		$sqlMoji .= ", Updater";
		$sqlMoji .= " from $this->TableName";

		if ($WhereStr != "")
			$sqlMoji = $sqlMoji . " where " . $WhereStr;

		if ($OrderByStr != "")
			$sqlMoji = $sqlMoji . " order by " . $OrderByStr;

		$rtn = $this->myDB->executeQuery($sqlMoji);
		if (!$rtn) {
			$this->Err = "レコード取得に失敗しました";
			$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
			unset($myLog);
			return FALSE;
		} else {
			$this->RecCnt = $this->myDB->getNumberOfRows($rtn);
			if ($this->RecCnt == 1) {
				$this->tmprtn = $rtn;
				$this->getDataSet(0);
			} else if ($this->RecCnt > 1) {
				$this->tmprtn = $rtn;
			}
		}

		return TRUE;
	}

	//タプル取得
	function getDataSet($RowNo)
	{
		if ($RowNo >= $this->RecCnt) {
			$this->Err = "行番号が不正です";
			return FALSE;
		} else {
			list(
				$this->IraiRenkeiCD,
				$this->IraiRenkeiStatus,
				$this->BukkenCD,
				$this->IraiDate,
				$this->SekoShutai,
				$this->Answer,
				$this->Answer489,
				$this->WEBRecept,
				$this->YoshikiYotei,
				$this->YoshikiKakutei,
				$this->OPUketuke,
				$this->KakuteiCompanyName,
				$this->PicStatus,
				$this->PicAppSekoName,
				$this->PicAppKojiName,
				$this->PostingFlg,
				$this->PostingName,
				$this->PostingBasho,
				$this->PostType,
				$this->WanshoFlg,
				$this->PostingBiko,

				$this->KakuninFlg,
				$this->KakuninTitle,
				$this->KakuninFooter,
				$this->YoteTekyoDate,
				$this->YoteDate,
				$this->YoyakuEnd,
				$this->TekyoDate,
				$this->KeteiDate,
				$this->TantoCD1,
				$this->TantoCD2,
				$this->GyosyaTantoCD1,
				$this->GyosyaTantoCD2,
				$this->WakuRange,
				$this->TimeAStart,
				$this->TimeBStart,
				$this->TimeCStart,
				$this->TimeAEnd,
				$this->TimeBEnd,
				$this->TimeCEnd,

				$this->TimeASu,
				$this->TimeBSu,
				$this->TimeCSu,
				$this->OyaKataban,
				$this->KokiKataban,
				$this->OyaDeviceCD,
				$this->KokiDeviceCD,
				$this->KokiPanelKataban,
				$this->OP1Name,
				$this->OP2Name,
				$this->OP3Name,
				$this->OP4Name,
				$this->OP5Name,
				$this->OP6Name,
				$this->OP7Name,
				$this->OP8Name,
				$this->OP9Name,
				$this->OP10Name,
				$this->OPPrice1,
				$this->OPPrice2,
				$this->OPPrice3,
				$this->OPPrice4,
				$this->OPPrice5,
				$this->OPPrice6,
				$this->OPPrice7,
				$this->OPPrice8,
				$this->OPPrice9,
				$this->OPPrice10,
				$this->OPCategory1,
				$this->OPCategory2,
				$this->OPCategory3,
				$this->OPCategory4,
				$this->OPCategory5,
				$this->OPCategory6,
				$this->OPCategory7,
				$this->OPCategory8,
				$this->OPCategory9,
				$this->OPCategory10,

				$this->OPNotes,
				$this->Shiharai,
				$this->Hansu,
				$this->ConstTime,
				$this->KyukoDate,
				$this->Kaijyo,
				$this->Isetu,
				$this->PhotoTekyoDate,
				$this->PhotoPattern,
				$this->PhotoSenyu1,
				$this->PSScene1,
				$this->PhotoSenyu2,
				$this->PSScene2,
				$this->PhotoSenyu3,
				$this->PSScene3,
				$this->PhotoSenyu4,
				$this->PSScene4,
				$this->PhotoSenyu5,
				$this->PSScene5,
				$this->PhotoSenyu6,
				$this->PSScene6,
				$this->PhotoKyoyo1,
				$this->PKScene1,
				$this->PhotoKyoyo2,
				$this->PKScene2,
				$this->PhotoKyoyo3,
				$this->PKScene3,
				$this->PhotoKyoyo4,
				$this->PKScene4,
				$this->PhotoKyoyo5,
				$this->PKScene5,
				$this->PhotoKyoyo6,
				$this->PKScene6,
				$this->PhotoKyoyo7,
				$this->PKScene7,
				$this->PhotoKyoyo8,
				$this->PKScene8,
				$this->PhotoKyoyo9,
				$this->PKScene9,
				$this->PhotoKyoyoNotes,
				$this->Notes,

				$this->RenewalType,
				$this->HaisenType,
				$this->KanrishituCall,
				$this->PicKSu,
				$this->PicKType,
				$this->PicSu,
				$this->PicType,
				$this->Shobo,
				$this->TeishutuDoc,
				$this->SonotaShiji,

				$this->KyoyuStartDate,
				$this->SenyuEndDate,
				$this->BukkenCD489,
				$this->IraiUpdated,
				$this->KyoyoEndDate,
				$this->SenyuStartDate,
				$this->Otherwise6,
				$this->DateS6,
				$this->DateE6,
				$this->Otherwise7,
				$this->DateS7,
				$this->DateE7,
				$this->Otherwise8,
				$this->DateS8,
				$this->DateE8,
				$this->Otherwise9,
				$this->DateS9,
				$this->DateE9,
				$this->Otherwise10,
				$this->DateS10,
				$this->DateE10,

				$this->IR01,
				$this->IR02,
				$this->IR03,
				$this->IR04,
				$this->IR05,
				$this->IR06,
				$this->IR07,
				$this->OpEndDate,
				$this->KeijiBasho,
				$this->SmartFlg,
				$this->TantoCD3,
				$this->TantoCD4,
				$this->TantoMemo,
				$this->GyosyaTantoCD3,
				$this->GyosyaTantoCD4,
				$this->GyosyaTantoCD5,
				$this->GyosyaTantoMemo,


				$this->MukouFlg,
				$this->Created,
				$this->Creator,
				$this->Updated,
				$this->Updater
			) = $this->myDB->fetchRow($this->tmprtn, $RowNo);



			$this->IraiDate = stripslashes($this->IraiDate);
			$this->SekoShutai = stripslashes($this->SekoShutai);
			$this->Answer = stripslashes($this->Answer);
			$this->Answer489 = stripslashes($this->Answer489);
			$this->WEBRecept = stripslashes($this->WEBRecept);
			$this->YoshikiYotei = stripslashes($this->YoshikiYotei);
			$this->YoshikiKakutei = stripslashes($this->YoshikiKakutei);
			$this->OPUketuke = stripslashes($this->OPUketuke);
			$this->KakuteiCompanyName = stripslashes($this->KakuteiCompanyName);

			$this->YoteTekyoDate = stripslashes($this->YoteTekyoDate);
			$this->YoteDate = stripslashes($this->YoteDate);
			$this->YoyakuEnd = stripslashes($this->YoyakuEnd);
			$this->TekyoDate = stripslashes($this->TekyoDate);
			$this->KeteiDate = stripslashes($this->KeteiDate);

			$this->PostingBasho = stripslashes($this->PostingBasho);
			$this->PostType = stripslashes($this->PostType);
			$this->WanshoFlg = stripslashes($this->WanshoFlg);
			$this->PostingBiko = stripslashes($this->PostingBiko);

			$this->WakuRange = stripslashes($this->WakuRange);
			$this->TimeAStart = stripslashes($this->TimeAStart);
			$this->TimeBStart = stripslashes($this->TimeBStart);
			$this->TimeCStart = stripslashes($this->TimeCStart);
			$this->TimeAEnd = stripslashes($this->TimeAEnd);
			$this->TimeBEnd = stripslashes($this->TimeBEnd);
			$this->TimeCEnd = stripslashes($this->TimeCEnd);

			$this->TimeASu = stripslashes($this->TimeASu);
			$this->TimeBSu = stripslashes($this->TimeBSu);
			$this->TimeCSu = stripslashes($this->TimeCSu);
			$this->OyaKataban = stripslashes($this->OyaKataban);
			$this->KokiKataban = stripslashes($this->KokiKataban);
			$this->OyaDeviceCD = stripslashes($this->OyaDeviceCD);
			$this->KokiDeviceCD = stripslashes($this->KokiDeviceCD);
			$this->KokiPanelKataban = stripslashes($this->KokiPanelKataban);

			$this->OP1Name = stripslashes($this->OP1Name);
			$this->OP2Name = stripslashes($this->OP2Name);
			$this->OP3Name = stripslashes($this->OP3Name);
			$this->OP4Name = stripslashes($this->OP4Name);
			$this->OP5Name = stripslashes($this->OP5Name);
			$this->OP6Name = stripslashes($this->OP6Name);
			$this->OP7Name = stripslashes($this->OP7Name);
			$this->OP8Name = stripslashes($this->OP8Name);
			$this->OP9Name = stripslashes($this->OP9Name);
			$this->OP10Name = stripslashes($this->OP10Name);
			$this->OPPrice1 = stripslashes($this->OPPrice1);
			$this->OPPrice2 = stripslashes($this->OPPrice2);
			$this->OPPrice3 = stripslashes($this->OPPrice3);
			$this->OPPrice4 = stripslashes($this->OPPrice4);
			$this->OPPrice5 = stripslashes($this->OPPrice5);
			$this->OPPrice6 = stripslashes($this->OPPrice6);
			$this->OPPrice7 = stripslashes($this->OPPrice7);
			$this->OPPrice8 = stripslashes($this->OPPrice8);
			$this->OPPrice9 = stripslashes($this->OPPrice9);
			$this->OPPrice10 = stripslashes($this->OPPrice10);
			$this->OPCategory1 = stripslashes($this->OPCategory1);
			$this->OPCategory2 = stripslashes($this->OPCategory2);
			$this->OPCategory3 = stripslashes($this->OPCategory3);
			$this->OPCategory4 = stripslashes($this->OPCategory4);
			$this->OPCategory5 = stripslashes($this->OPCategory5);
			$this->OPCategory6 = stripslashes($this->OPCategory6);
			$this->OPCategory7 = stripslashes($this->OPCategory7);
			$this->OPCategory8 = stripslashes($this->OPCategory8);
			$this->OPCategory9 = stripslashes($this->OPCategory9);
			$this->OPCategory10 = stripslashes($this->OPCategory10);

			$this->KokiPanelKataban = stripslashes($this->KokiPanelKataban);

			$this->OPNotes = stripslashes($this->OPNotes);
			$this->Shiharai = stripslashes($this->Shiharai);
			$this->Hansu = stripslashes($this->Hansu);
			$this->ConstTime = stripslashes($this->ConstTime);
			$this->KyukoDate = stripslashes($this->KyukoDate);
			$this->Kaijyo = stripslashes($this->Kaijyo);
			$this->Isetu = stripslashes($this->Isetu);
			$this->PhotoTekyoDate = stripslashes($this->PhotoTekyoDate);
			$this->PhotoPattern = stripslashes($this->PhotoPattern);
			$this->PhotoSenyu1 = stripslashes($this->PhotoSenyu1);
			$this->PSScene1 = stripslashes($this->PSScene1);
			$this->PhotoSenyu2 = stripslashes($this->PhotoSenyu2);
			$this->PSScene2 = stripslashes($this->PSScene2);
			$this->PhotoSenyu3 = stripslashes($this->PhotoSenyu3);
			$this->PSScene3 = stripslashes($this->PSScene3);
			$this->PhotoSenyu4 = stripslashes($this->PhotoSenyu4);
			$this->PSScene4 = stripslashes($this->PSScene4);
			$this->PhotoSenyu5 = stripslashes($this->PhotoSenyu5);
			$this->PSScene5 = stripslashes($this->PSScene5);
			$this->PhotoSenyu6 = stripslashes($this->PhotoSenyu6);
			$this->PSScene6 = stripslashes($this->PSScene6);
			$this->PhotoKyoyo1 = stripslashes($this->PhotoKyoyo1);
			$this->PKScene1 = stripslashes($this->PKScene1);
			$this->PhotoKyoyo2 = stripslashes($this->PhotoKyoyo2);
			$this->PKScene2 = stripslashes($this->PKScene2);
			$this->PhotoKyoyo3 = stripslashes($this->PhotoKyoyo3);
			$this->PKScene3 = stripslashes($this->PKScene3);
			$this->PhotoKyoyo4 = stripslashes($this->PhotoKyoyo4);
			$this->PKScene4 = stripslashes($this->PKScene4);
			$this->PhotoKyoyo5 = stripslashes($this->PhotoKyoyo5);
			$this->PKScene5 = stripslashes($this->PKScene5);
			$this->PhotoKyoyo6 = stripslashes($this->PhotoKyoyo6);
			$this->PKScene6 = stripslashes($this->PKScene6);
			$this->PhotoKyoyo7 = stripslashes($this->PhotoKyoyo7);
			$this->PKScene7 = stripslashes($this->PKScene7);
			$this->PhotoKyoyo8 = stripslashes($this->PhotoKyoyo8);
			$this->PKScene8 = stripslashes($this->PKScene8);
			$this->PhotoKyoyo9 = stripslashes($this->PhotoKyoyo9);
			$this->PKScene9 = stripslashes($this->PKScene9);
			$this->PhotoKyoyoNotes = stripslashes($this->PhotoKyoyoNotes);
			$this->Notes = stripslashes($this->Notes);

			$this->RenewalType = stripslashes($this->RenewalType);
			$this->HaisenType = stripslashes($this->HaisenType);
			$this->KanrishituCall = stripslashes($this->KanrishituCall);
			$this->PicKSu = stripslashes($this->PicKSu);
			$this->PicKType = stripslashes($this->PicKType);
			$this->PicSu = stripslashes($this->PicSu);
			$this->PicType = stripslashes($this->PicType);
			$this->Shobo = stripslashes($this->Shobo);
			$this->TeishutuDoc = stripslashes($this->TeishutuDoc);
			$this->SonotaShiji = stripslashes($this->SonotaShiji);

			$this->KyoyuStartDate = stripslashes($this->KyoyuStartDate);
			$this->SenyuEndDate = stripslashes($this->SenyuEndDate);
			$this->BukkenCD489 = stripslashes($this->BukkenCD489);
			$this->IraiUpdated = stripslashes($this->IraiUpdated);
			$this->KyoyoEndDate = stripslashes($this->KyoyoEndDate);
			$this->SenyuStartDate = stripslashes($this->SenyuStartDate);
			$this->Otherwise6 = stripslashes($this->Otherwise6);
			$this->DateS6 = stripslashes($this->DateS6);
			$this->DateE6 = stripslashes($this->DateE6);
			$this->Otherwise7 = stripslashes($this->Otherwise7);
			$this->DateS7 = stripslashes($this->DateS7);
			$this->DateE7 = stripslashes($this->DateE7);
			$this->Otherwise8 = stripslashes($this->Otherwise8);
			$this->DateS8 = stripslashes($this->DateS8);
			$this->DateE8 = stripslashes($this->DateE8);
			$this->Otherwise9 = stripslashes($this->Otherwise9);
			$this->DateS9 = stripslashes($this->DateS9);
			$this->DateE9 = stripslashes($this->DateE9);
			$this->Otherwise10 = stripslashes($this->Otherwise10);
			$this->DateS10 = stripslashes($this->DateS10);
			$this->DateE10 = stripslashes($this->DateE10);

			$this->IR01 = stripslashes($this->IR01);
			$this->IR02 = stripslashes($this->IR02);
			$this->IR03 = stripslashes($this->IR03);
			$this->IR04 = stripslashes($this->IR04);
			$this->IR05 = stripslashes($this->IR05);
			$this->IR06 = stripslashes($this->IR06);
			$this->IR07 = stripslashes($this->IR07);
			$this->OpEndDate = stripslashes($this->OpEndDate);
			$this->KeijiBasho = stripslashes($this->KeijiBasho);
			$this->SmartFlg = stripslashes($this->SmartFlg);
			$this->TantoCD3 = stripslashes($this->TantoCD3);
			$this->TantoCD4 = stripslashes($this->TantoCD4);
			$this->TantoMemo = stripslashes($this->TantoMemo);
			$this->GyosyaTantoCD3 = stripslashes($this->GyosyaTantoCD3);
			$this->GyosyaTantoCD4 = stripslashes($this->GyosyaTantoCD4);
			$this->GyosyaTantoCD5 = stripslashes($this->GyosyaTantoCD5);
			$this->GyosyaTantoMemo = stripslashes($this->GyosyaTantoMemo);



			$this->MukouFlg = $this->convertFlgToBoolean($this->MukouFlg);
		}
	}

	/* アップデート */
	function executeUpdate()
	{
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

		if ($this->IraiRenkeiCD < 0) {
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
					$sqlMoji = "select IraiRenkeiCD from $this->TableName order by IraiRenkeiCD desc limit 1";
					$maxCD = $this->myDB->getOneValue($sqlMoji);
					$maxCD = ($maxCD == -1) ? 0 : $maxCD;
					$maxCD++;

					// step 4. インサート
					$sqlMoji = "Insert into $this->TableName (";
					$sqlMoji .= "IraiRenkeiCD";

					if ($this->IraiRenkeiStatus != "") $sqlMoji .= ", IraiRenkeiStatus";
					if ($this->BukkenCD != "") $sqlMoji .= ", BukkenCD";
					if ($this->IraiDate != "") $sqlMoji .= ", IraiDate";
					if ($this->SekoShutai != "") $sqlMoji .= ", SekoShutai";
					if ($this->Answer != "") $sqlMoji .= ", Answer";
					if ($this->Answer489 != "") $sqlMoji .= ", Answer489";
					if ($this->WEBRecept != "") $sqlMoji .= ", WEBRecept";
					if ($this->YoshikiYotei != "") $sqlMoji .= ", YoshikiYotei";
					if ($this->YoshikiKakutei != "") $sqlMoji .= ", YoshikiKakutei";
					if ($this->OPUketuke != "") $sqlMoji .= ", OPUketuke";
					if ($this->KakuteiCompanyName != "") $sqlMoji .= ", KakuteiCompanyName";
					if ($this->PicStatus != "") $sqlMoji .= ", PicStatus";
					if ($this->PicAppSekoName != "") $sqlMoji .= ", PicAppSekoName";
					if ($this->PicAppKojiName != "") $sqlMoji .= ", PicAppKojiName";
					if ($this->PostingFlg != "") $sqlMoji .= ", PostingFlg";
					if ($this->PostingName != "") $sqlMoji .= ", PostingName";
					if ($this->PostingBasho != "") $sqlMoji .= ", PostingBasho";
					if ($this->PostType != "") $sqlMoji .= ", PostType";
					if ($this->WanshoFlg != "") $sqlMoji .= ", WanshoFlg";
					if ($this->PostingBiko != "") $sqlMoji .= ", PostingBiko";

					if ($this->KakuninFlg != "") $sqlMoji .= ", KakuninFlg";
					if ($this->KakuninTitle != "") $sqlMoji .= ", KakuninTitle";
					if ($this->KakuninFooter != "") $sqlMoji .= ", KakuninFooter";
					if ($this->YoteTekyoDate != "") $sqlMoji .= ", YoteTekyoDate";
					if ($this->YoteDate != "") $sqlMoji .= ", YoteDate";
					if ($this->YoyakuEnd != "") $sqlMoji .= ", YoyakuEnd";
					if ($this->TekyoDate != "") $sqlMoji .= ", TekyoDate";
					if ($this->KeteiDate != "") $sqlMoji .= ", KeteiDate";
					if ($this->TantoCD1 != "") $sqlMoji .= ", TantoCD1";
					if ($this->TantoCD2 != "") $sqlMoji .= ", TantoCD2";
					if ($this->GyosyaTantoCD1 != "") $sqlMoji .= ", GyosyaTantoCD1";
					if ($this->GyosyaTantoCD2 != "") $sqlMoji .= ", GyosyaTantoCD2";
					if ($this->WakuRange != "") $sqlMoji .= ", WakuRange";
					if ($this->TimeAStart != "") $sqlMoji .= ", TimeAStart";
					if ($this->TimeBStart != "") $sqlMoji .= ", TimeBStart";
					if ($this->TimeCStart != "") $sqlMoji .= ", TimeCStart";
					if ($this->TimeAEnd != "") $sqlMoji .= ", TimeAEnd";
					if ($this->TimeBEnd != "") $sqlMoji .= ", TimeBEnd";
					if ($this->TimeCEnd != "") $sqlMoji .= ", TimeCEnd";

					if ($this->TimeASu != "") $sqlMoji .= ", TimeASu";
					if ($this->TimeBSu != "") $sqlMoji .= ", TimeBSu";
					if ($this->TimeCSu != "") $sqlMoji .= ", TimeCSu";
					if ($this->OyaKataban != "") $sqlMoji .= ", OyaKataban";
					if ($this->KokiKataban != "") $sqlMoji .= ", KokiKataban";
					if ($this->OyaDeviceCD != "") $sqlMoji .= ", OyaDeviceCD";
					if ($this->KokiDeviceCD != "") $sqlMoji .= ", KokiDeviceCD";
					if ($this->KokiPanelKataban != "") $sqlMoji .= ", KokiPanelKataban";
					if ($this->OP1Name != "") $sqlMoji .= ", OP1Name";
					if ($this->OP2Name != "") $sqlMoji .= ", OP2Name";
					if ($this->OP3Name != "") $sqlMoji .= ", OP3Name";
					if ($this->OP4Name != "") $sqlMoji .= ", OP4Name";
					if ($this->OP5Name != "") $sqlMoji .= ", OP5Name";
					if ($this->OP6Name != "") $sqlMoji .= ", OP6Name";
					if ($this->OP7Name != "") $sqlMoji .= ", OP7Name";
					if ($this->OP8Name != "") $sqlMoji .= ", OP8Name";
					if ($this->OP9Name != "") $sqlMoji .= ", OP9Name";
					if ($this->OP10Name != "") $sqlMoji .= ", OP10Name";
					if ($this->OPPrice1 != "") $sqlMoji .= ", OPPrice1";
					if ($this->OPPrice2 != "") $sqlMoji .= ", OPPrice2";
					if ($this->OPPrice3 != "") $sqlMoji .= ", OPPrice3";
					if ($this->OPPrice4 != "") $sqlMoji .= ", OPPrice4";
					if ($this->OPPrice5 != "") $sqlMoji .= ", OPPrice5";
					if ($this->OPPrice6 != "") $sqlMoji .= ", OPPrice6";
					if ($this->OPPrice7 != "") $sqlMoji .= ", OPPrice7";
					if ($this->OPPrice8 != "") $sqlMoji .= ", OPPrice8";
					if ($this->OPPrice9 != "") $sqlMoji .= ", OPPrice9";
					if ($this->OPPrice10 != "") $sqlMoji .= ", OPPrice10";
					if ($this->OPCategory1 != "") $sqlMoji .= ", OPCategory1";
					if ($this->OPCategory2 != "") $sqlMoji .= ", OPCategory2";
					if ($this->OPCategory3 != "") $sqlMoji .= ", OPCategory3";
					if ($this->OPCategory4 != "") $sqlMoji .= ", OPCategory4";
					if ($this->OPCategory5 != "") $sqlMoji .= ", OPCategory5";
					if ($this->OPCategory6 != "") $sqlMoji .= ", OPCategory6";
					if ($this->OPCategory7 != "") $sqlMoji .= ", OPCategory7";
					if ($this->OPCategory8 != "") $sqlMoji .= ", OPCategory8";
					if ($this->OPCategory9 != "") $sqlMoji .= ", OPCategory9";
					if ($this->OPCategory10 != "") $sqlMoji .= ", OPCategory10";

					if ($this->OPNotes != "") $sqlMoji .= ", OPNotes";
					if ($this->Shiharai != "") $sqlMoji .= ", Shiharai";
					if ($this->Hansu != "") $sqlMoji .= ", Hansu";
					if ($this->ConstTime != "") $sqlMoji .= ", ConstTime";
					if ($this->KyukoDate != "") $sqlMoji .= ", KyukoDate";
					if ($this->Kaijyo != "") $sqlMoji .= ", Kaijyo";
					if ($this->Isetu != "") $sqlMoji .= ", Isetu";
					if ($this->PhotoTekyoDate != "") $sqlMoji .= ", PhotoTekyoDate";
					if ($this->PhotoPattern != "") $sqlMoji .= ", PhotoPattern";
					if ($this->PhotoSenyu1 != "") $sqlMoji .= ", PhotoSenyu1";
					if ($this->PSScene1 != "") $sqlMoji .= ", PSScene1";
					if ($this->PhotoSenyu2 != "") $sqlMoji .= ", PhotoSenyu2";
					if ($this->PSScene2 != "") $sqlMoji .= ", PSScene2";
					if ($this->PhotoSenyu3 != "") $sqlMoji .= ", PhotoSenyu3";
					if ($this->PSScene3 != "") $sqlMoji .= ", PSScene3";
					if ($this->PhotoSenyu4 != "") $sqlMoji .= ", PhotoSenyu4";
					if ($this->PSScene4 != "") $sqlMoji .= ", PSScene4";
					if ($this->PhotoSenyu5 != "") $sqlMoji .= ", PhotoSenyu5";
					if ($this->PSScene5 != "") $sqlMoji .= ", PSScene5";
					if ($this->PhotoSenyu6 != "") $sqlMoji .= ", PhotoSenyu6";
					if ($this->PSScene6 != "") $sqlMoji .= ", PSScene6";
					if ($this->PhotoKyoyo1 != "") $sqlMoji .= ", PhotoKyoyo1";
					if ($this->PKScene1 != "") $sqlMoji .= ", PKScene1";
					if ($this->PhotoKyoyo2 != "") $sqlMoji .= ", PhotoKyoyo2";
					if ($this->PKScene2 != "") $sqlMoji .= ", PKScene2";
					if ($this->PhotoKyoyo3 != "") $sqlMoji .= ", PhotoKyoyo3";
					if ($this->PKScene3 != "") $sqlMoji .= ", PKScene3";
					if ($this->PhotoKyoyo4 != "") $sqlMoji .= ", PhotoKyoyo4";
					if ($this->PKScene4 != "") $sqlMoji .= ", PKScene4";
					if ($this->PhotoKyoyo5 != "") $sqlMoji .= ", PhotoKyoyo5";
					if ($this->PKScene5 != "") $sqlMoji .= ", PKScene5";
					if ($this->PhotoKyoyo6 != "") $sqlMoji .= ", PhotoKyoyo6";
					if ($this->PKScene6 != "") $sqlMoji .= ", PKScene6";
					if ($this->PhotoKyoyo7 != "") $sqlMoji .= ", PhotoKyoyo7";
					if ($this->PKScene7 != "") $sqlMoji .= ", PKScene7";
					if ($this->PhotoKyoyo8 != "") $sqlMoji .= ", PhotoKyoyo8";
					if ($this->PKScene8 != "") $sqlMoji .= ", PKScene8";
					if ($this->PhotoKyoyo9 != "") $sqlMoji .= ", PhotoKyoyo9";
					if ($this->PKScene9 != "") $sqlMoji .= ", PKScene9";
					if ($this->PhotoKyoyoNotes != "") $sqlMoji .= ", PhotoKyoyoNotes";
					if ($this->Notes != "") $sqlMoji .= ", Notes";

					if ($this->RenewalType != "") $sqlMoji .= ", RenewalType";
					if ($this->HaisenType != "") $sqlMoji .= ", HaisenType";
					if ($this->KanrishituCall != "") $sqlMoji .= ", KanrishituCall";
					if ($this->PicKSu != "") $sqlMoji .= ", PicKSu";
					if ($this->PicKType != "") $sqlMoji .= ", PicKType";
					if ($this->PicSu != "") $sqlMoji .= ", PicSu";
					if ($this->PicType != "") $sqlMoji .= ", PicType";
					if ($this->Shobo != "") $sqlMoji .= ", Shobo";
					if ($this->TeishutuDoc != "") $sqlMoji .= ", TeishutuDoc";
					if ($this->SonotaShiji != "") $sqlMoji .= ", SonotaShiji";

					if ($this->KyoyuStartDate != "") $sqlMoji .= ", KyoyuStartDate";
					if ($this->SenyuEndDate != "") $sqlMoji .= ", SenyuEndDate";
					if ($this->BukkenCD489 != "") $sqlMoji .= ", BukkenCD489";
					if ($this->IraiUpdated != "") $sqlMoji .= ", IraiUpdated";
					if ($this->KyoyoEndDate != "") $sqlMoji .= ", KyoyoEndDate";
					if ($this->SenyuStartDate != "") $sqlMoji .= ", SenyuStartDate";
					if ($this->Otherwise6 != "") $sqlMoji .= ", Otherwise6";
					if ($this->DateS6 != "") $sqlMoji .= ", DateS6";
					if ($this->DateE6 != "") $sqlMoji .= ", DateE6";
					if ($this->Otherwise7 != "") $sqlMoji .= ", Otherwise7";
					if ($this->DateS7 != "") $sqlMoji .= ", DateS7";
					if ($this->DateE7 != "") $sqlMoji .= ", DateE7";
					if ($this->Otherwise8 != "") $sqlMoji .= ", Otherwise8";
					if ($this->DateS8 != "") $sqlMoji .= ", DateS8";
					if ($this->DateE8 != "") $sqlMoji .= ", DateE8";
					if ($this->Otherwise9 != "") $sqlMoji .= ", Otherwise9";
					if ($this->DateS9 != "") $sqlMoji .= ", DateS9";
					if ($this->DateE9 != "") $sqlMoji .= ", DateE9";
					if ($this->Otherwise10 != "") $sqlMoji .= ", Otherwise10";
					if ($this->DateS10 != "") $sqlMoji .= ", DateS10";
					if ($this->DateE10 != "") $sqlMoji .= ", DateE10";

					if ($this->IR01 != "") $sqlMoji .= ", IR01";
					if ($this->IR02 != "") $sqlMoji .= ", IR02";
					if ($this->IR03 != "") $sqlMoji .= ", IR03";
					if ($this->IR04 != "") $sqlMoji .= ", IR04";
					if ($this->IR05 != "") $sqlMoji .= ", IR05";
					if ($this->IR06 != "") $sqlMoji .= ", IR06";
					if ($this->IR07 != "") $sqlMoji .= ", IR07";
					if ($this->OpEndDate != "") $sqlMoji .= ", OpEndDate";
					if ($this->KeijiBasho != "") $sqlMoji .= ", KeijiBasho";
					if ($this->SmartFlg != "") $sqlMoji .= ", SmartFlg";
					if ($this->TantoCD3 != "") $sqlMoji .= ", TantoCD3";
					if ($this->TantoCD4 != "") $sqlMoji .= ", TantoCD4";
					if ($this->TantoMemo != "") $sqlMoji .= ", TantoMemo";
					if ($this->GyosyaTantoCD3 != "") $sqlMoji .= ", GyosyaTantoCD3";
					if ($this->GyosyaTantoCD4 != "") $sqlMoji .= ", GyosyaTantoCD4";
					if ($this->GyosyaTantoCD5 != "") $sqlMoji .= ", GyosyaTantoCD5";
					if ($this->GyosyaTantoMemo != "") $sqlMoji .= ", GyosyaTantoMemo";




					if ($this->MukouFlg != "") $sqlMoji .= ", MukouFlg";
					$sqlMoji .= ", Created";
					if ($this->Creator != "") $sqlMoji .= ", Creator";
					$sqlMoji .= ", Updated";
					if ($this->Updater != "") $sqlMoji .= ", Updater";
					$sqlMoji .= ") values (";
					$sqlMoji .= "$maxCD";

					if ($this->IraiRenkeiStatus != "") $sqlMoji .= ", '" . addslashes($this->IraiRenkeiStatus) . "'";
					if ($this->BukkenCD != "") $sqlMoji .= ", '" . addslashes($this->BukkenCD) . "'";
					if ($this->IraiDate != "") $sqlMoji .= ", '" . addslashes($this->IraiDate) . "'";
					if ($this->SekoShutai != "") $sqlMoji .= ", '" . addslashes($this->SekoShutai) . "'";
					if ($this->Answer != "") $sqlMoji .= ", '" . addslashes($this->Answer) . "'";
					if ($this->Answer489 != "") $sqlMoji .= ", '" . addslashes($this->Answer489) . "'";
					if ($this->WEBRecept != "") $sqlMoji .= ", '" . addslashes($this->WEBRecept) . "'";
					if ($this->YoshikiYotei != "") $sqlMoji .= ", '" . addslashes($this->YoshikiYotei) . "'";
					if ($this->YoshikiKakutei != "") $sqlMoji .= ", '" . addslashes($this->YoshikiKakutei) . "'";
					if ($this->OPUketuke != "") $sqlMoji .= ", '" . addslashes($this->OPUketuke) . "'";
					if ($this->KakuteiCompanyName != "") $sqlMoji .= ", '" . addslashes($this->KakuteiCompanyName) . "'";
					if ($this->PicStatus != "") $sqlMoji .= ", '" . addslashes($this->PicStatus) . "'";
					if ($this->PicAppSekoName != "") $sqlMoji .= ", '" . addslashes($this->PicAppSekoName) . "'";
					if ($this->PicAppKojiName != "") $sqlMoji .= ", '" . addslashes($this->PicAppKojiName) . "'";
					if ($this->PostingFlg != "") $sqlMoji .= ", '" . addslashes($this->PostingFlg) . "'";
					if ($this->PostingName != "") $sqlMoji .= ", '" . addslashes($this->PostingName) . "'";
					if ($this->PostingBasho != "") $sqlMoji .= ", '" . addslashes($this->PostingBasho) . "'";
					if ($this->PostType != "") $sqlMoji .= ", '" . addslashes($this->PostType) . "'";
					if ($this->WanshoFlg != "") $sqlMoji .= ", '" . addslashes($this->WanshoFlg) . "'";
					if ($this->PostingBiko != "") $sqlMoji .= ", '" . addslashes($this->PostingBiko) . "'";

					if ($this->KakuninFlg != "") $sqlMoji .= ", '" . addslashes($this->KakuninFlg) . "'";
					if ($this->KakuninTitle != "") $sqlMoji .= ", '" . addslashes($this->KakuninTitle) . "'";
					if ($this->KakuninFooter != "") $sqlMoji .= ", '" . addslashes($this->KakuninFooter) . "'";
					if ($this->YoteTekyoDate != "") $sqlMoji .= ", '" . addslashes($this->YoteTekyoDate) . "'";
					if ($this->YoteDate != "") $sqlMoji .= ", '" . addslashes($this->YoteDate) . "'";
					if ($this->YoyakuEnd != "") $sqlMoji .= ", '" . addslashes($this->YoyakuEnd) . "'";
					if ($this->TekyoDate != "") $sqlMoji .= ", '" . addslashes($this->TekyoDate) . "'";
					if ($this->KeteiDate != "") $sqlMoji .= ", '" . addslashes($this->KeteiDate) . "'";
					if ($this->TantoCD1 != "") $sqlMoji .= ", '" . addslashes($this->TantoCD1) . "'";
					if ($this->TantoCD2 != "") $sqlMoji .= ", '" . addslashes($this->TantoCD2) . "'";
					if ($this->GyosyaTantoCD1 != "") $sqlMoji .= ", '" . addslashes($this->GyosyaTantoCD1) . "'";
					if ($this->GyosyaTantoCD2 != "") $sqlMoji .= ", '" . addslashes($this->GyosyaTantoCD2) . "'";
					if ($this->WakuRange != "") $sqlMoji .= ", '" . addslashes($this->WakuRange) . "'";
					if ($this->TimeAStart != "") $sqlMoji .= ", '" . addslashes($this->TimeAStart) . "'";
					if ($this->TimeBStart != "") $sqlMoji .= ", '" . addslashes($this->TimeBStart) . "'";
					if ($this->TimeCStart != "") $sqlMoji .= ", '" . addslashes($this->TimeCStart) . "'";
					if ($this->TimeAEnd != "") $sqlMoji .= ", '" . addslashes($this->TimeAEnd) . "'";
					if ($this->TimeBEnd != "") $sqlMoji .= ", '" . addslashes($this->TimeBEnd) . "'";
					if ($this->TimeCEnd != "") $sqlMoji .= ", '" . addslashes($this->TimeCEnd) . "'";

					if ($this->TimeASu != "") $sqlMoji .= ", '" . addslashes($this->TimeASu) . "'";
					if ($this->TimeBSu != "") $sqlMoji .= ", '" . addslashes($this->TimeBSu) . "'";
					if ($this->TimeCSu != "") $sqlMoji .= ", '" . addslashes($this->TimeCSu) . "'";
					if ($this->OyaKataban != "") $sqlMoji .= ", '" . addslashes($this->OyaKataban) . "'";
					if ($this->KokiKataban != "") $sqlMoji .= ", '" . addslashes($this->KokiKataban) . "'";
					if ($this->OyaDeviceCD != "") $sqlMoji .= ", '" . addslashes($this->OyaDeviceCD) . "'";
					if ($this->KokiDeviceCD != "") $sqlMoji .= ", '" . addslashes($this->KokiDeviceCD) . "'";
					if ($this->KokiPanelKataban != "") $sqlMoji .= ", '" . addslashes($this->KokiPanelKataban) . "'";

					if ($this->OP1Name != "") $sqlMoji .= ", '" . addslashes($this->OP1Name) . "'";
					if ($this->OP2Name != "") $sqlMoji .= ", '" . addslashes($this->OP2Name) . "'";
					if ($this->OP3Name != "") $sqlMoji .= ", '" . addslashes($this->OP3Name) . "'";
					if ($this->OP4Name != "") $sqlMoji .= ", '" . addslashes($this->OP4Name) . "'";
					if ($this->OP5Name != "") $sqlMoji .= ", '" . addslashes($this->OP5Name) . "'";
					if ($this->OP6Name != "") $sqlMoji .= ", '" . addslashes($this->OP6Name) . "'";
					if ($this->OP7Name != "") $sqlMoji .= ", '" . addslashes($this->OP7Name) . "'";
					if ($this->OP8Name != "") $sqlMoji .= ", '" . addslashes($this->OP8Name) . "'";
					if ($this->OP9Name != "") $sqlMoji .= ", '" . addslashes($this->OP9Name) . "'";
					if ($this->OP10Name != "") $sqlMoji .= ", '" . addslashes($this->OP10Name) . "'";
					if ($this->OPPrice1 != "") $sqlMoji .= ", '" . addslashes($this->OPPrice1) . "'";
					if ($this->OPPrice2 != "") $sqlMoji .= ", '" . addslashes($this->OPPrice2) . "'";
					if ($this->OPPrice3 != "") $sqlMoji .= ", '" . addslashes($this->OPPrice3) . "'";
					if ($this->OPPrice4 != "") $sqlMoji .= ", '" . addslashes($this->OPPrice4) . "'";
					if ($this->OPPrice5 != "") $sqlMoji .= ", '" . addslashes($this->OPPrice5) . "'";
					if ($this->OPPrice6 != "") $sqlMoji .= ", '" . addslashes($this->OPPrice6) . "'";
					if ($this->OPPrice7 != "") $sqlMoji .= ", '" . addslashes($this->OPPrice7) . "'";
					if ($this->OPPrice8 != "") $sqlMoji .= ", '" . addslashes($this->OPPrice8) . "'";
					if ($this->OPPrice9 != "") $sqlMoji .= ", '" . addslashes($this->OPPrice9) . "'";
					if ($this->OPPrice10 != "") $sqlMoji .= ", '" . addslashes($this->OPPrice10) . "'";
					if ($this->OPCategory1 != "") $sqlMoji .= ", '" . addslashes($this->OPCategory1) . "'";
					if ($this->OPCategory2 != "") $sqlMoji .= ", '" . addslashes($this->OPCategory2) . "'";
					if ($this->OPCategory3 != "") $sqlMoji .= ", '" . addslashes($this->OPCategory3) . "'";
					if ($this->OPCategory4 != "") $sqlMoji .= ", '" . addslashes($this->OPCategory4) . "'";
					if ($this->OPCategory5 != "") $sqlMoji .= ", '" . addslashes($this->OPCategory5) . "'";
					if ($this->OPCategory6 != "") $sqlMoji .= ", '" . addslashes($this->OPCategory6) . "'";
					if ($this->OPCategory7 != "") $sqlMoji .= ", '" . addslashes($this->OPCategory7) . "'";
					if ($this->OPCategory8 != "") $sqlMoji .= ", '" . addslashes($this->OPCategory8) . "'";
					if ($this->OPCategory9 != "") $sqlMoji .= ", '" . addslashes($this->OPCategory9) . "'";
					if ($this->OPCategory10 != "") $sqlMoji .= ", '" . addslashes($this->OPCategory10) . "'";

					if ($this->OPNotes != "") $sqlMoji .= ", '" . addslashes($this->OPNotes) . "'";
					if ($this->Shiharai != "") $sqlMoji .= ", '" . addslashes($this->Shiharai) . "'";
					if ($this->Hansu != "") $sqlMoji .= ", '" . addslashes($this->Hansu) . "'";
					if ($this->ConstTime != "") $sqlMoji .= ", '" . addslashes($this->ConstTime) . "'";
					if ($this->KyukoDate != "") $sqlMoji .= ", '" . addslashes($this->KyukoDate) . "'";
					if ($this->Kaijyo != "") $sqlMoji .= ", '" . addslashes($this->Kaijyo) . "'";
					if ($this->Isetu != "") $sqlMoji .= ", '" . addslashes($this->Isetu) . "'";
					if ($this->PhotoTekyoDate != "") $sqlMoji .= ", '" . addslashes($this->PhotoTekyoDate) . "'";
					if ($this->PhotoPattern != "") $sqlMoji .= ", '" . addslashes($this->PhotoPattern) . "'";
					if ($this->PhotoSenyu1 != "") $sqlMoji .= ", '" . addslashes($this->PhotoSenyu1) . "'";
					if ($this->PSScene1 != "") $sqlMoji .= ", '" . addslashes($this->PSScene1) . "'";
					if ($this->PhotoSenyu2 != "") $sqlMoji .= ", '" . addslashes($this->PhotoSenyu2) . "'";
					if ($this->PSScene2 != "") $sqlMoji .= ", '" . addslashes($this->PSScene2) . "'";
					if ($this->PhotoSenyu3 != "") $sqlMoji .= ", '" . addslashes($this->PhotoSenyu3) . "'";
					if ($this->PSScene3 != "") $sqlMoji .= ", '" . addslashes($this->PSScene3) . "'";
					if ($this->PhotoSenyu4 != "") $sqlMoji .= ", '" . addslashes($this->PhotoSenyu4) . "'";
					if ($this->PSScene4 != "") $sqlMoji .= ", '" . addslashes($this->PSScene4) . "'";
					if ($this->PhotoSenyu5 != "") $sqlMoji .= ", '" . addslashes($this->PhotoSenyu5) . "'";
					if ($this->PSScene5 != "") $sqlMoji .= ", '" . addslashes($this->PSScene5) . "'";
					if ($this->PhotoSenyu6 != "") $sqlMoji .= ", '" . addslashes($this->PhotoSenyu6) . "'";
					if ($this->PSScene6 != "") $sqlMoji .= ", '" . addslashes($this->PSScene6) . "'";
					if ($this->PhotoKyoyo1 != "") $sqlMoji .= ", '" . addslashes($this->PhotoKyoyo1) . "'";
					if ($this->PKScene1 != "") $sqlMoji .= ", '" . addslashes($this->PKScene1) . "'";
					if ($this->PhotoKyoyo2 != "") $sqlMoji .= ", '" . addslashes($this->PhotoKyoyo2) . "'";
					if ($this->PKScene2 != "") $sqlMoji .= ", '" . addslashes($this->PKScene2) . "'";
					if ($this->PhotoKyoyo3 != "") $sqlMoji .= ", '" . addslashes($this->PhotoKyoyo3) . "'";
					if ($this->PKScene3 != "") $sqlMoji .= ", '" . addslashes($this->PKScene3) . "'";
					if ($this->PhotoKyoyo4 != "") $sqlMoji .= ", '" . addslashes($this->PhotoKyoyo4) . "'";
					if ($this->PKScene4 != "") $sqlMoji .= ", '" . addslashes($this->PKScene4) . "'";
					if ($this->PhotoKyoyo5 != "") $sqlMoji .= ", '" . addslashes($this->PhotoKyoyo5) . "'";
					if ($this->PKScene5 != "") $sqlMoji .= ", '" . addslashes($this->PKScene5) . "'";
					if ($this->PhotoKyoyo6 != "") $sqlMoji .= ", '" . addslashes($this->PhotoKyoyo6) . "'";
					if ($this->PKScene6 != "") $sqlMoji .= ", '" . addslashes($this->PKScene6) . "'";
					if ($this->PhotoKyoyo7 != "") $sqlMoji .= ", '" . addslashes($this->PhotoKyoyo7) . "'";
					if ($this->PKScene7 != "") $sqlMoji .= ", '" . addslashes($this->PKScene7) . "'";
					if ($this->PhotoKyoyo8 != "") $sqlMoji .= ", '" . addslashes($this->PhotoKyoyo8) . "'";
					if ($this->PKScene8 != "") $sqlMoji .= ", '" . addslashes($this->PKScene8) . "'";
					if ($this->PhotoKyoyo9 != "") $sqlMoji .= ", '" . addslashes($this->PhotoKyoyo9) . "'";
					if ($this->PKScene9 != "") $sqlMoji .= ", '" . addslashes($this->PKScene9) . "'";
					if ($this->PhotoKyoyoNotes != "") $sqlMoji .= ", '" . addslashes($this->PhotoKyoyoNotes) . "'";
					if ($this->Notes != "") $sqlMoji .= ", '" . addslashes($this->Notes) . "'";

					if ($this->RenewalType != "") $sqlMoji .= ", '" . addslashes($this->RenewalType) . "'";
					if ($this->HaisenType != "") $sqlMoji .= ", '" . addslashes($this->HaisenType) . "'";
					if ($this->KanrishituCall != "") $sqlMoji .= ", '" . addslashes($this->KanrishituCall) . "'";
					if ($this->PicKSu != "") $sqlMoji .= ", '" . addslashes($this->PicKSu) . "'";
					if ($this->PicKType != "") $sqlMoji .= ", '" . addslashes($this->PicKType) . "'";
					if ($this->PicSu != "") $sqlMoji .= ", '" . addslashes($this->PicSu) . "'";
					if ($this->PicType != "") $sqlMoji .= ", '" . addslashes($this->PicType) . "'";
					if ($this->Shobo != "") $sqlMoji .= ", '" . addslashes($this->Shobo) . "'";
					if ($this->TeishutuDoc != "") $sqlMoji .= ", '" . addslashes($this->TeishutuDoc) . "'";
					if ($this->SonotaShiji != "") $sqlMoji .= ", '" . addslashes($this->SonotaShiji) . "'";

					if ($this->KyoyuStartDate != "") $sqlMoji .= ", '" . addslashes($this->KyoyuStartDate) . "'";
					if ($this->SenyuEndDate != "") $sqlMoji .= ", '" . addslashes($this->SenyuEndDate) . "'";
					if ($this->BukkenCD489 != "") $sqlMoji .= ", '" . addslashes($this->BukkenCD489) . "'";
					if ($this->IraiUpdated != "") $sqlMoji .= ", '" . addslashes($this->IraiUpdated) . "'";
					if ($this->KyoyoEndDate != "") $sqlMoji .= ", '" . addslashes($this->KyoyoEndDate) . "'";
					if ($this->SenyuStartDate != "") $sqlMoji .= ", '" . addslashes($this->SenyuStartDate) . "'";
					if ($this->Otherwise6 != "") $sqlMoji .= ", '" . addslashes($this->Otherwise6) . "'";
					if ($this->DateS6 != "") $sqlMoji .= ", '" . addslashes($this->DateS6) . "'";
					if ($this->DateE6 != "") $sqlMoji .= ", '" . addslashes($this->DateE6) . "'";
					if ($this->Otherwise7 != "") $sqlMoji .= ", '" . addslashes($this->Otherwise7) . "'";
					if ($this->DateS7 != "") $sqlMoji .= ", '" . addslashes($this->DateS7) . "'";
					if ($this->DateE7 != "") $sqlMoji .= ", '" . addslashes($this->DateE7) . "'";
					if ($this->Otherwise8 != "") $sqlMoji .= ", '" . addslashes($this->Otherwise8) . "'";
					if ($this->DateS8 != "") $sqlMoji .= ", '" . addslashes($this->DateS8) . "'";
					if ($this->DateE8 != "") $sqlMoji .= ", '" . addslashes($this->DateE8) . "'";
					if ($this->Otherwise9 != "") $sqlMoji .= ", '" . addslashes($this->Otherwise9) . "'";
					if ($this->DateS9 != "") $sqlMoji .= ", '" . addslashes($this->DateS9) . "'";
					if ($this->DateE9 != "") $sqlMoji .= ", '" . addslashes($this->DateE9) . "'";
					if ($this->Otherwise10 != "") $sqlMoji .= ", '" . addslashes($this->Otherwise10) . "'";
					if ($this->DateS10 != "") $sqlMoji .= ", '" . addslashes($this->DateS10) . "'";
					if ($this->DateE10 != "") $sqlMoji .= ", '" . addslashes($this->DateE10) . "'";

					if ($this->IR01 != "") $sqlMoji .= ", '" . addslashes($this->IR01) . "'";
					if ($this->IR02 != "") $sqlMoji .= ", '" . addslashes($this->IR02) . "'";
					if ($this->IR03 != "") $sqlMoji .= ", '" . addslashes($this->IR03) . "'";
					if ($this->IR04 != "") $sqlMoji .= ", '" . addslashes($this->IR04) . "'";
					if ($this->IR05 != "") $sqlMoji .= ", '" . addslashes($this->IR05) . "'";
					if ($this->IR06 != "") $sqlMoji .= ", '" . addslashes($this->IR06) . "'";
					if ($this->IR07 != "") $sqlMoji .= ", '" . addslashes($this->IR07) . "'";
					if ($this->OpEndDate != "") $sqlMoji .= ", '" . addslashes($this->OpEndDate) . "'";
					if ($this->KeijiBasho != "") $sqlMoji .= ", '" . addslashes($this->KeijiBasho) . "'";
					if ($this->SmartFlg != "") $sqlMoji .= ", '" . addslashes($this->SmartFlg) . "'";
					if ($this->TantoCD3 != "") $sqlMoji .= ", '" . addslashes($this->TantoCD3) . "'";
					if ($this->TantoCD4 != "") $sqlMoji .= ", '" . addslashes($this->TantoCD4) . "'";
					if ($this->TantoMemo != "") $sqlMoji .= ", '" . addslashes($this->TantoMemo) . "'";
					if ($this->GyosyaTantoCD3 != "") $sqlMoji .= ", '" . addslashes($this->GyosyaTantoCD3) . "'";
					if ($this->GyosyaTantoCD4 != "") $sqlMoji .= ", '" . addslashes($this->GyosyaTantoCD4) . "'";
					if ($this->GyosyaTantoCD5 != "") $sqlMoji .= ", '" . addslashes($this->GyosyaTantoCD5) . "'";
					if ($this->GyosyaTantoMemo != "") $sqlMoji .= ", '" . addslashes($this->GyosyaTantoMemo) . "'";


					if ($this->MukouFlg != "") $sqlMoji .= ", " . $this->convertFlgToString($this->MukouFlg);
					$sqlMoji .= ", NOW()";
					if ($this->Creator != "") $sqlMoji .= ", $this->Creator";
					$sqlMoji .= ", NOW()";
					if ($this->Updater != "") $sqlMoji .= ", $this->Updater";
					$sqlMoji .= ")";

					$rtn = $this->myDB->executeQuery($sqlMoji);
					if (!$rtn) {
						$this->Err = "追加処理に失敗しました";
						$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
						unset($myLog);
						return FALSE;
					} else {
						// step 5. コミット
						if (!($this->myDB->executeQuery("COMMIT"))) {
							$this->Err = "追加処理に失敗しました(COMMIT)";
							$this->myDB->executeQuery("ROLLBACK");
							return FALSE;
						} else {
							$this->IraiRenkeiCD = $maxCD;
						}
					}
				}
			}
		} else {
			// アップデート
			$sqlMoji = "Update $this->TableName set ";
			$sqlMoji .= "IraiRenkeiCD = $this->IraiRenkeiCD";

			$sqlMoji .= ", IraiRenkeiStatus = " . $this->convertNullStringValue($this->IraiRenkeiStatus);
			$sqlMoji .= ", BukkenCD = " . $this->convertNullStringValue($this->BukkenCD);
			$sqlMoji .= ", IraiDate = " . $this->convertNullStringValue($this->IraiDate);
			$sqlMoji .= ", SekoShutai = " . $this->convertNullStringValue($this->SekoShutai);
			$sqlMoji .= ", Answer = " . $this->convertNullStringValue($this->Answer);
			$sqlMoji .= ", Answer489 = " . $this->convertNullStringValue($this->Answer489);
			$sqlMoji .= ", WEBRecept = " . $this->convertNullStringValue($this->WEBRecept);
			$sqlMoji .= ", YoshikiYotei = " . $this->convertNullStringValue($this->YoshikiYotei);
			$sqlMoji .= ", YoshikiKakutei = " . $this->convertNullStringValue($this->YoshikiKakutei);
			$sqlMoji .= ", OPUketuke = " . $this->convertNullStringValue($this->OPUketuke);
			$sqlMoji .= ", KakuteiCompanyName = " . $this->convertNullStringValue($this->KakuteiCompanyName);
			$sqlMoji .= ", PicStatus = " . $this->convertNullStringValue($this->PicStatus);
			$sqlMoji .= ", PicAppSekoName = " . $this->convertNullStringValue($this->PicAppSekoName);
			$sqlMoji .= ", PicAppKojiName = " . $this->convertNullStringValue($this->PicAppKojiName);
			$sqlMoji .= ", PostingFlg = " . $this->convertNullStringValue($this->PostingFlg);
			$sqlMoji .= ", PostingName = " . $this->convertNullStringValue($this->PostingName);
			$sqlMoji .= ", PostingBasho = " . $this->convertNullStringValue($this->PostingBasho);
			$sqlMoji .= ", PostType = " . $this->convertNullStringValue($this->PostType);
			$sqlMoji .= ", WanshoFlg = " . $this->convertNullStringValue($this->WanshoFlg);
			$sqlMoji .= ", PostingBiko = " . $this->convertNullStringValue($this->PostingBiko);

			$sqlMoji .= ", KakuninFlg = " . $this->convertNullStringValue($this->KakuninFlg);
			$sqlMoji .= ", KakuninTitle = " . $this->convertNullStringValue($this->KakuninTitle);
			$sqlMoji .= ", KakuninFooter = " . $this->convertNullStringValue($this->KakuninFooter);
			$sqlMoji .= ", YoteTekyoDate = " . $this->convertNullStringValue($this->YoteTekyoDate);
			$sqlMoji .= ", YoteDate = " . $this->convertNullStringValue($this->YoteDate);
			$sqlMoji .= ", YoyakuEnd = " . $this->convertNullStringValue($this->YoyakuEnd);
			$sqlMoji .= ", TekyoDate = " . $this->convertNullStringValue($this->TekyoDate);
			$sqlMoji .= ", KeteiDate = " . $this->convertNullStringValue($this->KeteiDate);
			$sqlMoji .= ", TantoCD1 = " . $this->convertNullStringValue($this->TantoCD1);
			$sqlMoji .= ", TantoCD2 = " . $this->convertNullStringValue($this->TantoCD2);
			$sqlMoji .= ", GyosyaTantoCD1 = " . $this->convertNullStringValue($this->GyosyaTantoCD1);
			$sqlMoji .= ", GyosyaTantoCD2 = " . $this->convertNullStringValue($this->GyosyaTantoCD2);
			$sqlMoji .= ", WakuRange = " . $this->convertNullStringValue($this->WakuRange);
			$sqlMoji .= ", TimeAStart = " . $this->convertNullStringValue($this->TimeAStart);
			$sqlMoji .= ", TimeBStart = " . $this->convertNullStringValue($this->TimeBStart);
			$sqlMoji .= ", TimeCStart = " . $this->convertNullStringValue($this->TimeCStart);
			$sqlMoji .= ", TimeAEnd = " . $this->convertNullStringValue($this->TimeAEnd);
			$sqlMoji .= ", TimeBEnd = " . $this->convertNullStringValue($this->TimeBEnd);
			$sqlMoji .= ", TimeCEnd = " . $this->convertNullStringValue($this->TimeCEnd);

			$sqlMoji .= ", TimeASu = " . $this->convertNullStringValue($this->TimeASu);
			$sqlMoji .= ", TimeBSu = " . $this->convertNullStringValue($this->TimeBSu);
			$sqlMoji .= ", TimeCSu = " . $this->convertNullStringValue($this->TimeCSu);
			$sqlMoji .= ", OyaKataban = " . $this->convertNullStringValue($this->OyaKataban);
			$sqlMoji .= ", KokiKataban = " . $this->convertNullStringValue($this->KokiKataban);
			$sqlMoji .= ", OyaDeviceCD = " . $this->convertNullStringValue($this->OyaDeviceCD);
			$sqlMoji .= ", KokiDeviceCD = " . $this->convertNullStringValue($this->KokiDeviceCD);
			$sqlMoji .= ", KokiPanelKataban = " . $this->convertNullStringValue($this->KokiPanelKataban);

			$sqlMoji .= ", OP1Name = " . $this->convertNullStringValue($this->OP1Name);
			$sqlMoji .= ", OP2Name = " . $this->convertNullStringValue($this->OP2Name);
			$sqlMoji .= ", OP3Name = " . $this->convertNullStringValue($this->OP3Name);
			$sqlMoji .= ", OP4Name = " . $this->convertNullStringValue($this->OP4Name);
			$sqlMoji .= ", OP5Name = " . $this->convertNullStringValue($this->OP5Name);
			$sqlMoji .= ", OP6Name = " . $this->convertNullStringValue($this->OP6Name);
			$sqlMoji .= ", OP7Name = " . $this->convertNullStringValue($this->OP7Name);
			$sqlMoji .= ", OP8Name = " . $this->convertNullStringValue($this->OP8Name);
			$sqlMoji .= ", OP9Name = " . $this->convertNullStringValue($this->OP9Name);
			$sqlMoji .= ", OP10Name = " . $this->convertNullStringValue($this->OP10Name);
			$sqlMoji .= ", OPPrice1 = " . $this->convertNullStringValue($this->OPPrice1);
			$sqlMoji .= ", OPPrice2 = " . $this->convertNullStringValue($this->OPPrice2);
			$sqlMoji .= ", OPPrice3 = " . $this->convertNullStringValue($this->OPPrice3);
			$sqlMoji .= ", OPPrice4 = " . $this->convertNullStringValue($this->OPPrice4);
			$sqlMoji .= ", OPPrice5 = " . $this->convertNullStringValue($this->OPPrice5);
			$sqlMoji .= ", OPPrice6 = " . $this->convertNullStringValue($this->OPPrice6);
			$sqlMoji .= ", OPPrice7 = " . $this->convertNullStringValue($this->OPPrice7);
			$sqlMoji .= ", OPPrice8 = " . $this->convertNullStringValue($this->OPPrice8);
			$sqlMoji .= ", OPPrice9 = " . $this->convertNullStringValue($this->OPPrice9);
			$sqlMoji .= ", OPPrice10 = " . $this->convertNullStringValue($this->OPPrice10);
			$sqlMoji .= ", OPCategory1 = " . $this->convertNullStringValue($this->OPCategory1);
			$sqlMoji .= ", OPCategory2 = " . $this->convertNullStringValue($this->OPCategory2);
			$sqlMoji .= ", OPCategory3 = " . $this->convertNullStringValue($this->OPCategory3);
			$sqlMoji .= ", OPCategory4 = " . $this->convertNullStringValue($this->OPCategory4);
			$sqlMoji .= ", OPCategory5 = " . $this->convertNullStringValue($this->OPCategory5);
			$sqlMoji .= ", OPCategory6 = " . $this->convertNullStringValue($this->OPCategory6);
			$sqlMoji .= ", OPCategory7 = " . $this->convertNullStringValue($this->OPCategory7);
			$sqlMoji .= ", OPCategory8 = " . $this->convertNullStringValue($this->OPCategory8);
			$sqlMoji .= ", OPCategory9 = " . $this->convertNullStringValue($this->OPCategory9);
			$sqlMoji .= ", OPCategory10 = " . $this->convertNullStringValue($this->OPCategory10);

			$sqlMoji .= ", OPNotes = " . $this->convertNullStringValue($this->OPNotes);
			$sqlMoji .= ", Shiharai = " . $this->convertNullStringValue($this->Shiharai);
			$sqlMoji .= ", Hansu = " . $this->convertNullStringValue($this->Hansu);
			$sqlMoji .= ", ConstTime = " . $this->convertNullStringValue($this->ConstTime);
			$sqlMoji .= ", KyukoDate = " . $this->convertNullStringValue($this->KyukoDate);
			$sqlMoji .= ", Kaijyo = " . $this->convertNullStringValue($this->Kaijyo);
			$sqlMoji .= ", Isetu = " . $this->convertNullStringValue($this->Isetu);
			$sqlMoji .= ", PhotoTekyoDate = " . $this->convertNullStringValue($this->PhotoTekyoDate);
			$sqlMoji .= ", PhotoPattern = " . $this->convertNullStringValue($this->PhotoPattern);
			$sqlMoji .= ", PhotoSenyu1 = " . $this->convertNullStringValue($this->PhotoSenyu1);
			$sqlMoji .= ", PSScene1 = " . $this->convertNullStringValue($this->PSScene1);
			$sqlMoji .= ", PhotoSenyu2 = " . $this->convertNullStringValue($this->PhotoSenyu2);
			$sqlMoji .= ", PSScene2 = " . $this->convertNullStringValue($this->PSScene2);
			$sqlMoji .= ", PhotoSenyu3 = " . $this->convertNullStringValue($this->PhotoSenyu3);
			$sqlMoji .= ", PSScene3 = " . $this->convertNullStringValue($this->PSScene3);
			$sqlMoji .= ", PhotoSenyu4 = " . $this->convertNullStringValue($this->PhotoSenyu4);
			$sqlMoji .= ", PSScene4 = " . $this->convertNullStringValue($this->PSScene4);
			$sqlMoji .= ", PhotoSenyu5 = " . $this->convertNullStringValue($this->PhotoSenyu5);
			$sqlMoji .= ", PSScene5 = " . $this->convertNullStringValue($this->PSScene5);
			$sqlMoji .= ", PhotoSenyu6 = " . $this->convertNullStringValue($this->PhotoSenyu6);
			$sqlMoji .= ", PSScene6 = " . $this->convertNullStringValue($this->PSScene6);
			$sqlMoji .= ", PhotoKyoyo1 = " . $this->convertNullStringValue($this->PhotoKyoyo1);
			$sqlMoji .= ", PKScene1 = " . $this->convertNullStringValue($this->PKScene1);
			$sqlMoji .= ", PhotoKyoyo2 = " . $this->convertNullStringValue($this->PhotoKyoyo2);
			$sqlMoji .= ", PKScene2 = " . $this->convertNullStringValue($this->PKScene2);
			$sqlMoji .= ", PhotoKyoyo3 = " . $this->convertNullStringValue($this->PhotoKyoyo3);
			$sqlMoji .= ", PKScene3 = " . $this->convertNullStringValue($this->PKScene3);
			$sqlMoji .= ", PhotoKyoyo4 = " . $this->convertNullStringValue($this->PhotoKyoyo4);
			$sqlMoji .= ", PKScene4 = " . $this->convertNullStringValue($this->PKScene4);
			$sqlMoji .= ", PhotoKyoyo5 = " . $this->convertNullStringValue($this->PhotoKyoyo5);
			$sqlMoji .= ", PKScene5 = " . $this->convertNullStringValue($this->PKScene5);
			$sqlMoji .= ", PhotoKyoyo6 = " . $this->convertNullStringValue($this->PhotoKyoyo6);
			$sqlMoji .= ", PKScene6 = " . $this->convertNullStringValue($this->PKScene6);
			$sqlMoji .= ", PhotoKyoyo7 = " . $this->convertNullStringValue($this->PhotoKyoyo7);
			$sqlMoji .= ", PKScene7 = " . $this->convertNullStringValue($this->PKScene7);
			$sqlMoji .= ", PhotoKyoyo8 = " . $this->convertNullStringValue($this->PhotoKyoyo8);
			$sqlMoji .= ", PKScene8 = " . $this->convertNullStringValue($this->PKScene8);
			$sqlMoji .= ", PhotoKyoyo9 = " . $this->convertNullStringValue($this->PhotoKyoyo9);
			$sqlMoji .= ", PKScene9 = " . $this->convertNullStringValue($this->PKScene9);
			$sqlMoji .= ", PhotoKyoyoNotes = " . $this->convertNullStringValue($this->PhotoKyoyoNotes);
			$sqlMoji .= ", Notes = " . $this->convertNullStringValue($this->Notes);

			$sqlMoji .= ", RenewalType = " . $this->convertNullStringValue($this->RenewalType);
			$sqlMoji .= ", HaisenType = " . $this->convertNullStringValue($this->HaisenType);
			$sqlMoji .= ", KanrishituCall = " . $this->convertNullStringValue($this->KanrishituCall);
			$sqlMoji .= ", PicKSu = " . $this->convertNullStringValue($this->PicKSu);
			$sqlMoji .= ", PicKType = " . $this->convertNullStringValue($this->PicKType);
			$sqlMoji .= ", PicSu = " . $this->convertNullStringValue($this->PicSu);
			$sqlMoji .= ", PicType = " . $this->convertNullStringValue($this->PicType);
			$sqlMoji .= ", Shobo = " . $this->convertNullStringValue($this->Shobo);
			$sqlMoji .= ", TeishutuDoc = " . $this->convertNullStringValue($this->TeishutuDoc);
			$sqlMoji .= ", SonotaShiji = " . $this->convertNullStringValue($this->SonotaShiji);

			$sqlMoji .= ", KyoyuStartDate = " . $this->convertNullStringValue($this->KyoyuStartDate);
			$sqlMoji .= ", SenyuEndDate = " . $this->convertNullStringValue($this->SenyuEndDate);
			$sqlMoji .= ", BukkenCD489 = " . $this->convertNullStringValue($this->BukkenCD489);
			$sqlMoji .= ", IraiUpdated = " . $this->convertNullStringValue($this->IraiUpdated);
			$sqlMoji .= ", KyoyoEndDate = " . $this->convertNullStringValue($this->KyoyoEndDate);
			$sqlMoji .= ", SenyuStartDate = " . $this->convertNullStringValue($this->SenyuStartDate);
			$sqlMoji .= ", Otherwise6 = " . $this->convertNullStringValue($this->Otherwise6);
			$sqlMoji .= ", DateS6 = " . $this->convertNullStringValue($this->DateS6);
			$sqlMoji .= ", DateE6 = " . $this->convertNullStringValue($this->DateE6);
			$sqlMoji .= ", Otherwise7 = " . $this->convertNullStringValue($this->Otherwise7);
			$sqlMoji .= ", DateS7 = " . $this->convertNullStringValue($this->DateS7);
			$sqlMoji .= ", DateE7 = " . $this->convertNullStringValue($this->DateE7);
			$sqlMoji .= ", Otherwise8 = " . $this->convertNullStringValue($this->Otherwise8);
			$sqlMoji .= ", DateS8 = " . $this->convertNullStringValue($this->DateS8);
			$sqlMoji .= ", DateE8 = " . $this->convertNullStringValue($this->DateE8);
			$sqlMoji .= ", Otherwise9 = " . $this->convertNullStringValue($this->Otherwise9);
			$sqlMoji .= ", DateS9 = " . $this->convertNullStringValue($this->DateS9);
			$sqlMoji .= ", DateE9 = " . $this->convertNullStringValue($this->DateE9);
			$sqlMoji .= ", Otherwise10 = " . $this->convertNullStringValue($this->Otherwise10);
			$sqlMoji .= ", DateS10 = " . $this->convertNullStringValue($this->DateS10);
			$sqlMoji .= ", DateE10 = " . $this->convertNullStringValue($this->DateE10);

			$sqlMoji .= ", IR01 = " . $this->convertNullStringValue($this->IR01);
			$sqlMoji .= ", IR02 = " . $this->convertNullStringValue($this->IR02);
			$sqlMoji .= ", IR03 = " . $this->convertNullStringValue($this->IR03);
			$sqlMoji .= ", IR04 = " . $this->convertNullStringValue($this->IR04);
			$sqlMoji .= ", IR05 = " . $this->convertNullStringValue($this->IR05);
			$sqlMoji .= ", IR06 = " . $this->convertNullStringValue($this->IR06);
			$sqlMoji .= ", IR07 = " . $this->convertNullStringValue($this->IR07);
			$sqlMoji .= ", OpEndDate = " . $this->convertNullStringValue($this->OpEndDate);
			$sqlMoji .= ", KeijiBasho = " . $this->convertNullStringValue($this->KeijiBasho);
			$sqlMoji .= ", SmartFlg = " . $this->convertNullStringValue($this->SmartFlg);
			$sqlMoji .= ", TantoCD3 = " . $this->convertNullStringValue($this->TantoCD3);
			$sqlMoji .= ", TantoCD4 = " . $this->convertNullStringValue($this->TantoCD4);
			$sqlMoji .= ", TantoMemo = " . $this->convertNullStringValue($this->TantoMemo);
			$sqlMoji .= ", GyosyaTantoCD3 = " . $this->convertNullStringValue($this->GyosyaTantoCD3);
			$sqlMoji .= ", GyosyaTantoCD4 = " . $this->convertNullStringValue($this->GyosyaTantoCD4);
			$sqlMoji .= ", GyosyaTantoCD5 = " . $this->convertNullStringValue($this->GyosyaTantoCD5);
			$sqlMoji .= ", GyosyaTantoMemo = " . $this->convertNullStringValue($this->GyosyaTantoMemo);


			$sqlMoji .= ", MukouFlg = " . $this->convertFlgToString($this->MukouFlg);
			$sqlMoji .= ", Created = " . $this->convertNullStringValue($this->Created);
			$sqlMoji .= ", Creator = " . $this->convertNullIntValue($this->Creator);
			$sqlMoji .= ", Updated = NOW()";
			$sqlMoji .= ", Updater = " . $this->convertNullIntValue($this->Updater);
			$sqlMoji .= " where IraiRenkeiCD = $this->IraiRenkeiCD";

			$rtn = $this->myDB->executeQuery($sqlMoji);
			if (!$rtn) {
				$this->Err = "更新処理に失敗しました";
				$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
				unset($myLog);
				return FALSE;
			}
		}

		return TRUE;
	}

	function convertFlgToString($flg)
	{
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

	function convertFlgToBoolean($flg)
	{
		if ($flg == "t")
			return TRUE;
		else if ($flg == "f")
			return FALSE;
	}

	function convertNullIntValue($value)
	{
		if ($value == "")
			return "NULL";
		else
			return $value;
	}

	function convertNullStringValue($value)
	{
		if ($value == "")
			return "NULL";
		else
			return "'" . addslashes($value) . "'";
	}

	function setTransaction($lockLevel = "EXCLUSIVE")
	{
		// ログ準備
		$myLog = new SPFWLog();

		$lockString = "LOCK TABLE " . $this->TableName . " IN SHARE ROW " . $lockLevel . " MODE";
		$rtn = $this->myDB->setTransaction($lockString);
		if (!$rtn) {
			$this->Err = $this->myDB->Err;
			$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
			unset($myLog);
			return FALSE;
		}
		return TRUE;
	}

	function rollbackTransaction()
	{
		// ログ準備
		$myLog = new SPFWLog();

		$rtn = $this->myDB->rollbackTransaction();
		if (!$rtn) {
			$this->Err = $this->myDB->Err;
			$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
			unset($myLog);
			return FALSE;
		}
		return TRUE;
	}

	function commitTransaction()
	{
		// ログ準備
		$myLog = new SPFWLog();

		$rtn = $this->myDB->commitTransaction();
		if (!$rtn) {
			$this->Err = $this->myDB->Err;
			$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
			unset($myLog);
			return FALSE;
		}
		return TRUE;
	}
}