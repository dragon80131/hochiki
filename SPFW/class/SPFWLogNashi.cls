<?php
/**
 * SPFWLogNashiクラス
 *
 * 各種ログ記録を担います。
 * includeディレクトリの中にプロパティファイル"log.properties"が必要です。
 *
 * @package 
 * @access  public
 * @author  MasahitoSAMEKAWA <masahito@souppot.jp>
 * @create  2003/01/31
 * @version 0.1
 **/

define ("EXIST_CLASS_ASLOG","yes");

class SPFWLogNashi {
	/**
	 * デバッグ用出力ファイル
	 */
	var $debugFile;
	/**
	 * DB処理出力ファイル
	 */
	var $dbFile;
	/**
	 * 実行SQL出力ファイル
	 */
	var $sqlFile;
	/**
	 * エラー出力ファイル
	 */
	var $errorFile;

	/**
	 * エラーメッセージ
	 */
	var $Err;
	
	/**
	 * コンストラクタ
	 * プロパティファイルからログファイルのパスを読み込み、クラスプロパティにセットします。
	 * directory.propertiesが呼出元で読み込まれていることが必要です。
	 * @access		public
	 */
	function  __construct() {
		// プロパティセット
		$this->debugFile = _DEBUG_LOG;
		$this->dbFile = _DB_LOG;
		$this->sqlFile = _SQL_LOG;
		$this->errorFile = _ERROR_LOG;
		
		// エラー初期化
		$this->Err = "";
	}

	/**
	 * デバッグ出力を記録します。
	 * @access		public
	 * @param		String		$logMsg		ログ出力メッセージ
	 * @return		bool		書込処理の結果
	 */
	function debug($logMsg){
		if ($this->debugFile == "")
			return FALSE;

		if (!$this->write($this->debugFile, $logMsg))
			return FALSE;

		return TRUE;
	}

	/**
	 * DB処理出力ファイルへの記録します。
	 * @access		public
	 * @param		String		$logMsg		ログ出力メッセージ
	 * @return		bool		書込処理の結果
	 */
	function db($logMsg){
		if ($this->dbFile == "")
			return FALSE;

		if (!$this->write($this->dbFile, $logMsg))
			return FALSE;

		return TRUE;
	}

	/**
	 * SQL処理出力ファイルへの記録します。
	 * @access		public
	 * @param		String		$logMsg		ログ出力メッセージ
	 * @return		bool		書込処理の結果
	 */
	function sql($logMsg){
/*		if ($this->sqlFile == "")
			return FALSE;

		if (!$this->write($this->sqlFile, $logMsg))
			return FALSE;
*/
		return TRUE;
	}

	/**
	 * エラー出力ファイルへの記録します。
	 * @access		public
	 * @param		String		$logMsg		ログ出力メッセージ
	 * @return		bool		書込処理の結果
	 */
	function error($logMsg){
		if ($this->errorFile == "")
			return FALSE;

		if (!$this->write($this->errorFile, $logMsg))
			return FALSE;

		return TRUE;
	}

	/**
	 * メール送信ログファイルへの記録します。
	 * @access		public
	 * @param		String		$logMsg		ログ出力メッセージ
	 * @return		bool		書込処理の結果
	 */
	function mail($logMsg){
		if ($this->mailFile == "")
			return FALSE;

		if (!$this->write($this->mailFile, $logMsg))
			return FALSE;

		return TRUE;
	}

	/**
	 * データベースへの接続を開き、その結果を返します。
	 * @access		public
	 * @param		String		$FileName	ログファイル名
	 * @param		String		$logMsg		ログ出力メッセージ
	 * @return		bool		書込処理の結果
	 */
	function write($FileName = "", $logMsg = "") {
		global $PHP_SELF;

		// ファイル名称チェック
		if ($FileName == "") {
			$this->Err = "ファイル名が指定されていません";
			return FALSE;
		}

		if (!$fp = fopen($FileName, "a")){
			$this->Err = "ファイルがオープンできませんでした";
			return FALSE;
		}

		if (!flock($fp, LOCK_EX)){
			$this->Err = "ファイルがロックできませんでした";
			return FALSE;
		}

		fwrite($fp, "[" . date("Y/m/d H:i") . "] " . $PHP_SELF . " \n");
		fwrite($fp, $logMsg . "\n\n");

		if (!flock($fp, LOCK_UN)){
			$this->Err = "ファイルがロックできませんでした";
			return FALSE;
		}

		if (!fclose($fp)){
			$this->Err = "ファイルがクローズできませんでした";
			return FALSE;
		}

		return TRUE;
	}
}
?>
