<?php

include_once "C:/xampp/htdocs/hochiki/SPFW/inc/setting.properties";
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
include_once _CLS_DIR . "SPUSKoji.cls";
include_once "./include/common_489.php";

$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
if (!$myDB->Connection)
	trigger_error("SPFWDatabase Failed.", E_USER_ERROR);

########################################################
# 値取得
########################################################
$rKey 			= SPFWParameter::getValues("rKey");
$editBukkenCD 	= SPFWParameter::getValues('editBukkenCD');

$date = date("YmdHis");

########################################################
# 認証動作
########################################################
$myUser = new User($myDB);
if ($rKey == NULL)
	showSorryPage(_ILLEGAL_ACCESS2);

if (!$myUser->doAuthenticationByRegistKey($rKey))
	trigger_error("doAuthentication Failed.", E_USER_ERROR);

if ($myUser->UserCD == -1)
	showSorryPage(_ILLEGAL_ACCESS2);

$UserCD 		= $myUser->UserCD;
$wClientCD 		= $myUser->ClientCD; #幹事企業CD
$EigyosyoCD 		= $myUser->EigyosyoCD; #幹事企業支店・営業所CD
$UserKbn 		= $myUser->UserKbn; #1:幹事企業一般 2:管理者 3:協力業者CD
$GyosyaCD	= $myUser->GyosyaCD; #協力業者CD

unset($myUser);

########################################################
# 物件情報取得（編集）
########################################################

$myBukken = new Bukken($myDB);

if (!$myBukken->executeSelect("MukouFlg = FALSE AND BukkenCD = " . $editBukkenCD, "") || $myBukken->RecCnt != 1) {
	trigger_error("Getting Bukken Failed.", E_USER_ERROR);
}

// $wKenmeiNo 				= $myBukken->KenmeiNo;
$wBukkenName 			= $myBukken->BukkenName;
// $wBukkenName_Hurigana	= $myBukken->BukkenName_Hurigana;
$Created 				= $myBukken->Created;#作成日
$SenyuStartDate = $myBukken->SenyuStartDate;
$SenyuEndDate = $myBukken->SenyuEndDate;
$SenyuDateCnt = (( strtotime( $SenyuEndDate ) -  strtotime( $SenyuStartDate )) / 86400) + 1 ;#専有部日数
$Holiday1 = $myBukken->Holiday1;
$wHoliday = SPFWTools::decodePluralValue($Holiday1);
sort($wHoliday);
$wFirstDateFeature = $myBukken->FirstDateFeature;

$wHansu = $myBukken->Hansu;
$wWakuPattern = $myBukken->WakuPattern;
$MaxWakuSu = $myBukken->MaxWakuSu;
$MaxWaku = explode("-",$MaxWakuSu);
$wWakuAM = $MaxWaku[0];
$wWakuPM = $MaxWaku[1];
$wWakuPM1 = $MaxWaku[1];
if(count($MaxWaku)>2){
	$wWakuPM2 = $MaxWaku[2];
}

if(!$wWakuPattern){
	echo ('<script>
if(confirm("作業日程登録がまだ終わっていないようです。\r\nブラウザで戻り、作業日程登録の各項目の入力をお願いします。\r\n作業日程登録ページへ移動しますか？")){
	location.href="./doc/s_make_kanryo2.php?rKey='.$rKey.'&editBukkenCD='.$editBukkenCD.'";
}
</script>');
}
unset($myBukken);
########################################################
# 資料掲載情報表示
########################################################
######################################################
# QRコード生成
#####################################################
$folderPath = './upfile/'.date('Y',strtotime($Created) );
// フォルダが存在するかチェック
if (!is_dir($folderPath)) {
    // フォルダが存在しない場合、作成する
    if (mkdir($folderPath, 0777, true)) {
        // echo "フォルダ '$folderPath' が作成されました。";
    }
}
$QRCD = $folderPath."/".$editBukkenCD.'qrcode.png';
// QRコードを生成するデータ
$URLdata = 'https://app5.489501.jp/hochiki/login.php?editBukkenCD='.$editBukkenCD ;
$URL = 'https://app5.489501.jp/hochiki/login.php';
if (!file_exists($QRCD)) {
	include 'phpqrcode/qrlib.php';
	$file = $QRCD;
	QRcode::png($URLdata, $file, QR_ECLEVEL_L, 10);
	// echo 'QRコードが生成されました: ' . $file;
}
########################################################
# 物件初期ユーザパスワード
########################################################
$myListObject = new SPFWListObject($myDB);

$sql = "SELECT ";
$sql .= "Passwd ";
$myListObject->SelectSQL = $sql;
$sql = " FROM tUserM";
$sql .= " WHERE MukouFlg = FALSE AND BukkenCD = " . $editBukkenCD;
$myListObject->Condition	= $sql;
$myListObject->Order 		= "UserCD";
$myListObject->Limit 		= "1";

if (!($myListObject->GetList(1)))
	trigger_error("Getting Menu List Failed.", E_USER_ERROR);
$wPasswd = $myListObject->GetValue(0, 0);#一番初めのユーザのパスワードをセット

########################################################
# 詳細工程表表示
########################################################

#Koteihyoに、空きか部屋番号をいれていく。
########################################################
# 日程情報取得
########################################################
// SELECT 
//    DATE(TimeFrom) AS Date,
//     CASE 
//         WHEN TIME(TimeFrom) BETWEEN '09:00:00' AND '12:00:00' THEN 'AM'
//         WHEN TIME(TimeFrom) BETWEEN '13:00:00' AND '15:00:00' THEN 'PM1'
//         WHEN TIME(TimeFrom) BETWEEN '15:00:01' AND '18:00:00' THEN 'PM2'
//         ELSE 'Other'
//     END AS TimePeriod
// FROM tReservationF
// where BukkenCD = 217

$myListObject = new SPFWListObject($myDB);
$sql  = "SELECT ";
$sql .= "ReservationCD, ";
$sql .= "DATE(TimeFrom) AS Date, ";
if($wWakuPattern == '0' || $wWakuPattern == '1' || $wWakuPattern == '2'){ // 2枠
	$sql .= "CASE ";
	$sql .= " WHEN TIME(TimeFrom) BETWEEN '09:00:00' AND '12:00:00' THEN 'AM'";
	$sql .= " WHEN TIME(TimeFrom) BETWEEN '13:00:00' AND '18:00:00' THEN 'PM'";
	$sql .= " ELSE 'Other'";
	$sql .= " END AS AMPM ,";
}else{ // 3枠
	$sql .= "CASE ";
	$sql .= " WHEN TIME(TimeFrom) BETWEEN '09:00:00' AND '12:00:00' THEN 'AM'";
	$sql .= " WHEN TIME(TimeFrom) BETWEEN '13:00:00' AND '14:59:00' THEN 'PM1'";
	$sql .= " WHEN TIME(TimeFrom) BETWEEN '15:00:00' AND '18:00:00' THEN 'PM2'";
	$sql .= " ELSE 'Other'";
	$sql .= " END AS AMPM ,";
}

$sql .= "ID ";
$myListObject->SelectSQL = $sql;
$sql  = " FROM tReservationF";
$sql .= " WHERE Status = 1 AND MukouFlg = FALSE";
$sql .= " AND BukkenCD = " . $editBukkenCD;
$sql .= " AND ClientCD = " . $wClientCD;

$myListObject->Condition = $sql;
$myListObject->Order = "TimeFrom,ReservationCD ";
$myListObject->Limit = "allpage";

if (!($myListObject->GetList(1)))
	trigger_error("Getting Reservation List Failed.", E_USER_ERROR);

$ReservationLoop = $myListObject->Rows;
for ($i = 0; $i < $ReservationLoop; $i++) {
	$ReservationCD[$i] = $myListObject->GetValue($i, 0);
	$tDate = $myListObject->GetValue($i, 1);
	$AMPM = $myListObject->GetValue($i, 2);
	$ID[$i] = $myListObject->GetValue($i, 3);
	$Reserve[$tDate][$AMPM][] = $ID[$i];
}

######################################################################################





for ($i = 0; $i < count($WAKUPATTERN[$wWakuPattern]['AMPM']); $i++) {
	$WakuName = $WAKUPATTERN[$wWakuPattern]['AMPM'][$i];
	${'wWaku' . $WakuName . 'Col'} = ceil(${'wWaku' . $WakuName} / $wHansu);
	${'wWaku' . $WakuName . 'ColSum'} = ${'wWaku' . $WakuName . 'Col'} * $wHansu;
	$wWakuColSum += ${'wWaku' . $WakuName . 'Col'};

	${'wWaku' . $WakuName . 'col'} = ceil(${'wWaku' . $WakuName} / $wHansu);
	if($WakuName == 'PM')$wWakuPM1col = ${'wWaku' . $WakuName . 'Col'} ;
}

########################################################
# 初日・土日祝考慮
########################################################

$wWakuSum1 = 0;
$wWakuSum2 = 0;

$week = ['日', '月', '火', '水', '木', '金', '土'];

$date = new DateTime($SenyuStartDate);
$x = 0;
for ($i = 0; $i < $SenyuDateCnt; $i++) {
	$Syoniti[$i] = false;
	$KojiHoliday[$i] = false;
	$holiday[$i] = false;


	if ($i == 0) {
		$Syoniti[$i] = true;
	}
	$SenyuDate = $date->format('Y-m-d');
	$result = array_search($SenyuDate, $SHUKUJITULIST);
	$YoubiCD =  $date->format('w');
	if ($result !== false || $YoubiCD == 0 || $YoubiCD == 6) {
		$holiday[$i] = true;
	}
	if (count($wHoliday) > 0) { //休工日
		$KojiHoliday[$i] = (array_search($SenyuDate, $wHoliday) === false) ? false : true;
	}
	for ($j = 0; $j < count($WAKUPATTERN[$wWakuPattern]['AMPM']); $j++) {
		$WakuName = $WAKUPATTERN[$wWakuPattern]['AMPM'][$j];
		//空きを考慮した枠数を取得
		// ${'Waku' . $WakuName . 'Su'} = getWakuRoomSu($WakuName, $Syoniti[$i], $holiday[$i], ${'wWaku' . $WakuName}, $wFirstDateFeature);
		//初日考慮
		$SyonitiKouryo = false;
		if ($wFirstDateFeature == 1 && strpos($WakuName, 'AM') !== FALSE && $Syoniti[$i]) {
			$SyonitiKouryo = true;
		}else if ($wFirstDateFeature == 2 && (strpos($WakuName, 'AM') !== FALSE || strpos($WakuName, 'PM1') !== FALSE) && $Syoniti[$i]){
			$SyonitiKouryo = true;
		}
#★★★ここから
		for ($k = 0; $k < ${'wWaku' . $WakuName . 'ColSum'}; $k++) {#10,8,8
			if (!$KojiHoliday[$i]) { //休工日以外
				if ($SyonitiKouryo) { //初日考慮でAMなら　空を入れる
					${'Waku' . $WakuName . 'Room'}[] = "";
				}elseif(isset($Reserve[$SenyuDate][$WakuName][$k])){
					${'Waku' . $WakuName . 'Room'}[] = $Reserve[$SenyuDate][$WakuName][$k];
				}elseif (${'wWaku' . $WakuName} > $k) { //残った最大工事枠数分は空き
					${'Waku' . $WakuName . 'Room'}[] = "空き";
				}else{
					${'Waku' . $WakuName . 'Room'}[] = "";
				}	
			}
			// if (!$KojiHoliday[$i]) { //休工日以外
				// if ($SyonitiKouryo) { //初日考慮でAMなら　空を入れる
				// 	${'Waku' . $WakuName . 'Room'}[] = "";
				// } elseif (${'Waku' . $WakuName . 'Su'} > $k && isset($KaiRoom3[$x])) { //空きを考慮した枠数分　部屋を入れる
				// 	${'Waku' . $WakuName . 'Room'}[] = $KaiRoom3[$x];
				// 	$x++;
				// } elseif (${'wWaku' . $WakuName} > $k) { //残った最大工事枠数分は空き
				// 	${'Waku' . $WakuName . 'Room'}[] = "空き";
				// } else {
				// 	${'Waku' . $WakuName . 'Room'}[] = "";
				// }
			// }
		}
	}
	$date->modify('+1 days');
}
// //組み込んだ部屋の数
// $RoomCnt = $x;

// if (count($KaiRoom3) > $RoomCnt) {
// 	$IfError = TRUE;
// 	$SakuseiDisabled = 'disabled';
// }

// #★1 End
// $wShukujitucolor = SPFWTools::encodePluralValue($Shukujitucolor);
// $wKyukobi = SPFWTools::encodePluralValue($wKyukobi);





########################################################
# 詳細工程表（イメージ）部分
########################################################

$Koteihyou = "<table border='1' width='600px' cellspacing='5' style='text-align:center' cellpadding='7' class='ex_table no-action'>";
$Koteihyou .= "<tr><td class='ex_table2' width='130px'>日程</td>";
$Koteihyou .= "<td class='ex_table2' width='60px'>曜日</td>";
for ($i = 0; $i < count($WAKUPATTERN[$wWakuPattern]['AMPM']); $i++) {
	$WakuName = $WAKUPATTERN[$wWakuPattern]['AMPM'][$i];

	$Koteihyou .= "<td colspan = " . ${'wWaku' . $WakuName . 'Col'};
	if ($i % 2 == 0) {
		$Koteihyou .= " style='background-color:#add8e6;' ";
	} else {
		$Koteihyou .= " style='background-color:#e0ffff;' ";
	}
	$Koteihyou .= "class='ex_table2'>" . $WakuName."</td>";

	${$WakuName . "index"} = 0;
	// echo "<pre>";
	// var_dump(${'Waku' . $WakuName . 'Room'});
	// echo "</pre>";

	${"wWaku".$WakuName."col"} = ${'wWaku' . $WakuName . 'Col'} ;#大文字、小文字がちがう！
	#echo "<br> ".__LINE__." Hensu :".${"wWaku".$WakuName."col"};
	

}
$Koteihyou .= "</tr>";
$holiday = array();
$date = new DateTime($SenyuStartDate);

for ($i = 0; $i < $SenyuDateCnt; $i++) {
	$SenyuDate = $date->format('Y-m-d');
	$week_str = $week[$date->format('w')];
	if($week_str == '土')
		$week_str = '<span style="color:#0070c0">'.$week_str.'</span>';
	else if($week_str == '日')
		$week_str = '<span style="color:#ff9999">'.$week_str.'</span>';

	if ($KojiHoliday[$i]) { #★１休工日なら
		if ($holiday[$i]) { //土日祝なら
			$Koteihyou .= "<tr class='trtop trholiday'><td class='ex_table2' style='background-color:pink;'>" . $SenyuDate . "</td>";
			$Koteihyou .= "<td  class='ex_table2' style='background-color:pink;'>" . $week_str . "</td>";
		} else { //平日なら
			$Koteihyou .= "<tr class='trtop trholiday'><td  class='ex_table2'>" . $SenyuDate . "</td>";
			$Koteihyou .= "<td class='ex_table2'>" . $week_str . "</td>";
		}
		$Koteihyou .= "<td colspan=" . $wWakuColSum . " class='ex_table2'>";
		$Koteihyou .= "休工日</td>";
	} else { #★１休工日でない場合
		if ($holiday[$i]) { //土日祝なら
			$Koteihyou .= "<tr class='trtop'><td rowspan=" . $wHansu . " class='ex_table2' style='background-color:pink;'>" . $SenyuDate . "</td>";
			$Koteihyou .= "<td rowspan=" . $wHansu . " class='ex_table2' style='background-color:pink;'>" . $week_str . "</td>";
		} else { //平日なら
			$Koteihyou .= "<tr class='trtop'><td rowspan=" . $wHansu . " class='ex_table2'>" . $SenyuDate . "</td>";
			$Koteihyou .= "<td rowspan=" . $wHansu . " class='ex_table2'>" . $week_str . "</td>";
		}
		for ($j = 0; $j < $wHansu; $j++) {
			if ($j != 0)
				$Koteihyou .= "<tr>";
			for ($k = 0; $k < count($WAKUPATTERN[$wWakuPattern]['AMPM']); $k++) {
				$WakuName = $WAKUPATTERN[$wWakuPattern]['AMPM'][$k];
				for ($l = 0; $l < ${'wWaku' . $WakuName . 'Col'}; $l++) {
					if ($k % 2 == 0) {
						$Koteihyou .= "<td class='link_cell' style='background-color:#ffff9e;'>";
					} else {
						$Koteihyou .= "<td class='link_cell' style='background-color:#ffffcf;'>";
					}
					if (${'Waku' . $WakuName . 'Room'}[${$WakuName . "index"}] == "空き") {
						$Koteihyou .= ${'Waku' . $WakuName . 'Room'}[${$WakuName . "index"}];
					} else {
						$Koteihyou .= '<font size="4"> <b>' . ${'Waku' . $WakuName . 'Room'}[${$WakuName . "index"}] . '</b></font>';
					}
					$KoteihyouEX[] = ${'Waku' . $WakuName . 'Room'}[${$WakuName . "index"}];

					// echo "<br>" . $WakuName . ":" . ${$WakuName . "index"};
					$Koteihyou .= "</td>";
					${$WakuName . "index"}++;
				}
			}
			$Koteihyou .= "</tr>";
		}
	}#休工日

	$date->modify('+1 days');
}
$Koteihyou .= "</table>";





########################################################
// # 担当者リスト表示
// ########################################################
// $myListObject = new SPFWListObject($myDB);

// $sql = "SELECT ";
// $sql .= "UserCD, ";
// $sql .= "LastName ";
// $myListObject->SelectSQL = $sql;

// $sql = " FROM tUserM";
// if ($UserKbn != 2) { #管理者でなければ
// 	$sql .= " WHERE MukouFlg = FALSE AND ClientCD = " . $ClientCD;
// 	#		$sql .= " AND EigyosyoCD = ".$EigyosyoCD; #幹事企業CD
// }
// $myListObject->Condition	= $sql;
// $myListObject->Order 		= "LastNameKana,LastName ";
// $myListObject->Limit 		= "allpage";

// if (!($myListObject->GetList(1)))
// 	trigger_error("Getting Menu List Failed.", E_USER_ERROR);

// $TantoLoop = $myListObject->Rows;
// for ($i = 0; $i < $TantoLoop; $i++) {
// 	$TantoCD[$i] 	= $myListObject->GetValue($i, 0);
// 	$TantoName[$i]	= $myListObject->GetValue($i, 1);
// 	$TantoSoeji[$TantoCD[$i]] = $i;
// }
// unset($myListObject);

// ########################################################
// # 施工業者担当者
// ########################################################
// $myListObject = new SPFWListObject($myDB);

// $sql = "SELECT ";
// $sql .= "gt.UserCD, ";		#0業者担当CD
// $sql .= "gt.LastName, ";	#1業者担当名
// $sql .= "gt.GyosyaCD, ";		#2業者CD
// $sql .= "gt.TEL, ";			#3業者担当TEL
// $sql .= "gt.Address3, ";	#4業者担当携帯
// $sql .= "gt.EMail, ";		#5業者担当メールアドレス
// $sql .= "gt.Address3, ";	#6  2こめのメールアドレス
// $sql .= "gt.Notes, ";		#7備考
// $sql .= "g.GyosyaName, ";	#8業者名
// $sql .= "g.ShozokuCD ";		#9管轄支店　|3|4|5|となっている。
// $myListObject->SelectSQL = $sql;
// $sql = " FROM tUserM gt , tGyosyaM g ";
// $sql .= " WHERE gt.GyosyaCD = g.GyosyaCD AND gt.MukouFlg = FALSE AND UserKbn = 3 "; //

// $myListObject->Condition	= $sql;
// $myListObject->Order 		= "CAST( g.GyosyaNameKana as BINARY ) "; #表示順
// $myListObject->Limit 		= "allpage";

// if (!($myListObject->GetList(1)))
// 	trigger_error("Getting User List Failed.", E_USER_ERROR);

// $GyosyaTantoLoop = $myListObject->Rows;
// for ($i = 0; $i < $GyosyaTantoLoop; $i++) {
// 	$GyosyaTantoCD[$i] 		= $myListObject->GetValue($i, 0);
// 	$GyosyaTantoName[$i]	= $myListObject->GetValue($i, 1);
// 	$GyosyaName[$i] 		= $myListObject->GetValue($i, 8);
// 	$GyosyaKey[$GyosyaTantoCD[$i]] = $i;
// }
// unset($myListObject);

########################################################
# OPメニュー表示
########################################################

########################################################
# 設定情報
########################################################


########################################################
# フォーマットファイルがアップされているか確認
########################################################


// ディレクトリのパス
/*
$directory = 'C:/xampp/htdocs/hochiki/httpdocs/kojifile/'.$editBukkenCD."/";
if (file_exists($directory)) {

	// ディレクトリを開く
	if ($handle = opendir($directory)) {
		#echo "ディレクトリ内のファイル一覧:<br>";

		// ディレクトリ内のアイテムを1つずつ読み込む
		$FileLoop = 0;
		while (($file = readdir($handle)) !== false) {
			if ($file != "." && $file != "..") {
				#echo $file . "<br>";
				$FileName[] = $file ;
				$FileLoop++;
			}
		}

		// ディレクトリハンドルを閉じる
		closedir($handle);
	} else {
		echo "エラー ディレクトリを開けませんでした。";
	}
}
*/
// 
$myListObject = new SPFWListObject($myDB);

$sql  = "SELECT ";
$sql .= "UploadFileID, ";
$sql .= "FileName, ";
$sql .= "FilePath, ";
$sql .= "tUserM.LastName, ";
$sql .= "tUploadFileF.Created ";
$myListObject->SelectSQL = $sql;
$sql  = " FROM tUploadFileF";
$sql .= " LEFT JOIN tUserM ON tUserM.UserCD = tUploadFileF.UserCD";
$sql .= " AND tUploadFileF.BukkenCD = " . $editBukkenCD;

$myListObject->Condition = $sql;
$myListObject->Order = "tUploadFileF.UploadFileID ASC ";
$myListObject->Limit = "allpage";

if (!($myListObject->GetList(1)))
	trigger_error("Getting Reservation List Failed.", E_USER_ERROR);

$UploadFileLoop = $myListObject->Rows;
$UploadFileID = $FileName = $FilePath = $LastName = $Created = [];
for ($i = 0; $i < $UploadFileLoop; $i++) {
	$UploadFileID[$i] 		= $myListObject->GetValue($i, 0);
	$FileName[$i]	= $myListObject->GetValue($i, 1);
	$FilePath[$i] 		= str_replace('C:/xampp/htdocs/hochiki/httpdocs/kojifile/', './kojifile/', $myListObject->GetValue($i, 2));
	$LastName[$i] 		= $myListObject->GetValue($i, 3);
	$Created[$i] 		= date("Y-m-d H:i", strtotime($myListObject->GetValue($i, 4)));
}

########################################################
# コンテンツ表示
########################################################

$CNT_FILE = "s_format.tpl";

$myTemplate 	= new SPFWTemplate($CNT_FILE, $MyCarrier);
$HiddenValues 	= $myTemplate->getValuesToPass();

$myTemplate->convertTags();
$myTemplate->outputTemplate();

unset($myTemplate);
unset($myLog);


########################################################
# 関数群
########################################################
//最大工事枠数から空きの数を引いた枠数を取得
function getWakuRoomSu($WakuName, $Syoniti, $holiday, $MaxWakuSu, $FirstDateFeature)
{
	// 第一引数：$WakuName string
	//  AM・PMなど

	// 第二引数： $Syoniti boolean
	//  初日かどうか

	// 第三引数：$holiday boolean
	//  土日祝かどうか

	// 第四引数：$MaxWakuSu int
	//  枠ごとの最大工事枠数

	// 第五引数：$FirstDateFeature int
	//  初日考慮
	//  1：午前中NG
	//  2：15時までNG


	//最大工事枠数から空きの数を引く計算をする
	if ($Syoniti == true && $holiday) { //初日・土日祝　(最大工事枠数/2)-1

		$WakuRoomSu = ceil($MaxWakuSu / 2) - 1;#切り上げて1引く。　

		if ($FirstDateFeature == 1 && strpos($WakuName, 'AM') !== FALSE) { //初日午前NG
			$WakuRoomSu = 0;
		} elseif ($FirstDateFeature == 2 && (strpos($WakuName, 'AM') !== FALSE || strpos($WakuName, 'PM1') !== FALSE)) { //初日15：00以降OK
			$WakuRoomSu = 0;
		}
	} elseif ($Syoniti == true) { //初日・平日　最大工事枠数-2

		$WakuRoomSu = $MaxWakuSu - 2;

		if ($FirstDateFeature == 1 && strpos($WakuName, 'AM') !== FALSE) { //初日午前NG
			$WakuRoomSu = 0;
		} elseif ($FirstDateFeature == 2 && (strpos($WakuName, 'AM') !== FALSE || strpos($WakuName, 'PM1') !== FALSE)) { //初日15：00以降OK
			$WakuRoomSu = 0;
		}
	} elseif ($holiday) { //土日祝 最大工事枠数/2
		$WakuRoomSu = ceil($MaxWakuSu / 2);
	} else { //平日 最大工事枠数-1
		$WakuRoomSu = $MaxWakuSu - 1;
	}
	if ($WakuRoomSu < 0) {
		$WakuRoomSu = 0;
	}
	return $WakuRoomSu;
}


#品番一致
function getDeviceData_Hinban($myDB, $Category, $Hinban)
{

	$DeviceData = array();

	$myListObject = new SPFWListObject($myDB);

	$sql = "SELECT ";
	$sql .= "DeviceName, ";
	$sql .= "Kataban, ";
	$sql .= "D003, "; #機器説明
	$sql .= "ShortName ";
	$myListObject->SelectSQL = $sql;

	$sql = " FROM tDeviceM";
	$sql .= " WHERE MukouFlg = FALSE and Category = '" . $Category . "'"; #1:親機 2:子機
	$sql .= " AND Kataban = '" . $Hinban . "' ";

	$myListObject->Condition = $sql;
	$myListObject->Order = "Kataban";
	$myListObject->Limit = "allpage";

	if (!($myListObject->GetList(1)))
		trigger_error("Getting Menu List Failed.", E_USER_ERROR);

	if ($myListObject->Rows == 1) {
		$DeviceData['DeviceName'] 	= $myListObject->GetValue(0, 0);
		$DeviceData['Kataban'] 		= $myListObject->GetValue(0, 1);
		$DeviceData['KikiSetumei'] 	= $myListObject->GetValue(0, 2);
		$DeviceData['ShortName'] 	= $myListObject->GetValue(0, 3);
	}
	unset($myListObject);

	return $DeviceData;
}