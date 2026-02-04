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
	include_once _CLS_DIR . "SPUSUser.cls";

	include_once _CLS_DIR . "SPUSBukken.cls";
	include_once _CLS_DIR . "SPUSSiten.cls";

	include_once _CLS_DIR . "SPUSIraiRenkei.cls";
	include_once _CLS_DIR . "SPUSIraiFile.cls";
	include_once _CLS_DIR . "SPUSBukkenMatrix.cls";
	include_once _CLS_DIR . "SPFWParameter.cls";
	include_once _CLS_DIR . "SPUSKoji.cls";

	// データベースコネクト
	$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
	if (!$myDB->Connection)
		trigger_error("SPFWDatabase Failed.", E_USER_ERROR);


	########################################################
	# 入力チェック
	########################################################
	$rKey = SPFWParameter::getValues('rKey');
	$format = SPFWParameter::getValues('format');

	if ($rKey == NULL) {
		echo a;
		$URL = _MAIN_URL . 'login_form.php';
		header('Location: ' . $URL);
		exit;
	}


	########################################################
	# 認証動作
	########################################################

	$myUser = new User($myDB);

	if (!$myUser->doAuthenticationByRegistKey($rKey))
		trigger_error("doAuthentication Failed.", E_USER_ERROR);

	if ($myUser->UserCD == -1) {
		$URL = _MAIN_URL . 'login_form.php';
		header('Location: ' . $URL);
		exit;
	}

	$wUserCD = $myUser->UserCD;
	$MyShozokuCD = $myUser->Extra1 ;	#所属支店CD
	$MyZokusei = $myUser->Extra3 ;		#管理ユーザ２一般ユーザ１
	if($MyZokusei == 2 ) $IfNespe = TRUE ;#予定案内表示
	$MyEigyoshoCD = $myUser->Extra4 ;	#営業所CD nespeユーザはNULLになってる


	########################################################
	# BukkenMatrix　部屋構成 登録
	########################################################

	$editBukkenCD = SPFWParameter::getValues('editBukkenCD');
	
	$work = SPFWParameter::getValues('work');


#	if( $work == 1 ){
		$KojiJun = SPFWParameter::getValues('KojiJun');
		$KaiRoom = SPFWParameter::getValues('KaiRoom');#配列


#		$myBukkenMatrix = new BukkenMatrix($myDB);

#		if (!$myBukkenMatrix->executeSelect("BukkenCD = " . $editBukkenCD . " AND MukouFlg = FALSE", "")){
#			$ErrorString = array();
#			$ErrorString[] = "tBukkenM情報の抽出に失敗しました。";
#			showSorryPage($ErrorString);
#		}

#		if ($myBukkenMatrix->RecCnt == 0) {#新規
#			$myBukkenMatrix->BukkenMatrixCD = -1;
#		}

/*
		$myBukkenMatrix->BukkenCD = $editBukkenCD;
		$myBukkenMatrix->KaiRoom = SPFWTools::encodePluralValue($KaiRoom);

		if (!$myBukkenMatrix->executeUpdate()){
			$ErrorString = array();
			$ErrorString[] = "依頼連携情報の更新に失敗しました。";
			showAdminSorryPage($ErrorString);
		}else{
			$IfOK = TRUE ;

		}
*/
#	}


	########################################################
	# 物件情報抽出
	########################################################

if ( $editBukkenCD > 0 ){ #物件情報の修正の場合

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
	$wTosu = $myBukken->Tosu;
	$wKosu = $myBukken->Kosu;
	$wKaidaka = $myBukken->Kaidaka;



	########################################################
	# 部屋構成情報抽出
	########################################################



	$RoomSuu = count($KaiRoom);
	for ($x=0; $x < count($KaiRoom); $x++){
		#　前はいくつあるか不定　後ろはゼロサブで2桁固定 大きい部屋から格納されている
		$Room[$x] = substr($KaiRoom[$x],-2);
		#階高がかならずしも建物の部屋の前の文字を表していない 右うしろ2桁以外の文字 空に置き換え
		$Kai[$x] = str_replace( $Room[$x] , "", $KaiRoom[$x]); 
		#echo "<br>Kai-Room:".$Kai[$x]."-".$Room[$x] ;
	}


	########################################################
	# ２重ループ最小構成 Tate Yoko ( x,y )
	########################################################

	$CNT_FILE = "s_make_kanryo.tpl";
	$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier);

	// リスト部分(Loopの中身)のエレメント確定
	$LoopString = $myTemplate->getStringBetween('ColsLoop');
	$LoopString = '__ColsLoop__' . $LoopString . '__ColsLoop__';



	#$ColsLoop = count($Yoko );#
	$ColsLoop = max($Room) ;#
	$RowsLoop = $wKaidaka;#
#echo "182行目:".$RowsLoop;
	$x = 0;
	for ($i = 0; $i < $RowsLoop; $i++){

		$wKaiStart = 0;
		for ($j = 0; $j < $ColsLoop; $j++) {#上からのフロアごとに左にすすむ

			if($wKaiStart == $Kai[$x] or $wKaiStart == 0){#
				$Pic[$j] = $KaiRoom[$x];
				$wKaiStart = $Kai[$x];
				$x = $x + 1;
			}else{#階が異なっていたらーをいれておく。
				$Pic[$j] = "-";
			}
		}

		$myTemplate->Msg = $LoopString;
		$myTemplate->convertTags();
		$ColsBlock[$i] = $myTemplate->Msg;

	}

}
	$wColsBlock = SPFWTools::encodePluralValue($ColsBlock);
#	echo 'wColsBlock=',$wColsBlock."<br/>";
	
	$xKaiRoom = SPFWTools::encodePluralValue($KaiRoom);
#	echo 'KaiRoom!!!='. $xKaiRoom."<br/>";


	########################################################
	# コンテンツ表示
	########################################################

	$CNT_FILE = "s_syouboukensa_kakutei.tpl";

	$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier);
	$HiddenValues = $myTemplate->getValuesToPass();

	$myTemplate->convertTags();
	$myTemplate->outputTemplate();

	unset($myTemplate);
	unset($myLog);
?>
