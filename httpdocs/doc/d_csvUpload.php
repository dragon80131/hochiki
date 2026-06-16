<?php

	include_once "C:/xampp/htdocs/hochiki/SPFW/inc/setting.properties";
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

	include_once _CLS_DIR . "SPUSBukkenMatrix.cls";
	include_once _CLS_DIR . "SPFWParameter.cls";



	// データベースコネクト
	$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
	if (!$myDB->Connection)
		trigger_error("SPFWDatabase Failed.", E_USER_ERROR);

	########################################################
	# ターゲットクライアント確定
	########################################################
	#$TargetClientCD = $editClientCD;

	$rKey = SPFWParameter::getValues('rKey');


	$editBukkenCD = SPFWParameter::getValues('editBukkenCD');

	// サンプルファイル
	$samplefile = "./template/nittei.xlsx";
	#$samplefile = str_replace(_DOCUMENT_ROOT,_ROOT_URL._PART_DIR,$samplefile);

	########################################################
	# 設定確認
	########################################################
	$myBukken = new Bukken($myDB);

	if (!$myBukken->executeSelect("BukkenCD = " . $editBukkenCD . " AND MukouFlg = FALSE", "")){
		$ErrorString = array();
		$ErrorString[] = "tSettingM情報の抽出に失敗しました。";
		showAdminSorryPage($ErrorString);
	}

	$SenyuStartDate = $myBukken->SenyuStartDate;
	$SenyuEndDate = $myBukken->SenyuEndDate;
	unset ($myBukken);

	/* 枠数チェックに使いたかったが、あとまわし。
	########################################################
	# メニュー抽出
	########################################################

	$myListObject = new SPFWListObject($myDB);

	$sql = "SELECT ";
	$sql .= "MenuCD, ";
	$sql .= "MinuteType ";
	$myListObject->SelectSQL = $sql;
	$sql = " FROM tMenuM";
	$sql .= " WHERE MenuCD > 0 AND MukouFlg = FALSE";
	for ($i = 0; $i < count($whereSQL); $i++)
		$sql .= " AND " . $whereSQL[$i];
	if ($IfASP) {
		if ($IfAdminSystem)
			$sql .= " AND ClientCD = " . $TargetClientCD;
		if ($IfAdminClient)
			$sql .= " AND ClientCD = " . $TargetClientCD;
	}
	$myListObject->Condition = $sql;
	$myListObject->Order = "MenuCD";
	$myListObject->Limit = "allpage";

	if (!($myListObject->GetList(1)))
		trigger_error("Getting Menu List Failed.", E_USER_ERROR);

	$MenuLoop = $myListObject->Rows;
	if( $MenuLoop > 1){
		$IfMultiMenu = "工事が複数種類ある物件です。工事枠数が1日の可能工事数を超えていないか確認おねがいします。";
	}
	for ($i = 0; $i < $MenuLoop; $i++) {
		$MenuCD[$i] = $myListObject->GetValue($i, 0);
		$MinuteType[$i] = $myListObject->GetValue($i, 1);#
	}

	#何枠あるかどうやってわかる？
	#　ETime-STime　/　MenuのMinuteType×単位時間　$MINUTEUNIT

		for( $i=0; $i< count( $WAKUPATTERN[$WakuPattern]['AMPM']); $i++ ){
	#		$ETime_STime[$i] = strtotime( $WAKUPATTERN[[$WakuPattern]['EndTime'][$i]) - strtotime( $WAKUPATTERN[$WakuPattern]['StartTime'][$i] ) ;#開始時間―終了時間（秒）

			$ETime_STime[$i] = strtotime( $WAKUPATTERN[$WakuPattern]['EndTime'][$i] ) - strtotime( $WAKUPATTERN[$WakuPattern]['StartTime'][$i] ) ;#開始時間―終了時間（秒）


			$WakuSuuPerTime[$i] = ( $ETime_STime[$i] / 60 ) / ( $MinuteType[0] * $MinuteUnit );
	#		echo "<br>101行目:".$WakuSuuPerTime[$i];#１とか２
		}
		#枠パターンに時間表記補正
		#$WAKUPATTERN[3]['Name'] = "3枠(9:00-12:00,13:00-15:00,15:00-17:00)";
		#$WAKUPATTERN[3]['StartTime'][0] = "09:00";
		#$WAKUPATTERN[3]['EndTime'][0] = "12:00";
		#$WAKUPATTERN[3]['AMPM'][0] = "AM";
		#$WAKUPATTERN[3]['StartTime'][1] = "13:00";
		#$WAKUPATTERN[3]['EndTime'][1] = "15:00";
		#$WAKUPATTERN[3]['AMPM'][1] = "PM1";
		#$WAKUPATTERN[3]['StartTime'][2] = "15:00";
		#$WAKUPATTERN[3]['EndTime'][2] = "17:00";
		#$WAKUPATTERN[3]['AMPM'][2] = "PM2";
	*/


	########################################################
	# パラメータチェック/値加工
	########################################################

	if ($IfASP) {
		$ErrorString = array();
		if ($TargetClientCD == "" || !SPFWInputCheck::isNumeric($TargetClientCD))
			$ErrorString[] = "正規の手順を踏んでアクセスされていないようです。";
		if (count($ErrorString) > 0){
			$ErrorLoop = count($ErrorString);
			$myTemplate = new SPFWTemplate(_ADMIN_ERROR_TPL, $MyCarrier, TRUE);
			unset($myTemplate);
			exit;
		}
	}

	$IfErrmisFile = "";
	$IfErrmisFile2 = "";

	$IfErrNoUP  = "";
	$IfErrNoFile = "";

	$IfErrNoUP2  = "";
	$IfErrNoFile2 = "";
	$IfUp = "TRUE";
	$IfDb = "";

	####20120529 CVS UPLOAD
	if (is_uploaded_file($_FILES["upfile"]["tmp_name"])) {

		$filePath = "./upload/" .date('Ymdhi'). $_FILES["upfile"]["name"];

		if (move_uploaded_file($_FILES["upfile"]["tmp_name"], $filePath )) {
			chmod($filePath, 0644);
			$UpFile = $filePath ;
			$Extension = pathinfo($UpFile, PATHINFO_EXTENSION);#拡張子

			if ($Extension == "xlsx" ){
				$IfDb = "TRUE";
				$IfUp = "";
			}else{
				$IfErrmisFile = "TRUE";
			}

		} else {
			#echo "ファイルをアップロードできません。";
			$IfErrNoUP = "True";
		}
	} else {
		#echo "ファイルが選択されていません。";
		$IfErrNoFile = "True";
	}


	// ファイル名の指定
	#$readFile =  dirname(__FILE__) ."/file/".$UpFile;

	// 連想配列でデータ受け取り
	#$data = readXlsx($readFile);

	// 出力確認
	#print '<pre>';
	#var_dump($data);
	#print '</pre>';

if( $IfDb ){#ファイルがアップされていたら

	########################################################
	# EXCEL処理
	########################################################

	// テンプレート読み込み
	$template_filepath = $UpFile;

	// ライブラリ読み込み
	require_once './Classes/PHPExcel.php';
	require_once './Classes/PHPExcel/IOFactory.php';

	// PHPExelオブジェクトの作成
	$objPHPExcel = new PHPExcel();

	// キャッシュ方法を一時ファイルに保存する
	$cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_discISAM;

	// 一時ファイルの場所を指定
	$cacheSettings = array('dir' => '/tmp');
	// 設定を反映
	if (!PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings)) {
		$objPHPExcel->disconnectWorksheets();
		unset($objPHPExcel);
		die ('Can not set PHPExcel cache storage setting');
	}

	$objReader = PHPExcel_IOFactory::createReader('Excel2007');
	// テンプレート読み込み
	$objPHPExcel = $objReader->load( $template_filepath );

	// 1枚目のシートを選択
	$sheet = $objPHPExcel->getActiveSheet(0);

	$ErrorString = array();
	$i = 0;
	foreach ($sheet->getRowIterator() as $row){//行ごとに回る

		$k = 0;
		$j = 0; #★
		foreach ($row->getCellIterator() as $cell){#ここでセルの値を$cellでとってくるみたい。1行の中の部屋番号、工事日、開始時間分回る

		    // 各セルの値を取得
			$CellValue =  $cell->getValue();#とってきたときのセルの値
			if( $CellValue ){

				if( $k == 0 ){		#部屋番号★
					$UserCD[$j] = $CellValue;
					$Reserve['UserCD'][$i] = $CellValue;#部屋ダブりチェックで配列にいれておく。

				}elseif( $k == 1 ){	#工事日★

						$CellValue = date('Y/m/d', ($CellValue - 25569) * 86400 );#86400秒＝1日
						#$TimeFromDate[$j] = $CellValue;
						#echo  "<font color=white>★". $CellValue." ".$SenyuStartDate." ".$SenyuEndDate."</font>";
						if( strtotime( $SenyuStartDate ) > strtotime( $CellValue ) or strtotime( $SenyuEndDate ) < strtotime( $CellValue ) ){
							$IfError = TRUE ;
							$ErrorString[] = "工事日が専有部工事期間内ではありません。".$UserCD[$j];
							$IfDb = "";#次のボタンなし
							$IfUp = TRUE ;
							#break 2;
						}

						$Reserve['Date'][$i] = $CellValue;
						#echo " Date:".$Reserve['Date'][$i];


				}elseif( $k == 2 ) { #開始時間★

					$wCellValue = round($CellValue *24,2);#9.5 9:30にする。
					$CellValueHour	= floor($wCellValue);
					$CellValueMinute	=$wCellValue-$CellValueHour;
					$CellValueHour		=sprintf('%02d',$CellValueHour );
					$CellValueMinute	=sprintf('%02d',$CellValueMinute *60 );
					#$CellValueMinute	= $CellValueMinute *60;
					$TimeFromTime = $CellValueHour.":".$CellValueMinute;
					$Reserve['Time'][$i] = $TimeFromTime ;
				#	echo " cell:".$wCellValue."-".$CellValueHour;
				#	echo " Time:".$Reserve['Time'][$i];

				}
				$k++;#★
			}
		}

		#echo " <br>268行目:";
		$wUserCD[$i] = $Reserve['UserCD'][$i];

		$wTimeFromDate[$i] = $Reserve['Date'][$i];
		$wTimeFromTime[$i] = $Reserve['Time'][$i];
		#1つの変数にいれる。8月27日　09:00　がはいる。
		$ReserveStatus[$wTimeFromTime[$i]][$wTimeFromDate[$i]][] = 1;

		$i++;
		$j++;

	}
	$CellLoop = $i;


	#部屋のダブり
	#UserCDの配列のユニークな要素数と部屋のループ数を比較

	if( count($wUserCD ) <> $CellLoop ){
		$IfError = TRUE ;
		$ErrorString[] = "部屋番号にダブりがあるようです。";
		$IfDb = "";#次のボタンなし
		$IfUp = TRUE ;
	}


	#各時間の枠数をこえていないかチェック
	#AMの枠数 AM6 PM10　array_count_values( 配列 )
	#DateとTimeでまわす。否　Count
	#$ReserveStatus[$Reserve['Date']][$Reserve['Time']]
	#うまくいかず　さきにすすむこととした。


	#専有部工期期間内か

	if( $IfError ){
		for( $i=0; $i<count($ErrorString); $i++){
			$ErrorStringAll = $ErrorStringAll.$ErrorString[$i]."<br>";
		}
	}
}#ファイルがアップされていたら END


	########################################################
	# コンテンツ表示
	########################################################

	SPFWTemplate::setValue("work");
	SPFWTemplate::setValue("editClientCD", $TargetClientCD);
	$HiddenValues = SPFWTemplate::getValuesToPass();

	$CNT_FILE = "d_csvUpload.tpl";
	$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier, TRUE);
	unset($myTemplate);

	unset($myTemplate);
	unset($myLog);
	unset($myDB);
?>
