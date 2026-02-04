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
$rKey 	= SPFWParameter::getValues('rKey');
$myUser = new User($myDB);

if ($rKey == NULL)
	showSorryPage(_ILLEGAL_ACCESS);

if (!$myUser->doAuthenticationByRegistKey($rKey))
	trigger_error("doAuthentication Failed.", E_USER_ERROR);

if ($myUser->UserCD == -1)
	showSorryPage(_ILLEGAL_ACCESS);

$UserCD = $myUser->UserCD;
$ID 	= $myUser->ID;
unset($myUser);

########################################################
# 設定パラメータ取得取得
########################################################

foreach ($_POST as $key => $value) {
	#echo "<br>key:".$key;
	${"$key"} = SPFWParameter::getValues($key);
}
//echo "b".$wWakuPM1."<br>";

$ColsBlock 			= SPFWTools::decodePluralValue($wColsBlock);
$RowsLoop 			= SPFWParameter::getValues('RowsLoop');

$wKojijun 			= SPFWParameter::getValues('wKojijun');		# 工事順
$wFirstDateFeature 	= SPFWParameter::getValues('wFirstDateFeature'); #初日工事数考慮
$wHoliday1 			= SPFWParameter::getValues('wHoliday1');		#休日
$wHoliday2 			= SPFWParameter::getValues('wHoliday2');
$wHoliday3 			= SPFWParameter::getValues('wHoliday3');
$wHoliday4 			= SPFWParameter::getValues('wHoliday4');
$editBukkenCD 		= SPFWParameter::getValues('editBukkenCD');

$wWakuAMcol 		= SPFWParameter::getValues('wWakuAMcol');
$wWakuAM2col 		= SPFWParameter::getValues('wWakuAM2col');
$wWakuAM3col 		= SPFWParameter::getValues('wWakuAM3col');
$wWakuAM4col 		= SPFWParameter::getValues('wWakuAM4col');
$wWakuPM1col 		= SPFWParameter::getValues('wWakuPM1col');
$wWakuPM2col 		= SPFWParameter::getValues('wWakuPM2col');
$wWakuPM3col 		= SPFWParameter::getValues('wWakuPM3col');
$wWakuPM4col 		= SPFWParameter::getValues('wWakuPM4col');
$wWakuPM5col 		= SPFWParameter::getValues('wWakuPM5col');
$wWakuPM6col 		= SPFWParameter::getValues('wWakuPM6col');

$wKoteihyouEX 		= SPFWParameter::getValues('wKoteihyouEX');
$holiday 			= SPFWParameter::getValues('holiday');		#休日
$wShukujitucolor	= SPFWParameter::getValues('wShukujitucolor');		#祝日色
$wKyukobi 			= SPFWParameter::getValues('wKyukobi');		#休工日
$work 				= SPFWParameter::getValues('work');		#休工日
$wKaiRoom3 			= SPFWParameter::getValues('wKaiRoom3');		#休工日
$wKoteihyouEX 		= SPFWTools::decodePluralValue($wKoteihyouEX); #配列
$wShukujitucolor 	= SPFWTools::decodePluralValue($wShukujitucolor);
$wKyukobi 			= SPFWTools::decodePluralValue($wKyukobi);
$wKaiRoom3 			= SPFWTools::decodePluralValue($wKaiRoom3); //部屋番号

$myKoji = new Koji($myDB);

if (!$myKoji->executeSelect("BukkenCD = " . $editBukkenCD, "")) {
	$ErrorString 	= array();
	$ErrorString[] 	= "tKojiF情報の抽出に失敗しました。";
	showAdminSorryPage($ErrorString);
}
$KyoyoStartDate = $myKoji->KyoyoStartDate;
$KyoyoEndDate 	= $myKoji->KyoyoEndDate;
$SenyuStartDate	= $myKoji->SenyuStartDate;
$SenyuEndDate 	= $myKoji->SenyuEndDate;
$SenyuDateCnt 	= ((strtotime($SenyuEndDate) -  strtotime($SenyuStartDate)) / 86400) + 1; #専有部日数



########################################################
# 空き削除処理
########################################################
if ($work == 2) {
	$wKoteihyouEX	= SPFWParameter::getValues('wwKoteihyouEX'); #配列
	$Aki 			= SPFWParameter::getValues('Aki');		#
	$wWakuAMcol 	= ceil(($wWakuAM) / $wHansu); #入力枠数を班数でわった切り上げた数
	$WakuAMCEL 		= $wWakuAMcol * $wHansu; #一日のAMのセル数
	if ($wWakuPattern > 2 and $wWakuPattern <= 7) { //3枠
		$wWakuPM1col 	= ceil(($wWakuPM1) / $wHansu);
		$wWakuPM2col 	= ceil(($wWakuPM2) / $wHansu);
		$WakuPM1CEL 	= $wWakuPM1col * $wHansu; #一日のPM1のセル数
		$WakuPM2CEL 	= $wWakuPM2col * $wHansu; #一日のPM2のセル数
		$wWakuSum 		= $wWakuAMcol + $wWakuPM1col + $wWakuPM2col;
	} elseif ($wWakuPattern == 8) { //5枠
		$wWakuAM2col 	= ceil(($wWakuAM2) / $wHansu);
		$wWakuPM1col 	= ceil(($wWakuPM1) / $wHansu);
		$wWakuPM2col 	= ceil(($wWakuPM2) / $wHansu);
		$wWakuPM3col 	= ceil(($wWakuPM3) / $wHansu);
		$WakuAM2CEL 	= $wWakuAM2col * $wHansu; #一日のAM2のセル数
		$WakuPM1CEL 	= $wWakuPM1col * $wHansu; #一日のPM1のセル数
		$WakuPM2CEL 	= $wWakuPM2col * $wHansu; #一日のPM2のセル数
		$WakuPM3CEL 	= $wWakuPM3col * $wHansu; #一日のPM3のセル数
		$wWakuSum 		= $wWakuAMcol + $wWakuAM2col + $wWakuPM1col + $wWakuPM2col + $wWakuPM3col;
	} elseif ($wWakuPattern == 9) { //10枠
		$wWakuAM2col 	= ceil(($wWakuAM2) / $wHansu);
		$wWakuAM3col 	= ceil(($wWakuAM3) / $wHansu);
		$wWakuAM4col 	= ceil(($wWakuAM4) / $wHansu);
		$wWakuPM1col 	= ceil(($wWakuPM1) / $wHansu);
		$wWakuPM2col 	= ceil(($wWakuPM2) / $wHansu);
		$wWakuPM3col 	= ceil(($wWakuPM3) / $wHansu);
		$wWakuPM4col 	= ceil(($wWakuPM4) / $wHansu);
		$wWakuPM5col 	= ceil(($wWakuPM5) / $wHansu);
		$wWakuPM6col 	= ceil(($wWakuPM6) / $wHansu);
		$WakuAM2CEL 	= $wWakuAM2col * $wHansu; #一日のAM2のセル数
		$WakuAM3CEL 	= $wWakuAM3col * $wHansu; #一日のAM3のセル数
		$WakuAM4CEL 	= $wWakuAM4col * $wHansu; #一日のAM4のセル数
		$WakuPM1CEL 	= $wWakuPM1col * $wHansu; #一日のPM1のセル数
		$WakuPM2CEL 	= $wWakuPM2col * $wHansu; #一日のPM2のセル数
		$WakuPM3CEL 	= $wWakuPM3col * $wHansu; #一日のPM3のセル数
		$WakuPM4CEL 	= $wWakuPM4col * $wHansu; #一日のPM4のセル数
		$WakuPM5CEL 	= $wWakuPM5col * $wHansu; #一日のPM5のセル数
		$WakuPM6CEL 	= $wWakuPM5col * $wHansu; #一日のPM6のセル数
		$wWakuSum 		= $wWakuAMcol + $wWakuAM2col + $wWakuAM3col + $wWakuAM4col + $wWakuPM1col + $wWakuPM2col + $wWakuPM3col + $wWakuPM4col + $wWakuPM5col + $wWakuPM6col;
	} else {
		$wWakuPM1col 	= ceil(($wWakuPM1) / $wHansu);
		$WakuPM1CEL 	= $wWakuPM1col * $wHansu; #一日のPM1のセル数
		$wWakuSum 		= $wWakuAMcol + $wWakuPM1col;
	}

	$x = 0;
	$y = 0;

	//AM1
	for ($i = 0; $i < $SenyuDateCnt; $i++) {
		for ($j = 0; $j < $wHansu; $j++) {
			for ($k = 0; $k < $wWakuAMcol; $k++) {
				$wKoteihyou1[$x] = $wKoteihyouEX[$y];

				if ($y == $Aki)
					$n = $x;
				$x++;
				$y++;
			}
			if ($wWakuPattern > 2 and $wWakuPattern <= 7) { //3枠
				$y = $y + $wWakuPM1col + $wWakuPM2col;
			} elseif ($wWakuPattern == 8) { //5枠
				$y = $y + $wWakuAM2col + $wWakuPM1col + $wWakuPM2col + $wWakuPM3col;
			} elseif ($wWakuPattern == 9) { //10枠
				$y = $y + $wWakuAM2col + $wWakuAM3col + $wWakuAM4col + $wWakuPM1col + $wWakuPM2col + $wWakuPM3col + $wWakuPM4col + $wWakuPM5col + $wWakuPM6col;
			} else {
				$y = $y + $wWakuPM1col;
			}
		}
		if ($wWakuPattern > 2 and $wWakuPattern <= 7) { //3枠
			$x = $x + $WakuPM1CEL + $WakuPM2CEL;
		} elseif ($wWakuPattern == 8) { //5枠
			$x = $x + $WakuAM2CEL + $WakuPM1CEL + $WakuPM2CEL + $WakuPM3CEL;
		} elseif ($wWakuPattern == 9) { //10枠
			$x = $x + $WakuAM2CEL + $WakuAM3CEL + $WakuAM4CEL + $WakuPM1CEL + $WakuPM2CEL + $WakuPM3CEL + $WakuPM4CEL + $WakuPM5CEL + $WakuPM6CEL;
		} else {
			$x = $x + $WakuPM1CEL;
		}
	}


	//AM2
	if ($wWakuPattern == 8) { //5枠
		$x = $WakuAMCEL;
		$y = $wWakuAMcol;
		for ($i = 0; $i < $SenyuDateCnt; $i++) {
			for ($j = 0; $j < $wHansu; $j++) {
				for ($k = 0; $k < $wWakuAM2col; $k++) {
					$wKoteihyou1[$x] = $wKoteihyouEX[$y];
					if ($y == $Aki)
						$n = $x;
					$x++;
					$y++;
				}
				$y = $y + $wWakuAMcol + $wWakuPM1col + $wWakuPM2col  + $wWakuPM3col;
			}
			$x = $x  + $WakuAMCEL + $WakuPM1CEL + $WakuPM2CEL + $WakuPM3CEL;
		}
	} elseif ($wWakuPattern == 9) { //10枠
		$x = $WakuAMCEL;
		$y = $wWakuAMcol;
		for ($i = 0; $i < $SenyuDateCnt; $i++) {
			for ($j = 0; $j < $wHansu; $j++) {
				for ($k = 0; $k < $wWakuAM2col; $k++) {
					$wKoteihyou1[$x] = $wKoteihyouEX[$y];
					if ($y == $Aki)
						$n = $x;
					$x++;
					$y++;
				}
				$y = $y + $wWakuPM6col + $wWakuPM5col + $wWakuPM4col + $wWakuPM3col + $wWakuPM2col + $wWakuPM1col + $wWakuAM4col + $wWakuAM3col + $wWakuAMcol;
			}
			$x = $x + $WakuPM6CEL + $WakuPM5CEL + $WakuPM4CEL + $WakuPM3CEL + $WakuPM2CEL + $WakuPM1CEL + $WakuAM4CEL + $WakuAM3CEL + $WakuAMCEL;
		}
	}


	//AM3
	if ($wWakuPattern == 9) { //10枠
		$x = $WakuAMCEL + $WakuAM2CEL;
		$y = $wWakuAMcol + $wWakuAM2col;
		for ($i = 0; $i < $SenyuDateCnt; $i++) {
			for ($j = 0; $j < $wHansu; $j++) {
				for ($k = 0; $k < $wWakuAM3col; $k++) {
					$wKoteihyou1[$x] = $wKoteihyouEX[$y];
					if ($y == $Aki)
						$n = $x;
					$x++;
					$y++;
				}
				$y = $y + $wWakuPM6col + $wWakuPM5col + $wWakuPM4col + $wWakuPM3col + $wWakuPM2col + $wWakuPM1col + $wWakuAM4col + $wWakuAM2col + $wWakuAMcol;
			}
			$x = $x + $WakuPM6CEL + $WakuPM5CEL + $WakuPM4CEL + $WakuPM3CEL + $WakuPM2CEL + $WakuPM1CEL + $WakuAM4CEL + $WakuAM2CEL + $WakuAMCEL;
		}
	}


	//AM4
	if ($wWakuPattern == 9) { //10枠
		$x = $WakuAMCEL + $WakuAM2CEL + $WakuAM3CEL;
		$y = $wWakuAMcol + $wWakuAM2col + $wWakuAM3col;
		for ($i = 0; $i < $SenyuDateCnt; $i++) {
			for ($j = 0; $j < $wHansu; $j++) {
				for ($k = 0; $k < $wWakuAM4col; $k++) {
					$wKoteihyou1[$x] = $wKoteihyouEX[$y];
					if ($y == $Aki)
						$n = $x;
					$x++;
					$y++;
				}
				$y = $y + $wWakuPM6col + $wWakuPM5col + $wWakuPM4col + $wWakuPM3col + $wWakuPM2col + $wWakuPM1col + $wWakuAM3col + $wWakuAM2col + $wWakuAMcol;
			}
			$x = $x + $WakuPM6CEL + $WakuPM5CEL + $WakuPM4CEL + $WakuPM3CEL + $WakuPM2CEL + $WakuPM1CEL + $WakuAM3CEL + $WakuAM2CEL + $WakuAMCEL;
		}
	}


	//PM1
	if ($wWakuPattern > 2 and $wWakuPattern <= 7) { //3枠
		$x = $WakuAMCEL;
		$y = $wWakuAMcol;
		for ($i = 0; $i < $SenyuDateCnt; $i++) {
			for ($j = 0; $j < $wHansu; $j++) {
				for ($k = 0; $k < $wWakuPM1col; $k++) {
					$wKoteihyou1[$x] = $wKoteihyouEX[$y];
					echo "<br>" . __LINE__ . "行目:" . $wKoteihyou1[$x];
					if ($y == $Aki)
						$n = $x;
					$x++;
					$y++;
				}
				$y = $y + $wWakuPM2col + $wWakuAMcol;
			}
			$x = $x + $WakuPM2CEL + $WakuAMCEL;
		}
	} elseif ($wWakuPattern <= 2) { //2枠
		$x = $WakuAMCEL;
		$y = $wWakuAMcol;
		for ($i = 0; $i < $SenyuDateCnt; $i++) {
			for ($j = 0; $j < $wHansu; $j++) {
				for ($k = 0; $k < $wWakuPM1col; $k++) {
					$wKoteihyou1[$x] = $wKoteihyouEX[$y];
					if ($y == $Aki)
						$n = $x;
					$x++;
					$y++;
				}
				$y = $y + $wWakuAMcol;
			}
			$x = $x + $WakuAMCEL;
		}
	} elseif ($wWakuPattern == 8) { //5枠
		$x = $WakuAMCEL + $WakuAM2CEL;
		$y = $wWakuAMcol + $wWakuAM2col;
		for ($i = 0; $i < $SenyuDateCnt; $i++) {
			for ($j = 0; $j < $wHansu; $j++) {
				for ($k = 0; $k < $wWakuPM1col; $k++) {
					$wKoteihyou1[$x] = $wKoteihyouEX[$y];
					if ($y == $Aki)
						$n = $x;
					$x++;
					$y++;
				}
				$y = $y + $wWakuPM3col + $wWakuPM2col + $wWakuAM2col  + $wWakuAMcol;
			}
			$x = $x + $WakuPM3CEL + $WakuPM2CEL + $WakuAM2CEL  + $WakuAMCEL;
		}
	} elseif ($wWakuPattern == 9) { //10枠
		$x = $WakuAMCEL + $WakuAM2CEL + $WakuAM3CEL + $WakuAM4CEL;
		$y = $wWakuAMcol + $wWakuAM2col + $wWakuAM3col + $wWakuAM4col;
		for ($i = 0; $i < $SenyuDateCnt; $i++) {
			for ($j = 0; $j < $wHansu; $j++) {
				for ($k = 0; $k < $wWakuPM1col; $k++) {
					$wKoteihyou1[$x] = $wKoteihyouEX[$y];
					if ($y == $Aki)
						$n = $x;
					$x++;
					$y++;
				}
				$y = $y + $wWakuPM6col + $wWakuPM5col + $wWakuPM4col + $wWakuPM3col  + $wWakuPM2col + $wWakuAM4col + $wWakuAM3col + $wWakuAM2col + $wWakuAMcol;
			}
			$x = $x + $WakuPM6CEL + $WakuPM5CEL + $WakuPM4CEL + $WakuPM3CEL + $WakuPM2CEL + $WakuAM4CEL + $WakuAM3CEL + $WakuAM2CEL + $WakuAMCEL;
		}
	}



	//PM2
	if ($wWakuPattern > 2 and $wWakuPattern <= 7) { //3枠
		$x = $WakuAMCEL + $WakuPM1CEL;
		$y = $wWakuAMcol + $wWakuPM1col;
		for ($i = 0; $i < $SenyuDateCnt; $i++) {
			for ($j = 0; $j < $wHansu; $j++) {
				for ($k = 0; $k < $wWakuPM2col; $k++) {
					$wKoteihyou1[$x] = $wKoteihyouEX[$y];

					if ($y == $Aki)
						$n = $x;
					$x++;
					$y++;
				}
				$y = $y + $wWakuPM1col + $wWakuAMcol;
			}
			$x = $x + $WakuPM1CEL + $WakuAMCEL;
		}
	} elseif ($wWakuPattern == 8) { //5枠
		$x = $WakuAMCEL + $WakuAM2CEL + $WakuPM1CEL;
		$y = $wWakuAMcol + $wWakuAM2col + $wWakuPM1col;
		for ($i = 0; $i < $SenyuDateCnt; $i++) {
			for ($j = 0; $j < $wHansu; $j++) {
				for ($k = 0; $k < $wWakuPM2col; $k++) {
					$wKoteihyou1[$x] = $wKoteihyouEX[$y];
					if ($y == $Aki)
						$n = $x;
					$x++;
					$y++;
				}
				$y = $y + $wWakuPM3col + $wWakuPM1col + $wWakuAM2col  + $wWakuAMcol;
			}
			$x = $x + $WakuPM3CEL + $WakuPM1CEL + $WakuAM2CEL  + $WakuAMCEL;
		}
	} elseif ($wWakuPattern == 9) { //10枠
		$x = $WakuAMCEL + $WakuAM2CEL + $WakuAM3CEL + $WakuAM4CEL + $WakuPM1CEL;
		$y = $wWakuAMcol + $wWakuAM2col + $wWakuAM3col + $wWakuAM4col + $wWakuPM1col;
		for ($i = 0; $i < $SenyuDateCnt; $i++) {
			for ($j = 0; $j < $wHansu; $j++) {
				for ($k = 0; $k < $wWakuPM2col; $k++) {
					$wKoteihyou1[$x] = $wKoteihyouEX[$y];
					if ($y == $Aki)
						$n = $x;
					$x++;
					$y++;
				}
				$y = $y + $wWakuPM6col + $wWakuPM5col + $wWakuPM4col + $wWakuPM3col  + $wWakuPM1col + $wWakuAM4col + $wWakuAM3col + $wWakuAM2col + $wWakuAMcol;
			}
			$x = $x + $WakuPM6CEL + $WakuPM5CEL + $WakuPM4CEL + $WakuPM3CEL + $WakuPM1CEL + $WakuAM4CEL + $WakuAM3CEL + $WakuAM2CEL + $WakuAMCEL;
		}
	}



	//PM3
	if ($wWakuPattern == 8) { //5枠
		$x = $WakuAMCEL + $WakuAM2CEL + $WakuPM1CEL + $WakuPM2CEL;
		$y = $wWakuAMcol + $wWakuAM2col + $wWakuPM1col + $wWakuPM2col;
		for ($i = 0; $i < $SenyuDateCnt; $i++) {
			for ($j = 0; $j < $wHansu; $j++) {
				for ($k = 0; $k < $wWakuPM3col; $k++) {
					$wKoteihyou1[$x] = $wKoteihyouEX[$y];
					if ($y == $Aki)
						$n = $x;
					$x++;
					$y++;
				}
				$y = $y + $wWakuPM2col + $wWakuPM1col + $wWakuAM2col  + $wWakuAMcol;
			}
			$x = $x + $WakuPM2CEL + $WakuPM1CEL + $WakuAM2CEL  + $WakuAMCEL;
		}
	} elseif ($wWakuPattern == 9) { //10枠
		$x = $WakuAMCEL + $WakuAM2CEL + $WakuAM3CEL + $WakuAM4CEL + $WakuPM1CEL + $WakuPM2CEL;
		$y = $wWakuAMcol + $wWakuAM2col + $wWakuAM3col + $wWakuAM4col + $wWakuPM1col + $wWakuPM2col;
		for ($i = 0; $i < $SenyuDateCnt; $i++) {
			for ($j = 0; $j < $wHansu; $j++) {
				for ($k = 0; $k < $wWakuPM3col; $k++) {
					$wKoteihyou1[$x] = $wKoteihyouEX[$y];
					if ($y == $Aki)
						$n = $x;
					$x++;
					$y++;
				}
				$y = $y + $wWakuPM6col + $wWakuPM5col + $wWakuPM4col + $wWakuPM2col  + $wWakuPM1col + $wWakuAM4col + $wWakuAM3col + $wWakuAM2col + $wWakuAMcol;
			}
			$x = $x + $WakuPM6CEL + $WakuPM5CEL + $WakuPM4CEL + $WakuPM2CEL + $WakuPM1CEL + $WakuAM4CEL + $WakuAM3CEL + $WakuAM2CEL + $WakuAMCEL;
		}
	}



	//PM4
	if ($wWakuPattern == 9) { //10枠
		$x = $WakuAMCEL + $WakuAM2CEL + $WakuAM3CEL + $WakuAM4CEL + $WakuPM1CEL + $WakuPM2CEL + $WakuPM3CEL;
		$y = $wWakuAMcol + $wWakuAM2col + $wWakuAM3col + $wWakuAM4col + $wWakuPM1col + $wWakuPM2col + $wWakuPM3col;
		for ($i = 0; $i < $SenyuDateCnt; $i++) {
			for ($j = 0; $j < $wHansu; $j++) {
				for ($k = 0; $k < $wWakuPM4col; $k++) {
					$wKoteihyou1[$x] = $wKoteihyouEX[$y];
					if ($y == $Aki)
						$n = $x;
					$x++;
					$y++;
				}
				$y = $y + $wWakuPM6col + $wWakuPM5col + $wWakuPM3col + $wWakuPM2col + $wWakuPM1col + $wWakuAM4col  + $wWakuAM3col + $wWakuAM2col + $wWakuAMcol;
			}
			$x = $x + $WakuPM6CEL + $WakuPM5CEL + $WakuPM3CEL + $WakuPM2CEL + $WakuPM1CEL + $WakuAM4CEL  + $WakuAM3CEL + $WakuAM2CEL + $WakuAMCEL;
		}
	}


	//PM5
	if ($wWakuPattern == 9) { //10枠
		$x = $WakuAMCEL + $WakuAM2CEL + $WakuAM3CEL + $WakuAM4CEL + $WakuPM1CEL + $WakuPM2CEL + $WakuPM3CEL + $WakuPM4CEL;
		$y = $wWakuAMcol + $wWakuAM2col + $wWakuAM3col + $wWakuAM4col + $wWakuPM1col + $wWakuPM2col + $wWakuPM3col  + $wWakuPM4col;
		for ($i = 0; $i < $SenyuDateCnt; $i++) {
			for ($j = 0; $j < $wHansu; $j++) {
				for ($k = 0; $k < $wWakuPM5col; $k++) {
					$wKoteihyou1[$x] = $wKoteihyouEX[$y];
					if ($y == $Aki)
						$n = $x;
					$x++;
					$y++;
				}
				$y = $y + $wWakuPM6col + $wWakuPM4col + $wWakuPM3col + $wWakuPM2col + $wWakuPM1col + $wWakuAM4col  + $wWakuAM3col + $wWakuAM2col + $wWakuAMcol;
			}
			$x = $x + $WakuPM6CEL + $WakuPM4CEL + $WakuPM3CEL + $WakuPM2CEL + $WakuPM1CEL + $WakuAM4CEL  + $WakuAM3CEL + $WakuAM2CEL + $WakuAMCEL;
		}
	}


	//PM6
	if ($wWakuPattern == 9) { //10枠
		$x = $WakuAMCEL + $WakuAM2CEL + $WakuAM3CEL + $WakuAM4CEL + $WakuPM1CEL + $WakuPM2CEL + $WakuPM3CEL + $WakuPM4CEL + $WakuPM5CEL;
		$y = $wWakuAMcol + $wWakuAM2col + $wWakuAM3col + $wWakuAM4col + $wWakuPM1col + $wWakuPM2col + $wWakuPM3col  + $wWakuPM4col + $wWakuPM5col;
		for ($i = 0; $i < $SenyuDateCnt; $i++) {
			for ($j = 0; $j < $wHansu; $j++) {
				for ($k = 0; $k < $wWakuPM6col; $k++) {
					$wKoteihyou1[$x] = $wKoteihyouEX[$y];
					if ($y == $Aki)
						$n = $x;
					$x++;
					$y++;
				}
				$y = $y + $wWakuPM5col + $wWakuPM4col + $wWakuPM3col + $wWakuPM2col + $wWakuPM1col + $wWakuAM4col  + $wWakuAM3col + $wWakuAM2col + $wWakuAMcol;
			}
			$x = $x + $WakuPM5CEL + $WakuPM4CEL + $WakuPM3CEL + $WakuPM2CEL + $WakuPM1CEL + $WakuAM4CEL  + $WakuAM3CEL + $WakuAM2CEL + $WakuAMCEL;
		}
	}


	$key = 0;
	ksort($wKoteihyou1);

	for ($i = 0; $i < count($wKoteihyou1); $i++) {
		if (ctype_digit($wKoteihyou1[$i]) && $wKoteihyou1[$i] !== "空き") {
			$LastRoom = $i;
		}
	}
	$l = 0;
	$o = 0;
	for ($i = 0; $i < count($wKoteihyou1); $i++) {
		if ($n <= $i) {
			if (ctype_digit($wKoteihyou1[$i]) && $l == 0 && $o == 0) {
				$wKoteihyou1[$n] = $wKoteihyou1[$i];
				for ($j = 0; $j < count($wKaiRoom3); $j++) {
					if ($wKaiRoom3[$j] == $wKoteihyou1[$n]) {
						$Room = $j + 1;
					}
				}
				if (isset($wKaiRoom3[$Room])) {
					$wKoteihyou1[$i] = $wKaiRoom3[$Room];
					$Room++;
				} else {
					$wKoteihyou1[$i] = "空き";
				}
				$l++;
			} elseif (ctype_digit($wKoteihyou1[$i])) {
				if (isset($wKaiRoom3[$Room])) {
					$wKoteihyou1[$i] = $wKaiRoom3[$Room];
					$Room++;
				} else {
					$wKoteihyou1[$i] = "空き";
				}
			} elseif ($LastRoom <= $i && isset($wKaiRoom3[$Room]) && $wKoteihyou1[$i] == "空き" && $o == 0 && $n == $i) {
				$wKoteihyou1[$i] = $wKaiRoom3[$Room];
				$Room++;
				$o++;
			}
		} else {
			if ($i == $LastRoom) {
				for ($j = 0; $j < count($wKaiRoom3); $j++) {
					if ($wKaiRoom3[$j] == $wKoteihyou1[$i]) {
						$Room = $j + 1;
					}
				}
			}
		}
	}

	$x = 0;
	$y = 0;
	//AM
	for ($i = 0; $i < $SenyuDateCnt; $i++) {
		for ($j = 0; $j < $wHansu; $j++) {
			for ($k = 0; $k < $wWakuAMcol; $k++) {
				$wKoteihyouEX[$y] = $wKoteihyou1[$x];
				if ($y == $Aki)
					$n = $x;
				$x++;
				$y++;
			}
			if ($wWakuPattern > 2 and $wWakuPattern <= 7) { //3枠
				$y = $y + $wWakuPM1col + $wWakuPM2col;
			} elseif ($wWakuPattern == 8) { //5枠
				$y = $y + $wWakuAM2col + $wWakuPM1col + $wWakuPM2col + $wWakuPM3col;
			} elseif ($wWakuPattern == 9) { //10枠
				$y = $y + $wWakuAM2col + $wWakuAM3col + $wWakuAM4col + $wWakuPM1col + $wWakuPM2col + $wWakuPM3col + $wWakuPM4col + $wWakuPM5col + $wWakuPM6col;
			} else {
				$y = $y + $wWakuPM1col;
			}
		}
		if ($wWakuPattern > 2 and $wWakuPattern <= 7) { //3枠
			$x = $x + $WakuPM1CEL + $WakuPM2CEL;
		} elseif ($wWakuPattern == 8) { //5枠
			$x = $x + $WakuAM2CEL + $WakuPM1CEL + $WakuPM2CEL + $WakuPM3CEL;
		} elseif ($wWakuPattern == 9) { //10枠
			$x = $x + $WakuAM2CEL + $WakuAM3CEL + $WakuAM4CEL + $WakuPM1CEL + $WakuPM2CEL + $WakuPM3CEL + $WakuPM4CEL + $WakuPM5CEL + $WakuPM6CEL;
		} else {
			$x = $x + $WakuPM1CEL;
		}
	}


	//AM2
	if ($wWakuPattern == 8) { //5枠
		$x = $WakuAMCEL;
		$y = $wWakuAMcol;
		for ($i = 0; $i < $SenyuDateCnt; $i++) {
			for ($j = 0; $j < $wHansu; $j++) {
				for ($k = 0; $k < $wWakuAM2col; $k++) {
					$wKoteihyouEX[$y] = $wKoteihyou1[$x];
					if ($y == $Aki)
						$n = $x;
					$x++;
					$y++;
				}
				$y = $y + $wWakuPM3col + $wWakuPM2col + $wWakuPM1col + $wWakuAMcol;
			}
			$x = $x + $WakuPM3CEL + $WakuPM2CEL + $WakuPM1CEL + $WakuAMCEL;
		}
	} elseif ($wWakuPattern == 9) { //10枠
		$x = $WakuAMCEL;
		$y = $wWakuAMcol;
		for ($i = 0; $i < $SenyuDateCnt; $i++) {
			for ($j = 0; $j < $wHansu; $j++) {
				for ($k = 0; $k < $wWakuAM2col; $k++) {
					$wKoteihyouEX[$y] = $wKoteihyou1[$x];
					if ($y == $Aki)
						$n = $x;
					$x++;
					$y++;
				}
				$y = $y + $wWakuPM6col + $wWakuPM5col + $wWakuPM4col + $wWakuPM3col + $wWakuPM2col + $wWakuPM1col + $wWakuAM4col + $wWakuAM3col + $wWakuAMcol;
			}
			$x = $x + $WakuPM6CEL + $WakuPM5CEL + $WakuPM4CEL + $WakuPM3CEL + $WakuPM2CEL + $WakuPM1CEL + $WakuAM4CEL + $WakuAM3CEL + $WakuAMCEL;
		}
	}


	//AM3
	if ($wWakuPattern == 9) { //10枠
		$x = $WakuAMCEL + $WakuAM2CEL;
		$y = $wWakuAMcol + $wWakuAM2col;
		for ($i = 0; $i < $SenyuDateCnt; $i++) {
			for ($j = 0; $j < $wHansu; $j++) {
				for ($k = 0; $k < $wWakuAM3col; $k++) {
					$wKoteihyouEX[$y] = $wKoteihyou1[$x];
					if ($y == $Aki)
						$n = $x;
					$x++;
					$y++;
				}
				$y = $y + $wWakuPM6col + $wWakuPM5col + $wWakuPM4col + $wWakuPM3col + $wWakuPM2col + $wWakuPM1col + $wWakuAM4col + $wWakuAM2col + $wWakuAMcol;
			}
			$x = $x + $WakuPM6CEL + $WakuPM5CEL + $WakuPM4CEL + $WakuPM3CEL + $WakuPM2CEL + $WakuPM1CEL + $WakuAM4CEL + $WakuAM2CEL + $WakuAMCEL;
		}
	}


	//AM4
	if ($wWakuPattern == 9) { //10枠
		$x = $WakuAMCEL + $WakuAM2CEL + $WakuAM3CEL;
		$y = $wWakuAMcol + $wWakuAM2col + $wWakuAM3col;
		for ($i = 0; $i < $SenyuDateCnt; $i++) {
			for ($j = 0; $j < $wHansu; $j++) {
				for ($k = 0; $k < $wWakuAM4col; $k++) {
					$wKoteihyouEX[$y] = $wKoteihyou1[$x];
					if ($y == $Aki)
						$n = $x;
					$x++;
					$y++;
				}
				$y = $y + $wWakuPM6col + $wWakuPM5col + $wWakuPM4col + $wWakuPM3col + $wWakuPM2col + $wWakuPM1col + $wWakuAM3col + $wWakuAM2col + $wWakuAMcol;
			}
			$x = $x + $WakuPM6CEL + $WakuPM5CEL + $WakuPM4CEL + $WakuPM3CEL + $WakuPM2CEL + $WakuPM1CEL + $WakuAM3CEL + $WakuAM2CEL + $WakuAMCEL;
		}
	}




	//PM1
	if ($wWakuPattern > 2 and $wWakuPattern <= 7) { //3枠
		$x = $WakuAMCEL;
		$y = $wWakuAMcol;
		for ($i = 0; $i < $SenyuDateCnt; $i++) {
			for ($j = 0; $j < $wHansu; $j++) {
				for ($k = 0; $k < $wWakuPM1col; $k++) {
					$wKoteihyouEX[$y] = $wKoteihyou1[$x];
					if ($y == $Aki)
						$n = $x;
					$x++;
					$y++;
				}
				$y = $y + $wWakuPM2col + $wWakuAMcol;
			}
			$x = $x + $WakuPM2CEL + $WakuAMCEL;
		}
	} elseif ($wWakuPattern <= 2) { //2枠
		$x = $WakuAMCEL;
		$y = $wWakuAMcol;
		for ($i = 0; $i < $SenyuDateCnt; $i++) {
			for ($j = 0; $j < $wHansu; $j++) {
				for ($k = 0; $k < $wWakuPM1col; $k++) {
					$wKoteihyouEX[$y] = $wKoteihyou1[$x];
					if ($y == $Aki)
						$n = $x;
					$x++;
					$y++;
				}
				$y = $y + $wWakuAMcol;
			}
			$x = $x + $WakuAMCEL;
		}
	} elseif ($wWakuPattern == 8) { //5枠
		$x = $WakuAMCEL + $WakuAM2CEL;
		$y = $wWakuAMcol + $wWakuAM2col;
		for ($i = 0; $i < $SenyuDateCnt; $i++) {
			for ($j = 0; $j < $wHansu; $j++) {
				for ($k = 0; $k < $wWakuPM1col; $k++) {
					$wKoteihyouEX[$y] = $wKoteihyou1[$x];
					if ($y == $Aki)
						$n = $x;
					$x++;
					$y++;
				}
				$y = $y + $wWakuPM3col + $wWakuPM2col + $wWakuAM2col + $wWakuAMcol;
			}
			$x = $x + $WakuPM3CEL + $WakuPM2CEL + $WakuAM2CEL + $WakuAMCEL;
		}
	} elseif ($wWakuPattern == 9) { //10枠
		$x = $WakuAMCEL + $WakuAM2CEL + $WakuAM3CEL + $WakuAM4CEL;
		$y = $wWakuAMcol + $wWakuAM2col + $wWakuAM3col + $wWakuAM4col;
		for ($i = 0; $i < $SenyuDateCnt; $i++) {
			for ($j = 0; $j < $wHansu; $j++) {
				for ($k = 0; $k < $wWakuPM1col; $k++) {
					$wKoteihyouEX[$y] = $wKoteihyou1[$x];
					if ($y == $Aki)
						$n = $x;
					$x++;
					$y++;
				}
				$y = $y + $wWakuPM6col + $wWakuPM5col + $wWakuPM4col  + $wWakuPM3col + $wWakuPM2col + $wWakuAM4col + $wWakuAM3col + $wWakuAM2col + $wWakuAMcol;
			}
			$x = $x + $WakuPM6CEL + $WakuPM5CEL + $WakuPM4CEL  + $WakuPM3CEL + $WakuPM2CEL + $WakuAM4CEL + $WakuAM3CEL + $WakuAM2CEL + $WakuAMCEL;
		}
	}


	//PM2
	if ($wWakuPattern > 2 and $wWakuPattern <= 7) { //3枠
		$x = $WakuAMCEL + $WakuPM1CEL;
		$y = $wWakuAMcol + $wWakuPM1col;
		for ($i = 0; $i < $SenyuDateCnt; $i++) {
			for ($j = 0; $j < $wHansu; $j++) {
				for ($k = 0; $k < $wWakuPM2col; $k++) {
					$wKoteihyouEX[$y] = $wKoteihyou1[$x];
					if ($y == $Aki)
						$n = $x;
					$x++;
					$y++;
				}
				$y = $y + $wWakuPM1col + $wWakuAMcol;
			}
			$x = $x + $WakuPM1CEL + $WakuAMCEL;
		}
	} elseif ($wWakuPattern == 8) { //5枠
		$x = $WakuAMCEL + $WakuAM2CEL + $WakuPM1CEL;
		$y = $wWakuAMcol + $wWakuAM2col + $wWakuPM1col;
		for ($i = 0; $i < $SenyuDateCnt; $i++) {
			for ($j = 0; $j < $wHansu; $j++) {
				for ($k = 0; $k < $wWakuPM2col; $k++) {
					$wKoteihyouEX[$y] = $wKoteihyou1[$x];
					if ($y == $Aki)
						$n = $x;
					$x++;
					$y++;
				}
				$y = $y + $wWakuPM3col + $wWakuPM1col + $wWakuAM2col + $wWakuAMcol;
			}
			$x = $x + $WakuPM3CEL + $WakuPM1CEL + $WakuAM2CEL + $WakuAMCEL;
		}
	} elseif ($wWakuPattern == 9) { //10枠
		$x = $WakuAMCEL + $WakuAM2CEL + $WakuAM3CEL + $WakuAM4CEL + $WakuPM1CEL;
		$y = $wWakuAMcol + $wWakuAM2col + $wWakuAM3col + $wWakuAM4col + $wWakuPM1col;
		for ($i = 0; $i < $SenyuDateCnt; $i++) {
			for ($j = 0; $j < $wHansu; $j++) {
				for ($k = 0; $k < $wWakuPM2col; $k++) {
					$wKoteihyouEX[$y] = $wKoteihyou1[$x];
					if ($y == $Aki)
						$n = $x;
					$x++;
					$y++;
				}
				$y = $y + $wWakuPM6col + $wWakuPM5col + $wWakuPM4col  + $wWakuPM3col + $wWakuPM1col + $wWakuAM4col + $wWakuAM3col + $wWakuAM2col + $wWakuAMcol;
			}
			$x = $x + $WakuPM6CEL + $WakuPM5CEL + $WakuPM4CEL  + $WakuPM3CEL + $WakuPM1CEL + $WakuAM4CEL + $WakuAM3CEL + $WakuAM2CEL + $WakuAMCEL;
		}
	}



	//PM3
	if ($wWakuPattern == 8) { //5枠
		$x = $WakuAMCEL + $WakuAM2CEL + $WakuPM1CEL + $WakuPM2CEL;
		$y = $wWakuAMcol + $wWakuAM2col + $wWakuPM1col + $wWakuPM2col;
		for ($i = 0; $i < $SenyuDateCnt; $i++) {
			for ($j = 0; $j < $wHansu; $j++) {
				for ($k = 0; $k < $wWakuPM3col; $k++) {
					$wKoteihyouEX[$y] = $wKoteihyou1[$x];
					if ($y == $Aki)
						$n = $x;
					$x++;
					$y++;
				}
				$y = $y + $wWakuPM2col + $wWakuPM1col + $wWakuAM2col + $wWakuAMcol;
			}
			$x = $x + $WakuPM2CEL + $WakuPM1CEL + $WakuAM2CEL + $WakuAMCEL;
		}
	} elseif ($wWakuPattern == 9) { //10枠
		$x = $WakuAMCEL + $WakuAM2CEL + $WakuAM3CEL + $WakuAM4CEL + $WakuPM1CEL + $WakuPM2CEL;
		$y = $wWakuAMcol + $wWakuAM2col + $wWakuAM3col + $wWakuAM4col + $wWakuPM1col + $wWakuPM2col;
		for ($i = 0; $i < $SenyuDateCnt; $i++) {
			for ($j = 0; $j < $wHansu; $j++) {
				for ($k = 0; $k < $wWakuPM3col; $k++) {
					$wKoteihyouEX[$y] = $wKoteihyou1[$x];
					if ($y == $Aki)
						$n = $x;
					$x++;
					$y++;
				}
				$y = $y + $wWakuPM6col + $wWakuPM5col + $wWakuPM4col  + $wWakuPM2col + $wWakuPM1col + $wWakuAM4col + $wWakuAM3col + $wWakuAM2col + $wWakuAMcol;
			}
			$x = $x + $WakuPM6CEL + $WakuPM5CEL + $WakuPM4CEL  + $WakuPM2CEL + $WakuPM1CEL + $WakuAM4CEL + $WakuAM3CEL + $WakuAM2CEL + $WakuAMCEL;
		}
	}



	//PM4
	if ($wWakuPattern == 9) { //10枠
		$x = $WakuAMCEL + $WakuAM2CEL + $WakuAM3CEL + $WakuAM4CEL + $WakuPM1CEL + $WakuPM2CEL + $WakuPM3CEL;
		$y = $wWakuAMcol + $wWakuAM2col + $wWakuAM3col + $wWakuAM4col + $wWakuPM1col + $wWakuPM2col + $wWakuPM3col;
		for ($i = 0; $i < $SenyuDateCnt; $i++) {
			for ($j = 0; $j < $wHansu; $j++) {
				for ($k = 0; $k < $wWakuPM4col; $k++) {
					$wKoteihyouEX[$y] = $wKoteihyou1[$x];
					if ($y == $Aki)
						$n = $x;
					$x++;
					$y++;
				}
				$y = $y + $wWakuPM6col + $wWakuPM5col + $wWakuPM3col + $wWakuPM2col + $wWakuPM1col + $wWakuAM4col + $wWakuAM3col + $wWakuAM2col + $wWakuAMcol;
			}
			$x = $x + $WakuPM6CEL + $WakuPM5CEL + $WakuPM3CEL + $WakuPM2CEL + $WakuPM1CEL + $WakuAM4CEL + $WakuAM3CEL + $WakuAM2CEL + $WakuAMCEL;
		}
	}



	//PM5
	if ($wWakuPattern == 9) { //10枠
		$x = $WakuAMCEL + $WakuAM2CEL + $WakuAM3CEL + $WakuAM4CEL + $WakuPM1CEL + $WakuPM2CEL + $WakuPM3CEL + $WakuPM4CEL;
		$y = $wWakuAMcol + $wWakuAM2col + $wWakuAM3col + $wWakuAM4col + $wWakuPM1col + $wWakuPM2col + $wWakuPM3col + $wWakuPM4col;
		for ($i = 0; $i < $SenyuDateCnt; $i++) {
			for ($j = 0; $j < $wHansu; $j++) {
				for ($k = 0; $k < $wWakuPM5col; $k++) {
					$wKoteihyouEX[$y] = $wKoteihyou1[$x];
					if ($y == $Aki)
						$n = $x;
					$x++;
					$y++;
				}
				$y = $y + $wWakuPM6col + $wWakuPM4col + $wWakuPM3col + $wWakuPM2col + $wWakuPM1col + $wWakuAM4col + $wWakuAM3col + $wWakuAM2col + $wWakuAMcol;
			}
			$x = $x + $WakuPM6CEL + $WakuPM4CEL + $WakuPM3CEL + $WakuPM2CEL + $WakuPM1CEL + $WakuAM4CEL + $WakuAM3CEL + $WakuAM2CEL + $WakuAMCEL;
		}
	}


	//PM6
	if ($wWakuPattern == 9) { //10枠
		$x = $WakuAMCEL + $WakuAM2CEL + $WakuAM3CEL + $WakuAM4CEL + $WakuPM1CEL + $WakuPM2CEL + $WakuPM3CEL + $WakuPM4CEL + $WakuPM5CEL;
		$y = $wWakuAMcol + $wWakuAM2col + $wWakuAM3col + $wWakuAM4col + $wWakuPM1col + $wWakuPM2col + $wWakuPM3col + $wWakuPM4col + $wWakuPM5col;
		for ($i = 0; $i < $SenyuDateCnt; $i++) {
			for ($j = 0; $j < $wHansu; $j++) {
				for ($k = 0; $k < $wWakuPM6col; $k++) {
					$wKoteihyouEX[$y] = $wKoteihyou1[$x];
					if ($y == $Aki)
						$n = $x;
					$x++;
					$y++;
				}
				$y = $y + $wWakuPM5col + $wWakuPM4col + $wWakuPM3col + $wWakuPM2col + $wWakuPM1col + $wWakuAM4col + $wWakuAM3col + $wWakuAM2col + $wWakuAMcol;
			}
			$x = $x + $WakuPM5CEL + $WakuPM4CEL + $WakuPM3CEL + $WakuPM2CEL + $WakuPM1CEL + $WakuAM4CEL + $WakuAM3CEL + $WakuAM2CEL + $WakuAMCEL;
		}
	}
}

########################################################
# 空き削除処理
########################################################

if ($work == 3) {
	$wKoteihyouEX = SPFWParameter::getValues('wwKoteihyouEX');		#配列
	$Aki = SPFWParameter::getValues('Aki');
	$wWakuAMcol = ceil(($wWakuAM) / $wHansu); #入力枠数を班数でわった切り上げた数
	$WakuAMCEL = $wWakuAMcol * $wHansu; #一日のAMのセル数
	if ($wWakuPattern > 2 and $wWakuPattern <= 7) { //3枠
		$wWakuPM1col = ceil(($wWakuPM1) / $wHansu);
		$wWakuPM2col = ceil(($wWakuPM2) / $wHansu);
		$WakuPM1CEL = $wWakuPM1col * $wHansu; #一日のPM1のセル数
		$WakuPM2CEL = $wWakuPM2col * $wHansu; #一日のPM2のセル数
	} elseif ($wWakuPattern == 8) { //5枠
		$wWakuAM2col = ceil(($wWakuAM2) / $wHansu);
		$wWakuPM1col = ceil(($wWakuPM1) / $wHansu);
		$wWakuPM2col = ceil(($wWakuPM2) / $wHansu);
		$wWakuPM3col = ceil(($wWakuPM3) / $wHansu);
		$WakuAM2CEL = $wWakuAM2col * $wHansu; #一日のAM2のセル数
		$WakuPM1CEL = $wWakuPM1col * $wHansu; #一日のPM1のセル数
		$WakuPM2CEL = $wWakuPM2col * $wHansu; #一日のPM2のセル数
		$WakuPM3CEL = $wWakuPM3col * $wHansu; #一日のPM3のセル数
	} elseif ($wWakuPattern == 9) { //10枠
		$wWakuAM2col = ceil(($wWakuAM2) / $wHansu);
		$wWakuAM3col = ceil(($wWakuAM3) / $wHansu);
		$wWakuAM4col = ceil(($wWakuAM4) / $wHansu);
		$wWakuPM1col = ceil(($wWakuPM1) / $wHansu);
		$wWakuPM2col = ceil(($wWakuPM2) / $wHansu);
		$wWakuPM3col = ceil(($wWakuPM3) / $wHansu);
		$wWakuPM4col = ceil(($wWakuPM4) / $wHansu);
		$wWakuPM5col = ceil(($wWakuPM5) / $wHansu);
		$wWakuPM6col = ceil(($wWakuPM6) / $wHansu);
		$WakuAM2CEL = $wWakuAM2col * $wHansu; #一日のAM2のセル数
		$WakuAM3CEL = $wWakuAM3col * $wHansu; #一日のAM3のセル数
		$WakuAM4CEL = $wWakuAM4col * $wHansu; #一日のAM4のセル数
		$WakuPM1CEL = $wWakuPM1col * $wHansu; #一日のPM1のセル数
		$WakuPM2CEL = $wWakuPM2col * $wHansu; #一日のPM2のセル数
		$WakuPM3CEL = $wWakuPM3col * $wHansu; #一日のPM3のセル数
		$WakuPM4CEL = $wWakuPM4col * $wHansu; #一日のPM4のセル数
		$WakuPM5CEL = $wWakuPM5col * $wHansu; #一日のPM5のセル数
		$WakuPM6CEL = $wWakuPM6col * $wHansu; #一日のPM6のセル数
	} else {
		$wWakuPM1col = ceil(($wWakuPM1) / $wHansu);
		$WakuPM1CEL = $wWakuPM1col * $wHansu; #一日のPM1のセル数
	}

	$x = 0;
	$y = 0;
	//AM
	for ($i = 0; $i < $SenyuDateCnt; $i++) { ##工事順にソートするAM
		for ($j = 0; $j < $wHansu; $j++) {
			for ($k = 0; $k < $wWakuAMcol; $k++) {
				$wKoteihyou1[$x] = $wKoteihyouEX[$y];
				if ($y == $Aki)
					$n = $x;
				$x++;
				$y++;
			}
			if ($wWakuPattern > 2 and $wWakuPattern <= 7) { //3枠
				$y = $y + $wWakuPM1col + $wWakuPM2col;
			} elseif ($wWakuPattern == 8) { //5枠
				$y = $y + $wWakuAM2col + $wWakuPM1col + $wWakuPM2col + $wWakuPM3col;
			} elseif ($wWakuPattern == 9) { //10枠
				$y = $y + $wWakuAM2col  + $wWakuAM3col + $wWakuAM4col + $wWakuPM1col + $wWakuPM2col + $wWakuPM3col + $wWakuPM4col + $wWakuPM5col + $wWakuPM6col;
			} else {
				$y = $y + $wWakuPM1col;
			}
		}
		if ($wWakuPattern > 2 and $wWakuPattern <= 7) { //3枠
			$x = $x + $WakuPM1CEL + $WakuPM2CEL;
		} elseif ($wWakuPattern == 8) { //5枠
			$x = $x + $WakuAM2CEL + $WakuPM1CEL + $WakuPM2CEL + $WakuPM3CEL;
		} elseif ($wWakuPattern == 9) { //10枠
			$x = $x + $WakuAM2CEL + $WakuAM3CEL + $WakuAM4CEL + $WakuPM1CEL + $WakuPM2CEL + $WakuPM3CEL + $WakuPM4CEL + $WakuPM5CEL + $WakuPM6CEL;
		} else {
			$x = $x + $WakuPM1CEL;
		}
	}


	//AM2
	if ($wWakuPattern == 8) { //5枠
		$x = $WakuAMCEL;
		$y = $wWakuAMcol;
		for ($i = 0; $i < $SenyuDateCnt; $i++) {
			for ($j = 0; $j < $wHansu; $j++) {
				for ($k = 0; $k < $wWakuAM2col; $k++) {
					$wKoteihyou1[$x] = $wKoteihyouEX[$y];
					if ($y == $Aki)
						$n = $x;
					$x++;
					$y++;
				}
				$y = $y + $wWakuPM3col + $wWakuPM2col + $wWakuPM1col + $wWakuAMcol;
			}
			$x = $x + $WakuPM3CEL + $WakuPM2CEL + $WakuPM1CEL + $WakuAMCEL;
		}
	} elseif ($wWakuPattern == 9) { //10枠
		$x = $WakuAMCEL;
		$y = $wWakuAMcol;
		for ($i = 0; $i < $SenyuDateCnt; $i++) {
			for ($j = 0; $j < $wHansu; $j++) {
				for ($k = 0; $k < $wWakuAM2col; $k++) {
					$wKoteihyou1[$x] = $wKoteihyouEX[$y];
					if ($y == $Aki)
						$n = $x;
					$x++;
					$y++;
				}
				$y = $y + $wWakuPM6col + $wWakuPM5col + $wWakuPM4col + $wWakuPM3col + $wWakuPM2col + $wWakuPM1col + $wWakuAM4col + $wWakuAM3col + $wWakuAMcol;
			}
			$x = $x + $WakuPM6CEL + $WakuPM5CEL + $WakuPM4CEL + $WakuPM3CEL + $WakuPM2CEL + $WakuPM1CEL + $WakuAM4CEL + $WakuAM3CEL + $WakuAMCEL;
		}
	}




	//AM3
	if ($wWakuPattern == 9) { //10枠
		$x = $WakuAMCEL + $WakuAM2CEL;
		$y = $wWakuAMcol + $wWakuAM2col;
		for ($i = 0; $i < $SenyuDateCnt; $i++) {
			for ($j = 0; $j < $wHansu; $j++) {
				for ($k = 0; $k < $wWakuAM3col; $k++) {
					$wKoteihyou1[$x] = $wKoteihyouEX[$y];
					if ($y == $Aki)
						$n = $x;
					$x++;
					$y++;
				}
				$y = $y + $wWakuPM6col + $wWakuPM5col + $wWakuPM4col + $wWakuPM3col + $wWakuPM2col + $wWakuPM1col + $wWakuAM4col + $wWakuAM2col + $wWakuAMcol;
			}
			$x = $x + $WakuPM6CEL + $WakuPM5CEL + $WakuPM4CEL + $WakuPM3CEL + $WakuPM2CEL + $WakuPM1CEL + $WakuAM4CEL + $WakuAM2CEL + $WakuAMCEL;
		}
	}



	//AM4
	if ($wWakuPattern == 9) { //10枠
		$x = $WakuAMCEL + $WakuAM2CEL + $WakuAM3CEL;
		$y = $wWakuAMcol + $wWakuAM2col + $wWakuAM3col;
		for ($i = 0; $i < $SenyuDateCnt; $i++) {
			for ($j = 0; $j < $wHansu; $j++) {
				for ($k = 0; $k < $wWakuAM4col; $k++) {
					$wKoteihyou1[$x] = $wKoteihyouEX[$y];
					if ($y == $Aki)
						$n = $x;
					$x++;
					$y++;
				}
				$y = $y + $wWakuPM6col + $wWakuPM5col + $wWakuPM4col + $wWakuPM3col + $wWakuPM2col + $wWakuPM1col + $wWakuAM3col + $wWakuAM2col + $wWakuAMcol;
			}
			$x = $x + $WakuPM6CEL + $WakuPM5CEL + $WakuPM4CEL + $WakuPM3CEL + $WakuPM2CEL + $WakuPM1CEL + $WakuAM3CEL + $WakuAM2CEL + $WakuAMCEL;
		}
	}


	//PM1
	if ($wWakuPattern > 2 and $wWakuPattern <= 7) { //3枠
		$x = $WakuAMCEL;
		$y = $wWakuAMcol;
		for ($i = 0; $i < $SenyuDateCnt; $i++) {
			for ($j = 0; $j < $wHansu; $j++) {
				for ($k = 0; $k < $wWakuPM1col; $k++) {
					$wKoteihyou1[$x] = $wKoteihyouEX[$y];
					if ($y == $Aki)
						$n = $x;
					$x++;
					$y++;
				}
				$y = $y + $wWakuPM2col + $wWakuAMcol;
			}
			$x = $x + $WakuPM2CEL + $WakuAMCEL;
		}
	} elseif ($wWakuPattern <= 2) { //2枠
		$x = $WakuAMCEL;
		$y = $wWakuAMcol;
		for ($i = 0; $i < $SenyuDateCnt; $i++) {
			for ($j = 0; $j < $wHansu; $j++) {
				for ($k = 0; $k < $wWakuPM1col; $k++) {
					$wKoteihyou1[$x] = $wKoteihyouEX[$y];
					if ($y == $Aki)
						$n = $x;
					$x++;
					$y++;
				}
				$y = $y + $wWakuAMcol;
			}
			$x = $x + $WakuAMCEL;
		}
	} elseif ($wWakuPattern == 8) { //5枠
		$x = $WakuAMCEL + $WakuAM2CEL;
		$y = $wWakuAMcol + $wWakuAM2col;
		for ($i = 0; $i < $SenyuDateCnt; $i++) {
			for ($j = 0; $j < $wHansu; $j++) {
				for ($k = 0; $k < $wWakuPM1col; $k++) {
					$wKoteihyou1[$x] = $wKoteihyouEX[$y];
					if ($y == $Aki)
						$n = $x;
					$x++;
					$y++;
				}
				$y = $y + $wWakuPM3col + $wWakuPM2col + $wWakuAM2col + $wWakuAMcol;
			}
			$x = $x + $WakuPM3CEL + $WakuPM2CEL + $WakuAM2CEL + $WakuAMCEL;
		}
	} elseif ($wWakuPattern == 9) { //10枠
		$x = $WakuAMCEL + $WakuAM2CEL + $WakuAM3CEL + $WakuAM4CEL;
		$y = $wWakuAMcol + $wWakuAM2col + $wWakuAM3col + $wWakuAM4col;
		for ($i = 0; $i < $SenyuDateCnt; $i++) {
			for ($j = 0; $j < $wHansu; $j++) {
				for ($k = 0; $k < $wWakuPM1col; $k++) {
					$wKoteihyou1[$x] = $wKoteihyouEX[$y];
					if ($y == $Aki)
						$n = $x;
					$x++;
					$y++;
				}
				$y = $y + $wWakuPM6col + $wWakuPM5col + $wWakuPM4col  + $wWakuPM3col + $wWakuPM2col + $wWakuAM4col + $wWakuAM3col + $wWakuAM2col + $wWakuAMcol;
			}
			$x = $x + $WakuPM6CEL + $WakuPM5CEL + $WakuPM4CEL + $WakuPM3CEL + $WakuPM2CEL + $WakuAM4CEL + $WakuAM3CEL + $WakuAM2CEL + $WakuAMCEL;
		}
	}


	//PM2
	if ($wWakuPattern > 2 and $wWakuPattern <= 7) { //3枠
		$x = $WakuAMCEL + $WakuPM1CEL;
		$y = $wWakuAMcol + $wWakuPM1col;
		for ($i = 0; $i < $SenyuDateCnt; $i++) {
			for ($j = 0; $j < $wHansu; $j++) {
				for ($k = 0; $k < $wWakuPM2col; $k++) {
					$wKoteihyou1[$x] = $wKoteihyouEX[$y];
					if ($y == $Aki)
						$n = $x;
					$x++;
					$y++;
				}
				$y = $y + $wWakuPM1col + $wWakuAMcol;
			}
			$x = $x + $WakuPM1CEL + $WakuAMCEL;
		}
	} elseif ($wWakuPattern == 8) { //5枠
		$x = $WakuAMCEL + $WakuAM2CEL + $WakuPM1CEL;
		$y = $wWakuAMcol + $wWakuAM2col + $wWakuPM1col;
		for ($i = 0; $i < $SenyuDateCnt; $i++) {
			for ($j = 0; $j < $wHansu; $j++) {
				for ($k = 0; $k < $wWakuPM2col; $k++) {
					$wKoteihyou1[$x] = $wKoteihyouEX[$y];
					if ($y == $Aki)
						$n = $x;
					$x++;
					$y++;
				}
				$y = $y + $wWakuPM3col + $wWakuPM1col + $wWakuAM2col + $wWakuAMcol;
			}
			$x = $x + $WakuPM3CEL + $WakuPM1CEL + $WakuAM2CEL + $WakuAMCEL;
		}
	} elseif ($wWakuPattern == 9) { //10枠
		$x = $WakuAMCEL + $WakuAM2CEL + $WakuAM3CEL + $WakuAM4CEL + $WakuPM1CEL;
		$y = $wWakuAMcol + $wWakuAM2col + $wWakuAM3col + $wWakuAM4col + $wWakuPM1col;
		for ($i = 0; $i < $SenyuDateCnt; $i++) {
			for ($j = 0; $j < $wHansu; $j++) {
				for ($k = 0; $k < $wWakuPM2col; $k++) {
					$wKoteihyou1[$x] = $wKoteihyouEX[$y];
					if ($y == $Aki)
						$n = $x;
					$x++;
					$y++;
				}
				$y = $y + $wWakuPM6col + $wWakuPM5col + $wWakuPM4col  + $wWakuPM3col + $wWakuPM1col + $wWakuAM4col + $wWakuAM3col + $wWakuAM2col + $wWakuAMcol;
			}
			$x = $x + $WakuPM6CEL + $WakuPM5CEL + $WakuPM4CEL + $WakuPM3CEL + $WakuPM1CEL + $WakuAM4CEL + $WakuAM3CEL + $WakuAM2CEL + $WakuAMCEL;
		}
	}




	//PM3
	if ($wWakuPattern == 8) { //5枠
		$x = $WakuAMCEL + $WakuAM2CEL + $WakuPM1CEL + $WakuPM2CEL;
		$y = $wWakuAMcol + $wWakuAM2col + $wWakuPM1col + $wWakuPM2col;
		for ($i = 0; $i < $SenyuDateCnt; $i++) {
			for ($j = 0; $j < $wHansu; $j++) {
				for ($k = 0; $k < $wWakuPM3col; $k++) {
					$wKoteihyou1[$x] = $wKoteihyouEX[$y];
					if ($y == $Aki)
						$n = $x;
					$x++;
					$y++;
				}
				$y = $y + $wWakuPM2col + $wWakuPM1col + $wWakuAM2col + $wWakuAMcol;
			}
			$x = $x + $WakuPM2CEL + $WakuPM1CEL + $WakuAM2CEL + $WakuAMCEL;
		}
	} elseif ($wWakuPattern == 9) { //10枠
		$x = $WakuAMCEL + $WakuAM2CEL + $WakuAM3CEL + $WakuAM4CEL + $WakuPM1CEL + $WakuPM2CEL;
		$y = $wWakuAMcol + $wWakuAM2col + $wWakuAM3col + $wWakuAM4col + $wWakuPM1col + $wWakuPM2col;
		for ($i = 0; $i < $SenyuDateCnt; $i++) {
			for ($j = 0; $j < $wHansu; $j++) {
				for ($k = 0; $k < $wWakuPM3col; $k++) {
					$wKoteihyou1[$x] = $wKoteihyouEX[$y];
					if ($y == $Aki)
						$n = $x;
					$x++;
					$y++;
				}
				$y = $y + $wWakuPM6col + $wWakuPM5col + $wWakuPM4col  + $wWakuPM2col + $wWakuPM1col + $wWakuAM4col + $wWakuAM3col + $wWakuAM2col + $wWakuAMcol;
			}
			$x = $x + $WakuPM6CEL + $WakuPM5CEL + $WakuPM4CEL + $WakuPM2CEL + $WakuPM1CEL + $WakuAM4CEL + $WakuAM3CEL + $WakuAM2CEL + $WakuAMCEL;
		}
	}



	//PM4
	if ($wWakuPattern == 9) { //10枠
		$x = $WakuAMCEL + $WakuAM2CEL + $WakuAM3CEL + $WakuAM4CEL + $WakuPM1CEL + $WakuPM2CEL + $WakuPM3CEL;
		$y = $wWakuAMcol + $wWakuAM2col + $wWakuAM3col + $wWakuAM4col + $wWakuPM1col + $wWakuPM2col + $wWakuPM3col;
		for ($i = 0; $i < $SenyuDateCnt; $i++) {
			for ($j = 0; $j < $wHansu; $j++) {
				for ($k = 0; $k < $wWakuPM4col; $k++) {
					$wKoteihyou1[$x] = $wKoteihyouEX[$y];
					if ($y == $Aki)
						$n = $x;
					$x++;
					$y++;
				}
				$y = $y + $wWakuPM6col + $wWakuPM5col + $wWakuPM3col + $wWakuPM2col + $wWakuPM1col + $wWakuAM4col + $wWakuAM3col + $wWakuAM2col + $wWakuAMcol;
			}
			$x = $x + $WakuPM6CEL + $WakuPM5CEL + $WakuPM3CEL + $WakuPM2CEL + $WakuPM1CEL + $WakuAM4CEL + $WakuAM3CEL + $WakuAM2CEL + $WakuAMCEL;
		}
	}


	//PM5
	if ($wWakuPattern == 9) { //10枠
		$x = $WakuAMCEL + $WakuAM2CEL + $WakuAM3CEL + $WakuAM4CEL + $WakuPM1CEL + $WakuPM2CEL + $WakuPM3CEL + $WakuPM4CEL;
		$y = $wWakuAMcol + $wWakuAM2col + $wWakuAM3col + $wWakuAM4col + $wWakuPM1col + $wWakuPM2col + $wWakuPM3col + $wWakuPM4col;
		for ($i = 0; $i < $SenyuDateCnt; $i++) {
			for ($j = 0; $j < $wHansu; $j++) {
				for ($k = 0; $k < $wWakuPM5col; $k++) {
					$wKoteihyou1[$x] = $wKoteihyouEX[$y];
					if ($y == $Aki)
						$n = $x;
					$x++;
					$y++;
				}
				$y = $y + $wWakuPM6col + $wWakuPM4col + $wWakuPM3col + $wWakuPM2col + $wWakuPM1col + $wWakuAM4col + $wWakuAM3col + $wWakuAM2col + $wWakuAMcol;
			}
			$x = $x + $WakuPM6CEL + $WakuPM4CEL + $WakuPM3CEL + $WakuPM2CEL + $WakuPM1CEL + $WakuAM4CEL + $WakuAM3CEL + $WakuAM2CEL + $WakuAMCEL;
		}
	}


	//PM6
	if ($wWakuPattern == 9) { //10枠
		$x = $WakuAMCEL + $WakuAM2CEL + $WakuAM3CEL + $WakuAM4CEL + $WakuPM1CEL + $WakuPM2CEL + $WakuPM3CEL + $WakuPM4CEL + $WakuPM5CEL;
		$y = $wWakuAMcol + $wWakuAM2col + $wWakuAM3col + $wWakuAM4col + $wWakuPM1col + $wWakuPM2col + $wWakuPM3col + $wWakuPM4col + $wWakuPM5col;
		for ($i = 0; $i < $SenyuDateCnt; $i++) {
			for ($j = 0; $j < $wHansu; $j++) {
				for ($k = 0; $k < $wWakuPM6col; $k++) {
					$wKoteihyou1[$x] = $wKoteihyouEX[$y];
					if ($y == $Aki)
						$n = $x;
					$x++;
					$y++;
				}
				$y = $y + $wWakuPM5col + $wWakuPM4col + $wWakuPM3col + $wWakuPM2col + $wWakuPM1col + $wWakuAM4col + $wWakuAM3col + $wWakuAM2col + $wWakuAMcol;
			}
			$x = $x + $WakuPM5CEL + $WakuPM4CEL + $WakuPM3CEL + $WakuPM2CEL + $WakuPM1CEL + $WakuAM4CEL + $WakuAM3CEL + $WakuAM2CEL + $WakuAMCEL;
		}
	}


	ksort($wKoteihyou1);

	for ($i = 0; $i < count($wKoteihyou1); $i++) {
		if (ctype_digit($wKoteihyou1[$i])) {
			$LastRoom = $i;
		}
	}

	$l = 0;
	for ($i = 0; $i < count($wKoteihyou1); $i++) {
		/*		if($wKoteihyou1[$i]==$wKoteihyouEX[$Aki]){
				$n=$i;
			}
	*/
		if ($n <= $i) {
			if (ctype_digit($wKoteihyou1[$i]) && $l == 0) {
				$wKoteihyou2 = $wKoteihyou1[$n];
				$wKoteihyouEX[$n] = "空き";
				for ($j = 0; $j < count($wKaiRoom3); $j++) {
					if ($wKaiRoom3[$j] == $wKoteihyou2) {
						$Room = $j - 1;
					}
				}
				$wKoteihyou1[$i] = "空き";
				$Room++;
				$l++;
			} elseif (ctype_digit($wKoteihyou1[$i])) {
				if ($wKaiRoom3[$Room]) {
					$wKoteihyou1[$i] = $wKaiRoom3[$Room];
					$Room++;
				} else {
					$wKoteihyou1[$i] = "空き";
					$Room++;
				}
			} elseif ($LastRoom < $i && isset($wKaiRoom3[$Room]) && $wKoteihyou1[$i] == "空き" && $l == 1) {
				$wKoteihyou1[$i] = $wKaiRoom3[$Room];
				$Room++;
				$l++;
			}
		}
	}



	$x = 0;
	$y = 0;
	for ($i = 0; $i < $SenyuDateCnt; $i++) {
		for ($j = 0; $j < $wHansu; $j++) {
			for ($k = 0; $k < $wWakuAMcol; $k++) {
				$wKoteihyouEX[$y] = $wKoteihyou1[$x];
				if ($y == $Aki)
					$n = $x;
				$x++;
				$y++;
			}
			if ($wWakuPattern > 2 and $wWakuPattern <= 7) { //3枠
				$y = $y + $wWakuPM1col + $wWakuPM2col;
			} elseif ($wWakuPattern == 8) { //5枠
				$y = $y + $wWakuAM2col + $wWakuPM1col + $wWakuPM2col + $wWakuPM3col;
			} elseif ($wWakuPattern == 9) { //10枠
				$y = $y + $wWakuAM2col + $wWakuAM3col + $wWakuAM4col + $wWakuPM1col + $wWakuPM2col + $wWakuPM3col + $wWakuPM4col + $wWakuPM5col + $wWakuPM6col;
			} else {
				$y = $y + $wWakuPM1col;
			}
		}
		if ($wWakuPattern > 2 and $wWakuPattern <= 7) { //3枠
			$x = $x + $WakuPM1CEL + $WakuPM2CEL;
		} elseif ($wWakuPattern == 8) { //5枠
			$x = $x + $WakuAM2CEL + $WakuPM1CEL + $WakuPM2CEL + $WakuPM3CEL;
		} elseif ($wWakuPattern == 9) { //10枠
			$x = $x + $WakuAM2CEL + $WakuAM3CEL + $WakuAM4CEL + $WakuPM1CEL + $WakuPM2CEL + $WakuPM3CEL + $WakuPM4CEL + $WakuPM5CEL + $WakuPM6CEL;
		} else {
			$x = $x + $WakuPM1CEL;
		}
	}


	//AM2
	if ($wWakuPattern == 8) { //5枠
		$x = $WakuAMCEL;
		$y = $wWakuAMcol;
		for ($i = 0; $i < $SenyuDateCnt; $i++) {
			for ($j = 0; $j < $wHansu; $j++) {
				for ($k = 0; $k < $wWakuAM2col; $k++) {
					$wKoteihyouEX[$y] = $wKoteihyou1[$x];
					if ($y == $Aki)
						$n = $x;
					$x++;
					$y++;
				}
				$y = $y + $wWakuPM3col + $wWakuPM2col + $wWakuPM1col + $wWakuAMcol;
			}
			$x = $x + $WakuPM3CEL + $WakuPM2CEL + $WakuPM1CEL + $WakuAMCEL;
		}
	} elseif ($wWakuPattern == 9) { //10枠
		$x = $WakuAMCEL;
		$y = $wWakuAMcol;
		for ($i = 0; $i < $SenyuDateCnt; $i++) {
			for ($j = 0; $j < $wHansu; $j++) {
				for ($k = 0; $k < $wWakuAM2col; $k++) {
					$wKoteihyouEX[$y] = $wKoteihyou1[$x];
					if ($y == $Aki)
						$n = $x;
					$x++;
					$y++;
				}
				$y = $y + $wWakuPM6col + $wWakuPM5col + $wWakuPM4col + $wWakuPM3col + $wWakuPM2col + $wWakuPM1col + $wWakuAM4col + $wWakuAM3col + $wWakuAMcol;
			}
			$x = $x + $WakuPM6CEL + $WakuPM5CEL + $WakuPM4CEL + $WakuPM3CEL + $WakuPM2CEL + $WakuPM1CEL + $WakuAM4CEL + $WakuAM3CEL + $WakuAMCEL;
		}
	}


	//AM3
	if ($wWakuPattern == 9) {
		$x = $WakuAMCEL + $WakuAM2CEL;
		$y = $wWakuAMcol + $wWakuAM2col;
		for ($i = 0; $i < $SenyuDateCnt; $i++) {
			for ($j = 0; $j < $wHansu; $j++) {
				for ($k = 0; $k < $wWakuAM3col; $k++) {
					$wKoteihyouEX[$y] = $wKoteihyou1[$x];
					if ($y == $Aki)
						$n = $x;
					$x++;
					$y++;
				}
				$y = $y + $wWakuPM6col + $wWakuPM5col + $wWakuPM4col + $wWakuPM3col + $wWakuPM2col + $wWakuPM1col + $wWakuAM4col + $wWakuAM2col + $wWakuAMcol;
			}
			$x = $x + $WakuPM6CEL + $WakuPM5CEL + $WakuPM4CEL + $WakuPM3CEL + $WakuPM2CEL + $WakuPM1CEL + $WakuAM4CEL + $WakuAM2CEL + $WakuAMCEL;
		}
	}



	//AM4
	if ($wWakuPattern == 9) { //10枠
		$x = $WakuAMCEL + $WakuAM2CEL + $WakuAM3CEL;
		$y = $wWakuAMcol + $wWakuAM2col + $wWakuAM3col;
		for ($i = 0; $i < $SenyuDateCnt; $i++) {
			for ($j = 0; $j < $wHansu; $j++) {
				for ($k = 0; $k < $wWakuAM4col; $k++) {
					$wKoteihyouEX[$y] = $wKoteihyou1[$x];
					if ($y == $Aki)
						$n = $x;
					$x++;
					$y++;
				}
				$y = $y + $wWakuPM6col + $wWakuPM5col + $wWakuPM4col + $wWakuPM3col + $wWakuPM2col + $wWakuPM1col + $wWakuAM3col + $wWakuAM2col + $wWakuAMcol;
			}
			$x = $x + $WakuPM6CEL + $WakuPM5CEL + $WakuPM4CEL + $WakuPM3CEL + $WakuPM2CEL + $WakuPM1CEL + $WakuAM3CEL + $WakuAM2CEL + $WakuAMCEL;
		}
	}


	//PM1
	if ($wWakuPattern > 2 and $wWakuPattern <= 7) { //3枠
		$x = $WakuAMCEL;
		$y = $wWakuAMcol;
		for ($i = 0; $i < $SenyuDateCnt; $i++) {
			for ($j = 0; $j < $wHansu; $j++) {
				for ($k = 0; $k < $wWakuPM1col; $k++) {
					$wKoteihyouEX[$y] = $wKoteihyou1[$x];

					if ($y == $Aki)
						$n = $x;
					$x++;
					$y++;
				}
				$y = $y + $wWakuPM2col + $wWakuAMcol;
			}
			$x = $x + $WakuPM2CEL + $WakuAMCEL;
		}
	} elseif ($wWakuPattern <= 2) { //2枠
		$x = $WakuAMCEL;
		$y = $wWakuAMcol;
		for ($i = 0; $i < $SenyuDateCnt; $i++) {
			for ($j = 0; $j < $wHansu; $j++) {
				for ($k = 0; $k < $wWakuPM1col; $k++) {
					$wKoteihyouEX[$y] = $wKoteihyou1[$x];

					if ($y == $Aki)
						$n = $x;
					$x++;
					$y++;
				}
				$y = $y + $wWakuAMcol;
			}
			$x = $x + $WakuAMCEL;
		}
	} elseif ($wWakuPattern == 8) { //5枠
		$x = $WakuAMCEL + $WakuAM2CEL;
		$y = $wWakuAMcol + $wWakuAM2col;
		for ($i = 0; $i < $SenyuDateCnt; $i++) {
			for ($j = 0; $j < $wHansu; $j++) {
				for ($k = 0; $k < $wWakuPM1col; $k++) {
					$wKoteihyouEX[$y] = $wKoteihyou1[$x];
					if ($y == $Aki)
						$n = $x;
					$x++;
					$y++;
				}
				$y = $y + $wWakuPM3col + $wWakuPM2col + $wWakuAM2col + $wWakuAMcol;
			}
			$x = $x + $WakuPM3CEL + $WakuPM2CEL + $WakuAM2CEL + $WakuAMCEL;
		}
	} elseif ($wWakuPattern == 9) { //10枠
		$x = $WakuAMCEL + $WakuAM2CEL + $WakuAM3CEL + $WakuAM4CEL;
		$y = $wWakuAMcol + $wWakuAM2col + $wWakuAM3col + $wWakuAM4col;
		for ($i = 0; $i < $SenyuDateCnt; $i++) {
			for ($j = 0; $j < $wHansu; $j++) {
				for ($k = 0; $k < $wWakuPM1col; $k++) {
					$wKoteihyouEX[$y] = $wKoteihyou1[$x];
					if ($y == $Aki)
						$n = $x;
					$x++;
					$y++;
				}
				$y = $y + $wWakuPM6col + $wWakuPM5col + $wWakuPM4col + $wWakuPM3col  + $wWakuPM2col + $wWakuAM4col + $wWakuAM3col + $wWakuAM2col + $wWakuAMcol;
			}
			$x = $x + $WakuPM6CEL + $WakuPM5CEL + $WakuPM4CEL + $WakuPM3CEL + $WakuPM2CEL + $WakuAM4CEL + $WakuAM3CEL + $WakuAM2CEL + $WakuAMCEL;
		}
	}



	//PM2
	if ($wWakuPattern > 2 and $wWakuPattern <= 7) { //3枠
		$x = $WakuAMCEL + $WakuPM1CEL;
		$y = $wWakuAMcol + $wWakuPM1col;
		for ($i = 0; $i < $SenyuDateCnt; $i++) {
			for ($j = 0; $j < $wHansu; $j++) {
				for ($k = 0; $k < $wWakuPM2col; $k++) {
					$wKoteihyouEX[$y] = $wKoteihyou1[$x];

					if ($y == $Aki)
						$n = $x;
					$x++;
					$y++;
				}
				$y = $y + $wWakuPM1col + $wWakuAMcol;
			}
			$x = $x + $WakuPM1CEL + $WakuAMCEL;
		}
	} elseif ($wWakuPattern == 8) { //5枠
		$x = $WakuAMCEL + $WakuAM2CEL + $WakuPM1CEL;
		$y = $wWakuAMcol + $wWakuAM2col + $wWakuPM1col;
		for ($i = 0; $i < $SenyuDateCnt; $i++) {
			for ($j = 0; $j < $wHansu; $j++) {
				for ($k = 0; $k < $wWakuPM2col; $k++) {
					$wKoteihyouEX[$y] = $wKoteihyou1[$x];
					if ($y == $Aki)
						$n = $x;
					$x++;
					$y++;
				}
				$y = $y + $wWakuPM3col + $wWakuPM1col + $wWakuAM2col + $wWakuAMcol;
			}
			$x = $x + $WakuPM3CEL + $WakuPM1CEL + $WakuAM2CEL + $WakuAMCEL;
		}
	} elseif ($wWakuPattern == 9) { //10枠
		$x = $WakuAMCEL + $WakuAM2CEL + $WakuAM3CEL + $WakuAM4CEL + $WakuPM1CEL;
		$y = $wWakuAMcol + $wWakuAM2col + $wWakuAM3col + $wWakuAM4col + $wWakuPM1col;
		for ($i = 0; $i < $SenyuDateCnt; $i++) {
			for ($j = 0; $j < $wHansu; $j++) {
				for ($k = 0; $k < $wWakuPM2col; $k++) {
					$wKoteihyouEX[$y] = $wKoteihyou1[$x];
					if ($y == $Aki)
						$n = $x;
					$x++;
					$y++;
				}
				$y = $y + $wWakuPM6col + $wWakuPM5col + $wWakuPM4col + $wWakuPM3col  + $wWakuPM1col + $wWakuAM4col + $wWakuAM3col + $wWakuAM2col + $wWakuAMcol;
			}
			$x = $x + $WakuPM6CEL + $WakuPM5CEL + $WakuPM4CEL + $WakuPM3CEL + $WakuPM1CEL + $WakuAM4CEL + $WakuAM3CEL + $WakuAM2CEL + $WakuAMCEL;
		}
	}


	//PM3
	if ($wWakuPattern == 8) { //5枠
		$x = $WakuAMCEL + $WakuAM2CEL + $WakuPM1CEL + $WakuPM2CEL;
		$y = $wWakuAMcol + $wWakuAM2col + $wWakuPM1col + $wWakuPM2col;
		for ($i = 0; $i < $SenyuDateCnt; $i++) {
			for ($j = 0; $j < $wHansu; $j++) {
				for ($k = 0; $k < $wWakuPM3col; $k++) {
					$wKoteihyouEX[$y] = $wKoteihyou1[$x];
					if ($y == $Aki)
						$n = $x;
					$x++;
					$y++;
				}
				$y = $y + $wWakuPM2col + $wWakuPM1col + $wWakuAM2col + $wWakuAMcol;
			}
			$x = $x + $WakuPM2CEL + $WakuPM1CEL + $WakuAM2CEL + $WakuAMCEL;
		}
	} elseif ($wWakuPattern == 9) { //10枠
		$x = $WakuAMCEL + $WakuAM2CEL + $WakuAM3CEL + $WakuAM4CEL + $WakuPM1CEL + $WakuPM2CEL;
		$y = $wWakuAMcol + $wWakuAM2col + $wWakuAM3col + $wWakuAM4col + $wWakuPM1col + $wWakuPM2col;
		for ($i = 0; $i < $SenyuDateCnt; $i++) {
			for ($j = 0; $j < $wHansu; $j++) {
				for ($k = 0; $k < $wWakuPM3col; $k++) {
					$wKoteihyouEX[$y] = $wKoteihyou1[$x];
					if ($y == $Aki)
						$n = $x;
					$x++;
					$y++;
				}
				$y = $y + $wWakuPM6col + $wWakuPM5col + $wWakuPM4col + $wWakuPM2col  + $wWakuPM1col + $wWakuAM4col + $wWakuAM3col + $wWakuAM2col + $wWakuAMcol;
			}
			$x = $x + $WakuPM6CEL + $WakuPM5CEL + $WakuPM4CEL + $WakuPM2CEL + $WakuPM1CEL + $WakuAM4CEL + $WakuAM3CEL + $WakuAM2CEL + $WakuAMCEL;
		}
	}


	//PM4
	if ($wWakuPattern == 9) { //10枠
		$x = $WakuAMCEL + $WakuAM2CEL + $WakuAM3CEL + $WakuAM4CEL + $WakuPM1CEL + $WakuPM2CEL + $WakuPM3CEL;
		$y = $wWakuAMcol + $wWakuAM2col + $wWakuAM3col + $wWakuAM4col + $wWakuPM1col + $wWakuPM2col + $wWakuPM3col;
		for ($i = 0; $i < $SenyuDateCnt; $i++) {
			for ($j = 0; $j < $wHansu; $j++) {
				for ($k = 0; $k < $wWakuPM4col; $k++) {
					$wKoteihyouEX[$y] = $wKoteihyou1[$x];
					if ($y == $Aki)
						$n = $x;
					$x++;
					$y++;
				}
				$y = $y + $wWakuPM6col + $wWakuPM5col + $wWakuPM3col + $wWakuPM2col + $wWakuPM1col + $wWakuAM4col + $wWakuAM3col + $wWakuAM2col + $wWakuAMcol;
			}
			$x = $x + $WakuPM6CEL + $WakuPM5CEL + $WakuPM3CEL + $WakuPM2CEL + $WakuPM1CEL + $WakuAM4CEL + $WakuAM3CEL + $WakuAM2CEL + $WakuAMCEL;
		}
	}


	//PM5
	if ($wWakuPattern == 9) { //10枠
		$x = $WakuAMCEL + $WakuAM2CEL + $WakuAM3CEL + $WakuAM4CEL + $WakuPM1CEL + $WakuPM2CEL + $WakuPM3CEL + $WakuPM4CEL;
		$y = $wWakuAMcol + $wWakuAM2col + $wWakuAM3col + $wWakuAM4col + $wWakuPM1col + $wWakuPM2col + $wWakuPM3col + $wWakuPM4col;
		for ($i = 0; $i < $SenyuDateCnt; $i++) {
			for ($j = 0; $j < $wHansu; $j++) {
				for ($k = 0; $k < $wWakuPM5col; $k++) {
					$wKoteihyouEX[$y] = $wKoteihyou1[$x];
					if ($y == $Aki)
						$n = $x;
					$x++;
					$y++;
				}
				$y = $y + $wWakuPM6col + $wWakuPM4col + $wWakuPM3col + $wWakuPM2col + $wWakuPM1col + $wWakuAM4col + $wWakuAM3col + $wWakuAM2col + $wWakuAMcol;
			}
			$x = $x + $WakuPM6CEL + $WakuPM4CEL + $WakuPM3CEL + $WakuPM2CEL + $WakuPM1CEL + $WakuAM4CEL + $WakuAM3CEL + $WakuAM2CEL + $WakuAMCEL;
		}
	}


	//PM6
	if ($wWakuPattern == 9) { //10枠
		$x = $WakuAMCEL + $WakuAM2CEL + $WakuAM3CEL + $WakuAM4CEL + $WakuPM1CEL + $WakuPM2CEL + $WakuPM3CEL + $WakuPM4CEL + $WakuPM5CEL;
		$y = $wWakuAMcol + $wWakuAM2col + $wWakuAM3col + $wWakuAM4col + $wWakuPM1col + $wWakuPM2col + $wWakuPM3col + $wWakuPM4col + $wWakuPM5col;
		for ($i = 0; $i < $SenyuDateCnt; $i++) {
			for ($j = 0; $j < $wHansu; $j++) {
				for ($k = 0; $k < $wWakuPM6col; $k++) {
					$wKoteihyouEX[$y] = $wKoteihyou1[$x];
					if ($y == $Aki)
						$n = $x;
					$x++;
					$y++;
				}
				$y = $y + $wWakuPM5col + $wWakuPM4col + $wWakuPM3col + $wWakuPM2col + $wWakuPM1col + $wWakuAM4col + $wWakuAM3col + $wWakuAM2col + $wWakuAMcol;
			}
			$x = $x + $WakuPM5CEL + $WakuPM4CEL + $WakuPM3CEL + $WakuPM2CEL + $WakuPM1CEL + $WakuAM4CEL + $WakuAM3CEL + $WakuAM2CEL + $WakuAMCEL;
		}
	}
}

########################################################
# 詳細工程表イメージ作成
########################################################
$wWakuAMcol = ceil(($wWakuAM) / $wHansu); #入力枠数を班数でわった切り上げた数

if ($wWakuPattern > 2 and $wWakuPattern <= 7) { //3枠
	$wWakuPM1col = ceil(($wWakuPM1) / $wHansu);
	$wWakuPM2col = ceil(($wWakuPM2) / $wHansu);
} elseif ($wWakuPattern == 8) { //5枠
	$wWakuAM2col = ceil(($wWakuAM2) / $wHansu);
	$wWakuPM1col = ceil(($wWakuPM1) / $wHansu);
	$wWakuPM2col = ceil(($wWakuPM2) / $wHansu);
	$wWakuPM3col = ceil(($wWakuPM3) / $wHansu);
} elseif ($wWakuPattern == 9) { //10枠
	$wWakuAM2col = ceil(($wWakuAM2) / $wHansu);
	$wWakuAM3col = ceil(($wWakuAM3) / $wHansu);
	$wWakuAM4col = ceil(($wWakuAM4) / $wHansu);
	$wWakuPM1col = ceil(($wWakuPM1) / $wHansu);
	$wWakuPM2col = ceil(($wWakuPM2) / $wHansu);
	$wWakuPM3col = ceil(($wWakuPM3) / $wHansu);
	$wWakuPM4col = ceil(($wWakuPM4) / $wHansu);
	$wWakuPM5col = ceil(($wWakuPM5) / $wHansu);
	$wWakuPM6col = ceil(($wWakuPM6) / $wHansu);
} else { //2枠のパターンの時
	$wWakuPM1col = ceil(($wWakuPM1) / $wHansu);
}
$Koteihyou = "<table border='3' width='600px' cellspacing='5' style='text-align:center' cellpadding='7' class='ex_table'><tr><td>日程</td><td class='ex_table2'>曜日</td><td colspan = " . $wWakuAMcol . " style='background-color:#ff7f50;' class='ex_table2'>AM</td>";
if ($wWakuPattern <= 2) { //2枠
	$Koteihyou .= "<td colspan = " . $wWakuPM1col . " style='background-color:#87cefa'  class='ex_table2'>PM</td></tr>";
	$wWakuPM2col = 0;
} elseif ($wWakuPattern == 8) { //5枠
	$Koteihyou .= "<td colspan = " . $wWakuAM2col . " style='background-color:#FFDDFF'  class='ex_table2'>AM2</td><td colspan = " . $wWakuPM1col . " style='background-color:#87cefa'  class='ex_table2'>PM1</td><td colspan = " . $wWakuPM2col . " style='background-color:#99FF66;' class='ex_table2'>PM2</td><td colspan = " . $wWakuPM3col . " style='background-color:#B384FF'  class='ex_table2'>PM3</td></tr>";
} elseif ($wWakuPattern == 9) { //10枠
	$Koteihyou .= "<td colspan = " . $wWakuAM2col . " style='background-color:#FFDDFF'  class='ex_table2'>AM2</td><td colspan = " . $wWakuAM3col . " style='background-color:#FF97C2'  class='ex_table2'>AM3</td><td colspan = " . $wWakuAM4col . " style='background-color:#D6FF58'  class='ex_table2'>AM4</td><td colspan = " . $wWakuPM1col . " style='background-color:#87cefa'  class='ex_table2'>PM1</td><td colspan = " . $wWakuPM2col . " style='background-color:#99FF66;' class='ex_table2'>PM2</td><td colspan = " . $wWakuPM3col . " style='background-color:#B384FF'  class='ex_table2'>PM3</td><td colspan = " . $wWakuPM4col . " style='background-color:#5D99FF'  class='ex_table2'>PM4</td><td colspan = " . $wWakuPM5col . " style='background-color:#77F9C3'  class='ex_table2'>PM5</td><td colspan = " . $wWakuPM6col . " style='background-color:#43FF6B'  class='ex_table2'>PM6</td></tr>";
} else { //3枠
	$Koteihyou .= "<td colspan = " . $wWakuPM1col . " style='background-color:#87cefa'  class='ex_table2'>PM1</td><td colspan = " . $wWakuPM2col . " style='background-color:#99FF66;' class='ex_table2'>PM2</td></tr>";
}

$date = new DateTime($SenyuStartDate);

$week = ['日', '月', '火', '水', '木', '金', '土'];
$m = 0;
for ($i = 0; $i < $SenyuDateCnt; $i++) {
	$SenyuDate	= $date->format('Y-m-d');
	$result 	= array_search($SenyuDate, $SHUKUJITULIST);
	$YoubiCD 	= $date->format('w');
	$Youbi 		= $week[$YoubiCD];
	if ($result !== false || $YoubiCD == 0 || $YoubiCD == 6) {
		$holidayCD = $i;
	}
	#★１休工日なら
	if (array_search($i, $wKyukobi) !== false && $wKyukobi[$i] !== "") {
		$Koteihyou .= "<tr><td rowspan =1" . $wShukujitucolor[$i] . " class='ex_table2'>" . $SenyuDate . "</td>";
		$Koteihyou .= "<td rowspan =1" . $wShukujitucolor[$i] . " class='ex_table2'>" . $Youbi . "</td>";
		$Koteihyou .= "<td colspan=";
		$Koteihyou .= $wWakuAMcol + $wWakuPM1col + $wWakuPM2col; #うまくつかって上部をつくる　あとで♪
		$Koteihyou .= " rowspan = 1 style='text-align:center;' class='ex_table2'> ";
		$Koteihyou .= "休工日";
		$Koteihyou .= "</td></tr>";

		#★１休工日でない場合
	} else {
		$Koteihyou .= "<tr class='trtop'><td rowspan =" . $wHansu . $wShukujitucolor[$i] . " class='ex_table2'>" . $SenyuDate . "</td>";
		$Koteihyou .= "<td rowspan =" . $wHansu . $wShukujitucolor[$i] . " class='ex_table2'>" . $Youbi . "</td>";

		#★２班数分くりかえす
		for ($j = 0; $j < $wHansu; $j++) {
			if ($j !== 0) #班が１つめなら<tr>つける
				$Koteihyou .= "<tr>";
			#★３AM枠数の班数で割った数分繰り返す
			//AM
			for ($k = 0; $k < $wWakuAMcol; $k++) {
				$Koteihyou .= "<td style='background-color:#ffefd5;'>";
				if ($wKoteihyouEX[$m] == "空き") {
					$Koteihyou .= "<font color='blue'>";
					$Koteihyou .= "<a href='#' onclick='moveandaki(" . $m . ")' >" . $wKoteihyouEX[$m] . "</a>";
					$Koteihyou .= "<input type='hidden' name='wwKoteihyouEX[]' value=" . $wKoteihyouEX[$m] . " >";
					$Koteihyou .= "</font>";
				} else {
					$Koteihyou .= "<a href='#' onclick='moveandroom(" . $m . ")' >" . $wKoteihyouEX[$m] . "</a>";
					$Koteihyou .= "<input type='hidden' name='wwKoteihyouEX[]' value=" . $wKoteihyouEX[$m] . " >";
				}
				$m++;
				$Koteihyou .= "</td>";
			}


			//AM2
			if ($wWakuPattern >= 8) {
				for ($k = 0; $k < $wWakuAM2col; $k++) {
					$Koteihyou .= "<td style='background-color:#FFEEFF;'>";
					if ($wKoteihyouEX[$m] == "空き") {
						$Koteihyou .= "<font color='blue'>";
						$Koteihyou .= "<a href='#' onclick='moveandaki(" . $m . ")' >" . $wKoteihyouEX[$m] . "</a>";
						$Koteihyou .= "<input type='hidden' name='wwKoteihyouEX[]' value=" . $wKoteihyouEX[$m] . " >";
						$Koteihyou .= "</font>";
					} else {
						$Koteihyou .= "<a href='#' onclick='moveandroom(" . $m . ")' >" . $wKoteihyouEX[$m] . "</a>";
						$Koteihyou .= "<input type='hidden' name='wwKoteihyouEX[]' value=" . $wKoteihyouEX[$m] . " >";
					}
					$m++;
					$Koteihyou .= "</td>";
				}
			}

			//AM3
			if ($wWakuPattern >= 8) {
				for ($k = 0; $k < $wWakuAM3col; $k++) {
					$Koteihyou .= "<td style='background-color:#FFD5EC'>";
					if ($wKoteihyouEX[$m] == "空き") {
						$Koteihyou .= "<font color='blue'>";
						$Koteihyou .= "<a href='#' onclick='moveandaki(" . $m . ")' >" . $wKoteihyouEX[$m] . "</a>";
						$Koteihyou .= "<input type='hidden' name='wwKoteihyouEX[]' value=" . $wKoteihyouEX[$m] . " >";
						$Koteihyou .= "</font>";
					} else {
						$Koteihyou .= "<a href='#' onclick='moveandroom(" . $m . ")' >" . $wKoteihyouEX[$m] . "</a>";
						$Koteihyou .= "<input type='hidden' name='wwKoteihyouEX[]' value=" . $wKoteihyouEX[$m] . " >";
					}
					$m++;
					$Koteihyou .= "</td>";
				}
			}


			//AM4
			if ($wWakuPattern >= 8) {
				for ($k = 0; $k < $wWakuAM4col; $k++) {
					$Koteihyou .= "<td style='background-color:#E9FFA5'>";
					if ($wKoteihyouEX[$m] == "空き") {
						$Koteihyou .= "<font color='blue'>";
						$Koteihyou .= "<a href='#' onclick='moveandaki(" . $m . ")' >" . $wKoteihyouEX[$m] . "</a>";
						$Koteihyou .= "<input type='hidden' name='wwKoteihyouEX[]' value=" . $wKoteihyouEX[$m] . " >";
						$Koteihyou .= "</font>";
					} else {
						$Koteihyou .= "<a href='#' onclick='moveandroom(" . $m . ")' >" . $wKoteihyouEX[$m] . "</a>";
						$Koteihyou .= "<input type='hidden' name='wwKoteihyouEX[]' value=" . $wKoteihyouEX[$m] . " >";
					}
					$m++;
					$Koteihyou .= "</td>";
				}
			}


			#★３-２PM１枠数を班数で割った数分繰り返す
			//PM1
			for ($k = 0; $k < $wWakuPM1col; $k++) {
				$Koteihyou .= "<td style='background-color:#d2e5ff;'>";
				if ($wKoteihyouEX[$m] == "空き") {
					$Koteihyou .= "<font color='blue'>";
					$Koteihyou .= "<a href='#' onclick='moveandaki(" . $m . ")' >" . $wKoteihyouEX[$m] . "</a>";
					$Koteihyou .= "<input type='hidden' name='wwKoteihyouEX[]' value=" . $wKoteihyouEX[$m] . " >";
					$Koteihyou .= "</font>";
				} else {
					$Koteihyou .= "<a href='#' onclick='moveandroom(" . $m . ")' >" . $wKoteihyouEX[$m] . "</a>";
					$Koteihyou .= "<input type='hidden' name='wwKoteihyouEX[]' value=" . $wKoteihyouEX[$m] . " >";
				}
				$m++;
				$Koteihyou .= "</td>";
			}

			#★３-３PM２枠数を班数で割った数分繰り返す
			//PM2
			if ($wWakuPattern > 2) {
				for ($k = 0; $k < $wWakuPM2col; $k++) {
					$Koteihyou .= "<td style='background-color:#d1f9b7'>";
					if ($wKoteihyouEX[$m] == "空き") {
						$Koteihyou .= "<font color='blue'>";
						$Koteihyou .= "<a href='#' onclick='moveandaki(" . $m . ")' >" . $wKoteihyouEX[$m] . "</a>";
						$Koteihyou .= "<input type='hidden' name='wwKoteihyouEX[]' value=" . $wKoteihyouEX[$m] . " >";
						$Koteihyou .= "</font>";
					} else {
						$Koteihyou .= "<a href='#' onclick='moveandroom(" . $m . ")' >" . $wKoteihyouEX[$m] . "</a>";
						$Koteihyou .= "<input type='hidden' name='wwKoteihyouEX[]' value=" . $wKoteihyouEX[$m] . " >";
					}
					$m++;
					$Koteihyou .= "</td>";
				}
			}


			//PM3
			if ($wWakuPattern >= 8) {
				for ($k = 0; $k < $wWakuPM3col; $k++) {
					$Koteihyou .= "<td style='background-color:#DCC2FF'>";
					if ($wKoteihyouEX[$m] == "空き") {
						$Koteihyou .= "<font color='blue'>";
						$Koteihyou .= "<a href='#' onclick='moveandaki(" . $m . ")' >" . $wKoteihyouEX[$m] . "</a>";
						$Koteihyou .= "<input type='hidden' name='wwKoteihyouEX[]' value=" . $wKoteihyouEX[$m] . " >";
						$Koteihyou .= "</font>";
					} else {
						$Koteihyou .= "<a href='#' onclick='moveandroom(" . $m . ")' >" . $wKoteihyouEX[$m] . "</a>";
						$Koteihyou .= "<input type='hidden' name='wwKoteihyouEX[]' value=" . $wKoteihyouEX[$m] . " >";
					}
					$m++;
					$Koteihyou .= "</td>";
				}
			}


			//PM4
			if ($wWakuPattern == 9) {
				for ($k = 0; $k < $wWakuPM4col; $k++) {
					$Koteihyou .= "<td style='background-color:#BAD3FF'>";
					if ($wKoteihyouEX[$m] == "空き") {
						$Koteihyou .= "<font color='blue'>";
						$Koteihyou .= "<a href='#' onclick='moveandaki(" . $m . ")' >" . $wKoteihyouEX[$m] . "</a>";
						$Koteihyou .= "<input type='hidden' name='wwKoteihyouEX[]' value=" . $wKoteihyouEX[$m] . " >";
						$Koteihyou .= "</font>";
					} else {
						$Koteihyou .= "<a href='#' onclick='moveandroom(" . $m . ")' >" . $wKoteihyouEX[$m] . "</a>";
						$Koteihyou .= "<input type='hidden' name='wwKoteihyouEX[]' value=" . $wKoteihyouEX[$m] . " >";
					}
					$m++;
					$Koteihyou .= "</td>";
				}
			}


			//PM5
			if ($wWakuPattern == 9) {
				for ($k = 0; $k < $wWakuPM5col; $k++) {
					$Koteihyou .= "<td style='background-color:#CEF9DC'>";
					if ($wKoteihyouEX[$m] == "空き") {
						$Koteihyou .= "<font color='blue'>";
						$Koteihyou .= "<a href='#' onclick='moveandaki(" . $m . ")' >" . $wKoteihyouEX[$m] . "</a>";
						$Koteihyou .= "<input type='hidden' name='wwKoteihyouEX[]' value=" . $wKoteihyouEX[$m] . " >";
						$Koteihyou .= "</font>";
					} else {
						$Koteihyou .= "<a href='#' onclick='moveandroom(" . $m . ")' >" . $wKoteihyouEX[$m] . "</a>";
						$Koteihyou .= "<input type='hidden' name='wwKoteihyouEX[]' value=" . $wKoteihyouEX[$m] . " >";
					}
					$m++;
					$Koteihyou .= "</td>";
				}
			}


			//PM6
			if ($wWakuPattern == 9) {
				for ($k = 0; $k < $wWakuPM6col; $k++) {
					$Koteihyou .= "<td style='background-color:#AEFFBD'>";
					if ($wKoteihyouEX[$m] == "空き") {
						$Koteihyou .= "<font color='blue'>";
						$Koteihyou .= "<a href='#' onclick='moveandaki(" . $m . ")' >" . $wKoteihyouEX[$m] . "</a>";
						$Koteihyou .= "<input type='hidden' name='wwKoteihyouEX[]' value=" . $wKoteihyouEX[$m] . " >";
						$Koteihyou .= "</font>";
					} else {
						$Koteihyou .= "<a href='#' onclick='moveandroom(" . $m . ")' >" . $wKoteihyouEX[$m] . "</a>";
						$Koteihyou .= "<input type='hidden' name='wwKoteihyouEX[]' value=" . $wKoteihyouEX[$m] . " >";
					}
					$m++;
					$Koteihyou .= "</td>";
				}
			}


			if ($j !== 0)
				$Koteihyou .= "</tr>";
		} #★２班数分繰り返すEnd

	}
	$date->modify('+1 days');
	$Koteihyou .= "</tr>";
}
$Koteihyou .= "</table>";
for ($i = 0; $i < count($wKaiRoom3); $i++) {
	if (array_search($wKaiRoom3[$i], $wKoteihyouEX) === false) {
		if ($ShortageRoom == "") {
			$ShortageRoom = "　" . $wKaiRoom3[$i];
		} else {
			$ShortageRoom .= "、" . $wKaiRoom3[$i];
		}
	}
}
if (!$ShortageRoom) {
	$IfShortage 		= FALSE;
	$IfNotShortage 		= TRUE;
	$SakuseiDisabled 	= "";
} else {
	$IfShortage 		= TRUE;
	$IfNotShortage 		= FALSE;
	$SakuseiDisabled	= 'disabled';
}
$wShukujitucolor	= SPFWTools::encodePluralValue($wShukujitucolor);
$wKaiRoom3 			= SPFWTools::encodePluralValue($wKaiRoom3);
$wKyukobi 			= SPFWTools::encodePluralValue($wKyukobi);

########################################################
# コンテンツ表示
########################################################

$CNT_FILE = "s_make_kanryo_hensyuhayashi.tpl";

$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier);
$HiddenValues = $myTemplate->getValuesToPass();

$myTemplate->convertTags();
$myTemplate->outputTemplate();

unset($myTemplate);
unset($myLog);
