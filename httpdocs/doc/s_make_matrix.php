<?php
$isAdminMode = TRUE;
	include_once "D:/xampp/htdocs/hochiki/SPFW/inc/setting.properties";
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
	include_once _CLS_DIR . "SPUSBuilding.cls";

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
	$editBuildingCD = SPFWParameter::getValues('editBuildingCD');
	$KaiRoom = SPFWParameter::getValues('KaiRoom');
	$wYoko = SPFWParameter::getValues('wYoko');

	if (!$editBukkenCD or !$wYoko) {
		echo "値が入力されていません。<a href='#' onclick='javascript:window.history.back(-1);return false;'>back</a>";
		exit();
	}



if ( $editBukkenCD > 0 ){ #物件情報の修正の場合

	$myBukken = new Bukken($myDB);

	if (!$myBukken->executeSelect("BukkenCD = " . $editBukkenCD . " AND MukouFlg = FALSE", "")){
		$ErrorString = array();
		$ErrorString[] = "tBukkenM情報の抽出に失敗しました。";
		showSorryPage(_ILLEGAL_ACCESS2);
	}

	if ($myBukken->RecCnt == 0) {
		showSorryPage(_ILLEGAL_ACCESS2);
	}

	$editBukkenCD = $myBukken->BukkenCD;
	$wBukkenName = $myBukken->BukkenName;
	$wBuildingName = $myBukken->BuildingName;
	$wTantoCD = $myBukken->TantoCD;
#	$wShozokuCD = $myBukken->ShozokuCD;
#	$wTosu = $myBukken->Tosu;
	$wKosu = $myBukken->Kosu;
	$wKaidaka = $myBukken->Kaidaka;

	$myBuilding = new Building($myDB);
	if($editBuildingCD){
		if (!$myBuilding->executeSelect("BukkenCD = " . $editBukkenCD . " AND BuildingCD = ".$editBuildingCD." AND MukouFlg = FALSE", "")) {
			trigger_error("Getting myBuilding Failed.", E_USER_ERROR);
		}
		$wKosu = $myBuilding->Kosu;
		$wKaidaka = $myBuilding->Kaidaka;
		$wBuildingName = $myBuilding->BuildingName;
	}

	// 棟名称が空の場合、例外処理
	$myListObject = new SPFWListObject($myDB);

	$sql = "SELECT ";
	$sql .= "u.BuildingCD, ";
	$sql .= "u.BuildingName ";

	$myListObject->SelectSQL = $sql;

	$sql = " FROM tBuildingM u ";
	$sql .= " WHERE u.MukouFlg = FALSE AND BukkenCD='".$editBukkenCD."'";

	$myListObject->Condition = $sql;
	$myListObject->Order = "u.BuildingCD ASC";
	$myListObject->Limit = "allpage";

	if (!($myListObject->GetList(1)))
		trigger_error("Getting User List Failed.", E_USER_ERROR);

	$BuildingCD = [];
	$BuildingName = [];
	$BuildingLoop = $myListObject->Rows;

	for ($i = 0; $i < $BuildingLoop; $i++) {
		$BuildingCD[$i] = $myListObject->GetValue($i, 0);
		$BuildingName[$i] = $myListObject->GetValue($i, 1);
	}
	unset($myListObject);


	// 棟名称が空の場合、例外処理
function numberToCircled($number) {
    $map = [
        1 => '①', 2 => '②', 3 => '③', 4 => '④', 5 => '⑤',
        6 => '⑥', 7 => '⑦', 8 => '⑧', 9 => '⑨', 10 => '⑩',
        11 => '⑪', 12 => '⑫', 13 => '⑬', 14 => '⑭', 15 => '⑮',
        16 => '⑯', 17 => '⑰', 18 => '⑱', 19 => '⑲', 20 => '⑳'
    ];

    return $map[$number] ?? $number;
}	
	if(!$wBuildingName){
		if($editBuildingCD){
			$wBuildingName = '棟'.numberToCircled(2);
			for ($i = 0; $i < $BuildingLoop; $i++) {
				if($BuildingCD[$i] == $editBuildingCD){
					$wBuildingName = '棟'.numberToCircled($i+2);
				}
			}

		}else{
			if($BuildingLoop > 0){
				$wBuildingName = '棟'.numberToCircled(1);
			}
		}
	}

	if($BuildingLoop > 0)
		$IfBuildingExist = true;
	else
		$IfBuildingExist = false;

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
// echo "<br> ".__LINE__." wKaidaka :".$wKaidaka;
// echo "<br> ".__LINE__." wYoko :".$wYoko;
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
