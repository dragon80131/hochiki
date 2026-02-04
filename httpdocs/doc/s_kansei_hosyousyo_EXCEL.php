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
	include_once _CLS_DIR . "SPUSKoji.cls";
	include_once _CLS_DIR . "SPUSEigyosho.cls";

	include_once "../include/kenmei_connect.php";
	include_once "../include/common.php";

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
		$ErrorString = array();
		$ErrorString[] = "tBukkenM情報の抽出に失敗しました。";
		showSorryPage($ErrorString);
	}
	if ($myBukken->RecCnt == 0) {
		showSorryPage('システムに必要な情報が設定されていません。');
	}

	$wBukkenCD = $myBukken->BukkenCD;
	$wBukkenName = $myBukken->BukkenName;
	$wTantoCD = $myBukken->TantoCD;
	$wShozokuCD = $myBukken->ShozokuCD;
	$KenmeiNo = $myBukken->KenmeiNo;
	$BSYO_CD 	= getTantoData($myDB, $wTantoCD)['EigyoshoCD'];

	$myEigyosho = new Eigyosho($myDB);

	if (!$myEigyosho->executeSelect("EigyoshoCD = " . $BSYO_CD . " AND MukouFlg = FALSE", "")){
		trigger_error("Getting Koji Failed.", E_USER_ERROR);
	}
	
	$BSYO_MEI = $myEigyosho->EigyoshoName;
	$ADDRESS = $myEigyosho->EigyoshoAddress;
	$TEL = $myEigyosho->TEL;
	$FAX = $myEigyosho->FAX;

	########################################################
	# パラメータ取得
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
		$HosyouSdate = $myKoji->SenyuEndDate;
		$HosyouY = substr($HosyouSdate, 0, 4);
		$HosyouM = substr($HosyouSdate, 5, 2);
		$HosyouD = substr($HosyouSdate, 8, 2);
		$HosyouD += 1;
	}

	########################################################
	# Milky 値取得
	########################################################
	if($KenmeiNo){
		$myListObject = new SPFWListObject($myDB);

		$sql = "SELECT ";
		$sql .= "MEISAI_KIKI_KBN, ";
		$sql .= "SYHN_CD, ";
		$sql .= "HINBAN, ";
		$sql .= "HINMEI_SIYO, ";
		$sql .= "JUC_SURYO ";
		$myListObject->SelectSQL = $sql;

		$sql = " FROM tBukkenKikiM";
		$sql .= " WHERE KenmeiNo = '".$KenmeiNo."' AND MukouFlg = FALSE";

		$myListObject->Condition = $sql;
		$myListObject->Order = "JUC_GYO_NO";
		$myListObject->Limit = "allpage";

		if (!($myListObject->GetList(1)))
			trigger_error("Getting Menu List Failed.", E_USER_ERROR);

		if ($myListObject->Rows != 0) {
			$MeisaiLoop = $myListObject->Rows;
			for ($i = 0; $i < $MeisaiLoop; $i++) {
				$No[$i] = $i + 1;
				$MEISAI_KIKI_KBN[$i] = $myListObject->GetValue($i, 0);
				#明細機器区分　0：標準品／1：モジュール品／2：受注品／A：工事費／B：他社品／C：コメント／D：220号他社品／+：合計／/：値引き
				switch ($MEISAI_KIKI_KBN){
					case "0":
					$MEISAI_KIKI_KBN[$i] = "標準品";
					break;
					case "1":
					$MEISAI_KIKI_KBN[$i] = "モジュール品";
					break;
					case "2":
					$MEISAI_KIKI_KBN[$i] = "受注品";
					break;
					case "A":
					$MEISAI_KIKI_KBN[$i] = "工事費";
					break;
					case "B":
					$MEISAI_KIKI_KBN[$i] = "他社品";
					break;
					case "D":
					$MEISAI_KIKI_KBN[$i] = "220号他社品";
					break;
					default:
					$MEISAI_KIKI_KBN[$i] = "-";
				}
				$SYHN_CD[$i] = $myListObject->GetValue($i, 1);
				$HINBAN[$i] = $myListObject->GetValue($i, 2);
				$HINMEI_SIYO[$i] = $myListObject->GetValue($i, 3);

				#OyaArrayに、HINBANがあれば、親機
				if(strpos($HINMEI_SIYO[$i],"住宅情報盤")!== false or strpos($HINMEI_SIYO[$i],"親機")!== false  ){

		 			if ( is_array( getOyaData( $myDB, $HINBAN[$i] ) ) ){
						$wOyaKataban = $HINBAN[$i];#
					}
				}
				#KoArrayに、HINBANがあれば、玄関子機
				if(strpos($HINMEI_SIYO[$i],"玄関子機")!== false ){
					$KoData = getKoData( $myDB, $HINBAN[$i] );
		 			if ( is_array( $KoData ) ){
						$wKokiKataban = $HINBAN[$i];#
					}
				}

				if(strpos($HINMEI_SIYO[$i],"集合玄関機")!== false ){
					$AutoLockChecked1 = " checked ";
					$AutoLockChecked2 = "";
					$wShuGenKataban = $HINBAN[$i];
				}
				$JUC_SURYO[$i] = $myListObject->GetValue($i, 4);
			}
		}
		unset($myListObject);

	}



	
		#件名テーブル
/*		$KenmeiData = getKenmeiOneData($KenmeiNo);#include/kenmei_connect.phpに定義

		$BKN_NO = $KenmeiData['BKN_NO'];
		$BKN_NM_ALL = $KenmeiData['BKN_NM_ALL'];#★物件名
		$KANRI_BSYO_CD = $KenmeiData['KANRI_BSYO_CD'];
		$KANRI_SYIN_CD = $KenmeiData['KANRI_SYIN_CD'];
		$JYUSYO_JKY = $KenmeiData['JYUSYO_JKY1'].$KenmeiData[0]['JYUSYO_JKY2'].$KenmeiData[0]['JYUSYO_JKY3'].$KenmeiData[0]['JYUSYO_JKY4'];#★住所
		$KAIKO = $KenmeiData['KAIKO'];#★
		$KO_SU = $KenmeiData['KO_SU'];#★
		$HNB_SIJO_KBN = $KenmeiData['HNB_SIJO_KBN'];
		$KANMIN_KBN = $KenmeiData['KANMIN_KBN'];
		$NAITEI_YMD_D = $KenmeiData['NAITEI_YMD_D'];#内定日
		$KANRI_BLOCK_CD = $KenmeiData['KANRI_BLOCK_CD'];#支店CD（ブロックCD)

		$JyuchuData = getKenmeiJyuchuH($KenmeiNo);

		$JUC_NO = $JyuchuData['JUC_NO'];

		$JyuchuMData = getKenmeiJyuchuM($JUC_NO);

		for($i = 0; $i < count($JyuchuMData); $i++){
			$HINBAN[] = $JyuchuMData[$i]['HINBAN'];
			$HINMEI_SIYO[] = $JyuchuMData[$i]['HINMEI_SIYO'];
			$JUC_SURYO[] = $JyuchuMData[$i]['JUC_SURYO'];
			$TANI[] = $JyuchuMData[$i]['TANI'];
		}
		#受注
	}
	if($KANRI_BSYO_CD){

		$BsyoData = getKenmeiBusyo($KANRI_BSYO_CD);#include/kenmei_connect.phpに定義

		$BSYO_MEI = $BsyoData['BSYO_MEI'];
		$BMON_CD = $BsyoData['BMON_CD'];
		$ZIP = $BsyoData['ZIP'];
		$ADDRESS = $BsyoData['ADDRESS'];
		$TEL = $BsyoData['TEL'];
		$FAX = $BsyoData['FAX'];

		$BmonData = getKenmeiBumon($BMON_CD);#include/kenmei_connect.phpに定義

		$BMON_MEI = $BmonData['BMON_MEI'];

		$ZIP = substr($ZIP,0,3) . "　-　" . substr($ZIP,3);

	}
*/
	########################################################
	# 関数群
	########################################################


	function getOyaData($myDB , $Hinban){
		// 機器カテゴリで、親機と子機の配列をつくる。
		$myListObject = new SPFWListObject($myDB);

		$sql = "SELECT ";
		$sql .= "DeviceName, ";
		$sql .= "Kataban, ";
		$sql .= "D003, ";#機器説明
		$sql .= "ShortName ";
		$myListObject->SelectSQL = $sql;

		$sql = " FROM tDeviceM";
		$sql .= " WHERE MukouFlg = FALSE and Category = '1'";#親機
		$sql .= " AND Kataban = '".$Hinban."' ";

		$myListObject->Condition = $sql;
		$myListObject->Order = "Kataban";
		$myListObject->Limit = "allpage";

		if (!($myListObject->GetList(1)))
			trigger_error("Getting Menu List Failed.", E_USER_ERROR);

		if ($myListObject->Rows == 1) {
	#		$OyaLoop = $myListObject->Rows;
	#		for ($i = 0; $i < $OyaLoop; $i++) {
				$OyaData['DeviceName'] 	= $myListObject->GetValue(0, 0);
				$OyaData['Kataban'] 	= $myListObject->GetValue(0, 1);
				$OyaData['KikiSetumei'] = $myListObject->GetValue(0, 2);
				$OyaData['ShortName'] 	= $myListObject->GetValue(0, 3);
	#		}

		}
		return $OyaData ;
		unset($myListObject);
	}

	#品番前方一致
	function getKoData($myDB , $Hinban){

		$myListObject = new SPFWListObject($myDB);

		$sql = "SELECT ";
		$sql .= "DeviceName, ";
		$sql .= "Kataban, ";
		$sql .= "D003, ";#機器説明
		$sql .= "ShortName ";
		$myListObject->SelectSQL = $sql;

		$sql = " FROM tDeviceM";
		$sql .= " WHERE MukouFlg = FALSE and Category = '2' ";
		$sql .= " AND Kataban = '".$Hinban."' ";

		$myListObject->Condition = $sql;
		$myListObject->Order = "Kataban";
		$myListObject->Limit = "allpage";

		if (!($myListObject->GetList(1)))
			trigger_error("Getting Menu List Failed.", E_USER_ERROR);

		if ($myListObject->Rows == 1) {
			$KoData['DeviceName'] 	= $myListObject->GetValue(0, 0);
			$KoData['Kataban'] 		= $myListObject->GetValue(0, 1);
			$KoData['KikiSetumei'] 	= $myListObject->GetValue(0, 2);
			$KoData['ShortName'] 	= $myListObject->GetValue(0, 3);
		}
		unset($myListObject);
		return $KoData ;
	}

	########################################################
	# Excelファイル生成
	########################################################
	require 'vendor/autoload.php';

	use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;
	$reader = new XlsxReader();

	$spreadsheet = $reader->load('./template/kansei_hosyousyo.xlsx'); //template.xlsx 読込

	// 1シート目
	$sheet = $spreadsheet->getSheetByName('Sheet1');

	$sheet->setCellValue('G3', $wBukkenName." 管理組合");
	$sheet->setCellValue('H5', $wBukkenName);

	$sheet->setCellValue('J30', $HosyouY);
	$sheet->setCellValue('M30', $HosyouM);
	$sheet->setCellValue('O30', $HosyouD);

	$sheet->setCellValue('D34', $ZIP);
	$sheet->setCellValue('D35', $ADDRESS);

	$sheet->setCellValue('H37', $BMON_MEI);
	$sheet->setCellValue('H38', $BSYO_MEI);
	$sheet->setCellValue('E39', $TEL);
	$sheet->setCellValue('M39', $FAX);
	$sheet->setCellValue('U41', $KenmeiNo);

	// 2シート目
	$sheet = $spreadsheet->getSheetByName('Sheet2');
	$gyo = 6;
	for($i = 0; $i < count($JyuchuMData); $i++){
		$gyo += $i;
		$sheet->setCellValue('B'.$gyo, $HINBAN[$i]);
		$sheet->setCellValue('G'.$gyo, $HINMEI_SIYO[$i]);
		$sheet->setCellValue('O'.$gyo, $JUC_SURYO[$i]);
		$sheet->setCellValue('R'.$gyo, $JUC_SURYO[$i]);
	}
	$sheet->setCellValue('U3', $KenmeiNo);



	//ダウンロード用
	//MIMEタイプ：https://technet.microsoft.com/ja-jp/ee309278.aspx
	header("Content-Description: File Transfer");
	$wFileName = "システム保証書_".$wBukkenName.".xlsx" ;
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
