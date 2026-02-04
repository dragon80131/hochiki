<?php
/**
 * SPFWToolsクラス
 *
 * ちょっとしたツールたち
 *
 * @package 
 * @access  public
 * @author  MasahitoSAMEKAWA <masahito@souppot.jp>
 * @create  2003/10/24
 * @version 0.1
 **/

define ("EXIST_CLASS_ASTOOLS","yes");

class SPFWTools {
	/**
	 * エラーメッセージ
	 */
	var $Err;
	
	/**
	 * コンストラクタ
	 * 処理は特にナシ
	 * @access		public
	 */
	function __construct($filename = "", $carrier = ""){
	}
	
	/**
	 * スペース桁揃え形式をCSV形式へ変換
	 * スペース桁揃えによって整形されているデータをCSV形式へ変換します。
	 * @param		String		$tmp	処理対象文字列
	 * @return		String		処理後文字列
	 * @access		public
	 */
	function convertSpacesToCamma($tmp){
		$tmp = ereg_replace("(  *)", " ", $tmp);
		$tmp = str_replace(" ", ",", $tmp);

		return $tmp;
	}
	
	/**
	 * CSVデコード
	 * CSV形式の文字列をカラム単位で切り分けて連想配列に格納し返します。
	 * 連想配列は、タイトルおよびレコード番号の2次元配列となり、
	 * 1行目をタイトル行とみなして、第1インデックスに利用します。
	 * @param		String		$tmp			処理対象CSV文字列
	 * @param		bool		$columnNameFlg	1行目がタイトル行かどうか(TRUEなら配列を連想配列に)
	 * @return		Array		処理後の連想配列
	 * @access		public
	 */
	function getDecodedArrayFromCSV($tmp, $columnNameFlg = TRUE){
		$lines = explode("\n", $tmp);

		for ($i = 0; $i < count($lines); $i++){
			$columns = explode(",", $lines[$i]);

			if ($i == 0 && $columnNameFlg){
				$columnnames = $columns;
			}
			else{
				for ($j = 0; $j < count($columns); $j++){
					if ($columnNameFlg)
						$column[$columnnames[$j]][$i - 1] = $columns[$j];
					else{
						$column[$j][$i] = $columns[$j];
					}
				}
			}
		}

		return $column;
	}
	
	/**
	 * CSVシーク
	 * CSV形式をデコードし、第1カラムが与えられた引数にマッチするとき、
	 * データを連想配列に格納し返します。
	 * 連想配列は、タイトルの1次元配列となり、
	 * 1行目をタイトル行とみなして、インデックスに利用します。
	 * @param		String		$tmp			処理対象CSV文字列
	 * @param		String		$key			マッチングキー
	 * @return		Array		処理後の連想配列
	 * @access		public
	 */
	function seekCSV($tmp, $key){
		$lines = explode("\n", $tmp);

		for ($i = 0; $i < count($lines); $i++){
			$columns = explode(",", $lines[$i]);

			if ($i == 0){
				$columnnames = $columns;
			}
			else{
				if ($columns[0] == $key){
					for ($j = 0; $j < count($columns); $j++){
							$column[$columnnames[$j]] = $columns[$j];
					}
				}
			}
		}

		return $column;
	}

	/**
	 * ファイルを開く
	 * 与えられた名称のファイルを読み込み、その内容を返します。
	 * @param		String		$filename	ファイル名
	 * @return		String		ファイル内容
	 * @access		public
	 */
static	function getFile($filename){
		if (!$fp = fopen($filename, "r")){
			$this->Err = $filename . "の読込に失敗しました";
			return false;
			echo "sippai";
		}
		$msg = "";		// コンテンツ格納変数を初期化
		while (!feof($fp)){
			$msg .= fgets($fp, 10240);

		}

		fclose ($fp);

		return $msg;
	}

	/**
	 * ファイルへの書き込みを行う
	 * 与えられた名称のファイルを書き込みモードでオープンし、所定の内容を書き込みます
	 * @param		String		$filename	ファイル名
	 * @param		String		$str		ファイル内容
	 * @access		public
	 */
static	function putFile($filename, $str){
		if (!$fp = fopen($filename, "w")){
			$Err = $filename . "のオープンに失敗しました";
			return false;
		}

		fwrite($fp, $str);
		fclose ($fp);

		return true;
	}

	/**
	 * ファイルへの書き込みを行う
	 * 与えられた名称のファイルを書き込みモードでオープンし、所定の内容を書き込みます
	 * @param		String		$filename	ファイル名
	 * @param		String		$str		ファイル内容
	 * @access		public
	 */
	function appendFile($filename, $str){
		if (!$fp = fopen($filename, "a+")){
			$Err = $filename . "のオープンに失敗しました";
			return false;
		}

		fwrite($fp, $str);
		fclose ($fp);

		return true;
	}

	/**
	 * apacheのログを解析して所定の値を返す
	 * このメソッドを利用するためには、入力されるapacheのログが、httpd.confにデフォルトで定義してある『combined』のCustomLogである必要がある
	 *
	 * @access		public
	 * @param		String		$str		生ログ文字列
	 * @return		array		解析結果の連想配列
	 */
	function getApacheLogAnalysis($str){
		// 入力されたログ文字列を行分割(＝1レコードずつ処理する)
		$lines = explode("\n", $str);

		for ($i = 0; $i < count($lines); $i++){
			// 最後の空行でなければ処理
			if (!($i == count($lines) - 1 && $lines[$i] == "")){
				// 正規表現([]で囲まれたもの)で日時の値を取得
				preg_match_all("/(\[)(.*?)(\])/", $lines[$i], $regs);
				$information[$i]['datetime'] = $regs[2][0];

				// 正規表現(DoubleQuoteで囲まれたもの)でいくつかの値を取得
				preg_match_all("/(\\\")(.*?)(\\\")/", $lines[$i], $regs);
				$information[$i]['request'] = $regs[2][0];
				$information[$i]['referer'] = $regs[2][1];
				$information[$i]['useragent'] = $regs[2][2];

				// スペース分割した最初のブロックがREMOTE_HOST
				$regs = explode(" ", $lines[$i]);
				$information[$i]['remotehost'] = $regs[0];
			}
		}

		return $information;
	}

	/**
	 * 2次元配列で渡されたデータをCSV形式で記録する
	 * 配列のインデックスは、$sample[レコード番号][カラム番号]
	 *
	 * @access		public
	 * @param		String		$filename	記録対象ファイル
	 * @param		String		$str		記録すべき配列
	 * @param		String		$mode		対象ファイルのオープンモード
	 * @return		bool		記録結果
	 */
	function writeCSV($filename, $str, $mode = "a+"){
		$recordString = "";
		for ($i = 0; $i < count($str); $i++){
			for ($j = 0; $j < count($str[$i]); $j++){
				$recordString .= "\"" . $str[$i][$j] . "\"";
				if ($j != count($str[$i]) - 1)
					$recordString .= ",";
				else
					$recordString .= "\n";
			}
		}

		if (!$fp = fopen($filename, $mode))
			return false;

		fwrite($fp, $recordString);
		fclose ($fp);

		return true;
	}

	/**
	 * かなり脈絡なくツールが入っています...
	 * 8桁数値形式の日付を、表示用の数値形式に直して返します。
	 * 通常日付チェックそのものは行われているはずとして、ここでは一切エラーチェックしない
	 *
	 * @access		public
	 * @param		String		$date		加工対象数値
	 * @param		String		$type		表示方法(1(-):YYYY-MM-DD  2(/):YYYY/MM/DD  それ以外:YYYY年MM月DD日)
	 * @return		String		加工結果
	 */
	function formatNumberToDate($date, $type = ""){
		$year = substr($date, 0, 4);
		$month = substr($date, 4, 2);
		$day = substr($date, 6, 2);

		if ($type == 1 || $type == "-")
			$dateString = $year . "-" . $month . "-" . $day;
		else if ($type == 2 || $type == "/")
			$dateString = $year . "/" . $month . "/" . $day;
		else
			$dateString = $year . "年" . $month . "月" . $day . "日";

		return $dateString;
	}

	/**
	 * ハイフンやスラッシュが入った日付からそれら記号を取り除きます
	 *
	 * @access		public
	 * @param		String		$date		加工対象日付
	 * @return		String		加工結果
	 */
	function getStrippedDate($date){
		$date = str_replace("-", "", $date);
		$date = str_replace("/", "", $date);
		$date = str_replace("年", "", $date);
		$date = str_replace("月", "", $date);
		$date = str_replace("日", "", $date);

		return $date;
	}

	/**
	 * DBのtimestamp型の日時から日付だけを取り出します
	 *
	 * @access		public
	 * @param		String		$date		加工対象日時
	 * @return		String		加工結果
	 */
	function getDateFromTimestamp($date){
		$date = substr($date, 0, 10);

		return $date;
	}

	/**
	 * 配列からバイナリ文字列(ビット文字列)を返します
	 * 単に配列の要素をつなげて返すという言い方も...
	 *
	 * @access		public
	 * @param		array		$array		加工対象配列
	 * @return		String		加工結果
	 */
	function getBinaryString($array){
		$binaryString = "";

		for ($i = 0; $i < count($array); $i++){
			$binaryString .= $array[$i];
		}

		return $binaryString;
	}

	/**
	 * ファイルから拡張子を抜き出します。
	 *
	 * @access		public
	 * @param		string		$Filename		ファイル名
	 * @return		String		拡張子
	 */
	function getExtensionOfFilename($Filename){
		$regs = explode('.', $Filename);

		return $regs[count($regs) - 1];
	}

	/**
	 * 指定されたディレクトリ内のファイル名をリストします。
	 *
	 * @param		string		$DirectoryPath		ディレクトリパス
	 * @return		array		ファイル名の配列
	 * @access		public
	 */
	function getFileListInDir($DirectoryPath){
		if (is_dir($DirectoryPath)) {
			if ($dh = opendir($DirectoryPath)) {
				while (($eachfile = readdir($dh)) !== false) {
					if ($eachfile != '.' && $eachfile != '..')
						$files[] = $eachfile;
				}

				closedir($dh);
			}
		}

		return $files;
	}

	/**
	 * 指定された文字列を示すQRコードを作成します。
	 *
	 * @param		string		$Filename		保存ファイル名
	 * @param		string		$Target			元文字列
	 * @access		public
	 */
	public static function  createQRCode($Filename, $Target) {
		$BarcodeURL = _MAIN_URL . 'admin/qr/php/qr_img.php?d=' . urlencode($Target) . '&t=J';

		$Image1 = imagecreatefromjpeg($BarcodeURL);
		$Image2 = imagecreate(120, 120);

		$Width = imagesx($Image1);
		$Height = imagesy($Image1);
/*
		imagecolorallocate($Image2,255,255,255);
		imagecopyresized($Image2, $Image1, 0, 0, 0, 0, 120, 120, $Width, $Height);
		imagecolortransparent ($Image2, imagecolorclosest($Image2, 255, 255, 255));

		imagegif($Image2, $Filename);

		imagedestroy($Image1);
		imagedestroy($Image2);
		*/
	}

	/**
	 * 複数値をエンコードします(テキスト版)。
	 * 
	 * @access		public
	 * @param		string		$Value		処理対象値
	 * @return		string		処理後値
	 */
static	function encodePluralValue($Value, $Separator = '|'){
		if (is_array($Value)) {
			for ($i = 0; $i < count($Value); $i++) {
				if ($i > 0)
					$Encoded .= $Separator;
				$Encoded .= $Value[$i];
			}
		}
		else
			$Encoded = $Value;

		$Encoded = '|' . $Encoded . '|';

		return $Encoded;
	}

	/**
	 * 複数値をデコードします(テキスト版)。
	 * 
	 * @access		public
	 * @param		string		$value		処理対象値
	 * @return		string		処理後値
	 */
static	function decodePluralValue($Value, $Separator = '|'){
		if (substr($Value, 0, 1) == '|')
			$Value = substr($Value, 1, -1);

		if ($Value == NULL)
			return array();

		$Decoded = explode($Separator, $Value);

		return $Decoded;
	}

	/**
	 * mb_convert_encoding()の拡張
	 *
	 * @param  mixed  $target       arrayかstring
	 * @param  string $toEncoding   エンコード先
	 * @param  string $fromEncoding エンコード元(default:null)
	 * @return mixed  arrayが来たらarrayを、stringが来たらstringを
	 */
	function _mbConvertEncodingEx($target, $toEncoding, $fromEncoding = null)
	{
		if (is_array($target)) {
			foreach ($target as $key => $val) {
				if (is_null($fromEncoding)) {
					$fromEncoding = mb_detect_encoding($val);
				}
				$target[$key] = SPFWTools::_mbConvertEncodingEx($val,$toEncoding,$fromEncoding);
			}
		} else {
			if	(is_null($fromEncoding)) {
				$fromEncoding = mb_detect_encoding($target);
			}
			$target = mb_convert_encoding($target, $toEncoding,$fromEncoding);
		}
		return $target;
	}

	/**
	 * テキストをメール中の引用の形に加工
	 *
	 * @param  string $Value   加工対象
	 * @return string 加工結果
	 */
	function getReference($Value) {
		$Value = str_replace("\r\n", "\n", $Value);

		$Value = "> " . $Value;

		$Value = str_replace("\n", "\n> ", $Value);

		return $Value;
	}
}
?>
