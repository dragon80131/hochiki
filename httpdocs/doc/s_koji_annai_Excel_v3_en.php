<?php
ini_set('error_reporting', E_ALL);
//出力するPHPのエラーレベルを設定。
ini_set('display_errors', "On");
//PHPエラーの表示・非表示を設定。

/*
 * 工事案内英語版
 * s_koji_annai_Excel_v3_en.php
 * @created 2021.03.16 齋藤
 * 【システム出力(可変にする)にあたって絶対必要な情報】
・施工するマンション名☑︎
・配布月?
・管理会社☑︎
・施工会社☑︎
・共有部の工事日程☑︎
・専有部の工事日程☑︎
・施工担当名☑︎
・担当者TEL☑︎
 * @updated
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
	include_once _CLS_DIR . "SPUSShiryo.cls";
	include_once _CLS_DIR . "SPUSIraiRenkei.cls";
	include_once _CLS_DIR . "SPUSKoji.cls";
	include_once _CLS_DIR . "SPUSFile.cls";
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

	$loginUserCD = $myUser->UserCD;
	$ID = $myUser->ID;
	$GyosyaCD = $myUser->Extra5;

	unset($myUser);
	$editBukkenCD = SPFWParameter::getValues('editBukkenCD');

	#######################################################
	# 物件情報抽出
	########################################################
	$myBukken = new Bukken($myDB);
	if (!$myBukken->executeSelect("BukkenCD = " . $editBukkenCD . " AND MukouFlg = FALSE", "")){
		trigger_error("Getting Bukken Failed.", E_USER_ERROR);
	}
	if ($myBukken->RecCnt != 1) {
		trigger_error("Getting myBukken List Failed.", E_USER_ERROR);
	} else {
		$BukkenName = $myBukken->BukkenName;
		$KanriGaisya = $myBukken->KanriGaisya;
		$KanriGaisyaTEL = $myBukken->KanriGaisyaTEL;
	}
	unset($myBukken);

	########################################################
	# 工事情報抽出
	########################################################
	$myKoji = new Koji($myDB);

	if (!$myKoji->executeSelect("BukkenCD = " . $editBukkenCD . " AND MukouFlg = FALSE", "")){
		trigger_error("Getting Koji Failed.", E_USER_ERROR);
	}

	if ($myKoji->RecCnt != 1) {
		#工事情報登録がまだ
		$ErrorString = array();
		$ErrorString[] = "工事情報を登録してください。";
		$ErrorLoop = count($ErrorString);
		$myTemplate = new SPFWTemplate(_ERROR_TPL, $MyCarrier, TRUE);
		exit;
	}
	// $weekarray = array("(日)","(月)","(火)","(水)","(木)","(金)","(土)");
	$KojiName = $myKoji->KojiName;

	$AnnaiDate = $myKoji->AnnaiDate;#案内配布日
	$AnnaiDate = new DateTime($AnnaiDate);
	$AnnaiDateDisp = $AnnaiDate->format('M, Y');

	$SekoShutai = $myKoji->SekoShutai; //施工会社の主体？これでいい？

	$KojiShozokuName = $myKoji->KojiShozokuName;
	$KojiTantoName = $myKoji->KojiTantoName;
	$TantoTEL = $myKoji->KojiShozokuTEL;

	$GyosyaData = getGyosyaData($myDB, $GyosyaCD);
	$GyosyaName = $GyosyaData['GyosyaName'];

	//共用部終了日の処理追加

	$KyoyoStartDate = $myKoji->KyoyoStartDate;
	$KyoyoEndDate = $myKoji->KyoyoEndDate;
	if(!empty($KyoyoStartDate && $KyoyoEndDate)){ #共用部に日程が登録されていれば
	#フォーマットを変換する
	$KyoyoStartDate = date('M d, Y',strtotime( $myKoji->KyoyoStartDate ));
	// $KyoyoStartDate .= $weekarray[ date('w',strtotime( $myKoji->KyoyoStartDate )) ];
	$KyoyoEndDate = date('M d, Y',strtotime( $myKoji->KyoyoEndDate ));
	// $KyoyoEndDate .= $weekarray[ date('w',strtotime( $myKoji->KyoyoEndDate )) ];
	}else{#共用部に値が登録されいなかったら
	#なにも表示させない
	$KyoyoStartDate = "";
	$KyoyoEndDate = "";
	 }

	$SenyuStartDate = $myKoji->SenyuStartDate;
	$SenyuEndDate = $myKoji->SenyuEndDate;
	if(!empty($SenyuStartDate && $SenyuEndDate)){ #専有部に日程が登録されていれば
	#フォーマットを変換する
	$SenyuStartDate = date('M d, Y',strtotime( $myKoji->SenyuStartDate ));
	// $SenyuStartDate .= $weekarray[ date('w',strtotime( $myKoji->SenyuStartDate )) ];
	$SenyuEndDate = date('M d, Y',strtotime( $myKoji->SenyuEndDate ));
	// $SenyuEndDate .= $weekarray[ date('w',strtotime( $myKoji->SenyuEndDate )) ];
	}else{#専有部に値が登録されいなかったら
	#なにも表示させない
	$SenyuStartDate = "";
	$SenyuEndDate = "";
	 }

	unset($myKoji);

	#######################################################
	# Excelファイル生成 #
	########################################################
	require 'vendor/autoload.php';

	use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;
	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	use PhpOffice\PhpSpreadsheet\Style\Border as Border;
	use PhpOffice\PhpSpreadsheet\Style\Fill as Fill;
	use PhpOffice\PhpSpreadsheet\Style\Style;
	use PhpOffice\PhpSpreadsheet\Style\Alignment as Align;
	use PhpOffice\PhpSpreadsheet\Worksheet\SheetView;

	$reader = new XlsxReader();

	$spreadsheet = $reader->load('./template/s_koji_annai_Excel_en_v3.xlsx');//template.xlsx 読込

	$sheet = $spreadsheet->getActiveSheet();

	$sheet->setCellValue('B3','Dear residents of '. $BukkenName);
  $sheet->setCellValue('W1',$AnnaiDateDisp);//工事資料配布日
  $sheet->setCellValue('W4','Management Company：'.$KanriGaisya);//管理会社
  $sheet->setCellValue('W5','Construction Company：'.$SekoShutai);//施工会社の主体

	// #【共用部工事日程】
	if($KyoyoStartDate && $KyoyoEndDate){
		#共用部工事日程が設定されていれば日程をセルにセットする
	$sheet->setCellValue('C20',$KyoyoStartDate.'〜'.$KyoyoEndDate);
	}else{
	#共用部工事日程が設定されていれば警告文ををセルにセットする（暫定）
	$sheet->setCellValue('C20', '共用部工事の日程が登録されていません。');
	}

	// #【部屋内工事工程】専有部工事
	if($SenyuStartDate && $SenyuEndDate){
	$sheet->setCellValue('C23',$SenyuStartDate.'〜'.$SenyuEndDate);
	}else {
	$sheet->setCellValue('C23', '専有部部工事の日程が登録されていません。');
	}
	#【工事お問い合わせ】
  $sheet->setCellValue('V91', 'Construction Company：'.$SekoShutai.'  Person in charge：'.$KojiTantoName);
  $sheet->setCellValue('V92', 'Telephone：'.$TantoTEL);

	$sheet->getPageSetup()->setPrintArea('A1:X93'); //印刷範囲の設定
	$sheet->setBreak('A46', \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_ROW); //改ページ
	$sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4); //印刷設定をA4にする
	$sheet->getPageSetup()->setHorizontalCentered(true); //印刷の中央配置
	$sheet->setSelectedCells('A1'); //セルの選択をA1に設定

	# 余白調整
	$sheet->getPageMargins()->setTop(0);
	$sheet->getPageMargins()->setBottom(0);
	$sheet->getPageMargins()->setRight(0);
	$sheet->getPageMargins()->setLeft(0);

	#シート名変更
	$sheet = $spreadsheet->getSheetByName('工事案内資料');
	// $sheet = $spreadsheet->removeSheetByName('Sheet1');

	//ダウンロード用
	//MIMEタイプ：https://technet.microsoft.com/ja-jp/ee309278.aspx
	header("Content-Description: File Transfer");
	$wFileName = "工事案内_".$BukkenName."_".date('YmdHis').".xlsx" ;
	$wFileName = mb_convert_encoding($wFileName, "SJIS", "UTF-8"); // 20181217 IEでの文字化け対応

	header("Content-Disposition: attachment; filename=".$wFileName);
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Transfer-Encoding: binary');
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	header('Expires: 0');
	ob_end_clean(); //バッファ消去

	$writer = new XlsxWriter($spreadsheet);
	$writer->save('php://output');
