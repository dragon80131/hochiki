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

	include_once _CLS_DIR . "SPUSBukkenMatrix.cls";
	include_once _CLS_DIR . "SPUSIraiFile.cls";
	include_once _CLS_DIR . "SPFWParameter.cls";

	// データベースコネクト
	$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
	if (!$myDB->Connection)
		trigger_error("SPFWDatabase Failed.", E_USER_ERROR);


	########################################################
	# 入力チェック
	########################################################

	if ($rKey == NULL) {
		$URL = _MAIN_URL . 'login_form.php';
		header('Location: ' . $URL);
		exit;
	}

	########################################################
	# 認証動作
	########################################################

	$rKey = SPFWParameter::getValues('rKey');

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
	# 物件情報抽出
	########################################################

	$editBukkenCD = SPFWParameter::getValues('editBukkenCD');
	$KaiRoom = SPFWParameter::getValues('KaiRoom');
	$wYoko = SPFWParameter::getValues('wYoko');



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

	$editBukkenCD = $myBukken->BukkenCD;
	$wBukkenName = $myBukken->BukkenName;
	$wTantoCD = $myBukken->TantoCD;
#	$wShozokuCD = $myBukken->ShozokuCD;
#	$wTosu = $myBukken->Tosu;
	$wKosu = $myBukken->Kosu;
	$wKaidaka = $myBukken->Kaidaka;


	########################################################
	# 部屋構成情報抽出
	########################################################

	$myBukkenMatrix = new BukkenMatrix($myDB);

	if (!$myBukkenMatrix->executeSelect("BukkenCD = " . $editBukkenCD . " AND KaiRoom > 0 AND MukouFlg = FALSE", "")){
		$ErrorString = array();
		$ErrorString[] = "tBukkenM情報の抽出に失敗しました。";
		showSorryPage($ErrorString);
	}

	if ($myBukkenMatrix->RecCnt == 0) {#新規
		#echo "未作成です。";
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

		if( $wYoko < max($Room)){
			$wYoko = max($Room);
		}
		$x = 0;
	}
}



	########################################################
	# ２重ループ最小構成
	########################################################

	$CNT_FILE = "s_make_matrix.tpl";
	$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier);

	// リスト部分(Loopの中身)のエレメント確定
	$LoopString = $myTemplate->getStringBetween('ColsLoop');
	$LoopString = '__ColsLoop__' . $LoopString . '__ColsLoop__';

#echo str_pad(1, 2, 0, STR_PAD_LEFT); // 01　ゼロサブレス　2桁

	$ColsLoop = $wYoko;#１フロアの最大部屋数
	$RowsLoop = $wKaidaka;#階高
	$TemporaryRoom= $wKaidaka * $wYoko;
	$wRoomSuu = 0;

	for ($i = 0; $i < $RowsLoop; $i++){
		$Kai = $wKaidaka - $i;
		$KaiRoomLoop =  is_countable( $KaiRoom )?count($KaiRoom):0;
		if( $KaiRoomLoop > 0){#登録済み

			for ($j = 0; $j < $ColsLoop; $j++) {

				$Room = str_pad( $j + 1 ,2,0, STR_PAD_LEFT );

				if( in_array(  $Kai.$Room , $KaiRoom )){ #登録済みの　$KaiRoomに含まれていたら
					${$Kai.$Room."checked"} = "checked" ;
					$wRoomSuu++;
				}

				$Pic[$j] = "<input type=checkbox class='check-range' name=KaiRoom[] value=$Kai$Room ".${$Kai.$Room."checked"}." onchange='RoomCheck()'>".$Kai.$Room;
			}

		}else{#新規

			for ($j = 0; $j < $ColsLoop; $j++) {

				$Room = str_pad( $j + 1 ,2,0, STR_PAD_LEFT );

				if( ($Except4 == 1 AND $j == 3) OR ($Except9 == 1 AND $j == 8) ){

				}else{
					${$Kai.$Room."checked"} = "checked" ;
					$wRoomSuu++;
				}
				$Pic[$j] = "<input type=checkbox class='check-range' name=KaiRoom[] value=$Kai$Room ".${$Kai.$Room."checked"}." onchange='RoomCheck()'>".$Kai.$Room;
			}
		}

		$myTemplate->Msg = $LoopString;
		$myTemplate->convertTags();
		$ColsBlock[$i] = $myTemplate->Msg;

	}





	########################################################
	# コンテンツ表示
	########################################################

	$CNT_FILE = "s_make_matrix.tpl";

	$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier);
	$HiddenValues = $myTemplate->getValuesToPass();

	$myTemplate->convertTags();
	$myTemplate->outputTemplate();

	unset($myTemplate);
	unset($myLog);
?>
