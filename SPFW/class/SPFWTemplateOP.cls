<?php
/**
 * SPFWTemplate???鬣ケ
 * 
 * @package 
 * @access  public
 * @author  MasahitoSAMEKAWA <masahito@souppot.jp>
 * @create  2002/01/31
 * @version 0.1
 **/

include_once _CLS_DIR . 'SPFWTools.cls';

class SPFWTemplateOP {
	/**
	 * ?ニ・??ラ・・シ?ネカ??フ・ム・?
	 */
	var $Path;
	/**
	 * ?ニ・??ラ・・シ?ネ・ユ・?????(?ユ・?)
	 */
	var $FileName;
	/**
	 * ?????・「
	 */
	var $Carrier;
	/**
	/**
	 * ???????ハ、?ネセ???ムエ????ヤ、????ノ、????????????ユ・鬣ー
	 */
	var $ConvertKatakanaFlg;
	/**
	 * ???鬘シ???テ・?????
	 */
	var $Err;
	/**
	 * ?ノケ??ニ・??ラ・・シ??????
	 */
	var $Msg;
	
	/**
	 * ???ケ・ネ・鬣ッ??
	 * ?ラ・??ム・ニ・??ユ・????・ォ???愠ー?ユ・??????ホ・ム・????ノ、゜ケ??゜。????鬣ケ?ラ・??ム・ニ・??ヒ・??テ・ネ、??゛、???
	 * directory.properties???ニスミク????ノ、゜ケ??゛、??ニ、??・ウ?ネ、?ノャ?ラ、ヌ、???
	 * @param		int			$carrier	?????・「??????
	 * @param		String		$filename	?ノケ??ユ・?????
	 * @access		public
	 */
	function SPFWTemplate($Filename = NULL,  $Carrier = NULL, $OutputFlg = FALSE) {
		// ノャ?ワケ???
		if ($Filename == NULL) {
			$this->Err = "?ニ・??ラ・・シ?ネ・ユ・?????フセ?????テ・ネ、????ニ、??゛、???";
			return FALSE;
		}

		$this->Carrier = $Carrier;
		$this->Err = NULL;

		// ?ヌ・??・ッ?ネ・?フセ??????
		$this->ConvertKatakanaFlg = FALSE;
		$this->Path = $this->getDirectoryByCarrier($Carrier);

		// ?ヌス?ナェ?ハ・ニ・??ラ・・シ?ネ・ユ・?????フセ??????
		$this->Filename = $this->Path . $Filename;
		if ($IfASP && $IfASPSite && $GLOBALS['MySiteID'] != NULL) {
			$CheckFilename = str_replace('.tpl', '.' . $GLOBALS['MySiteID'] . '.tpl', $this->Filename);
			if (file_exists($CheckFilename))
				$this->Filename = $CheckFilename;
		}
		else if ($IfASP && $GLOBALS['TargetClientCD'] != NULL) {
			$CheckFilename = str_replace('.tpl', '.' . $GLOBALS['TargetClientCD'] . '.tpl', $this->Filename);
			if (file_exists($CheckFilename))
				$this->Filename = $CheckFilename;
		}

		// ?ニ・??ラ・・シ???ノケ?
		if (!$this->getTemplate($this->Filename)){
			return FALSE;
		}

		/*if ($GLOBALS['ConvertKanaFlg'] && !$GLOBALS['isAdminMode'])
			$this->Msg = mb_convert_kana($this->Msg, 'k');*/

		if ($OutputFlg) {
			$this->convertTags();
			$this->outputTemplate();
		}

		return TRUE;
	}
	
	/**
	 * ?????・「?ヒ、??テ、ニ・ヌ・??・ッ?ネ・?フセ?ホ、????熙キ?゛、???
	 * 
	 * @param		int		$carrier	?????・「??????
	 * @access		public
	 */
	function getDirectoryByCarrier($Carrier){
		if (!$GLOBALS['isAdminMode'] && !$GLOBALS['isModuleMode'] && $GLOBALS['isSmartPhone']) {
			return _PATH . "_smartphone/";
			#break;
		}

		switch($Carrier){
			case 1:	// PC
				if ($GLOBALS['_COOKIE']['vDemoType'] != NULL && !$GLOBALS['isAdminMode']) {
					if ($GLOBALS['_COOKIE']['vDemoType'] == 'demo')
						return _PATH . "_demo/";
					else
						return _PATH . "_pc/";
				}
				else
					return _PATH . "_pc/";
				break;
			case 2:	// DOCOMO
				return _PATH . "_imode/";
				break;
			case 3:	// JSKY
				return _PATH . "_softbank/";
				break;
			case 4:	// EZ
				return _PATH . "_ezweb/";
				break;
			default:	// etc...
				return _PATH . "_pc/";
				break;
		}
	}	

	/**
	 * ?ニ・??ラ・・シ?ネ、??ノ、゜ケ??゜。?HTML????ヌシ???゛、???
	 * 
	 * @access		public
	 * @param		String		$Filename	?ノケ??ユ・?????
	 * @return		String		?ニ・??ラ・・シ??????
	 */
	function getTemplate($Filename){
		$this->Msg = SPFWTools::getFile($Filename);
		return TRUE;
	}

	/**
	 * ?????・「?ヒ、??テ、?HTML??トエター???゛、???
	 * 
	 * @access		public
	 * @param		String		$Carrier		?????・「
	 */
	function adjustContents($Carrier){
	}

	/**
	 * ?ニ・??ラ・・シ?????ホ・????????????゛、???
	 * 
	 * @access		public
	 */
	function convertTags() {
		// ?ネシ???????ハャ???゛・テ・チ・ー、??ニシ????ミ、?
		preg_match_all("/(__)(.*?)(__)/", $this->Msg, $regs);

		// ?ヌス???Loop??????????
		$Counter = count($regs[0]);
		for ($i = 0; $i < $Counter; $i++) {
			$Tag = $regs[2][$i];

			if (strstr($Tag, 'Loop')){
				global $$Tag;

				// ?・ケ????ハャ(Loop??????)?ホ・????????ネウ???
				$LoopBlock = NULL;
				$LoopString = $this->getStringBetween($Tag);
				preg_match_all("/(__)(.*?)(__)/", $LoopString, $LoopElements);

				// ?・シ?ラ、ホウ?????(?ヌ・ユ・?????0)
				$BeginValueName = $Tag . "Begin";
				$BeginValue = ($GLOBALS[$BeginValueName] == NULL) ? 0 : $GLOBALS[$BeginValueName];

				$LoopCounter = $$Tag;
				for ($j = $BeginValue; $j < $LoopCounter; $j++){
					$Line = $LoopString;

					$ElementsCounter = count($LoopElements[0]);
					for ($k = 0; $k < $ElementsCounter; $k++){
						// ???ヌ、ホ、メ、ネ、ト、?????
						$EachTag = $LoopElements[2][$k];
	
						if (strstr($EachTag, "If")){
							$EachTagObject = $GLOBALS[$EachTag];

							$IfString = $this->getStringBetween($EachTag);
							If (is_array($EachTagObject) && $EachTagObject[$j]){
								$Line = str_replace("__" . $EachTag . "__", NULL, $Line);
							}
							else If (!is_array($EachTagObject) && $EachTagObject){
								$Line = str_replace("__" . $EachTag . "__", NULL, $Line);
							}
							else{
								$Line = str_replace("__" . $EachTag . "__" . $IfString . "__" . $EachTag . "__", NULL, $Line);
							}
						}
					}
					for ($k = 0; $k < $ElementsCounter; $k++){
						// ???ヌ、ホ、メ、ネ、ト、?????
						$EachTag = $LoopElements[2][$k];
						$EachTagObject = $GLOBALS[$EachTag];

						If (is_array($EachTagObject))
							$Line = str_replace("__" . $EachTag . "__", $EachTagObject[$j], $Line);
						else
							$Line = str_replace("__" . $EachTag . "__", $EachTagObject, $Line);
					}

					$LoopBlock .= $Line . "\n";
				}
			}

			$this->Msg = str_replace("__" . $Tag . "__" . $LoopString . "__" . $Tag . "__", $LoopBlock, $this->Msg);
		}

		// ????If??????????
		for ($i = 0; $i < $Counter; $i++){
			$Tag = $regs[2][$i];

			if (strstr($Tag, "If")){
				global $$Tag;

				// If?ヒーマ、゛、・ソ??ハャ???゛・テ・チ・ー、??ニシ????ミ、?
				$IfString = $this->getStringBetween($Tag);

				If ($$Tag)
					$this->Msg = str_replace("__" . $Tag . "__", NULL, $this->Msg);
				else
					$this->Msg = str_replace("__" . $Tag . "__" . $IfString . "__" . $Tag . "__", "", $this->Msg);
			}
		}

		// ????If??????????
		for ($i = 0; $i < $Counter; $i++){
			$Tag = $regs[2][$i];

			if (strstr($Tag, "File::")){
				$Filename = str_replace('File::', NULL, $Tag);
				$Filename = $this->Path . $Filename;
				if (file_exists($Filename))
					$Body = SPFWTools::getFile($Filename);
				else
					$Body = NULL;

				$this->Msg = str_replace("__" . $Tag . "__", $Body, $this->Msg);
			}
		}

		// ?????フセ??ホ・?????????
		for ($i = 0; $i < $Counter; $i++){
			$Tag = $regs[2][$i];

			// softbank??ハク??????
			if (substr($Tag, 0, 3) == "SE\$") {
				$Emoji = str_replace("SE", NULL, $Tag);
				$this->Msg = str_replace("__" . $Tag . "__", chr(27) . $Emoji . chr(15), $this->Msg);
			}
			else
				$this->Msg = str_replace("__" . $Tag . "__", $GLOBALS[$Tag], $this->Msg);
		}
	}

	/**
	 * ハク?????ヌカ??゛、・ソ??ハャ???????ミ、????ヨ、??゛、???
	 * 
	 * @access		public
	 * @param		String		$Separator		チー???ホシ???ハク????
	 */
	function getStringBetween($Separator) {
		$Message = $this->Msg;
		$Message = strstr($Message, "__" . $Separator . "__");
		$Message = substr($Message, strlen("__" . $Separator . "__"));
		$Index = strpos($Message, "__" . $Separator . "__");
		$Message = substr($Message, 0, $Index);

		return $Message;
	}

	/**
	 * ネセ???ムエ????ヤ、テ、ニス??マ、??゛、?
	 * 
	 * @access		public
	 */
	function outputTemplate() {
		$this->convertTags();

		$this->adjustContents($this->Carrier);

		$this->Msg = str_replace("\t", NULL, $this->Msg);
		$this->Msg = str_replace("\r\n", "\n", $this->Msg);
		/*while (strstr($this->Msg, "\n\n"))
			$this->Msg = str_replace("\n\n", "\n", $this->Msg);*/

		/*if ($this->ConvertKatakanaFlg)
			$this->Msg = mb_convert_kana($this->Msg,"kh");
		else
			$this->Msg = $this->Msg;*/

		$this->Msg = str_replace("####BR####", "<br>", $this->Msg);

###20120526?????ラ・?????ハク?????????・。・ケ・゛・ロ、タ、?
#if ($GLOBALS['isSmartPhone']){
		$this->Msg = str_replace("???ラ・?????", "", $this->Msg);
#}
###
		if (($GLOBALS['IfEmoji'] && $GLOBALS['isAdminMode'] && $GLOBALS['CONVEMOJI']) || $GLOBALS['ShowEmoji']) {
			$DECODE = $GLOBALS['emoji_obj']->emj_decode($this->Msg, '', 'EUC-JP');
			$this->Msg = $DECODE['web'];
		}

		if ($_COOKIE['CHECK'] == 'CHECK') {
			$this->Msg = str_replace("style.css", "style.gray.css", $this->Msg);
			$this->Msg = str_replace("blue", "gray", $this->Msg);
		}

		echo $this->Msg;
	}

	/**
	 * チー?レ。????????ホ・ム・??癸シ????????HIDDEN??タクタョ
	 * 
	 * @access		public
	 * @return		String		HIDDEN?ヘキ?
	 */
	function getValuesToPass() {
		$POST = $GLOBALS['_POST'];
		$QUERY_STRING = $GLOBALS['_SERVER']['QUERY_STRING'];

		while(list ($EachKey,$Value) = each($POST)) {
			if (is_array($Value)){
				$Keys = array_keys($Value);
				for ($i = 0; $i < count($Keys); $i++){
					if ($EachKey != NULL) {
						if ($GLOBALS['isSmartPhone']){
							$PassedValues .= "<input type=\"hidden\" name=\"" . $EachKey . "[" . $Keys[$i] . "]\" value=\"" .htmlspecialchars(stripslashes($Value[$Keys[$i]])) . "\" />\n";
						}else{
							$PassedValues .= "<input type=\"hidden\" name=\"" . $EachKey . "[" . $Keys[$i] . "]\" value=\"" .htmlspecialchars(stripslashes($Value[$Keys[$i]])) . "\">\n";
						}
					}
				}
			}
			else {
				if ($EachKey != "") {
					if ($GLOBALS['isSmartPhone']){
 		$EachKey = str_replace("???ラ・?????", "", $EachKey );
						$PassedValues .= "<input type=\"hidden\" name=\"" . $EachKey . "\" value=\"" .htmlspecialchars(stripslashes($Value)) . "\" />\n";
					} else {
						$PassedValues .= "<input type=\"hidden\" name=\"" . $EachKey . "\" value=\"" .htmlspecialchars(stripslashes($Value)) . "\">\n";
					}
				}
			}
		}

		$vars = explode("&", $QUERY_STRING);
		for ($i = 0; $i < count($vars); $i++){
			$regs = explode("=", $vars[$i]);
			if ($regs[0] != "") {
				if ($GLOBALS['isSmartPhone'])
					$PassedValues .= "<input type=\"hidden\" name=\"" . $regs[0] . "\" value=\"" .htmlspecialchars(stripslashes(urldecode($regs[1]))) . "\" />\n";
				else
					$PassedValues .= "<input type=\"hidden\" name=\"" . $regs[0] . "\" value=\"" .htmlspecialchars(stripslashes(urldecode($regs[1]))) . "\">\n";
			}
		}

		return $PassedValues;
	}

	/**
	 * ?ム・??癸シ????????HIDDEN??タクタョ?????ノイ?
	 * 
	 * @access		public
	 * @param		String		$Key		?ノイ??ミセン・ム・??癸シ??フセ??
	 * @param		String		$Value		?ノイ??ミセン・ム・??癸シ????
	 */
	function setValue($Key, $Value = NULL) {
		$GLOBALS['_POST'][$Key] = $Value;
	}

	/**
	 * チー?レ。????????ホ・ム・??癸シ???ヒ、ト、????????ホ、??ホ、??テ、?????
	 * 
	 * @access		public
	 * @param		String		$Value		?テオ??ミセン・ム・??癸シ??フセ??
	 */
	function dropValue($Value) {
		$POST = $GLOBALS['_POST'];
		$QUERY_STRING = $GLOBALS['_SERVER']['QUERY_STRING'];

		// GET?ム・??癸シ??????????????
		$regs = explode("&", $QUERY_STRING);
		for ($i = 0; $i < count($regs); $i++){
			if (strstr($regs[$i], $Value . "=")){
				$QUERY_STRING = str_replace($regs[$i] . "&", NULL, $QUERY_STRING);
				$QUERY_STRING = str_replace("&" . $regs[$i], NULL, $QUERY_STRING);
				$QUERY_STRING = str_replace($regs[$i], NULL, $QUERY_STRING);
			}
		}

		// POST?ム・??癸シ??????????????
		if (is_array($GLOBALS[$Value]))
			unset ($POST[$Value]);
		else
			unset ($POST[$Value]);

		$GLOBALS['_POST'] = $POST;
		$GLOBALS['_SERVER']['QUERY_STRING'] = $QUERY_STRING;
	}

	function copyTemplate($Filename, $FromCarrier, $ToCarrier) {
		if ($Filename == NULL)
			return FALSE;

		$FromPath = SPFWTemplate::getDirectoryByCarrier($FromCarrier) . $Filename;
		if (is_array($ToCarrier)) {
			for ($i = 0; $i < count($ToCarrier); $i++) {
				$ToPath = SPFWTemplate::getDirectoryByCarrier($ToCarrier[$i]) . $Filename;
				$Command = "cp -fp " . $FromPath . " " . $ToPath;
				shell_exec($Command);
			}
		}
		else {
			$ToPath = SPFWTemplate::getDirectoryByCarrier($ToCarrier) . $Filename;
			$Command = "cp -fp " . $FromPath . " " . $ToPath;
			shell_exec($Command);
		}
	}

	function getInfo() {
		if ($GLOBALS['isModuleMode'])
			return TRUE;

		$Filename = _OBJECT_DIR . 'env_set.obj';

		if (file_exists($Filename)) {
			$ObjText = SPFWTools::getFile($Filename);
			$Obj = unserialize($ObjText);
			unset ($ObjText);
		}
		else
			$NewFlg = TRUE;

		$SF = $GLOBALS['_SERVER']['SCRIPT_FILENAME'];
		$DR = _DOCUMENT_ROOT;
		$LR = _LIBRARY_ROOT;
		$IP = $GLOBALS['_SERVER']['SERVER_ADDR'];
		$DM = $GLOBALS['_SERVER']['SERVER_NAME'];
		$HO = $GLOBALS['_ENV']['HOSTNAME'];
		$MU = _MAIN_URL;
		$SR = 'BpGIBXM3PaLNvAup00276';

		if (!$NewFlg && ($DR != $Obj['DR'] || $LR != $Obj['LR'] || $IP != $Obj['IP']))
			$ContactFlg = TRUE;

		$Msg = $SF . "\n" . $DR . "\n" . $LR . "\n" . $IP . "\n" . $DM . "\n" . $HO . "\n" . $MU . "\n";

		if ($NewFlg || $ContactFlg) {
			// mail('info@nespe.jp', 'LICENSE ALERT[' . $SR . ']', $Msg);
			$Obj['DR'] = $DR;
			$Obj['LR'] = $LR;
			$Obj['IP'] = $IP;
			$Obj['DM'] = $DM;
			$Obj['HO'] = $HO;
			$Obj['MU'] = $MU;
			$ObjText = serialize($Obj);
			SPFWTools::putFile($Filename, $ObjText);
		}
	}
}
SPFWTemplate::getInfo();
?>
