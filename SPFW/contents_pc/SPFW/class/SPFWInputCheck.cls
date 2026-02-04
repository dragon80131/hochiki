<?php
/**
 * SPFWInputCheckクラス
 *
 * 入力チェックに用いる静的メソッドの集合クラス。
 *
 * @package 
 * @access  public
 * @author  MasahitoSAMEKAWA <masahito@souppot.jp>
 * @create  2003/01/31
 * @version 0.1
 **/

define ("EXIST_CLASS_ASINPUTCHECK","yes");

class SPFWInputCheck {
	/**
	 * コンストラクタ
	 * @access		public
	 */
	function __construct () {
	}

	/**
	 * 入力が数字列かどうかを判定します。
	 * @access		public
	 * @param		String		$Input	チェック対象文字列
	 * @return		boolean		チェック結果
	 */
	static function isNumeric($Input, $IfError = FALSE, $HyphenFlg = TRUE){
		$Value = TRUE;

		if (strlen($input) != mb_strlen($input))
			$Value = FALSE;
		else if (is_array($Input)) {
			for ($i = 0; $i < count($Input); $i++) {
				if (!SPFWInputCheck::isNumeric($Input[$i])) {
					$Value = FALSE;
					break;
				}
			}
		}
		else {
			for( $i = 0 ; $i < strlen( $Input ) ; $i++ ){
				$Character = substr( $Input, $i, 1 );
				if ($HyphenFlg) {

			###20120613 ハイフン対応
				if(
					!((ord("a") <= ord($Character) && ord("z") >= ord($Character))
					|| (ord("A") <= ord($Character) && ord("Z") >= ord($Character))
 					|| (ord("0") <= ord($Character) && ord("9") >= ord($Character))
					|| (ord("-") == ord($Character) )
					)
				){
#					if(!(ord("-") == ord($Character) || (ord("0") <= ord($Character) && ord("9") >= ord($Character)))){
						$Value = FALSE;
					}
				}
				else {
					if(!(ord("0") <= ord($Character) && ord("9") >= ord($Character))){
						$Value = FALSE;
					}
				}
			}
		}

		if (!$Value)
			$IfError = !$Value;

		return $Value;
	}

	/**
	 * 入力が半角英数字かどうかを判定します。
	 * @access		public
	 * @param		String		$Input	チェック対象文字列
	 * @return		boolean		チェック結果
	 */
	function isAlphabet($Input, $IfError = FALSE){
		$Value = TRUE;

		for( $i = 0 ; $i < strlen( $Input ) ; $i++ ){
			$Character = substr( $Input, $i, 1 );
			if(!((ord("a") <= ord($Character) && ord("z") >= ord($Character)) || (ord("A") <= ord($Character) && ord("Z") >= ord($Character)))){
				$Value = FALSE;
			}
		}

		if (!$Value)
			$IfError = !$Value;

		return $Value;
	}

	/**
	 * 入力が半角英数字かどうかを判定します。
	 * @access		public
	 * @param		String		$Input	チェック対象文字列
	 * @return		boolean		チェック結果
	 */
static	function isAlphaNumeric($Input, $IfError = FALSE){
		$Value = TRUE;

		for( $i = 0 ; $i < strlen( $Input ) ; $i++ ){
			$Character = substr( $Input, $i, 1 );
###20120613　ハイフン対応
			if(!((ord("a") <= ord($Character) && ord("z") >= ord($Character))
			 || (ord("A") <= ord($Character) && ord("Z") >= ord($Character))
			 || (ord("0") <= ord($Character) && ord("9") >= ord($Character))
			 || (ord("-") == ord($Character) )
			 || ($Character == "_")
	 		)
			){

#			if(!((ord("a") <= ord($Character) && ord("z") >= ord($Character)) || (ord("A") <= ord($Character) && ord("Z") >= ord($Character)) || (ord("0") <= ord($Character) && ord("9") >= ord($Character)))){
				$Value = FALSE;
			}
		}

		if (!$Value)
			$IfError = !$Value;

		return $Value;
	}

	/**
	 * 入力がe-mailとして適しているかどうかを判定します。
	 * @access		public
	 * @param		String		$Input	チェック対象文字列
	 * @return		boolean		チェック結果
	 */
	function isRightEMail($Input, $IfError = FALSE){
		$Value = TRUE;

		for($i = 0; $i < strlen( $Input ); $i++){
			$Character = substr( $Input, $i, 1);

			if(!(((ord("a") <= ord($Character)) && (ord("z") >= ord($Character))) ||
				((ord("A") <= ord($Character)) && (ord("Z") >= ord($Character))) ||
				((ord("0") <= ord($Character)) && (ord("9") >= ord($Character))) ||
				($Character == "@") ||
				($Character == ":") ||
				($Character == ";") ||
				($Character == "<") ||
				($Character == ">") ||
				($Character == ".") ||
				($Character == "-") ||
				($Character == "/") ||
				($Character == "\\") ||
				($Character == "?") ||
				($Character == "&") ||
				($Character == "=") ||
				($Character == "%") ||
				($Character == "$") ||
				($Character == "#") ||
				($Character == "'") ||
				($Character == "+") ||
				($Character == "_"))){
				$Value = FALSE;
			}
		}

		if (!strstr($Input, "@"))
			$Value = FALSE;

		if (!strstr($Input, "."))
			$Value = FALSE;

		if (!$Value)
			$IfError = !$Value;

		return $Value;
	}

	/**
	 * 入力がurlとして適しているかどうかを判定します。
	 * @access		public
	 * @param		String		$Input	チェック対象文字列
	 * @return		boolean		チェック結果
	 */
	function isRightURL($Input, $IfError = FALSE){
		$Value = TRUE;

		for($i = 0; $i < strlen( $Input ); $i++){
			$Character = substr( $Input, $i, 1);

			if(!(((ord("a") <= ord($Character)) && (ord("z") >= ord($Character))) ||
				((ord("A") <= ord($Character)) && (ord("Z") >= ord($Character))) ||
				((ord("0") <= ord($Character)) && (ord("9") >= ord($Character))) ||
				($Character == ":") ||
				($Character == ".") ||
				($Character == "-") ||
				($Character == "/") ||
				($Character == "\\") ||
				($Character == "?") ||
				($Character == "&") ||
				($Character == "=") ||
				($Character == "%") ||
				($Character == "$") ||
				($Character == "#") ||
				($Character == "'") ||
				($Character == "+") ||
				($Character == "_"))){
				$Value = FALSE;
			}
		}

		if (substr($Input, 0, 7) != 'http://' && substr($Input, 0, 8) != 'https://')
			$Value = FALSE;

		if (!$Value)
			$IfError = !$Value;

		return $Value;
	}

	/**
	 * 入力の文字数をチェックします。
	 * @access		public
	 * @param		String		$Input	チェック対象文字列
	 * @return		Integer		文字列長
	 */
	function checkLength($Input, $Minimum, $Maximum, $IfError = FALSE){
		$Value = TRUE;

		if ($Minimum != NULL && strlen($Input) < $Minimum)
			$Value = FALSE;

		if ($Maximum != NULL && strlen($Input) > $Maximum)
			$Value = FALSE;

		if (!$Value)
			$IfError = !$Value;

		return $Value;
	}

	/**
	 * 入力が日付として適しているかどうかを判定します。
	 * @access		public
	 * @param		String		$Input	YYYY/MM/DD形式の日付
	 * @return		boolean		チェック結果
	 */
	function isRightDate($Input, $IfError = FALSE){
		$regs = explode("/", $Input);

		$Year = $regs[0];
		$Month = $regs[1];
		$Day = $regs[2];

		$Value = checkdate($Month, $Day, $Year);

		if (!$Value)
			$IfError = !$Value;

		return $Value;
	}

	/**
	 * 入力の文字数を返します。
	 * @access		public
	 * @param		String		$Input	チェック対象文字列
	 * @return		Integer		文字列長
	 */
	function getStringLength($Input){
		return strlen($Input);
	}

	/**
	 * 入力が電話番号として正しいかどうかを判定します。
	 * 半角数値・ハイフンだけかどうかのチェックですが、本来は桁数チェックも必要かも
	 * @access		public
	 * @param		String		$Input	チェック対象文字列
	 * @return		boolean		チェック結果
	 */
	function isRightTelephoneNumber($Input){
		for( $i = 0 ; $i < strlen( $Input ) ; $i++ ){
			$Character = substr( $Input, $i, 1 );
			if( ( (ord("0") <= ord($Character) ) && (ord("9") >= ord($Character) ) ) ||
				( $Character == "-" )){
			}else{
				return false;
				break;
			}
		}
		
		return true;
	}

	/**
	 * 入力が空かどうか検査します。
	 *
	 * @access		public
	 * @param		String		$Input	チェック対象文字列
	 * @return		Bool			空ならTRUE
	 */
	function isEmpty($Input, $IfError = FALSE){
		$Value = FALSE;

		if (is_array($Input)) {
			if (count($Input) == 0)
				$Value = TRUE;
		}
		else {
			if ($Input == NULL)
				$Value = TRUE;
		}

		if ($Value)
			$IfError = $Value;

		return $Value;
	}

	/**
	* 絵文字チェック
	*
	* $inputに絵文字が含まれているかをチェックします。
	*
	* @access	public
	* @param	string		$input	チェックする項目(SJISで受け取った場合のみ有効)
	* @return	boolean		絵文字が含まれていたらTRUE
	* @created	2006/2/2
	*/
	
	function checkPictureCharacter($input, $MyCarrier = NULL) {
		if ($MyCarrier == NULL)
			return FALSE;

		$input = mb_convert_encoding($input, 'SJIS', 'EUC-JP');

		// docomo
		if ($MyCarrier == 2) {
			$input = unpack("C*", $input);
			$Length = count($input);
			$n = 1;
			
			while($n <= $Length) {
				$ch1 = $input[$n];
				$ch2 = $input[$n+1];
				
				if (($ch1 == 0xF8) && (0x9F <= $ch2) && ($ch2 <= 0xFC)) {
					return TRUE;
				}
				else if (($ch1 == 0xF9) && ((0x40 <= $ch2) && ($ch2 <= 0x49) || (0x50 <= $ch2) && ($ch2 <= 0x52) || (0x55 <= $ch2) && ($ch2 <= 0x57) || (0x5B <= $ch2) && ($ch2 <= 0x5E) || (0x72 <= $ch2) && ($ch2 <= 0x7E) || (0x80 <= $ch2) && ($ch2 <= 0xFC))) {
					return TRUE;
				}
				// 2バイト文字の処理
				else if (((0x81 <= $ch1) && ($ch1 <= 0x9f)) || ((0xe0 <= $ch1) && ($ch1 <= 0xfc))) {
					$n++;
				}
				
				$n++;
			}
			return FALSE;
		}
		// softbank
		if ($MyCarrier == 3) {
			if(preg_match("/\x1B[\x24][\x21-\x7A]{2,}\x0F/", $input))
				return TRUE;
			else
				return FALSE;
		}
		// ez
		if ($MyCarrier == 4) {
			$Length = mb_strlen($input, "SJIS");

			for ($i = 0; $i < $Length; $i++) {
				$w = mb_substr($input, $i, 1, "SJIS"); 
				$hex = dechex(ord($w[0])) . dechex(ord($w[1]));
				
				if (($w[0] == "\xf3" && ereg("[\x40-\x7e\x80-\xfc]",$w[1])) || ($w[0] == "\xf4" && ereg("[\x40-\x7e\x80-\xfc]",$w[1])) || ($w[0] == "\xf6" && ereg("[\x40-\x7e\x80-\xfc]",$w[1])) || ($w[0] == "\xf7" && ereg("[\x40-\x7e\x80-\xfc]",$w[1]))) {
					return TRUE;
				}
			}
			
			return FALSE;
		}
	}

	function isKatakana($input, $IfError = FALSE){
		// 入力の調整
		$input = mb_convert_kana($input, 'KV');
		$Katakana = array('キャ', 'キュ', 'キョ', 'ギャ', 'ギュ', 'ギョ', 'シャ', 'シュ', 'ショ', 'ジャ', 'ジュ', 'ジョ', 'チャ', 'チュ', 'チョ', 'ヂャ', 'ヂュ', 'ヂョ', 'ニャ', 'ニュ', 'ニョ', 'ヒャ', 'ヒュ', 'ヒョ', 'ビャ', 'ビュ', 'ビョ', 'ピャ', 'ピュ', 'ピョ', 'リャ', 'リュ', 'リョ', 'ア', 'イ', 'ウ', 'エ', 'オ', 'カ', 'キ', 'ク', 'ケ', 'コ', 'サ', 'シ', 'ス', 'セ', 'ソ', 'タ', 'チ', 'ツ', 'テ', 'ト', 'ナ', 'ニ', 'ヌ', 'ネ', 'ノ', 'ハ', 'ヒ', 'フ', 'ヘ', 'ホ', 'マ', 'ミ', 'ム', 'メ', 'モ', 'ヤ', 'ユ', 'ヨ', 'ラ', 'リ', 'ル', 'レ', 'ロ', 'ワ', 'ヲ', 'ン', 'ヴ', 'ガ', 'ギ', 'グ', 'ゲ', 'ゴ', 'ザ', 'ジ', 'ズ', 'ゼ', 'ゾ', 'ダ', 'ヂ', 'ヅ', 'デ', 'ド', 'バ', 'ビ', 'ブ', 'ベ', 'ボ', 'パ', 'ピ', 'プ', 'ペ', 'ポ', 'ァ', 'ィ', 'ゥ', 'ェ', 'ォ', 'ッ', 'ャ', 'ュ', 'ョ', 'ー');

		$Value = TRUE;

		for ($i = 0; $i < mb_strlen($input); $i++){
			$character = mb_substr($input, $i, 1);
			if (array_search($character, $Katakana) === FALSE || array_search($character, $Katakana) === NULL)
				$Value = FALSE;
		}

		if (!$Value)
			$IfError = !$Value;

		return $Value;
	}

	function isHiragana($input, $IfError = FALSE){
		$Hiragana = array('きゃ', 'きゅ', 'きょ', 'ぎゃ', 'ぎゅ', 'ぎょ', 'しゃ', 'しゅ', 'しょ', 'じゃ', 'じゅ', 'じょ', 'ちゃ', 'ちゅ', 'ちょ', 'ぢゃ', 'ぢゅ', 'ぢょ', 'にゃ', 'にゅ', 'にょ', 'ひゃ', 'ひゅ', 'ひょ', 'びゃ', 'びゅ', 'びょ', 'ぴゃ', 'ぴゅ', 'ぴょ', 'りゃ', 'りゅ', 'りょ', 'あ', 'い', 'う', 'え', 'お', 'か', 'き', 'く', 'け', 'こ', 'さ', 'し', 'す', 'せ', 'そ', 'た', 'ち', 'つ', 'て', 'と', 'な', 'に', 'ぬ', 'ね', 'の', 'は', 'ひ', 'ふ', 'へ', 'ほ', 'ま', 'み', 'む', 'め', 'も', 'や', 'ゆ', 'よ', 'ら', 'り', 'る', 'れ', 'ろ', 'わ', 'を', 'ん', 'う゛', 'が', 'ぎ', 'ぐ', 'げ', 'ご', 'ざ', 'じ', 'ず', 'ぜ', 'ぞ', 'だ', 'ぢ', 'づ', 'で', 'ど', 'ば', 'び', 'ぶ', 'べ', 'ぼ', 'ぱ', 'ぴ', 'ぷ', 'ぺ', 'ぽ', 'ぁ', 'ぃ', 'ぅ', 'ぇ', 'ぉ', 'っ', 'ゃ', 'ゅ', 'ょ', 'ー');

		$Value = TRUE;

		for ($i = 0; $i < mb_strlen($input); $i++){
			$character = mb_substr($input, $i, 1);
			if (array_search($character, $Hiragana) === FALSE || array_search($character, $Hiragana) === NULL)
				$Value = FALSE;
		}

		if (!$Value)
			$IfError = !$Value;

		return $Value;
	}
}
?>
