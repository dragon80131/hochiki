<?php
$isAdminMode = TRUE;
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
include_once _CLS_DIR . "SPUSSign.cls";
include_once _CLS_DIR . "SPUSReservation.cls";
include_once _CLS_DIR . "SPUSBuilding.cls";

include_once "./include/common.php";

$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
if (!$myDB->Connection)
	trigger_error("SPFWDatabase Failed.", E_USER_ERROR);

$rKey 			= SPFWParameter::getValues("rKey");
$editBukkenCD 	= SPFWParameter::getValues('editBukkenCD');
$editBuildingCD 	= SPFWParameter::getValues('editBuildingCD');
$wID 	= SPFWParameter::getValues('wID');
$wUserCD 	= SPFWParameter::getValues('wUserCD');

########################################################
# 認証チェック
########################################################
$myUser = new User($myDB);

if ($rKey == NULL)
	showSorryPage(_ILLEGAL_ACCESS2);

if (!$myUser->doAuthenticationByRegistKey($rKey))
	trigger_error("doAuthentication Failed.", E_USER_ERROR);

if ($myUser->UserCD == -1)
	showSorryPage(_ILLEGAL_ACCESS2);

$MyUserCD = $myUser->UserCD;

unset($myUser);

########################################################
# 物件情報取得
########################################################
$myBukken = new Bukken($myDB);

if (!$myBukken->executeSelect("MukouFlg = FALSE AND BukkenCD = " . $editBukkenCD, "") || $myBukken->RecCnt != 1)
	trigger_error("Getting Bukken Failed.", E_USER_ERROR);

$myBuilding = new Building($myDB);
if($editBuildingCD){
	if (!$myBuilding->executeSelect("BukkenCD = " . $editBukkenCD . " AND BuildingCD = ".$editBuildingCD." AND MukouFlg = FALSE", "") || $myBuilding->RecCnt != 1) {
		trigger_error("Getting myBuilding Failed.", E_USER_ERROR);
	}
}


$wClientCD = $myBukken->ClientCD ;
$Created = substr( $myBukken->Created  , 0,4);
if($editBuildingCD){
    $Created = substr( $myBuilding->Created  , 0,4);
}
$directory = 'upfile/'.$Created ; // 作成したいディレクトリのパス

// ディレクトリが存在しない場合は作成する
if (!is_dir($directory)) {
    mkdir($directory, 0777, true);
} 

$directory = 'upfile/'.$Created."/".$editBukkenCD ; // 作成したいディレクトリのパス
if($editBuildingCD){
    $directory = 'upfile/'.$Created."/".$editBukkenCD."-".$editBuildingCD ; // 作成したいディレクトリのパス
}
// ディレクトリが存在しない場合は作成する
if (!is_dir($directory)) {
    mkdir($directory, 0777, true);
} 

if (isset($_POST['imgData'])) {
    $imgData = $_POST['imgData'];
    
    // "data:image/png;base64," を削除
    $imgData = str_replace('data:image/png;base64,', '', $imgData);
    $imgData = str_replace(' ', '+', $imgData);
    $data = base64_decode($imgData);


    if ($wID) {
        // ファイルに保存
        $fileName = $wID.'_' . time() . '.jpg';
        $filePath = 'upfile/'.$Created.'/'.$editBukkenCD.'/'.$fileName;
        if($editBuildingCD){
            $filePath = 'upfile/'.$Created.'/'.$editBukkenCD."-".$editBuildingCD.'/'.$fileName;
        }
        file_put_contents($filePath, $data);

        echo "サインが $filePath に保存されました";
    // #調査ミサイル
    // $fh = fopen("aaa.txt", "a");
    // fwrite($fh,"\n s_sign_regist.php内 :" );
    // fclose($fh);
    // #調査ミサイルEnd

        #tSignFにInsert
        $mySign = new Sign($myDB);
        if($editBuildingCD){
            if (!$mySign->executeSelect(" Created >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH) AND BukkenCD = '".$editBukkenCD."' AND BuildingCD = '".$editBuildingCD."' AND ID = '".$wID."' AND MukouFlg = FALSE", "") || $myBukken->RecCnt != 1) {
                trigger_error("Getting Bukken Failed.", E_USER_ERROR);
            }
        }else{
            if (!$mySign->executeSelect(" Created >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH) AND BukkenCD = '".$editBukkenCD."' AND BuildingCD IS NULL AND ID = '".$wID."' AND MukouFlg = FALSE", "") || $myBukken->RecCnt != 1) {
                trigger_error("Getting Bukken Failed.", E_USER_ERROR);
            }
        }

// #調査ミサイル
// $fh = fopen("aaa.txt", "a");
// fwrite($fh,"\n  :".$wID );
// fclose($fh);
// #調査ミサイルEnd	

        $mySign->ClientCD	= $wClientCD ;
        $mySign->BukkenCD	= $editBukkenCD ;
        if($editBuildingCD){
            $mySign->BuildingCD	= $editBuildingCD ;
        }
        $mySign->ID	= $wID ;
        $mySign->UserCD	= $wUserCD ;
        $mySign->FilePath = $filePath;
        $mySign->Creator = $MyUserCD;
        $mySign->Updater = $MyUserCD;

        if (!$mySign->executeUpdate()) {
            trigger_error("executeUpdate(mySign) Failed.", E_USER_ERROR);
        }   
    } 
    unset($mySign);
    #tSignFにInsert
	$myReservation = new Reservation($myDB);

    if($editBuildingCD){
        if (!$myReservation->executeSelect(" TimeFrom >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH) AND  BukkenCD = '".$editBukkenCD."' AND BuildingCD = '".$editBuildingCD."' AND ID = '".$wID."' AND MukouFlg = FALSE", "") || $myBukken->RecCnt != 1) {
            trigger_error("Getting Bukken Failed.", E_USER_ERROR);
        }
    }else{
        if (!$myReservation->executeSelect(" TimeFrom >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH) AND  BukkenCD = '".$editBukkenCD."' AND BuildingCD IS NULL AND ID = '".$wID."' AND MukouFlg = FALSE", "") || $myBukken->RecCnt != 1) {
            trigger_error("Getting Bukken Failed.", E_USER_ERROR);
        }
    }

    $myReservation->KanryoFlg = 1;
    $myReservation->Updater = $MyUserCD;

    if (!$myReservation->executeUpdate()) {
        trigger_error("executeUpdate(mySign) Failed.", E_USER_ERROR);
    }   

    unset($myReservation);

} else {
    echo "エラー: サインデータが受信されませんでした";
}
?>
