<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', "On");


#施工指示書PATMO・GBM-2M（K）　Excel出力

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
	include_once _CLS_DIR . "SPUSKikiSettei.cls";


	include_once  "../include/common.php";



	// データベースコネクト
	$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
	if (!$myDB->Connection)
		trigger_error("SPFWDatabase Failed.", E_USER_ERROR);

	$rKey = SPFWParameter::getValues('rKey');
	$editBukkenCD = SPFWParameter::getValues('editBukkenCD');
	########################################################
	# 認証動作
	########################################################
	echo $editBukkenCD;

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
	# 物件情報抽出
	########################################################
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

	########################################################
	# パラメータ取得
	########################################################
	
	#DB登録済みのデータを取得
	$myKikiSettei = new KikiSettei($myDB);
	if(!$myKikiSettei->executeSelect("BukkenCD = ".$editBukkenCD." AND KikiCD = 5",  "")){
	}
	
	for($i=1 ; $i< 32 ; $i++){
		if($i<16)
			${"wShugoSettei".$i} = $myKikiSettei->{"ShugoSettei".$i};
		if($i<11)
			${"wOyakiSettei".$i} = $myKikiSettei->{"OyakiSettei".$i};
		if($i<4)
			${"wJutakuMenuSettei".$i} = $myKikiSettei->{"JutakuMenuSettei".$i};

		if($i>4 && $i<32)
			${"wJutakuServiceSettei".$i} = $myKikiSettei->{"JutakuServiceSettei".$i};
	}

	########################################################
	# DB連携
	########################################################

	$myKikiSettei = new KikiSettei($myDB);

	#初期値を取得
	if(!$myKikiSettei->executeSelect("KikiCD = 5 AND BukkenCD is NULL",""))
		trigger_error("executeSelect(myKikiSettei) Failed" , E_USER_ERROR);

	for($i=1 ;$i<32;$i++ ){
		if($i<16)
			${"dShugoSettei".$i} = $myKikiSettei->{"ShugoSettei".$i};
		if($i<11)
			${"dOyakiSettei".$i} = $myKikiSettei->{"OyakiSettei".$i};
		if($i<4)
			${"dJutakuMenuSettei".$i} = $myKikiSettei->{"JutakuMenuSettei".$i};
		if($i>4 && $i<32)
			${"dJutakuServiceSettei".$i} = $myKikiSettei->{"JutakuServiceSettei".$i};
	}

	unset($myKikiSettei);




	########################################################
	# 初期設定と比較
	########################################################

#値を入力するセル位置を定義
//集合玄関機設定
	$SHUGOSETTEI[1]="R19";
	$SHUGOSETTEI[2]="R20";
	$SHUGOSETTEI[3]="R21";
	$SHUGOSETTEI[4]="R22";
	$SHUGOSETTEI[5]="R29";
	$SHUGOSETTEI[6]="R30";
	$SHUGOSETTEI[7]="AW13";
	$SHUGOSETTEI[8]="AW14";
	$SHUGOSETTEI[9]="AW15";
	$SHUGOSETTEI[10]="AW16";
	$SHUGOSETTEI[11]="AW17";
	$SHUGOSETTEI[12]="AW18";
	$SHUGOSETTEI[13]="AW19";
	$SHUGOSETTEI[14]="AW20";
	$SHUGOSETTEI[15]="AW23";
	
//管理室親機設定
	$OYAKISETTEI[1]="R14";
	$OYAKISETTEI[2]="AW12";
	$OYAKISETTEI[3]="AW13";
	$OYAKISETTEI[4]="AW14";
	$OYAKISETTEI[5]="AW15";
	$OYAKISETTEI[6]="AW16";
	$OYAKISETTEI[7]="AW17";
	$OYAKISETTEI[8]="AW18";
	$OYAKISETTEI[9]="AW19";
	$OYAKISETTEI[10]="AW21";


#居室親機設定指示書
	//メニュー画面からの設定
	$JUTAKUMENUSETTEI[1]="M7";
	$JUTAKUMENUSETTEI[2]="M9";
	$JUTAKUMENUSETTEI[3]="M11";

	//施工設定
	$JUTAKUSERVICESETTEI[5]="U16";
	$JUTAKUSERVICESETTEI[6]="U17";
	$JUTAKUSERVICESETTEI[7]="U18";
	$JUTAKUSERVICESETTEI[8]="M19";
	$JUTAKUSERVICESETTEI[9]="M20";
	$JUTAKUSERVICESETTEI[10]="M21";
	$JUTAKUSERVICESETTEI[11]="U22";
	$JUTAKUSERVICESETTEI[12]="U23";
	$JUTAKUSERVICESETTEI[13]="U24";
	$JUTAKUSERVICESETTEI[14]="U25";
	$JUTAKUSERVICESETTEI[15]="U26";
	$JUTAKUSERVICESETTEI[16]="U27";
	$JUTAKUSERVICESETTEI[17]="U28";
	$JUTAKUSERVICESETTEI[18]="U29";
	$JUTAKUSERVICESETTEI[19]="U30";
	$JUTAKUSERVICESETTEI[20]="U31";
	$JUTAKUSERVICESETTEI[21]="U32";
	$JUTAKUSERVICESETTEI[22]="U33";
	$JUTAKUSERVICESETTEI[23]="U34";
	$JUTAKUSERVICESETTEI[24]="U35";
	$JUTAKUSERVICESETTEI[25]="U36";
	$JUTAKUSERVICESETTEI[26]="U37";
	$JUTAKUSERVICESETTEI[27]="U38";
	$JUTAKUSERVICESETTEI[28]="U39";
	$JUTAKUSERVICESETTEI[29]="U40";
	$JUTAKUSERVICESETTEI[30]="U41";
	$JUTAKUSERVICESETTEI[31]="U42";


	for($i=1 ;$i<32 ;$i++ ){
		if($i<16){
			if(${"dShugoSettei".$i} !== ${"wShugoSettei".$i}){
				$ShugoSetteiPlace[]=$SHUGOSETTEI[$i];
			}else{
				${"wShugoSettei".$i} = "";
			}
		}
		if($i<11){
			if(${"dOyakiSettei".$i} !== ${"wOyakiSettei".$i}){
				$OyakiSetteiPlace[] = $OYAKISETTEI[$i];
			}else{
				${"wOyakiSettei".$i."Value"} = "";
			}
		}
		if($i<4){
			if(${"dJutakuMenuSettei".$i} !== ${"wJutakuMenuSettei".$i}){
				$JutakuSetteiPlace[] = $JUTAKUMENUSETTEI[$i];
			}else{
				${"wJutakuMenuSettei".$i} = "";
			}
		}
		if($i>4 && $i<32){
#		if($i == 27){
#			echo "shoki ".${"dJutakuServiceSettei".$i};
#			echo "gamen ".${"wJutakuServiceSettei".$i};
			
#		}
			if(${"dJutakuServiceSettei".$i} !== ${"wJutakuServiceSettei".$i}){
				$JutakuSetteiPlace[] = $JUTAKUSERVICESETTEI[$i];
			}else{
				${"wJutakuServiceSettei".$i} = "";
			}
		}
	}
#		showSorryPage(_ILLEGAL_ACCESS);

	########################################################
	# Excelファイル生成
	########################################################
	require 'vendor/autoload.php';

	use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;
	use PhpOffice\PhpSpreadsheet\Style;
	use PhpOffice\PhpSpreadsheet\Style\Fill;

	$reader = new XlsxReader();
	$spreadsheet = $reader->load('./template/kikisettei_PATMO_GBM-2M_K_.xlsx'); //template.xlsx 読込


#表示文言を定義

#あり/なし
$ARINASHI_SUJI[0] = "1：あり";
$ARINASHI_SUJI[1] = "2：なし";

#押しボタン方式
$OSIBUTTON_2[0] = "ワンショット式";
$OSIBUTTON_2[1] = "ロック式";

#玄関子機出力
$GENKANKOKISYUTURYUOKU[0] = "無";
$GENKANKOKISYUTURYUOKU[1] = "有：0秒";
$GENKANKOKISYUTURYUOKU[2] = "有：30秒";

#有/無
$YUUKOU[0] = "有効";
$YUUKOU[1] = "無効";


#プリトーン回数
$PRETONEKAISU[0] = "0：0回";
$PRETONEKAISU[1] = "1：1回";
$PRETONEKAISU[2] = "2：2回";
$PRETONEKAISU[3] = "3：3回";
$PRETONEKAISU[4] = "4：4回";

#プリトーン音量/表示明るさ
$PRETONEONRYO_AKARUSA[0] = "1：1";
$PRETONEONRYO_AKARUSA[1] = "2：2";
$PRETONEONRYO_AKARUSA[2] = "3：3";
$PRETONEONRYO_AKARUSA[3] = "4：4";
$PRETONEONRYO_AKARUSA[4] = "5：5";
$PRETONEONRYO_AKARUSA[5] = "6：6";
$PRETONEONRYO_AKARUSA[6] = "7：7";
$PRETONEONRYO_AKARUSA[7] = "8：8";


#表示時間
$HYOJIJIKAN[0] = "1：5秒";
$HYOJIJIKAN[1] = "2：10秒";
$HYOJIJIKAN[2] = "3：20秒";
$HYOJIJIKAN[3] = "4：30秒";
$HYOJIJIKAN[4] = "5：60秒";

#サービス種類
$SERVICESYURUI[0] = "非常";
$SERVICESYURUI[1] = "汎用1";
$SERVICESYURUI[2] = "汎用2";
$SERVICESYURUI[3] = "防犯1";
$SERVICESYURUI[4] = "使用しない";

	#集合玄関機設定
	$sheet = $spreadsheet->getSheetByName('集玄設定表'); //weatherシート取得
		#物件名の表示
		$sheet->setCellValue('AC3',$BukkenName);

		$sheet->setCellValue($SHUGOSETTEI[1], $ARINASHI_SUJI[$wShugoSettei1]);
		$sheet->setCellValue($SHUGOSETTEI[2], $PRETONEKAISU[$wShugoSettei2]);
		$sheet->setCellValue($SHUGOSETTEI[3], $PRETONEKAISU[$wShugoSettei3]);
		$sheet->setCellValue($SHUGOSETTEI[4], $PRETONEONRYO_AKARUSA[$wShugoSettei4]);
		$sheet->setCellValue($SHUGOSETTEI[5], $HYOJIJIKAN[$wShugoSettei5]);
		$sheet->setCellValue($SHUGOSETTEI[6], $PRETONEONRYO_AKARUSA[$wShugoSettei6]);


		$sheet->setCellValue($SHUGOSETTEI[7], $IHOUSETTEN[$wShugoSettei7]);	#489.properties
		$sheet->setCellValue($SHUGOSETTEI[8], $IHOUSETTEN[$wShugoSettei8]);	
		$sheet->setCellValue($SHUGOSETTEI[9], $IHOUSETTEN[$wShugoSettei9]);	
		$sheet->setCellValue($SHUGOSETTEI[10], $IHOUSETTEN[$wShugoSettei10]);
		$sheet->setCellValue($SHUGOSETTEI[11], $IHOUSETTEN[$wShugoSettei11]);
		$sheet->setCellValue($SHUGOSETTEI[12], $IHOUSETTEN[$wShugoSettei12]);
		$sheet->setCellValue($SHUGOSETTEI[13], $IHOUSETTEN[$wShugoSettei13]);
		$sheet->setCellValue($SHUGOSETTEI[14], $IHOUSETTEN[$wShugoSettei14]);

		$sheet->setCellValue($SHUGOSETTEI[15], $ARINASHI_SUJI[$wShugoSettei15]);

		//初期値と値が異なっていたら赤色にする
		for($i=0;$i<count($ShugoSetteiPlace);$i++){
			$spreadsheet->getSheetByName('集玄設定表')->getStyle($ShugoSetteiPlace[$i])->getFill()
				->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
				->getStartColor()->setARGB('FFBAB3');
		}

	#管理室親機設定
	$sheet = $spreadsheet->getSheetByName('管親設定表'); //weatherシート取得
		$sheet->setCellValue($OYAKISETTEI[1], $PRETONEONRYO_AKARUSA[$wOyakiSettei1]);
		$sheet->setCellValue($OYAKISETTEI[2], $IHOUSETTEN[$wOyakiSettei2]);#489.properties
		$sheet->setCellValue($OYAKISETTEI[3], $IHOUSETTEN[$wOyakiSettei3]);
		$sheet->setCellValue($OYAKISETTEI[4], $IHOUSETTEN[$wOyakiSettei4]);
		$sheet->setCellValue($OYAKISETTEI[5], $IHOUSETTEN[$wOyakiSettei5]);
		$sheet->setCellValue($OYAKISETTEI[6], $IHOUSETTEN[$wOyakiSettei6]);
		$sheet->setCellValue($OYAKISETTEI[7], $IHOUSETTEN[$wOyakiSettei7]);
		$sheet->setCellValue($OYAKISETTEI[8], $IHOUSETTEN[$wOyakiSettei8]);
		$sheet->setCellValue($OYAKISETTEI[9], $IHOUSETTEN[$wOyakiSettei9]);
		$sheet->setCellValue($OYAKISETTEI[10], $ARINASHI_SUJI[$wOyakiSettei10]);

		//初期値と値が異なっていたら赤色にする
		for($i=0;$i<count($OyakiSetteiPlace);$i++){
			$spreadsheet->getSheetByName('管親設定表')->getStyle($OyakiSetteiPlace[$i])->getFill()
				->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
				->getStartColor()->setARGB('FFBAB3');
		}

	//メニュー画面からの設定
	$JUTAKUMENUSETTEI[1]="M7";
	$JUTAKUMENUSETTEI[2]="M9";
	$JUTAKUMENUSETTEI[3]="M11";

	//施工設定
	$JUTAKUSERVICESETTEI[5]="U16";
	$JUTAKUSERVICESETTEI[6]="U17";
	
	$sheet = $spreadsheet->getSheetByName('親機設定表'); //weatherシート取得

		$sheet->setCellValue($JUTAKUMENUSETTEI[1], $SIYOU[$wJutakuMenuSettei1]);
		$sheet->setCellValue($JUTAKUMENUSETTEI[2], $TIENJIKAN[$wJutakuMenuSettei2]);
		$sheet->setCellValue($JUTAKUMENUSETTEI[3], $TIENJIKAN[$wJutakuMenuSettei3]);

		$sheet->setCellValue($JUTAKUSERVICESETTEI[5], $ARINASI[$wJutakuServiceSettei5]);
		$sheet->setCellValue($JUTAKUSERVICESETTEI[6], $YUUKOU[$wJutakuServiceSettei6]);
		$sheet->setCellValue($JUTAKUSERVICESETTEI[7], $YUUKOU[$wJutakuServiceSettei7]);
		$sheet->setCellValue($JUTAKUSERVICESETTEI[8], $SERVICESYURUI[$wJutakuServiceSettei8]);
		$sheet->setCellValue($JUTAKUSERVICESETTEI[9], $SERVICESYURUI[$wJutakuServiceSettei9]);
		$sheet->setCellValue($JUTAKUSERVICESETTEI[10], $SERVICESYURUI[$wJutakuServiceSettei10]);
		$sheet->setCellValue($JUTAKUSERVICESETTEI[11], $TIENJIKAN[$wJutakuServiceSettei11]);
		$sheet->setCellValue($JUTAKUSERVICESETTEI[12], $KENSYUTU[$wJutakuServiceSettei12]);
		$sheet->setCellValue($JUTAKUSERVICESETTEI[13], $OSIBUTTON_2[$wJutakuServiceSettei13]);
		$sheet->setCellValue($JUTAKUSERVICESETTEI[14], $YUUKOU[$wJutakuServiceSettei14]);
		$sheet->setCellValue($JUTAKUSERVICESETTEI[15], $YUUKOU[$wJutakuServiceSettei15]);
		$sheet->setCellValue($JUTAKUSERVICESETTEI[16], $TIENJIKAN[$wJutakuServiceSettei16]);
		$sheet->setCellValue($JUTAKUSERVICESETTEI[17], $KENSYUTU[$wJutakuServiceSettei17]);
		$sheet->setCellValue($JUTAKUSERVICESETTEI[18], $OSIBUTTON_2[$wJutakuServiceSettei18]);
		$sheet->setCellValue($JUTAKUSERVICESETTEI[19], $GENKANKOKISYUTURYUOKU[$wJutakuServiceSettei19]);
		$sheet->setCellValue($JUTAKUSERVICESETTEI[20], $TIENJIKAN[$wJutakuServiceSettei20]);
		$sheet->setCellValue($JUTAKUSERVICESETTEI[21], $KENSYUTU[$wJutakuServiceSettei21]);
		$sheet->setCellValue($JUTAKUSERVICESETTEI[22], $OSIBUTTON_2[$wJutakuServiceSettei22]);
		$sheet->setCellValue($JUTAKUSERVICESETTEI[23], $GENKANKOKISYUTURYUOKU[$wJutakuServiceSettei23]);
		$sheet->setCellValue($JUTAKUSERVICESETTEI[24], $TIENJIKAN[$wJutakuServiceSettei24]);
		$sheet->setCellValue($JUTAKUSERVICESETTEI[25], $TIENJIKAN[$wJutakuServiceSettei25]);

		if($wJutakuServiceSettei26 == 0){	#初期値=1 違う場合のみ出力
			$sheet->setCellValue($JUTAKUSERVICESETTEI[26],'25分後解除');
		}

		$sheet->setCellValue($JUTAKUSERVICESETTEI[27], $TIENJIKAN[$wJutakuServiceSettei27]);
		$sheet->setCellValue($JUTAKUSERVICESETTEI[28], $YUUKOU[$wJutakuServiceSettei28]);
		$sheet->setCellValue($JUTAKUSERVICESETTEI[29], $YUUKOU[$wJutakuServiceSettei29]);

		if($wJutakuServiceSettei30 == 1){	#初期値=0 違う場合のみ出力
			$sheet->setCellValue($JUTAKUSERVICESETTEI[30],'警報のみ');
		}

		if($wJutakuServiceSettei31 == 1){	#初期値=0 違う場合のみ出力
			$sheet->setCellValue($JUTAKUSERVICESETTEI[31],'シンプル防犯モード');
		}

		//初期値と値が異なっていたら赤色にする
		for($i=0;$i<count($JutakuSetteiPlace);$i++){
			$spreadsheet->getSheetByName('親機設定表')->getStyle($JutakuSetteiPlace[$i])->getFill()
				->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
				->getStartColor()->setARGB('FFBAB3');
		}

	//ダウンロード用
	//MIMEタイプ：https://technet.microsoft.com/ja-jp/ee309278.aspx
	header("Content-Description: File Transfer");
	$wFileName = "PATMO・GBM-2M（K）機器設定指示書".date('Ymd').".xlsx" ;
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
