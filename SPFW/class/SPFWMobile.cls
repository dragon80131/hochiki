<?php
/**
 * SPFWMobileクラス
 *
 * モバイル用の関数群です。
 *
 * @package 
 * @access  public
 * @author  MasahitoSAMEKAWA <masahito@souppot.jp>
 * @create  2003/05/09
 * @version 0.1
 **/

define ("_CARRIER_ETC", "1");
define ("_CARRIER_DOCOMO", "2");
define ("_CARRIER_SOFTBANK", "3");
define ("_CARRIER_EZWEB", "4");

class SPFWMobile {
	
	/**
	 * コンストラクタ
	 * 
	 * @access		public
	 */
	function SPFWMobile(){
	}

	/**
	 * 端末名称抽出
	 * UserAgentから機種名の部分を抽出します。
	 * @param		String		$UA		UserAgent
	 * @return		String		機種名
	 * @access		public
	 */
	function getHandSetName($UA){
		if(preg_match("/^DoCoMo\/1\.0/",$UA)){
			list($x,$x,$UA) = explode("/",$UA);
		}
		else if(preg_match("/^DoCoMo\/2\.0\s([^\(]+)\(/",$UA,$m)){
			$UA = $m[1];
		}
		else if(preg_match("/^J-PHONE/",$UA)){
			list($x,$x,$UA,$x) = explode("/",$UA);
		}
		else if(preg_match("/^KDDI-/",$UA)){
			list($x,$UA) = explode("-",$UA);
			$UA = substr($UA, 0, strpos($UA, " "));
		}
		else if(preg_match("/^UP.Browser\//",$UA)){
			list($x,$UA) = explode("-",$UA);
			$UA = substr($UA, 0, strpos($UA, " "));
		}
		else
			$UA = "";

		$UA = preg_replace("/<>\"&/","",$UA);

		return $UA;
	}

	/**
	 * キャリア名称抽出
	 * UserAgentからキャリア名を生成します。
	 * @param		string		$UA		UserAgent
	 * @return		int			キャリアコード
	 * @access		public
	 */
	function getCarrierCode($UA){
		// 変数 
		$MyCarrier = 0;

		// 判別用
		$JudgeString[_CARRIER_DOCOMO] = array('DoCoMo');
		$JudgeString[_CARRIER_SOFTBANK] = array('J-PHONE', 'Vodafone', 'SoftBank', 'MOT-V980', 'MOT-C980');
		$JudgeString[_CARRIER_EZWEB] = array('UP.Browser');

		// UserAgentからキャリアを判別
		for ($i = 2; $i < 5; $i++) {
			$UpTo = count($JudgeString[$i]);

			for ($j = 0; $j < $UpTo; $j++) {
				if (stristr($UA, $JudgeString[$i][$j])) {
					$MyCarrier = $i;
					break;
				}
			}

			if ($MyCarrier > 0)
				break;
		}

		// どのキャリアとも判別されなかった場合
		if ($MyCarrier == 0)
			$MyCarrier = _CARRIER_ETC;

		return $MyCarrier;
	}

	/**
	 * キャリア名称抽出
	 * UserAgentからキャリア名を生成します。
	 * @param		String		$EMail		メールアドレス
	 * @return		int			キャリアコード
	 * @access		public
	 */
	function getCarrierCodeByEMail($EMail){
		// 変数 
		$MyCarrier = 0;

		// 判別用
		$JudgeString[_CARRIER_DOCOMO] = array('docomo.ne.jp');
		$JudgeString[_CARRIER_SOFTBANK] = array('jp-', 'vodafone.ne.jp', 'softbank.ne.jp');
		$JudgeString[_CARRIER_EZWEB] = array('ezweb.ne.jp', 'tu-ka');

		// UserAgentからキャリアを判別
		for ($i = 2; $i < 5; $i++) {
			$UpTo = count($JudgeString[$i]);

			for ($j = 0; $j < $UpTo; $j++) {
				if (stristr($EMail, $JudgeString[$i][$j])) {
					$MyCarrier = $i;
					break;
				}
			}

			if ($MyCarrier > 0)
				break;
		}

		// どのキャリアとも判別されなかった場合
		if ($MyCarrier == 0)
			$MyCarrier = _CARRIER_ETC;

		return $MyCarrier;
	}

	/**
	 * キャリア判定と各キャリア用処理
	 * 
	 * @return		int			キャリアコード
	 * @access		public
	 */
	function getCarrierCodeByUserAgent(){
		$UserAgent = $GLOBALS['_SERVER']['HTTP_USER_AGENT'];

		$MyCarrier = SPFWMobile::getCarrierCode($UserAgent);

		switch ($MyCarrier){
			case _CARRIER_ETC:
				mb_http_output("SJIS");
				$METHOD = "POST";
				break;
			case _CARRIER_DOCOMO:
				mb_http_output("SJIS");
				$METHOD = "POST";
				break;
			case _CARRIER_SOFTBANK:
				mb_http_output("SJIS");
				$METHOD = "POST";
				break;
			case _CARRIER_EZWEB:
				mb_http_output("SJIS");
				$METHOD = "POST";
				break;
		}

		if (!$GLOBALS['BUFFERING_FLG'])
			ob_start('mb_output_handler');

		$GLOBALS['BUFFERING_FLG'] = TRUE;

		return $MyCarrier;
	}

	/**
	 * 非対応端末判定処理
	 * 非対応端末をPROPERTIESファイルなどで array $UNSUPPORTED_TERMINAL[CARRIER_CODE][INDEX] に格納しておくことが前提。
	 * 
	 * @access		public
	 * @return		Bool			非対応端末ならTRUE
	 */
	function isNGTerminal(){
		$HTTP_USER_AGENT = $GLOBALS['_SERVER']['HTTP_USER_AGENT'];
		$MyCarrier = $GLOBALS['MyCarrier'];
		$UNSUPPORTED_TERMINAL = $GLOBALS['UNSUPPORTED_TERMINAL'];

		for ($i = 0; $i < count($UNSUPPORTED_TERMINAL[$MyCarrier]); $i++) {
			if (stristr($HTTP_USER_AGENT, $UNSUPPORTED_TERMINAL[$MyCarrier][$i]))
				return TRUE;
		}

		return FALSE;
	}

	/**
	 * 端末ID抽出
	 * 
	 * @return		String		端末ID
	 * @access		public
	 */
	function getUID(){
		$UserAgent = $GLOBALS['_SERVER']["HTTP_USER_AGENT"];

		if ($GLOBALS['MyCarrier'] == 2) {
			if (strstr($UserAgent, "(") !== FALSE) {
				$Step1 = strstr($UserAgent, "ser");
				$Step2 = explode(";", $Step1);
				$UID =  trim(str_replace("ser", "", $Step2[0]));
			}
			else {
				$UserAgentParts = explode("/", $UserAgent);
				for ($i = 0; $i < count($UserAgentParts); $i++) {
					if (strstr($UserAgentParts[$i], "ser") !== FALSE)
						$UID = trim(str_replace("ser", "", $UserAgentParts[$i]));
				}
			}

			if (strstr($UserAgent, "(") !== FALSE) {
				$Step1 = strstr($UserAgent, "icc");
				$Step2 = explode(";", $Step1);
				$ICC =  trim(str_replace("icc", "", $Step2[0]));

				$ICC = str_replace(')', NULL, $ICC);
			}
		}
		else if ($GLOBALS['MyCarrier'] == 3) {
			$UserAgentParts = explode("/", $UserAgent);
			for ($i = 0; $i < count($UserAgentParts); $i++) {
				if (strstr($UserAgentParts[$i], "SN") !== FALSE) {
					$UID = trim($UserAgentParts[$i]);
					$regs = explode(' ', $UID);
					$UID = $regs[0];
				}
			}
		}
		else if ($GLOBALS['MyCarrier'] == 4)
			$UID = $GLOBALS['_SERVER']["HTTP_X_UP_SUBNO"];

		return $UID;
	}

	function isSmartPhone(){
		$UserAgent = $GLOBALS['_SERVER']["HTTP_USER_AGENT"];

		if (strstr($UserAgent, 'iPhone OS') or strstr($UserAgent, 'iPad')  or strstr($UserAgent, 'Android') )
			return TRUE;
		else
			return FALSE;
	}
}
?>
