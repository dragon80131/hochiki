<?php

// ini_set('display_errors', "On");

include_once "D:/xampp/htdocs/hochiki/SPFW/inc/setting.properties";
include_once _INC_DIR . "carrier.inc";
include_once _INC_DIR . "global.inc";
include_once _CLS_DIR . "SPFWDatabase.cls";
include_once _CLS_DIR . "SPFWLog.cls";
include_once _CLS_DIR . "SPFWTemplate.cls";
include_once _CLS_DIR . "SPFWParameter.cls";
include_once _CLS_DIR . "SPFWListObject.cls";
include_once _CLS_DIR . "SPFWDate.cls";
include_once _CLS_DIR . "SPFWInputCheck.cls";
include_once _CLS_DIR . "SPUSUser.cls";
include_once _CLS_DIR . "SPUSBukken.cls";
include_once _CLS_DIR . "SPUSSign.cls";
// include_once _CLS_DIR . "SPUSKoji.cls";
include_once _CLS_DIR . "SPFWTools.cls";

$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
if (!$myDB->Connection)
	trigger_error("SPFWDatabase Failed.", E_USER_ERROR);

########################################################
# 値取得
########################################################
$rKey 			= SPFWParameter::getValues("rKey");
$editBukkenCD 	= SPFWParameter::getValues('editBukkenCD');
$deleteFlg 	= SPFWParameter::getValues('deleteFlg');


########################################################
# 認証動作
########################################################
if ($rKey) {
	$myUser = new User($myDB);
	if ($rKey == NULL)
		showSorryPage(_ILLEGAL_ACCESS);

	if (!$myUser->doAuthenticationByRegistKey($rKey))
		trigger_error("doAuthentication Failed.", E_USER_ERROR);

	if ($myUser->UserCD == -1)
		showSorryPage(_ILLEGAL_ACCESS);

	$UserCD 		= $myUser->UserCD;
	$ClientCD 		= $myUser->ClientCD; #幹事企業CD
	unset($myUser);
}

########################################################
# 削除
########################################################
if($deleteFlg == 1){
	
	$deleteID 	= SPFWParameter::getValues('ID');
	$mySign = new Sign($myDB);


	if (!$mySign->executeSelect(" Created >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH) AND  BukkenCD = '".$editBukkenCD."' AND ID = '".$deleteID."' AND MukouFlg = FALSE", "") || $mySign->RecCnt != 1) {
		trigger_error("Getting mySign Failed.", E_USER_ERROR);
	}
	$FilePath = $mySign->FilePath ;

	if (unlink("D:/xampp/htdocs/hochiki/httpdocs/".$FilePath)) {
		echo "ファイル '$FilePath' が削除されました。";
	} else {
		echo "ファイル '$FilePath' を削除できませんでした。";
	}


	$SignCD = $mySign->SignCD	;
	$mySign->MukouFlg = 1;
	$mySign->Updater = $MyUserCD;

	if (!$mySign->executeUpdate()) {
		trigger_error("executeUpdate(mySign) Failed.", E_USER_ERROR);
	} 

}


// ########################################################
// # 担当者リスト表示
// ########################################################
// $myListObject = new SPFWListObject($myDB);

// $sql = "SELECT ";
// $sql .= "UserCD, ";
// $sql .= "LastName ";

// $myListObject->SelectSQL = $sql;

// $sql = " FROM tUserM";
// // if($UserKbn != 2){#ユーザ区分が管理者でなければ　自分の幹事企業のみ表示
// $sql .= " WHERE MukouFlg = FALSE AND ClientCD = " . $ClientCD;
// $sql .= " AND UserKbn < 4 ";#住人さん以外
// $myListObject->Condition	= $sql;
// $myListObject->Order 		= "";
// $myListObject->Limit 		= "allpage";

// if (!($myListObject->GetList(1)))
// 	trigger_error("Getting Menu List Failed.", E_USER_ERROR);

// $UserLoop = $myListObject->Rows;
// for ($i = 0; $i < $UserLoop; $i++) {
// 	$UserCDs[$i] 	= $myListObject->GetValue($i, 0);
// 	$LastName[$i]	= $myListObject->GetValue($i, 1);
// 	$LastNameArray[$UserCDs[$i]] = $LastName[$i];
// }
// unset($myListObject);
########################################################
# 
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


$myListObject = new SPFWListObject($myDB);

$sql = "SELECT ";
$sql .= "r.ID, ";
$sql .= "r.TimeFrom, ";
$sql .= "r.Memo, ";
$sql .= "u.LastName, ";
$sql .= "u.TEL, ";
$sql .= "u.Updater,";
$sql .= "u.Updated,";
$sql .= "u.ReplyFlg,";
$sql .= "r.KanryoFlg";
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

$ResidentsFormLoop = $myListObject->Rows;
for ($i = 0; $i < $ResidentsFormLoop; $i++) {
	$ID[$i] 	= $myListObject->GetValue($i, 0);
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


	$KanryoFlg[$i] = $myListObject->GetValue($i, 8);
	if($KanryoFlg[$i] == 1){#完了書がある
		$Kanryosyo[$i] ="<a href='#' onclick=\"javascript:moveWithKey('s_d2.php?rKey=".$rKey."&ID=".$ID[$i]."&deleteFlg=1',".$editBukkenCD." )\">削除</a>";


		$KanryosyoColor[$i] =" bgcolor ='lightgray' ";









	}else{
		$Kanryosyo[$i] ="<a href='#' onclick=\"javascript:moveWithKey('s_sign.php?rKey=".$rKey."&ID=".$ID[$i]."',".$editBukkenCD." )\">未</a>";
	}
	// __Kanryosyo__
	// <a href="#" onclick="javascript:moveWithKey('s_sign.php?rKey=__rKey__&ID=__ID__',__editBukkenCD__ )">完了書</a>


}

unset($myListObject);


########################################################
# コンテンツ表示
########################################################
$CNT_FILE = "s_d2.tpl";

$myTemplate 	= new SPFWTemplate($CNT_FILE, $MyCarrier);
$HiddenValues 	= $myTemplate->getValuesToPass();

$myTemplate->convertTags();
$myTemplate->outputTemplate();

unset($myTemplate);
unset($myLog);
########################################################
# 関数群
########################################################
#品番一致
function getDeviceData_Hinban($myDB, $Category, $Hinban)
{

	$DeviceData = array();

	$myListObject = new SPFWListObject($myDB);

	$sql = "SELECT ";
	$sql .= "DeviceName, ";
	$sql .= "Kataban, ";
	$sql .= "D003, "; #機器説明
	$sql .= "ShortName ";
	$myListObject->SelectSQL = $sql;

	$sql = " FROM tDeviceM";
	$sql .= " WHERE MukouFlg = FALSE and Category = '" . $Category . "'"; #1:親機 2:子機
	$sql .= " AND Kataban = '" . $Hinban . "' ";

	$myListObject->Condition = $sql;
	$myListObject->Order = "Kataban";
	$myListObject->Limit = "allpage";


	if (!($myListObject->GetList(1)))
		trigger_error("Getting Menu List Failed.", E_USER_ERROR);

	if ($myListObject->Rows == 1) {
		$DeviceData['DeviceName'] 	= $myListObject->GetValue(0, 0);
		$DeviceData['Kataban'] 		= $myListObject->GetValue(0, 1);
		$DeviceData['KikiSetumei'] 	= $myListObject->GetValue(0, 2);
		$DeviceData['ShortName'] 	= $myListObject->GetValue(0, 3);
	}
	unset($myListObject);

	return $DeviceData;
}

function obtainWakuTime($WakuPattern, $TimeFrom,$WAKUPATTERN){
	$TimeFrom = substr($TimeFrom,11,5);#09:20
    $sTimeFrom = strtotime($TimeFrom);
    $WakuSu = count($WAKUPATTERN[$WakuPattern]['AMPM']);

    for($i=0; $i<$WakuSu; $i++){
        $StartTime = $WAKUPATTERN[$WakuPattern]['StartTime'][$i];
        $sStartTime = strtotime($StartTime);
        $EndTime = $WAKUPATTERN[$WakuPattern]['EndTime'][$i];
        $sEndTime = strtotime($EndTime);

        // $sTimeFromが$sStartTime以上、かつ$sEndTime以下の場合に時間枠に含まれる
        if($sStartTime <= $sTimeFrom && $sTimeFrom < $sEndTime){
            // $WakuTime['STime'] = $StartTime;
            // $WakuTime['ETime'] = $EndTime;
			$WakuTime = $WAKUPATTERN[$WakuPattern]['AMPM'][$i];

			// echo "<br> ".__LINE__." StartTime :".$StartTime;
			// echo "<br> ".__LINE__." EndTime :".$EndTime;

            return $WakuTime;
        }
    }
    return null;  // どの時間枠にも当てはまらない場合はnullを返す

}