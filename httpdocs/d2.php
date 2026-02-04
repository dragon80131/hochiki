<?php
include_once "setting.properties";
#調査ミサイル

#*echo "<br>".__LINE__." Start :".microtime(true);

include_once _INC_DIR . "carrier.inc";
include_once _INC_DIR . "global.inc";

include_once _CLS_DIR . "SPFWDatabase.cls";
include_once _CLS_DIR . "SPFWLog.cls";
include_once _CLS_DIR . "SPFWTemplate.cls";
include_once _CLS_DIR . "SPFWListObject.cls";
include_once _CLS_DIR . "SPFWDate.cls";
include_once _CLS_DIR . "SPFWInputCheck.cls";
include_once _CLS_DIR . "SPUSUser.cls";
include_once _CLS_DIR . "SPUSMenu.cls";
include_once _CLS_DIR . "SPUSReservation.cls";
// include_once _CLS_DIR . "SPUSSetting.cls";
#include_once _CLS_DIR . "SPUSTato.cls";
include_once _CLS_DIR . "SPFWParameter.cls";
include_once _CLS_DIR . "SPUSClient.cls";
// include_once _CLS_DIR . "SPUSHearingContent.cls";
// include_once _CLS_DIR . "SPUSKakuninsho.cls";

include_once "./include/common.php";

// データベースコネクト
$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
if (!$myDB->Connection)
	trigger_error("SPFWDatabase Failed.", E_USER_ERROR);


########################################################
# クライアント取得
########################################################
$wClientCD = SPFWParameter::getValues('wClientCD');
$editBukkenCD = SPFWParameter::getValues('editBukkenCD');
########################################################
# 認証動作
########################################################
$rKey = SPFWParameter::getValues('rKey');

$myUser = new User($myDB);
if (!$myUser->doAuthenticationByRegistKey($rKey))
	trigger_error("doAuthentication Failed.", E_USER_ERROR);

if ($myUser->UserCD == -1) {
	$URL = _MAIN_URL ."/login_form.php?wClientCD=".$wClientID;
	header('Location: ' . $URL);
	exit;
}


$MyUserCD = $myUser->UserCD;

unset($myUser);


########################################################
# 作業工程リスト表示
########################################################
$myBukken = new Bukken($myDB);
if (!$myBukken->executeSelect(" BukkenCD = $editBukkenCD AND MukouFlg = FALSE", "")) {
	$ErrorString = array();
	$ErrorString[] = "設定ファイル情報の抽出に失敗しました。";
	$ErrorLoop = count($ErrorString);
	$myTemplate = new SPFWTemplate(_ADMIN_ERROR_TPL, $MyCarrier, TRUE);
	unset($myTemplate);
	exit;
}
$MansionName = $myBukken->BukkenName;
$WakuPattern = $myBukken->WakuPattern;
$TargetClientCD = $myBukken->ClientCD; #111


unset($myBukken);

if (is_array($WAKUPATTERN[$WakuPattern]['AMPM'])) {
	$WakuKazu = count($WAKUPATTERN[$WakuPattern]['AMPM']);
}
// $WakuKazu = count($WAKUPATTERN[$WakuPattern]['AMPM']);
$sSTime = $WAKUPATTERN[$WakuPattern]['StartTime'][0];
$sETime = $WAKUPATTERN[$WakuPattern]['EndTime'][$WakuKazu - 1];

$TimeLoop  = $WakuKazu;
for ($i = 0; $i < $TimeLoop; $i++) { #時間選択し
	$sSTime = $WAKUPATTERN[$WakuPattern]['StartTime'][$i];
	$sETime = $WAKUPATTERN[$WakuPattern]['EndTime'][$i];
	$wTime[$i] = $sSTime . "～" . $sETime;
}

// SELECT r.ID, r.TimeFrom, r.Memo, u.LastName, u.TEL, u.Updater, u.Updated, u.ReplyFlg
// FROM tUserM u
// LEFT OUTER JOIN tReservationF r 
//   ON r.UserCD = u.UserCD
// WHERE u.MukouFlg = FALSE
//   AND u.BukkenCD = 214
//   AND (r.Created > CURDATE() - INTERVAL 6 MONTH OR r.Created IS NULL)
//   AND (r.Status = 1 OR r.Status IS NULL);

$myListObject = new SPFWListObject($myDB);

$sql = "SELECT ";
$sql .= "r.ID, ";
$sql .= "r.TimeFrom, ";
$sql .= "r.Memo, ";
$sql .= "u.LastName, ";
$sql .= "u.TEL, ";
$sql .= "u.Updater,";
$sql .= "u.Updated,";
$sql .= "u.ReplyFlg";
$myListObject->SelectSQL = $sql;

$sql = " FROM tUserM u left outer join tReservationF r on r.UserCD = u.UserCD ";
// if($UserKbn != 2){#ユーザ区分が管理者でなければ　自分の幹事企業のみ表示
$sql .= " WHERE u.MukouFlg = FALSE AND u.BukkenCD = " . $editBukkenCD;
$sql .= " AND (r.Created  > CURDATE() - INTERVAL 4 MONTH OR r.Created IS NULL)";
$sql .= " AND (r.Status = 1 OR r.Status IS NULL)";
$myListObject->Condition	= $sql;

$OrderBy 	= SPFWParameter::getValues('OrderBy');

$myListObject->Order 		= str_replace("_", " ", $OrderBy); #"Created desc";
if( $OrderBy == "ID"){
	$OrderBy = " CAST(u.ID AS UNSIGNED)";
	$myListObject->Order 		= $OrderBy; #"Created desc";
}elseif( $OrderBy == "ID_Desc"){
	$OrderBy = " CAST(u.ID AS UNSIGNED) desc";
	$myListObject->Order 		= $OrderBy; #"Created desc";
}

$myListObject->Limit 		= "allpage";

if (!($myListObject->GetList(1)))
	trigger_error("Getting Menu List Failed.", E_USER_ERROR);

$RecoLoop = $myListObject->Rows;
for ($i = 0; $i < $RecoLoop; $i++) {
	$ID[$i] 	= $myListObject->GetValue($i, 0);
	echo "<br> ".__LINE__." Hensu :".$ID[$i];
	$TimeFrom[$i]	= $myListObject->GetValue($i, 1);
	$TimeFromDate[$i]	= str_replace("-","/",substr($TimeFrom[$i],6,5));
	$TimeFromTime[$i]	= obtainWakuTime($WakuPattern, $TimeFrom[$i],$WAKUPATTERN);
	$Memo[$i]	= $myListObject->GetValue($i, 2);
	$LastName[$i] = $myListObject->GetValue($i, 3);

	$TEL[$i] = $myListObject->GetValue($i, 4);
	$Updater[$i] = $myListObject->GetValue($i, 5);
	$Updated[$i] = $myListObject->GetValue($i, 6);
	$ReplyFlg[$i] = $myListObject->GetValue($i, 7);
	if($ReplyFlg[$i] > 0 ){
		$DispReply[$i] = "レ";
		if($Updater[$i] <> $ID[$i]){
			$DispUpdater[$i] = $LastNameArray[$Updater[$i]]; 
		}else{
			$DispUpdater[$i] = "WEB";
		}
	}
}

unset($myListObject);








########################################################
# コンテンツ表示
########################################################

$CNT_FILE = basename($_SERVER["SCRIPT_NAME"], ".php") . ".tpl";

// kanriでログインした場合は、シンプルなページを表示させる
//$KanriID = SPFWParameter::getValues('KanriID');
if ($IfKanri) $CNT_FILE = "d2kanri.tpl";

$myTemplate 	= new SPFWTemplate($CNT_FILE, $MyCarrier);
$HiddenValues 	= $myTemplate->getValuesToPass();

$myTemplate->convertTags();
$myTemplate->outputTemplate();

unset($myTemplate);
unset($myLog);
