<?php
/* 
 * インターホン工事書類・掲示物一式 出力
 * s_seko_keiji_Excel.php
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
		$BukkenName = $myBukken->BukkenName;
		$TantoCD = $myBukken->TantoCD;#担当者CD
		$KanriGaisya = $myBukken->KanriGaisya;

		$KanriinName = $myBukken->KanriinName;
		$KanriTEL = $myBukken->KanriTEL;

	}
	unset($myBukken);
if($TantoCD){
	$TantoData = getTantoData($myDB, $TantoCD);
	$TantoName = $TantoData['TantoName'] ;
	$EigyoshoAddress = $TantoData['EigyoshoAddress'] ;
	$TEL = $TantoData['TEL'] ;
}else{
echo "<br><br><br><br><br><br>営業担当者が設定されていません。";
echo "<br>リニューアル支援の物件基本情報の営業担当をセットしてください。";
echo "<br>施工業者様は、アイホン営業担当へ依頼お願いします。";
exit;

}
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

	} else {
		$KojiName = $myKoji->KojiName;

		$weekarray = array("(日)","(月)","(火)","(水)","(木)","(金)","(土)");
		$ZentaiStartDate 	= date('Y年m月d日',strtotime( $myKoji->ZentaiStartDate ));
		$ZentaiStartDate 	.= $weekarray[ date('w',strtotime( $myKoji->ZentaiStartDate )) ];
		$ZentaiEndDate		= date('Y年m月d日',strtotime( $myKoji->ZentaiEndDate ));
		$ZentaiEndDate 		.= $weekarray[ date('w',strtotime( $myKoji->ZentaiEndDate )) ];

		$KojiShozokuName = $myKoji->KojiShozokuName;
		$KojiTantoName = $myKoji->KojiTantoName;
		$RNsystem = $myKoji->RNsystem;#システム

		$SekoShutaiOP = $myKoji->SekoShutaiOP;#施工主体区分　0:元請け　1:下請け
		$SekoShutai = $myKoji->SekoShutai;#施工主体名

		#専有部工事終了日
		$SenyuEndDate		= date('n月d日',strtotime( $myKoji->SenyuEndDate ));
		$SenyuEndDate 		.= $weekarray[ date('w',strtotime( $myKoji->SenyuEndDate )) ];
	}
	unset($myKoji);


	########################################################
	# Excelファイル生成
	########################################################
	require 'vendor/autoload.php';

	use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;
	$reader = new XlsxReader();

	$spreadsheet = $reader->load('./template/seko_keiji_issiki.xlsx'); //template.xlsx 読込


	#シートを指定して各項目をセットしていく
#	$sheet = $spreadsheet->getSheetByName('入力シート');

#	$sheet->setCellValue('C5', $BukkenName);		#物件名
#	$sheet->setCellValue('C6', $KojiName);			#工事名
#	$sheet->setCellValue('C7', $ZentaiStartDate.'～'.$ZentaiEndDate);#工期
#	$sheet->setCellValue('C8', $KojiShozokuName);#所属名
#	$sheet->setCellValue('C9', $KojiTantoName);		#担当名
#	$sheet->setCellValue('C15', $KanriGaisya);		#管理会社名
#	$sheet->setCellValue('C17', $KanriinName);		#管理会社担当名
#	$sheet->setCellValue('C18', $KanriTEL);			#管理人TEL


	########################################################
	# 1工事確認書（標準）生成
	########################################################
	$sheet = $spreadsheet->getSheetByName('工事確認書（標準）');
	$sheet->setCellValue('E3', $BukkenName);		#物件名
	$sheet->setCellValue('B5', $BukkenName);		#物件名
	unset($sheet);
	########################################################
	# 2工事確認書（感知器交換有）生成
	########################################################
	$sheet = $spreadsheet->getSheetByName('工事確認書（感知器交換有）');
	$sheet->setCellValue('E3', $BukkenName);		#物件名
	$sheet->setCellValue('B5', $BukkenName);		#物件名
	unset($sheet);
	########################################################
	# 3工事確認書（ノンタッチ受領有）生成
	########################################################
	$sheet = $spreadsheet->getSheetByName('工事確認書（ノンタッチ受領有）');
	$sheet->setCellValue('E3', $BukkenName);		#物件名
	$sheet->setCellValue('B5', $BukkenName);		#物件名
	unset($sheet);
	########################################################
	# 4集合玄関機案内（システム停止　暗証番号解錠無）生成
	########################################################
	$sheet = $spreadsheet->getSheetByName('集合玄関機案内（システム停止　暗証番号解錠無）');

	$sheet->setCellValue('B2', $BukkenName);		#物件名
	$sheet->setCellValue('A7', $ZentaiStartDate.'～'.$ZentaiEndDate);#工期

	if ($RNsystem == 1 or $RNsystem == 2 or $RNsystem == 4 or $RNsystem == 3 or $RNsystem == 6) {

		#画像貼り付け
		$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
		if($RNsystem == 1 or $RNsystem == 2 or $RNsystem == 4  ){ #らくタッチプラス　Vixus
			$picpath = "./images/SekoGamenVJ.jpg";
			$drawing->setPath($picpath);
			$drawing->setHeight(170);
		}elseif($RNsystem == 3 or $RNsystem == 6  ){ #WISM
			$picpath = "./images/SekoGamenVH.jpg";
			$drawing->setPath($picpath);
			$drawing->setHeight(150);
		}
		$drawing->setPath($picpath);
		$drawing->setHeight(170);
		$drawing->setCoordinates('M13');
		$drawing->setWorksheet($sheet);
		unset($sheet);
		unset($drawing);
	}

	########################################################
	# 5集合玄関機案内（システム停止　暗証番号解錠有）生成
	########################################################
	$sheet = $spreadsheet->getSheetByName('集合玄関機案内（システム停止　暗証番号解錠有）');

	$sheet->setCellValue('B2', $BukkenName);		#物件名
	$sheet->setCellValue('A7', $ZentaiStartDate.'～'.$ZentaiEndDate);#工期

	if ($RNsystem == 1 or $RNsystem == 2 or $RNsystem == 4 or $RNsystem == 3 or $RNsystem == 6) {
		#画像貼り付け
		$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
		if($RNsystem == 1 or $RNsystem == 2 or $RNsystem == 4  ){ #らくタッチプラス　Vixus
			$picpath = "./images/SekoGamenVJ.jpg";
			$drawing->setPath($picpath);
			$drawing->setHeight(170);
		}elseif($RNsystem == 3 or $RNsystem == 6  ){ #WISM
			$picpath = "./images/SekoGamenVH.jpg";
			$drawing->setPath($picpath);
			$drawing->setHeight(150);
		}

		$drawing->setCoordinates('M13');
		$drawing->setWorksheet($sheet);
		unset($sheet);
		unset($drawing);
	}

	########################################################
	# 6工事確認書（標準）生成
	########################################################
	$sheet = $spreadsheet->getSheetByName('集合玄関機案内（並行稼動）');

	$sheet->setCellValue('B2', $BukkenName);		#物件名
	$sheet->setCellValue('A7', $ZentaiStartDate.'～'.$ZentaiEndDate);#工期
	unset($sheet);
	########################################################
	# 7専有部写真看板生成
	########################################################
	$sheet = $spreadsheet->getSheetByName('専有部写真看板');

	$sheet->setCellValue('B2', $BukkenName);		#物件名
	$sheet->setCellValue('B12', $BukkenName);		#物件名
	$sheet->setCellValue('B7', $SekoShutai);		#施工主体名　元請け会社　
	$sheet->setCellValue('B17', $SekoShutai);		#施工主体名　元請け会社　
	unset($sheet);

	########################################################
	# 8共用部写真看板 生成
	########################################################
	$sheet = $spreadsheet->getSheetByName('共用部写真看板');

	$sheet->setCellValue('B2', $BukkenName);		#物件名
	$sheet->setCellValue('B13', $BukkenName);		#物件名
	$sheet->setCellValue('B8', $SekoShutai);		#施工主体名　元請け会社　
	$sheet->setCellValue('B19', $SekoShutai);		#施工主体名　元請け会社　
	unset($sheet);

	########################################################
	# 9不在表 生成
	########################################################
	$sheet = $spreadsheet->getSheetByName('不在表');

	$sheet->setCellValue('J6', $SekoShutai);		#施工主体名　元請け会社　
	$sheet->setCellValue('D29', $SenyuEndDate);		#専有部工事終了日　
	unset($sheet);

	########################################################
	# 10借用書 生成
	########################################################
	$sheet = $spreadsheet->getSheetByName('借用書');
	$sheet->setCellValue('B33', $EigyoshoAddress);		#住所　元請け会社　
	$sheet->setCellValue('B34', $SekoShutai);		#施工主体名　
	$sheet->setCellValue('B35', $TantoName);		#担当名　
	$sheet->setCellValue('B36', $TEL);		#担当会社TEL　
	unset($sheet);

	########################################################
	# 11産廃置き場掲示 生成
	########################################################
	$sheet = $spreadsheet->getSheetByName('産廃置き場掲示');


	if( $SekoShutai == "アイホン株式会社" ){
		#画像貼り付け
		$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
		$picpath = "./images/aiphone.jpg";
		$drawing->setPath($picpath);
		$drawing->setHeight(58);
		$drawing->setCoordinates('K13');
		$drawing->setWorksheet($sheet);
	}else{
		$sheet->setCellValue('K13', $SekoShutai);		#施工主体名
	}

	unset($sheet);
	unset($drawing);



	########################################################
	# 12資材置き場掲示 生成
	########################################################
	$sheet = $spreadsheet->getSheetByName('資材置き場掲示');


	if( $SekoShutai == "アイホン株式会社" ){
		#画像貼り付け
		$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
		$picpath = "./images/aiphone.jpg";
		$drawing->setPath($picpath);
		$drawing->setHeight(58);
		$drawing->setCoordinates('K13');
		$drawing->setWorksheet($sheet);
	}else{
		$sheet->setCellValue('K13', $SekoShutai);		#施工主体名
	}

	unset($sheet);
	unset($drawing);



	########################################################
	# 13駐車証 生成
	########################################################
	$sheet = $spreadsheet->getSheetByName('駐車証');


	if( $SekoShutai == "アイホン株式会社" ){
		#画像貼り付け
		$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
		$picpath = "./images/aiphone.jpg";
		$drawing->setPath($picpath);
		$drawing->setHeight(58);
		$drawing->setCoordinates('K12');
		$drawing->setWorksheet($sheet);
	}else{
		$sheet->setCellValue('K12', $SekoShutai);		#施工主体名
	}

	unset($sheet);
	unset($drawing);


	//ダウンロード用
	//MIMEタイプ：https://technet.microsoft.com/ja-jp/ee309278.aspx
	header("Content-Description: File Transfer");
	$wFileName = "インターホン工事書類・掲示物一式_".$BukkenName.".xlsx" ;
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
