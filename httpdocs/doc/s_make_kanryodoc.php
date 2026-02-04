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
#	include_once _CLS_DIR . "SPUSTanto.cls";
	include_once _CLS_DIR . "SPUSSiten.cls";

	include_once _CLS_DIR . "SPUSIraiRenkei.cls";
	include_once _CLS_DIR . "SPUSIraiFile.cls";
	include_once _CLS_DIR . "SPUSBukkenMatrix.cls";

	// データベースコネクト
	$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
	if (!$myDB->Connection)
		trigger_error("SPFWDatabase Failed.", E_USER_ERROR);


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
	$MyEigyoshoCD = $myUser->Extra4 ;	#営業所CD nespeユーザはNULLになってる


	########################################################
	# 入力チェック
	########################################################

	if ($rKey == NULL) {
		$URL = _MAIN_URL . 'login_form.php';
		header('Location: ' . $URL);
		exit;
	} 



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
	$wKosu = $myBukken->Kosu;
	$wKaidaka = $myBukken->Kaidaka;



	########################################################
	# 部屋構成情報抽出
	########################################################

	$myBukkenMatrix = new BukkenMatrix($myDB);


	if (!$myBukkenMatrix->executeSelect("BukkenCD = " . $editBukkenCD . " AND MukouFlg = FALSE", "")){
		$ErrorString = array();
		$ErrorString[] = "tBukkenM情報の抽出に失敗しました。";
		showSorryPage($ErrorString);
	}

	if ($myBukkenMatrix->RecCnt == 0) {#新規
		echo "未作成です。";
	}else{


		$KaiRoom = $myBukkenMatrix->KaiRoom;
		$KaiRoom = SPFWTools::decodePluralValue($KaiRoom);

		for ($x=0; $x < count($KaiRoom); $x++){
			#　前はいくつあるか不定　後ろはゼロサブで2桁固定 大きい部屋から格納されている
			$Room[$x] = substr($KaiRoom[$x],-2);
			#階高がかならずしも建物の部屋の前の文字を表していない 右うしろ2桁以外の文字 空に置き換え
			$Kai[$x] = str_replace( $Room[$x] , "", $KaiRoom[$x]); 
			#echo "<br>Kai-Room:".$Kai[$x]."-".$Room[$x] ;
		}



	}

}



	########################################################
	# ２重ループ最小構成 Tate Yoko ( x,y )
	########################################################

	$CNT_FILE = "s_make_kanryodoc.tpl";
	$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier);

	// リスト部分(Loopの中身)のエレメント確定
	$LoopString = $myTemplate->getStringBetween('ColsLoop');
	$LoopString = '__ColsLoop__' . $LoopString . '__ColsLoop__';




#	$ColsLoop = count($Yoko );#
	$ColsLoop = max($Room) ;#
	$RowsLoop = $wKaidaka;#

	$x = 0;
	for ($i = 0; $i < $RowsLoop; $i++){
	$Kaidaka[$i] = $wKaidaka - $i;
		$wKaiStart = 0;
		for ($j = 0; $j < $ColsLoop; $j++) {#上からのフロアごとに左にすすむ

			if($wKaiStart == $Kai[$x] or $wKaiStart == 0){#
				$Pic[$j] = $KaiRoom[$x];
				$wKaiStart = $Kai[$x];
				$x = $x + 1;
			}else{#階が異なっていたらーをいれておく。
				$Pic[$j] = "-";
			}

			$ColsBlock2[$i] = $ColsBlock2[$i]."<td  ><font color=gainsboro >○</font></td>";

		}

	$myTemplate->Msg = $LoopString;
	$myTemplate->convertTags();
	$ColsBlock[$i] = $myTemplate->Msg;

	}



	########################################################
	# コンテンツ表示
	########################################################

	$CNT_FILE = "s_make_kanryodoc.tpl";

	$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier);
	$HiddenValues = $myTemplate->getValuesToPass();

	$myTemplate->convertTags();
	$myTemplate->outputTemplate();

	unset($myTemplate);
	unset($myLog);


?>
