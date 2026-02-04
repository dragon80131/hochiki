<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', "On");


#WISM7αのエクセル出力

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
	
	#KIKICD = 3(WISM7α)
	$myKikiSettei = new KikiSettei($myDB);
	if(!$myKikiSettei->executeSelect("BukkenCD = ".$editBukkenCD." AND KikiCD = 3",  "")){
	}

	for($i=1 ; $i < 56 ; $i++){
		#集合玄関機（施工予定）
		if($i<7)
			${"wShugoSettei".$i} = $myKikiSettei->{"ShugoSettei".$i};

		#メニュー画面からの設定[住宅情報盤設定指示書]
		if($i<5)
			${"wJutakuMenuSettei".$i} = $myKikiSettei->{"JutakuMenuSettei".$i};

		#施工設定（サービス）[住宅情報盤設定指示書]
		if($i<6)
			${"wJutakuServiceTSettei".$i} = SPFWTools::decodePluralValue($myKikiSettei->{"JutakuServiceTSettei".$i});

		#施工設定（サービス　防犯、窓、玄関、トイレ、バス、部屋など）[住宅情報盤設定指示書]１～４は未使用	
		if($i<41 && $i>4)
			${"wJutakuServiceSettei".$i} = $myKikiSettei->{"JutakuServiceSettei".$i};

		#施工設定（住戸玄関、管理室）[住宅情報盤設定]
		if($i<6)
			${"wJutakuKanriSettei".$i} = $myKikiSettei->{"JutakuKanriSettei".$i};
			
		#施工設定（その他）「住宅情報盤設定]
		if($i<4)
			${"wSonotaSettei".$i} = $myKikiSettei->{"SonotaSettei".$i};
	}
		
		#施工設定（サービス）
		for($ii=1; $ii<6; $ii++){
			for($i=0; $i<count(${"wJutakuServiceTSettei".$ii});$i++ ){
				#0(なし)以外の場合、表示用の編集
				if(${"wJutakuServiceTSettei".$ii}[$i]!=0){
					
					if($i==0)
						${"wJutakuServiceTSettei".$ii."Name"} = $SERVICETSETTEINAME[${"wJutakuServiceTSettei".$ii}[$i]];
					elseif($i%5==0)
						${"wJutakuServiceTSettei".$ii."Name"} .= ",\r\n".$SERVICETSETTEINAME[${"wJutakuServiceTSettei".$ii}[$i]];
						
					else
						${"wJutakuServiceTSettei".$ii."Name"} .= ",　".$SERVICETSETTEINAME[${"wJutakuServiceTSettei".$ii}[$i]];
				}
			}
		}
		/* #施工設定（サービス）
		for($i=0; $i<count($wJutakuServiceSettei1);$i++ ){
			#0(なし)以外の場合、表示用の編集
			if($wJutakuServiceSettei1[$i]!=0){
				
				if($i==0)
					$wJutakuServiceSettei1Name = $SERVICEJYUTAKUNAME[$wJutakuServiceSettei1[$i]];
				elseif($i%3==0)
					$wJutakuServiceSettei1Name .= ",\r\n".$SERVICEJYUTAKUNAME[$wJutakuServiceSettei1[$i]];
					
				else
					$wJutakuServiceSettei1Name .= ",　".$SERVICEJYUTAKUNAME[$wJutakuServiceSettei1[$i]];
			}
		} */
		#JutakuServiceTSetteiの1～５のみ使用　施工設定（サービス選択）
        for ($i=1; $i<6; $i++) {
            #$i=1;
            ${"wJutakuServiceTSettei".$i."Value"} = SPFWTools::encodePluralValue(${"wJutakuServiceTSettei".$i});
        }

		/* echo "<br>wJutakuServiceTSettei1".$wJutakuServiceTSettei1Name;
		echo "<br>wJutakuServiceTSettei2".$wJutakuServiceTSettei2Name;
		echo "<br>wJutakuServiceTSettei3".$wJutakuServiceTSettei3Name;
		echo "<br>wJutakuServiceTSettei4".$wJutakuServiceTSettei4Name;
		echo "<br>wJutakuServiceTSettei5".$wJutakuServiceTSettei5Name;
		trigger_error("executeSelect(myKikiSettei) Failed" , E_USER_ERROR); */
	########################################################
	# DB連携
	########################################################


	$myKikiSettei = new KikiSettei($myDB);

	#kikicd=3(WISM7α)　& BukkenCd=null	:初期値データ
	if(!$myKikiSettei->executeSelect("KikiCD = 3 AND BukkenCD is NULL",""))
		trigger_error("executeSelect(myKikiSettei) Failed" , E_USER_ERROR);

	for($i=1 ;$i<53;$i++ ){
		#集合玄関機（施工予定）
		if($i<7)
			${"dShugoSettei".$i} = $myKikiSettei->{"ShugoSettei".$i};

		#メニュー画面からの設定[住宅情報盤設定指示書]	
		if($i<5)
			${"dJutakuMenuSettei".$i} = $myKikiSettei->{"JutakuMenuSettei".$i};

		##施工設定（サービス）[住宅情報盤設定指示書]
		if($i<6)
			${"dJutakuServiceTSettei".$i} = $myKikiSettei->{"JutakuServiceTSettei".$i};

		#施工設定（サービス　防犯、窓、玄関、トイレ、バス、部屋など）[住宅情報盤設定指示書]１～４は未使用	
		if($i<41 && $i>4)
			${"dJutakuServiceSettei".$i} = $myKikiSettei->{"JutakuServiceSettei".$i};
			
		#施工設定（住戸玄関、管理室）[住宅情報盤設定]	
		if($i<6)
			${"dJutakuKanriSettei".$i} = $myKikiSettei->{"JutakuKanriSettei".$i};

		#施工設定（その他）「住宅情報盤設定]
		if($i<4)
			${"dSonotaSettei".$i} = $myKikiSettei->{"SonotaSettei".$i};
	}

	unset($myKikiSettei);
	
	


	########################################################
	# 初期設定と比較
	########################################################

//集合玄関機設定
	$SHUGOSETTEI[1]="R32";
	$SHUGOSETTEI[2]="R33";
	$SHUGOSETTEI[3]="R34";
	$SHUGOSETTEI[4]="R35";
	$SHUGOSETTEI[5]="R36";
	$SHUGOSETTEI[6]="R37";
	
//住宅情報盤設定
	//メニュー画面からの設定
	$JUTAKUMENUSETTEI[1]="M7";
	$JUTAKUMENUSETTEI[2]="M8";
	$JUTAKUMENUSETTEI[3]="M9";
	$JUTAKUMENUSETTEI[4]="M26";

	//施工設定（サービス）
	$JUTAKUSERVICETSETTEI[1]="AO15";
	$JUTAKUSERVICETSETTEI[2]="AO18";
	$JUTAKUSERVICETSETTEI[3]="AO21";
	$JUTAKUSERVICETSETTEI[4]="AO24";
	$JUTAKUSERVICETSETTEI[5]="AO27";
	

	//施工設定（サービス防犯、玄関、窓、トイレ）
	$JUTAKUSERVICESETTEI[1]="AR14";
	$JUTAKUSERVICESETTEI[2]="L32";
	$JUTAKUSERVICESETTEI[3]="L35";
	$JUTAKUSERVICESETTEI[4]="L38";
	$JUTAKUSERVICESETTEI[5]="BS7";
	$JUTAKUSERVICESETTEI[6]="BS8";
	$JUTAKUSERVICESETTEI[7]="BS9";
	$JUTAKUSERVICESETTEI[8]="BS10";
	$JUTAKUSERVICESETTEI[9]="BS11";
	$JUTAKUSERVICESETTEI[10]="BS12";
	$JUTAKUSERVICESETTEI[11]="BS15";
	$JUTAKUSERVICESETTEI[12]="BS16";
	$JUTAKUSERVICESETTEI[13]="BS17";
	$JUTAKUSERVICESETTEI[14]="BS19";
	$JUTAKUSERVICESETTEI[15]="BS21";
	$JUTAKUSERVICESETTEI[16]="BS25";
	$JUTAKUSERVICESETTEI[17]="BS26";
	$JUTAKUSERVICESETTEI[18]="BS27";
	$JUTAKUSERVICESETTEI[19]="BS28";
	$JUTAKUSERVICESETTEI[20]="BS29";
	$JUTAKUSERVICESETTEI[21]="BS33";
	$JUTAKUSERVICESETTEI[22]="BS34";
	$JUTAKUSERVICESETTEI[23]="BS35";
	$JUTAKUSERVICESETTEI[24]="BS36";
	$JUTAKUSERVICESETTEI[25]="BS37";
	$JUTAKUSERVICESETTEI[26]="BS41";
	$JUTAKUSERVICESETTEI[27]="BS42";
	$JUTAKUSERVICESETTEI[28]="BS43";
	$JUTAKUSERVICESETTEI[29]="BS44";
	$JUTAKUSERVICESETTEI[30]="BS45";
	$JUTAKUSERVICESETTEI[31]="CV7";
	$JUTAKUSERVICESETTEI[32]="CV8";
	$JUTAKUSERVICESETTEI[33]="CV9";
	$JUTAKUSERVICESETTEI[34]="CV10";
	$JUTAKUSERVICESETTEI[35]="CV11";
	$JUTAKUSERVICESETTEI[36]="CV15";
	$JUTAKUSERVICESETTEI[37]="CV16";
	$JUTAKUSERVICESETTEI[38]="CV20";
	$JUTAKUSERVICESETTEI[39]="CV21";
	$JUTAKUSERVICESETTEI[40]="CV22";
	
	//施工設定（住戸玄関、管理室）
	$JUTAKUKANRISETTEI[1]="AR6";
	$JUTAKUKANRISETTEI[2]="AR7";
	$JUTAKUKANRISETTEI[3]="AR8";
	$JUTAKUKANRISETTEI[4]="AR11";
	$JUTAKUKANRISETTEI[5]="AR12";
	
	//施工設定（その他）
	$SONOTASETTEI[1] = "CV25";
	$SONOTASETTEI[2] = "CV26";
	$SONOTASETTEI[3] = "CV27";
	
	
	for($i=1 ;$i<53 ;$i++ ){
		#集合玄関機（施工予定）
		if($i<7){
			if(${"dShugoSettei".$i} !== ${"wShugoSettei".$i}) {
				$ShugoSetteiPlace[]=$SHUGOSETTEI[$i];
				echo "<br />wShugoSettei=".${"wShugoSettei".$i}."/id=".$i."dShugoSettei=".${"dShugoSettei".$i};
			}else{
				${"wShugoSettei".$i} = "";
				echo "<br />/&i=".$i;
			}
		}
		
		#メニュー画面からの設定[住宅情報盤設定指示書]
		if($i<5){
			if(${"dJutakuMenuSettei".$i} !== ${"wJutakuMenuSettei".$i}){
				echo $i.":".$OYAKISETTEI[$i]."<br>";
				$JutakuSetteiPlace[] = $JUTAKUMENUSETTEI[$i];
			}else{
				${"wJutakuMenuSettei".$i} = "";
			}
		}
		#施工設定（サービス）
		if($i<6){
			#配列１のみ使用（1～5のみ使用）
			if (${"dJutakuServiceTSettei".$i} !== ${"wJutakuServiceTSettei".$i."Value"}) {
				$JutakuSetteiPlace[] = $JUTAKUSERVICETSETTEI[$i];
		}else{
			${"wJutakuServiceTSettei".$i} = "";
			}
		}
		
		#施工設定（サービス　防犯、窓、玄関、トイレ、バスなど）
		if($i<41 && $i>4){
			#配列１のみ使用（２～４は未使用のため）
			if(${"dJutakuServiceSettei".$i} !== ${"wJutakuServiceSettei".$i} && $i>4){
				$JutakuSetteiPlace[] = $JUTAKUSERVICESETTEI[$i];
			}else{
				${"wJutakuServiceSettei".$i} = "";
			}
		}
		/* if($i<15){
			#配列１のみ使用（２～４は未使用のため）
			if (${"dJutakuServiceSettei".$i} !== ${"wJutakuServiceSettei".$i."Value"} && $i==1) {
					$JutakuSetteiPlace[] = $JUTAKUSERVICESETTEI[$i];
					#JutakuServiceSettei5から使用分
			}elseif(${"dJutakuServiceSettei".$i} !== ${"wJutakuServiceSettei".$i} && $i>4){
				$JutakuSetteiPlace[] = $JUTAKUSERVICESETTEI[$i];
			}else{
				${"wJutakuServiceSettei".$i} = "";
			}
		} */
		

		#施工設定（玄関子機、管理室）[住宅情報盤設定]
		if($i<6){
			if(${"dJutakuKanriSettei".$i} !== ${"wJutakuKanriSettei".$i}){
				$JutakuSetteiPlace[] = $JUTAKUKANRISETTEI[$i];
			}else{
				${"wJutakuKanriSettei".$i} = "";
			}
		}

		#施工設定（その他）「住宅情報盤設定]
		if($i<4){
			if(${"dSonotaSettei".$i} !== ${"wSonotaSettei".$i}){
				$JutakuSetteiPlace[] = $SONOTASETTEI[$i];
			}else{
				${"wSonotaSettei".$i} = "";
			}
		}
		
	}
	
	
	########################################################
	# Excelファイル生成
	########################################################
	require 'vendor/autoload.php';

	use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;
	use PhpOffice\PhpSpreadsheet\Style;
	use PhpOffice\PhpSpreadsheet\Style\Fill;

	$reader = new XlsxReader();
	$spreadsheet = $reader->load('./template/kikisettei_WISM7α.xlsx'); //template.xlsx 読込

	#集合玄関機設定
	$sheet = $spreadsheet->getSheetByName('集玄設定表（小型集玄）'); //weatherシート取得
	#物件名の表示
	$sheet->setCellValue('AC3',$BukkenName);
	#$sheet->setCellValue('R31', $wShugoSettei1);
	#有無
	$ONOFF[0] = "OFF：無";
	$ONOFF[1] = "ON：有";
	if($wShugoSettei1) 
        $sheet->setCellValue($SHUGOSETTEI[1], $ONOFF[$wShugoSettei1]);
		$sheet->setCellValue($SHUGOSETTEI[2], $ONOFF[$wShugoSettei2]);
		$sheet->setCellValue($SHUGOSETTEI[3], $ONOFF[$wShugoSettei3]);
		$sheet->setCellValue($SHUGOSETTEI[4], $ONOFF[$wShugoSettei4]);
		$sheet->setCellValue($SHUGOSETTEI[5], $ONOFF[$wShugoSettei5]);

	#プリトーン音量
	$PONRYOU[0] = "OFF：標準";
	$PONRYOU[1] = "ON：大";
		$sheet->setCellValue($SHUGOSETTEI[6], $PONRYOU[$wShugoSettei6]);

		//初期値と値が異なっていたら赤色にする
		for($i=0;$i<count($ShugoSetteiPlace);$i++){
			$spreadsheet->getSheetByName('集玄設定表（小型集玄）')->getStyle($ShugoSetteiPlace[$i])->getFill()
				->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
				->getStartColor()->setARGB('FFBAB3');
		}

	$sheet = $spreadsheet->getSheetByName('親機設定表'); //weatherシート取得
	##遅延時間の設定
	$TIENJIKAN2[0] = "0秒";
	$TIENJIKAN2[1] = "30秒";
	$TIENJIKAN2[2] = "60秒";
	$TIENJIKAN2[3] = "90秒";
	$TIENJIKAN2[4] = "2分";
	$TIENJIKAN2[5] = "5分";
	$TIENJIKAN2[6] = "10分";

	##遅延時間
	$JITENTO[0] = "自動点灯しない";
	$JITENTO[1] = "自動点灯する";

		#--メニュー画面
	#--
		$sheet->setCellValue($JUTAKUMENUSETTEI[1], $SIYOU[$wJutakuMenuSettei1]);
		$sheet->setCellValue($JUTAKUMENUSETTEI[2], $TIENJIKAN2[$wJutakuMenuSettei2]);
		$sheet->setCellValue($JUTAKUMENUSETTEI[3], $TIENJIKAN2[$wJutakuMenuSettei3]);
		$sheet->setCellValue($JUTAKUMENUSETTEI[4], $JITENTO[$wJutakuMenuSettei4]);

	#-----施工設定（住戸玄関、管理室）
	#管理室呼出ボタン設定

	$YOBIDASI[0] = "表示しない";
	$YOBIDASI[1] = "お知らせのみ表示";
	$YOBIDASI[2] = "常時表示";
	$YOBIDASI[3] = "安否確認";

	#優先呼出管理室

	$EIZOADAPTER[0] = "別設置";
	$EIZOADAPTER[1] = "内蔵";

	#住戸用電気錠の設置
	$DENKIJYOU[0] = "未設置";
	$DENKIJYOU[1] = "設置";

	#施工設定（玄関子機使用時）

	$sheet->setCellValue($JUTAKUKANRISETTEI[1], $EIZOADAPTER[$wJutakuKanriSettei1]);
	$sheet->setCellValue($JUTAKUKANRISETTEI[2], $NASHIARI1[$wJutakuKanriSettei2]);
	$sheet->setCellValue($JUTAKUKANRISETTEI[3], $DENKIJYOU[$wJutakuKanriSettei3]);
	$sheet->setCellValue($JUTAKUKANRISETTEI[4], $KANRISITUJYUTAKUNAME[$wJutakuKanriSettei4]);
	$sheet->setCellValue($JUTAKUKANRISETTEI[5], $YOBIDASI[$wJutakuKanriSettei5]);

	#----施工設定（サービス）	
    for ($i=1 ;$i<6;$i++) {
        $sheet->setCellValue($JUTAKUSERVICETSETTEI[$i], ${"wJutakuServiceTSettei".$i."Name"});
    }
	
	#---施工設定（サービス　防犯、窓、玄関、トイレ）
	##押しボタン方式
	$OSIBUTTON1[0] = "ノンロック";
	$OSIBUTTON1[1] = "ロック";
	$OSIBUTTON1[2] = "ワンショット";

	##なし、あり
	$NASHIARI1[0] = "あり";
	$NASHIARI1[1] = "なし";

	##遅延時間の設定
	$TIENJIKAN3[0] = "0秒";
	$TIENJIKAN3[1] = "30秒";
	$TIENJIKAN3[2] = "60秒";
	$TIENJIKAN3[3] = "90秒";
	$TIENJIKAN3[4] = "120秒";


	#管理用暗証番号の使用有無
	$ANSYONOUMU[0] = "使用しない";
	$ANSYONOUMU[1] = "25分後使用";
	$ANSYONOUMU[2] = "15分後使用";
	$ANSYONOUMU[3] = "5分後使用";

	#玄関防犯とJEM-Aの連動
	$JEMARENDO[0] = "連動しない";
	$JEMARENDO[1] = "連動する";

	#セット錠防犯の遠隔操作
	$ENKAKUSOUSA[0] = "機能なし";
	$ENKAKUSOUSA[1] = "機能あり";

	#セット錠防犯の室内解除
	$SITUNAIKAIJYO[0] = "不可";
	$SITUNAIKAIJYO[1] = "即時解除";
	$SITUNAIKAIJYO[2] = "暗証解除";

	#防犯、玄関、窓
	$sheet->setCellValue($JUTAKUSERVICESETTEI[5], $NASHIARI1[$wJutakuServiceSettei5]);
	$sheet->setCellValue($JUTAKUSERVICESETTEI[6], $NASHIARI1[$wJutakuServiceSettei6]);
	$sheet->setCellValue($JUTAKUSERVICESETTEI[7], $TIENJIKAN3[$wJutakuServiceSettei7]);
	$sheet->setCellValue($JUTAKUSERVICESETTEI[8], $TIENJIKAN3[$wJutakuServiceSettei8]);
	$sheet->setCellValue($JUTAKUSERVICESETTEI[9], $ANSYONOUMU[$wJutakuServiceSettei9]);
	$sheet->setCellValue($JUTAKUSERVICESETTEI[10], $JEMARENDO[$wJutakuServiceSettei10]);
	$sheet->setCellValue($JUTAKUSERVICESETTEI[11], $JEMARENDO[$wJutakuServiceSettei11]);
	$sheet->setCellValue($JUTAKUSERVICESETTEI[12], $SITUNAIKAIJYO[$wJutakuServiceSettei12]);
	$sheet->setCellValue($JUTAKUSERVICESETTEI[13], $JEMARENDO[$wJutakuServiceSettei13]);
	$sheet->setCellValue($JUTAKUSERVICESETTEI[14], $NASHIARI1[$wJutakuServiceSettei14]);
	$sheet->setCellValue($JUTAKUSERVICESETTEI[15], $KENSYUTU[$wJutakuServiceSettei15]);

	 #トイレ、バス、部屋、コール
	for ($i=0; $i<5; $i++) {
			$idx = 16+(5*$i);
			/* echo "<br>i=".$i."/ii=".$ii."/idx=".$idx;
			echo "<br>??1=".$JUTAKUSERVICESETTEI[$idx];
			echo "<br>??2=".$JUTAKUSERVICESETTEI[$idx+1];
			echo "<br>??3=".$JUTAKUSERVICESETTEI[$idx+2];
			echo "<br>??4=".$JUTAKUSERVICESETTEI[$idx+3];
			echo "<br>??5=".$JUTAKUSERVICESETTEI[$idx+4]; */
			$sheet->setCellValue($JUTAKUSERVICESETTEI[$idx], $TIENJIKAN[${"wJutakuServiceSettei".$idx}]);
            $sheet->setCellValue($JUTAKUSERVICESETTEI[$idx+1], $KENSYUTU[${"wJutakuServiceSettei".($idx+1)}]);
            $sheet->setCellValue($JUTAKUSERVICESETTEI[$idx+2], $OSIBUTTON1[${"wJutakuServiceSettei".($idx+2)}]);
            $sheet->setCellValue($JUTAKUSERVICESETTEI[$idx+3], $NASHIARI1[${"wJutakuServiceSettei".($idx+3)}]);
            $sheet->setCellValue($JUTAKUSERVICESETTEI[$idx+4], $TIENJIKAN[${"wJutakuServiceSettei".($idx+4)}]);
	}
	#非常
	$sheet->setCellValue($JUTAKUSERVICESETTEI[36], $KENSYUTU[$wJutakuServiceSettei36]);
	$sheet->setCellValue($JUTAKUSERVICESETTEI[37], $OSIBUTTON1[$wJutakuServiceSettei37]);
	#漏水
	$sheet->setCellValue($JUTAKUSERVICESETTEI[38], $TIENJIKAN[$wJutakuServiceSettei38]);
	$sheet->setCellValue($JUTAKUSERVICESETTEI[39], $NASHIARI1[$wJutakuServiceSettei39]);
	$sheet->setCellValue($JUTAKUSERVICESETTEI[40], $TIENJIKAN[$wJutakuServiceSettei40]);


#施工設定（その他）
#補助音響設置
$HOJYOMEIDO[0]="警報音のみ";
$HOJYOMEIDO[1]="警報音+呼出音";

#補助音響設置
$DAIHYOUIHOU[0]="警報音のみ";
$DAIHYOUIHOU[1]="警報+呼出";

#緊急放送の音量
$KIKYUONRYO[0]="大";
$KIKYUONRYO[1]="特大";

	$sheet->setCellValue($SONOTASETTEI[1], $HOJYOMEIDO[$wSonotaSettei1]);
	$sheet->setCellValue($SONOTASETTEI[2], $DAIHYOUIHOU[$wSonotaSettei2]);	
	$sheet->setCellValue($SONOTASETTEI[3], $KIKYUONRYO[$wSonotaSettei3]);

		for($i=0;$i<count($JutakuSetteiPlace);$i++){
#echo "i==".$i;
#echo "value==".$JutakuSetteiPlace[$i];
			$spreadsheet->getSheetByName('親機設定表')->getStyle($JutakuSetteiPlace[$i])->getFill()
				->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
				->getStartColor()->setARGB('FFBAB3');
		}
#		showSorryPage(_ILLEGAL_ACCESS);
	#trigger_error("SPFWDatabase Failed.", E_USER_ERROR);
		
	/* 	echo "<Br>1=".$JUTAKUSERVICESETTEI[1]."/".$wJutakuServiceSettei1Name;
		echo "<Br>2=".$JUTAKUSERVICESETTEI[5]."/".$TIENJIKAN[$wJutakuServiceSettei5];
		echo "<Br>3=".$JUTAKUSERVICESETTEI[6]."/".$KENSYUTU[$wJutakuServiceSettei6];
		trigger_error("SPFWDatabase Failed.", E_USER_ERROR);	  */
		#Q--
		
	
	

	//ダウンロード用
	//MIMEタイプ：https://technet.microsoft.com/ja-jp/ee309278.aspx
	header("Content-Description: File Transfer");
	$wFileName = "WISM7α機器設定指示書".date('Ymd').".xlsx" ;
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
