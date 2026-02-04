<?php
/**
 * SPFWDateクラス
 *
 * 日付関係のツール群です。
 *
 * @package 
 * @access  public
 * @author  MasahitoSAMEKAWA <masahito@souppot.jp>
 * @create  2003/12/03
 * @version 0.1
 **/

define ("EXIST_CLASS_SPFWDate","yes");

class SPFWDate {

	/**
	 * 日時を分解します。
	 * 記号等が完全に取り除かれた状態が前提となります。
	 *
	 * @access		public
	 * @param		String		$timestamp		処理対象日付
	 * @param		Array		加工結果の配列
	 */
static	function getPiecesOfTimestamp($timestamp){
		// 整形して各値をセット
		$Year = substr($timestamp,0,4);
		$Month = substr($timestamp,4,2);
		$Day = substr($timestamp,6,2);
		$Hour = substr($timestamp,9,2);
		$Minute = substr($timestamp,11,2);
		$Second = substr($timestamp,13,2);

		// 配列に格納して返す
		$myTimestamp['Year'] = $Year;
		$myTimestamp['Month'] = $Month;
		$myTimestamp['Day'] = $Day;
		$myTimestamp['Hour'] = $Hour;
		$myTimestamp['Minute'] = $Minute;
		$myTimestamp['Second'] = $Second;

		return $myTimestamp;
	}

	/**
	 * ハイフンやスラッシュが入った日時からそれら記号を取り除きます
	 *
	 * @access		public
	 * @param		String		$timestamp	加工対象日時
	 * @return		String		加工結果
	 */
static	function getStrippedTimestamp($timestamp){
		$timestamp = str_replace("-", "", $timestamp);
		$timestamp = str_replace("/", "", $timestamp);
		$timestamp = str_replace(":", "", $timestamp);
		$timestamp = str_replace(".", "", $timestamp);
		$timestamp = str_replace("年", "", $timestamp);
		$timestamp = str_replace("月", "", $timestamp);
		$timestamp = str_replace("日", "", $timestamp);
		$timestamp = str_replace("時", "", $timestamp);
		$timestamp = str_replace("分", "", $timestamp);

		return $timestamp;
	}

	/**
	 * 指定日数日付を足して結果を返します。
	 *
	 * @access		public
	 * @param		String		$logMsg		ログ出力メッセージ
	 * @return		bool		書込処理の結果
	 */
	static function getDatePlus($date, $days){
		$date = SPFWDate::getStrippedTimestamp($date);
		$myTimestamp = SPFWDate::getPiecesOfTimestamp($date);

		$returnDate = date("Ymd", mktime(0, 0, 0, $myTimestamp['Month'], $myTimestamp['Day'] + $days, $myTimestamp['Year']));

		return $returnDate;
	}

	/**
	 * 指定月数月を足して結果を返します。
	 *
	 * @access		public
	 * @param		String		$logMsg		ログ出力メッセージ
	 * @return		bool		書込処理の結果
	 */
	static function getMonthPlus($date, $months){
		$date = SPFWDate::getStrippedTimestamp($date);

		$date = SPFWDate::getFirstOfThisMonth($date);

		$myTimestamp = SPFWDate::getPiecesOfTimestamp($date);

		$returnDate = date("Ymd", mktime(0, 0, 0, $myTimestamp['Month'] + $months, $myTimestamp['Day'], $myTimestamp['Year']));

		return $returnDate;
	}

	/**
	 * 8桁数値形式の日付を、表示用の数値形式に直して返します。
	 * 通常日付チェックそのものは行われているはずとして、ここでは一切エラーチェックしない
	 *
	 * @access		public
	 * @param		String		$timestamp	加工対象数値
	 * @param		String		$type		表示方法(1(-):YYYY-MM-DD hh:mm:ss  2(/):YYYY/MM/DD hh:mm:ss  それ以外:YYYY年MM月DD日 hh時mm分ss秒)
	 * @param		String		$needTime	時間の部分が必要かどうか
	 * @param		String		$custom		完全なフォーマット指定(例: M-D-Y h:m.s)
	 * @return		String		加工結果
	 */
	static function getFormattedTimestamp($date, $type = "", $needTime = false, $custom = ""){
		$myTimestamp = SPFWDate::getPiecesOfTimestamp($date);

		if ($custom == ""){
			// まず日付を整形
			if ($type == 1 || $type == "-")
				$formatted = $myTimestamp['Year'] . "-" . $myTimestamp['Month'] . "-" . $myTimestamp['Day'];
			else if ($type == 2 || $type == "/")
				$formatted = $myTimestamp['Year'] . "/" . $myTimestamp['Month'] . "/" . $myTimestamp['Day'];
			else
				$formatted = $myTimestamp['Year'] . "年" . $myTimestamp['Month'] . "月" . $myTimestamp['Day'] . "日";

			// needTimeがTRUEで、かつ時刻要素があれば時刻も処理する
			if ($needTime && $myTimestamp['Hour'] != "" && $myTimestamp['Minute'] != "" && $myTimestamp['Second'] != ""){
				if ($type == 1 || $type == "-")
					$formatted .= " " . $myTimestamp['Hour'] . ":" . $myTimestamp['Minute'] . ":" . $myTimestamp['Second'];
				else if ($type == 2 || $type == "/")
					$formatted .= " " . $myTimestamp['Hour'] . ":" . $myTimestamp['Minute'] . ":" . $myTimestamp['Second'];
				else
					$formatted .= " " . $myTimestamp['Hour'] . "時" . $myTimestamp['Minute'] . "分" . $myTimestamp['Second'] . "秒";
			}
		}
		else{
			$formatted = $custom;

			$formatted = str_replace("Y", $myTimestamp['Year'], $formatted);
			$formatted = str_replace("M", $myTimestamp['Month'], $formatted);
			$formatted = str_replace("D", $myTimestamp['Day'], $formatted);
			$formatted = str_replace("h", $myTimestamp['Hour'], $formatted);
			$formatted = str_replace("m", $myTimestamp['Minute'], $formatted);
			$formatted = str_replace("s", $myTimestamp['Second'], $formatted);
		}

		return $formatted;
	}

	/**
	 * 与えられた日付から、曜日を求めて指定の形式で返します。
	 * 形式は適当に足してください。
	 *
	 * @access		public
	 * @param		String		$timestamp	加工対象日時
	 * @param		String		$type		表示方法(1:月曜日  2:月  3:Monday)
	 * @param		String		$needTime	時間の部分が必要かどうか
	 * @return		String		加工結果
	 */
	function getDayOfWeek($date, $type = 1){
		$date = SPFWDate::getStrippedTimestamp($date);
		$myTimestamp = SPFWDate::getPiecesOfTimestamp($date);

		$DAY_OF_WEEK[0] = array('0', '日曜日', '日', 'Sunday');
		$DAY_OF_WEEK[1] = array('1', '月曜日', '月', 'Monday');
		$DAY_OF_WEEK[2] = array('2', '火曜日', '火', 'Tuesday');
		$DAY_OF_WEEK[3] = array('3', '水曜日', '水', 'Wednesday');
		$DAY_OF_WEEK[4] = array('4', '木曜日', '木', 'Thursday');
		$DAY_OF_WEEK[5] = array('5', '金曜日', '金', 'Friday');
		$DAY_OF_WEEK[6] = array('6', '土曜日', '土', 'Saturday');

		$number = date("w", mktime(0, 0, 0, $myTimestamp['Month'], $myTimestamp['Day'], $myTimestamp['Year']));

		return $DAY_OF_WEEK[$number][$type];
	}

	/**
	 * 与えられたふたつの日付から、その日数を求めます
	 *
	 * @access		public
	 * @param		String		$timestamp1	計算対象開始日時
	 * @param		String		$timestamp2	計算対象終了日時
	 * @return		integer		計算結果
	 */
	static function getDayCountOfTerm($timestamp1, $timestamp2){
		// 形式を整えて配列へ
		$timestamp1 = SPFWDate::getStrippedTimestamp($timestamp1);
		$timestamp2 = SPFWDate::getStrippedTimestamp($timestamp2);
		$myTimestamp1 = SPFWDate::getPiecesOfTimestamp($timestamp1);
		$myTimestamp2 = SPFWDate::getPiecesOfTimestamp($timestamp2);

		// 両日付のタイムスタンプを得る
		$unixTimestamp1 = mktime(0, 0, 0, $myTimestamp1['Month'], $myTimestamp1['Day'] + $days, $myTimestamp1['Year']);
		$unixTimestamp2 = mktime(0, 0, 0, $myTimestamp2['Month'], $myTimestamp2['Day'] + $days, $myTimestamp2['Year']);

		// 差を求めて1日の秒数で割る
		$days = ($unixTimestamp2 - $unixTimestamp1) / (24*60*60);

		return $days;
	}

	/**
	 * 与えられた日付の月の最初の日を求めます
	 *
	 * @access		public
	 * @param		String		$timestamp1	計算対象開始日時
	 * @return		String		加工結果
	 */
static	function getFirstOfThisMonth($timestamp1){
		// 形式を整えて配列へ
		$timestamp1 = SPFWDate::getStrippedTimestamp($timestamp1);
		$myTimestamp1 = SPFWDate::getPiecesOfTimestamp($timestamp1);

		// 初日を得る
		$returnDate = date("Ymd", mktime(0, 0, 0, $myTimestamp1['Month'], 1, $myTimestamp1['Year']));

		return $returnDate;
	}

	/**
	 * 与えられた日付の月の最後の日を求めます
	 *
	 * @access		public
	 * @param		String		$timestamp1	計算対象開始日時
	 * @return		String		加工結果
	 */
	static function getEndOfThisMonth($timestamp1){
		// 形式を整えて配列へ
		$timestamp1 = SPFWDate::getStrippedTimestamp($timestamp1);
		$myTimestamp1 = SPFWDate::getPiecesOfTimestamp($timestamp1);

		// 初日を得る
		$returnDate = date("Ymd", mktime(0, 0, 0, $myTimestamp1['Month'] + 1, 0, $myTimestamp1['Year']));

		return $returnDate;
	}
}
?>
