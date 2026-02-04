--<?php

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
	include_once _CLS_DIR . "SPUSAnzenSyorui.cls";


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
	$TantoData = getTantoData($myDB, $UserCD);
	$wEigyoshoName = $TantoData['EigyoshoName'] ;
	$wEigyoshoAddress = $TantoData['EigyoshoAddress'];
	$wEigyoshoTEL = $TantoData['TEL'];
	$wTantoTEL = $TantoData['Address3'];#携帯電話

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
		$Kaidaka = $myBukken->Kaidaka."階";
		$Kosu = $myBukken->Kosu."戸 ";


	}

	########################################################
	# Postデータ取得
	########################################################


	foreach($_POST as $key => $value){
		${"$key"} = SPFWParameter::getValues($key);
	}
/*

	$wSyoruiSakuseiDate = SPFWParameter::getValues('wSyoruiSakuseiDate');
	$wAnzenKojiName = SPFWParameter::getValues('wAnzenKojiName');
	$wAnzenKojiNaiyo = SPFWParameter::getValues('wAnzenKojiNaiyo');
#	$wKojiName = SPFWParameter::getValues('KojiName');
	$wBukkenName = SPFWParameter::getValues('BukkenName');
	$wAnzenHattyusya = SPFWParameter::getValues('wAnzenHattyusya');
	$wAnzenHattyusyaAddress = SPFWParameter::getValues('wAnzenHattyusyaAddress');
	$wAnzenHattyusyaTEL = SPFWParameter::getValues('wAnzenHattyusyaTEL');
	$wContract = SPFWParameter::getValues('wContract');
	$wZentaiStartDate = SPFWParameter::getValues('ZentaiStartDate');
	$wZentaiEndDate = SPFWParameter::getValues('ZentaiEndDate');
	$wSitentyo = SPFWParameter::getValues('wSitentyo');
	$wEigyoSyotyo = SPFWParameter::getValues('wEigyoSyotyo');
	$wSyuninGijutusya = SPFWParameter::getValues('wSyuninGijutusya');
	$wBukkenTanto = SPFWParameter::getValues('wBukkenTanto');
	$wEmergencyTEL = SPFWParameter::getValues('wEmergencyTEL');
	$wSitauke = SPFWParameter::getValues('Sitauke');


	#アイホン用
	$wAIPHONEContract = SPFWParameter::getValues('wAIPHONEContract');
	$wAIPHONETanto = SPFWParameter::getValues('wAIPHONETanto');
	$wAIPHONEEmergencyTEL = SPFWParameter::getValues('wAIPHONEEmergencyTEL');
	
	#施工業者用
	$wATEContract = SPFWParameter::getValues('wATEContract');
	$wHealthNumber = SPFWParameter::getValues('wHealthNumber');
	$wNenkinNumber = SPFWParameter::getValues('wNenkinNumber');
	$wHNSyurui = SPFWParameter::getValues('wHNSyurui');
	$wEmployNumber = SPFWParameter::getValues('wEmployNumber');


	#書式
	$wFormat = SPFWParameter::getValues('wFormat');
	
	#長谷工分
	$wHasekoSiten= SPFWParameter::getValues('wHasekoSiten');
	$wHasekoAddress = SPFWParameter::getValues('wHasekoAddress');
	$wHasekoTEL = SPFWParameter::getValues('wHasekoTEL');
	$wHasekoTanto = SPFWParameter::getValues('wHasekoTanto');
	$wHasekoAnzenYakuin = SPFWParameter::getValues('wHasekoAnzenYakuin');
	$wHasekoAnzenSekininsya = SPFWParameter::getValues('wHasekoAnzenSekininsya');
	$wHasekoAnzenTanto = SPFWParameter::getValues('wHasekoAnzenTanto');
	$wHasekoKojiYakuin = SPFWParameter::getValues('wHasekoKojiYakuin');
	$wHasekoKojiSekininsya = SPFWParameter::getValues('wHasekoKojiSekininsya');
	$wHasekoKojiTanto = SPFWParameter::getValues('wHasekoKojiTanto');
	$wHasekoGenbaDairinin = SPFWParameter::getValues('wHasekoGenbaDairinin');
	$wHasekoShokucho = SPFWParameter::getValues('wHasekoShokucho');
	$wHasekoSyuninGijyutusya = SPFWParameter::getValues('wHasekoSyuninGijyutusya');
	$wHasekoSenmonGijyutusya = SPFWParameter::getValues('wHasekoSenmonGijyutusya');
	$wHasekoTantoKojiNaiyo = SPFWParameter::getValues('wHasekoTantoKojiNaiyo');
	$wHaseko2jiAnzenSekininsya = SPFWParameter::getValues('wHaseko2jiAnzenSekininsya');
	$wHaseko2jiSyuninGijyutusya = SPFWParameter::getValues('wHaseko2jiSyuninGijyutusya');
	$wHaseko2jiSenmonGijyutusya = SPFWParameter::getValues('wHaseko2jiSenmonGijyutusya');
	$wHaseko2jiTantoKojiNaiyo = SPFWParameter::getValues('wHaseko2jiTantoKojiNaiyo');
*/
	########################################################
	# パラメータ取得
	########################################################

	$myKoji = new Koji($myDB);
	
	if (!$myKoji->executeSelect("Updated = (select max(Updated) from tKojiF where BukkenCD = " . $editBukkenCD . " and MukouFlg = 0)","")){
		trigger_error("Getting Bukken Failed.", E_USER_ERROR);
	}

	$editKojiCD = $myKoji->KojiCD;
	$ZentaiStartDate = $myKoji->ZentaiStartDate;
	$ZentaiEndDate = $myKoji->ZentaiEndDate;
	$GyosyaTantoCD1 = $myKoji->GyosyaTantoCD1;#施工業者 担当者CD

	#施工会社情報

	$myListObject = new SPFWListObject($myDB);

	$sql = "SELECT ";
	$sql .= "g.GyosyaCD, ";			#0
	$sql .= "g.GyosyaKanriNo, ";	#1	
	$sql .= "g.GyosyaName, ";		#2
	$sql .= "g.GyosyaNameKana, ";	#3
	$sql .= "g.ShozokuCD, ";		#4
	$sql .= "g.GyosyaTEL, ";		#5
	$sql .= "g.GyosyaFAX, ";		#6
	$sql .= "g.GyosyaMail, ";		#7
	$sql .= "g.Yubin, ";			#8
	$sql .= "g.Address, ";			#9
	$sql .= "g.DaihyoName, ";		#10
	$sql .= "g.MadogutiTantoName, ";#11
	$sql .= "g.MadogutiTantoTEL, ";	#12

	$sql .= "g.DenkiKojiKyoka, ";		#13建業法許可　電気工事　知事
	$sql .= "g.DenkiKojiSyubetu, ";		#14 一般
	$sql .= "g.DenkiKojiGou, ";			#15 2345123
	$sql .= "g.DenkiKojiKyokaDate, ";	#16 2018-01-01

	$sql .= "g.DenkiTushinKojiKyoka, ";		#17建業法許可　電気通信工事　
	$sql .= "g.DenkiTushinKojiSyubetu, ";	#18
	$sql .= "g.DenkiTushinKojiGou, ";		#19
	$sql .= "g.DenkiTushinKojiKyokaDate, ";	#20

	$sql .= "g.SyoboShisetuKojiKyoka, ";	#21建業法許可　消防施設工事　
	$sql .= "g.SyoboShisetuKojiSyubetu, ";	#22
	$sql .= "g.SyoboShisetuKojiGou, ";		#23
	$sql .= "g.SyoboShisetuKojiKyokaDate, ";#24

	$sql .= "g.GyosyaNotes ";		#25

	$myListObject->SelectSQL = $sql;
	$sql = " FROM tGyosyaM g , tUserM u";
	$sql .= " WHERE g.GyosyaCD = u.Extra5 AND ";
	$sql .= " g.MukouFlg = FALSE AND u.UserCD = ".$GyosyaTantoCD1;

	$myListObject->Condition = $sql;
	$myListObject->Order = "Extra2";#表示順
	$myListObject->Limit = "allpage";

	if (!($myListObject->GetList(1)))
		trigger_error("Getting Menu List Failed.", E_USER_ERROR);


	$GyosyaCD = $myListObject->GetValue(0, 0);
	$ATESyamei = $myListObject->GetValue(0, 2);

	$ATEAddress = $myListObject->GetValue(0, 9);
	$ATEDaihyou = $myListObject->GetValue(0, 10);
	$ATEAddress = $myListObject->GetValue(0, 9);
	$ATETanto = $myListObject->GetValue(0, 11);
	$ATEEmergencyTEL = $myListObject->GetValue(0, 12);
	if($myListObject->GetValue(0, 13)){
		$Kyoka = $myListObject->GetValue(0, 13);
		$Syubetu = $myListObject->GetValue(0, 14);
		$Gou = $myListObject->GetValue(0, 15);
		$LicenseNumber = $Kyoka." ".$Syubetu." ".$Gou;
		$LicenseDate = $myListObject->GetValue(0, 16);
		$Syurui ="電気工事 ";
	}
	if(!$LicenseNumber){
		$Kyoka = $myListObject->GetValue(0, 17);
		$Syubetu = $myListObject->GetValue(0, 18);
		$Gou = $myListObject->GetValue(0, 19);
		$LicenseNumber = $Kyoka." ".$Syubetu." ".$Gou;
		$LicenseDate = $myListObject->GetValue(0, 20);
		$Syurui = $Syurui."電気通信工事 ";
	}
	if(!$LicenseNumber ){
		$Kyoka = $myListObject->GetValue(0, 21);
		$Syubetu = $myListObject->GetValue(0, 22);
		$Gou = $myListObject->GetValue(0, 23);
		$LicenseNumber = $Kyoka." ".$Syubetu." ".$Gou;
		$LicenseDate = $myListObject->GetValue(0, 24);
		$Syurui = $Syurui."消防施設工事 ";
	}


	$LicenseDate = 	( $LicenseDate == "0000-00-00" )?"":$LicenseDate ;

	########################################################
	# tAnzenSyoruiFに登録・編集
	########################################################

	if($editKojiCD > 0){
		// 必須項目はない。
		#if ($wDeviceID == NULL or $wDeviceID == "") $ErrorString[] = "wDeviceIDは必須項目です。";
		#if ($wLastName == NULL or $wLastName == "") $ErrorString[] = "wLastNameは必須項目です。";

		#if (count($ErrorString) > 0){
		#	showAdminSorryPage($ErrorString);
		#}

		$myAnzenSyorui = new AnzenSyorui($myDB);

#echo "<br>241行目".date('Y-m-d H:i-s').$editKojiCD;
		if (!$myAnzenSyorui->executeSelect("KojiCD = " . $editKojiCD, "") ){
			$ErrorString = array();
			$ErrorString[] = "tAnzenSyoruiM情報の抽出に失敗しました。";
			showAdminSorryPage($ErrorString);
		}

#if($myAnzenSyorui->RecCnt != 1

		$myAnzenSyorui->KojiCD = $editKojiCD;
		if($wSyoruiSakuseiDate)$myAnzenSyorui->SyoruiSakuseiDate = $wSyoruiSakuseiDate;
		if($wAnzenKojiNaiyo)$myAnzenSyorui->AnzenKojiNaiyo = $wAnzenKojiNaiyo;
		if($wAnzenKojiName)$myAnzenSyorui->AnzenKojiName = $wAnzenKojiName;
		if($wAnzenHattyusya)$myAnzenSyorui->AnzenHattyusya = $wAnzenHattyusya;
		if($wAnzenHattyusyaAddress)$myAnzenSyorui->AnzenHattyusyaAddress = $wAnzenHattyusyaAddress;
		if($wAnzenHattyusyaTEL)$myAnzenSyorui->AnzenHattyusyaTEL = $wAnzenHattyusyaTEL;
		if($wContract)$myAnzenSyorui->Contract = $wContract;
		if($wSitentyo)$myAnzenSyorui->Sitentyo = $wSitentyo;
		if($wEigyoSyotyo)$myAnzenSyorui->EigyoSyotyo = $wEigyoSyotyo;
		if($wSyuninGijutusya)$myAnzenSyorui->SyuninGijutusya = $wSyuninGijutusya;
		if($wBukkenTanto)$myAnzenSyorui->BukkenTanto = $wBukkenTanto;
		if($wEmergencyTEL)$myAnzenSyorui->EmergencyTEL = $wEmergencyTEL;
		if($wATEContract)$myAnzenSyorui->ATEContract = $wATEContract;
		if($wHealthNumber)$myAnzenSyorui->HealthNumber = $wHealthNumber;
		if($wNenkinNumber)$myAnzenSyorui->NenkinNumber = $wNenkinNumber;
		if($wHNSyurui)$myAnzenSyorui->HNSyurui = $wHNSyurui;
		if($wEmployNumber)$myAnzenSyorui->EmployNumber = $wEmployNumber;
		if($wAIPHONEContract)$myAnzenSyorui->AIPHONEContract = $wAIPHONEContract;
		if($wAIPHONETanto)$myAnzenSyorui->AIPHONETanto = $wAIPHONETanto;
		if($wAIPHONEEmergencyTEL)$myAnzenSyorui->AIPHONEEmergencyTEL = $wAIPHONEEmergencyTEL;
		#長谷工分
		if($wHasekoSiten)$myAnzenSyorui->HasekoSiten = $wHasekoSiten;
		if($wHasekoAddress)$myAnzenSyorui->HasekoAddress = $wHasekoAddress;
		if($wHasekoTEL)$myAnzenSyorui->HasekoTEL = $wHasekoTEL;
		if($wHasekoTanto)$myAnzenSyorui->HasekoTanto = $wHasekoTanto;
		if($wHasekoAnzenYakuin)$myAnzenSyorui->HasekoAnzenYakuin = $wHasekoAnzenYakuin;
		if($wHasekoAnzenSekininsya)$myAnzenSyorui->HasekoAnzenSekininsya = $wHasekoAnzenSekininsya;
		if($wHasekoAnzenTanto)$myAnzenSyorui->HasekoAnzenTanto = $wHasekoAnzenTanto;
		if($wHasekoKojiYakuin)$myAnzenSyorui->HasekoKojiYakuin = $wHasekoKojiYakuin;
		if($wHasekoKojiSekininsya)$myAnzenSyorui->HasekoKojiSekininsya = $wHasekoKojiSekininsya;
		if($wHasekoKojiTanto)$myAnzenSyorui->HasekoKojiTanto = $wHasekoKojiTanto;
		if($wHasekoGenbaDairinin)$myAnzenSyorui->HasekoGenbaDairinin = $wHasekoGenbaDairinin;
		if($wHasekoShokucho)$myAnzenSyorui->HasekoShokucho = $wHasekoShokucho;
		if($wHasekoSyuninGijyutusya)$myAnzenSyorui->HasekoSyuninGijyutusya = $wHasekoSyuninGijyutusya;
		if($wHasekoSenmonGijyutusya)$myAnzenSyorui->HasekoSenmonGijyutusya = $wHasekoSenmonGijyutusya;
		if($wHasekoTantoKojiNaiyo)$myAnzenSyorui->HasekoTantoKojiNaiyo = $wHasekoTantoKojiNaiyo;
		if($wHaseko2jiAnzenSekininsya)$myAnzenSyorui->Haseko2jiAnzenSekininsya = $wHaseko2jiAnzenSekininsya;
		if($wHaseko2jiSyuninGijyutusya)$myAnzenSyorui->Haseko2jiSyuninGijyutusya = $wHaseko2jiSyuninGijyutusya;
		if($wHaseko2jiSenmonGijyutusya)$myAnzenSyorui->Haseko2jiSenmonGijyutusya = $wHaseko2jiSenmonGijyutusya;
		if($wHaseko2jiTantoKojiNaiyo)$myAnzenSyorui->Haseko2jiTantoKojiNaiyo = $wHaseko2jiTantoKojiNaiyo;


		$myAnzenSyorui->Updater = $UserCD;

		if (!$myAnzenSyorui->executeUpdate()){
			$ErrorString = array();
			$ErrorString[] = "メニューマスタ情報の更新に失敗しました。";
			showAdminSorryPage($ErrorString);
		}

	}




	########################################################
	# Excelファイル生成
	########################################################
	require 'vendor/autoload.php';

	use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;
	$reader = new XlsxReader();

#	$spreadsheet = $reader->load('./template/anzensyorui.xlsx'); //template.xlsx 読込
#	$spreadsheet = $reader->load('./template/anzensyorui_nom.xlsx'); //template.xlsx 読込

#	$sheet = $spreadsheet->getActiveSheet();
#	$sheet->setCellValue('A2', date('Y年m月').'吉日');

#$wFormat = 1 ;#仮設定

#①アイホンフォーマット
if( $wFormat == "1"){

	$spreadsheet = $reader->load('./template/anzensyorui.xlsx'); //template.xlsx 読込
	$sheet = $spreadsheet->getActiveSheet();

	
	#書類作成日
	$sheet->setCellValue('D6', $wSyoruiSakuseiDate);
	#工事名称
	$sheet->setCellValue('D7', $wAnzenKojiNaiyo);
	#工事名称
	$sheet->setCellValue('D8', $wAnzenKojiName);
	#たてもの名称
	$sheet->setCellValue('D9', $BukkenName);
	#発注者名
	$sheet->setCellValue('D10', $wAnzenHattyusya);
	#発注者住所
	$sheet->setCellValue('D11', $wAnzenHattyusyaAddress);
	#発注者電話番号
	$sheet->setCellValue('D12', $wAnzenHattyusyaTEL);
	#契約日
	$sheet->setCellValue('D13', $wContract);
	#工事開始日
	$sheet->setCellValue('D14', $ZentaiStartDate);
	#工事終了日
	$sheet->setCellValue('D15', $ZentaiEndDate);
	#支店長
	$sheet->setCellValue('D16', $wSitentyo);
	#営業所長
	$sheet->setCellValue('D17', $wEigyoSyotyo);
	#主任技術者
	$sheet->setCellValue('D18', $wSyuninGijutusya);
	#物件担当者
	$sheet->setCellValue('D19', $wBukkenTanto);
	#緊急連絡先
	$sheet->setCellValue('D20', $wEmergencyTEL);

	#ATEの場合
	if($wSitauke == 1 ){
		#下請契約日
		$sheet->setCellValue('D24', $wATEContract);
		#ATE社名
		$sheet->setCellValue('D25', $ATESyamei);
		#ATE住所
		$sheet->setCellValue('D26', $ATEAddress);
		#ATE代表者名
		$sheet->setCellValue('D27', $ATEDaihyou);
		#ATE担当者名
		$sheet->setCellValue('D28', $ATETanto);
		#緊急連絡番号
		$sheet->setCellValue('D29', $ATEEmergencyTEL);
		#建設業許可番号
		$sheet->setCellValue('D30', $Gou);#番号表示部分のみ
		#建設業許可年月日
		$sheet->setCellValue('D31', $LicenseDate);
		#健康保険番号
		$sheet->setCellValue('D32', $wHealthNumber);
		#年金番号
		$sheet->setCellValue('D33', $wNenkinNumber);
		#年金保険の種類
		$sheet->setCellValue('D34', $wHNSyurui);
		#雇用保険番号
		$sheet->setCellValue('D35', $wEmployNumber);
	#アイホンの場合
	}elseif($wSitauke == 2 ){
		#下請契約日
		$sheet->setCellValue('D24', $wAIPHONEContract);
		#ATE社名
		$sheet->setCellValue('D25', $AIPHONESyamei);
		#ATE住所
		$sheet->setCellValue('D26', $AIPHONEAddress);
		#ATE代表者名
		$sheet->setCellValue('D27', $AIPHONEDaihyou);
		#ATE担当者名
		$sheet->setCellValue('D28', $wAIPHONETanto);
		#緊急連絡番号
		$sheet->setCellValue('D29', $wAIPHONEEmergencyTEL);
		#建設業許可番号
		$sheet->setCellValue('D30', $AIPHONELicenseNumber);
		#建設業許可年月日
		$sheet->setCellValue('D31', $AIPHONELicenseDate);
		#健康保険番号
		$sheet->setCellValue('D32', $AIPHONEHealthNumber);
		#年金番号
		$sheet->setCellValue('D33', $AIPHONENenkinNumber);
		#年金保険の種類
		$sheet->setCellValue('D34', $AIPHONEHNSyurui);
		#雇用保険番号
		$sheet->setCellValue('D35', $AIPHONEEmployNumber);
	}#ATE/アイホンの場合　End


#②長谷工フォーマット
}elseif( $wFormat == "2" ){

	$spreadsheet = $reader->load('./template/anzensyoruiHaseko.xlsx'); //template.xlsx 読込
	$sheet = $spreadsheet->getActiveSheet();

		
	#物件名
	$sheet->setCellValue('D6', $BukkenName);
	#工事内容
	$sheet->setCellValue('D7', $wAnzenKojiNaiyo);
	#工事名称
	$sheet->setCellValue('D8', $wAnzenKojiName);
	#発注者
	$sheet->setCellValue('D9', $wAnzenHattyusya);
	#物件住所
	$sheet->setCellValue('D10', $wAnzenHattyusyaAddress);
	#階数・戸数
	$sheet->setCellValue('D11', $Kaidaka." ".$Kosu);




	#長谷工支店名
	$sheet->setCellValue('D16', $wHasekoSiten);
	#長谷工支店名
	$sheet->setCellValue('D17', "株式会社長谷工コミュニティ ".$wHasekoSiten);
	#支店住所
	$sheet->setCellValue('D18', $wHasekoAddress);
	#電話
	$sheet->setCellValue('D19', $wHasekoTEL);
	#長谷工支店担当者（氏名）
	$sheet->setCellValue('D20', $wHasekoTanto);


	#工期　自
	$sheet->setCellValue('D25', $ZentaiStartDate);
	#工期　至
	$sheet->setCellValue('D26', $ZentaiEndDate);


	#アイホン支店・営業所
	$sheet->setCellValue('D29', $wEigyoshoName);
	#住所
	$sheet->setCellValue('D30', $wEigyoshoAddress);
	#電話
	$sheet->setCellValue('D31', $wEigyoshoTEL);
	#担当者（氏名）
	$sheet->setCellValue('D32', $wAIPHONETanto);
	#担当者　携帯電話
	$sheet->setCellValue('D33', $wAIPHONEEmergencyTEL);
	#安全衛生担当役員（氏名・役職）
	$sheet->setCellValue('D36', $wHasekoAnzenYakuin);
	#安全衛生担当責任者（氏名・役職）
	$sheet->setCellValue('D37', $wHasekoAnzenSekininsya);
	#安全衛生担当者（氏名・役職）
	$sheet->setCellValue('D38', $wHasekoAnzenTanto);
	#工事担当役員（氏名・役職）
	$sheet->setCellValue('D39', $wHasekoKojiYakuin);
	#工事担当責任者（氏名・役職）
	$sheet->setCellValue('D40', $wHasekoKojiSekininsya);
	#工事担当者（氏名・役職）
	$sheet->setCellValue('D41', $wHasekoKojiTanto);
	#現場代理人（氏名）
	$sheet->setCellValue('D42', $wHasekoGenbaDairinin);
	#職長（氏名）
	$sheet->setCellValue('D43', $wHasekoShokucho);
	#主任技術者
	$sheet->setCellValue('D44', $wHasekoSyuninGijyutusya);

	#専門技術者
	$sheet->setCellValue('D45', $wHasekoSenmonGijyutusya);

	#担当工事内容
	$sheet->setCellValue('D46', $wHasekoTantoKojiNaiyo);

	#二次下請け会社
	$sheet->setCellValue('D48', $ATESyamei);

	#住所
	$sheet->setCellValue('D49', $ATEAddress);

	#担当者名
	$sheet->setCellValue('D50', $ATETanto);
	#担当者　携帯電話
	$sheet->setCellValue('D51', $ATEEmergencyTEL);
	#建設業許可番号
	$sheet->setCellValue('D52', $LicenseNumber);
	#建設業許可種類
	$sheet->setCellValue('D53', $Syurui);
	#建設業許可年月日
	$sheet->setCellValue('D54', $LicenseDate);
	#安全衛生責任者（氏名）
	$sheet->setCellValue('D55', $wHaseko2jiAnzenSekininsya);
	#主任技術者（氏名）
	$sheet->setCellValue('D56', $wHaseko2jiSyuninGijyutusya);
	#専門技術者
	$sheet->setCellValue('D57', $wHaseko2jiSenmonGijyutusya);
	#担当工事内容
	$sheet->setCellValue('D58', $wHaseko2jiTantoKojiNaiyo);




}# Format別If END　日本ハウジングはまだ。


	//ダウンロード用
	//MIMEタイプ：https://technet.microsoft.com/ja-jp/ee309278.aspx
	header("Content-Description: File Transfer");
	$wFileName = "安全書類.xlsx" ;
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
