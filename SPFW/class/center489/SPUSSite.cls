<?php
/**
 * Siteクラス
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
class Site {
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
	var $SiteNo;
	var $SiteName;
	var $StartDate;
	var $EndDate;
	var $S001;
	var $S002;
	var $S003;
	var $S004;
	var $S005;
	var $S006;
	var $Created;
	var $Updated;
	var $KyoyuStartDate;
	var $ReserveTo2;
	var $ReserveFrom2;
	var $Tanto;
	var $YoyakuStart;
	var $TekyoDate;
	var $YoyakuEnd;

	var $YoteTekyoDate;
	var $KeteiDate;
	var $OPEnd;
	var $PostingFlg;
	var $PostingGyosya;
	var $PostingStatus;
	var $YoteiNotes;
	var $KeteiNotes;
	var $Wansho;
	var $WanshoNo;
	var $WanshoReturn;
	var $PicStatus;
	var $PostYoteiTemp;
	var $PostYoteiHasoDate;
	var $PostYoteiHaifuDate;
	var $PostYoteiMailFlg;
	var $PostKeteiTemp;

	var $PostKeteiHasoDate;
	var $PostKeteiHaifuDate;
	var $PostKeteiMailFlg;
	var $PostNotes;
	var $SeikyuDate;
	var $HokokuDate;
	var $PicTeikyoDate;
	var $PhotoPattern;
	var $PicTeikyoStatus;
	var $S007;

	var $S008;
	var $S009;
	var $S010;
	var $SFBukkenCD;
	var $Yomikata;
	var $SFUpdate;
	var $TerrakojiCD;
	var $Juuko;
	var $PhotoTeikyoMemo;
	var $PhotoTeikyoTanto;
	var $PhotoTeikyoCheckTanto;
	var $SekoCD;
	var $YoteiSakuseiFlg;

	var $YoteiSakuseiTanto;
var $KetteiSakuseiFlg;
var $KetteiSakuseiTanto;
var $KetteiTeishutuTanto;
var $MihenjiSakuseiFlg;
var $MihenjiSakuseiTanto;
var $MihenjiTeishutuTanto;
var $PostTokusokuiMailFlg;
var $TokusokuNotes;





	function __construct($myDB2 = "",$Site = -1) {
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
		settype($SiteNo,"integer");
		// 整数チェック
#		if (!is_long($SiteNo) or $SiteNo == 0) {
		if (!is_long($SiteNo)) {
			$this->Err = "ユーザーコード が不正です".$SiteNo ;
			$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
			unset($myLog);
			return FALSE;
		}

		$this->myDB2 = $myDB2;
		$this->TableName = "tSiteM";
		$this->SiteNo = $SiteNo;

		return TRUE;
	}

	function initialize(){
		$this->Err = "";

		$this->SiteNo = "";
		$this->SiteName = "";
		$this->StartDate = "";
		$this->EndDate = "";
		$this->S001 = "";
		$this->S002 = "";
		$this->S003 = "";
		$this->S004 = "";
		$this->S005 = "";
		$this->S006 = "";
		$this->Created = "";
		$this->Updated = "";
		$this->KyoyuStartDate = "";
		$this->ReserveTo2 = "";
		$this->ReserveFrom2 = "";
		$this->Tanto = "";
		$this->YoyakuStart = "";
		$this->TekyoDate = "";
		$this->YoyakuEnd = "";
		$this->YoteTekyoDate = "";
		$this->KeteiDate = "";
  		$this->OPEnd = "";
  		$this->PostingFlg = "";
  		$this->PostingGyosya = "";
  		$this->PostingStatus = "";
  		$this->YoteiNotes = "";
  		$this->KeteiNotes = "";
  		$this->Wansho = "";
  		$this->WanshoNo = "";
  		$this->WanshoReturn = "";
  		$this->PicStatus = "";
  		$this->PostYoteiTemp = "";
  		$this->PostYoteiHasoDate = "";
  		$this->PostYoteiHaifuDate = "";
  		$this->PostYoteiMailFlg = "";
  		$this->PostKeteiTemp = "";
  		$this->PostKeteiHasoDate = "";
  		$this->PostKeteiHaifuDate = "";
  		$this->PostKeteiMailFlg = "";
  		$this->PostNotes = "";
  		$this->SeikyuDate = "";
 		$this->HokokuDate = "";
		$this->PicTeikyoDate = "";
		$this->PhotoPattern = "";
		$this->PicTeikyoStatus = "";
		$this->S007 = "";
		$this->S008 = "";
		$this->S009 = "";
		$this->S010 = "";
		$this->SFBukkenCD = "";
		$this->Yomikata = "";
		$this->SFUpdate = "";
		$this->TerrakojiCD = "";
		$this->Juuko = "";
		$this->PhotoTeikyoMemo = "";
		$this->PhotoTeikyoTanto = "";
		$this->PhotoTeikyoCheckTanto = "";
		$this->SekoCD = "";
		$this->YoteiSakuseiFlg = "";
		$this->YoteiSakuseiTanto = "";
$this->KetteiSakuseiFlg = "";
$this->KetteiSakuseiTanto = "";
$this->KetteiTeishutuTanto = "";
$this->MihenjiSakuseiFlg = "";
$this->MihenjiSakuseiTanto = "";
$this->MihenjiTeishutuTanto = "";
$this->PostTokusokuMailFlg= "";
$this->TokusokuNotes= "";



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
		$sqlMoji .= "SiteNo";
		$sqlMoji .= ", SiteName";
		$sqlMoji .= ", StartDate";
		$sqlMoji .= ", EndDate";
		$sqlMoji .= ", S001";
		$sqlMoji .= ", S002";
		$sqlMoji .= ", S003";
		$sqlMoji .= ", S004";
		$sqlMoji .= ", S005";
		$sqlMoji .= ", S006";
		$sqlMoji .= ", Created";
		$sqlMoji .= ", Updated";
		$sqlMoji .= ", KyoyuStartDate";
		$sqlMoji .= ", ReserveTo2";
		$sqlMoji .= ", ReserveFrom2";
		$sqlMoji .= ", Tanto";
		$sqlMoji .= ", YoyakuStart";
		$sqlMoji .= ", TekyoDate";
		$sqlMoji .= ", YoyakuEnd";
		$sqlMoji .= ", YoteTekyoDate";
		$sqlMoji .= ", KeteiDate";
		$sqlMoji .= ", OPEnd ";
		$sqlMoji .= ", PostingFlg ";
		$sqlMoji .= ", PostingGyosya ";
		$sqlMoji .= ", PostingStatus ";
		$sqlMoji .= ", YoteiNotes ";
		$sqlMoji .= ", KeteiNotes ";
		$sqlMoji .= ", Wansho ";
		$sqlMoji .= ", WanshoNo ";
		$sqlMoji .= ", WanshoReturn ";
		$sqlMoji .= ", PicStatus ";
		$sqlMoji .= ", PostYoteiTemp ";
		$sqlMoji .= ", PostYoteiHasoDate ";
		$sqlMoji .= ", PostYoteiHaifuDate ";
		$sqlMoji .= ", PostYoteiMailFlg ";
		$sqlMoji .= ", PostKeteiTemp ";
		$sqlMoji .= ", PostKeteiHasoDate ";
		$sqlMoji .= ", PostKeteiHaifuDate ";
		$sqlMoji .= ", PostKeteiMailFlg ";
		$sqlMoji .= ", PostNotes ";
		$sqlMoji .= ", SeikyuDate ";
		$sqlMoji .= ", HokokuDate ";
		$sqlMoji .= ", PicTeikyoDate ";
		$sqlMoji .= ", PhotoPattern ";
		$sqlMoji .= ", PicTeikyoStatus ";
		$sqlMoji .= ", S007 ";
		$sqlMoji .= ", S008 ";
		$sqlMoji .= ", S009 ";
		$sqlMoji .= ", S010 ";
		$sqlMoji .= ", SFBukkenCD ";
		$sqlMoji .= ", Yomikata ";
		$sqlMoji .= ", SFUpdate ";
		$sqlMoji .= ", TerrakojiCD ";
		$sqlMoji .= ", Juuko ";
		$sqlMoji .= ", PhotoTeikyoMemo ";
		$sqlMoji .= ", PhotoTeikyoTanto ";
		$sqlMoji .= ", PhotoTeikyoCheckTanto ";
		$sqlMoji .= ", SekoCD ";
		$sqlMoji .= ", YoteiSakuseiFlg ";
		$sqlMoji .= ", YoteiSakuseiTanto ";
		$sqlMoji .= ", KetteiSakuseiFlg ";
		$sqlMoji .= ", KetteiSakuseiTanto ";
		$sqlMoji .= ", KetteiTeishutuTanto ";
		$sqlMoji .= ", MihenjiSakuseiFlg ";
		$sqlMoji .= ", MihenjiSakuseiTanto ";
		$sqlMoji .= ", MihenjiTeishutuTanto ";
		$sqlMoji .= ", PostTokusokuMailFlg ";
		$sqlMoji .= ", TokusokuNotes ";




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
				$this->SiteNo,
				$this->SiteName,
				$this->StartDate,
				$this->EndDate,
				$this->S001,
				$this->S002,
				$this->S003,
				$this->S004,
				$this->S005,
				$this->S006,
				$this->Created,
				$this->Updated,
				$this->KyoyuStartDate,
				$this->ReserveTo2,
				$this->ReserveFrom2,
				$this->Tanto,
				$this->YoyakuStart,
				$this->TekyoDate,
				$this->YoyakuEnd,
				$this->YoteTekyoDate,
				$this->KeteiDate,
				$this->OPEnd,
				$this->PostingFlg,
				$this->PostingGyosya,
				$this->PostingStatus,
				$this->YoteiNotes,
				$this->KeteiNotes,
				$this->Wansho,
				$this->WanshoNo,
				$this->WanshoReturn,
				$this->PicStatus,
				$this->PostYoteiTemp,
				$this->PostYoteiHasoDate,
				$this->PostYoteiHaifuDate,
				$this->PostYoteiMailFlg,
				$this->PostKeteiTemp,
				$this->PostKeteiHasoDate,
				$this->PostKeteiHaifuDate,
				$this->PostKeteiMailFlg,
				$this->PostNotes,
				$this->SeikyuDate,
				$this->HokokuDate,
				$this->PicTeikyoDate,
	 			$this->PhotoPattern,
				$this->PicTeikyoStatus,
				$this->S007,
				$this->S008,
				$this->S009,
				$this->S010,
				$this->SFBukkenCD,
				$this->Yomikata,
				$this->SFUpdate,
				$this->TerrakojiCD,
				$this->Juuko,
				$this->PhotoTeikyoMemo,
				$this->PhotoTeikyoTanto,
				$this->PhotoTeikyoCheckTanto,
				$this->SekoCD,
				$this->YoteiSakuseiFlg,
				$this->YoteiSakuseiTanto,
				$this->KetteiSakuseiFlg,
				$this->KetteiSakuseiTanto,
				$this->KetteiTeishutuTanto,
				$this->MihenjiSakuseiFlg,
				$this->MihenjiSakuseiTanto,
				$this->MihenjiTeishutuTanto,
				$this->PostTokusokuMailFlg,
				$this->TokusokuNotes


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
		
		if ($this->SiteNo < 0) {
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
					$sqlMoji = "select SiteNo from $this->TableName order by SiteNo desc limit 1";
					$maxCD = $this->myDB2->getOneValue($sqlMoji);
					$maxCD = ($maxCD == -1) ? 0 : $maxCD;
					$maxCD++;

					// step 4. インサート
					$sqlMoji = "Insert into $this->TableName (";

					$sqlMoji .= "SiteNo";
					if ($this->SiteName != "") $sqlMoji .= ", SiteName";
					if ($this->StartDate != "") $sqlMoji .= ", StartDate";
					if ($this->EndDate != "") $sqlMoji .= ", EndDate";
					if ($this->S001 != "") $sqlMoji .= ", S001";
					if ($this->S002 != "") $sqlMoji .= ", S002";
					if ($this->S003 != "") $sqlMoji .= ", S003";
					if ($this->S004 != "") $sqlMoji .= ", S004";
					if ($this->S005 != "") $sqlMoji .= ", S005";
					if ($this->S006 != "") $sqlMoji .= ", S006";
					if ($this->KyoyuStartDate != "") $sqlMoji .= ", KyoyuStartDate";
					if ($this->ReserveTo2 != "") $sqlMoji .= ", ReserveTo2";
					if ($this->ReserveFrom2 != "") $sqlMoji .= ", ReserveFrom2";
					if ($this->Tanto != "") $sqlMoji .= ", Tanto";
					if ($this->YoyakuStart != "") $sqlMoji .= ", YoyakuStart";
					if ($this->TekyoDate != "") $sqlMoji .= ", TekyoDate";
					if ($this->YoyakuEnd != "") $sqlMoji .= ", YoyakuEnd";
					if ($this->YoteTekyoDate != "") $sqlMoji .= ", YoteTekyoDate";
					if ($this->KeteiDate != "") $sqlMoji .= ", KeteiDate";
					$sqlMoji .= ", Created";
					$sqlMoji .= ", Updated";
					if ($this->OPEnd != "") $sqlMoji .= ", OPEnd";
					if ($this->PostingFlg != "") $sqlMoji .= ", PostingFlg";
					if ($this->PostingGyosya != "") $sqlMoji .= ", PostingGyosya";
					if ($this->PostingStatus != "") $sqlMoji .= ", PostingStatus";
					if ($this->YoteiNotes != "") $sqlMoji .= ", YoteiNotes";
					if ($this->KeteiNotes != "") $sqlMoji .= ", KeteiNotes";
					if ($this->Wansho != "") $sqlMoji .= ", Wansho";
					if ($this->WanshoNo != "") $sqlMoji .= ", WanshoNo";
					if ($this->WanshoReturn != "") $sqlMoji .= ", WanshoReturn";
					if ($this->PicStatus != "") $sqlMoji .= ", PicStatus";
					if ($this->PostYoteiTemp != "") $sqlMoji .= ", PostYoteiTemp";
					if ($this->PostYoteiHasoDate != "") $sqlMoji .= ", PostYoteiHasoDate";
					if ($this->PostYoteiHaifuDate != "") $sqlMoji .= ", PostYoteiHaifuDate";
					if ($this->PostYoteiMailFlg != "") $sqlMoji .= ", PostYoteiMailFlg";
					if ($this->PostKeteiTemp != "") $sqlMoji .= ", PostKeteiTemp";
					if ($this->PostKeteiHasoDate != "") $sqlMoji .= ", PostKeteiHasoDate";
					if ($this->PostKeteiHaifuDate != "") $sqlMoji .= ", PostKeteiHaifuDate";
					if ($this->PostKeteiMailFlg != "") $sqlMoji .= ", PostKeteiMailFlg";
					if ($this->PostNotes != "") $sqlMoji .= ", PostNotes";
					if ($this->SeikyuDate != "") $sqlMoji .= ", SeikyuDate";
					if ($this->HokokuDate != "") $sqlMoji .= ", HokokuDate";
					if ($this->PicTeikyoDate != "") $sqlMoji .= ", PicTeikyoDate";
					if ($this->PhotoPattern != "") $sqlMoji .= ", PhotoPattern";
					if ($this->PicTeikyoStatus != "") $sqlMoji .= ", PicTeikyoStatus";
					if ($this->S007 != "") $sqlMoji .= ", S007";
					if ($this->S008 != "") $sqlMoji .= ", S008";
					if ($this->S009 != "") $sqlMoji .= ", S009";
					if ($this->S010 != "") $sqlMoji .= ", S010";
					if ($this->SFBukkenCD != "") $sqlMoji .= ", SFBukkenCD";
					if ($this->Yomikata != "") $sqlMoji .= ", Yomikata";
					if ($this->SFUpdate != "") $sqlMoji .= ", SFUpdate";
 					if ($this->TerrakojiCD != "") $sqlMoji .= ", TerrakojiCD";
 					if ($this->Juuko != "") $sqlMoji .= ", Juuko";
 					if ($this->PhotoTeikyoMemo != "") $sqlMoji .= ", PhotoTeikyoMemo";
 					if ($this->PhotoTeikyoTanto != "") $sqlMoji .= ", PhotoTeikyoTanto";
 					if ($this->PhotoTeikyoCheckTanto != "") $sqlMoji .= ", PhotoTeikyoCheckTanto";
 					if ($this->SekoCD != "") $sqlMoji .= ", SekoCD";
 					if ($this->YoteiSakuseiFlg != "") $sqlMoji .= ", YoteiSakuseiFlg";
 					if ($this->YoteiSakuseiTanto != "") $sqlMoji .= ", YoteiSakuseiTanto";
					if ($this->KetteiSakuseiFlg != "") $sqlMoji .= ", KetteiSakuseiFlg";
					if ($this->KetteiSakuseiTanto != "") $sqlMoji .= ", KetteiSakuseiTanto";
					if ($this->KetteiTeishutuTanto != "") $sqlMoji .= ", KetteiTeishutuTanto";
					if ($this->MihenjiSakuseiFlg != "") $sqlMoji .= ", MihenjiSakuseiFlg";
					if ($this->MihenjiSakuseiTanto != "") $sqlMoji .= ", MihenjiSakuseiTanto";
					if ($this->MihenjiTeishutuTanto != "") $sqlMoji .= ", MihenjiTeishutuTanto";
					if ($this->PostTokusokuMailFlg != "") $sqlMoji .= ", PostTokusokuMailFlg";
					if ($this->TokusokuNotes != "") $sqlMoji .= ", TokusokuNotes";


					$sqlMoji .= ") values (";

					$sqlMoji .= "$maxCD";
					if ($this->SiteName != "") $sqlMoji .= ", '" . addslashes($this->SiteName) . "'";
					if ($this->StartDate != "")    $sqlMoji .= ", '" . addslashes($this->StartDate) . "'";
					if ($this->EndDate != "")    $sqlMoji .= ", '" . addslashes($this->EndDate) . "'";
					if ($this->S001 != "") $sqlMoji .= ", '" . addslashes($this->S001) . "'";
					if ($this->S002 != "") $sqlMoji .= ", '" . addslashes($this->S002) . "'";
					if ($this->S003 != "") $sqlMoji .= ", '" . addslashes($this->S003) . "'";
					if ($this->S004 != "") $sqlMoji .= ", '" . addslashes($this->S004) . "'";
					if ($this->S005 != "") $sqlMoji .= ", '" . addslashes($this->S005) . "'";
					if ($this->S006 != "") $sqlMoji .= ", '" . addslashes($this->S006) . "'";
					if ($this->KyoyuStartDate != "") $sqlMoji .= ", '" . addslashes($this->KyoyuStartDate) . "'";
					if ($this->ReserveTo2 != "") $sqlMoji .= ", '" . addslashes($this->ReserveTo2) . "'";
					if ($this->ReserveFrom2 != "") $sqlMoji .= ", '" . addslashes($this->ReserveFrom2) . "'";
					if ($this->Tanto != "") $sqlMoji .= ", '" . addslashes($this->Tanto) . "'";
					if ($this->YoyakuStart != "") $sqlMoji .= ", '" . addslashes($this->YoyakuStart) . "'";
					if ($this->TekyoDate != "") $sqlMoji .= ", '" . addslashes($this->TekyoDate) . "'";
					if ($this->YoyakuEnd != "") $sqlMoji .= ", '" . addslashes($this->YoyakuEnd) . "'";
					if ($this->YoteTekyoDate != "") $sqlMoji .= ", '" . addslashes($this->YoteTekyoDate) . "'";
					if ($this->KeteiDate != "") $sqlMoji .= ", '" . addslashes($this->KeteiDate) . "'";
					$sqlMoji .= ", NOW()";
					$sqlMoji .= ", NOW()";
					if ($this->OPEnd != "") $sqlMoji .= ", '" . addslashes($this->OPEnd) . "'";
					if ($this->PostingFlg != "") $sqlMoji .= ", '" . addslashes($this->PostingFlg) . "'";
					if ($this->PostingGyosya != "") $sqlMoji .= ", '" . addslashes($this->PostingGyosya) . "'";
					if ($this->PostingStatus != "") $sqlMoji .= ", '" . addslashes($this->PostingStatus) . "'";
					if ($this->YoteiNotes != "") $sqlMoji .= ", '" . addslashes($this->YoteiNotes) . "'";
					if ($this->KeteiNotes != "") $sqlMoji .= ", '" . addslashes($this->KeteiNotes) . "'";
					if ($this->Wansho != "") $sqlMoji .= ", '" . addslashes($this->Wansho) . "'";
					if ($this->WanshoNo != "") $sqlMoji .= ", '" . addslashes($this->WanshoNo) . "'";
					if ($this->WanshoReturn != "") $sqlMoji .= ", '" . addslashes($this->WanshoReturn) . "'";
					if ($this->PicStatus != "") $sqlMoji .= ", '" . addslashes($this->PicStatus) . "'";
					if ($this->PostYoteiTemp != "") $sqlMoji .= ", '" . addslashes($this->PostYoteiTemp) . "'";
					if ($this->PostYoteiHasoDate != "") $sqlMoji .= ", '" . addslashes($this->PostYoteiHasoDate) . "'";
					if ($this->PostYoteiHaifuDate != "") $sqlMoji .= ", '" . addslashes($this->PostYoteiHaifuDate) . "'";
					if ($this->PostYoteiMailFlg != "") $sqlMoji .= ", '" . addslashes($this->PostYoteiMailFlg) . "'";
					if ($this->PostKeteiTemp != "") $sqlMoji .= ", '" . addslashes($this->PostKeteiTemp) . "'";
					if ($this->PostKeteiHasoDate != "") $sqlMoji .= ", '" . addslashes($this->PostKeteiHasoDate) . "'";
					if ($this->PostKeteiHaifuDate != "") $sqlMoji .= ", '" . addslashes($this->PostKeteiHaifuDate) . "'";
					if ($this->PostKeteiMailFlg != "") $sqlMoji .= ", '" . addslashes($this->PostKeteiMailFlg) . "'";
					if ($this->PostNotes != "") $sqlMoji .= ", '" . addslashes($this->PostNotes) . "'";
					if ($this->SeikyuDate != "") $sqlMoji .= ", '" . addslashes($this->SeikyuDate) . "'";
					if ($this->HokokuDate != "") $sqlMoji .= ", '" . addslashes($this->HokokuDate) . "'";
					if ($this->PicTeikyoDate != "") $sqlMoji .= ", '" . addslashes($this->PicTeikyoDate) . "'";
					if ($this->PhotoPattern != "") $sqlMoji .= ", '" . addslashes($this->PhotoPattern) . "'";
					if ($this->PicTeikyoStatus != "") $sqlMoji .= ", '" . addslashes($this->PicTeikyoStatus) . "'";
					if ($this->S007 != "") $sqlMoji .= ", '" . addslashes($this->S007) . "'";
					if ($this->S008 != "") $sqlMoji .= ", '" . addslashes($this->S008) . "'";
					if ($this->S009 != "") $sqlMoji .= ", '" . addslashes($this->S009) . "'";
					if ($this->S010 != "") $sqlMoji .= ", '" . addslashes($this->S010) . "'";
					if ($this->SFBukkenCD != "") $sqlMoji .= ", '" . addslashes($this->SFBukkenCD) . "'";
					if ($this->Yomikata != "") $sqlMoji .= ", '" . addslashes($this->Yomikata) . "'";
					if ($this->SFUpdate != "") $sqlMoji .= ", '" . addslashes($this->SFUpdate) . "'";
					if ($this->TerrakojiCD != "") $sqlMoji .= ", '" . addslashes($this->TerrakojiCD) . "'";
					if ($this->Juuko != "") $sqlMoji .= ", '" . addslashes($this->Juuko) . "'";
					if ($this->PhotoTeikyoMemo != "") $sqlMoji .= ", '" . addslashes($this->PhotoTeikyoMemo) . "'";
					if ($this->PhotoTeikyoTanto != "") $sqlMoji .= ", '" . addslashes($this->PhotoTeikyoTanto) . "'";
					if ($this->PhotoTeikyoCheckTanto != "") $sqlMoji .= ", '" . addslashes($this->PhotoTeikyoCheckTanto) . "'";
					if ($this->SekoCD != "") $sqlMoji .= ", '" . addslashes($this->SekoCD) . "'";
					if ($this->YoteiSakuseiFlg != "") $sqlMoji .= ", '" . addslashes($this->YoteiSakuseiFlg) . "'";
					if ($this->YoteiSakuseiTanto != "") $sqlMoji .= ", '" . addslashes($this->YoteiSakuseiTanto) . "'";
					if ($this->KetteiSakuseiFlg != "") $sqlMoji .= ", KetteiSakuseiFlg";
					if ($this->KetteiSakuseiTanto != "") $sqlMoji .= ", KetteiSakuseiTanto";
					if ($this->KetteiTeishutuTanto != "") $sqlMoji .= ", KetteiTeishutuTanto";
					if ($this->MihenjiSakuseiFlg != "") $sqlMoji .= ", '" . addslashes($this->MihenjiSakuseiFlg) . "'";
					if ($this->MihenjiSakuseiTanto != "") $sqlMoji .= ", '" . addslashes($this->MihenjiSakuseiTanto) . "'";
					if ($this->MihenjiTeishutuTanto != "") $sqlMoji .= ", '" . addslashes($this->MihenjiTeishutuTanto) . "'";
					if ($this->PostTokusokuMailFlg != "") $sqlMoji .= ", '" . addslashes($this->PostTokusokuMailFlg) . "'";
					if ($this->TokusokuNotes != "") $sqlMoji .= ", '" . addslashes($this->TokusokuNotes) . "'";


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
							$this->SiteNo = $maxCD;
						}
					}
				}
			}

		} else {
###Update
			// アップデート
			$sqlMoji = "Update $this->TableName set ";
			$sqlMoji .= "SiteNo = $this->SiteNo";
			$sqlMoji .= ", SiteName = " . $this->convertNullStringValue($this->SiteName);
			$sqlMoji .= ", StartDate = " . $this->convertNullStringValue($this->StartDate);
			$sqlMoji .= ", EndDate = " . $this->convertNullStringValue($this->EndDate);
			$sqlMoji .= ", S001 = " . $this->convertNullStringValue($this->S001);
			$sqlMoji .= ", S002 = " . $this->convertNullStringValue($this->S002);
			$sqlMoji .= ", S003 = " . $this->convertNullStringValue($this->S003);
			$sqlMoji .= ", S004 = " . $this->convertNullStringValue($this->S004);
			$sqlMoji .= ", S005 = " . $this->convertNullStringValue($this->S005);
			$sqlMoji .= ", S006 = " . $this->convertNullStringValue($this->S006);
			$sqlMoji .= ", KyoyuStartDate = " . $this->convertNullStringValue($this->KyoyuStartDate);
			$sqlMoji .= ", ReserveTo2 = " . $this->convertNullStringValue($this->ReserveTo2);
			$sqlMoji .= ", ReserveFrom2 = " . $this->convertNullStringValue($this->ReserveFrom2);
			$sqlMoji .= ", Tanto = " . $this->convertNullStringValue($this->Tanto);
			$sqlMoji .= ", YoyakuStart = " . $this->convertNullStringValue($this->YoyakuStart);
			$sqlMoji .= ", TekyoDate = " . $this->convertNullStringValue($this->TekyoDate);
			$sqlMoji .= ", YoyakuEnd = " . $this->convertNullStringValue($this->YoyakuEnd);
			$sqlMoji .= ", YoteTekyoDate = " . $this->convertNullStringValue($this->YoteTekyoDate);
			$sqlMoji .= ", KeteiDate = " . $this->convertNullStringValue($this->KeteiDate);

			$sqlMoji .= ", Updated = NOW()";

			$sqlMoji .= ", OPEnd = " . $this->convertNullStringValue($this->OPEnd);
			$sqlMoji .= ", PostingFlg = " . $this->convertNullStringValue($this->PostingFlg);
			$sqlMoji .= ", PostingGyosya = " . $this->convertNullStringValue($this->PostingGyosya);
			$sqlMoji .= ", PostingStatus = " . $this->convertNullStringValue($this->PostingStatus);
			$sqlMoji .= ", YoteiNotes = " . $this->convertNullStringValue($this->YoteiNotes);
			$sqlMoji .= ", KeteiNotes = " . $this->convertNullStringValue($this->KeteiNotes);
			$sqlMoji .= ", Wansho = " . $this->convertNullStringValue($this->Wansho);
			$sqlMoji .= ", WanshoNo = " . $this->convertNullStringValue($this->WanshoNo);
			$sqlMoji .= ", WanshoReturn = " . $this->convertNullStringValue($this->WanshoReturn);
			$sqlMoji .= ", PicStatus = " . $this->convertNullStringValue($this->PicStatus);
			$sqlMoji .= ", PostYoteiTemp = " . $this->convertNullStringValue($this->PostYoteiTemp);
			$sqlMoji .= ", PostYoteiHasoDate = " . $this->convertNullStringValue($this->PostYoteiHasoDate);
			$sqlMoji .= ", PostYoteiHaifuDate = " . $this->convertNullStringValue($this->PostYoteiHaifuDate);
			$sqlMoji .= ", PostYoteiMailFlg = " . $this->convertNullStringValue($this->PostYoteiMailFlg);
			$sqlMoji .= ", PostKeteiTemp = " . $this->convertNullStringValue($this->PostKeteiTemp);
			$sqlMoji .= ", PostKeteiHasoDate = " . $this->convertNullStringValue($this->PostKeteiHasoDate);
			$sqlMoji .= ", PostKeteiHaifuDate = " . $this->convertNullStringValue($this->PostKeteiHaifuDate);
			$sqlMoji .= ", PostKeteiMailFlg = " . $this->convertNullStringValue($this->PostKeteiMailFlg);
			$sqlMoji .= ", PostNotes = " . $this->convertNullStringValue($this->PostNotes);
			$sqlMoji .= ", SeikyuDate = " . $this->convertNullStringValue($this->SeikyuDate);
			$sqlMoji .= ", HokokuDate = " . $this->convertNullStringValue($this->HokokuDate);
			$sqlMoji .= ", PicTeikyoDate = " . $this->convertNullStringValue($this->PicTeikyoDate);
			$sqlMoji .= ", PhotoPattern = " . $this->convertNullStringValue($this->PhotoPattern);
			$sqlMoji .= ", PicTeikyoStatus = " . $this->convertNullStringValue($this->PicTeikyoStatus);
			$sqlMoji .= ", S007 = " . $this->convertNullStringValue($this->S007);
			$sqlMoji .= ", S008 = " . $this->convertNullStringValue($this->S008);
			$sqlMoji .= ", S009 = " . $this->convertNullStringValue($this->S009);
			$sqlMoji .= ", S010 = " . $this->convertNullStringValue($this->S010);
			$sqlMoji .= ", SFBukkenCD = " . $this->convertNullStringValue($this->SFBukkenCD);
			$sqlMoji .= ", Yomikata = " . $this->convertNullStringValue($this->Yomikata);
			$sqlMoji .= ", SFUpdate = " . $this->convertNullStringValue($this->SFUpdate);
			$sqlMoji .= ", TerrakojiCD = " . $this->convertNullStringValue($this->TerrakojiCD);
			$sqlMoji .= ", Juuko = " . $this->convertNullStringValue($this->Juuko);
			$sqlMoji .= ", PhotoTeikyoMemo = " . $this->convertNullStringValue($this->PhotoTeikyoMemo);
			$sqlMoji .= ", PhotoTeikyoTanto = " . $this->convertNullStringValue($this->PhotoTeikyoTanto);
			$sqlMoji .= ", PhotoTeikyoCheckTanto = " . $this->convertNullStringValue($this->PhotoTeikyoCheckTanto);
			$sqlMoji .= ", SekoCD = " . $this->convertNullStringValue($this->SekoCD);
			$sqlMoji .= ", YoteiSakuseiFlg = " . $this->convertNullStringValue($this->YoteiSakuseiFlg);
			$sqlMoji .= ", YoteiSakuseiTanto = " . $this->convertNullStringValue($this->YoteiSakuseiTanto);
			$sqlMoji .= ", KetteiSakuseiFlg = " . $this->convertNullStringValue($this->KetteiSakuseiFlg);
			$sqlMoji .= ", KetteiSakuseiTanto = " . $this->convertNullStringValue($this->KetteiSakuseiTanto);
			$sqlMoji .= ", KetteiTeishutuTanto = " . $this->convertNullStringValue($this->KetteiTeishutuTanto);
			$sqlMoji .= ", MihenjiSakuseiFlg = " . $this->convertNullStringValue($this->MihenjiSakuseiFlg);
			$sqlMoji .= ", MihenjiSakuseiTanto = " . $this->convertNullStringValue($this->MihenjiSakuseiTanto);
			$sqlMoji .= ", MihenjiTeishutuTanto = " . $this->convertNullStringValue($this->MihenjiTeishutuTanto);
			$sqlMoji .= ", PostTokusokuMailFlg = " . $this->convertNullStringValue($this->PostTokusokuMailFlg);
			$sqlMoji .= ", TokusokuNotes = " . $this->convertNullStringValue($this->TokusokuNotes);

			$sqlMoji .= " where SiteNo = $this->SiteNo";

			$rtn = $this->myDB2->executeQuery($sqlMoji);
			if (!$rtn){
				$this->Err = "更新処理に失敗しました";
				$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
				unset($myLog);
				return FALSE;
			}
		} #ElseのEnd


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

}
?>
