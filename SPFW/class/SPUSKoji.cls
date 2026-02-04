<?php
/**
 * Kojiクラス
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

class Koji {
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
	var $KojiCD;

	var $BukkenCD;
	var $ClientCD;
	var $KojiName;
	var $SekoShutai;
	var $GyosyaTantoCD1;
	var $SagyoTime;
	var $SagyoDate;
	var $GasKeihoRNFlg;
	var $GasBaseFlg;
	var $KasaiRNFlg;
	var $KasaiChukeiRNFlg;
	var $KasaiKyoyuRNFlg;
	var $SonotaKojiFlg;
	var $SonotaKojiNaiyo;
	var $ZentaiStartDate;
	var $ZentaiEndDate;
	var $AnnaiDate;
	var $ReceptionDate;
	var $KakuteiDate;

	var $KyoyoStartDate;
	var $KyoyoEndDate;
	var $SenyuStartDate;
	var $SenyuEndDate;
	var $YobiStartDate;
	var $YobiEndDate;
	var $Otherwise6;
	var $Otherwise6Flg;
	var $DateS6;
	var $DateE6;
	var $Otherwise7;
	var $Otherwise7Flg;
	var $DateS7;
	var $DateE7;
	var $Otherwise8;
	var $Otherwise8Flg;
	var $DateS8;
	var $DateE8;
	var $Otherwise9;
	var $Otherwise9Flg;
	var $DateS9;
	var $DateE9;
	var $Otherwise10;
	var $Otherwise10Flg;
	var $DateS10;
	var $DateE10;

	var $SanpaiKaishuDate;
	var $SanpaiBiko;
	var $SonotaKoji;
	var $CurrentNyukan;
	var $CurrentNyukanSonota;
	var $RNNyukan;
	var $RNNyukanSonota;
	var $JikahoRendoFlg;
	var $Jikaho;
	var $ShizaiOkiba;
	var $DoguOkiba;
	var $SanpaiOkiba;
	var $Kyukei;
	var $Toilet;
	var $Smoking;
	var $RentKey;
	var $Parking;
	var $KeibiCompany;
	var $KeibiCompanyTEL;
	var $Hijyo;
	var $HijyoIho;
	var $HijyoIhoShubetu;
	var $Gas;
	var $GasIho;
	var $GasIhoShubetu;
	var $Kasai;
	var $KasaiIho;
	var $KasaiIhoShubetu;
	var $Trouble;
	var $TroubleIho;
	var $TroubleIhoShubetu;
	var $Bohan;
	var $BohanIho;
	var $BohanIhoShubetu;
	var $Kanki;
	var $KankiIho;
	var $KankiIhoShubetu;
	var $Sonota1;
	var $Sonota1Iho;
	var $Sonota1IhoShubetu;
	var $Sonota2;
	var $Sonota2Iho;
	var $Sonota2IhoShubetu;
	var $Sonota3;
	var $Sonota3Iho;
	var $Sonota3IhoShubetu;
	var $Takuhai;
	var $TakuhaiCompany;
	var $TakuhaiCompanyTEL;
	var $TakuhaiSetuzoku;
	var $OP1DeviceCD;
	var $OP2DeviceCD;
	var $OP3DeviceCD;
	var $OP4DeviceCD;
	var $OP5DeviceCD;
	var $OP6DeviceCD;
	var $OP7DeviceCD;
	var $OP8DeviceCD;
	var $OP9DeviceCD;
	var $OP10DeviceCD;
	var $OPZiyuu1;
	var $OPZiyuu2;
	var $OPZiyuu3;
	var $OPZiyuu4;
	var $OPZiyuu5;
	var $OPZiyuu6;
	var $OPZiyuu7;
	var $OPZiyuu8;
	var $OPZiyuu9;
	var $OPZiyuu10;
	var $OPPrice1;
	var $OPPrice2;
	var $OPPrice3;
	var $OPPrice4;
	var $OPPrice5;
	var $OPPrice6;
	var $OPPrice7;
	var $OPPrice8;
	var $OPPrice9;
	var $OPPrice10;
	var $OPNotes;
	var $SekoKyoyoKikiPic;
	var $SekoSenyuKikiPic;
	var $SekoHaisenPic;
	var $AnshoNo;
	var $AnshoNoKojichu;
	var $PanelFlg;
	var $OyaPanel;
	var $KoPanel;
	var $PanelGyosya;
	var $GetHeyaNoFlg;
	var $OrderKanriFutoFlg;

	var $ShizaiOkibaFlg;
	var $DoguOkibaFlg;
	var $SanpaiOkibaFlg;
	var $KyukeiFlg;
	var $ToiletFlg;
	var $SmokingFlg;
	var $RentKeyFlg;
	var $ParkingFlg;
	var $WakuSu;
	var $OPUketuke;
	var $MaxWakuSu;
	var $KojiTantoName;
	var $KojiShozokuName;
	var $KojiShozokuTEL;
	var $ShoboTokurei;
	var $ShoboSikenhoho;
	var $GyosyaTantoCD2;
	var $AnshoNoBiko;
	var $SekoHaisenPicFlg;
	var $SekoKyoyoKikiPicFlg;
	var $SekoSenyuKikiPicFlg;
	var $KeihoBiko;
	var $SetubiBiko;
	var $KojiBiko;

	var $OPBiko1;
	var $OPBiko2;
	var $OPBiko3;
	var $OPBiko4;
	var $OPBiko5;
	var $OPBiko6;
	var $OPBiko7;
	var $OPBiko8;
	var $OPBiko9;
	var $OPBiko10;
	var $SiyobuzaiOyaFlg;
	var $SiyobuzaiOyaBiko;
	var $SiyobuzaiKoFlg;
	var $SiyobuzaiKoBiko;
	var $SiyobuzaiSyugenFlg;
	var $SiyobuzaiSyugenBiko;
	var $SiyobuzaiKanOyaFlg;
	var $SiyobuzaiKanOyaBiko;
	var $SiyobuzaiSeigyoFlg;
	var $SiyobuzaiSeigyoBiko;
	var $Hansu;
	var $MinuteTime;
	var $Kojijun;
	var $FirstDateFeature;
	var $Holiday1;
	var $Holiday2;
	var $Holiday3;
	var $Holiday4;
	var $WakuPattern;
	var $Kirikaehoho;
	var $KirikaeHeikoEizoriyo;
	var $KasaiHeya;
	var $KansenKoji;
	var $GasKoji;
	var $RosuiKoji;
	var $Shiharai;
	var $TagSuu;
	var $AnzenHattyusya;
	var $AnzenHattyusyaAddress;
	var $AnzenHattyusyaTEL;
	var $AnzenKojiNaiyo;
	var $AnzenKojiName;
	var $ConstTime;
	var $BohanKoji;
	var $OwnerTagSuu;
	var $TagKirikae; 
	var $KojiShijiBiko;
	var $Kanrisitu; 
	var $DispCompany1;
	var $DispCompanyTEL1;
	var $DispCompanySashi1;
	var $DispCompanyToi1;
	var $DispCompany2;
	var $DispCompanyTEL2;
	var $DispCompanySashi2;
	var $DispCompanyToi2;
	var $DispCompany3;
	var $DispCompanyTEL3;
	var $DispCompanySashi3;
	var $DispCompanyToi3;
	var $ToiawasewakiDisp;
	var $DispCompanyTop;
	var $Sagyoin;
	var $MitumorishoNo;
	var $ShiteiVestCD;
	var $ShiteiVestOther;
	var $Answer;
	var $Answer489;
	var $WEBRecept;
	var $YoteiPage;
	var $YobiSuu;
	var $KeijiSuu;
	var $Battery;
	var $BatterySuu;
	var $BukkenTourokuStatus;

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

	function __construct($myDB = "",$KojiCD = -1) {
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
		settype($KojiCD,"integer");
		// 整数チェック
		if (!is_long($KojiCD) or $KojiCD == 0) {
			$this->Err = "ユーザーコード が不正です";
			$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
			unset($myLog);
			return FALSE;
		}

		$this->myDB = $myDB;
		$this->TableName = "tKojiF";
		$this->KojiCD = $KojiCD;

		return TRUE;
	}

	function initialize(){
		$this->Err = "";

		$this->KojiCD = "";

	  $this->BukkenCD = "";
	  $this->ClientCD = "";
	  $this->KojiName = "";
	  $this->SekoShutai = "";
	  $this->GyosyaTantoCD1 = "";
	  $this->SagyoTime = "";
	  $this->SagyoDate = "";
	  $this->GasKeihoRNFlg = "";
	  $this->GasBaseFlg = "";
	  $this->KasaiRNFlg = "";
	  $this->KasaiChukeiRNFlg = "";
	  $this->KasaiKyoyuRNFlg = "";
	  $this->SonotaKojiFlg = "";
	  $this->SonotaKojiNaiyo = "";
	  $this->ZentaiStartDate = "";
	  $this->ZentaiEndDate = "";
	  $this->AnnaiDate = "";
	  $this->ReceptionDate = "";
	  $this->KakuteiDate = "";

	  $this->KyoyoStartDate = "";
	  $this->KyoyoEndDate = "";
	  $this->SenyuStartDate = "";
	  $this->SenyuEndDate = "";
	  $this->YobiStartDate = "";
	  $this->YobiEndDate = "";
	  $this->Otherwise6 = "";
	  $this->Otherwise6Flg = "";
	  $this->DateS6 = "";
	  $this->DateE6 = "";
	  $this->Otherwise7 = "";
	  $this->Otherwise7Flg = "";
	  $this->DateS7 = "";
	  $this->DateE7 = "";
	  $this->Otherwise8 = "";
	  $this->Otherwise8Flg = "";
	  $this->DateS8 = "";
	  $this->DateE8 = "";
	  $this->Otherwise9 = "";
	  $this->Otherwise9Flg = "";
	  $this->DateS9 = "";
	  $this->DateE9 = "";
	  $this->Otherwise10 = "";
	  $this->Otherwise10Flg = "";
	  $this->DateS10 = "";
	  $this->DateE10 = "";

	  $this->SanpaiKaishuDate = "";
	  $this->SanpaiBiko = "";
	  $this->SonotaKoji = "";
	  $this->CurrentNyukan = "";
	  $this->CurrentNyukanSonota = "";
	  $this->RNNyukan = "";
	  $this->RNNyukanSonota = "";
	  $this->JikahoRendoFlg = "";
	  $this->Jikaho = "";
	  $this->ShizaiOkiba = "";
	  $this->DoguOkiba = "";
	  $this->SanpaiOkiba = "";
	  $this->Kyukei = "";
	  $this->Toilet = "";
	  $this->Smoking = "";
	  $this->RentKey = "";
	  $this->Parking = "";
	  $this->KeibiCompany = "";
	  $this->KeibiCompanyTEL = "";
	  $this->Hijyo = "";
	  $this->HijyoIho = "";
	  $this->HijyoIhoShubetu = "";
	  $this->Gas = "";
	  $this->GasIho = "";
	  $this->GasIhoShubetu = "";
	  $this->Kasai = "";
	  $this->KasaiIho = "";
	  $this->KasaiIhoShubetu = "";
	  $this->Trouble = "";
	  $this->TroubleIho = "";
	  $this->TroubleIhoShubetu = "";
	  $this->Bohan = "";
	  $this->BohanIho = "";
	  $this->BohanIhoShubetu = "";
	  $this->Kanki = "";
	  $this->KankiIho = "";
	  $this->KankiIhoShubetu = "";
	  $this->Sonota1 = "";
	  $this->Sonota1Iho = "";
	  $this->Sonota1IhoShubetu = "";
	  $this->Sonota2 = "";
	  $this->Sonota2Iho = "";
	  $this->Sonota2IhoShubetu = "";
	  $this->Sonota3 = "";
	  $this->Sonota3Iho = "";
	  $this->Sonota3IhoShubetu = "";
	  $this->Takuhai = "";
	  $this->TakuhaiCompany = "";
	  $this->TakuhaiCompanyTEL = "";
	  $this->TakuhaiSetuzoku = "";
	  $this->OP1DeviceCD = "";
	  $this->OP2DeviceCD = "";
	  $this->OP3DeviceCD = "";
	  $this->OP4DeviceCD = "";
	  $this->OP5DeviceCD = "";
	  $this->OP6DeviceCD = "";
	  $this->OP7DeviceCD = "";
	  $this->OP8DeviceCD = "";
	  $this->OP9DeviceCD = "";
	  $this->OP10DeviceCD = "";
	  $this->OPZiyuu1 = "";
	  $this->OPZiyuu2 = "";
	  $this->OPZiyuu3 = "";
	  $this->OPZiyuu4 = "";
	  $this->OPZiyuu5 = "";
	  $this->OPZiyuu6 = "";
	  $this->OPZiyuu7 = "";
	  $this->OPZiyuu8 = "";
	  $this->OPZiyuu9 = "";
	  $this->OPZiyuu10 = "";
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
	  $this->OPNotes = "";
	  $this->SekoKyoyoKikiPic = "";
	  $this->SekoSenyuKikiPic = "";
	  $this->SekoHaisenPic = "";
	  $this->AnshoNo = "";
	  $this->AnshoNoKojichu = "";
	  $this->PanelFlg = "";
	  $this->OyaPanel = "";
	  $this->KoPanel = "";
	  $this->PanelGyosya = "";
	  $this->GetHeyaNoFlg = "";
	  $this->OrderKanriFutoFlg = "";

	  $this->ShizaiOkibaFlg = "";
	  $this->DoguOkibaFlg = "";
	  $this->SanpaiOkibaFlg = "";
	  $this->KyukeiFlg = "";
	  $this->ToiletFlg = "";
	  $this->SmokingFlg = "";
	  $this->RentKeyFlg = "";
	  $this->ParkingFlg = "";

	  $this->WakuSu = "";
	  $this->OPUketuke = "";
	  $this->MaxWakuSu = "";
	  $this->KojiTantoName = "";
	  $this->KojiShozokuName = "";
	  $this->KojiShozokuTEL = "";
	  $this->ShoboTokurei = "";
	  $this->ShoboSikenhoho = "";
	  $this->GyosyaTantoCD2 = "";
	  $this->AnshoNoBiko = "";
	  $this->SekoHaisenPicFlg = "";
	  $this->SekoKyoyoKikiPicFlg = "";
	  $this->SekoSenyuKikiPicFlg = "";
	  $this->KeihoBiko = "";
	  $this->SetubiBiko = "";
	  $this->KojiBiko = "";

	  $this->OPBiko1 = "";
	  $this->OPBiko2 = "";
	  $this->OPBiko3 = "";
	  $this->OPBiko4 = "";
	  $this->OPBiko5 = "";
	  $this->OPBiko6 = "";
	  $this->OPBiko7 = "";
	  $this->OPBiko8 = "";
	  $this->OPBiko9 = "";
	  $this->OPBiko10 = "";
	  $this->SiyobuzaiOyaFlg = "";
	  $this->SiyobuzaiOyaBiko = "";
	  $this->SiyobuzaiKoFlg = "";
	  $this->SiyobuzaiKoBiko = "";
	  $this->SiyobuzaiSyugenFlg = "";
	  $this->SiyobuzaiSyugenBiko = "";
	  $this->SiyobuzaiKanOyaFlg = "";
	  $this->SiyobuzaiKanOyaBiko = "";
	  $this->SiyobuzaiSeigyoFlg = "";
	  $this->SiyobuzaiSeigyoBiko = "";

	  $this->Hansu = "";
	  $this->MinuteTime = "";
	  $this->Kojijun = "";
	  $this->FirstDateFeature = "";
	  $this->Holiday1 = "";
	  $this->Holiday2 = "";
	  $this->Holiday3 = "";
	  $this->Holiday4 = "";
	  $this->WakuPattern = "";
	  $this->Kirikaehoho = "";
	  $this->KirikaeHeikoEizoriyo = "";
	  $this->KasaiHeya = "";
	  $this->KansenKoji = "";
	  $this->GasKoji = "";
	  $this->RosuiKoji = "";
	  $this->Shiharai = "";
	  $this->TagSuu = "";
	  $this->AnzenHattyusya = "";
	  $this->AnzenHattyusyaAddress = "";
	  $this->AnzenHattyusyaTEL = "";
	  $this->AnzenKojiNaiyo = "";
	  $this->AnzenKojiName = "";
	  $this->ConstTime = "";
	  $this->BohanKoji = "";
	  $this->OwnerTagSuu = "";
	  $this->TagKirikae = "";
	  $this->Kanrisitu = "";

	  $this->KojiShijiBiko = "";
		$this->DispCompany1 = "";
		$this->DispCompanyTEL1 = "";
		$this->DispCompanySashi1 = "";
		$this->DispCompanyToi1 = "";
		$this->DispCompany2 = "";
		$this->DispCompanyTEL2 = "";
		$this->DispCompanySashi2 = "";
		$this->DispCompanyToi2 = "";
		$this->DispCompany3 = "";
		$this->DispCompanyTEL3 = "";
		$this->DispCompanySashi3 = "";
		$this->DispCompanyToi3 = "";
		$this->ToiawasewakiDisp = "";
		$this->DispCompanyTop = "";
		$this->Sagyoin = "";
		$this->MitumorishoNo = "";
		$this->ShiteiVestCD = "";
		$this->ShiteiVestOther = "";
		$this->Answer = "";
		$this->Answer489 = "";
		$this->WEBRecept = "";
		$this->YoteiPage = "";
		$this->YobiSuu = "";
		$this->KeijiSuu = "";
		$this->Battery = "";
		$this->BatterySuu = "";
		$this->BukkenTourokuStatus = "";

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
		$sqlMoji .= "KojiCD";

		 $sqlMoji .= ", BukkenCD ";
		 $sqlMoji .= ", ClientCD ";
		 $sqlMoji .= ", KojiName ";
		 $sqlMoji .= ", SekoShutai ";
		 $sqlMoji .= ", GyosyaTantoCD1 ";
		 $sqlMoji .= ", SagyoTime ";
		 $sqlMoji .= ", SagyoDate ";
		 $sqlMoji .= ", GasKeihoRNFlg ";
		 $sqlMoji .= ", GasBaseFlg ";
		 $sqlMoji .= ", KasaiRNFlg ";
		 $sqlMoji .= ", KasaiChukeiRNFlg ";
		 $sqlMoji .= ", KasaiKyoyuRNFlg ";
		 $sqlMoji .= ", SonotaKojiFlg ";
		 $sqlMoji .= ", SonotaKojiNaiyo ";
		 $sqlMoji .= ", ZentaiStartDate ";
		 $sqlMoji .= ", ZentaiEndDate ";
		 $sqlMoji .= ", AnnaiDate ";
		 $sqlMoji .= ", ReceptionDate ";
		 $sqlMoji .= ", KakuteiDate ";

		 $sqlMoji .= ", KyoyoStartDate ";
		 $sqlMoji .= ", KyoyoEndDate ";
		 $sqlMoji .= ", SenyuStartDate ";
		 $sqlMoji .= ", SenyuEndDate ";
		 $sqlMoji .= ", YobiStartDate ";
		 $sqlMoji .= ", YobiEndDate ";
		 $sqlMoji .= ", Otherwise6 ";
		 $sqlMoji .= ", Otherwise6Flg ";
		 $sqlMoji .= ", DateS6 ";
		 $sqlMoji .= ", DateE6 ";
		 $sqlMoji .= ", Otherwise7 ";
		 $sqlMoji .= ", Otherwise7Flg ";
		 $sqlMoji .= ", DateS7 ";
		 $sqlMoji .= ", DateE7 ";
		 $sqlMoji .= ", Otherwise8 ";
		 $sqlMoji .= ", Otherwise8Flg ";
		 $sqlMoji .= ", DateS8 ";
		 $sqlMoji .= ", DateE8 ";
		 $sqlMoji .= ", Otherwise9 ";
		 $sqlMoji .= ", Otherwise9Flg ";
		 $sqlMoji .= ", DateS9 ";
		 $sqlMoji .= ", DateE9 ";
		 $sqlMoji .= ", Otherwise10 ";
		 $sqlMoji .= ", Otherwise10Flg ";
		 $sqlMoji .= ", DateS10 ";
		 $sqlMoji .= ", DateE10 ";

		 $sqlMoji .= ", SanpaiKaishuDate ";
		 $sqlMoji .= ", SanpaiBiko ";
		 $sqlMoji .= ", SonotaKoji ";
		 $sqlMoji .= ", CurrentNyukan ";
		 $sqlMoji .= ", CurrentNyukanSonota ";
		 $sqlMoji .= ", RNNyukan ";
		 $sqlMoji .= ", RNNyukanSonota ";
		 $sqlMoji .= ", JikahoRendoFlg ";
		 $sqlMoji .= ", Jikaho ";
		 $sqlMoji .= ", ShizaiOkiba ";
		 $sqlMoji .= ", DoguOkiba ";
		 $sqlMoji .= ", SanpaiOkiba ";
		 $sqlMoji .= ", Kyukei ";
		 $sqlMoji .= ", Toilet ";
		 $sqlMoji .= ", Smoking ";
		 $sqlMoji .= ", RentKey ";
		 $sqlMoji .= ", Parking ";
		 $sqlMoji .= ", KeibiCompany ";
		 $sqlMoji .= ", KeibiCompanyTEL ";
		 $sqlMoji .= ", Hijyo ";
		 $sqlMoji .= ", HijyoIho ";
		 $sqlMoji .= ", HijyoIhoShubetu ";
		 $sqlMoji .= ", Gas ";
		 $sqlMoji .= ", GasIho ";
		 $sqlMoji .= ", GasIhoShubetu ";
		 $sqlMoji .= ", Kasai ";
		 $sqlMoji .= ", KasaiIho ";
		 $sqlMoji .= ", KasaiIhoShubetu ";
		 $sqlMoji .= ", Trouble ";
		 $sqlMoji .= ", TroubleIho ";
		 $sqlMoji .= ", TroubleIhoShubetu ";
		 $sqlMoji .= ", Bohan ";
		 $sqlMoji .= ", BohanIho ";
		 $sqlMoji .= ", BohanIhoShubetu ";
		 $sqlMoji .= ", Kanki ";
		 $sqlMoji .= ", KankiIho ";
		 $sqlMoji .= ", KankiIhoShubetu ";
		 $sqlMoji .= ", Sonota1 ";
		 $sqlMoji .= ", Sonota1Iho ";
		 $sqlMoji .= ", Sonota1IhoShubetu ";
		 $sqlMoji .= ", Sonota2 ";
		 $sqlMoji .= ", Sonota2Iho ";
		 $sqlMoji .= ", Sonota2IhoShubetu ";
		 $sqlMoji .= ", Sonota3 ";
		 $sqlMoji .= ", Sonota3Iho ";
		 $sqlMoji .= ", Sonota3IhoShubetu ";
		 $sqlMoji .= ", Takuhai ";
		 $sqlMoji .= ", TakuhaiCompany ";
		 $sqlMoji .= ", TakuhaiCompanyTEL ";
		 $sqlMoji .= ", TakuhaiSetuzoku ";
		 $sqlMoji .= ", OP1DeviceCD ";
		 $sqlMoji .= ", OP2DeviceCD ";
		 $sqlMoji .= ", OP3DeviceCD ";
		 $sqlMoji .= ", OP4DeviceCD ";
		 $sqlMoji .= ", OP5DeviceCD ";
		 $sqlMoji .= ", OP6DeviceCD ";
		 $sqlMoji .= ", OP7DeviceCD ";
		 $sqlMoji .= ", OP8DeviceCD ";
		 $sqlMoji .= ", OP9DeviceCD ";
		 $sqlMoji .= ", OP10DeviceCD ";
		 $sqlMoji .= ", OPZiyuu1 ";
		 $sqlMoji .= ", OPZiyuu2 ";
		 $sqlMoji .= ", OPZiyuu3 ";
		 $sqlMoji .= ", OPZiyuu4 ";
		 $sqlMoji .= ", OPZiyuu5 ";
		 $sqlMoji .= ", OPZiyuu6 ";
		 $sqlMoji .= ", OPZiyuu7 ";
		 $sqlMoji .= ", OPZiyuu8 ";
		 $sqlMoji .= ", OPZiyuu9 ";
		 $sqlMoji .= ", OPZiyuu10 ";
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
		 $sqlMoji .= ", OPNotes ";
		 $sqlMoji .= ", SekoKyoyoKikiPic ";
		 $sqlMoji .= ", SekoSenyuKikiPic ";
		 $sqlMoji .= ", SekoHaisenPic ";
		 $sqlMoji .= ", AnshoNo ";
		 $sqlMoji .= ", AnshoNoKojichu ";
		 $sqlMoji .= ", PanelFlg ";
		 $sqlMoji .= ", OyaPanel ";
		 $sqlMoji .= ", KoPanel ";
		 $sqlMoji .= ", PanelGyosya ";
		 $sqlMoji .= ", GetHeyaNoFlg ";
		 $sqlMoji .= ", OrderKanriFutoFlg ";

		 $sqlMoji .= ", ShizaiOkibaFlg ";
		 $sqlMoji .= ", DoguOkibaFlg ";
		 $sqlMoji .= ", SanpaiOkibaFlg ";
		 $sqlMoji .= ", KyukeiFlg ";
		 $sqlMoji .= ", ToiletFlg ";
		 $sqlMoji .= ", SmokingFlg ";
		 $sqlMoji .= ", RentKeyFlg ";
		 $sqlMoji .= ", ParkingFlg ";

		 $sqlMoji .= ", WakuSu ";
		 $sqlMoji .= ", OPUketuke ";
		 $sqlMoji .= ", MaxWakuSu ";
		 $sqlMoji .= ", KojiTantoName ";
		 $sqlMoji .= ", KojiShozokuName ";
		 $sqlMoji .= ", KojiShozokuTEL ";
		 $sqlMoji .= ", ShoboTokurei ";
		 $sqlMoji .= ", ShoboSikenhoho ";
		 $sqlMoji .= ", GyosyaTantoCD2 ";
		 $sqlMoji .= ", AnshoNoBiko ";
		 $sqlMoji .= ", SekoHaisenPicFlg ";
		 $sqlMoji .= ", SekoKyoyoKikiPicFlg ";
		 $sqlMoji .= ", SekoSenyuKikiPicFlg ";
		 $sqlMoji .= ", KeihoBiko ";
		 $sqlMoji .= ", SetubiBiko ";
		 $sqlMoji .= ", KojiBiko ";

		 $sqlMoji .= ", OPBiko1 ";
		 $sqlMoji .= ", OPBiko2 ";
		 $sqlMoji .= ", OPBiko3 ";
		 $sqlMoji .= ", OPBiko4 ";
		 $sqlMoji .= ", OPBiko5 ";
		 $sqlMoji .= ", OPBiko6 ";
		 $sqlMoji .= ", OPBiko7 ";
		 $sqlMoji .= ", OPBiko8 ";
		 $sqlMoji .= ", OPBiko9 ";
		 $sqlMoji .= ", OPBiko10 ";
		 $sqlMoji .= ", SiyobuzaiOyaFlg ";
		 $sqlMoji .= ", SiyobuzaiOyaBiko ";
		 $sqlMoji .= ", SiyobuzaiKoFlg ";
		 $sqlMoji .= ", SiyobuzaiKoBiko ";
		 $sqlMoji .= ", SiyobuzaiSyugenFlg ";
		 $sqlMoji .= ", SiyobuzaiSyugenBiko ";
		 $sqlMoji .= ", SiyobuzaiKanOyaFlg ";
		 $sqlMoji .= ", SiyobuzaiKanOyaBiko ";
		 $sqlMoji .= ", SiyobuzaiSeigyoFlg ";
		 $sqlMoji .= ", SiyobuzaiSeigyoBiko ";

		 $sqlMoji .= ", Hansu ";
		 $sqlMoji .= ", MinuteTime ";
		 $sqlMoji .= ", Kojijun ";
		 $sqlMoji .= ", FirstDateFeature ";
		 $sqlMoji .= ", Holiday1 ";
		 $sqlMoji .= ", Holiday2 ";
		 $sqlMoji .= ", Holiday3 ";
		 $sqlMoji .= ", Holiday4 ";
		 $sqlMoji .= ", WakuPattern ";
		 $sqlMoji .= ", Kirikaehoho ";
		 $sqlMoji .= ", KirikaeHeikoEizoriyo ";
		 $sqlMoji .= ", KasaiHeya ";
		 $sqlMoji .= ", KansenKoji ";
		 $sqlMoji .= ", GasKoji ";
		 $sqlMoji .= ", RosuiKoji ";
		 $sqlMoji .= ", Shiharai ";
		 $sqlMoji .= ", TagSuu ";
		 $sqlMoji .= ", AnzenHattyusya ";
		 $sqlMoji .= ", AnzenHattyusyaAddress ";
		 $sqlMoji .= ", AnzenHattyusyaTEL ";
		 $sqlMoji .= ", AnzenKojiNaiyo ";
		 $sqlMoji .= ", AnzenKojiName ";
		 $sqlMoji .= ", ConstTime ";
		 $sqlMoji .= ", BohanKoji ";
		 $sqlMoji .= ", OwnerTagSuu ";
		 $sqlMoji .= ", TagKirikae ";
		 $sqlMoji .= ", Kanrisitu ";
		 $sqlMoji .= ", KojiShijiBiko ";
		$sqlMoji .= ", DispCompany1 ";
		$sqlMoji .= ", DispCompanyTEL1 ";
		$sqlMoji .= ", DispCompanySashi1 ";
		$sqlMoji .= ", DispCompanyToi1 ";
		$sqlMoji .= ", DispCompany2 ";
		$sqlMoji .= ", DispCompanyTEL2 ";
		$sqlMoji .= ", DispCompanySashi2 ";
		$sqlMoji .= ", DispCompanyToi2 ";
		$sqlMoji .= ", DispCompany3 ";
		$sqlMoji .= ", DispCompanyTEL3 ";
		$sqlMoji .= ", DispCompanySashi3 ";
		$sqlMoji .= ", DispCompanyToi3 ";
		$sqlMoji .= ", ToiawasewakiDisp ";
		$sqlMoji .= ", DispCompanyTop ";
		$sqlMoji .= ", Sagyoin ";
		$sqlMoji .= ", MitumorishoNo ";
		$sqlMoji .= ", ShiteiVestCD ";
		$sqlMoji .= ", ShiteiVestOther ";
		$sqlMoji .= ", Answer ";
		$sqlMoji .= ", Answer489 ";
		$sqlMoji .= ", WEBRecept ";
		$sqlMoji .= ", YoteiPage ";
		$sqlMoji .= ", YobiSuu ";
		$sqlMoji .= ", KeijiSuu ";
		$sqlMoji .= ", Battery ";
		$sqlMoji .= ", BatterySuu ";
		$sqlMoji .= ", BukkenTourokuStatus ";

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
				$this->KojiCD,

				$this->BukkenCD,
				$this->ClientCD,
				$this->KojiName,
				$this->SekoShutai,
				$this->GyosyaTantoCD1,
				$this->SagyoTime,
				$this->SagyoDate,
				$this->GasKeihoRNFlg,
				$this->GasBaseFlg,
				$this->KasaiRNFlg,
				$this->KasaiChukeiRNFlg,
				$this->KasaiKyoyuRNFlg,
				$this->SonotaKojiFlg,
				$this->SonotaKojiNaiyo,
				$this->ZentaiStartDate,
				$this->ZentaiEndDate,
				$this->AnnaiDate,
				$this->ReceptionDate,
				$this->KakuteiDate,

				$this->KyoyoStartDate,
				$this->KyoyoEndDate,
				$this->SenyuStartDate,
				$this->SenyuEndDate,
				$this->YobiStartDate,
				$this->YobiEndDate,
				$this->Otherwise6,
				$this->Otherwise6Flg,
				$this->DateS6,
				$this->DateE6,
				$this->Otherwise7,
				$this->Otherwise7Flg,
				$this->DateS7,
				$this->DateE7,
				$this->Otherwise8,
				$this->Otherwise8Flg,
				$this->DateS8,
				$this->DateE8,
				$this->Otherwise9,
				$this->Otherwise9Flg,
				$this->DateS9,
				$this->DateE9,
				$this->Otherwise10,
				$this->Otherwise10Flg,
				$this->DateS10,
				$this->DateE10,

				$this->SanpaiKaishuDate,
				$this->SanpaiBiko,
				$this->SonotaKoji,
				$this->CurrentNyukan,
				$this->CurrentNyukanSonota,
				$this->RNNyukan,
				$this->RNNyukanSonota,
				$this->JikahoRendoFlg,
				$this->Jikaho,
				$this->ShizaiOkiba,
				$this->DoguOkiba,
				$this->SanpaiOkiba,
				$this->Kyukei,
				$this->Toilet,
				$this->Smoking,
				$this->RentKey,
				$this->Parking,
				$this->KeibiCompany,
				$this->KeibiCompanyTEL,
				$this->Hijyo,
				$this->HijyoIho,
				$this->HijyoIhoShubetu,
				$this->Gas,
				$this->GasIho,
				$this->GasIhoShubetu,
				$this->Kasai,
				$this->KasaiIho,
				$this->KasaiIhoShubetu,
				$this->Trouble,
				$this->TroubleIho,
				$this->TroubleIhoShubetu,
				$this->Bohan,
				$this->BohanIho,
				$this->BohanIhoShubetu,
				$this->Kanki,
				$this->KankiIho,
				$this->KankiIhoShubetu,
				$this->Sonota1,
				$this->Sonota1Iho,
				$this->Sonota1IhoShubetu,
				$this->Sonota2,
				$this->Sonota2Iho,
				$this->Sonota2IhoShubetu,
				$this->Sonota3,
				$this->Sonota3Iho,
				$this->Sonota3IhoShubetu,
				$this->Takuhai,
				$this->TakuhaiCompany,
				$this->TakuhaiCompanyTEL,
				$this->TakuhaiSetuzoku,
				$this->OP1DeviceCD,
				$this->OP2DeviceCD,
				$this->OP3DeviceCD,
				$this->OP4DeviceCD,
				$this->OP5DeviceCD,
				$this->OP6DeviceCD,
				$this->OP7DeviceCD,
				$this->OP8DeviceCD,
				$this->OP9DeviceCD,
				$this->OP10DeviceCD,
				$this->OPZiyuu1,
				$this->OPZiyuu2,
				$this->OPZiyuu3,
				$this->OPZiyuu4,
				$this->OPZiyuu5,
				$this->OPZiyuu6,
				$this->OPZiyuu7,
				$this->OPZiyuu8,
				$this->OPZiyuu9,
				$this->OPZiyuu10,
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
				$this->OPNotes,
				$this->SekoKyoyoKikiPic,
				$this->SekoSenyuKikiPic,
				$this->SekoHaisenPic,
				$this->AnshoNo,
				$this->AnshoNoKojichu,
				$this->PanelFlg,
				$this->OyaPanel,
				$this->KoPanel,
				$this->PanelGyosya,
				$this->GetHeyaNoFlg,
				$this->OrderKanriFutoFlg,

				$this->ShizaiOkibaFlg,
				$this->DoguOkibaFlg,
				$this->SanpaiOkibaFlg,
				$this->KyukeiFlg,
				$this->ToiletFlg,
				$this->SmokingFlg,
				$this->RentKeyFlg,
				$this->ParkingFlg,

				$this->WakuSu,
				$this->OPUketuke,
				$this->MaxWakuSu,
				$this->KojiTantoName,
				$this->KojiShozokuName,
				$this->KojiShozokuTEL,
				$this->ShoboTokurei,
				$this->ShoboSikenhoho,
				$this->GyosyaTantoCD2,
				$this->AnshoNoBiko,
				$this->SekoHaisenPicFlg,
				$this->SekoKyoyoKikiPicFlg,
				$this->SekoSenyuKikiPicFlg,
				$this->KeihoBiko,
				$this->SetubiBiko,
				$this->KojiBiko,

				$this->OPBiko1,
				$this->OPBiko2,
				$this->OPBiko3,
				$this->OPBiko4,
				$this->OPBiko5,
				$this->OPBiko6,
				$this->OPBiko7,
				$this->OPBiko8,
				$this->OPBiko9,
				$this->OPBiko10,
				$this->SiyobuzaiOyaFlg,
				$this->SiyobuzaiOyaBiko,
				$this->SiyobuzaiKoFlg,
				$this->SiyobuzaiKoBiko,
				$this->SiyobuzaiSyugenFlg,
				$this->SiyobuzaiSyugenBiko,
				$this->SiyobuzaiKanOyaFlg,
				$this->SiyobuzaiKanOyaBiko,
				$this->SiyobuzaiSeigyoFlg,
				$this->SiyobuzaiSeigyoBiko,

				$this->Hansu,
				$this->MinuteTime,
				$this->Kojijun,
				$this->FirstDateFeature,
				$this->Holiday1,
				$this->Holiday2,
				$this->Holiday3,
				$this->Holiday4,
				$this->WakuPattern,
				$this->Kirikaehoho,
				$this->KirikaeHeikoEizoriyo,
				$this->KasaiHeya,
				$this->KansenKoji,
				$this->GasKoji,
				$this->RosuiKoji,
				$this->Shiharai,
				$this->TagSuu,
				$this->AnzenHattyusya,
				$this->AnzenHattyusyaAddress,
				$this->AnzenHattyusyaTEL,
				$this->AnzenKojiNaiyo,
				$this->AnzenKojiName,
				$this->ConstTime,
				$this->BohanKoji,
				$this->OwnerTagSuu,
				$this->TagKirikae,
				$this->Kanrisitu,
				$this->KojiShijiBiko,
				$this->DispCompany1,
				$this->DispCompanyTEL1,
				$this->DispCompanySashi1,
				$this->DispCompanyToi1,
				$this->DispCompany2,
				$this->DispCompanyTEL2,
				$this->DispCompanySashi2,
				$this->DispCompanyToi2,
				$this->DispCompany3,
				$this->DispCompanyTEL3,
				$this->DispCompanySashi3,
				$this->DispCompanyToi3,
				$this->ToiawasewakiDisp,
				$this->DispCompanyTop,
				$this->Sagyoin,
				$this->MitumorishoNo,
				$this->ShiteiVestCD,
				$this->ShiteiVestOther,
				$this->Answer,
				$this->Answer489,
				$this->WEBRecept,
				$this->YoteiPage,
				$this->YobiSuu,
				$this->KeijiSuu,
				$this->Battery,
				$this->BatterySuu,
				$this->BukkenTourokuStatus,

				$this->MukouFlg,
				$this->Created,
				$this->Creator,
				$this->Updated,
				$this->Updater
			) = $this->myDB->fetchRow($this->tmprtn,$RowNo);


		   $this->BukkenCD = stripslashes($this->BukkenCD);
		   $this->ClientCD = stripslashes($this->ClientCD);
		   $this->KojiName = stripslashes($this->KojiName);
		   $this->SekoShutai = stripslashes($this->SekoShutai);
		   $this->GyosyaTantoCD1 = stripslashes($this->GyosyaTantoCD1);
		   $this->SagyoTime = stripslashes($this->SagyoTime);
		   $this->SagyoDate = stripslashes($this->SagyoDate);
		   $this->GasKeihoRNFlg = stripslashes($this->GasKeihoRNFlg);
		   $this->GasBaseFlg = stripslashes($this->GasBaseFlg);
		   $this->KasaiRNFlg = stripslashes($this->KasaiRNFlg);
		   $this->KasaiChukeiRNFlg = stripslashes($this->KasaiChukeiRNFlg);
		   $this->KasaiKyoyuRNFlg = stripslashes($this->KasaiKyoyuRNFlg);
		   $this->SonotaKojiFlg = stripslashes($this->SonotaKojiFlg);
		   $this->SonotaKojiNaiyo = stripslashes($this->SonotaKojiNaiyo);
		   $this->ZentaiStartDate = stripslashes($this->ZentaiStartDate);
		   $this->ZentaiEndDate = stripslashes($this->ZentaiEndDate);
		   $this->AnnaiDate = stripslashes($this->AnnaiDate);
		   $this->ReceptionDate = stripslashes($this->ReceptionDate);
		   $this->KakuteiDate = stripslashes($this->KakuteiDate);

		   $this->KyoyoStartDate = stripslashes($this->KyoyoStartDate);
		   $this->KyoyoEndDate = stripslashes($this->KyoyoEndDate);
		   $this->SenyuStartDate = stripslashes($this->SenyuStartDate);
		   $this->SenyuEndDate = stripslashes($this->SenyuEndDate);
		   $this->YobiStartDate = stripslashes($this->YobiStartDate);
		   $this->YobiEndDate = stripslashes($this->YobiEndDate);
		   $this->Otherwise6 = stripslashes($this->Otherwise6);
		   $this->Otherwise6Flg = stripslashes($this->Otherwise6Flg);
		   $this->DateS6 = stripslashes($this->DateS6);
		   $this->DateE6 = stripslashes($this->DateE6);
		   $this->Otherwise7 = stripslashes($this->Otherwise7);
		   $this->Otherwise7Flg = stripslashes($this->Otherwise7Flg);
		   $this->DateS7 = stripslashes($this->DateS7);
		   $this->DateE7 = stripslashes($this->DateE7);
		   $this->Otherwise8 = stripslashes($this->Otherwise8);
		   $this->Otherwise8Flg = stripslashes($this->Otherwise8Flg);
		   $this->DateS8 = stripslashes($this->DateS8);
		   $this->DateE8 = stripslashes($this->DateE8);
		   $this->Otherwise9 = stripslashes($this->Otherwise9);
		   $this->Otherwise9Flg = stripslashes($this->Otherwise9Flg);
		   $this->DateS9 = stripslashes($this->DateS9);
		   $this->DateE9 = stripslashes($this->DateE9);
		   $this->Otherwise10 = stripslashes($this->Otherwise10);
		   $this->Otherwise10Flg = stripslashes($this->Otherwise10Flg);
		   $this->DateS10 = stripslashes($this->DateS10);
		   $this->DateE10 = stripslashes($this->DateE10);

		   $this->SanpaiKaishuDate = stripslashes($this->SanpaiKaishuDate);
		   $this->SanpaiBiko = stripslashes($this->SanpaiBiko);
		   $this->SonotaKoji = stripslashes($this->SonotaKoji);
		   $this->CurrentNyukan = stripslashes($this->CurrentNyukan);
		   $this->CurrentNyukanSonota = stripslashes($this->CurrentNyukanSonota);
		   $this->RNNyukan = stripslashes($this->RNNyukan);
		   $this->RNNyukanSonota = stripslashes($this->RNNyukanSonota);
		   $this->JikahoRendoFlg = stripslashes($this->JikahoRendoFlg);
		   $this->Jikaho = stripslashes($this->Jikaho);
		   $this->ShizaiOkiba = stripslashes($this->ShizaiOkiba);
		   $this->DoguOkiba = stripslashes($this->DoguOkiba);
		   $this->SanpaiOkiba = stripslashes($this->SanpaiOkiba);
		   $this->Kyukei = stripslashes($this->Kyukei);
		   $this->Toilet = stripslashes($this->Toilet);
		   $this->Smoking = stripslashes($this->Smoking);
		   $this->RentKey = stripslashes($this->RentKey);
		   $this->Parking = stripslashes($this->Parking);
		   $this->KeibiCompany = stripslashes($this->KeibiCompany);
		   $this->KeibiCompanyTEL = stripslashes($this->KeibiCompanyTEL);
		   $this->Hijyo = stripslashes($this->Hijyo);
		   $this->HijyoIho = stripslashes($this->HijyoIho);
		   $this->HijyoIhoShubetu = stripslashes($this->HijyoIhoShubetu);
		   $this->Gas = stripslashes($this->Gas);
		   $this->GasIho = stripslashes($this->GasIho);
		   $this->GasIhoShubetu = stripslashes($this->GasIhoShubetu);
		   $this->Kasai = stripslashes($this->Kasai);
		   $this->KasaiIho = stripslashes($this->KasaiIho);
		   $this->KasaiIhoShubetu = stripslashes($this->KasaiIhoShubetu);
		   $this->Trouble = stripslashes($this->Trouble);
		   $this->TroubleIho = stripslashes($this->TroubleIho);
		   $this->TroubleIhoShubetu = stripslashes($this->TroubleIhoShubetu);
		   $this->Bohan = stripslashes($this->Bohan);
		   $this->BohanIho = stripslashes($this->BohanIho);
		   $this->BohanIhoShubetu = stripslashes($this->BohanIhoShubetu);
		   $this->Kanki = stripslashes($this->Kanki);
		   $this->KankiIho = stripslashes($this->KankiIho);
		   $this->KankiIhoShubetu = stripslashes($this->KankiIhoShubetu);
		   $this->Sonota1 = stripslashes($this->Sonota1);
		   $this->Sonota1Iho = stripslashes($this->Sonota1Iho);
		   $this->Sonota1IhoShubetu = stripslashes($this->Sonota1IhoShubetu);
		   $this->Sonota2 = stripslashes($this->Sonota2);
		   $this->Sonota2Iho = stripslashes($this->Sonota2Iho);
		   $this->Sonota2IhoShubetu = stripslashes($this->Sonota2IhoShubetu);
		   $this->Sonota3 = stripslashes($this->Sonota3);
		   $this->Sonota3Iho = stripslashes($this->Sonota3Iho);
		   $this->Sonota3IhoShubetu = stripslashes($this->Sonota3IhoShubetu);
		   $this->Takuhai = stripslashes($this->Takuhai);
		   $this->TakuhaiCompany = stripslashes($this->TakuhaiCompany);
		   $this->TakuhaiCompanyTEL = stripslashes($this->TakuhaiCompanyTEL);
		   $this->TakuhaiSetuzoku = stripslashes($this->TakuhaiSetuzoku);
		   $this->OP1DeviceCD = stripslashes($this->OP1DeviceCD);
		   $this->OP2DeviceCD = stripslashes($this->OP2DeviceCD);
		   $this->OP3DeviceCD = stripslashes($this->OP3DeviceCD);
		   $this->OP4DeviceCD = stripslashes($this->OP4DeviceCD);
		   $this->OP5DeviceCD = stripslashes($this->OP5DeviceCD);
		   $this->OP6DeviceCD = stripslashes($this->OP6DeviceCD);
		   $this->OP7DeviceCD = stripslashes($this->OP7DeviceCD);
		   $this->OP8DeviceCD = stripslashes($this->OP8DeviceCD);
		   $this->OP9DeviceCD = stripslashes($this->OP9DeviceCD);
		   $this->OP10DeviceCD = stripslashes($this->OP10DeviceCD);
		   $this->OPZiyuu1 = stripslashes($this->OPZiyuu1);
		   $this->OPZiyuu2 = stripslashes($this->OPZiyuu2);
		   $this->OPZiyuu3 = stripslashes($this->OPZiyuu3);
		   $this->OPZiyuu4 = stripslashes($this->OPZiyuu4);
		   $this->OPZiyuu5 = stripslashes($this->OPZiyuu5);
		   $this->OPZiyuu6 = stripslashes($this->OPZiyuu6);
		   $this->OPZiyuu7 = stripslashes($this->OPZiyuu7);
		   $this->OPZiyuu8 = stripslashes($this->OPZiyuu8);
		   $this->OPZiyuu9 = stripslashes($this->OPZiyuu9);
		   $this->OPZiyuu10 = stripslashes($this->OPZiyuu10);
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
		   $this->OPNotes = stripslashes($this->OPNotes);
		   $this->SekoKyoyoKikiPic = stripslashes($this->SekoKyoyoKikiPic);
		   $this->SekoSenyuKikiPic = stripslashes($this->SekoSenyuKikiPic);
		   $this->SekoHaisenPic = stripslashes($this->SekoHaisenPic);
		   $this->AnshoNo = stripslashes($this->AnshoNo);
		   $this->AnshoNoKojichu = stripslashes($this->AnshoNoKojichu);
		   $this->PanelFlg = stripslashes($this->PanelFlg);
		   $this->OyaPanel = stripslashes($this->OyaPanel);
		   $this->KoPanel = stripslashes($this->KoPanel);
		   $this->PanelGyosya = stripslashes($this->PanelGyosya);
		   $this->GetHeyaNoFlg = stripslashes($this->GetHeyaNoFlg);
		   $this->OrderKanriFutoFlg = stripslashes($this->OrderKanriFutoFlg);

		   $this->ShizaiOkibaFlg = stripslashes($this->ShizaiOkibaFlg);
		   $this->DoguOkibaFlg = stripslashes($this->DoguOkibaFlg);
		   $this->SanpaiOkibaFlg = stripslashes($this->SanpaiOkibaFlg);
		   $this->KyukeiFlg = stripslashes($this->KyukeiFlg);
		   $this->ToiletFlg = stripslashes($this->ToiletFlg);
		   $this->SmokingFlg = stripslashes($this->SmokingFlg);
		   $this->RentKeyFlg = stripslashes($this->RentKeyFlg);
		   $this->ParkingFlg = stripslashes($this->ParkingFlg);

		   $this->WakuSu = stripslashes($this->WakuSu);
		   $this->OPUketuke = stripslashes($this->OPUketuke);
		   $this->MaxWakuSu = stripslashes($this->MaxWakuSu);
		   $this->KojiTantoName = stripslashes($this->KojiTantoName);
		   $this->KojiShozokuName = stripslashes($this->KojiShozokuName);
		   $this->KojiShozokuTEL = stripslashes($this->KojiShozokuTEL);
		   $this->ShoboTokurei = stripslashes($this->ShoboTokurei);
		   $this->ShoboSikenhoho = stripslashes($this->ShoboSikenhoho);

		   
		   $this->GyosyaTantoCD2 = stripslashes($this->GyosyaTantoCD2);
		   $this->AnshoNoBiko = stripslashes($this->AnshoNoBiko);
		   $this->SekoHaisenPicFlg = stripslashes($this->SekoHaisenPicFlg);
		   $this->SekoKyoyoKikiPicFlg = stripslashes($this->SekoKyoyoKikiPicFlg);
		   $this->SekoSenyuKikiPicFlg = stripslashes($this->SekoSenyuKikiPicFlg);
		   $this->KeihoBiko = stripslashes($this->KeihoBiko);
		   $this->SetubiBiko = stripslashes($this->SetubiBiko);
		   $this->KojiBiko = stripslashes($this->KojiBiko);

		   $this->OPBiko1 = stripslashes($this->OPBiko1);
		   $this->OPBiko2 = stripslashes($this->OPBiko2);
		   $this->OPBiko3 = stripslashes($this->OPBiko3);
		   $this->OPBiko4 = stripslashes($this->OPBiko4);
		   $this->OPBiko5 = stripslashes($this->OPBiko5);
		   $this->OPBiko6 = stripslashes($this->OPBiko6);
		   $this->OPBiko7 = stripslashes($this->OPBiko7);
		   $this->OPBiko8 = stripslashes($this->OPBiko8);
		   $this->OPBiko9 = stripslashes($this->OPBiko9);
		   $this->OPBiko10 = stripslashes($this->OPBiko10);
		   $this->SiyobuzaiOyaFlg = stripslashes($this->SiyobuzaiOyaFlg);
		   $this->SiyobuzaiOyaBiko = stripslashes($this->SiyobuzaiOyaBiko);
		   $this->SiyobuzaiKoFlg = stripslashes($this->SiyobuzaiKoFlg);
		   $this->SiyobuzaiKoBiko = stripslashes($this->SiyobuzaiKoBiko);
		   $this->SiyobuzaiSyugenFlg = stripslashes($this->SiyobuzaiSyugenFlg);
		   $this->SiyobuzaiSyugenBiko = stripslashes($this->SiyobuzaiSyugenBiko);
		   $this->SiyobuzaiKanOyaFlg = stripslashes($this->SiyobuzaiKanOyaFlg);
		   $this->SiyobuzaiKanOyaBiko = stripslashes($this->SiyobuzaiKanOyaBiko);
		   $this->SiyobuzaiSeigyoFlg = stripslashes($this->SiyobuzaiSeigyoFlg);
		   $this->SiyobuzaiSeigyoBiko = stripslashes($this->SiyobuzaiSeigyoBiko);

		   $this->Hansu = stripslashes($this->Hansu);
		   $this->MinuteTime = stripslashes($this->MinuteTime);
		   $this->Kojijun = stripslashes($this->Kojijun);
		   $this->FirstDateFeature = stripslashes($this->FirstDateFeature);
		   $this->Holiday1 = stripslashes($this->Holiday1);
		   $this->Holiday2 = stripslashes($this->Holiday2);
		   $this->Holiday3 = stripslashes($this->Holiday3);
		   $this->Holiday4 = stripslashes($this->Holiday4);
		   $this->WakuPattern = stripslashes($this->WakuPattern);
		   $this->Kirikaehoho = stripslashes($this->Kirikaehoho);
		   $this->KirikaeHeikoEizoriyo = stripslashes($this->KirikaeHeikoEizoriyo);
		   $this->KasaiHeya = stripslashes($this->KasaiHeya);
		   $this->KansenKoji = stripslashes($this->KansenKoji);
		   $this->GasKoji = stripslashes($this->GasKoji);
		   $this->RosuiKoji = stripslashes($this->RosuiKoji);
		   $this->Shiharai = stripslashes($this->Shiharai);
		   $this->TagSuu = stripslashes($this->TagSuu);
		   $this->AnzenHattyusya = stripslashes($this->AnzenHattyusya);
		   $this->AnzenHattyusyaAddress = stripslashes($this->AnzenHattyusyaAddress);
		   $this->AnzenHattyusyaTEL = stripslashes($this->AnzenHattyusyaTEL);
		   $this->AnzenKojiNaiyo = stripslashes($this->AnzenKojiNaiyo);
		   $this->AnzenKojiName = stripslashes($this->AnzenKojiName);
		   $this->ConstTime = stripslashes($this->ConstTime);
		   $this->BohanKoji = stripslashes($this->BohanKoji);
		   $this->OwnerTagSuu = stripslashes($this->OwnerTagSuu);
		   $this->TagKirikae = stripslashes($this->TagKirikae);
		   $this->Kanrisitu = stripslashes($this->Kanrisitu);
		   $this->KojiShijiBiko = stripslashes($this->KojiShijiBiko);
			$this->DispCompany1 = stripslashes($this->DispCompany1);
			$this->DispCompanyTEL1 = stripslashes($this->DispCompanyTEL1);
			$this->DispCompanySashi1 = stripslashes($this->DispCompanySashi1);
			$this->DispCompanyToi1 = stripslashes($this->DispCompanyToi1);
			$this->DispCompany2 = stripslashes($this->DispCompany2);
			$this->DispCompanyTEL2 = stripslashes($this->DispCompanyTEL2);
			$this->DispCompanySashi2 = stripslashes($this->DispCompanySashi2);
			$this->DispCompanyToi2 = stripslashes($this->DispCompanyToi2);
			$this->DispCompany3 = stripslashes($this->DispCompany3);
			$this->DispCompanyTEL3 = stripslashes($this->DispCompanyTEL3);
			$this->DispCompanySashi3 = stripslashes($this->DispCompanySashi3);
			$this->DispCompanyToi3 = stripslashes($this->DispCompanyToi3);
			$this->ToiawasewakiDisp = stripslashes($this->ToiawasewakiDisp);
			$this->DispCompanyTop = stripslashes($this->DispCompanyTop);
			$this->Sagyoin = stripslashes($this->Sagyoin);
			$this->MitumorishoNo = stripslashes($this->MitumorishoNo);
			$this->ShiteiVestCD = stripslashes($this->ShiteiVestCD);
			$this->ShiteiVestOther = stripslashes($this->ShiteiVestOther);
			$this->Answer = stripslashes($this->Answer);
			$this->Answer489 = stripslashes($this->Answer489);
			$this->WEBRecept = stripslashes($this->WEBRecept);
			$this->YoteiPage = stripslashes($this->YoteiPage);
			$this->YobiSuu = stripslashes($this->YobiSuu);
			$this->KeijiSuu = stripslashes($this->KeijiSuu);
			$this->Battery = stripslashes($this->Battery);
			$this->BatterySuu = stripslashes($this->BatterySuu);
			$this->BukkenTourokuStatus = stripslashes($this->BukkenTourokuStatus);

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
		
		if ($this->KojiCD < 0) {
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
					$sqlMoji = "select KojiCD from $this->TableName order by KojiCD desc limit 1";
					$maxCD = $this->myDB->getOneValue($sqlMoji);
					$maxCD = ($maxCD == -1) ? 0 : $maxCD;
					$maxCD++;

					// step 4. インサート
					$sqlMoji = "Insert into $this->TableName (";
					$sqlMoji .= "KojiCD";
	
					if ($this->BukkenCD != "") $sqlMoji .= ", BukkenCD";
					if ($this->ClientCD != "") $sqlMoji .= ", ClientCD";
					if ($this->KojiName != "") $sqlMoji .= ", KojiName";
					if ($this->SekoShutai != "") $sqlMoji .= ", SekoShutai";
					if ($this->GyosyaTantoCD1 != "") $sqlMoji .= ", GyosyaTantoCD1";
					if ($this->SagyoTime != "") $sqlMoji .= ", SagyoTime";
					if ($this->SagyoDate != "") $sqlMoji .= ", SagyoDate";
					if ($this->GasKeihoRNFlg != "") $sqlMoji .= ", GasKeihoRNFlg";
					if ($this->GasBaseFlg != "") $sqlMoji .= ", GasBaseFlg";
					if ($this->KasaiRNFlg != "") $sqlMoji .= ", KasaiRNFlg";
					if ($this->KasaiChukeiRNFlg != "") $sqlMoji .= ", KasaiChukeiRNFlg";
					if ($this->KasaiKyoyuRNFlg != "") $sqlMoji .= ", KasaiKyoyuRNFlg";
					if ($this->SonotaKojiFlg != "") $sqlMoji .= ", SonotaKojiFlg";
					if ($this->SonotaKojiNaiyo != "") $sqlMoji .= ", SonotaKojiNaiyo";
					if ($this->ZentaiStartDate != "") $sqlMoji .= ", ZentaiStartDate";
					if ($this->ZentaiEndDate != "") $sqlMoji .= ", ZentaiEndDate";
					if ($this->AnnaiDate != "") $sqlMoji .= ", AnnaiDate";
					if ($this->ReceptionDate != "") $sqlMoji .= ", ReceptionDate";
					if ($this->KakuteiDate != "") $sqlMoji .= ", KakuteiDate";

					if ($this->KyoyoStartDate != "") $sqlMoji .= ", KyoyoStartDate";
					if ($this->KyoyoEndDate != "") $sqlMoji .= ", KyoyoEndDate";
					if ($this->SenyuStartDate != "") $sqlMoji .= ", SenyuStartDate";
					if ($this->SenyuEndDate != "") $sqlMoji .= ", SenyuEndDate";
					if ($this->YobiStartDate != "") $sqlMoji .= ", YobiStartDate";
					if ($this->YobiEndDate != "") $sqlMoji .= ", YobiEndDate";
					if ($this->Otherwise6 != "") $sqlMoji .= ", Otherwise6";
					if ($this->Otherwise6Flg != "") $sqlMoji .= ", Otherwise6Flg";
					if ($this->DateS6 != "") $sqlMoji .= ", DateS6";
					if ($this->DateE6 != "") $sqlMoji .= ", DateE6";
					if ($this->Otherwise7 != "") $sqlMoji .= ", Otherwise7";
					if ($this->Otherwise7 != "") $sqlMoji .= ", Otherwise7Flg";
					if ($this->DateS7 != "") $sqlMoji .= ", DateS7";
					if ($this->DateE7 != "") $sqlMoji .= ", DateE7";
					if ($this->Otherwise8 != "") $sqlMoji .= ", Otherwise8";
					if ($this->Otherwise8Flg != "") $sqlMoji .= ", Otherwise8Flg";
					if ($this->DateS8 != "") $sqlMoji .= ", DateS8";
					if ($this->DateE8 != "") $sqlMoji .= ", DateE8";
					if ($this->Otherwise9 != "") $sqlMoji .= ", Otherwise9";
					if ($this->Otherwise9Flg != "") $sqlMoji .= ", Otherwise9Flg";
					if ($this->DateS9 != "") $sqlMoji .= ", DateS9";
					if ($this->DateE9 != "") $sqlMoji .= ", DateE9";
					if ($this->Otherwise10 != "") $sqlMoji .= ", Otherwise10";
					if ($this->Otherwise10Flg != "") $sqlMoji .= ", Otherwise10Flg";
					if ($this->DateS10 != "") $sqlMoji .= ", DateS10";
					if ($this->DateE10 != "") $sqlMoji .= ", DateE10";

					if ($this->SanpaiKaishuDate != "") $sqlMoji .= ", SanpaiKaishuDate";
					if ($this->SanpaiBiko != "") $sqlMoji .= ", SanpaiBiko";
					if ($this->SonotaKoji != "") $sqlMoji .= ", SonotaKoji";
					if ($this->CurrentNyukan != "") $sqlMoji .= ", CurrentNyukan";
					if ($this->CurrentNyukanSonota != "") $sqlMoji .= ", CurrentNyukanSonota";
					if ($this->RNNyukan != "") $sqlMoji .= ", RNNyukan";
					if ($this->RNNyukanSonota != "") $sqlMoji .= ", RNNyukanSonota";
					if ($this->JikahoRendoFlg != "") $sqlMoji .= ", JikahoRendoFlg";
					if ($this->Jikaho != "") $sqlMoji .= ", Jikaho";
					if ($this->ShizaiOkiba != "") $sqlMoji .= ", ShizaiOkiba";
					if ($this->DoguOkiba != "") $sqlMoji .= ", DoguOkiba";
					if ($this->SanpaiOkiba != "") $sqlMoji .= ", SanpaiOkiba";
					if ($this->Kyukei != "") $sqlMoji .= ", Kyukei";
					if ($this->Toilet != "") $sqlMoji .= ", Toilet";
					if ($this->Smoking != "") $sqlMoji .= ", Smoking";
					if ($this->RentKey != "") $sqlMoji .= ", RentKey";
					if ($this->Parking != "") $sqlMoji .= ", Parking";
					if ($this->KeibiCompany != "") $sqlMoji .= ", KeibiCompany";
					if ($this->KeibiCompanyTEL != "") $sqlMoji .= ", KeibiCompanyTEL";
					if ($this->Hijyo != "") $sqlMoji .= ", Hijyo";
					if ($this->HijyoIho != "") $sqlMoji .= ", HijyoIho";
					if ($this->HijyoIhoShubetu != "") $sqlMoji .= ", HijyoIhoShubetu";
					if ($this->Gas != "") $sqlMoji .= ", Gas";
					if ($this->GasIho != "") $sqlMoji .= ", GasIho";
					if ($this->GasIhoShubetu != "") $sqlMoji .= ", GasIhoShubetu";
					if ($this->Kasai != "") $sqlMoji .= ", Kasai";
					if ($this->KasaiIho != "") $sqlMoji .= ", KasaiIho";
					if ($this->KasaiIhoShubetu != "") $sqlMoji .= ", KasaiIhoShubetu";
					if ($this->Trouble != "") $sqlMoji .= ", Trouble";
					if ($this->TroubleIho != "") $sqlMoji .= ", TroubleIho";
					if ($this->TroubleIhoShubetu != "") $sqlMoji .= ", TroubleIhoShubetu";
					if ($this->Bohan != "") $sqlMoji .= ", Bohan";
					if ($this->BohanIho != "") $sqlMoji .= ", BohanIho";
					if ($this->BohanIhoShubetu != "") $sqlMoji .= ", BohanIhoShubetu";
					if ($this->Kanki != "") $sqlMoji .= ", Kanki";
					if ($this->KankiIho != "") $sqlMoji .= ", KankiIho";
					if ($this->KankiIhoShubetu != "") $sqlMoji .= ", KankiIhoShubetu";
					if ($this->Sonota1 != "") $sqlMoji .= ", Sonota1";
					if ($this->Sonota1Iho != "") $sqlMoji .= ", Sonota1Iho";
					if ($this->Sonota1IhoShubetu != "") $sqlMoji .= ", Sonota1IhoShubetu";
					if ($this->Sonota2 != "") $sqlMoji .= ", Sonota2";
					if ($this->Sonota2Iho != "") $sqlMoji .= ", Sonota2Iho";
					if ($this->Sonota2IhoShubetu != "") $sqlMoji .= ", Sonota2IhoShubetu";
					if ($this->Sonota3 != "") $sqlMoji .= ", Sonota3";
					if ($this->Sonota3Iho != "") $sqlMoji .= ", Sonota3Iho";
					if ($this->Sonota3IhoShubetu != "") $sqlMoji .= ", Sonota3IhoShubetu";
					if ($this->Takuhai != "") $sqlMoji .= ", Takuhai";
					if ($this->TakuhaiCompany != "") $sqlMoji .= ", TakuhaiCompany";
					if ($this->TakuhaiCompanyTEL != "") $sqlMoji .= ", TakuhaiCompanyTEL";
					if ($this->TakuhaiSetuzoku != "") $sqlMoji .= ", TakuhaiSetuzoku";
					if ($this->OP1DeviceCD != "") $sqlMoji .= ", OP1DeviceCD";
					if ($this->OP2DeviceCD != "") $sqlMoji .= ", OP2DeviceCD";
					if ($this->OP3DeviceCD != "") $sqlMoji .= ", OP3DeviceCD";
					if ($this->OP4DeviceCD != "") $sqlMoji .= ", OP4DeviceCD";
					if ($this->OP5DeviceCD != "") $sqlMoji .= ", OP5DeviceCD";
					if ($this->OP6DeviceCD != "") $sqlMoji .= ", OP6DeviceCD";
					if ($this->OP7DeviceCD != "") $sqlMoji .= ", OP7DeviceCD";
					if ($this->OP8DeviceCD != "") $sqlMoji .= ", OP8DeviceCD";
					if ($this->OP9DeviceCD != "") $sqlMoji .= ", OP9DeviceCD";
					if ($this->OP10DeviceCD != "") $sqlMoji .= ", OP10DeviceCD";
					if ($this->OPZiyuu1 != "") $sqlMoji .= ", OPZiyuu1";
					if ($this->OPZiyuu2 != "") $sqlMoji .= ", OPZiyuu2";
					if ($this->OPZiyuu3 != "") $sqlMoji .= ", OPZiyuu3";
					if ($this->OPZiyuu4 != "") $sqlMoji .= ", OPZiyuu4";
					if ($this->OPZiyuu5 != "") $sqlMoji .= ", OPZiyuu5";
					if ($this->OPZiyuu6 != "") $sqlMoji .= ", OPZiyuu6";
					if ($this->OPZiyuu7 != "") $sqlMoji .= ", OPZiyuu7";
					if ($this->OPZiyuu8 != "") $sqlMoji .= ", OPZiyuu8";
					if ($this->OPZiyuu9 != "") $sqlMoji .= ", OPZiyuu9";
					if ($this->OPZiyuu10 != "") $sqlMoji .= ", OPZiyuu10";
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
					if ($this->OPNotes != "") $sqlMoji .= ", OPNotes";
					if ($this->SekoKyoyoKikiPic != "") $sqlMoji .= ", SekoKyoyoKikiPic";
					if ($this->SekoSenyuKikiPic != "") $sqlMoji .= ", SekoSenyuKikiPic";
					if ($this->SekoHaisenPic != "") $sqlMoji .= ", SekoHaisenPic";
					if ($this->AnshoNo != "") $sqlMoji .= ", AnshoNo";
					if ($this->AnshoNoKojichu != "") $sqlMoji .= ", AnshoNoKojichu";
					if ($this->PanelFlg != "") $sqlMoji .= ", PanelFlg";
					if ($this->OyaPanel != "") $sqlMoji .= ", OyaPanel";
					if ($this->KoPanel != "") $sqlMoji .= ", KoPanel";
					if ($this->PanelGyosya != "") $sqlMoji .= ", PanelGyosya";
					if ($this->GetHeyaNoFlg != "") $sqlMoji .= ", GetHeyaNoFlg";
					if ($this->OrderKanriFutoFlg != "") $sqlMoji .= ", OrderKanriFutoFlg";

					if ($this->ShizaiOkibaFlg != "") $sqlMoji .= ", ShizaiOkibaFlg";
					if ($this->DoguOkibaFlg != "") $sqlMoji .= ", DoguOkibaFlg";
					if ($this->SanpaiOkibaFlg != "") $sqlMoji .= ", SanpaiOkibaFlg";
					if ($this->KyukeiFlg != "") $sqlMoji .= ", KyukeiFlg";
					if ($this->ToiletFlg != "") $sqlMoji .= ", ToiletFlg";
					if ($this->SmokingFlg != "") $sqlMoji .= ", SmokingFlg";
					if ($this->RentKeyFlg != "") $sqlMoji .= ", RentKeyFlg";
					if ($this->ParkingFlg != "") $sqlMoji .= ", ParkingFlg";

					if ($this->WakuSu != "") $sqlMoji .= ", WakuSu";
					if ($this->OPUketuke != "") $sqlMoji .= ", OPUketuke";
					if ($this->MaxWakuSu != "") $sqlMoji .= ", MaxWakuSu";
					if ($this->KojiTantoName != "") $sqlMoji .= ", KojiTantoName";
					if ($this->KojiShozokuName != "") $sqlMoji .= ", KojiShozokuName";
					if ($this->KojiShozokuTEL != "") $sqlMoji .= ", KojiShozokuTEL";
					if ($this->ShoboTokurei != "") $sqlMoji .= ", ShoboTokurei";
					if ($this->ShoboSikenhoho != "") $sqlMoji .= ", ShoboSikenhoho";
					if ($this->GyosyaTantoCD2 != "") $sqlMoji .= ", GyosyaTantoCD2";
					if ($this->AnshoNoBiko != "") $sqlMoji .= ", AnshoNoBiko";
					if ($this->SekoHaisenPicFlg != "") $sqlMoji .= ", SekoHaisenPicFlg";
					if ($this->SekoKyoyoKikiPicFlg != "") $sqlMoji .= ", SekoKyoyoKikiPicFlg";
					if ($this->SekoSenyuKikiPicFlg != "") $sqlMoji .= ", SekoSenyuKikiPicFlg";
					if ($this->KeihoBiko != "") $sqlMoji .= ", KeihoBiko";
					if ($this->SetubiBiko != "") $sqlMoji .= ", SetubiBiko";
					if ($this->KojiBiko != "") $sqlMoji .= ", KojiBiko";


					if ($this->OPBiko1 != "") $sqlMoji .= ", OPBiko1";
					if ($this->OPBiko2 != "") $sqlMoji .= ", OPBiko2";
					if ($this->OPBiko3 != "") $sqlMoji .= ", OPBiko3";
					if ($this->OPBiko4 != "") $sqlMoji .= ", OPBiko4";
					if ($this->OPBiko5 != "") $sqlMoji .= ", OPBiko5";
					if ($this->OPBiko6 != "") $sqlMoji .= ", OPBiko6";
					if ($this->OPBiko7 != "") $sqlMoji .= ", OPBiko7";
					if ($this->OPBiko8 != "") $sqlMoji .= ", OPBiko8";
					if ($this->OPBiko9 != "") $sqlMoji .= ", OPBiko9";
					if ($this->OPBiko10 != "") $sqlMoji .= ", OPBiko10";
					if ($this->SiyobuzaiOyaFlg != "") $sqlMoji .= ", SiyobuzaiOyaFlg";
					if ($this->SiyobuzaiOyaBiko != "") $sqlMoji .= ", SiyobuzaiOyaBiko";
					if ($this->SiyobuzaiKoFlg != "") $sqlMoji .= ", SiyobuzaiKoFlg";
					if ($this->SiyobuzaiKoBiko != "") $sqlMoji .= ", SiyobuzaiKoBiko";
					if ($this->SiyobuzaiSyugenFlg != "") $sqlMoji .= ", SiyobuzaiSyugenFlg";
					if ($this->SiyobuzaiSyugenBiko != "") $sqlMoji .= ", SiyobuzaiSyugenBiko";
					if ($this->SiyobuzaiKanOyaFlg != "") $sqlMoji .= ", SiyobuzaiKanOyaFlg";
					if ($this->SiyobuzaiKanOyaBiko != "") $sqlMoji .= ", SiyobuzaiKanOyaBiko";
					if ($this->SiyobuzaiSeigyoFlg != "") $sqlMoji .= ", SiyobuzaiSeigyoFlg";
					if ($this->SiyobuzaiSeigyoBiko != "") $sqlMoji .= ", SiyobuzaiSeigyoBiko";

					if ($this->Hansu != "") $sqlMoji .= ", Hansu";
					if ($this->MinuteTime != "") $sqlMoji .= ", MinuteTime";
					if ($this->Kojijun != "") $sqlMoji .= ", Kojijun";
					if ($this->FirstDateFeature != "") $sqlMoji .= ", FirstDateFeature";
					if ($this->Holiday1 != "") $sqlMoji .= ", Holiday1";
					if ($this->Holiday2 != "") $sqlMoji .= ", Holiday2";
					if ($this->Holiday3 != "") $sqlMoji .= ", Holiday3";
					if ($this->Holiday4 != "") $sqlMoji .= ", Holiday4";
					if ($this->WakuPattern != "") $sqlMoji .= ", WakuPattern";
					if ($this->Kirikaehoho != "") $sqlMoji .= ", Kirikaehoho";
					if ($this->KirikaeHeikoEizoriyo != "") $sqlMoji .= ", KirikaeHeikoEizoriyo";
					if ($this->KasaiHeya != "") $sqlMoji .= ", KasaiHeya";
					if ($this->KansenKoji != "") $sqlMoji .= ", KansenKoji";
					if ($this->GasKoji != "") $sqlMoji .= ", GasKoji";
					if ($this->RosuiKoji != "") $sqlMoji .= ", RosuiKoji";
					if ($this->Shiharai != "") $sqlMoji .= ", Shiharai";
					if ($this->TagSuu != "") $sqlMoji .= ", TagSuu";
					if ($this->AnzenHattyusya != "") $sqlMoji .= ", AnzenHattyusya";
					if ($this->AnzenHattyusyaAddress != "") $sqlMoji .= ", AnzenHattyusyaAddress";
					if ($this->AnzenHattyusyaTEL != "") $sqlMoji .= ", AnzenHattyusyaTEL";
					if ($this->AnzenKojiNaiyo != "") $sqlMoji .= ", AnzenKojiNaiyo";
					if ($this->AnzenKojiName != "") $sqlMoji .= ", AnzenKojiName";
					if ($this->ConstTime != "") $sqlMoji .= ", ConstTime";
					if ($this->BohanKoji != "") $sqlMoji .= ", BohanKoji";
					if ($this->OwnerTagSuu != "") $sqlMoji .= ", OwnerTagSuu";
					if ($this->TagKirikae != "") $sqlMoji .= ", TagKirikae";
					if ($this->Kanrisitu != "") $sqlMoji .= ", Kanrisitu";
					if ($this->KojiShijiBiko != "") $sqlMoji .= ", KojiShijiBiko";
					if ($this->DispCompany1 != "") $sqlMoji .= ", DispCompany1";
					if ($this->DispCompanyTEL1 != "") $sqlMoji .= ", DispCompanyTEL1";
					if ($this->DispCompanySashi1 != "") $sqlMoji .= ", DispCompanySashi1";
					if ($this->DispCompanyToi1 != "") $sqlMoji .= ", DispCompanyToi1";
					if ($this->DispCompany2 != "") $sqlMoji .= ", DispCompany2";
					if ($this->DispCompanyTEL2 != "") $sqlMoji .= ", DispCompanyTEL2";
					if ($this->DispCompanySashi2 != "") $sqlMoji .= ", DispCompanySashi2";
					if ($this->DispCompanyToi2 != "") $sqlMoji .= ", DispCompanyToi2";
					if ($this->DispCompany3 != "") $sqlMoji .= ", DispCompany3";
					if ($this->DispCompanyTEL3 != "") $sqlMoji .= ", DispCompanyTEL3";
					if ($this->DispCompanySashi3 != "") $sqlMoji .= ", DispCompanySashi3";
					if ($this->DispCompanyToi3 != "") $sqlMoji .= ", DispCompanyToi3";
					if ($this->ToiawasewakiDisp != "") $sqlMoji .= ", ToiawasewakiDisp";
					if ($this->DispCompanyTop != "") $sqlMoji .= ", DispCompanyTop";
					if ($this->Sagyoin != "") $sqlMoji .= ", Sagyoin";
					if ($this->MitumorishoNo != "") $sqlMoji .= ", MitumorishoNo";
					if ($this->ShiteiVestCD != "") $sqlMoji .= ", ShiteiVestCD";
					if ($this->ShiteiVestOther != "") $sqlMoji .= ", ShiteiVestOther";
					if ($this->Answer != "") $sqlMoji .= ", Answer";
					if ($this->Answer489 != "") $sqlMoji .= ", Answer489";
					if ($this->WEBRecept != "") $sqlMoji .= ", WEBRecept";
					if ($this->YoteiPage != "") $sqlMoji .= ", YoteiPage";
					if ($this->YobiSuu != "") $sqlMoji .= ", YobiSuu";
					if ($this->KeijiSuu != "") $sqlMoji .= ", KeijiSuu";
					if ($this->Battery != "") $sqlMoji .= ", Battery";
					if ($this->BatterySuu != "") $sqlMoji .= ", BatterySuu";
					if ($this->BukkenTourokuStatus != "") $sqlMoji .= ", BukkenTourokuStatus";

					if ($this->MukouFlg != "") $sqlMoji .= ", MukouFlg";
					$sqlMoji .= ", Created";
					if ($this->Creator != "") $sqlMoji .= ", Creator";
					$sqlMoji .= ", Updated";
					if ($this->Updater != "") $sqlMoji .= ", Updater";
					$sqlMoji .= ") values (";
					$sqlMoji .= "$maxCD";

					if ($this->BukkenCD != "") $sqlMoji .= ", '" . addslashes($this->BukkenCD) . "'";
					if ($this->ClientCD != "") $sqlMoji .= ", '" . addslashes($this->ClientCD) . "'";
					if ($this->KojiName != "") $sqlMoji .= ", '" . addslashes($this->KojiName) . "'";
					if ($this->SekoShutai != "") $sqlMoji .= ", '" . addslashes($this->SekoShutai) . "'";
					if ($this->GyosyaTantoCD1 != "") $sqlMoji .= ", '" . addslashes($this->GyosyaTantoCD1) . "'";
					if ($this->SagyoTime != "") $sqlMoji .= ", '" . addslashes($this->SagyoTime) . "'";
					if ($this->SagyoDate != "") $sqlMoji .= ", '" . addslashes($this->SagyoDate) . "'";
					if ($this->GasKeihoRNFlg != "") $sqlMoji .= ", '" . addslashes($this->GasKeihoRNFlg) . "'";
					if ($this->GasBaseFlg != "") $sqlMoji .= ", '" . addslashes($this->GasBaseFlg) . "'";
					if ($this->KasaiRNFlg != "") $sqlMoji .= ", '" . addslashes($this->KasaiRNFlg) . "'";
					if ($this->KasaiChukeiRNFlg != "") $sqlMoji .= ", '" . addslashes($this->KasaiChukeiRNFlg) . "'";
					if ($this->KasaiKyoyuRNFlg != "") $sqlMoji .= ", '" . addslashes($this->KasaiKyoyuRNFlg) . "'";
					if ($this->SonotaKojiFlg != "") $sqlMoji .= ", '" . addslashes($this->SonotaKojiFlg) . "'";
					if ($this->SonotaKojiNaiyo != "") $sqlMoji .= ", '" . addslashes($this->SonotaKojiNaiyo) . "'";
					if ($this->ZentaiStartDate != "") $sqlMoji .= ", '" . addslashes($this->ZentaiStartDate) . "'";
					if ($this->ZentaiEndDate != "") $sqlMoji .= ", '" . addslashes($this->ZentaiEndDate) . "'";
					if ($this->AnnaiDate != "") $sqlMoji .= ", '" . addslashes($this->AnnaiDate) . "'";
					if ($this->ReceptionDate != "") $sqlMoji .= ", '" . addslashes($this->ReceptionDate) . "'";
					if ($this->KakuteiDate != "") $sqlMoji .= ", '" . addslashes($this->KakuteiDate) . "'";

					if ($this->KyoyoStartDate != "") $sqlMoji .= ", '" . addslashes($this->KyoyoStartDate) . "'";
					if ($this->KyoyoEndDate != "") $sqlMoji .= ", '" . addslashes($this->KyoyoEndDate) . "'";
					if ($this->SenyuStartDate != "") $sqlMoji .= ", '" . addslashes($this->SenyuStartDate) . "'";
					if ($this->SenyuEndDate != "") $sqlMoji .= ", '" . addslashes($this->SenyuEndDate) . "'";
					if ($this->YobiStartDate != "") $sqlMoji .= ", '" . addslashes($this->YobiStartDate) . "'";
					if ($this->YobiEndDate != "") $sqlMoji .= ", '" . addslashes($this->YobiEndDate) . "'";
					if ($this->Otherwise6 != "") $sqlMoji .= ", '" . addslashes($this->Otherwise6) . "'";
					if ($this->Otherwise6Flg != "") $sqlMoji .= ", '" . addslashes($this->Otherwise6Flg) . "'";
					if ($this->DateS6 != "") $sqlMoji .= ", '" . addslashes($this->DateS6) . "'";
					if ($this->DateE6 != "") $sqlMoji .= ", '" . addslashes($this->DateE6) . "'";
					if ($this->Otherwise7 != "") $sqlMoji .= ", '" . addslashes($this->Otherwise7) . "'";
					if ($this->Otherwise7Flg != "") $sqlMoji .= ", '" . addslashes($this->Otherwise7Flg) . "'";
					if ($this->DateS7 != "") $sqlMoji .= ", '" . addslashes($this->DateS7) . "'";
					if ($this->DateE7 != "") $sqlMoji .= ", '" . addslashes($this->DateE7) . "'";
					if ($this->Otherwise8 != "") $sqlMoji .= ", '" . addslashes($this->Otherwise8) . "'";
					if ($this->Otherwise8Flg != "") $sqlMoji .= ", '" . addslashes($this->Otherwise8Flg) . "'";
					if ($this->DateS8 != "") $sqlMoji .= ", '" . addslashes($this->DateS8) . "'";
					if ($this->DateE8 != "") $sqlMoji .= ", '" . addslashes($this->DateE8) . "'";
					if ($this->Otherwise9 != "") $sqlMoji .= ", '" . addslashes($this->Otherwise9) . "'";
					if ($this->Otherwise9Flg != "") $sqlMoji .= ", '" . addslashes($this->Otherwise9Flg) . "'";
					if ($this->DateS9 != "") $sqlMoji .= ", '" . addslashes($this->DateS9) . "'";
					if ($this->DateE9 != "") $sqlMoji .= ", '" . addslashes($this->DateE9) . "'";
					if ($this->Otherwise10 != "") $sqlMoji .= ", '" . addslashes($this->Otherwise10) . "'";
					if ($this->Otherwise10Flg != "") $sqlMoji .= ", '" . addslashes($this->Otherwise10Flg) . "'";
					if ($this->DateS10 != "") $sqlMoji .= ", '" . addslashes($this->DateS10) . "'";
					if ($this->DateE10 != "") $sqlMoji .= ", '" . addslashes($this->DateE10) . "'";

					if ($this->SanpaiKaishuDate != "") $sqlMoji .= ", '" . addslashes($this->SanpaiKaishuDate) . "'";
					if ($this->SanpaiBiko != "") $sqlMoji .= ", '" . addslashes($this->SanpaiBiko) . "'";
					if ($this->SonotaKoji != "") $sqlMoji .= ", '" . addslashes($this->SonotaKoji) . "'";
					if ($this->CurrentNyukan != "") $sqlMoji .= ", '" . addslashes($this->CurrentNyukan) . "'";
					if ($this->CurrentNyukanSonota != "") $sqlMoji .= ", '" . addslashes($this->CurrentNyukanSonota) . "'";
					if ($this->RNNyukan != "") $sqlMoji .= ", '" . addslashes($this->RNNyukan) . "'";
					if ($this->RNNyukanSonota != "") $sqlMoji .= ", '" . addslashes($this->RNNyukanSonota) . "'";
					if ($this->JikahoRendoFlg != "") $sqlMoji .= ", '" . addslashes($this->JikahoRendoFlg) . "'";
					if ($this->Jikaho != "") $sqlMoji .= ", '" . addslashes($this->Jikaho) . "'";
					if ($this->ShizaiOkiba != "") $sqlMoji .= ", '" . addslashes($this->ShizaiOkiba) . "'";
					if ($this->DoguOkiba != "") $sqlMoji .= ", '" . addslashes($this->DoguOkiba) . "'";
					if ($this->SanpaiOkiba != "") $sqlMoji .= ", '" . addslashes($this->SanpaiOkiba) . "'";
					if ($this->Kyukei != "") $sqlMoji .= ", '" . addslashes($this->Kyukei) . "'";
					if ($this->Toilet != "") $sqlMoji .= ", '" . addslashes($this->Toilet) . "'";
					if ($this->Smoking != "") $sqlMoji .= ", '" . addslashes($this->Smoking) . "'";
					if ($this->RentKey != "") $sqlMoji .= ", '" . addslashes($this->RentKey) . "'";
					if ($this->Parking != "") $sqlMoji .= ", '" . addslashes($this->Parking) . "'";
					if ($this->KeibiCompany != "") $sqlMoji .= ", '" . addslashes($this->KeibiCompany) . "'";
					if ($this->KeibiCompanyTEL != "") $sqlMoji .= ", '" . addslashes($this->KeibiCompanyTEL) . "'";
					if ($this->Hijyo != "") $sqlMoji .= ", '" . addslashes($this->Hijyo) . "'";
					if ($this->HijyoIho != "") $sqlMoji .= ", '" . addslashes($this->HijyoIho) . "'";
					if ($this->HijyoIhoShubetu != "") $sqlMoji .= ", '" . addslashes($this->HijyoIhoShubetu) . "'";
					if ($this->Gas != "") $sqlMoji .= ", '" . addslashes($this->Gas) . "'";
					if ($this->GasIho != "") $sqlMoji .= ", '" . addslashes($this->GasIho) . "'";
					if ($this->GasIhoShubetu != "") $sqlMoji .= ", '" . addslashes($this->GasIhoShubetu) . "'";
					if ($this->Kasai != "") $sqlMoji .= ", '" . addslashes($this->Kasai) . "'";
					if ($this->KasaiIho != "") $sqlMoji .= ", '" . addslashes($this->KasaiIho) . "'";
					if ($this->KasaiIhoShubetu != "") $sqlMoji .= ", '" . addslashes($this->KasaiIhoShubetu) . "'";
					if ($this->Trouble != "") $sqlMoji .= ", '" . addslashes($this->Trouble) . "'";
					if ($this->TroubleIho != "") $sqlMoji .= ", '" . addslashes($this->TroubleIho) . "'";
					if ($this->TroubleIhoShubetu != "") $sqlMoji .= ", '" . addslashes($this->TroubleIhoShubetu) . "'";
					if ($this->Bohan != "") $sqlMoji .= ", '" . addslashes($this->Bohan) . "'";
					if ($this->BohanIho != "") $sqlMoji .= ", '" . addslashes($this->BohanIho) . "'";
					if ($this->BohanIhoShubetu != "") $sqlMoji .= ", '" . addslashes($this->BohanIhoShubetu) . "'";
					if ($this->Kanki != "") $sqlMoji .= ", '" . addslashes($this->Kanki) . "'";
					if ($this->KankiIho != "") $sqlMoji .= ", '" . addslashes($this->KankiIho) . "'";
					if ($this->KankiIhoShubetu != "") $sqlMoji .= ", '" . addslashes($this->KankiIhoShubetu) . "'";
					if ($this->Sonota1 != "") $sqlMoji .= ", '" . addslashes($this->Sonota1) . "'";
					if ($this->Sonota1Iho != "") $sqlMoji .= ", '" . addslashes($this->Sonota1Iho) . "'";
					if ($this->Sonota1IhoShubetu != "") $sqlMoji .= ", '" . addslashes($this->Sonota1IhoShubetu) . "'";
					if ($this->Sonota2 != "") $sqlMoji .= ", '" . addslashes($this->Sonota2) . "'";
					if ($this->Sonota2Iho != "") $sqlMoji .= ", '" . addslashes($this->Sonota2Iho) . "'";
					if ($this->Sonota2IhoShubetu != "") $sqlMoji .= ", '" . addslashes($this->Sonota2IhoShubetu) . "'";
					if ($this->Sonota3 != "") $sqlMoji .= ", '" . addslashes($this->Sonota3) . "'";
					if ($this->Sonota3Iho != "") $sqlMoji .= ", '" . addslashes($this->Sonota3Iho) . "'";
					if ($this->Sonota3IhoShubetu != "") $sqlMoji .= ", '" . addslashes($this->Sonota3IhoShubetu) . "'";
					if ($this->Takuhai != "") $sqlMoji .= ", '" . addslashes($this->Takuhai) . "'";
					if ($this->TakuhaiCompany != "") $sqlMoji .= ", '" . addslashes($this->TakuhaiCompany) . "'";
					if ($this->TakuhaiCompanyTEL != "") $sqlMoji .= ", '" . addslashes($this->TakuhaiCompanyTEL) . "'";
					if ($this->TakuhaiSetuzoku != "") $sqlMoji .= ", '" . addslashes($this->TakuhaiSetuzoku) . "'";
					if ($this->OP1DeviceCD != "") $sqlMoji .= ", '" . addslashes($this->OP1DeviceCD) . "'";
					if ($this->OP2DeviceCD != "") $sqlMoji .= ", '" . addslashes($this->OP2DeviceCD) . "'";
					if ($this->OP3DeviceCD != "") $sqlMoji .= ", '" . addslashes($this->OP3DeviceCD) . "'";
					if ($this->OP4DeviceCD != "") $sqlMoji .= ", '" . addslashes($this->OP4DeviceCD) . "'";
					if ($this->OP5DeviceCD != "") $sqlMoji .= ", '" . addslashes($this->OP5DeviceCD) . "'";
					if ($this->OP6DeviceCD != "") $sqlMoji .= ", '" . addslashes($this->OP6DeviceCD) . "'";
					if ($this->OP7DeviceCD != "") $sqlMoji .= ", '" . addslashes($this->OP7DeviceCD) . "'";
					if ($this->OP8DeviceCD != "") $sqlMoji .= ", '" . addslashes($this->OP8DeviceCD) . "'";
					if ($this->OP9DeviceCD != "") $sqlMoji .= ", '" . addslashes($this->OP9DeviceCD) . "'";
					if ($this->OP10DeviceCD != "") $sqlMoji .= ", '" . addslashes($this->OP10DeviceCD) . "'";
					if ($this->OPZiyuu1 != "") $sqlMoji .= ", '" . addslashes($this->OPZiyuu1) . "'";
					if ($this->OPZiyuu2 != "") $sqlMoji .= ", '" . addslashes($this->OPZiyuu2) . "'";
					if ($this->OPZiyuu3 != "") $sqlMoji .= ", '" . addslashes($this->OPZiyuu3) . "'";
					if ($this->OPZiyuu4 != "") $sqlMoji .= ", '" . addslashes($this->OPZiyuu4) . "'";
					if ($this->OPZiyuu5 != "") $sqlMoji .= ", '" . addslashes($this->OPZiyuu5) . "'";
					if ($this->OPZiyuu6 != "") $sqlMoji .= ", '" . addslashes($this->OPZiyuu6) . "'";
					if ($this->OPZiyuu7 != "") $sqlMoji .= ", '" . addslashes($this->OPZiyuu7) . "'";
					if ($this->OPZiyuu8 != "") $sqlMoji .= ", '" . addslashes($this->OPZiyuu8) . "'";
					if ($this->OPZiyuu9 != "") $sqlMoji .= ", '" . addslashes($this->OPZiyuu9) . "'";
					if ($this->OPZiyuu10 != "") $sqlMoji .= ", '" . addslashes($this->OPZiyuu10) . "'";
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
					if ($this->OPNotes != "") $sqlMoji .= ", '" . addslashes($this->OPNotes) . "'";
					if ($this->SekoKyoyoKikiPic != "") $sqlMoji .= ", '" . addslashes($this->SekoKyoyoKikiPic) . "'";
					if ($this->SekoSenyuKikiPic != "") $sqlMoji .= ", '" . addslashes($this->SekoSenyuKikiPic) . "'";
					if ($this->SekoHaisenPic != "") $sqlMoji .= ", '" . addslashes($this->SekoHaisenPic) . "'";
					if ($this->AnshoNo != "") $sqlMoji .= ", '" . addslashes($this->AnshoNo) . "'";
					if ($this->AnshoNoKojichu != "") $sqlMoji .= ", '" . addslashes($this->AnshoNoKojichu) . "'";
					if ($this->PanelFlg != "") $sqlMoji .= ", '" . addslashes($this->PanelFlg) . "'";
					if ($this->OyaPanel != "") $sqlMoji .= ", '" . addslashes($this->OyaPanel) . "'";
					if ($this->KoPanel != "") $sqlMoji .= ", '" . addslashes($this->KoPanel) . "'";
					if ($this->PanelGyosya != "") $sqlMoji .= ", '" . addslashes($this->PanelGyosya) . "'";
					if ($this->GetHeyaNoFlg != "") $sqlMoji .= ", '" . addslashes($this->GetHeyaNoFlg) . "'";
					if ($this->OrderKanriFutoFlg != "") $sqlMoji .= ", '" . addslashes($this->OrderKanriFutoFlg) . "'";

					if ($this->ShizaiOkibaFlg != "") $sqlMoji .= ", '" . addslashes($this->ShizaiOkibaFlg) . "'";
					if ($this->DoguOkibaFlg != "") $sqlMoji .= ", '" . addslashes($this->DoguOkibaFlg) . "'";
					if ($this->SanpaiOkibaFlg != "") $sqlMoji .= ", '" . addslashes($this->SanpaiOkibaFlg) . "'";
					if ($this->KyukeiFlg != "") $sqlMoji .= ", '" . addslashes($this->KyukeiFlg) . "'";
					if ($this->ToiletFlg != "") $sqlMoji .= ", '" . addslashes($this->ToiletFlg) . "'";
					if ($this->SmokingFlg != "") $sqlMoji .= ", '" . addslashes($this->SmokingFlg) . "'";
					if ($this->RentKeyFlg != "") $sqlMoji .= ", '" . addslashes($this->RentKeyFlg) . "'";
					if ($this->ParkingFlg != "") $sqlMoji .= ", '" . addslashes($this->ParkingFlg) . "'";

					if ($this->WakuSu != "") $sqlMoji .= ", '" . addslashes($this->WakuSu) . "'";
					if ($this->OPUketuke != "") $sqlMoji .= ", '" . addslashes($this->OPUketuke) . "'";
					if ($this->MaxWakuSu != "") $sqlMoji .= ", '" . addslashes($this->MaxWakuSu) . "'";
					if ($this->KojiTantoName != "") $sqlMoji .= ", '" . addslashes($this->KojiTantoName) . "'";
					if ($this->KojiShozokuName != "") $sqlMoji .= ", '" . addslashes($this->KojiShozokuName) . "'";
					if ($this->KojiShozokuTEL != "") $sqlMoji .= ", '" . addslashes($this->KojiShozokuTEL) . "'";
					if ($this->ShoboTokurei != "") $sqlMoji .= ", '" . addslashes($this->ShoboTokurei) . "'";
					if ($this->ShoboSikenhoho != "") $sqlMoji .= ", '" . addslashes($this->ShoboSikenhoho) . "'";
					if ($this->GyosyaTantoCD2 != "") $sqlMoji .= ", '" . addslashes($this->GyosyaTantoCD2) . "'";
					if ($this->AnshoNoBiko != "") $sqlMoji .= ", '" . addslashes($this->AnshoNoBiko) . "'";
					if ($this->SekoHaisenPicFlg != "") $sqlMoji .= ", '" . addslashes($this->SekoHaisenPicFlg) . "'";
					if ($this->SekoKyoyoKikiPicFlg != "") $sqlMoji .= ", '" . addslashes($this->SekoKyoyoKikiPicFlg) . "'";
					if ($this->SekoSenyuKikiPicFlg != "") $sqlMoji .= ", '" . addslashes($this->SekoSenyuKikiPicFlg) . "'";
					if ($this->KeihoBiko != "") $sqlMoji .= ", '" . addslashes($this->KeihoBiko) . "'";
					if ($this->SetubiBiko != "") $sqlMoji .= ", '" . addslashes($this->SetubiBiko) . "'";
					if ($this->KojiBiko != "") $sqlMoji .= ", '" . addslashes($this->KojiBiko) . "'";

					if ($this->OPBiko1 != "") $sqlMoji .= ", '" . addslashes($this->OPBiko1) . "'";
					if ($this->OPBiko2 != "") $sqlMoji .= ", '" . addslashes($this->OPBiko2) . "'";
					if ($this->OPBiko3 != "") $sqlMoji .= ", '" . addslashes($this->OPBiko3) . "'";
					if ($this->OPBiko4 != "") $sqlMoji .= ", '" . addslashes($this->OPBiko4) . "'";
					if ($this->OPBiko5 != "") $sqlMoji .= ", '" . addslashes($this->OPBiko5) . "'";
					if ($this->OPBiko6 != "") $sqlMoji .= ", '" . addslashes($this->OPBiko6) . "'";
					if ($this->OPBiko7 != "") $sqlMoji .= ", '" . addslashes($this->OPBiko7) . "'";
					if ($this->OPBiko8 != "") $sqlMoji .= ", '" . addslashes($this->OPBiko8) . "'";
					if ($this->OPBiko9 != "") $sqlMoji .= ", '" . addslashes($this->OPBiko9) . "'";
					if ($this->OPBiko10 != "") $sqlMoji .= ", '" . addslashes($this->OPBiko10) . "'";
					if ($this->SiyobuzaiOyaFlg != "") $sqlMoji .= ", '" . addslashes($this->SiyobuzaiOyaFlg) . "'";
					if ($this->SiyobuzaiOyaBiko != "") $sqlMoji .= ", '" . addslashes($this->SiyobuzaiOyaBiko) . "'";
					if ($this->SiyobuzaiKoFlg != "") $sqlMoji .= ", '" . addslashes($this->SiyobuzaiKoFlg) . "'";
					if ($this->SiyobuzaiKoBiko != "") $sqlMoji .= ", '" . addslashes($this->SiyobuzaiKoBiko) . "'";
					if ($this->SiyobuzaiSyugenFlg != "") $sqlMoji .= ", '" . addslashes($this->SiyobuzaiSyugenFlg) . "'";
					if ($this->SiyobuzaiSyugenBiko != "") $sqlMoji .= ", '" . addslashes($this->SiyobuzaiSyugenBiko) . "'";
					if ($this->SiyobuzaiKanOyaFlg != "") $sqlMoji .= ", '" . addslashes($this->SiyobuzaiKanOyaFlg) . "'";
					if ($this->SiyobuzaiKanOyaBiko != "") $sqlMoji .= ", '" . addslashes($this->SiyobuzaiKanOyaBiko) . "'";
					if ($this->SiyobuzaiSeigyoFlg != "") $sqlMoji .= ", '" . addslashes($this->SiyobuzaiSeigyoFlg) . "'";
					if ($this->SiyobuzaiSeigyoBiko != "") $sqlMoji .= ", '" . addslashes($this->SiyobuzaiSeigyoBiko) . "'";

					if ($this->Hansu != "") $sqlMoji .= ", '" . addslashes($this->Hansu) . "'";
					if ($this->MinuteTime != "") $sqlMoji .= ", '" . addslashes($this->MinuteTime) . "'";
					if ($this->Kojijun != "") $sqlMoji .= ", '" . addslashes($this->Kojijun) . "'";
					if ($this->FirstDateFeature != "") $sqlMoji .= ", '" . addslashes($this->FirstDateFeature) . "'";
					if ($this->Holiday1 != "") $sqlMoji .= ", '" . addslashes($this->Holiday1) . "'";
					if ($this->Holiday2 != "") $sqlMoji .= ", '" . addslashes($this->Holiday2) . "'";
					if ($this->Holiday3 != "") $sqlMoji .= ", '" . addslashes($this->Holiday3) . "'";
					if ($this->Holiday4 != "") $sqlMoji .= ", '" . addslashes($this->Holiday4) . "'";
					if ($this->WakuPattern != "") $sqlMoji .= ", '" . addslashes($this->WakuPattern) . "'";
					if ($this->Kirikaehoho != "") $sqlMoji .= ", '" . addslashes($this->Kirikaehoho) . "'";
					if ($this->KirikaeHeikoEizoriyo != "") $sqlMoji .= ", '" . addslashes($this->KirikaeHeikoEizoriyo) . "'";
					if ($this->KasaiHeya != "") $sqlMoji .= ", '" . addslashes($this->KasaiHeya) . "'";
					if ($this->KansenKoji != "") $sqlMoji .= ", '" . addslashes($this->KansenKoji) . "'";
					if ($this->GasKoji != "") $sqlMoji .= ", '" . addslashes($this->GasKoji) . "'";
					if ($this->RosuiKoji != "") $sqlMoji .= ", '" . addslashes($this->RosuiKoji) . "'";
					if ($this->Shiharai != "") $sqlMoji .= ", '" . addslashes($this->Shiharai) . "'";
					if ($this->TagSuu != "") $sqlMoji .= ", '" . addslashes($this->TagSuu) . "'";
					if ($this->AnzenHattyusya != "") $sqlMoji .= ", '" . addslashes($this->AnzenHattyusya) . "'";
					if ($this->AnzenHattyusyaAddress != "") $sqlMoji .= ", '" . addslashes($this->AnzenHattyusyaAddress) . "'";
					if ($this->AnzenHattyusyaTEL != "") $sqlMoji .= ", '" . addslashes($this->AnzenHattyusyaTEL) . "'";
					if ($this->AnzenKojiNaiyo != "") $sqlMoji .= ", '" . addslashes($this->AnzenKojiNaiyo) . "'";
					if ($this->AnzenKojiName != "") $sqlMoji .= ", '" . addslashes($this->AnzenKojiName) . "'";
					if ($this->ConstTime != "") $sqlMoji .= ", '" . addslashes($this->ConstTime) . "'";
					if ($this->BohanKoji != "") $sqlMoji .= ", '" . addslashes($this->BohanKoji) . "'";
					if ($this->OwnerTagSuu != "") $sqlMoji .= ", '" . addslashes($this->OwnerTagSuu) . "'";
					if ($this->TagKirikae != "") $sqlMoji .= ", '" . addslashes($this->TagKirikae) . "'";
					if ($this->Kanrisitu != "") $sqlMoji .= ", '" . addslashes($this->Kanrisitu) . "'";
					if ($this->KojiShijiBiko != "") $sqlMoji .= ", '" . addslashes($this->KojiShijiBiko) . "'";
					if ($this->DispCompany1 != "") $sqlMoji .= ", '" . addslashes($this->DispCompany1) . "'";
					if ($this->DispCompanyTEL1 != "") $sqlMoji .= ", '" . addslashes($this->DispCompanyTEL1) . "'";
					if ($this->DispCompanySashi1 != "") $sqlMoji .= ", '" . addslashes($this->DispCompanySashi1) . "'";
					if ($this->DispCompanyToi1 != "") $sqlMoji .= ", '" . addslashes($this->DispCompanyToi1) . "'";
					if ($this->DispCompany2 != "") $sqlMoji .= ", '" . addslashes($this->DispCompany2) . "'";
					if ($this->DispCompanyTEL2 != "") $sqlMoji .= ", '" . addslashes($this->DispCompanyTEL2) . "'";
					if ($this->DispCompanySashi2 != "") $sqlMoji .= ", '" . addslashes($this->DispCompanySashi2) . "'";
					if ($this->DispCompanyToi2 != "") $sqlMoji .= ", '" . addslashes($this->DispCompanyToi2) . "'";
					if ($this->DispCompany3 != "") $sqlMoji .= ", '" . addslashes($this->DispCompany3) . "'";
					if ($this->DispCompanyTEL3 != "") $sqlMoji .= ", '" . addslashes($this->DispCompanyTEL3) . "'";
					if ($this->DispCompanySashi3 != "") $sqlMoji .= ", '" . addslashes($this->DispCompanySashi3) . "'";
					if ($this->DispCompanyToi3 != "") $sqlMoji .= ", '" . addslashes($this->DispCompanyToi3) . "'";
					if ($this->ToiawasewakiDisp != "") $sqlMoji .= ", '" . addslashes($this->ToiawasewakiDisp) . "'";
					if ($this->DispCompanyTop != "") $sqlMoji .= ", '" . addslashes($this->DispCompanyTop) . "'";
					if ($this->Sagyoin != "") $sqlMoji .= ", '" . addslashes($this->Sagyoin) . "'";
					if ($this->MitumorishoNo != "") $sqlMoji .= ", '" . addslashes($this->MitumorishoNo) . "'";
					if ($this->ShiteiVestCD != "") $sqlMoji .= ", '" . addslashes($this->ShiteiVestCD) . "'";
					if ($this->ShiteiVestOther != "") $sqlMoji .= ", '" . addslashes($this->ShiteiVestOther) . "'";
					if ($this->Answer != "") $sqlMoji .= ", '" . addslashes($this->Answer) . "'";
					if ($this->Answer489 != "") $sqlMoji .= ", '" . addslashes($this->Answer489) . "'";
					if ($this->WEBRecept != "") $sqlMoji .= ", '" . addslashes($this->WEBRecept) . "'";
					if ($this->YoteiPage != "") $sqlMoji .= ", '" . addslashes($this->YoteiPage) . "'";
					if ($this->YobiSuu != "") $sqlMoji .= ", '" . addslashes($this->YobiSuu) . "'";
					if ($this->KeijiSuu != "") $sqlMoji .= ", '" . addslashes($this->KeijiSuu) . "'";
					if ($this->Battery != "") $sqlMoji .= ", '" . addslashes($this->Battery) . "'";
					if ($this->BatterySuu != "") $sqlMoji .= ", '" . addslashes($this->BatterySuu) . "'";
					if ($this->BukkenTourokuStatus != "") $sqlMoji .= ", '" . addslashes($this->BukkenTourokuStatus) . "'";

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
							$this->KojiCD = $maxCD;
						}
					}
				}
			}
		} else {
			// アップデート
			$sqlMoji = "Update $this->TableName set ";
			$sqlMoji .= "KojiCD = $this->KojiCD";

		   $sqlMoji .= ", BukkenCD = " . $this->convertNullStringValue($this->BukkenCD);
		   $sqlMoji .= ", ClientCD = " . $this->convertNullStringValue($this->ClientCD);
		   $sqlMoji .= ", KojiName = " . $this->convertNullStringValue($this->KojiName);
		   $sqlMoji .= ", SekoShutai = " . $this->convertNullStringValue($this->SekoShutai);
		   $sqlMoji .= ", GyosyaTantoCD1 = " . $this->convertNullStringValue($this->GyosyaTantoCD1);
		   $sqlMoji .= ", SagyoTime = " . $this->convertNullStringValue($this->SagyoTime);
		   $sqlMoji .= ", SagyoDate = " . $this->convertNullStringValue($this->SagyoDate);
		   $sqlMoji .= ", GasKeihoRNFlg = " . $this->convertNullStringValue($this->GasKeihoRNFlg);
		   $sqlMoji .= ", GasBaseFlg = " . $this->convertNullStringValue($this->GasBaseFlg);
		   $sqlMoji .= ", KasaiRNFlg = " . $this->convertNullStringValue($this->KasaiRNFlg);
		   $sqlMoji .= ", KasaiChukeiRNFlg = " . $this->convertNullStringValue($this->KasaiChukeiRNFlg);
		   $sqlMoji .= ", KasaiKyoyuRNFlg = " . $this->convertNullStringValue($this->KasaiKyoyuRNFlg);
		   $sqlMoji .= ", SonotaKojiFlg = " . $this->convertNullStringValue($this->SonotaKojiFlg);
		   $sqlMoji .= ", SonotaKojiNaiyo = " . $this->convertNullStringValue($this->SonotaKojiNaiyo);
		   $sqlMoji .= ", ZentaiStartDate = " . $this->convertNullStringValue($this->ZentaiStartDate);
		   $sqlMoji .= ", ZentaiEndDate = " . $this->convertNullStringValue($this->ZentaiEndDate);
		   $sqlMoji .= ", AnnaiDate = " . $this->convertNullStringValue($this->AnnaiDate);
		   $sqlMoji .= ", ReceptionDate = " . $this->convertNullStringValue($this->ReceptionDate);
		   $sqlMoji .= ", KakuteiDate = " . $this->convertNullStringValue($this->KakuteiDate);

		   $sqlMoji .= ", KyoyoStartDate = " . $this->convertNullStringValue($this->KyoyoStartDate);
		   $sqlMoji .= ", KyoyoEndDate = " . $this->convertNullStringValue($this->KyoyoEndDate);
		   $sqlMoji .= ", SenyuStartDate = " . $this->convertNullStringValue($this->SenyuStartDate);
		   $sqlMoji .= ", SenyuEndDate = " . $this->convertNullStringValue($this->SenyuEndDate);
		   $sqlMoji .= ", YobiStartDate = " . $this->convertNullStringValue($this->YobiStartDate);
		   $sqlMoji .= ", YobiEndDate = " . $this->convertNullStringValue($this->YobiEndDate);
		   $sqlMoji .= ", Otherwise6 = " . $this->convertNullStringValue($this->Otherwise6);
		   $sqlMoji .= ", Otherwise6Flg = " . $this->convertNullStringValue($this->Otherwise6Flg);
		   $sqlMoji .= ", DateS6 = " . $this->convertNullStringValue($this->DateS6);
		   $sqlMoji .= ", DateE6 = " . $this->convertNullStringValue($this->DateE6);
		   $sqlMoji .= ", Otherwise7 = " . $this->convertNullStringValue($this->Otherwise7);
		   $sqlMoji .= ", Otherwise7Flg = " . $this->convertNullStringValue($this->Otherwise7Flg);
		   $sqlMoji .= ", DateS7 = " . $this->convertNullStringValue($this->DateS7);
		   $sqlMoji .= ", DateE7 = " . $this->convertNullStringValue($this->DateE7);
		   $sqlMoji .= ", Otherwise8 = " . $this->convertNullStringValue($this->Otherwise8);
		   $sqlMoji .= ", Otherwise8Flg = " . $this->convertNullStringValue($this->Otherwise8Flg);
		   $sqlMoji .= ", DateS8 = " . $this->convertNullStringValue($this->DateS8);
		   $sqlMoji .= ", DateE8 = " . $this->convertNullStringValue($this->DateE8);
		   $sqlMoji .= ", Otherwise9 = " . $this->convertNullStringValue($this->Otherwise9);
		   $sqlMoji .= ", Otherwise9Flg = " . $this->convertNullStringValue($this->Otherwise9Flg);
		   $sqlMoji .= ", DateS9 = " . $this->convertNullStringValue($this->DateS9);
		   $sqlMoji .= ", DateE9 = " . $this->convertNullStringValue($this->DateE9);
		   $sqlMoji .= ", Otherwise10 = " . $this->convertNullStringValue($this->Otherwise10);
		   $sqlMoji .= ", Otherwise10Flg = " . $this->convertNullStringValue($this->Otherwise10Flg);
		   $sqlMoji .= ", DateS10 = " . $this->convertNullStringValue($this->DateS10);
		   $sqlMoji .= ", DateE10 = " . $this->convertNullStringValue($this->DateE10);

		   $sqlMoji .= ", SanpaiKaishuDate = " . $this->convertNullStringValue($this->SanpaiKaishuDate);
		   $sqlMoji .= ", SanpaiBiko = " . $this->convertNullStringValue($this->SanpaiBiko);
		   $sqlMoji .= ", SonotaKoji = " . $this->convertNullStringValue($this->SonotaKoji);
		   $sqlMoji .= ", CurrentNyukan = " . $this->convertNullStringValue($this->CurrentNyukan);
		   $sqlMoji .= ", CurrentNyukanSonota = " . $this->convertNullStringValue($this->CurrentNyukanSonota);
		   $sqlMoji .= ", RNNyukan = " . $this->convertNullStringValue($this->RNNyukan);
		   $sqlMoji .= ", RNNyukanSonota = " . $this->convertNullStringValue($this->RNNyukanSonota);
		   $sqlMoji .= ", JikahoRendoFlg = " . $this->convertNullStringValue($this->JikahoRendoFlg);
		   $sqlMoji .= ", Jikaho = " . $this->convertNullStringValue($this->Jikaho);
		   $sqlMoji .= ", ShizaiOkiba = " . $this->convertNullStringValue($this->ShizaiOkiba);
		   $sqlMoji .= ", DoguOkiba = " . $this->convertNullStringValue($this->DoguOkiba);
		   $sqlMoji .= ", SanpaiOkiba = " . $this->convertNullStringValue($this->SanpaiOkiba);
		   $sqlMoji .= ", Kyukei = " . $this->convertNullStringValue($this->Kyukei);
		   $sqlMoji .= ", Toilet = " . $this->convertNullStringValue($this->Toilet);
		   $sqlMoji .= ", Smoking = " . $this->convertNullStringValue($this->Smoking);
		   $sqlMoji .= ", RentKey = " . $this->convertNullStringValue($this->RentKey);
		   $sqlMoji .= ", Parking = " . $this->convertNullStringValue($this->Parking);
		   $sqlMoji .= ", KeibiCompany = " . $this->convertNullStringValue($this->KeibiCompany);
		   $sqlMoji .= ", KeibiCompanyTEL = " . $this->convertNullStringValue($this->KeibiCompanyTEL);
		   $sqlMoji .= ", Hijyo = " . $this->convertNullStringValue($this->Hijyo);
		   $sqlMoji .= ", HijyoIho = " . $this->convertNullStringValue($this->HijyoIho);
		   $sqlMoji .= ", HijyoIhoShubetu = " . $this->convertNullStringValue($this->HijyoIhoShubetu);
		   $sqlMoji .= ", Gas = " . $this->convertNullStringValue($this->Gas);
		   $sqlMoji .= ", GasIho = " . $this->convertNullStringValue($this->GasIho);
		   $sqlMoji .= ", GasIhoShubetu = " . $this->convertNullStringValue($this->GasIhoShubetu);
		   $sqlMoji .= ", Kasai = " . $this->convertNullStringValue($this->Kasai);
		   $sqlMoji .= ", KasaiIho = " . $this->convertNullStringValue($this->KasaiIho);
		   $sqlMoji .= ", KasaiIhoShubetu = " . $this->convertNullStringValue($this->KasaiIhoShubetu);
		   $sqlMoji .= ", Trouble = " . $this->convertNullStringValue($this->Trouble);
		   $sqlMoji .= ", TroubleIho = " . $this->convertNullStringValue($this->TroubleIho);
		   $sqlMoji .= ", TroubleIhoShubetu = " . $this->convertNullStringValue($this->TroubleIhoShubetu);
		   $sqlMoji .= ", Bohan = " . $this->convertNullStringValue($this->Bohan);
		   $sqlMoji .= ", BohanIho = " . $this->convertNullStringValue($this->BohanIho);
		   $sqlMoji .= ", BohanIhoShubetu = " . $this->convertNullStringValue($this->BohanIhoShubetu);
		   $sqlMoji .= ", Kanki = " . $this->convertNullStringValue($this->Kanki);
		   $sqlMoji .= ", KankiIho = " . $this->convertNullStringValue($this->KankiIho);
		   $sqlMoji .= ", KankiIhoShubetu = " . $this->convertNullStringValue($this->KankiIhoShubetu);
		   $sqlMoji .= ", Sonota1 = " . $this->convertNullStringValue($this->Sonota1);
		   $sqlMoji .= ", Sonota1Iho = " . $this->convertNullStringValue($this->Sonota1Iho);
		   $sqlMoji .= ", Sonota1IhoShubetu = " . $this->convertNullStringValue($this->Sonota1IhoShubetu);
		   $sqlMoji .= ", Sonota2 = " . $this->convertNullStringValue($this->Sonota2);
		   $sqlMoji .= ", Sonota2Iho = " . $this->convertNullStringValue($this->Sonota2Iho);
		   $sqlMoji .= ", Sonota2IhoShubetu = " . $this->convertNullStringValue($this->Sonota2IhoShubetu);
		   $sqlMoji .= ", Sonota3 = " . $this->convertNullStringValue($this->Sonota3);
		   $sqlMoji .= ", Sonota3Iho = " . $this->convertNullStringValue($this->Sonota3Iho);
		   $sqlMoji .= ", Sonota3IhoShubetu = " . $this->convertNullStringValue($this->Sonota3IhoShubetu);
		   $sqlMoji .= ", Takuhai = " . $this->convertNullStringValue($this->Takuhai);
		   $sqlMoji .= ", TakuhaiCompany = " . $this->convertNullStringValue($this->TakuhaiCompany);
		   $sqlMoji .= ", TakuhaiCompanyTEL = " . $this->convertNullStringValue($this->TakuhaiCompanyTEL);
		   $sqlMoji .= ", TakuhaiSetuzoku = " . $this->convertNullStringValue($this->TakuhaiSetuzoku);
		   $sqlMoji .= ", OP1DeviceCD = " . $this->convertNullStringValue($this->OP1DeviceCD);
		   $sqlMoji .= ", OP2DeviceCD = " . $this->convertNullStringValue($this->OP2DeviceCD);
		   $sqlMoji .= ", OP3DeviceCD = " . $this->convertNullStringValue($this->OP3DeviceCD);
		   $sqlMoji .= ", OP4DeviceCD = " . $this->convertNullStringValue($this->OP4DeviceCD);
		   $sqlMoji .= ", OP5DeviceCD = " . $this->convertNullStringValue($this->OP5DeviceCD);
		   $sqlMoji .= ", OP6DeviceCD = " . $this->convertNullStringValue($this->OP6DeviceCD);
		   $sqlMoji .= ", OP7DeviceCD = " . $this->convertNullStringValue($this->OP7DeviceCD);
		   $sqlMoji .= ", OP8DeviceCD = " . $this->convertNullStringValue($this->OP8DeviceCD);
		   $sqlMoji .= ", OP9DeviceCD = " . $this->convertNullStringValue($this->OP9DeviceCD);
		   $sqlMoji .= ", OP10DeviceCD = " . $this->convertNullStringValue($this->OP10DeviceCD);
		   $sqlMoji .= ", OPZiyuu1 = " . $this->convertNullStringValue($this->OPZiyuu1);
		   $sqlMoji .= ", OPZiyuu2 = " . $this->convertNullStringValue($this->OPZiyuu2);
		   $sqlMoji .= ", OPZiyuu3 = " . $this->convertNullStringValue($this->OPZiyuu3);
		   $sqlMoji .= ", OPZiyuu4 = " . $this->convertNullStringValue($this->OPZiyuu4);
		   $sqlMoji .= ", OPZiyuu5 = " . $this->convertNullStringValue($this->OPZiyuu5);
		   $sqlMoji .= ", OPZiyuu6 = " . $this->convertNullStringValue($this->OPZiyuu6);
		   $sqlMoji .= ", OPZiyuu7 = " . $this->convertNullStringValue($this->OPZiyuu7);
		   $sqlMoji .= ", OPZiyuu8 = " . $this->convertNullStringValue($this->OPZiyuu8);
		   $sqlMoji .= ", OPZiyuu9 = " . $this->convertNullStringValue($this->OPZiyuu9);
		   $sqlMoji .= ", OPZiyuu10 = " . $this->convertNullStringValue($this->OPZiyuu10);
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
		   $sqlMoji .= ", OPNotes = " . $this->convertNullStringValue($this->OPNotes);
		   $sqlMoji .= ", SekoKyoyoKikiPic = " . $this->convertNullStringValue($this->SekoKyoyoKikiPic);
		   $sqlMoji .= ", SekoSenyuKikiPic = " . $this->convertNullStringValue($this->SekoSenyuKikiPic);
		   $sqlMoji .= ", SekoHaisenPic = " . $this->convertNullStringValue($this->SekoHaisenPic);
		   $sqlMoji .= ", AnshoNo = " . $this->convertNullStringValue($this->AnshoNo);
		   $sqlMoji .= ", AnshoNoKojichu = " . $this->convertNullStringValue($this->AnshoNoKojichu);
		   $sqlMoji .= ", PanelFlg = " . $this->convertNullStringValue($this->PanelFlg);
		   $sqlMoji .= ", OyaPanel = " . $this->convertNullStringValue($this->OyaPanel);
		   $sqlMoji .= ", KoPanel = " . $this->convertNullStringValue($this->KoPanel);
		   $sqlMoji .= ", PanelGyosya = " . $this->convertNullStringValue($this->PanelGyosya);
		   $sqlMoji .= ", GetHeyaNoFlg = " . $this->convertNullStringValue($this->GetHeyaNoFlg);
		   $sqlMoji .= ", OrderKanriFutoFlg = " . $this->convertNullStringValue($this->OrderKanriFutoFlg);

		   $sqlMoji .= ", ShizaiOkibaFlg = " . $this->convertNullStringValue($this->ShizaiOkibaFlg);
		   $sqlMoji .= ", DoguOkibaFlg = " . $this->convertNullStringValue($this->DoguOkibaFlg);
		   $sqlMoji .= ", SanpaiOkibaFlg = " . $this->convertNullStringValue($this->SanpaiOkibaFlg);
		   $sqlMoji .= ", KyukeiFlg = " . $this->convertNullStringValue($this->KyukeiFlg);
		   $sqlMoji .= ", ToiletFlg = " . $this->convertNullStringValue($this->ToiletFlg);
		   $sqlMoji .= ", SmokingFlg = " . $this->convertNullStringValue($this->SmokingFlg);
		   $sqlMoji .= ", RentKeyFlg = " . $this->convertNullStringValue($this->RentKeyFlg);
		   $sqlMoji .= ", ParkingFlg = " . $this->convertNullStringValue($this->ParkingFlg);

		   $sqlMoji .= ", WakuSu = " . $this->convertNullStringValue($this->WakuSu);
		   $sqlMoji .= ", OPUketuke = " . $this->convertNullStringValue($this->OPUketuke);
		   $sqlMoji .= ", MaxWakuSu = " . $this->convertNullStringValue($this->MaxWakuSu);
		   $sqlMoji .= ", KojiTantoName = " . $this->convertNullStringValue($this->KojiTantoName);
		   $sqlMoji .= ", KojiShozokuName = " . $this->convertNullStringValue($this->KojiShozokuName);
		   $sqlMoji .= ", KojiShozokuTEL = " . $this->convertNullStringValue($this->KojiShozokuTEL);
		   $sqlMoji .= ", ShoboTokurei = " . $this->convertNullStringValue($this->ShoboTokurei);
		   $sqlMoji .= ", ShoboSikenhoho = " . $this->convertNullStringValue($this->ShoboSikenhoho);
		   $sqlMoji .= ", GyosyaTantoCD2 = " . $this->convertNullStringValue($this->GyosyaTantoCD2);
		   $sqlMoji .= ", AnshoNoBiko = " . $this->convertNullStringValue($this->AnshoNoBiko);
		   $sqlMoji .= ", SekoHaisenPicFlg = " . $this->convertNullStringValue($this->SekoHaisenPicFlg);
		   $sqlMoji .= ", SekoKyoyoKikiPicFlg = " . $this->convertNullStringValue($this->SekoKyoyoKikiPicFlg);
		   $sqlMoji .= ", SekoSenyuKikiPicFlg = " . $this->convertNullStringValue($this->SekoSenyuKikiPicFlg);
		   $sqlMoji .= ", KeihoBiko = " . $this->convertNullStringValue($this->KeihoBiko);
		   $sqlMoji .= ", SetubiBiko = " . $this->convertNullStringValue($this->SetubiBiko);
		   $sqlMoji .= ", KojiBiko = " . $this->convertNullStringValue($this->KojiBiko);

		   $sqlMoji .= ", OPBiko1 = " . $this->convertNullStringValue($this->OPBiko1);
		   $sqlMoji .= ", OPBiko2 = " . $this->convertNullStringValue($this->OPBiko2);
		   $sqlMoji .= ", OPBiko3 = " . $this->convertNullStringValue($this->OPBiko3);
		   $sqlMoji .= ", OPBiko4 = " . $this->convertNullStringValue($this->OPBiko4);
		   $sqlMoji .= ", OPBiko5 = " . $this->convertNullStringValue($this->OPBiko5);
		   $sqlMoji .= ", OPBiko6 = " . $this->convertNullStringValue($this->OPBiko6);
		   $sqlMoji .= ", OPBiko7 = " . $this->convertNullStringValue($this->OPBiko7);
		   $sqlMoji .= ", OPBiko8 = " . $this->convertNullStringValue($this->OPBiko8);
		   $sqlMoji .= ", OPBiko9 = " . $this->convertNullStringValue($this->OPBiko9);
		   $sqlMoji .= ", OPBiko10 = " . $this->convertNullStringValue($this->OPBiko10);
		   $sqlMoji .= ", SiyobuzaiOyaFlg = " . $this->convertNullStringValue($this->SiyobuzaiOyaFlg);
		   $sqlMoji .= ", SiyobuzaiOyaBiko = " . $this->convertNullStringValue($this->SiyobuzaiOyaBiko);
		   $sqlMoji .= ", SiyobuzaiKoFlg = " . $this->convertNullStringValue($this->SiyobuzaiKoFlg);
		   $sqlMoji .= ", SiyobuzaiKoBiko = " . $this->convertNullStringValue($this->SiyobuzaiKoBiko);
		   $sqlMoji .= ", SiyobuzaiSyugenFlg = " . $this->convertNullStringValue($this->SiyobuzaiSyugenFlg);
		   $sqlMoji .= ", SiyobuzaiSyugenBiko = " . $this->convertNullStringValue($this->SiyobuzaiSyugenBiko);
		   $sqlMoji .= ", SiyobuzaiKanOyaFlg = " . $this->convertNullStringValue($this->SiyobuzaiKanOyaFlg);
		   $sqlMoji .= ", SiyobuzaiKanOyaBiko = " . $this->convertNullStringValue($this->SiyobuzaiKanOyaBiko);
		   $sqlMoji .= ", SiyobuzaiSeigyoFlg = " . $this->convertNullStringValue($this->SiyobuzaiSeigyoFlg);
		   $sqlMoji .= ", SiyobuzaiSeigyoBiko = " . $this->convertNullStringValue($this->SiyobuzaiSeigyoBiko);

		   $sqlMoji .= ", Hansu = " . $this->convertNullStringValue($this->Hansu);
		   $sqlMoji .= ", MinuteTime = " . $this->convertNullStringValue($this->MinuteTime);
		   $sqlMoji .= ", Kojijun = " . $this->convertNullStringValue($this->Kojijun);
		   $sqlMoji .= ", FirstDateFeature = " . $this->convertNullStringValue($this->FirstDateFeature);
		   $sqlMoji .= ", Holiday1 = " . $this->convertNullStringValue($this->Holiday1);
		   $sqlMoji .= ", Holiday2 = " . $this->convertNullStringValue($this->Holiday2);
		   $sqlMoji .= ", Holiday3 = " . $this->convertNullStringValue($this->Holiday3);
		   $sqlMoji .= ", Holiday4 = " . $this->convertNullStringValue($this->Holiday4);
		   $sqlMoji .= ", WakuPattern = " . $this->convertNullStringValue($this->WakuPattern);
		   $sqlMoji .= ", Kirikaehoho = " . $this->convertNullStringValue($this->Kirikaehoho);
		   $sqlMoji .= ", KirikaeHeikoEizoriyo = " . $this->convertNullStringValue($this->KirikaeHeikoEizoriyo);
		   $sqlMoji .= ", KasaiHeya = " . $this->convertNullStringValue($this->KasaiHeya);
		   $sqlMoji .= ", KansenKoji = " . $this->convertNullStringValue($this->KansenKoji);
		   $sqlMoji .= ", GasKoji = " . $this->convertNullStringValue($this->GasKoji);
		   $sqlMoji .= ", RosuiKoji = " . $this->convertNullStringValue($this->RosuiKoji);
		   $sqlMoji .= ", Shiharai = " . $this->convertNullStringValue($this->Shiharai);
		   $sqlMoji .= ", TagSuu = " . $this->convertNullStringValue($this->TagSuu);
		   $sqlMoji .= ", AnzenHattyusya = " . $this->convertNullStringValue($this->AnzenHattyusya);
		   $sqlMoji .= ", AnzenHattyusyaAddress = " . $this->convertNullStringValue($this->AnzenHattyusyaAddress);
		   $sqlMoji .= ", AnzenHattyusyaTEL = " . $this->convertNullStringValue($this->AnzenHattyusyaTEL);
		   $sqlMoji .= ", AnzenKojiNaiyo = " . $this->convertNullStringValue($this->AnzenKojiNaiyo);
		   $sqlMoji .= ", AnzenKojiName = " . $this->convertNullStringValue($this->AnzenKojiName);
		   $sqlMoji .= ", ConstTime = " . $this->convertNullStringValue($this->ConstTime);
		   $sqlMoji .= ", BohanKoji = " . $this->convertNullStringValue($this->BohanKoji);
		   $sqlMoji .= ", OwnerTagSuu = " . $this->convertNullStringValue($this->OwnerTagSuu);
		   $sqlMoji .= ", TagKirikae = " . $this->convertNullStringValue($this->TagKirikae);
		   $sqlMoji .= ", Kanrisitu = " . $this->convertNullStringValue($this->Kanrisitu);
		   $sqlMoji .= ", KojiShijiBiko = " . $this->convertNullStringValue($this->KojiShijiBiko);
			$sqlMoji .= ", DispCompany1 = " . $this->convertNullStringValue($this->DispCompany1);
			$sqlMoji .= ", DispCompanyTEL1 = " . $this->convertNullStringValue($this->DispCompanyTEL1);
			$sqlMoji .= ", DispCompanySashi1 = " . $this->convertNullStringValue($this->DispCompanySashi1);
			$sqlMoji .= ", DispCompanyToi1 = " . $this->convertNullStringValue($this->DispCompanyToi1);
			$sqlMoji .= ", DispCompany2 = " . $this->convertNullStringValue($this->DispCompany2);
			$sqlMoji .= ", DispCompanyTEL2 = " . $this->convertNullStringValue($this->DispCompanyTEL2);
			$sqlMoji .= ", DispCompanySashi2 = " . $this->convertNullStringValue($this->DispCompanySashi2);
			$sqlMoji .= ", DispCompanyToi2 = " . $this->convertNullStringValue($this->DispCompanyToi2);
			$sqlMoji .= ", DispCompany3 = " . $this->convertNullStringValue($this->DispCompany3);
			$sqlMoji .= ", DispCompanyTEL3 = " . $this->convertNullStringValue($this->DispCompanyTEL3);
			$sqlMoji .= ", DispCompanySashi3 = " . $this->convertNullStringValue($this->DispCompanySashi3);
			$sqlMoji .= ", DispCompanyToi3 = " . $this->convertNullStringValue($this->DispCompanyToi3);
			$sqlMoji .= ", ToiawasewakiDisp = " . $this->convertNullStringValue($this->ToiawasewakiDisp);
			$sqlMoji .= ", DispCompanyTop = " . $this->convertNullStringValue($this->DispCompanyTop);
			$sqlMoji .= ", Sagyoin = " . $this->convertNullStringValue($this->Sagyoin);
			$sqlMoji .= ", MitumorishoNo = " . $this->convertNullStringValue($this->MitumorishoNo);
			$sqlMoji .= ", ShiteiVestCD = " . $this->convertNullStringValue($this->ShiteiVestCD);
			$sqlMoji .= ", ShiteiVestOther = " . $this->convertNullStringValue($this->ShiteiVestOther);
			$sqlMoji .= ", Answer = " . $this->convertNullStringValue($this->Answer);
			$sqlMoji .= ", Answer489 = " . $this->convertNullStringValue($this->Answer489);
			$sqlMoji .= ", WEBRecept = " . $this->convertNullStringValue($this->WEBRecept);
			$sqlMoji .= ", YoteiPage = " . $this->convertNullStringValue($this->YoteiPage);
			$sqlMoji .= ", YobiSuu = " . $this->convertNullStringValue($this->YobiSuu);
			$sqlMoji .= ", KeijiSuu = " . $this->convertNullStringValue($this->KeijiSuu);
			$sqlMoji .= ", Battery = " . $this->convertNullStringValue($this->Battery);
			$sqlMoji .= ", BatterySuu = " . $this->convertNullStringValue($this->BatterySuu);
			$sqlMoji .= ", BukkenTourokuStatus = " . $this->convertNullStringValue($this->BukkenTourokuStatus);

			$sqlMoji .= ", MukouFlg = " . $this->convertFlgToString($this->MukouFlg);
			$sqlMoji .= ", Created = " . $this->convertNullStringValue($this->Created);
			$sqlMoji .= ", Creator = " . $this->convertNullIntValue($this->Creator);
			$sqlMoji .= ", Updated = NOW()";
			$sqlMoji .= ", Updater = " . $this->convertNullIntValue($this->Updater);
			$sqlMoji .= " where KojiCD = $this->KojiCD";

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
