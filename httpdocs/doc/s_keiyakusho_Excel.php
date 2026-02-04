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
	include_once _CLS_DIR . "SPUSKoji.cls";
	include_once _CLS_DIR . "SPUSKeiyakusho.cls";

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
		#必要な項目　マンション名、工事名称、住所、戸数、管理会社、消防特例、管理会社、管理員関係

		$KanriGaisya = $myBukken->KanriGaisya;
		$BukkenName = $myBukken->BukkenName;
	}
	#契約書
	$wOrder = SPFWParameter::getValues("wOrder");
	$wKojiName = SPFWParameter::getValues("wKojiName");
	$wAddress = SPFWParameter::getValues("wAddress");
	$wKokiStart = SPFWParameter::getValues("wKokiStart");
	$wKokiEnd = SPFWParameter::getValues("wKokiEnd");
	$wUkeoiKingaku = SPFWParameter::getValues("wUkeoiKingaku");
	$wPayPeriod = SPFWParameter::getValues("wPayPeriod");
	$wMitumoriNo = SPFWParameter::getValues("wMitumoriNo");
	$wKeiyakuDate = SPFWParameter::getValues("wKeiyakuDate");
	$wAddress2 = SPFWParameter::getValues("wAddress2");
	$wSosikiType = SPFWParameter::getValues("wSosikiType");
	$wSosiki = SPFWParameter::getValues("wSosiki");
	$wDaihyouYakusyoku = SPFWParameter::getValues("wDaihyouYakusyoku");
	$wDaihyouName = SPFWParameter::getValues("wDaihyouName");
	$wTusu = SPFWParameter::getValues("wTusu");

	#捺印申請書
	$wSinseiDate = SPFWParameter::getValues("wSinseiDate");
	$wSyotyouName = SPFWParameter::getValues("wSyotyouName");
	$wTantou = SPFWParameter::getValues("wTantou");
	$wEigyousyo = SPFWParameter::getValues("wEigyousyo");
	$wTokuisakiCD = SPFWParameter::getValues("wTokuisakiCD");


	// 入力チェック
	$ErrorStrings = array();
	if($wOrder == "") $ErrorStrings[] = "注文者が入力されていません。";
	if($wKojiName == "") $ErrorStrings[] = "工事名称が入力されていません。";	#201903 ADD GOE
	if($wAddress == "") $ErrorStrings[] = "住所が入力されていません。";
	if($wKokiStart == "" || $wKokiEnd == "" ) $ErrorStrings[] = "工期が入力されていません。";
	if($wUkeoiKingaku == "") $ErrorStrings[] = "請負金額が入力されてません。";
	if($wPayPeriod == "") $ErrorStrings[] = "支払期限が入力されてません";
	if($wMitumoriNo == "") $ErrorStrings[] = "見積番号が入力されてません";

	$ErrorLoop = count($ErrorStrings);
	if($ErrorLoop > 0){
		$IfError = TRUE;
		include_once("../s_keiyakusho.php");
		exit;
	}


	$myKeiyakusho = new Keiyakusho($myDB);

	if (!$myKeiyakusho->executeSelect("BukkenCD = " . $editBukkenCD, "")){
		trigger_error("Getting Bukken Failed.", E_USER_ERROR);
	}
	if ($myKeiyakusho->RecCnt == 0) {

		$myKeiyakusho->KeiyakushoCD = -1;
		$myKeiyakusho->BukkenCD = $editBukkenCD;

	}
		$myKeiyakusho->KanriKumiai = $wOrder;
		$myKeiyakusho->KojiName = $wKojiName;
		$myKeiyakusho->Address = $wAddress;
		$myKeiyakusho->ZentaiStartDate = $wKokiStart;
		$myKeiyakusho->ZentaiEndDate = $wKokiEnd;
		$myKeiyakusho->UkeoiKin = $wUkeoiKingaku;
		$myKeiyakusho->PayRule = $wPayPeriod;
		$myKeiyakusho->MitsumoriNo = $wMitumoriNo;
		$myKeiyakusho->KeiyakuDate = $wKeiyakuDate;
		$myKeiyakusho->Address2 = $wAddress2;
		$myKeiyakusho->Sosikishurui = $wSosikiType;
		$myKeiyakusho->SosikiName = $wSosiki;
		$myKeiyakusho->DaihyoYaku = $wDaihyouYakusyoku;
		$myKeiyakusho->DaihyoName = $wDaihyouName;
		$myKeiyakusho->Tusu = $wTusu;
		$myKeiyakusho->ShinseiDate = $wSinseiDate;
		$myKeiyakusho->SyotyoName = $wSyotyouName;
		$myKeiyakusho->TantoName = $wTantou;
		$myKeiyakusho->EigyoSyoName = $wEigyousyo;
		$myKeiyakusho->TokuisakiCD = $wTokuisakiCD;


	if (!$myKeiyakusho->executeUpdate())
		trigger_error("Update Ringi Failed.", E_USER_ERROR);




	$week = array('日','月','火','水','木','金','土');

	########################################################
	# Excelファイル生成
	########################################################
	require 'vendor/autoload.php';

	use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;
	$reader = new XlsxReader();
	$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
	$spreadsheet = $reader->load('./template/keiyakusho'.$wTusu.'.xlsx'); //template.xlsx 読込

	$sheet = $spreadsheet->getSheetByName('営-41');

	$picpath = "./images/Waku_Keiyakusho.png";
	$drawing->setPath("./images/Waku_Keiyakusho.png");
	$drawing->setHeight(80);
	$drawing->setCoordinates('B1');
	$drawing->setWorksheet($sheet);
	unset($drawing);

	$sheet
	    ->getComment('B3')
	    ->setAuthor('Mark Baker');
	$commentRichText = $sheet
	    ->getComment('B3')
	    ->getText()->createTextRun('印紙は請負金額10億円までは正確です。');
	$commentRichText->getFont()->setSize(8);
	$sheet
	    ->getComment('B3')
	    ->getText()->createTextRun("\r\n");

	unset($commentRichText);

	$sheet->setCellValue('B7', $wOrder);
	$sheet->setCellValue('D11', $wKojiName);
	$sheet->setCellValue('D12', $wAddress);
	$sheet->setCellValue('D13', date('Y年m月d日',strtotime($wKokiStart)));
	$sheet->setCellValue('G13', date('Y年m月d日',strtotime($wKokiEnd)));
	if(date('Ymd')>=20191001){
		$Tax=0.1;
	}else{
		$Tax=0.08;
	}
	$sheet->setCellValue('D14', number_format($wUkeoiKingaku)."円（内消費税等：".number_format($wUkeoiKingaku*$Tax)."円）");
	$sheet->setCellValue('E15', $wPayPeriod."　　全額現金支払");
	$sheet->setCellValue('C18', "甲及び乙は、本契約に関し、別紙見積書(登録番号".$wMitumoriNo.")に基づき、これを履行する。");
	$sheet->setCellValue('B200', date('Y年m月d日',strtotime($wKeiyakuDate)));
	$sheet->setCellValue('I201', $wAddress2);
	$sheet->setCellValue('E203', $wSosikiType);
	$sheet->setCellValue('I203', $wSosiki);
	$sheet->setCellValue('I205', $wDaihyouYakusyoku);
	$sheet->setCellValue('K205', $wDaihyouName);

	$sheet->getPageSetup()->setPrintArea('A1:O213');

	$sheet->getPageSetup()->setFitToWidth(1);
	$sheet->getPageSetup()->setFitToHeight(0);
	$sheet->setBreak('O36', \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_ROW);
	$sheet->setBreak('O72', \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_ROW);
	$sheet->setBreak('O110', \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_ROW);
	$sheet->setBreak('O148', \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_ROW);


	$sheet = $spreadsheet->getSheetByName('捺印申請書');

	if ($wSinseiDate) $sheet->setCellValue('Q2', date('Y年m月d日',strtotime($wSinseiDate)));
	$sheet->setCellValue('F5', $wSyotyouName);
	$sheet->setCellValue('B6', $wTantou);
	$sheet->setCellValue('B5', $wEigyousyo);

	$sheet->getComment('L9')
	    ->setAuthor('Mark Baker');
	$commentRichText = $sheet->getComment('L9')
	    ->getText()->createTextRun('捺印数は割印も含め');
	$commentRichText->getFont()->setSize(8);
	$sheet->getComment('L9')
	    ->getText()->createTextRun("\r\n");
	$commentRichText = $sheet->getComment('L9')
	    ->getText()->createTextRun('た総数をご記入お願');
	$commentRichText->getFont()->setSize(8);
	$sheet->getComment('L9')
	    ->getText()->createTextRun("\r\n");
	$commentRichText = $sheet->getComment('L9')
	    ->getText()->createTextRun('いします。');
	$commentRichText->getFont()->setSize(8);


	//////////////条件書式の設定 start//////////////////////////////////////////////////

	$conditional1 = new \PhpOffice\PhpSpreadsheet\Style\Conditional();
	$conditional1->setConditionType(\PhpOffice\PhpSpreadsheet\Style\Conditional::CONDITION_CELLIS);
	$conditional1->setOperatorType(\PhpOffice\PhpSpreadsheet\Style\Conditional::OPERATOR_NOTEQUAL);
	$conditional1->addCondition('""');
	$conditional1->getStyle()->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_NONE);
	$conditional1->getStyle()->getFont()->setBold(false);
	$conditionalStyles = $sheet->getStyle('L9')->getConditionalStyles();
	$conditionalStyles[] = $conditional1;

	$sheet->getStyle('L9')->setConditionalStyles($conditionalStyles);

	$conditionalStyles = $sheet->getStyle('Q2')->getConditionalStyles();
	$conditionalStyles[] = $conditional1;

	$sheet->getStyle('Q2')->setConditionalStyles($conditionalStyles);

	$conditionalStyles = $sheet->getStyle('B5:B6')->getConditionalStyles();
	$conditionalStyles[] = $conditional1;

	$sheet->getStyle('B5:B6')->setConditionalStyles($conditionalStyles);

	$conditionalStyles = $sheet->getStyle('C8')->getConditionalStyles();
	$conditionalStyles[] = $conditional1;

	$sheet->getStyle('C8')->setConditionalStyles($conditionalStyles);

	$conditionalStyles = $sheet->getStyle('E9')->getConditionalStyles();
	$conditionalStyles[] = $conditional1;

	$sheet->getStyle('E9')->setConditionalStyles($conditionalStyles);

	$conditionalStyles = $sheet->getStyle('H9')->getConditionalStyles();
	$conditionalStyles[] = $conditional1;

	$sheet->getStyle('H9')->setConditionalStyles($conditionalStyles);

	$conditionalStyles = $sheet->getStyle('P9')->getConditionalStyles();
	$conditionalStyles[] = $conditional1;

	$sheet->getStyle('P9')->setConditionalStyles($conditionalStyles);

	$conditionalStyles = $sheet->getStyle('C12')->getConditionalStyles();
	$conditionalStyles[] = $conditional1;

	$sheet->getStyle('C12')->setConditionalStyles($conditionalStyles);

	$conditionalStyles = $sheet->getStyle('E13')->getConditionalStyles();
	$conditionalStyles[] = $conditional1;

	$sheet->getStyle('E13')->setConditionalStyles($conditionalStyles);

	$conditionalStyles = $sheet->getStyle('H13')->getConditionalStyles();
	$conditionalStyles[] = $conditional1;

	$sheet->getStyle('H13')->setConditionalStyles($conditionalStyles);

	$conditionalStyles = $sheet->getStyle('P13')->getConditionalStyles();
	$conditionalStyles[] = $conditional1;

	$sheet->getStyle('P13')->setConditionalStyles($conditionalStyles);

	$conditionalStyles = $sheet->getStyle('Q23')->getConditionalStyles();
	$conditionalStyles[] = $conditional1;

	$sheet->getStyle('Q23')->setConditionalStyles($conditionalStyles);

	$conditionalStyles = $sheet->getStyle('B26')->getConditionalStyles();
	$conditionalStyles[] = $conditional1;

	$sheet->getStyle('B26')->setConditionalStyles($conditionalStyles);

	$conditionalStyles = $sheet->getStyle('B27')->getConditionalStyles();
	$conditionalStyles[] = $conditional1;

	$sheet->getStyle('B27')->setConditionalStyles($conditionalStyles);

	$conditionalStyles = $sheet->getStyle('C29')->getConditionalStyles();
	$conditionalStyles[] = $conditional1;

	$sheet->getStyle('C29')->setConditionalStyles($conditionalStyles);

	$conditionalStyles = $sheet->getStyle('E30')->getConditionalStyles();
	$conditionalStyles[] = $conditional1;

	$sheet->getStyle('E30')->setConditionalStyles($conditionalStyles);

	$conditionalStyles = $sheet->getStyle('H30')->getConditionalStyles();
	$conditionalStyles[] = $conditional1;

	$sheet->getStyle('H30')->setConditionalStyles($conditionalStyles);

	$conditionalStyles = $sheet->getStyle('L30')->getConditionalStyles();
	$conditionalStyles[] = $conditional1;

	$sheet->getStyle('L30')->setConditionalStyles($conditionalStyles);

	$conditionalStyles = $sheet->getStyle('P30')->getConditionalStyles();
	$conditionalStyles[] = $conditional1;

	$sheet->getStyle('P30')->setConditionalStyles($conditionalStyles);

	$conditionalStyles = $sheet->getStyle('C32')->getConditionalStyles();
	$conditionalStyles[] = $conditional1;

	$sheet->getStyle('C32')->setConditionalStyles($conditionalStyles);

	$conditionalStyles = $sheet->getStyle('E33')->getConditionalStyles();
	$conditionalStyles[] = $conditional1;

	$sheet->getStyle('E33')->setConditionalStyles($conditionalStyles);

	$conditionalStyles = $sheet->getStyle('K33')->getConditionalStyles();
	$conditionalStyles[] = $conditional1;

	$sheet->getStyle('K33')->setConditionalStyles($conditionalStyles);

	$conditionalStyles = $sheet->getStyle('O33')->getConditionalStyles();
	$conditionalStyles[] = $conditional1;

	$sheet->getStyle('O33')->setConditionalStyles($conditionalStyles);

	//////////////条件書式の設定 End//////////////////////////////////////////////////

	$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
	$picpath = "./images/in.png";
	$drawing->setPath($picpath);
	$drawing->setHeight(40);
	$drawing->setCoordinates('F6');
	$drawing->setWorksheet($sheet);
	unset($drawing);

	$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
	$picpath = "./images/in.png";
	$drawing->setPath($picpath);
	$drawing->setHeight(40);
	$drawing->setCoordinates('F27');
	$drawing->setWorksheet($sheet);
	unset($drawing);


	$sheet->getPageSetup()->setPrintArea('A1:U39');

	$sheet->getPageSetup()->setFitToWidth(1);

	$sheet->getPageSetup()->setHorizontalCentered(true);
	$sheet->getPageSetup()->setVerticalCentered(false);





/*	if($wTokuisakiCD == 1)
		$sheet->setCellValue('B28', '有');
	elseif($wTokuisakiCD == 2)
		$sheet->setCellValue('B28', '無');
*/

	if($wTokuisakiCD == 1){
		$sheet->setCellValue('U9', '■');
		$sheet->setCellValue('U10', '□');
	#}elseif($wTokuisakiCD == 2){
	} else {
		$sheet->setCellValue('U9', '□');
		$sheet->setCellValue('U10', '■');
	}

/*
	for($i = 2; $i <= $ko; $i++){
		$clonedWorksheet = clone $spreadsheet->getSheetByName('Sheet1');
		$clonedWorksheet->setTitle('Sheet'.$i);
		$spreadsheet->addSheet($clonedWorksheet);
		$sheet = $spreadsheet->getSheetByName('Sheet'.$i); //weatherシート取得
		$sheet->setCellValue('J3', $notrepID[$i-1]);
	}

*/
	//ダウンロード用
	//MIMEタイプ：https://technet.microsoft.com/ja-jp/ee309278.aspx
	header("Content-Description: File Transfer");
	$wFileName = "工事請負契約書_".$BukkenName.".xlsx" ;
	$wFileName = mb_convert_encoding($wFileName, "SJIS", "UTF-8"); // 20181217 IEでの文字化け対応

	header("Content-Disposition: attachment; filename=".$wFileName );
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Transfer-Encoding: binary');
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	header('Expires: 0');
	ob_end_clean(); //バッファ消去

	$writer = new XlsxWriter($spreadsheet);
	$writer->save('php://output');

?>
