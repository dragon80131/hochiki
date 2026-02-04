<?php
/*
 * 2017.11.24 nespe
 * 
 * アイホンさん社内サーバへの接続処理
 * 
 * 本番環境・テスト環境の切り替えは、hosyu.propertiesのHOSYU_DEBUGを変更すること
 *
*/



#20180207 hosyu.propertiesに移動しました
#	// FALSE or TRUE
#	define('HOSYU_DEBUG', TRUE); // 20180118 本番サーバの場合は"FALSE" テストサーバの場合は"TRUE"

$HOSYU_DEBUG = 1 ;
	// pdoでデータベース接続
	// 件名システム（ミルキー）データベース
	function pdo_connect() {
		try{
			if (HOSYU_DEBUG) {
				$dbh = new PDO("mysql:dbname=Milkey1.world;host=localhost", "root", "" );
			} else {
				$dbh = new PDO("oci:dbname=192.168.84.140:1521/Milkey1.world;charset=utf8;", "ORCLAIP", "ORCLAIP" );
			}
			$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			return $dbh;
		}catch(PDOException $e){

			header('Content-Type: text/plain; charset=UTF-8', true, 500);
			exit($e->getMessage());
		}
	}

	// pdoでデータベース接続
	// 修理履歴（SRC）データベース


	function pdo_connect_src() {
		try{
			if (HOSYU_DEBUG) {
				$dbh = new PDO("mysql:dbname=Milkey1.world;host=localhost", "root", "" );
			} else {
				$dbh = new PDO("oci:dbname=192.168.84.130:1521/CCDB;charset=utf8;", "ORCLAIP", "ORCLAIP" );
			}
			$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			return $dbh;
		}catch(PDOException $e){

			header('Content-Type: text/plain; charset=UTF-8', true, 500);
			exit($e->getMessage());
		}

	}


	// pdoでデータベース接続
	// 受注番号データベース
	function pdo_connect_jyuchu() {
		try{
			if (HOSYU_DEBUG) {
				$dbh = new PDO("mysql:dbname=Milkey1.world;host=localhost", "root", "" );
			} else {
				$dbh = new PDO("oci:dbname=192.168.84.144:1521/MILKEY1T.world;charset=utf8;", "ORCLURI", "ORCLURI" );
			}
			$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			return $dbh;
		}catch(PDOException $e){

			header('Content-Type: text/plain; charset=UTF-8', true, 500);
			exit($e->getMessage());
		}

	}







	#物件NOを指定して件名データを取得する。　納完日が設定されていないものを取得するために利用。
	function getKenmeiOneData($BKN_NO) {

		$dbh = pdo_connect();
	
		$sql = "select ";
		$sql .= "BKN_NO, "; 
		$sql .= "BKN_KBN, ";
		$sql .= "BKN_END_YMD,  ";	#納完日は空
		$sql .= "BKN_NM_ALL,  ";
		$sql .= "JYUSYO_TBN1,  ";
		$sql .= "JYUSYO_TBN2,  ";
		$sql .= "JYUSYO_TBN3,  ";
		$sql .= "JYUSYO_TBN4,  ";
		$sql .= "JYUSYO_JKY1,  ";
		$sql .= "JYUSYO_JKY2,  ";
		$sql .= "JYUSYO_JKY3,  ";
		$sql .= "JYUSYO_JKY4, ";
		$sql .= "HNB_SIJO_KBN,  ";
		$sql .= "KETAI_BNRUI_KBN,  ";
		$sql .= "KANRI_BMON_CD, ";
		$sql .= "KANRI_BSYO_CD, ";
		$sql .= "KANRI_SYIN_CD, ";
		$sql .= "KO_SU, ";
		$sql .= "KANMIN_KBN, ";
		$sql .= "NAITEI_YMD_D, ";
		$sql .= "KANRI_BLOCK_CD, ";
		$sql .= "SYNK_YOTEI_YMD ";
		$sql .= "from T_BKNKHN ";
		$sql .= "WHERE BKN_NO = '".$BKN_NO."'";		#Oracle MySQL共通

        $stmt = $dbh->query($sql);
		if ($KenmeiData = $stmt->fetch(PDO::FETCH_ASSOC)){
			return $KenmeiData;
		}


	}
	function getKenmeiNo($SYIN_CD) {

		$dbh = pdo_connect();
	
		$sql = "select ";
		$sql .= "BKN_NO, "; 
		$sql .= "BKN_KBN, ";
		$sql .= "BKN_END_YMD,  ";	#納完日は空
		$sql .= "BKN_NM_ALL,  ";
		$sql .= "JYUSYO_TBN1,  ";
		$sql .= "JYUSYO_TBN2,  ";
		$sql .= "JYUSYO_TBN3,  ";
		$sql .= "JYUSYO_TBN4,  ";
		$sql .= "JYUSYO_JKY1,  ";
		$sql .= "JYUSYO_JKY2,  ";
		$sql .= "JYUSYO_JKY3,  ";
		$sql .= "JYUSYO_JKY4, ";
		$sql .= "HNB_SIJO_KBN,  ";
		$sql .= "KETAI_BNRUI_KBN,  ";
		$sql .= "KANRI_BMON_CD, ";
		$sql .= "KANRI_BSYO_CD, ";
		$sql .= "KANRI_SYIN_CD, ";
		$sql .= "KO_SU, ";
		$sql .= "KANMIN_KBN, ";
		$sql .= "NAITEI_YMD_D, ";
		$sql .= "KANRI_BLOCK_CD, ";
		$sql .= "SYNK_YOTEI_YMD ";
		$sql .= "from T_BKNKHN ";
		$sql .= "WHERE KANRI_SYIN_CD = '".$SYIN_CD."'";		#Oracle MySQL共通

        $stmt = $dbh->query($sql);
		if ($KenmeiData = $stmt->fetchAll(PDO::FETCH_ASSOC)){
			return $KenmeiData;
		}


	}

	function getKenmeiBusyo($BSYO_CD) {

		$dbh = pdo_connect();

		$sql = "select ";
		$sql .= "BSYO_CD, ";
		$sql .= "BSYO_MEI, ";
		$sql .= "BMON_CD, ";
		$sql .= "ZIP, ";
		$sql .= "ADDRESS, ";
		$sql .= "TEL, ";
		$sql .= "FAX ";
		$sql .= "from T_BSYMST ";
		$sql .= "where DEL_FLG = 0 ";
		$sql .= "AND  BSYO_CD = '".$BSYO_CD."'";

		$stmt = $dbh->query($sql);
		if ($BsyoData = $stmt->fetch(PDO::FETCH_ASSOC)) {
			return $BsyoData;
		}
	}
	function getKenmeiBusyoBmon($BMON_CD) {

		$dbh = pdo_connect();

		$sql = "select ";
		$sql .= "BSYO_CD, ";
		$sql .= "BSYO_MEI, ";
		$sql .= "BMON_CD, ";
		$sql .= "ZIP, ";
		$sql .= "ADDRESS, ";
		$sql .= "TEL, ";
		$sql .= "FAX ";
		$sql .= "from T_BSYMST ";
		$sql .= "where DEL_FLG = 0 ";
		$sql .= "AND  BMON_CD = '".$BMON_CD."'";

		$stmt = $dbh->query($sql);
		if ($BsyoData = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
			return $BsyoData;
		}
	}

	function getKenmeiTanto($SYIN_CD) {

		$dbh = pdo_connect();

		$sql = "select ";
		$sql .= "SYIN_CD, ";
		$sql .= "BSYO_CD, ";
		$sql .= "KBSYO_CD, ";
		$sql .= "SYIN_KNJ, ";
		$sql .= "SYIN_KAN, ";
		$sql .= "YAKU_CD, ";
		$sql .= "MAIL_ADR, ";
		$sql .= "PASS_CD1 ";
		$sql .= "from T_SHNMST ";
		$sql .= "where SYIN_CD = '".$SYIN_CD."'";

		$stmt = $dbh->query($sql);
		if ($TantoData = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
			return $TantoData;
		}
	}

	function getKenmeiJyuchuH($BKN_NO) {

		$dbh = pdo_connect();

		$sql = "select ";
		$sql .= "JUC_NO, ";
		$sql .= "BKN_NO, ";
		$sql .= "RNG_SIN_NO, ";
		$sql .= "NET_NAI_GOKEI, ";
		$sql .= "NET_TAS_GOKEI, ";
		$sql .= "NET_KOJ_GOKEI, ";
		$sql .= "NET_NBK_GOKEI, ";
		$sql .= "SYS_ATE_NM ";
		$sql .= "from T_JUTYUH ";
		$sql .= "where BKN_NO = '".$BKN_NO."'";

		$stmt = $dbh->query($sql);
		if ($JyuchuData = $stmt->fetch(PDO::FETCH_ASSOC)) {
			return $JyuchuData;
		}
	}
	function getKenmeiJyuchuM($JUC_NO) {

		$dbh = pdo_connect();

		$sql = "select ";
		$sql .= "JUC_NO, ";
		$sql .= "JUC_GYO_NO, ";
		$sql .= "MEISAI_KIKI_KBN, ";
		$sql .= "SYHN_CD, ";
		$sql .= "HINBAN, ";
		$sql .= "HINMEI_SIYO, ";
		$sql .= "JUC_SURYO, ";
		$sql .= "HAT_SYORI_SU ";

		$sql .= "from T_JUTYUM ";
		$sql .= "where JUC_NO = '".$JUC_NO."'";

		$stmt = $dbh->query($sql);
		if ($JyuchuMData = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
			return $JyuchuMData;
		}
	}




	function getKenmeiBumonAll() {
		#０部門CD、１部門名、２ブロックCD

		$dbh = pdo_connect();

		$sql = "select BMON_CD,BMON_MEI,BLOCK_CD from T_BMNMST where DEL_FLG = 0 ";

		$stmt = $dbh->query($sql);
		if ($BmonData=$stmt->fetchAll(PDO::FETCH_ASSOC)){
			return $BmonData;
		}
	}


	function getKenmeiBumon($BMON_CD) {

		$dbh = pdo_connect();

		$sql = "select BMON_CD,BMON_MEI,BLOCK_CD from T_BMNMST where DEL_FLG = 0 AND BMON_CD = '".$BMON_CD."'";

		$stmt = $dbh->query($sql);
		if ($BmonData=$stmt->fetch(PDO::FETCH_ASSOC)){
			return $BmonData;
		}
	}


	function getKenmeiBusyoAll() {

		$dbh = pdo_connect();

		$sql = "select BSYO_CD,BSYO_MEI,BMON_CD,ZIP,ADDRESS,TEL,FAX from T_BSYMST where DEL_FLG = 0 ";

		$stmt = $dbh->query($sql);
        if ($BsyoData = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
			return $BsyoData;
		}
	}



/*
	function getKenmeiTantoAll() {

		$dbh = pdo_connect();

		$sql = "select SYIN_CD,BSYO_CD,KBSYO_CD,SYIN_KNJ,SYIN_KAN,YAKU_CD,MAIL_ADR,PASS_CD1 from T_SHNMST where YUKO_YMD = '9999/12/31' ";

		$stmt = $dbh->query($sql);
		if ($TantoData = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
			return $TantoData;
		}
	}


	function getKenmeiTanto($SYIN_CD) {

		$dbh = pdo_connect();

		$sql = "select SYIN_CD,BSYO_CD,KBSYO_CD,SYIN_KNJ,SYIN_KAN,YAKU-CD,MAIL_ADR,PASS_CD1 from T_SHNMST where YUKO_YMD = 9999/12/31 AND  SYIN_CD = '".$SYIN_CD."'";

		$stmt = $dbh->query($sql);
		if ($TantoData = $stmt->fetch(PDO::FETCH_ASSOC)) {
			return $TantoData;
		}
	}


	// 件名システムから件名データをコピーする
	function getKenmeiData() {

		$dbh = pdo_connect();

		$sql = "select ";
		$sql .= "BKN_NO, "; 
		$sql .= "BKN_KBN, ";
		$sql .= "BKN_END_YMD, ";
		$sql .= "BKN_NM_ALL, ";
		$sql .= "JYUSYO_TBN1, ";
		$sql .= "JYUSYO_TBN2, ";
		$sql .= "JYUSYO_TBN3, ";
		$sql .= "JYUSYO_TBN4, ";
		$sql .= "JYUSYO_JKY1, ";
		$sql .= "JYUSYO_JKY2, ";
		$sql .= "JYUSYO_JKY3, ";
		$sql .= "JYUSYO_JKY4, ";
		$sql .= "HNB_SIJO_KBN, ";
		$sql .= "KETAI_BNRUI_KBN, ";
		$sql .= "KANRI_BMON_CD, ";
		$sql .= "KANRI_BSYO_CD, ";
		$sql .= "KANRI_SYIN_CD, ";
		$sql .= "KANMIN_KBN, ";
		$sql .= "SYNK_YOTEI_YMD ";
		$sql .= "from T_BKNKHN ";

		$sql .= " WHERE HNB_SIJO_KBN in ( '2','3','4','5','6','7','8','9','C' )";		#Oracle MySQL共通

		if (HOSYU_DEBUG) {
			// 指定しない
		} else {
			#$sql .= " AND BKN_END_YMD > TO_DATE( '2018-01-15', 'YY/MM/DD') ";		#Oracle用 物件完了日
			$sql .= " AND BKN_END_YMD > TO_DATE(TO_CHAR(SYSDATE - 2 ),'YY-MM-DD')";	#Oracle用 物件完了日
		}

        $stmt = $dbh->query($sql);
		if ($KenmeiData = $stmt->fetchAll(PDO::FETCH_ASSOC)){
			return $KenmeiData;
		}

	}


	// 納入済み有寿命品対象機種
	function getKenmeiData2($KenmeiNo) {

		$Yujumyo = array("NFX-PCN-17","NFX-PCN-17K","NFX-PCN-17L","NFX-PCN-17LW","NFX-PCN-17N","NFX-PCN-17R","NFX-PCN-T19","NFX-PCN-T19L","NFX-PCN-T19LW","NFX-PCN-T19W","NFXV-PCN-17","NFXV-PCN-17K","NFXV-PCN-17L","NFXV-PCN-17LW","NFXV-PCN-17N","NFXV-PCN-17R","NFXV-PCN-T19","NFXV-PCN-T19L","NFXV-PCN-T19LW","NFXV-PCN-T19W","NFX-PCS-17","NFX-PCS-17L","NFX-PCS-17LW","NFX-PCS-17W","NFX-PCS-T19","NFX-PCS-T19L","NFX-PCS-T19LW","NFX-PCS-T19W","NFXV-PCS-17","NFXV-PCS-17L","NFXV-PCS-17LW","NFXV-PCS-17W","NFXV-PCS-T19","NFXV-PCS-T19L","NFXV-PCS-T19LW","NFXV-PCS-T19W","NFX-PCN-17S5","NFX-PCN-17S10","NFX-PCN-17S15","NFX-PCN-17SG5","NFX-PCN-17SG10","NFX-PCN-17SG15","NFXV-PCN-17S5","NFXV-PCN-17S10","NFXV-PCN-17S15","NFXV-PCN-17SG5","NFXV-PCN-17SG10","NFXV-PCN-17SG15","SDX-HLX","SDX-PCM","SDX-PCS","SDX-UPS","NLX-PCN-CS","NLX-PCN-SV","NLX-PCND","NLX-PCNDA-SF-UP","NLX-PCNDB-SF-UP","NLX-PCNDC-SF-UP","NLX-PCNU","NLX-PCNUA-SF-UP","NLX-PCNUB-SF-UP","NLX-PCNUC-SF-UP","NLX-PCSD","NLX-PCSU","FS909M-PS","FS926M-PS","X230-10GP","X310-26FP","BY50S","BN75R","BN75T","BN150R","NVR500");


		$dbh = pdo_connect();

		$str = implode("','", $Yujumyo);
		$str = "'".$str."'";

		$sql = "select ";
		$sql .= "HINBAN, ";
		$sql .= "JUC_SURYO ";
		$sql .= "from T_JUTYUM ";
		$sql .= " where JUC_NO = 'J".$KenmeiNo."'" ;
		$sql .= " and ( HINBAN in($str)";
		$sql .= " or HINBAN like('NFX-PCN-17S__') ";	# 参考URL https://www.dbonline.jp/mysql/select/index7.html
		$sql .= " or HINBAN like('NFX-PCN-17SG__') ) ";	# 2桁のパターンマッチングのため、任意の2文字「__」としている

		$stmt = $dbh->query($sql);
		if ($KenmeiData2 = $stmt->fetchAll(PDO::FETCH_ASSOC)){
			return $KenmeiData2 ;
		}

	}

	// 件名システム NLX★マークチェック
	function getKenmeiDataNLX($BKN_NO) {

		$dbh = pdo_connect();

		$sql = "select ";
		$sql .= "JUC_NO,SYHN_CD "; 
		$sql .= "from T_JUTYUM ";
		$sql .= "WHERE JUC_NO = 'J".$BKN_NO."'";
		$sql .= " AND HINBAN LIKE 'NLX-3X%'";

        $stmt = $dbh->query($sql);
		if ($KenmeiDataNLX = $stmt->fetch(PDO::FETCH_ASSOC)){
			return $KenmeiDataNLX;
		}
	}


	// 申請システムへの連携関数
	function getSinseiKekka($userid, $bdfid) {

		$URL = "http://ntsrvwfdb1.rad.aiphone.co.jp:8080/XFV20/HoshukeiyakuSinsei?userid=".$userid."&bdfid=".$bdfid."&mail=3";

		if (HOSYU_DEBUG) { #テスト環境用
			return 0 ;

		} else { #本番運用
			$SinseiKekka = file_get_contents( $URL );

			return $SinseiKekka;
		}
	}


	// 修理履歴データベースから、物件名・住所で検索しデータを取得する
	function getSyuriData($BukkenName,$Jusyo) {

		$Lastyear = date("Ymd",strtotime("-1 year"));

		$dbh = pdo_connect_src();

		$sql = "select ";
		$sql .= "SK_UKE_NO, "; 		#受付番号
		$sql .= "SA_SYU_MEI, ";		#物件名
		$sql .= "UK_UKE_YMD, ";		#受付日付
		$sql .= "UK_JYOTAI, ";		#故障状態_受付時
		$sql .= "SJ_JYOTAI, ";		#故障状態_修理時
		$sql .= "SJ_KSN_MEMO, ";	#故障内容_箇所
		$sql .= "SJ_SYN_MEMO, ";	#修理内容
		$sql .= "HJ_HMN_YMD1, ";	#訪問日1
		$sql .= "HJ_HMN_HM1, ";		#訪問日時1
		$sql .= "HJ_HMN_YMD2, ";	#訪問日2
		$sql .= "HJ_HMN_HM2, ";		#訪問日時2
		$sql .= "HJ_HMN_YMD3, ";	#訪問日3
		$sql .= "HJ_HMN_HM3";		#訪問日時3
		$sql .= " from V_SYURI1";
		$sql .= " where UK_UKE_YMD > '".$Lastyear."'";
		if($BukkenName){
			$sql .= " and SA_SYU_MEI LIKE '%".$BukkenName."%'"; 
		}
		if($Jusyo){
			$sql .= " and (SA_JYUSYO1 || SA_JYUSYO2 || SA_JYUSYO3) LIKE '%".$Jusyo."%'"; 
		}

		$sql .= " ORDER BY UK_UKE_YMD DESC";

		$stmt = $dbh->query($sql);
		$stmt->execute();
		if ($SyuriData = $stmt->fetchAll(PDO::FETCH_ASSOC)){
			return $SyuriData;
		}

	}


	//標準品取得 件名システム　型番で検索しデータを取得する
	function getStandardProductData($Kataban) {

		$Lastyear = date("Ymd",strtotime("-1 year"));

		$dbh = pdo_connect();

		$sql = "select SYHN_CD,KATABAN,SYHN_MEI,SYATORI,OROSI,TEIKA ";
		$sql .= " from T_GODMST	";
		$sql .= " where KATABAN like '%".$Kataban."%' ";
		$sql .= " and (DEL_KBN = '' or DEL_KBN is null or DEL_KBN = ' ')";

		$stmt = $dbh->query($sql);
		if ($StandardProductData = $stmt->fetchAll(PDO::FETCH_ASSOC)){
			return $StandardProductData;
		}
	}

	//特注品取得 売上システム？　型番で検索しデータを取得する
	function getTokuchuProductData($Kataban, $BSYO_CD) {

		$Lastyear = date("Ymd",strtotime("-1 year"));

		$dbh = pdo_connect();

		$sql = "select SEIBI_NO,KATABAN,TZAI_SU,SYATORI,BKN_MEI  ";
		$sql .= " from t_tkkzai ";
#		$sql .= " where EIG_CD = '".$BSYO_CD."' ";
#		$sql .= " and kataban like '%".$Kataban."%'  and TZAI_SU <> 0 ";
		$sql .= " where kataban like '%".$Kataban."%'  and TZAI_SU <> 0 ";

		$stmt = $dbh->query($sql);
		$stmt->execute();
		if ($TokuchuProductData = $stmt->fetchAll(PDO::FETCH_ASSOC)){
			return $TokuchuProductData;
		}

	}


	//得意先CDを指定して得意先情報を取得する
	function getTokuisakiData( $TOKU_CD ) {

		$dbh = pdo_connect();

		$sql = "select ";
		$sql .= "TOKU_CD, ";
		$sql .= "RYAKU, ";
		$sql .= "G_SYU, ";
		$sql .= "RBT_RITU, ";
		$sql .= "RBT_FLG, ";
		$sql .= "TOKU_MEI, ";
		$sql .= "TIIKI_CD, ";
		$sql .= "DN_FLG, ";
		$sql .= "EIG_CD ";
		$sql .= "from T_TKUMST ";
		$sql .= " where TOKU_CD = '$TOKU_CD'" ;

		$stmt = $dbh->query($sql);
		if ($TokuisakiData = $stmt->fetch(PDO::FETCH_ASSOC)){
#			return $TokuisakiData ;
		}

		if( strlen( $TOKU_CD ) == 7 ){
			if( !$TokuisakiData['RBT_RITU'] || !$TokuisakiData['RBT_FLG'] || !$TokuisakiData['G_SYU'] || !$TokuisakiData['TIIKI_CD'] ){
			 
				$sql = "select ";
				$sql .= "G_SYU, ";
				$sql .= "RBT_RITU, ";
				$sql .= "RBT_FLG, ";
				$sql .= "TIIKI_CD ";
				$sql .= "from T_TKUMST ";
				$sql .= " where TOKU_CD = substr( '$TOKU_CD', 1 , 5 )" ;

				$stmt = $dbh->query($sql);
				if ($oyaTokuisakiData = $stmt->fetch(PDO::FETCH_ASSOC)){
				#	return $TokuisakiData ;
					$TokuisakiData['G_SYU']  	= $oyaTokuisakiData['G_SYU'] ;
					$TokuisakiData['RBT_RITU'] 	= $oyaTokuisakiData['RBT_RITU'] ;
					$TokuisakiData['RBT_FLG'] 	= $oyaTokuisakiData['RBT_FLG'] ;
					$TokuisakiData['TIIKI_CD'] 	= $oyaTokuisakiData['TIIKI_CD'] ;
				}

			}
		}

		return $TokuisakiData ;

	}

	//得意先のリストを取得する 配列
	function getTokuisakiList( $search_val , $search_val2 = null ) {

		$dbh = pdo_connect();

		$sql = "select ";
		$sql .= "TOKU_CD, ";
		$sql .= "RYAKU, ";
		$sql .= "G_SYU, ";
		$sql .= "RBT_RITU, ";
		$sql .= "TIIKI_CD, ";
		$sql .= "TOKU_MEI ";
		$sql .= "from T_TKUMST ";
		$sql .= " where RYAKU not like '0%' AND RYAKU not like 'X%'" ; // 得意先略称で、頭が0かXは除外する（20180727元栄様より）

		if ($search_val) {
			$sql .= " and TOKU_MEI like '%".$search_val."%'";
		}

		if ($search_val2) {
			$sql .= " and RYAKU like '%".$search_val2."%'";
		}


		$stmt = $dbh->query($sql);
		if ($TokuisakiList = $stmt->fetchAll(PDO::FETCH_ASSOC)){
			return $TokuisakiList;
		}
	}



	//受注番号取得＆更新
	function getJyuchuNo( ) {

		$dbh = pdo_connect_jyuchu();

		$sql = "select  SAI_CHAR1 ,  SAI_CHAR5 from T_SAIBAN_URI where SAI_KEY1 = 'ZYZ'  and SAI_KEY2 = '0001' ";

#echo "<br>h_kenmei_connect内435行目:" ;
		$stmt = $dbh->query($sql);
		if ($JyuchuNoData = $stmt->fetch(PDO::FETCH_ASSOC)){
			$JyNo = $JyuchuNoData[SAI_CHAR1]; #期＋連番  例61000005
			$KiNo = $JyuchuNoData[SAI_CHAR5]; #期　　　　例61
		}else{
			echo "Error 443行目";
		}

		if( date('m') < 4 ){
			$strKi = date( 'Y' ) - 1958;#1～3月
		}else{
			$strKi = date( 'Y' ) - 1957 ;#4月以降
		}

#echo "\n\n 454行目 JyNo:".$JyNo ;
		if( $KiNo <> $strKi ) {
			$JyNo = $strKi."000001";
		}


		$JyNoF2 = substr( $JyNo , 0 , 2); #61 F2=Front2桁
		$JyNoB6 = substr( $JyNo , -6 );    #34623　B6＝Back6桁
#echo "\n 458行目 JyNoB6:".$JyNoB6 ;
		$JyNoB6F1 = substr( $JyNoB6 , 0 , 1);# 上の連番の1桁
		$JyNoB6B5 = substr( $JyNoB6 , -5 );# 上の連番の下5桁

#echo "<br>462行目 JyNoB6:".$JyNoB6F1."-".$JyNoB6B5 ;
		if( ctype_digit($JyNoB6F1) ){#6桁目が数字なら
			if( $JyNoB6 == "999999" ){#　連番下6桁が最後の数字なら
				$JyNoB6 = "A00000" ;#　連番に、A00000をセット
			}else{#たとえば000045 なら
				$JyNoB6 = preg_replace('/[^0-9]/', '', $JyNoB6);#数字だけ取り出し ex:45
				$JyNoB6 = $JyNoB6 + 1 ;#1足す ex:46
#echo "\n 473行目 JyNoB6:".$JyNoB6 ;
				$JyNoB6 = sprintf('%06d', $JyNoB6 ); #0埋め ex:000045
			}
#echo "<br>471行目:".$JyNoB6 ;
		}else{#6桁目が数字でなくアルファベット ex:A99999 else A00045
			if( $JyNoB6B5 == "99999" ){#　連番下5桁が最後の数字なら

				$JyNoB6F1 = ++$JyNoB6F1; // 次のアルファベット ex:B
				$JyNoB6 = $JyNoB6F1."00000"; # ex:B00000
			
			}else{# ex:00045
				$JyNoB6B5 = preg_replace('/[^0-9]/', '', $JyNoB6B5);#数字だけ取り出し ex:45
				$JyNoB6B5 = $JyNoB6B5 + 1 ;#1足す ex:46
#echo "\n485行目 JyNoB6B5:".$JyNoB6B5 ;
				$JyNoB6B5 = sprintf('%05d', $JyNoB6B5 ); #0埋め ex:00046
				$JyNoB6 = $JyNoB6F1.$JyNoB6B5 ;# ex:A00046
			}

		}

		#受注番号テーブル更新処理
		try {
			$JyNo = $strKi.$JyNoB6;
#echo "\n494行目 JyNo:".$JyNo ;
			$sql = "UPDATE T_SAIBAN_URI set SAI_CHAR1 = '$JyNo' , SAI_CHAR5 = '$strKi' , UPD_YMD = current_date WHERE SAI_KEY1 = 'ZYZ' AND SAI_KEY2 = '0001'";
			$dbh = pdo_connect_jyuchu();
			$res = $dbh->query($sql);
		} catch(PDOException $e) {
			echo $e->getMessage();
			die();
		}


		#受注番号シーケンステーブル更新処理（Oracleは自動、テスト環境のみ作動）
		if (HOSYU_DEBUG) {
			try {
				$JyNo = $strKi.$JyNoB6;

				$sql = "UPDATE T_SAIBAN_URI set SAI_CHAR1 = '$JyNo' , SAI_CHAR5 = '$strKi'";
				$dbh = pdo_connect_jyuchu();
				$res = $dbh->query($sql);
			} catch(PDOException $e) {
				echo $e->getMessage();
				die();
			}
		}

		$dbh = null;
		return $JyNo ;


	}


	//受注メモ.受注テーブルにレコードをInsert
	function insertJyuchuSqlMoji($Column ) {

		$sqlMoji = "Insert into T_JYUDTH (";

		foreach($Column as $key => $value ) {
			if ($key != "") {
				if ($key == "JYU_EIG_CD") {
					$sqlMoji .= $key ; // UPD_DT
				}else{
					$sqlMoji .= ", $key "; // UPD_DT
				}
			}
		}

		$sqlMoji .= " ) values (";

		foreach($Column as $key => $value ) {
			if ($key != "") {
				if ($key == "JYU_EIG_CD") {
					$sqlMoji .= "'$value'" ; // UPD_DT
				}else{
					$sqlMoji .= ", '$value' ";
				}
			}
		}
		$sqlMoji .= ")";
		return $sqlMoji ;

	}






	//受注メモ.受注明細テーブルにレコードをInsert
	function insertJyuchuMeisaiSqlMoji($Column) {

		$sqlMojiM = "Insert into T_JYUDTM (";


		foreach($Column as $key => $value ) {
			if ($value != "") {
				if ($key == "JYU_EIG_CD") {
					$sqlMojiM .= $key ; // UPD_DT
				}else{
					$sqlMojiM .= ", $key "; // UPD_DT
				}
			}
		}

		$sqlMojiM .= " ) values (";

		foreach($Column as $key => $value ) {
			if ($value != "") {
				if ($key == "JYU_EIG_CD") {
					$sqlMojiM .= "'$value'" ; // UPD_DT
				}else{
					$sqlMojiM .= ", '$value' ";
				}
			}
		}

		$sqlMojiM .= ")";

		return $sqlMojiM ;



	}


*/


?>
