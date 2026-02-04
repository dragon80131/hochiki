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
	include_once _CLS_DIR . "SPUSFile.cls";
	include_once _CLS_DIR . "SPUSKojiDate.cls";


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
	# パラメーター取得
	########################################################

	$myKoji = new Koji($myDB);

	if (!$myKoji->executeSelect("BukkenCD = " . $editBukkenCD, "") ){
		$ErrorString = array();
		$ErrorString[] = "tKojiF情報の抽出に失敗しました。";
		showAdminSorryPage($ErrorString);
	}
	$KyoyoStartDate = $myKoji->KyoyoStartDate;
	$KyoyoEndDate = $myKoji->KyoyoEndDate;
	$SenyuStartDate = $myKoji->SenyuStartDate;
	$SenyuEndDate = $myKoji->SenyuEndDate;
	$SenyuDateCnt = (( strtotime( $SenyuEndDate ) -  strtotime( $SenyuStartDate )) / 86400) + 1 ;#専有部日数
	$ReceptionDate = $myKoji->ReceptionDate;
			echo "<br>count(Holiday)".count($wHoliday);
	for($i=1 ; $i<=count($wHoliday) ; $i++){
		$Kyujitu[] = (strtotime( $wHoliday[$i-1] ) - strtotime( $SenyuStartDate ))/86400;

	}
	#$myKoji->WakuSu = $wWakuSu ;

	if (!$myKoji->executeUpdate()){
		$ErrorString = array();
		$ErrorString[] = "工事情報登録に失敗しました。";
		showAdminSorryPage($ErrorString);
	}else{
		$IfOK = TRUE;
	}
	unset($myKoji);

	########################################################
	# 部屋情報　保存
	########################################################
	$myBukkenMatrix = new BukkenMatrix($myDB);

	if (!$myBukkenMatrix->executeSelect("BukkenCD > 0 AND MukouFlg = FALSE AND BukkenCD = ".$editBukkenCD , ""))
		trigger_error("Getting myBukkenMatrix Failed.", E_USER_ERROR);

	$wKaiRoom = $myBukkenMatrix->KaiRoom;
	$KaiRoom = SPFWTools::decodePluralValue($wKaiRoom); #配列

	unset($myBukkenMatrix);



	$myBukken = new Bukken($myDB);

	if (!$myBukken->executeSelect("BukkenCD > 0 AND MukouFlg = FALSE AND BukkenCD = ".$editBukkenCD , ""))
		trigger_error("Getting tFileF Failed.", E_USER_ERROR);

	$BukkenName = $myBukken->BukkenName;
	unset($myBukken);


	########################################################
	# 作成準備
	########################################################

	function getExcelAddress($col,$row) {
		$sinsu = 26;
		$col_val="";
		while($col>0) {
			$intval = (($col-1) % $sinsu);
			$col_val=chr($intval+65).$col_val;
			$col=intval(($col-1) / $sinsu);
		}
		return $col_val.$row;
	}


#工事枠を測る
	$wWakuAMPM = $wWakuAMcol+$wWakuPM1col+$wWakuPM2col;
	$week = ['日','月','火','水','木','金','土'];

	########################################################
	# Excelファイル生成
	########################################################
	require 'vendor/autoload.php';

	use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;

	use PhpOffice\PhpSpreadsheet\Style\Border;
	use PhpOffice\PhpSpreadsheet\Style\Fill;
	use PhpOffice\PhpSpreadsheet\Style\Style;

	$reader = new XlsxReader();
	$spreadsheet = $reader->load('./template/kotei_koteihyo.xlsx'); //template.xlsx 読込
#	$sheet = $spreadsheet->getSheetByName('weather'); //weatherシート取得
	$sheet = $spreadsheet->getActiveSheet();
//	$sheet->setCellValue('B5', $BukkenName);
//	$sheet->setCellValue('K5', date('Y年m月d日',strtotime( $KyoyoStartDate ))."～".date('m月d日',strtotime( $KyoyoEndDate )) );	#共用部
//	$sheet->setCellValue('K6', date('Y年m月d日',strtotime( $SenyuStartDate ))."～".date('m月d日',strtotime( $SenyuEndDate )) );	#専有部

	$aReceptionDate = date('Y年m月d日',strtotime( $ReceptionDate ))."(".$week[date('w',strtotime( $ReceptionDate ))].")";
	$sheet->setCellValue('B5',"　日程が合わない場合は、".$aReceptionDate."までに、下記に記載のフリーダイヤル" );



	//セルの結合
	$cellAM = getExcelAddress(($wWakuAMcol+3),8);#列　行　
	$sheet->mergeCells('D8:'.$cellAM);
	$cellPM1c = getExcelAddress(($wWakuAMcol+4),8);#列　行　
	$cellPM1r = getExcelAddress(($wWakuAMcol+3+$wWakuPM1col),8);#列　行
	$sheet->mergeCells($cellPM1c.':'.$cellPM1r);

	#AM表記変更
	$aAM = $WAKUPATTERN[$wWakuPattern]['StartTime'][0];
	$eAM = $WAKUPATTERN[$wWakuPattern]['EndTime'][0];
	$DispAM = $aAM."～".$eAM ;
	$sheet->setCellValue( "D8", $DispAM);

	$aPM1 = $WAKUPATTERN[$wWakuPattern]['StartTime'][1] ;
	$ePM1 = $WAKUPATTERN[$wWakuPattern]['EndTime'][1] ;
	$DispPM1 = $aPM1."～".$ePM1 ;

	$aPM2 = $WAKUPATTERN[$wWakuPattern]['StartTime'][2] ;
	$ePM2 = $WAKUPATTERN[$wWakuPattern]['EndTime'][2];
	$DispPM2 = $aPM2."～".$ePM2 ;


	if($wWakuPM2col > 0){
		$sheet->setCellValue($cellPM1c, $DispPM1);
		$cellPM2c = getExcelAddress(($wWakuAMcol+4+$wWakuPM1col),8);#列　行
		$cellPM2r = getExcelAddress(($wWakuAMcol+3+$wWakuPM1col+$wWakuPM2col),8);#列　行
		$sheet->mergeCells($cellPM2c.':'.$cellPM2r);
		$sheet->setCellValue($cellPM2c, $DispPM2);
	}else{
		$sheet->setCellValue($cellPM1c, $DispPM1);
	}




	$cell_row = 9;
	$k = 0;
	// 枠線
	$sharedStyle1 = new Style();
	$sharedStyle1->applyFromArray([
		'borders' => [
			'outline' => ['borderStyle' => Border::BORDER_THICK],
			'bottom' => ['borderStyle' => Border::BORDER_THIN],
			'top' => ['borderStyle' => Border::BORDER_THIN],
			'right' => ['borderStyle' => Border::BORDER_THIN],
			'left' => ['borderStyle' => Border::BORDER_THIN],
		],
		'alignment' => [
			'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
		],
		'font' => [
			'name' => 'メイリオ',
			'size' => '16',
			'bold' => true,
		],
	]);
	$sharedStyle2 = new Style();#すこし小さめ
	$sharedStyle2->applyFromArray([
		'borders' => [
			'outline' => ['borderStyle' => Border::BORDER_THICK],
			'bottom' => ['borderStyle' => Border::BORDER_THIN],
			'top' => ['borderStyle' => Border::BORDER_THIN],
			'right' => ['borderStyle' => Border::BORDER_THIN],
			'left' => ['borderStyle' => Border::BORDER_THIN],
		],
		'alignment' => [
			'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
		],
		'font' => [
			'name' => 'メイリオ',
			'size' => '12',
			'bold' => true,
		],
	]);

	$cell = getExcelAddress(($wWakuAMPM+3),($SenyuDateCnt*$wHansu+8));#列　行　D9から
#	$sheet->duplicateStyle($sharedStyle1, ('B8:C8'));
#	$sheet->duplicateStyle($sharedStyle1, ('D8:'.$cell));
	$sheet->duplicateStyle($sharedStyle1, ('B8:'.$cell));

	$cella = getExcelAddress(($wWakuAMPM+3),8 );#列　行　D9から
	$sheet->duplicateStyle($sharedStyle2, ('D8:'.$cella  ));

	unset($sharedStyle1);
	unset($sharedStyle2);	

	for($i = 0 ; $i < $SenyuDateCnt*$wHansu ; $i++ ){#1 専有部日数　#日付はまわす 枠がいっぱいになったら次の日にいく。
		$cell_col = 4;
		$y=0;
		for($x=0 ; $x<count($Kyujitu) ;$x++){
			if($i == $Kyujitu[$x] * $wHansu ){
				$cell = getExcelAddress($cell_col,$cell_row);#列　行　D9から
				$cell2 = getExcelAddress($wWakuAMPM+3,($cell_row+$wHansu)-1);
				$sheet->mergeCells($cell.':'.$cell2);
				$sheet->setCellValue($cell, "休 工 日");
				$spreadsheet->getSheetByName('Sheet1')->getStyle($cell)->getFill()
					->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
					->getStartColor()->setARGB('cccccc');
			}
			if($i >= $Kyujitu[$x] * $wHansu && $i < ($Kyujitu[$x]+1) * $wHansu)
				$y=1;
		}





		if($y==0){
			for($j = 0 ; $j < $wWakuAMPM ; $j++){
				if($wFirstDateFeature>0 && $j < $wWakuAMcol && $i >= 0 && $i < $wHansu){
						$cell = getExcelAddress($cell_col,$cell_row);#列　行　D9から
					#セルに値をセットする
						$sheet->setCellValue($cell, "");
					#セルの色をグレーにする
					$spreadsheet->getSheetByName('Sheet1')->getStyle($cell)->getFill()
						->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
						->getStartColor()->setARGB('cccccc');
	//				$sheet->duplicateStyle($sharedStyle2, ($cell));
					
					# 右横にずらす
						$cell_col = $cell_col + 1; 
						$k++;
						unset($sharedStyle1);
				}else{
					$cell = getExcelAddress($cell_col,$cell_row);#列　行　D9から
					if($wKoteihyouEX[$k]=="空き"){
						#セルに値をセットする
							$sheet->setCellValue($cell, "");
						}elseif($wKoteihyouEX[$k]==""){
						#セルの色をグレーにする
						$spreadsheet->getSheetByName('Sheet1')->getStyle($cell)->getFill()
							->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
							->getStartColor()->setARGB('cccccc');
					}else{
						#セルに値をセットする
							$sheet->setCellValue($cell, $wKoteihyouEX[$k]);
							if($j<$wWakuAMcol){
								$KoteihyouTa[$i]['AM'][]=$wKoteihyouEX[$k];
							}elseif($j>=$wWakuAMcol && $j<($wWakuAMcol+$wWakuPM1col)){
								$KoteihyouTa[$i]['PM1'][]=$wKoteihyouEX[$k];
							}elseif($j>=($wWakuAMcol+$wWakuPM1col) && $j<$wWakuAMPM){
								$KoteihyouTa[$i]['PM2'][]=$wKoteihyouEX[$k];
							}

					}
				# 右横にずらす
					$cell_col = $cell_col + 1; 
					$k++;

				}
			}
		}
			$cell_row = $cell_row + 1;
	}
#echo "<br>385行目wKoteihyouTa:";
#print_r($KoteihyouTa);
	$date = new DateTime($SenyuStartDate);
	$CellStart = 9;
	for($i = 0 ; $i < $SenyuDateCnt ; $i++ ){#1 専有部日数　#日付はまわす 枠がいっぱいになったら次の日にいく。
		$SenyuDate = $date->format('m月d日');
		$YoubiCD =  $date->format('w') ;
		$Youbi = $week[$YoubiCD];
#echo "<br>382行目SenyuDate:".$SenyuDate;
		$wSenyuDate[] = $date->format('Y-m-d');	
	
		$wCellStart = $CellStart + ( $i * $wHansu);
		$sheet->mergeCells('B'.$wCellStart.':B'.($wCellStart + $wHansu - 1));
		$sheet->setCellValue('B'.$wCellStart,$SenyuDate );

		$sheet->mergeCells('C'.$wCellStart.':C'.($wCellStart + $wHansu - 1));
		$sheet->setCellValue('C'.$wCellStart,$Youbi );
		
	$date->modify('+1 days');
	}

	$wCellStart += 4; 

	$sharedStyle1 = new Style();
	$sharedStyle1->applyFromArray([
		'font' => [
			'name' => 'メイリオ',
			'size' => '14',
			'bold' => true,
		],
	]);
	$cell = $wCellStart+6;
	$sheet->duplicateStyle($sharedStyle1, ('B'.$wCellStart.':M'.$cell));
	unset($sharedStyle1);

	$styleArray = [
	    'borders' => [
	        'outline' => [
	            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
	        ],
	    ],
	];

	$sheet->getStyle('B'.$wCellStart.':L'.$cell)->applyFromArray($styleArray);


	$sheet->setCellValue('B'.$wCellStart,"　　<お部屋訪問日時変更のお申込み>　締め切り　".date('Y年m月d日',strtotime( $ReceptionDate ))."(".$week[date('w',strtotime( $ReceptionDate ))].")");
	$wCellStart++; 
	$sheet->setCellValue('B'.$wCellStart,"　　　■受付フリーダイヤル");
	$wCellStart++; 
	$sheet->setCellValue('B'.$wCellStart,"電話　0120－489－501（日時変更フリーダイヤル）");
	$spreadsheet->getSheetByName('Sheet1')->getStyle('B'.$wCellStart)->getFont()->setSize(18);
	$spreadsheet->getSheetByName('Sheet1')->getStyle('B'.$wCellStart)->getFont()->setUnderline(true);
	$sheet->mergeCells('B'.$wCellStart.':L'.$wCellStart);
	$spreadsheet->getSheetByName('Sheet1')->getStyle('B'.$wCellStart)
		->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);	$wCellStart++; 
	$sheet->setCellValue('F'.$wCellStart,"（工事期間中無休　９：００～１７：３０）");
	$wCellStart++; 
	$sheet->setCellValue('B'.$wCellStart,"　　　■お願い");
	$wCellStart++; 
	$sheet->setCellValue('B'.$wCellStart,"　　　　○電話にてマンション名・号室・お名前をお伝え願います。");
	$wCellStart++; 
	$sheet->setCellValue('B'.$wCellStart,"　　　　　委託先オペレーターが対応致します。");

	$ROWS=$SenyuDateCnt*$wHansu+8;
	for($i=8 ;$i<=$ROWS ; $i++){
		$spreadsheet->getActiveSheet()->getRowDimension($i)->setRowHeight(40);
	}


	########################################################
	# 日程情報　保存
	########################################################

	#登録前に、同一物件は削除　
	#同じ物件CDを全部削除してしまう。多棟のときは、別物件でやってもらおう。まちがえたら上書きになる。復活できない
	$db_link = mysqli_connect(_HOST_NAME, _USER_NAME, _PASSWD, _MAIN_DB);

#	$sql = " UPDATE tKojiDateF SET MukouFlg = 1, Updated = '".date('Y-m-d H:i:s')."', Updater = '$UserCD' ";
	$sql = " delete from  tKojiDateF ";
	$sql .= " WHERE BukkenCD = '".$editBukkenCD."' ";
	$result = mysqli_query( $db_link,$sql);	

	#いつでも新規登録

	for( $i=0; $i< count($wSenyuDate); $i++){
	#	echo "<br>日付:".date('Y-m-d',strtotime( $wSenyuDate[$i]));
		echo "<br>日付:".$wSenyuDate[$i] ;
		for( $j=0; $j< $wHansu; $j++){
			#echo " ☆:".($i*$wHansu+$j);

			#print_r($KoteihyouTa[$i*$wHansu+$j]['AM']);
			for($k=0; $k<count($KoteihyouTa[$i*$wHansu+$j]['AM']);$k++){
				#echo " AM ".$KoteihyouTa[$i*$wHansu+$j]['AM'][$k];
				$KojiDate['RoomID'][] = $KoteihyouTa[$i*$wHansu+$j]['AM'][$k];#
				$KojiDate['RoomDate'][] = $wSenyuDate[$i]." ".$aAM;#日付+AMの開始時刻　196行付近に定義あり
			}

			for($k=0; $k<count($KoteihyouTa[$i*$wHansu+$j]['PM1']);$k++){
				#echo " PM1 ".$KoteihyouTa[$i*$wHansu+$j]['PM1'][$k];
				$KojiDate['RoomID'][] = $KoteihyouTa[$i*$wHansu+$j]['PM1'][$k];#
				$KojiDate['RoomDate'][] = $wSenyuDate[$i]." ".$aPM1;#日付+PM1の開始時刻　196行付近に定義あり

			}

			for($k=0; $k<count($KoteihyouTa[$i*$wHansu+$j]['PM2']);$k++){
				#echo " PM2 ".$KoteihyouTa[$i*$wHansu+$j]['PM2'][$k];
				$KojiDate['RoomID'][] = $KoteihyouTa[$i*$wHansu+$j]['PM2'][$k];#
				$KojiDate['RoomDate'][] = $wSenyuDate[$i]." ".$aPM2;#日付+PM2の開始時刻　196行付近に定義あり

			}
		}

	}

	##テーブル登録
	for( $i=0; $i<count( $KojiDate['RoomID'] ); $i++){
		$myKojiDate = new KojiDate($myDB);

		$myKojiDate->KojiDateCD = "-1";
		$myKojiDate->BukkenCD = $editBukkenCD;
		$myKojiDate->RoomID = $KojiDate['RoomID'][$i];
		$myKojiDate->RoomDate = $KojiDate['RoomDate'][$i];
		$myKojiDate->Creator = $UserCD;
		$myKojiDate->Updater = $UserCD;
		
		if (!$myKojiDate->executeUpdate()){
			$ErrorString = array();
			$ErrorString[] = "部屋日程登録に失敗しました。";
			showAdminSorryPage($ErrorString);
		}
		unset($myKojiDate);
	}




	###★ Excel(.xlsx)としてtFileFに登録する
	$writer = new XlsxWriter($spreadsheet);
	$writer->save( './tmp/s'.date('Ymdhis').'.xlsx');

	// 画像の取得
	#		$image_path = $dir.$image_name;
	$image_path = './tmp/s'.date('Ymdhis').'.xlsx' ;
	$img_file = file_get_contents( $image_path );

	//画像を保存するSQL文の実行
	$myFile = new File($myDB);

	$myFile->FileCD = -1;
	$myFile->BukkenCD = $editBukkenCD;
	$myFile->SekoStatus = 5;                     #5作成ファイル
	$myFile->P001 = '詳細工程表'.date('Ymdihs').'.xlsx';             #ファイル名
	$myFile->P002 = 'xlsx';  #拡張子

	$myFile->File = $img_file;
	#		$myFile->Memo = $Memo;
	$myFile->Creator = $wUserCD;
	$myFile->Updater = $wUserCD;

	if (!$myFile->executeUpdate()){
		$ErrorString = array();
		$ErrorString[] = "メニューマスタ情報の更新に失敗しました。";
		showAdminSorryPage($ErrorString);
	}
	#テンポラリーファイルを削除する。
	$Command = "rm -f ".$image_path;
	shell_exec($Command);
	###★ Excel(.xlsx)としてtFileFに登録する End









	//ダウンロード用
	//MIMEタイプ：https://technet.microsoft.com/ja-jp/ee309278.aspx
	header("Content-Description: File Transfer");
	$wFileName = "詳細工程表_".$BukkenName.".xlsx" ;
#	$wFileName = mb_convert_encoding($wFileName, "SJIS", "UTF-8"); // 20181217 IEでの文字化け対応

#	header("Content-Disposition: attachment; filename=".$wFileName );

	// 判定するのに小文字にする
	$browser = strtolower($_SERVER['HTTP_USER_AGENT']);
	if (strstr($browser , 'edge')) {
	#    echo('ご使用のブラウザはEdge Chromeです。');
		header('Content-Disposition: attachment; filename*=UTF-8\'\''.rawurlencode($wFileName));

	} elseif (strstr($browser , 'trident') || strstr($browser , 'msie')) {
	#    echo('ご使用のブラウザはInternet Explorerです。');
		$wFileName = mb_convert_encoding($wFileName, "SJIS", "UTF-8"); // 20181217 IEでの文字化け対応
		header("Content-Disposition: attachment; filename=".$wFileName );

	} elseif (strstr($browser , 'chrome')) {
	#    echo('ご使用のブラウザはGoogle Chromeです。');
		header("Content-Disposition: attachment; filename=".$wFileName );
	} 

	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Transfer-Encoding: binary');
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	header('Expires: 0');
	ob_end_clean(); //バッファ消去

	$writer = new XlsxWriter($spreadsheet);
	$writer->save('php://output');


#ファイルを仮フォルダに格納

#ｔFileFに格納



?>
