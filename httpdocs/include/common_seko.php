<?php

/*
 スマート工事くん
 施工さん用　共通関数

*/

#	include_once "setting.properties";






	// pdoでデータベース接続
	function pdo_connect() {

		// データベース接続
//		$dbh = new PDO('mysql:host='._HOST_NAME.';dbname='._MAIN_DB.';charset=utf8',_USER_NAME,_PASSWD); // PHP5.3.6以降の場合
		$dbh = new PDO('mysql:host='._HOST_NAME.';dbname='._MAIN_DB.';',_USER_NAME,_PASSWD
						,array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8')); // PHP5.3.5以前の場合
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		return $dbh;
	}



	// tUserM から情報取得
	function getOneUser($val) {
		if ($val != "") {
			$dbh = pdo_connect();

			$sql = "select* from tUserM where MukouFlg = FALSE AND UserCD = '".$val."'";
			$stmt = $dbh->query($sql);

			if ( $UserData = $stmt->fetchAll(PDO::FETCH_ASSOC)){
				return $UserData;
			}
		} else {
			return false;
		}
	}


	// tGyosyaM から施工業者情報取得
	function getOneGyosya($val) {
		if ($val != "") {
			$dbh = pdo_connect();

			$sql = "select * from tGyosyaM where MukouFlg = FALSE AND GyosyaCD = '".$val."'";
			$stmt = $dbh->query($sql);

			if ( $GyosyaData = $stmt->fetchAll(PDO::FETCH_ASSOC)){
				return $GyosyaData;
			}
		} else {
			return false;
		}
	}




	// データベースが存在するか確認
	function checkDB($BukkenCD489) {

		include_once "setting.properties";
		include_once _CLS_DIR . "SPFWDatabase.cls";
		include_once _CLS_DIR . "SPFWListObject.cls";


		try {
			$pdb = new PDO('mysql:host='._HOST_NAME489.';dbname=kojiportal;charset=utf8',_USER_NAME,_PASSWD);
			$ret = true;

			// SQL文を作成
			// クエリ実行（データを取得）
			$stmt = $pdb->query("select S004,S001 FROM tSiteM WHERE S001 = '$BukkenCD489' ");

			$result = $stmt->fetch();
			$dbh = null;
			if( $result['S004'] != "1" ){#489.kojiportal.tSiteMのS004が１のものがskoji
				$dbname = "koji".$BukkenCD489;
			}else{
				$dbname = _MAIN_DB489 ;#skoji
			}

		} catch(PDOException $e){
			//echo $e->getMessage();
			$ret = false;
		}
		$pdo = null;

		try {
			$pdb = new PDO('mysql:host='._HOST_NAME489.';dbname='.$dbname.';charset=utf8',_USER_NAME,_PASSWD);
			$ret = true;
		} catch(PDOException $e){
			//echo $e->getMessage();
			$ret = false;
		}
		$pdo = null;

		return $ret;
	}




	// 各物件の残部屋数を返す
	// 完了部屋がなしの場合、専有部が開始していなければ住戸数を返す
	function getZanHeya($BukkenCD489) {

		include_once "setting.properties";
		include_once _CLS_DIR . "SPFWDatabase.cls";
		include_once _CLS_DIR . "SPFWListObject.cls";


		$MiKanID = array();

		########################################################
		# 各物件　データベースコネクト
		########################################################

		$pdb = new PDO('mysql:host='._HOST_NAME489.';dbname=kojiportal;charset=utf8',_USER_NAME,_PASSWD);

		// SQL文を作成
		// クエリ実行（データを取得）
		$stmt = $pdb->query("select S004,S001 FROM tSiteM WHERE S001 = '$BukkenCD489' ");

		$result = $stmt->fetch();
		$dbh = null;
		if( $result['S004'] != "1" ){#489.kojiportal.tSiteMのS004が１のものがskoji
			$dbname = "koji".$BukkenCD489;
		}else{
			$dbname = _MAIN_DB489 ;#skoji
		}




		// データベースコネクト
		$myDBeach = new SPFWDatabase( $dbname,_HOST_NAME489, _USER_NAME, _PASSWD , FALSE);
		if (!$myDBeach->Connection)
			trigger_error("SPFWDatabase Failed.", E_USER_ERROR);
			#echo "<br>▼".$BukkenCD489."　にコネクト";

			$myListObject2 = new SPFWListObject($myDBeach);

			$sql = "SELECT ";
			$sql .= "ClientCD ";
			$myListObject2->SelectSQL = $sql;
			$sql = " FROM tClientM ";
			$sql .= " WHERE ID =".$BukkenCD489;
			$sql .= " AND MukouFlg = false";
	
			$myListObject2->Condition = $sql;
			$myListObject2->Order = "1";
			$myListObject2->Limit = "allpage";
	
			if (!($myListObject2->GetList(1)))
				trigger_error("Getting Stylist List Failed.", E_USER_ERROR);
	
			$RecoLoop = $myListObject2->Rows;
			for ($j = 0; $j < $RecoLoop; $j++) {
				$ClientCD = $myListObject2->GetValue($j,0);
			}
			unset($myListObject2);


		########################################################
		# 各物件　残工事部屋情報取得
		########################################################
		##完了フラグを見る　Notesに日付がはいっているのが手動で完了とした部屋 Start
		##SELECT * FROM tReservationF WHERE Notes = NULL or Notes = '' 

		$myListObject2 = new SPFWListObject($myDBeach);

		$sql = "SELECT ";
		$sql .= "u.ID ";
		$myListObject2->SelectSQL = $sql;
		$sql = " FROM tReservationF r , tUserM u ";
		$sql .= " WHERE r.UserCD = u.UserCD and r.Notes !='' and r.Status = 1 and r.ClientCD =".$ClientCD." and u.ClientCD = ".$ClientCD;

		$myListObject2->Condition = $sql;
		$myListObject2->Order = "1";
		$myListObject2->Limit = "allpage";

		if (!($myListObject2->GetList(1)))
			trigger_error("Getting Stylist List Failed.", E_USER_ERROR);

		$RecoLoop = $myListObject2->Rows;
		for ($j = 0; $j < $RecoLoop; $j++) {
			$KanID[] = $myListObject2->GetValue($j,0);
		}
		unset($myListObject2);
		##完了フラグを見る　Notesに日付がはいっているのが手動で完了とした部屋 End

		#print_r($KanID[$i]);
	echo $BukkenCD489;

		##親機の施工後（Device=1かつSekoStatus=2）の写真があれば、完了と判断する Start
		#select ID from tUserM a where  exists (select ID from tPictureF b where a.ID = b.ID ) 
		$myListObject3 = new SPFWListObject($myDBeach);

		$sql = "SELECT ID "; 
		$myListObject3->SelectSQL = $sql;
		$sql = " From tUserM a ";#　施工後の親機を対象とする　施行前の玄関子機だけ写真とるときがある
		$sql .= " where exists (select ID from tPictureF b where a.ID = b.ID AND b.MukouFlg = 0 AND b.Device = 1 AND b.SekoStatus = 2 AND a.ClientCD = ".$ClientCD." AND b.ClientCD = ".$ClientCD.")";

		$myListObject3->Condition = $sql;
		$myListObject3->Order = "1";
		$myListObject3->Limit = "allpage";

		if (!($myListObject3->GetList(1)))
			trigger_error("Getting Stylist List Failed.", E_USER_ERROR);

		$RecoLoop = $myListObject3->Rows;
		for ($j = 0; $j < $RecoLoop; $j++) {
			$KanID[] = $myListObject3->GetValue($j,0);
		}
		unset($myListObject3);

		##親機の施工後（Device=1かつSekoStatus=2）の写真があれば、完了と判断する End
		#print_r($KanID[$i]);

		// 完了部屋があった場合
		if ($KanID) {
			$KanID = array_unique( $KanID ); //配列で重複している物を削除する
			$KanID = array_values( $KanID ); //キーが飛び飛びになっているので、キーを振り直す

			##不要な部屋（dummy等）を除外 Start
			$myListObject4 = new SPFWListObject($myDBeach);
			$sql = "SELECT ";
			$sql .= "ID ";
			$myListObject4->SelectSQL = $sql;
			$sql = " FROM tUserM ";
			$sql .= " WHERE ID not like 'dummy%' and ID != 1234 and ID != 5678 and ID != 'kanri'  AND MukouFlg = 0 and ID != 'aiphone' AND ClientCD = ".$ClientCD;

			$myListObject4->Condition = $sql;
			$myListObject4->Order = "1";
			$myListObject4->Limit = "allpage";

			if (!($myListObject4->GetList(1)))
				trigger_error("Getting Stylist List Failed.", E_USER_ERROR);

			$RecoLoop = $myListObject4->Rows;
			for ($j = 0; $j < $RecoLoop; $j++) {
				$ID[$j] = $myListObject4->GetValue($j,0);

				if(!in_array( $ID[$j] , $KanID )){
					$MiKanID[] = $ID[$j];
				}
			}
			unset($myListObject4);
			##不要な部屋（dummy等）を除外 End


#			if( count($MiKanID) > 0 ){
#				#echo "<br>完了している部屋あり、残工事部屋あり：<br>";
#				#print_r($MiKanID);
#				for($k = 0 ; $k < count($MiKanID); $k++){
#		   			$ZanHeyaDisp = $ZanHeyaDisp." ".$MiKanID[$k] ;
#				}
#			} else {#tBukkenM.ZanHeyaフラグがあるにもかかわらず、残部屋がなくなった
#				#echo "<br>全部屋完了、残工事部屋なし";
#				$ClearBukkenCD = $BukkenCD;
#			}
var_dump($MiKanID);
			$ret = count($MiKanID); // 残工事部屋数

		} else {

			#$IfNoZan = TRUE;
			#echo "<br>完了部屋はなし
			// 完了部屋はなし 工事開始前 もしくは、共用部開始後専有部開始前


			// データベースコネクト
			$myDBportal = new SPFWDatabase( "kojiportal" ,_HOST_NAME489, _USER_NAME, _PASSWD , FALSE);
			if (!$myDBeach->Connection)
				trigger_error("SPFWDatabase Failed.", E_USER_ERROR);

			#20190508 工事統一化物件はSettingMのReserveTo2がSenyuStartDateとなっており、カラムがないため、portalを見るよう変更
			// 専有部が開始しているかどうか
			#$myListObject = new SPFWListObject($myDBeach);
			#$sql = "SELECT ";
			#$sql .= "ReserveTo2, ";
			#$sql .= "Juuko ";
			#$myListObject->SelectSQL = $sql;
			#$sql = " FROM tSettingM ";
			#$sql .= " WHERE SettingCD = 1";
			#$myListObject->Condition = $sql;
			#$myListObject->Order = "1";
			#$myListObject->Limit = "allpage";

			$myListObject = new SPFWListObject($myDBportal);
			$sql = "SELECT ";
			$sql .= "ReserveTo2, ";
			$sql .= "Juuko ";
			$myListObject->SelectSQL = $sql;
			$sql = " FROM tSiteM ";
			$sql .= " WHERE S001 = '$BukkenCD489'";
			$myListObject->Condition = $sql;
			$myListObject->Order = "1";
			$myListObject->Limit = "1";

			if (!($myListObject->GetList(1)))
				trigger_error("Getting Setting List Failed.", E_USER_ERROR);

			$SenyuStartDay = $myListObject->GetValue(0,0);
			$Juuko = $myListObject->GetValue(0,1);
			unset($myListObject);

			$Today = date('Y-m-d');
			if ($Today < $SenyuStartDay)
				$ret = $Juuko;	// 専有部が開始していない場合は住戸数を返す
			else 
				$ret = "-";		// 専有部が開始している場合は、何も返さない これで正しいのか？

		}

		return $ret;
	}

















//西暦和暦変換
//function toJpDate ($year, $month, $day) {
function toJpDate ($ymd) {

	// 年月日に分ける
	$year = substr($ymd,0,4);
	$month = substr($ymd,4,2);
	$day = substr($ymd,6,2);


	if (!checkdate($month, $day, $year) || $year < 1800) {
    	return false;
  	}
 
  	$date = (int) sprintf('%04d%02d%02d', $year, $month, $day);
 
	if ($date >= 19890108) {  // 1989年1月8日から平成
    $era = 'H';//'平成';
	$jpYear = $year - 1988;
  } elseif ($date >= 19261225) {  // 1926年12月25日から昭和
    $era = 'S';//'昭和';
    $jpYear = $year - 1925;
  } elseif ($date >= 19120730) {  // 1912年7月30日から大正
    $era = 'T';//'大正';
    $jpYear = $year - 1911;
  } elseif ($date >= 18680125){  // 1868年1月25日から明治
    $era = 'M';//'明治';
    $jpYear = $year - 1867;
  }


	$wareki = $era . $jpYear;
	$ret = $wareki .",". $month .','. $day;
	return explode(',',$ret);
}







function getUserAuth($myDB, $usercd) {

	$ret = array();
	$ret["type"] = "";
	$ret["typename"] = "";

	include_once _CLS_DIR . "SPUSUser.cls";

	$myUser = new User($myDB);

	if (!$myUser->executeSelect("UserCD = " . $usercd, "") || $myUser->RecCnt == 0)
		trigger_error("Getting myUser Failed.", E_USER_ERROR);

	if ($myUser->RecCnt == 1) {
		$ID = $myUser->ID;
		$Extra3 = $myUser->Extra3; #1アイホンさん一般 2:アイホンさん・ネスペ管理者 3:業者 4:業者(管理者) 5:協力会社
		$Extra4 = $myUser->Extra4; #5:アイホン施工管理課
		#$Extra5 = $myUser->Extra5; #業者CD

		if ($Extra4 == 5 or $Extra3 == 2 AND strpos($ID,"nespe") !== false) { // 施工管理課・ネスペ
			$ret["type"] = "aipadmin";
			$ret["typename"] = "管理者";
		} else if ($Extra3 == 1 or $Extra3 == 2) { // アイホンさん一般ユーザ
			$ret["type"] = "aipuser";
			$ret["typename"] = "アイホン";
		} else if ($Extra3 == 3) { // ATE一般
			$ret["type"] = "ateuser";
			$ret["typename"] = "ATE業者";
		} else if ($Extra3 == 4) { // ATE管理者
			$ret["type"] = "ateadmin";
			$ret["typename"] = "ATE業者　管理者";
		}

	}
	unset($myUser);

	return $ret;
}





?>
