<?php

	/* 
	* s_zentaikoteihyo_Excel.php
	* 全体工程表出力
	*  ※エクセル内の「条件付き書式」を利用しています。
	*/

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
	include_once _CLS_DIR . "SPUSKoji.cls";
	include_once _CLS_DIR . "SPUSFile.cls";

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

	$loginUserCD = $myUser->UserCD;
	$ID = $myUser->ID;
	unset($myUser);

	########################################################
	# パラメータ取得
	########################################################

	$editBukkenCD = SPFWParameter::getValues('editBukkenCD');

	$wAnnaiDate = SPFWParameter::getValues('wAnnaiDate');			#予定案内配布日
	$wReceptionDate = SPFWParameter::getValues('wReceptionDate');	#受付締日
	$wKakuteiDate = SPFWParameter::getValues('wKakuteiDate');		#確定案内配布日

	$wKyoyoStartDate = SPFWParameter::getValues('wKyoyoStartDate');	#共用部開始日
	$wKyoyoEndDate = SPFWParameter::getValues('wKyoyoEndDate');		#共用部終了日
	$wSenyuStartDate = SPFWParameter::getValues('wSenyuStartDate');	#専有部開始日
	$wSenyuEndDate = SPFWParameter::getValues('wSenyuEndDate');		#専有部終了日

	$Holiday = SPFWParameter::getValues('Holiday');				#休工日①
	$wHoliday = SPFWTools::decodePluralValue($Holiday);				#配列
	//var_dump($wHoliday);

	$wKojiBiko = SPFWParameter::getValues('wKojiBiko');			#備考欄

	// その他、工程表に記載する日程 5項目
	for($i = 6; $i < 11; $i++){
		${"wDateS".$i} = SPFWParameter::getValues('wDateS'.$i);
		${"wDateE".$i} = SPFWParameter::getValues('wDateE'.$i);
		${"wOtherwise".$i} = SPFWParameter::getValues('wOtherwise'.$i);
		${"wOtherwise".$i."Flg"} = SPFWParameter::getValues('wOtherwise'.$i.'Flg');
	}

	// 入力チェック
	$ErrorStrings = array();
	if($wAnnaiDate == "")
		$ErrorStrings[] = "案内配布日を設定してください。";
	if($wReceptionDate == "")
		$ErrorStrings[] = "受付締切日を設定してください。";
	if($wKakuteiDate == "")
		$ErrorStrings[] = "確定案内配布日を設定してください。";
	/*
	if($wKyoyoStartDate == "")
		$ErrorStrings[] = "共用部開始日を設定してください。";
	*/
	if($wSenyuStartDate == "")
		$ErrorStrings[] = "専有部開始日を設定してください。";

	$ErrorLoop = count($ErrorStrings);
	if($ErrorLoop > 0){
		$IfError = TRUE;
		include_once("s_zentaikoteihyo_form.php");
		exit;
	}


	########################################################
	# 物件名取得
	########################################################

	$myBukken = new Bukken($myDB);

	if (!$myBukken->executeSelect("BukkenCD = ".$editBukkenCD , ""))
		trigger_error("Getting tFileF Failed.", E_USER_ERROR);

	$BukkenName = $myBukken->BukkenName;

	unset($myBukken);

	########################################################
	# 工事情報取得・更新
	########################################################

	$myKoji = new Koji($myDB);

	if (!$myKoji->executeSelect("BukkenCD = " . $editBukkenCD, "") ){
		trigger_error("Getting myKoji Failed.", E_USER_ERROR);
	}

	if ($myKoji->RecCnt != 1) {

		#工事情報登録がまだ
		$ErrorString = array();
		$ErrorString[] = "工事情報を登録してください。";
		$ErrorLoop = count($ErrorString);
		$myTemplate = new SPFWTemplate(_ERROR_TPL, $MyCarrier, TRUE);
		exit;
	}

	$KojiName = $myKoji->KojiName; // 工事名称

	//$ZentaiStartDate = $myKoji->ZentaiStartDate; // 全体工期 開始
	//$ZentaiEndDate = $myKoji->ZentaiEndDate; // 全体工期 終了
	
	$myKoji->AnnaiDate = $wAnnaiDate;
	$myKoji->ReceptionDate = $wReceptionDate;
	$myKoji->KakuteiDate = $wKakuteiDate;
	/*
	$myKoji->KyoyoStartDate = str_replace( "/","" ,$wKyoyoStartDate ) ;
	$myKoji->KyoyoEndDate = $wKyoyoEndDate;
	$myKoji->SenyuStartDate = $wSenyuStartDate;
	$myKoji->SenyuEndDate = str_replace( "/","" ,$wSenyuEndDate ) ;
	*/
	$myKoji->Otherwise6 = $wOtherwise6;
	$myKoji->Otherwise6Flg = $wOtherwise6Flg;
	$myKoji->DateS6 = $wDateS6;
	$myKoji->DateE6 = $wDateE6;

	$myKoji->Otherwise7 = $wOtherwise7;
	$myKoji->Otherwise7Flg = $wOtherwise7Flg;
	$myKoji->DateS7 = $wDateS7;
	$myKoji->DateE7 = $wDateE7;

	$myKoji->Otherwise8 = $wOtherwise8;
	$myKoji->Otherwise8Flg = $wOtherwise8Flg;
	$myKoji->DateS8 = $wDateS8;
	$myKoji->DateE8 = $wDateE8;

	$myKoji->Otherwise9 = $wOtherwise9;
	$myKoji->Otherwise9Flg = $wOtherwise9Flg;
	$myKoji->DateS9 = $wDateS9;
	$myKoji->DateE9 = $wDateE9;

	$myKoji->Otherwise10 = $wOtherwise10;
	$myKoji->Otherwise10Flg = $wOtherwise10Flg;
	$myKoji->DateS10 = $wDateS10;
	$myKoji->DateE10 = $wDateE10;


	$myKoji->KojiBiko = $wKojiBiko;

	$myKoji->Updater = $loginUserCD;

	
	if (!$myKoji->executeUpdate()){
		trigger_error("executeUpdate(myKoji) Failed.", E_USER_ERROR);
	}
	unset($myKoji);


	########################################################
	# データ（連想配列）を作成する
	########################################################

	#案内配布日
	$wDate[0]['Start'] = strtotime( $wAnnaiDate );
	$wDate[0]['End'] = "";
	$wDate[0]['Name'] = "案内配布日";
	$wDate[0]['ShortName'] = "配布";

	#受付締切日
	$wDate[1]['Start'] = strtotime( $wReceptionDate );
	$wDate[1]['End'] = "";
	$wDate[1]['Name'] = "受付締切日";
	$wDate[1]['ShortName'] = "締切";

	#確定案内配布日
	$wDate[2]['Start'] = strtotime( $wKakuteiDate );
	$wDate[2]['End'] = "";
	$wDate[2]['Name'] = "確定案内配布日";
	$wDate[2]['ShortName'] = "配布";

	#共用部
	if($wKyoyoStartDate){
		$wDate[3]['Start'] = strtotime( $wKyoyoStartDate );
		$wDate[3]['End'] = ( $wKyoyoEndDate )? strtotime( $wKyoyoEndDate ) : "";
		$wDate[3]['Name'] = "共用部工事";
		$wDate[3]['ShortName'] = "共用";
	}

	#専有部
	$wDate[4]['Start'] = strtotime( $wSenyuStartDate );
	$wDate[4]['End'] = strtotime( $wSenyuEndDate );
	$wDate[4]['Name'] = "専有部工事";
	$wDate[4]['ShortName'] = "専有";

	#その他、工程表に記載する日程
	for($i = 0; $i < count( $OTHERWISEDATE ); $i++ ){
		$d = $i + 5;
		if(${"wDateS".$d} ){
			$wDate[$d]['Start'] = strtotime( ${"wDateS".$d} );
			$wDate[$d]['End'] = strtotime( ${"wDateE".$d} );
			if(${"wOtherwise".$d."Flg"}){
				$wDate[$d]['Name'] = ${"wOtherwise".$d};
				//echo ":";
				if(array_search(${"wOtherwise".$d},$OTHERWISEDATE)!==false){
					#echo "<br>".__LINE__.":".array_search(${"wOtherwise".$d},$OTHERWISEDATE);
					$wDate[$d]['ShortName'] = $OTHERWISESHORTNAME[array_search(${"wOtherwise".$d},$OTHERWISEDATE)];
				}else{
					#echo "<br>".__LINE__.":".mb_substr(${"wOtherwise".$d},0,2,"utf-8")."__".${"wOtherwise".$d};

					$wDate[$d]['ShortName'] = mb_substr(${"wOtherwise".$d},0,2,"utf-8");
				}
			}
		}
	}


	$wStartDate = min(array_column($wDate, 'Start')); // 開始日の最小値
	$pStartDate	= max(array_column($wDate, 'Start')); // 開始日の最大値
	$pEndDate	= max(array_column($wDate, 'End')); // 終了日の最大値
	if( $pStartDate > $pEndDate ){ 
		$wEndDate = $pStartDate;
	}else{
		$wEndDate = $pEndDate;
	}

	#var_export(array_column($wDate, 'Start'));
	#echo "<br>max:".max(array_column($wDate, 'Start'));
	#echo "<br>min:".min(array_column($wDate, 'Start'));

	#echo "<br>wStartDate:".date("Y-m-d",$wStartDate);
	#echo "<br>pStartDate:".$pStartDate;
	#echo "<br>pEndDate:".$pEndDate;
	#echo "<br>wEndDate:".date("Y-m-d",$wEndDate);

	// 開始日から終了日までの月数を取得
	if (date("Y",$wStartDate) == date("Y",$wEndDate)) {
		$MonthCnt = (date("m",$wEndDate) - date("m",$wStartDate)) + 1;
	} else {
		$MonthCnt = (((date("Y",$wEndDate) - date("Y",$wStartDate)) * 12) - date("m",$wStartDate)) + 1 + date("m",$wEndDate);
	}
	#echo "<br>MonthCnt".$MonthCnt;



	#echo "<pre>";
	#var_dump($wDate);
	#echo "</pre>";

	// 開始日の小さいもの順に並び変え
	foreach ($wDate as $key => $value) {
	  $id[$key] = $value['Start'];
	}
	array_multisort($id, SORT_ASC, $wDate);

	#echo "<pre>";
	#var_dump($wDate);
	#echo "</pre>";
	//echo 254;

	########################################################
	# Excelファイル生成
	########################################################
	require 'vendor/autoload.php';

	use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;
	$reader = new XlsxReader();

	$spreadsheet = $reader->load('./template/zentaikoteihyo.xlsx');

	$sheet = $spreadsheet->getActiveSheet();
	$sheet->setCellValue('C2', $BukkenName);
	$sheet->setCellValue('C3', $KojiName);
	/*!QAzSE$#"
	
	$sheet->setCellValue('Z2', date('Y',strtotime( $ZentaiStartDate ) ) );
	$sheet->setCellValue('AD2', date('n',strtotime( $ZentaiStartDate ) ) );
	$sheet->setCellValue('AF2', date('j',strtotime( $ZentaiStartDate ) ) );
	$sheet->setCellValue('Z3', date('Y',strtotime( $ZentaiEndDate ) ) );
	$sheet->setCellValue('AD3', date('n',strtotime( $ZentaiEndDate ) ) );
	$sheet->setCellValue('AF3', date('j',strtotime( $ZentaiEndDate ) ) );
	*/

	/*
	$sheet->setCellValue('Q2', date('Y',strtotime( $wKyoyoStartDate ) ) );
	$sheet->setCellValue('T2', date('m',strtotime( $wKyoyoStartDate ) ) );
	$sheet->setCellValue('V2', date('d',strtotime( $wKyoyoStartDate ) ) );
	$sheet->setCellValue('Q3', date('Y',strtotime( $wSenyuEndDate ) ) );
	$sheet->setCellValue('T3', date('m',strtotime( $wSenyuEndDate ) ) );
	$sheet->setCellValue('V3', date('d',strtotime( $wSenyuEndDate ) ) );
	*/
	$sheet->setCellValue( "C107", $wKojiBiko);





	$weekArray = array("日","月","火","水","木","金","土");
	$arrayDate = array(); 
	$arrayYobi = array();
	#エクセル曜日色変更時場所特定のため
	$RetuArray = array("C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","AA","AB","AC","AD","AE","AF","AG") ;

	$gyo = 4; // 初期値
	// 月のループ
	for ($m = 0; $m < $MonthCnt; $m++) {

		// その月の月数を割り出す
		$day1 = date("Y-m-01", $wStartDate); // 開始日の数字を日付に戻す（01日にする）
		$day2 = date("Y-m", strtotime( $day1 . "+".$m." month" )); // ループの月数を足した年月を取得
		$DayCnt = date("d", strtotime( 'last day of ' . $day2 )); // 指定月の最終日を取得
		#echo "<br>★day1:".$day1."　☆day2:".$day2."　☆DayCnt:".$DayCnt;

		$TargetY = substr($day2,0,4);
		$TargetM = substr($day2,5,2);


		// 日のループ
		for ($d = 1; $d <= $DayCnt; $d++) {

			$TargetD = sprintf('%02d',$d);
			$TargetYMD = strtotime($TargetY.$TargetM.$TargetD);

			#$arrayDate[] = $TargetD;
			$arrayDate[] = $d; // 桁合わせしない（数字とする）
			$arrayYobi[] = $weekArray[ date('w',$TargetYMD) ];
			if($d<10){
				$D='0'.$d;
			}else{
				$D=$d;
			}
			// 項目のル―プ
			for ($x = 0; $x < count($wDate); $x++) {

				if ($d == 1) {
					// 作業項目
					$NameGyo = "B".( $x + ($gyo + 3));
					$sheet->setCellValue( $NameGyo , $wDate[$x]['Name']);
				}
				if(!in_array($TargetY."-".$TargetM."-".$TargetD,$wHoliday)&&isset($wHoliday)){
					if ($TargetYMD == $wDate[$x]['Start']) {
						$arrayYotei[$x][$d] = $wDate[$x]['ShortName'];

					} else {
						if ($wDate[$x]['End']) { // 終了日がある項目の場合
							if ($TargetYMD > $wDate[$x]['Start'] AND $TargetYMD <= $wDate[$x]['End']) {
								$arrayYotei[$x][$d] = $wDate[$x]['ShortName'];
								/*for($y=0;$y<count($wHoliday);$y++){
									if(strtotime($TargetY.$TargetM.$D)==strtotime($wHoliday[$y])){
									echo "<br>".$wHoliday[$y].":".$TargetY.$TargetM.$D;
										$arrayYotei[$x][$d] = NULL;
									}
								}
							*/} else {
								$arrayYotei[$x][$d] = NULL;
							}
						} else {
							$arrayYotei[$x][$d] = NULL;
						}
					}
				}else{
					$arrayYotei[$x][$d] = NULL;
				}
			}

		}
		#var_dump($arrayYotei);

		//echo "<br>".$gyo;

		// 作業項目
		$tmp = "B".$gyo.":B".($gyo + 2);
		$sheet->mergeCells($tmp); // セルの結合
		$sheet->setCellValue('B'.$gyo, "作業項目");
		$sheet->getStyle('B'.$gyo)->getFill()
			->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
			->getStartColor()->setARGB('FFC0C0C0');

		// 月
		if($TargetM<10){
			$TargetN = str_replace("0","",$TargetM);
		}else{
			$TargetN = $TargetM;
		}
		$sheet->mergeCells('C'.$gyo.':AG'.$gyo);
		$sheet->setCellValue('C'.$gyo, $TargetN."月");
		$sheet->getStyle('C'.$gyo)->getFill()
			->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
			->getStartColor()->setARGB('FFC0C0C0');

		$sheet->getRowDimension($gyo)->setRowHeight(18); // 行の高さ
		$gyo += 1;

		// 日
		$sheet->fromArray($arrayDate, NULL, 'C'.$gyo);
		$sheet->getRowDimension($gyo)->setRowHeight(18);
		$gyo += 1;
		//echo count($arrayDate);
		for($i=0;$i<count($arrayDate);$i++){
		//echo "<br>曜日".date('w',strtotime($TargetY.$TargetM.$arrayDate[$i]));
			if($arrayDate[$i]<10){
				$arrayDate[$i] = '0'.$arrayDate[$i];
			}

			if(array_search(($TargetY."-".$TargetM."-".$arrayDate[$i]),$SHUKUJITULIST)!==false){
				$spreadsheet->getActiveSheet()->getStyle($RetuArray[$i].$gyo)
					->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);
			}elseif(date('w',strtotime($TargetY.$TargetM.$arrayDate[$i]))==6){
				$spreadsheet->getActiveSheet()->getStyle($RetuArray[$i].$gyo)
					->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLUE);
			}elseif( date('w',strtotime($TargetY.$TargetM.$arrayDate[$i]))==0){
				//echo $TargetY.$TargetM.$arrayDate[$i];
				//echo "<br>".$RetuArray[$i].$gyo;
				$spreadsheet->getActiveSheet()->getStyle($RetuArray[$i].$gyo)
					->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);
			}
		}

		// 曜日
		$sheet->fromArray($arrayYobi, NULL, 'C'.$gyo);
		$sheet->getRowDimension($gyo)->setRowHeight(18);
		$gyo += 1;
		//echo "<br>riset<br>";
		// 予定
		$sheet->fromArray($arrayYotei, NULL, 'C'.$gyo);
		$gyo1 = $gyo;
		//var_dump($arrayYotei);
		
		if(is_array($arrayYotei)){		
		for($i=0;$i<=count($arrayYotei);$i++){
			if(is_array($arrayYotei[$i])){
			for($j=0;$j<=count($arrayYotei[$i]);$j++){
				//echo "<br>i:".$i;
				if($arrayYotei[$i][$j]){
					
					//echo "<br>arrayYotei".$arrayYotei[$i][$j];
					//echo "<br>gyo1".$gyo1;
					//echo "<br>j:".$j."RetuArray".$RetuArray[$j-1]."<br>";
					$spreadsheet->getActiveSheet()->getStyle($RetuArray[$j-1].$gyo1)
						->getFont()->getColor()->setARGB("000000");
					$sheet->getStyle($RetuArray[$j-1].$gyo1)->getFill()
						->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
						->getStartColor()->setARGB('FFFF66');

					
				}
			}
			}
			$gyo1++;
			//echo count($arrayYotei[$i]);
		}
		}
		
		// 予定のない項目は非表示とする
		$hidecell_gyo = $gyo;
		foreach( $arrayYotei as $key => $val) {
			$datacheck = false;
			foreach($val as $key2 => $val2) {
				if (!is_null($val2)) {
					$datacheck = true; // データあり
				}
			}
			if ($datacheck == false) { // データなし
				$sheet->getRowDimension($hidecell_gyo)->setVisible(false); // 非表示
			}
			$hidecell_gyo += 1;
		}
		
		$gyo += count($arrayYotei);
		$arrayYotei = array();
		$arrayDate = array();
		$arrayYobi = array();

	}
	
	

	// 不要な行を非表示
	for ($i = $gyo; $i <= 106; $i++) {
		$sheet->getRowDimension($i)->setVisible(false);
	}

	// 印刷範囲
	$sheet->getPageSetup()->setPrintArea('A1:AG107');

	ob_end_clean();

	$Date = date('YmdHis');

	###★ Excel(.xlsx)としてtFileFに登録する
	$writer = new XlsxWriter($spreadsheet);
	$writer->save( './tmp/z'.$Date.'.xlsx');

	// 画像の取得
	$image_path = './tmp/z'.$Date.'.xlsx' ;
	$img_file = file_get_contents( $image_path );

	//画像を保存するSQL文の実行
	$myFile = new File($myDB);

	$myFile->FileCD = -1;
	$myFile->BukkenCD = $editBukkenCD;
	$myFile->SekoStatus = 5;                     #5作成ファイル
	$myFile->P001 = '全体工程表_'.date('Ymd_His').'.xlsx';             #ファイル名
	$myFile->P002 = 'xlsx';  #拡張子

	$myFile->File = $img_file;
	$myFile->Creator = $loginUserCD;
	$myFile->Updater = $loginUserCD;

	if (!$myFile->executeUpdate()){
		$ErrorString = array();
		$ErrorString[] = "メニューマスタ情報の更新に失敗しました。";
		showAdminSorryPage($ErrorString);
	}
	#テンポラリーファイルを削除する。
	$Command = "rm -f ".$image_path;
	shell_exec($Command);
	###★ Excel(.xlsx)としてtFileFに登録する End





	header("Content-Description: File Transfer");
	$wFileName = "全体工程表_".$BukkenName.".xlsx" ;

	// 判定するのに小文字にする
	$browser = strtolower($_SERVER['HTTP_USER_AGENT']);


	if (strstr($browser , 'edge')) {
		#echo('ご使用のブラウザはEdge Chromeです。');
		header('Content-Disposition: attachment; filename*=UTF-8\'\''.rawurlencode($wFileName));

	} elseif (strstr($browser , 'trident') || strstr($browser , 'msie')) {
		#echo('ご使用のブラウザはInternet Explorerです。');
		$wFileName = mb_convert_encoding($wFileName, "SJIS", "UTF-8"); // 20181217 IEでの文字化け対応
		header("Content-Disposition: attachment; filename=".$wFileName );

	} elseif (strstr($browser , 'chrome')) {
		#echo('ご使用のブラウザはGoogle Chromeです。');
		header("Content-Disposition: attachment; filename=".$wFileName );
	} 


	#header("Content-Disposition: attachment; filename=".$wFileName );
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Transfer-Encoding: binary');
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	header('Expires: 0');
	ob_end_clean();

	$writer = new XlsxWriter($spreadsheet);
	$writer->save('php://output');

?>
