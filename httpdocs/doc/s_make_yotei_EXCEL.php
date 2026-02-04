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
		showSorryPage(_ILLEGAL_ACCESS);

	if (!$myUser->doAuthenticationByRegistKey($rKey)) 
		trigger_error("doAuthentication Failed.", E_USER_ERROR);

	if ($myUser->UserCD == -1) 
		showSorryPage(_ILLEGAL_ACCESS);

	$UserCD = $myUser->UserCD;
	$ID = $myUser->ID;
	unset($myUser);


	########################################################
	# 設定パラメータ取得取得
	########################################################
	$editBukkenCD = SPFWParameter::getValues('editBukkenCD');

	$myIraiRenkei = new IraiRenkei($myDB);

	if (!$myIraiRenkei->executeSelect("BukkenCD = " . $editBukkenCD, "") ){
		$ErrorString = array();
		$ErrorString[] = "tIraiRenkeiF情報の抽出に失敗しました。";
		showAdminSorryPage($ErrorString);
	}
	$SenyuStartDate = $myIraiRenkei->SenyuStartDate ;
#$SenyuStartDate = "2018-10-05";
#echo $SenyuStartDate;
	$SenyuEndDate = $myIraiRenkei->SenyuEndDate ;
	$SenyuDateCnt = ( strtotime( $SenyuEndDate ) -  strtotime( $SenyuStartDate )) /86400 ;#専有部日数


#echo "<br>63行目".$SenyuEndDate."---".$SenyuStartDate ;
	$myBukkenMatrix = new BukkenMatrix($myDB);

	if (!$myBukkenMatrix->executeSelect("BukkenCD > 0 AND MukouFlg = FALSE AND BukkenCD = ".$editBukkenCD , ""))
		trigger_error("Getting myBukkenMatrix Failed.", E_USER_ERROR);

	$wKaiRoom = $myBukkenMatrix->KaiRoom;
	$KaiRoom = SPFWTools::decodePluralValue($wKaiRoom); #配列

	unset($myBukkenMatrix);


#print_r( $KaiRoom ); ;
	$myBukken = new Bukken($myDB);

	if (!$myBukken->executeSelect("BukkenCD > 0 AND MukouFlg = FALSE AND BukkenCD = ".$editBukkenCD , ""))
		trigger_error("Getting tFileF Failed.", E_USER_ERROR);

	$BukkenName = $myBukken->BukkenName;
	unset($myBukken);


	$wHansu = SPFWParameter::getValues('wHansu');
	$wMinuteTime = SPFWParameter::getValues('wMinuteTime');
	$wWakuPattern = SPFWParameter::getValues('wWakuPattern');
	$wKojijun = SPFWParameter::getValues('wKojijun');
	$wFirstDateFeature = SPFWParameter::getValues('wFirstDateFeature');
	$wHoliday1 = SPFWParameter::getValues('wHoliday1');
	$wHoliday2 = SPFWParameter::getValues('wHoliday2');
	$wHoliday3 = SPFWParameter::getValues('wHoliday3');
	$wHoliday4 = SPFWParameter::getValues('wHoliday4');

	########################################################
	# 作成準備
	########################################################
#print_r($SHUKUJITULIST);
	// 返り値：trueなら休日
	function checkHoliday($date, $SHUKUJITULIST){
	    $w = intval(date('w', strtotime($date)));
		if( $w === 0 || $w === 6 ){
	 		return TRUE ;
		}else{
			#祝日判定
			if (in_array($date, $SHUKUJITULIST)) {
				return TRUE ;
			}else{
				return FALSE ;
			}
		}
	}


#工事枠を測る


#テスト用仮セット
$wMinuteTime = 60 ;#仮に60分とする
$wWakuPattern = 3;
$wHansu = 2;


for( $i = 0; $i < count( $WAKUPATTERN[$wWakuPattern]['EndTime'] ); $i++ ){
	#UseWaku　３－２－２
	$UseWaku[$i] =  ( strtotime( $WAKUPATTERN[$wWakuPattern]['EndTime'][$i] ) - strtotime( $WAKUPATTERN[$wWakuPattern]['StartTime'][$i] ) ) / ($wMinuteTime * 60) ;
	#echo "<br>128行目:".$UseWaku[$i];
}




$h_cnt = 0; // 部屋数カウント
for ($j = 0; $j < $SenyuDateCnt; $j++) { // 日数カウント

	$TargetDate = date( 'Y-m-d', strtotime( $SenyuStartDate ) + 86400 * $j );

	for($aa = 0; $aa < count($UseWaku); $aa++){ #0 ,1 
		$AMPM = $WAKUPATTERN[$wWakuPattern]['AMPM'][$aa];#AM , PM1

		for($Han = 0; $Han < $wHansu; $Han++){
			for($k = 0; $k < $UseWaku[$aa]; $k++){#3 $UseWaku[0]→６　$UseWaku[1]→４

				// 20181030 nomu add
				##最後の班から減らす
				##平日は午前・午後から-1、土日は午前・午後から-2
				if ($Han == $wHansu - 1) { // 最後の班

					if ( !checkHoliday($TargetDate, $SHUKUJITULIST) ) { // 平日
						if ($AMPM == 'AM' or ($aa == count($UseWaku) - 1) ) { // AMの場合、または最後の枠の場合(PM1・2があるなら2から減らすため)
							if ( $k < ($UseWaku[$aa] - 1) ) { // 平日は-1
								$KariDate[$TargetDate][$Han][$AMPM][] = $KaiRoom[$h_cnt];
								$h_cnt++;
							}
						} else {
							$KariDate[$TargetDate][$Han][$AMPM][] = $KaiRoom[$h_cnt];
							$h_cnt++;
						}

					} else { // 土日
						if ($AMPM == 'AM' or $AMPM == 'PM') { // AMの場合、または2枠のPMの場合
							if ( $k < ($UseWaku[$aa] - 2) ) { // -2する
								$KariDate[$TargetDate][$Han][$AMPM][] = $KaiRoom[$h_cnt];
								$h_cnt++;
							}
						} else if (count($WAKUPATTERN[$wWakuPattern]['AMPM']) > 2) { // AMでない、かつ3枠以上の場合
							if ( $k < ($UseWaku[$aa] - 1) ) { // -1する
								$KariDate[$TargetDate][$Han][$AMPM][] = $KaiRoom[$h_cnt];
								$h_cnt++;
							}
						} else {
							// ここはたぶん通らない
							//$KariDate[$TargetDate][$Han][$AMPM][] = $KaiRoom[$h_cnt];
						}
					}

				} else {
					$KariDate[$TargetDate][$Han][$AMPM][] = $KaiRoom[$h_cnt];
					$h_cnt++;
				}

				if ($h_cnt == count($KaiRoom)) {
					break 4;
				}

			}#3End UseWaku分部屋をセットするEnd
		}
	}
} #ここまでbreak抜ける



#print_r($KariDate);
#echo "<pre>";
#var_dump($KariDate);
#echo "</pre>";





	########################################################
	# Excelファイル生成
	########################################################
	require 'vendor/autoload.php';

	use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;

	$reader = new XlsxReader();
	$spreadsheet = $reader->load('./template/kotei_yotei1.xlsm'); // 予定①_日変　シート
#	$sheet = $spreadsheet->getSheetByName('weather'); //weatherシート取得
	$sheet = $spreadsheet->getActiveSheet();
	$sheet->setCellValue('B2', $BukkenName);
	$sheet->setCellValue('P3', date('Y年m月')." 吉日");




#参考URL　https://qiita.com/mosaxiv/items/d71890ef203e506b75fc
#$arrayData = [
#    [NULL, 2016, 2017, 2018],
#    ['Q1', 12, 15, 21],
#    ['Q2', 56, 73, 86],
#    ['Q3', 52, 61, 69],
#    ['Q4', 30, 32, 0],
#];
#$sheet->fromArray($arrayData, NULL, 'C3');


	$WeekArray = array("日","月","火","水","木","金","土");

	$KariDate = array_merge($KariDate); // キーを詰める

	$cell_s = 9;
	foreach ($KariDate as $key1 => $kojiday) { // 工事日
		#echo "<br>".$key1;
		$kojiday_str = substr($key1, 5, 2)."月".substr($key1, 8, 2)."日";
		$kojiweek_str = $WeekArray[intval(date('w', strtotime($key1)))]."曜日";

		foreach ($kojiday as $key2 => $kojihan) { // 工事班
			#echo "_".$key2;

			foreach ($kojihan as $key3 => $kojiampm) { // AMPM
				#echo "_".$key3;

				foreach ($WAKUPATTERN[$wWakuPattern]['AMPM'] as $key => $val) { // 工事時間の取得
					if ($key3 == $val) {
						$ampm_soeji = $key;
						break;
					}
				}
				$kojitime_str = $WAKUPATTERN[$wWakuPattern]['StartTime'][$ampm_soeji]."～".$WAKUPATTERN[$wWakuPattern]['EndTime'][$ampm_soeji];

				foreach ($kojiampm as $key4 => $kojiheya) { // 部屋
					#echo "-".$kojiheya;

					$sheet->setCellValue('A'.$cell_s, $kojiheya); // 部屋
					$sheet->setCellValue('E'.$cell_s, $kojiday_str); // 工事日
					$sheet->setCellValue('F'.$cell_s, $kojiweek_str); // 曜日
					$sheet->setCellValue('G'.$cell_s, $kojitime_str); // 時間
#					$sheet->setCellValue('O'.$cell_s, $kojiheya); // ユーザー名

#					$kojipass = substr(str_shuffle('1234567890abcfgxyz'), 0, 6);
#					$sheet->setCellValue('P'.$cell_s, $kojipass); // パスワード

					$cell_s = $cell_s + 1;

				}
			}
		}

	}



	//ダウンロード用
	//MIMEタイプ：https://technet.microsoft.com/ja-jp/ee309278.aspx
	header("Content-Description: File Transfer");
	$wFileName = "予定案内".date('Ymd').".xlsm" ;
	header("Content-Disposition: attachment; filename=".$wFileName );
	#header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); // xlsx
	header('Content-Type: application/vnd.ms-excel.sheet.macroEnabled.12'); // xlsm
	header('Content-Transfer-Encoding: binary');
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	header('Expires: 0');
	ob_end_clean(); //バッファ消去

	$writer = new XlsxWriter($spreadsheet);
	$writer->save('php://output');



/*

	########################################################
	# コンテンツ表示
	########################################################

	$CNT_FILE = "s_make_schedule.tpl";

	$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier);
	$HiddenValues = $myTemplate->getValuesToPass();

	$myTemplate->convertTags();
	$myTemplate->outputTemplate();

	unset($myTemplate);
	unset($myLog);
*/

?>
