<?php
/**
 * Ringiクラス
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

class Ringi {
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
	 * 稟議コード
	 * 
	 * 連番
	 */
	var $RingiCD;
	/**
	 * 件名No
	 */
	var $KenmeiNo;
	/**
	 * 仕入　申請No
	 */
	var $TSR_ShinseiNo;
	/**
	 * 仕入　決裁No
	 */
	var $TSR_KesaiNo;
	/**
	 * 仕入　申請日
	 */
	var $TSR_ShinseiDate;
	/**
	 * 仕入　決裁日
	 */
	var $TSR_KesaiDate;
	/**
	 * 仕入　申請社員CD
	 */
	var $TSR_ShinseiSyainCD;
	/**
	 * 仕入　決裁社員
	 */
	var $TSR_KesaiSyainCD;
	/**
	 * 仕入　申請種類　1：他社仕入　2：工事仕入　3：他社＆工事仕入
	 */
	var $ShinseiSyurui;
	/**
	 * 仕入　フラグ　1：不要　２：未完了　３：完了
	 */
	var $TSR_Flg;
	/**
	 * 営業協力費　申請No
	 */
	var $KHI_ShinseiNo;
	/**
	 * 営業協力費　決裁No
	 */
	var $KHI_KesaiNo;
	/**
	 * 営業協力費　申請日
	 */
	var $KHI_ShinseiDate;
	/**
	 * 営業協力費　決裁日
	 */
	var $KHI_KesaiDate;
	/**
	 * 営業協力費　申請社員CD
	 */
	var $KHI_ShinseiSyainCD;
	/**
	 * 営業協力費　決裁社員
	 */
	var $KHI_KesaiSyainCD;
	/**
	 * 営業協力費　フラグ　1：不要　２：未完了　３：完了
	 */
	var $KHI_Flg;
	/**
	 * その他販売　申請No
	 */
	var $SKH_ShinseiNo;
	/**
	 * その他販売　決裁No
	 */
	var $SKH_KesaiNo;
	/**
	 * その他販売　申請日
	 */
	var $SKH_ShinseiDate;
	/**
	 * その他販売　決裁日
	 */
	var $SKH_KesaiDate;
	/**
	 * その他販売　申請社員CD
	 */
	var $SKH_ShinseiSyainCD;
	/**
	 * その他販売　決裁社員
	 */
	var $SKH_KesaiSyainCD;
	/**
	 * その他販売　フラグ　1：不要　２：未完了　３：完了
	 */
	var $SKH_Flg;
	/**
	 * 物件値引　申請No
	 */
	var $BKN_ShinseiNo;
	/**
	 * 物件値引　決裁No
	 */
	var $BKN_KesaiNo;
	/**
	 * 物件値引　申請日
	 */
	var $BKN_ShinseiDate;
	/**
	 * 物件値引　決裁日
	 */
	var $BKN_KesaiDate;
	/**
	 * 物件値引　申請社員CD
	 */
	var $BKN_ShinseiSyainCD;
	/**
	 * 物件値引　決裁社員
	 */
	var $BKN_KesaiSyainCD;
	/**
	 * 物件値引　フラグ　1：不要　２：未完了　３：完了
	 */
	var $BKN_Flg;
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

	function __construct($myDB = "",$RingiCD = -1) {
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
		settype($RingiCD,"integer");
		// 整数チェック
		if (!is_long($RingiCD) or $RingiCD == 0) {
			$this->Err = "ユーザーコード が不正です";
			$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
			unset($myLog);
			return FALSE;
		}

		$this->myDB = $myDB;
		$this->TableName = "tRingiM";
		$this->RingiCD = $RingiCD;

		return TRUE;
	}

	function initialize(){
		$this->Err = "";

		$this->RingiCD = "";
		$this->KenmeiNo = "";

		$this->TSR_ShinseiNo = "";
		$this->TSR_KesaiNo = "";
		$this->TSR_ShinseiDate = "";
		$this->TSR_KesaiDate = "";
		$this->TSR_ShinseiSyainCD = "";
		$this->TSR_KesaiSyainCD = "";
		$this->ShinseiSyurui = "";
		$this->TSR_Flg = "";

		$this->KHI_ShinseiNo = "";
		$this->KHI_KesaiNo = "";
		$this->KHI_ShinseiDate = "";
		$this->KHI_KesaiDate = "";
		$this->KHI_ShinseiSyainCD = "";
		$this->KHI_KesaiSyainCD = "";
		$this->KHI_Flg = "";

		$this->SKH_ShinseiNo = "";
		$this->SKH_KesaiNo = "";
		$this->SKH_ShinseiDate = "";
		$this->SKH_KesaiDate = "";
		$this->SKH_ShinseiSyainCD = "";
		$this->SKH_KesaiSyainCD = "";
		$this->SKH_Flg = "";

		$this->BKN_ShinseiNo = "";
		$this->BKN_KesaiNo = "";
		$this->BKN_ShinseiDate = "";
		$this->BKN_KesaiDate = "";
		$this->BKN_ShinseiSyainCD = "";
		$this->BKN_KesaiSyainCD = "";
		$this->BKN_Flg = "";

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
		$sqlMoji .= "RingiCD";
		$sqlMoji .= ", KenmeiNo";
		$sqlMoji .= ", TSR_ShinseiNo";
		$sqlMoji .= ", TSR_KesaiNo";
		$sqlMoji .= ", TSR_ShinseiDate";
		$sqlMoji .= ", TSR_KesaiDate";
		$sqlMoji .= ", TSR_ShinseiSyainCD";
		$sqlMoji .= ", TSR_KesaiSyainCD";
		$sqlMoji .= ", ShinseiSyurui";
		$sqlMoji .= ", TSR_Flg";

		$sqlMoji .= ", KHI_ShinseiNo";
		$sqlMoji .= ", KHI_KesaiNo";
		$sqlMoji .= ", KHI_ShinseiDate";
		$sqlMoji .= ", KHI_KesaiDate";
		$sqlMoji .= ", KHI_ShinseiSyainCD";
		$sqlMoji .= ", KHI_KesaiSyainCD";
		$sqlMoji .= ", KHI_Flg";

		$sqlMoji .= ", SKH_ShinseiNo";
		$sqlMoji .= ", SKH_KesaiNo";
		$sqlMoji .= ", SKH_ShinseiDate";
		$sqlMoji .= ", SKH_KesaiDate";
		$sqlMoji .= ", SKH_ShinseiSyainCD";
		$sqlMoji .= ", SKH_KesaiSyainCD";
		$sqlMoji .= ", SKH_Flg";

		$sqlMoji .= ", BKN_ShinseiNo";
		$sqlMoji .= ", BKN_KesaiNo";
		$sqlMoji .= ", BKN_ShinseiDate";
		$sqlMoji .= ", BKN_KesaiDate";
		$sqlMoji .= ", BKN_ShinseiSyainCD";
		$sqlMoji .= ", BKN_KesaiSyainCD";
		$sqlMoji .= ", BKN_Flg";

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
				$this->RingiCD,
				$this->KenmeiNo,

				$this->TSR_ShinseiNo,
				$this->TSR_KesaiNo,
				$this->TSR_ShinseiDate,
				$this->TSR_KesaiDate,
				$this->TSR_ShinseiSyainCD,
				$this->TSR_KesaiSyainCD,
				$this->ShinseiSyurui,
				$this->TSR_Flg,

				$this->KHI_ShinseiNo,
				$this->KHI_KesaiNo,
				$this->KHI_ShinseiDate,
				$this->KHI_KesaiDate,
				$this->KHI_ShinseiSyainCD,
				$this->KHI_KesaiSyainCD,
				$this->KHI_Flg,

				$this->SKH_ShinseiNo,
				$this->SKH_KesaiNo,
				$this->SKH_ShinseiDate,
				$this->SKH_KesaiDate,
				$this->SKH_ShinseiSyainCD,
				$this->SKH_KesaiSyainCD,
				$this->SKH_Flg,

				$this->BKN_ShinseiNo,
				$this->BKN_KesaiNo,
				$this->BKN_ShinseiDate,
				$this->BKN_KesaiDate,
				$this->BKN_ShinseiSyainCD,
				$this->BKN_KesaiSyainCD,
				$this->BKN_Flg,

				$this->MukouFlg,
				$this->Created,
				$this->Creator,
				$this->Updated,
				$this->Updater
			) = $this->myDB->fetchRow($this->tmprtn,$RowNo);

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
		
		if ($this->RingiCD < 0) {
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
					$sqlMoji = "select RingiCD from $this->TableName order by RingiCD desc limit 1";
					$maxCD = $this->myDB->getOneValue($sqlMoji);
					$maxCD = ($maxCD == -1) ? 0 : $maxCD;
					$maxCD++;

					// step 4. インサート
					$sqlMoji = "Insert into $this->TableName (";
					$sqlMoji .= "RingiCD";
					if ($this->KenmeiNo != "") $sqlMoji .= ", KenmeiNo";
					if ($this->TSR_ShinseiNo != "") $sqlMoji .= ", TSR_ShinseiNo";
					if ($this->TSR_KesaiNo != "") $sqlMoji .= ", TSR_KesaiNo";
					if ($this->TSR_ShinseiDate != "") $sqlMoji .= ", TSR_ShinseiDate";
					if ($this->TSR_KesaiDate != "") $sqlMoji .= ", TSR_KesaiDate";
					if ($this->TSR_ShinseiSyainCD != "") $sqlMoji .= ", TSR_ShinseiSyainCD";
					if ($this->TSR_KesaiSyainCD != "") $sqlMoji .= ", TSR_KesaiSyainCD";
					if ($this->ShinseiSyurui != "") $sqlMoji .= ", ShinseiSyurui";
					if ($this->TSR_Flg != "") $sqlMoji .= ", TSR_Flg";

					if ($this->KHI_ShinseiNo != "") $sqlMoji .= ", KHI_ShinseiNo";
					if ($this->KHI_KesaiNo != "") $sqlMoji .= ", KHI_KesaiNo";
					if ($this->KHI_ShinseiDate != "") $sqlMoji .= ", KHI_ShinseiDate";
					if ($this->KHI_KesaiDate != "") $sqlMoji .= ", KHI_KesaiDate";
					if ($this->KHI_ShinseiSyainCD != "") $sqlMoji .= ", KHI_ShinseiSyainCD";
					if ($this->KHI_KesaiSyainCD != "") $sqlMoji .= ", KHI_KesaiSyainCD";
					if ($this->KHI_Flg != "") $sqlMoji .= ", KHI_Flg";

					if ($this->SKH_ShinseiNo != "") $sqlMoji .= ", SKH_ShinseiNo";
					if ($this->SKH_KesaiNo != "") $sqlMoji .= ", SKH_KesaiNo";
					if ($this->SKH_ShinseiDate != "") $sqlMoji .= ", SKH_ShinseiDate";
					if ($this->SKH_KesaiDate != "") $sqlMoji .= ", SKH_KesaiDate";
					if ($this->SKH_ShinseiSyainCD != "") $sqlMoji .= ", SKH_ShinseiSyainCD";
					if ($this->SKH_KesaiSyainCD != "") $sqlMoji .= ", SKH_KesaiSyainCD";
					if ($this->SKH_Flg != "") $sqlMoji .= ", SKH_Flg";

					if ($this->BKN_ShinseiNo != "") $sqlMoji .= ", BKN_ShinseiNo";
					if ($this->BKN_KesaiNo != "") $sqlMoji .= ", BKN_KesaiNo";
					if ($this->BKN_ShinseiDate != "") $sqlMoji .= ", BKN_ShinseiDate";
					if ($this->BKN_KesaiDate != "") $sqlMoji .= ", BKN_KesaiDate";
					if ($this->BKN_ShinseiSyainCD != "") $sqlMoji .= ", BKN_ShinseiSyainCD";
					if ($this->BKN_KesaiSyainCD != "") $sqlMoji .= ", BKN_KesaiSyainCD";
					if ($this->BKN_Flg != "") $sqlMoji .= ", BKN_Flg";

					if ($this->MukouFlg != "") $sqlMoji .= ", MukouFlg";
					$sqlMoji .= ", Created";
					if ($this->Creator != "") $sqlMoji .= ", Creator";
					$sqlMoji .= ", Updated";
					if ($this->Updater != "") $sqlMoji .= ", Updater";
					$sqlMoji .= ") values (";
					$sqlMoji .= "$maxCD";
					if ($this->KenmeiNo != "") $sqlMoji .= ", '" . addslashes($this->KenmeiNo) . "'";
					if ($this->TSR_ShinseiNo != "") $sqlMoji .= ", '" . addslashes($this->TSR_ShinseiNo) . "'";
					if ($this->TSR_KesaiNo != "") $sqlMoji .= ", '" . addslashes($this->TSR_KesaiNo) . "'";
					if ($this->TSR_ShinseiDate != "") $sqlMoji .= ", $this->TSR_ShinseiDate";
					if ($this->TSR_KesaiDate != "") $sqlMoji .= ", $this->TSR_KesaiDate";
					if ($this->TSR_ShinseiSyainCD != "") $sqlMoji .= ", '" . addslashes($this->TSR_ShinseiSyainCD) . "'";
					if ($this->TSR_KesaiSyainCD != "") $sqlMoji .= ", '" . addslashes($this->TSR_KesaiSyainCD) . "'";
					if ($this->ShinseiSyurui != "") $sqlMoji .= ", '" . addslashes($this->ShinseiSyurui) . "'";
					if ($this->TSR_Flg != "") $sqlMoji .= ", '" . addslashes($this->TSR_Flg) . "'";

					if ($this->KHI_ShinseiNo != "") $sqlMoji .= ", '" . addslashes($this->KHI_ShinseiNo) . "'";
					if ($this->KHI_KesaiNo != "") $sqlMoji .= ", '" . addslashes($this->KHI_KesaiNo) . "'";
					if ($this->KHI_ShinseiDate != "") $sqlMoji .= ", $this->KHI_ShinseiDate";
					if ($this->KHI_KesaiDate != "") $sqlMoji .= ", $this->KHI_KesaiDate";
					if ($this->KHI_ShinseiSyainCD != "") $sqlMoji .= ", '" . addslashes($this->KHI_ShinseiSyainCD) . "'";
					if ($this->KHI_KesaiSyainCD != "") $sqlMoji .= ", '" . addslashes($this->KHI_KesaiSyainCD) . "'";
					if ($this->KHI_Flg != "") $sqlMoji .= ", '" . addslashes($this->KHI_Flg) . "'";

					if ($this->SKH_ShinseiNo != "") $sqlMoji .= ", '" . addslashes($this->SKH_ShinseiNo) . "'";
					if ($this->SKH_KesaiNo != "") $sqlMoji .= ", '" . addslashes($this->SKH_KesaiNo) . "'";
					if ($this->SKH_ShinseiDate != "") $sqlMoji .= ", $this->SKH_ShinseiDate";
					if ($this->SKH_KesaiDate != "") $sqlMoji .= ", $this->SKH_KesaiDate";
					if ($this->SKH_ShinseiSyainCD != "") $sqlMoji .= ", '" . addslashes($this->SKH_ShinseiSyainCD) . "'";
					if ($this->SKH_KesaiSyainCD != "") $sqlMoji .= ", '" . addslashes($this->SKH_KesaiSyainCD) . "'";
					if ($this->SKH_Flg != "") $sqlMoji .= ", '" . addslashes($this->SKH_Flg) . "'";

					if ($this->BKN_ShinseiNo != "") $sqlMoji .= ", '" . addslashes($this->BKN_ShinseiNo) . "'";
					if ($this->BKN_KesaiNo != "") $sqlMoji .= ", '" . addslashes($this->BKN_KesaiNo) . "'";
					if ($this->BKN_ShinseiDate != "") $sqlMoji .= ", $this->BKN_ShinseiDate";
					if ($this->BKN_KesaiDate != "") $sqlMoji .= ", $this->BKN_KesaiDate";
					if ($this->BKN_ShinseiSyainCD != "") $sqlMoji .= ", '" . addslashes($this->BKN_ShinseiSyainCD) . "'";
					if ($this->BKN_KesaiSyainCD != "") $sqlMoji .= ", '" . addslashes($this->BKN_KesaiSyainCD) . "'";
					if ($this->BKN_Flg != "") $sqlMoji .= ", '" . addslashes($this->BKN_Flg) . "'";

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
							$this->RingiCD = $maxCD;
						}
					}
				}
			}
		} else {
			// アップデート
			$sqlMoji = "Update $this->TableName set ";
			$sqlMoji .= "RingiCD = $this->RingiCD";
			$sqlMoji .= ", KenmeiNo = " . $this->convertNullStringValue($this->KenmeiNo);
			$sqlMoji .= ", TSR_ShinseiNo = " . $this->convertNullStringValue($this->TSR_ShinseiNo);
			$sqlMoji .= ", TSR_KesaiNo = " . $this->convertNullStringValue($this->TSR_KesaiNo);
			$sqlMoji .= ", TSR_ShinseiDate = " . $this->convertNullStringValue($this->TSR_ShinseiDate);
			$sqlMoji .= ", TSR_KesaiDate = " . $this->convertNullStringValue($this->TSR_KesaiDate);
			$sqlMoji .= ", TSR_ShinseiSyainCD = " . $this->convertNullStringValue($this->TSR_ShinseiSyainCD);
			$sqlMoji .= ", TSR_KesaiSyainCD = " . $this->convertNullStringValue($this->TSR_KesaiSyainCD);
			$sqlMoji .= ", ShinseiSyurui = " . $this->convertNullStringValue($this->ShinseiSyurui);
			$sqlMoji .= ", TSR_Flg = " . $this->convertNullStringValue($this->TSR_Flg);

			$sqlMoji .= ", KHI_ShinseiNo = " . $this->convertNullStringValue($this->KHI_ShinseiNo);
			$sqlMoji .= ", KHI_KesaiNo = " . $this->convertNullStringValue($this->KHI_KesaiNo);
			$sqlMoji .= ", KHI_ShinseiDate = " . $this->convertNullStringValue($this->KHI_ShinseiDate);
			$sqlMoji .= ", KHI_KesaiDate = " . $this->convertNullStringValue($this->KHI_KesaiDate);
			$sqlMoji .= ", KHI_ShinseiSyainCD = " . $this->convertNullStringValue($this->KHI_ShinseiSyainCD);
			$sqlMoji .= ", KHI_KesaiSyainCD = " . $this->convertNullStringValue($this->KHI_KesaiSyainCD);
			$sqlMoji .= ", KHI_Flg = " . $this->convertNullStringValue($this->KHI_Flg);

			$sqlMoji .= ", SKH_ShinseiNo = " . $this->convertNullStringValue($this->SKH_ShinseiNo);
			$sqlMoji .= ", SKH_KesaiNo = " . $this->convertNullStringValue($this->SKH_KesaiNo);
			$sqlMoji .= ", SKH_ShinseiDate = " . $this->convertNullStringValue($this->SKH_ShinseiDate);
			$sqlMoji .= ", SKH_KesaiDate = " . $this->convertNullStringValue($this->SKH_KesaiDate);
			$sqlMoji .= ", SKH_ShinseiSyainCD = " . $this->convertNullStringValue($this->SKH_ShinseiSyainCD);
			$sqlMoji .= ", SKH_KesaiSyainCD = " . $this->convertNullStringValue($this->SKH_KesaiSyainCD);
			$sqlMoji .= ", SKH_Flg = " . $this->convertNullStringValue($this->SKH_Flg);

			$sqlMoji .= ", BKN_ShinseiNo = " . $this->convertNullStringValue($this->BKN_ShinseiNo);
			$sqlMoji .= ", BKN_KesaiNo = " . $this->convertNullStringValue($this->BKN_KesaiNo);
			$sqlMoji .= ", BKN_ShinseiDate = " . $this->convertNullStringValue($this->BKN_ShinseiDate);
			$sqlMoji .= ", BKN_KesaiDate = " . $this->convertNullStringValue($this->BKN_KesaiDate);
			$sqlMoji .= ", BKN_ShinseiSyainCD = " . $this->convertNullStringValue($this->BKN_ShinseiSyainCD);
			$sqlMoji .= ", BKN_KesaiSyainCD = " . $this->convertNullStringValue($this->BKN_KesaiSyainCD);
			$sqlMoji .= ", BKN_Flg = " . $this->convertNullStringValue($this->BKN_Flg);

			$sqlMoji .= ", MukouFlg = " . $this->convertFlgToString($this->MukouFlg);
			$sqlMoji .= ", Created = " . $this->convertNullStringValue($this->Created);
			$sqlMoji .= ", Creator = " . $this->convertNullIntValue($this->Creator);
			$sqlMoji .= ", Updated = NOW()";
			$sqlMoji .= ", Updater = " . $this->convertNullIntValue($this->Updater);
			$sqlMoji .= " where RingiCD = $this->RingiCD";

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
