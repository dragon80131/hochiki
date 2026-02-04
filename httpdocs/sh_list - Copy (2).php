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
$MaxWaku = explode("-",$MaxWakuSu);
$wWakuAM = $MaxWaku[0];
$wWakuPM = $MaxWaku[1];
$wWakuPM1 = $MaxWaku[1];
if(count($MaxWaku)>2){
	$wWakuPM2 = $MaxWaku[2];
}
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

$MaxWaku = explode("-",$MaxWakuSu);
$wWakuAM = $MaxWaku[0];
$wWakuPM = $MaxWaku[1];
$wWakuPM1 = $MaxWaku[1];
if(count($MaxWaku)>2){
	$wWakuPM2 = $MaxWaku[2];
}

if(!$wWakuPattern){
	echo ('<script>
alert("作業日程登録がまだ終わっていないようです。\r\nブラウザで戻り、作業日程登録の各項目の入力をお願いします。");
</script>');
}


########################################################
# 詳細工程表表示
########################################################

#Koteihyoに、空きか部屋番号をいれていく。
########################################################
# 日程情報取得
########################################################
// SELECT 
//    DATE(TimeFrom) AS Date,
//     CASE 
//         WHEN TIME(TimeFrom) BETWEEN '09:00:00' AND '12:00:00' THEN 'AM'
//         WHEN TIME(TimeFrom) BETWEEN '13:00:00' AND '15:00:00' THEN 'PM1'
//         WHEN TIME(TimeFrom) BETWEEN '15:00:01' AND '18:00:00' THEN 'PM2'
//         ELSE 'Other'
//     END AS TimePeriod
// FROM tReservationF
// where BukkenCD = 217

$myListObject = new SPFWListObject($myDB);
$sql  = "SELECT ";
$sql .= "r.ReservationCD, "; #0
$sql .= "DATE(r.TimeFrom) AS Date, "; #1
if($wWakuPattern == '0' || $wWakuPattern == '1' || $wWakuPattern == '2'){ // 2枠
	$sql .= "CASE ";
	$sql .= " WHEN TIME(r.TimeFrom) BETWEEN '09:00:00' AND '12:00:00' THEN 'AM'";
	$sql .= " WHEN TIME(r.TimeFrom) BETWEEN '13:00:00' AND '18:00:00' THEN 'PM'";
	$sql .= " ELSE 'Other'";
	$sql .= " END AS AMPM ,"; #2
}else{ // 3枠
	$sql .= "CASE ";
	$sql .= " WHEN TIME(r.TimeFrom) BETWEEN '09:00:00' AND '12:00:00' THEN 'AM'";
	$sql .= " WHEN TIME(r.TimeFrom) BETWEEN '13:00:00' AND '14:59:00' THEN 'PM1'";
	$sql .= " WHEN TIME(r.TimeFrom) BETWEEN '15:00:00' AND '18:00:00' THEN 'PM2'";
	$sql .= " ELSE 'Other'";
	$sql .= " END AS AMPM ,"; #2
}
$sql .= "r.ID, "; #3
$sql .= "r.UserCD, "; #4
$sql .= "r.TimeFrom, "; #5
$sql .= "r.TimeTo, "; #6
$sql .= "u.LastName, "; #7
$sql .= "u.TEL, "; #8
$sql .= "r.Memo "; #9

$myListObject->SelectSQL = $sql;
$sql  = " FROM tReservationF r, tUserM u ";
$sql .= " WHERE r.Status = 1 AND r.MukouFlg = FALSE AND r.UserCD = u.UserCD";
$sql .= " AND r.BukkenCD = " . $editBukkenCD;
// $sql .= " AND ClientCD = " . $wClientCD;

$myListObject->Condition = $sql;
$myListObject->Order = "r.TimeFrom,r.ReservationCD ";
$myListObject->Limit = "allpage";

if (!($myListObject->GetList(1)))
	trigger_error("Getting Reservation List Failed.", E_USER_ERROR);

$ReservationLoop = $myListObject->Rows;
for ($i = 0; $i < $ReservationLoop; $i++) {
	$ReservationCD[$i] = $myListObject->GetValue($i, 0);
	$tDate = $myListObject->GetValue($i, 1);
	$AMPM = $myListObject->GetValue($i, 2);
	$ID[$i] = $myListObject->GetValue($i, 3);
	$Reserve[$tDate][$AMPM][] = $ID[$i];

	$UserCD[$i] = $myListObject->GetValue($i, 4);

	$UserData['LastName'][$ID[$i]] = $myListObject->GetValue($i, 7);
	$UserData['TEL'][$ID[$i]] 	= $myListObject->GetValue($i, 8);
	$UserData['Memo'][$ID[$i]] 	= $myListObject->GetValue($i, 9);

}

######################################################################################





for ($i = 0; $i < count($WAKUPATTERN[$wWakuPattern]['AMPM']); $i++) {
	$WakuName = $WAKUPATTERN[$wWakuPattern]['AMPM'][$i];
	${'wWaku' . $WakuName . 'Col'} = ceil(${'wWaku' . $WakuName} / $wHansu);
	${'wWaku' . $WakuName . 'ColSum'} = ${'wWaku' . $WakuName . 'Col'} * $wHansu;
	$wWakuColSum += ${'wWaku' . $WakuName . 'Col'};

	${'wWaku' . $WakuName . 'col'} = ceil(${'wWaku' . $WakuName} / $wHansu);
	if($WakuName == 'PM')$wWakuPM1col = ${'wWaku' . $WakuName . 'Col'} ;
}

########################################################
# 初日・土日祝考慮
########################################################

$wWakuSum1 = 0;
$wWakuSum2 = 0;

$week = ['日', '月', '火', '水', '木', '金', '土'];

$date = new DateTime($SenyuStartDate);
$x = 0;
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
	for ($j = 0; $j < count($WAKUPATTERN[$wWakuPattern]['AMPM']); $j++) {
		$WakuName = $WAKUPATTERN[$wWakuPattern]['AMPM'][$j];
		//空きを考慮した枠数を取得
		// ${'Waku' . $WakuName . 'Su'} = getWakuRoomSu($WakuName, $Syoniti[$i], $holiday[$i], ${'wWaku' . $WakuName}, $wFirstDateFeature);
		//初日考慮
		$SyonitiKouryo = false;
		if ($wFirstDateFeature == 1 && strpos($WakuName, 'AM') !== FALSE && $Syoniti[$i]) {
			$SyonitiKouryo = true;
		}else if($wFirstDateFeature == 2 && (strpos($WakuName, 'AM') !== FALSE || strpos($WakuName, 'PM1') !== FALSE) && $Syoniti[$i]){
			$SyonitiKouryo = true;
		}
#★★★ここから
		for ($k = 0; $k < ${'wWaku' . $WakuName . 'ColSum'}; $k++) {#10,8,8
			if (!$KojiHoliday[$i]) { //休工日以外
				if ($SyonitiKouryo) { //初日考慮でAMなら　空を入れる
					${'Waku' . $WakuName . 'Room'}[] = "";
				}elseif(isset($Reserve[$SenyuDate][$WakuName][$k])){
					${'Waku' . $WakuName . 'Room'}[] = $Reserve[$SenyuDate][$WakuName][$k];
				}elseif (${'wWaku' . $WakuName} > $k) { //残った最大工事枠数分は空き
					${'Waku' . $WakuName . 'Room'}[] = "空き";
				}else{
					${'Waku' . $WakuName . 'Room'}[] = "";
				}	
			}
			// if (!$KojiHoliday[$i]) { //休工日以外
				// if ($SyonitiKouryo) { //初日考慮でAMなら　空を入れる
				// 	${'Waku' . $WakuName . 'Room'}[] = "";
				// } elseif (${'Waku' . $WakuName . 'Su'} > $k && isset($KaiRoom3[$x])) { //空きを考慮した枠数分　部屋を入れる
				// 	${'Waku' . $WakuName . 'Room'}[] = $KaiRoom3[$x];
				// 	$x++;
				// } elseif (${'wWaku' . $WakuName} > $k) { //残った最大工事枠数分は空き
				// 	${'Waku' . $WakuName . 'Room'}[] = "空き";
				// } else {
				// 	${'Waku' . $WakuName . 'Room'}[] = "";
				// }
			// }
		}
	}
	$date->modify('+1 days');
}


########################################################
# 詳細工程表（イメージ）部分
########################################################

$Koteihyou = "<table border='1' width='600px' cellspacing='5' style='text-align:center' cellpadding='7' class='ex_table'>";
$Koteihyou .= "<tr><td class='ex_table2' width='130px'>日程</td>";
$Koteihyou .= "<td class='ex_table2' width='60px'>曜日</td>";
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
$holiday = array();
$date = new DateTime($SenyuStartDate);

for ($i = 0; $i < $SenyuDateCnt; $i++) {
	$SenyuDate = $date->format('Y-m-d');
	$week_str = $week[$date->format('w')];
	if($week_str == '土')
		$week_str = '<span style="color:#0070c0">'.$week_str.'</span>';
	else if($week_str == '日')
		$week_str = '<span style="color:#ff9999">'.$week_str.'</span>';

	if ($KojiHoliday[$i]) { #★１休工日なら
		if ($holiday[$i]) { //土日祝なら
			$Koteihyou .= "<tr class='trtop trholiday' id='row".$SenyuDate."'><td class='ex_table2' style='background-color:pink;'>" . $SenyuDate . "</td>";
			$Koteihyou .= "<td  class='ex_table2' style='background-color:pink;'>" . $week_str . "</td>";
		} else { //平日なら
			$Koteihyou .= "<tr class='trtop trholiday' id='row".$SenyuDate."'><td  class='ex_table2'>" . $SenyuDate . "</td>";
			$Koteihyou .= "<td class='ex_table2'>" . $week_str . "</td>";
		}
		$Koteihyou .= "<td colspan=" . $wWakuColSum . " class='ex_table2'>";
		$Koteihyou .= "休工日</td>";
	} else { #★１休工日でない場合
		if ($holiday[$i]) { //土日祝なら
			$Koteihyou .= "<tr class='trtop' id='row".$SenyuDate."'><td rowspan=" . $wHansu . " class='ex_table2' style='background-color:pink;'>" . $SenyuDate . "</td>";
			$Koteihyou .= "<td rowspan=" . $wHansu . " class='ex_table2' style='background-color:pink;'>" . $week_str . "</td>";
		} else { //平日なら
			$Koteihyou .= "<tr class='trtop' id='row".$SenyuDate."'><td rowspan=" . $wHansu . " class='ex_table2'>" . $SenyuDate . "</td>";
			$Koteihyou .= "<td rowspan=" . $wHansu . " class='ex_table2'>" . $week_str . "</td>";
		}
		for ($j = 0; $j < $wHansu; $j++) {
			if ($j != 0)
				$Koteihyou .= "<tr>";
			for ($k = 0; $k < count($WAKUPATTERN[$wWakuPattern]['AMPM']); $k++) {
				$WakuName = $WAKUPATTERN[$wWakuPattern]['AMPM'][$k];
				$WakuStart = $WAKUPATTERN[$wWakuPattern]['StartTime'][$k];
				for ($l = 0; $l < ${'wWaku' . $WakuName . 'Col'}; $l++) {
					$Koteihyou_event = "";

					if ($k % 2 == 0) {
						$Koteihyou_temp = "<td class='link_cell' style='background-color:#ffff9e;' @event@>";
					} else {
						$Koteihyou_temp = "<td class='link_cell' style='background-color:#ffffcf;' @event@>";
					}

					$addedTime = $MinuteTime * $l ;
					$SenyuDateTime = date('H:i', strtotime("+$addedTime minutes", strtotime($WakuStart)));

					if (${'Waku' . $WakuName . 'Room'}[${$WakuName . "index"}] == "空き") {
						$Koteihyou_temp .= ${'Waku' . $WakuName . 'Room'}[${$WakuName . "index"}];
						$Koteihyou_event = "onclick=\"clickBtn7('".$SenyuDate."', '".$SenyuDateTime."', '".$WakuName."')\"";

					} else if(${'Waku' . $WakuName . 'Room'}[${$WakuName . "index"}]) {
						$tID = ${'Waku' . $WakuName . 'Room'}[${$WakuName . "index"}];
						$aLastName = $UserData['LastName'][$tID] ;
						$aTEL = $UserData['TEL'][$tID] ;
						$aMemo = $UserData['Memo'][$tID] ;
						$Koteihyou_temp .= '<font size="4"> <b>' . ${'Waku' . $WakuName . 'Room'}[${$WakuName . "index"}] . '</b></font>';
						$Koteihyou_event = "onclick=\"clickBtn8('".$tID."' ,'".$aLastName."'  ,'".$aTEL."','".$aMemo."' , '".$SenyuDate."', '".$SenyuDateTime."', '".$WakuName."' )\"";
					}
					// $KoteihyouEX[] = ${'Waku' . $WakuName . 'Room'}[${$WakuName . "index"}];

					// echo "<br>" . $WakuName . ":" . ${$WakuName . "index"};
					$Koteihyou_temp = str_replace("@event@", $Koteihyou_event, $Koteihyou_temp);
					$Koteihyou .= $Koteihyou_temp;

					$Koteihyou .= "</td>";
					${$WakuName . "index"}++;
				}
			}
			$Koteihyou .= "</tr>";
		}
	}#休工日

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
		} elseif ($FirstDateFeature == 2 && (strpos($WakuName, 'AM') !== FALSE || strpos($WakuName, 'PM1') !== FALSE)) { //初日15：00以降OK
			$WakuRoomSu = 0;
		}
	} elseif ($Syoniti == true) { //初日・平日　最大工事枠数-2

		$WakuRoomSu = $MaxWakuSu - 2;

		if ($FirstDateFeature == 1 && strpos($WakuName, 'AM') !== FALSE) { //初日午前NG
			$WakuRoomSu = 0;
		} elseif ($FirstDateFeature == 2 && (strpos($WakuName, 'AM') !== FALSE || strpos($WakuName, 'PM1') !== FALSE)) { //初日15：00以降OK
			$WakuRoomSu = 0;
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
