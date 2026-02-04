<?php

include_once "D:/xampp/htdocs/hochiki/SPFW/inc/setting.properties";
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
include_once _CLS_DIR . "SPUSKojiNittei.cls";

$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
if (!$myDB->Connection)
    trigger_error("SPFWDatabase Failed.", E_USER_ERROR);


// $str = 'すもももももももものうち';

$str = $argv[1];

$mecab = new Mecab\Tagger();
$nodes = $mecab->parseToNode($str);
foreach ($nodes as $n) {
    $items = $n->getFeature();
    $item = explode(",", $items);
    // var_dump($item);

    if(preg_match("/^[ァ-ヾ]+$/u",$item[7])){
        $text .= $item[7];
    }else{
        // echo "カタカナのみではありません";
    }
}
var_dump($text);

// $myBukken = new Bukken($myDB);
// $myBukken->$editBukkenCD = -1;
// if (!$myBukken->executeUpdate()){
//     trigger_error("executeUpdate(myBukken) Failed.", E_USER_ERROR);
// }
