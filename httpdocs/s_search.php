<?php
$isAdminMode = TRUE;
include_once "C:/xampp/htdocs/hochiki/SPFW/inc/setting.properties";
include_once _INC_DIR . "carrier.inc";
include_once _INC_DIR . "global.inc";

include_once _CLS_DIR . "SPFWDatabase.cls";
include_once _CLS_DIR . "SPFWLog.cls";
include_once _CLS_DIR . "SPFWTemplate.cls";
include_once _CLS_DIR . "SPFWListObject.cls";
include_once _CLS_DIR . "SPFWDate.cls";
include_once _CLS_DIR . "SPFWInputCheck.cls";
include_once _CLS_DIR . "SPFWParameter.cls";
include_once _CLS_DIR . "SPUSUser.cls";
include_once _CLS_DIR . "SPUSGyosya.cls";
include_once _CLS_DIR . "SPUSNotice.cls";
include_once _CLS_DIR . "SPUSBukken.cls";
include_once  "./include/kenmei_connect.php";

// データベースコネクト
$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
if (!$myDB->Connection)
	trigger_error("SPFWDatabase Failed.", E_USER_ERROR);

########################################################
# 入力チェック
########################################################
$rKey = SPFWParameter::getValues('rKey');
$m = SPFWParameter::getValues('m');
if ($rKey == NULL) {
	$URL = _MAIN_URL . 'login_form.php';
	header('Location: ' . $URL);
	exit;
}

#アドレス取得　スマホからの本WEBアクセスを不正と表示する。
$YourDomain = $_SERVER["REMOTE_ADDR"];
$Today = date("Y-m-d");
########################################################
# 認証チェック
########################################################
$myUser = new User($myDB);

if ($rKey == NULL)
	showSorryPage(_ILLEGAL_ACCESS2);

if (!$myUser->doAuthenticationByRegistKey($rKey))
	trigger_error("doAuthentication Failed.", E_USER_ERROR);

if ($myUser->UserCD == -1)
	showSorryPage(_ILLEGAL_ACCESS2);

$loginID = $myUser->ID;

$UserKbn = $myUser->UserKbn;
$IfDeleteHead = true;
$IfDeleteCell = true;
$IfCheckboxHead = true;
$IfCheckboxCell = true;
if ($UserKbn == 3) {
	// $SHeaderKanri =	'<div class="col-12 text-center">';
	// $SHeaderKanri .= 	'<img class="logo" src="./images/489work.png" alt="489作業者" width="600" height="73">';
	// $SHeaderKanri .= '</div>';

	$IfDeleteHead = false;
	$IfDeleteCell = false;
	$IfCheckboxHead = false;
	$IfCheckboxCell = false;

}
$IfDevelper = $UserKbn != 3;
$IfWorker = $UserKbn == 3;
$IfSP = $m == 1; // スマホ用
$IfPC = $m != 1;
if($IfSP){
	$IfDevelper = false;
}
$UserType = $myUser->UserType;
if($UserType != '1'){
	$IfDeleteHead = false;
	$IfDeleteCell = false;
	$IfCheckboxHead = false;
	$IfCheckboxCell = false;
}
$BrancheCD = $myUser->BrancheCD;
// マスターメンテナンス
$IfMasterMaintenance = false;
if($IfDevelper && $UserType == '1'){
	$IfMasterMaintenance = true;
}

if (preg_match('/nespe/', $loginID)) {
	// $IfNespe = true;
	$IfGyosya = false;
} else {
	$IfNespe = false;
	$IfNespeCell = false;
	$IfGyosya = true;
}
if ($UserKbn != "3") { //幹事企業だった場合
	$IfNespe = true;
	$IfNespeCell = true;
}

$LastName = $myUser->LastName; #名前
$wUserCD = $myUser->UserCD;
$wClientCD = $myUser->ClientCD;
$PartnerGyosyaCD = $myUser->GyosyaCD;

if ($Extra3 == "2") {
	$IfSystemUser = true; //tpl表示用
	$IfSystemUser1 = true; //tpl表示用
	$IfSystemUser2 = true; //tpl表示用
	if (strpos($loginID, "nespe") !== false) $IfSystemNespeUser = true; //tpl表示用
}


########################################################
# 初期値設定
########################################################
$myPage  = SPFWParameter::getValues("myPage");
// 現在ページ
if (!isset($myPage) || $myPage == "" || $myPage < 1) $myPage = 1;

// 幹事企業名
$ClientName = '';
// if($IfDevelper){
	$myListObject = new SPFWListObject($myDB);
	$sql  = "SELECT ";
	$sql .= "tClientM.ClientName ";
	$myListObject->SelectSQL = $sql;
	$sql = " FROM tUserM LEFT JOIN tClientM ON tUserM.ClientCD = tClientM.ClientCD ";
	$sql .= " WHERE tUserM.MukouFlg = FALSE AND tClientM.MukouFlg = FALSE AND tUserM.UserCD = '".$myUser->UserCD."'";
	$myListObject->Condition = $sql;
	$myListObject->Order = "tUserM.UserCD desc";
	$myListObject->Limit = 1;
	if (!($myListObject->GetList($myPage)))
		trigger_error("Getting Bukken List Failed.", E_USER_ERROR);
	$ClientLoop = $myListObject->Rows;
	for ($i = 0; $i < $ClientLoop; $i++) {
		$ClientName = $myListObject->GetValue($i, 0);
		break;
	}
// }

// 協力会社名
$wGyosyaName = '';
if($IfWorker){
	$myListObject = new SPFWListObject($myDB);
	$sql  = "SELECT ";
	$sql .= "tGyosyaM.GyosyaName ";
	$myListObject->SelectSQL = $sql;
	$sql = " FROM tUserM LEFT JOIN tGyosyaM ON tUserM.GyosyaCD = tGyosyaM.GyosyaCD ";
	$sql .= " WHERE tUserM.MukouFlg = FALSE AND tGyosyaM.MukouFlg = FALSE AND tUserM.UserCD = '".$myUser->UserCD."'";
	$myListObject->Condition = $sql;
	$myListObject->Order = "tUserM.UserCD desc";
	$myListObject->Limit = 1;
	if (!($myListObject->GetList($myPage)))
		trigger_error("Getting Bukken List Failed.", E_USER_ERROR);
	$ClientLoop = $myListObject->Rows;
	for ($i = 0; $i < $ClientLoop; $i++) {
		$wGyosyaName = $myListObject->GetValue($i, 0);
		break;
	}
}

unset($myUser);

// 1ページ当たり件数
$cRowsPerPage = 20;
$cRowsPerPage2 = 20;


########################################################
# モバイル用QRコード表示
########################################################

$folderPath = './images';
// フォルダが存在するかチェック
if (!is_dir($folderPath)) {
	// フォルダが存在しない場合、作成する
	if (mkdir($folderPath, 0777, true)) {
		// echo "フォルダ '$folderPath' が作成されました。";
	}
}
$file = $folderPath."/".$wClientCD.'mobile.png';
// QRコードを生成するデータ
// $URLdata = _ROOT_URL . 'login.php?editBukkenCD=' . $editBukkenCD;
$URLdata = _ROOT_URL . 'login_form.php?m=1';
// 毎回コードから生成する（URLが変わっても古い画像が残らないように）
include_once 'phpqrcode/qrlib.php';
QRcode::png($URLdata, $file, QR_ECLEVEL_L, 10);
// echo 'QRコードが生成されました: ' . $file;
// ブラウザキャッシュ対策（URLが変わった時だけ再取得される）
$file .= '?v=' . substr(md5($URLdata), 0, 8);




########################################################
# 値受け取り
########################################################
$KensakuDisp = SPFWParameter::getValues('KensakuDisp');

if ($KensakuDisp == "1") {
	$wBukkenCD = SPFWParameter::getValues('wBukkenCD');
	$wKenmeiNo = SPFWParameter::getValues('wKenmeiNo');
	$wSitenCD = SPFWParameter::getValues('wSitenCD');

	$wBukkenName = SPFWParameter::getValues('wBukkenName');
	$wBukkenNameKana = SPFWParameter::getValues('wBukkenNameKana');
	$wAddress = SPFWParameter::getValues('wAddress');
	$wKanriGaisya = SPFWParameter::getValues('wKanriGaisya');
	$wStartKosu = SPFWParameter::getValues('wStartKosu');
	$wEndKosu = SPFWParameter::getValues('wEndKosu');
	$wStartMonth = SPFWParameter::getValues('wStartMonth');
	$wEndMonth = SPFWParameter::getValues('wEndMonth');
	$wRNstate = SPFWParameter::getValues('wRNstate');
	$wTaioPhase = SPFWParameter::getValues('wTaioPhase');
	$wGyosyaCompany = SPFWParameter::getValues('wGyosyaCompany');
	$wKanriCompany = SPFWParameter::getValues('wKanriCompany');
	$wBrancheCompany = SPFWParameter::getValues('wBrancheCompany');
	$wBukkenMemo = SPFWParameter::getValues('wBukkenMemo');
	$wTenkenMonth = SPFWParameter::getValues('wTenkenMonth');
	$BukkenStatus = SPFWParameter::getValues('BukkenStatus');
	$Tenken_Category = SPFWParameter::getValues('Tenken_Category');
	$wSenyuStartDate = SPFWParameter::getValues('wSenyuStartDate');
	$wSenyuEndDate = SPFWParameter::getValues('wSenyuEndDate');
	$wSenyuMonth = SPFWParameter::getValues('wSenyuMonth');

	// 空白除去、数値確認
	if (is_numeric($wBukkenCD)) {
		$wBukkenCD = trim($wBukkenCD);
	} else {
		$wBukkenCD = "";
	}

	if ($wBukkenName) 	$wBukkenName 	= trim($wBukkenName);
	if ($wBukkenNameKana) 	$wBukkenNameKana 	= trim($wBukkenNameKana);
	if ($wAddress) 		$wAddress 		= trim($wAddress);
	if ($wKanriGaisya) 	$wKanriGaisya 	= trim($wKanriGaisya);

	if (is_numeric($wStartKosu)) 	$wStartKosu 	= trim($wStartKosu);
	else $wStartKosu = "";

	if (is_numeric($wEndKosu)) 		$wEndKosu 		= trim($wEndKosu);
	else $wEndKosu = "";

	if (is_numeric($wStartMonth))	$wStartMonth 	= trim($wStartMonth);
	else $wStartMonth = "";

	if (is_numeric($wEndMonth))		$wEndMonth 		= trim($wEndMonth);
	else $wEndMonth = "";
} else {

	// 検索ボタンを押していない場合
	$wBukkenCD = "";
	$wSitenCD = "";
	$wBukkenName = "";
	$wBukkenNameKana = "";
	$wAddress = "";
	$wKanriGaisya = "";
	$wStartKosu = "";
	$wEndKosu = "";
	$wStartMonth = "";
	$wEndMonth = "";
	$wRNstate = "";
	$wTaioPhase = "";
	$wGyosyaCompany = "";
	$wKanriCompany = "";
	$wBrancheCompany = "";
	$wKyoyoStartDate = "";
	$wSenyuEndDate = "";

	$wSenyuStartDate = "";
	$wSenyuEndDate = "";
	$wSenyuMonth = "";

}

########################################################
# 支店・支社情報取得
########################################################

$myListObject = new SPFWListObject($myDB);

$sql = "SELECT ";
$sql .= "BrancheCD, ";
$sql .= "BrancheName ";
$myListObject->SelectSQL = $sql;

$sql = " FROM tBrancheM ";
$sql .= " WHERE MukouFlg = FALSE";

$myListObject->Condition	= $sql;
$myListObject->Order 		= "";
$myListObject->Limit 		= "allpage";

if (!($myListObject->GetList(1)))
	trigger_error("Getting Menu List Failed.", E_USER_ERROR);

$BrancheCompanyLoop = $myListObject->Rows;
for ($i = 0; $i < $BrancheCompanyLoop; $i++) {
	$aBrancheCD[$i] 	= $myListObject->GetValue($i, 0);
	$BrancheName[$i]	= $myListObject->GetValue($i, 1);
	if($wBrancheCompany == $aBrancheCD[$i]){
		$BrancheSelected[$i] = " selected";
	}
}
unset($myListObject);


########################################################
# お知らせ機能
########################################################
$myListObject = new SPFWListObject($myDB);
$sql  = "SELECT ";
$sql .= "Memo, ";	#0
$sql .= "Updated "; #1
$myListObject->SelectSQL = $sql;
$sql  = " FROM tNoticeF";
$sql .= " WHERE MukouFlg = FALSE ";

$myListObject->Condition = $sql;
$myListObject->Order = "Updated DESC";
$myListObject->Limit = "allpage";

if (!($myListObject->GetList($myPage)))
	trigger_error("Getting Notice List Failed.", E_USER_ERROR);

$NoticeLoop = $myListObject->Rows;
for ($i = 0; $i < $NoticeLoop; $i++) {
	$Memo[$i] = $myListObject->GetValue($i, 0);
	$Updated[$i] = $myListObject->GetValue($i, 1);
	$wUpdated[$i] = date('Y/n/d', strtotime($Updated[$i]));
}
########################################################
# 物件削除
########################################################
$deleteFlg = SPFWParameter::getValues('deleteFlg');
if ($deleteFlg == "1") {

	$delete_editBukkenCD = SPFWParameter::getValues('editBukkenCD');

	$myBukken =	new Bukken($myDB);

	if (!$myBukken->executeSelect(" BukkenCD = '" . $delete_editBukkenCD . "' AND MukouFlg = FALSE", "")) {
		trigger_error("Getting Bukken Failed.", E_USER_ERROR);
	}
	if ($myBukken->RecCnt == 1) {

		$myBukken->MukouFlg = "1";

		if (!$myBukken->executeUpdate()) {
			trigger_error("Updating Bukken Failed.", E_USER_ERROR);
		}
	}
	unset($myBukken);
}else if($deleteFlg == "2"){ // 選択した物件を削除
	$chk_item = SPFWParameter::getValues("chk_item"); // 配列
	if(is_array($chk_item) && !empty($chk_item)){
		foreach($chk_item as $deleteBukkenCD){
			$myBukken =	new Bukken($myDB);

			if (!$myBukken->executeSelect(" BukkenCD = '" . $deleteBukkenCD . "' AND MukouFlg = FALSE", "")) {
				trigger_error("Getting Bukken Failed.", E_USER_ERROR);
			}
			if ($myBukken->RecCnt == 1) {

				$myBukken->MukouFlg = "1";

				if (!$myBukken->executeUpdate()) {
					trigger_error("Updating Bukken Failed.", E_USER_ERROR);
				}
			}
			unset($myBukken);
		}
	}
}

########################################################
# 物件検索＆結果表示
########################################################
// 検索ボタン押下
if ($KensakuDisp == "1") {

	$IfSearch = true;
	// 検索
	$myListObject = new SPFWListObject($myDB);
	$sql = "SELECT ";
	$sql .= "b.BukkenCD , ";	#0
	$sql .= "b.BukkenName, ";	#1
	$sql .= "b.GyosyaCD, "; #2
	$sql .= "b.BukkenMemo, "; #3
	$sql .= "b.KikiStatus, "; #4

	$sql .= "b.SougouTenkenMonth,";
	$sql .= "b.KikiTenkenMonth,";
	$sql .= "b.ReportSubmitted, ";
	$sql .= "b.FirstKojiDate,";
	$sql .= "b.LastKojiDate,";
	$sql .= "b.HaifuDownloadDate, ";

	$sql .= "b.Date1,";
	$sql .= "b.Date2,";
	$sql .= "b.Date3,";
	$sql .= "b.Date4,";
	$sql .= "b.Date5,";
	$sql .= "b.BousaiTenkenMonth,";
	$sql .= "b.BousaiStartDate, ";
	$sql .= "b.BousaiEndDate, ";
	$sql .= "b.GyosyaBousaiCD, ";
	$sql .= "b.SenyuStartDate,"; #20
	$sql .= "b.SenyuEndDate,"; #21
	$sql .= "k.KanriCompanyName"; #22


	$myListObject->SelectSQL = $sql;
	$sql = " FROM tBukkenM AS b LEFT OUTER JOIN tGyosyaM AS g ON b.GyosyaCD = g.GyosyaCD ";
	$sql .= " LEFT OUTER JOIN tKanriCompanyM AS k ON b.KanriCompanyCD = k.KanriCompanyCD ";
	$sql .= " WHERE b.MukouFlg = FALSE ";

	if ($UserKbn == "1") {
		$sql .= " AND b.ClientCD = " . $wClientCD;

		if ($UserType != "1") {
			$sql .= " AND b.BrancheCD = '" . $BrancheCD . "'";
		}

	} else if ($UserKbn == "3") {
		$sql .= " AND ( b.GyosyaCD = " . $PartnerGyosyaCD . " OR b.GyosyaBousaiCD = " . $PartnerGyosyaCD . ")";
	}

	if ($wBukkenCD > 0) { #物件CDで検索
		$sql .= " AND b.BukkenCD = " . $wBukkenCD;
	}
	if (strlen($wBukkenName) > 0) { #物件名で検索
		$sql .= " AND b.BukkenName like '%" . $wBukkenName . "%'";
	}
	if (strlen($wBukkenNameKana) > 0) { #物件名で検索
		$sql .= " AND b.BukkenNameKana like '%" . $wBukkenNameKana . "%'";
	}
	if (strlen($wAddress) > 0) { #住所で検索
		$sql .= " AND b.Address like '%" . $wAddress . "%'";
	}
	if (strlen($wGyosyaCompany) > 0) { #会社別で検索
		$sql .= " AND g.GyosyaName like '%" . $wGyosyaCompany . "%'";
	}
	if (strlen($wKanriCompany) > 0) { #会社別で検索
		$sql .= " AND k.KanriCompanyName like '%" . $wKanriCompany . "%'";
	}
	if (strlen($wBrancheCompany) > 0) { #会社別で検索
		$sql .= " AND b.BrancheCD = '" . $wBrancheCompany . "'";
	}
	if (strlen($wBukkenMemo) > 0) { #会社別で検索
		$sql .= " AND b.BukkenMemo like '%" . $wBukkenMemo . "%'";
	}
	if($wSenyuMonth != ""){
		$sql .= " AND MONTH(b.SenyuEndDate) >= " . $wSenyuMonth;
		$sql .= " AND MONTH(b.SenyuStartDate) <= " . $wSenyuMonth;

		${"SenyuMonthSelected" . $wSenyuMonth} = "selected";
	}else{
		if ($wSenyuStartDate != "") { #作業期間
			$sql .= " AND b.SenyuEndDate >= '" . $wSenyuStartDate . "'";
		}
		if ($wSenyuEndDate != "") { #作業期間
			$sql .= " AND b.SenyuStartDate <= '" . $wSenyuEndDate . "'";
		}
	}

	if ($BukkenStatus) {
		$sql .= " AND KikiStatus = " . $BukkenStatus;
		${"BukkenStatusSelected" . $BukkenStatus} = "selected";
	}

	if ($Tenken_Category == "1") {
		// 消防点検の場合
		${"Tenken_Category_Selected1"} = "selected";

		// KikiTenkenMonthとSougouTenkenMonthがNULLではない物件を検索
		$sql .= " AND (b.KikiTenkenMonth IS NOT NULL OR b.SougouTenkenMonth IS NOT NULL) ";

		if ($wTenkenMonth) {
			$sql .= " AND ( KikiTenkenMonth = '" . $wTenkenMonth . "' OR SougouTenkenMonth = '" . $wTenkenMonth . "')";
			${"TenkenSelected" . $wTenkenMonth} = "selected";
		}
	} else if ($Tenken_Category == "2") {
		//防火が有りの物件の場合
		${"Tenken_Category_Selected2"} = "selected";
		$sql .= " AND  BousaiFlg = 1";

		if ($wTenkenMonth) {
			$sql .= " AND  BousaiTenkenMonth = '" . $wTenkenMonth . "'";
			${"TenkenSelected" . $wTenkenMonth} = "selected";
		}
	} else {
		if ($wTenkenMonth) {
			$sql .= " AND ( KikiTenkenMonth = '" . $wTenkenMonth . "' OR SougouTenkenMonth = '" . $wTenkenMonth . "')";
			${"TenkenSelected" . $wTenkenMonth} = "selected";
		}
	}


	$myListObject->Condition = $sql;
	$myListObject->Order = "b.BukkenCD desc";

	$myListObject->Limit = $cRowsPerPage;
	if (!($myListObject->GetList($myPage)))
		trigger_error("Getting Bukken List Failed.", E_USER_ERROR);

	$BukkenLoop = $myListObject->Rows;
	for ($i = 0; $i < $BukkenLoop; $i++) {
		$BukkenCD[$i] = $myListObject->GetValue($i, 0);
		$BukkenName[$i] = $myListObject->GetValue($i, 1);
		$GyosyaCD[$i] = $myListObject->GetValue($i, 2);
		$SekoShutai[$i] = $myListObject->GetValue($i, 3);
		$KanriCompanyName[$i] = $myListObject->GetValue($i, 22);

		if ($GyosyaCD[$i]) {
			$myGyosya = new Gyosya($myDB);

			if (!$myGyosya->executeSelect(" GyosyaCD =" . $GyosyaCD[$i], ""))
				trigger_error('Getting Gyosya Failed.', E_USER_ERROR);

			$GyosyaName[$i] = $myGyosya->GyosyaName;
		}

		$SougouTenkenMonth[$i] = $myListObject->GetValue($i, 5);
		$KikiTenkenMonth[$i] = $myListObject->GetValue($i, 6);

		$CurrentMonth = date('n');
		$date = array($SougouTenkenMonth[$i], $KikiTenkenMonth[$i]);
		$num = 0;
		// 次回消防点検が近い方（機器か総合）の月を表示
		while ($num < 13) {
			$check_date = $CurrentMonth + $num;
			if ($check_date > 12) {
				$check_date = $check_date - 12;
			}
			$index = array_search($check_date, $date);
			if ($index !== FALSE) {
				$result_date[$i] = $date[$index];
				break;
			}
			$num++;
		}

		$ReportSubmitted[$i] = $myListObject->GetValue($i, 7);
		if ($ReportSubmitted[$i] == 1) {
			$IsReport[$i] = "IsReport";
		}

		$FirstKojiDate[$i] = $myListObject->GetValue($i, 8);
		$LastKojiDate[$i] = $myListObject->GetValue($i, 9);
		$HaifuDownloadDate[$i] = $myListObject->GetValue($i, 10);

		if ($IfNespe) {
			$GyosyaDisp[$i] = $GyosyaName[$i];
		} else {
		}

		$Status[$i] = $myListObject->GetValue($i, 4);
		if ($Status[$i] == 1) {
			$Now[$i] = "now";

			$IfNow[$i] = true;
		} else if ($Status[$i] == 2) {
			$Now[$i] = "now";
			$Now1[$i] = "now";

			$IfNow[$i] = true;
			$IfNow1[$i] = true;
		} else if ($Status[$i] == 3) {

			$Now[$i] = "now";
			$Now1[$i] = "now";
			$Now2[$i] = "now";

			$IfNow[$i] = true;
			$IfNow1[$i] = true;
			$IfNow2[$i] = true;
		}

		if ($UserKbn != 3) {
			$IfKanri[$i] = true;
			$IfKanri2[$i] = true;
		}

		$HaifuDownloadDate[$i] = $myListObject->GetValue($i, 10);

		$Date1[$i] = $myListObject->GetValue($i, 11);
		$Date2[$i] = $myListObject->GetValue($i, 12);
		$Date3[$i] = $myListObject->GetValue($i, 13);
		$Date4[$i] = $myListObject->GetValue($i, 14);
		$Date5[$i] = $myListObject->GetValue($i, 15);

		$Dates[$i] = array($Date1[$i], $Date2[$i], $Date3[$i], $Date4[$i], $Date5[$i]);
		usort($Dates[$i], "date_sort");
		$Dates[$i] = array_diff($Dates[$i], array(""));
		$Dates[$i] = array_values($Dates[$i]);
		$FirstKojiDate[$i] = current($Dates[$i]);
		$LastKojiDate[$i] = end($Dates[$i]);
		if ($FirstKojiDate[$i] == $LastKojiDate[$i]) {
			$LastKojiDate[$i] = "";
		}
		// if ($LastKojiDate[$i] == "") {
		// 	$KojiDate[$i] = $FirstKojiDate[$i];
		// } else {
		// 	$KojiDate[$i] = $FirstKojiDate[$i] . "<br>～" . $LastKojiDate[$i];
		// }
		$SenyuStartDate[$i] = $myListObject->GetValue($i, 20);
		$SenyuEndDate[$i] = $myListObject->GetValue($i, 21);
		if ($SenyuEndDate[$i] == "") {
			$KojiDate[$i] = $SenyuStartDate[$i];
		} else {
			$KojiDate[$i] = $SenyuStartDate[$i] . "～" . $SenyuEndDate[$i];
		}

		if ($myListObject->GetValue($i, 16)) {
			$BousaiTenkenMonth[$i]  = $myListObject->GetValue($i, 16) . "月";
		}

		$BousaiStartDate[$i] = $myListObject->GetValue($i, 17);
		$BousaiEndDate[$i] = $myListObject->GetValue($i, 18);
		if ($BousaiStartDate[$i] == $BousaiEndDate[$i]) {
			$BousaiEndDate[$i] = "";
		}
		if ($BousaiEndDate[$i] == "") {
			$Bouka_KojiDate[$i] = $BousaiStartDate[$i];
		} else {
			$Bouka_KojiDate[$i] = $BousaiStartDate[$i] . "<br>～" . $BousaiEndDate[$i];
		}

		$GyosyaBousaiCD[$i] = $myListObject->GetValue($i, 19);
		$myGyosya2 = new Gyosya($myDB);
		if ($GyosyaBousaiCD[$i]) {
			if (!$myGyosya2->executeSelect(" GyosyaCD =" . $GyosyaBousaiCD[$i], ""))
				trigger_error('Getting Gyosya Failed.', E_USER_ERROR);

			$GyosyaName2[$i] = $myGyosya2->GyosyaName;
			if ($IfNespe) {
				$GyosyaDisp2[$i] = $GyosyaName2[$i];
			} else {
			}
		}
	}

	$wSitenSelect = str_replace(",", "", $wSitenSelect);
	$kSitenSelect = $wSitenSelect;
	$kBukkenCD = $wBukkenCD;
	$kBukkenName = $wBukkenName;
	$kAddress = $wAddress;
	$kKanriGaisya = $wKanriGaisya;
	$kRNState = $wRNState;
	$kTaioPhase = $wTaioPhase;
	$kKyoyoStartDate = $wKyoyoStartDate;
	$kSenyuEndDate = $wSenyuEndDate;

	// ページ遷移関連
	$AllPages = $myListObject->Pages; //1ページ辺りの表示件数が全部で何ぺージあるか
	$PreviousPage = $myPage - 1;
	$NextPage = $myPage + 1;
	$LastPage = $AllPages;
	if ($myPage > 1) { //現在ページが2以上かどうか
		$IfToTop = true;
		$IfToPre = true;
	}
	if ($myPage < $AllPages) { //全部のページ数の最高の数字より小さいかどうか
		$IfToNext = true;
		$IfToLast = true;
	}

	// 結果を判断
	if ($myListObject->Rows == 0) {
		$IfNoResults = TRUE;
		$myPage = 0;
	} else
		$IfResults = TRUE;

	$AllRows = $myListObject->Count;

	unset($myListObject);
} elseif ($KensakuDisp != "1") {

	$IfSearch = true;

	// 検索
	$myListObject = new SPFWListObject($myDB);
	$sql = "SELECT ";
	$sql .= "b.BukkenCD , ";	#0
	$sql .= "b.BukkenName, ";	#1
	// $sql .= "i.IraiRenkeiStatus, "; #2
	// $sql .= "i.SekoShutai, ";	#3
	$sql .= "b.GyosyaCD, "; #2
	$sql .= "b.BukkenMemo, "; #3
	// $sql .= "k.KyoyoStartDate, "; #6
	// $sql .= "k.SenyuEndDate, "; #7
	$sql .= "b.KikiStatus,";#4

	$sql .= "b.SougouTenkenMonth,";#5
	$sql .= "b.KikiTenkenMonth, ";#6
	$sql .= "b.ReportSubmitted, ";#7
	$sql .= "b.FirstKojiDate,";#8
	$sql .= "b.LastKojiDate,";#9
	$sql .= "b.HaifuDownloadDate,";#10

	$sql .= "b.Date1,";
	$sql .= "b.Date2,";
	$sql .= "b.Date3,";
	$sql .= "b.Date4,";
	$sql .= "b.Date5,";
	$sql .= "b.BousaiTenkenMonth,";
	$sql .= "b.BousaiStartDate, ";
	$sql .= "b.BousaiEndDate, ";
	$sql .= "b.GyosyaBousaiCD, ";
	$sql .= "b.SenyuStartDate,"; #20
	$sql .= "b.SenyuEndDate,"; #21
	$sql .= "k.KanriCompanyName"; #22



	$myListObject->SelectSQL = $sql;
#	$sql = " FROM tBukkenM AS b LEFT OUTER JOIN tIraiRenkeiF AS i ON b.BukkenCD = i.BukkenCD LEFT OUTER JOIN tKojiF AS k ON b.BukkenCD = k.BukkenCD";
	$sql = " FROM tBukkenM AS b ";
	$sql .= " LEFT OUTER JOIN tKanriCompanyM AS k ON b.KanriCompanyCD = k.KanriCompanyCD ";
	$sql .= " WHERE b.MukouFlg = FALSE ";

	if ($UserKbn == "1") {
		$sql .= " AND b.ClientCD = " . $wClientCD;

		if ($UserType != "1") {
			$sql .= " AND b.BrancheCD = '" . $BrancheCD . "'";
		}
	} else if ($UserKbn == "3") {
		$sql .= " AND ( b.GyosyaCD = " . $PartnerGyosyaCD . " OR b.GyosyaBousaiCD = " . $PartnerGyosyaCD . ")";
	}

	$myListObject->Condition = $sql;
	$myListObject->Order = "b.BukkenCD desc";

	$myListObject->Limit = $cRowsPerPage;

	if (!($myListObject->GetList($myPage)))
		trigger_error("Getting Bukken List Failed.", E_USER_ERROR);

	$BukkenLoop = $myListObject->Rows;
	for ($i = 0; $i < $BukkenLoop; $i++) {

		$BukkenCD[$i] = $myListObject->GetValue($i, 0);
		$BukkenName[$i] = $myListObject->GetValue($i, 1);
		$KanriCompanyName[$i] = $myListObject->GetValue($i, 22);
		// $IraiRenkeiStatus[$i] = $myListObject->GetValue($i, 2);

		// if ($IraiRenkeiStatus[$i] != "依頼済み") {
		// 	$IfNotAlreadyCooperate[$i] = TRUE;
		// }
		// $SekoShutai[$i] = $myListObject->GetValue($i, 3);
		$GyosyaCD[$i] = $myListObject->GetValue($i, 2);

		$myGyosya = new Gyosya($myDB);
		if ($GyosyaCD[$i]) {
			if (!$myGyosya->executeSelect(" GyosyaCD =" . $GyosyaCD[$i], ""))
				trigger_error('Getting Gyosya Failed.', E_USER_ERROR);

			$GyosyaName[$i] = $myGyosya->GyosyaName;
		}

		$BukkenMemo[$i] = $myListObject->GetValue($i, 3);
		// $KyoyoStartDate[$i] = $myListObject->GetValue($i, 6);
		// $SenyuEndDate[$i] = $myListObject->GetValue($i, 7);
		if ($IfNespe) {
			$GyosyaDisp[$i] = $GyosyaName[$i];
		} else {
			// $GyosyaDisp[$i] = "$KyoyoStartDate[$i]〜$SenyuEndDate[$i]";
		}

		$Status[$i] = $myListObject->GetValue($i, 4);
		if ($Status[$i] == 1) {
			$Now[$i] = "now";

			$IfNow[$i] = true;
		} else if ($Status[$i] == 2) {
			$Now[$i] = "now";
			$Now1[$i] = "now";

			$IfNow[$i] = true;
			$IfNow1[$i] = true;
		} else if ($Status[$i] == 3) {
			$Now[$i] = "now";
			$Now1[$i] = "now";
			$Now2[$i] = "now";

			$IfNow[$i] = true;
			$IfNow1[$i] = true;
			$IfNow2[$i] = true;
		}

		$SougouTenkenMonth[$i] = $myListObject->GetValue($i,5);
		$KikiTenkenMonth[$i] = $myListObject->GetValue($i, 6);

		$ReportSubmitted[$i] = $myListObject->GetValue($i, 7);
		if ($ReportSubmitted[$i] == 1) {
			$IsReport[$i] = "IsReport";
		}

		$FirstKojiDate[$i] = $myListObject->GetValue($i, 8);
		$LastKojiDate[$i] = $myListObject->GetValue($i, 9);

		$CurrentMonth = date('n');
		$date = array($SougouTenkenMonth[$i], $KikiTenkenMonth[$i]);
		$num = 0;
		// 次回消防点検が近い方（機器か総合）の月を表示
		while ($num < 13) {
			$check_date = $CurrentMonth + $num;
			if ($check_date > 12) {
				$check_date = $check_date - 12;
			}
			$index = array_search($check_date, $date);
			if ($index !== FALSE) {
				$result_date[$i] = $date[$index];
				break;
			}
			$num++;
		}

		if ($UserKbn != 3) {
			$IfKanri[$i] = true;
			$IfKanri2[$i] = true;
		}

		$HaifuDownloadDate[$i] = $myListObject->GetValue($i, 10);

		$Date1[$i] = $myListObject->GetValue($i, 11);
		$Date2[$i] = $myListObject->GetValue($i, 12);
		$Date3[$i] = $myListObject->GetValue($i, 13);
		$Date4[$i] = $myListObject->GetValue($i, 14);
		$Date5[$i] = $myListObject->GetValue($i, 15);

		$Dates[$i] = array($Date1[$i], $Date2[$i], $Date3[$i], $Date4[$i], $Date5[$i]);
		usort($Dates[$i], "date_sort");
		$Dates[$i] = array_diff($Dates[$i], array(""));
		$Dates[$i] = array_values($Dates[$i]);
		$FirstKojiDate[$i] = current($Dates[$i]);
		$LastKojiDate[$i] = end($Dates[$i]);
		if ($FirstKojiDate[$i] == $LastKojiDate[$i]) {
			$LastKojiDate[$i] = "";
		}
		// if ($LastKojiDate[$i] == "") {
		// 	$KojiDate[$i] = $FirstKojiDate[$i];
		// } else {
		// 	$KojiDate[$i] = $FirstKojiDate[$i] . "<br>～" . $LastKojiDate[$i];
		// }
		$SenyuStartDate[$i] = $myListObject->GetValue($i, 20);
		$SenyuEndDate[$i] = $myListObject->GetValue($i, 21);
		if ($SenyuEndDate[$i] == "") {
			$KojiDate[$i] = $SenyuStartDate[$i];
		} else {
			$KojiDate[$i] = $SenyuStartDate[$i] . "～" . $SenyuEndDate[$i];
		}

		if ($myListObject->GetValue($i, 16)) {
			$BousaiTenkenMonth[$i]  = $myListObject->GetValue($i, 20) . "月";
		}

		$BousaiStartDate[$i] = $myListObject->GetValue($i, 17);
		$BousaiEndDate[$i] = $myListObject->GetValue($i, 18);
		if ($BousaiStartDate[$i] == $BousaiEndDate[$i]) {
			$BousaiEndDate[$i] = "";
		}
		if ($BousaiEndDate[$i] == "") {
			$Bouka_KojiDate[$i] = $BousaiStartDate[$i];
		} else {
			$Bouka_KojiDate[$i] = $BousaiStartDate[$i] . "<br>～" . $BousaiEndDate[$i];
		}

		$GyosyaBousaiCD[$i] = $myListObject->GetValue($i, 19);
		$myGyosya2 = new Gyosya($myDB);
		if ($GyosyaBousaiCD[$i]) {
			if (!$myGyosya2->executeSelect(" GyosyaCD =" . $GyosyaBousaiCD[$i], ""))
				trigger_error('Getting Gyosya Failed.', E_USER_ERROR);

			$GyosyaName2[$i] = $myGyosya2->GyosyaName;
			if ($IfNespe) {
				$GyosyaDisp2[$i] = $GyosyaName2[$i];
			} else {
			}
		}
	}

	$wSitenSelect = str_replace(",", "", $wSitenSelect);
	$kSitenSelect = $wSitenSelect;
	$kBukkenCD = $wBukkenCD;
	$kBukkenName = $wBukkenName;
	$kAddress = $wAddress;
	$kKanriGaisya = $wKanriGaisya;
	$kRNState = $wRNState;
	$kTaioPhase = $wTaioPhase;

	// ページ遷移関連
	$AllPages = $myListObject->Pages;
	$PreviousPage = $myPage - 1;
	$NextPage = $myPage + 1;
	$LastPage = $AllPages;
	if ($myPage > 1) {
		$IfToTop = true;
		$IfToPre = true;
	}
	if ($myPage < $AllPages) {
		$IfToNext = true;
		$IfToLast = true;
	}

	// 結果を判断
	if ($myListObject->Rows == 0) {
		$IfNoResults = TRUE;
		$myPage = 0;
	} else {
		$IfResults = TRUE;
	}
	$AllRows = $myListObject->Count;
	unset($myListObject);
} else {
	$IfError = true;
}

SPFWTemplate::dropValue("myPage");
SPFWTemplate::dropValue("cRowsPerPage");
SPFWTemplate::dropValue("KensakuDisp");
SPFWTemplate::dropValue("wBukkenCD");
SPFWTemplate::dropValue("wKenmeiNo");
SPFWTemplate::dropValue("wBukkenName");
SPFWTemplate::dropValue("wBukkenNameKana");
SPFWTemplate::dropValue("wAddress");
SPFWTemplate::dropValue("wKanriGaisya");
SPFWTemplate::dropValue("wStartKosu");
SPFWTemplate::dropValue("wEndKosu");
SPFWTemplate::dropValue("wStartMonth");
SPFWTemplate::dropValue("wEndMonth");
SPFWTemplate::dropValue("wRNStatus");
SPFWTemplate::dropValue("wSitenSelect");
SPFWTemplate::dropValue("wSenyuStartDate");
SPFWTemplate::dropValue("wSenyuEndDate");
SPFWTemplate::dropValue("wSenyuMonth");


########################################################
# コンテンツ表示
########################################################
$HiddenValues = SPFWTemplate::getValuesToPass();

$CNT_FILE = "s_search.tpl";

$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier);

$myTemplate->convertTags();
$myTemplate->outputTemplate();

unset($myTemplate);
unset($myLog);
########################################################
# 関数群
########################################################
function date_sort($a, $b)
{
	return strtotime($a) - strtotime($b);
}