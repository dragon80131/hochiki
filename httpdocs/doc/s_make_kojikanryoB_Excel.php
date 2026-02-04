<?php
/* 
 * 工事完了報告書B 出力
 * s_make_kojikanryoB_Excel.php
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
	include_once _CLS_DIR . "SPUSKoji.cls";
	include_once _CLS_DIR . "SPUSIraiRenkei.cls";


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
	unset($myUser);


	########################################################
	# 物件情報抽出
	########################################################

	$editBukkenCD = SPFWParameter::getValues('editBukkenCD');

	$myBukken = new Bukken($myDB);

	if (!$myBukken->executeSelect("BukkenCD = " . $editBukkenCD . " AND MukouFlg = FALSE", "")){
		trigger_error("Getting Bukken Failed.", E_USER_ERROR);
	}

	if ($myBukken->RecCnt != 1) {
		trigger_error("Getting myBukken List Failed.", E_USER_ERROR);

	} else {

		$BukkenName = $myBukken->BukkenName;
		$Kosu = $myBukken->Kosu;
		$ZanHeya = $myBukken->ZanHeya;
		$ZanDate = $myBukken->ZanDate;
	}
	unset($myBukken);


	########################################################
	# 依頼情報抽出
	########################################################

	$myIraiRenkei = new IraiRenkei($myDB);

	if (!$myIraiRenkei->executeSelect("BukkenCD = " . $editBukkenCD, "") ){
		trigger_error("Getting IraiRenkei Failed.", E_USER_ERROR);
	}

	if ($myIraiRenkei->RecCnt != 1) {

		#依頼登録がまだ
		$ErrorString = array();
		$ErrorString[] = "依頼情報を登録してください。";
		$ErrorLoop = count($ErrorString);
		$myTemplate = new SPFWTemplate(_ERROR_TPL, $MyCarrier, TRUE);
		exit;

	} else {
		$BukkenCD489 = $myIraiRenkei->BukkenCD489;

		if (!$BukkenCD489) {
			$ErrorString = array();
			$ErrorString[] = "予約センター側の物件が登録されていません。しばらくお待ちいただくかお問い合わせください。";
			$ErrorLoop = count($ErrorString);
			$myTemplate = new SPFWTemplate(_ERROR_TPL, $MyCarrier, TRUE);
			exit;
		}
	}
	unset($myIraiRenkei);


	########################################################
	# 工事情報抽出
	########################################################

	$myKoji = new Koji($myDB);

	if (!$myKoji->executeSelect("BukkenCD = " . $editBukkenCD, "") ){
		trigger_error("Getting Koji Failed.", E_USER_ERROR);
	}

	if ($myKoji->RecCnt != 1) {

		#工事情報登録がまだ
		$ErrorString = array();
		$ErrorString[] = "工事情報を登録してください。";
		$ErrorLoop = count($ErrorString);
		$myTemplate = new SPFWTemplate(_ERROR_TPL, $MyCarrier, TRUE);
		exit;

	} else {
		$KojiName = $myKoji->KojiName;
	}
	unset($myKoji);




#echo " ZanHeya".$ZanHeya;
#echo " ZanDate".$ZanDate;
#echo " BukkenCD489".$BukkenCD489;
	########################################################
	# 残工事情報取得
	########################################################

	// 予約センターから残連携済 かつ 残工事なし
	if ($ZanDate != "0000-00-00" AND $ZanHeya == "") {
		#$IfNoZan = true; // 残工事情報はありません。#

	} else {
		// 予約センターから残連携未
		//  または
		// 予約センターから残連携済で 残工事あり

		#$IfZan = TRUE; // 残工事部屋を表示する


		########################################################
		# データベースコネクト
		########################################################

		$myDBeach = new SPFWDatabase( 'koji'.$BukkenCD489, '192.168.98.221', _USER_NAME, _PASSWD , FALSE);
		if (!$myDBeach->Connection)
			trigger_error("SPFWDatabase Failed.", E_USER_ERROR);
		#echo "<br>▼".$BukkenCD489."　にコネクト";


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

		$sql = "SELECT ID "; 
		$myListObject3->SelectSQL = $sql;
		$sql = " From tUserM a ";#　施工後の親機を対象とする　施行前の玄関子機だけ写真とるときがある
		$sql .= " where exists (select ID from tPictureF b where a.ID = b.ID AND b.MukouFlg = 0 AND b.Device = 1 AND b.SekoStatus = 2 ) ";

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
		#print_r($KanID);

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
			$sql .= " WHERE ID not like 'dummy%' and ID != 1234 and ID != 5678 and ID !='aiphone' and MukouFlg = false";

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
				for($k = 0 ; $k < count($MiKanID); $k++){
					if ($ZanHeyaDisp) $ZanHeyaDisp = $ZanHeyaDisp.", ".$MiKanID[$k];
					else $ZanHeyaDisp = $MiKanID[$k];
				}
			} else {#tBukkenM.ZanHeyaフラグがあるにもかかわらず、残部屋がなくなった
				#echo "<br>全部屋完了、残工事部屋なし";
				$ClearBukkenCD = $BukkenCD;
			}

		} else {
			#$IfNoZan = TRUE;
			#$ZanHeyaDisp = "不明";
			#echo "<br>完了部屋はなし　[不明]となる";
		}

	}




	########################################################
	# Excelファイル生成
	########################################################
	require 'vendor/autoload.php';

	use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;
	$reader = new XlsxReader();

	$spreadsheet = $reader->load('./template/kojikanryoB.xlsx'); //template.xlsx 読込

	$sheet = $spreadsheet->getActiveSheet();

	$sheet->setCellValue('U2', date('Y年m月d日') );
	$sheet->setCellValue('J12', $KojiName);

	$sheet->setCellValue('G18', $Kosu); // 総住戸数
	if ($ZanHeyaDisp) 
		$sheet->setCellValue('F21', $ZanHeyaDisp); // 未施工住戸


	//ダウンロード用
	//MIMEタイプ：https://technet.microsoft.com/ja-jp/ee309278.aspx
	header("Content-Description: File Transfer");
	$wFileName = "工事完了報告書B".date('Ymd').".xlsx" ;
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
