<?php
/**
 * MisumoriIraiFクラス
 *
 * メニューマスタを扱うクラスです。
 *
 * @package 
 * @access  public
 * @author  
 * @create  2019/03/22
 * @version 1.0
 **/

// 依存
include_once _CLS_DIR . "SPFWLog.cls";

class MitumoriIrai {
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
	var $MitumoriIraiCD;
	var $BukkenCD;

	var $KojiFullName;
	var $Address;
	var $KojiGaiyo;
	var $YoteiKoki;
	var $YoteiKokiMonth;
	var $YoteiKokiDateStart;
	var $YoteiKokiDateEnd;
	var $Koho;
	var $Sikyuhin;
	var $SekojyoukenHanni;
	var $Siharaijyoken;
	var $Zumenwatasi;
	var $ZumenwatasiDate;
	var $Mitsumoriteisyutukigen;
	var $Seiyakujyouken;

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

	function __construct($myDB = "",$MitumoriIraiCD = -1) {
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
		settype($MitumoriIraiCD,"integer");
		// 整数チェック
		if (!is_long($MitumoriIraiCD) or $MitumoriIraiCD == 0) {
			$this->Err = "ユーザーコード が不正です";
			$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
			unset($myLog);
			return FALSE;
		}

		$this->myDB = $myDB;
		$this->TableName = " tMisumoriIraiF";
		$this->MitumoriIraiCD = $MitumoriIraiCD;

		return TRUE;
	}

	function initialize(){
		$this->Err = "";

		$this->MitumoriIraiCD = "";
		$this->BukkenCD = "";
		
		$this->KojiFullName = "";
		$this->Address = "";
		$this->KojiGaiyo = "";
		$this->YoteiKoki = "";

		$this->YoteiKokiMonth = "";
		$this->YoteiKokiDateStart = "";
		$this->YoteiKokiDateEnd = "";
		$this->Koho = "";
		$this->Sikyuhin = "";
		$this->SekojyoukenHanni = "";
		$this->Siharaijyoken = "";
		$this->Zumenwatasi = "";
		$this->ZumenwatasiDate = "";
		$this->Mitsumoriteisyutukigen = "";
		$this->Seiyakujyouken = "";
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
		$sqlMoji .= "MitumoriIraiCD";
		$sqlMoji .= ", BukkenCD";

		$sqlMoji .= ", KojiFullName ";
		$sqlMoji .= ", Address ";
		$sqlMoji .= ", KojiGaiyo ";
		$sqlMoji .= ", YoteiKoki ";
		$sqlMoji .= ", YoteiKokiMonth ";
		$sqlMoji .= ", YoteiKokiDateStart ";
		$sqlMoji .= ", YoteiKokiDateEnd ";
		$sqlMoji .= ", Koho ";
		$sqlMoji .= ", Sikyuhin ";
		$sqlMoji .= ", SekojyoukenHanni ";
		$sqlMoji .= ", Siharaijyoken ";
		$sqlMoji .= ", Zumenwatasi ";
		$sqlMoji .= ", ZumenwatasiDate ";
		$sqlMoji .= ", Mitsumoriteisyutukigen ";
		$sqlMoji .= ", Seiyakujyouken ";
		$sqlMoji .= ", MukouFlg ";
		$sqlMoji .= ", Created ";
		$sqlMoji .= ", Creator ";
		$sqlMoji .= ", Updated ";
		$sqlMoji .= ", Updater ";
		
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
				$this->MitumoriIraiCD,
				$this->BukkenCD,

				$this->KojiFullName,
				$this->Address,
				$this->KojiGaiyo,
				$this->YoteiKoki,
				$this->YoteiKokiMonth,
				$this->YoteiKokiDateStart,
				$this->YoteiKokiDateEnd,
				$this->Koho,
				$this->Sikyuhin,
				$this->SekojyoukenHanni,
				$this->Siharaijyoken,
				$this->Zumenwatasi,
				$this->ZumenwatasiDate,
				$this->Mitsumoriteisyutukigen,
				$this->Seiyakujyouken,

				$this->MukouFlg,
				$this->Created,
				$this->Creator,
				$this->Updated,
				$this->Updater
			) = $this->myDB->fetchRow($this->tmprtn,$RowNo);


		   $this->BukkenCD = stripslashes($this->BukkenCD);

		$this->KojiFullName = stripslashes($this->KojiFullName);
		$this->Address = stripslashes($this->Address);
		$this->KojiGaiyo = stripslashes($this->KojiGaiyo);
		$this->YoteiKoki = stripslashes($this->YoteiKoki);
		$this->YoteiKokiMonth = stripslashes($this->YoteiKokiMonth);
		$this->YoteiKokiDateStart = stripslashes($this->YoteiKokiDateStart);
		$this->YoteiKokiDateEnd = stripslashes($this->YoteiKokiDateEnd);
		$this->Koho = stripslashes($this->Koho);
		$this->Sikyuhin = stripslashes($this->Sikyuhin);
		$this->SekojyoukenHanni = stripslashes($this->SekojyoukenHanni);
		$this->Siharaijyoken = stripslashes($this->Siharaijyoken);
		$this->Zumenwatasi = stripslashes($this->Zumenwatasi);
		$this->ZumenwatasiDate = stripslashes($this->ZumenwatasiDate);
		$this->Mitsumoriteisyutukigen = stripslashes($this->Mitsumoriteisyutukigen);
		$this->Seiyakujyouken = stripslashes($this->Seiyakujyouken);

		$this->MukouFlg = stripslashes($this->MukouFlg);


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
		
		if ($this->MitumoriIraiCD < 0) {
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
					$sqlMoji = "select MitumoriIraiCD from $this->TableName order by MitumoriIraiCD desc limit 1";
					$maxCD = $this->myDB->getOneValue($sqlMoji);
					$maxCD = ($maxCD == -1) ? 0 : $maxCD;
					$maxCD++;

					// step 4. インサート
					$sqlMoji = "Insert into $this->TableName (";
					$sqlMoji .= "MitumoriIraiCD";
	
				     if ($this->BukkenCD != "") $sqlMoji .= ", BukkenCD";
				     if ($this->KojiFullName != "") $sqlMoji .= ", KojiFullName";
				     if ($this->Address != "") $sqlMoji .= ", Address";
				     if ($this->KojiGaiyo != "") $sqlMoji .= ", KojiGaiyo";
				     if ($this->YoteiKoki != "") $sqlMoji .= ", YoteiKoki";
				     if ($this->YoteiKokiMonth != "") $sqlMoji .= ", YoteiKokiMonth";
				     if ($this->YoteiKokiDateStart != "") $sqlMoji .= ", YoteiKokiDateStart";
				     if ($this->YoteiKokiDateEnd != "") $sqlMoji .= ", YoteiKokiDateEnd";
				     if ($this->Koho != "") $sqlMoji .= ", Koho";
				     if ($this->Sikyuhin != "") $sqlMoji .= ", Sikyuhin";
				     if ($this->SekojyoukenHanni != "") $sqlMoji .= ", SekojyoukenHanni";
				     if ($this->Siharaijyoken != "") $sqlMoji .= ", Siharaijyoken";
				     if ($this->Zumenwatasi != "") $sqlMoji .= ", Zumenwatasi";
				     if ($this->ZumenwatasiDate != "") $sqlMoji .= ", ZumenwatasiDate";
				     if ($this->Mitsumoriteisyutukigen != "") $sqlMoji .= ", Mitsumoriteisyutukigen";
				     if ($this->Seiyakujyouken != "") $sqlMoji .= ", Seiyakujyouken";


					if ($this->MukouFlg != "") $sqlMoji .= ", MukouFlg";
					$sqlMoji .= ", Created";
					if ($this->Creator != "") $sqlMoji .= ", Creator";
					$sqlMoji .= ", Updated";
					if ($this->Updater != "") $sqlMoji .= ", Updater";
					$sqlMoji .= ") values (";
					$sqlMoji .= "$maxCD";


				     if ($this->BukkenCD != "") $sqlMoji .= ", '" . addslashes($this->BukkenCD) . "'";
				     
				     if ($this->KojiFullName != "") $sqlMoji .= ", '" . addslashes($this->KojiFullName) . "'";
				     if ($this->Address != "") $sqlMoji .= ", '" . addslashes($this->Address) . "'";
				     if ($this->KojiGaiyo != "") $sqlMoji .= ", '" . addslashes($this->KojiGaiyo) . "'";
				     if ($this->YoteiKoki != "") $sqlMoji .= ", '" . addslashes($this->YoteiKoki) . "'";
				     if ($this->YoteiKokiMonth != "") $sqlMoji .= ", '" . addslashes($this->YoteiKokiMonth) . "'";
				     if ($this->YoteiKokiDateStart != "") $sqlMoji .= ", '" . addslashes($this->YoteiKokiDateStart) . "'";
				     if ($this->YoteiKokiDateEnd != "") $sqlMoji .= ", '" . addslashes($this->YoteiKokiDateEnd) . "'";
				     if ($this->Koho != "") $sqlMoji .= ", '" . addslashes($this->Koho) . "'";
				     if ($this->Sikyuhin != "") $sqlMoji .= ", '" . addslashes($this->Sikyuhin) . "'";
				     if ($this->SekojyoukenHanni != "") $sqlMoji .= ", '" . addslashes($this->SekojyoukenHanni) . "'";
				     if ($this->Siharaijyoken != "") $sqlMoji .= ", '" . addslashes($this->Siharaijyoken) . "'";
				     if ($this->Zumenwatasi != "") $sqlMoji .= ", '" . addslashes($this->Zumenwatasi) . "'";
				     if ($this->ZumenwatasiDate != "") $sqlMoji .= ", '" . addslashes($this->ZumenwatasiDate) . "'";
				     if ($this->Mitsumoriteisyutukigen != "") $sqlMoji .= ", '" . addslashes($this->Mitsumoriteisyutukigen) . "'";
				     if ($this->Seiyakujyouken != "") $sqlMoji .= ", '" . addslashes($this->Seiyakujyouken) . "'";

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
							$this->MitumoriIraiCD = $maxCD;
						}
					}
				}
			}
		} else {
			// アップデート
			$sqlMoji = "Update $this->TableName set ";
			$sqlMoji .= "MitumoriIraiCD = $this->MitumoriIraiCD";

		   $sqlMoji .= ", BukkenCD = " . $this->convertNullStringValue($this->BukkenCD);
		   $sqlMoji .= ", KojiFullName = " . $this->convertNullStringValue($this->KojiFullName);
		   $sqlMoji .= ", Address = " . $this->convertNullStringValue($this->Address);
		   $sqlMoji .= ", KojiGaiyo = " . $this->convertNullStringValue($this->KojiGaiyo);
		   $sqlMoji .= ", YoteiKoki = " . $this->convertNullStringValue($this->YoteiKoki);
		   $sqlMoji .= ", YoteiKokiMonth = " . $this->convertNullStringValue($this->YoteiKokiMonth);
		   $sqlMoji .= ", YoteiKokiDateStart = " . $this->convertNullStringValue($this->YoteiKokiDateStart);
		   $sqlMoji .= ", YoteiKokiDateEnd = " . $this->convertNullStringValue($this->YoteiKokiDateEnd);
		   $sqlMoji .= ", Koho = " . $this->convertNullStringValue($this->Koho);
		   $sqlMoji .= ", Sikyuhin = " . $this->convertNullStringValue($this->Sikyuhin);
		   $sqlMoji .= ", SekojyoukenHanni = " . $this->convertNullStringValue($this->SekojyoukenHanni);
		   $sqlMoji .= ", Siharaijyoken = " . $this->convertNullStringValue($this->Siharaijyoken);
		   $sqlMoji .= ", Zumenwatasi = " . $this->convertNullStringValue($this->Zumenwatasi);
		   $sqlMoji .= ", ZumenwatasiDate = " . $this->convertNullStringValue($this->ZumenwatasiDate);
		   $sqlMoji .= ", Mitsumoriteisyutukigen = " . $this->convertNullStringValue($this->Mitsumoriteisyutukigen);
		   $sqlMoji .= ", Seiyakujyouken = " . $this->convertNullStringValue($this->Seiyakujyouken);



			$sqlMoji .= ", MukouFlg = " . $this->convertFlgToString($this->MukouFlg);
			$sqlMoji .= ", Created = " . $this->convertNullStringValue($this->Created);
			$sqlMoji .= ", Creator = " . $this->convertNullIntValue($this->Creator);
			$sqlMoji .= ", Updated = NOW()";
			$sqlMoji .= ", Updater = " . $this->convertNullIntValue($this->Updater);
			$sqlMoji .= " where MitumoriIraiCD = $this->MitumoriIraiCD";





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
