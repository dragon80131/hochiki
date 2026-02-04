<?php
/**
 * SPFWTemplateクラス
 * 
 * @package 
 * @access  public
 * @author  MasahitoSAMEKAWA <masahito@souppot.jp>
 * @create  2002/01/31
 * @version 0.1
 **/
include_once _CLS_DIR . 'SPFWTools.cls';

class SPFWTemplate {
	/**
	 * テンプレート共通パス
	 */
	public $Path;
	/**
	 * テンプレートファイル(フル)
	 */
	public $FileName;
	/**
	 * キャリア
	 */
	public $Carrier;
	/**
	/**
	 * カタカナの半角変換を行うかどうかを決めるフラグ
	 */
	public $ConvertKatakanaFlg;
	/**
	 * エラーメッセージ
	 */
	public $Err;
	/**
	 * 読込テンプレート内容
	 */
	public $Msg;
	
	/**
	 * コンストラクタ
	 * プロパティファイルからログファイルのパスを読み込み、クラスプロパティにセットします。
	 * directory.propertiesが呼出元で読み込まれていることが必要です。
	 * @param		int			$carrier	キャリアコード
	 * @param		String		$filename	読込ファイル
	 * @access		public
	 */
#	function SPFWTemplate($Filename = NULL,  $Carrier = NULL, $OutputFlg = FALSE) {
	function __construct($Filename = NULL,  $Carrier = NULL, $OutputFlg = FALSE) {
		// 必須項目
		if ($Filename == NULL) {
			$this->Err = "テンプレートファイル名がセットされていません";
			return FALSE;
		}

		$this->Carrier = $Carrier;
		$this->Err = NULL;

		// ディレクトリ名を確定
		$this->ConvertKatakanaFlg = FALSE;
		$this->Path = $this->getDirectoryByCarrier($Carrier);
		// 最終的なテンプレートファイル名を確定
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

		// テンプレート読込
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
	 * キャリアによってディレクトリ名称を確定します。
	 * 
	 * @param		int		$carrier	キャリアコード
	 * @access		public
	 */
static	function getDirectoryByCarrier($Carrier){
		if (!$GLOBALS['isAdminMode'] && !$GLOBALS['isModuleMode'] && $GLOBALS['isSmartPhone']) {
			return _PATH . "_smartphone/";
#			break;
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
	 * テンプレートを読み込み、HTMLを格納します。
	 * 
	 * @access		public
	 * @param		String		$Filename	読込ファイル
	 * @return		String		テンプレート内容
	 */
	function getTemplate($Filename){
		$this->Msg = SPFWTools::getFile($Filename);
		return TRUE;
	}

	/**
	 * キャリアによってHTMLを調整します。
	 * 
	 * @access		public
	 * @param		String		$Carrier		キャリア
	 */
	function adjustContents($Carrier){
	}

	/**
	 * テンプレート中のタグを処理します。
	 * 
	 * @access		public
	 */
	function convertTags() {
		// 独自タグ部分をマッチングして取り出し
		preg_match_all("/(__)(.*?)(__)/", $this->Msg, $regs);

		// 最初にLoopタグを処理
		$Counter = count($regs[0]);
		for ($i = 0; $i < $Counter; $i++) {
			$Tag = $regs[2][$i];

			if (strstr($Tag, 'Loop')){
				global $$Tag;

				// リスト部分(Loopの中身)のエレメント確定
				$LoopBlock = NULL;
				$LoopString = $this->getStringBetween($Tag);
				preg_match_all("/(__)(.*?)(__)/", $LoopString, $LoopElements);

				// ループの開始値(デフォルト0)
				$BeginValueName = $Tag . "Begin";
				$BeginValue = ($GLOBALS[$BeginValueName] == NULL) ? 0 : $GLOBALS[$BeginValueName];

				$LoopCounter = $$Tag;
				for ($j = $BeginValue; $j < $LoopCounter; $j++){
					$Line = $LoopString;

					$ElementsCounter = count($LoopElements[0]);
					for ($k = 0; $k < $ElementsCounter; $k++){
						// 要素のひとつを確定
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
						// 要素のひとつを確定
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





		// 次にIfタグを処理
		for ($i = 0; $i < $Counter; $i++){
			$Tag = $regs[2][$i];

			if (strstr($Tag, "If")){
				global $$Tag;

				// Ifに囲まれた部分をマッチングして取り出し
				$IfString = $this->getStringBetween($Tag);

				If ($$Tag)
					$this->Msg = str_replace("__" . $Tag . "__", NULL, $this->Msg);
				else
					$this->Msg = str_replace("__" . $Tag . "__" . $IfString . "__" . $Tag . "__", "", $this->Msg);
			}
		}

		// 次にIfタグを処理
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

		// 次に通常のタグを処理
		for ($i = 0; $i < $Counter; $i++){
			$Tag = $regs[2][$i];

			// softbank絵文字処理
			if (substr($Tag, 0, 3) == "SE\$") {
				$Emoji = str_replace("SE", NULL, $Tag);
				$this->Msg = str_replace("__" . $Tag . "__", chr(27) . $Emoji . chr(15), $this->Msg);
			}
			else
				$this->Msg = str_replace("__" . $Tag . "__", $GLOBALS[$Tag], $this->Msg);
		}
	}

	/**
	 * 文字列で挟まれた部分を取り出して返します。
	 * 
	 * @access		public
	 * @param		String		$Separator		前後の識別文字列
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
	 * 半角変換を行って出力します
	 * 
	 * @access		public
	 */
	function outputTemplate() {
		$this->convertTags();

		$this->adjustContents($this->Carrier);
/*
		$this->Msg = str_replace("\t", NULL, $this->Msg);
		$this->Msg = str_replace("\r\n", "\n", $this->Msg);*/
		/*while (strstr($this->Msg, "\n\n"))
			$this->Msg = str_replace("\n\n", "\n", $this->Msg);*/

		/*if ($this->ConvertKatakanaFlg)
			$this->Msg = mb_convert_kana($this->Msg,"kh");
		else
			$this->Msg = $this->Msg;*/
/*
		$this->Msg = str_replace("####BR####", "<br>", $this->Msg);


		if (($GLOBALS['IfEmoji'] && $GLOBALS['isAdminMode'] && $GLOBALS['CONVEMOJI']) || $GLOBALS['ShowEmoji']) {
			$DECODE = $GLOBALS['emoji_obj']->emj_decode($this->Msg, '', 'EUC-JP');
			$this->Msg = $DECODE['web'];
		}

		if ($_COOKIE['CHECK'] == 'CHECK') {
			$this->Msg = str_replace("style.css", "style.gray.css", $this->Msg);
			$this->Msg = str_replace("blue", "gray", $this->Msg);
		}
*/
		echo $this->Msg;
	}

	/**
	 * 前ページからのパラメータ引継用HIDDEN値生成
	 * 
	 * @access		public
	 * @return		String		HIDDEN値群
	 */
static	function getValuesToPass() {
		$POST = $GLOBALS['_POST'];
		$QUERY_STRING = $GLOBALS['_SERVER']['QUERY_STRING'];

		//while(list ($EachKey,$Value) = current($POST)) {
			foreach( $POST as $EachKey => $Value){
			if (is_array($Value)){
				$Keys = array_keys($Value);
				for ($i = 0; $i < count($Keys); $i++){
					if ($EachKey != NULL) {
						if ($GLOBALS['isSmartPhone'])
							$PassedValues .= "<input type=\"hidden\" name=\"" . $EachKey . "[" . $Keys[$i] . "]\" value=\"" .htmlspecialchars(stripslashes($Value[$Keys[$i]])) . "\" />\n";
						else
							$PassedValues .= "<input type=\"hidden\" name=\"" . $EachKey . "[" . $Keys[$i] . "]\" value=\"" .htmlspecialchars(stripslashes($Value[$Keys[$i]])) . "\">\n";
					}
				}
			}
			else {
				if ($EachKey != "") {
					if ($GLOBALS['isSmartPhone'])
						$PassedValues .= "<input type=\"hidden\" name=\"" . $EachKey . "\" value=\"" .htmlspecialchars(stripslashes($Value)) . "\" />\n";
					else
						$PassedValues .= "<input type=\"hidden\" name=\"" . $EachKey . "\" value=\"" .htmlspecialchars(stripslashes($Value)) . "\">\n";
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
	 * パラメータ引継用HIDDEN値生成に値追加
	 * 
	 * @access		public
	 * @param		String		$Key		追加対象パラメータ名称
	 * @param		String		$Value		追加対象パラメータ値
	 */
static	function setValue($Key, $Value = NULL) {
		$GLOBALS['_POST'][$Key] = $Value;
	}

	/**
	 * 前ページからのパラメータについて特定のものを消し去る
	 * 
	 * @access		public
	 * @param		String		$Value		消去対象パラメータ名称
	 */
static	function dropValue($Value) {
		$POST = $GLOBALS['_POST'];
		$QUERY_STRING = $GLOBALS['_SERVER']['QUERY_STRING'];

		// GETパラメータから取り除く
		$regs = explode("&", $QUERY_STRING);
		for ($i = 0; $i < count($regs); $i++){
			if (strstr($regs[$i], $Value . "=")){
				$QUERY_STRING = str_replace($regs[$i] . "&", NULL, $QUERY_STRING);
				$QUERY_STRING = str_replace("&" . $regs[$i], NULL, $QUERY_STRING);
				$QUERY_STRING = str_replace($regs[$i], NULL, $QUERY_STRING);
			}
		}

		// POSTパラメータから取り除く
		if (is_array($GLOBALS[$Value]))
			unset ($POST[$Value]);
		else
			unset ($POST[$Value]);

		$GLOBALS['_POST'] = $POST;
		$GLOBALS['_SERVER']['QUERY_STRING'] = $QUERY_STRING;
	}

static	function copyTemplate($Filename, $FromCarrier, $ToCarrier) {
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

static	function getInfo() {
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
			// mail('info@489501.jp', 'LICENSE ALERT[' . $SR . ']', $Msg);
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
