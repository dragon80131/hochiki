<?php
/**
 * Bukkenクラス
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

class Bukken {
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
 var $BukkenCD;
 var $BukkenName;
 var $TantoCD;
 var $ShozokuCD;
 var $ChosaDate;
 var $Shunko;
 var $Address;
 var $Lat;
 var $Lng;
 var $Researcher;
 var $MitumoriNo;
 var $Bunjyo;
 var $Tosu;
 var $Kosu;
 var $Kaidaka;
 var $HeyaBiko;
 var $KanriGaisya;
 var $KanriGaisyaTanto;
 var $KanriGaisyaTEL;
 var $KanriKinmu;
 var $KanriTEL;
 var $Sokai;
 var $Yobo;
 var $NextSystem;
 var $AutoLock;
 var $DoorType;
 var $InitialMaker;
 var $InitialMakerSonota;
 var $InitialSystem;
 var $Maker;
 var $MakerSonota;
 var $MakerSystem;
 var $RNstate;
 var $RNYear;
 var $BukkenMemo;
 var $SeigyoKataban;
 var $SeigyoSize;
 var $SeigyoKeibi;
 var $SeigyoKeibiGaisya;
 var $SeigyoKeibiIho;
 var $SeigyoTakuhai;
 var $SeigyoTakuhaiGaisya;
 var $SeigyoTakuhaiSetuzoku;
 var $SeigyoEV;
 var $SeigyoEVGaisya;
 var $ShuGenExist;
 var $ShuGenKataban;
 var $ShuGenDaisu;
 var $ShuGenSize;
 var $ShuGenCamera;
 var $ShuGenGyakuMaster;
 var $ShuGenToritukeBubun;
 var $ShuGenToritukeHoho;
 var $ShuGenColor;
 var $ShuGenMaterial;
 var $KanOyaExist;
 var $KanOyaKataban;
 var $KanOyaDaisu;
 var $KanOyaSize;
 var $KanOyaTorituke;
 var $JyukoAdapter;
 var $OyaKataban;
 var $OyaSize;
 var $OyaID;
 var $OyaTorituke;
 var $OyaSW;
 var $OyaUnit;
 var $OyaFuzoku;
 var $OyaKanri;
 var $OyaZosetu;
 var $OyaPanel;
 var $OyaKeiho;
 var $OyaKeihoSonota;
 var $OyaKahoMaker;
 var $OyaKahoMakerSonota;
 var $KokiKataban;
 var $KokiSize;
 var $KokiType;
 var $KokiSW;
 var $KokiPanel;
 var $KokiColor;
 var $Keito;
 var $KeitoSu;
 var $KyoyoSonota;
 var $SenyuSonota;
 var $MilkyBiko;
 var $MilkyDate;
 var $BikoYobo;
 var $TaioPhase;
 var $TaioUpdated;
 var $BukkenStatus;

 var $B001;
 var $B002;
 var $B003;
 var $B004;
 var $B005;
 var $B006;
 var $B007;
 var $B008;
 var $B009;
 var $B010;

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

	function Bukken($myDB = "",$BukkenCD = -1) {
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
		settype($BukkenCD,"integer");
		// 整数チェック
		if (!is_long($BukkenCD) or $BukkenCD == 0) {
			$this->Err = "ユーザーコード が不正です";
			$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
			unset($myLog);
			return FALSE;
		}

		$this->myDB = $myDB;
		$this->TableName = "tBukkenM";
		$this->BukkenCD = $BukkenCD;

		return TRUE;
	}

	function initialize(){
		$this->Err = "";

  $this->BukkenCD = "";
  $this->BukkenName = "";
  $this->TantoCD = "";
  $this->ShozokuCD = "";
  $this->ChosaDate = "";
  $this->Shunko = "";
  $this->Address = "";
  $this->Lat = "";
  $this->Lng = "";
  $this->Researcher = "";
  $this->MitumoriNo = "";
  $this->Bunjyo = "";
  $this->Tosu = "";
  $this->Kosu = "";
  $this->Kaidaka = "";
  $this->HeyaBiko = "";
  $this->KanriGaisya = "";
  $this->KanriGaisyaTanto = "";
  $this->KanriGaisyaTEL = "";
  $this->KanriKinmu = "";
  $this->KanriTEL = "";
  $this->Sokai = "";
  $this->Yobo = "";
  $this->NextSystem = "";
  $this->AutoLock = "";
  $this->DoorType = "";
  $this->InitialMaker = "";
  $this->InitialMakerSonota = "";
  $this->InitialSystem = "";
  $this->Maker = "";
  $this->MakerSonota = "";
  $this->MakerSystem = "";
  $this->RNstate = "";
  $this->RNYear = "";
  $this->BukkenMemo = "";
  $this->SeigyoKataban = "";
  $this->SeigyoSize = "";
  $this->SeigyoKeibi = "";
  $this->SeigyoKeibiGaisya = "";
  $this->SeigyoKeibiIho = "";
  $this->SeigyoTakuhai = "";
  $this->SeigyoTakuhaiGaisya = "";
  $this->SeigyoTakuhaiSetuzoku = "";
  $this->SeigyoEV = "";
  $this->SeigyoEVGaisya = "";
  $this->ShuGenExist = "";
  $this->ShuGenKataban = "";
  $this->ShuGenDaisu = "";
  $this->ShuGenSize = "";
  $this->ShuGenCamera = "";
  $this->ShuGenGyakuMaster = "";
  $this->ShuGenToritukeBubun = "";
  $this->ShuGenToritukeHoho = "";
  $this->ShuGenColor = "";
  $this->ShuGenMaterial = "";
  $this->KanOyaExist = "";
  $this->KanOyaKataban = "";
  $this->KanOyaDaisu = "";
  $this->KanOyaSize = "";
  $this->KanOyaTorituke = "";
  $this->JyukoAdapter = "";
  $this->OyaKataban = "";
  $this->OyaSize = "";
  $this->OyaID = "";
  $this->OyaTorituke = "";
  $this->OyaSW = "";
  $this->OyaUnit = "";
  $this->OyaFuzoku = "";
  $this->OyaKanri = "";
  $this->OyaZosetu = "";
  $this->OyaPanel = "";
  $this->OyaKeiho = "";
  $this->OyaKeihoSonota = "";
  $this->OyaKahoMaker = "";
  $this->OyaKahoMakerSonota = "";
  $this->KokiKataban = "";
  $this->KokiSize = "";
  $this->KokiType = "";
  $this->KokiSW = "";
  $this->KokiPanel = "";
  $this->KokiColor = "";
  $this->Keito = "";
  $this->KeitoSu = "";
  $this->KyoyoSonota = "";
  $this->SenyuSonota = "";
  $this->MilkyBiko = "";
  $this->MilkyDate = "";
  $this->BikoYobo = "";
  $this->TaioPhase = "";
  $this->TaioUpdated = "";
  $this->BukkenStatus = "";

  $this->B001 = "";
  $this->B002 = "";
  $this->B003 = "";
  $this->B004 = "";
  $this->B005 = "";
  $this->B006 = "";
  $this->B007 = "";
  $this->B008 = "";
  $this->B009 = "";
  $this->B010 = "";

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

  $sqlMoji .= " BukkenCD ";
  $sqlMoji .= ", BukkenName ";
  $sqlMoji .= ", TantoCD ";
  $sqlMoji .= ", ShozokuCD ";
  $sqlMoji .= ", ChosaDate ";
  $sqlMoji .= ", Shunko ";
  $sqlMoji .= ", Address ";
  $sqlMoji .= ", Lat ";
  $sqlMoji .= ", Lng ";
  $sqlMoji .= ", Researcher ";
  $sqlMoji .= ", MitumoriNo ";
  $sqlMoji .= ", Bunjyo ";
  $sqlMoji .= ", Tosu ";
  $sqlMoji .= ", Kosu ";
  $sqlMoji .= ", Kaidaka ";
  $sqlMoji .= ", HeyaBiko ";
  $sqlMoji .= ", KanriGaisya ";
  $sqlMoji .= ", KanriGaisyaTanto ";
  $sqlMoji .= ", KanriGaisyaTEL ";
  $sqlMoji .= ", KanriKinmu ";
  $sqlMoji .= ", KanriTEL ";
  $sqlMoji .= ", Sokai ";
  $sqlMoji .= ", Yobo ";
  $sqlMoji .= ", NextSystem ";
  $sqlMoji .= ", AutoLock ";
  $sqlMoji .= ", DoorType ";
  $sqlMoji .= ", InitialMaker ";
  $sqlMoji .= ", InitialMakerSonota ";
  $sqlMoji .= ", InitialSystem ";
  $sqlMoji .= ", Maker ";
  $sqlMoji .= ", MakerSonota ";
  $sqlMoji .= ", MakerSystem ";
  $sqlMoji .= ", RNstate ";
  $sqlMoji .= ", RNYear ";
  $sqlMoji .= ", BukkenMemo ";
  $sqlMoji .= ", SeigyoKataban ";
  $sqlMoji .= ", SeigyoSize ";
  $sqlMoji .= ", SeigyoKeibi ";
  $sqlMoji .= ", SeigyoKeibiGaisya ";
  $sqlMoji .= ", SeigyoKeibiIho ";
  $sqlMoji .= ", SeigyoTakuhai ";
  $sqlMoji .= ", SeigyoTakuhaiGaisya ";
  $sqlMoji .= ", SeigyoTakuhaiSetuzoku ";
  $sqlMoji .= ", SeigyoEV ";
  $sqlMoji .= ", SeigyoEVGaisya ";
  $sqlMoji .= ", ShuGenExist ";
  $sqlMoji .= ", ShuGenKataban ";
  $sqlMoji .= ", ShuGenDaisu ";
  $sqlMoji .= ", ShuGenSize ";
  $sqlMoji .= ", ShuGenCamera ";
  $sqlMoji .= ", ShuGenGyakuMaster ";
  $sqlMoji .= ", ShuGenToritukeBubun ";
  $sqlMoji .= ", ShuGenToritukeHoho ";
  $sqlMoji .= ", ShuGenColor ";
  $sqlMoji .= ", ShuGenMaterial ";
  $sqlMoji .= ", KanOyaExist ";
  $sqlMoji .= ", KanOyaKataban ";
  $sqlMoji .= ", KanOyaDaisu ";
  $sqlMoji .= ", KanOyaSize ";
  $sqlMoji .= ", KanOyaTorituke ";
  $sqlMoji .= ", JyukoAdapter ";
  $sqlMoji .= ", OyaKataban ";
  $sqlMoji .= ", OyaSize ";
  $sqlMoji .= ", OyaID ";
  $sqlMoji .= ", OyaTorituke ";
  $sqlMoji .= ", OyaSW ";
  $sqlMoji .= ", OyaUnit ";
  $sqlMoji .= ", OyaFuzoku ";
  $sqlMoji .= ", OyaKanri ";
  $sqlMoji .= ", OyaZosetu ";
  $sqlMoji .= ", OyaPanel ";
  $sqlMoji .= ", OyaKeiho ";
  $sqlMoji .= ", OyaKeihoSonota ";
  $sqlMoji .= ", OyaKahoMaker ";
  $sqlMoji .= ", OyaKahoMakerSonota ";
  $sqlMoji .= ", KokiKataban ";
  $sqlMoji .= ", KokiSize ";
  $sqlMoji .= ", KokiType ";
  $sqlMoji .= ", KokiSW ";
  $sqlMoji .= ", KokiPanel ";
  $sqlMoji .= ", KokiColor ";
  $sqlMoji .= ", Keito ";
  $sqlMoji .= ", KeitoSu ";
  $sqlMoji .= ", KyoyoSonota ";
  $sqlMoji .= ", SenyuSonota ";
  $sqlMoji .= ", MilkyBiko ";
  $sqlMoji .= ", MilkyDate ";
  $sqlMoji .= ", BikoYobo ";
  $sqlMoji .= ", TaioPhase ";
  $sqlMoji .= ", TaioUpdated ";
  $sqlMoji .= ", BukkenStatus ";

  $sqlMoji .= ", B001 ";
  $sqlMoji .= ", B002 ";
  $sqlMoji .= ", B003 ";
  $sqlMoji .= ", B004 ";
  $sqlMoji .= ", B005 ";
  $sqlMoji .= ", B006 ";
  $sqlMoji .= ", B007 ";
  $sqlMoji .= ", B008 ";
  $sqlMoji .= ", B009 ";
  $sqlMoji .= ", B010 ";

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

    $this->BukkenCD,
    $this->BukkenName,
    $this->TantoCD,
    $this->ShozokuCD,
    $this->ChosaDate,
    $this->Shunko,
    $this->Address,
    $this->Lat,
    $this->Lng,
    $this->Researcher,
    $this->MitumoriNo,
    $this->Bunjyo,
    $this->Tosu,
    $this->Kosu,
    $this->Kaidaka,
    $this->HeyaBiko,
    $this->KanriGaisya,
    $this->KanriGaisyaTanto,
    $this->KanriGaisyaTEL,
    $this->KanriKinmu,
    $this->KanriTEL,
    $this->Sokai,
    $this->Yobo,
    $this->NextSystem,
    $this->AutoLock,
    $this->DoorType,
    $this->InitialMaker,
    $this->InitialMakerSonota,
    $this->InitialSystem,
    $this->Maker,
    $this->MakerSonota,
    $this->MakerSystem,
    $this->RNstate,
    $this->RNYear,
    $this->BukkenMemo,
    $this->SeigyoKataban,
    $this->SeigyoSize,
    $this->SeigyoKeibi,
    $this->SeigyoKeibiGaisya,
    $this->SeigyoKeibiIho,
    $this->SeigyoTakuhai,
    $this->SeigyoTakuhaiGaisya,
    $this->SeigyoTakuhaiSetuzoku,
    $this->SeigyoEV,
    $this->SeigyoEVGaisya,
    $this->ShuGenExist,
    $this->ShuGenKataban,
    $this->ShuGenDaisu,
    $this->ShuGenSize,
    $this->ShuGenCamera,
    $this->ShuGenGyakuMaster,
    $this->ShuGenToritukeBubun,
    $this->ShuGenToritukeHoho,
    $this->ShuGenColor,
    $this->ShuGenMaterial,
    $this->KanOyaExist,
    $this->KanOyaKataban,
    $this->KanOyaDaisu,
    $this->KanOyaSize,
    $this->KanOyaTorituke,
    $this->JyukoAdapter,
    $this->OyaKataban,
    $this->OyaSize,
    $this->OyaID,
    $this->OyaTorituke,
    $this->OyaSW,
    $this->OyaUnit,
    $this->OyaFuzoku,
    $this->OyaKanri,
    $this->OyaZosetu,
    $this->OyaPanel,
    $this->OyaKeiho,
    $this->OyaKeihoSonota,
    $this->OyaKahoMaker,
    $this->OyaKahoMakerSonota,
    $this->KokiKataban,
    $this->KokiSize,
    $this->KokiType,
    $this->KokiSW,
    $this->KokiPanel,
    $this->KokiColor,
    $this->Keito,
    $this->KeitoSu,
    $this->KyoyoSonota,
    $this->SenyuSonota,
    $this->MilkyBiko,
    $this->MilkyDate,
    $this->BikoYobo,
    $this->TaioPhase,
    $this->TaioUpdated,
    $this->BukkenStatus,

    $this->B001,
    $this->B002,
    $this->B003,
    $this->B004,
    $this->B005,
    $this->B006,
    $this->B007,
    $this->B008,
    $this->B009,
    $this->B010,

				$this->MukouFlg,
				$this->Created,
				$this->Creator,
				$this->Updated,
				$this->Updater
			) = $this->myDB->fetchRow($this->tmprtn,$RowNo);

   $this->BukkenName = stripslashes($this->BukkenName);
   $this->TantoCD = stripslashes($this->TantoCD);
   $this->ShozokuCD = stripslashes($this->ShozokuCD);
   $this->ChosaDate = stripslashes($this->ChosaDate);
   $this->Shunko = stripslashes($this->Shunko);
   $this->Address = stripslashes($this->Address);
   $this->Lat = stripslashes($this->Lat);
   $this->Lng = stripslashes($this->Lng);
   $this->Researcher = stripslashes($this->Researcher);
   $this->MitumoriNo = stripslashes($this->MitumoriNo);
   $this->Bunjyo = stripslashes($this->Bunjyo);
   $this->Tosu = stripslashes($this->Tosu);
   $this->Kosu = stripslashes($this->Kosu);
   $this->Kaidaka = stripslashes($this->Kaidaka);
   $this->HeyaBiko = stripslashes($this->HeyaBiko);
   $this->KanriGaisya = stripslashes($this->KanriGaisya);
   $this->KanriGaisyaTanto = stripslashes($this->KanriGaisyaTanto);
   $this->KanriGaisyaTEL = stripslashes($this->KanriGaisyaTEL);
   $this->KanriKinmu = stripslashes($this->KanriKinmu);
   $this->KanriTEL = stripslashes($this->KanriTEL);
   $this->Sokai = stripslashes($this->Sokai);
   $this->Yobo = stripslashes($this->Yobo);
   $this->NextSystem = stripslashes($this->NextSystem);
   $this->AutoLock = stripslashes($this->AutoLock);
   $this->DoorType = stripslashes($this->DoorType);
   $this->InitialMaker = stripslashes($this->InitialMaker);
   $this->InitialMakerSonota = stripslashes($this->InitialMakerSonota);
   $this->InitialSystem = stripslashes($this->InitialSystem);
   $this->Maker = stripslashes($this->Maker);
   $this->MakerSonota = stripslashes($this->MakerSonota);
   $this->MakerSystem = stripslashes($this->MakerSystem);
   $this->RNstate = stripslashes($this->RNstate);
   $this->RNYear = stripslashes($this->RNYear);
   $this->BukkenMemo = stripslashes($this->BukkenMemo);
   $this->SeigyoKataban = stripslashes($this->SeigyoKataban);
   $this->SeigyoSize = stripslashes($this->SeigyoSize);
   $this->SeigyoKeibi = stripslashes($this->SeigyoKeibi);
   $this->SeigyoKeibiGaisya = stripslashes($this->SeigyoKeibiGaisya);
   $this->SeigyoKeibiIho = stripslashes($this->SeigyoKeibiIho);
   $this->SeigyoTakuhai = stripslashes($this->SeigyoTakuhai);
   $this->SeigyoTakuhaiGaisya = stripslashes($this->SeigyoTakuhaiGaisya);
   $this->SeigyoTakuhaiSetuzoku = stripslashes($this->SeigyoTakuhaiSetuzoku);
   $this->SeigyoEV = stripslashes($this->SeigyoEV);
   $this->SeigyoEVGaisya = stripslashes($this->SeigyoEVGaisya);
   $this->ShuGenExist = stripslashes($this->ShuGenExist);
   $this->ShuGenKataban = stripslashes($this->ShuGenKataban);
   $this->ShuGenDaisu = stripslashes($this->ShuGenDaisu);
   $this->ShuGenSize = stripslashes($this->ShuGenSize);
   $this->ShuGenCamera = stripslashes($this->ShuGenCamera);
   $this->ShuGenGyakuMaster = stripslashes($this->ShuGenGyakuMaster);
   $this->ShuGenToritukeBubun = stripslashes($this->ShuGenToritukeBubun);
   $this->ShuGenToritukeHoho = stripslashes($this->ShuGenToritukeHoho);
   $this->ShuGenColor = stripslashes($this->ShuGenColor);
   $this->ShuGenMaterial = stripslashes($this->ShuGenMaterial);
   $this->KanOyaExist = stripslashes($this->KanOyaExist);
   $this->KanOyaKataban = stripslashes($this->KanOyaKataban);
   $this->KanOyaDaisu = stripslashes($this->KanOyaDaisu);
   $this->KanOyaSize = stripslashes($this->KanOyaSize);
   $this->KanOyaTorituke = stripslashes($this->KanOyaTorituke);
   $this->JyukoAdapter = stripslashes($this->JyukoAdapter);
   $this->OyaKataban = stripslashes($this->OyaKataban);
   $this->OyaSize = stripslashes($this->OyaSize);
   $this->OyaID = stripslashes($this->OyaID);
   $this->OyaTorituke = stripslashes($this->OyaTorituke);
   $this->OyaSW = stripslashes($this->OyaSW);
   $this->OyaUnit = stripslashes($this->OyaUnit);
   $this->OyaFuzoku = stripslashes($this->OyaFuzoku);
   $this->OyaKanri = stripslashes($this->OyaKanri);
   $this->OyaZosetu = stripslashes($this->OyaZosetu);
   $this->OyaPanel = stripslashes($this->OyaPanel);
   $this->OyaKeiho = stripslashes($this->OyaKeiho);
   $this->OyaKeihoSonota = stripslashes($this->OyaKeihoSonota);
   $this->OyaKahoMaker = stripslashes($this->OyaKahoMaker);
   $this->OyaKahoMakerSonota = stripslashes($this->OyaKahoMakerSonota);
   $this->KokiKataban = stripslashes($this->KokiKataban);
   $this->KokiSize = stripslashes($this->KokiSize);
   $this->KokiType = stripslashes($this->KokiType);
   $this->KokiSW = stripslashes($this->KokiSW);
   $this->KokiPanel = stripslashes($this->KokiPanel);
   $this->KokiColor = stripslashes($this->KokiColor);
   $this->Keito = stripslashes($this->Keito);
   $this->KeitoSu = stripslashes($this->KeitoSu);
   $this->KyoyoSonota = stripslashes($this->KyoyoSonota);
   $this->SenyuSonota = stripslashes($this->SenyuSonota);
   $this->MilkyBiko = stripslashes($this->MilkyBiko);
   $this->MilkyDate = stripslashes($this->MilkyDate);
   $this->BikoYobo = stripslashes($this->BikoYobo);
   $this->TaioPhase = stripslashes($this->TaioPhase);
   $this->TaioUpdated = stripslashes($this->TaioUpdated);
   $this->BukkenStatus = stripslashes($this->BukkenStatus);

   $this->B001 = stripslashes($this->B001);
   $this->B002 = stripslashes($this->B002);
   $this->B003 = stripslashes($this->B003);
   $this->B004 = stripslashes($this->B004);
   $this->B005 = stripslashes($this->B005);
   $this->B006 = stripslashes($this->B006);
   $this->B007 = stripslashes($this->B007);
   $this->B008 = stripslashes($this->B008);
   $this->B009 = stripslashes($this->B009);
   $this->B010 = stripslashes($this->B010);

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
		
		if ($this->BukkenCD < 0) {
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
					$sqlMoji = "select BukkenCD from $this->TableName order by BukkenCD desc limit 1";
					$maxCD = $this->myDB->getOneValue($sqlMoji);
					$maxCD = ($maxCD == -1) ? 0 : $maxCD;
					$maxCD++;

					// step 4. インサート
					$sqlMoji = "Insert into $this->TableName (";
					$sqlMoji .= "BukkenCD";

     if ($this->BukkenName != "") $sqlMoji .= ", BukkenName";
     if ($this->TantoCD != "") $sqlMoji .= ", TantoCD";
     if ($this->ShozokuCD != "") $sqlMoji .= ", ShozokuCD";
     if ($this->ChosaDate != "") $sqlMoji .= ", ChosaDate";
     if ($this->Shunko != "") $sqlMoji .= ", Shunko";
     if ($this->Address != "") $sqlMoji .= ", Address";
     if ($this->Lat != "") $sqlMoji .= ", Lat";
     if ($this->Lng != "") $sqlMoji .= ", Lng";
     if ($this->Researcher != "") $sqlMoji .= ", Researcher";
     if ($this->MitumoriNo != "") $sqlMoji .= ", MitumoriNo";
     if ($this->Bunjyo != "") $sqlMoji .= ", Bunjyo";
     if ($this->Tosu != "") $sqlMoji .= ", Tosu";
     if ($this->Kosu != "") $sqlMoji .= ", Kosu";
     if ($this->Kaidaka != "") $sqlMoji .= ", Kaidaka";
     if ($this->HeyaBiko != "") $sqlMoji .= ", HeyaBiko";
     if ($this->KanriGaisya != "") $sqlMoji .= ", KanriGaisya";
     if ($this->KanriGaisyaTanto != "") $sqlMoji .= ", KanriGaisyaTanto";
     if ($this->KanriGaisyaTEL != "") $sqlMoji .= ", KanriGaisyaTEL";
     if ($this->KanriKinmu != "") $sqlMoji .= ", KanriKinmu";
     if ($this->KanriTEL != "") $sqlMoji .= ", KanriTEL";
     if ($this->Sokai != "") $sqlMoji .= ", Sokai";
     if ($this->Yobo != "") $sqlMoji .= ", Yobo";
     if ($this->NextSystem != "") $sqlMoji .= ", NextSystem";
     if ($this->AutoLock != "") $sqlMoji .= ", AutoLock";
     if ($this->DoorType != "") $sqlMoji .= ", DoorType";
     if ($this->InitialMaker != "") $sqlMoji .= ", InitialMaker";
     if ($this->InitialMakerSonota != "") $sqlMoji .= ", InitialMakerSonota";
     if ($this->InitialSystem != "") $sqlMoji .= ", InitialSystem";
     if ($this->Maker != "") $sqlMoji .= ", Maker";
     if ($this->MakerSonota != "") $sqlMoji .= ", MakerSonota";
     if ($this->MakerSystem != "") $sqlMoji .= ", MakerSystem";
     if ($this->RNstate != "") $sqlMoji .= ", RNstate";
     if ($this->RNYear != "") $sqlMoji .= ", RNYear";
     if ($this->BukkenMemo != "") $sqlMoji .= ", BukkenMemo";
     if ($this->SeigyoKataban != "") $sqlMoji .= ", SeigyoKataban";
     if ($this->SeigyoSize != "") $sqlMoji .= ", SeigyoSize";
     if ($this->SeigyoKeibi != "") $sqlMoji .= ", SeigyoKeibi";
     if ($this->SeigyoKeibiGaisya != "") $sqlMoji .= ", SeigyoKeibiGaisya";
     if ($this->SeigyoKeibiIho != "") $sqlMoji .= ", SeigyoKeibiIho";
     if ($this->SeigyoTakuhai != "") $sqlMoji .= ", SeigyoTakuhai";
     if ($this->SeigyoTakuhaiGaisya != "") $sqlMoji .= ", SeigyoTakuhaiGaisya";
     if ($this->SeigyoTakuhaiSetuzoku != "") $sqlMoji .= ", SeigyoTakuhaiSetuzoku";
     if ($this->SeigyoEV != "") $sqlMoji .= ", SeigyoEV";
     if ($this->SeigyoEVGaisya != "") $sqlMoji .= ", SeigyoEVGaisya";
     if ($this->ShuGenExist != "") $sqlMoji .= ", ShuGenExist";
     if ($this->ShuGenKataban != "") $sqlMoji .= ", ShuGenKataban";
     if ($this->ShuGenDaisu != "") $sqlMoji .= ", ShuGenDaisu";
     if ($this->ShuGenSize != "") $sqlMoji .= ", ShuGenSize";
     if ($this->ShuGenCamera != "") $sqlMoji .= ", ShuGenCamera";
     if ($this->ShuGenGyakuMaster != "") $sqlMoji .= ", ShuGenGyakuMaster";
     if ($this->ShuGenToritukeBubun != "") $sqlMoji .= ", ShuGenToritukeBubun";
     if ($this->ShuGenToritukeHoho != "") $sqlMoji .= ", ShuGenToritukeHoho";
     if ($this->ShuGenColor != "") $sqlMoji .= ", ShuGenColor";
     if ($this->ShuGenMaterial != "") $sqlMoji .= ", ShuGenMaterial";
     if ($this->KanOyaExist != "") $sqlMoji .= ", KanOyaExist";
     if ($this->KanOyaKataban != "") $sqlMoji .= ", KanOyaKataban";
     if ($this->KanOyaDaisu != "") $sqlMoji .= ", KanOyaDaisu";
     if ($this->KanOyaSize != "") $sqlMoji .= ", KanOyaSize";
     if ($this->KanOyaTorituke != "") $sqlMoji .= ", KanOyaTorituke";
     if ($this->JyukoAdapter != "") $sqlMoji .= ", JyukoAdapter";
     if ($this->OyaKataban != "") $sqlMoji .= ", OyaKataban";
     if ($this->OyaSize != "") $sqlMoji .= ", OyaSize";
     if ($this->OyaID != "") $sqlMoji .= ", OyaID";
     if ($this->OyaTorituke != "") $sqlMoji .= ", OyaTorituke";
     if ($this->OyaSW != "") $sqlMoji .= ", OyaSW";
     if ($this->OyaUnit != "") $sqlMoji .= ", OyaUnit";
     if ($this->OyaFuzoku != "") $sqlMoji .= ", OyaFuzoku";
     if ($this->OyaKanri != "") $sqlMoji .= ", OyaKanri";
     if ($this->OyaZosetu != "") $sqlMoji .= ", OyaZosetu";
     if ($this->OyaPanel != "") $sqlMoji .= ", OyaPanel";
     if ($this->OyaKeiho != "") $sqlMoji .= ", OyaKeiho";
     if ($this->OyaKeihoSonota != "") $sqlMoji .= ", OyaKeihoSonota";
     if ($this->OyaKahoMaker != "") $sqlMoji .= ", OyaKahoMaker";
     if ($this->OyaKahoMakerSonota != "") $sqlMoji .= ", OyaKahoMakerSonota";
     if ($this->KokiKataban != "") $sqlMoji .= ", KokiKataban";
     if ($this->KokiSize != "") $sqlMoji .= ", KokiSize";
     if ($this->KokiType != "") $sqlMoji .= ", KokiType";
     if ($this->KokiSW != "") $sqlMoji .= ", KokiSW";
     if ($this->KokiPanel != "") $sqlMoji .= ", KokiPanel";
     if ($this->KokiColor != "") $sqlMoji .= ", KokiColor";
     if ($this->Keito != "") $sqlMoji .= ", Keito";
     if ($this->KeitoSu != "") $sqlMoji .= ", KeitoSu";
     if ($this->KyoyoSonota != "") $sqlMoji .= ", KyoyoSonota";
     if ($this->SenyuSonota != "") $sqlMoji .= ", SenyuSonota";
     if ($this->MilkyBiko != "") $sqlMoji .= ", MilkyBiko";
     if ($this->MilkyDate != "") $sqlMoji .= ", MilkyDate";
     if ($this->BikoYobo != "") $sqlMoji .= ", BikoYobo";
     if ($this->TaioPhase != "") $sqlMoji .= ", TaioPhase";
     if ($this->TaioUpdated != "") $sqlMoji .= ", TaioUpdated";
     if ($this->BukkenStatus != "") $sqlMoji .= ", BukkenStatus";

     if ($this->B001 != "") $sqlMoji .= ", B001";
     if ($this->B002 != "") $sqlMoji .= ", B002";
     if ($this->B003 != "") $sqlMoji .= ", B003";
     if ($this->B004 != "") $sqlMoji .= ", B004";
     if ($this->B005 != "") $sqlMoji .= ", B005";
     if ($this->B006 != "") $sqlMoji .= ", B006";
     if ($this->B007 != "") $sqlMoji .= ", B007";
     if ($this->B008 != "") $sqlMoji .= ", B008";
     if ($this->B009 != "") $sqlMoji .= ", B009";
     if ($this->B010 != "") $sqlMoji .= ", B010";

					$sqlMoji .= ", Created";
					if ($this->Creator != "") $sqlMoji .= ", Creator";
					$sqlMoji .= ", Updated";
					if ($this->Updater != "") $sqlMoji .= ", Updater";
					$sqlMoji .= ") values (";
					$sqlMoji .= "$maxCD";

     if ($this->BukkenName != "") $sqlMoji .= ", '" . addslashes($this->BukkenName) . "'";
     if ($this->TantoCD != "") $sqlMoji .= ", '" . addslashes($this->TantoCD) . "'";
     if ($this->ShozokuCD != "") $sqlMoji .= ", '" . addslashes($this->ShozokuCD) . "'";
     if ($this->ChosaDate != "") $sqlMoji .= ", '" . addslashes($this->ChosaDate) . "'";
     if ($this->Shunko != "") $sqlMoji .= ", '" . addslashes($this->Shunko) . "'";
     if ($this->Address != "") $sqlMoji .= ", '" . addslashes($this->Address) . "'";
     if ($this->Lat != "") $sqlMoji .= ", '" . addslashes($this->Lat) . "'";
     if ($this->Lng != "") $sqlMoji .= ", '" . addslashes($this->Lng) . "'";
     if ($this->Researcher != "") $sqlMoji .= ", '" . addslashes($this->Researcher) . "'";
     if ($this->MitumoriNo != "") $sqlMoji .= ", '" . addslashes($this->MitumoriNo) . "'";
     if ($this->Bunjyo != "") $sqlMoji .= ", '" . addslashes($this->Bunjyo) . "'";
     if ($this->Tosu != "") $sqlMoji .= ", '" . addslashes($this->Tosu) . "'";
     if ($this->Kosu != "") $sqlMoji .= ", '" . addslashes($this->Kosu) . "'";
     if ($this->Kaidaka != "") $sqlMoji .= ", '" . addslashes($this->Kaidaka) . "'";
     if ($this->HeyaBiko != "") $sqlMoji .= ", '" . addslashes($this->HeyaBiko) . "'";
     if ($this->KanriGaisya != "") $sqlMoji .= ", '" . addslashes($this->KanriGaisya) . "'";
     if ($this->KanriGaisyaTanto != "") $sqlMoji .= ", '" . addslashes($this->KanriGaisyaTanto) . "'";
     if ($this->KanriGaisyaTEL != "") $sqlMoji .= ", '" . addslashes($this->KanriGaisyaTEL) . "'";
     if ($this->KanriKinmu != "") $sqlMoji .= ", '" . addslashes($this->KanriKinmu) . "'";
     if ($this->KanriTEL != "") $sqlMoji .= ", '" . addslashes($this->KanriTEL) . "'";
     if ($this->Sokai != "") $sqlMoji .= ", '" . addslashes($this->Sokai) . "'";
     if ($this->Yobo != "") $sqlMoji .= ", '" . addslashes($this->Yobo) . "'";
     if ($this->NextSystem != "") $sqlMoji .= ", '" . addslashes($this->NextSystem) . "'";
     if ($this->AutoLock != "") $sqlMoji .= ", '" . addslashes($this->AutoLock) . "'";
     if ($this->DoorType != "") $sqlMoji .= ", '" . addslashes($this->DoorType) . "'";
     if ($this->InitialMaker != "") $sqlMoji .= ", '" . addslashes($this->InitialMaker) . "'";
     if ($this->InitialMakerSonota != "") $sqlMoji .= ", '" . addslashes($this->InitialMakerSonota) . "'";
     if ($this->InitialSystem != "") $sqlMoji .= ", '" . addslashes($this->InitialSystem) . "'";
     if ($this->Maker != "") $sqlMoji .= ", '" . addslashes($this->Maker) . "'";
     if ($this->MakerSonota != "") $sqlMoji .= ", '" . addslashes($this->MakerSonota) . "'";
     if ($this->MakerSystem != "") $sqlMoji .= ", '" . addslashes($this->MakerSystem) . "'";
     if ($this->RNstate != "") $sqlMoji .= ", '" . addslashes($this->RNstate) . "'";
     if ($this->RNYear != "") $sqlMoji .= ", '" . addslashes($this->RNYear) . "'";
     if ($this->BukkenMemo != "") $sqlMoji .= ", '" . addslashes($this->BukkenMemo) . "'";
     if ($this->SeigyoKataban != "") $sqlMoji .= ", '" . addslashes($this->SeigyoKataban) . "'";
     if ($this->SeigyoSize != "") $sqlMoji .= ", '" . addslashes($this->SeigyoSize) . "'";
     if ($this->SeigyoKeibi != "") $sqlMoji .= ", '" . addslashes($this->SeigyoKeibi) . "'";
     if ($this->SeigyoKeibiGaisya != "") $sqlMoji .= ", '" . addslashes($this->SeigyoKeibiGaisya) . "'";
     if ($this->SeigyoKeibiIho != "") $sqlMoji .= ", '" . addslashes($this->SeigyoKeibiIho) . "'";
     if ($this->SeigyoTakuhai != "") $sqlMoji .= ", '" . addslashes($this->SeigyoTakuhai) . "'";
     if ($this->SeigyoTakuhaiGaisya != "") $sqlMoji .= ", '" . addslashes($this->SeigyoTakuhaiGaisya) . "'";
     if ($this->SeigyoTakuhaiSetuzoku != "") $sqlMoji .= ", '" . addslashes($this->SeigyoTakuhaiSetuzoku) . "'";
     if ($this->SeigyoEV != "") $sqlMoji .= ", '" . addslashes($this->SeigyoEV) . "'";
     if ($this->SeigyoEVGaisya != "") $sqlMoji .= ", '" . addslashes($this->SeigyoEVGaisya) . "'";
     if ($this->ShuGenExist != "") $sqlMoji .= ", '" . addslashes($this->ShuGenExist) . "'";
     if ($this->ShuGenKataban != "") $sqlMoji .= ", '" . addslashes($this->ShuGenKataban) . "'";
     if ($this->ShuGenDaisu != "") $sqlMoji .= ", '" . addslashes($this->ShuGenDaisu) . "'";
     if ($this->ShuGenSize != "") $sqlMoji .= ", '" . addslashes($this->ShuGenSize) . "'";
     if ($this->ShuGenCamera != "") $sqlMoji .= ", '" . addslashes($this->ShuGenCamera) . "'";
     if ($this->ShuGenGyakuMaster != "") $sqlMoji .= ", '" . addslashes($this->ShuGenGyakuMaster) . "'";
     if ($this->ShuGenToritukeBubun != "") $sqlMoji .= ", '" . addslashes($this->ShuGenToritukeBubun) . "'";
     if ($this->ShuGenToritukeHoho != "") $sqlMoji .= ", '" . addslashes($this->ShuGenToritukeHoho) . "'";
     if ($this->ShuGenColor != "") $sqlMoji .= ", '" . addslashes($this->ShuGenColor) . "'";
     if ($this->ShuGenMaterial != "") $sqlMoji .= ", '" . addslashes($this->ShuGenMaterial) . "'";
     if ($this->KanOyaExist != "") $sqlMoji .= ", '" . addslashes($this->KanOyaExist) . "'";
     if ($this->KanOyaKataban != "") $sqlMoji .= ", '" . addslashes($this->KanOyaKataban) . "'";
     if ($this->KanOyaDaisu != "") $sqlMoji .= ", '" . addslashes($this->KanOyaDaisu) . "'";
     if ($this->KanOyaSize != "") $sqlMoji .= ", '" . addslashes($this->KanOyaSize) . "'";
     if ($this->KanOyaTorituke != "") $sqlMoji .= ", '" . addslashes($this->KanOyaTorituke) . "'";
     if ($this->JyukoAdapter != "") $sqlMoji .= ", '" . addslashes($this->JyukoAdapter) . "'";
     if ($this->OyaKataban != "") $sqlMoji .= ", '" . addslashes($this->OyaKataban) . "'";
     if ($this->OyaSize != "") $sqlMoji .= ", '" . addslashes($this->OyaSize) . "'";
     if ($this->OyaID != "") $sqlMoji .= ", '" . addslashes($this->OyaID) . "'";
     if ($this->OyaTorituke != "") $sqlMoji .= ", '" . addslashes($this->OyaTorituke) . "'";
     if ($this->OyaSW != "") $sqlMoji .= ", '" . addslashes($this->OyaSW) . "'";
     if ($this->OyaUnit != "") $sqlMoji .= ", '" . addslashes($this->OyaUnit) . "'";
     if ($this->OyaFuzoku != "") $sqlMoji .= ", '" . addslashes($this->OyaFuzoku) . "'";
     if ($this->OyaKanri != "") $sqlMoji .= ", '" . addslashes($this->OyaKanri) . "'";
     if ($this->OyaZosetu != "") $sqlMoji .= ", '" . addslashes($this->OyaZosetu) . "'";
     if ($this->OyaPanel != "") $sqlMoji .= ", '" . addslashes($this->OyaPanel) . "'";
     if ($this->OyaKeiho != "") $sqlMoji .= ", '" . addslashes($this->OyaKeiho) . "'";
     if ($this->OyaKeihoSonota != "") $sqlMoji .= ", '" . addslashes($this->OyaKeihoSonota) . "'";
     if ($this->OyaKahoMaker != "") $sqlMoji .= ", '" . addslashes($this->OyaKahoMaker) . "'";
     if ($this->OyaKahoMakerSonota != "") $sqlMoji .= ", '" . addslashes($this->OyaKahoMakerSonota) . "'";
     if ($this->KokiKataban != "") $sqlMoji .= ", '" . addslashes($this->KokiKataban) . "'";
     if ($this->KokiSize != "") $sqlMoji .= ", '" . addslashes($this->KokiSize) . "'";
     if ($this->KokiType != "") $sqlMoji .= ", '" . addslashes($this->KokiType) . "'";
     if ($this->KokiSW != "") $sqlMoji .= ", '" . addslashes($this->KokiSW) . "'";
     if ($this->KokiPanel != "") $sqlMoji .= ", '" . addslashes($this->KokiPanel) . "'";
     if ($this->KokiColor != "") $sqlMoji .= ", '" . addslashes($this->KokiColor) . "'";
     if ($this->Keito != "") $sqlMoji .= ", '" . addslashes($this->Keito) . "'";
     if ($this->KeitoSu != "") $sqlMoji .= ", '" . addslashes($this->KeitoSu) . "'";
     if ($this->KyoyoSonota != "") $sqlMoji .= ", '" . addslashes($this->KyoyoSonota) . "'";
     if ($this->SenyuSonota != "") $sqlMoji .= ", '" . addslashes($this->SenyuSonota) . "'";
     if ($this->MilkyBiko != "") $sqlMoji .= ", '" . addslashes($this->MilkyBiko) . "'";
     if ($this->MilkyDate != "") $sqlMoji .= ", '" . addslashes($this->MilkyDate) . "'";
     if ($this->BikoYobo != "") $sqlMoji .= ", '" . addslashes($this->BikoYobo) . "'";
     if ($this->TaioPhase != "") $sqlMoji .= ", '" . addslashes($this->TaioPhase) . "'";
     if ($this->TaioUpdated != "") $sqlMoji .= ", '" . addslashes($this->TaioUpdated) . "'";
     if ($this->BukkenStatus != "") $sqlMoji .= ", '" . addslashes($this->BukkenStatus) . "'";

     if ($this->B001 != "") $sqlMoji .= ", '" . addslashes($this->B001) . "'";
     if ($this->B002 != "") $sqlMoji .= ", '" . addslashes($this->B002) . "'";
     if ($this->B003 != "") $sqlMoji .= ", '" . addslashes($this->B003) . "'";
     if ($this->B004 != "") $sqlMoji .= ", '" . addslashes($this->B004) . "'";
     if ($this->B005 != "") $sqlMoji .= ", '" . addslashes($this->B005) . "'";
     if ($this->B006 != "") $sqlMoji .= ", '" . addslashes($this->B006) . "'";
     if ($this->B007 != "") $sqlMoji .= ", '" . addslashes($this->B007) . "'";
     if ($this->B008 != "") $sqlMoji .= ", '" . addslashes($this->B008) . "'";
     if ($this->B009 != "") $sqlMoji .= ", '" . addslashes($this->B009) . "'";
     if ($this->B010 != "") $sqlMoji .= ", '" . addslashes($this->B010) . "'";


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
							$this->BukkenCD = $maxCD;
						}
					}
				}
			}
		} else {
			// アップデート 途中にインサートする用


					$sqlMoji = "Insert into $this->TableName (";
					$sqlMoji .= "BukkenCD";

     if ($this->BukkenName != "") $sqlMoji .= ", BukkenName";
     if ($this->TantoCD != "") $sqlMoji .= ", TantoCD";
     if ($this->ShozokuCD != "") $sqlMoji .= ", ShozokuCD";
     if ($this->ChosaDate != "") $sqlMoji .= ", ChosaDate";
     if ($this->Shunko != "") $sqlMoji .= ", Shunko";
     if ($this->Address != "") $sqlMoji .= ", Address";
     if ($this->Lat != "") $sqlMoji .= ", Lat";
     if ($this->Lng != "") $sqlMoji .= ", Lng";
     if ($this->Researcher != "") $sqlMoji .= ", Researcher";
     if ($this->MitumoriNo != "") $sqlMoji .= ", MitumoriNo";
     if ($this->Bunjyo != "") $sqlMoji .= ", Bunjyo";
     if ($this->Tosu != "") $sqlMoji .= ", Tosu";
     if ($this->Kosu != "") $sqlMoji .= ", Kosu";
     if ($this->Kaidaka != "") $sqlMoji .= ", Kaidaka";
     if ($this->HeyaBiko != "") $sqlMoji .= ", HeyaBiko";
     if ($this->KanriGaisya != "") $sqlMoji .= ", KanriGaisya";
     if ($this->KanriGaisyaTanto != "") $sqlMoji .= ", KanriGaisyaTanto";
     if ($this->KanriGaisyaTEL != "") $sqlMoji .= ", KanriGaisyaTEL";
     if ($this->KanriKinmu != "") $sqlMoji .= ", KanriKinmu";
     if ($this->KanriTEL != "") $sqlMoji .= ", KanriTEL";
     if ($this->Sokai != "") $sqlMoji .= ", Sokai";
     if ($this->Yobo != "") $sqlMoji .= ", Yobo";
     if ($this->NextSystem != "") $sqlMoji .= ", NextSystem";
     if ($this->AutoLock != "") $sqlMoji .= ", AutoLock";
     if ($this->DoorType != "") $sqlMoji .= ", DoorType";
     if ($this->InitialMaker != "") $sqlMoji .= ", InitialMaker";
     if ($this->InitialMakerSonota != "") $sqlMoji .= ", InitialMakerSonota";
     if ($this->InitialSystem != "") $sqlMoji .= ", InitialSystem";
     if ($this->Maker != "") $sqlMoji .= ", Maker";
     if ($this->MakerSonota != "") $sqlMoji .= ", MakerSonota";
     if ($this->MakerSystem != "") $sqlMoji .= ", MakerSystem";
     if ($this->RNstate != "") $sqlMoji .= ", RNstate";
     if ($this->RNYear != "") $sqlMoji .= ", RNYear";
     if ($this->BukkenMemo != "") $sqlMoji .= ", BukkenMemo";
     if ($this->SeigyoKataban != "") $sqlMoji .= ", SeigyoKataban";
     if ($this->SeigyoSize != "") $sqlMoji .= ", SeigyoSize";
     if ($this->SeigyoKeibi != "") $sqlMoji .= ", SeigyoKeibi";
     if ($this->SeigyoKeibiGaisya != "") $sqlMoji .= ", SeigyoKeibiGaisya";
     if ($this->SeigyoKeibiIho != "") $sqlMoji .= ", SeigyoKeibiIho";
     if ($this->SeigyoTakuhai != "") $sqlMoji .= ", SeigyoTakuhai";
     if ($this->SeigyoTakuhaiGaisya != "") $sqlMoji .= ", SeigyoTakuhaiGaisya";
     if ($this->SeigyoTakuhaiSetuzoku != "") $sqlMoji .= ", SeigyoTakuhaiSetuzoku";
     if ($this->SeigyoEV != "") $sqlMoji .= ", SeigyoEV";
     if ($this->SeigyoEVGaisya != "") $sqlMoji .= ", SeigyoEVGaisya";
     if ($this->ShuGenExist != "") $sqlMoji .= ", ShuGenExist";
     if ($this->ShuGenKataban != "") $sqlMoji .= ", ShuGenKataban";
     if ($this->ShuGenDaisu != "") $sqlMoji .= ", ShuGenDaisu";
     if ($this->ShuGenSize != "") $sqlMoji .= ", ShuGenSize";
     if ($this->ShuGenCamera != "") $sqlMoji .= ", ShuGenCamera";
     if ($this->ShuGenGyakuMaster != "") $sqlMoji .= ", ShuGenGyakuMaster";
     if ($this->ShuGenToritukeBubun != "") $sqlMoji .= ", ShuGenToritukeBubun";
     if ($this->ShuGenToritukeHoho != "") $sqlMoji .= ", ShuGenToritukeHoho";
     if ($this->ShuGenColor != "") $sqlMoji .= ", ShuGenColor";
     if ($this->ShuGenMaterial != "") $sqlMoji .= ", ShuGenMaterial";
     if ($this->KanOyaExist != "") $sqlMoji .= ", KanOyaExist";
     if ($this->KanOyaKataban != "") $sqlMoji .= ", KanOyaKataban";
     if ($this->KanOyaDaisu != "") $sqlMoji .= ", KanOyaDaisu";
     if ($this->KanOyaSize != "") $sqlMoji .= ", KanOyaSize";
     if ($this->KanOyaTorituke != "") $sqlMoji .= ", KanOyaTorituke";
     if ($this->JyukoAdapter != "") $sqlMoji .= ", JyukoAdapter";
     if ($this->OyaKataban != "") $sqlMoji .= ", OyaKataban";
     if ($this->OyaSize != "") $sqlMoji .= ", OyaSize";
     if ($this->OyaID != "") $sqlMoji .= ", OyaID";
     if ($this->OyaTorituke != "") $sqlMoji .= ", OyaTorituke";
     if ($this->OyaSW != "") $sqlMoji .= ", OyaSW";
     if ($this->OyaUnit != "") $sqlMoji .= ", OyaUnit";
     if ($this->OyaFuzoku != "") $sqlMoji .= ", OyaFuzoku";
     if ($this->OyaKanri != "") $sqlMoji .= ", OyaKanri";
     if ($this->OyaZosetu != "") $sqlMoji .= ", OyaZosetu";
     if ($this->OyaPanel != "") $sqlMoji .= ", OyaPanel";
     if ($this->OyaKeiho != "") $sqlMoji .= ", OyaKeiho";
     if ($this->OyaKeihoSonota != "") $sqlMoji .= ", OyaKeihoSonota";
     if ($this->OyaKahoMaker != "") $sqlMoji .= ", OyaKahoMaker";
     if ($this->OyaKahoMakerSonota != "") $sqlMoji .= ", OyaKahoMakerSonota";
     if ($this->KokiKataban != "") $sqlMoji .= ", KokiKataban";
     if ($this->KokiSize != "") $sqlMoji .= ", KokiSize";
     if ($this->KokiType != "") $sqlMoji .= ", KokiType";
     if ($this->KokiSW != "") $sqlMoji .= ", KokiSW";
     if ($this->KokiPanel != "") $sqlMoji .= ", KokiPanel";
     if ($this->KokiColor != "") $sqlMoji .= ", KokiColor";
     if ($this->Keito != "") $sqlMoji .= ", Keito";
     if ($this->KeitoSu != "") $sqlMoji .= ", KeitoSu";
     if ($this->KyoyoSonota != "") $sqlMoji .= ", KyoyoSonota";
     if ($this->SenyuSonota != "") $sqlMoji .= ", SenyuSonota";
     if ($this->MilkyBiko != "") $sqlMoji .= ", MilkyBiko";
     if ($this->MilkyDate != "") $sqlMoji .= ", MilkyDate";
     if ($this->BikoYobo != "") $sqlMoji .= ", BikoYobo";
     if ($this->TaioPhase != "") $sqlMoji .= ", TaioPhase";
     if ($this->TaioUpdated != "") $sqlMoji .= ", TaioUpdated";
     if ($this->BukkenStatus != "") $sqlMoji .= ", BukkenStatus";

     if ($this->B001 != "") $sqlMoji .= ", B001";
     if ($this->B002 != "") $sqlMoji .= ", B002";
     if ($this->B003 != "") $sqlMoji .= ", B003";
     if ($this->B004 != "") $sqlMoji .= ", B004";
     if ($this->B005 != "") $sqlMoji .= ", B005";
     if ($this->B006 != "") $sqlMoji .= ", B006";
     if ($this->B007 != "") $sqlMoji .= ", B007";
     if ($this->B008 != "") $sqlMoji .= ", B008";
     if ($this->B009 != "") $sqlMoji .= ", B009";
     if ($this->B010 != "") $sqlMoji .= ", B010";

					$sqlMoji .= ", Created";
					if ($this->Creator != "") $sqlMoji .= ", Creator";
					$sqlMoji .= ", Updated";
					if ($this->Updater != "") $sqlMoji .= ", Updater";
					$sqlMoji .= ") values (";


					$sqlMoji .= $this->BukkenCD ;

     if ($this->BukkenName != "") $sqlMoji .= ", '" . addslashes($this->BukkenName) . "'";
     if ($this->TantoCD != "") $sqlMoji .= ", '" . addslashes($this->TantoCD) . "'";
     if ($this->ShozokuCD != "") $sqlMoji .= ", '" . addslashes($this->ShozokuCD) . "'";
     if ($this->ChosaDate != "") $sqlMoji .= ", '" . addslashes($this->ChosaDate) . "'";
     if ($this->Shunko != "") $sqlMoji .= ", '" . addslashes($this->Shunko) . "'";
     if ($this->Address != "") $sqlMoji .= ", '" . addslashes($this->Address) . "'";
     if ($this->Lat != "") $sqlMoji .= ", '" . addslashes($this->Lat) . "'";
     if ($this->Lng != "") $sqlMoji .= ", '" . addslashes($this->Lng) . "'";
     if ($this->Researcher != "") $sqlMoji .= ", '" . addslashes($this->Researcher) . "'";
     if ($this->MitumoriNo != "") $sqlMoji .= ", '" . addslashes($this->MitumoriNo) . "'";
     if ($this->Bunjyo != "") $sqlMoji .= ", '" . addslashes($this->Bunjyo) . "'";
     if ($this->Tosu != "") $sqlMoji .= ", '" . addslashes($this->Tosu) . "'";
     if ($this->Kosu != "") $sqlMoji .= ", '" . addslashes($this->Kosu) . "'";
     if ($this->Kaidaka != "") $sqlMoji .= ", '" . addslashes($this->Kaidaka) . "'";
     if ($this->HeyaBiko != "") $sqlMoji .= ", '" . addslashes($this->HeyaBiko) . "'";
     if ($this->KanriGaisya != "") $sqlMoji .= ", '" . addslashes($this->KanriGaisya) . "'";
     if ($this->KanriGaisyaTanto != "") $sqlMoji .= ", '" . addslashes($this->KanriGaisyaTanto) . "'";
     if ($this->KanriGaisyaTEL != "") $sqlMoji .= ", '" . addslashes($this->KanriGaisyaTEL) . "'";
     if ($this->KanriKinmu != "") $sqlMoji .= ", '" . addslashes($this->KanriKinmu) . "'";
     if ($this->KanriTEL != "") $sqlMoji .= ", '" . addslashes($this->KanriTEL) . "'";
     if ($this->Sokai != "") $sqlMoji .= ", '" . addslashes($this->Sokai) . "'";
     if ($this->Yobo != "") $sqlMoji .= ", '" . addslashes($this->Yobo) . "'";
     if ($this->NextSystem != "") $sqlMoji .= ", '" . addslashes($this->NextSystem) . "'";
     if ($this->AutoLock != "") $sqlMoji .= ", '" . addslashes($this->AutoLock) . "'";
     if ($this->DoorType != "") $sqlMoji .= ", '" . addslashes($this->DoorType) . "'";
     if ($this->InitialMaker != "") $sqlMoji .= ", '" . addslashes($this->InitialMaker) . "'";
     if ($this->InitialMakerSonota != "") $sqlMoji .= ", '" . addslashes($this->InitialMakerSonota) . "'";
     if ($this->InitialSystem != "") $sqlMoji .= ", '" . addslashes($this->InitialSystem) . "'";
     if ($this->Maker != "") $sqlMoji .= ", '" . addslashes($this->Maker) . "'";
     if ($this->MakerSonota != "") $sqlMoji .= ", '" . addslashes($this->MakerSonota) . "'";
     if ($this->MakerSystem != "") $sqlMoji .= ", '" . addslashes($this->MakerSystem) . "'";
     if ($this->RNstate != "") $sqlMoji .= ", '" . addslashes($this->RNstate) . "'";
     if ($this->RNYear != "") $sqlMoji .= ", '" . addslashes($this->RNYear) . "'";
     if ($this->BukkenMemo != "") $sqlMoji .= ", '" . addslashes($this->BukkenMemo) . "'";
     if ($this->SeigyoKataban != "") $sqlMoji .= ", '" . addslashes($this->SeigyoKataban) . "'";
     if ($this->SeigyoSize != "") $sqlMoji .= ", '" . addslashes($this->SeigyoSize) . "'";
     if ($this->SeigyoKeibi != "") $sqlMoji .= ", '" . addslashes($this->SeigyoKeibi) . "'";
     if ($this->SeigyoKeibiGaisya != "") $sqlMoji .= ", '" . addslashes($this->SeigyoKeibiGaisya) . "'";
     if ($this->SeigyoKeibiIho != "") $sqlMoji .= ", '" . addslashes($this->SeigyoKeibiIho) . "'";
     if ($this->SeigyoTakuhai != "") $sqlMoji .= ", '" . addslashes($this->SeigyoTakuhai) . "'";
     if ($this->SeigyoTakuhaiGaisya != "") $sqlMoji .= ", '" . addslashes($this->SeigyoTakuhaiGaisya) . "'";
     if ($this->SeigyoTakuhaiSetuzoku != "") $sqlMoji .= ", '" . addslashes($this->SeigyoTakuhaiSetuzoku) . "'";
     if ($this->SeigyoEV != "") $sqlMoji .= ", '" . addslashes($this->SeigyoEV) . "'";
     if ($this->SeigyoEVGaisya != "") $sqlMoji .= ", '" . addslashes($this->SeigyoEVGaisya) . "'";
     if ($this->ShuGenExist != "") $sqlMoji .= ", '" . addslashes($this->ShuGenExist) . "'";
     if ($this->ShuGenKataban != "") $sqlMoji .= ", '" . addslashes($this->ShuGenKataban) . "'";
     if ($this->ShuGenDaisu != "") $sqlMoji .= ", '" . addslashes($this->ShuGenDaisu) . "'";
     if ($this->ShuGenSize != "") $sqlMoji .= ", '" . addslashes($this->ShuGenSize) . "'";
     if ($this->ShuGenCamera != "") $sqlMoji .= ", '" . addslashes($this->ShuGenCamera) . "'";
     if ($this->ShuGenGyakuMaster != "") $sqlMoji .= ", '" . addslashes($this->ShuGenGyakuMaster) . "'";
     if ($this->ShuGenToritukeBubun != "") $sqlMoji .= ", '" . addslashes($this->ShuGenToritukeBubun) . "'";
     if ($this->ShuGenToritukeHoho != "") $sqlMoji .= ", '" . addslashes($this->ShuGenToritukeHoho) . "'";
     if ($this->ShuGenColor != "") $sqlMoji .= ", '" . addslashes($this->ShuGenColor) . "'";
     if ($this->ShuGenMaterial != "") $sqlMoji .= ", '" . addslashes($this->ShuGenMaterial) . "'";
     if ($this->KanOyaExist != "") $sqlMoji .= ", '" . addslashes($this->KanOyaExist) . "'";
     if ($this->KanOyaKataban != "") $sqlMoji .= ", '" . addslashes($this->KanOyaKataban) . "'";
     if ($this->KanOyaDaisu != "") $sqlMoji .= ", '" . addslashes($this->KanOyaDaisu) . "'";
     if ($this->KanOyaSize != "") $sqlMoji .= ", '" . addslashes($this->KanOyaSize) . "'";
     if ($this->KanOyaTorituke != "") $sqlMoji .= ", '" . addslashes($this->KanOyaTorituke) . "'";
     if ($this->JyukoAdapter != "") $sqlMoji .= ", '" . addslashes($this->JyukoAdapter) . "'";
     if ($this->OyaKataban != "") $sqlMoji .= ", '" . addslashes($this->OyaKataban) . "'";
     if ($this->OyaSize != "") $sqlMoji .= ", '" . addslashes($this->OyaSize) . "'";
     if ($this->OyaID != "") $sqlMoji .= ", '" . addslashes($this->OyaID) . "'";
     if ($this->OyaTorituke != "") $sqlMoji .= ", '" . addslashes($this->OyaTorituke) . "'";
     if ($this->OyaSW != "") $sqlMoji .= ", '" . addslashes($this->OyaSW) . "'";
     if ($this->OyaUnit != "") $sqlMoji .= ", '" . addslashes($this->OyaUnit) . "'";
     if ($this->OyaFuzoku != "") $sqlMoji .= ", '" . addslashes($this->OyaFuzoku) . "'";
     if ($this->OyaKanri != "") $sqlMoji .= ", '" . addslashes($this->OyaKanri) . "'";
     if ($this->OyaZosetu != "") $sqlMoji .= ", '" . addslashes($this->OyaZosetu) . "'";
     if ($this->OyaPanel != "") $sqlMoji .= ", '" . addslashes($this->OyaPanel) . "'";
     if ($this->OyaKeiho != "") $sqlMoji .= ", '" . addslashes($this->OyaKeiho) . "'";
     if ($this->OyaKeihoSonota != "") $sqlMoji .= ", '" . addslashes($this->OyaKeihoSonota) . "'";
     if ($this->OyaKahoMaker != "") $sqlMoji .= ", '" . addslashes($this->OyaKahoMaker) . "'";
     if ($this->OyaKahoMakerSonota != "") $sqlMoji .= ", '" . addslashes($this->OyaKahoMakerSonota) . "'";
     if ($this->KokiKataban != "") $sqlMoji .= ", '" . addslashes($this->KokiKataban) . "'";
     if ($this->KokiSize != "") $sqlMoji .= ", '" . addslashes($this->KokiSize) . "'";
     if ($this->KokiType != "") $sqlMoji .= ", '" . addslashes($this->KokiType) . "'";
     if ($this->KokiSW != "") $sqlMoji .= ", '" . addslashes($this->KokiSW) . "'";
     if ($this->KokiPanel != "") $sqlMoji .= ", '" . addslashes($this->KokiPanel) . "'";
     if ($this->KokiColor != "") $sqlMoji .= ", '" . addslashes($this->KokiColor) . "'";
     if ($this->Keito != "") $sqlMoji .= ", '" . addslashes($this->Keito) . "'";
     if ($this->KeitoSu != "") $sqlMoji .= ", '" . addslashes($this->KeitoSu) . "'";
     if ($this->KyoyoSonota != "") $sqlMoji .= ", '" . addslashes($this->KyoyoSonota) . "'";
     if ($this->SenyuSonota != "") $sqlMoji .= ", '" . addslashes($this->SenyuSonota) . "'";
     if ($this->MilkyBiko != "") $sqlMoji .= ", '" . addslashes($this->MilkyBiko) . "'";
     if ($this->MilkyDate != "") $sqlMoji .= ", '" . addslashes($this->MilkyDate) . "'";
     if ($this->BikoYobo != "") $sqlMoji .= ", '" . addslashes($this->BikoYobo) . "'";
     if ($this->TaioPhase != "") $sqlMoji .= ", '" . addslashes($this->TaioPhase) . "'";
     if ($this->TaioUpdated != "") $sqlMoji .= ", '" . addslashes($this->TaioUpdated) . "'";
     if ($this->BukkenStatus != "") $sqlMoji .= ", '" . addslashes($this->BukkenStatus) . "'";

     if ($this->B001 != "") $sqlMoji .= ", '" . addslashes($this->B001) . "'";
     if ($this->B002 != "") $sqlMoji .= ", '" . addslashes($this->B002) . "'";
     if ($this->B003 != "") $sqlMoji .= ", '" . addslashes($this->B003) . "'";
     if ($this->B004 != "") $sqlMoji .= ", '" . addslashes($this->B004) . "'";
     if ($this->B005 != "") $sqlMoji .= ", '" . addslashes($this->B005) . "'";
     if ($this->B006 != "") $sqlMoji .= ", '" . addslashes($this->B006) . "'";
     if ($this->B007 != "") $sqlMoji .= ", '" . addslashes($this->B007) . "'";
     if ($this->B008 != "") $sqlMoji .= ", '" . addslashes($this->B008) . "'";
     if ($this->B009 != "") $sqlMoji .= ", '" . addslashes($this->B009) . "'";
     if ($this->B010 != "") $sqlMoji .= ", '" . addslashes($this->B010) . "'";


					$sqlMoji .= ", NOW()";
					if ($this->Creator != "") $sqlMoji .= ", $this->Creator";
					$sqlMoji .= ", NOW()";
					if ($this->Updater != "") $sqlMoji .= ", $this->Updater";
					$sqlMoji .= ")";


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
