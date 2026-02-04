<?php
#外部オーナー用案内Excel出力

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

	include_once _CLS_DIR . "SPUSFile.cls";

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

	$loginUserCD = $myUser->UserCD;
	$ID = $myUser->ID;

	unset($myUser);

	$TantoTEL = getTantoData($myDB, $loginUserCD )['TEL'];


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
		$KanriGaisyaTEL =  $myBukken->KanriGaisyaTEL;
		$BukkenName = $myBukken->BukkenName;

	}

	########################################################
	# 工事情報抽出
	########################################################
	#OPありなし取得　　

	$myKoji = new Koji($myDB);

	if (!$myKoji->executeSelect("BukkenCD = " . $editBukkenCD . " AND MukouFlg = FALSE", "")){
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

		if( $myKoji->OP1DeviceCD ) $IfOP = TRUE ;
		$KojiShozokuName = $myKoji->KojiShozokuName; 
		$KojiTantoName = $myKoji->KojiTantoName; 
		$KojiName = $myKoji->KojiName; 
		$GyosyaTantoCD1 = $myKoji->GyosyaTantoCD1; 
		$GyosyaData = getGyosyaData($myDB, $GyosyaTantoCD1 );
		$Shiharai = $myKoji->Shiharai; 
		$wShiharai = SPFWTools::decodePluralValue($Shiharai);##配列
		$GyosyaName = $GyosyaData['GyosyaName'];
		$GyosyaTantoName = $GyosyaData['GyosyaTantoName'];
		$GyosyaTantoTEL = $GyosyaData['GyosyaTantoTEL'];

		$SekoShutai = $myKoji->SekoShutai; //201905 ADD

		#資料下部のお問い合わせ先に表示する情報を取得
		$TantoTEL = $myKoji->KojiShozokuTEL;

		#|管理会社|1:電話番号表示する 0:しない|アイホン|1:電話番号表示する0:しない|その他会社名|1:電話番号表示する0:しない|電話番号|
		if(!empty($myKoji->ToiawasewakiDisp)){
			$wToiawasewakiDispArr = SPFWTools::decodePluralValue($myKoji->ToiawasewakiDisp); // 文字列を配列に変換
		}else{
			$wToiawasewakiDispArr = null;
		}

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

	}



	########################################################
	# Excelファイル生成
	########################################################
	require 'vendor/autoload.php';

	use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;
	$reader = new XlsxReader();

	$spreadsheet = $reader->load('./template/gaibuowner.xlsx'); //template.xlsx 読込

	$sheet = $spreadsheet->getActiveSheet();

	$sheet->setCellValue('AM1', date('Y年n月').'吉日');
	$sheet->setCellValue('B2', $BukkenName.' 外部オーナー様へ');
	$sheet->setCellValue('B6', $KojiName.'実施のお知らせ');

	$sheet->setCellValue('C9', 'この度、ご所有の物件の'.$KojiName.'を実施させていただく');


	if( $IfOP ){#オプションあり
		#テンプレに直書き$sheet->setCellValue('B21', "●"."オプション工事をご希望される"."オーナー様へ" );
		#$sheet->getStyle('B21')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);

		$jyogenFlg = false;
		$haraikomihyoFlg = false;
		$seikyusyoFlg = false;
		
		$shiharaiNum = 0;
		if(in_array('コンビニ',$wShiharai)){
			$shiharaiNum += 1;
			$haraikomihyoFlg = true;
			#$Syorui = '払込票';
			$sheet->setCellValue('D26', "コンビニエンスストアにて払込票をご提示してのお支払い" );
			if (in_array('上限あり',$wShiharai)){
				$jyogenFlg = true;
				$sheet->setCellValue('B32', " ※54,000円(税込)以上のお支払の場合、銀行振り込みのみとなります。" );
			}else{
				$sheet->getRowDimension('32')->setVisible(false);			
			}
		}else{
			$sheet->getRowDimension('26')->setVisible(false);
			$sheet->getRowDimension('32')->setVisible(false);
		}
		
		if(in_array('NP',$wShiharai)){
			$shiharaiNum += 1;
			$seikyusyoFlg = true;
			#$Syorui = 'ご請求書';
				$sheet->setCellValue('D27', "コンビニ・郵便局・銀行にてお支払い" );
		}else{
			$sheet->getRowDimension('27')->setVisible(false);
		}
		
		if(in_array("振込",$wShiharai) || $jyogenFlg == true 
			|| ($haraikomihyoFlg == false && $seikyusyoFlg == false) ){
			$shiharaiNum += 1;
			$seikyusyoFlg = true;
			$sheet->setCellValue('D28', "ご請求書記載の口座までお振込み" );
		}else{
			$sheet->getRowDimension('28')->setVisible(false);
		}
		
		if($haraikomihyoFlg && $seikyusyoFlg){
			$Syorui = '払込票 又は ご請求書';
		}elseif($haraikomihyoFlg){
			$Syorui = '払込票';
		}elseif($seikyusyoFlg){
			$Syorui = 'ご請求書';
		}

		if($shiharaiNum > 1){
			$shiharaiAddStr = "いずれか";
		}else{
			$shiharaiAddStr = "";
		}
		$sheet->setCellValue('B22', " ・『オプション機器のご案内』をご確認下さい。" );

		$sheet->setCellValue('B23', " ・オプション販売は、".$GyosyaName."（アイホン㈱代理店）にて行わせて頂きます。");
		$sheet->setCellValue('B24', " ・オプション工事の代金は、".$GyosyaName."より".$Syorui."を発行させて頂きます。");

		$sheet->setCellValue('B25', "　お手数ですが、下記".$shiharaiAddStr."の方法にて代金のお支払いをお願いいたします。" );
		$sheet->setCellValue('B29', " ※お申込みの際、".$Syorui."送付先をお伝え下さい。");
		$sheet->setCellValue('B30', " ※オプション代金・振込み手数料は、希望される区分所有者様の個人負担となります。");
		if(in_array('現金',$wShiharai)){
			$sheet->setCellValue('B31', " （専有部工事にお立会い頂ける場合は、工事当日に現金でのお支払いも可能です）");
		}else{
			$sheet->getRowDimension('31')->setVisible(false);
		}

	}else{#オプションなし オプション関連の表示行を削除
		$rowsForInvisible = array(); //非表示対象の行リスト	空の配列として定義
		$rowsForInvisible = [21,22,23,24,25,26,27,28,29,30,31,32];
		#不要行を非表示に
		for($tmp_i=0 ; $tmp_i<count($rowsForInvisible) ; $tmp_i++){
			$sheet->getRowDimension($rowsForInvisible[$tmp_i])->setVisible(false);
		}
	}
/*	
	$sheet->setCellValue('B35', "■アイホン株式会社　".$KojiShozokuName );
	$sheet->setCellValue('B36', "（".$GyosyaName."　協力業者）" );
	$sheet->setCellValue('B37', "担当：".$KojiTantoName."　電話：".$TantoTEL."（9時～17時30分　土日祝休み）" );
*/

	#右上に工事案内作成画面にて登録された会社名を表示
	#◆右上に表示する会社名
	$sheet->setCellValue('AM3', $DispCompanyTop1);
	$sheet->setCellValue('AM4', $DispCompanyTop2);
	$sheet->setCellValue('AM5', $DispCompanyTop3);

	#工事案内作成画面にて、お問い合わせの登録があった場合のみ

/*	if($wToiawasewakiDispArr != null){
		#管理会社
		if ($wToiawasewakiDispArr[0] == "管理会社"){
			#TEL表示？
			if ($wToiawasewakiDispArr[1] == "1"){
				$sheet->setCellValue('D37', $KanriGaisya."　電話：".$KanriGaisyaTEL);
			}else{
				$sheet->setCellValue('D37', $KanriGaisya);
			}
		}else{#値がない場合は表示個所を非表示に
			$sheet->getRowDimension('36')->setVisible(false);
			$sheet->getRowDimension('37')->setVisible(false);
		}
		#その他会社
		if ($wToiawasewakiDispArr[4] != ""){	#化社名の登録があれば表示
			$sheet->setCellValue('C38', "◆連絡先会社　");
			if ($wToiawasewakiDispArr[5] == "1"){
				$sheet->setCellValue('D39', $wToiawasewakiDispArr[4]."　電話：".$wToiawasewakiDispArr[6]);
			}else{
				$sheet->setCellValue('D39', $wToiawasewakiDispArr[4]);
			}
		}else{	#なければ非表示
			$sheet->getRowDimension('38')->setVisible(false);
			$sheet->getRowDimension('39')->setVisible(false);
		}

		if ($wToiawasewakiDispArr[2] == "アイホン"){
			if ($wToiawasewakiDispArr[3] == "1"){
				$sheet->setCellValue('D41',  "担当：".$KojiTantoName."　電話：".$TantoTEL."（9時～17時30分　土日祝休み）" );
			}else{
				$sheet->setCellValue('D41', $KojiTantoName);			
			}

		}else{
			$sheet->getRowDimension('40')->setVisible(false);
			$sheet->getRowDimension('41')->setVisible(false);
		}

	}*/

	#お問い合わせ先に出力 201905 変更
	#施工主体がアイホンの場合（元請け）①アイホン
	if ($SekoShutai == 'アイホン株式会社'){
		$sheet->setCellValue('M38', $KojiShozokuName );
		$sheet->setCellValue('D39',  "担当：".$KojiTantoName."　電話：".$TantoTEL."（9時～17時30分　土日祝休み）" );
		
		#不要行を削除
		$sheet->getRowDimension('36')->setVisible(false);
		$sheet->getRowDimension('37')->setVisible(false);

	}else{	#①管理会社	②アイホン
		$sheet->setCellValue('D37',  $KanriGaisya."　電話：".$KanriGaisyaTEL);

		$sheet->setCellValue('M38', $KojiShozokuName );
		$sheet->setCellValue('D39',  "担当：".$KojiTantoName."　電話：".$TantoTEL."（9時～17時30分　土日祝休み）" );
		
	}
	
	###★ Excel(.xlsx)としてtFileFに登録する
	$writer = new XlsxWriter($spreadsheet);
	$Date = date('YmdHis');
	$writer->save( './tmp/g'.$Date.'.xlsx');

	// 画像の取得
	$image_path = './tmp/g'.$Date.'.xlsx' ;
	$img_file = file_get_contents( $image_path );

	//画像を保存するSQL文の実行
	$myFile = new File($myDB);

	$myFile->FileCD = -1;
	$myFile->BukkenCD = $editBukkenCD;
	$myFile->SekoStatus = 5;                     #5作成ファイル
	$myFile->P001 = '外部オーナー案内_'.date('Ymd_His').'.xlsx';             #ファイル名
	$myFile->P002 = 'xlsx';  #拡張子

	$myFile->File = $img_file;
	#		$myFile->Memo = $Memo;
	$myFile->Creator = $loginUserCD;
	$myFile->Updater = $loginUserCD;

	if (!$myFile->executeUpdate()){
		$ErrorString = array();
		$ErrorString[] = "メニューマスタ情報の更新に失敗しました。";
		showAdminSorryPage($ErrorString);
	}
	#テンポラリーファイルを削除する。
	$Command = "rm -f ".$image_path;
	shell_exec($Command);
	###★ Excel(.xlsx)としてtFileFに登録する End

	//ダウンロード用
	//MIMEタイプ：https://technet.microsoft.com/ja-jp/ee309278.aspx
	header("Content-Description: File Transfer");
	$wFileName = "外部オーナー案内_".$BukkenName.".xlsx" ;
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
