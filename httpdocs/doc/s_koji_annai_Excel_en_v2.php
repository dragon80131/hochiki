<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', "On");
?>
<?php
/* 
 * 工事案内出力（英語版）
 * s_koji_annai_Excel_en_v2.php
 * 
 * @created 2020.04.28
 * @updated 2020.05.12
 *  1シートの工事案内を、シートを分けた
 * 
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



	$editBukkenCD = SPFWParameter::getValues('editBukkenCD');


	########################################################
	# 物件情報抽出
	########################################################
	$myBukken = new Bukken($myDB);

	if (!$myBukken->executeSelect("BukkenCD = " . $editBukkenCD . " AND MukouFlg = FALSE", "")){
		trigger_error("Getting Bukken Failed.", E_USER_ERROR);
	}

	if ($myBukken->RecCnt != 1) {
		trigger_error("Getting myBukken List Failed.", E_USER_ERROR);

	} else {
		$BukkenName = $myBukken->BukkenName;
		$KanriGaisya = $myBukken->KanriGaisya;
		$KanriGaisyaTEL = $myBukken->KanriGaisyaTEL;
		$wAutoLock = $myBukken->AutoLock;

		#if($wAutoLock == 1 ){
		#	$DispAutoLock = "エントランス・";
		#	$DispAutoLock2 = "オートロック解錠機能、";
		#	$DispAutoLock3 = "オートロック解錠・";
		#}
		#$ShoboTokurei = $myBukken->ShoboTokurei;#0:なし　1:170　2:220　3:220
	}

	unset($myBukken);

	########################################################
	# 工事情報抽出
	########################################################

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

	}

	#$weekarray = array("(日)","(月)","(火)","(水)","(木)","(金)","(土)");
	$weekarray = array("Sun","Mon","Tue","Wed","Thu","Fri","Sat");
	$KojiName = $myKoji->KojiName;
	$KojiShozokuName = $myKoji->KojiShozokuName;
	$KojiTantoName = $myKoji->KojiTantoName;
	$TantoTEL = $myKoji->KojiShozokuTEL;

	$GyosyaTantoCD1 = $myKoji->GyosyaTantoCD1;

	$ShoboTokurei = $myKoji->ShoboTokurei;#0:なし　1:170　2:220　3:220
	$ShoboSikenhoho = $myKoji->ShoboSikenhoho;	#0:着工前後　1:終了後　2:都度
	$ShoboSikenDate = $myKoji->ShoboSikenDate;	#
	$ShoboSikenJikan = $myKoji->ShoboSikenJikan;	#
	$ShoboSikenJikan2 = $myKoji->ShoboSikenJikan2;	#
	if ($ShoboSikenDate != "0000-00-00") {
		$ShoboSikenDate = date('Y年n月j日',strtotime( $myKoji->ShoboSikenDate ));
		$ShoboSikenDate .= $weekarray[ date('w',strtotime( $myKoji->ShoboSikenDate )) ];
	}

	$Kanrisitu = $myKoji->Kanrisitu;#0:なし　1:あり 201903 ADD GOE

	$SekoShutai = $myKoji->SekoShutai; //201902 ADD GOE

	$GyosyaData = getGyosyaData($myDB, $GyosyaTantoCD1);
	$GyosyaName = $GyosyaData['GyosyaName'];

	#$ZentaiStartDate = date('Y年n月j日',strtotime( $myKoji->ZentaiStartDate ));
	#$ZentaiStartDate .= $weekarray[ date('w',strtotime( $myKoji->ZentaiStartDate )) ];
	$ZentaiStartDate = date('M j Y',strtotime( $myKoji->ZentaiStartDate ));
	$ZentaiStartDate = $weekarray[ date('w',strtotime( $myKoji->ZentaiStartDate )) ].", ".$ZentaiStartDate;
	#$ZentaiEndDate = date('Y年n月j日',strtotime( $myKoji->ZentaiEndDate ));
	#$ZentaiEndDate .= $weekarray[ date('w',strtotime( $myKoji->ZentaiEndDate )) ];
	$ZentaiEndDate = date('M j Y',strtotime( $myKoji->ZentaiEndDate ));
	$ZentaiEndDate = $weekarray[ date('w',strtotime( $myKoji->ZentaiEndDate )) ].", ".$ZentaiEndDate;

//専有部終了日の登録がない場合の処理追加
	#$SenyuStartDate = date('Y年n月j日',strtotime( $myKoji->SenyuStartDate ));
	#$SenyuStartDate .= $weekarray[ date('w',strtotime( $myKoji->SenyuStartDate )) ];
	$SenyuStartDate = date('M j Y',strtotime( $myKoji->SenyuStartDate ));
	$SenyuStartDate = $weekarray[ date('w',strtotime( $myKoji->SenyuStartDate )) ].", ".$SenyuStartDate;
	if(empty($myKoji->SenyuEndDate) OR $myKoji->SenyuStartDate === $myKoji->SenyuEndDate){#専有部が1日だけの場合
		$SenyuEndDate = "";
	}else{
		#$SenyuEndDate = date('Y年n月j日',strtotime( $myKoji->SenyuEndDate )); 
		#$SenyuEndDate .= $weekarray[ date('w',strtotime( $myKoji->SenyuEndDate )) ];
		$SenyuEndDate = date('M j Y',strtotime( $myKoji->SenyuEndDate )); 
		$SenyuEndDate = $weekarray[ date('w',strtotime( $myKoji->SenyuEndDate )) ].", ".$SenyuEndDate;
	}

//共用部終了日の処理追加 201902 ADD GOE
	$KyoyoStartDate = $myKoji->KyoyoStartDate;
	$KyoyoEndDate 	= $myKoji->KyoyoEndDate;

	#共用部に値がある場合
	if(!empty($KyoyoStartDate)){
		if(empty($KyoyoEndDate) OR $KyoyoStartDate === $KyoyoEndDate){#共用部が1日だけの場合
			$KyoyoEndDate = "";
		}else{	#共用部終了日に値がある又は開始日と別日の場合のみフォーマットを変換する
			#$KyoyoEndDate = date('Y年n月j日',strtotime( $myKoji->KyoyoEndDate )); 
			#$KyoyoEndDate .= $weekarray[ date('w',strtotime( $myKoji->KyoyoEndDate )) ];
			$KyoyoEndDate = date('M j Y',strtotime( $myKoji->KyoyoEndDate )); 
			$KyoyoEndDate = $weekarray[ date('w',strtotime( $myKoji->KyoyoEndDate )) ].", ".$KyoyoEndDate;
		}
		#$KyoyoStartDate = date('Y年n月j日',strtotime( $myKoji->KyoyoStartDate )); 
		#$KyoyoStartDate .= $weekarray[ date('w',strtotime( $myKoji->KyoyoStartDate )) ];
		$KyoyoStartDate = date('M j Y',strtotime( $myKoji->KyoyoStartDate )); 
		$KyoyoStartDate = $weekarray[ date('w',strtotime( $myKoji->KyoyoStartDate )) ].", ".$KyoyoStartDate;

	}else{
		$KyoyoStartDate = "";
	}

	#休工日
	$Holiday = SPFWTools::decodePluralValue($myKoji->Holiday1);

/* この項目は未使用
	if ($myKoji->Holiday2 != "0000-00-00") {
		$Holiday2 		= date('Y年n月j日',strtotime( $myKoji->Holiday2 )); 
		$Holiday2 		.= $weekarray[ date('w',strtotime( $myKoji->Holiday2 )) ];
		$Holiday[]		= $Holiday2;
	}
	if ($myKoji->Holiday3 != "0000-00-00") {
		$Holiday3 		= date('Y年n月j日',strtotime( $myKoji->Holiday3 )); 
		$Holiday3 		.= $weekarray[ date('w',strtotime( $myKoji->Holiday3 )) ];
		$Holiday[]		= $Holiday3;
	}
	if ($myKoji->Holiday4 != "0000-00-00") {
		$Holiday4 		= date('Y年n月j日',strtotime( $myKoji->Holiday4 )); 
		$Holiday4 		.= $weekarray[ date('w',strtotime( $myKoji->Holiday4 )) ];
		$Holiday[]		= $Holiday4;
	}
*/
	#予備日に登録があれば
	if (!empty($myKoji->YobiStartDate)) {
		#$YobiStartDate = date('Y年n月j日',strtotime( $myKoji->YobiStartDate )); 
		#$YobiStartDate .= $weekarray[ date('w',strtotime( $myKoji->YobiStartDate ))] ;
		$YobiStartDate = date('M j Y',strtotime( $myKoji->YobiStartDate )); 
		$YobiStartDate = $weekarray[ date('w',strtotime( $myKoji->YobiStartDate ))].", ".$YobiStartDate;

		#予備日が一日以上の場合
		if(empty($myKoji->YobiEndDate) 
			OR $myKoji->YobiStartDate === $myKoji->YobiEndDate){#予備日が1日だけの場合 201903 ADD
			$YobiEndDate = ""; #フォーマット変換せず空をセット
		}else{
			#$YobiEndDate = date('Y年n月j日',strtotime( $myKoji->YobiEndDate )); 
			#$YobiEndDate .= $weekarray[ date('w',strtotime( $myKoji->YobiEndDate )) ];
			$YobiEndDate = date('M j Y',strtotime( $myKoji->YobiEndDate )); 
			$YobiEndDate = $weekarray[ date('w',strtotime( $myKoji->YobiEndDate )) ].", ".$YobiEndDate;
		}

	}else{
		$YobiStartDate = "";
	}

	$AnnaiDate = date('Y年n月j日',strtotime( $myKoji->AnnaiDate ));	#案内配布日
	#$AnnaiDate2 = date('Y年n月j日',strtotime( $myKoji->AnnaiDate ));	#案内配布日
	$AnnaiDate2 = date('F Y',strtotime( $myKoji->AnnaiDate ));	#案内配布日
	$AnnaiDate .= $weekarray[ date('w',strtotime( $myKoji->AnnaiDate )) ];
	$ReceptionDate = date('Y年n月j日',strtotime( $myKoji->ReceptionDate ));	#受付締切日
	$ReceptionDate .= $weekarray[ date('w',strtotime( $myKoji->ReceptionDate )) ];
	$ReceptionDate2 = date('Y-n-j',strtotime( $myKoji->ReceptionDate ));	#受付締切日

	if ($myKoji->OpEndDate) {
		$OpEndDate = date('Y年n月j日',strtotime( $myKoji->OpEndDate ));	#オプション締切日
		$OpEndDate .= $weekarray[ date('w',strtotime( $myKoji->OpEndDate )) ];
	} else {
		$OpEndDate = $ReceptionDate;
	}

	$WakuPattern = $myKoji->WakuPattern;
	$AnshoNo = $myKoji->AnshoNo;	#//201902 ADD GOE
	$AnshoNoKojichu = $myKoji->AnshoNoKojichu;
	$CurrentNyukan = $myKoji->CurrentNyukan;
	$arrayCurrentNyukan = SPFWTools::decodePluralValue($CurrentNyukan);#配列へ変換
	if (in_array("2",$arrayCurrentNyukan)) { // 従前の入館方法にノンタッチタグ:2 が含まれるかどうか
		$wTagKoji = 1;
	}

	#オプションある場合
	for($i=1;$i<11;$i++){
		if( $myKoji->{"OP".$i."DeviceCD"} || $myKoji->{"OPZiyuu".$i}) { 
			$IfOP = TRUE;
		}
	}
	if($IfOP) { #OPあり
		#オプションある場合のみ有効な画面の値をDBに登録
		$wShiharai = SPFWTools::decodePluralValue($myKoji->Shiharai); // 配列を文字列に変換
		#オプション支払い方法
		if(in_array("コンビニ",$wShiharai)){
			if(in_array("上限あり",$wShiharai)){ // 上限54000円税込
				$wShiharaiConveni = 1;
			}
		}

		#オプション受付方法
		$wOPUketuke = $myKoji->OPUketuke;


		# OPメニュー一覧
		$myListObject = new SPFWListObject($myDB);
		$sql = "SELECT ";
		$sql .= "DeviceCD, ";	#機器CD
		$sql .= "DeviceName, ";	#機器名
		$sql .= "Kataban, ";	#型番
		$sql .= "Category, ";	#カテゴリ
		$sql .= "OPUseKbn, ";	#OP使用区分
		$sql .= "OPUseDisp ";	#OP説明文
		$myListObject->SelectSQL = $sql;
		$sql = " FROM tDeviceM ";
		$sql .= " WHERE MukouFlg = FALSE ";
		$myListObject->Condition = $sql;
		$myListObject->Order = "";
		$myListObject->Limit = "allpage";

		if (!($myListObject->GetList(1)))
			trigger_error("Getting Device List Failed.", E_USER_ERROR);

		$OPDeviceLoop = $myListObject->Rows;
		for ($i = 0; $i < $OPDeviceLoop; $i++) {
			$listOPDeviceCD[$i] = $myListObject->GetValue($i, 0);
			$listOPDeviceName[$listOPDeviceCD[$i]] = $myListObject->GetValue($i, 1);
			$listOPKataban[$listOPDeviceCD[$i]] = $myListObject->GetValue($i, 2);
			$listOPCategory[$listOPDeviceCD[$i]] = $myListObject->GetValue($i, 3); // 数字 Choice5Nameでカテゴリ名取得可能
			$listOPUseKbn[$listOPDeviceCD[$i]] = $myListObject->GetValue($i, 4); // 数字 Choice5Nameでカテゴリ名取得可能
			$listOPUseDisp[$listOPDeviceCD[$i]] = $myListObject->GetValue($i, 5); // 数字 Choice5Nameでカテゴリ名取得可能
		}
		unset($myListObject);


		$opcnt = 0; // 配列カウント
		$OPInfo = array();
		for ($i = 1; $i <= 10; $i++) {
			// https://qiita.com/mpyw/items/41230bec5c02142ae691
			$strcd = "OP".$i."DeviceCD";
			$strpr = "OPPrice".$i;
			$strcdZyuu = "OPZiyuu".$i;	#201905 ADD 自由記入の機器

			#自由入力の機器があればそちらが優先
			if ($myKoji->$strcdZyuu) {
				$OPInfo[$opcnt]["OPDeviceName"] = $myKoji->$strcdZyuu;
				$OPInfo[$opcnt]["OPUseKbn"] =  "Z";
				$OPPrice = $myKoji->$strpr; // OPPrice1 ～ OPPrice10
				if ($OPPrice > 0)  $OPPrice = number_format($OPPrice); // カンマ区切り
				$OPInfo[$opcnt]["OPPrice"] = $OPPrice;
				$opcnt += 1; // 配列カウント
			#通常の機器
			}elseif ($myKoji->$strcd) {
				$OPCD = $myKoji->$strcd; // OP1DeviceCD ～ OP10DeviceCD
				$OPPrice = $myKoji->$strpr; // OPPrice1 ～ OPPrice10
				if ($OPPrice > 0)  $OPPrice = number_format($OPPrice); // カンマ区切り

				$OPInfo[$opcnt]["OPDeviceName"] = $listOPDeviceName[$OPCD];
				$OPInfo[$opcnt]["OPCategory"] = $KIKICATEGORY[ $listOPCategory[$OPCD] ];
				$OPInfo[$opcnt]["OPCategoryNo"] = $listOPCategory[$OPCD];
				$OPInfo[$opcnt]["OPKataban"] = $listOPKataban[$OPCD];
				$OPInfo[$opcnt]["OPPrice"] = $OPPrice;
				$OPInfo[$opcnt]["OPUseKbn"] =  $listOPUseKbn[$OPCD];
				$OPInfo[$opcnt]["OPUseDisp"] = $listOPUseDisp[$OPCD];
				$opcnt += 1; // 配列カウント
			}
		}
	} #OPあり


	#入館方法を取得
	$wRNNyukan = $myKoji->RNNyukan;	#|0|1|2| 鍵　暗証番号　ノンタッチ　で入っている#
	$wCurrentNyukan = $myKoji->CurrentNyukan;	#RN前

	#タグ切替日を取得
	if($wTagKirikae == '1'){	#タグ切替が着工日の場合	共用部開始日を設定
		$TagKirikaeDate = $myKoji->KyoyoStartDate; 
	}else{	#工事終了日の場合	専有部終了日を設定
		if($SenyuEndDate == ""){#終了日がない（専有部工事が一日場合は）開始日をセット
			$TagKirikaeDate = $myKoji->SenyuStartDate;
		}else{
			$TagKirikaeDate = $myKoji->SenyuEndDate;
		}
	}

	$Kanrisitu = $myKoji->Kanrisitu;	#0:無　1:有	管理室親機有無
	$wConstTime = $myKoji->ConstTime;
	$wKirikaehoho = $myKoji->Kirikaehoho;

	$wKirikaeHeikoEizoriyo = $myKoji->KirikaeHeikoEizoriyo;
	$wJikaho = $myKoji->Jikaho;
	$wKasaiHeya = $myKoji->KasaiHeya;
	$wKansenKoji = $myKoji->KansenKoji;
	$wGasKoji = $myKoji->GasKoji;
	$wBohanKoji = $myKoji->BohanKoji;
	$wRosuiKoji = $myKoji->RosuiKoji;
	$wTakuhai = $myKoji->Takuhai;

	$wAnswer = $myKoji->Answer;
	$wWEBRecept = $myKoji->WEBRecept;
	$wTagSuu = $myKoji->TagSuu;
	$wOwnerTagSuu = $myKoji->OwnerTagSuu;
	$wTagKirikae = $myKoji->TagKirikae;

	#親機・玄関子機パネル
	$wOyakiPanel = $myKoji->SiyobuzaiOyaFlg;
	$wKokiPanel = $myKoji->SiyobuzaiKoFlg;


	###資料上部に表示する会社名###
	#DB1カラムに登録するため下記形状に変換して登録
	#|1番目に表示する会社名|2番目|3番目|
	$DispCompanyTop1 = SPFWParameter::getValues('DispCompanyTop1');
	$DispCompanyTop2 = SPFWParameter::getValues('DispCompanyTop2');
	$DispCompanyTop3 = SPFWParameter::getValues('DispCompanyTop3');

	#入力値をDB格納
	$DispCompanyTopArr = array($DispCompanyTop1,$DispCompanyTop2,$DispCompanyTop3);
	$myKoji->DispCompanyTop = SPFWTools::encodePluralValue($DispCompanyTopArr); # 配列を文字列に変換

	###資料下部 工事に関するお問い合わせ先に記載する会社###
	#DB1カラムに登録するため下記形状に変換して登録
	#|管理会社|1:電話番号表示する 0:しない|アイホン|1:電話番号表示する0:しない|その他会社名|1:電話番号表示する0:しない|電話番号|
	$DispCompanyBottomChk1 = SPFWParameter::getValues('DispCompanyBottomChk1'); // 管理会社 チェック
	$DispCompanyBottomChk2 = SPFWParameter::getValues('DispCompanyBottomChk2'); // アイホン チェック
	$DispCompanyBottomChk3 = SPFWParameter::getValues('DispCompanyBottomChk3'); // その他 チェック
	$DispCompanyBottom3    = SPFWParameter::getValues('DispCompanyBottom3');    // その他 入力欄
	$DispCompanyBottom1FLG = SPFWParameter::getValues('DispCompanyBottom1FLG'); // 管理会社 電話番号記載
	$DispCompanyBottom2FLG = SPFWParameter::getValues('DispCompanyBottom2FLG'); // アイホン 電話番号記載
	$DispCompanyBottom3FLG = SPFWParameter::getValues('DispCompanyBottom3FLG'); // その他 電話番号記載
	$DispCompanyBottomTEL3 = SPFWParameter::getValues('DispCompanyBottomTEL3'); // その他 電話番号

	$ToiawasewakiDispArr = array();

	#管理会社がONの場合
	if($DispCompanyBottomChk1 == '1'){
		$ToiawasewakiDispArr[0] = '管理会社';
	}else{
		$ToiawasewakiDispArr[0] = '';
	}
	$ToiawasewakiDispArr[1] = $DispCompanyBottom1FLG;	#電話番号を出力　1：する　0：しない

	#アイホンさんがONの場合
	if($DispCompanyBottomChk2 == '1'){
		$ToiawasewakiDispArr[2] = 'アイホン';
	}else{
		$ToiawasewakiDispArr[2] = '';
	}
	$ToiawasewakiDispArr[3] = $DispCompanyBottom2FLG;	#電話番号を出力　1：する　0：しない

	#その他がONの場合
	if($DispCompanyBottomChk3 == '1'){
		$ToiawasewakiDispArr[4]= $DispCompanyBottom3;	#その他の会社名
	}else{
		$ToiawasewakiDispArr[4] = '';
	}
	$ToiawasewakiDispArr[5] = $DispCompanyBottom3FLG;	#電話番号を出力　1：する　0：しない
	$ToiawasewakiDispArr[6] = $DispCompanyBottomTEL3;	#その他電話番号

	#入力値をDB格納（配列を文字列に変換）
	$myKoji->ToiawasewakiDisp = SPFWTools::encodePluralValue($ToiawasewakiDispArr);

	###作業員着用（ベスト・腕章）・施工主体指定の着用着
	$wSagyoinArr = SPFWParameter::getValues('wSagyoin');	#作業員 ベスト/腕章　配列
	$wShiteiVest = SPFWParameter::getValues('wShiteiVest');	#指定 ベスト
	$wShiteiVestOther = SPFWParameter::getValues('ShiteiVestOther');	#指定 ベストその他会社名
	#入力値をDB格納
	$myKoji->Sagyoin = SPFWTools::encodePluralValue($wSagyoinArr);
	$myKoji->ShiteiVestCD = $wShiteiVest ;	#指定ベスト
	$myKoji->ShiteiVestOther = $wShiteiVestOther ;	#指定ベストその他

	if (!$myKoji->executeUpdate()){
		$ErrorString = array();
		$ErrorString[] = "工事情報のアップデートに失敗しました。";
		showAdminSorryPage($ErrorString);
	}
	unset($myKoji);


	#ノンタッチシステム導入FlgON
	#オートロックあり 且つ リニューアル後のタグアリ　且つ　リニューアル前のタグなし
	if($wAutoLock == "1" and strstr($wRNNyukan ,'2') and !strstr($wCurrentNyukan ,'2')){
		$nonTouchFLG = true;
	} 


	#######################################################
	# Excelファイル生成#
	########################################################
	require 'vendor/autoload.php';

	use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;
	$reader = new XlsxReader();

	$spreadsheet = $reader->load('./template/s_koji_annai_Excel_en_v2.xlsx');


######### 1_概要 #########

	$sheet = $spreadsheet->getSheetByName('1_概要');

	#行を変数に格納
	$gyo_sheet1 = 0;

	#◆タイトル部
	$sheet->setCellValue('AN'.($gyo_sheet1 + 1), $AnnaiDate2); // 1行目
	$sheet->setCellValue('A'.($gyo_sheet1 + 2), "Dear ".$BukkenName); // 2行目

	#管理会社・アイホン問合せ先
	#◆右上に表示する会社名
	$sheet->setCellValue('AN'.($gyo_sheet1 + 3), str_replace("アイホン株式会社","Aiphone Co.,Ltd.",$DispCompanyTop1)); // 3行目
	$sheet->setCellValue('AN'.($gyo_sheet1 + 4), str_replace("アイホン株式会社","Aiphone Co.,Ltd.",$DispCompanyTop2)); // 4行目
	$sheet->setCellValue('AN'.($gyo_sheet1 + 5), str_replace("アイホン株式会社","Aiphone Co.,Ltd.",$DispCompanyTop3)); // 5行目
/*
	if($nonTouchFLG){ #ノンタッチシステム導入あり
		$addStr ='及びノンタッチシステム導入工事';	#タイトルに文言を追加
		$tmpCell = $sheet->getCell('E'.($gyo_sheet1 + 7));	#タイトル ノンタッチシステム導入工事
		$tmpCellStr = $tmpCell->getValue();
		$tmpCellStr = str_replace("KojiName",$KojiName.PHP_EOL.$addStr,$tmpCellStr);
		$tmpCell->setValue($tmpCellStr);
	}else{
		$addStr = '';
		$tmpCell = $sheet->getCell('E'.($gyo_sheet1 + 7));	#タイトル 工事名称
		$tmpCellStr = $tmpCell->getValue();
		$tmpCellStr = str_replace("KojiName",$KojiName,$tmpCellStr);
		$tmpCell->setValue($tmpCellStr);
		$spreadsheet->getActiveSheet()->getStyle('E'.($gyo_sheet1 + 7))->getFont()->setSize(23);
	}

	$tmpCell = $sheet->getCell('B'.($gyo_sheet1 + 9)); // 9行目 挨拶文
	$tmpCellStr = $tmpCell->getValue();
	$tmpCellStr = str_replace("KojiName",$KojiName.$addStr,$tmpCellStr);
	$tmpCell->setValue($tmpCellStr);
*/

	#切替後の1key/2keyで文言を出し分け
	if($nonTouchFLG){

		$tmpCell = $sheet->getCell('C'.($gyo_sheet1 + 20)); // 20行目
		$tmpCellStr = $tmpCell->getValue();
		#$tmpCellStr = str_replace("●月●日",date('n月j日',strtotime( $TagKirikaeDate )),$tmpCellStr);
		$tmpCellStr = str_replace("●●",date('M j',strtotime( $TagKirikaeDate )),$tmpCellStr);
		$tmpCell->setValue($tmpCellStr);

		$tmpCell = $sheet->getCell('C'.($gyo_sheet1 + 23)); // 23行目
		$tmpCellStr = $tmpCell->getValue();
		$tmpCellStr = str_replace("●",$wTagSuu,$tmpCellStr);
		$tmpCell->setValue($tmpCellStr);

		if(strstr ($wRNNyukan ,'0')){ #RN後の入館方法:鍵 →2key（鍵&タグ）
			#$sheet->setCellValue('C'.($gyo_sheet1 + 24), "従来の鍵またはノンタッチタグキーでオートロック開錠が可能になります。" );
			#$sheet->getRowDimension($gyo_sheet1 + 25)->setVisible(false);
			#$sheet->getRowDimension($gyo_sheet1 + 26)->setVisible(false);

			$sheet->setCellValue('C'.($gyo_sheet1 + 24), "You can unlock the entrance with the current key or non-touch tag key.");
			$sheet->getRowDimension($gyo_sheet1 + 25)->setRowHeight(1);
			$sheet->getRowDimension($gyo_sheet1 + 26)->setRowHeight(1);
		}

	}else{	#オートロックなし　又は　タグ本数0本の場合　対象行を非表示

		for($temp_i = ($gyo_sheet1 + 15); $temp_i < ($gyo_sheet1 + 29) ; $temp_i++){ // 15～28
			$sheet->getRowDimension($temp_i)->setVisible(false);#行非表示
		}
	}

	#◆工事名称、工期
	#全体工期
	$sheet->setCellValue('J'.($gyo_sheet1 + 30), $ZentaiStartDate."～".$ZentaiEndDate ); // 30行目

	#共用部工事
	#if(substr($KyoyoStartDate,0,4)==substr($KyoyoEndDate,0,4)){#年を跨がない場合は西暦を出さない
	if(substr($KyoyoStartDate,-4)==substr($KyoyoEndDate,-4)){#年を跨がない場合は西暦を出さない
		#$KyoyoStartDate = substr($KyoyoStartDate,7);
		#$KyoyoEndDate = substr($KyoyoEndDate,7);
		$KyoyoStartDate = substr($KyoyoStartDate,0,-4);
		$KyoyoEndDate = substr($KyoyoEndDate,0,-4);
	}
	if($KyoyoStartDate != ""){
		if($KyoyoEndDate != ""){	#終了日がある場合 又は開始日と終了日が別日の場合
			$sheet->setCellValue('J'.($gyo_sheet1 + 31), $KyoyoStartDate."～".$KyoyoEndDate ); // 31行目
		}else{	#それ以外は開始日のみ出力
			$sheet->setCellValue('J'.($gyo_sheet1 + 31), $KyoyoStartDate ); // 31行目
		}
	}else{
		$sheet->getRowDimension($gyo_sheet1 + 31)->setVisible(false); // 31行目
		#$sheet->setCellValue('C'.($gyo_sheet1 + 32), '　専有部工事：');	#共用部がない場合ナンバリング②を削除
		$sheet->setCellValue('C'.($gyo_sheet1 + 32), '　Each rooms：');	#共用部がない場合ナンバリング②を削除
	}

	#専有部工事
	#if(substr($SenyuStartDate,0,4)==substr($SenyuEndDate,0,4)){#年を跨がない場合は西暦を出さない
	if(substr($SenyuStartDate,-4)==substr($SenyuEndDate,-4)){#年を跨がない場合は西暦を出さない
		#$SenyuStartDate = substr($SenyuStartDate,7);
		#$SenyuEndDate = substr($SenyuEndDate,7);
		$SenyuStartDate = substr($SenyuStartDate,0,-4);
		$SenyuEndDate = substr($SenyuEndDate,0,-4);
	}
	if($SenyuEndDate != ""){	#終了日がある場合 又は開始日と終了日が別日の場合
		$sheet->setCellValue('J'.($gyo_sheet1 + 32), $SenyuStartDate."～".$SenyuEndDate );
	}else{		#それ以外は開始日のみ出力
		$sheet->setCellValue('J'.($gyo_sheet1 + 32), $SenyuStartDate);
	}

	#予備日
	#if(substr($YobiStartDate,0,4)==substr($YobiEndDate,0,4)){#年を跨がない場合は西暦を出さない
	if(substr($YobiStartDate,-4)==substr($YobiEndDate,-4)){#年を跨がない場合は西暦を出さない
		#$YobiStartDate = substr($YobiStartDate,7);
		#$YobiEndDate = substr($YobiEndDate,7);
		$YobiStartDate = substr($YobiStartDate,0,-4);
		$YobiEndDate = substr($YobiEndDate,0,-4);
	}
	if( $YobiStartDate != ""){
		if($YobiEndDate != ""){
			$YobiDate = $YobiStartDate."～".$YobiEndDate;
		}else{
			$YobiDate = $YobiStartDate;
		}
		$sheet->setCellValue('J'.($gyo_sheet1 + 33), $YobiDate ); // 33行目
	} else{
		$sheet->getRowDimension($gyo_sheet1 + 33)->setVisible(false);
	}

	#休工日
	if (count($Holiday) > 0) {
		for ($i = 0; $i < count($Holiday); $i++) {
			#$tmpHoliday[] = date('n月j日',strtotime($Holiday[$i]));
			$tmpHoliday[] = date('M j',strtotime($Holiday[$i]));
		}
		$tmp = implode(",",$tmpHoliday);
		$sheet->setCellValue('J'.($gyo_sheet1 + 34), $tmp );
	} else {
		$sheet->getRowDimension($gyo_sheet1 + 34)->setVisible(false);
	}
	$sheet->getRowDimension($gyo_sheet1 + 35)->setVisible(false); // 未使用の行
	$sheet->getRowDimension($gyo_sheet1 + 36)->setVisible(false); // 未使用の行
	$sheet->getRowDimension($gyo_sheet1 + 37)->setVisible(false); // 未使用の行


	#作業時間帯 
	#20200414edit:作業時間の入力場所は工事案内の次のため、9-17:30で固定とするため、以下コメントアウト
	#$last_key = count($WAKUPATTERN[$WakuPattern]['EndTime']) - 1; // 枠の最後の添え字取得
	#$SagyoTime = $WAKUPATTERN[$WakuPattern]['StartTime'][0]."～".$WAKUPATTERN[$WakuPattern]['EndTime'][$last_key];
	#$SagyoTime = ltrim($SagyoTime, '0'); #作業時間の先頭が0の場合は除去
	#$sheet->setCellValue('J'.($gyo_sheet1 + 38), $SagyoTime); // 38行目

/* 英語版はOPなし
	#◆専有部（お部屋内）工事日程・オプション申込の連絡先
	if(!$IfOP){	#オプション無しの場合不要の文言を削除
		$tmpCell = $sheet->getCell('A'.($gyo_sheet1 + 41)); // 41行目
		$tmpCellStr = $tmpCell->getValue();
		$tmpCellStr = str_replace('・オプション申込','',$tmpCellStr);
		$tmpCell->setValue($tmpCellStr);
	}
*/
	#◆工事に関する問い合わせ先
/* 英語版は、アイホンにチェックがあればアイホンだけ表示
	if($DispCompanyBottomChk1 || $DispCompanyBottomChk2 || $DispCompanyBottomChk3){ #どれかにチェックがあれば

		#◆下部に表示する会社名連絡先
		if($DispCompanyBottomChk1){ #管理会社 チェックあり
			if($DispCompanyBottom1FLG == '1'){ // 電話番号記載する
				$tmp = $KanriGaisya."　電話：".$KanriGaisyaTEL;
			}else{ // 管理会社 電話番号記載なし
				$tmp = $KanriGaisya;
			}
			$sheet->setCellValue('D'.($gyo_sheet1 + 51), $tmp);

		}else{
			$sheet->getRowDimension($gyo_sheet1 + 50)->setVisible(false); // 50行目
			$sheet->getRowDimension($gyo_sheet1 + 51)->setVisible(false); // 51行目
		}

		if($DispCompanyBottomChk3){ #その他会社 チェックあり
			if($DispCompanyBottom3FLG == '1'){ // 電話番号記載する
				$tmp = $DispCompanyBottom3."　電話：".$DispCompanyBottomTEL3;
			}else{
				$tmp = $DispCompanyBottom3;
			}
			$sheet->setCellValue('D'.($gyo_sheet1 + 53), $tmp); // 53行目
		}else{
			$sheet->getRowDimension($gyo_sheet1 + 52)->setVisible(false); // 52行目
			$sheet->getRowDimension($gyo_sheet1 + 53)->setVisible(false); // 53行目
		}

		if($DispCompanyBottomChk2){ #アイホン チェックあり
			$sheet->setCellValue('M'.($gyo_sheet1 + 54), $KojiShozokuName); // 54行目
			if($DispCompanyBottom2FLG == '1'){ // 電話番号記載する
				$tmp = "担当：".$KojiTantoName."　電話：".$TantoTEL."（9時～17時30分　土日祝休み）";
			}else{
				$tmp = $KojiTantoName;
			}
			$sheet->setCellValue('D'.($gyo_sheet1 + 55), $tmp); // 55行目

		}else{
			$sheet->getRowDimension($gyo_sheet1 + 54)->setVisible(false); // 54行目
			$sheet->getRowDimension($gyo_sheet1 + 55)->setVisible(false); // 55行目
		}

	}else{ #工事に関する問い合わせ先 チェックがなければ
		$sheet->getRowDimension($gyo_sheet1 + 49)->setVisible(false); // 49行目
		$sheet->getRowDimension($gyo_sheet1 + 50)->setVisible(false);
		$sheet->getRowDimension($gyo_sheet1 + 51)->setVisible(false);
		$sheet->getRowDimension($gyo_sheet1 + 52)->setVisible(false);
		$sheet->getRowDimension($gyo_sheet1 + 53)->setVisible(false);
		$sheet->getRowDimension($gyo_sheet1 + 54)->setVisible(false);
		$sheet->getRowDimension($gyo_sheet1 + 55)->setVisible(false);
		$sheet->getRowDimension($gyo_sheet1 + 56)->setVisible(false);
	}
*/
	if($DispCompanyBottomChk2){ #アイホンにチェックがあれば

		if($DispCompanyBottom2FLG == '1'){ // 電話番号記載する
			#$tmp = "担当：".$KojiTantoName."　電話：".$TantoTEL."（9時～17時30分　土日祝休み）";
			$tmp = "　TEL：".$TantoTEL."（9:00～17:30　only weekdays）";
			$sheet->setCellValue('D'.($gyo_sheet1 + 55), $tmp); // 55行目
		}

		#アイホン以外は非表示
		$sheet->getRowDimension($gyo_sheet1 + 50)->setVisible(false); // 50行目
		$sheet->getRowDimension($gyo_sheet1 + 51)->setVisible(false); // 51行目
		$sheet->getRowDimension($gyo_sheet1 + 52)->setVisible(false); // 52行目
		$sheet->getRowDimension($gyo_sheet1 + 53)->setVisible(false); // 53行目

	}else{ #アイホンにチェックがなければ、非表示にする
		$sheet->getRowDimension($gyo_sheet1 + 49)->setVisible(false); // 49行目
		$sheet->getRowDimension($gyo_sheet1 + 50)->setVisible(false);
		$sheet->getRowDimension($gyo_sheet1 + 51)->setVisible(false);
		$sheet->getRowDimension($gyo_sheet1 + 52)->setVisible(false);
		$sheet->getRowDimension($gyo_sheet1 + 53)->setVisible(false);
		$sheet->getRowDimension($gyo_sheet1 + 54)->setVisible(false);
		$sheet->getRowDimension($gyo_sheet1 + 55)->setVisible(false);
		$sheet->getRowDimension($gyo_sheet1 + 56)->setVisible(false);
	}

######### 1_概要 #########



######### 2_工事内容 #########

	$sheet = $spreadsheet->getSheetByName('2_工事内容');

	#行を変数に格納
	$gyo_sheet2 = 0;

	if($Kanrisitu == '0'){#管理室親機　無　不要な文言を削除した文章で差し替えておく
		#$sheet->setCellValue('D'.($gyo_sheet2 + 5), "●インターホン制御装置を交換"); // 5行目
		$sheet->setCellValue('D'.($gyo_sheet2 + 5), "●Replacing the intercom control unit."); // 5行目
	}

	if($wJikaho == 0 or $wJikaho == 1){ #自火報無し/流用の場合不要の文言を削除
		$sheet->getRowDimension($gyo_sheet2 + 10)->setVisible(false); // 10行目
		$sheet->getRowDimension($gyo_sheet2 + 11)->setVisible(false); // 11行目
		$sheet->getRowDimension($gyo_sheet2 + 12)->setVisible(false); // 12行目
	}

	#$sheet->setCellValue('L'.($gyo_sheet2 + 14), "「約".$wConstTime."分」"); // 14行目
	$sheet->setCellValue('T'.($gyo_sheet2 + 14), '"'.$wConstTime.' minutes"'); // 14行目

	$rowsForInvisible = array(); //非表示対象の行リスト 空の配列として定義

	if($wKansenKoji == 3){#3:1対1 ①共用部工事の内容を非表示
		array_push($rowsForInvisible, 2,3,4,5,6,7,8,9,10,11,12); // 2～12行目

		$sheet->setCellValue('B'.($gyo_sheet2 + 13), ""); // 13行目 #①共用部工事がないので専有部の②をはずす

	}elseif($wKansenKoji == 2){#2:部屋渡り →～の配線工事を実施 文章まるっとなし
		array_push($rowsForInvisible, 6,7,8,9); // 6～9行目

	}elseif($wKansenKoji == 1){#1:玄関子機渡り
		array_push($rowsForInvisible, 8,9); // 8・9行目

	}elseif($wKansenKoji == 0){#0:パイプシャフト渡り
		array_push($rowsForInvisible, 6,7); // 6・7行目

	}
	if($wAutoLock != "1"){#オートロック無しの場合の非表示行を追加
		array_push($rowsForInvisible, 4); // 4行目
	}
	#不要行を非表示に
	for($tmp_i=0 ; $tmp_i<count($rowsForInvisible) ; $tmp_i++){
		$sheet->getRowDimension($rowsForInvisible[$tmp_i])->setVisible(false);
	}
	unset($rowsForInvisible); // クリア

/* 英文にパネルのことは書かない。親機と子機の交換で2行使うため
	if($wOyakiPanel=="1" && $wKokiPanel=="1"){
		$sheet->getRowDimension($gyo_sheet2 + 17)->setVisible(false);
	}elseif($wOyakiPanel=="0" && $wKokiPanel=="0"){
		$sheet->setCellValue('D'.($gyo_sheet2 + 17), "●住戸内インターホン親機と住戸前玄関子機のパネル取付");
	}elseif($wOyakiPanel=="0" && $wKokiPanel=="1"){
		$sheet->setCellValue('D'.($gyo_sheet2 + 17), "●住戸内インターホン親機のパネル取付");
	}elseif($wOyakiPanel=="1" && $wKokiPanel=="0"){
		$sheet->setCellValue('D'.($gyo_sheet2 + 17), "●住戸前玄関子機のパネル取付");
	}else{
		$sheet->getRowDimension($gyo_sheet2 + 17)->setVisible(false); // 17行目
	}
*/
	#タグの内容 英語版は22・23行目は同じ内容で、どちらかを表示
	if($wAutoLock != "1" or $wTagSuu == 0) {#オートロックなし　又は　標準タグなしの場合　文言非表示
		$sheet->getRowDimension($gyo_sheet2 + 20)->setVisible(false); // 20行目
		$sheet->getRowDimension($gyo_sheet2 + 21)->setVisible(false); // 21行目
		$sheet->getRowDimension($gyo_sheet2 + 23)->setVisible(false); // 23行目
	}else{
		$tmpCell = $sheet->getCell('D'.($gyo_sheet2 + 20)); // 20行目 #タグ本数を出力
		$tmpCellStr = $tmpCell->getValue();
		$tmpCellStr = str_replace("▲", $wTagSuu, $tmpCellStr);
		$tmpCell->setValue($tmpCellStr);

		if( $wOwnerTagSuu > 0){	#オーナー渡しある場合
			$tmpCell = $sheet->getCell('E'.($gyo_sheet2 + 21)); // 21行目 #●標準本数-オーナー渡し　▲オーナー渡し
			$tmpCellStr = $tmpCell->getValue();
			$tmpCellStr = str_replace("●", (intval($wTagSuu) - intval($wOwnerTagSuu)), $tmpCellStr);
			$tmpCellStr = str_replace("▲", $wOwnerTagSuu, $tmpCellStr);
			$tmpCell->setValue($tmpCellStr);
		}else{
			$sheet->getRowDimension($gyo_sheet2 + 21)->setVisible(false); // 21行目
		}
		$sheet->getRowDimension($gyo_sheet2 + 22)->setVisible(false); // 22行目 #タグ渡し無しの場合のサイン受領文を非表示
	}


	#工事内容によって表示をコントロールする
	#シート内の図形リストを取得する
	$drawings = $sheet->getDrawingCollection();

	$ryuyoFlg = false; #流用フラグをOFFで初期化 201902 ADD GOE

	#火災感知器の文言を非表示に
	if( $wJikaho == 0 ){
		#不要な画像を削除
		foreach ($drawings as $key=>$drawing){
			if($drawing->getName() ===  '2_工事内容_火災感知器_A'){
				unset($drawings[$key]);
			}elseif($drawing->getName() ===  '2_工事内容_火災感知器_B'){
				unset($drawings[$key]);
			}
		}
		$sheet->getRowDimension($gyo_sheet2 + 25)->setVisible(false); // 25行目
		$sheet->getRowDimension($gyo_sheet2 + 26)->setVisible(false);
		$sheet->getRowDimension($gyo_sheet2 + 27)->setVisible(false);
		$sheet->getRowDimension($gyo_sheet2 + 28)->setVisible(false);
		$sheet->getRowDimension($gyo_sheet2 + 29)->setVisible(false);
		$sheet->getRowDimension($gyo_sheet2 + 30)->setVisible(false);

	}else{
		$ryuyoFlg = true;	#流用フラグをON

		if( $wJikaho == 1 ){#既設流用 流用フラグの判定
			#$sheet->setCellValue('E'.($gyo_sheet2 + 25), "・火災感知器の作業：火災感知器の動作試験");
			$sheet->setCellValue('E'.($gyo_sheet2 + 25), "・Fire alarm: Operation test");
		}elseif($wJikaho == 2){
			$sheet->setCellValue('M'.($gyo_sheet2 + 28), ""); // 28行目 「本工事では～」の文言をけす
		}
		if( $wKasaiHeya == 0 ){#火災抵抗器交換部屋立入り無しの場合　部屋立ち入りの文言削除
			$sheet->getRowDimension($gyo_sheet2 + 29)->setVisible(false); // 29行目
			$sheet->getRowDimension($gyo_sheet2 + 30)->setVisible(false);
		}
	}

	#ガス漏れの文言を非表示に
	if( $wGasKoji == 0 ){	#なしの場合は画像と文言を削除
		#不要な画像を削除
		foreach ($drawings as $key=>$drawing){
			if($drawing->getName() ===  '2_工事内容_ガス漏れ'){
				unset($drawings[$key]);
				break;
			}
		}
		$sheet->getRowDimension($gyo_sheet2 + 31)->setVisible(false); // 31行目
		$sheet->getRowDimension($gyo_sheet2 + 32)->setVisible(false);
		$sheet->getRowDimension($gyo_sheet2 + 33)->setVisible(false);
		$sheet->getRowDimension($gyo_sheet2 + 34)->setVisible(false);

	}elseif( $wGasKoji == 1 ){#流用
		#$sheet->setCellValue('E'.($gyo_sheet2 + 31), "・ガス漏れ検知器の作業：動作試験");
		$sheet->setCellValue('E'.($gyo_sheet2 + 31), "・Gas leakage alarm: Operation test");
		$ryuyoFlg = true;	#流用フラグをON

	}elseif( $wGasKoji == 2 ){#交換
		$sheet->setCellValue('M'.($gyo_sheet2 + 34), "");#34 「本工事では～」の文言をけす

	}

	#防犯センサーの文言を非表示に
	if( $wBohanKoji == 0 ){
		#不要な画像を削除
		foreach ($drawings as $key=>$drawing){
			if($drawing->getName() ===  '2_工事内容_防犯センサー'){
				unset($drawings[$key]);
				break;
			}
		}
		$sheet->getRowDimension($gyo_sheet2 + 35)->setVisible(false); // 35行目
		$sheet->getRowDimension($gyo_sheet2 + 36)->setVisible(false);
		$sheet->getRowDimension($gyo_sheet2 + 37)->setVisible(false);
		$sheet->getRowDimension($gyo_sheet2 + 38)->setVisible(false);
	}else{
		$ryuyoFlg = true;	#流用フラグをON

		if( $wBohanKoji == 1 ){	#１F住戸のみの表示を消す
			#$sheet->setCellValue('W'.($gyo_sheet2 + 35), "※1階住戸のみ");
			$sheet->setCellValue('W'.($gyo_sheet2 + 35), "※1st floor room unit only");
		}elseif($wBohanKoji ==2){ #全住戸
			$sheet->setCellValue('W'.($gyo_sheet2 + 35), "");
		}elseif($wBohanKoji ==3){ #設置住戸のみ
			#$sheet->setCellValue('W'.($gyo_sheet2 + 35), "※設置住戸のみ");
			$sheet->setCellValue('W'.($gyo_sheet2 + 35), "※Installed units only");
		}
	}

	#漏水センサーの文言を非表示に
	if( $wRosuiKoji == 0 ){
		#不要な画像を削除
		foreach ($drawings as $key=>$drawing){
			if($drawing->getName() ===  '2_工事内容_漏水センサー'){
				unset($drawings[$key]);
				break;
			}
		}
		$sheet->getRowDimension($gyo_sheet2 + 39)->setVisible(false); // 39行目
		$sheet->getRowDimension($gyo_sheet2 + 40)->setVisible(false);
		$sheet->getRowDimension($gyo_sheet2 + 41)->setVisible(false);
		$sheet->getRowDimension($gyo_sheet2 + 42)->setVisible(false);

	}elseif($wRosuiKoji == 1){#流用
		#$sheet->setCellValue('E'.($gyo_sheet2 + 39), "・漏水センサーの作業：動作試験");
		$sheet->setCellValue('E'.($gyo_sheet2 + 39), "・Water leakage sensor: Operation test");
		$ryuyoFlg = true;	#流用フラグをON

	}elseif( $wRosuiKoji == 2 ){#交換
		$sheet->setCellValue('M'.($gyo_sheet2 + 42), "");#42「本工事では～」の文言をけす

	}

	if($ryuyoFlg == false){	#流用がない場合は既設配線工事はしません文言を削除
		$sheet->getRowDimension($gyo_sheet2 + 43)->setVisible(false); // 43行目
		$sheet->getRowDimension($gyo_sheet2 + 44)->setVisible(false);
	}

	#印刷設定
#	$sheet->getPageSetup()->setPrintArea('A1:AO44');
#
#	$sheet->getPageSetup()->setFitToWidth(1);
//	$sheet->getPageSetup()->setFitToHeight(0);


######### 2_工事内容 #########



######### 3_注意事項 #########

	$sheet = $spreadsheet->getSheetByName('3_注意事項');

	#行を変数に格納
	$gyo_sheet3 = 0;

	if($wKansenKoji == 3){ #3:1対1
		#3注意事項のシートは出力不要
		for($tmp_i=($gyo_sheet3 + 1) ; $tmp_i<($gyo_sheet3 + 80); $tmp_i++){
			$sheet->getRowDimension($tmp_i)->setVisible(false);
		}

		$del_key = array();
		$drawings = $sheet->getDrawingCollection();
		foreach ($drawings as $key=>$drawing){
			if( ($drawing->getName() === '3_上attention') OR 
				($drawing->getName() === '3_上duringinstllation') OR 
				($drawing->getName() === '3_上NEW集合玄関機') OR 
				($drawing->getName() === '3_上右矢印罰') OR 
				($drawing->getName() === '3_上旧親機') OR 
				($drawing->getName() === '3_上左矢印丸') OR 
				($drawing->getName() === '3_上旧玄関子機') OR 

				($drawing->getName() === '3_中new-new-newEN') OR 
				($drawing->getName() === '3_中NEW集合玄関機') OR 
				($drawing->getName() === '3_中NEW右矢印丸') OR 
				($drawing->getName() === '3_中NEW親機') OR 
				($drawing->getName() === '3_中NEW左矢印丸') OR 
				($drawing->getName() === '3_中NEW玄関子機') OR 

				($drawing->getName() === '3_下attention') OR 
				($drawing->getName() === '3_下並行稼働1') OR
				($drawing->getName() === '3_下並行稼働2') ){

				$del_key[] = $key; // 削除する画像のキー
			}
		}
		foreach($del_key as $val) {
			unset($drawings[$val]);
		}
		$sheet->setSheetState('veryHidden'); #シートを削除

	} else { #1対1 以外

		if ($wAutoLock == "1"){ #AutoLockありなら
			if ($wKirikaehoho == '0'){	#切替方法→停止

				#並行稼働・オートロックなし部分を非表示
				for($tmp_i=($gyo_sheet3 + 31) ; $tmp_i<($gyo_sheet3+80) ; $tmp_i++){
					$sheet->getRowDimension($tmp_i)->setVisible(false); // 31～79行目
				}

				$del_key = array();
				$drawings = $sheet->getDrawingCollection();
				foreach ($drawings as $key=>$drawing){
					if( ($drawing->getName() === '3_下attention') OR 
						($drawing->getName() === '3_下並行稼働1') OR
						($drawing->getName() === '3_下並行稼働2') ){

						$del_key[] = $key; // 削除する画像のキー
					}
				}
				foreach($del_key as $val) {
					unset($drawings[$val]);
				}

				#$sheet->setCellValue('D'.($gyo_sheet3 + 5),"共用部工事開始（".$KyoyoStartDate."）よりインターホン設備の下記の機能がご使用いただけなくなります。"); // 5行目
				$sheet->setCellValue('D'.($gyo_sheet3 + 5),"From ".$KyoyoStartDate." (common area installation work), some functions of the intercom system will not be available."); // 5行目

				if($Kanrisitu == '0'){#管理室親機　無の場合
					$sheet->getRowDimension($gyo_sheet3 + 7)->setVisible(false); // 7行目
					$sheet->getRowDimension($gyo_sheet3 + 9)->setVisible(false); // 9行目
				}else{#管理室親機　有の場合
					$sheet->getRowDimension($gyo_sheet3 + 8)->setVisible(false); // 8行目
					$sheet->getRowDimension($gyo_sheet3 + 10)->setVisible(false); // 10行目
				}

				#英語版は文言修正しなくてよい
				#if ($wTagKoji == "1") {#従前入館方法にタグの指定があれば文言修正
				#	$sheet->setCellValue('E'.($gyo_sheet3 + 15),"居住者様の入館につきましては、従来通り鍵・タグでのオートロック解錠・入館が可能です。お出かけの際は、必ず鍵をお持ちいただきますようお願い申し上げます。");
				#}

				if ($wKansenKoji == "2") { #幹線ルート部屋渡りの場合

					$del_key = array();
					$drawings = $sheet->getDrawingCollection();
					foreach ($drawings as $key=>$drawing){
						if( ($drawing->getName() === '3_中new-new-newEN') OR 
							($drawing->getName() === '3_中NEW集合玄関機') OR 
							($drawing->getName() === '3_中NEW右矢印丸') OR
							($drawing->getName() === '3_中NEW親機') OR
							($drawing->getName() === '3_中NEW左矢印丸') OR
							($drawing->getName() === '3_中NEW玄関子機') ){

							$del_key[] = $key; // 削除する画像のキー
						}
					}
					foreach($del_key as $val) {
						unset($drawings[$val]);
					}

					#$sheet->setCellValue('C'.($gyo_sheet3 + 19),"注意事項");
					$sheet->setCellValue('C'.($gyo_sheet3 + 19),"Notice");
					$sheet->getRowDimension($gyo_sheet3 + 19)->setRowHeight(22);
					#$tmp = "全世帯の工事が完了するまでエントランス集合玄関機からの『呼出・通話・解錠』が出来ません。";
					#$tmp .= "（工事完了していないお部屋が1部屋でもありますと、マンション全体でインターホンが使用できません。）";
					#$tmp .= "必ず工事をしていただきますよう、お願いいたします。";
					$tmp = "Until the installation of all room is completed, 'Call / Talk/ Unlock' cannot be done from the  entrance station unit.";
					$tmp .= "(If there is even one room that has not been installed, the intercom cannot be used in the entire apartment.)";
					$tmp .= "Please be sure to do the installation.";
					$sheet->setCellValue('E'.($gyo_sheet3 + 20),$tmp);
					$sheet->getRowDimension($gyo_sheet3 + 22)->setRowHeight(22);
					$sheet->getRowDimension($gyo_sheet3 + 23)->setRowHeight(22);

					for($tmp_i=($gyo_sheet3 + 24) ; $tmp_i<($gyo_sheet3 + 31) ; $tmp_i++){
						$sheet->getRowDimension($tmp_i)->setVisible(false); // 24～30行目
					}
				}

			}else{	#並行稼働の場合

				#停止・オートロックなし部分を非表示
				for($tmp_i=($gyo_sheet3 + 1) ; $tmp_i<($gyo_sheet3 + 31) ; $tmp_i++){
					$sheet->getRowDimension($tmp_i)->setVisible(false);
				}
				for($tmp_i=($gyo_sheet3 + 61) ; $tmp_i<($gyo_sheet3 + 80) ; $tmp_i++){
					$sheet->getRowDimension($tmp_i)->setVisible(false);
				}

				$del_key = array();
				$drawings = $sheet->getDrawingCollection();
				foreach ($drawings as $key=>$drawing){
					if( ($drawing->getName() === '3_上attention') OR 
						($drawing->getName() === '3_上duringinstllation') OR 
						($drawing->getName() === '3_上NEW集合玄関機') OR 
						($drawing->getName() === '3_上右矢印罰') OR 
						($drawing->getName() === '3_上旧親機') OR 
						($drawing->getName() === '3_上左矢印丸') OR 
						($drawing->getName() === '3_上旧玄関子機') OR 

						($drawing->getName() === '3_中new-new-newEN') OR 
						($drawing->getName() === '3_中NEW集合玄関機') OR 
						($drawing->getName() === '3_中NEW右矢印丸') OR 
						($drawing->getName() === '3_中NEW親機') OR 
						($drawing->getName() === '3_中NEW左矢印丸') OR 
						($drawing->getName() === '3_中NEW玄関子機') ){

						$del_key[] = $key; // 削除する画像のキー
					}
				}
				foreach($del_key as $val) {
					unset($drawings[$val]);
				}
			}

		}else{ #AutoLockなしなら

			$del_key = array();
			$drawings = $sheet->getDrawingCollection();
			foreach ($drawings as $key=>$drawing){
				if( ($drawing->getName() === '3_上attention') OR 
					($drawing->getName() === '3_上duringinstllation') OR 
					($drawing->getName() === '3_上NEW集合玄関機') OR 
					($drawing->getName() === '3_上右矢印罰') OR 
					($drawing->getName() === '3_上旧親機') OR 
					($drawing->getName() === '3_上左矢印丸') OR 
					($drawing->getName() === '3_上旧玄関子機') OR 

					($drawing->getName() === '3_中new-new-newEN') OR 
					($drawing->getName() === '3_中NEW集合玄関機') OR 
					($drawing->getName() === '3_中NEW右矢印丸') OR 
					($drawing->getName() === '3_中NEW親機') OR 
					($drawing->getName() === '3_中NEW左矢印丸') OR 
					($drawing->getName() === '3_中NEW玄関子機') OR 

					($drawing->getName() === '3_下attention') OR 
					($drawing->getName() === '3_下並行稼働1') OR
					($drawing->getName() === '3_下並行稼働2') ){

					$del_key[] = $key; // 削除する画像のキー
				}
			}
			foreach($del_key as $val) {
				unset($drawings[$val]);
			}

			for($tmp_i=($gyo_sheet3 + 1) ; $tmp_i<($gyo_sheet3 + 61) ; $tmp_i++){
				$sheet->getRowDimension($tmp_i)->setVisible(false); // 1～60行目
			}

			#$sheet->setCellValue('J'.($gyo_sheet3 + 64),"共用部工事開始日（".$KyoyoStartDate."）");
			$sheet->setCellValue('J'.($gyo_sheet3 + 64),"From ".$KyoyoStartDate." (common area installation work), some functions of the intercom system will not be available."); // 5行目

			if($Kanrisitu == '0') { #管理室親機　無の場合
				$sheet->getRowDimension(($gyo_sheet3 + 67))->setVisible(false);
				$sheet->getRowDimension(($gyo_sheet3 + 68))->setVisible(false);
			}
		}
	}
######### 3_注意事項 #########



######### 4_仮暗証番号操作方法 #########

	$sheet = $spreadsheet->getSheetByName('4_仮暗証番号操作方法');

	#行を変数に格納
	$gyo_sheet4 = 0;

	#4_仮暗証番号操作方法
	if ($wAutoLock == "1"){ #AutoLockありなら

		#切替方法→停止 かつ 暗証番号あり 且つ 幹線ルート1:1 以外
		if ($wKirikaehoho == '0' and $AnshoNo == '0' and $wKansenKoji != "3"){

			#呼出ボタン→[1]→[1]→[1]→[1]
			$a1 = substr($AnshoNoKojichu,0,1);
			$a2 = substr($AnshoNoKojichu,1,1);
			$a3 = substr($AnshoNoKojichu,2,1);
			$a4 = substr($AnshoNoKojichu,3,1);
			$AnshoNoKojichu = "[呼出ボタン]→[".$a1."]→[".$a2."]→[".$a3."]→[".$a4."]";
			$sheet->setCellValue('Q'.($gyo_sheet4 + 2), $AnshoNoKojichu); // 2行目
			$sheet->setCellValue('G'.($gyo_sheet4 + 3), $AnshoNoKojichu); // 3行目

			if($KyoyoStartDate) {
				#$sheet->setCellValue('G'.($gyo_sheet4 + 4), $KyoyoStartDate."～".$SenyuEndDate ."工事完了まで");
				$sheet->setCellValue('G'.($gyo_sheet4 + 4), $KyoyoStartDate."～".$SenyuEndDate );
			} else {
				#$sheet->setCellValue('G'.($gyo_sheet4 + 4), $SenyuStartDate."～".$SenyuEndDate ."工事完了まで");
				$sheet->setCellValue('G'.($gyo_sheet4 + 4), $SenyuStartDate."～".$SenyuEndDate );
			}
		}else{#切替方法並行稼働 or 暗証番号なしの場合
			for($tmp_i=($gyo_sheet4 + 1) ; $tmp_i<($gyo_sheet4 + 7) ; $tmp_i++){
				$sheet->getRowDimension($tmp_i)->setVisible(false);
			}
		}

	}else{ #AutoLockなしなら
		for($tmp_i=($gyo_sheet4 + 1) ; $tmp_i<($gyo_sheet4 + 7) ; $tmp_i++){
			$sheet->getRowDimension($tmp_i)->setVisible(false);
		}
	}

	#ベスト腕章表示処理
/* 英語版は文章のみ
	if (count($wSagyoinArr) == 0) { // ベスト・腕章なし
		for($tmp_i=($gyo_sheet4 + 8) ; $tmp_i<($gyo_sheet4 + 17) ; $tmp_i++){
			$sheet->getRowDimension($tmp_i)->setVisible(false);
		}

	} else {
		$sheet->getRowDimension($gyo_sheet4 + 8)->setVisible(false); // 8

		if(count($wSagyoinArr) == 1){ #ベストor腕章
			$tmpSagyoin = $wSagyoinArr[0];
		}else{
			$tmpSagyoin = $wSagyoinArr[0].'又は'.$wSagyoinArr[1];
		}

		if ($wShiteiVest == "8") { // その他
			if ($wShiteiVestOther) { // その他の場合の組織名
				$tmp = $wShiteiVestOther." の ".$tmpSagyoin; // 「会社名」の「ベスト腕章」
			} else {
				$tmp = $tmpSagyoin; // 「ベスト腕章」
			}
		} else {
			$tmp = $SHITEIVEST[$wShiteiVest]." の ".$tmpSagyoin; // 「会社名」の「ベスト腕章」
		}
		$tmp_vw = "作業員は身分を明確にするために、 ".$tmp." を着用しております。";
		$sheet->setCellValue('C'.($gyo_sheet4 + 9), $tmp_vw); // 9


		// ベスト・腕章画像貼り付け処理
		function set_vestwansho_img($sheet, $picpath, $cell, $height) {
			if (file_exists($picpath)) {
				$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
				$drawing->setPath($picpath);
				$drawing->setHeight($height);
				$drawing->setOffsetX(2);
				$drawing->setOffsetY(2);
				$drawing->setCoordinates($cell);
				$drawing->setWorksheet($sheet);
				return true;
			} else {
				return false;
			}
		}
		if ($wShiteiVest === "0") { // アイホンのみ、腕章とベスト画像が2種類ある
			if (in_array("ベスト",$wSagyoinArr)) {
				$picfile = "4_0_aiphone_vest.png";
				$picpath = _DOCUMENT_ROOT."images/vestwansho_picdata/".$picfile;
				set_vestwansho_img($sheet, $picpath, "E11", 130);
			}
			if (in_array("腕章",$wSagyoinArr)) {
				$picfile = "4_0_aiphone_wansho.png";
				$picpath = _DOCUMENT_ROOT."images/vestwansho_picdata/".$picfile;
				set_vestwansho_img($sheet, $picpath, "V12", 50);
			}
		} else {
			$height = 130; // デフォルト画像高さ
			// 会社ごとに画像が異なる
			if ($wShiteiVest == "1") {
				$picfile = "4_1_assetlife.png";
			} else if ($wShiteiVest == "2") {
				$picfile = "4_2_mcservice.png";
			} else if ($wShiteiVest == "3") {
				$picfile = "4_3_taisei.png";
			} else if ($wShiteiVest == "4") {
				$picfile = "4_4_hasekogroup.png";
			} else if ($wShiteiVest == "5") {
				$picfile = "4_5_itochu.png";
				$height = 50; // 腕章
			} else if ($wShiteiVest == "6") {
				$picfile = "4_6_mitsubishi.png";
			} else if ($wShiteiVest == "7") {
				$picfile = "4_7_tokyu.png";
				$height = 100; // 腕章
			} else if ($wShiteiVest == "8") {
				$noimg_flg = true; // その他は画像なし
			}

			$picpath = _DOCUMENT_ROOT."images/vestwansho_picdata/".$picfile;
			if ($noimg_flg OR !set_vestwansho_img($sheet, $picpath, "E11", $height)) {
				// 画像がない場合は行非表示にする
				for($tmp_i=($gyo_sheet4 + 11) ; $tmp_i<($gyo_sheet4 + 17) ; $tmp_i++){
					$sheet->getRowDimension($tmp_i)->setVisible(false);
				}
			}
		}
	}
*/
	#英語版
	if (count($wSagyoinArr) == 0) { // ベスト・腕章なし
		for($tmp_i=($gyo_sheet4 + 8) ; $tmp_i<($gyo_sheet4 + 17) ; $tmp_i++){
			$sheet->getRowDimension($tmp_i)->setVisible(false);
		}

	} else {
		if (in_array("ベスト",$wSagyoinArr)) {
			$tmp_vw = "Our staff members wear the uniform.";

			if (in_array("腕章",$wSagyoinArr)) {
				$tmp_vw = "Our staff members wear the uniform or armband.";
			}
		} else {
			$tmp_vw = "Our staff members wear the armband.";
		}
		$sheet->setCellValue('C'.($gyo_sheet4 + 8), $tmp_vw); // 8

		for($tmp_i=($gyo_sheet4 + 9) ; $tmp_i<($gyo_sheet4 + 17) ; $tmp_i++){
			$sheet->getRowDimension($tmp_i)->setVisible(false);
		}
	}
	#ベスト腕章表示処理END

	#パネル
	if($wOyakiPanel !== "0"){ #有にチェックがない場合（＝無？ or チェックしてない）
		$sheet->getRowDimension($gyo_sheet4 + 25)->setVisible(false); // 25行目
	}
	if($wKokiPanel !== "0"){ #有にチェックがない場合（＝無？ or チェックしてない）
		$sheet->getRowDimension($gyo_sheet4 + 26)->setVisible(false); // 26行目
	}
	if(!$IfOP){ #オプション無し
		$sheet->getRowDimension($gyo_sheet4 + 28)->setVisible(false); // 28行目
	}

######### 4_仮暗証番号操作方法 #########


/* 英語版は不要
######### 6_オプション機器のご案内 #########

	$sheet = $spreadsheet->getSheetByName('6_オプション機器のご案内');

	#行を変数に格納
	$gyo_sheet6 = 0;

	if(!$IfOP){ #オプション無し
		for($i=($gyo_sheet6 + 1) ;$i<($gyo_sheet6 + 124) ;$i++){ // 1～123行目
			$sheet->getRowDimension($i)->setVisible(false);
		}
		$sheet->setSheetState('veryHidden'); #シートを削除
	}else{

		#申込方法
		if( $wOPUketuke == "1"){ #1:アンケート
			$sheet->setCellValue('K'.($gyo_sheet6 + 80),"アンケート用紙を1階アンケート回収ボックスへ投函ください。");

		}elseif($wOPUketuke == "2"){ #2:フリーダイヤルとWEB受付（アンケート用紙ではない）
			$sheet->setCellValue('K'.($gyo_sheet6 + 80),"予約受付センター（0120-489-501）にお電話、またはWebポータルサイト（別紙）でご注文ください。");

			for($tmp_i=($gyo_sheet6 + 105); $tmp_i<($gyo_sheet6 + 124); $tmp_i++){ // 105～123
				$sheet->getRowDimension($tmp_i)->setVisible(false);
			}

		}else{ #0:日程変更と同じフリーダイヤル（デフォルト）
			$sheet->setCellValue('K'.($gyo_sheet6 + 80),"予約受付センター（0120-489-501）にお電話でご注文ください。");

			for($tmp_i=($gyo_sheet6 + 105); $tmp_i<($gyo_sheet6 + 124); $tmp_i++){ // 105～123
				$sheet->getRowDimension($tmp_i)->setVisible(false);
			}
		}
		$sheet->setCellValue('K'.($gyo_sheet6 + 85),$OpEndDate); // 85行目
		$sheet->setCellValue('K'.($gyo_sheet6 + 88),$GyosyaName);


		#支払方法
		$tmp = "";
		foreach ($wShiharai as $val) {
			if ($val == "現金") {
				$tmp .= " 専有部工事当日の現金払い（事前にご用意ください）";
				$sheet->setCellValue('F'.($gyo_sheet6 + 92),'協力会社（'.$GyosyaName.')名義にて領収書を発行いたします。'); // 92行目
			} else if ($val == "振込") {
				$tmp .= " 銀行振込";
			} else if ($val == "NP") {
				$tmp .= " NP後払い";
			} else if ($val == "コンビニ") {
				$tmp .= " コンビニ振込票支払い";
				if ($wShiharaiConveni == 1){	#上限有の場合
					$tmp .= " 又は銀行振込";
				}
			}
		}
		$sheet->setCellValue('K'.($gyo_sheet6 + 90),$tmp);

		#支払方法 不要な行を非表示
		if (!in_array("現金",$wShiharai)) {
			$sheet->getRowDimension($gyo_sheet6 + 92)->setVisible(false); // 92
		}
		if (!in_array("振込",$wShiharai)) {
			$sheet->getRowDimension($gyo_sheet6 + 93)->setVisible(false); // 93
		}
		if (!in_array("NP",$wShiharai)) {
			$sheet->getRowDimension($gyo_sheet6 + 94)->setVisible(false); // 94
		}
		if (!in_array("コンビニ",$wShiharai)) {
			$sheet->getRowDimension($gyo_sheet6 + 95)->setVisible(false); // 95
			$sheet->getRowDimension($gyo_sheet6 + 96)->setVisible(false); // 96
			$sheet->getRowDimension($gyo_sheet6 + 97)->setVisible(false); // 97
			$sheet->getRowDimension($gyo_sheet6 + 98)->setVisible(false); // 98
		} else {
			if ($wShiharaiConveni != 1){ #上限有でない
				$sheet->getRowDimension($gyo_sheet6 + 98)->setVisible(false); // 98
			}
		}


		// オプション機器情報
		$OP_COUNT = count($OPInfo);

		if ($OP_COUNT > 6) {
			$OP_COUNT = 6;

			#$ErrorString = array();
			#$ErrorString[] = "現在、オプション7つ以上が対応していません。<br>ご迷惑をおかけしますが、6つで登録して手修正お願いします。";
			#$ErrorLoop = count($ErrorString);
			#$myTemplate = new SPFWTemplate(_ERROR_TPL, $MyCarrier, TRUE);
			#exit;
		}

		// オプション数によって、表示場所が変わるため、先に定義しておく
		if ($OP_COUNT == 1) {
			$op_posi[0]["title"] = "B5";
			$op_posi[0]["image"] = "B7";
			$op_posi[0]["body"]  = "B13";
			$op_posi[0]["price"] = "B16";
			$op_posi[0]["size"]  = "big";

			for($tmp_i=($gyo_sheet6 + 19); $tmp_i<($gyo_sheet6 + 78); $tmp_i++){
				$sheet->getRowDimension($tmp_i)->setVisible(false);
			}
		} else if ($OP_COUNT == 2) {
			$sheet->setCellValue("B19", "オプション①");
			$op_posi[0]["title"] = "B20";
			$op_posi[0]["image"] = "B21";
			$op_posi[0]["body"]  = "B27";
			$op_posi[0]["price"] = "B30";
			$op_posi[0]["size"]  = "mid";
			$sheet->setCellValue("U19", "オプション②");
			$op_posi[1]["title"] = "U20";
			$op_posi[1]["image"] = "U21";
			$op_posi[1]["body"]  = "U27";
			$op_posi[1]["price"] = "U30";
			$op_posi[1]["size"]  = "mid";

			for($tmp_i=($gyo_sheet6 + 5); $tmp_i<($gyo_sheet6 + 19); $tmp_i++){
				$sheet->getRowDimension($tmp_i)->setVisible(false);
			}
			for($tmp_i=($gyo_sheet6 + 33); $tmp_i<($gyo_sheet6 + 78); $tmp_i++){
				$sheet->getRowDimension($tmp_i)->setVisible(false);
			}
		} else if ($OP_COUNT == 3) {
			$sheet->setCellValue("B33", "オプション①");
			$op_posi[0]["title"] = "B34";
			$op_posi[0]["image"] = "B35";
			$op_posi[0]["body"]  = "B41";
			$op_posi[0]["price"] = "B44";
			$op_posi[0]["size"]  = "small";
			$sheet->setCellValue("O33", "オプション②");
			$op_posi[1]["title"] = "O34";
			$op_posi[1]["image"] = "O35";
			$op_posi[1]["body"]  = "O41";
			$op_posi[1]["price"] = "O44";
			$op_posi[1]["size"]  = "small";
			$sheet->setCellValue("AB33", "オプション③");
			$op_posi[2]["title"] = "AB34";
			$op_posi[2]["image"] = "AB35";
			$op_posi[2]["body"]  = "AB41";
			$op_posi[2]["price"] = "AB44";
			$op_posi[2]["size"]  = "small";

			for($tmp_i=($gyo_sheet6 + 5); $tmp_i<($gyo_sheet6 + 33); $tmp_i++){
				$sheet->getRowDimension($tmp_i)->setVisible(false);
			}
			for($tmp_i=($gyo_sheet6 + 47); $tmp_i<($gyo_sheet6 + 78); $tmp_i++){
				$sheet->getRowDimension($tmp_i)->setVisible(false);
			}
		} else if ($OP_COUNT == 4) {
			$sheet->setCellValue("B19", "オプション①");
			$op_posi[0]["title"] = "B20";
			$op_posi[0]["image"] = "B21";
			$op_posi[0]["body"]  = "B27";
			$op_posi[0]["price"] = "B30";
			$op_posi[0]["size"]  = "mid";
			$sheet->setCellValue("U19", "オプション②");
			$op_posi[1]["title"] = "U20";
			$op_posi[1]["image"] = "U21";
			$op_posi[1]["body"]  = "U27";
			$op_posi[1]["price"] = "U30";
			$op_posi[1]["size"]  = "mid";
			$sheet->setCellValue("B47", "オプション③");
			$op_posi[2]["title"] = "B48";
			$op_posi[2]["image"] = "B49";
			$op_posi[2]["body"]  = "B55";
			$op_posi[2]["price"] = "B58";
			$op_posi[2]["size"]  = "mid";
			$sheet->setCellValue("U47", "オプション④");
			$op_posi[3]["title"] = "U48";
			$op_posi[3]["image"] = "U49";
			$op_posi[3]["body"]  = "U55";
			$op_posi[3]["price"] = "U58";
			$op_posi[3]["size"]  = "mid";

			for($tmp_i=($gyo_sheet6 + 5); $tmp_i<($gyo_sheet6 + 19); $tmp_i++){
				$sheet->getRowDimension($tmp_i)->setVisible(false);
			}
			for($tmp_i=($gyo_sheet6 + 33); $tmp_i<($gyo_sheet6 + 47); $tmp_i++){
				$sheet->getRowDimension($tmp_i)->setVisible(false);
			}
			for($tmp_i=($gyo_sheet6 + 61); $tmp_i<($gyo_sheet6 + 78); $tmp_i++){
				$sheet->getRowDimension($tmp_i)->setVisible(false);
			}
		} else if ($OP_COUNT == 5) {
			$sheet->setCellValue("B33", "オプション①");
			$op_posi[0]["title"] = "B34";
			$op_posi[0]["image"] = "B35";
			$op_posi[0]["body"]  = "B41";
			$op_posi[0]["price"] = "B44";
			$op_posi[0]["size"]  = "small";
			$sheet->setCellValue("O33", "オプション②");
			$op_posi[1]["title"] = "O34";
			$op_posi[1]["image"] = "O35";
			$op_posi[1]["body"]  = "O41";
			$op_posi[1]["price"] = "O44";
			$op_posi[1]["size"]  = "small";
			$sheet->setCellValue("AB33", "オプション③");
			$op_posi[2]["title"] = "AB34";
			$op_posi[2]["image"] = "AB35";
			$op_posi[2]["body"]  = "AB41";
			$op_posi[2]["price"] = "AB44";
			$op_posi[2]["size"]  = "small";
			$sheet->setCellValue("B47", "オプション④");
			$op_posi[3]["title"] = "B48";
			$op_posi[3]["image"] = "B49";
			$op_posi[3]["body"]  = "B55";
			$op_posi[3]["price"] = "B58";
			$op_posi[3]["size"]  = "mid";
			$sheet->setCellValue("U47", "オプション⑤");
			$op_posi[4]["title"] = "U48";
			$op_posi[4]["image"] = "U49";
			$op_posi[4]["body"]  = "U55";
			$op_posi[4]["price"] = "U58";
			$op_posi[4]["size"]  = "mid";

			for($tmp_i=($gyo_sheet6 + 5); $tmp_i<($gyo_sheet6 + 33); $tmp_i++){
				$sheet->getRowDimension($tmp_i)->setVisible(false);
			}
			for($tmp_i=($gyo_sheet6 + 61); $tmp_i<($gyo_sheet6 + 78); $tmp_i++){
				$sheet->getRowDimension($tmp_i)->setVisible(false);
			}
		} else if ($OP_COUNT == 6) {
			$sheet->setCellValue("B33", "オプション①");
			$op_posi[0]["title"] = "B34";
			$op_posi[0]["image"] = "B35";
			$op_posi[0]["body"]  = "B41";
			$op_posi[0]["price"] = "B44";
			$op_posi[0]["size"]  = "small";
			$sheet->setCellValue("O33", "オプション②");
			$op_posi[1]["title"] = "O34";
			$op_posi[1]["image"] = "O35";
			$op_posi[1]["body"]  = "O41";
			$op_posi[1]["price"] = "O44";
			$op_posi[1]["size"]  = "small";
			$sheet->setCellValue("AB33", "オプション③");
			$op_posi[2]["title"] = "AB34";
			$op_posi[2]["image"] = "AB35";
			$op_posi[2]["body"]  = "AB41";
			$op_posi[2]["price"] = "AB44";
			$op_posi[2]["size"]  = "small";
			$sheet->setCellValue("B61", "オプション④");
			$op_posi[3]["title"] = "B62";
			$op_posi[3]["image"] = "B63";
			$op_posi[3]["body"]  = "B69";
			$op_posi[3]["price"] = "B72";
			$op_posi[3]["size"]  = "small";
			$sheet->setCellValue("O61", "オプション⑤");
			$op_posi[4]["title"] = "O62";
			$op_posi[4]["image"] = "O63";
			$op_posi[4]["body"]  = "O69";
			$op_posi[4]["price"] = "O72";
			$op_posi[4]["size"]  = "small";
			$sheet->setCellValue("AB61", "オプション⑥");
			$op_posi[5]["title"] = "AB62";
			$op_posi[5]["image"] = "AB63";
			$op_posi[5]["body"]  = "AB69";
			$op_posi[5]["price"] = "AB72";
			$op_posi[5]["size"]  = "small";

			for($tmp_i=($gyo_sheet6 + 5); $tmp_i<($gyo_sheet6 + 33); $tmp_i++){
				$sheet->getRowDimension($tmp_i)->setVisible(false);
			}
			for($tmp_i=($gyo_sheet6 + 47); $tmp_i<($gyo_sheet6 + 61); $tmp_i++){
				$sheet->getRowDimension($tmp_i)->setVisible(false);
			}
			for($tmp_i=($gyo_sheet6 + 75); $tmp_i<($gyo_sheet6 + 78); $tmp_i++){
				$sheet->getRowDimension($tmp_i)->setVisible(false);
			}
		}


		// オプション表示部分
		$i = 0;
		foreach ($OPInfo as $key => $val) {

			if ($i == 6) break; // オプション数は6こまでしか対応していない

			#オプション名・価格
			$sheet->setCellValue($op_posi[$i]["title"], $OPInfo[$i]["OPDeviceName"]);
			$sheet->setCellValue($op_posi[$i]["price"], "販売価格:".$OPInfo[$i]["OPPrice"]."円（税込）");

			#説明文
			$tmp_body = "";
			if (isset($KIKICATEGORY_INFO[$OPInfo[$i]["OPCategoryNo"]])) {
				$tmp_body = implode("\n",$KIKICATEGORY_INFO[$OPInfo[$i]["OPCategoryNo"]]);
			}

			#カテゴリ・表示サイズごとの画像・（変更がある場合は説明文）
			$picfile = "";
			$offsetX = 0; // 玄関子機画像で使用
			if ($OPInfo[$i]["OPCategoryNo"] == "1" OR $OPInfo[$i]["OPCategoryNo"] == "2") { // 1:居室親機 2:玄関子機（通話）（標準機器なのでOPで選ばれることはないはず）
				$picfile = ""; // 画像なし

			} else if ($OPInfo[$i]["OPCategoryNo"] == "3") { // 3:カメラ付玄関子機
				$picfile = $OPInfo[$i]["OPKataban"].".jpg"; // 型番のファイル名

				#玄関子機の画像が小さいため、セル位置を変更する
				$offsetX = 100; // offsetもセットしておく
				if ($op_posi[$i]["image"] == "B7") 		 $op_posi[$i]["image"] = "Q7";
				else if ($op_posi[$i]["image"] == "B21") $op_posi[$i]["image"] = "G21";
				else if ($op_posi[$i]["image"] == "U21") $op_posi[$i]["image"] = "Z21";
				else if ($op_posi[$i]["image"] == "B49") $op_posi[$i]["image"] = "G49";
				else if ($op_posi[$i]["image"] == "U49") $op_posi[$i]["image"] = "Z49";

			} else if ($OPInfo[$i]["OPCategoryNo"] == "4") { // 4:ワイヤレス増設親機

				if ($op_posi[$i]["size"] == "big") 		  $picfile = "4_wirelessset_1.png"; // サイズ大
				else if ($op_posi[$i]["size"] == "mid")   $picfile = "4_wirelessset_2.png"; // サイズ中
				else if ($op_posi[$i]["size"] == "small") $picfile = "4_wirelessset_3.png"; // サイズ小

				if ($op_posi[$i]["size"] == "small") {
					// 文字数が多いため、へらす
					$tmp_body = "別のお部屋にいても来客対応ができます。ワイヤレスなので配線工事は不要です。";
					$tmp_body .= "（インターホン親機から基地アンテナまでは有線になります。）";
				}

			} else if ($OPInfo[$i]["OPCategoryNo"] == "5") { // 5:ワイヤレスチャイム
				$picfile = ""; // 画像なし

				if(strpos($OPInfo[$i]["OPDeviceName"],'追加受信機')){ // ワイヤレスチャイム追加受信機の場合
					$tmp_body = $KIKICATEGORY_INFO[$OPInfo[$i]["OPCategoryNo"]][0]; #機器の説明だけ出力
				}

			} else if ($OPInfo[$i]["OPCategoryNo"] == "6") { // 6:受話器

				if ($op_posi[$i]["size"] == "big")	 $picfile = "6_jyuwaki_1.png"; // サイズ大
				if ($op_posi[$i]["size"] == "mid")	 $picfile = "6_jyuwaki_2.png"; // サイズ中
				if ($op_posi[$i]["size"] == "small") $picfile = "6_jyuwaki_3.png"; // サイズ小

			} else if ($OPInfo[$i]["OPCategoryNo"] == "7") { // 7:増設親機
				$picfile = ""; // 画像なし

			} else if ($OPInfo[$i]["OPCategoryNo"] == "8") { // 8:スマホ連動

				if ($op_posi[$i]["size"] == "big")	 $picfile = "8_sumaho_1.png"; // サイズ大
				if ($op_posi[$i]["size"] == "mid")	 $picfile = "8_sumaho_2.png"; // サイズ中
				if ($op_posi[$i]["size"] == "small") $picfile = "8_sumaho_3.png"; // サイズ小

			} else if ($OPInfo[$i]["OPCategoryNo"] == "9") { // 9:タグ

				$tmp_body = str_replace("標準数をお渡しします","標準数（".$wTagSuu."本）をお渡しします",$tmp_body);

				if (strstr($OPInfo[$i]["OPDeviceName"],'ノンタッチキーヘッド')){ // ノンタッチキーヘッド
					if ($op_posi[$i]["size"] == "big") 			$picfile = "9_nontouchkeyhead_1.png"; // サイズ大
					else if ($op_posi[$i]["size"] == "mid") 	$picfile = "9_nontouchkeyhead_2.png"; // サイズ中
					else if ($op_posi[$i]["size"] == "small") 	$picfile = "9_nontouchkeyhead_3.png"; // サイズ小
				} else { // ノンタッチタグ
					if ($op_posi[$i]["size"] == "big") 			$picfile = "9_nontouchtag_1.png"; // サイズ大
					else if ($op_posi[$i]["size"] == "mid") 	$picfile = "9_nontouchtag_2.png"; // サイズ中
					else if ($op_posi[$i]["size"] == "small") 	$picfile = "9_nontouchtag_3.png"; // サイズ小
				}
			}

			#画像
			$picpath = _DOCUMENT_ROOT."images/kikipicdata/".$picfile;
			if (($picfile == "" OR !file_exists($picpath))) { // 画像ファイルがない場合
				$picpath = _DOCUMENT_ROOT."images/kikipicdata/"."0_NoImage.png";
			}
			$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
			$drawing->setPath($picpath);
			$drawing->setHeight(153);
			$drawing->setOffsetX(2 + $offsetX);
			$drawing->setOffsetY(2);
			$drawing->setCoordinates($op_posi[$i]["image"]);
			$drawing->setWorksheet($sheet);

			#説明文
			$sheet->setCellValue($op_posi[$i]["body"], $tmp_body);

			$i += 1;
		}

	}

######### 6_オプション機器のご案内 #########
*/

/* 英語版は不要
######### 7_火災警報 #########

	$sheet = $spreadsheet->getSheetByName('7_火災警報_220号');

	#行を変数に格納
	$gyo_sheet7 = 0;

	if( $ShoboTokurei != 3) {#220号共同住宅用　以外の場合 非表示
		for($i=($gyo_sheet7 + 1); $i<($gyo_sheet7 + 40) ; $i++){
			$sheet->getRowDimension($i)->setVisible(false); // 1～38行目
		}

		$del_key = array();
		$drawings = $sheet->getDrawingCollection();
		foreach ($drawings as $key=>$drawing){
			if( ($drawing->getName() === '7_近隣火災警報鳴動') ){

				$del_key[] = $key; // 削除する画像のキー
			}
		}
		foreach($del_key as $val) {
			unset($drawings[$val]);
		}

	} else {
		$tmpCell = $sheet->getCell('C'.($gyo_sheet7 + 4)); // マンション名を置き換え
		$tmpCellStr = $tmpCell->getValue();
		$tmpCellStr = str_replace("○○様", $BukkenName.'様', $tmpCellStr);
		$tmpCell->setValue($tmpCellStr);

		$tmpKasai = "「火災」";
		$tmpGus = "「ガス漏れ」"; 
		if($wJikaho == 0){ #0:自火報連動なし
			$tmpKasai = "";
		}
		if( $wGasKoji == 0 ){ #0:ガス漏れ検知器連動なし
			$tmpGus = ""; 
		}

		$tmpCell = $sheet->getCell('C'.($gyo_sheet7 + 18)); // 火災防犯置き換え
		$tmpCellStr = $tmpCell->getValue();
		$tmpCellStr = str_replace("「ガス漏れ」", $tmpGus, $tmpCellStr);
		$tmpCellStr = str_replace("「火災」", $tmpKasai, $tmpCellStr);
		$tmpCell->setValue($tmpCellStr);

		$tmpCell = $sheet->getCell('C'.($gyo_sheet7 + 19)); // 火災防犯置き換え
		$tmpCellStr = $tmpCell->getValue();
		$tmpCellStr = str_replace("「ガス漏れ」", $tmpGus, $tmpCellStr);
		$tmpCellStr = str_replace("「火災」", $tmpKasai, $tmpCellStr);
		$tmpCell->setValue($tmpCellStr);

		if($ShoboSikenhoho == "0"){#0:着工前後

			$sheet->setCellValue('C'.($gyo_sheet7 + 18), "共用部工事開始前の".$KyoyoStartDate."に「近隣火災」警報の試験を行います。"); // 18行目
			$sheet->setCellValue('C'.($gyo_sheet7 + 19), "「近隣火災」警報の試験の際は、試験中の住戸とその近隣住戸でも「近隣火災」警報が鳴動します。");
			$sheet->setCellValue('C'.($gyo_sheet7 + 21), "専有部工事完了の都度、「非常」「ガス漏れ」「火災」警報の試験を行います。");
			$sheet->setCellValue('C'.($gyo_sheet7 + 22), "「非常」「ガス漏れ」「火災」警報は管理室と試験中の住戸のみ警報が鳴動します。");
			$sheet->setCellValue('B'.($gyo_sheet7 + 24), "③");
			$sheet->setCellValue('C'.($gyo_sheet7 + 24), "専有部工事完了後の".$SenyuEndDate."に「近隣火災」警報の試験を行います。");
			$sheet->setCellValue('B'.($gyo_sheet7 + 25), "※");
			$sheet->setCellValue('C'.($gyo_sheet7 + 25), "試験方法は①と同様です。");

		} else if($ShoboSikenhoho == "1"){#1:日時記入(終了後)

			$tmp_date = $SenyuEndDate;
			if ($ShoboSikenDate != "0000-00-00") {
				$tmp_date = $ShoboSikenDate; // 試験日
				if($ShoboSikenJikan){
					$tmp_date = $ShoboSikenDate." ".$ShoboSikenJikan; // 試験日 時間
					if($ShoboSikenJikan2){
						$tmp_date = $ShoboSikenDate." ".$ShoboSikenJikan."～".$ShoboSikenJikan2; // 試験日 開始時間～終了時間
					}
				}
			}
			$sheet->setCellValue('C'.($gyo_sheet7 + 21), "専有部工事完了後の".$tmp_date."に「近隣火災」警報の試験を行います。"); // 21行目
			unset($tmp_date);

		} else if($ShoboSikenhoho == "2"){#2:都度
			// そのまま

		}

	}

######### 7_火災警報 #########
*/

/* 英語版は不要
######### 8ノンタッチシステムの導入について#########

	$sheet = $spreadsheet->getSheetByName('8_ノンタッチシステムの導入について');

	#行を変数に格納
	$gyo_sheet8 = 0;

	#オートロックありのみ
	if($wAutoLock == "1"){

		#1keyの場合
		if($nonTouchFLG and !strstr($wRNNyukan ,'0') AND $wTagSuu > 0){#ノンタッチシステム導入 & RN後 鍵なし & 標準配布あり

			// タグ切替日を設定
			$tmpCell = $sheet->getCell('B'.($gyo_sheet8 + 3)); // 3行目
			$tmpCellStr = $tmpCell->getValue();
			$tmpCellStr = str_replace("●月●日",date('n月j日',strtotime( $TagKirikaeDate )),$tmpCellStr);
			$tmpCell->setValue($tmpCellStr);

			if($wOwnerTagSuu <= 0){#オーナータグ0本の場合
				// タグ本数
				$tmpCell = $sheet->getCell('C'.($gyo_sheet8 + 13)); // 13行目
				$tmpCellStr = $tmpCell->getValue();
				$tmpCellStr = str_replace("【標準本数】",$wTagSuu,$tmpCellStr);
				$tmpCell->setValue($tmpCellStr);

				$sheet->getRowDimension($gyo_sheet8 + 14)->setVisible(false); // 14行目
				$sheet->getRowDimension($gyo_sheet8 + 15)->setVisible(false); // 15行目
				$sheet->getRowDimension($gyo_sheet8 + 16)->setVisible(false); // 16行目
			}else{
				// タグ本数
				$tmpCell = $sheet->getCell('C'.($gyo_sheet8 + 14)); // 14行目
				$tmpCellStr = $tmpCell->getValue();
				$tmpCellStr = str_replace("【標準本数】",$wTagSuu,$tmpCellStr);
				$tmpCell->setValue($tmpCellStr);

				// タグ本数　区分所有者
				$tmpCell = $sheet->getCell('C'.($gyo_sheet8 + 15)); // 15行目
				$tmpCellStr = $tmpCell->getValue();
				$tmpCellStr = str_replace("【標準本数‐外部オーナー本数】",(intval($wTagSuu) - intval($wOwnerTagSuu)),$tmpCellStr);
				$tmpCell->setValue($tmpCellStr);

				// タグ本数　オーナー
				$tmpCell = $sheet->getCell('C'.($gyo_sheet8 + 16)); // 16行目
				$tmpCellStr = $tmpCell->getValue();
				$tmpCellStr = str_replace("【外部オーナー本数】",$wOwnerTagSuu,$tmpCellStr);
				$tmpCell->setValue($tmpCellStr);

				$sheet->getRowDimension($gyo_sheet8 + 13)->setVisible(false); // 13行目
			}

		}else{
			for($i=($gyo_sheet8 + 1);$i<($gyo_sheet8 + 19);$i++){
				$sheet->getRowDimension($i)->setVisible(false);
			}
			$sheet->setSheetState('veryHidden'); #シートを削除
		}
	}else{
		for($i=($gyo_sheet8 + 1);$i<($gyo_sheet8 + 19);$i++){
			$sheet->getRowDimension($i)->setVisible(false);
		}
		$sheet->setSheetState('veryHidden'); #シートを削除
	}

######### 8ノンタッチシステムの導入について#########
*/


/*
	###★ Excel(.xlsx)としてtFileFに登録する
	$writer = new XlsxWriter($spreadsheet);

	$kojiannai_path = './tmp/a'.date('YmdHis').'.xlsx';
	$writer->save($kojiannai_path);
	$img_file = file_get_contents( $kojiannai_path );

	//画像を保存するSQL文の実行
	$myFile = new File($myDB);

	$myFile->FileCD = -1;
	$myFile->BukkenCD = $editBukkenCD;
	$myFile->SekoStatus = 5; #5作成ファイル
	$myFile->P001 = '工事案内_'.date('YmdHis').'.xlsx'; #ファイル名
	$myFile->P002 = 'xlsx';  #拡張子

	$myFile->File = $img_file;
	$myFile->Creator = $loginUserCD;
	$myFile->Updater = $loginUserCD;

	if (!$myFile->executeUpdate()){
		$ErrorString = array();
		$ErrorString[] = "メニューマスタ情報の更新に失敗しました。";
		showAdminSorryPage($ErrorString);
	}
	#テンポラリーファイルを削除する。
	$Command = "rm -f ".$kojiannai_path;
	shell_exec($Command);
	###★ Excel(.xlsx)としてtFileFに登録する End
*/

	//ダウンロード用
	//MIMEタイプ：https://technet.microsoft.com/ja-jp/ee309278.aspx
	header("Content-Description: File Transfer");
	$wFileName = "工事案内（英語）_".$BukkenName."_".date('YmdHis').".xlsx" ;
	$wFileName = mb_convert_encoding($wFileName, "SJIS", "UTF-8"); // 20181217 IEでの文字化け対応

	header("Content-Disposition: attachment; filename=".$wFileName);
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Transfer-Encoding: binary');
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	header('Expires: 0');
	ob_end_clean(); //バッファ消去

	$writer = new XlsxWriter($spreadsheet);
	$writer->save('php://output');

?>