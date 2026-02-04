<?php
/* 
 * 残工事情報表示
 * s_zan.php
*/
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
	include_once _CLS_DIR . "SPUSIraiRenkei.cls";
	include_once _CLS_DIR . "./center489/SPUSSite.cls";
	include_once _CLS_DIR . "./center489/SPUSClient.cls";

	#include_once _CLS_DIR . "center489/SPUSSetting489.cls";

	include_once "./include/common_seko.php";


	// データベースコネクト
	$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
	if (!$myDB->Connection)
		trigger_error("SPFWDatabase Failed.", E_USER_ERROR);


	########################################################
	# 認証動作
	########################################################
	$rKey = SPFWParameter::getValues('rKey');

	$myUser = new User($myDB);

	if ($rKey == NULL)
		showSorryPage(_ILLEGAL_ACCESS);

	if (!$myUser->doAuthenticationByRegistKey($rKey))
		trigger_error("doAuthentication Failed.", E_USER_ERROR);

	if ($myUser->UserCD == -1)
		showSorryPage(_ILLEGAL_ACCESS);

	$UserCD = $myUser->UserCD;
	$MyShozokuCD = $myUser->Extra1 ;	#所属支店CD
	$MyZokusei = $myUser->Extra3 ;		#管理ユーザ２一般ユーザ１
	$MyEigyoshoCD = $myUser->Extra4 ;	#営業所CD nespeユーザはNULLになってる
	unset($myUser);


	########################################################
	# 値取得
	########################################################

	$editBukkenCD = SPFWParameter::getValues('editBukkenCD');


	########################################################
	# 残工事なしに修正
	########################################################

	$work = SPFWParameter::getValues('work');

	// 「残工事なしに修正」ボタンを押した場合(work=2)
	if ( $work == 2 ){

		#tBukkenMのZanHeyaを””にする
		$myBukken = new Bukken($myDB);
		if (!$myBukken->executeSelect("BukkenCD = '" .$editBukkenCD."'", "")){
			trigger_error("Getting myBukken Failed.", E_USER_ERROR);
		}

		if ($myBukken->ZanHeya != "") {
			$myBukken->ZanHeya = "";
			$myBukken->Updater = $UserCD;

			if (!$myBukken->executeUpdate()){
				trigger_error("Updating myBukken Failed.", E_USER_ERROR);
			}
		}
		unset($myBukken);

		SPFWTemplate::dropValue(work);
		$work = "";
	}


	########################################################
	# 物件情報取得
	########################################################

	$myBukken = new Bukken($myDB);

	if (!$myBukken->executeSelect("MukouFlg = FALSE AND BukkenCD = '".$editBukkenCD."'", ""))
		trigger_error("Getting myBukken Failed.", E_USER_ERROR);

	$BukkenName = $myBukken->BukkenName;
	$BukkenZanDate = $myBukken->ZanDate;
	$BukkenZanHeya = $myBukken->ZanHeya; //残連携済のものは1が入っている
	unset($myBukken);


	$myIraiRenkei = new IraiRenkei($myDB);
	if (!$myIraiRenkei->executeSelect("BukkenCD = '" .$editBukkenCD."'", "")){
		trigger_error("Getting myIraiRenkei Failed.", E_USER_ERROR);
	}
	if ($myIraiRenkei->RecCnt != 1) {
		#依頼がまだ
		$ErrorString = array();
		$ErrorString[] = "依頼情報がありません。";
		$ErrorLoop = count($ErrorString);
		$myTemplate = new SPFWTemplate(_ERROR_TPL, $MyCarrier, TRUE);
		exit;
	}
	$BukkenCD489 = $myIraiRenkei->BukkenCD489;
	unset($myIraiRenkei);


	if ($BukkenZanHeya == "") {
		// 残連携まだか、残工事なしに修正済

		if ($BukkenZanDate == "0000-00-00") {
			// 残連携未
			$IfZanRenkeiMi = true;

			if ($BukkenCD489 != "") {
				if (checkDB($BukkenCD489)) { // common_seko.php
					$ZanHeyaSuu = getZanHeya($BukkenCD489);
					// 残部屋数が0の場合は工事完了とみなす
					if ($ZanHeyaSuu > 0) {
						$IfZanRenkeiMi_Message = true;
					}
				}
			}
		} else {
			// 残連携済、残部屋なし
			$IfZanHeyaNasi = true;
		}
//echo __LINE__;
	} else if ($BukkenZanHeya != "") { // 残部屋ありの場合

		$IfZanHeya = true;

		########################################################
		# 物件情報取得
		########################################################
		$myListObject = new SPFWListObject($myDB);

		$sql = "SELECT ";
		$sql .= "b.BukkenCD,";
		$sql .= "i.BukkenCD489,";
		$sql .= "b.ZanHeya,";
		$sql .= "b.ZanDate,";
		$sql .= "b.BukkenName,";
		$sql .= "b.RNstate";

		$myListObject->SelectSQL = $sql;
		$sql = " FROM tBukkenM b, tIraiRenkeiF i ";
		$sql .= " WHERE b.BukkenCD = i.BukkenCD AND b.BukkenCD > 0 ";
		$sql .= " AND b.MukouFlg = FALSE AND i.MukouFlg = FALSE "; 
		#$sql .= " AND length(b.ZanHeya) >= 1 and b.BukkenCD = ".$editBukkenCD;
		$sql .= " AND b.BukkenCD = ".$editBukkenCD;

		$myListObject->Condition = $sql;
		$myListObject->Order = "";
		$myListObject->Limit = "allpage";

		if (!($myListObject->GetList(1))){
			trigger_error("Getting Bukken List Failed.", E_USER_ERROR);
		}

		if ($myListObject->Rows == 1) {

			$BukkenCD = $myListObject->GetValue(0, 0);
			$BukkenCD489 = $myListObject->GetValue(0, 1);
			$ZanHeya = $myListObject->GetValue(0, 2);
			$ZanDate = $myListObject->GetValue(0, 3);
			$BukkenName = $myListObject->GetValue(0, 4);

			$RNstate = $myListObject->GetValue(0, 5);
			$IfRenewal = ($RNstate == 1 )? "TRUE" : ""; #リニューアルが済みかどうか

			$IfZan = TRUE;


			########################################################
			# 489データベースコネクト
			########################################################

			$pdb = new PDO('mysql:host='._HOST_NAME489.';dbname=kojiportal;charset=utf8',_USER_NAME,_PASSWD);

			// SQL文を作成
			// クエリ実行（データを取得）
			$stmt = $pdb->query("select S004,S001 FROM tSiteM WHERE S001 = '$BukkenCD489' ");

			$result = $stmt->fetch();
			$dbh = null;


			if( $result['S004'] != "1" ){#489.kojiportal.tSiteMのS004が１のものがskoji
				$dbname = "koji".$BukkenCD489;
				$myDBeach = new SPFWDatabase( $dbname, 'localhost', _USER_NAME, _PASSWD , FALSE);
				if (!$myDBeach->Connection)
					trigger_error("SPFWDatabase Failed.", E_USER_ERROR);

			}else{
				$dbname = _MAIN_DB489 ;#skoji
				echo "$dbname, 'localhost', _USER_NAME, _PASSWD , FALSE";
				$myDBeach = new SPFWDatabase( $dbname, 'localhost', _USER_NAME, _PASSWD , FALSE);
				if (!$myDBeach->Connection)
					trigger_error("SPFWDatabase Failed.", E_USER_ERROR);

				$Skoji = 1;
				$myClient = new Client($myDBeach);
				if (!$myClient->executeSelect( "MukouFlg = FALSE AND ID = '$BukkenCD489'" , "")){
					$ErrorString = array();
					$ErrorString[] = "設定ファイル情報の抽出に失敗しました。";
					$ErrorLoop = count($ErrorString);
					$myTemplate = new SPFWTemplate(_ADMIN_ERROR_TPL, $MyCarrier, TRUE);
					unset($myTemplate);
					exit;
				}
				$wClientCD = $myClient->ClientCD;#新型式で物件を識別するClientCD
				unset($myClient );

			}

			#$myDBeach = new SPFWDatabase( $dbname, '192.168.98.221', _USER_NAME, _PASSWD , FALSE);
			#if (!$myDBeach->Connection)
			#	trigger_error("SPFWDatabase Failed.", E_USER_ERROR);
			#echo "<br>▼".$BukkenCD489.$dbname."　にコネクト";


			########################################################
			# 489物件　機器保管場所取得
			########################################################
			$myListObject2 = new SPFWListObject($myDBeach);

			$sql = "SELECT ";
			$sql .= "MansionMemo ";
			$myListObject2->SelectSQL = $sql;
			$sql = " FROM tSettingM ";
			$sql .= " WHERE MukouFlg = FALSE ";
			$sql .= " AND SFBukkenCD = $editBukkenCD ";
			$myListObject2->Condition = $sql;
			$myListObject2->Order = "1";
			$myListObject2->Limit = "allpage";

			if (!($myListObject2->GetList(1)))
				trigger_error("Getting Reservation List Failed.", E_USER_ERROR);

			if ($myListObject2->Rows == 1) {
				$MansionMemo = $myListObject2->GetValue(0,0);
			}
			unset($myListObject2);

			########################################################
			# 489物件　残工事部屋情報取得
			########################################################
			##完了フラグを見る　Notesに日付がはいっているのが手動で完了とした部屋 Start
			##SELECT * FROM tReservationF WHERE Notes = NULL or Notes = '' 

			$myListObject2 = new SPFWListObject($myDBeach);

			$sql = "SELECT ";
			$sql .= "u.ID ";
			$myListObject2->SelectSQL = $sql;
			$sql = " FROM tReservationF r , tUserM u ";
			$sql .= " WHERE r.UserCD = u.UserCD and r.Notes !='' and r.Status = 1";
			if( $Skoji )$sql .= " AND r.ClientCD = $wClientCD AND u.ClientCD = $wClientCD  ";



			$myListObject2->Condition = $sql;
			$myListObject2->Order = "1";
			$myListObject2->Limit = "allpage";

			if (!($myListObject2->GetList(1)))
				trigger_error("Getting Reservation List Failed.", E_USER_ERROR);

			$RecoLoop = $myListObject2->Rows;
			for ($j = 0; $j < $RecoLoop; $j++) {
				$KanID[] = $myListObject2->GetValue($j,0);
			}
			unset($myListObject2);
			##完了フラグを見る　Notesに日付がはいっているのが手動で完了とした部屋 End

			#print_r($KanID);


			##親機の施工後（Device=1かつSekoStatus=2）の写真があれば、完了と判断する Start
			#select ID from tUserM a where  exists (select ID from tPictureF b where a.ID = b.ID ) 
			$myListObject3 = new SPFWListObject($myDBeach);

#			$sql = "SELECT ID "; 
#			$myListObject3->SelectSQL = $sql;
#			$sql = " From tUserM a ";#　施工後の親機を対象とする　施行前の玄関子機だけ写真とるときがある
#			$sql .= " where exists (select ID from tPictureF b where a.ID = b.ID AND b.MukouFlg = 0 AND b.Device = 1 AND b.SekoStatus = 2 ) ";
			$sql = "SELECT a.ID";
			$myListObject3->SelectSQL = $sql; 
			$sql = " from tUserM a , tPictureF b ";
			$sql .= " where a.ID = b.ID  ";
			$sql .= " AND b.MukouFlg = 0  ";
			$sql .= " AND b.Device = 1  ";
			$sql .= " AND b.SekoStatus = 2 ";
			if($Skoji)$sql .= " a.ClientCD  = $wClientCD  ";
			if($Skoji)$sql .= " and b.ClientCD = $wClientCD   ";

			$myListObject3->Condition = $sql;
			$myListObject3->Order = "1";
			$myListObject3->Limit = "allpage";

			if (!($myListObject3->GetList(1)))
				trigger_error("Getting User List Failed.", E_USER_ERROR);

			$RecoLoop = $myListObject3->Rows;
			for ($j = 0; $j < $RecoLoop; $j++) {
				$KanID[] = $myListObject3->GetValue($j,0);
			}
			unset($myListObject3);

			##親機の施工後（Device=1かつSekoStatus=2）の写真があれば、完了と判断する End


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
				$sql .= " WHERE ID not like 'dummy%' and ID != 1234 and ID != 5678 and ID != 'kanri' and ID != 'aiphone' and MukouFlg = false";
				if($Skoji)$sql .= " AND ClientCD  = $wClientCD  ";
				$myListObject4->Condition = $sql;
				$myListObject4->Order = "1";
				$myListObject4->Limit = "allpage";

				if (!($myListObject4->GetList(1)))
					trigger_error("Getting User List Failed.", E_USER_ERROR);

				$RecoLoop = $myListObject4->Rows;
				for ($j = 0; $j < $RecoLoop; $j++) {
					$ID[$j] = $myListObject4->GetValue($j,0);

					if(!in_array( $ID[$j] , $KanID )){
						$MiKanID[] = $ID[$j];
					}
				}
				unset($myListObject4);
				##不要な部屋（dummy等）を除外 End


				if( count($MiKanID) > 0 ){
					#echo "<br>完了している部屋あり、残工事部屋あり：<br>";
					#print_r($MiKanID);
					$IfZanHeya='true';
					for($k = 0 ; $k < count($MiKanID); $k++){
						if ($ZanHeyaDisp) $ZanHeyaDisp = $ZanHeyaDisp.", ".$MiKanID[$k];
						else $ZanHeyaDisp = $MiKanID[$k];
					}
				} else {#tBukkenM.ZanHeyaフラグがあるにもかかわらず、残部屋がなくなった
					#echo "<br>全部屋完了、残工事部屋なし";
					#$ClearBukkenCD = $BukkenCD;
					$IfNoZan = true;
				}

			} else {
				$IfNoZan = TRUE;
				#echo "<br>完了部屋はなし　[不明]となる";
			}

		}
	}





	########################################################
	# コンテンツ表示
	########################################################

	$CNT_FILE = "s_zan.tpl";

	$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier);
	$HiddenValues = $myTemplate->getValuesToPass();

	$myTemplate->convertTags();
	$myTemplate->outputTemplate();

	unset($myTemplate);
	unset($myLog);
?>
