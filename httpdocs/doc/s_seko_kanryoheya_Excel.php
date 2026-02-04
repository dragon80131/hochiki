<?php
/* 
 * 工事完了住戸一覧表 出力
 * s_seko_kanryoheya_Excel.php
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
	include_once _CLS_DIR . "SPUSBukkenMatrix.cls";

	include_once  "../include/common.php";


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
	# 物件情報抽出
	########################################################
	$editBukkenCD = SPFWParameter::getValues('editBukkenCD');

	$myBukken = new Bukken($myDB);

	if (!$myBukken->executeSelect("BukkenCD = " . $editBukkenCD . " AND MukouFlg = FALSE", "")){
		trigger_error("Getting Bukken Failed.", E_USER_ERROR);
	}

	if ($myBukken->RecCnt != 1) {
		trigger_error("Getting myBukken List Failed.", E_USER_ERROR);

	} else {
		$BukkenCD = $myBukken->BukkenCD;	#不使用
		$BukkenName = $myBukken->BukkenName;	#物件名
		# $wKosu = $myBukken->Kosu;			#全体住戸数 不使用
		$Kaidaka = $myBukken->Kaidaka;		#階数
	}
	unset($myBukken);


	########################################################
	# 部屋構成情報抽出
	########################################################

	$myBukkenMatrix = new BukkenMatrix($myDB);

	if (!$myBukkenMatrix->executeSelect("BukkenCD = " . $editBukkenCD . " AND MukouFlg = FALSE", "")){
		trigger_error("Getting Koji Failed.", E_USER_ERROR);
	}

	if ($myBukkenMatrix->RecCnt != 1) {

		#部屋情報登録がまだ
		$ErrorString = array();
		$ErrorString[] = "部屋情報を登録してください。";
		$ErrorLoop = count($ErrorString);
		$myTemplate = new SPFWTemplate(_ERROR_TPL, $MyCarrier, TRUE);
		exit;

	} else {

		$KaiRoom = $myBukkenMatrix->KaiRoom;
		$KaiRoom = SPFWTools::decodePluralValue($KaiRoom);#部屋番号配列

		for ($x=0; $x < count($KaiRoom); $x++){	#全部屋分ループ 
			#　前はいくつあるか不定　後ろはゼロサブで2桁固定 大きい部屋から格納されている 303→3　1010→10
			$Room[$x] = substr($KaiRoom[$x],-2);#部屋番号から後ろ2桁を取得 303→03 1011→11　1010→10
			#階高がかならずしも建物の部屋の前の文字を表していない 右うしろ2桁以外の文字 空に置き換え

#str_replace ( $search , $replace , $subject) subject の中の search を全て replace に置換
			#$Kai[$x] = str_replace( $Room[$x] , "", $KaiRoom[$x]);  #部屋番号から後ろ2桁と同じ数字を消す 303→3　1011→10　1010→""
			$Kai[$x] = preg_replace( "/".$Room[$x]."/" , "", $KaiRoom[$x],1);  #部屋番号から後ろ2桁と同じ数字を消す 303→3　1011→10　1010→""

			#echo "<br>Kai-Room:".$Kai[$x]."-".$Room[$x] ;
		}

	}
	unset($myBukkenMatrix);


	########################################################
	# ２重ループ最小構成 Tate Yoko ( x,y )
	########################################################
 $Retu = array('B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
		'AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ',
		'BA','BB','BC','BD','BE','BF','BG','BH','BI');

# リスト部分(Loopの中身)のエレメント確定
	$ColsLoop = max($Room) ;#部屋数最大値を取得
	$RowsLoop = $Kaidaka;	#物件の階数

	$x = 0;
	#$Gyo = 6;#6行目から開始
	$Gyo = 2;	#2行目から開始

/* 
	# SAMPLE
	$sheet->duplicateStyle($sharedStyle1, "A2:M3");	#選択された範囲を格子網掛けセット

	$sheet->mergeCells( 'A1:A3' );	#セルの結合
	$sheet->mergeCellsByColumnAndRow( 0, 1, 0, 3 );#セルの結合↑と同じ意味
*/
	###　固定値定義　###
	
	if($Kaidaka<10 or max($Room)>13){	#11階までは横レイアウト
		$LAYOUT_MAX_ROWNUM = 40;	#横レイアウト 最大行数	
		$LAYOUT_MAX_COLNUM = 60;	#横レイアウト 最大列数
		$templateSheetName = "レイアウト_横";
	}else{#11階以上は縦レイアウト
		$LAYOUT_MAX_ROWNUM = 60;	#縦レイアウト 最大行数
		$LAYOUT_MAX_COLNUM = 40;	#縦レイアウト 最大列数
		$templateSheetName="レイアウト_縦";
	}


	#最大行数÷階数(切り捨て)で1階当たりの行数を求める
	$useRowNum = floor($LAYOUT_MAX_ROWNUM / $Kaidaka);	
	$useRowAddNum = $useRowNum - 1;	 #増加分

	if($useRowNum > 3 ){
		#1階あたり3行以上使えるとき+1にセット
		$useRowTitleNum = 1;
		$titleFontSize = '18';
	}else{
		#3行以下は1行なので+0
		$useRowTitleNum = 0;
		$titleFontSize = '11';
	}


	#最大列数÷階ごとの部屋数(最大)(切り捨て)で一部屋あたりの列数を求める
	$useColNum = floor($LAYOUT_MAX_COLNUM / max($Room));
	$useColAddNum = $useColNum-1; #増加分
	if($useColNum < 4){
		$titleFontSize = '12';
	}
	

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

	$spreadsheet = $reader->load('./template/kanryoheya.xlsx'); //template.xlsx 読込
	#$sheet = $spreadsheet->getActiveSheet();
	
	#レイアウトを指定
	$sheet = $spreadsheet->getSheetByName($templateSheetName);
    #最後は使用したテンプレートでおわる。
	$spreadsheet->setActiveSheetIndexByName($templateSheetName);

	$sheet->setCellValue('A1', $str);

	##-----基本情報のセット
	$sheet->setCellValue('B1', $BukkenName.'工事完了部屋一覧');

	# 枠線	★処理わからないからとりあえずもとのまま
	$sharedStyle1 = new Style();
	$sharedStyle1->applyFromArray([
		'borders' => [
			'bottom' => ['borderStyle' => Border::BORDER_THIN],
			'top' => ['borderStyle' => Border::BORDER_THIN],
			'right' => ['borderStyle' => Border::BORDER_THIN],
			'left' => ['borderStyle' => Border::BORDER_THIN],
		],
		'font' => [
			'name' => 'メイリオ',
			'size' => $titleFontSize,
		],
		'alignment' => [
			'horizontal'=>\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			'vertical'=>\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
		],
	]);
	
	$Gyo = 4;	#初期値　開始行をセット

	for ($i = 0; $i < $RowsLoop; $i++){	#縦方向　最大階数までループ
		$Kaidaka[$i] = $Kaidaka - $i*$useRowNum;	#新規配列要素に現在処理中の階数を登録

		$KaiStart = 0;		#変数初期化

		$tmpRowTitleBottom = $Gyo+$useRowTitleNum;	#タイトル行の下段
		$tmpRowTop = $tmpRowTitleBottom+1;			#タイトル行の一つ下
		$tmpRowBottom = $Gyo+$useRowAddNum;			#該当階で使える一番下の行
		
		for ($j = 0; $j < $ColsLoop; $j++) {#上からのフロアごとに左にすすむ	最大部屋数分ループ
				
				if($KaiStart == $Kai[$x] or $KaiStart == 0){
					$KaiStart = $Kai[$x];
					
					$tmpColTop = $j+$j*$useColAddNum;
					$tmpColLast = $tmpColTop+$useColAddNum;
					#タイトルセルの結合
					$sheet->mergeCells( $Retu[$tmpColTop].($Gyo).":".$Retu[$tmpColLast].($tmpRowTitleBottom) );

					
					#データ行のセルの結合
					$sheet->mergeCells( $Retu[$tmpColTop].($tmpRowTop).":".$Retu[$tmpColLast].($tmpRowBottom) );	

					$sheet->setCellValue($Retu[$tmpColTop].($Gyo), $KaiRoom[$x]);
					$sheet->setCellValue($Retu[$tmpColTop].($tmpRowTop), "○");
#					$sheet->setCellValue($Retu[$tmpColTop].($tmpRowTop), "X=".$x."Kai[$x]".$Kai[$x]);

			        $sheet->duplicateStyle( $sharedStyle1,$Retu[$tmpColTop].($Gyo).":".$Retu[$tmpColLast].($tmpRowBottom) );

					$x = $x + 1;
				}else{
					break;
				}
		}
		
		$Gyo = $tmpRowBottom + 1;
		
	}

/*
	for ($i = 0; $i < $RowsLoop; $i++){	#縦方向　最大階数までループ
		$Kaidaka[$i] = $wKaidaka - $i;	#新規配列要素に現在処理中の階数を登録
		
		$Gyo = $Gyo + 2;	#初期値6　2進める→★進めるセル数を最大階数によって変動させる　#最小値3
		$wKaiStart = 0;		#変数初期化
		
		for ($j = 0; $j < $ColsLoop; $j++) {#上からのフロアごとに左にすすむ	最大部屋数分ループ

			if($wKaiStart == $Kai[$x] or $wKaiStart == 0){#
				$Pic[$j] = $KaiRoom[$x];	#★意味ない？
				$wKaiStart = $Kai[$x];

				$sheet->setCellValue($Retu[$j].($Gyo-1), $KaiRoom[$x]);
				$sheet->setCellValue($Retu[$j].$Gyo, "○");

		        $sheet->duplicateStyle($sharedStyle1, $Retu[$j].($Gyo-1).":".$Retu[$j].$Gyo );
				$x = $x + 1;

			}else{#階が異なっていたらーをいれておく。
				$Pic[$j] = "-";
			}
		}
	}
*/
/* 元の処理
	##-----基本情報のセット
#	$sheet->setCellValue('B39', "■アイホン株式会社　" );
	$sheet->setCellValue('A1', $BukkenName );


	// 枠線
	$sharedStyle1 = new Style();
	$sharedStyle1->applyFromArray([
		'borders' => [
			'bottom' => ['borderStyle' => Border::BORDER_THIN],
			'top' => ['borderStyle' => Border::BORDER_THIN],
			'right' => ['borderStyle' => Border::BORDER_THIN],
			'left' => ['borderStyle' => Border::BORDER_THIN],
		],
		'font' => [
			'name' => 'メイリオ',
			'size' => '18',
		],
		'alignment' => [
			'horizontal'=>\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
		],
	]);

	for ($i = 0; $i < $RowsLoop; $i++){	#縦方向　最大階数までループ
		$Kaidaka[$i] = $wKaidaka - $i;	#新規配列要素に現在処理中の階数を登録
		$Gyo = $Gyo + 2;	#初期値6　2進める
		$wKaiStart = 0;		#変数初期化
		
		for ($j = 0; $j < $ColsLoop; $j++) {#上からのフロアごとに左にすすむ	最大部屋数分ループ

			if($wKaiStart == $Kai[$x] or $wKaiStart == 0){#
				$Pic[$j] = $KaiRoom[$x];
				$wKaiStart = $Kai[$x];

				$sheet->setCellValue($Retu[$j].($Gyo-1), $KaiRoom[$x]);
				$sheet->setCellValue($Retu[$j].$Gyo, "○");

		        $sheet->duplicateStyle($sharedStyle1, $Retu[$j].($Gyo-1).":".$Retu[$j].$Gyo );
				$x = $x + 1;

			}else{#階が異なっていたらーをいれておく。
				$Pic[$j] = "-";
			}
		}
	}
*/





	//ダウンロード用
	//MIMEタイプ：https://technet.microsoft.com/ja-jp/ee309278.aspx
	header("Content-Description: File Transfer");
	$wFileName = "住戸一覧表_".$BukkenName.".xlsx" ;
	$wFileName = mb_convert_encoding($wFileName, "SJIS", "UTF-8"); // 20181217 IEでの文字化け対応

	header("Content-Disposition: attachment; filename=".$wFileName);
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Transfer-Encoding: binary');
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	header('Expires: 0');
	ob_end_clean(); //バッファ消去

	$writer = new XlsxWriter($spreadsheet);
	$writer->save('php://output');

?>
