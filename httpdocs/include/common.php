<?php

#	include_once "E:/xampp8.2.4/kotei-k/SPFW/inc/setting.properties";



	########################################################
	# 共通関数
	########################################################

	// POSTからセッション変数にセットする
	function setSessionFromPost($keys) {
		foreach ($keys as $key) {
			if (isset($_POST[$key])) $_SESSION[$key] = h($_POST[$key]);
		}
	}

	// POSTからセッション変数(配列)にセットする
	function setSessionArrFromPost($arr,$keys) {
		foreach ($keys as $key) {

			if (is_array($key)){

				foreach ($key as $akey) {
					if (isset($_POST[$akey])) $_SESSION[$arr][$akey][] = h($_POST[$akey]);
				}

			} else {
				if (isset($_POST[$key])) $_SESSION[$arr][$key] = h($_POST[$key]);
			}
		}
	}


	// 特殊文字を HTML エンティティに変換する
	function h($a) {
		return htmlspecialchars($a);
	}

	// リダイレクトさせる
	function redirect($URL) {
		header('Location: ' . $URL);
		exit;
	}

	// カンマ除去関数
	function delFigure($arg) {
		$result = str_replace(',','',$arg);
		return $result;
	}

	// 日付フォーマットチェック関数
	// 参考 http://takafumi-s.hatenablog.com/entry/2015/06/06/192443
	function checkDatetimeFormat($datetime){
		//return $datetime === date("Y-m-d H:i:s", strtotime($datetime));
		return $datetime === date("Y-m-d", strtotime($datetime));
	}



	// floorをすると、1円の誤差が出る可能性があるので、先に文字列にする
	// http://blog.livedoor.jp/grtsg/archives/4205572.html
	// http://rice1031.blog106.fc2.com/blog-entry-780.html
	function myFloor($num) {
		$num = (string)$num;
		$num = (double)$num;

		// マイナスの値の場合は切り上げないとおかしくなる
		if ($num >= 0) {
			$num = floor($num);
		} else {
			ceil($num);
		}

	    return $num;
	}





// セッションをすべて削除する
function clearAllSession() {

	$_SESSION = array();

	if (isset($_COOKIE[session_name()])) {
		setcookie(session_name(), '', time() - 1440, '/') ;
	}

    session_destroy();

}


function getTantoData($myDB, $wTantoCD){

	$TantoData = array();

	$myListObject = new SPFWListObject($myDB);

	$sql = "SELECT ";
	$sql .= "UserCD, ";		#担当CD
	$sql .= "LastName, ";	#名前
	$sql .= "EMail, ";		#メールアドレス
	$sql .= "Address2, ";	#メールアドレス２
	$sql .= "TEL, ";		#電話番号
	$sql .= "Address3, ";	#携帯電話
	$sql .= "Extra4 ";		#営業所
	$myListObject->SelectSQL = $sql;
	$sql = " FROM tUserM";
	$sql .= " WHERE MukouFlg = FALSE AND UserCD = $wTantoCD ";

	$myListObject->Condition = $sql;
	$myListObject->Order = "";
	$myListObject->Limit = "allpage";

	if (!($myListObject->GetList(1)))
		trigger_error("Getting User Failed.", E_USER_ERROR);

	if ($myListObject->Rows == 1) {
		$TantoData['TantoCD'] = $myListObject->GetValue(0, 0);
		$TantoData['TantoName'] = $myListObject->GetValue(0, 1);
		$TantoData['EMail'] = $myListObject->GetValue(0, 2);
		$TantoData['Address2'] = $myListObject->GetValue(0, 3);
		$TantoData['TantoTEL'] = $myListObject->GetValue(0, 4);
		$TantoData['Address3'] = $myListObject->GetValue(0, 5);
		$Extra4 = $myListObject->GetValue(0, 6);
		$TantoData['EigyoshoCD'] = $Extra4;


		// 営業所名取得
		$myListObject2 = new SPFWListObject($myDB);
		$sql2 = "SELECT ";
		$sql2 .= "EigyoshoName, ";
		$sql2 .= "EigyoshoAddress, ";
		$sql2 .= "TEL, ";
		$sql2 .= "FAX ";
		$myListObject2->SelectSQL = $sql2;
		$sql2 = " FROM tEigyoshoM";
		$sql2 .= " WHERE EigyoshoCD = '{$Extra4}' AND MukouFlg = FALSE ";
		$myListObject2->Condition = $sql2;
		$myListObject2->Order = "";
		$myListObject2->Limit = "allpage";

		if (!($myListObject2->GetList(1)))
			trigger_error("Getting Eigyosho Failed.", E_USER_ERROR);

		if ($myListObject2->Rows == 1) {
			$TantoData['EigyoshoName'] = $myListObject2->GetValue(0, 0);
			$TantoData['EigyoshoAddress'] = $myListObject2->GetValue(0, 1);
			$TantoData['TEL'] = $myListObject2->GetValue(0, 2);
			$TantoData['FAX'] = $myListObject2->GetValue(0, 3);
		}
		unset($myListObject2);
	}
	unset($myListObject);

	return $TantoData;
}


// 支店名取得
function getSitenData($myDB, $SitenCD) {

	$SitenName = "";

	$myListObject = new SPFWListObject($myDB);

	$sql = "SELECT ";
	$sql .= "SitenName ";
	$myListObject->SelectSQL = $sql;

	$sql = " FROM tSitenM";
	$sql .= " WHERE MukouFlg = FALSE AND SitenCD = ".$SitenCD;

	$myListObject->Condition = $sql;
	$myListObject->Order = "";
	$myListObject->Limit = "allpage";

	if (!($myListObject->GetList(1)))
		trigger_error("Getting Siten Failed.", E_USER_ERROR);

	if ($myListObject->Rows == 1) {
		$SitenName = $myListObject->GetValue(0, 0);
	}
	unset($myListObject);

	return $SitenName;
}


#業者担当CDで業者担当名や業者名を取得
function getGyosyaData($myDB, $wGyosyaTantoCD){

	$GyosyaData = array();

	$myListObject = new SPFWListObject($myDB);

	$sql = "SELECT ";
	$sql .= "gt.UserCD, ";		#0業者担当CD
	$sql .= "gt.LastName, ";	#1業者担当名
	$sql .= "gt.Extra5, ";		#2業者CD
	$sql .= "gt.TEL, ";			#3業者担当TEL
	$sql .= "gt.Address3, ";	#4業者担当携帯
	$sql .= "gt.EMail, ";		#5業者担当メールアドレス
	$sql .= "gt.Address3, ";	#6  2こめのメールアドレス
	$sql .= "gt.Notes, ";		#7備考
	$sql .= "g.GyosyaName, ";	#8業者名
	$sql .= "g.ShozokuCD, ";	#9管轄支店　|3|4|5|となっている。
	$sql .= "g.Address ";		#10住所
	$myListObject->SelectSQL = $sql;
	$sql = " FROM tUserM gt , tGyosyaM g ";
	$sql .= " WHERE gt.Extra5 = g.GyosyaCD AND gt.MukouFlg = FALSE AND gt.UserCD = $wGyosyaTantoCD";

	$myListObject->Condition = $sql;
	$myListObject->Order = "";
	$myListObject->Limit = "allpage";

	if (!($myListObject->GetList(1)))
		trigger_error("Getting User Failed.", E_USER_ERROR);

	if ($myListObject->Rows == 1) {
		$GyosyaData['GyosyaTantoCD'] = $myListObject->GetValue(0, 0);
		$GyosyaData['GyosyaTantoName'] = $myListObject->GetValue(0, 1);
		$GyosyaData['GyosyaTantoTEL'] = $myListObject->GetValue(0, 3);
		$GyosyaData['GyosyaTantoKeitai'] = $myListObject->GetValue(0, 4);
		$GyosyaData['GyosyaTantoMail'] = $myListObject->GetValue(0, 5);
		$GyosyaData['GyosyaTantoMail2'] = $myListObject->GetValue(0, 6);
		$GyosyaData['GyosyaTantoNotes'] = $myListObject->GetValue(0, 7);
		$GyosyaData['GyosyaName'] = $myListObject->GetValue(0, 8);
		$GyosyaData['ShozokuCD'] = $myListObject->GetValue(0, 9);
		$GyosyaData['Address'] = $myListObject->GetValue(0,10);
	}
	unset($myListObject);

	return $GyosyaData;
}




#デバイスCDでデバイス情報を取得
function getDeviceData($myDB, $DeviceCD){

	$DeviceData = array();

	if ($DeviceCD) {
		$myDevice = new Device($myDB);

		if (!$myDevice->executeSelect("DeviceCD > 0 AND MukouFlg = FALSE AND DeviceCD = ".$DeviceCD , ""))
			trigger_error("Getting tFileF Failed.", E_USER_ERROR);

		if ($myDevice->RecCnt == 1) {
			$DeviceData['DeviceName'] = $myDevice->DeviceName;
			$DeviceData['Kataban'] = $myDevice->Kataban;
			$DeviceData['Category'] = $myDevice->Category;
			$DeviceData['ShortName'] = $myDevice->ShortName;
		}
		unset($myDevice);
	}
	return $DeviceData;
}


#型番からDeviceCDでを取得
function getDeviceCDfromKataban($myDB, $Kataban){

	$DeviceData = array();

	if ($Kataban) {
		$myDevice = new Device($myDB);

		if (!$myDevice->executeSelect("DeviceCD > 0 AND MukouFlg = FALSE AND Kataban = '".$Kataban."'" , ""))
			trigger_error("Getting tFileF Failed.", E_USER_ERROR);

		if ($myDevice->RecCnt == 1) {
			$DeviceCD = $myDevice->DeviceCD;
		}
		unset($myDevice);
	}
	return $DeviceCD;
}
#型番からDeviceCDでを取得
function getDeviceDatafromKataban($myDB, $Kataban){

	$DeviceData = array();

	if ($Kataban) {
		$myDevice = new Device($myDB);

		if (!$myDevice->executeSelect("DeviceCD > 0 AND MukouFlg = FALSE AND Kataban = '".$Kataban."'" , ""))
			trigger_error("Getting tFileF Failed.", E_USER_ERROR);

		if ($myDevice->RecCnt == 1) {
			$DeviceData['DeviceCD'] = $myDevice->DeviceCD;
			$DeviceData['DeviceName'] = $myDevice->DeviceName;
			$DeviceData['Kataban'] = $myDevice->Kataban;
			$DeviceData['Category'] = $myDevice->Category;
		}
		unset($myDevice);
	}
	return $DeviceData;
}


?>
