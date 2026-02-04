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
	# データ取得
	########################################################
	$editBukkenCD = SPFWParameter::getValues('editBukkenCD');
	$JissiDate = SPFWParameter::getValues('JissiDate');


	$myBukken = new Bukken($myDB);

	if (!$myBukken->executeSelect("BukkenCD = ".$editBukkenCD , ""))
		trigger_error("Getting Bukken Failed.", E_USER_ERROR);

	$BukkenName = $myBukken->BukkenName;
	$KanriGaisya = $myBukken->KanriGaisya;
	unset($myBukken);


	########################################################
	# 工事情報取得
	########################################################

	$myKoji = new Koji($myDB);

	if (!$myKoji->executeSelect("BukkenCD = ".$editBukkenCD , ""))
		trigger_error("Getting Bukken Failed.", E_USER_ERROR);

	if ($myKoji->RecCnt != 1) {

		#工事情報登録がまだ
		$ErrorString = array();
		$ErrorString[] = "工事情報を登録してください。";
		$ErrorLoop = count($ErrorString);
		$myTemplate = new SPFWTemplate(_ERROR_TPL, $MyCarrier, TRUE);
		exit;

	}

	$KojiName = $myKoji->KojiName;

	$KojiShozokuName = $myKoji->KojiShozokuName; 
	$KojiTantoName = $myKoji->KojiTantoName; 
	$GyosyaTantoCD1 = $myKoji->GyosyaTantoCD1; 
	$GyosyaData = getGyosyaData($myDB, $GyosyaTantoCD1 );
	$GyosyaName = $GyosyaData['GyosyaName'];
	$ConstTime = $myKoji->ConstTime; 
	
	#作業日時の判定
	$wOtherwise[1] = $myKoji->Otherwise6;
	$wOtherwise[2] = $myKoji->DateS6;
	$wOtherwise[3] = $myKoji->Otherwise7;
	$wOtherwise[4] = $myKoji->DateS7;
	$wOtherwise[5] = $myKoji->Otherwise8;
	$wOtherwise[6] = $myKoji->DateS8;
	$wOtherwise[7] = $myKoji->Otherwise9;
	$wOtherwise[8] = $myKoji->DateS9;
	$wOtherwise[9] = $myKoji->Otherwise10;
	$wOtherwise[10] = $myKoji->DateS10;

	$wSagyoDay = 0;
	for ($j=1;$j<=10;$j=$j+2){
		#工事前・事前確認作業案内
		if ($wOtherwise[$j] == 3){
			$i=$j+1;
			$wSagyoDay = $wOtherwise[$i];}
	}
	
	unset($myKoji);


	$format = SPFWParameter::getValues('format');




	########################################################
	# Excelファイル生成
	########################################################
	require 'vendor/autoload.php';

	use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;
	$reader = new XlsxReader();

	$template_file = './template/kyutokihazusi.xlsx';
	$spreadsheet = $reader->load( $template_file ); //template.xlsx 読込

	$sheet = $spreadsheet->getActiveSheet();
	$sheet->setCellValue('B2', $BukkenName);

	$sheet->setCellValue('AJ1', date("Y年m月")." 吉日");

	$sheet->setCellValue('AJ3', $KanriGaisya);

	if($wSagyoDay != 0){
		$weekArray = array("日","月","火","水","木","金","土");
		$sheet->setCellValue('Y14', date('m月d日',strtotime($JissiDate))."(".$weekArray[ date('w',strtotime($JissiDate))].")" );
	}
	else{
		//echo "<br>155行目"."事前工事日がない場合は作業日付のセットをしていないです";
	}

	//ダウンロード用
	//MIMEタイプ：https://technet.microsoft.com/ja-jp/ee309278.aspx
	header("Content-Description: File Transfer");
	$wFileName = "給湯器外し案内_".$BukkenName.".xlsx" ;
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
