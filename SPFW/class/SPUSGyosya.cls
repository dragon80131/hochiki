<?php

include_once _CLS_DIR . "SPFWLog.cls";

class Gyosya {
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
	var $GyosyaCD;
	/**
	 * クライアントコード
	 */
	var $ClientCD;
	var $GyosyaKanriNo;
	var $GyosyaName;
	var $GyosyaNameKana;
	var $ShozokuCD;
	var $GyosyaTEL;
	var $GyosyaMail2;
	var $GyosyaMail;
	var $IsSyoubou;
	var $IsBouka;
	var $DaihyoName;
	var $MadogutiTantoName;
	var $MadogutiTantoTEL;
	var $GyosyaPasswd;
	var $GyosyaNotes;

	var $DenkiKoji;
	var $DenkiKojiKyoka;
	var $DenkiKojiSyubetu;
	var $DenkiKojiGou;
	var $DenkiKojiKyokaDate;
	var $DenkiTushinKoji;
	var $DenkiTushinKojiKyoka;
	var $DenkiTushinKojiSyubetu;
	var $DenkiTushinKojiGou;
	var $DenkiTushinKojiKyokaDate;
	var $SyoboShisetuKoji;
	var $SyoboShisetuKojiKyoka;
	var $SyoboShisetuKojiSyubetu;
	var $SyoboShisetuKojiGou;
	var $SyoboShisetuKojiKyokaDate;
	var $KoyoHokenState;
	var $KenkoHokenState;
	var $NenkinHokenState;
	var $BaisyoHokenState;
	var $SikakuNum1;
	var $SikakuNum2;
	var $SikakuNum3;
	var $SikakuNum4;
	var $SikakuNum5;
	var $SikakuNum6;
	var $SikakuNum7;
	var $SikakuNum8;
	var $SikakuNum9;
	var $SikakuNum10;
	var $SikakuNum11;
	var $SikakuNum12;

	var $G01;
	var $GyosyaShitauke;
	var $Color;
	

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

	function __construct($myDB = "",$GyosyaCD = -1) {
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
		settype($GyosyaCD,"integer");
		// 整数チェック
		if (!is_long($GyosyaCD) or $GyosyaCD == 0) {
			$this->Err = "ユーザーコード が不正です";
			$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
			unset($myLog);
			return FALSE;
		}

		$this->myDB = $myDB;
		$this->TableName = "tGyosyaM";
		$this->GyosyaCD = $GyosyaCD;

		return TRUE;
	}

	function initialize(){
		$this->Err = "";

		$this->GyosyaCD = "";
		$this->ClientCD = "";
		$this->GyosyaKanriNo = "";
		$this->GyosyaName = "";
		$this->GyosyaNameKana = "";
		$this->ShozokuCD = "";
		$this->GyosyaTEL = "";
		$this->GyosyaMail2 = "";
		$this->GyosyaMail = "";
		$this->IsSyoubou = "";
		$this->IsBouka = "";
		$this->DaihyoName = "";
		$this->MadogutiTantoName = "";
		$this->MadogutiTantoTEL = "";
		$this->GyosyaPasswd = "";
		$this->GyosyaNotes = "";

		$this->DenkiKoji = "";
		$this->DenkiKojiKyoka = "";
		$this->DenkiKojiSyubetu = "";
		$this->DenkiKojiGou = "";
		$this->DenkiKojiKyokaDate = "";
		$this->DenkiTushinKoji = "";
		$this->DenkiTushinKojiKyoka = "";
		$this->DenkiTushinKojiSyubetu = "";
		$this->DenkiTushinKojiGou = "";
		$this->DenkiTushinKojiKyokaDate = "";
		$this->SyoboShisetuKoji = "";
		$this->SyoboShisetuKojiKyoka = "";
		$this->SyoboShisetuKojiSyubetu = "";
		$this->SyoboShisetuKojiGou = "";
		$this->SyoboShisetuKojiKyokaDate = "";
		$this->KoyoHokenState = "";
		$this->KenkoHokenState = "";
		$this->NenkinHokenState = "";
		$this->BaisyoHokenState = "";
		$this->SikakuNum1 = "";
		$this->SikakuNum2 = "";
		$this->SikakuNum3 = "";
		$this->SikakuNum4 = "";
		$this->SikakuNum5 = "";
		$this->SikakuNum6 = "";
		$this->SikakuNum7 = "";
		$this->SikakuNum8 = "";
		$this->SikakuNum9 = "";
		$this->SikakuNum10 = "";
		$this->SikakuNum11 = "";
		$this->SikakuNum12 = "";

		$this->G01 = "";
		$this->GyosyaShitauke = "";
		$this->Color = "";


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
		$sqlMoji .= "GyosyaCD";
		$sqlMoji .= ", ClientCD";
		$sqlMoji .= ", GyosyaKanriNo";
		$sqlMoji .= ", GyosyaName";
		$sqlMoji .= ", GyosyaNameKana";
		$sqlMoji .= ", ShozokuCD";
		$sqlMoji .= ", GyosyaTEL";
		$sqlMoji .= ", GyosyaMail2";
		$sqlMoji .= ", GyosyaMail";
		$sqlMoji .= ", IsSyoubou";
		$sqlMoji .= ", IsBouka";
		$sqlMoji .= ", DaihyoName";
		$sqlMoji .= ", MadogutiTantoName";
		$sqlMoji .= ", MadogutiTantoTEL";
		$sqlMoji .= ", GyosyaPasswd";
		$sqlMoji .= ", GyosyaNotes";

		$sqlMoji .= ", DenkiKoji";
		$sqlMoji .= ", DenkiKojiKyoka";
		$sqlMoji .= ", DenkiKojiSyubetu";
		$sqlMoji .= ", DenkiKojiGou";
		$sqlMoji .= ", DenkiKojiKyokaDate";
		$sqlMoji .= ", DenkiTushinKoji";
		$sqlMoji .= ", DenkiTushinKojiKyoka";
		$sqlMoji .= ", DenkiTushinKojiSyubetu";
		$sqlMoji .= ", DenkiTushinKojiGou";
		$sqlMoji .= ", DenkiTushinKojiKyokaDate";
		$sqlMoji .= ", SyoboShisetuKoji";
		$sqlMoji .= ", SyoboShisetuKojiKyoka";
		$sqlMoji .= ", SyoboShisetuKojiSyubetu";
		$sqlMoji .= ", SyoboShisetuKojiGou";
		$sqlMoji .= ", SyoboShisetuKojiKyokaDate";
		$sqlMoji .= ", KoyoHokenState";
		$sqlMoji .= ", KenkoHokenState";
		$sqlMoji .= ", NenkinHokenState";
		$sqlMoji .= ", BaisyoHokenState";
		$sqlMoji .= ", SikakuNum1";
		$sqlMoji .= ", SikakuNum2";
		$sqlMoji .= ", SikakuNum3";
		$sqlMoji .= ", SikakuNum4";
		$sqlMoji .= ", SikakuNum5";
		$sqlMoji .= ", SikakuNum6";
		$sqlMoji .= ", SikakuNum7";
		$sqlMoji .= ", SikakuNum8";
		$sqlMoji .= ", SikakuNum9";
		$sqlMoji .= ", SikakuNum10";
		$sqlMoji .= ", SikakuNum11";
		$sqlMoji .= ", SikakuNum12";

		$sqlMoji .= ", G01 ";
		$sqlMoji .= ", GyosyaShitauke ";
		$sqlMoji .= ", Color ";



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
				$this->GyosyaCD,
				$this->ClientCD,
				$this->GyosyaKanriNo,
				$this->GyosyaName,
				$this->GyosyaNameKana,
				$this->ShozokuCD,
				$this->GyosyaTEL,
				$this->GyosyaMail2,
				$this->GyosyaMail,
				$this->IsSyoubou,
				$this->IsBouka,
				$this->DaihyoName,
				$this->MadogutiTantoName,
				$this->MadogutiTantoTEL,
				$this->GyosyaPasswd,
				$this->GyosyaNotes,

				$this->DenkiKoji,
				$this->DenkiKojiKyoka,
				$this->DenkiKojiSyubetu,
				$this->DenkiKojiGou,
				$this->DenkiKojiKyokaDate,
				$this->DenkiTushinKoji,
				$this->DenkiTushinKojiKyoka,
				$this->DenkiTushinKojiSyubetu,
				$this->DenkiTushinKojiGou,
				$this->DenkiTushinKojiKyokaDate,
				$this->SyoboShisetuKoji,
				$this->SyoboShisetuKojiKyoka,
				$this->SyoboShisetuKojiSyubetu,
				$this->SyoboShisetuKojiGou,
				$this->SyoboShisetuKojiKyokaDate,
				$this->KoyoHokenState,
				$this->KenkoHokenState,
				$this->NenkinHokenState,
				$this->BaisyoHokenState,
				$this->SikakuNum1,
				$this->SikakuNum2,
				$this->SikakuNum3,
				$this->SikakuNum4,
				$this->SikakuNum5,
				$this->SikakuNum6,
				$this->SikakuNum7,
				$this->SikakuNum8,
				$this->SikakuNum9,
				$this->SikakuNum10,
				$this->SikakuNum11,
				$this->SikakuNum12,

				$this->G01,
				$this->GyosyaShitauke,
				$this->Color,


				$this->MukouFlg,
				$this->Created,
				$this->Creator,
				$this->Updated,
				$this->Updater
			) = $this->myDB->fetchRow($this->tmprtn,$RowNo);

			$this->GyosyaKanriNo = stripslashes($this->GyosyaKanriNo);
			$this->GyosyaName = stripslashes($this->GyosyaName);
			$this->GyosyaNameKana = stripslashes($this->GyosyaNameKana);
			$this->ShozokuCD = stripslashes($this->ShozokuCD);
			$this->GyosyaTEL = stripslashes($this->GyosyaTEL);
			$this->GyosyaMail2 = stripslashes($this->GyosyaMail2);
			$this->GyosyaMail = stripslashes($this->GyosyaMail);
			$this->IsSyoubou = stripslashes($this->IsSyoubou);
			$this->IsBouka = stripslashes($this->IsBouka);
			$this->DaihyoName = stripslashes($this->DaihyoName);
			$this->MadogutiTantoName = stripslashes($this->MadogutiTantoName);
			$this->MadogutiTantoTEL = stripslashes($this->MadogutiTantoTEL);
			$this->GyosyaPasswd = stripslashes($this->GyosyaPasswd);
			$this->GyosyaNotes = stripslashes($this->GyosyaNotes);

			$this->DenkiKoji = stripslashes($this->DenkiKoji);
			$this->DenkiKojiKyoka = stripslashes($this->DenkiKojiKyoka);
			$this->DenkiKojiSyubetu = stripslashes($this->DenkiKojiSyubetu);
			$this->DenkiKojiGou = stripslashes($this->DenkiKojiGou);
			$this->DenkiKojiKyokaDate = stripslashes($this->DenkiKojiKyokaDate);
			$this->DenkiTushinKoji = stripslashes($this->DenkiTushinKoji);
			$this->DenkiTushinKojiKyoka = stripslashes($this->DenkiTushinKojiKyoka);
			$this->DenkiTushinKojiSyubetu = stripslashes($this->DenkiTushinKojiSyubetu);
			$this->DenkiTushinKojiGou = stripslashes($this->DenkiTushinKojiGou);
			$this->DenkiTushinKojiKyokaDate = stripslashes($this->DenkiTushinKojiKyokaDate);
			$this->SyoboShisetuKoji = stripslashes($this->SyoboShisetuKoji);
			$this->SyoboShisetuKojiKyoka = stripslashes($this->SyoboShisetuKojiKyoka);
			$this->SyoboShisetuKojiSyubetu = stripslashes($this->SyoboShisetuKojiSyubetu);
			$this->SyoboShisetuKojiGou = stripslashes($this->SyoboShisetuKojiGou);
			$this->SyoboShisetuKojiKyokaDate = stripslashes($this->SyoboShisetuKojiKyokaDate);
			$this->KoyoHokenState = stripslashes($this->KoyoHokenState);
			$this->KenkoHokenState = stripslashes($this->KenkoHokenState);
			$this->NenkinHokenState = stripslashes($this->NenkinHokenState);
			$this->BaisyoHokenState = stripslashes($this->BaisyoHokenState);
			$this->SikakuNum1 = stripslashes($this->SikakuNum1);
			$this->SikakuNum2 = stripslashes($this->SikakuNum2);
			$this->SikakuNum3 = stripslashes($this->SikakuNum3);
			$this->SikakuNum4 = stripslashes($this->SikakuNum4);
			$this->SikakuNum5 = stripslashes($this->SikakuNum5);
			$this->SikakuNum6 = stripslashes($this->SikakuNum6);
			$this->SikakuNum7 = stripslashes($this->SikakuNum7);
			$this->SikakuNum8 = stripslashes($this->SikakuNum8);
			$this->SikakuNum9 = stripslashes($this->SikakuNum9);
			$this->SikakuNum10 = stripslashes($this->SikakuNum10);
			$this->SikakuNum11 = stripslashes($this->SikakuNum11);
			$this->SikakuNum12 = stripslashes($this->SikakuNum12);

			$this->G01 = stripslashes($this->G01);
			$this->GyosyaShitauke = stripslashes($this->GyosyaShitauke);
			$this->Color = stripslashes($this->Color);


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
		
		if ($this->GyosyaCD < 0) {
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
					$sqlMoji = "select GyosyaCD from $this->TableName order by GyosyaCD desc limit 1";
					$maxCD = $this->myDB->getOneValue($sqlMoji);
					$maxCD = ($maxCD == -1) ? 0 : $maxCD;
					$maxCD++;

					// step 4. インサート
					$sqlMoji = "Insert into $this->TableName (";
					$sqlMoji .= "GyosyaCD";
					if ($this->ClientCD != "") $sqlMoji .= ", ClientCD";
					if ($this->GyosyaKanriNo != "") $sqlMoji .= ", GyosyaKanriNo";
					if ($this->GyosyaName != "") $sqlMoji .= ", GyosyaName";
					if ($this->GyosyaNameKana != "") $sqlMoji .= ", GyosyaNameKana";
					if ($this->ShozokuCD != "") $sqlMoji .= ", ShozokuCD";
					if ($this->GyosyaTEL != "") $sqlMoji .= ", GyosyaTEL";
					if ($this->GyosyaMail2 != "") $sqlMoji .= ", GyosyaMail2";
					if ($this->GyosyaMail != "") $sqlMoji .= ", GyosyaMail";
					if ($this->IsSyoubou != "") $sqlMoji .= ", IsSyoubou";
					if ($this->IsBouka != "") $sqlMoji .= ", IsBouka";
					if ($this->DaihyoName != "") $sqlMoji .= ", DaihyoName";
					if ($this->MadogutiTantoName != "") $sqlMoji .= ", MadogutiTantoName";
					if ($this->MadogutiTantoTEL != "") $sqlMoji .= ", MadogutiTantoTEL";
					if ($this->GyosyaPasswd != "") $sqlMoji .= ", GyosyaPasswd";
					if ($this->GyosyaNotes != "") $sqlMoji .= ", GyosyaNotes";

					if ($this->DenkiKoji != "") $sqlMoji .= ", DenkiKoji";
					if ($this->DenkiKojiKyoka != "") $sqlMoji .= ", DenkiKojiKyoka";
					if ($this->DenkiKojiSyubetu != "") $sqlMoji .= ", DenkiKojiSyubetu";
					if ($this->DenkiKojiGou != "") $sqlMoji .= ", DenkiKojiGou";
					if ($this->DenkiKojiKyokaDate != "") $sqlMoji .= ", DenkiKojiKyokaDate";
					if ($this->DenkiTushinKoji != "") $sqlMoji .= ", DenkiTushinKoji";
					if ($this->DenkiTushinKojiKyoka != "") $sqlMoji .= ", DenkiTushinKojiKyoka";
					if ($this->DenkiTushinKojiSyubetu != "") $sqlMoji .= ", DenkiTushinKojiSyubetu";
					if ($this->DenkiTushinKojiGou != "") $sqlMoji .= ", DenkiTushinKojiGou";
					if ($this->DenkiTushinKojiKyokaDate != "") $sqlMoji .= ", DenkiTushinKojiKyokaDate";
					if ($this->SyoboShisetuKoji != "") $sqlMoji .= ", SyoboShisetuKoji";
					if ($this->SyoboShisetuKojiKyoka != "") $sqlMoji .= ", SyoboShisetuKojiKyoka";
					if ($this->SyoboShisetuKojiSyubetu != "") $sqlMoji .= ", SyoboShisetuKojiSyubetu";
					if ($this->SyoboShisetuKojiGou != "") $sqlMoji .= ", SyoboShisetuKojiGou";
					if ($this->SyoboShisetuKojiKyokaDate != "") $sqlMoji .= ", SyoboShisetuKojiKyokaDate";
					if ($this->KoyoHokenState != "") $sqlMoji .= ", KoyoHokenState";
					if ($this->KenkoHokenState != "") $sqlMoji .= ", KenkoHokenState";
					if ($this->NenkinHokenState != "") $sqlMoji .= ", NenkinHokenState";
					if ($this->BaisyoHokenState != "") $sqlMoji .= ", BaisyoHokenState";
					if ($this->SikakuNum1 != "") $sqlMoji .= ", SikakuNum1";
					if ($this->SikakuNum2 != "") $sqlMoji .= ", SikakuNum2";
					if ($this->SikakuNum3 != "") $sqlMoji .= ", SikakuNum3";
					if ($this->SikakuNum4 != "") $sqlMoji .= ", SikakuNum4";
					if ($this->SikakuNum5 != "") $sqlMoji .= ", SikakuNum5";
					if ($this->SikakuNum6 != "") $sqlMoji .= ", SikakuNum6";
					if ($this->SikakuNum7 != "") $sqlMoji .= ", SikakuNum7";
					if ($this->SikakuNum8 != "") $sqlMoji .= ", SikakuNum8";
					if ($this->SikakuNum9 != "") $sqlMoji .= ", SikakuNum9";
					if ($this->SikakuNum10 != "") $sqlMoji .= ", SikakuNum10";
					if ($this->SikakuNum11 != "") $sqlMoji .= ", SikakuNum11";
					if ($this->SikakuNum12 != "") $sqlMoji .= ", SikakuNum12";

					if ($this->G01 != "") $sqlMoji .= ", G01";
					if ($this->GyosyaShitauke != "") $sqlMoji .= ", GyosyaShitauke";
					if ($this->Color != "") $sqlMoji .= ", Color ";


					if ($this->MukouFlg != "") $sqlMoji .= ", MukouFlg";
					$sqlMoji .= ", Created";
					if ($this->Creator != "") $sqlMoji .= ", Creator";
					$sqlMoji .= ", Updated";
					if ($this->Updater != "") $sqlMoji .= ", Updater";
					$sqlMoji .= ") values (";
					$sqlMoji .= "$maxCD";
					if ($this->ClientCD != "") $sqlMoji .= ", $this->ClientCD";
					if ($this->GyosyaKanriNo != "") $sqlMoji .= ", '" . addslashes($this->GyosyaKanriNo) . "'";
					if ($this->GyosyaName != "") $sqlMoji .= ", '" . addslashes($this->GyosyaName) . "'";
					if ($this->GyosyaNameKana != "") $sqlMoji .= ", '" . addslashes($this->GyosyaNameKana) . "'";
					if ($this->ShozokuCD != "") $sqlMoji .= ", '" . addslashes($this->ShozokuCD) . "'";
					if ($this->GyosyaTEL != "") $sqlMoji .= ", '" . addslashes($this->GyosyaTEL) . "'";
					if ($this->GyosyaMail2 != "") $sqlMoji .= ", '" . addslashes($this->GyosyaMail2) . "'";
					if ($this->GyosyaMail != "") $sqlMoji .= ", '" . addslashes($this->GyosyaMail) . "'";
					if ($this->IsSyoubou != "") $sqlMoji .= ", '" . addslashes($this->IsSyoubou) . "'";
					if ($this->IsBouka != "") $sqlMoji .= ", '" . addslashes($this->IsBouka) . "'";
					if ($this->DaihyoName != "") $sqlMoji .= ", '" . addslashes($this->DaihyoName) . "'";
					if ($this->MadogutiTantoName != "") $sqlMoji .= ", '" . addslashes($this->MadogutiTantoName) . "'";
					if ($this->MadogutiTantoTEL != "") $sqlMoji .= ", '" . addslashes($this->MadogutiTantoTEL) . "'";
					if ($this->GyosyaPasswd != "") $sqlMoji .= ", '" . addslashes($this->GyosyaPasswd) . "'";
					if ($this->GyosyaNotes != "") $sqlMoji .= ", '" . addslashes($this->GyosyaNotes) . "'";

					if ($this->DenkiKoji != "") $sqlMoji .= ", '" . addslashes($this->DenkiKoji) . "'";
					if ($this->DenkiKojiKyoka != "") $sqlMoji .= ", '" . addslashes($this->DenkiKojiKyoka) . "'";
					if ($this->DenkiKojiSyubetu != "") $sqlMoji .= ", '" . addslashes($this->DenkiKojiSyubetu) . "'";
					if ($this->DenkiKojiGou != "") $sqlMoji .= ", '" . addslashes($this->DenkiKojiGou) . "'";
					if ($this->DenkiKojiKyokaDate != "") $sqlMoji .= ", '" . addslashes($this->DenkiKojiKyokaDate) . "'";
					if ($this->DenkiTushinKoji != "") $sqlMoji .= ", '" . addslashes($this->DenkiTushinKoji) . "'";
					if ($this->DenkiTushinKojiKyoka != "") $sqlMoji .= ", '" . addslashes($this->DenkiTushinKojiKyoka) . "'";
					if ($this->DenkiTushinKojiSyubetu != "") $sqlMoji .= ", '" . addslashes($this->DenkiTushinKojiSyubetu) . "'";
					if ($this->DenkiTushinKojiGou != "") $sqlMoji .= ", '" . addslashes($this->DenkiTushinKojiGou) . "'";
					if ($this->DenkiTushinKojiKyokaDate != "") $sqlMoji .= ", '" . addslashes($this->DenkiTushinKojiKyokaDate) . "'";
					if ($this->SyoboShisetuKoji != "") $sqlMoji .= ", '" . addslashes($this->SyoboShisetuKoji) . "'";
					if ($this->SyoboShisetuKojiKyoka != "") $sqlMoji .= ", '" . addslashes($this->SyoboShisetuKojiKyoka) . "'";
					if ($this->SyoboShisetuKojiSyubetu != "") $sqlMoji .= ", '" . addslashes($this->SyoboShisetuKojiSyubetu) . "'";
					if ($this->SyoboShisetuKojiGou != "") $sqlMoji .= ", '" . addslashes($this->SyoboShisetuKojiGou) . "'";
					if ($this->SyoboShisetuKojiKyokaDate != "") $sqlMoji .= ", '" . addslashes($this->SyoboShisetuKojiKyokaDate) . "'";
					if ($this->KoyoHokenState != "") $sqlMoji .= ", '" . addslashes($this->KoyoHokenState) . "'";
					if ($this->KenkoHokenState != "") $sqlMoji .= ", '" . addslashes($this->KenkoHokenState) . "'";
					if ($this->NenkinHokenState != "") $sqlMoji .= ", '" . addslashes($this->NenkinHokenState) . "'";
					if ($this->BaisyoHokenState != "") $sqlMoji .= ", '" . addslashes($this->BaisyoHokenState) . "'";
					if ($this->SikakuNum1 != "") $sqlMoji .= ", '" . addslashes($this->SikakuNum1) . "'";
					if ($this->SikakuNum2 != "") $sqlMoji .= ", '" . addslashes($this->SikakuNum2) . "'";
					if ($this->SikakuNum3 != "") $sqlMoji .= ", '" . addslashes($this->SikakuNum3) . "'";
					if ($this->SikakuNum4 != "") $sqlMoji .= ", '" . addslashes($this->SikakuNum4) . "'";
					if ($this->SikakuNum5 != "") $sqlMoji .= ", '" . addslashes($this->SikakuNum5) . "'";
					if ($this->SikakuNum6 != "") $sqlMoji .= ", '" . addslashes($this->SikakuNum6) . "'";
					if ($this->SikakuNum7 != "") $sqlMoji .= ", '" . addslashes($this->SikakuNum7) . "'";
					if ($this->SikakuNum8 != "") $sqlMoji .= ", '" . addslashes($this->SikakuNum8) . "'";
					if ($this->SikakuNum9 != "") $sqlMoji .= ", '" . addslashes($this->SikakuNum9) . "'";
					if ($this->SikakuNum10 != "") $sqlMoji .= ", '" . addslashes($this->SikakuNum10) . "'";
					if ($this->SikakuNum11 != "") $sqlMoji .= ", '" . addslashes($this->SikakuNum11) . "'";
					if ($this->SikakuNum12 != "") $sqlMoji .= ", '" . addslashes($this->SikakuNum12) . "'";

					if ($this->G01 != "") $sqlMoji .= ", '" . addslashes($this->G01) . "'";
					if ($this->GyosyaShitauke != "") $sqlMoji .= ", '" . addslashes($this->GyosyaShitauke) . "'";
					if ($this->Color != "") $sqlMoji .= ", '" . addslashes($this->Color) . "'";


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
							$this->GyosyaCD = $maxCD;
						}
					}
				}
			}
		} else {
			// アップデート
			$sqlMoji = "Update $this->TableName set ";
			$sqlMoji .= "GyosyaCD = $this->GyosyaCD";
			$sqlMoji .= ", ClientCD = " . $this->convertNullIntValue($this->ClientCD);
			$sqlMoji .= ", GyosyaKanriNo = " . $this->convertNullStringValue($this->GyosyaKanriNo);
			$sqlMoji .= ", GyosyaName = " . $this->convertNullStringValue($this->GyosyaName);
			$sqlMoji .= ", GyosyaNameKana = " . $this->convertNullStringValue($this->GyosyaNameKana);
			$sqlMoji .= ", ShozokuCD = " . $this->convertNullStringValue($this->ShozokuCD);
			$sqlMoji .= ", GyosyaTEL = " . $this->convertNullStringValue($this->GyosyaTEL);
			$sqlMoji .= ", GyosyaMail2 = " . $this->convertNullStringValue($this->GyosyaMail2);
			$sqlMoji .= ", GyosyaMail = " . $this->convertNullStringValue($this->GyosyaMail);
			$sqlMoji .= ", IsSyoubou = " . $this->convertNullStringValue($this->IsSyoubou);
			$sqlMoji .= ", IsBouka = " . $this->convertNullStringValue($this->IsBouka);
			$sqlMoji .= ", DaihyoName = " . $this->convertNullStringValue($this->DaihyoName);
			$sqlMoji .= ", MadogutiTantoName = " . $this->convertNullStringValue($this->MadogutiTantoName);
			$sqlMoji .= ", MadogutiTantoTEL = " . $this->convertNullStringValue($this->MadogutiTantoTEL);
			$sqlMoji .= ", GyosyaPasswd = " . $this->convertNullStringValue($this->GyosyaPasswd);
			$sqlMoji .= ", GyosyaNotes = " . $this->convertNullStringValue($this->GyosyaNotes);

			$sqlMoji .= ", DenkiKoji = " . $this->convertNullStringValue($this->DenkiKoji);
			$sqlMoji .= ", DenkiKojiKyoka = " . $this->convertNullStringValue($this->DenkiKojiKyoka);
			$sqlMoji .= ", DenkiKojiSyubetu = " . $this->convertNullStringValue($this->DenkiKojiSyubetu);
			$sqlMoji .= ", DenkiKojiGou = " . $this->convertNullStringValue($this->DenkiKojiGou);
			$sqlMoji .= ", DenkiKojiKyokaDate = " . $this->convertNullStringValue($this->DenkiKojiKyokaDate);
			$sqlMoji .= ", DenkiTushinKoji = " . $this->convertNullStringValue($this->DenkiTushinKoji);
			$sqlMoji .= ", DenkiTushinKojiKyoka = " . $this->convertNullStringValue($this->DenkiTushinKojiKyoka);
			$sqlMoji .= ", DenkiTushinKojiSyubetu = " . $this->convertNullStringValue($this->DenkiTushinKojiSyubetu);
			$sqlMoji .= ", DenkiTushinKojiGou = " . $this->convertNullStringValue($this->DenkiTushinKojiGou);
			$sqlMoji .= ", DenkiTushinKojiKyokaDate = " . $this->convertNullStringValue($this->DenkiTushinKojiKyokaDate);
			$sqlMoji .= ", SyoboShisetuKoji = " . $this->convertNullStringValue($this->SyoboShisetuKoji);
			$sqlMoji .= ", SyoboShisetuKojiKyoka = " . $this->convertNullStringValue($this->SyoboShisetuKojiKyoka);
			$sqlMoji .= ", SyoboShisetuKojiSyubetu = " . $this->convertNullStringValue($this->SyoboShisetuKojiSyubetu);
			$sqlMoji .= ", SyoboShisetuKojiGou = " . $this->convertNullStringValue($this->SyoboShisetuKojiGou);
			$sqlMoji .= ", SyoboShisetuKojiKyokaDate = " . $this->convertNullStringValue($this->SyoboShisetuKojiKyokaDate);
			$sqlMoji .= ", KoyoHokenState = " . $this->convertNullStringValue($this->KoyoHokenState);
			$sqlMoji .= ", KenkoHokenState = " . $this->convertNullStringValue($this->KenkoHokenState);
			$sqlMoji .= ", NenkinHokenState = " . $this->convertNullStringValue($this->NenkinHokenState);
			$sqlMoji .= ", BaisyoHokenState = " . $this->convertNullStringValue($this->BaisyoHokenState);
			$sqlMoji .= ", SikakuNum1 = " . $this->convertNullIntValue($this->SikakuNum1);
			$sqlMoji .= ", SikakuNum2 = " . $this->convertNullIntValue($this->SikakuNum2);
			$sqlMoji .= ", SikakuNum3 = " . $this->convertNullIntValue($this->SikakuNum3);
			$sqlMoji .= ", SikakuNum4 = " . $this->convertNullIntValue($this->SikakuNum4);
			$sqlMoji .= ", SikakuNum5 = " . $this->convertNullIntValue($this->SikakuNum5);
			$sqlMoji .= ", SikakuNum6 = " . $this->convertNullIntValue($this->SikakuNum6);
			$sqlMoji .= ", SikakuNum7 = " . $this->convertNullIntValue($this->SikakuNum7);
			$sqlMoji .= ", SikakuNum8 = " . $this->convertNullIntValue($this->SikakuNum8);
			$sqlMoji .= ", SikakuNum9 = " . $this->convertNullIntValue($this->SikakuNum9);
			$sqlMoji .= ", SikakuNum10 = " . $this->convertNullIntValue($this->SikakuNum10);
			$sqlMoji .= ", SikakuNum11 = " . $this->convertNullIntValue($this->SikakuNum11);
			$sqlMoji .= ", SikakuNum12 = " . $this->convertNullIntValue($this->SikakuNum12);

			$sqlMoji .= ", G01 = " . $this->convertNullStringValue($this->G01);
			$sqlMoji .= ", GyosyaShitauke = " . $this->convertNullStringValue($this->GyosyaShitauke);
			$sqlMoji .= ", Color = " . $this->convertNullStringValue($this->Color);


			$sqlMoji .= ", MukouFlg = " . $this->convertFlgToString($this->MukouFlg);
			$sqlMoji .= ", Created = " . $this->convertNullStringValue($this->Created);
			$sqlMoji .= ", Creator = " . $this->convertNullIntValue($this->Creator);
			$sqlMoji .= ", Updated = NOW()";
			$sqlMoji .= ", Updater = " . $this->convertNullIntValue($this->Updater);
			$sqlMoji .= " where GyosyaCD = $this->GyosyaCD";

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
