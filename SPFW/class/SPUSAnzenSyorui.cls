<?php


// 依存
include_once _CLS_DIR . "SPFWLog.cls";

class AnzenSyorui {
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
	var $AnzenSyoruiCD;
	/**
	 * クライアントコード
	 */
	var $KojiCD;
	/**
	 * メニュータイトル
	 */
	var $SyoruiSakuseiDate;
	/**
	 * 略称
	 */
	var $AnzenKojiNaiyo;
	/**
	 * 所要ライン数
	 */
	var $AnzenKojiName;
	/**
	 * 所要時間区分
	 */
	var $AnzenHattyusya;
	/**
	 * 備考
	 */
	var $AnzenHattyusyaAddress;

	var $AnzenHattyusyaTEL;
	var $Contract;
	var $BukkenTanto;
	var $EmergencyTEL;
	var $Sitentyo;
	var $EigyoSyotyo;
	var $SyuninGijutusya;
	var $ATEContract;
	var $HealthNumber;
	var $NenkinNumber;
	var $HNSyurui;
	var $EmployNumber;
	var $AIPHONETanto;
	var $AIPHONEEmergencyTEL;
	var $Yobi1;
	var $Yobi2;
	var $Yobi3;
	var $Yobi4;
	var $Yobi5;
	var $Yobi6;
	var $AIPHONEContract;
	var $HasekoSiten;
	var $HasekoAddress;
	var $HasekoTEL;
	var $HasekoTanto;
	var $HasekoAnzenYakuin;
	var $HasekoAnzenSekininsya;
	var $HasekoAnzenTanto;
	var $HasekoKojiYakuin;
	var $HasekoKojiSekininsya;
	var $HasekoKojiTanto;
	var $HasekoGenbaDairinin;
	var $HasekoShokucho;
	var $HasekoSyuninGijyutusya;
	var $HasekoSenmonGijyutusya;
	var $HasekoTantoKojiNaiyo;
	var $Haseko2jiAnzenSekininsya;
	var $Haseko2jiSyuninGijyutusya;
	var $Haseko2jiSenmonGijyutusya;
	var $Haseko2jiTantoKojiNaiyo;


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

	function __construct($myDB = "",$AnzenSyoruiCD = -1) {
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
		settype($AnzenSyoruiCD,"integer");
		// 整数チェック
		if (!is_long($AnzenSyoruiCD) or $AnzenSyoruiCD == 0) {
			$this->Err = "ユーザーコード が不正です";
			$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
			unset($myLog);
			return FALSE;
		}

		$this->myDB = $myDB;
		$this->TableName = "tAnzenSyoruiF";
		$this->AnzenSyoruiCD = $AnzenSyoruiCD;

		return TRUE;
	}

	function initialize(){
		$this->Err = "";

		$this->AnzenSyoruiCD = "";
		$this->KojiCD = "";
		$this->SyoruiSakuseiDate = "";
		$this->AnzenKojiNaiyo = "";
		$this->AnzenKojiName = "";
		$this->AnzenHattyusya = "";
		$this->AnzenHattyusyaAddress = "";
		$this->AnzenHattyusyaTEL = "";
		$this->Contract = "";
		$this->Sitentyo = "";
		$this->EigyoSyotyo = "";
		$this->SyuninGijutusya = "";
		$this->BukkenTanto = "";
		$this->EmergencyTEL = "";
		$this->ATEContract = "";
		$this->HealthNumber = "";
		$this->NenkinNumber = "";
		$this->HNSyurui = "";
		$this->EmployNumber = "";
		$this->AIPHONETanto = "";
		$this->AIPHONEEmergencyTEL = "";
		$this->Yobi1 = "";
		$this->Yobi2 = "";
		$this->Yobi3 = "";
		$this->Yobi4 = "";
		$this->Yobi5 = "";
		$this->Yobi6 = "";
		$this->AIPHONEContract = "";
		$this->HasekoSiten = "";
		$this->HasekoAddress = "";
		$this->HasekoTEL = "";
		$this->HasekoTanto = "";
		$this->HasekoAnzenYakuin = "";
		$this->HasekoAnzenSekininsya = "";
		$this->HasekoAnzenTanto = "";
		$this->HasekoKojiYakuin = "";
		$this->HasekoKojiSekininsya = "";
		$this->HasekoKojiTanto = "";
		$this->HasekoGenbaDairinin = "";
		$this->HasekoShokucho = "";
		$this->HasekoSyuninGijyutusya = "";
		$this->HasekoSenmonGijyutusya = "";
		$this->HasekoTantoKojiNaiyo = "";
		$this->Haseko2jiAnzenSekininsya = "";
		$this->Haseko2jiSyuninGijyutusya = "";
		$this->Haseko2jiSenmonGijyutusya = "";
		$this->Haseko2jiTantoKojiNaiyo = "";

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
		$sqlMoji .= "AnzenSyoruiCD";
		$sqlMoji .= ", KojiCD";
		$sqlMoji .= ", SyoruiSakuseiDate";
		$sqlMoji .= ", AnzenKojiNaiyo";
		$sqlMoji .= ", AnzenKojiName";
		$sqlMoji .= ", AnzenHattyusya";
		$sqlMoji .= ", AnzenHattyusyaAddress";
		$sqlMoji .= ", AnzenHattyusyaTEL";
		$sqlMoji .= ", Contract";
		$sqlMoji .= ", BukkenTanto";
		$sqlMoji .= ", EmergencyTEL";
		$sqlMoji .= ", Sitentyo";
		$sqlMoji .= ", EigyoSyotyo";
		$sqlMoji .= ", SyuninGijutusya";
		$sqlMoji .= ", ATEContract ";
		$sqlMoji .= ", HealthNumber ";
		$sqlMoji .= ", NenkinNumber ";
		$sqlMoji .= ", HNSyurui ";
		$sqlMoji .= ", EmployNumber ";
		$sqlMoji .= ", AIPHONETanto ";
		$sqlMoji .= ", AIPHONEEmergencyTEL ";
		$sqlMoji .= ", Yobi1 ";
		$sqlMoji .= ", Yobi2 ";
		$sqlMoji .= ", Yobi3 ";
		$sqlMoji .= ", Yobi4 ";
		$sqlMoji .= ", Yobi5 ";
		$sqlMoji .= ", Yobi6 ";
		$sqlMoji .= ", AIPHONEContract ";
		$sqlMoji .= ", HasekoSiten ";
		$sqlMoji .= ", HasekoAddress ";
		$sqlMoji .= ", HasekoTEL ";
		$sqlMoji .= ", HasekoTanto ";
		$sqlMoji .= ", HasekoAnzenYakuin ";
		$sqlMoji .= ", HasekoAnzenSekininsya ";
		$sqlMoji .= ", HasekoAnzenTanto ";
		$sqlMoji .= ", HasekoKojiYakuin ";
		$sqlMoji .= ", HasekoKojiSekininsya ";
		$sqlMoji .= ", HasekoKojiTanto ";
		$sqlMoji .= ", HasekoGenbaDairinin ";
		$sqlMoji .= ", HasekoShokucho ";
		$sqlMoji .= ", HasekoSyuninGijyutusya ";
		$sqlMoji .= ", HasekoSenmonGijyutusya ";
		$sqlMoji .= ", HasekoTantoKojiNaiyo ";
		$sqlMoji .= ", Haseko2jiAnzenSekininsya ";
		$sqlMoji .= ", Haseko2jiSyuninGijyutusya ";
		$sqlMoji .= ", Haseko2jiSenmonGijyutusya ";
		$sqlMoji .= ", Haseko2jiTantoKojiNaiyo ";

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
				$this->AnzenSyoruiCD,
				$this->KojiCD,
				$this->SyoruiSakuseiDate,
				$this->AnzenKojiNaiyo,
				$this->AnzenKojiName,
				$this->AnzenHattyusya,
				$this->AnzenHattyusyaAddress,
				$this->AnzenHattyusyaTEL,
				$this->Contract,
				$this->BukkenTanto,
				$this->EmergencyTEL,
				$this->Sitentyo,
				$this->EigyoSyotyo,
				$this->SyuninGijutusya,
				$this->ATEContract,
				$this->HealthNumber,
				$this->NenkinNumber,
				$this->HNSyurui,
				$this->EmployNumber,
				$this->AIPHONETanto,
				$this->AIPHONEEmergencyTEL,
				$this->Yobi1,
				$this->Yobi2,
				$this->Yobi3,
				$this->Yobi4,
				$this->Yobi5,
				$this->Yobi6,
				$this->AIPHONEContract,
				$this->HasekoSiten,
				$this->HasekoAddress,
				$this->HasekoTEL,
				$this->HasekoTanto,
				$this->HasekoAnzenYakuin,
				$this->HasekoAnzenSekininsya,
				$this->HasekoAnzenTanto,
				$this->HasekoKojiYakuin,
				$this->HasekoKojiSekininsya,
				$this->HasekoKojiTanto,
				$this->HasekoGenbaDairinin,
				$this->HasekoShokucho,
				$this->HasekoSyuninGijyutusya,
				$this->HasekoSenmonGijyutusya,
				$this->HasekoTantoKojiNaiyo,
				$this->Haseko2jiAnzenSekininsya,
				$this->Haseko2jiSyuninGijyutusya,
				$this->Haseko2jiSenmonGijyutusya,
				$this->Haseko2jiTantoKojiNaiyo,

				$this->MukouFlg,
				$this->Created,
				$this->Creator,
				$this->Updated,
				$this->Updater
			) = $this->myDB->fetchRow($this->tmprtn,$RowNo);

			$this->SyoruiSakuseiDate = stripslashes($this->SyoruiSakuseiDate);
			$this->AnzenKojiNaiyo = stripslashes($this->AnzenKojiNaiyo);
			$this->AnzenHattyusyaAddress = stripslashes($this->AnzenHattyusyaAddress);
			$this->AnzenKojiName = stripslashes($this->AnzenKojiName);
			$this->AnzenHattyusya = stripslashes($this->AnzenHattyusya);
			$this->Contract = stripslashes($this->Contract);
			$this->BukkenTanto = stripslashes($this->BukkenTanto);
			$this->SyuninGijutusya = stripslashes($this->SyuninGijutusya);
			$this->Sitentyo = stripslashes($this->Sitentyo);
			$this->EigyoSyotyo = stripslashes($this->EigyoSyotyo);
			$this->ATEContract = stripslashes($this->ATEContract);
			$this->HealthNumber = stripslashes($this->HealthNumber);
			$this->NenkinNumber = stripslashes($this->NenkinNumber);
			$this->HNSyurui = stripslashes($this->HNSyurui);
			$this->EmployNumber = stripslashes($this->EmployNumber);
			$this->AIPHONETanto = stripslashes($this->AIPHONETanto);
			$this->AIPHONEEmergencyTEL = stripslashes($this->AIPHONEEmergencyTEL);
			$this->Yobi1 = stripslashes($this->Yobi1);
			$this->Yobi2 = stripslashes($this->Yobi2);
			$this->Yobi3 = stripslashes($this->Yobi3);
			$this->Yobi4 = stripslashes($this->Yobi4);
			$this->Yobi5 = stripslashes($this->Yobi5);
			$this->Yobi6 = stripslashes($this->Yobi6);
			$this->AIPHONEContract = stripslashes($this->AIPHONEContract);
			$this->HasekoSiten = stripslashes($this->HasekoSiten);
			$this->HasekoAddress = stripslashes($this->HasekoAddress);
			$this->HasekoTEL = stripslashes($this->HasekoTEL);
			$this->HasekoTanto = stripslashes($this->HasekoTanto);
			$this->HasekoAnzenYakuin = stripslashes($this->HasekoAnzenYakuin);
			$this->HasekoAnzenSekininsya = stripslashes($this->HasekoAnzenSekininsya);
			$this->HasekoAnzenTanto = stripslashes($this->HasekoAnzenTanto);
			$this->HasekoKojiYakuin = stripslashes($this->HasekoKojiYakuin);
			$this->HasekoKojiSekininsya = stripslashes($this->HasekoKojiSekininsya);
			$this->HasekoKojiTanto = stripslashes($this->HasekoKojiTanto);
			$this->HasekoGenbaDairinin = stripslashes($this->HasekoGenbaDairinin);
			$this->HasekoShokucho = stripslashes($this->HasekoShokucho);
			$this->HasekoSyuninGijyutusya = stripslashes($this->HasekoSyuninGijyutusya);
			$this->HasekoSenmonGijyutusya = stripslashes($this->HasekoSenmonGijyutusya);
			$this->HasekoTantoKojiNaiyo = stripslashes($this->HasekoTantoKojiNaiyo);
			$this->Haseko2jiAnzenSekininsya = stripslashes($this->Haseko2jiAnzenSekininsya);
			$this->Haseko2jiSyuninGijyutusya = stripslashes($this->Haseko2jiSyuninGijyutusya);
			$this->Haseko2jiSenmonGijyutusya = stripslashes($this->Haseko2jiSenmonGijyutusya);
			$this->Haseko2jiTantoKojiNaiyo = stripslashes($this->Haseko2jiTantoKojiNaiyo);


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
		
		if ($this->AnzenSyoruiCD < 0) {
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
					$sqlMoji = "select AnzenSyoruiCD from $this->TableName order by AnzenSyoruiCD desc limit 1";
					$maxCD = $this->myDB->getOneValue($sqlMoji);
					$maxCD = ($maxCD == -1) ? 0 : $maxCD;
					$maxCD++;

					// step 4. インサート
					$sqlMoji = "Insert into $this->TableName (";
					$sqlMoji .= "AnzenSyoruiCD";
					if ($this->KojiCD != "") $sqlMoji .= ", KojiCD";
					if ($this->SyoruiSakuseiDate != "") $sqlMoji .= ", SyoruiSakuseiDate";
					if ($this->AnzenKojiNaiyo != "") $sqlMoji .= ", AnzenKojiNaiyo";
					if ($this->AnzenKojiName != "") $sqlMoji .= ", AnzenKojiName";
					if ($this->AnzenHattyusya != "") $sqlMoji .= ", AnzenHattyusya";
					if ($this->AnzenHattyusyaAddress != "") $sqlMoji .= ", AnzenHattyusyaAddress";
					if ($this->AnzenHattyusyaTEL != "") $sqlMoji .= ", AnzenHattyusyaTEL";
					if ($this->Contract != "") $sqlMoji .= ", Contract";
					if ($this->Sitentyo != "") $sqlMoji .= ", Sitentyo";
					if ($this->EigyoSyotyo != "") $sqlMoji .= ", EigyoSyotyo";
					if ($this->SyuninGijutusya != "") $sqlMoji .= ", SyuninGijutusya";
					if ($this->BukkenTanto != "") $sqlMoji .= ", BukkenTanto";
					if ($this->EmergencyTEL != "") $sqlMoji .= ", EmergencyTEL";
					if ($this->ATEContract != "") $sqlMoji .= ", ATEContract";
					if ($this->HealthNumber != "") $sqlMoji .= ", HealthNumber";
					if ($this->NenkinNumber != "") $sqlMoji .= ", NenkinNumber";
					if ($this->HNSyurui != "") $sqlMoji .= ", HNSyurui";
					if ($this->EmployNumber != "") $sqlMoji .= ", EmployNumber";
					if ($this->AIPHONETanto != "") $sqlMoji .= ", AIPHONETanto";
					if ($this->AIPHONEEmergencyTEL != "") $sqlMoji .= ", AIPHONEEmergencyTEL";
					if ($this->Yobi1 != "") $sqlMoji .= ", Yobi1";
					if ($this->Yobi2 != "") $sqlMoji .= ", Yobi2";
					if ($this->Yobi3 != "") $sqlMoji .= ", Yobi3";
					if ($this->Yobi4 != "") $sqlMoji .= ", Yobi4";
					if ($this->Yobi5 != "") $sqlMoji .= ", Yobi5";
					if ($this->Yobi6 != "") $sqlMoji .= ", Yobi6";
					if ($this->AIPHONEContract != "") $sqlMoji .= ", AIPHONEContract";
					if ($this->HasekoSiten != "") $sqlMoji .= ", HasekoSiten";
					if ($this->HasekoAddress != "") $sqlMoji .= ", HasekoAddress";
					if ($this->HasekoTEL != "") $sqlMoji .= ", HasekoTEL";
					if ($this->HasekoTanto != "") $sqlMoji .= ", HasekoTanto";
					if ($this->HasekoAnzenYakuin != "") $sqlMoji .= ", HasekoAnzenYakuin";
					if ($this->HasekoAnzenSekininsya != "") $sqlMoji .= ", HasekoAnzenSekininsya";
					if ($this->HasekoAnzenTanto != "") $sqlMoji .= ", HasekoAnzenTanto";
					if ($this->HasekoKojiYakuin != "") $sqlMoji .= ", HasekoKojiYakuin";
					if ($this->HasekoKojiSekininsya != "") $sqlMoji .= ", HasekoKojiSekininsya";
					if ($this->HasekoKojiTanto != "") $sqlMoji .= ", HasekoKojiTanto";
					if ($this->HasekoGenbaDairinin != "") $sqlMoji .= ", HasekoGenbaDairinin";
					if ($this->HasekoShokucho != "") $sqlMoji .= ", HasekoShokucho";
					if ($this->HasekoSyuninGijyutusya != "") $sqlMoji .= ", HasekoSyuninGijyutusya";
					if ($this->HasekoSenmonGijyutusya != "") $sqlMoji .= ", HasekoSenmonGijyutusya";
					if ($this->HasekoTantoKojiNaiyo != "") $sqlMoji .= ", HasekoTantoKojiNaiyo";
					if ($this->Haseko2jiAnzenSekininsya != "") $sqlMoji .= ", Haseko2jiAnzenSekininsya";
					if ($this->Haseko2jiSyuninGijyutusya != "") $sqlMoji .= ", Haseko2jiSyuninGijyutusya";
					if ($this->Haseko2jiSenmonGijyutusya != "") $sqlMoji .= ", Haseko2jiSenmonGijyutusya";
					if ($this->Haseko2jiTantoKojiNaiyo != "") $sqlMoji .= ", Haseko2jiTantoKojiNaiyo";


					if ($this->MukouFlg != "") $sqlMoji .= ", MukouFlg";
					$sqlMoji .= ", Created";
					if ($this->Creator != "") $sqlMoji .= ", Creator";
					$sqlMoji .= ", Updated";
					if ($this->Updater != "") $sqlMoji .= ", Updater";
					$sqlMoji .= ") values (";
					$sqlMoji .= "$maxCD";
					if ($this->KojiCD != "") $sqlMoji .= ", $this->KojiCD";
					if ($this->SyoruiSakuseiDate != "") $sqlMoji .= ", '" . addslashes($this->SyoruiSakuseiDate) . "'";
					if ($this->AnzenKojiNaiyo != "") $sqlMoji .= ", '" . addslashes($this->AnzenKojiNaiyo) . "'";
					if ($this->AnzenKojiName != "") $sqlMoji .= ", '" . addslashes($this->AnzenKojiName) . "'";
					if ($this->AnzenHattyusya != "") $sqlMoji .= ", '" . addslashes($this->AnzenHattyusya) . "'";
					if ($this->AnzenHattyusyaAddress != "") $sqlMoji .= ", '" . addslashes($this->AnzenHattyusyaAddress) . "'";
					if ($this->AnzenHattyusyaTEL != "") $sqlMoji .= ", '" . addslashes($this->AnzenHattyusyaTEL) . "'";
					if ($this->Contract != "") $sqlMoji .= ", '" . addslashes($this->Contract) . "'";
					if ($this->Sitentyo != "") $sqlMoji .= ", '" . addslashes($this->Sitentyo) . "'";
					if ($this->EigyoSyotyo != "") $sqlMoji .= ", '" . addslashes($this->EigyoSyotyo) . "'";
					if ($this->SyuninGijutusya != "") $sqlMoji .= ", '" . addslashes($this->SyuninGijutusya) . "'";
					if ($this->BukkenTanto != "") $sqlMoji .= ", '" . addslashes($this->BukkenTanto) . "'";
					if ($this->EmergencyTEL != "") $sqlMoji .= ", '" . addslashes($this->EmergencyTEL) . "'";
					if ($this->ATEContract != "") $sqlMoji .= ", '" . addslashes($this->ATEContract) . "'";
					if ($this->HealthNumber != "") $sqlMoji .= ", '" . addslashes($this->HealthNumber) . "'";
					if ($this->NenkinNumber != "") $sqlMoji .= ", '" . addslashes($this->NenkinNumber) . "'";
					if ($this->HNSyurui != "") $sqlMoji .= ", '" . addslashes($this->HNSyurui) . "'";
					if ($this->EmployNumber != "") $sqlMoji .= ", '" . addslashes($this->EmployNumber) . "'";
					if ($this->AIPHONETanto != "") $sqlMoji .= ", '" . addslashes($this->AIPHONETanto) . "'";
					if ($this->AIPHONEEmergencyTEL != "") $sqlMoji .= ", '" . addslashes($this->AIPHONEEmergencyTEL) . "'";
					if ($this->Yobi1 != "") $sqlMoji .= ", '" . addslashes($this->Yobi1) . "'";
					if ($this->Yobi2 != "") $sqlMoji .= ", '" . addslashes($this->Yobi2) . "'";
					if ($this->Yobi3 != "") $sqlMoji .= ", '" . addslashes($this->Yobi3) . "'";
					if ($this->Yobi4 != "") $sqlMoji .= ", '" . addslashes($this->Yobi4) . "'";
					if ($this->Yobi5 != "") $sqlMoji .= ", '" . addslashes($this->Yobi5) . "'";
					if ($this->Yobi6 != "") $sqlMoji .= ", '" . addslashes($this->Yobi6) . "'";
					if ($this->AIPHONEContract != "") $sqlMoji .= ", '" . addslashes($this->AIPHONEContract) . "'";
					if ($this->HasekoSiten != "") $sqlMoji .= ", '" . addslashes($this->HasekoSiten) . "'";
					if ($this->HasekoAddress != "") $sqlMoji .= ", '" . addslashes($this->HasekoAddress) . "'";
					if ($this->HasekoTEL != "") $sqlMoji .= ", '" . addslashes($this->HasekoTEL) . "'";
					if ($this->HasekoTanto != "") $sqlMoji .= ", '" . addslashes($this->HasekoTanto) . "'";
					if ($this->HasekoAnzenYakuin != "") $sqlMoji .= ", '" . addslashes($this->HasekoAnzenYakuin) . "'";
					if ($this->HasekoAnzenSekininsya != "") $sqlMoji .= ", '" . addslashes($this->HasekoAnzenSekininsya) . "'";
					if ($this->HasekoAnzenTanto != "") $sqlMoji .= ", '" . addslashes($this->HasekoAnzenTanto) . "'";
					if ($this->HasekoKojiYakuin != "") $sqlMoji .= ", '" . addslashes($this->HasekoKojiYakuin) . "'";
					if ($this->HasekoKojiSekininsya != "") $sqlMoji .= ", '" . addslashes($this->HasekoKojiSekininsya) . "'";
					if ($this->HasekoKojiTanto != "") $sqlMoji .= ", '" . addslashes($this->HasekoKojiTanto) . "'";
					if ($this->HasekoGenbaDairinin != "") $sqlMoji .= ", '" . addslashes($this->HasekoGenbaDairinin) . "'";
					if ($this->HasekoShokucho != "") $sqlMoji .= ", '" . addslashes($this->HasekoShokucho) . "'";
					if ($this->HasekoSyuninGijyutusya != "") $sqlMoji .= ", '" . addslashes($this->HasekoSyuninGijyutusya) . "'";
					if ($this->HasekoSenmonGijyutusya != "") $sqlMoji .= ", '" . addslashes($this->HasekoSenmonGijyutusya) . "'";
					if ($this->HasekoTantoKojiNaiyo != "") $sqlMoji .= ", '" . addslashes($this->HasekoTantoKojiNaiyo) . "'";
					if ($this->Haseko2jiAnzenSekininsya != "") $sqlMoji .= ", '" . addslashes($this->Haseko2jiAnzenSekininsya) . "'";
					if ($this->Haseko2jiSyuninGijyutusya != "") $sqlMoji .= ", '" . addslashes($this->Haseko2jiSyuninGijyutusya) . "'";
					if ($this->Haseko2jiSenmonGijyutusya != "") $sqlMoji .= ", '" . addslashes($this->Haseko2jiSenmonGijyutusya) . "'";
					if ($this->Haseko2jiTantoKojiNaiyo != "") $sqlMoji .= ", '" . addslashes($this->Haseko2jiTantoKojiNaiyo) . "'";

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
							$this->AnzenSyoruiCD = $maxCD;
						}
					}
				}
			}
		} else {
			// アップデート
			$sqlMoji = "Update $this->TableName set ";
			$sqlMoji .= "AnzenSyoruiCD = $this->AnzenSyoruiCD";
			$sqlMoji .= ", KojiCD = " . $this->convertNullIntValue($this->KojiCD);
			$sqlMoji .= ", SyoruiSakuseiDate = " . $this->convertNullStringValue($this->SyoruiSakuseiDate);
			$sqlMoji .= ", AnzenKojiNaiyo = " . $this->convertNullStringValue($this->AnzenKojiNaiyo);
			$sqlMoji .= ", AnzenKojiName = " . $this->convertNullStringValue($this->AnzenKojiName);
			$sqlMoji .= ", AnzenHattyusya = " . $this->convertNullStringValue($this->AnzenHattyusya);
			$sqlMoji .= ", AnzenHattyusyaAddress = " . $this->convertNullStringValue($this->AnzenHattyusyaAddress);
			$sqlMoji .= ", AnzenHattyusyaTEL = " . $this->convertNullStringValue($this->AnzenHattyusyaTEL);
			$sqlMoji .= ", Contract = " . $this->convertNullStringValue($this->Contract);
			$sqlMoji .= ", Sitentyo = " . $this->convertNullStringValue($this->Sitentyo);
			$sqlMoji .= ", EigyoSyotyo = " . $this->convertNullStringValue($this->EigyoSyotyo);
			$sqlMoji .= ", SyuninGijutusya = " . $this->convertNullStringValue($this->SyuninGijutusya);
			$sqlMoji .= ", BukkenTanto = " . $this->convertNullStringValue($this->BukkenTanto);
			$sqlMoji .= ", EmergencyTEL = " . $this->convertNullStringValue($this->EmergencyTEL);
			$sqlMoji .= ", ATEContract = " . $this->convertNullStringValue($this->ATEContract);
			$sqlMoji .= ", HealthNumber = " . $this->convertNullStringValue($this->HealthNumber);
			$sqlMoji .= ", NenkinNumber = " . $this->convertNullStringValue($this->NenkinNumber);
			$sqlMoji .= ", HNSyurui = " . $this->convertNullStringValue($this->HNSyurui);
			$sqlMoji .= ", EmployNumber = " . $this->convertNullStringValue($this->EmployNumber);
			$sqlMoji .= ", AIPHONETanto = " . $this->convertNullStringValue($this->AIPHONETanto);
			$sqlMoji .= ", AIPHONEEmergencyTEL = " . $this->convertNullStringValue($this->AIPHONEEmergencyTEL);
			$sqlMoji .= ", Yobi1 = " . $this->convertNullStringValue($this->Yobi1);
			$sqlMoji .= ", Yobi2 = " . $this->convertNullStringValue($this->Yobi2);
			$sqlMoji .= ", Yobi3 = " . $this->convertNullStringValue($this->Yobi3);
			$sqlMoji .= ", Yobi4 = " . $this->convertNullStringValue($this->Yobi4);
			$sqlMoji .= ", Yobi5 = " . $this->convertNullStringValue($this->Yobi5);
			$sqlMoji .= ", Yobi6 = " . $this->convertNullStringValue($this->Yobi6);
			$sqlMoji .= ", AIPHONEContract = " . $this->convertNullStringValue($this->AIPHONEContract);
			$sqlMoji .= ", HasekoSiten = " . $this->convertNullStringValue($this->HasekoSiten);
			$sqlMoji .= ", HasekoAddress = " . $this->convertNullStringValue($this->HasekoAddress);
			$sqlMoji .= ", HasekoTEL = " . $this->convertNullStringValue($this->HasekoTEL);
			$sqlMoji .= ", HasekoTanto = " . $this->convertNullStringValue($this->HasekoTanto);
			$sqlMoji .= ", HasekoAnzenYakuin = " . $this->convertNullStringValue($this->HasekoAnzenYakuin);
			$sqlMoji .= ", HasekoAnzenSekininsya = " . $this->convertNullStringValue($this->HasekoAnzenSekininsya);
			$sqlMoji .= ", HasekoAnzenTanto = " . $this->convertNullStringValue($this->HasekoAnzenTanto);
			$sqlMoji .= ", HasekoKojiYakuin = " . $this->convertNullStringValue($this->HasekoKojiYakuin);
			$sqlMoji .= ", HasekoKojiSekininsya = " . $this->convertNullStringValue($this->HasekoKojiSekininsya);
			$sqlMoji .= ", HasekoKojiTanto = " . $this->convertNullStringValue($this->HasekoKojiTanto);
			$sqlMoji .= ", HasekoGenbaDairinin = " . $this->convertNullStringValue($this->HasekoGenbaDairinin);
			$sqlMoji .= ", HasekoShokucho = " . $this->convertNullStringValue($this->HasekoShokucho);
			$sqlMoji .= ", HasekoSyuninGijyutusya = " . $this->convertNullStringValue($this->HasekoSyuninGijyutusya);
			$sqlMoji .= ", HasekoSenmonGijyutusya = " . $this->convertNullStringValue($this->HasekoSenmonGijyutusya);
			$sqlMoji .= ", HasekoTantoKojiNaiyo = " . $this->convertNullStringValue($this->HasekoTantoKojiNaiyo);
			$sqlMoji .= ", Haseko2jiAnzenSekininsya = " . $this->convertNullStringValue($this->Haseko2jiAnzenSekininsya);
			$sqlMoji .= ", Haseko2jiSyuninGijyutusya = " . $this->convertNullStringValue($this->Haseko2jiSyuninGijyutusya);
			$sqlMoji .= ", Haseko2jiSenmonGijyutusya = " . $this->convertNullStringValue($this->Haseko2jiSenmonGijyutusya);
			$sqlMoji .= ", Haseko2jiTantoKojiNaiyo = " . $this->convertNullStringValue($this->Haseko2jiTantoKojiNaiyo);

			$sqlMoji .= ", MukouFlg = " . $this->convertFlgToString($this->MukouFlg);
			$sqlMoji .= ", Created = " . $this->convertNullStringValue($this->Created);
			$sqlMoji .= ", Creator = " . $this->convertNullIntValue($this->Creator);
			$sqlMoji .= ", Updated = NOW()";
			$sqlMoji .= ", Updater = " . $this->convertNullIntValue($this->Updater);

			$sqlMoji .= " where AnzenSyoruiCD = $this->AnzenSyoruiCD";

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
