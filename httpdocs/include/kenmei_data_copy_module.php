#!/usr/bin/php -q
<?php

	$isModuleMode = TRUE;

	$MyDir = dirname(__FILE__);
	include_once $MyDir . "/../inc/setting.properties";
	include_once _INC_DIR . "global.inc";

	include_once _CLS_DIR . "SPFWDatabase.cls";
	include_once _CLS_DIR . "SPFWLog.cls";
	include_once _CLS_DIR . "SPFWTemplate.cls";
	include_once _CLS_DIR . "SPFWListObject.cls";
	include_once _CLS_DIR . "SPFWDate.cls";
	include_once _CLS_DIR . "SPFWInputCheck.cls";
	include_once _CLS_DIR . "SPFWTools.cls";
	include_once _CLS_DIR . "SPFWParameter.cls";
	include_once _CLS_DIR . "SPUSSetting.cls";
	include_once _CLS_DIR . "SPUSRenkeiKenmei.cls";
//	include_once _CLS_DIR . "SPUSKenmei.cls"; //Newを使う
	include_once _CLS_DIR . "SPUSKenmeiNew.cls";
	include_once _CLS_DIR . "SPUSUser.cls";
	include_once _CLS_DIR . "SPUSSHIN.cls";

	include_once "E:/xampp8.2.4/hosyu/httpdocs/h_kenmei_connect.php";

	require_once "E:/xampp8.2.4/hosyu/httpdocs/include/common_top.php";
	require_once "E:/xampp8.2.4/hosyu/httpdocs/include/common.php";


	// データベースコネクト
	$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
	if (!$myDB->Connection)
		trigger_error("SPFWDatabase Failed.", E_USER_ERROR);

	########################################################
	# 件名システムから件名データをコピーする
	########################################################
	$KenmeiAllData = getKenmeiData(); // h_kenmei_connect.php
#Print_r($KenmeiAllData);

if( !isset($KenmeiAllData) ){
	echo "データなし";
	exit;
}
$KenSuu = count($KenmeiAllData);

foreach ( $KenmeiAllData as $KenmeiData ) {

	$KenmeiNo		= trim( $KenmeiData['BKN_NO'] );			#件名No trim
	$Keitai			= $KenmeiData['BKN_KBN'];					#物件区分
	$NonyuDate		= $KenmeiData['BKN_END_YMD'];				#納完日
	$BukkenName		= $KenmeiData['BKN_NM_ALL'];				#物件名
	$Prefecture		= $KenmeiData['JYUSYO_JKY1'];				#住所１
	$Address		= $KenmeiData['JYUSYO_JKY2'];				#住所２
	$Address2		= $KenmeiData['JYUSYO_JKY3'];				#住所３
	$Address2		= $Address2." ".$KenmeiData['JYUSYO_JKY4'];	#住所３＝住所３＋住所４
	$HanbaiShijyoKbn = $KenmeiData['HNB_SIJO_KBN'];				#市場区分
	$KETAI_BNRUI_KBN = $KenmeiData['KETAI_BNRUI_KBN'];			#形態分類区分
	$Bumon			= trim( $KenmeiData['KANRI_BMON_CD'] );		#部門CD trim
	$Busyo			= trim( $KenmeiData['KANRI_BSYO_CD'] );		#部署CD trim
	$TantoName		= trim( $KenmeiData['KANRI_SYIN_CD'] );		#社員CD trim
	$KanminKbn		= $KenmeiData['KANMIN_KBN'];				#官民区分
    $SyunkoYoteiDate = $KenmeiData['SYNK_YOTEI_YMD'];           #竣工予定日


	#形態分類
	if($Keitai == 1 AND $KETAI_BNRUI_KBN == 1){ // 物件区分＝1 AND 形態分類区分＝1
		$Keitai = "新築";
	}else{
		$Keitai = "既築";
	} 


	#販売市場区分
	#1:戸建住宅/2:集合住宅/3:病院/4:高齢者施設/5:高齢者住宅/6:ケアその他/7:事務所/8:店舗/9:その他/A:工場/B:学校 C:集合住宅・賃貸						
	switch ($HanbaiShijyoKbn){
	case 2;
		$HanbaiShijyoKbn = "集合住宅";
		$Shijyo = "集合";
		break;

	case 3;
		#NLX★マークチェック
		$KenmeiDataNLX  = getKenmeiDataNLX($KenmeiNo);
		$JUC_NO	= $KenmeiDataNLX['JUC_NO'];
		$NLXMark = ( $JUC_NO <> "" )?"1":"" ;

		$HanbaiShijyoKbn = "病院";
		$Shijyo = "ケア";
		break;

	case 4;
		#NLX★マークチェック
		$KenmeiDataNLX  = getKenmeiDataNLX($KenmeiNo);
		$SYHN_CD	= $KenmeiDataNLX['SYHN_CD'];
		$NLXMark = ( $SYHN_CD <> "" )?"1":"" ;

		$HanbaiShijyoKbn = "高齢者施設";
		$Shijyo = "ケア";
		break;

	case 5;
		#NLX★マークチェック
		$KenmeiDataNLX  = getKenmeiDataNLX($KenmeiNo);
		$SYHN_CD	= $KenmeiDataNLX['SYHN_CD'];
		$NLXMark = ( $SYHN_CD <> "" )?"1":"" ;

		$HanbaiShijyoKbn = "高齢者住宅";
		$Shijyo = "ケア";
		break;

	case 6;
		#NLX★マークチェック
		$KenmeiDataNLX  = getKenmeiDataNLX($KenmeiNo);
		$SYHN_CD	= $KenmeiDataNLX['SYHN_CD'];
		$NLXMark = ( $SYHN_CD <> "" )?"1":"" ;

		$HanbaiShijyoKbn = "ケアその他";
		$Shijyo = "ケア";
		break;

	case 7;
		$HanbaiShijyoKbn = "事務所";
		$Shijyo = "集合"; // デフォルトは集合とする 20170916
		break;

	case 8;
		$HanbaiShijyoKbn = "店舗";
		$Shijyo = "集合"; // デフォルトは集合とする 20170916
		break;
	case "C";
		$HanbaiShijyoKbn = "集合住宅・賃貸";
		$Shijyo = "集合"; // デフォルトは集合とする 20170916
		break;

	default:
#		#NLX★マークチェック
#		$KenmeiDataNLX  = getKenmeiDataNLX($KenmeiNo);
#		$SYHN_CD	= $KenmeiDataNLX['SYHN_CD'];
#		$NLXMark = ( $SYHN_CD <> "" )?"1":"" ;

		$HanbaiShijyoKbn = "その他";
		$Shijyo = "集合"; // デフォルトは集合とする 20170916
	}



		$ErrorLoop = 0;
		$Success = 0;
		$Failure = 0;

		// 連携件名テーブル書き込み
		$myRenkeiKenmei = new RenkeiKenmei($myDB);

		$myRenkeiKenmei->RenkeiNo = -1; #全部新規　KenmeiNoがだぶってるかチェック必要
		$myRenkeiKenmei->BukkenName = $BukkenName;
		$myRenkeiKenmei->Prefecture = $Prefecture;
		$myRenkeiKenmei->Address = $Address;
		$myRenkeiKenmei->Address2 = $Address2;
		$myRenkeiKenmei->Bumon = $Bumon;
		$myRenkeiKenmei->Busyo = $Busyo;
		$myRenkeiKenmei->TantoName = $TantoName;
		$myRenkeiKenmei->HanbaiShijyoKbn = $HanbaiShijyoKbn;
		$myRenkeiKenmei->Keitai = $Keitai;
		$myRenkeiKenmei->NonyuDate = $NonyuDate;
		$myRenkeiKenmei->KenmeiNo = $KenmeiNo;
		$myRenkeiKenmei->KanminKbn = $KanminKbn;
		$myRenkeiKenmei->SyunkoYoteiDate = $SyunkoYoteiDate;
		$myRenkeiKenmei->Creator = $UserCD;
		$myRenkeiKenmei->Updater = $UserCD;

		if (!$myRenkeiKenmei->executeUpdate()){
			trigger_error("executeUpdate(myRenkeiKenmei) Failed.", E_USER_ERROR);
		}


		#前項の処理で追加されたRenkeiNoを抽出する
		$sqlMoji = "select RenkeiNo from tRenkeiKenmeiF order by RenkeiNo desc limit 1";
		$maxCD = $myDB->getOneValue($sqlMoji);
		$RenkeiNo = ($maxCD == -1) ? 0 : $maxCD;


		#件名テーブルへ書き込み
		$myKenmei = new KenmeiNew($myDB);

		$myKenmei->KenmeiNo = $KenmeiNo ;#全部新規　KenmeiNoがだぶってるかチェック必要
		$myKenmei->BukkenName = $BukkenName;
		$myKenmei->Prefecture = $Prefecture;
		$myKenmei->Address = $Address;
		$myKenmei->Address2 = $Address2;
		$myKenmei->Bumon = $Bumon;
		$myKenmei->Busyo = $Busyo;
		$myKenmei->TantoName = $TantoName;
		$myKenmei->Shijyo = $Shijyo;
		$myKenmei->HanbaiShijyoKbn = $HanbaiShijyoKbn;
		$myKenmei->Keitai = $Keitai;
		$myKenmei->NonyuDate = $NonyuDate;
		$myKenmei->RenkeiNo = $RenkeiNo;
		$myKenmei->NLXMark = $NLXMark;
$NLXMark = "" ;#初期化
		$myKenmei->KanminKbn = $KanminKbn;
        $myKenmei->SyunkoYoteiDate = $SyunkoYoteiDate;
		$myKenmei->Creator = $UserCD;
		$myKenmei->Updater = $UserCD;


		// 納入済み有寿命品対象機種
		$KenmeiAllData2 = getKenmeiData2($KenmeiNo); // h_kenmei_connect.php
		$sTaisyou = "";
		$Taisyou = "";
		if( is_array($KenmeiAllData2)){

			foreach ( $KenmeiAllData2 as $KenmeiData2 ) {
				$sHINBAN		= $KenmeiData2['HINBAN'];		//有寿命対象機器の型番
				$sJUC_SURYO		= $KenmeiData2['JUC_SURYO'];	//有寿命対象機器の台数
				$Taisyou 		= $sHINBAN.",".$sJUC_SURYO;		//有寿命対象機器の型番と台数
				$sTaisyou 		.= $Taisyou."|";
			}
			if($sTaisyou){
				$myKenmei->Taisyou =  $sTaisyou;
			}
		}


		if (!$myKenmei->executeUpdate()){
				trigger_error("executeUpdate(myKenmei) Failed.", E_USER_ERROR);
		}
}


$IfKenmeiDataKekka = TRUE ;

unset($myRenkeiKenmei);
unset($myKenmei);
####

	########################################################
	# コンテンツ表示
	########################################################

#	SPFWTemplate::setValue("work");
#	SPFWTemplate::setValue("editClientCD", $TargetClientCD);
#	$HiddenValues = SPFWTemplate::getValuesToPass();

#	$CNT_FILE = basename($_SERVER["SCRIPT_NAME"], ".php") . ".tpl";
#	$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier, TRUE);
#	unset($myTemplate);

	unset($myTemplate);
	unset($myLog);
	unset($myDB);
?>
