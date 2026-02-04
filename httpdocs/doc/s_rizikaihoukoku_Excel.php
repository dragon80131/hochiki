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
	include_once _CLS_DIR . "SPFWParameter.cls";
	include_once _CLS_DIR . "SPUSUser.cls";
	include_once _CLS_DIR . "SPUSBukken.cls";
	include_once _CLS_DIR . "SPUSShiryo.cls";
	include_once _CLS_DIR . "SPUSIraiRenkei.cls";
	include_once _CLS_DIR . "SPUSKoji.cls";
	include_once _CLS_DIR . "center489/SPUSSetting489.cls";
	
	include_once  "../include/common.php";
	include_once  "../include/common_489.php";


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
	#$ID = $myUser->ID;
	unset($myUser);



	########################################################
	# データ取得
	########################################################
	$editBukkenCD = SPFWParameter::getValues('editBukkenCD');

	$myBukken = new Bukken($myDB);

	if (!$myBukken->executeSelect("BukkenCD = ".$editBukkenCD , ""))
		trigger_error("Getting Bukken Failed.", E_USER_ERROR);

	$BukkenName = $myBukken->BukkenName;
	$KanriGaisya = $myBukken->KanriGaisya;
	$Tosu =  $myBukken->Tosu;	#棟数
	$Kosu =  $myBukken->Kosu;	#棟数

	unset($myBukken);
	
	########################################################
	# 工事情報取得
	########################################################

	$myKoji = new Koji($myDB);

	if (!$myKoji->executeSelect("BukkenCD = ".$editBukkenCD , ""))
		trigger_error("Getting Bukken Failed.", E_USER_ERROR);

	if ($myKoji->RecCnt != 1) {

		#工事情報登録がまだ
		$ErrorString = array();
		$ErrorString[] = "工事情報を登録してください。";
		$ErrorLoop = count($ErrorString);
		$myTemplate = new SPFWTemplate(_ERROR_TPL, $MyCarrier, TRUE);
		exit;

	}

	$KojiName = $myKoji->KojiName;

	$GyosyaTantoCD1 = $myKoji->GyosyaTantoCD1; 
	$GyosyaData = getGyosyaData($myDB, $GyosyaTantoCD1 );
	$GyosyaName = $GyosyaData['GyosyaName'];

	#資料右上に出力する会社名を取得
	if(!empty($myKoji->DispCompanyTop)){
		$DispCompanyTopArr = SPFWTools::decodePluralValue($myKoji->DispCompanyTop);#パイプつなぎを配列に変換
		$DispCompanyTop1 = $DispCompanyTopArr[0];
		$DispCompanyTop2 = $DispCompanyTopArr[1];
		$DispCompanyTop3 = $DispCompanyTopArr[2];
	}else{#登録なければ空文字でセット
		$DispCompanyTop1 = "";
		$DispCompanyTop2 = "";
		$DispCompanyTop3 = "";
	}

	unset($myKoji);


	$format = SPFWParameter::getValues('format');



	$myListObject = new SPFWListObject($myDB);


	$sql = "SELECT ";
	$sql .= "BukkenCD489 ";     #1 0
	$myListObject->SelectSQL = $sql;
	$sql = " FROM tIraiRenkeiF";
	$sql .= " WHERE BukkenCD = ".$editBukkenCD;
	$myListObject->Condition = $sql;
	$myListObject->Order = "";
	$myListObject->Limit = "allpage";
	
	if (!($myListObject->GetList(1)))
		trigger_error("Getting IraiRenkei List Failed.", E_USER_ERROR);

	$BukkenCD489 = $myListObject->GetValue(0,0);


	if($myListObject->Rows >= 1){

		########################################################
		# 各物件　データベースコネクト
		########################################################

		$myDBeach = connect489($BukkenCD489); // DB接続
		#echo "<br>▼".$BukkenCD489."　にコネクト";

		$dbname = getDBname489($BukkenCD489); // DB名取得
		if ($dbname == "skoji") {
			$ClientCD = getClientCD489($BukkenCD489); // ClientCD取得
		}

		########################################################
		# 489物件 工事完了部屋取得
		########################################################

		$KanID = $MiKanID = $MihenjiID = array();

		##完了フラグを見る　Notesに日付がはいっているのが手動で完了とした部屋 Start
		$myListObject2 = new SPFWListObject($myDBeach);
		$sql = "SELECT ";
		$sql .= "u.ID ";
		$myListObject2->SelectSQL = $sql;
		$sql = " FROM tReservationF r, tUserM u ";
		$sql .= " WHERE r.UserCD = u.UserCD and r.Notes !='' and r.Status = 1";
		if ($ClientCD) $sql .= " AND r.ClientCD = ".$ClientCD." AND u.ClientCD = ".$ClientCD." ";

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


		##親機の施工後（Device=1かつSekoStatus=2）の写真があれば、完了と判断する Start
		$myListObject3 = new SPFWListObject($myDBeach);
		$sql = "SELECT u.ID";
		$myListObject3->SelectSQL = $sql; 
		$sql = " from tUserM u, tPictureF p ";
		$sql .= " where u.ID = p.ID  ";
		$sql .= " AND p.MukouFlg = 0  ";
		$sql .= " AND p.Device = 1  ";
		$sql .= " AND p.SekoStatus = 2 ";
		if ($ClientCD) $sql .= " AND u.ClientCD = ".$ClientCD." and p.ClientCD = ".$ClientCD." ";

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
		if (count($KanID) > 0) {

			########################################################
			# 489物件 未工事部屋取得
			########################################################

			$KanID = array_unique( $KanID ); //配列で重複している物を削除する
			$KanID = array_values( $KanID ); //キーが飛び飛びになっているので、キーを振り直す

			##不要な部屋（dummy等）を除外 Start
			$myListObject4 = new SPFWListObject($myDBeach);
			$sql = "SELECT ";
			$sql .= "ID ";
			$myListObject4->SelectSQL = $sql;
			$sql = " FROM tUserM ";
			$sql .= " WHERE ID not like 'dummy%' and ID != 1234 and ID != 5678 and ID != 'kanri' and ID != 'aiphone' and MukouFlg = false";
			if($ClientCD) $sql .= " AND ClientCD  = ".$ClientCD." ";

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
		}
	}


	// 工事未完了で、未返事の部屋を取得
	for($i = 0; $i < count($MiKanID); $i++){

		$myListObject = new SPFWListObject($myDBeach);

		if($ClientCD) {
			$sql = "SELECT ";
			$sql .= "ID, ";
			$sql .= "TimeFrom ";
			$myListObject->SelectSQL = $sql;
			$sql = " FROM vReservationF ";
			$sql .= " WHERE ID = '".$MiKanID[$i]."' ";
			$sql .= " AND ClientCD  = ".$ClientCD." ";
			$sql .= " AND Updater = '0'"; // 未連絡
			$myListObject->Condition = $sql;
			$myListObject->Order = "UserCD";
			$myListObject->Limit = "allpage";

		} else {

			$sql = "SELECT ";
			$sql .= "id, ";
			$sql .= "TimeFrom ";
			$myListObject->SelectSQL = $sql;
			$sql = " FROM vReservationF ";
			$sql .= " WHERE id = '$MiKanID[$i]' ";
			$sql .= " AND uUpdated = '0000-00-00 00:00:00'"; // 未連絡
			$myListObject->Condition = $sql;
			$myListObject->Order = "userCD";
			$myListObject->Limit = "allpage";

		}
		if (!($myListObject->GetList(1)))
			trigger_error("Getting Reservation List Failed.", E_USER_ERROR);

		if ($myListObject->Rows == 1) {
			$MihenjiID[] = $myListObject->GetValue(0, 0);
			$MihenjiTimeFrom[] = date('n月j日',strtotime($myListObject->GetValue(0, 1)));
		}
	}
	$ko = count($MihenjiID);


	########################################################
	# Excelファイル生成
	########################################################
	require 'vendor/autoload.php';

	use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;
	use PhpOffice\PhpSpreadsheet\Style\Border;
	use PhpOffice\PhpSpreadsheet\Style\Fill;
	use PhpOffice\PhpSpreadsheet\Style\Style;


	$reader = new XlsxReader();

	$template_file = './template/rijikaihoukoku.xlsx';
	$spreadsheet = $reader->load( $template_file ); //template.xlsx 読込

	$sheet = $spreadsheet->getActiveSheet();
	$sheet->setCellValue('B2', $BukkenName);
#	$sheet->setCellValue('A1', $Tosu);	#確認


	#右上に工事案内作成画面にて登録された会社名を表示
	#◆右上に表示する会社名
	$sheet->setCellValue('AJ3', $DispCompanyTop1);
	$sheet->setCellValue('AJ4', $DispCompanyTop2);
	$sheet->setCellValue('AJ5', $DispCompanyTop3);

	#タイトルに工事名を埋め込み
	$sheet->setCellValue('C6', $KojiName."の進捗報告");

	#あいさつ文に工事名称を埋め込み
	$tmpCell = $sheet->getCell('B10');	
	$tmpCellStr = $tmpCell->getValue();
	$tmpCellStr = str_replace("●●工事",$KojiName,$tmpCellStr);
	$tmpCell->setValue($tmpCellStr);

	#あいさつ文に工事名称を埋め込み
	$tmpCell = $sheet->getCell('B12');	
	$tmpCellStr = $tmpCell->getValue();
	$tmpCellStr = str_replace("●●工事",$KojiName,$tmpCellStr);
	$tmpCell->setValue($tmpCellStr);


	$sheet->setCellValue('AJ1', date("Y年n月j日"));
	#$sheet->setCellValue('AJ4', $KanriGaisya);
	$sheet->setCellValue('P14', "(".date("n月j日")."現在)");

	$sheet->setCellValue('G16', $Kosu); // 居室住戸数
	$sheet->setCellValue('L16', count($KanID)); // 工事完了数

	// 工事未完了・未返事部屋
	if($ko>0){
		$sheet->setCellValue('AB16', $ko);
		$sheet->setCellValue('C19',"下記の".$ko."戸につきまして、工事日が確定しておりません。管理会社様よりご連絡をお取り");
		$sheet->setCellValue('C20',"いただいておりますが、工期内に工事完了しない場合はお客様からご連絡いただき次第の");
		$sheet->setCellValue('C21',"工事となります。");
		$sheet->setCellValue('C22',"また、今後の工事進捗に伴いご不在による未施工住戸が発生する可能性があります。");
		$sheet->setCellValue('C23',"都度、不在票の投函や訪問を行い工事期間内に完了するよう活動させていただきます。");
		$sheet->setCellValue('C24',"工期外の工事については、工事期間終了後から６ヶ月間は無償工事とさせていただきますが、");
		$sheet->setCellValue('C25',"６ヶ月後の工事については別途工事費が発生するため、居住者様と調整させていただきます。");
		$sheet->setCellValue('AI26',"以上");
		$sheet->setCellValue('B27',"＜工事日未定住戸について＞");
		$sheet->mergeCells('C28:F28');
		$sheet->mergeCells('G28:M28');
		$sheet->mergeCells('N28:AI28');
		$sharedStyle1 = new Style();
		$sharedStyle1->applyFromArray([
			'borders' => [
				'outline' => ['borderStyle' => Border::BORDER_THICK],
				'bottom' => ['borderStyle' => Border::BORDER_THIN],
				'top' => ['borderStyle' => Border::BORDER_THIN],
				'right' => ['borderStyle' => Border::BORDER_THIN],
				'left' => ['borderStyle' => Border::BORDER_THIN],
			],
			'alignment' => [
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
				'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			],
			'font' => [
				'name' => 'メイリオ',
				'size' => '11',
			],
		]);
		$cell=28+$ko;
		$cell="AI".$cell;
		$sheet->duplicateStyle($sharedStyle1, ('C28:'.$cell));
		$sharedStyle2 = new Style();

		$sharedStyle2->applyFromArray([
			'borders' => [
				'outline' => ['borderStyle' => Border::BORDER_THICK],
				'bottom' => ['borderStyle' => Border::BORDER_THIN],
				'top' => ['borderStyle' => Border::BORDER_THIN],
				'right' => ['borderStyle' => Border::BORDER_THIN],
				'left' => ['borderStyle' => Border::BORDER_THIN],
			],
			'alignment' => [
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
				'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
			],
			'font' => [
				'name' => 'メイリオ',
				'size' => '11',
			],
		]);
		$sheet->duplicateStyle($sharedStyle2, ('N29:'.$cell));
		$sheet->setCellValue('C28', "住戸番号");
		$sheet->setCellValue('G28', "施工予定日");
		$sheet->setCellValue('N28', "対策方法");
		$spreadsheet->getSheetByName('理事会報告')->getStyle("C28:AI28")->getFill()
			->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
			->getStartColor()->setARGB('b6dde8');
			

		for($i=0;$i<$ko;$i++){
			$cell=29+$i;
			$sheet->mergeCells('C'.$cell.':F'.$cell);
			$sheet->mergeCells('G'.$cell.':M'.$cell);
			$sheet->mergeCells('N'.$cell.':AI'.$cell);
			$sheet->setCellValue('C'.$cell, $MihenjiID[$i]);
			$sheet->setCellValue('G'.$cell, $MihenjiTimeFrom[$i]);
		}

	}
//--住宅情報盤取り替えの状況の編集
#--test
#$Tosu = 6;
/*	if ($Tosu < 5){
		$wgyo = 5-$Tosu;		#削除する行数
		$wstart = 16 +$Tosu;	#削除基準の行数
		$sheet->insertNewRowBefore( 23, $wgyo );	#削除分の行数を追加
		$sheet->removeRow($wstart, $wgyo);			#不要分の削除


#---	セルの結合の解除
		$cellsArray = array("C","F","G","K","L","P","Q","V","W","AA","AB","AI");
		$wrow = 17+$Tosu;	#結合STar
		$l = 4 - $Tosu;		#ループ回数計算

		for ($k=0;$k<$l;$k++){
			for ($j=0;$j<=11;$j=$j+2){
				$i = $j+1;
				$Cell= $cellsArray[$j].$wrow.":".$cellsArray[$i].$wrow;
	echo "test";
				$sheet->unmergeCells($Cell);
#				echo $Cell."<br />";
			}
			$wrow = $wrow +1;

		}

//---		セルに数式をセット

		$wGyo = 15+$Tosu;	#棟最終行
		$wGyo1 = $wGyo+1;	#合計行
		
//---居室住戸数の数式セット
		$wsiki = "=SUM(G16:G".$wGyo.")";
		$sheet->setCellValueByColumnAndRow(7, $wGyo1,$wsiki);
		
//---工事完了数の数式セット
		$wsiki = "=SUM(L16:L".$wGyo.")";
		$sheet->setCellValueByColumnAndRow(12, $wGyo1,$wsiki);
	} 
*/	

	//ダウンロード用
	//MIMEタイプ：https://technet.microsoft.com/ja-jp/ee309278.aspx
	header("Content-Description: File Transfer");
	$wFileName = "理事会報告_".$BukkenName.".xlsx" ;
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
