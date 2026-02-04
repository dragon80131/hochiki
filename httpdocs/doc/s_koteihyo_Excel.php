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


	// 繝・・繧ｿ繝吶・繧ｹ繧ｳ繝阪け繝・
	$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
	if (!$myDB->Connection)
		trigger_error("SPFWDatabase Failed.", E_USER_ERROR);

	$rKey = SPFWParameter::getValues('rKey');
	########################################################
	# 隱崎ｨｼ蜍穂ｽ・
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


	########################################################
	# 蜿肴丐繝・・繧ｿ蜿門ｾ怜叙蠕・
	########################################################
	$editBukkenCD = SPFWParameter::getValues('editBukkenCD');
	$wAnnaiDate = SPFWParameter::getValues('wAnnaiDate');	#莠亥ｮ壽｡亥・驟榊ｸ・律
	$wReceptionDate = SPFWParameter::getValues('wReceptionDate');#蜿嶺ｻ倡ｷ譌･
	$wKakuteiDate = SPFWParameter::getValues('wKakuteiDate');	#遒ｺ螳壽｡亥・驟榊ｸ・律

	$wKyoyoStartDate = SPFWParameter::getValues('wKyoyoStartDate');	#蜈ｱ逕ｨ驛ｨ髢句ｧ区律

	$wKyoyoEndDate = SPFWParameter::getValues('wKyoyoEndDate');		#蜈ｱ逕ｨ驛ｨ邨ゆｺ・律
	$wSenyuStartDate = SPFWParameter::getValues('wSenyuStartDate');	#蟆よ怏驛ｨ髢句ｧ区律
	$wSenyuEndDate = SPFWParameter::getValues('wSenyuEndDate');		#蟆よ怏驛ｨ邨ゆｺ・律

	$wKoteiBiko = SPFWParameter::getValues('wKoteiBiko');			#蛯呵・ｬ・
	
	for($i=6 ;$i<11 ;$i++ ){
		${"wDateS".$i} = SPFWParameter::getValues('wDateS'.$i);
		${"wDateE".$i} = SPFWParameter::getValues('wDateE'.$i);
		${"Otherwise".$i} = SPFWParameter::getValues('otherwise'.$i);
		$j=$i-5;
		${"OtherwiseSelect".$j}[${"Otherwise".$i}] = "selected";
	}

	if($wAnnaiDate=="")
		$ErrorStrings[]="譯亥・驟榊ｸ・律繧定ｨｭ螳壹＠縺ｦ縺上□縺輔＞縲・;
	if($wReceptionDate=="")
		$ErrorStrings[]="蜿嶺ｻ倡ｷ蛻・律繧定ｨｭ螳壹＠縺ｦ縺上□縺輔＞縲・;
	if($wKakuteiDate=="")
		$ErrorStrings[]="遒ｺ螳壽｡亥・驟榊ｸ・律繧定ｨｭ螳壹＠縺ｦ縺上□縺輔＞縲・;
	if($wKyoyoStartDate=="")
		$ErrorStrings[]="蜈ｱ逕ｨ驛ｨ髢句ｧ区律繧定ｨｭ螳壹＠縺ｦ縺上□縺輔＞縲・;
	if($wSenyuStartDate=="")
		$ErrorStrings[]="蟆よ怏驛ｨ髢句ｧ区律繧定ｨｭ螳壹＠縺ｦ縺上□縺輔＞縲・;
	
	
	
	$ErrorLoop = count($ErrorStrings);
	if($ErrorLoop > 0){
		$work=1;
		$IfError=TRUE;
		include_once("s_koteihyo_confirm.php");
		exit;
	}

	
	
	

//		$wDateNameCD=  SPFWParameter::getValues('wDateNameCD'.$i);
//		$wDate['DateNameCD'][] 	=  $wDateNameCD;
//		$wDate['DateShortName'][] 	=  $DATESHORTNAME[ $wDateNameCD ];
		$wDate['Start'][] 	=  strtotime( $wAnnaiDate );#譯亥・驟榊ｸ・律
		$wDate['End'][] 	=  "";						#譯亥・驟榊ｸ・律
		$wDate['Name'][] 	=  "譯亥・驟榊ｸ・律";			#譯亥・驟榊ｸ・律
		$wDate['ShortName'][] 	=  "驟榊ｸ・;				#譯亥・驟榊ｸ・律

		$wDate['Start'][] 	=  strtotime( $wReceptionDate );#蜿嶺ｻ倡ｷ蛻・律
		$wDate['End'][] 	=  "";						#蜿嶺ｻ倡ｷ蛻・律
		$wDate['Name'][] 	=  "蜿嶺ｻ倡ｷ蛻・律";			#蜿嶺ｻ倡ｷ蛻・律
		$wDate['ShortName'][] 	=  "邱蛻・;				#

		$wDate['Start'][] 	=  strtotime( $wKakuteiDate );#遒ｺ螳壽｡亥・驟榊ｸ・律
		$wDate['End'][] 	=  "";						#遒ｺ螳壽｡亥・驟榊ｸ・律
		$wDate['Name'][] 	=  "遒ｺ螳壽｡亥・驟榊ｸ・律";			#遒ｺ螳壽｡亥・驟榊ｸ・律
		$wDate['ShortName'][] 	=  "驟榊ｸ・;			#


		$wDate['Start'][] 	=  strtotime( $wKyoyoStartDate );#蜈ｱ逕ｨ驛ｨ
		$wDate['End'][] 	=  ( $wKyoyoEndDate)?strtotime( $wKyoyoEndDate ):"" ;	#
		$wDate['Name'][] 	=  "蜈ｱ逕ｨ驛ｨ蟾･莠・;					#
		$wDate['ShortName'][] 	=  "蜈ｱ逕ｨ";			#


		$wDate['Start'][] 	=  strtotime( $wSenyuStartDate );#蟆よ怏驛ｨ
		$wDate['End'][] 	=  strtotime( $wSenyuEndDate );	#
		$wDate['Name'][] 	=  "蟆よ怏驛ｨ蟾･莠・;					#
		$wDate['ShortName'][] 	=  "蟆よ怏";			#譯亥・驟榊ｸ・律

	#閾ｪ逕ｱ驕ｸ謚・
#	for($i=6 ;$i<11 ;$i++ ){
	for($i=0 ;$i<count( $OTHERWISEDATE ); $i++ ){
		$aa = $i + 5;
		if(${"wDateS".$aa} ){
			$wDate['Start'][] 	=  strtotime( ${"wDateS".$aa} );
			$wDate['End'][] 	=  strtotime( ${"wDateE".$aa} );
			$wDate['Name'][] 	=  $OTHERWISEDATE[${"Otherwise".$aa}];#
			$wDate['ShortName'][] =  $OTHERWISESHORTNAME[${"Otherwise".$aa}];#
		}
	}

#	$wDate['Start']  = array_filter($wDate['Start'] , "strlen");

#繧ｽ繝ｼ繝育畑縺ｫ髢句ｧ区律繧帝｣諠ｳ驟榊・縺ｫ螟画鋤縲繧ｭ繝ｼ=豺ｻ縺亥ｭ・蛟､=髢句ｧ区律
$array_tmp = array_combine(array(0,1,2,3,4,5,6,7,8,9),$wDate['Start']);
#譏・・↓繧ｽ繝ｼ繝・
asort($array_tmp);
#繧ｽ繝ｼ繝亥ｾ後・繧ｭ繝ｼ蛟､繧帝・蛻励↓螟画鋤縺励※蜿門ｾ・
$array_sorted_index = array_keys($array_tmp);


/*echo "<br>";
echo "sortedIndex=====>";
echo var_dump($array_sorted_index);
*/

#sort($wDate['Start']);
#繧ｹ繧ｿ繝ｼ繝域律縺ｧ荳ｦ縺ｳ譖ｿ縺・
#foreach ($wDate['Start'] as $key => $value) {
#    $sort[] = $value;
#echo "<br>144陦檎岼".$key;
#echo "繝ｼ".$value;
#}
#print_r( $sort );

#array_multisort($sort, SORT_ASC, $wDate['End']);
#array_multisort($sort, SORT_ASC, $wDate['ShortName']);
#array_multisort($sort, SORT_ASC, $wDate['Name']);

####20181023 usort( $wDate['Name'], $numeric_sort( 'sort' ) );

#print_r($wDate);
/*
echo "<pre>";
var_dump($wDate['End'] );
echo "</pre>";
echo "<pre>";
var_dump($wDate['Name'] );
echo "</pre>";
*/

	#譛蛻昴・譌･縺ｨ譛蠕後・譌･繧貞叙蠕励☆繧九・
	$wStartDate = min( $wDate['Start'] );
	$pStartDate	= max( $wDate['Start'] ) ;
	$pEndDate	= max( $wDate['End'] ) ;
	if( $pStartDate > $pEndDate ){ 
		$wEndDate = $pStartDate;
	}else{
		$wEndDate = $pEndDate;
	}

$DateCnt =  ($wEndDate - $wStartDate )/86400 ;
#echo "<br>DateCnt:".$DateCnt ;

#echo "<br>max:".date('Y-m-d', max( $wDate['End'] ) );


	$myBukken = new Bukken($myDB);
	if (!$myBukken->executeSelect("BukkenCD > 0 AND MukouFlg = FALSE AND BukkenCD = ".$editBukkenCD , ""))
		trigger_error("Getting tFileF Failed.", E_USER_ERROR);
	$BukkenName = $myBukken->BukkenName;
	unset($myBukken);


	########################################################
	# 譌･遞区ュ蝣ｱ繧稚IraiRenkeiF縺ｫ譬ｼ邏阪＠縺ｦ縺翫￥縲・
	########################################################
/*
	$myIraiRenkei = new IraiRenkei($myDB);

	if (!$myIraiRenkei->executeSelect("BukkenCD = " . $editBukkenCD, "") ){
		$ErrorString = array();
		$ErrorString[] = "tIraiRenkeiF諠・ｱ縺ｮ謚ｽ蜃ｺ縺ｫ螟ｱ謨励＠縺ｾ縺励◆縲・;
		showAdminSorryPage($ErrorString);
	}

	if($myIraiRenkei->RecCnt == 1){
		$editIraiRenkeiCD = $myIraiRenkei->IraiRenkeiCD;

	}else {
		$myIraiRenkei->IraiRenkeiCD = -1;
		$myIraiRenkei->Creator = $wUserCD;
	}

	$myIraiRenkei->YoteDate = $wAnnaiDate;
	$myIraiRenkei->YoyakuEnd = $wReceptionDate;
	$myIraiRenkei->KeteiDate = $wKakuteiDate;
	$myIraiRenkei->KyoyuStartDate = str_replace( "/","" ,$wKyoyoStartDate ) ;#繧ｫ繝ｩ繝蜷肴ｳｨ諢・
	$myIraiRenkei->KyoyoEndDate = $wKyoyoEndDate;
	$myIraiRenkei->SenyuStartDate = $wSenyuStartDate;
	$myIraiRenkei->SenyuEndDate = str_replace( "/","" ,$wSenyuEndDate ) ;


	if($Otherwise6)$myIraiRenkei->Otherwise6 = $Otherwise6;
	if($wDateS6 )$myIraiRenkei->DateS6 = $wDateS6 ;
	if($wDateE6)$myIraiRenkei->DateE6 = $wDateE6 ;

	if($Otherwise7)$myIraiRenkei->Otherwise7 = $Otherwise7;
	if($wDateS7)$myIraiRenkei->DateS7 = $wDateS7 ;
	if($wDateE7)$myIraiRenkei->DateE7 = $wDateE7 ;

	if($Otherwise8)$myIraiRenkei->Otherwise8 = $Otherwise8;
	if($wDateS8)$myIraiRenkei->DateS8 = $wDateS8 ;
	if($wDateE8)$myIraiRenkei->DateE8 = $wDateE8 ;

	if($Otherwise9)$myIraiRenkei->Otherwise9 = $Otherwise9;
	if($wDateS9)$myIraiRenkei->DateS9 = $wDateS9 ;
	if($wDateE9)$myIraiRenkei->DateE9 = $wDateE9 ;

	if($Otherwise10)$myIraiRenkei->Otherwise10 = $Otherwise10;
	if($wDateS10)$myIraiRenkei->DateS10 = $wDateS10 ;
	if($wDateE10)$myIraiRenkei->DateE10 = $wDateE10 ;



	$myIraiRenkei->Updater = $wUserCD;

	if (!$myIraiRenkei->executeUpdate()){
		$ErrorString = array();
		$ErrorString[] = "萓晞ｼ騾｣謳ｺ諠・ｱ縺ｮ譖ｴ譁ｰ縺ｫ螟ｱ謨励＠縺ｾ縺励◆縲・;
		showAdminSorryPage($ErrorString);
	}else{
	#	$IfOK = TRUE;
	}
	unset($myIraiRenkei);
*/



	$myKoji = new Koji($myDB);

	if (!$myKoji->executeSelect("BukkenCD = " . $editBukkenCD, "") ){
		$ErrorString = array();
		$ErrorString[] = "tKojiF諠・ｱ縺ｮ謚ｽ蜃ｺ縺ｫ螟ｱ謨励＠縺ｾ縺励◆縲・;
		showAdminSorryPage($ErrorString);
	}

	$wKojiName = $myKoji->KojiName;#蟾･莠句錐遘ｰ縺ｮ蜿門ｾ励ｒ霑ｽ蜉

	if($myKoji->RecCnt == 1){
		$editKojiCD = $myKoji->KojiCD;
	}else {
		$myKoji->KojiCD = -1;
		$myKoji->Creator = $wUserCD;
	}

	$myKoji->AnnaiDate = $wAnnaiDate;
	$myKoji->ReceptionDate = $wReceptionDate;
	$myKoji->KakuteiDate = $wKakuteiDate;
	$myKoji->KyoyuStartDate = str_replace( "/","" ,$wKyoyoStartDate ) ;#繧ｫ繝ｩ繝蜷肴ｳｨ諢・
	$myKoji->KyoyoEndDate = $wKyoyoEndDate;
	$myKoji->SenyuStartDate = $wSenyuStartDate;
	$myKoji->SenyuEndDate = str_replace( "/","" ,$wSenyuEndDate ) ;


	if($Otherwise6)$myKoji->Otherwise6 = $Otherwise6;
	if($wDateS6 )$myKoji->DateS6 = $wDateS6 ;
	if($wDateE6)$myKoji->DateE6 = $wDateE6 ;

	if($Otherwise7)$myKoji->Otherwise7 = $Otherwise7;
	if($wDateS7)$myKoji->DateS7 = $wDateS7 ;
	if($wDateE7)$myKoji->DateE7 = $wDateE7 ;

	if($Otherwise8)$myKoji->Otherwise8 = $Otherwise8;
	if($wDateS8)$myKoji->DateS8 = $wDateS8 ;
	if($wDateE8)$myKoji->DateE8 = $wDateE8 ;

	if($Otherwise9)$myKoji->Otherwise9 = $Otherwise9;
	if($wDateS9)$myKoji->DateS9 = $wDateS9 ;
	if($wDateE9)$myKoji->DateE9 = $wDateE9 ;

	if($Otherwise10)$myKoji->Otherwise10 = $Otherwise10;
	if($wDateS10)$myKoji->DateS10 = $wDateS10 ;
	if($wDateE10)$myKoji->DateE10 = $wDateE10 ;



	$myKoji->Updater = $wUserCD;

	if (!$myKoji->executeUpdate()){
		$ErrorString = array();
		$ErrorString[] = "萓晞ｼ騾｣謳ｺ諠・ｱ縺ｮ譖ｴ譁ｰ縺ｫ螟ｱ謨励＠縺ｾ縺励◆縲・;
		showAdminSorryPage($ErrorString);
	}else{
	#	$IfOK = TRUE;
	}
	unset($myKoji);


	########################################################
	# Excel繝輔ぃ繧､繝ｫ逕滓・
	########################################################
	require 'vendor/autoload.php';

	use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;
	$reader = new XlsxReader();
	$spreadsheet = $reader->load('./template/koteihyo.xlsx'); //template.xlsx 隱ｭ霎ｼ
#	$sheet = $spreadsheet->getSheetByName('weather'); //weather繧ｷ繝ｼ繝亥叙蠕・
	$sheet = $spreadsheet->getActiveSheet();
#	$sheet->setCellValue('A1', '譚ｱ莠ｬ');
#	$sheet->setCellValue('D1', '譎ｴ繧・);
	$sheet->setCellValue('M2', $BukkenName);
	$sheet->setCellValue('M3', $wKojiName);	#蟾･莠句錐遘ｰ蜃ｺ蜉帙ｒ霑ｽ蜉 20181125
	$sheet->setCellValue('AA2', date('Y',strtotime( $wKyoyoStartDate ) ) );			#蟾･譛溘閾ｪ
	$sheet->setCellValue('AD2', date('m',strtotime( $wKyoyoStartDate ) ) );			#蟾･譛溘閾ｪ
	$sheet->setCellValue('AF2', date('d',strtotime( $wKyoyoStartDate ) ) );			#蟾･譛溘閾ｪ
	$sheet->setCellValue('AA3', date('Y',strtotime( $wSenyuEndDate ) ) );			#蟾･譛溘閾ｳ
	$sheet->setCellValue('AD3', date('m',strtotime( $wSenyuEndDate ) ) );			#蟾･譛溘閾ｳ
	$sheet->setCellValue('AF3', date('d',strtotime( $wSenyuEndDate ) ) );			#蟾･譛溘閾ｳ

	#蛯呵・・蜃ｺ蜉帙ｒ霑ｽ蜉20181125

	$sheet->setCellValue( "M27", $wKoteiBiko);	#蛯呵・・蜃ｺ蜉帙ｒ霑ｽ蜉20181125
	if($DateCnt > 30){
		$sheet->setCellValue( "M52", $wKoteiBiko);
	}
	if($DateCnt > 61){
		$sheet->setCellValue( "M77", $wKoteiBiko);
	}
	
###Start縺九ｉEnd縺ｾ縺ｧFor縺ｧ縺ｾ繧上☆

#蜿りザRL縲https://qiita.com/mosaxiv/items/d71890ef203e506b75fc
#$arrayData = [
#    [NULL, 2016, 2017, 2018],
#    ['Q1', 12, 15, 21],
#    ['Q2', 56, 73, 86],
#    ['Q3', 52, 61, 69],
#    ['Q4', 30, 32, 0],
#];
#$sheet->fromArray($arrayData, NULL, 'C3');

#M5縺悟・譌･縺ｮdate('m')縺ｫ縺吶ｋ縲縺薙％縺九ｉ笘・
$arrayDate =array(); #譌･莉倬Κ蛻・
$arrayYobi = array();#譖懈律驛ｨ蛻・
$weekArray = array("譌･","譛・,"轣ｫ","豌ｴ","譛ｨ","驥・,"蝨・);
$arrayMonth = array();
#2繝壹・繧ｸ逶ｮ
$arrayDatek =array(); #譌･莉倬Κ蛻・
$arrayYobik = array();#譖懈律驛ｨ蛻・
$arrayMonthk = array();

#3繝壹・繧ｸ逶ｮ霑ｽ蜉20181125
$arrayDatekk =array(); #譌･莉倬Κ蛻・
$arrayYobikk = array();#譖懈律驛ｨ蛻・
$arrayMonthkk = array();

$KaraArray = array("","");#鬆・岼縺・陦後≠繧九・縺ｧ遨ｺ陦後ｒ縺・ｌ繧狗畑

$j = 0;
$k = 0;
$kk = 0;
$koeta = 0;#2繝壹・繧ｸ逶ｮ繝輔Λ繧ｰ
$pMonth =  date('m譛・,$wStartDate);#蛻晄悄譛・
$arrayYotei = array();
$arrayYoteik = array();
$arrayYoteikk = array();	#3繝壹・繧ｸ逶ｮ逕ｨ霑ｽ蜉20181125

for( $j=0; $j < count($wDate['Start']); $j++ ){#0鬆・岼
#echo "<br><br>笘・10陦檎岼".$wDate['Start'][$j];
	$jstart = ( $wDate['Start'][$array_sorted_index[$j]] - $wDate['Start'][0] )/86400 ; #繧ｷ繝ｪ繧｢繝ｫ譌･莉・
	#echo "<br>222陦檎岼".$jstart;
	if ($wDate['End'][$array_sorted_index[$j]]) {
		$jend = ( $wDate['End'][$array_sorted_index[$j]] - $wDate['Start'][0] )/86400 ; #繧ｷ繝ｪ繧｢繝ｫ譌･莉・
		#echo "<br>224陦檎岼".$jend;
	}


	$TargetDate = $wStartDate - 86400 ;#繧ｷ繝ｪ繧｢繝ｫ譌･莉・

	$NameGyo = "B".( $j*2+7 );
	$ShortNameGyo = "B".( $j*2+8 );
	$sheet->setCellValue( $NameGyo , $wDate['Name'][$array_sorted_index[$j]]);
#	$sheet->setCellValue( $ShortNameGyo , $wDate['ShortName'][$array_sorted_index[$j]]);


	for( $i=0; $i<=$DateCnt; $i++ ){#1縲譛滄俣縺ｮ譌･謨ｰ蛻・

		$TargetDate = $TargetDate + 86400 ;


		if($j == 0){ #繧ｫ繝ｬ繝ｳ繝繝ｼ驛ｨ蛻・
			if($i==0 or  ( $i < 31 and $pMonth != date('m譛・,$TargetDate)) ){
				array_push( $arrayMonth ,date('m譛・,$TargetDate) );
			}else{
				array_push( $arrayMonth ,NULL );
			}

			if($i == 31 or ( $i > 30 and $pMonth != date('m譛・,$TargetDate)) ){
				array_push( $arrayMonthk ,date('m譛・,$TargetDate) );
			}elseif( $i > 30 ){
				array_push( $arrayMonthk ,NULL );
			}

			if($i == 62 or ( $i > 61 and $pMonth != date('m譛・,$TargetDate)) ){
				array_push( $arrayMonthkk ,date('m譛・,$TargetDate) );
			}elseif( $i > 61 ){
				array_push( $arrayMonthkk ,NULL );
			}
			
			
			if($i < 31){
				array_push( $arrayDate ,date('d',$TargetDate) );
				array_push( $arrayYobi ,$weekArray[ date('w',$TargetDate ) ] );
			}else if ($i < 62){
				array_push( $arrayDatek ,date('d',$TargetDate) );
				array_push( $arrayYobik ,$weekArray[ date('w',$TargetDate ) ] );
			}else{
				array_push( $arrayDatekk ,date('d',$TargetDate) );
				array_push( $arrayYobikk ,$weekArray[ date('w',$TargetDate ) ] );
			
			}
			$pMonth = date('m譛・,$TargetDate);

		}





		if($i < 31){

			if( $TargetDate == $wDate['Start'][$array_sorted_index[$j]] ){#4
				#繧ｻ繝ｫ縺ｫShortName繧偵＞繧後ｋ縲・
				${"Data".$j}[] = $wDate['ShortName'][$array_sorted_index[$j]];
			}elseif( $TargetDate < $wDate['Start'][$array_sorted_index[$j]] ){#4
				${"Data".$j}[] = NULL ;
				${"Data2".$j}[] = NULL ;
			}elseif( $TargetDate > $wDate['Start'][$array_sorted_index[$j]] ){#4
				if( $wDate['End'][$array_sorted_index[$j]] ){#3End譌･縺後≠繧・
					if( $TargetDate <= $wDate['End'][$array_sorted_index[$j]]){ #End譌･繧医ｊ縺｡縺・＆縺・
						${"Data".$j}[] = $wDate['ShortName'][$array_sorted_index[$j]];
					}else{
						${"Data".$j}[] = NULL ;
						${"Data2".$j}[] = NULL ;
						#break;
					}
				}else{#3End譌･縺後↑縺・
					${"Data".$j}[] = NULL ;
					${"Data2".$j}[] = NULL ;
					#break;
				}#3End
			}#4End
			
		}elseif( $i > 30 & $i < 62){
			if ($jstart > 30 OR $jend > 30) {#6
				$NextPage = 1;#笘・ｬ｡繝壹・繧ｸ蜊ｰ
				$NameGyo = "B".( $k*2+32 );			#$k縺ｯ0縺九ｉ繧ｹ繧ｿ繝ｼ繝・
				$ShortNameGyo = "B".( $k*2+33 );
				$sheet->setCellValue( $NameGyo , $wDate['Name'][$array_sorted_index[$j]]);
				#$sheet->setCellValue( $ShortNameGyo , $wDate['ShortName'][$array_sorted_index[$j]]);


				if( $TargetDate == $wDate['Start'][$array_sorted_index[$j]] ){#4
					#繧ｻ繝ｫ縺ｫShortName繧偵＞繧後ｋ縲・
					${"Datak".$j}[] = $wDate['ShortName'][$array_sorted_index[$j]];
				}elseif( $TargetDate < $wDate['Start'][$array_sorted_index[$j]] ){#4
					${"Datak".$j}[] = NULL ;
					${"Data2k".$j}[] = NULL ;
				}elseif( $TargetDate > $wDate['Start'][$array_sorted_index[$j]] ){#4
					if( $wDate['End'][$array_sorted_index[$j]] ){#3End譌･縺後≠繧・
						if( $TargetDate <= $wDate['End'][$array_sorted_index[$j]]){ #End譌･繧医ｊ縺｡縺・＆縺・
							${"Datak".$j}[] = $wDate['ShortName'][$array_sorted_index[$j]];
						}else{
							${"Datak".$j}[] = NULL ;
							${"Data2k".$j}[] = NULL ;
							#break;
						}
					}else{#3End譌･縺後↑縺・
						${"Datak".$j}[] = NULL ;
						${"Data2k".$j}[] = NULL ;
						#break;
					}#3End
				}#4End
			}#6End
			
#3繝壹・繧ｸ逶ｮ縺ｮ蜃ｦ逅・ｒ霑ｽ蜉20181125			
		}else{
		
		if ($jstart > 61 OR $jend > 61) {#6
				$NextPage = 2;#笘・ｬ｡繝壹・繧ｸ蜊ｰ
				$NameGyo = "B".( $kk*2+57);			#$k縺ｯ0縺九ｉ繧ｹ繧ｿ繝ｼ繝・
				$ShortNameGyo = "B".( $kk*2+58 );
				$sheet->setCellValue( $NameGyo , $wDate['Name'][$array_sorted_index[$j]]);
				#$sheet->setCellValue( $ShortNameGyo , $wDate['ShortName'][$array_sorted_index[$j]]);


				if( $TargetDate == $wDate['Start'][$array_sorted_index[$j]] ){#4
					#繧ｻ繝ｫ縺ｫShortName繧偵＞繧後ｋ縲・
					${"Datakk".$j}[] = $wDate['ShortName'][$array_sorted_index[$j]];
				}elseif( $TargetDate < $wDate['Start'][$array_sorted_index[$j]] ){#4
					${"Datakk".$j}[] = NULL ;
				}elseif( $TargetDate > $wDate['Start'][$array_sorted_index[$j]] ){#4
					if( $wDate['End'][$array_sorted_index[$j]] ){#3End譌･縺後≠繧・
						if( $TargetDate <= $wDate['End'][$array_sorted_index[$j]]){ #End譌･繧医ｊ縺｡縺・＆縺・
							${"Datakk".$j}[] = $wDate['ShortName'][$array_sorted_index[$j]];
						}else{
							${"Datakk".$j}[] = NULL ;
							#break;
						}
					}else{#3End譌･縺後↑縺・
						${"Datakk".$j}[] = NULL ;
						#break;
					}#3End
				}#4End
			}#6End
		
		}



	}#1縲譛滄俣縺ｮ譌･謨ｰ蛻・nd

#	if( $NextPage = 1 and empty( ${"Datak".$j} ) != TRUE  ){#31莉･荳翫・縲∥rrayYoteik縺ｫ縺・ｌ繧・


#		$AlreadyNextMark = 1;#谺｡縺ｮ繝壹・繧ｸ縺ｮ繧ｫ繝ｬ繝ｳ繝繝ｼ逕ｨ
	if (${"Datak".$j}) {
			array_push( $arrayYoteik ,${"Datak".$j} );#驟榊・閾ｪ菴薙ｒ$arrayYotei縺ｫ縺・ｌ繧・
			#array_push( $arrayYoteik ,${"Data2k".$j} );#驟榊・閾ｪ菴薙ｒ$arrayYotei縺ｫ縺・ｌ繧・
			array_push( $arrayYoteik ,$KaraArray );#驟榊・閾ｪ菴薙ｒ$arrayYotei縺ｫ縺・ｌ繧・

	#echo "<br>笘・・";
	#var_dump(${"Datak".$j});
			$k++;
	}

	if (${"Datakk".$j}) {
			array_push( $arrayYoteikk ,${"Datakk".$j} );#驟榊・閾ｪ菴薙ｒ$arrayYotei縺ｫ縺・ｌ繧・
			array_push( $arrayYoteikk ,$KaraArray );#驟榊・閾ｪ菴薙ｒ$arrayYotei縺ｫ縺・ｌ繧・

			$kk++;
	}

		array_push( $arrayYotei ,${"Data".$j} );#驟榊・閾ｪ菴薙ｒ$arrayYotei縺ｫ縺・ｌ繧・
		array_push( $arrayYotei ,${"Data2".$j} );#驟榊・閾ｪ菴薙ｒ$arrayYotei縺ｫ縺・ｌ繧・

#	}else{#Datak縺君ULL縺励°縺ｪ縺九▲縺溘ｉ縺薙▲縺｡縲empty( ${"Datak".$j} ) == TRUE 
#		 array_push( $arrayYotei ,${"Data".$j} );#驟榊・閾ｪ菴薙ｒ$arrayYotei縺ｫ縺・ｌ繧・
#		 array_push( $arrayYotei ,${"Data2".$j} );#驟榊・閾ｪ菴薙ｒ$arrayYotei縺ｫ縺・ｌ繧・

#	}



}#0 鬆・岼End



	$sheet->fromArray($arrayMonth, NULL, 'M4');
	$sheet->fromArray($arrayDate, NULL, 'M5');
	$sheet->fromArray($arrayYobi, NULL, 'M6');
	$sheet->fromArray($arrayYotei, NULL, 'M7');

	#2繝壹・繧ｸ逶ｮ 31譌･莉･荳・
	$sheet->fromArray($arrayMonthk, NULL, 'M29');
	$sheet->fromArray($arrayDatek, NULL, 'M30');
	$sheet->fromArray($arrayYobik, NULL, 'M31');
	$sheet->fromArray($arrayYoteik, NULL, 'M32');

	#3繝壹・繧ｸ逶ｮ 62譌･莉･荳・霑ｽ蜉20181125
	$sheet->fromArray($arrayMonthkk, NULL, 'M54');
	$sheet->fromArray($arrayDatekk, NULL, 'M55');
	$sheet->fromArray($arrayYobikk, NULL, 'M56');
	$sheet->fromArray($arrayYoteikk, NULL, 'M57');






	//繝繧ｦ繝ｳ繝ｭ繝ｼ繝臥畑
	//MIME繧ｿ繧､繝暦ｼ喇ttps://technet.microsoft.com/ja-jp/ee309278.aspx
	header("Content-Description: File Transfer");
	$wFileName = "蟾･遞玖｡ｨ".date('Ymd').".xlsx" ;
	header("Content-Disposition: attachment; filename=".$wFileName );
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Transfer-Encoding: binary');
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	header('Expires: 0');
	ob_end_clean(); //繝舌ャ繝輔ぃ豸亥悉
	   
	$writer = new XlsxWriter($spreadsheet);
	$writer->save('php://output');







?>
