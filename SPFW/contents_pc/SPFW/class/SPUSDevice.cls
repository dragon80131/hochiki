<?php


// 依存
include_once _CLS_DIR . "SPFWLog.cls";

class Device {
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
	var $DeviceCD;
	/**
	 * クライアントコード
	 */
	var $ClientCD;
	/**
	 * メニュータイトル
	 */
	var $DeviceName;
	/**
	 * 略称
	 */
	var $Kataban;
	/**
	 * 所要ライン数
	 */
	var $OPUseKbn;
	/**
	 * 所要時間区分
	 */
	var $OPUseDisp;
	/**
	 * 備考
	 */
	var $ShiyoshoURL;
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
	var $ShortName;
	var $D001;
	var $ManualURL;
	var $D003;
	var $Extra5;
	var $OptionFlg;
	var $IntroFlg;
	var $Category;
	var $BasicSystem;


	function __construct($myDB = "",$DeviceCD = -1) {
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
		settype($DeviceCD,"integer");
		// 整数チェック
		if (!is_long($DeviceCD) or $DeviceCD == 0) {
			$this->Err = "ユーザーコード が不正です";
			$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
			unset($myLog);
			return FALSE;
		}

		$this->myDB = $myDB;
		$this->TableName = "tDeviceM";
		$this->DeviceCD = $DeviceCD;

		return TRUE;
	}

	function initialize(){
		$this->Err = "";

		$this->DeviceCD = "";
		$this->ClientCD = "";
		$this->DeviceName = "";
		$this->Kataban = "";
		$this->OPUseKbn = "";
		$this->OPUseDisp = "";
		$this->ShiyoshoURL = "";
		$this->MukouFlg = "";
		$this->Created = "";
		$this->Creator = "";
		$this->Updated = "";
		$this->Updater = "";
		$this->ShortName = "";
		$this->D001 = "";
		$this->OptionFlg = "";
		$this->IntroFlg = "";
		$this->Category = "";
		$this-> BasicSystem= "";
		$this->ManualURL = "";
		$this->D003 = "";
		$this->Extra5 = "";

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
		$sqlMoji .= "DeviceCD";
		$sqlMoji .= ", ClientCD";
		$sqlMoji .= ", DeviceName";
		$sqlMoji .= ", Kataban";
		$sqlMoji .= ", OPUseKbn";
		$sqlMoji .= ", OPUseDisp";
		$sqlMoji .= ", ShiyoshoURL";
		$sqlMoji .= ", MukouFlg";
		$sqlMoji .= ", Created";
		$sqlMoji .= ", Creator";
		$sqlMoji .= ", Updated";
		$sqlMoji .= ", Updater";
		$sqlMoji .= ", ShortName";
		$sqlMoji .= ", D001";
		$sqlMoji .= ", ManualURL";
		$sqlMoji .= ", D003";
		$sqlMoji .= ", Extra5 ";
		$sqlMoji .= ", OptionFlg";
		$sqlMoji .= ", IntroFlg";
		$sqlMoji .= ", Category";
		$sqlMoji .= ", BasicSystem";
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
				$this->DeviceCD,
				$this->ClientCD,
				$this->DeviceName,
				$this->Kataban,
				$this->OPUseKbn,
				$this->OPUseDisp,
				$this->ShiyoshoURL,
				$this->MukouFlg,
				$this->Created,
				$this->Creator,
				$this->Updated,
				$this->Updater,
				$this->ShortName,
				$this->D001,
				$this->ManualURL,
				$this->D003,
				$this->Extra5,
				$this->OptionFlg,
				$this->IntroFlg,
				$this->Category,
				$this->BasicSystem
			) = $this->myDB->fetchRow($this->tmprtn,$RowNo);

			$this->DeviceName = stripslashes($this->DeviceName);
			$this->Kataban = stripslashes($this->Kataban);
			$this->ShiyoshoURL = stripslashes($this->ShiyoshoURL);
			$this->OPUseKbn = stripslashes($this->OPUseKbn);
			$this->OPUseDisp = stripslashes($this->OPUseDisp);
			$this->D001 = stripslashes($this->D001);
			$this->Category = stripslashes($this->Category);
			$this->BasicSystem = stripslashes($this->BasicSystem);
			$this->Extra5 = stripslashes($this->Extra5);
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

		if ($this->DeviceCD < 0) {
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
					$sqlMoji = "select DeviceCD from $this->TableName order by DeviceCD desc limit 1";
					$maxCD = $this->myDB->getOneValue($sqlMoji);
					$maxCD = ($maxCD == -1) ? 0 : $maxCD;
					$maxCD++;

					// step 4. インサート
					$sqlMoji = "Insert into $this->TableName (";
					$sqlMoji .= "DeviceCD";
					if ($this->ClientCD != "") $sqlMoji .= ", ClientCD";
					if ($this->DeviceName != "") $sqlMoji .= ", DeviceName";
					if ($this->Kataban != "") $sqlMoji .= ", Kataban";
					if ($this->OPUseKbn != "") $sqlMoji .= ", OPUseKbn";
					if ($this->OPUseDisp != "") $sqlMoji .= ", OPUseDisp";
					if ($this->ShiyoshoURL != "") $sqlMoji .= ", ShiyoshoURL";
					if ($this->MukouFlg != "") $sqlMoji .= ", MukouFlg";
					$sqlMoji .= ", Created";
					if ($this->Creator != "") $sqlMoji .= ", Creator";
					$sqlMoji .= ", Updated";
					if ($this->Updater != "") $sqlMoji .= ", Updater";
					if ($this->ShortName != "") $sqlMoji .= ", ShortName";
					if ($this->D001 != "") $sqlMoji .= ", D001";
					if ($this->OptionFlg != "") $sqlMoji .= ", OptionFlg";
					if ($this->IntroFlg != "") $sqlMoji .= ", IntroFlg";
					if ($this->Category != "") $sqlMoji .= ", Category";
					if ($this->BasicSystem != "") $sqlMoji .= ", BasicSystem";
					if ($this->ManualURL != "") $sqlMoji .= ", ManualURL";
					if ($this->Extra5 != "") $sqlMoji .= ", Extra5";
					if ($this->D003 != "") $sqlMoji .= ", D003";
					$sqlMoji .= ") values (";
					$sqlMoji .= "$maxCD";
					if ($this->ClientCD != "") $sqlMoji .= ", $this->ClientCD";
					if ($this->DeviceName != "") $sqlMoji .= ", '" . addslashes($this->DeviceName) . "'";
					if ($this->Kataban != "") $sqlMoji .= ", '" . addslashes($this->Kataban) . "'";
					if ($this->OPUseKbn != "") $sqlMoji .= ", '" . addslashes($this->OPUseKbn) . "'";
					if ($this->OPUseDisp != "") $sqlMoji .= ", '" . addslashes($this->OPUseDisp) . "'";
					if ($this->ShiyoshoURL != "") $sqlMoji .= ", '" . addslashes($this->ShiyoshoURL) . "'";
					if ($this->MukouFlg != "") $sqlMoji .= ", " . $this->convertFlgToString($this->MukouFlg);
					$sqlMoji .= ", NOW()";
					if ($this->Creator != "") $sqlMoji .= ", $this->Creator";
					$sqlMoji .= ", NOW()";
					if ($this->Updater != "") $sqlMoji .= ", $this->Updater";
					if ($this->ShortName != "") $sqlMoji .= ", '" . addslashes($this->ShortName) . "'";
					if ($this->D001 != "") $sqlMoji .= ", '" . addslashes($this->D001) . "'";
					if ($this->OptionFlg != "") $sqlMoji .= ", '" . addslashes($this->OptionFlg) . "'";
					if ($this->IntroFlg != "") $sqlMoji .= ", '" . addslashes($this->IntroFlg) . "'";
					if ($this->Category != "") $sqlMoji .= ", '" . addslashes($this->Category) . "'";
					if ($this->BasicSystem != "") $sqlMoji .= ", '" . addslashes($this->BasicSystem) . "'";
					if ($this->ManualURL != "") $sqlMoji .= ", '" . addslashes($this->ManualURL) . "'";
					if ($this->Extra5 != "") $sqlMoji .= ", '" . addslashes($this->Extra5) . "'";
					if ($this->D003 != "") $sqlMoji .= ", '" . addslashes($this->D003) . "'";
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
							$this->DeviceCD = $maxCD;
						}
					}
				}
			}
		} else {
			// アップデート
			$sqlMoji = "Update $this->TableName set ";
			$sqlMoji .= "DeviceCD = $this->DeviceCD";
			$sqlMoji .= ", ClientCD = " . $this->convertNullIntValue($this->ClientCD);
			$sqlMoji .= ", DeviceName = " . $this->convertNullStringValue($this->DeviceName);
			$sqlMoji .= ", Kataban = " . $this->convertNullStringValue($this->Kataban);
			$sqlMoji .= ", OPUseKbn = " . $this->convertNullStringValue($this->OPUseKbn);
			$sqlMoji .= ", OPUseDisp = " . $this->convertNullStringValue($this->OPUseDisp);
			$sqlMoji .= ", ShiyoshoURL = " . $this->convertNullStringValue($this->ShiyoshoURL);
			$sqlMoji .= ", MukouFlg = " . $this->convertFlgToString($this->MukouFlg);
			$sqlMoji .= ", Created = " . $this->convertNullStringValue($this->Created);
			$sqlMoji .= ", Creator = " . $this->convertNullIntValue($this->Creator);
			$sqlMoji .= ", Updated = NOW()";
			$sqlMoji .= ", Updater = " . $this->convertNullIntValue($this->Updater);
			$sqlMoji .= ", ShortName = " . $this->convertNullStringValue($this->ShortName);
			$sqlMoji .= ", D001 = " . $this->convertNullStringValue($this->D001);
			$sqlMoji .= ", OptionFlg = " . $this->convertNullStringValue($this->OptionFlg);
			$sqlMoji .= ", IntroFlg = " . $this->convertNullStringValue($this->IntroFlg);
			$sqlMoji .= ", Category = " . $this->convertNullStringValue($this->Category);
			$sqlMoji .= ", BasicSystem = " . $this->convertNullStringValue($this->BasicSystem);
			$sqlMoji .= ", ManualURL = " . $this->convertNullStringValue($this->ManualURL);
			$sqlMoji .= ", D003 = " . $this->convertNullStringValue($this->D003);
			$sqlMoji .= ", Extra5 = " . $this->convertNullStringValue($this->Extra5);
			$sqlMoji .= " where DeviceCD = $this->DeviceCD";

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
