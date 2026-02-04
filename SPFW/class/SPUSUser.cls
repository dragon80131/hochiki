<?php
/**
 * Userクラス
 *
 * 会員マスタを扱うクラスです。
 *
 * @package 
 * @access  public
 * @author  MasahitoSAMEKAWA <masahito@souppot.jp>
 * @create  2003/01/31
 * @version 0.1
 **/

$ENCODEKEY[0] = array('a', 'l', '1', 'N', 'U', 'D');
$ENCODEKEY[1] = array('r', 'v', 'c', 'C', 'O', 'E');
$ENCODEKEY[2] = array('3', 'q', 'i', 'Z', 'V', 'I');
$ENCODEKEY[3] = array('j', 'k', 'w', 'B', '9', 'P');
$ENCODEKEY[4] = array('y', 'd', 'b', 'R', 'J', 'A');
$ENCODEKEY[5] = array('p', '2', 's', 'K', 'T', 'H');
$ENCODEKEY[6] = array('f', 'z', 'e', 'S', '6', 'Q');
$ENCODEKEY[7] = array('g', 'm', '4', 'W', 'F', 'L');
$ENCODEKEY[8] = array('o', 't', 'n', 'G', 'X', '8');
$ENCODEKEY[9] = array('u', 'h', 'x', 'Y', '7', 'M');

class User {
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
	 * 会員コード
	 * 
	 * 連番
	 */
	var $UserCD;
	/**
	 * 会員ID
	 */
	var $ID;
	/**
	 * パスワード
	 */
	var $Passwd;
	/**
	 * 変更したログインパスワード	
	 */
	var $Passwd2;
	/**
	 * 姓
	 */
	var $LastName;
	/**
	 * 名
	 */
	var $FirstName;
	/**
	 * 姓かな
	 */
	var $LastNameKana;
	/**
	 * 名かな
	 */
	var $FirstNameKana;
	/**
	 * 性別
	 */
	var $Gender;
	/**
	 * メールアドレス
	 */
	var $EMail;
	/**
	 * 誕生日
	 */
	var $Birthday;
	/**
	 * 郵便番号
	 */
	var $ZipCode;
	/**
	 * 都道府県
	 */
	var $Prefecture;
	/**
	 * 市区町村
	 */
	var $Address1;
	/**
	 * 番地詳細
	 */
	var $Address2;
	/**
	 * 建物名
	 */
	var $Address3;
	/**
	 * 住所カナ
	 */
	var $AddressKana;
	/**
	 * 電話番号
	 */
	var $TEL;
	/**
	 * 顔写真
	 */
	var $FacePhoto;
	/**
	 * 拡張設問1
	 */
	var $Extra1;
	/**
	 * 拡張設問2
	 */
	var $Extra2;
	/**
	 * 拡張設問3
	 */
	var $Extra3;
	/**
	 * 拡張設問4
	 */
	var $Extra4;
	/**
	 * 拡張設問5
	 */
	var $Extra5;
	/**
	 * 拡張設問6
	 */
	var $Extra6;
	/**
	 * 拡張設問7
	 */
	var $Extra7;
	/**
	 * 拡張設問8
	 */
	var $Extra8;
	/**
	 * 拡張設問9
	 */
	var $Extra9;
	/**
	 * 拡張設問10
	 */
	var $Extra10;
	/**
	 * ポイント
	 */
	var $Points;
	/**
	 * メルマガ購読パーミット
	 */
	var $MailMagaFlg;
	/**
	 * 入会日時
	 */
	var $Joined;
	/**
	 * 属性登録完了日時
	 */
	var $Completed;
	/**
	 * 退会日時
	 */
	var $Withdrawn;
	/**
	 * 最終ログイン日時
	 */
	var $LastLogin;
	/**
	 * ショップ区分
	 */
	var $ShopType;
	/**
	 * 会員区分
	 */
	var $UserType;
	/**
	 * レジストキー
	 */
	var $RegistKey;
	/**
	 * 識別キー
	 */
	var $IdentifyKey;
	/**
	 * キャリア
	 */
	var $Carrier;
	/**
	 * UserAgent
	 */
	var $UserAgent;
	/**
	 * 端末識別子
	 */
	var $UID;
	/**
	 * フェリカID
	 */
	var $LineID;
	/**
	 * クライアントコード
	 */
	var $ClientCD;
	/**
	 * アカウントコード
	 */
	var $AccountCD;
	/**
	 * アカウントID
	 */
	var $AccountID;
	/**
	 * URLコード
	 */
	var $URLCD;
	/**
	 * バウンス回数
	 */
	var $BounceCount;
	/**
	 * ワンタイムパス
	 * 
	 * メールアドレス変更時用
	 */
	var $OneTimePass;
	/**
	 * 新メールアドレス
	 * 
	 * メールアドレス変更時の保管用
	 */
	var $NewEMail;
	/**
	 * 備考
	 */
	var $Notes;
	/**
	 * 社員番号
	 */
	var $UserKbn;
	var $EigyosyoCD;
	var $UserRole;
	var $GyosyaCD;
	var $Tou;
	var $BukkenCD;
	var $BuildingCD;
	var $ReplyFlg;
	var $ConfirmFlg;
	/**
	 * 支店・支社
	 */
	var $BrancheCD;
	
	/**
	 * 避難
	 */
	var $RefugeFlg;

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

	/**
	 * EmailVerification関連
	 */
	var $EmailVerificationCode;
	var $EmailVerificationExpiry;
	var $EmailVerified;
	var $Skip2faFlg;

	/**
	 * エラー
	 */
	var $Err;

	function __construct($myDB = "",$UserCD = -1) {
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
		settype($UserCD,"integer");
		// 整数チェック
		if (!is_long($UserCD) or $UserCD == 0) {
			$this->Err = "ユーザーコード が不正です";
			$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
			unset($myLog);
			return FALSE;
		}

		$this->myDB = $myDB;
		$this->TableName = "tUserM";
		$this->UserCD = $UserCD;

		return TRUE;
	}

	function initialize(){
		$this->Err = "";

		$this->UserCD = "";
		$this->ID = "";
		$this->Passwd = "";
		$this->Passwd2 = "";
		$this->LastName = "";
		$this->FirstName = "";
		$this->LastNameKana = "";
		$this->FirstNameKana = "";
		$this->Gender = "";
		$this->EMail = "";
		$this->Birthday = "";
		$this->ZipCode = "";
		$this->Prefecture = "";
		$this->Address1 = "";
		$this->Address2 = "";
		$this->Address3 = "";
		$this->AddressKana = "";
		$this->TEL = "";
		$this->FacePhoto = "";
		$this->Extra1 = "";
		$this->Extra2 = "";
		$this->Extra3 = "";
		$this->Extra4 = "";
		$this->Extra5 = "";
		$this->Extra6 = "";
		$this->Extra7 = "";
		$this->Extra8 = "";
		$this->Extra9 = "";
		$this->Extra10 = "";
		$this->Points = "";
		$this->MailMagaFlg = "";
		$this->Joined = "";
		$this->Completed = "";
		$this->Withdrawn = "";
		$this->LastLogin = "";
		$this->ShopType = "";
		$this->UserType = "";
		$this->RegistKey = "";
		$this->IdentifyKey = "";
		$this->Carrier = "";
		$this->UserAgent = "";
		$this->UID = "";
		$this->LineID = "";
		$this->ClientCD = "";
		$this->AccountCD = "";
		$this->AccountID = "";
		$this->URLCD = "";
		$this->BounceCount = "";
		$this->OneTimePass = "";
		$this->NewEMail = "";
		$this->Notes = "";
		$this->UserKbn = "";
		$this->EigyosyoCD = "";
		$this->UserRole = "";
		$this->GyosyaCD = "";
		$this->Tou = "";
		$this->BukkenCD = "";
		$this->BuildingCD = "";
		$this->ReplyFlg = "";
		$this->ConfirmFlg = "";
		$this->EmailVerificationCode = "";
		$this->EmailVerificationExpiry = "";
		$this->EmailVerified = "";
		$this->Skip2faFlg = "";
		$this->BrancheCD = "";
		$this->RefugeFlg = "";


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
		$sqlMoji .= "UserCD";
		$sqlMoji .= ", ID";
		$sqlMoji .= ", Passwd";
		$sqlMoji .= ", Passwd2";
		$sqlMoji .= ", LastName";
		$sqlMoji .= ", FirstName";
		$sqlMoji .= ", LastNameKana";
		$sqlMoji .= ", FirstNameKana";
		$sqlMoji .= ", Gender";
		$sqlMoji .= ", EMail";
		$sqlMoji .= ", Birthday";
		$sqlMoji .= ", ZipCode";
		$sqlMoji .= ", Prefecture";
		$sqlMoji .= ", Address1";
		$sqlMoji .= ", Address2";
		$sqlMoji .= ", Address3";
		$sqlMoji .= ", AddressKana";
		$sqlMoji .= ", TEL";
		$sqlMoji .= ", FacePhoto";
		$sqlMoji .= ", Extra1";
		$sqlMoji .= ", Extra2";
		$sqlMoji .= ", Extra3";
		$sqlMoji .= ", Extra4";
		$sqlMoji .= ", Extra5";
		$sqlMoji .= ", Extra6";
		$sqlMoji .= ", Extra7";
		$sqlMoji .= ", Extra8";
		$sqlMoji .= ", Extra9";
		$sqlMoji .= ", Extra10";
		$sqlMoji .= ", Points";
		$sqlMoji .= ", MailMagaFlg";
		$sqlMoji .= ", Joined";
		$sqlMoji .= ", Completed";
		$sqlMoji .= ", Withdrawn";
		$sqlMoji .= ", LastLogin";
		$sqlMoji .= ", ShopType";
		$sqlMoji .= ", UserType";
		$sqlMoji .= ", RegistKey";
		$sqlMoji .= ", IdentifyKey";
		$sqlMoji .= ", Carrier";
		$sqlMoji .= ", UserAgent";
		$sqlMoji .= ", UID";
		$sqlMoji .= ", LineID";
		$sqlMoji .= ", ClientCD";
		$sqlMoji .= ", AccountCD";
		$sqlMoji .= ", AccountID";
		$sqlMoji .= ", URLCD";
		$sqlMoji .= ", BounceCount";
		$sqlMoji .= ", OneTimePass";
		$sqlMoji .= ", NewEMail";
		$sqlMoji .= ", Notes";
		$sqlMoji .= ", UserKbn";
		$sqlMoji .= ", EigyosyoCD ";
		$sqlMoji .= ", UserRole ";
		$sqlMoji .= ", GyosyaCD ";
		$sqlMoji .= ", Tou ";
		$sqlMoji .= ", BukkenCD ";
		$sqlMoji .= ", BuildingCD ";
		$sqlMoji .= ", ReplyFlg ";
		$sqlMoji .= ", ConfirmFlg ";
		$sqlMoji .= ", EmailVerificationCode";
		$sqlMoji .= ", EmailVerificationExpiry";
		$sqlMoji .= ", EmailVerified";
		$sqlMoji .= ", Skip2faFlg";
		$sqlMoji .= ", BrancheCD ";
		$sqlMoji .= ", RefugeFlg ";

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
				$this->UserCD,
				$this->ID,
				$this->Passwd,
				$this->Passwd2,
				$this->LastName,
				$this->FirstName,
				$this->LastNameKana,
				$this->FirstNameKana,
				$this->Gender,
				$this->EMail,
				$this->Birthday,
				$this->ZipCode,
				$this->Prefecture,
				$this->Address1,
				$this->Address2,
				$this->Address3,
				$this->AddressKana,
				$this->TEL,
				$this->FacePhoto,
				$this->Extra1,
				$this->Extra2,
				$this->Extra3,
				$this->Extra4,
				$this->Extra5,
				$this->Extra6,
				$this->Extra7,
				$this->Extra8,
				$this->Extra9,
				$this->Extra10,
				$this->Points,
				$this->MailMagaFlg,
				$this->Joined,
				$this->Completed,
				$this->Withdrawn,
				$this->LastLogin,
				$this->ShopType,
				$this->UserType,
				$this->RegistKey,
				$this->IdentifyKey,
				$this->Carrier,
				$this->UserAgent,
				$this->UID,
				$this->LineID,
				$this->ClientCD,
				$this->AccountCD,
				$this->AccountID,
				$this->URLCD,
				$this->BounceCount,
				$this->OneTimePass,
				$this->NewEMail,
				$this->Notes,
				$this->UserKbn,
				$this->EigyosyoCD,
				$this->UserRole,
				$this->GyosyaCD,
				$this->Tou,
				$this->BukkenCD,
				$this->BuildingCD,
				$this->ReplyFlg,
				$this->ConfirmFlg,
				$this->EmailVerificationCode,
				$this->EmailVerificationExpiry,
				$this->EmailVerified,
				$this->Skip2faFlg,
				$this->BrancheCD,
				$this->RefugeFlg,

				$this->MukouFlg,
				$this->Created,
				$this->Creator,
				$this->Updated,
				$this->Updater
			) = $this->myDB->fetchRow($this->tmprtn,$RowNo);

			$this->ID = stripslashes($this->ID);
			$this->Passwd = stripslashes($this->Passwd);
			$this->Passwd2 = stripslashes($this->Passwd2);
			$this->LastName = stripslashes($this->LastName);
			$this->FirstName = stripslashes($this->FirstName);
			$this->LastNameKana = stripslashes($this->LastNameKana);
			$this->FirstNameKana = stripslashes($this->FirstNameKana);
			$this->EMail = stripslashes($this->EMail);
			$this->ZipCode = stripslashes($this->ZipCode);
			$this->Address1 = stripslashes($this->Address1);
			$this->Address2 = stripslashes($this->Address2);
			$this->Address3 = stripslashes($this->Address3);
			$this->AddressKana = stripslashes($this->AddressKana);
			$this->TEL = stripslashes($this->TEL);
			$this->FacePhoto = stripslashes($this->FacePhoto);
			$this->Extra1 = stripslashes($this->Extra1);
			$this->Extra2 = stripslashes($this->Extra2);
			$this->Extra3 = stripslashes($this->Extra3);
			$this->Extra4 = stripslashes($this->Extra4);
			$this->Extra5 = stripslashes($this->Extra5);
			$this->Extra6 = stripslashes($this->Extra6);
			$this->Extra7 = stripslashes($this->Extra7);
			$this->Extra8 = stripslashes($this->Extra8);
			$this->Extra9 = stripslashes($this->Extra9);
			$this->Extra10 = stripslashes($this->Extra10);
			$this->RegistKey = stripslashes($this->RegistKey);
			$this->IdentifyKey = stripslashes($this->IdentifyKey);
			$this->UserAgent = stripslashes($this->UserAgent);
			$this->UID = stripslashes($this->UID);
			$this->LineID = stripslashes($this->LineID);
			$this->AccountID = stripslashes($this->AccountID);
			$this->OneTimePass = stripslashes($this->OneTimePass);
			$this->NewEMail = stripslashes($this->NewEMail);
			$this->Notes = stripslashes($this->Notes);
			$this->UserKbn = stripslashes($this->UserKbn);
			$this->EigyosyoCD = stripslashes($this->EigyosyoCD);
			$this->UserRole = stripslashes($this->UserRole);
			$this->GyosyaCD = stripslashes($this->GyosyaCD);
			$this->Tou = stripslashes($this->Tou);
			$this->BukkenCD = stripslashes($this->BukkenCD);
			$this->BuildingCD = stripslashes($this->BuildingCD);
			$this->ReplyFlg = stripslashes($this->ReplyFlg);
			$this->ConfirmFlg = stripslashes($this->ConfirmFlg);
			$this->EmailVerificationCode = stripslashes($this->EmailVerificationCode);
			$this->EmailVerificationExpiry = stripslashes($this->EmailVerificationExpiry);
			$this->EmailVerified = stripslashes($this->EmailVerified);
			$this->Skip2faFlg = stripslashes($this->Skip2faFlg);
			$this->BrancheCD = stripslashes($this->BrancheCD);
			$this->RefugeFlg = stripslashes($this->RefugeFlg);

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
		
		if ($this->UserCD < 0) {
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
					$sqlMoji = "select UserCD from $this->TableName order by UserCD desc limit 1";
					$maxCD = $this->myDB->getOneValue($sqlMoji);
					$maxCD = ($maxCD == -1) ? 0 : $maxCD;
					$maxCD++;

					// step 4. インサート
					$sqlMoji = "Insert into $this->TableName (";
					$sqlMoji .= "UserCD";
					if ($this->ID != "") $sqlMoji .= ", ID";
					if ($this->Passwd != "") $sqlMoji .= ", Passwd";
					if ($this->Passwd2 != "") $sqlMoji .= ", Passwd2";
					if ($this->LastName != "") $sqlMoji .= ", LastName";
					if ($this->FirstName != "") $sqlMoji .= ", FirstName";
					if ($this->LastNameKana != "") $sqlMoji .= ", LastNameKana";
					if ($this->FirstNameKana != "") $sqlMoji .= ", FirstNameKana";
					if ($this->Gender != "") $sqlMoji .= ", Gender";
					if ($this->EMail != "") $sqlMoji .= ", EMail";
					if ($this->Birthday != "") $sqlMoji .= ", Birthday";
					if ($this->ZipCode != "") $sqlMoji .= ", ZipCode";
					if ($this->Prefecture != "") $sqlMoji .= ", Prefecture";
					if ($this->Address1 != "") $sqlMoji .= ", Address1";
					if ($this->Address2 != "") $sqlMoji .= ", Address2";
					if ($this->Address3 != "") $sqlMoji .= ", Address3";
					if ($this->AddressKana != "") $sqlMoji .= ", AddressKana";
					if ($this->TEL != "") $sqlMoji .= ", TEL";
					if ($this->FacePhoto != "") $sqlMoji .= ", FacePhoto";
					if ($this->Extra1 != "") $sqlMoji .= ", Extra1";
					if ($this->Extra2 != "") $sqlMoji .= ", Extra2";
					if ($this->Extra3 != "") $sqlMoji .= ", Extra3";
					if ($this->Extra4 != "") $sqlMoji .= ", Extra4";
					if ($this->Extra5 != "") $sqlMoji .= ", Extra5";
					if ($this->Extra6 != "") $sqlMoji .= ", Extra6";
					if ($this->Extra7 != "") $sqlMoji .= ", Extra7";
					if ($this->Extra8 != "") $sqlMoji .= ", Extra8";
					if ($this->Extra9 != "") $sqlMoji .= ", Extra9";
					if ($this->Extra10 != "") $sqlMoji .= ", Extra10";
					if ($this->Points != "") $sqlMoji .= ", Points";
					if ($this->MailMagaFlg != "") $sqlMoji .= ", MailMagaFlg";
					$sqlMoji .= ", Joined";
					if ($this->Completed != "") $sqlMoji .= ", Completed";
					if ($this->Withdrawn != "") $sqlMoji .= ", Withdrawn";
					if ($this->LastLogin != "") $sqlMoji .= ", LastLogin";
					if ($this->ShopType != "") $sqlMoji .= ", ShopType";
					if ($this->UserType != "") $sqlMoji .= ", UserType";
					if ($this->RegistKey != "") $sqlMoji .= ", RegistKey";
					if ($this->IdentifyKey != "") $sqlMoji .= ", IdentifyKey";
					if ($this->Carrier != "") $sqlMoji .= ", Carrier";
					if ($this->UserAgent != "") $sqlMoji .= ", UserAgent";
					if ($this->UID != "") $sqlMoji .= ", UID";
					if ($this->LineID != "") $sqlMoji .= ", LineID";
					if ($this->ClientCD != "") $sqlMoji .= ", ClientCD";
					if ($this->AccountCD != "") $sqlMoji .= ", AccountCD";
					if ($this->AccountID != "") $sqlMoji .= ", AccountID";
					if ($this->URLCD != "") $sqlMoji .= ", URLCD";
					if ($this->BounceCount != "") $sqlMoji .= ", BounceCount";
					if ($this->OneTimePass != "") $sqlMoji .= ", OneTimePass";
					if ($this->NewEMail != "") $sqlMoji .= ", NewEMail";
					if ($this->Notes != "") $sqlMoji .= ", Notes";
					if ($this->UserKbn != "") $sqlMoji .= ", UserKbn";
					if ($this->EigyosyoCD != "") $sqlMoji .= ", EigyosyoCD";
					if ($this->UserRole != "") $sqlMoji .= ", UserRole";
					if ($this->GyosyaCD != "") $sqlMoji .= ", GyosyaCD";
					if ($this->Tou != "") $sqlMoji .= ", Tou";
					if ($this->BukkenCD != "") $sqlMoji .= ", BukkenCD";
					if ($this->BuildingCD != "") $sqlMoji .= ", BuildingCD";
					if ($this->ReplyFlg != "") $sqlMoji .= ", ReplyFlg";
					if ($this->ConfirmFlg != "") $sqlMoji .= ", ConfirmFlg";
					if ($this->EmailVerificationCode != "") $sqlMoji .= ", EmailVerificationCode";
					if ($this->EmailVerificationExpiry != "") $sqlMoji .= ", EmailVerificationExpiry";
					if ($this->EmailVerified != "") $sqlMoji .= ", EmailVerified";
					if ($this->Skip2faFlg != "") $sqlMoji .= ", Skip2faFlg";
					if ($this->BrancheCD != "") $sqlMoji .= ", BrancheCD";
					if ($this->RefugeFlg != "") $sqlMoji .= ", RefugeFlg";

					if ($this->MukouFlg != "") $sqlMoji .= ", MukouFlg";
					$sqlMoji .= ", Created";
					if ($this->Creator != "") $sqlMoji .= ", Creator";
					$sqlMoji .= ", Updated";
					if ($this->Updater != "") $sqlMoji .= ", Updater";
					$sqlMoji .= ") values (";
					$sqlMoji .= "$maxCD";
					if ($this->ID != "") $sqlMoji .= ", '" . addslashes($this->ID) . "'";
					if ($this->Passwd != "") $sqlMoji .= ", '" . addslashes($this->Passwd) . "'";
					if ($this->Passwd2 != "") $sqlMoji .= ", '" . addslashes($this->Passwd2) . "'";
					if ($this->LastName != "") $sqlMoji .= ", '" . addslashes($this->LastName) . "'";
					if ($this->FirstName != "") $sqlMoji .= ", '" . addslashes($this->FirstName) . "'";
					if ($this->LastNameKana != "") $sqlMoji .= ", '" . addslashes($this->LastNameKana) . "'";
					if ($this->FirstNameKana != "") $sqlMoji .= ", '" . addslashes($this->FirstNameKana) . "'";
					if ($this->Gender != "") $sqlMoji .= ", $this->Gender";
					if ($this->EMail != "") $sqlMoji .= ", '" . addslashes($this->EMail) . "'";
					if ($this->Birthday != "") $sqlMoji .= ", '" . addslashes($this->Birthday) . "'";
					if ($this->ZipCode != "") $sqlMoji .= ", '" . addslashes($this->ZipCode) . "'";
					if ($this->Prefecture != "") $sqlMoji .= ", $this->Prefecture";
					if ($this->Address1 != "") $sqlMoji .= ", '" . addslashes($this->Address1) . "'";
					if ($this->Address2 != "") $sqlMoji .= ", '" . addslashes($this->Address2) . "'";
					if ($this->Address3 != "") $sqlMoji .= ", '" . addslashes($this->Address3) . "'";
					if ($this->AddressKana != "") $sqlMoji .= ", '" . addslashes($this->AddressKana) . "'";
					if ($this->TEL != "") $sqlMoji .= ", '" . addslashes($this->TEL) . "'";
					if ($this->FacePhoto != "") $sqlMoji .= ", '" . addslashes($this->FacePhoto) . "'";
					if ($this->Extra1 != "") $sqlMoji .= ", '" . addslashes($this->Extra1) . "'";
					if ($this->Extra2 != "") $sqlMoji .= ", '" . addslashes($this->Extra2) . "'";
					if ($this->Extra3 != "") $sqlMoji .= ", '" . addslashes($this->Extra3) . "'";
					if ($this->Extra4 != "") $sqlMoji .= ", '" . addslashes($this->Extra4) . "'";
					if ($this->Extra5 != "") $sqlMoji .= ", '" . addslashes($this->Extra5) . "'";
					if ($this->Extra6 != "") $sqlMoji .= ", '" . addslashes($this->Extra6) . "'";
					if ($this->Extra7 != "") $sqlMoji .= ", '" . addslashes($this->Extra7) . "'";
					if ($this->Extra8 != "") $sqlMoji .= ", '" . addslashes($this->Extra8) . "'";
					if ($this->Extra9 != "") $sqlMoji .= ", '" . addslashes($this->Extra9) . "'";
					if ($this->Extra10 != "") $sqlMoji .= ", '" . addslashes($this->Extra10) . "'";
					if ($this->Points != "") $sqlMoji .= ", $this->Points";
					if ($this->MailMagaFlg != "") $sqlMoji .= ", $this->MailMagaFlg";
					$sqlMoji .= ", NOW()";
					if ($this->Completed != "") $sqlMoji .= ", '" . addslashes($this->Completed) . "'";
					if ($this->Withdrawn != "") $sqlMoji .= ", '" . addslashes($this->Withdrawn) . "'";
					if ($this->LastLogin != "") $sqlMoji .= ", '" . addslashes($this->LastLogin) . "'";
					if ($this->ShopType != "") $sqlMoji .= ", $this->ShopType";
					if ($this->UserType != "") $sqlMoji .= ", $this->UserType";
					if ($this->RegistKey != "") $sqlMoji .= ", '" . addslashes($this->RegistKey) . "'";
					if ($this->IdentifyKey != "") $sqlMoji .= ", '" . addslashes($this->IdentifyKey) . "'";
					if ($this->Carrier != "") $sqlMoji .= ", $this->Carrier";
					if ($this->UserAgent != "") $sqlMoji .= ", '" . addslashes($this->UserAgent) . "'";
					if ($this->UID != "") $sqlMoji .= ", '" . addslashes($this->UID) . "'";
					if ($this->LineID != "") $sqlMoji .= ", '" . addslashes($this->LineID) . "'";
					if ($this->ClientCD != "") $sqlMoji .= ", $this->ClientCD";
					if ($this->AccountCD != "") $sqlMoji .= ", $this->AccountCD";
					if ($this->AccountID != "") $sqlMoji .= ", '" . addslashes($this->AccountID) . "'";
					if ($this->URLCD != "") $sqlMoji .= ", $this->URLCD";
					if ($this->BounceCount != "") $sqlMoji .= ", $this->BounceCount";
					if ($this->OneTimePass != "") $sqlMoji .= ", '" . addslashes($this->OneTimePass) . "'";
					if ($this->NewEMail != "") $sqlMoji .= ", '" . addslashes($this->NewEMail) . "'";
					if ($this->Notes != "") $sqlMoji .= ", '" . addslashes($this->Notes) . "'";
					if ($this->UserKbn != "") $sqlMoji .= ", '" . addslashes($this->UserKbn) . "'";
					if ($this->EigyosyoCD != "") $sqlMoji .= ", '" . addslashes($this->EigyosyoCD) . "'";
					if ($this->UserRole != "") $sqlMoji .= ", '" . addslashes($this->UserRole) . "'";
					if ($this->GyosyaCD != "") $sqlMoji .= ", '" . addslashes($this->GyosyaCD) . "'";
					if ($this->Tou != "") $sqlMoji .= ", '" . addslashes($this->Tou) . "'";
					if ($this->BukkenCD != "") $sqlMoji .= ", '" . addslashes($this->BukkenCD) . "'";
					if ($this->BuildingCD != "") $sqlMoji .= ", '" . addslashes($this->BuildingCD) . "'";
					if ($this->ReplyFlg != "") $sqlMoji .= ", '" . addslashes($this->ReplyFlg) . "'";
					if ($this->ConfirmFlg != "") $sqlMoji .= ", '" . addslashes($this->ConfirmFlg) . "'";
					if ($this->EmailVerificationCode != "") $sqlMoji .= ", '" . addslashes($this->EmailVerificationCode) . "'";
					if ($this->EmailVerificationExpiry != "") $sqlMoji .= ", '" . addslashes($this->EmailVerificationExpiry) . "'";
					if ($this->EmailVerified != "") $sqlMoji .= ", " . convertFlgToString($this->EmailVerified);
					if ($this->Skip2faFlg != "") $sqlMoji .= ", " . convertFlgToString($this->Skip2faFlg);
					if ($this->BrancheCD != "") $sqlMoji .= ", '" . addslashes($this->BrancheCD) . "'";
					if ($this->RefugeFlg != "") $sqlMoji .= ", '" . addslashes($this->RefugeFlg) . "'";

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
							$this->UserCD = $maxCD;
						}
					}
				}
			}
		} else {
			// アップデート
			$sqlMoji = "Update $this->TableName set ";
			$sqlMoji .= "UserCD = $this->UserCD";
			$sqlMoji .= ", ID = " . $this->convertNullStringValue($this->ID);
			$sqlMoji .= ", Passwd = " . $this->convertNullStringValue($this->Passwd);
			$sqlMoji .= ", Passwd2 = " . $this->convertNullStringValue($this->Passwd2);
			$sqlMoji .= ", LastName = " . $this->convertNullStringValue($this->LastName);
			$sqlMoji .= ", FirstName = " . $this->convertNullStringValue($this->FirstName);
			$sqlMoji .= ", LastNameKana = " . $this->convertNullStringValue($this->LastNameKana);
			$sqlMoji .= ", FirstNameKana = " . $this->convertNullStringValue($this->FirstNameKana);
			$sqlMoji .= ", Gender = " . $this->convertNullIntValue($this->Gender);
			$sqlMoji .= ", EMail = " . $this->convertNullStringValue($this->EMail);
			$sqlMoji .= ", Birthday = " . $this->convertNullStringValue($this->Birthday);
			$sqlMoji .= ", ZipCode = " . $this->convertNullStringValue($this->ZipCode);
			$sqlMoji .= ", Prefecture = " . $this->convertNullIntValue($this->Prefecture);
			$sqlMoji .= ", Address1 = " . $this->convertNullStringValue($this->Address1);
			$sqlMoji .= ", Address2 = " . $this->convertNullStringValue($this->Address2);
			$sqlMoji .= ", Address3 = " . $this->convertNullStringValue($this->Address3);
			$sqlMoji .= ", AddressKana = " . $this->convertNullStringValue($this->AddressKana);
			$sqlMoji .= ", TEL = " . $this->convertNullStringValue($this->TEL);
			$sqlMoji .= ", FacePhoto = " . $this->convertNullStringValue($this->FacePhoto);
			$sqlMoji .= ", Extra1 = " . $this->convertNullStringValue($this->Extra1);
			$sqlMoji .= ", Extra2 = " . $this->convertNullStringValue($this->Extra2);
			$sqlMoji .= ", Extra3 = " . $this->convertNullStringValue($this->Extra3);
			$sqlMoji .= ", Extra4 = " . $this->convertNullStringValue($this->Extra4);
			$sqlMoji .= ", Extra5 = " . $this->convertNullStringValue($this->Extra5);
			$sqlMoji .= ", Extra6 = " . $this->convertNullStringValue($this->Extra6);
			$sqlMoji .= ", Extra7 = " . $this->convertNullStringValue($this->Extra7);
			$sqlMoji .= ", Extra8 = " . $this->convertNullStringValue($this->Extra8);
			$sqlMoji .= ", Extra9 = " . $this->convertNullStringValue($this->Extra9);
			$sqlMoji .= ", Extra10 = " . $this->convertNullStringValue($this->Extra10);
			$sqlMoji .= ", Points = " . $this->convertNullIntValue($this->Points);
			$sqlMoji .= ", MailMagaFlg = " . $this->convertNullIntValue($this->MailMagaFlg);
			$sqlMoji .= ", Joined = " . $this->convertNullStringValue($this->Joined);
			$sqlMoji .= ", Completed = " . $this->convertNullStringValue($this->Completed);
			$sqlMoji .= ", Withdrawn = " . $this->convertNullStringValue($this->Withdrawn);
			$sqlMoji .= ", LastLogin = " . $this->convertNullStringValue($this->LastLogin);
			$sqlMoji .= ", ShopType = " . $this->convertNullIntValue($this->ShopType);
			$sqlMoji .= ", UserType = " . $this->convertNullIntValue($this->UserType);
			$sqlMoji .= ", RegistKey = " . $this->convertNullStringValue($this->RegistKey);
			$sqlMoji .= ", IdentifyKey = " . $this->convertNullStringValue($this->IdentifyKey);
			$sqlMoji .= ", Carrier = " . $this->convertNullIntValue($this->Carrier);
			$sqlMoji .= ", UserAgent = " . $this->convertNullStringValue($this->UserAgent);
			$sqlMoji .= ", UID = " . $this->convertNullStringValue($this->UID);
			$sqlMoji .= ", LineID = " . $this->convertNullStringValue($this->LineID);
			$sqlMoji .= ", ClientCD = " . $this->convertNullIntValue($this->ClientCD);
			$sqlMoji .= ", AccountCD = " . $this->convertNullIntValue($this->AccountCD);
			$sqlMoji .= ", AccountID = " . $this->convertNullStringValue($this->AccountID);
			$sqlMoji .= ", URLCD = " . $this->convertNullIntValue($this->URLCD);
			$sqlMoji .= ", BounceCount = " . $this->convertNullIntValue($this->BounceCount);
			$sqlMoji .= ", OneTimePass = " . $this->convertNullStringValue($this->OneTimePass);
			$sqlMoji .= ", NewEMail = " . $this->convertNullStringValue($this->NewEMail);
			$sqlMoji .= ", Notes = " . $this->convertNullStringValue($this->Notes);
			$sqlMoji .= ", UserKbn = " . $this->convertNullStringValue($this->UserKbn);
			$sqlMoji .= ", EigyosyoCD = " . $this->convertNullStringValue($this->EigyosyoCD);
			$sqlMoji .= ", UserRole = " . $this->convertNullStringValue($this->UserRole);
			$sqlMoji .= ", GyosyaCD = " . $this->convertNullStringValue($this->GyosyaCD);
			$sqlMoji .= ", Tou = " . $this->convertNullStringValue($this->Tou);
			$sqlMoji .= ", BukkenCD = " . $this->convertNullStringValue($this->BukkenCD);
			$sqlMoji .= ", BuildingCD = " . $this->convertNullStringValue($this->BuildingCD);
			$sqlMoji .= ", ReplyFlg = " . $this->convertNullStringValue($this->ReplyFlg);
			$sqlMoji .= ", ConfirmFlg = " . $this->convertNullStringValue($this->ConfirmFlg);
			$sqlMoji .= ", EmailVerificationCode = " . $this->convertNullStringValue($this->EmailVerificationCode);
			$sqlMoji .= ", EmailVerificationExpiry = " . $this->convertNullStringValue($this->EmailVerificationExpiry);
			$sqlMoji .= ", EmailVerified = " . $this->convertFlgToString($this->EmailVerified);
			$sqlMoji .= ", Skip2faFlg = " . $this->convertFlgToString($this->Skip2faFlg);
			$sqlMoji .= ", BrancheCD = " . $this->convertNullStringValue($this->BrancheCD);
			$sqlMoji .= ", RefugeFlg = " . $this->convertNullStringValue($this->RefugeFlg);

			$sqlMoji .= ", MukouFlg = " . $this->convertFlgToString($this->MukouFlg);
			$sqlMoji .= ", Created = " . $this->convertNullStringValue($this->Created);
			$sqlMoji .= ", Creator = " . $this->convertNullIntValue($this->Creator);
			$sqlMoji .= ", Updated = NOW()";
			$sqlMoji .= ", Updater = " . $this->convertNullIntValue($this->Updater);
			$sqlMoji .= " where UserCD = $this->UserCD";

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
		if ($flg == "t" || $flg == 1)
			return TRUE;
		else if ($flg == "f" || $flg == 0)
			return FALSE;
	}

	function convertNullIntValue($value){
		if ($value == 0)
			return "0";
		else if ($value == "")
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

	function isExistUserByID($ID, $rKey = "", $UserCD = ""){
		if ($UserCD != "" && !is_numeric($UserCD))
			trigger_error('Illeagl Value Detected.');

		$sql = "select count(*) from " . $this->TableName . " where ID = '" . $this->myDB->escapeString($ID) . "'";
		if ($rKey != "")
			$sql .= " and RegistKey != '" . $this->myDB->escapeString($rKey) . "'";
		if ($UserCD != "")
			$sql .= " and UserCD != " . $UserCD . "";

		$number = $this->myDB->getOneValue($sql);

		if ($number > 0)
			return TRUE;

		return FALSE;
	}

	function isExistUserByEMail($EMail, $rKey = "", $UserCD = ""){
		if ($UserCD != "" && !is_numeric($UserCD))
			trigger_error('Illeagl Value Detected.');

		$sql = "select count(*) from " . $this->TableName . " where EMail = '" . $this->myDB->escapeString($EMail) . "'";
		if ($rKey != "")
			$sql .= " and RegistKey != '" . $this->myDB->escapeString($rKey) . "'";
		if ($UserCD != "")
			$sql .= " and UserCD != " . $UserCD . "";

		$number = $this->myDB->getOneValue($sql);

		if ($number > 0)
			return TRUE;

		return FALSE;
	}

	function isExistUserByUID($UID, $rKey = "", $UserCD = ""){
		if ($UserCD != "" && !is_numeric($UserCD))
			trigger_error('Illeagl Value Detected.');

		$sql = "select count(*) from " . $this->TableName . " where UID = '" . $this->myDB->escapeString($UID) . "' and Withdrawn IS NULL";
		if ($rKey != "")
			$sql .= " and RegistKey != '" . $this->myDB->escapeString($rKey) . "'";
		if ($UserCD != "")
			$sql .= " and UserCD != " . $UserCD . "";

		$number = $this->myDB->getOneValue($sql);

		if ($number > 0)
			return TRUE;

		return FALSE;
	}

	function doAuthentication($id, $password){
		global $PHP_AUTH_USER, $PHP_AUTH_PW;

		// 認証動作
		//if (!$this->executeSelect("ID = '" . $this->myDB->escapeString($id) . "' and ((Passwd = '" . $this->myDB->escapeString($password) . "' and Passwd2 is null) OR (Passwd2 = '" . $this->myDB->escapeString($password) . "') ) and MukouFlg = false","")){
		if (!$this->executeSelect("ID = '" . $this->myDB->escapeString($id) . "' and (Passwd = '" . $this->myDB->escapeString($password) . "' OR Passwd2 = '" . $this->myDB->escapeString($password) . "') and MukouFlg = false","")){
			$this->Err = "認証動作に失敗しました";
			$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
			unset($myLog);
			return false;
		}

		if ($this->RecCnt <> 1){
			return false;
		}

		return true;
	}

	function doAuthenticationByRegistKey($rKey){
		// 認証動作
		if (!$this->executeSelect("RegistKey = '" . $this->myDB->escapeString($rKey) . "' and Withdrawn is NULL and MukouFlg = false","")){
			$this->Err = "認証動作に失敗しました";
			$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
			unset($myLog);
			return false;
		}
		// セッションタイムアウトチェック
		if ($this->LastLogin != NULL && $GLOBALS['Timeout'] > 0) {
			$LastLoginYear = substr($this->LastLogin, 0, 4);
			$LastLoginMonth = substr($this->LastLogin, 5, 2);
			$LastLoginDay = substr($this->LastLogin, 8, 2);
			$LastLoginHour = substr($this->LastLogin, 11, 2);
			$LastLoginMinute = substr($this->LastLogin, 14, 2);
			$LastLoginSecond = substr($this->LastLogin, 17, 2);
			$LastLogin = mktime($LastLoginHour, $LastLoginMinute, $LastLoginSecond, $LastLoginMonth, $LastLoginDay, $LastLoginYear);
			$Now = time();

			if ($Now - $LastLogin > $GLOBALS['Timeout'] * 60) {
				$sql = "UPDATE " . $this->TableName . " SET RegistKey = '" . $this->getNewKey() . "' WHERE UserCD = " . $this->UserCD;

				if (!($rtn = $this->myDB->executeQuery($sql))){
					$this->Err = "会員情報更新処理に失敗しました。" . $this->myDB->Err;
					return FALSE;
				}

				$this->UserCD = -1;
				return TRUE;
			}
		}

		if ($this->RecCnt <> 1)
			$this->UserCD = -1;

		return true;
	}

	function getNewKey($KeyName = 'RegistKey') {
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

		// 候補文字配列を作る
		// 英小文字
		for ($i = ord("a"); $i < ord("z") + 1; $i++)
			$charArray[] = chr($i);
		// 英大文字
		for ($i = ord("A"); $i < ord("Z") + 1; $i++)
			$charArray[] = chr($i);
		// 数字
		for ($i = ord("0"); $i < ord("9") + 1; $i++)
			$charArray[] = chr($i);

		$FlgOK = false;
		while ($FlgOK == false){
			$cnt = 10;
			
			// ランダム文字列生成
			srand((double)microtime()*1000000);
			// $cnt 文字を小文字で生成
			for ($i = 0; $i < $cnt; $i++)
				$strW[$i] = $charArray[rand(0, count($charArray) - 1)];

			// シャッフル
			srand((double)microtime()*1000000);
			shuffle($strW);
	
			// 出力
			$strKey = "";
			for ($i = 0; $i < $cnt; $i++)
				$strKey .= $strW[$i];

			$SqlStr = "select count(*) from tUserM where " . $KeyName . " = '" . $this->myDB->escapeString($strKey) . "'";

			$FlgOK = ($this->myDB->getOneValue($SqlStr) == 0) ? true : false;
		}

		unset($myLog);

		return $strKey;
	}

	function getCount($Date, $Carrier) {
		if (!is_numeric($Carrier))
			trigger_error('Illeagl Value Detected.');

		// カウント取得
		$sql = "select count(*) from " . $this->TableName . " ";
		$sql .= "where Joined::date = '" . $this->myDB->escapeString($Date) . "' ";
		$sql .= "and Carrier = " . $Carrier . " ";
		$count = $this->myDB->getOneValue($sql);

		if ($count == -1)
			$count = 0;

		return $count;
	}

	function updateBounceCount($EMail) {
		// カウント取得
		$sql = "UPDATE " . $this->TableName . " SET BounceCount = BounceCount + 1 WHERE EMail = '" . $this->myDB->escapeString($EMail) . "'";;
		if (!$this->myDB->executeQuery($sql)) {
			$this->Err = "データベースの更新に失敗しました。";
			$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
			unset($myLog);
			return FALSE;
		}

		return TRUEt;
	}

	function removeUserByBounceLimit($BounceLimit){
		if ($BounceLimit == NULL || $BounceLimit <= 0){
			$this->Err = "BounceLimit Is Empty Or Bounce Limit Is Under Zero.";
			return FALSE;
		}

		$sql = "UPDATE " . $this->TableName . " SET Withdrawn = NOW() WHERE BounceCount >= " . $BounceLimit . "::INT2 AND MukouFlg = FALSE AND Withdrawn IS NULL";

		if (!$this->myDB->executeQuery($sql))
			return FALSE;

		return TRUE;
	}

	function executeDelete($WhereStr) {
		// ログ準備
		$myLog = new SPFWLog();

		if ($WhereStr == ""){
			$this->Err = "削除対象の指定がありません";
			$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
			unset($myLog);
			return FALSE;
		}

		// エラークリア
		$this->Err = "";

		//設定
		if ($this->myDB == "") {
			$this->Err = "データベースへのコネクションがありません";
			$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
			unset($myLog);
			return FALSE;
		}

		//SQLセット
		$sqlMoji = "DELETE ";
		$sqlMoji .= " FROM " . $this->TableName . "";
		$sqlMoji .= " WHERE " . $WhereStr;

		$rtn = $this->myDB->executeQuery($sqlMoji);
		if (!$rtn){
			$this->Err = "削除処理に失敗しました";
			$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
			unset($myLog);
			return FALSE;
		}
		return TRUE;
	}

	/**
	 * 複数値をエンコードします(テキスト版)。
	 * 
	 * @access		public
	 * @param		string		$Value		処理対象値
	 * @return		string		処理後値
	 */
	function encodePluralValue($Value, $Separator = '|'){
		if (is_array($Value)) {
			for ($i = 0; $i < count($Value); $i++) {
				if ($i > 0)
					$Encoded .= $Separator;
				$Encoded .= $Value[$i];
			}
		}
		else
			$Encoded = $Value;

		$Encoded = '|' . $Encoded . '|';

		return $Encoded;
	}

	/**
	 * 複数値をデコードします(テキスト版)。
	 * 
	 * @access		public
	 * @param		string		$value		処理対象値
	 * @return		string		処理後値
	 */
	function decodePluralValue($Value, $Separator = '|'){
		if (substr($Value, 0, 1) == '|')
			$Value = substr($Value, 1, -1);

		if ($Value == NULL)
			return array();

		$Decoded = explode($Separator, $Value);

		return $Decoded;
	}

	function resetExtra($No, $ClientCD = NULL) {
		if (!is_numeric($No))
			trigger_error('Illeagl Value Detected.');
		if ($GLOBALS['IfASP'] && $ClientCD == NULL)
			trigger_error('Illeagl Value Detected.');

		//SQLセット
		$sqlMoji = "UPDATE " . $this->TableName . " SET ";
		$sqlMoji .= "Extra" . $No . " = NULL ";
		if ($ClientCD > 0)
			$sqlMoji .= "WHERE ClientCD = " . $ClientCD;

		$rtn = $this->myDB->executeQuery($sqlMoji);
		if (!$rtn){
			$this->Err = "更新処理に失敗しました";
			$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
			unset($myLog);
			return FALSE;
		}

		return TRUE;
	}

	function updatePoints($UserCD, $Points = 0) {
		// カウント取得
		$sql = "UPDATE " . $this->TableName . " SET Points = Points + (" . $Points . ") WHERE UserCD = " . $UserCD . "";;

		if (!$this->myDB->executeQuery($sql)) {
			$this->Err = "データベースの更新に失敗しました。";
			$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
			unset($myLog);
			return FALSE;
		}

		return TRUEt;
	}

	function encodeUserCD($UserCD) {
		if ($UserCD == NULL || !is_numeric($UserCD))
			trigger_error('Illeagl Value Detected.');

		$UserCD = sprintf('%08d', $UserCD);
		$EncodeKey = $GLOBALS['ENCODEKEY'];

		for ($i = 0; $i < 8; $i++) {
			$Index = mt_rand(0, 5);
			$EncodedChar = $EncodeKey[substr($UserCD, $i, 1)][$Index];
			$Encoded = $Encoded . $EncodedChar;
		}

		return $Encoded;
	}

	function decodeUserCD($UserCD) {
		if ($UserCD == NULL || strlen($UserCD) != 8)
			trigger_error('Illeagl Value Detected.');

		$EncodeKey = $GLOBALS['ENCODEKEY'];

		for ($i = 0; $i < 8; $i++) {
			$EncodedChar = substr($UserCD, $i, 1);
			for ($j = 0; $j < 10; $j++) {
				$Index = array_search($EncodedChar, $EncodeKey[$j]);
				if ($Index !== FALSE) {
					$Decoded = $Decoded . $j;
					break;
				}

				if ($j == 9 && $Index === FALSE)
					trigger_error('Illeagl Value Detected.');
			}
		}

		return $Decoded;
	}
}
?>
