<?php
function CheckAccess( $myDB,$wID, $wPasswd ){
	include_once "setting.properties";

	$ipAddress = $_SERVER["REMOTE_ADDR"];
	//$ip1 = "61.202.233.190" ;#アイホンさん
	#$OKIP[] = "211.6.109.126" ;#アイホンさん
	#$OKIP[] = "112.71.123.127";#アイホンさん
	#$ip5 = "192.168.98.";   #サーバ群
	#$ip6 = "219.111.5.92" ;
	#$ip2 = "202.171.130.45" ;//事務所新プロバイダ
	$ip2 = "192.168.98";//VPN接続
	$ip3 = "192.168.97";//20191029ueda

	if( $ipAddress == $ip1 or $ipAddress == $ip2 or $ipAddress == $ip3 ){
		return 1;
		exit;
	}
	#	return 2;
	#	exit;

	#テスト
	#$wID = 'nespekimura';
	#$wPasswd = '1qaz2wsx';
	$Today2 = date('Y-m-d H:i:s', strtotime("-1 day"));
	$Today = date('Y-m-d H:i:s');
	##############################################
	#3回以上ログインに失敗しているIPは利用できない
	##############################################
	$pdb = new PDO('mysql:host='._HOST_NAME.';dbname='._MAIN_DB.';charset=utf8',_USER_NAME,_PASSWD);

	// SQL文を作成 NGのもの
	$stmt = $pdb->query("select IPAddress FROM tOKIPM WHERE MukouFlg = FALSE and IPAddress = '$ipAddress' and OKNG = 1  and Created > '$Today2'");

	//fetchAllで結果を全件配列で取得
	$NGIP = $stmt->fetchAll();
	$pdb = null;
	#echo "<br>".__LINE__."行目NGIP[0]['IPAddress']:".$NGIP[0]['IPAddress'] ;
	if( count($NGIP) > 2 ){
		$ErrorString[] = "該当するユーザは見つかりませんでした。w";
		$IfError = TRUE;
	#echo "<br>".__LINE__."行目3回ログイン失敗IPでNG:".$wClientID ;
		#include_once("login_form.php");
		return 2;
		exit;
	}

	################################################
	#登録済みのIPかみる
	################################################
	$pdb = new PDO('mysql:host='._HOST_NAME.';dbname='._MAIN_DB.';charset=utf8',_USER_NAME,_PASSWD);
	// SQL文を作成
	// クエリ実行（データを取得）
	$stmt = $pdb->query("select IPAddress FROM tOKIPM WHERE MukouFlg = FALSE and IPAddress = '$ipAddress' and  OKNG = 0 and Created > '$Today2'");

	//fetchAllで結果を全件配列で取得
	$OKIP = $stmt->fetchAll();
	$pdb = null;

	if( count($OKIP) > 0){#①
		#ログイン記録する。
		$pdb = new PDO('mysql:host='._HOST_NAME.';dbname='._MAIN_DB.';charset=utf8',_USER_NAME,_PASSWD);
		$stmt = $pdb->query("insert into tOKIPM ( IPCD, IPAddress, OKNG,ID,MukouFlg, Created  ) values ( NULL, '$ipAddress' , '3','$wID','0', '$Today' ) ");
		$pdb = null;
		return 1;
		exit;
	}# END

	#アドレスが登録されていなくても3回までは見逃す。
	#ログインパスワードを教えたらNG


	########################################################
	#登録がないIPでもログインが成功すればOK　ただし3回失敗したらNGIPとして登録し以後アクセス不可
	########################################################
	# アクティブ会員かどうかの判定して、失敗したらNGIPに登録
	########################################################

	// データベースコネクト
	$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
	if (!$myDB->Connection)
		trigger_error("SPFWDatabase Failed.", E_USER_ERROR);

	$clsUser = new User($myDB);
	if (!$clsUser->executeSelect("ID = '" . $myDB->escapeString($wID) . "' AND Passwd = '" . $myDB->escapeString($wPasswd) . "' AND MukouFlg = FALSE"))
		trigger_error("Getting User Failed.", E_USER_ERROR);

	if ($clsUser->RecCnt != 1  ){#アイホン社内、ネスぺ社内から無条件にOK ログインNG　NGIPとして　登録する
		#ログイン失敗で記録する。
		$pdb = new PDO('mysql:host='._HOST_NAME.';dbname='._MAIN_DB.';charset=utf8',_USER_NAME,_PASSWD);
		$stmt = $pdb->query("insert into tOKIPM ( IPCD, IPAddress, OKNG,ID,MukouFlg, Created  ) values ( NULL, '$ipAddress' , '1','$wID','0', '$Today' ) ");
		$pdb = null;

	#echo "<br>".__LINE__."行目ログインNG:"."insert into tOKIPM ( IPCD, IPAddress, OKNG,MukouFlg, Created  ) values ( NULL, '$ipAddress' , '1','0', '$Today' ) ";	
		$ErrorString[] = "該当するユーザは見つかりませんでした。IPエラー";
	}else{#登録なし　ログインOK　登録する

		$IP = "ok" ;
		$pdb = new PDO('mysql:host='._HOST_NAME.';dbname='._MAIN_DB.';charset=utf8',_USER_NAME,_PASSWD);
		$stmt = $pdb->query("insert into tOKIPM ( IPCD, IPAddress, OKNG,ID, MukouFlg,Created  ) values (NULL, '$ipAddress' , '0','$wID','0',  '$Today' ) ");
		$pdb = null;
		echo "<br>".__LINE__."行目:OK".$wClientID ;
		return 1;
		exit;

	}

	#エラーならでる
	if( $IfError){
		return 2 ;#NG
		include_once("login_form.php");
		exit;
	}

	#echo "<br>".__LINE__."行目:".$wClientID ;

}
	#	unset($myLog);
?>
