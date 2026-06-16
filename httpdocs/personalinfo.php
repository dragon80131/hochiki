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
include_once _CLS_DIR . "SPFWParameter.cls";
include_once _CLS_DIR . "SPUSUser.cls";


include_once "./include/common_489.php";

########################################################
# コンテンツ表示
########################################################
if ($wLang == 1) {
	$CNT_FILE = "personalinfo_eng.tpl";
} else {
	$CNT_FILE = "personalinfo.tpl";
}
$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier);

// $myReload = new Reload();
// $myTemplate->setValue("_r_e_l_o_a_d_", $myReload->embedValue());
// if ($IfASP)
// 	$myTemplate->setValue("vC", $TargetClientCD);
if ($IfNew)
	$myTemplate->setValue("vN", 't');
else
	$myTemplate->setValue("vN", NULL);
$HiddenValues = $myTemplate->getValuesToPass();
$myTemplate->convertTags();
$myTemplate->outputTemplate();

unset($myTemplate);
unset($myLog);
