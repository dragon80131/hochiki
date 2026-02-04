<?php
#	$isAdminMode = TRUE;

	include_once "setting.properties";
	include_once _INC_DIR . "global.inc";

	include_once _CLS_DIR . "SPFWDatabase.cls";
	include_once _CLS_DIR . "SPFWLog.cls";
	include_once _CLS_DIR . "SPFWTemplate.cls";
	include_once _CLS_DIR . "SPFWListObject.cls";
	include_once _CLS_DIR . "SPFWDate.cls";
	include_once _CLS_DIR . "SPFWInputCheck.cls";
	include_once _CLS_DIR . "SPFWTools.cls";
	include_once _CLS_DIR . "SPFWParameter.cls";
	include_once _CLS_DIR . "reload.cls";
	include_once _CLS_DIR . "SPUSSetting.cls";

	########################################################
	# データベース接続
	########################################################

	$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);

	########################################################
	# 管理者認証
	########################################################

#	include_once "authorize.inc";

	########################################################
	# ターゲットクライアント確定
	########################################################

	if ($IfASP) {
		if ($IfAdminSystem)
			$TargetClientCD = $editClientCD;
		else if ($IfAdminClient)
			$TargetClientCD = $MyClientCD;
	}
	else
		$TargetClientCD = '0';

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
	if (move_uploaded_file($_FILES["upfile"]["tmp_name"], "uploads/" . $_FILES["upfile"]["name"])) {
	chmod("file/" . $_FILES["upfile"]["name"], 0644);
	$UpFile = $_FILES["upfile"]["name"] ;

		if ($UpFile == "test.txt" ){
		$IfDb = "TRUE";
		}else{
		$IfErrmisFile = "TRUE";
		}

	} else {
#	echo "ユーザファイルをアップロードできません。";
	$IfErrNoUP = "True";
	}
} else {
#  	echo "ユーザファイルが選択されていません。";
	$IfErrNoFile = "True";
}





	########################################################
	# コンテンツ表示
	########################################################

#	SPFWTemplate::setValue("work");
#	SPFWTemplate::setValue("editClientCD", $TargetClientCD);
	$HiddenValues = SPFWTemplate::getValuesToPass();

	$CNT_FILE =  basename($_SERVER["SCRIPT_NAME"], ".php") . ".tpl";
	$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier, TRUE);
	unset($myTemplate);

	unset($myTemplate);
	unset($myLog);
	unset($myDB);
?>
