<?php
	include_once "setting.properties";
	include_once _INC_DIR . "carrier.inc";
	include_once _INC_DIR . "global.inc";

	include_once _CLS_DIR . "SPFWDatabase.cls";
	include_once _CLS_DIR . "SPFWLog.cls";
	include_once _CLS_DIR . "SPFWTemplate.cls";
	include_once _CLS_DIR . "SPFWParameter.cls";
	include_once _CLS_DIR . "SPFWListObject.cls";
	include_once _CLS_DIR . "SPFWDate.cls";
	include_once _CLS_DIR . "SPFWInputCheck.cls";
	include_once _CLS_DIR . "SPUSUser.cls";

	include_once _CLS_DIR . "SPUSBukken.cls";

	// データベースコネクト
	$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
	if (!$myDB->Connection)
		trigger_error("SPFWDatabase Failed.", E_USER_ERROR);

	########################################################
	# 入力チェック
	########################################################


	$editBukkenCD = SPFWParameter::getValues("editBukkenCD");
	$rKey = SPFWParameter::getValues("rKey");



	if ($rKey == NULL) {
		$URL = _MAIN_URL . 'login_form.php';
		header('Location: ' . $URL);
		exit;
	}


	########################################################
	# 物件情報抽出
	########################################################


#物件情報の修正の場合
if ( $editBukkenCD > 0 ){ 


	########################################################
	# 写真・画像表示
	########################################################

#SekoStatus＝1が手書き
#SekoStatus＝2が撮影写真

	$ZumenNo = 0 ; #図面
	$KyoyoNo = 0 ; #共有部
	$SenyuNo = 0 ; #専有部
	$GenchoNo = 0; #現調
	$HeyaNo = 0; #部屋番号

	$myListObject = new SPFWListObject($myDB);

	$sql = "SELECT ";
	$sql .= "PictureCD, ";
	$sql .= "Device, ";
	$sql .= "Pic, ";
	$sql .= "Memo ";

	$myListObject->SelectSQL = $sql;
	$sql = " FROM tPictureF";
	$sql .= " WHERE BukkenCD = '{$editBukkenCD}' AND SekoStatus = 2 AND MukouFlg = FALSE";

	$myListObject->Condition = $sql;
	$myListObject->Order = "PictureCD";
	$myListObject->Limit = "allpage";

	if (!($myListObject->GetList(1)))
		trigger_error("Getting Menu List Failed.", E_USER_ERROR);

	$PicLoop = $myListObject->Rows;
	for ($i = 0; $i < $PicLoop; $i++) {
		$PictureCD[$i] = $myListObject->GetValue($i, 0);
		$Device[$i] = $myListObject->GetValue($i, 1);
		$Pic[$i] = $myListObject->GetValue($i, 2);

		if ( $Device[$i] == 1 ) {#制御装置　写真表示部分

		}else if( $Device[$i] == 2 ) {#集合玄関機　写真表示部分

		}else if( $Device[$i] == 3 ) {#管理室親機　写真表示部分

		}else if( $Device[$i] == 4 ) {#室内親機　写真表示部分

		}else if( $Device[$i] == 5 ) {#玄関子機　写真表示部分

		}else if( $Device[$i] == 6 ) {#系統図　写真表示部分

		}else if( $Device[$i] == 8 ) {#図面　写真表示部分
			$Memo[$i] = $myListObject->GetValue($i, 3);

			$PicZumen[$ZumenNo] = "";
			if ($ZumenNo % 10 == 0) $PicZumen[$ZumenNo] .= "</tr><tr>";
			$PicZumen[$ZumenNo] .= "<td  align=center ><a href=./viewpic.php?BukkenCD=".$editBukkenCD."&Device=".$Device[$i]."&PictureCD=".$PictureCD[$i]." TARGET=_blank ><img src=./viewpic.php?BukkenCD=".$editBukkenCD."&Device=".$Device[$i]."&PictureCD=".$PictureCD[$i]." width=100></a>";
			$PicZumen[$ZumenNo] .="<br><input type=checkbox  name=chk[] value=$PictureCD[$i] checked >";
			$PicZumen[$ZumenNo] .="$Memo[$i]</td>";
			$ZumenNo = $ZumenNo + 1;
			$PicZumenLoop = $ZumenNo;

		}else if( $Device[$i] == 9 ) {#共有部　写真表示部分
			$Memo[$i] = $myListObject->GetValue($i, 3);

			$PicKyoyo[$KyoyoNo] = "";
			if ($KyoyoNo % 10 == 0) $PicKyoyo[$KyoyoNo] .= "</tr><tr>";
			$PicKyoyo[$KyoyoNo] .= "<td  align=center ><a href=./viewpic.php?BukkenCD=".$editBukkenCD."&Device=".$Device[$i]."&PictureCD=".$PictureCD[$i]." TARGET=_blank ><img src=./viewpic2.php?BukkenCD=".$editBukkenCD."&Device=".$Device[$i]."&PictureCD=".$PictureCD[$i]." width=100></a>";
			$PicKyoyo[$KyoyoNo] .="<br><input type=checkbox  name=chk[] value=$PictureCD[$i] checked >";
			$PicKyoyo[$KyoyoNo] .="$Memo[$i]</td>";
			$KyoyoNo = $KyoyoNo + 1;
			$PicKyoyoLoop = $KyoyoNo;

		}else if( $Device[$i] == 10 ) {#専有部　写真表示部分
			$Memo[$i] = $myListObject->GetValue($i, 3);

			$PicSenyu[$SenyuNo] = "";
			if ($SenyuNo % 10 == 0) $PicSenyu[$SenyuNo] .= "</tr><tr>";
			$PicSenyu[$SenyuNo] .= "<td  align=center ><a href=./viewpic.php?BukkenCD=".$editBukkenCD."&Device=".$Device[$i]."&PictureCD=".$PictureCD[$i]." TARGET=_blank ><img src=./viewpic2.php?BukkenCD=".$editBukkenCD."&Device=".$Device[$i]."&PictureCD=".$PictureCD[$i]." width=100></a>";
			$PicSenyu[$SenyuNo] .="<br><input type=checkbox  name=chk[] value=$PictureCD[$i] checked >";
			$PicSenyu[$SenyuNo] .="$Memo[$i]</td>";
			$SenyuNo = $SenyuNo + 1;
			$PicSenyuLoop = $SenyuNo;

		}else if( $Device[$i] == 11 ) {#現調シート　写真表示部分
			$Memo[$i] = $myListObject->GetValue($i, 3);

			$PicGencho[$GenchoNo] = "";
			if ($GenchoNo % 10 == 0) $PicGencho[$GenchoNo] .= "</tr><tr>";
			$PicGencho[$GenchoNo] .= "<td  align=center ><a href=./viewpic.php?BukkenCD=".$editBukkenCD."&Device=".$Device[$i]."&PictureCD=".$PictureCD[$i]." TARGET=_blank ><img src=./viewpic2.php?BukkenCD=".$editBukkenCD."&Device=".$Device[$i]."&PictureCD=".$PictureCD[$i]." width=100></a>";
			$PicGencho[$GenchoNo] .="<br><input type=checkbox name=chk[] value=$PictureCD[$i] checked >";
			$PicGencho[$GenchoNo] .="$Memo[$i]</td>";
			$GenchoNo = $GenchoNo + 1;
			$PicGenchoLoop = $GenchoNo;

		}else if( $Device[$i] == 13 ) {#部屋番号　写真表示部分
			$Memo[$i] = $myListObject->GetValue($i, 3);

			$PicHeya[$HeyaNo] = "";
			if ($HeyaNo % 10 == 0) $PicHeya[$HeyaNo] .= "</tr><tr>";
			$PicHeya[$HeyaNo] .= "<td  align=center ><a href=./viewpic.php?BukkenCD=".$editBukkenCD."&Device=".$Device[$i]."&PictureCD=".$PictureCD[$i]." TARGET=_blank ><img src=./viewpic2.php?BukkenCD=".$editBukkenCD."&Device=".$Device[$i]."&PictureCD=".$PictureCD[$i]." width=100></a>";
			$PicHeya[$HeyaNo] .="<br><input type=checkbox name=chk[] value=$PictureCD[$i] checked >";
			$PicHeya[$HeyaNo] .="$Memo[$i]</td>";
			$HeyaNo = $HeyaNo + 1;
			$PicHeyaLoop = $HeyaNo;
		}

	}

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


	########################################################
	# コンテンツ表示
	########################################################

	$CNT_FILE = basename($_SERVER["SCRIPT_NAME"], ".php") .".tpl";

	$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier);
	$HiddenValues = $myTemplate->getValuesToPass();

	$myTemplate->convertTags();
	$myTemplate->outputTemplate();

	unset($myTemplate);
	unset($myLog);
?>
