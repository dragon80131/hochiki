<?php
include_once "C:/xampp/htdocs/hochiki/SPFW/inc/setting.properties";
include_once _INC_DIR . "carrier.inc";
include_once _INC_DIR . "global.inc";

include_once _CLS_DIR . "SPFWDatabase.cls";
include_once _CLS_DIR . "SPFWLog.cls";
include_once _CLS_DIR . "SPFWTemplate.cls";
include_once _CLS_DIR . "SPFWListObject.cls";
include_once _CLS_DIR . "SPFWDate.cls";
include_once _CLS_DIR . "SPFWInputCheck.cls";
include_once _CLS_DIR . "SPUSUser.cls";
// include_once _CLS_DIR . "SPUSClient.cls";
include_once _CLS_DIR . "SPUSStylist.cls";
// include_once _CLS_DIR . "SPUSSetting.cls";
include_once _CLS_DIR . "SPUSMenu.cls";
include_once _CLS_DIR . "SPUSReservation.cls";
include_once _CLS_DIR . "SPUSCalendar.cls";
include_once _CLS_DIR . "SPFWParameter.cls";

include_once _CLS_DIR . "SPUSBukken.cls";
include_once _CLS_DIR . "SPUSBuilding.cls";

include_once "./include/common_489.php";
include_once "./include/building_period_helpers.php";


// データベースコネクト
$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
if (!$myDB->Connection)
	trigger_error("SPFWDatabase Failed.", E_USER_ERROR);

########################################################
#　多言語化対応(Multilingual support)
########################################################
$lang = get_lang();
$WORD = get_word($myDB); //このあとにset_word()使う。commonplace参照

#利用ワード
$reserve_list1 = $WORD[$lang]['reserve_list.1']; #号室様
$reserve_list2 = $WORD[$lang]['reserve_list.2']; #仮予約日程
$reserve_list3 = $WORD[$lang]['reserve_list.3']; #以下の日程でよろしければ、「確定する」をクリックしてください
$reserve_list4 = $WORD[$lang]['reserve_list.4']; #
$reserve_list5 = $WORD[$lang]['reserve_list.5']; #
$reserve_list6 = $WORD[$lang]['reserve_list.6']; #
$reserve_list7 = $WORD[$lang]['reserve_list.7']; #

$logout1 = $WORD[$lang]['logout.1']; #ログアウト
$finish3 = $WORD[$lang]['finish.3']; #予約TOP

$steppng = 'step2.png';
if ($lang <> 'ja') $steppng = 'step2_en.png';
$stepoppng = 'step2-op.png';
if ($lang <> 'ja') $stepoppng = 'step2-op_en.png';

#### マンション名　をもってくる。
$wLang = SPFWParameter::getValues('wLang');
$rKey = SPFWParameter::getValues('rKey');
$editBukkenCD = SPFWParameter::getValues('editBukkenCD');
$editBuildingCD = SPFWParameter::getValues('editBuildingCD');

$myBukken = new Bukken($myDB);

if (!$myBukken->executeSelect(" BukkenCD = $editBukkenCD AND MukouFlg = FALSE", "") || $myBukken->RecCnt != 1) {
	trigger_error("Getting Setting Failed.", E_USER_ERROR);
}

$myBuilding = new Building($myDB);
if($editBuildingCD){
	if (!$myBuilding->executeSelect("BukkenCD = " . $editBukkenCD . " AND BuildingCD = ".$editBuildingCD." AND MukouFlg = FALSE", "") || $myBuilding->RecCnt != 1) {
		trigger_error("Getting myBuilding Failed.", E_USER_ERROR);
	}
}

$wWEBReceptType = $myBukken->WEBReceptType;
$MansionName 	= $myBukken->BukkenName;
$wBuildingName 	= $myBukken->BuildingName;

$WakuPattern 	= $myBukken->WakuPattern;

if($editBuildingCD){
	$wBuildingName 	= $myBuilding->BuildingName;
	$WakuPattern 	= $myBuilding->WakuPattern;
}

// 棟一覧
$myListObject = new SPFWListObject($myDB);

$sql = "SELECT ";
$sql .= "u.BuildingCD, ";
$sql .= "u.BuildingName ";

$myListObject->SelectSQL = $sql;

$sql = " FROM tBuildingM u ";
$sql .= " WHERE u.MukouFlg = FALSE AND BukkenCD='".$editBukkenCD."'";

$myListObject->Condition = $sql;
$myListObject->Order = "u.BuildingCD ASC";
$myListObject->Limit = "allpage";

if (!($myListObject->GetList(1)))
	trigger_error("Getting User List Failed.", E_USER_ERROR);

$BuildingCD = [];
$BuildingName = [];
$BuildingLoop = $myListObject->Rows;

for ($i = 0; $i < $BuildingLoop; $i++) {
	$BuildingCD[$i] = $myListObject->GetValue($i, 0);
	$BuildingName[$i] = $myListObject->GetValue($i, 1);
}
unset($myListObject);

// 棟名称が空の場合、例外処理
function numberToCircled($number) {
    $map = [
        1 => '①', 2 => '②', 3 => '③', 4 => '④', 5 => '⑤',
        6 => '⑥', 7 => '⑦', 8 => '⑧', 9 => '⑨', 10 => '⑩',
        11 => '⑪', 12 => '⑫', 13 => '⑬', 14 => '⑭', 15 => '⑮',
        16 => '⑯', 17 => '⑰', 18 => '⑱', 19 => '⑲', 20 => '⑳'
    ];

    return $map[$number] ?? $number;
}
if(!$wBuildingName){
	if($editBuildingCD){
		$wBuildingName = '棟'.numberToCircled(2);
		for ($i = 0; $i < $BuildingLoop; $i++) {
			if($BuildingCD[$i] == $editBuildingCD){
				$wBuildingName = '棟'.numberToCircled($i+2);
			}
		}

	}else{
		if($BuildingLoop > 0){
			$wBuildingName = '棟'.numberToCircled(1);
		}
	}
}
if($BuildingLoop < 1){
	$wBuildingName = '';
}

// $TatoFlg = $mySetting->TatoFlg;
// if(empty($TatoFlg)){
	$resolvedPeriod = resolveBuildingSenyuAndYoyaku($myBukken, $editBuildingCD ? $myBuilding : null, $editBuildingCD);
	$YoyakuEndDate  = $resolvedPeriod['YoyakuEndDate']; //受付締切日


	$Today = new DateTime();
	$Today = $Today->format('Y-m-d');
	$Today2 = new DateTime($Today);
	$YoyakuEndDate = new DateTime($YoyakuEndDate);

	if ($Today2 > $YoyakuEndDate) $wWEBReceptType = 2;
// }

unset($myBukken);

####

########################################################
# 入力チェック
########################################################

if ($rKey == NULL) { #2
	$URL = './login_form.php';
	header('Location: ' . $URL);
	exit;
} #2


########################################################
# 認証動作
########################################################

$myUser = new User($myDB);

if (!$myUser->doAuthenticationByRegistKey($rKey))
	trigger_error("doAuthentication Failed.", E_USER_ERROR);

///お客様情報が入ってなかったらformへ戻す20170621
$wTEL = $myUser->TEL;
$wLastName = $myUser->LastName;

if ($wTEL == "" or $wLastName == "") {
	$URL = './form.php?rKey=' . $rKey . '&editBukkenCD='.$editBukkenCD.'&editBuildingCD='.$editBuildingCD.'&wLang=' . $wLang . '&btnflg=1';
	header('Location: ' . $URL);
	exit;
}


if ($myUser->UserCD == -1) { #3
	$URL = './login_form.php';
	header('Location: ' . $URL);
	exit;
} #3

// $wShohiWaku = $myUser->Address2;#20150418追加
$wID = $myUser->ID; #20170608追加
$Address3 = $myUser->Address3;

$IfShowConfirmButton = true;
$IfShowDeclineButton = true;
$PageTitle = '仮予約日程';
if($myUser->ReplyFlg == '3'){
	$IfShowDeclineButton = false;
	$PageTitle = '辞退した日程';
}else if($myUser->ReplyFlg == '1' || $myUser->ReplyFlg == '2' || $myUser->ConfirmFlg == '1'){
	$IfShowConfirmButton = false;
	$PageTitle = '確定した日程';
}


// ########################################################
// # 多棟の場合 ユーザの属する棟の専有部期間に変換
// ########################################################
// if ($TatoFlg == "1" and $Address3 != NULL) {

// 	$ToData = getToData($myDB, $wClientCD);

// 	for ($x = 0; $x < count($ToData); $x++) {
// 		if ($Address3 == $ToData['ToName'][$x]) {
// 			$YoyakuEndDate  = $ToData['YoyakuEndDate'][$x]; //受付締切日
// 			$ExtendedYoyakuEndDate  = $ToData['ExtendedYoyakuEndDate'][$x]; //第2受付締切日

// 			$Today = new DateTime();
// 			$Today = $Today->format('Y-m-d');
// 			$Today2 = new DateTime($Today);
// 			$YoyakuEndDate = new DateTime($YoyakuEndDate);

// 			if ($Today2 > $YoyakuEndDate){
// 				$wWEBReceptType = 2;
// 			}
// 			break;
// 		}
// 	}
// }

########################################################
# クライアント取得
########################################################
$TargetClientCD = $wClientCD;


########################################################
# 予約抽出
########################################################

$myReservation = new Reservation($myDB);

if($editBuildingCD){
	$Condition = "  BukkenCD = $editBukkenCD AND BuildingCD = '" . $editBuildingCD . "' and UserCD = '" . $myUser->UserCD . "' AND Status = 1 AND MukouFlg = FALSE";
}else{
	$Condition = "  BukkenCD = $editBukkenCD AND BuildingCD IS NULL and UserCD = '" . $myUser->UserCD . "' AND Status = 1 AND MukouFlg = FALSE";
}
// $Condition = " UserCD = '" . $myUser->UserCD . "' AND Status = 1 AND MukouFlg = FALSE";
if ($IfASP)
	$Condition .= " AND ClientCD = '" . $TargetClientCD . "' ";

if (!$myReservation->executeSelect($Condition, "TimeFrom DESC"))
	trigger_error("Getting Reservation Failed.", E_USER_ERROR);

if ($myReservation->RecCnt == 0) {
	$IfNoReservation = TRUE;
} else {
	$IfReservation = TRUE;
}

$wTimeFrom = $myReservation->TimeFrom;
$WakuTime = getWakuTime2($WakuPattern, $wTimeFrom);
$Reservationtime = $WakuTime['STime'] . "～" . $WakuTime['ETime'];

$DispReservationDate = date('Y年m月d日', strtotime($wTimeFrom));
$WeekList = array("日", "月", "火", "水", "木", "金", "土");
if ($lang <> 'ja') {
	$DispReservationDate = date('d/m/Y', strtotime($wTimeFrom));
	$WeekList = array("Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat");
}


$w2 = $WeekList[date('w', strtotime($wTimeFrom))];

$ReserveQuery = $QUERY . "&r="  . $myReservation->ReservationCD;










########################################################
# コンテンツ表示
########################################################

// if ($wLang == 1) { #32
// 	$CNT_FILE = "reserve_list_eng.tpl";
// } else { #32
// 	if ((strpos($Company, 'イオ・ネットワーク') !== false) || (strpos($Company, '（株）イオ') !== false)) {
// 		$CNT_FILE = "reserve_list_kakutei.tpl";
	// } elseif ($wWEBReceptType == 2) {
		$CNT_FILE = "reserve_list_kakutei.tpl";
// 	} else {
// 		$CNT_FILE = "reserve_list.tpl";
// 	}
// } #32
$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier, FALSE, $MyClientCD);
$HiddenValues = $myTemplate->getValuesToPass();

$myTemplate->convertTags();
$myTemplate->outputTemplate();

unset($myTemplate);
unset($myLog);
