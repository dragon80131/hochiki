<?php
#WISM7ﾎｱ邱ｨ髮・

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


	include_once  "./include/common.php";



	// 繝・・繧ｿ繝吶・繧ｹ繧ｳ繝阪け繝・
	$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
	if (!$myDB->Connection)
		trigger_error("SPFWDatabase Failed.", E_USER_ERROR);

	$rKey = SPFWParameter::getValues('rKey');
	$editBukkenCD = SPFWParameter::getValues('editBukkenCD');
	$checkcopy = SPFWParameter::getValues('checkcopy');

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
	$TantoTEL = getTantoData($myDB, $UserCD )['TEL'];


	########################################################
	# 迚ｩ莉ｶ諠・ｱ謚ｽ蜃ｺ
	########################################################
	$myBukken = new Bukken($myDB);

	if (!$myBukken->executeSelect("BukkenCD = " . $editBukkenCD . " AND MukouFlg = FALSE", "")){
		trigger_error("Getting Bukken Failed.", E_USER_ERROR);
	}

	if ($myBukken->RecCnt != 1) {
		trigger_error("Getting myBukken List Failed.", E_USER_ERROR);

	} else {
	#蠢・ｦ√↑鬆・岼縲繝槭Φ繧ｷ繝ｧ繝ｳ蜷阪∝ｷ･莠句錐遘ｰ縲∽ｽ乗園縲∵虻謨ｰ縲∫ｮ｡逅・ｼ夂､ｾ縲∵ｶ磯亟迚ｹ萓九∫ｮ｡逅・ｼ夂､ｾ縲∫ｮ｡逅・藤髢｢菫・

		$KanriGaisya = $myBukken->KanriGaisya;
		$BukkenName = $myBukken->BukkenName;


	}
	
	########################################################
	# DB騾｣謳ｺ
	########################################################

	$myKikiSettei = new KikiSettei($myDB);
	
	
	# kikiCd=3(WISM7ﾎｱ)
	if(!$myKikiSettei->executeSelect("KikiCD = 3 AND BukkenCD IS NULL",""))
		trigger_error("executeSelect(myKikiSettei) Failed" , E_USER_ERROR);

		for($i=1 ;$i<56 ;$i++ ){
			
			#--- DB繧偵そ繝・ヨ ----
			
			#髮・粋邇・未讖滂ｼ域命蟾･莠亥ｮ夲ｼ・
			if($i<7)
				${"wShugoSettei".$i} = $myKikiSettei->{"ShugoSettei".$i};
			
			#繝｡繝九Η繝ｼ逕ｻ髱｢縺九ｉ縺ｮ險ｭ螳喙菴丞ｮ・ュ蝣ｱ逶､險ｭ螳壽欠遉ｺ譖ｸ]
			if($i<5)
				${"wJutakuMenuSettei".$i} = $myKikiSettei->{"JutakuMenuSettei".$i};
		
			#譁ｽ蟾･險ｭ螳夲ｼ医し繝ｼ繝薙せ・閏菴丞ｮ・ュ蝣ｱ逶､險ｭ螳壽欠遉ｺ譖ｸ]
			#if($i<53) 笘・ｽ・5
			if($i<15)
				${"wJutakuServiceSettei".$i} = $myKikiSettei->{"JutakuServiceSettei".$i};
	
			#譁ｽ蟾･險ｭ螳夲ｼ育私髢｢蟄先ｩ溘∫ｮ｡逅・ｮ､・閏菴丞ｮ・ュ蝣ｱ逶､險ｭ螳咯
			if($i<6)
				${"wJutakuKanriSettei".$i} = $myKikiSettei->{"JutakuKanriSettei".$i};
					
			#譁ｽ蟾･險ｭ螳夲ｼ医◎縺ｮ莉厄ｼ峨御ｽ丞ｮ・ュ蝣ｱ逶､險ｭ螳咯
			if($i<4)
				${"wSonotaSettei".$i} = $myKikiSettei->{"SonotaSettei".$i};

			#--- 鬆・岼縺ｮ濶ｲ縲√メ繧ｧ繝・け縺ｮ險ｭ螳・----

			#髮・粋邇・未讖滂ｼ域命蟾･莠亥ｮ夲ｼ・
			if($i<7){
				${"wShugoSettei".$i."BG".${"wShugoSettei".$i}} = "style='background-color:#A9F5A9'";
				${"wShugoSettei".$i."Checked".${"wShugoSettei".$i}} = "checked";
			}
			#繝｡繝九Η繝ｼ逕ｻ髱｢縺九ｉ縺ｮ險ｭ螳喙菴丞ｮ・ュ蝣ｱ逶､險ｭ螳壽欠遉ｺ譖ｸ]
			if($i<5){
				${"wJutakuMenuSettei".$i."BG".${"wJutakuMenuSettei".$i}} = "style='background-color:#A9F5A9'";
				${"wJutakuMenuSettei".$i."Checked".${"wJutakuMenuSettei".$i}} = "checked";
			}
			

			#譁ｽ蟾･險ｭ螳夲ｼ医し繝ｼ繝薙せ・閏菴丞ｮ・ュ蝣ｱ逶､險ｭ螳壽欠遉ｺ譖ｸ]
			if($i<5){
				${"wJutakuServiceSettei".$i."BG"}[0] = "style='background-color:#A9F5A9'";
				${"wJutakuServiceSettei".$i."Checked"}[0] = "checked";
			}elseif($i<15){
				${"wJutakuServiceSettei".$i."BG".${"wJutakuServiceSettei".$i}} = "style='background-color:#A9F5A9'";
				${"wJutakuServiceSettei".$i."Checked".${"wJutakuServiceSettei".$i}} = "checked";
			}
			
			#譁ｽ蟾･險ｭ螳夲ｼ育私髢｢蟄先ｩ溘∫ｮ｡逅・ｮ､・閏菴丞ｮ・ュ蝣ｱ逶､險ｭ螳咯
			if($i==3){
				${"wJutakuKanriSettei".$i."BG"}[${"wJutakuKanriSettei".$i}] = "style='background-color:#A9F5A9'";
				${"wJutakuKanriSettei".$i."Checked"}[${"wJutakuKanriSettei".$i}] = "checked";
			}elseif($i<5){
				${"wJutakuKanriSettei".$i."BG".${"wJutakuKanriSettei".$i}} = "style='background-color:#A9F5A9'";
				${"wJutakuKanriSettei".$i."Checked".${"wJutakuKanriSettei".$i}} = "checked";
			}
			
			#譁ｽ蟾･險ｭ螳夲ｼ医◎縺ｮ莉厄ｼ峨御ｽ丞ｮ・ュ蝣ｱ逶､險ｭ螳咯
			if($i<4){
				${"wSonotaSettei".$i."BG".${"wSonotaSettei".$i}} = "style='background-color:#A9F5A9'";
				${"wSonotaSettei".$i."Checked".${"wSonotaSettei".$i}} = "checked";
			}
		}

	unset($myKikiSettei);
		#譁ｽ蟾･險ｭ螳夲ｼ育ｮ｡逅・ｮ､・・[菴丞ｮ・ュ蝣ｱ逶､險ｭ螳咯
	$JutakuKanriSettei4Loop=count($KANRISITUJYUTAKUNAME);
	for($i=0 ; $i<$JutakuKanriSettei3Loop ; $i++){
		$wJutakuKanriSettei3CD[$i]=$i;
		if($i%3==0){
			$TRStart1[$i]="<tr>";
			$TREnd1[$i+2]="</tr>";
		}
	}
		#譁ｽ蟾･險ｭ螳夲ｼ医し繝ｼ繝薙せ驕ｸ謚橸ｼ閏菴丞ｮ・ュ蝣ｱ逶､]
	$ServiceLoop = count($SERVICEJYUTAKUNAME);
	for($i=0 ; $i<$ServiceLoop ; $i++){
		$wJutakuServiceSetteiCD[$i] = $i;
		if($i%6==0){
			$TRStart3[$i]="<tr>";
			/* $TREnd3[$i+5]="</tr>"; */
			$TREnd3[$i+2]="</tr>";
		}
	}

	
	/* $OyakiSettei31Disabled = "disabled";
	$OyakiSettei33Disabled = "disabled"; */

	
	/* echo 'kaunin:', $wShugoSettei1;
	echo 'KIKISETTEICD:', $KikiSetteiCD;
	trigger_error("executeSelect(myKikiSettei) Failed" , E_USER_ERROR); */

	
	########################################################
	# 驕主悉縺ｮ迚ｩ莉ｶ縺ｮ繧ｳ繝斐・
	########################################################

	if(isset($checkcopy)){
		$myKikiSettei = new KikiSettei($myDB);
				
		if(!$myKikiSettei->executeSelect("BukkenCD = ".$checkcopy." AND kikiCD=3",""))
			trigger_error("executeSelect(myKikiSettei) Failed" , E_USER_ERROR);
	

		for($i=1 ;$i<53 ;$i++ ){

			#----隍・・蜈・・繝・・繧ｿ蜀・ｮｹ繧偵そ繝・ヨ-----
			if($i<7)	#髮・粋邇・未讖溯ｨｭ螳夲ｼ域命蟾･險ｭ螳夲ｼ・
				${"dShugoSettei".$i} = $myKikiSettei->{"ShugoSettei".$i};
			if($i<5)	#繝｡繝九Η繝ｼ逕ｻ髱｢縺九ｉ縺ｮ險ｭ螳喙菴丞ｮ・ュ蝣ｱ逶､險ｭ螳壽欠遉ｺ譖ｸ]
				${"dJutakuMenuSettei".$i} = $myKikiSettei->{"JutakuMenuSettei".$i};

			if($i<15)	#譁ｽ蟾･險ｭ螳夲ｼ医し繝ｼ繝薙せ・閏菴丞ｮ・ュ蝣ｱ逶､險ｭ螳壽欠遉ｺ譖ｸ]・抵ｽ橸ｼ斐・譛ｪ菴ｿ逕ｨ
				${"dJutakuServiceSettei".$i} = $myKikiSettei->{"JutakuServiceSettei".$i};

			if($i<5)	#譁ｽ蟾･險ｭ螳夲ｼ育私髢｢蟄先ｩ溘∫ｮ｡逅・ｮ､・閏菴丞ｮ・ュ蝣ｱ逶､險ｭ螳咯
				${"dJutakuKanriSettei".$i} = $myKikiSettei->{"JutakuKanriSettei".$i};
			
			if($i<4)	#譁ｽ蟾･險ｭ螳夲ｼ医◎縺ｮ莉厄ｼ峨御ｽ丞ｮ・ュ蝣ｱ逶､險ｭ螳咯
				${"dSonotaSettei".$i} = $myKikiSettei->{"SonotaSettei".$i};

			#----隍・・蜈・・繝・・繧ｿ繧偵ｂ縺ｨ縺ｫ濶ｲ縲√メ繧ｧ繝・け繧偵そ繝・ヨ-----
			#髮・粋邇・未讖滂ｼ域命蟾･莠亥ｮ夲ｼ・
			if(${"dShugoSettei".$i}!==${"wShugoSettei".$i}&&$i<7){
				${"wShugoSettei".$i."BG".${"dShugoSettei".$i}} = "style='background-color:#ffbab3'";
				${"wShugoSettei".$i."Checked".${"wShugoSettei".$i}} = "";
				${"wShugoSettei".$i."Checked".${"dShugoSettei".$i}} = "checked";
			}
			#繝｡繝九Η繝ｼ逕ｻ髱｢縺九ｉ縺ｮ險ｭ螳喙菴丞ｮ・ュ蝣ｱ逶､險ｭ螳壽欠遉ｺ譖ｸ]
			if($i<5 && ${"dJutakuMenuSettei".$i}!==${"wJutakuMenuSettei".$i}){
				${"wJutakuMenuSettei".$i."BG".${"dJutakuMenuSettei".$i}} = "style='background-color:#ffbab3'";
				${"wJutakuMenuSettei".$i."Checked".${"wJutakuMenuSettei".$i}} = "";
				${"wJutakuMenuSettei".$i."Checked".${"dJutakuMenuSettei".$i}} = "checked";
			}
			#譁ｽ蟾･險ｭ螳夲ｼ医し繝ｼ繝薙せ・閏菴丞ｮ・ュ蝣ｱ逶､險ｭ螳壽欠遉ｺ譖ｸ]
			if($i<5 && ${"dJutakuServiceSettei".$i}!==${"wJutakuServiceSettei".$i}){
				${"dJutakuServiceSettei".$i."Value"} = SPFWTools::decodePluralValue(${"dJutakuServiceSettei".$i});#驟榊・
				${"wJutakuServiceSettei".$i."Value"} = SPFWTools::decodePluralValue(${"wJutakuServiceSettei".$i});#驟榊・
				for($j=0 ; $j<count(${"dJutakuServiceSettei".$i."Value"}) ; $j++){

					if(array_search(${"dJutakuServiceSettei".$i."Value"}[$j],${"wJutakuServiceSettei".$i."Value"})===false){

						${"wJutakuServiceSettei".$i."BG"}[${"dJutakuServiceSettei".$i."Value"}[$j]] = "style='background-color:#ffbab3'";
						${"wJutakuServiceSettei".$i."Checked"}[${"wJutakuServiceSettei".$i."Value"}[$j]] = "";
						${"wJutakuServiceSettei".$i."Checked"}[${"dJutakuServiceSettei".$i."Value"}[$j]] = "checked";
					}
				}
			}elseif($i<15 && ${"dJutakuServiceSettei".$i}!==${"wJutakuServiceSettei".$i}){
				${"wJutakuServiceSettei".$i."BG".${"dJutakuServiceSettei".$i}} = "style='background-color:#ffbab3'";
				${"wJutakuServiceSettei".$i."Checked".${"wJutakuServiceSettei".$i}} = "";
				${"wJutakuServiceSettei".$i."Checked".${"dJutakuServiceSettei".$i}} = "checked";
			}
			#譁ｽ蟾･險ｭ螳夲ｼ育私髢｢蟄先ｩ溘∫ｮ｡逅・ｮ､・閏菴丞ｮ・ュ蝣ｱ逶､險ｭ螳咯 koko
			if($i==3 && ${"dJutakuKanriSettei".$i}!==${"wJutakuKanriSettei".$i}){
				${"wJutakuKanriSettei".$i."BG"}[${"dJutakuKanriSettei".$i}] = "style='background-color:#ffbab3'";
				${"wJutakuKanriSettei".$i."Checked"}[${"wJutakuKanriSettei".$i}] = "";
				${"wJutakuKanriSettei".$i."Checked"}[${"dJutakuKanriSettei".$i}] = "checked";
			}elseif($i<23 && ${"dJutakuKanriSettei".$i}!==${"wJutakuKanriSettei".$i}){
				${"wJutakuKanriSettei".$i."BG".${"dJutakuKanriSettei".$i}} = "style='background-color:#ffbab3'";
				${"wJutakuKanriSettei".$i."Checked".${"wJutakuKanriSettei".$i}} = "";
				${"wJutakuKanriSettei".$i."Checked".${"dJutakuKanriSettei".$i}} = "checked";
			}
			#譁ｽ蟾･險ｭ螳夲ｼ医◎縺ｮ莉厄ｼ峨御ｽ丞ｮ・ュ蝣ｱ逶､險ｭ螳咯
			if($i<5 && ${"dSonotaSettei".$i}!==${"wSonotaSettei".$i}){
				${"wSonotaSettei".$i."BG".${"dSonotaSettei".$i}} = "style='background-color:#ffbab3'";
				${"wSonotaSettei".$i."Checked".${"wSonotaSettei".$i}} = "";
				${"wSonotaSettei".$i."Checked".${"dSonotaSettei".$i}} = "checked";
			}
			
		}
		if($dOyakiSettei30==1){
			$OyakiSettei31Disabled = "";
		}elseif($dOyakiSettei30==0){
			$OyakiSettei31Disabled = "disabled";
		}
		if($dOyakiSettei32==1){
			$OyakiSettei33Disabled = "";
		}elseif($dOyakiSettei32==0){
			$OyakiSettei33Disabled = "disabled";
		}





	}













	########################################################
	# 繧ｳ繝ｳ繝・Φ繝・｡ｨ遉ｺ
	########################################################
	
	$CNT_FILE = "s_kikiseteiWISM7ﾎｱ.tpl";
	ECHO "!!";
	
	$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier);
	$HiddenValues = $myTemplate->getValuesToPass();
	echo "!!";
	$myTemplate->convertTags();
	$myTemplate->outputTemplate();

	unset($myTemplate);
	unset($myLog);

?>
