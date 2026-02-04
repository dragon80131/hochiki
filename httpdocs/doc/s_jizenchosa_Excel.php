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
	$GyosyaTantoName = $GyosyaData['GyosyaTantoName'];
	$GyosyaTantoTEL = $GyosyaData['GyosyaTantoTEL'];

	$ConstTime = SPFWParameter::getValues('SagyoJikan'); 
	
	$SekoShutai = $myKoji->SekoShutai; //201905 ADD

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
		#工事前・事前確認作業
		if ($wOtherwise[$j] == 4){
			$i=$j+1;
			$wSagyoDay = $wOtherwise[$i];}
	}
	
	
	#資料下部のお問い合わせ先に表示する情報を取得
	$TantoTEL = $myKoji->KojiShozokuTEL;

	if(!empty($myKoji->ToiawasewakiDisp)){
		#|管理会社|1:電話番号表示する 0:しない|アイホン|1:電話番号表示する0:しない|その他会社名|1:電話番号表示する0:しない|電話番号|
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



	$JissiDate = SPFWParameter::getValues('JissiDate');
	$JissiStartH = SPFWParameter::getValues('JissiStartH');
	$JissiFinishH = SPFWParameter::getValues('JissiFinishH');
	$format = SPFWParameter::getValues('format');

	if ($format == "1") {
		// 入室なし
		$template_file = './template/2_jizenchosa_nyusitunasi.xlsx';

		$filename = "事前調査案内（入室なし）";

	} else if ($format == "2") {
		// 一部入室
		$template_file = './template/2_jizenchosa_itibunyusitu.xlsx';

		$filename = "事前調査案内（一部入室あり）";
	} else if ($format == "3") {

		$template_file = './template/2_jizenchosa_itibunyusitu1-1.xlsx';
		$filename = "事前調査案内（一部入室あり（1対1））";

	} else if ($format == "4"){
		$template_file = './template/2_jizenchosa_itibunyusitu_baai.xlsx';
		$filename = "事前調査案内（場合により入室あり）";

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

	$sheet->setCellValue('A2', $BukkenName.'にお住まいの皆様へ');
	$sheet->setCellValue('A6', $KojiName."事前確認作業のご案内");
	$sheet->setCellValue('AM1', date("Y年n月")." 吉日");
	$sheet->setCellValue('AM3', $KanriGaisya);

	$sheet->setCellValue('B9', "ご承認頂きました".$KojiName."が、着工の運びとなりました。");



	if($JissiDate != 0){
		$weekArray = array("日","月","火","水","木","金","土");
		$sheet->setCellValue('H15', date('Y年n月j日',strtotime($JissiDate))."(".$weekArray[ date('w',strtotime($JissiDate))].")" );
	}
	else{
		// echo "<br>155行目"."事前工事日がない場合は作業日付のセットをしていないです";
	}

	$sheet->setCellValue('T15', $JissiStartH."時～".$JissiFinishH."時");
	
	
	
	if($format == "3"){
		$sheet->setCellValue('Y26', "約".$ConstTime."分");
	}elseif($format == "2"){
		$sheet->setCellValue('X27', "約".$ConstTime."分");
	}

	#お問い合わせ先に出力 201905 変更
	#施工主体がアイホンの場合（元請け）①アイホン
	if ($SekoShutai == 'アイホン株式会社'){
		$sheet->setCellValue('L37', $KojiShozokuName );
		$sheet->setCellValue('C38',  "担当：".$KojiTantoName."　電話：".$TantoTEL."（9時～17時30分　土日祝休み）" );
		
		#不要行を削除
		$sheet->getRowDimension('35')->setVisible(false);
		$sheet->getRowDimension('36')->setVisible(false);

	}else{	#①管理会社	②アイホン
		$sheet->setCellValue('C36', $KanriGaisya."　電話：".$KanriGaisyaTEL);

		$sheet->setCellValue('L37', $KojiShozokuName );
		$sheet->setCellValue('C38',  "担当：".$KojiTantoName."　電話：".$TantoTEL."（9時～17時30分　土日祝休み）" );
		
	}

/*
	if ($format == "1") {
		$sheet->setCellValue('B33', "■アイホン株式会社　".$KojiShozokuName );
		$sheet->setCellValue('B34', "（".$GyosyaName."　協力業者）" );
		$sheet->setCellValue('B35', "担当：".$KojiTantoName);
		$sheet->setCellValue('N35', "電話：".$TantoTEL);
	}else{

		$sheet->setCellValue('A35', "■アイホン株式会社　".$KojiShozokuName );
		$sheet->setCellValue('A36', "（".$GyosyaName."　協力業者）" );
		$sheet->setCellValue('A37', "担当：".$KojiTantoName);
		$sheet->setCellValue('N37', "電話：".$TantoTEL);
		if($format == "3")			
			$sheet->setCellValue('Z26', "約".$ConstTime."分");
		elseif($format !== "4")			
			$sheet->setCellValue('Z27', "約".$ConstTime."分");
	
	}
*/

/*	#右上に工事案内作成画面にて登録された会社名を表示
	#◆右上に表示する会社名
	$sheet->setCellValue('AM3', $DispCompanyTop1);
	$sheet->setCellValue('AM4', $DispCompanyTop2);
	$sheet->setCellValue('AM5', $DispCompanyTop3);
	
	#工事案内作成画面にて、お問い合わせの登録があった場合のみ
	if($wToiawasewakiDispArr != null){
		#管理会社
		if ($wToiawasewakiDispArr[0] == "管理会社"){
			#TEL表示？
			if ($wToiawasewakiDispArr[1] == "1"){
				$sheet->setCellValue('C36', $KanriGaisya."　電話：".$KanriGaisyaTEL);
			}else{
				$sheet->setCellValue('C36', $KanriGaisya);
			}
		}else{#値がない場合は表示個所を非表示に
			$sheet->getRowDimension('35')->setVisible(false);
			$sheet->getRowDimension('36')->setVisible(false);
		}
		#その他会社
		if ($wToiawasewakiDispArr[4] != ""){	#化社名の登録があれば表示
			$sheet->setCellValue('B37', "◆連絡先会社　");
			if ($wToiawasewakiDispArr[5] == "1"){
				$sheet->setCellValue('C38', $wToiawasewakiDispArr[4]."　電話：".$wToiawasewakiDispArr[6]);
			}else{
				$sheet->setCellValue('C38', $wToiawasewakiDispArr[4]);
			}
		}else{	#なければ非表示
			$sheet->getRowDimension('37')->setVisible(false);
			$sheet->getRowDimension('38')->setVisible(false);
		}

		if ($wToiawasewakiDispArr[2] == "アイホン"){
			if ($wToiawasewakiDispArr[3] == "1"){
				$sheet->setCellValue('C40',  "担当：".$KojiTantoName."　電話：".$TantoTEL."（9時～17時30分　土日祝休み）" );
			}else{
				$sheet->setCellValue('C40', $KojiTantoName);			
			}

		}else{
			$sheet->getRowDimension('39')->setVisible(false);
			$sheet->getRowDimension('40')->setVisible(false);
		}

	}*/

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
