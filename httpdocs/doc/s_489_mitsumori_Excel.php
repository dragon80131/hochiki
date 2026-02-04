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

	include_once _CLS_DIR . "SPUSKoji.cls";
	include_once _CLS_DIR . "SPUSBukken.cls";
	include_once _CLS_DIR . "SPUSTanto.cls";
	include_once _CLS_DIR . "SPUSSiten.cls";
	include_once _CLS_DIR . "SPUSIraiRenkei.cls";
	include_once _CLS_DIR . "SPUSGyosya.cls";

	// データベースコネクト
	$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
	if (!$myDB->Connection)
		trigger_error("SPFWDatabase Failed.", E_USER_ERROR);

	$rKey = SPFWParameter::getValues('rKey');
	$editBukkenCD = SPFWParameter::getValues('editBukkenCD');

	foreach($_POST as $key => $value){
		${"$key"} = SPFWParameter::getValues($key);
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
	$wUserCD = $myUser->UserCD;
	$LastName = $myUser->LastName;
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
	$editBukkenCD = SPFWParameter::getValues('editBukkenCD');

	$myBukken = new Bukken($myDB);

	if (!$myBukken->executeSelect("BukkenCD = " . $editBukkenCD . " AND MukouFlg = FALSE", "")){
		trigger_error("Getting Bukken Failed.", E_USER_ERROR);
	}

	if ($myBukken->RecCnt != 1) {
		trigger_error("Getting myBukken List Failed.", E_USER_ERROR);
	}

	$wBukkenName = $myBukken->BukkenName;
	$wTantoCD = $myBukken->TantoCD;
	$wShozokuCD = $myBukken->ShozokuCD;
	$wAddress = $myBukken->Address;
	$wBunjyo = $myBukken->Bunjyo;
	$wKosu = $myBukken->Kosu;
	$wGyosyaCD = $myBukken->GyosyaCD;

	$myGyosya = new Gyosya($myDB);
	if (!$myGyosya->executeSelect("GyosyaCD = " . $wGyosyaCD . " AND MukouFlg = FALSE", "")){
		trigger_error("Getting Gyosya Failed.", E_USER_ERROR);
	}
	if ($myGyosya->RecCnt != 1) {
		trigger_error("Getting myBukken List Failed.", E_USER_ERROR);
	}
	$wGyosyaName = $myGyosya->GyosyaName;


	#20170711add
	$KenmeiNo = $myBukken->KenmeiNo;
	$KenmeiNo = trim($KenmeiNo); // 空白除去

	unset($myBukken);


	$myKoji =new Koji($myDB);

	if(!$myKoji->executeSelect("BukkenCD = '".$editBukkenCD."'","")){
		trigger_error("Getting Koji Failed.", E_USER_ERROR);
	}

	$KojiStartDate = $myKoji->KyoyoStartDate;
	$KojiEndDate = $myKoji->SenyuEndDate;
	$wRNNyukan = $myKoji->RNNyukan;
	$wRNNyukan = SPFWTools::decodePluralValue($wRNNyukan);#パイプつなぎを配列に変換

	$KojiStartYear = substr($KojiStartDate,'0','4');
	$KojiEndYear = substr($KojiEndDate,'0','4');
	$KojiStartMonth = substr($KojiStartDate,'5','2');
	$KojiEndMonth = substr($KojiEndDate,'5','2');
	$KojiStartDay = substr($KojiStartDate,'8','2');
	$KojiEndDay = substr($KojiEndDate,'8','2');


	if($KojiEndMonth< $KojiMonth){
		$KojiMonth = $KojiEndMonth + (12-$KojiStartMonth);
	}else{
		$KojiMonth = $KojiEndMonth-$KojiStartMonth;
	}
	if($KojiStartDay < $KojiEndDay){
		$KojiMonth +=1;
	}
	########################################################
	# 見積No発行
	########################################################
	$myListObject = new SPFWListObject($myDB);

	$sql = "SELECT ";
	$sql .= "MAX(MitumorishoNo) ";

	$myListObject->SelectSQL = $sql;
	$sql = " FROM tKojiF ";

	$myListObject->Condition = $sql;
	$myListObject->Order = "";#表示順
	$myListObject->Limit = "allpage";

	if (!($myListObject->GetList(1)))
		trigger_error("Getting User List Failed.", E_USER_ERROR);

	if($myListObject->Rows ==1){
		$MitumoriNo = $myListObject->GetValue(0,0);
	}
	if(!$MitumoriNo){
		$MitumoriNo = "a".substr(date("Ym"),2)."001";

	}else{
		if(substr($MitumoriNo,1,4)==substr(date("Ym"),2)){

			$MitumoriNo = substr($MitumoriNo,0,5).sprintf('%03d',((int)substr($MitumoriNo,-3)+1));
		}else{
			$MitumoriNo = "a".substr(date("Ym"),2)."001";
		}
	}

	$myKoji = new Koji($myDB);

	if (!$myKoji->executeSelect("BukkenCD = " . $editBukkenCD . " AND MukouFlg = FALSE", "")){
		trigger_error("Getting Koji Failed.", E_USER_ERROR);
	}

	$Tag=false;
	for($i=1;$i<=10;$i++){

		${"wOP".$i."DeviceCD"} = $myKoji->{"OP".$i."DeviceCD"};
		if(${"wOP".$i."DeviceCD"}==84 || ${"wOP".$i."DeviceCD"}==236 || ${"wOP".$i."DeviceCD"}==235
		 || ${"wOP".$i."DeviceCD"}==29 || ${"wOP".$i."DeviceCD"}==174){#オプションにタグが入っている場合
			$Tag=true;
		}

	}

	if($myKoji->MitumorishoNo){
		$MitumoriNo = $myKoji->MitumorishoNo;
	}else{
		$myKoji->MitumorishoNo = $MitumoriNo;
	}

	//excel出力される見積書NO
	$MitumoriNo = $editBukkenCD.substr(date("Ym"),2);


	if(isset($YoteiPage))	$myKoji->YoteiPage = $YoteiPage;
	if(isset($YobiSuu))		$myKoji->YobiSuu = $YobiSuu;
	if(isset($KeijiSuu))	$myKoji->KeijiSuu = $KeijiSuu;

	if (!$myKoji->executeUpdate())
		trigger_error("Update Koji Failed.", E_USER_ERROR);

	########################################################
	# パラメータ取得
	########################################################

	//専有部工事日時変更受付方法
	if($Tag){	#リニューアル後の入館方法にタグがある場合

		if($wAnswer == "A.日時変更住戸のみ返答"){
			$UketukeTanka = "500";
		}elseif($wAnswer == "B.全住戸返答"){
			$UketukeTanka = "700";
		}elseif($wAnswer == "C.全住戸返答+確定時未返事シート"){
			$UketukeTanka = "800";
		}

	}else{

		if($wAnswer == "A.日時変更住戸のみ返答"){
			$UketukeTanka = "400";
		}elseif($wAnswer == "B.全住戸返答"){
			$UketukeTanka = "600";
		}elseif($wAnswer == "C.全住戸返答+確定時未返事シート"){
			$UketukeTanka = "700";
		}
	}
	$UketukeKingaku = number_format ($UketukeTanka * $wKosu);
	$BikouRow = 4;
	$SubTotal = ($UketukeTanka * $wKosu)+12000;
	//ポスティング
	// if($wPostingFlg != 0){
	// 	if($wPostingFlg == 3){#予定案内選択時
	// 		$BikouRow += 7;
	// 		$SubTotal += 8000+($wKosu*20)+10000;
	// 		// if($wPostingBasho == 2){#ドア前ポスティングのとき
	// 		// 	$BikouRow += 1;
	// 		// 	$SubTotal += 2000;
	// 		// 	if($wKosu >100){
	// 		// 		$BikouRow += 1;
	// 		// 		$SubTotal += ($wKosu-100)*20;
	// 		// 	}
	// 		// }
	// 	}

	// 	if($wPostingFlg == 1){#決定案内選択時
	// 		$BikouRow += 4;
	// 		$SubTotal += 8000+($wKosu*20)+10000;
	// 		// if($wPostingBasho == 2){#ドア前ポスティングのとき
	// 		// 	$BikouRow += 1;
	// 		// 	$SubTotal += 2000;
	// 		// 	if($wKosu >100){
	// 		// 		$BikouRow += 1;
	// 		// 		$SubTotal += ($wKosu-100)*20;
	// 		// 	}
	// 		// }
	// 	}

	// 	if($wPostingFlg == 2){

	// 	}
	// }


	//工事写真アプリ
	if($wPicStatus == 1){

		if($wKosu <= 30){
			$picprice = 7500;
		}elseif($wKosu <= 100){
			$picprice = 15000;
		}elseif($wKosu >= 100){
			$picprice = 15000;
			$picprice += ($wKosu-100)*100;
		}
		else{

		}

		$BikouRow += 2;
		$SubTotal += 5000+($wKosu*100);

		$SubTotal += $picprice;
		$SubTotaltpl += $picprice;
	}

	if($wKakuninFlg == 1){
		$BikouRow += 2;
		$SubTotal += 2000;
		$SubTotaltpl += 2000;
	}

	$wSubTotal = "\\".number_format($SubTotal);
	if(date('Ymd')>=20191001){
		$TAX=$SubTotal * 0.1;
	}else{
		$TAX=$SubTotal * 0.08;
	}
	$wTAX = "\\".number_format($TAX);
	$Total = $SubTotal + $TAX;
	$wTotal = "\\".number_format($Total);

	########################################################
	# Excelファイル生成
	########################################################
	require 'vendor/autoload.php';

	use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;
	$reader = new XlsxReader();

	$spreadsheet = $reader->load('./template/489mitsumori.xlsx'); //template.xlsx 読込

	$sheet = $spreadsheet->getActiveSheet();

	$sheet->setCellValue('A4', $wGyosyaName."\t御中");//業者nameが切れる
	$sheet->setCellValue('K3', "見積書No:".$MitumoriNo);
	$sheet->setCellValue('K4', date('Y年n月j日'));
	$sheet->setCellValue('B21', $wBukkenName);
	$sheet->setCellValue('E23', $wKosu);
	$sheet->setCellValue('I23', $UketukeTanka);
	
	if($wPostingFlg != 0){

		if($wPostingFlg == 3){#予定案内選択時

			$sheet->setCellValue('E25', 1);
			$sheet->setCellValue('E26', $wKosu);
			if($YoteiPage){

				$sheet->setCellValue('B26', "A4カラー印刷　@20×".$YoteiPage."枚");
				$sheet->setCellValue('I26', ($YoteiPage * 20));
				$sheet->setCellValue('B27', "予備（A4カラー印刷）　@20×".$YoteiPage."枚");
				$sheet->setCellValue('I27', ($YoteiPage * 20));
				if($YobiSuu){
					$sheet->setCellValue('E27',$YobiSuu);
				}
				if($KeijiSuu){
					$sheet->setCellValue('E28',$KeijiSuu);
				}


			}

			$sheet->setCellValue('E29', $wKosu);
			$sheet->setCellValue('E30', 1);

			if($wKosu > 100){
				$sheet->setCellValue('E32', ($wKosu-100));
				$sheet->getRowDimension('31')->setVisible(false);
				$sheet->setCellValue('E31', 0);

			}else{
				$sheet->getRowDimension('32')->setVisible(false);
				$sheet->setCellValue('E31', 0);
				$sheet->getRowDimension('31')->setVisible(false);
				$sheet->getRowDimension('32')->setVisible(false);
			}
			
		}else{
			for($i=24 ; $i<33 ; $i++)
				$sheet->getRowDimension($i)->setVisible(false);
		}

		if($wPostingFlg == 1){#決定案内選択時
			$sheet->setCellValue('E34', 1);
			$sheet->setCellValue('E35', $wKosu);
			$sheet->setCellValue('I35', 20);
			$sheet->setCellValue('E36', 1);
			
			if($wKosu > 100){
				$sheet->setCellValue('E38', ($wKosu-100));
				$sheet->setCellValue('E37', 0);
				$sheet->getRowDimension('37')->setVisible(false);
			}else{
				$sheet->setCellValue('E37', 0);
				$sheet->getRowDimension('37')->setVisible(false);
				$sheet->getRowDimension('38')->setVisible(false);
			}
				
		}else{
			for($i=33 ; $i<39 ; $i++)
				$sheet->getRowDimension($i)->setVisible(false);
		}

	
		if($wPostingFlg == 2){//予定案内・決定案内両方
			//予定部分
			$sheet->setCellValue('E25', 1);
			$sheet->setCellValue('E26', $wKosu);
			if($YoteiPage){

				$sheet->setCellValue('B26', "A4カラー印刷　@20×".$YoteiPage."枚");
				$sheet->setCellValue('I26', ($YoteiPage * 20));
				$sheet->setCellValue('B27', "予備（A4カラー印刷）　@20×".$YoteiPage."枚");
				$sheet->setCellValue('I27', ($YoteiPage * 20));
				if($YobiSuu){
					$sheet->setCellValue('E27',$YobiSuu);
				}
				if($KeijiSuu){
					$sheet->setCellValue('E28',$KeijiSuu);
				}
			}

			$sheet->setCellValue('E30', 1);
			$sheet->setCellValue('E29', $wKosu);
			$sheet->setCellValue('E31', 1);

			if($wKosu > 100){
				$sheet->setCellValue('E32', ($wKosu-100));
				$sheet->setCellValue('E31', 0);
			}else{
				$sheet->getRowDimension('32')->setVisible(false);
				$sheet->setCellValue('E31', 0);
				$sheet->getRowDimension('32')->setVisible(false);
			}
		
			$sheet->setCellValue('E34', 1);
			$sheet->setCellValue('E35', $wKosu);
			$sheet->setCellValue('I35', 20);
			$sheet->setCellValue('E36', 1);
	
			if($wKosu > 100){
				$sheet->setCellValue('E38', ($wKosu-100));
				$sheet->setCellValue('E37', 0);

			}else{
				$sheet->getRowDimension('38')->setVisible(false);
				$sheet->setCellValue('E37', 0);
				$sheet->getRowDimension('38')->setVisible(false);
			}
			
			
			for($i=24 ; $i<39 ; $i++){
				$sheet->getRowDimension($i)->setVisible(true);
			}
			//ドア前ポスティングの分を消す
			$sheet->getRowDimension('31')->setVisible(false);
			$sheet->getRowDimension('37')->setVisible(false);
			
			//戸数が100個以下であれば項目見えないようにする。
			if($wKosu < 100){
				$sheet->getRowDimension('32')->setVisible(false);
				$sheet->getRowDimension('38')->setVisible(false);
			}


		}

		
	}else{//ポスティングない場合
		for($i=24 ; $i<39 ; $i++)
			$sheet->getRowDimension($i)->setVisible(false);
	}

	//工事写真アプリ利用有無
	if($wPicStatus == 1){
		$sheet->setCellValue('E40', 1);
		$sheet->getRowDimension(41)->setVisible(false);
	}else{
		for($i=39 ; $i<42 ; $i++)
			$sheet->getRowDimension($i)->setVisible(false);
	}

	//工事完了確認書アプリ利用有無
	if($wKakuninFlg == 1){
		$sheet->setCellValue('E43', 1);
	}else{
		for($i=42 ; $i<45 ; $i++)
			$sheet->getRowDimension($i)->setVisible(false);
	}

	$sheet->setCellValue('K47', '=ROUNDDOWN(K46*0.1,0)');
	$sheet->setCellValue('B52', $wAnswer." 単価\\".$UketukeTanka);

	// if($wPicStatus == 1){
	// 	$sheet->setCellValue('B53', "工事写真アプリ利用");
	// }else{
	// 	$sheet->setCellValue('B53', "工事写真アプリ利用なし");
	// }

	// if($wPostingFlg == 1){
	// 	$sheet->setCellValue('B56', "ポスティング資料発送後のキャンセル、変更は別途料金が発生いたします。");
	// }


	//ダウンロード用
	//MIMEタイプ：https://technet.microsoft.com/ja-jp/ee309278.aspx
	header("Content-Description: File Transfer");
	$wFileName = "見積書_".$wBukkenName.".xlsx" ;
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
