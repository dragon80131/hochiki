<?php
// phpinfo();
// exit;
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
include_once _CLS_DIR . "SPUSBukken.cls";
include_once _CLS_DIR . "SPUSShiryo.cls";
include_once _CLS_DIR . "SPUSIraiRenkei.cls";
include_once _CLS_DIR . "SPUSBukkenMatrix.cls";
include_once _CLS_DIR . "SPUSKoji.cls";


// データベースコネクト
$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
if (!$myDB->Connection)
	trigger_error("SPFWDatabase Failed.", E_USER_ERROR);


########################################################
# 認証動作
########################################################
$rKey = SPFWParameter::getValues('rKey');

$myUser = new User($myDB);

if ($rKey == NULL)
	showSorryPage(_ILLEGAL_ACCESS2);

if (!$myUser->doAuthenticationByRegistKey($rKey))
	trigger_error("doAuthentication Failed.", E_USER_ERROR);

if ($myUser->UserCD == -1)
	showSorryPage(_ILLEGAL_ACCESS2);

$UserCD = $myUser->UserCD;
$ID = $myUser->ID;
unset($myUser);

########################################################
# 設定パラメータ取得取得
########################################################

foreach ($_POST as $key => $value) {
	${"$key"} = SPFWParameter::getValues($key);
}

$ColsBlock = SPFWTools::decodePluralValue($wColsBlock);
$KaiRoom3 = SPFWTools::decodePluralValue($wKaiRoom3);

$myKoji = new Koji($myDB);

if (!$myKoji->executeSelect("BukkenCD = " . $editBukkenCD, "")) {
	$ErrorString = array();
	$ErrorString[] = "tKojiF情報の抽出に失敗しました。";
	showAdminSorryPage($ErrorString);
}
$KyoyoStartDate = $myKoji->KyoyoStartDate;
$KyoyoEndDate = $myKoji->KyoyoEndDate;
$SenyuStartDate = $myKoji->SenyuStartDate;
$SenyuEndDate = $myKoji->SenyuEndDate;
$SenyuDateCnt = ((strtotime($SenyuEndDate) -  strtotime($SenyuStartDate)) / 86400) + 1; #専有部日数
$wHansu = $myKoji->Hansu;
$wWakuPattern = $myKoji->WakuPattern;
$wFirstDateFeature = $myKoji->FirstDateFeature;
$wMaxWakuSu = $myKoji->MaxWakuSu;

$MaxWakuSus = explode("-", $wMaxWakuSu);
$Holiday1 = $myKoji->Holiday1;
$wHoliday = SPFWTools::decodePluralValue($Holiday1);
sort($wHoliday);
########################################################
# 詳細工程表イメージ作成
########################################################

for ($i = 0; $i < count($WAKUPATTERN[$wWakuPattern]['AMPM']); $i++) {
	$WakuName = $WAKUPATTERN[$wWakuPattern]['AMPM'][$i];
	${'wWaku' . $WakuName} = $MaxWakuSus[$i];
	${'wWaku' . $WakuName . 'Col'} = ceil(${'wWaku' . $WakuName} / $wHansu);
	${'wWaku' . $WakuName . 'ColSum'} = ${'wWaku' . $WakuName . 'Col'} * $wHansu;
	$wWakuColSum += ${'wWaku' . $WakuName . 'Col'};
	${'Waku' . $WakuName . 'NoRoomIndex'} = -1;
}

if (isset($before_wakusu_json)) {
	$before_wakusu = json_decode($before_wakusu_json, true);
}
$date = new DateTime($SenyuStartDate);
$x = 0;
$IfLast = false;
$IfLastWaku = false;

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

		if (isset($before_wakusu_json)) {
			${'Waku' . $WakuName . 'Su'} = $before_wakusu[$SenyuDate][$WakuName];
		} else {
			${'Waku' . $WakuName . 'Su'} = getWakuRoomSu($WakuName, $Syoniti[$i], $holiday[$i], ${'wWaku' . $WakuName}, $wFirstDateFeature);
		}
		//初日考慮
		$SyonitiKouryo = false;
		if ($wFirstDateFeature == 1 && strpos($WakuName, 'AM') !== FALSE && $Syoniti[$i]) {
			$SyonitiKouryo = true;
		}else if($wFirstDateFeature == 2 && (strpos($WakuName, 'AM') !== FALSE || strpos($WakuName, 'PM1') !== FALSE) && $Syoniti[$i]){
			$SyonitiKouryo = true;
		}
		if (!isset($KaiRoom3[$x])) {
			${'Waku' . $WakuName . 'Su'} = 0;
		}

		for ($k = 0; $k < ${'wWaku' . $WakuName . 'ColSum'}; $k++) {

			if (!$KojiHoliday[$i]) { //休工日以外
				if (isset($KaiRoom3[$x])) {
					${'Waku' . $WakuName . 'NoRoomIndex'}++;
				}
				if ($SyonitiKouryo) { //初日考慮でAMなら　空を入れる
					${'Waku' . $WakuName . 'Room'}[] = "";
				} elseif (${'Waku' . $WakuName . 'Su'} > $k && isset($KaiRoom3[$x])) { //空きを考慮した枠数分　部屋を入れる
					${'Waku' . $WakuName . 'Room'}[] = $KaiRoom3[$x];
					$x++;
					if (!isset($KaiRoom3[$x])) {
						${'Waku' . $WakuName . 'Su'} = ($k + 1);
					}
				} elseif (${'wWaku' . $WakuName} > $k) { //残った最大工事枠数分は空き
					${'Waku' . $WakuName . 'Room'}[] = "空き";
				} else {
					${'Waku' . $WakuName . 'Room'}[] = "";
				}
			}
		}
		$BeforWakuSu[$SenyuDate][$WakuName] = ${'Waku' . $WakuName . 'Su'};
	}
	$date->modify('+1 days');
}
$before_wakusu_json = json_encode($BeforWakuSu);
$Koteihyou = "<table border='3' width='600px' cellspacing='5' style='text-align:center' cellpadding='7' class='ex_table'>";
$Koteihyou .= "<tr><td class='ex_table2'>日程</td>";
$Koteihyou .= "<td class='ex_table2'>曜日</td>";
for ($i = 0; $i < count($WAKUPATTERN[$wWakuPattern]['AMPM']); $i++) {
	$WakuName = $WAKUPATTERN[$wWakuPattern]['AMPM'][$i];
	$Koteihyou .= "<td colspan = " . ${'wWaku' . $WakuName . 'Col'};
	if ($i % 2 == 0) {
		$Koteihyou .= " style='background-color:#ffff9e;' ";
	} else {
		$Koteihyou .= " style='background-color:#ffffcf;' ";
	}
	$Koteihyou .= "class='ex_table2'>" . $WakuName . "</td>";
	${$WakuName . "index"} = 0;
}
$Koteihyou .= "</tr>";


$date = new DateTime($SenyuStartDate);
$index = 0;
$week = ['日', '月', '火', '水', '木', '金', '土'];
for ($i = 0; $i < $SenyuDateCnt; $i++) {
	$SenyuDate = $date->format('Y-m-d');

	#★１休工日なら
	if ($KojiHoliday[$i]) { #★１休工日なら
		if ($holiday[$i]) { //土日祝なら
			$Koteihyou .= "<tr class='trtop'><td class='ex_table2' style='background-color:pink;'>" . $SenyuDate . "</td>";
			$Koteihyou .= "<td  class='ex_table2' style='background-color:pink;'>" . $week[$date->format('w')] . "</td>";
		} else { //平日なら
			$Koteihyou .= "<tr class='trtop'><td  class='ex_table2'>" . $SenyuDate . "</td>";
			$Koteihyou .= "<td class='ex_table2'>" . $week[$date->format('w')] . "</td>";
		}
		$Koteihyou .= "<td  colspan=" . $wWakuColSum . " class='ex_table2'>";
		$Koteihyou .= "休工日</td>";
	} else { #★１休工日でない場合
		if ($holiday[$i]) { //土日祝なら
			$Koteihyou .= "<tr class='trtop'><td rowspan=" . $wHansu . " class='ex_table2' style='background-color:pink;'>" . $SenyuDate . "<div id=".$SenyuDate."> </div></td>";
			$Koteihyou .= "<td rowspan=" . $wHansu . " class='ex_table2' style='background-color:pink;'>" . $week[$date->format('w')] . "</td>";
		} else { //平日なら
			$Koteihyou .= "<tr class='trtop'><td rowspan=" . $wHansu . " class='ex_table2'>" . $SenyuDate . "<div id=".$SenyuDate."> </div></td>";
			$Koteihyou .= "<td rowspan=" . $wHansu . " class='ex_table2'>" . $week[$date->format('w')] . "</td>";
		}
		for ($j = 0; $j < $wHansu; $j++) {
			if ($j != 0)
				$Koteihyou .= "<tr>";
			for ($k = 0; $k < count($WAKUPATTERN[$wWakuPattern]['AMPM']); $k++) {
				$WakuName = $WAKUPATTERN[$wWakuPattern]['AMPM'][$k];
				for ($l = 0; $l < ${'wWaku' . $WakuName . 'Col'}; $l++) {
					if ($k % 2 == 0) {
						$Koteihyou .= "<td style='background-color:#ffff9e;'>";
					} else {
						$Koteihyou .= "<td style='background-color:#ffffcf;'>";
					}
					if (${'Waku' . $WakuName . 'Room'}[${$WakuName . "index"}] == "空き") {
						if (${$WakuName . "index"} > ${'Waku' . $WakuName . 'NoRoomIndex'}) {
							$Koteihyou .= ${'Waku' . $WakuName . 'Room'}[${$WakuName . "index"}];
						} else {
							$Koteihyou .= "<a href='javascript:void(0)' class='aki' data-date='" . $SenyuDate . "' data-ampm='" . $WakuName . "'>" . ${'Waku' . $WakuName . 'Room'}[${$WakuName . "index"}] . "</a>";
						}
					} else {
						$Koteihyou .= "<a href='javascript:void(0)' class='room' data-date='" . $SenyuDate . "' data-ampm='" . $WakuName . "'>";
						$Koteihyou .= ${'Waku' . $WakuName . 'Room'}[${$WakuName . "index"}];
						$Koteihyou .= "</a>";
					}
					$Koteihyou .= "<input type='hidden' name='wwKoteihyouEX[]' value=" . ${'Waku' . $WakuName . 'Room'}[${$WakuName . "index"}] . " >";
					$KoteihyouEX[] = ${'Waku' . $WakuName . 'Room'}[${$WakuName . "index"}];
					$Koteihyou .= "</td>";
					${$WakuName . "index"}++;
				}
			}
			$Koteihyou .= "</tr>";
		}
	}

	$date->modify('+1 days');
}
$Koteihyou .= "</table>";


for ($i = 0; $i < count($KaiRoom3); $i++) {
	if (array_search($KaiRoom3[$i], $KoteihyouEX) === false) {
		if ($ShortageRoom == "") {
			$ShortageRoom = "　" . $KaiRoom3[$i];
		} else {
			$ShortageRoom .= "、" . $KaiRoom3[$i];
		}
	}
}
if (!$ShortageRoom) {
	$IfShortage = FALSE;
	$IfNotShortage = TRUE;
	$SakuseiDisabled = "";
} else {
	$IfShortage = TRUE;
	$IfNotShortage = FALSE;
	$SakuseiDisabled = 'disabled';
}
$wShukujitucolor = SPFWTools::encodePluralValue($wShukujitucolor);
$wKaiRoom3 = SPFWTools::encodePluralValue($KaiRoom3);
$wKyukobi = SPFWTools::encodePluralValue($wKyukobi);

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

		$WakuRoomSu = ceil($MaxWakuSu / 2) - 1;

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
########################################################
# コンテンツ表示
########################################################

$CNT_FILE = "s_make_kanryo_hensyu2.tpl";

$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier);
$HiddenValues = $myTemplate->getValuesToPass();

$myTemplate->convertTags();
$myTemplate->outputTemplate();

unset($myTemplate);
unset($myLog);
