<?php
/**
 * SPFWMailElementクラス
 *
 * メールを読み込み、解析して所定のプロパティに格納するクラスです。
 *
 * @package 
 * @access  public
 * @author  MasahitoSAMEKAWA <masahito@souppot.jp>
 * @create  2004/08/22
 * @version 0.1
 **/

// 依存
include_once _CLS_DIR . "SPFWLog.cls";

class SPFWMailElement {
	/**
	 * エラー
	 */
	var $Err;

	/**
	 * ファイル名
	 */
	var $FileName;

	/**
	 * メール全体
	 */
	var $RawMail;
	/**
	 * ヘッダ部
	 */
	var $Headers;
	/**
	 * ボディ部
	 */
	var $Body;

	/**
	 * Fromヘッダ
	 */
	var $From;
	/**
	 * 送信者
	 */
	var $Sender;
	/**
	 * ReplyToヘッダ
	 */
	var $ReplyTo;
	/**
	 * Toヘッダ
	 */
	var $To;
	/**
	 * Ccヘッダ
	 */
	var $Cc;
	/**
	 * Bccヘッダ
	 */
	var $Bcc;
	/**
	 * メールタイトル
	 */
	var $Subject;
	/**
	 * コメントヘッダ
	 */
	var $Comments;
	/**
	 * キーワード
	 */
	var $Keywords;
	/**
	 * 送信日時
	 */
	var $Date;
	/**
	 * 
	 */
	var $Posted;
	/**
	 * メッセージID
	 */
	var $MessageID;
	/**
	 * 
	 */
	var $InReplyTo;
	/**
	 * リファレンス
	 */
	var $References;
	/**
	 * ReturnPathヘッダ
	 */
	var $ReturnPath;
	/**
	 * ContentsTypeヘッダ
	 */
	var $ContentType;

	/**
	 * コンストラクタ
	 * ファイル名を受け取り処理対象のメールを読み込みます。
	 * @param		String		$filename	読込ファイル(省略時STDIN)
	 * @access		public
	 */
	function SPFWMailElement($filename = "php://stdin"){
		// 初期化
		$this->FileName = $filename;

		// メール取得
		if (!$this->getData())
			return FALSE;

		// ヘッダと本文の分離
		$this->separateHeader();

		// ヘッダ解析
		$this->analyzeHeader();

		return TRUE;
	}

	/**
	 * メールの取り込み
	 * @access		private
	 * @return		bool	処理結果
	 */
	function getData() {
		$this->Err = "";

		if (!($fp = fopen($this->FileName, "r")))
			return FALSE;

		$this->RawMail = NULL;

		while(!feof($fp)) {
			$line =fgets($fp, 10240);
			$this->RawMail .= $line;
		}

		fclose($fp);

		// 改行統一
		$this->RawMail = str_replace ("\r\n", "\n", $this->RawMail);
		$this->RawMail = str_replace ("\n\r", "\n", $this->RawMail);
		$this->RawMail = str_replace ("\r", "\n", $this->RawMail);

		return TRUE;
	}

	/**
	 * HeaderとBodyの分離
	 *
	 * @access		private
	 */
	function separateHeader() {
		$regs = explode("\n\n", $this->RawMail, 2);

		$this->Headers = $regs[0];
		$this->Body = $regs[1];
	}

	/**
	 * Headerから各種情報を取り出す
	 *
	 * @access		private
	 */
	function analyzeHeader() {
		$this->Received		=	$this->getMailHeader($this->Headers,"Received");
		$this->From			=	$this->getMailHeader($this->Headers,"From");
		$this->Sender		=	$this->getMailHeader($this->Headers,"Sender");
		$this->ReplyTo		=	$this->getMailHeader($this->Headers,"Reply-To");
		$this->To			=	$this->getMailHeader($this->Headers,"To");
		$this->Cc			=	$this->getMailHeader($this->Headers,"Cc");
		$this->Bcc			=	$this->getMailHeader($this->Headers,"Bcc");
		$this->Subject		=	$this->getMailHeader($this->Headers,"Subject");
		$this->Comments		=	$this->getMailHeader($this->Headers,"Comments");
		$this->Keywords		=	$this->getMailHeader($this->Headers,"Keywords");
		$this->Date			=	$this->getMailHeader($this->Headers,"Date");
		$this->Posted		=	$this->getMailHeader($this->Headers,"Posted");
		$this->MessageID	=	$this->getMailHeader($this->Headers,"Message-Id");
		$this->InReplyTo	=	$this->getMailHeader($this->Headers,"In-Reply-To");
		$this->References	=	$this->getMailHeader($this->Headers,"References");
		$this->ReturnPath	=	$this->getMailHeader($this->Headers,"Return-Path");
		$this->ContentType	=	$this->getMailHeader($this->Headers,"Content-Type");
	}

	/**
	 * メールアドレス取出し
	 *
	 * @access		private
	 * @param		String		$line	１行の文字列（行末改行コード含む）
	 * @return		String		xxxx@hoge.hoge 形式のメールアドレス
	 */
	function getMailAddr($line) {
		eregi("<.*>", $line, $col);

		if ($col[0] != NULL){
			$line	=	$col[0];
			$line = str_replace("<", "", $line);
			$line = str_replace(">", "", $line);
		}
		/*preg_match_all("(<)(.*)(>)", $line, $col);
		$line = $col[0][2];*/

		//$line = str_replace("\n", "", $line);
		/*$line = ereg_replace("^[a-zA-Z\-]+: ", "", $line);*/
		//$line = strstr($line, ": ");
		//$line = mb_ereg_replace(">.*$", "", $line);
		//$line = mb_ereg_replace("^.*<", "", $line);

		return $line;
	}

	/*
	 * メールヘッダー情報の取得<BR>
	 * 現在、同一のヘッダーが複数存在する場合は、始めのひとつだけを返します。<BR>
	 *
	 * @access		private
	 * @param String $ARG_TEXT メール全体のテキスト
	 * @param String $ARG_HEADER 取得したいヘッダー名
	 * @return String ヘッダーに対応する値
	 */
	function getMailHeader($ARG_TEXT, $ARG_HEADER){
		eregi("$ARG_HEADER: [^\n]*", $ARG_TEXT, $header);

		$value	=	trim(strstr(trim($header[0]), ' '));

		return	$this->decode($value);

	}

	/*
	 * メールヘッダーデコード<BR>
	 * デコードの必要のある個所を探して、デコードを行ないます<BR>
	 * 現在、BASE64にのみ対応しています<BR>
	 *
	 * @access		private
	 * @param String $ARG_TEXT デコード対象テキスト
	 * @return デコード結果
	 */
	function decode($ARG_TEXT){

		$myText	=	$ARG_TEXT;		// 退避しておく

		// =?  ?= で囲まれた部分があるか調べて、デコードしてやる
		while( eregi("=\?.*\?=", $myText, $col) ){
			$data	=	split ("\?", $col[0]);
			// デコード種類の判定
			if($data[2]=="B"){
				// BASE64でエンコードされている時
				$encoded_word	=	base64_decode ($data[3]);
			}

			$myText	=	str_replace ($col[0], $encoded_word, $myText);
		}

		return $myText;

	}
}
?>
