<?php

include_once _CLS_DIR . "SPFWLog.cls";

class ResultReport {
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
	var $id;

	var $ResultReport;
	var $Biko;

	var $ZyushinDengen;
	var $RendoubanConfirm;
	var $ZyushinConfirm;
	var $HukuZyushinConfirm;
	
	var $BukkenCD;
	var $KeibiCompany;
	var $SenyouKairoConfirm;

	var $PumpConfirm;
	var $AirPositionConfirm;
	var $WaterKentiConfirm;
	var $WaterShingouConfirm;
	var $AirAtsuryokuConfirm;
	var $HaisuiConfirm;
	var $SeigyoDengenConfirm;
	var $SenyouKairoConfirmForSPSetsubi;
	var $NozuruConfirm;
	var $HousyutsuConfirm;
	var $CleaningConfirm;
	var $HontaiDengenConfirm;
	var $HizyouDengenForKasai;
	var $TellKaisenConfirm;
	var $DoukanSetsuzoku;
	var $HizyouDengenForGasuSetsubi;
	var $KidouSetsuzoku;

	var $Sagyousya;


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

	function __construct($myDB = "",$id = -1) {
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
		settype($id,"integer");
		// 整数チェック
		if (!is_long($id) or $id == 0) {
			$this->Err = "ユーザーコード が不正です";
			$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
			unset($myLog);
			return FALSE;
		}

		$this->myDB = $myDB;
		$this->TableName = "tResultReportF";
		$this->id = $id;

		return TRUE;
	}

	function initialize(){
		$this->Err = "";

		$this->id = "";
		$this->ResultReport = "";
		$this->Biko = "";
		$this->ZyushinDengen = "";
		$this->RendoubanConfirm	 = "";

		$this->HukuZyushinConfirm = "";
		$this->BukkenCD = "";
		$this->KeibiCompany = "";
		$this->SenyouKairoConfirm = "";
		$this->ZyushinConfirm = "";

		$this->PumpConfirm = "";
		$this->AirPositionConfirm = "";
		$this->WaterKentiConfirm = "";
		$this->WaterShingouConfirm = "";
		$this->AirAtsuryokuConfirm = "";
		$this->HaisuiConfirm = "";
		$this->SeigyoDengenConfirm = "";
		$this->SenyouKairoConfirmForSPSetsubi = "";
		$this->NozuruConfirm = "";
		$this->HousyutsuConfirm = "";
		$this->CleaningConfirm = "";
		$this->HontaiDengenConfirm = "";
		$this->HizyouDengenForKasai = "";
		$this->TellKaisenConfirm = "";
		$this->DoukanSetsuzoku = "";
		$this->HizyouDengenForGasuSetsubi = "";
		$this->KidouSetsuzoku = "";
		$this->Sagyousya = "";


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
		$sqlMoji .= "id";
		$sqlMoji .= ",ResultReport";
		$sqlMoji .= ",Biko";
		$sqlMoji .= ",ZyushinDengen";
		$sqlMoji .= ",RendoubanConfirm";

		$sqlMoji .= ", HukuZyushinConfirm ";
		$sqlMoji .= ", BukkenCD ";
		$sqlMoji .= ", KeibiCompany ";
		$sqlMoji .= ", SenyouKairoConfirm ";
		$sqlMoji .= ", ZyushinConfirm ";
		$sqlMoji .= ", PumpConfirm ";
		$sqlMoji .= ", AirPositionConfirm ";
		$sqlMoji .= ", WaterKentiConfirm ";
		$sqlMoji .= ", WaterShingouConfirm ";
		$sqlMoji .= ", AirAtsuryokuConfirm ";
		$sqlMoji .= ", HaisuiConfirm ";
		$sqlMoji .= ", SeigyoDengenConfirm ";
		$sqlMoji .= ", SenyouKairoConfirmForSPSetsubi ";
		$sqlMoji .= ", NozuruConfirm ";
		$sqlMoji .= ", HousyutsuConfirm ";
		$sqlMoji .= ", CleaningConfirm ";
		$sqlMoji .= ", HontaiDengenConfirm ";
		$sqlMoji .= ", HizyouDengenForKasai ";
		$sqlMoji .= ", TellKaisenConfirm ";
		$sqlMoji .= ", DoukanSetsuzoku ";
		$sqlMoji .= ", HizyouDengenForGasuSetsubi ";
		$sqlMoji .= ", KidouSetsuzoku ";
		$sqlMoji .= ", Sagyousya ";


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
				$this->id,
				$this->ResultReport,
				$this->Biko,
				$this->ZyushinDengen,
				$this->RendoubanConfirm,

				$this->HukuZyushinConfirm,
				$this->BukkenCD,
				$this->KeibiCompany,
				$this->SenyouKairoConfirm,
				$this->ZyushinConfirm,

				$this->PumpConfirm,
				$this->AirPositionConfirm,
				$this->WaterKentiConfirm,
				$this->WaterShingouConfirm,
				$this->AirAtsuryokuConfirm,
				$this->HaisuiConfirm,
				$this->SeigyoDengenConfirm,
				$this->SenyouKairoConfirmForSPSetsubi,
				$this->NozuruConfirm,
				$this->HousyutsuConfirm,
				$this->CleaningConfirm,
				$this->HontaiDengenConfirm,
				$this->HizyouDengenForKasai,
				$this->TellKaisenConfirm,
				$this->DoukanSetsuzoku,
				$this->HizyouDengenForGasuSetsubi,
				$this->KidouSetsuzoku,
				$this->Sagyousya,

				$this->MukouFlg,
				$this->Created,
				$this->Creator,
				$this->Updated,
				$this->Updater
			) = $this->myDB->fetchRow($this->tmprtn,$RowNo);

			$this->ResultReport = stripslashes($this->ResultReport);
			$this->Biko = stripslashes($this->Biko);
			$this->ZyushinDengen = stripslashes($this->ZyushinDengen);
			$this->RendoubanConfirm	 = stripslashes($this->RendoubanConfirm);

			$this->HukuZyushinConfirm = stripslashes($this->HukuZyushinConfirm);
			$this->BukkenCD = stripslashes($this->BukkenCD);
			$this->KeibiCompany = stripslashes($this->KeibiCompany);
			$this->SenyouKairoConfirm = stripslashes($this->SenyouKairoConfirm);
			$this->ZyushinConfirm = stripslashes($this->ZyushinConfirm);

			$this->PumpConfirm = stripslashes($this->PumpConfirm);
			$this->AirPositionConfirm = stripslashes($this->AirPositionConfirm);
			$this->WaterKentiConfirm = stripslashes($this->WaterKentiConfirm);
			$this->WaterShingouConfirm = stripslashes($this->WaterShingouConfirm);
			$this->AirAtsuryokuConfirm = stripslashes($this->AirAtsuryokuConfirm);
			$this->HaisuiConfirm = stripslashes($this->HaisuiConfirm);
			$this->SeigyoDengenConfirm = stripslashes($this->SeigyoDengenConfirm);
			$this->SenyouKairoConfirmForSPSetsubi = stripslashes($this->SenyouKairoConfirmForSPSetsubi);
			$this->NozuruConfirm = stripslashes($this->NozuruConfirm);
			$this->HousyutsuConfirm = stripslashes($this->HousyutsuConfirm);
			$this->CleaningConfirm = stripslashes($this->CleaningConfirm);
			$this->HontaiDengenConfirm = stripslashes($this->HontaiDengenConfirm);
			$this->HizyouDengenForKasai = stripslashes($this->HizyouDengenForKasai);
			$this->TellKaisenConfirm = stripslashes($this->TellKaisenConfirm);
			$this->DoukanSetsuzoku = stripslashes($this->DoukanSetsuzoku);
			$this->HizyouDengenForGasuSetsubi = stripslashes($this->HizyouDengenForGasuSetsubi);
			$this->KidouSetsuzoku = stripslashes($this->KidouSetsuzoku);
			$this->Sagyousya = stripslashes($this->Sagyousya);


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
		
		if ($this->id < 0) {
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
					$sqlMoji = "select id from $this->TableName order by id desc limit 1";
					$maxCD = $this->myDB->getOneValue($sqlMoji);
					$maxCD = ($maxCD == -1) ? 0 : $maxCD;
					$maxCD++;

					// step 4. インサート
					$sqlMoji = "Insert into $this->TableName (";
					$sqlMoji .= "id";
					if ($this->ResultReport != "") $sqlMoji .= ",ResultReport";
					if ($this->Biko != "") $sqlMoji .= ",Biko";
					if ($this->ZyushinDengen != "") $sqlMoji .= ", ZyushinDengen";
					if ($this->RendoubanConfirm	 != "") $sqlMoji .= ", RendoubanConfirm";
					if ($this->HukuZyushinConfirm != "") $sqlMoji .= ", HukuZyushinConfirm";
					if ($this->BukkenCD != "") $sqlMoji .= ", BukkenCD";
					if ($this->KeibiCompany != "") $sqlMoji .= ", KeibiCompany";
					if ($this->SenyouKairoConfirm != "") $sqlMoji .= ", SenyouKairoConfirm";
					if ($this->ZyushinConfirm != "") $sqlMoji .= ", ZyushinConfirm";

					if ($this->PumpConfirm != "") $sqlMoji .= ", PumpConfirm";
					if ($this->AirPositionConfirm != "") $sqlMoji .= ", AirPositionConfirm";
					if ($this->WaterKentiConfirm != "") $sqlMoji .= ", WaterKentiConfirm";
					if ($this->WaterShingouConfirm != "") $sqlMoji .= ", WaterShingouConfirm";
					if ($this->AirAtsuryokuConfirm != "") $sqlMoji .= ", AirAtsuryokuConfirm";
					if ($this->HaisuiConfirm != "") $sqlMoji .= ", HaisuiConfirm";
					if ($this->SeigyoDengenConfirm != "") $sqlMoji .= ", SeigyoDengenConfirm";
					if ($this->SenyouKairoConfirmForSPSetsubi != "") $sqlMoji .= ", SenyouKairoConfirmForSPSetsubi";
					if ($this->NozuruConfirm != "") $sqlMoji .= ", NozuruConfirm";
					if ($this->HousyutsuConfirm != "") $sqlMoji .= ", HousyutsuConfirm";
					if ($this->CleaningConfirm != "") $sqlMoji .= ", CleaningConfirm";
					if ($this->HontaiDengenConfirm != "") $sqlMoji .= ", HontaiDengenConfirm";
					if ($this->HizyouDengenForKasai != "") $sqlMoji .= ", HizyouDengenForKasai";
					if ($this->TellKaisenConfirm != "") $sqlMoji .= ", TellKaisenConfirm";
					if ($this->DoukanSetsuzoku != "") $sqlMoji .= ", DoukanSetsuzoku";
					if ($this->HizyouDengenForGasuSetsubi != "") $sqlMoji .= ", HizyouDengenForGasuSetsubi";
					if ($this->KidouSetsuzoku != "") $sqlMoji .= ", KidouSetsuzoku";
					if ($this->Sagyousya != "") $sqlMoji .= ", Sagyousya";



					if ($this->MukouFlg != "") $sqlMoji .= ", MukouFlg";
					$sqlMoji .= ", Created";
					if ($this->Creator != "") $sqlMoji .= ", Creator";
					$sqlMoji .= ", Updated";
					if ($this->Updater != "") $sqlMoji .= ", Updater";
					$sqlMoji .= ") values (";
					$sqlMoji .= "$maxCD";
					
					if ($this->ResultReport != "") $sqlMoji .= ", '" . addslashes($this->ResultReport) . "'";
					if ($this->Biko != "") $sqlMoji .= ", '" . addslashes($this->Biko) . "'";
					if ($this->ZyushinDengen != "") $sqlMoji .= ",'" . addslashes($this->ZyushinDengen) . "'";
					if ($this->RendoubanConfirm	!= "") $sqlMoji .= ",'" . addslashes($this->RendoubanConfirm) . "'";
					if ($this->HukuZyushinConfirm != "") $sqlMoji .= ", '" . addslashes($this->HukuZyushinConfirm) . "'";
					if ($this->BukkenCD != "") $sqlMoji .= ", '" . addslashes($this->BukkenCD) . "'";
					if ($this->KeibiCompany != "") $sqlMoji .= ", '" . addslashes($this->KeibiCompany) . "'";
					if ($this->SenyouKairoConfirm != "") $sqlMoji .= ", '" . addslashes($this->SenyouKairoConfirm) . "'";
					if ($this->ZyushinConfirm != "") $sqlMoji .= ", '" . addslashes($this->ZyushinConfirm) . "'";
					if ($this->PumpConfirm != "") $sqlMoji .= ", '" . addslashes($this->PumpConfirm) . "'";
					if ($this->AirPositionConfirm != "") $sqlMoji .= ", '" . addslashes($this->AirPositionConfirm) . "'";
					if ($this->WaterKentiConfirm != "") $sqlMoji .= ", '" . addslashes($this->WaterKentiConfirm) . "'";
					if ($this->WaterShingouConfirm != "") $sqlMoji .= ", '" . addslashes($this->WaterShingouConfirm) . "'";
					if ($this->AirAtsuryokuConfirm != "") $sqlMoji .= ", '" . addslashes($this->AirAtsuryokuConfirm) . "'";
					if ($this->HaisuiConfirm != "") $sqlMoji .= ", '" . addslashes($this->HaisuiConfirm) . "'";
					if ($this->SeigyoDengenConfirm != "") $sqlMoji .= ", '" . addslashes($this->SeigyoDengenConfirm) . "'";
					if ($this->SenyouKairoConfirmForSPSetsubi != "") $sqlMoji .= ", '" . addslashes($this->SenyouKairoConfirmForSPSetsubi) . "'";
					if ($this->NozuruConfirm != "") $sqlMoji .= ", '" . addslashes($this->NozuruConfirm) . "'";
					if ($this->HousyutsuConfirm != "") $sqlMoji .= ", '" . addslashes($this->HousyutsuConfirm) . "'";
					if ($this->CleaningConfirm != "") $sqlMoji .= ", '" . addslashes($this->CleaningConfirm) . "'";
					if ($this->HontaiDengenConfirm != "") $sqlMoji .= ", '" . addslashes($this->HontaiDengenConfirm) . "'";
					if ($this->HizyouDengenForKasai != "") $sqlMoji .= ", '" . addslashes($this->HizyouDengenForKasai) . "'";
					if ($this->TellKaisenConfirm != "") $sqlMoji .= ", '" . addslashes($this->TellKaisenConfirm) . "'";
					if ($this->DoukanSetsuzoku != "") $sqlMoji .= ", '" . addslashes($this->DoukanSetsuzoku) . "'";
					if ($this->HizyouDengenForGasuSetsubi != "") $sqlMoji .= ", '" . addslashes($this->HizyouDengenForGasuSetsubi) . "'";
					if ($this->KidouSetsuzoku != "") $sqlMoji .= ", '" . addslashes($this->KidouSetsuzoku) . "'";
					if ($this->Sagyousya != "") $sqlMoji .= ", '" . addslashes($this->Sagyousya) . "'";

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
							$this->id = $maxCD;
						}
					}
				}
			}
		} else {
			// アップデート
			$sqlMoji = "Update $this->TableName set ";
			$sqlMoji .= "id = $this->id";
			$sqlMoji .= ", ResultReport = " . $this->convertNullStringValue($this->ResultReport);
			$sqlMoji .= ", Biko = " . $this->convertNullStringValue($this->Biko);
			$sqlMoji .= ", ZyushinDengen = " . $this->convertNullStringValue($this->ZyushinDengen);
			$sqlMoji .= ", RendoubanConfirm	 = " . $this->convertNullStringValue($this->RendoubanConfirm);
			$sqlMoji .= ", HukuZyushinConfirm = " . $this->convertNullStringValue($this->HukuZyushinConfirm);
			$sqlMoji .= ", BukkenCD = " . $this->convertNullStringValue($this->BukkenCD);
			$sqlMoji .= ", KeibiCompany = " . $this->convertNullStringValue($this->KeibiCompany);
			$sqlMoji .= ", SenyouKairoConfirm = " . $this->convertNullStringValue($this->SenyouKairoConfirm);
			$sqlMoji .= ", ZyushinConfirm = " . $this->convertNullStringValue($this->ZyushinConfirm);

			$sqlMoji .= ", PumpConfirm = " . $this->convertNullStringValue($this->PumpConfirm);
			$sqlMoji .= ", AirPositionConfirm = " . $this->convertNullStringValue($this->AirPositionConfirm);
			$sqlMoji .= ", WaterKentiConfirm = " . $this->convertNullStringValue($this->WaterKentiConfirm);
			$sqlMoji .= ", WaterShingouConfirm = " . $this->convertNullStringValue($this->WaterShingouConfirm);
			$sqlMoji .= ", AirAtsuryokuConfirm = " . $this->convertNullStringValue($this->AirAtsuryokuConfirm);
			$sqlMoji .= ", HaisuiConfirm = " . $this->convertNullStringValue($this->HaisuiConfirm);
			$sqlMoji .= ", SeigyoDengenConfirm = " . $this->convertNullStringValue($this->SeigyoDengenConfirm);
			$sqlMoji .= ", SenyouKairoConfirmForSPSetsubi = " . $this->convertNullStringValue($this->SenyouKairoConfirmForSPSetsubi);
			$sqlMoji .= ", NozuruConfirm = " . $this->convertNullStringValue($this->NozuruConfirm);
			$sqlMoji .= ", HousyutsuConfirm = " . $this->convertNullStringValue($this->HousyutsuConfirm);
			$sqlMoji .= ", CleaningConfirm = " . $this->convertNullStringValue($this->CleaningConfirm);
			$sqlMoji .= ", HontaiDengenConfirm = " . $this->convertNullStringValue($this->HontaiDengenConfirm);
			$sqlMoji .= ", HizyouDengenForKasai = " . $this->convertNullStringValue($this->HizyouDengenForKasai);
			$sqlMoji .= ", TellKaisenConfirm = " . $this->convertNullStringValue($this->HontaiDengenConfirm);
			$sqlMoji .= ", DoukanSetsuzoku = " . $this->convertNullStringValue($this->DoukanSetsuzoku);
			$sqlMoji .= ", HizyouDengenForGasuSetsubi = " . $this->convertNullStringValue($this->HizyouDengenForGasuSetsubi);
			$sqlMoji .= ", KidouSetsuzoku = " . $this->convertNullStringValue($this->KidouSetsuzoku);
			$sqlMoji .= ", Sagyousya = " . $this->convertNullStringValue($this->Sagyousya);

			$sqlMoji .= ", MukouFlg = " . $this->convertFlgToString($this->MukouFlg);
			$sqlMoji .= ", Created = " . $this->convertNullStringValue($this->Created);
			$sqlMoji .= ", Creator = " . $this->convertNullIntValue($this->Creator);
			$sqlMoji .= ", Updated = NOW()";
			$sqlMoji .= ", Updater = " . $this->convertNullIntValue($this->Updater);
			$sqlMoji .= " where id = $this->id";

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
