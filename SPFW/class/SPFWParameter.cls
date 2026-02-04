<?php
/**
 * SPFWParameterクラス
 *
 * パラメータの入力・出力に関する調整などを行なうクラス。
 * 
 * @package 
 * @access  public
 * @author  MasahitoSAMEKAWA <masahito@souppot.jp>
 * @create  2002/01/31
 * @version 0.1
 **/

class SPFWParameter {
	/**
	 * コンストラクタ
	 * このコンストラクタは何もしていません。
	 * 
	 * @access		public
	 */
	function __construct () {
	}

	/**
	 * HTMLタグを含む文字列をフォーム出力用にします。
	 * 
	 * @access		public
	 * @param		String		$input	処理対象文字列
	 * @return		String		処理後文字列
	 */
static	function adjustForPrint($input){
		if (!is_array($input))
			$processed = htmlspecialchars($input, ENT_COMPAT, mb_internal_encoding());
		else {
			for ($i = 0; $i < count($input); $i++)
				$processed[$i] = htmlspecialchars($input[$i], ENT_COMPAT, mb_internal_encoding());
		}

		return $processed;
	}

	/**
	 * HTMLタグを含む文字列をフォーム出力用にします。
	 * 
	 * @access		public
	 * @param		String		$input	処理対象文字列
	 * @return		String		処理後文字列
	 */
	function adjustNewLineForPrint($input){
		if (!is_array($input))
			$processed = nl2br($input);
		else {
			for ($i = 0; $i < count($input); $i++)
				$processed[$i] = nl2br($input[$i]);
		}

		return $processed;
	}

	/**
	 * 文字列または配列パラメータを取得します。
	 * 
	 * @access		public
	 * @param		String		$name	取得パラメータ名称
	 * @return		String		取得値
	 */
static	function getValues($name){
		if (isset($GLOBALS['_GET'][$name]))
			$Method = '_GET';
		else
			$Method = '_POST';

		if (!isset($GLOBALS['_GET'][$name]) && !isset($GLOBALS['_POST'][$name]))
			return NULL;

		if (!is_array($GLOBALS[$Method][$name])) {
			$GLOBALS['_POST'][$name] = stripslashes($GLOBALS['_POST'][$name]);
			$GLOBALS[$name] = stripslashes($GLOBALS['_POST'][$name]);
			$HTTP_POST_VARS[$name] =  stripslashes($GLOBALS['_POST'][$name]);
		}
		else {
			for ($i = 0; $i < count($GLOBALS[$Method][$name]); $i++) {
				$GLOBALS['_POST'][$name][$i] = stripslashes($GLOBALS['_POST'][$name][$i]);
				$GLOBALS[$name][$i] = stripslashes($GLOBALS['_POST'][$name][$i]);
				$HTTP_POST_VARS[$name][$i] =  stripslashes($GLOBALS['_POST'][$name][$i]);
			}
		}

		$value = $GLOBALS[$Method][$name];

		return $value;
	}

	/**
	 * YYYY/MM/DD で入ってくる日付パラメータを分解
	 * 
	 * @access		public
	 * @param		String		$name	取得パラメータ名称
	 * @return		String		取得値
	 */
	function wrapDate($ValueName, $HiddenSet = TRUE) {
		$MyValue = SPFWParameter::getValues($ValueName);

		$GLOBALS[$ValueName . 'Year'] = substr($MyValue, 0, 4);
		$GLOBALS[$ValueName . 'Month'] = substr($MyValue, 5, 2);
		$GLOBALS[$ValueName . 'Day'] = substr($MyValue, 8, 2);

		if ($HiddenSet) {
			SPFWTemplate::setValue($ValueName . 'Year', $GLOBALS[$ValueName . 'Year']);
			SPFWTemplate::setValue($ValueName . 'Month', $GLOBALS[$ValueName . 'Month']);
			SPFWTemplate::setValue($ValueName . 'Day', $GLOBALS[$ValueName . 'Day']);

			if (substr($ValueName, 0, 1) == 'w')
				SPFWTemplate::dropValue($ValueName);
		}
	}

	/**
	 * AJAX表示に適したように加工
	 * 
	 * @access		public
	 * @param		String		$name	取得パラメータ名称
	 * @return		String		取得値
	 */
	function adjustDate($Value, $Type = 'DATE') {
		if ($Type == 'DATE')
			$Value = substr($Value, 0, 10);
		else if ($TYPE == 'DATETIME')
			$Value = substr($Value, 0, 19);
		else if ($TYPE == 'DATETIME2')
			$Value = substr($Value, 0, 16);

		$Value = str_replace('-', '/', $Value);

		return $Value;
	}
}
?>
