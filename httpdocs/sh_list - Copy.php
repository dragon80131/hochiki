<?php

include_once "D:/xampp/htdocs/hochiki/SPFW/inc/setting.properties";
	include_once _INC_DIR . "carrier.inc";
	include_once _INC_DIR . "global.inc";

	include_once _CLS_DIR . "SPFWDatabase.cls";
	include_once _CLS_DIR . "SPFWLog.cls";
	include_once _CLS_DIR . "SPFWTemplate.cls";
	include_once _CLS_DIR . "SPFWListObject.cls";
	include_once _CLS_DIR . "SPFWParameter.cls";
#	include_once _CLS_DIR . "SPFWDate.cls";
	include_once _CLS_DIR . "SPFWInputCheck.cls";
	include_once _CLS_DIR . "SPUSUser.cls";
	include_once _CLS_DIR . "SPUSReservation.cls";
	include_once _CLS_DIR . "SPUSBukken.cls";
	// include_once _CLS_DIR . "SPUSHenkoRoom.cls";
	// include_once _CLS_DIR . "SPUSHenkoDate.cls";



	// データベースコネクト
	$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
	if (!$myDB->Connection)
		trigger_error("SPFWDatabase Failed.", E_USER_ERROR);



	########################################################
	# 値取得
	########################################################
	$rKey 			= SPFWParameter::getValues("rKey");
	$editBukkenCD = SPFWParameter::getValues('editBukkenCD');
	$ClientCD = SPFWParameter::getValues('ClientCD');
	$work = SPFWParameter::getValues('work');
	$m 				= SPFWParameter::getValues('m');

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

			$myUserCD 		= $myUser->UserCD;
			$ClientCD 		= $myUser->ClientCD;
			$UserKbn 		= $myUser->UserKbn;
			unset($myUser);
	}

$IfWorker = $UserKbn == 3;
$IfDeveloper	= $UserKbn != 3;
$IfSP 			= $m == 1; // スマホ用
$IfPC 			= $m != 1;
$IfShowSchedule = false;
if($IfSP){
	if($IfDeveloper)
		$IfShowSchedule = true;
	$IfWorker		= true;
	$IfDeveloper	= false;
}

// if ($UserKbn == 3) {
// 	$SHeaderKanri = "<div style='text-align:center;'>";
// 	$SHeaderKanri .= "<img class='logo'  src='./images/489work.png' alt='489作業者' width='600' height='73'>";
// 	$SHeaderKanri .= "</div>";
// }

#######################################移植
	########################################################
# クライアント取得 tBukkenMからとる
########################################################
$myBukken = new Bukken($myDB);
if (!$myBukken->executeSelect(" BukkenCD = '".$editBukkenCD."' AND MukouFlg = FALSE", "")) {
	$ErrorString = array();
	$ErrorString[] = "設定ファイル情報の抽出に失敗しました。";
	$ErrorLoop = count($ErrorString);
	$myTemplate = new SPFWTemplate(_ADMIN_ERROR_TPL, $MyCarrier, TRUE);
	unset($myTemplate);
	exit;
}
$MansionName = $myBukken->BukkenName;
$wWakuPattern = $myBukken->WakuPattern;
$MaxWakuSu = $myBukken->MaxWakuSu;#6-4-4
$KokonoWakuSu = explode("-",$MaxWakuSu);
$wHansu = $myBukken->Hansu;
$TargetClientCD = $myBukken->ClientCD;#111
$SenyuStartDate = $myBukken->SenyuStartDate;
$SenyuEndDate = $myBukken->SenyuEndDate;
$SenyuDateCnt = ((strtotime($SenyuEndDate) -  strtotime($SenyuStartDate)) / 86400) + 1; #専有部日数
$SyonitiKouryo = $myBukken->FirstDateFeature; 
$MinuteTime = $myBukken->MinuteTime; #20ぷん
$Holiday1 = $myBukken->Holiday1;
$wHoliday = SPFWTools::decodePluralValue($Holiday1);
sort($wHoliday);
$wFirstDateFeature = $myBukken->FirstDateFeature;
// echo "<br> ".__LINE__." MansionName :".$MansionName ;
########################################################
# 詳細工程表（イメージ）部分
########################################################

for ($i = 0; $i < count($WAKUPATTERN[$wWakuPattern]['AMPM']); $i++) {
	$WakuName = $WAKUPATTERN[$wWakuPattern]['AMPM'][$i];




	${'wWaku' . $WakuName . 'Col'} = ceil($KokonoWakuSu[$i] / $wHansu);
	${'wWaku' . $WakuName . 'ColSum'} = ${'wWaku' . $WakuName . 'Col'} * $wHansu;
	$wWakuColSum += ${'wWaku' . $WakuName . 'Col'};
}



$Koteihyou = "<table border='1px' width='600px' cellspacing='5' style='text-align:center' cellpadding='7' class='ex_table'>";
$Koteihyou .= "<tr><td class='ex_table2'>日程</td>";
$Koteihyou .= "<td class='ex_table2'>曜日</td>";
for ($i = 0; $i < count($WAKUPATTERN[$wWakuPattern]['AMPM']); $i++) {
	$WakuName = $WAKUPATTERN[$wWakuPattern]['AMPM'][$i];

	$Koteihyou .= "<td colspan = " . ${'wWaku' . $WakuName . 'Col'};
	if ($i % 2 == 0) {
		$Koteihyou .= " style='background-color:#add8e6;' ";
	} else {
		$Koteihyou .= " style='background-color:#e0ffff;' ";
	}
	$Koteihyou .= "class='ex_table2'>" . $WakuName."</td>";

	${$WakuName . "index"} = 0;
	// echo "<pre>";
	// var_dump(${'Waku' . $WakuName . 'Room'});
	// echo "</pre>";

	${"wWaku".$WakuName."col"} = ${'wWaku' . $WakuName . 'Col'} ;#大文字、小文字がちがう！
	#echo "<br> ".__LINE__." Hensu :".${"wWaku".$WakuName."col"};
	

}
$Koteihyou .= "</tr>";


$wWakuSum1 = 0;
$wWakuSum2 = 0;

$week = ['日', '月', '火', '水', '木', '金', '土'];
$date = new DateTime($SenyuStartDate);

$myListObject = new SPFWListObject($myDB);
$sql = "SELECT ";
$sql .= "r.ReservationCD , ";	#0
$sql .= "r.UserCD , ";			#1
$sql .= "r.ID , ";				#2
$sql .= "r.TimeFrom , ";		#3
$sql .= "r.TimeTo, ";			#4
$sql .= "u.LastName, ";			#5
$sql .= "u.TEL, ";				#6
$sql .= "r.Memo ";				#7

//  echo "<br> ".__LINE__." Hensu :".$sql;
$myListObject->SelectSQL = $sql;
	$sql = " FROM tReservationF r, tUserM u ";
	$sql .= " WHERE r.Status = 1 and r.MukouFlg = FALSE and r.UserCD = u.UserCD";
	$sql .= "  and r.BukkenCD = '".$editBukkenCD."'";
	// $sql .= "  and u.BukkenCD = '".$editBukkenCD."'";
//  echo "<br> ".$sql;
	$myListObject->Condition = $sql;
	$myListObject->Order = "r.TimeFrom,r.ReservationCD";

	$myListObject->Limit = "allpage";

	if (!($myListObject->GetList(1)))
		trigger_error("Getting Bukken List Failed.", E_USER_ERROR);

	$ReserveLoop = $myListObject->Rows;
	for ($i = 0; $i < $ReserveLoop; $i++) {

		$ReservationCD[$i] = $myListObject->GetValue($i, 0);
		$UserCD[$i] = $myListObject->GetValue($i, 1);
		$ID[$i] = $myListObject->GetValue($i, 2);
		$TimeFrom[$i] = $myListObject->GetValue($i, 3);
		$TimeFromDate[$i] = substr($TimeFrom[$i],0,10);#2024-08-10
		$TimeFromTime[$i] = substr($TimeFrom[$i],11,5);#10:20
		$Reserve[$TimeFromDate[$i]][$TimeFromTime[$i]][] = $ID[$i];

		#ユーザ情報$aLastName."'  ,'".$aTEL."','".$aMemo
		$UserData['LastName'][$ID[$i]] = $myListObject->GetValue($i, 5);
		$UserData['TEL'][$ID[$i]] 	= $myListObject->GetValue($i, 6);
		$UserData['Memo'][$ID[$i]] 	= $myListObject->GetValue($i, 7);

	}


// echo 	$UserData['LastName']['301'];
// print_r($UserData);




for ($i = 0; $i < $SenyuDateCnt; $i++) {
	$Syoniti[$i] = false;
	$KojiHoliday[$i] = false;
	$holiday[$i] = false;

	if ($i == 0) {
		$Syoniti[$i] = true;
	}
	
	$SenyuDate = $date->format('Y-m-d');

	$result = array_search($SenyuDate, $SHUKUJITULIST);
	$YoubiCD =  $date->format('w');
	if ($result !== false || $YoubiCD == 0 || $YoubiCD == 6) {
		$holiday[$i] = true;
	}
	if (count($wHoliday) > 0) { //休工日
		$KojiHoliday[$i] = (array_search($SenyuDate, $wHoliday) === false) ? false : true;
	}

		$week_str = $week[$date->format('w')];
		if($week_str == '土')
			$week_str = '<span style="color:#0070c0">'.$week_str.'</span>';
		else if($week_str == '日')
			$week_str = '<span style="color:#ff9999">'.$week_str.'</span>';

		// if ($holiday[$i]) { //土日祝なら
		// 	$Koteihyou .= "<tr class='trtop' id='row".$SenyuDate."' ><td rowspan=" . $wHansu . " class='ex_table2' style='background-color:pink;'>" . $SenyuDate . "</td>";
		// 	$Koteihyou .= "<td rowspan=" . $wHansu . " class='ex_table2' style='background-color:pink;'>" . $week_str . "</td>";
		// } else { //平日なら
			$Koteihyou .= "<tr class='trtop'  id='row".$SenyuDate."' ><td rowspan=" . $wHansu . " class='ex_table2'>" . $SenyuDate . "</td>";
			$Koteihyou .= "<td rowspan=" . $wHansu . " class='ex_table2'>" . $week_str . "</td>";
		// }

				
		for ($j = 0; $j < $wHansu; $j++) {
			if ($j != 0)
			$Koteihyou .= "<tr >";	
			for ($k = 0; $k < count($WAKUPATTERN[$wWakuPattern]['AMPM']); $k++) {
				$WakuName = $WAKUPATTERN[$wWakuPattern]['AMPM'][$k];
				//空きを考慮した枠数を取得
				// ${'Waku' . $WakuName . 'Su'} = getWakuRoomSu($WakuName, $Syoniti[$i], $holiday[$i], ${'wWaku' . $WakuName}, $wFirstDateFeature);
				//初日考慮
				$SyonitiKouryo = false;
				if ($wFirstDateFeature > 0 && strpos($WakuName, 'AM') !== FALSE && $Syoniti[$i]) {
					$SyonitiKouryo = true;
				}

				$WakuStart = $WAKUPATTERN[$wWakuPattern]['StartTime'][$k];
		
				for ($l = 0; $l < ${'wWaku' . $WakuName . 'Col'}; $l++) {
					$tID = "";
					$Koteihyou_temp = "";
					$Koteihyou_event = "";
					if ($k % 2 == 0) {
						$Koteihyou_temp = "<td class='link_cell' style='background-color:#ffff9e;' @event@>";
					} else {
						$Koteihyou_temp = "<td class='link_cell' style='background-color:#ffffcf;' @event@>";
					}

					$addedTime = $MinuteTime * $l ;
					$SenyuDateTime = date('H:i', strtotime("+$addedTime minutes", strtotime($WakuStart)));
					$tID = $Reserve[$SenyuDate][$SenyuDateTime][$j];#部屋ID
					if($tID){
						$aLastName = $UserData['LastName'][$tID] ;
						$aTEL = $UserData['TEL'][$tID] ;
						$aMemo = $UserData['Memo'][$tID] ;
					}

					if ($KojiHoliday[$i]) { //休工日以外
						if ($k % 2 == 0) {
							$Koteihyou_temp = "<td style='background-color:#ffff9e;'>";
						} else {
							$Koteihyou_temp = "<td style='background-color:#ffffcf;'>";
						}
						$Koteihyou_event = "";
					}else if($SyonitiKouryo){
						if ($k % 2 == 0) {
							$Koteihyou_temp = "<td style='background-color:#ffff9e;'>";
						} else {
							$Koteihyou_temp = "<td style='background-color:#ffffcf;'>";
						}
						$Koteihyou_event = "";
					}else if($tID){
						$Koteihyou_temp .= "<font size='5'> <b>" ;
						$Koteihyou_temp .= $tID."</b></font>";
						$Koteihyou_event = "onclick=\"clickBtn8('".$tID."' ,'".$aLastName."'  ,'".$aTEL."','".$aMemo."' , '".$SenyuDate."', '".$SenyuDateTime."', '".$WakuName."' )\"";
					}else if((($l+1)*($j+1)) <= $KokonoWakuSu[$k]){
						$Koteihyou_temp .= "空き";
						$Koteihyou_event = "onclick=\"clickBtn7('".$SenyuDate."', '".$SenyuDateTime."', '".$WakuName."')\"";
					}else{
						if ($k % 2 == 0) {
							$Koteihyou_temp = "<td style='background-color:#ffff9e;'>";
						} else {
							$Koteihyou_temp = "<td style='background-color:#ffffcf;'>";
						}
						$Koteihyou_event = "";
						$Koteihyou_event = "";
					}
					#$KoteihyouEX[] = "${'Waku' . $WakuName . 'Room'}[${$WakuName . "index"}]";

					// echo "<br>" . $WakuName . ":" . ${$WakuName . "index"};
					$Koteihyou_temp = str_replace("@event@", $Koteihyou_event, $Koteihyou_temp);
					$Koteihyou .= $Koteihyou_temp;
					$Koteihyou .= "</td>";
					${$WakuName . "index"}++;

	
				}
			}
			$Koteihyou .= "</tr>";
		}

	$date->modify('+1 days');
}
$Koteihyou .= "</table>";







#######################################移植終わり

// #調査ミサイル
// $fh = fopen("aaa.txt", "a");
// fwrite($fh,"\n work:".__FILE__." ".$work );
// fclose($fh);
// #調査ミサイルEnd
	if($work){#追加・修正
		$aHenkoDate = SPFWParameter::getValues('HenkoDate');#wHenkoDateにしたら下でダブっておかしくなる
		$aTimeFromTime = SPFWParameter::getValues('TimeFromTime');
		#$WakuNo = SPFWParameter::getValues('WakuNo');# value red
		$aRoomNo = SPFWParameter::getValues('RoomNo');
		$aLastName = SPFWParameter::getValues('Name');
		$aTEL = SPFWParameter::getValues('TEL');
		$aMemo = SPFWParameter::getValues('Memo');
// #調査ミサイル
// $fh = fopen("aaa.txt", "a");
// fwrite($fh,"\n aLastName:".$aLastName );
// fclose($fh);
// #調査ミサイルEnd
		###登録処理
		if($aHenkoDate != 'red'){#Javascriptに　HiddenHenkoDate の　ElementByID部分の変更前の値

			$myReservation = new Reservation($myDB);
			if (!$myReservation->executeSelect("  BukkenCD = '".$editBukkenCD."' AND ID = $aRoomNo AND MukouFlg = FALSE", "")) 
				trigger_error("Getting Reservation Failed.", E_USER_ERROR);
			$myReservation->Updater = $UserCD;

			$myReservation->TimeFrom = $aHenkoDate." ".$aTimeFromTime;

			// $MinuteTime（20分）後の時刻を計算
			$TimeToTime = date('H:i', strtotime('+'.$MinuteTime.' minutes', strtotime($TimeFromTime)));


			$myReservation->TimeTo = $aHenkoDate." ".$TimeToTime;
			$myReservation->Memo = $aMemo;
			$myReservation->Updater = $myUserCD;

			if (!$myReservation->executeUpdate())
				trigger_error("Updating myReservation Failed.", E_USER_ERROR);

			$myUser = new User($myDB);
			if (!$myUser->executeSelect("  BukkenCD = '".$editBukkenCD."' AND ID = '".$aRoomNo."' AND MukouFlg = FALSE", "")) 
				trigger_error("Getting Reservation Failed.", E_USER_ERROR);
			$myUser->Updater = $myUserCD;

			$myUser->LastName = $aLastName;
			$myUser->TEL 	=  $aTEL;
			$myUser->ReplyFlg 	= "2";#TEL受付
			if (!$myUser->executeUpdate())
				trigger_error("Updating myUser Failed.", E_USER_ERROR);
	// 		####登録処理End
	// 		####変数ドロップしておく。
			SPFWTemplate::dropValue('work');

				
	// // 		####登録処理End
	// // 		####変数ドロップしておく。
			SPFWTemplate::dropValue('work');
			// リダイレクトによるページリロード
			header("Location: " . $_SERVER['PHP_SELF'] . "?ClientCD=" . urlencode($ClientCD)."&editBukkenCD=" . urlencode($editBukkenCD)."&rKey=" . urlencode($rKey));
			exit;

		}
	}



	########################################################
	# コンテンツ表示
	########################################################

	$CNT_FILE = "sh_list.tpl";
	$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier);
	$HiddenValues = $myTemplate->getValuesToPass();
	$myTemplate->convertTags();
	$myTemplate->outputTemplate();
	unset($myTemplate);
	unset($myLog);

########################################################
# 関数群
########################################################
//最大工事枠数から空きの数を引いた枠数を取得
function getWakuRoomSu($WakuName, $Syoniti, $holiday, $MaxWakuSu, $FirstDateFeature)
{
	// 第一引数：$WakuName string
	//  AM・PMなど

	// 第二引数： $Syoniti boolean
	//  初日かどうか

	// 第三引数：$holiday boolean
	//  土日祝かどうか

	// 第四引数：$MaxWakuSu int
	//  枠ごとの最大工事枠数

	// 第五引数：$FirstDateFeature int
	//  初日考慮
	//  1：午前中NG
	//  2：15時までNG


	//最大工事枠数から空きの数を引く計算をする
	if ($Syoniti == true && $holiday) { //初日・土日祝　(最大工事枠数/2)-1

		$WakuRoomSu = ceil($MaxWakuSu / 2) - 1;#切り上げて1引く。　

		if ($FirstDateFeature == 1 && strpos($WakuName, 'AM') !== FALSE) { //初日午前NG
			$WakuRoomSu = 0;
		} elseif ($FirstDateFeature == 2) { //初日15：00以降OK

		}
	} elseif ($Syoniti == true) { //初日・平日　最大工事枠数-2

		$WakuRoomSu = $MaxWakuSu - 2;

		if ($FirstDateFeature == 1 && strpos($WakuName, 'AM') !== FALSE) { //初日午前NG
			$WakuRoomSu = 0;
		} elseif ($FirstDateFeature == 2) {
		}
	} elseif ($holiday) { //土日祝 最大工事枠数/2
		$WakuRoomSu = ceil($MaxWakuSu / 2);
	} else { //平日 最大工事枠数-1
		$WakuRoomSu = $MaxWakuSu - 1;
	}
	if ($WakuRoomSu < 0) {
		$WakuRoomSu = 0;
	}
	return $WakuRoomSu;
}


?>
