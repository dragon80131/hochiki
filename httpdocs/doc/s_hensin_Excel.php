<?php
/* 
 * 返信催促案内
 * s_hensin_Excel.php
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
	include_once _CLS_DIR . "SPUSShiryo.cls";
	include_once _CLS_DIR . "SPUSIraiRenkei.cls";
	include_once _CLS_DIR . "SPUSKoji.cls";

	include_once  "../include/common.php";


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
	$ID = $myUser->ID;
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
		#必要な項目　マンション名、工事名称、住所、戸数、管理会社、消防特例、管理会社、管理員関係

		$KanriGaisya = $myBukken->KanriGaisya;
		$BukkenName = $myBukken->BukkenName;
	}


	$myIraiRenkei = new IraiRenkei($myDB);

	if (!$myIraiRenkei->executeSelect("BukkenCD = " . $editBukkenCD . " AND MukouFlg = FALSE", "")){
		trigger_error("Getting IraiRenkei Failed.", E_USER_ERROR);
	}

	if ($myIraiRenkei->RecCnt != 1 or !$myIraiRenkei->BukkenCD489) {

		#489依頼がまだ、予約センターの工事番号がまだない
		$ErrorString = array();
		$ErrorString[] = "予約センターにて、督促案内は、まだ作成されていません。";
		$ErrorLoop = count($ErrorString);
		$myTemplate = new SPFWTemplate(_ERROR_TPL, $MyCarrier, TRUE);
		exit;
	}

	$BukkenID = $myIraiRenkei->BukkenCD489;
	unset($myIraiRenkei);



	########################################################
	# 489センターへ接続
	########################################################
	$myDBeach = new SPFWDatabase('koji'.$BukkenID, '192.168.98.221', _USER_NAME, _PASSWD, FALSE);


	$myListObject = new SPFWListObject($myDBeach);
	$sql = "SELECT ";       
	$sql .= "MenuCD, ";     #1 0
	$sql .= "MenuName, ";   #2 1
	$sql .= "PicMenu, ";    #3 2 全戸対象/申込み部屋対象１２
	$sql .= "PicSekoStatus ";       #4 3施工前・後：０施工前：１施工後：２
	$myListObject->SelectSQL = $sql;
	$sql = " FROM tMenuM";
	$sql .= " WHERE MenuCD > 0 AND MukouFlg = FALSE AND PicMenu in ( 1, 2)";

	$myListObject->Condition = $sql;
	$myListObject->Order = "MenuCD";
	$myListObject->Limit = "allpage";
	if (!($myListObject->GetList(1)))
		trigger_error("Getting MenuM List Failed.", E_USER_ERROR);

	$OpLoop = $myListObject->Rows;
	for ($i = 0; $i < $OpLoop  ; $i++) {
		$wOp[$i] =  $myListObject->GetValue($i, 0) ; # MenuCDをとってくる。
		$Op[$i] =  $myListObject->GetValue($i, 0) + 2 ; # tPictureFのDeviceと一致させる。

//		$OpName[$i] = $myListObject->GetValue($i, 1); #追加デバイス名
		$PicMenu[$i] = $myListObject->GetValue($i, 2); #全部屋対象1かオプション申込み部屋のみ2か

		$wPicSekoStatus[$i] = $myListObject->GetValue($i, 3); #施工前・後１２
		$PicSekoStatus[$i] = $PicSekoStatusArray[$wPicSekoStatus[$i]];
	}



	$myListObject = new SPFWListObject($myDBeach);
	$sql = "SELECT ";
	$sql .= "r.userCD, ";    #1 0
	$sql .= "r.id, ";        #2 1
	$sql .= "r.TimeTo, ";    #3 2
	$sql .= "p.SekoStatus, ";#4 3
	$sql .= "p.Device, ";    #5 4
	$sql .= "p.Eda, ";       #6 5
	$sql .= "length(p.P002), ";    #7 6
	$sql .= "r.MenuCD ";     #8 7
	$myListObject->SelectSQL = $sql;
	$sql = " FROM vReservationF r ";
	$sql .= " left outer join tPictureF p on r.ID = p.ID and p.MukouFlg = 0 and r.Status = 1 ";
	$myListObject->Condition = $sql;
	$myListObject->Order = "3,1";
	$myListObject->Limit = "allpage";
	
	if (!($myListObject->GetList(1)))
		trigger_error("Getting Stylist List Failed.", E_USER_ERROR);
	$RecoLoop = $myListObject->Rows;
	$j = 0;
	$k = 1 ;
	$KanID[0] = "999" ;
	for ($i = 0; $i < $RecoLoop; $i++) {
//		$uCD[$i] = $myListObject->GetValue($i, 0);
		$wID[$i] = $myListObject->GetValue($i, 1);
		$SekoStatus[$i] = $myListObject->GetValue($i, 3);
		$wDevice[$i] = $myListObject->GetValue($i, 4) ;
//		$Device[$i] = $myListObject->GetValue($i, 4) + 2 ;
		$Eda[$i] = $myListObject->GetValue($i, 5);
		$PLength[$i] = $myListObject->GetValue($i, 6);
		$MenuCD[$i] = $myListObject->GetValue($i, 7);
		
		#初期の部屋
		$xID[0] = $wID[0]; #部屋番号を＄IDにいれる。

		if ( $wID[$i] != $xID[$j] or $i == 0 ){ #⑤　1行目か部屋番号が変わったら　ｊが一つ増えて次の部屋になる
			if($i != 0 ){#1行目のときは、jを増やさない。
			    $j = $j + 1;
		        $xID[$j] = $wID[$i]; #部屋番号を＄IDにいれる。
			}

			$MaxCnt[$j] = 4;
			#オプション表示部分＄OpPic[$j]にいれる。
			for ( $x = 0; $x < $OpLoop ; $x++){#⑦
				if ( $PicMenu[$x] == 1 ){      #②全戸対象の対応
				    if ($wPicSekoStatus[$x] == 0 ){ #施工前・後
						$MaxCnt[$j] = $MaxCnt[$j] + 2; #写真がそろったらグレーアウト用
					}elseif ($wPicSekoStatus[$x] == 1 ){ #施工前
						$MaxCnt[$j] = $MaxCnt[$j] + 1;
					}elseif ($wPicSekoStatus[$x] == 2 ){ #施工後
						$MaxCnt[$j] = $MaxCnt[$j] + 1;
					}elseif ($wPicSekoStatus[$x] == 3 ){ #施工前中後
						$MaxCnt[$j] = $MaxCnt[$j] + 3;
				    }

				}elseif ($PicMenu[$x] == 2 ){ #②申込住戸の対応

			    	if  (!strpos( $MenuCD[$i] , $wOp[$x] )){ #③申し込んだ内容に、申込住戸の写真撮影がなかったら オプションCDは4　ここではｔMenuCDのMenuCDと比べる|1|2|

					}else{#③

						if ($wPicSekoStatus[$x] == 0 ){#施工前・後
							$MaxCnt[$j] = $MaxCnt[$j] + 2; #写真がそろったらグレーアウト用
						}elseif ($wPicSekoStatus[$x] == 1 ){ #施工前
							$MaxCnt[$j] = $MaxCnt[$j] + 1;
						}elseif ($wPicSekoStatus[$x] == 2 ){ #施工後
							$MaxCnt[$j] = $MaxCnt[$j] + 1;
						}elseif ($wPicSekoStatus[$x] == 3 ){ #施工前中後
							$MaxCnt[$j] = $MaxCnt[$j] + 3;
						}
					}#③申込住戸の対応IfのEnd
				}#②IfのEnd
			}#⑦
		}#⑤

		if ( $PLength[$i] > 1 ){ #⑨写真があったら 初期値の写真リンクを上書きする
			if($wDevice[$i] < 3){#　⑥
	    		switch ($SekoStatus[$i].$wDevice[$i].$Eda[$i]) {
	    		case '111': #親機施工前
	    		case '211': #親機施工後
	    		case '121': #子機施工前
	    		case '221': #子機施工後
				case '311': #親機施工中 1枚目
				case '321': #子機施工中 1枚目
					$Gray[$j] = $Gray[$j] + 1;
		     		break;
				}
			}else{ #Deviceが3以上のとき⑥
				#未完成
	    		switch ($SekoStatus[$i].$Eda[$i]) {
	    		case '11': #施工前　1枚目
	    		case '21': #施工後　1枚目
				case '31': #施工中　1枚目
					$Gray[$j] = $Gray[$j] + 1;
		     		break;
				}
			}#⑥End
		}#⑨写真があるなしEnd

		####グレーにする。
		#専有部分が4種類ある
		if ( $Gray[$j] == $MaxCnt[$j] ){
			$KanID[$k] = $xID[$j];#写真完了した部屋を配列にいれる。
			$k = $k + 1;
		}
	}#for i End

###########写真そろってグレーアウトEnd



	// Notesに日付が入っていれば（削除のチェックを入れた日）完了
	$myListObject = new SPFWListObject($myDBeach);
	$sql = "SELECT ";
	$sql .= "Notes, ";
	$sql .= "id ";
	$myListObject->SelectSQL = $sql;
	$sql = " FROM vReservationF";
	$sql .= " WHERE timefrom is not NULL";
	$myListObject->Condition = $sql;
	$myListObject->Order = "TimeTo , userCD "; // 工事日、部屋番号順
	$myListObject->Limit = "allpage";
	
	if (!($myListObject->GetList(1)))
		trigger_error("Getting Stylist List Failed.", E_USER_ERROR);

	$RecoLoop = $myListObject->Rows;

	$z = 1;
	$compID[0] = "0";

	for ($i = 0; $i < $RecoLoop; $i++) {
		$Notes[$i] = $myListObject->GetValue($i, 0);
		$xID[$i] = $myListObject->GetValue($i, 1);

		if (($Notes[$i] == "" or $Notes[$i] == 0)){ 
			if (in_array( $xID[$i], $KanID)) {#20140725写真がそろえば完了のグレーアウト
				$compID[$z] = $xID[$i];
				$z = $z + 1;
			}
		} else {
			$compID[$z] = $xID[$i];
			$z = $z + 1;
		}
	}

	unset($myListObject);
	########################################################
	# ログインしていない部屋の中に、工事完了している部屋をカウントする
	########################################################

	$myListObject = new SPFWListObject($myDBeach);
	$sql = "SELECT ";
	$sql .= "id ";
	$myListObject->SelectSQL = $sql;
	$sql = " FROM vReservationF ";
	$sql .= " WHERE uUpdated = '0000-00-00 00:00:00' ";

	$myListObject->Condition = $sql;
	$myListObject->Order = "userCD";
	$myListObject->Limit = "allpage";

	if (!($myListObject->GetList(1)))
		trigger_error("Getting Reservation List Failed.", E_USER_ERROR);

	$ReservationLoop = $myListObject->Rows;
	unset($ID);
	for ($i = 0; $i < $ReservationLoop; $i++) {
		$ID[$i] = $myListObject->GetValue($i, 0);

	}


	$j=0; // 未返事かつ工事未完了のループ用
	$cnt = count($ID); // 未返事の数
	//echo "<br>cnt:" .$cnt;
	for ($i=0; $i<$cnt; $i++){
		if (in_array( $ID[$i] , $compID)){ // 工事済が未返事にある場合
			// 表示しない
		} else { // 工事済じゃないやつ
			// 表示する
			$notrepID[$j] = $ID[$i];
//	echo $notrepID[$j];
			$j = $j + 1;
		}
	}

	$NotreplayLoop = count($notrepID); // 未返事かつ工事未完了の数
	$ko = $NotreplayLoop; // 合計
	//echo "<br>NotreplayLoop:" .$NotreplayLoop;





	$week = array('日','月','火','水','木','金','土');
	########################################################
	# Excelファイル生成
	########################################################
	require 'vendor/autoload.php';

	use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;
	$reader = new XlsxReader();

	$spreadsheet = $reader->load('./template/hensinsaisoku.xlsx'); //template.xlsx 読込

	$sheet = $spreadsheet->getActiveSheet();

	$sheet->setCellValue('U2', date('Y年m月d日'));
	$sheet->setCellValue('B3', $BukkenName);
	$sheet->setCellValue('T6', $KanriGaisya);#管理会社
	$sheet->setCellValue('D15', date('Y年m月d日')."(".$week[date('w')].")");
	$sheet->setCellValue('J3', $notrepID[0]);

	for($i = 2; $i <= $ko; $i++){
		$clonedWorksheet = clone $spreadsheet->getSheetByName('Sheet1');
		$clonedWorksheet->setTitle('Sheet'.$i);
		$spreadsheet->addSheet($clonedWorksheet);
		$sheet = $spreadsheet->getSheetByName('Sheet'.$i); //weatherシート取得
		$sheet->setCellValue('J3', $notrepID[$i-1]);
	}


	//ダウンロード用
	//MIMEタイプ：https://technet.microsoft.com/ja-jp/ee309278.aspx
	header("Content-Description: File Transfer");
	$wFileName = "返信督促案内_".$BukkenName.".xlsx" ;
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
