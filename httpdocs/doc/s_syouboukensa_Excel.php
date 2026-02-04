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
	$TantoTEL = getTantoData($myDB, $UserCD )['TEL'];



	########################################################
	# データ取得
	########################################################
	$editBukkenCD = SPFWParameter::getValues('editBukkenCD');
	$JissiDate = SPFWParameter::getValues('JissiDate');
	$JissiStart = SPFWParameter::getValues('JissiStart');
	$JissiFinish = SPFWParameter::getValues('JissiFinish');
	$KaiRoom4 = SPFWParameter::getValues('KaiRoom4');#配列

	
	$myBukken = new Bukken($myDB);

	if (!$myBukken->executeSelect("BukkenCD = ".$editBukkenCD , ""))
		trigger_error("Getting Bukken Failed.", E_USER_ERROR);

	$BukkenName = $myBukken->BukkenName;
	$KanriGaisya = $myBukken->KanriGaisya;
	$KanriGaisyaTEL = $myBukken->KanriGaisyaTEL;
	unset($myBukken);

	########################################################
	# 工事情報取得
	########################################################

	$myKoji = new Koji($myDB);

	if (!$myKoji->executeSelect("BukkenCD = ".$editBukkenCD , ""))
		trigger_error("Getting Bukken Failed.", E_USER_ERROR);

	$KojiName = $myKoji->KojiName;

	$KojiShozokuName = $myKoji->KojiShozokuName; 
	$KojiTantoName = $myKoji->KojiTantoName; 
	$GyosyaTantoCD1 = $myKoji->GyosyaTantoCD1; 
	$GyosyaData = getGyosyaData($myDB, $GyosyaTantoCD1 );
	$GyosyaName = $GyosyaData['GyosyaName'];
	$Address	= $GyosyaData['Address'];



	#資料下部のお問い合わせ先に表示する情報を取得
	$GyosyaTantoName = $GyosyaData['GyosyaTantoName'];
	$GyosyaTantoTEL = $GyosyaData['GyosyaTantoTEL'];

	$SekoShutai = $myKoji->SekoShutai; 

	$TantoTEL = $myKoji->KojiShozokuTEL;

	#|管理会社|1:電話番号表示する 0:しない|アイホン|1:電話番号表示する0:しない|その他会社名|1:電話番号表示する0:しない|電話番号|
	if(!empty($myKoji->ToiawasewakiDisp)){
		$wToiawasewakiDispArr = SPFWTools::decodePluralValue($myKoji->ToiawasewakiDisp); // 文字列を配列に変換
	}else{
		$wToiawasewakiDispArr = null;
	}

	#資料右上に出力する会社名を取得
	if(!empty($myKoji->DispCompanyTop)){
		$DispCompanyTopArr = SPFWTools::decodePluralValue($myKoji->DispCompanyTop);#パイプつなぎを配列に変換
		$DispCompanyTop1 = $DispCompanyTopArr[0];
		$DispCompanyTop2 = $DispCompanyTopArr[1];
		$DispCompanyTop3 = $DispCompanyTopArr[2];
	}else{#登録なければ空文字でセット
		$DispCompanyTop1 = "";
		$DispCompanyTop2 = "";
		$DispCompanyTop3 = "";
	}


	unset($myKoji);



	$format = SPFWParameter::getValues('format');
	switch ($format){
		case 1;
		// 外部試験のみ
			$template_file = './template/1_syouboukensa_gaibu.xlsx';
			$filename = "消防検査案内（外部試験）"; // shouobukensa_gaibu
			break;

		case 2;
		// 火災感知器発報試験
			$template_file = './template/2_syouboukensa_kasaikanchi.xlsm';
			$filename = "消防検査案内（火災感知器発報試験）"; // syouboukensa_kasaikanchi
			break;
		
		case 3;
		// 火災感知器発報試験・外部試験
			$template_file = './template/3_syouboukensa_kasai_gaibu.xlsx';
			$filename = "消防検査案内（火災感知器発報試験・外部試験）"; // syouboukensa_kasai_gaibu
			break;
		
		case 4;
		// 火災感知器発報試験・近隣火災警報鳴動
			$template_file = './template/4_syouboukensa_nyusitu_kinrin.xlsx';
			$filename = "消防検査案内（住戸用）"; // syouboukensa_nyusitu_kyoju
			break;
		
		case 5;
		//	入室検査依頼
			$template_file = './template/5_syouboukensa_nyusitukensa.xlsm';
			$filename = "消防検査案内（共住用 ）"; // syouboukensa_juko
			break;

		case 6;
		//	入室検査依頼
			$template_file = './template/6_syouboukensa_keiji.xlsx';
			$filename = "消防検査案内（掲示用 ）"; // syouboukensa_juko
			break;
	}	




	########################################################
	# Excelファイル生成
	########################################################
	require 'vendor/autoload.php';

	use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;
	$reader = new XlsxReader();

	$spreadsheet = $reader->load( $template_file ); //template.xlsx 読込
	$sheet = $spreadsheet->getActiveSheet();
	
	##-----基本情報のセット
	
	$sheet->setCellValue('A2', $BukkenName);
	$sheet->setCellValue('C6', $KojiName."の完了にともなう");

	#あいさつ文に工事名称を埋め込み
	$tmpCell = $sheet->getCell('B11');	
	$tmpCellStr = $tmpCell->getValue();
	$tmpCellStr = str_replace("●●工事",$KojiName,$tmpCellStr);
	$tmpCell->setValue($tmpCellStr);

	if($format!=5){
		$tmpCell = $sheet->getCell('B14');	
		$tmpCellStr = $tmpCell->getValue();
		$tmpCellStr = str_replace("●●工事",$KojiName,$tmpCellStr);
		$tmpCell->setValue($tmpCellStr);
	}


	$sheet->setCellValue('AK1', date("Y年n月")." 吉日");
#	$sheet->setCellValue('D1', '晴れ');
	
	$weekArray = array("日","月","火","水","木","金","土");
	$sheet->setCellValue('AK3', $KanriGaisya);
	$sheet->setCellValue('I21', date('Y年n月j日',strtotime($JissiDate))."(".$weekArray[ date('w',strtotime($JissiDate))].")");
	$sheet->setCellValue('AA21', $JissiStart."時～".$JissiFinish."時");
	

	#右上に工事案内作成画面にて登録された会社名を表示
	#◆右上に表示する会社名
	$sheet->setCellValue('AK3', $DispCompanyTop1);
	$sheet->setCellValue('AK4', $DispCompanyTop2);
	$sheet->setCellValue('AK5', $DispCompanyTop3);

	
/*	$sheet->setCellValue('A39', "■アイホン株式会社　".$KojiShozokuName );
	$sheet->setCellValue('A40', "（".$GyosyaName."　協力業者）" );
	$sheet->setCellValue('D41', $Address);
	$sheet->setCellValue('A42', "担当：".$KojiTantoName);
	$sheet->setCellValue('K42', "電話：".$TantoTEL);
*/
	#工事案内作成画面にて、お問い合わせの登録があった場合のみ
/*
	if($wToiawasewakiDispArr != null){
		#管理会社
		if ($wToiawasewakiDispArr[0] == "管理会社"){
			#TEL表示？
			if ($wToiawasewakiDispArr[1] == "1"){
				$sheet->setCellValue('D40', $KanriGaisya."　電話：".$KanriGaisyaTEL);
			}else{
				$sheet->setCellValue('D40', $KanriGaisya);
			}
		}else{#値がない場合は表示個所を非表示に
			$sheet->getRowDimension('39')->setVisible(false);
			$sheet->getRowDimension('40')->setVisible(false);
		}
		#その他会社
		if ($wToiawasewakiDispArr[4] != ""){	#化社名の登録があれば表示
			$sheet->setCellValue('C41', "◆連絡先会社　");
			if ($wToiawasewakiDispArr[5] == "1"){
				$sheet->setCellValue('D42', $wToiawasewakiDispArr[4]."　電話：".$wToiawasewakiDispArr[6]);
			}else{
				$sheet->setCellValue('D42', $wToiawasewakiDispArr[4]);
			}
		}else{	#なければ非表示
			$sheet->getRowDimension('41')->setVisible(false);
			$sheet->getRowDimension('42')->setVisible(false);
		}

		if ($wToiawasewakiDispArr[2] == "アイホン"){
			if ($wToiawasewakiDispArr[3] == "1"){
				$sheet->setCellValue('D44',  "担当：".$KojiTantoName."　電話：".$TantoTEL."（9時～17時30分 土日祝休み）" );
			}else{
				$sheet->setCellValue('D44', $KojiTantoName);			
			}

		}else{
			$sheet->getRowDimension('43')->setVisible(false);
			$sheet->getRowDimension('44')->setVisible(false);
		}
	}
*/

	#お問い合わせ先に出力 201905 変更
	#施工主体がアイホンの場合（元請け）①アイホン
	if ($SekoShutai == 'アイホン株式会社'){
		$sheet->setCellValue('M41', $KojiShozokuName );
		$sheet->setCellValue('D42',  "担当：".$KojiTantoName."　電話：".$TantoTEL."（9時～17時30分　土日祝休み）" );
		
		
		#不要行を削除
		$sheet->getRowDimension('39')->setVisible(false);
		$sheet->getRowDimension('40')->setVisible(false);

	}else{	#①管理会社	②アイホン
		$sheet->setCellValue('D40',  $KanriGaisya."　電話：".$KanriGaisyaTEL);

		$sheet->setCellValue('M41', $KojiShozokuName );
		$sheet->setCellValue('D42',  "担当：".$KojiTantoName."　電話：".$TantoTEL."（9時～17時30分　土日祝休み）" );
		
	}
	
	
	if($format==2||$format==5){
		$sheet->setCellValue('A3', $KaiRoom4[0]);
	}
	if($format==2||$format==5){
		for($i=1 ; $i<count($KaiRoom4) ; $i++){

			$clonedWorksheet = clone $spreadsheet->getSheetByName('Sheet1');
			$j= $i+1;
			$clonedWorksheet->setTitle('Sheet'.$j);
			$spreadsheet->addSheet($clonedWorksheet);
			$sheet = $spreadsheet->getSheetByName('Sheet'.$j); //weatherシート取得
			$sheet->setCellValue('A3', $KaiRoom4[$i]);

		}
	}
	//ダウンロード用
	//MIMEタイプ：https://technet.microsoft.com/ja-jp/ee309278.aspx
	header("Content-Description: File Transfer");
	$wFileName = $filename."_".$BukkenName.".xlsx" ;
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
