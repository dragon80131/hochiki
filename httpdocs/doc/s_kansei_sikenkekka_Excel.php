<?php
/* 
 * 完成図書 試験結果出力
 * s_kansei_sikenkekka_Excel.php
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
		$KanriGaisya = $myBukken->KanriGaisya;
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

	} else {
		$KojiName = $myKoji->KojiName;
		$SenyuStartDate = $myKoji->SenyuStartDate;
		$SenyuEndDate = $myKoji->SenyuEndDate;
		
		$shikenKikan = date('Y年n月j日',strtotime( $SenyuStartDate ));

		if(!empty($SenyuEndDate) AND $SenyuStartDate != $SenyuEndDate){#専有部が1日以上だけの場合
			$shikenKikan .= '～'.date('n月j日',strtotime( $SenyuEndDate ));
		}
		
/*		$weekarray = array("(日)","(月)","(火)","(水)","(木)","(金)","(土)");
		$ZentaiStartDate 	= date('Y年m月d日',strtotime( $myKoji->ZentaiStartDate ));
		$ZentaiStartDate 	.= $weekarray[ date('w',strtotime( $myKoji->ZentaiStartDate )) ];
		$ZentaiEndDate		= date('Y年m月d日',strtotime( $myKoji->ZentaiEndDate ));
		$ZentaiEndDate 		.= $weekarray[ date('w',strtotime( $myKoji->ZentaiEndDate )) ];
*/	}
	unset($myKoji);


	########################################################
	# 部屋構成情報抽出
	########################################################

	$myBukkenMatrix = new BukkenMatrix($myDB);

	if (!$myBukkenMatrix->executeSelect("BukkenCD = " . $editBukkenCD . "  AND MukouFlg = FALSE", "")){
		trigger_error("Getting BukkenMatrix Failed.", E_USER_ERROR);
	}

	if ($myBukkenMatrix->RecCnt != 1) {

		#部屋情報登録がまだ
		$ErrorString = array();
		$ErrorString[] = "工事情報を登録してください。";
		$ErrorLoop = count($ErrorString);
		$myTemplate = new SPFWTemplate(_ERROR_TPL, $MyCarrier, TRUE);
		exit;

	} else {

		$KaiRoom = $myBukkenMatrix->KaiRoom;
		$KaiRoomArray = SPFWTools::decodePluralValue($KaiRoom);
	}
	unset($myBukkenMatrix);



	########################################################
	# Excelファイル生成
	########################################################
	require 'vendor/autoload.php';

	use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;
	$reader = new XlsxReader();

	$spreadsheet = $reader->load('./template/kansei_sikenkekka.xlsx'); //template.xlsx 読込


	#シートを指定して各項目をセットしていく
	$sheet = $spreadsheet->getSheetByName('試験結果報告書');

	$sheet->setCellValue('B1', $KojiName.'試験成績表');
	$sheet->setCellValue('C2', '件名　'.$BukkenName);
	$sheet->setCellValue('J2', $shikenKikan);
	
#テスト
#array_push($KaiRoomAray,"901","901","901","901","901","901","901","902");

	$gyo = 5;
	for ($i = 0; $i < count($KaiRoomArray); $i++) {
		if($gyo == 31){	#レイアウト1ページ目最後まで来たら備考欄を飛ばして次ページに入力
			$gyo = 35;
		}
		$sheet->setCellValue('C'.$gyo, $KaiRoomArray[$i]);
		$gyo += 1;
	}

	if ($gyo <= 30) {
		//$gyo = $gyo + 3;
		// 印刷範囲
		//$sheet->getPageSetup()->setPrintArea('B1:AB'.$gyo);
		$sheet->getPageSetup()->setPrintArea('B1:S34');
	}else{
		$sheet->getPageSetup()->setPrintArea('B1:S64');	
	}


	//ダウンロード用
	//MIMEタイプ：https://technet.microsoft.com/ja-jp/ee309278.aspx
	header("Content-Description: File Transfer");
	$wFileName = "完成図書_試験結果報告書_".$BukkenName.".xlsx" ;
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
