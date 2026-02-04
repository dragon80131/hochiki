<?php
	include_once "setting.properties";
	include_once _INC_DIR . "carrier.inc";
	include_once _INC_DIR . "global.inc";

	include_once _CLS_DIR . "SPFWDatabase.cls";
	include_once _CLS_DIR . "SPFWLog.cls";
	include_once _CLS_DIR . "SPFWTemplate.cls";
	include_once _CLS_DIR . "SPFWListObject.cls";
	include_once _CLS_DIR . "SPFWDate.cls";
	include_once _CLS_DIR . "SPFWInputCheck.cls";
	include_once _CLS_DIR . "SPUSUser.cls";

	include_once _CLS_DIR . "SPUSBukken.cls";
	include_once _CLS_DIR . "SPUSSagyoin.cls";

	// データベースコネクト
	$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
	if (!$myDB->Connection)
		trigger_error("SPFWDatabase Failed.", E_USER_ERROR);

	########################################################
	# 入力チェック
	########################################################

	if ($rKey == NULL) {
		$URL = _MAIN_URL . 'login_form.php';
		header('Location: ' . $URL);
		exit;
	}


	########################################################
	# 物件情報抽出
	########################################################

if ( $editBukkenCD > 0 ){ #物件情報の修正の場合


	$myListObject = new SPFWListObject($myDB);

	$sql = "SELECT ";

		$sql .= "BukkenCD , ";
		$sql .= "ClientCD, ";
		$sql .= "BukkenName, ";
		$sql .= "BukkenColor , ";
		$sql .= "Kosu , ";
		$sql .= "TantoCD , ";
		$sql .= "BukkenNotes , ";
		$sql .= "MukouFlg , ";
		$sql .= "Setsumei , ";
		$sql .= "KyoyoStartDate , ";
		$sql .= "KyoyoEndDate , ";
		$sql .= "SenyuStartDate , ";
		$sql .= "SenyuEndDate , ";
		$sql .= "ConfirmFlg , ";
		$sql .= "DefaultSagyoin , ";
		$sql .= "MitsumoriNo , ";
		$sql .= "Jucyugaku , ";
		$sql .= "SeikyuNotes , ";
		$sql .= "GenbaTanto , ";
		$sql .= "Nespe , ";
		$sql .= "Panel , ";
		$sql .= "Zairyo , ";
		$sql .= "Kiki , ";
		$sql .= "Kanseizu , ";
		$sql .= "Keitozu , ";
		$sql .= "Complete , ";
		$sql .= "NyukinDate , ";
		$sql .= "Created , ";
		$sql .= "Creator , ";
		$sql .= "Updated , ";
		$sql .= "Updater ";

	$myListObject->SelectSQL = $sql;
	$sql = " FROM tBukkenM";
	$sql .= " WHERE BukkenCD > 0 AND MukouFlg = FALSE AND BukkenCD =".$editBukkenCD ;

	$myListObject->Condition = $sql;
	$myListObject->Order = "";
	$myListObject->Limit = "allpage";
	
	if (!($myListObject->GetList(1)))
		trigger_error("Getting Menu List Failed.", E_USER_ERROR);

	$BukkenLoop = $myListObject->Rows;
	for ($i = 0; $i < $BukkenLoop  ; $i++) {


$wBukkenName = $myListObject->GetValue($i, 2);
$wBukkenColor = $myListObject->GetValue($i, 3);


$wKosu = $myListObject->GetValue($i, 4);
$wTantoCD = $myListObject->GetValue($i, 5);
$wBukkenNotes = $myListObject->GetValue($i, 6);
$wMukouFlg = $myListObject->GetValue($i, 7);
$wSetsumei = $myListObject->GetValue($i, 8);
$wKyoyoStartDate = $myListObject->GetValue($i, 9);
$wKyoyoEndDate = $myListObject->GetValue($i, 10);
$wSenyuStartDate = $myListObject->GetValue($i, 11);
$wSenyuEndDate = $myListObject->GetValue($i, 12);
$wConfirmFlg = $myListObject->GetValue($i, 13);
$wDefaultSagyoin = $myListObject->GetValue($i, 14);
$wMitsumoriNo = $myListObject->GetValue($i, 15);
$wJucyugaku = $myListObject->GetValue($i, 16);
$wSeikyuNotes = $myListObject->GetValue($i, 17);
$wGenbaTanto = $myListObject->GetValue($i, 18);
$wNespe = $myListObject->GetValue($i, 19);
$wPanel = $myListObject->GetValue($i, 20);
$wZairyo = $myListObject->GetValue($i, 21);
$wKiki = $myListObject->GetValue($i, 22);
$wKanseizu = $myListObject->GetValue($i, 23);
$wKeitozu = $myListObject->GetValue($i, 24);
$wComplete = $myListObject->GetValue($i, 25);
$wNyukinDate = $myListObject->GetValue($i, 26);

$wDefaultSagyoinCD = SPFWTools::decodePluralValue($wDefaultSagyoin); #SagyoinCDのArray

	}

}else{
$editBukkenCD = -1 ;
}


	########################################################
	# 営業担当者のリストボックス表示
	########################################################

	// 担当者
	$TantoArray = array ("-","川上","松尾","下出","近藤",
		"平塚","渋谷","益原","中迫","Aさん","Bさん","Cさん");

	$TantoLoop = count($TantoArray);

	// 編集の場合
	if ($wTantoCD <> "") {
		$TantoSelected[$wTantoCD] = selected;
	} else {
		$TantoSelected[0] = selected;
	}

	for ($i = 0; $i < $TantoLoop; $i++) {

		$TantoName[$i] = $TantoArray[$i];
		$TantoNo[$i] = $i;
	}

	########################################################
	# 物件カラーのラジオボタン表示
	########################################################

	// 物件カラー
	$ColorArray = array ("cyan","lime","yellow","orange","deeppink",
		"violet","darkviolet","magenta","darkcyan","turquoise",
		"darkturquoise","yellowgreen","limegreen","forestgreen","midnightblue",
		"moccasin","brown","lightsalmon","red","blue");

	$ColorArrayja = array ("シアン","ライム","黄色","オレンジ","濃いピンク",
		"バイオレット","ダークバイオレット","マゼンタ","ダークシアン","ターコイズ",
		"ダークターコイズ","黄緑","ライムグリーン","フォレストグリーン","ミッドナイトブルー",
		"モカシン","茶色","ライトサーモン","赤","青");

	$ColorLoop = count($ColorArray);
echo "wBukkenColor:".$wBukkenColor ;
	// 編集の場合
	if ($wBukkenColor <> "") {
		$ColorChecked[$wBukkenColor] = "selected";
	}

	for ($i = 0; $i < $ColorLoop; $i++) {

		$ColorName[$i] = $ColorArrayja[$i];
		$ColorValue[$i] = $ColorArray[$i];
		$BukkenColorCD[$i] = $i;
	}

	########################################################
	# 初期設定作業員のチェックボックス表示
	########################################################

	$myListObject = new SPFWListObject($myDB);

	// Select SQL を設定
	$sql = "SELECT ";
	$sql .= "SagyoinCD, ";
	$sql .= "SagyoinName ";

	$myListObject->SelectSQL = $sql;

	// WHERE Condition を設定
	$sql = " FROM tSagyoinM";
	$sql .= " WHERE SagyoinCD > 0 AND MukouFlg = FALSE";
	$myListObject->Condition = $sql;
	$myListObject->Order = $SagyoinCD;
	$myListObject->Limit = "allpage";

	// 検索実行
	if (!($myListObject->GetList(1))) {
		$ErrorString = array();
		$ErrorString[] = "メニューマスタリストの抽出に失敗しました。";
		showAdminSorryPage($ErrorString);
	}

	// データ表示
	$SagyoinListLoop = $myListObject->Rows;

	for ($i = 0; $i < $SagyoinListLoop; $i++) {
		$SagyoinCD[$i] = $myListObject->GetValue($i, 0);
		$SagyoinName[$i] = $myListObject->GetValue($i, 1);

		$wDefaultSagyoinChecked[$i] = ( is_array($wDefaultSagyoinCD)  &&  array_search($SagyoinCD[$i], $wDefaultSagyoinCD) !== FALSE) ? ' checked' : NULL;

	}



	########################################################
	# 認証動作
	########################################################

	$myUser = new User($myDB);

	if (!$myUser->doAuthenticationByRegistKey($rKey))
		trigger_error("doAuthentication Failed.", E_USER_ERROR);

	if ($myUser->UserCD == -1) {
		$URL = _MAIN_URL . 'login_form.php';
		header('Location: ' . $URL);
		exit;
	}

	if ($IfASP && $IfASPSite) {
		include_once _CLS_DIR . "SPUSClient.cls";

		$myClient = new Client($myDB);
		if (!$myClient->executeSelect("ClientCD = " . $myUser->ClientCD . " AND MukouFlg = FALSE") && $myClient->RecCnt != 1)
			trigger_error("Getting Client Failed.", E_USER_ERROR);

		if ($myClient->ID != $MySiteID) {
			showSorryPage(_ILLEGAL_ACCESS);
			exit;
		}

		unset ($myClient);
	}

	$Nickname = $myUser->Nickname;
	$EMail = $myUser->EMail;

	########################################################
	# コンテンツ表示
	########################################################

	$CNT_FILE = basename($_SERVER["SCRIPT_NAME"], ".php") .".tpl";

	$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier);
	$HiddenValues = $myTemplate->getValuesToPass();

	$myTemplate->convertTags();
	$myTemplate->outputTemplate();

	unset($myTemplate);
	unset($myLog);
?>
