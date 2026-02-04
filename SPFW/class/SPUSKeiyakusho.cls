<?php
/**
 * Keiyakushoクラス
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

class Keiyakusho {
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
	var $KeiyakushoCD;
	var $BukkenCD;

	 var $KanriKumiai;
	 var $KojiName;
	 var $Address;
	 var $ZentaiStartDate;
	 var $ZentaiEndDate;
	 var $UkeoiKin;
	 var $PayRule;
	 var $MitsumoriNo;
	 var $KeiyakuDate;
	 var $Address2;
	 var $Sosikishurui;
	 var $SosikiName;
	 var $DaihyoYaku;
	 var $DaihyoName;
	 var $Tusu;
	 var $ShinseiDate;

	 var $SyotyoName;
	 var $TantoName;
	 var $EigyoSyoName;
	 var $TokuisakiCD;

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

	function __construct($myDB = "",$KeiyakushoCD = -1) {
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
		settype($KeiyakushoCD,"integer");
		// 整数チェック
		if (!is_long($KeiyakushoCD) or $KeiyakushoCD == 0) {
			$this->Err = "ユーザーコード が不正です";
			$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
			unset($myLog);
			return FALSE;
		}

		$this->myDB = $myDB;
		$this->TableName = "tKeiyakushoF";
		$this->KeiyakushoCD = $KeiyakushoCD;

		return TRUE;
	}

	function initialize(){
		$this->Err = "";

		$this->KeiyakushoCD = "";
		$this->BukkenCD = "";

		$this->KanriKumiai = "";
		$this->KojiName = "";
		$this->Address = "";
		$this->ZentaiStartDate = "";
		$this->ZentaiEndDate = "";
		$this->UkeoiKin = "";
		$this->PayRule = "";
		$this->MitsumoriNo = "";
		$this->KeiyakuDate = "";
		$this->Address2 = "";
		$this->Sosikishurui = "";
		$this->SosikiName = "";
		$this->DaihyoYaku = "";
		$this->DaihyoName = "";
		$this->Tusu = "";
		$this->ShinseiDate = "";

		$this->SyotyoName = "";
		$this->TantoName = "";
		$this->EigyoSyoName = "";
		$this->TokuisakiCD = "";
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
		$sqlMoji .= "KeiyakushoCD";
		$sqlMoji .= ", BukkenCD";

		  $sqlMoji .= ", KanriKumiai ";
		  $sqlMoji .= ", KojiName ";
		  $sqlMoji .= ", Address ";
		  $sqlMoji .= ", ZentaiStartDate ";
		  $sqlMoji .= ", ZentaiEndDate ";
		  $sqlMoji .= ", UkeoiKin ";
		  $sqlMoji .= ", PayRule ";
		  $sqlMoji .= ", MitsumoriNo ";
		  $sqlMoji .= ", KeiyakuDate ";
		  $sqlMoji .= ", Address2 ";
		  $sqlMoji .= ", Sosikishurui ";
		  $sqlMoji .= ", SosikiName ";
		  $sqlMoji .= ", DaihyoYaku ";
		  $sqlMoji .= ", DaihyoName ";
		  $sqlMoji .= ", Tusu ";

		  $sqlMoji .= ", ShinseiDate ";
		  $sqlMoji .= ", SyotyoName ";
		  $sqlMoji .= ", TantoName ";
		  $sqlMoji .= ", EigyoSyoName ";
		  $sqlMoji .= ", TokuisakiCD ";
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
				$this->KeiyakushoCD,
				$this->BukkenCD,

			    $this->KanriKumiai,
			    $this->KojiName,
			    $this->Address,
			    $this->ZentaiStartDate,
			    $this->ZentaiEndDate,
			    $this->UkeoiKin,
			    $this->PayRule,
			    $this->MitsumoriNo,
			    $this->KeiyakuDate,
			    $this->Address2,
			    $this->Sosikishurui,
			    $this->SosikiName,
			    $this->DaihyoYaku,
			    $this->DaihyoName,
			    $this->Tusu,
			    $this->ShinseiDate,

			    $this->SyotyoName,
			    $this->TantoName,
			    $this->EigyoSyoName,
			    $this->TokuisakiCD,

				$this->MukouFlg,
				$this->Created,
				$this->Creator,
				$this->Updated,
				$this->Updater
			) = $this->myDB->fetchRow($this->tmprtn,$RowNo);


		   $this->BukkenCD = stripslashes($this->BukkenCD);
		   $this->KanriKumiai = stripslashes($this->KanriKumiai);
		   $this->KojiName = stripslashes($this->KojiName);
		   $this->Address = stripslashes($this->Address);
		   $this->ZentaiStartDate = stripslashes($this->ZentaiStartDate);
		   $this->ZentaiEndDate = stripslashes($this->ZentaiEndDate);
		   $this->UkeoiKin = stripslashes($this->UkeoiKin);
		   $this->PayRule = stripslashes($this->PayRule);
		   $this->MitsumoriNo = stripslashes($this->MitsumoriNo);
		   $this->KeiyakuDate = stripslashes($this->KeiyakuDate);
		   $this->Address2 = stripslashes($this->Address2);
		   $this->Sosikishurui = stripslashes($this->Sosikishurui);
		   $this->SosikiName = stripslashes($this->SosikiName);
		   $this->DaihyoYaku = stripslashes($this->DaihyoYaku);
		   $this->DaihyoName = stripslashes($this->DaihyoName);
		   $this->Tusu = stripslashes($this->Tusu);
		   $this->ShinseiDate = stripslashes($this->ShinseiDate);

		   $this->SyotyoName = stripslashes($this->SyotyoName);
		   $this->TantoName = stripslashes($this->TantoName);
		   $this->EigyoSyoName = stripslashes($this->EigyoSyoName);
		   $this->TokuisakiCD = stripslashes($this->TokuisakiCD);

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
		
		if ($this->KeiyakushoCD < 0) {
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
					$sqlMoji = "select KeiyakushoCD from $this->TableName order by KeiyakushoCD desc limit 1";
					$maxCD = $this->myDB->getOneValue($sqlMoji);
					$maxCD = ($maxCD == -1) ? 0 : $maxCD;
					$maxCD++;

					// step 4. インサート
					$sqlMoji = "Insert into $this->TableName (";
					$sqlMoji .= "KeiyakushoCD";
	
				     if ($this->BukkenCD != "") $sqlMoji .= ", BukkenCD";
				     if ($this->KanriKumiai != "") $sqlMoji .= ", KanriKumiai";
				     if ($this->KojiName != "") $sqlMoji .= ", KojiName";
				     if ($this->Address != "") $sqlMoji .= ", Address";
				     if ($this->ZentaiStartDate != "") $sqlMoji .= ", ZentaiStartDate";
				     if ($this->ZentaiEndDate != "") $sqlMoji .= ", ZentaiEndDate";
				     if ($this->UkeoiKin != "") $sqlMoji .= ", UkeoiKin";
				     if ($this->PayRule != "") $sqlMoji .= ", PayRule";
				     if ($this->MitsumoriNo != "") $sqlMoji .= ", MitsumoriNo";
				     if ($this->KeiyakuDate != "") $sqlMoji .= ", KeiyakuDate";
				     if ($this->Address2 != "") $sqlMoji .= ", Address2";
				     if ($this->Sosikishurui != "") $sqlMoji .= ", Sosikishurui";
				     if ($this->SosikiName != "") $sqlMoji .= ", SosikiName";
				     if ($this->DaihyoYaku != "") $sqlMoji .= ", DaihyoYaku";
				     if ($this->DaihyoName != "") $sqlMoji .= ", DaihyoName";
				     if ($this->Tusu != "") $sqlMoji .= ", Tusu";
				     if ($this->ShinseiDate != "") $sqlMoji .= ", ShinseiDate";

				     if ($this->SyotyoName != "") $sqlMoji .= ", SyotyoName";
				     if ($this->TantoName != "") $sqlMoji .= ", TantoName";
				     if ($this->EigyoSyoName != "") $sqlMoji .= ", EigyoSyoName";
				     if ($this->TokuisakiCD != "") $sqlMoji .= ", TokuisakiCD";

					if ($this->MukouFlg != "") $sqlMoji .= ", MukouFlg";
					$sqlMoji .= ", Created";
					if ($this->Creator != "") $sqlMoji .= ", Creator";
					$sqlMoji .= ", Updated";
					if ($this->Updater != "") $sqlMoji .= ", Updater";
					$sqlMoji .= ") values (";
					$sqlMoji .= "$maxCD";


				     if ($this->BukkenCD != "") $sqlMoji .= ", '" . addslashes($this->BukkenCD) . "'";
				     if ($this->KanriKumiai != "") $sqlMoji .= ", '" . addslashes($this->KanriKumiai) . "'";
				     if ($this->KojiName != "") $sqlMoji .= ", '" . addslashes($this->KojiName) . "'";
				     if ($this->Address != "") $sqlMoji .= ", '" . addslashes($this->Address) . "'";
				     if ($this->ZentaiStartDate != "") $sqlMoji .= ", '" . addslashes($this->ZentaiStartDate) . "'";
				     if ($this->ZentaiEndDate != "") $sqlMoji .= ", '" . addslashes($this->ZentaiEndDate) . "'";
				     if ($this->UkeoiKin != "") $sqlMoji .= ", '" . addslashes($this->UkeoiKin) . "'";
				     if ($this->PayRule != "") $sqlMoji .= ", '" . addslashes($this->PayRule) . "'";
				     if ($this->MitsumoriNo != "") $sqlMoji .= ", '" . addslashes($this->MitsumoriNo) . "'";
				     if ($this->KeiyakuDate != "") $sqlMoji .= ", '" . addslashes($this->KeiyakuDate) . "'";
				     if ($this->Address2 != "") $sqlMoji .= ", '" . addslashes($this->Address2) . "'";
				     if ($this->Sosikishurui != "") $sqlMoji .= ", '" . addslashes($this->Sosikishurui) . "'";
				     if ($this->SosikiName != "") $sqlMoji .= ", '" . addslashes($this->SosikiName) . "'";
				     if ($this->DaihyoYaku != "") $sqlMoji .= ", '" . addslashes($this->DaihyoYaku) . "'";
				     if ($this->DaihyoName != "") $sqlMoji .= ", '" . addslashes($this->DaihyoName) . "'";
				     if ($this->Tusu != "") $sqlMoji .= ", '" . addslashes($this->Tusu) . "'";
				     if ($this->ShinseiDate != "") $sqlMoji .= ", '" . addslashes($this->ShinseiDate) . "'";

				     if ($this->SyotyoName != "") $sqlMoji .= ", '" . addslashes($this->SyotyoName) . "'";
				     if ($this->TantoName != "") $sqlMoji .= ", '" . addslashes($this->TantoName) . "'";
				     if ($this->EigyoSyoName != "") $sqlMoji .= ", '" . addslashes($this->EigyoSyoName) . "'";
				     if ($this->TokuisakiCD != "") $sqlMoji .= ", '" . addslashes($this->TokuisakiCD) . "'";

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
							$this->KeiyakushoCD = $maxCD;
						}
					}
				}
			}
		} else {
			// アップデート
			$sqlMoji = "Update $this->TableName set ";
			$sqlMoji .= "KeiyakushoCD = $this->KeiyakushoCD";

		   $sqlMoji .= ", BukkenCD = " . $this->convertNullStringValue($this->BukkenCD);
		   $sqlMoji .= ", KanriKumiai = " . $this->convertNullStringValue($this->KanriKumiai);
		   $sqlMoji .= ", KojiName = " . $this->convertNullStringValue($this->KojiName);
		   $sqlMoji .= ", Address = " . $this->convertNullStringValue($this->Address);
		   $sqlMoji .= ", ZentaiStartDate = " . $this->convertNullStringValue($this->ZentaiStartDate);
		   $sqlMoji .= ", ZentaiEndDate = " . $this->convertNullStringValue($this->ZentaiEndDate);
		   $sqlMoji .= ", UkeoiKin = " . $this->convertNullStringValue($this->UkeoiKin);
		   $sqlMoji .= ", PayRule = " . $this->convertNullStringValue($this->PayRule);
		   $sqlMoji .= ", MitsumoriNo = " . $this->convertNullStringValue($this->MitsumoriNo);
		   $sqlMoji .= ", KeiyakuDate = " . $this->convertNullStringValue($this->KeiyakuDate);
		   $sqlMoji .= ", Address2 = " . $this->convertNullStringValue($this->Address2);
		   $sqlMoji .= ", Sosikishurui = " . $this->convertNullStringValue($this->Sosikishurui);
		   $sqlMoji .= ", SosikiName = " . $this->convertNullStringValue($this->SosikiName);
		   $sqlMoji .= ", DaihyoYaku = " . $this->convertNullStringValue($this->DaihyoYaku);
		   $sqlMoji .= ", DaihyoName = " . $this->convertNullStringValue($this->DaihyoName);
		   $sqlMoji .= ", Tusu = " . $this->convertNullStringValue($this->Tusu);
		   $sqlMoji .= ", ShinseiDate = " . $this->convertNullStringValue($this->ShinseiDate);

		   $sqlMoji .= ", SyotyoName = " . $this->convertNullStringValue($this->SyotyoName);
		   $sqlMoji .= ", TantoName = " . $this->convertNullStringValue($this->TantoName);
		   $sqlMoji .= ", EigyoSyoName = " . $this->convertNullStringValue($this->EigyoSyoName);
		   $sqlMoji .= ", TokuisakiCD = " . $this->convertNullStringValue($this->TokuisakiCD);


			$sqlMoji .= ", MukouFlg = " . $this->convertFlgToString($this->MukouFlg);
			$sqlMoji .= ", Created = " . $this->convertNullStringValue($this->Created);
			$sqlMoji .= ", Creator = " . $this->convertNullIntValue($this->Creator);
			$sqlMoji .= ", Updated = NOW()";
			$sqlMoji .= ", Updater = " . $this->convertNullIntValue($this->Updater);
			$sqlMoji .= " where KeiyakushoCD = $this->KeiyakushoCD";





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
