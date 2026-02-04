<?php
/**
 * SPFWBounceクラス
 *
 * バウンスメール処理専門のクラスです。
 * このクラスはSPFWMailElementクラスを拡張しています。
 *
 * @package 
 * @access  public
 * @author  MasahitoSAMEKAWA <masahito@souppot.jp>
 * @create  2004/08/22
 * @version 0.1
 **/

// 依存
include_once _CLS_DIR . "SPFWLog.cls";
include_once _CLS_DIR . "SPFWMailElement.cls";
include_once _CLS_DIR . "SPFWInputCheck.cls";

// バウンスメールとして検知すべきSubjectの定義
$CheckSubject[] = "Undelivered Mail Returned to Sender";
$CheckSubject[] = "Returned mail: see transcript for details";
$CheckSubject[] = "Returned mail: User unknown";
$CheckSubject[] = "Returned mail:";
$CheckSubject[] = "Returned Mail: Sending Fail";
$CheckSubject[] = "Mail delivery failure";
$CheckSubject[] = "Mail delivery failed: returning message to sender";
$CheckSubject[] = "failure delivery";
$CheckSubject[] = "failure notice";
$CheckSubject[] = "Error Mail";
$CheckSubject[] = "Returned mail: Cannot send message within 30 minutes";
$CheckSubject[] = "Returned mail: Can't create output";
$CheckSubject[] = "Delivery Status Notification (Failure)";
$CheckSubject[] = "Returned mail: Service unavailable";
$CheckSubject[] = "Delivery Notification: Delivery has failed";
$CheckSubject[] = "DELIVERY FAILURE";
$CheckSubject[] = "Returned mail: Service unavailable";
$CheckSubject[] = "Warning: could not send message for past 4 hours";
$CheckSubject[] = "Returned Mail";
$CheckSubject[] = "Mail System Error - Returned Mail";

class SPFWBounce extends SPFWMailElement {
	/**
	 * 本文中に含まれる全てのメールアドレスの配列
	 */
	Var $EMailInBody;
	/**
	 * バウンス処理されるべきアドレスの候補
	 */
	Var $BounceAddress;

	/**
	 * 現在のメールがバウンスメールかどうかを判定する
	 *
	 * @access		public
	 * @return		Bool		このメールがバウンスメールかどうか
	 */
	function isBounceMail() {

		$myLog = new SPFWLog();	
		#$myLog->debug("Subject is " . $this->Subject . "");

		// Subjectがバウンスメール検知対象かどうかをチェックする
		if (!$this->checkSubject())
			return FALSE;

		// ez関係のように見えるのにバウンスアドレスが取れなくて取りこぼすのを防ぎます
		if (stristr($this->RawMail, "@ezweb.ne.jp"))
			$EZFlg = TRUE;

		if (stristr($this->Body, "Hi. This is the qmail-send program at admin.ebet.jp.")) {
			$this->getEMailInBody();
			$BounceAddress = $this->EMailInBody[0];
		}
		else if (stristr($this->Body, "The user(s) account is disabled.")) {
			$this->getEMailInBody();
			$BounceAddress = $this->EMailInBody[0];
		}
		else {
			$this->Body = eregi_replace("reply-to", " ", $this->Body);
			$BounceAddress = $this->getMailAddr($this->getMailHeader($this->Body, "To"));

			if ($BounceAddress == NULL)
				$BounceAddress = $this->getMailAddr($this->getMailHeader($this->Body, "here"));

			if ($BounceAddress == NULL)
				$BounceAddress = $this->getMailAddr($this->getMailHeader($this->Headers, "X-Failed-Recipients"));

			if ($BounceAddress == NULL)
				$BounceAddress = $this->getMailAddr($this->getMailHeader($this->Body, "Final-Recipient"));

			if ($BounceAddress == "undisclosed-recipients:;")
				$BounceAddress2 = $this->getMailAddr($this->getMailHeader($this->Body, "Final-Recipient"));

			if ($BounceAddress2 != NULL) {
				$BounceAddress2 = str_replace ("rfc822; ", "", $BounceAddress2);
				$BounceAddress = str_replace ("RFC822; ", "", $BounceAddress2);
			}
		}

		if (strlen($BounceAddress) > 0 && SPFWInputCheck::isRightEMail($BounceAddress)) {
			// Deferredのチェック対象
			$CheckDeferred[] = "deferred";
			$CheckDeferred[] = "504 Command parameter not implemented";

			for ($i = 0; $i < count($CheckDeferred); $i++) {
				if(stristr($this->Body, $CheckDeferred[$i]) == TRUE) {
					return FALSE;
					exit();
				}
			}

			// 本文のチェック対象
			// SMTP500番台
			$CheckBodyErrorMsg[] = "user unknown";
			$CheckBodyErrorMsg[] = "The user(s) account is disabled.";

			$CheckBodyMsg[] = "Sorry, I wasn't able to establish an SMTP connection.";

			if (ereg("@ezweb.ne.jp$", $BounceAddress)) {
				for ($i = 0; $i < count($CheckBodyErrorMsg); $i++) {
					if(stristr($this->Body, $CheckBodyErrorMsg[$i]) == TRUE) {
						$this->BounceAddress = $BounceAddress;
						return TRUE;
						exit();
					}
				}

				return FALSE;
			}
			else {
				$this->BounceAddress = $BounceAddress;
				return TRUE;
			}
		}
		else
			return FALSE;
	}

	/**
	 * サブジェクトがバウンスメールのものかどうかチェックする
	 *
	 * @access		public
	 * @return		Bool		このメールがバウンスメールかどうか
	 */
	function checkSubject() {
		global $CheckSubject;

		if (ereg($CheckSubject[19], $this->Subject)) {
			return TRUE;
		}
		else if ($CheckSubject[12] == $this->Subject) {
			return TRUE;
		}

		for ($i = 0; $i < count($CheckSubject); $i++){
			if (ereg($CheckSubject[$i], $this->Subject))
				return TRUE;
				
		}

		return FALSE;
	}

	/**
	 * 本文内に含まれるメールアドレスを全て取り出す
	 *
	 * @access		private
	 */
	function getEMailInBody() {
		preg_match_all("/(<)(.*?)(>)/", $this->Body, $regs);

		for ($i = 0; $i < count($regs[0]); $i++){
			$EMail = $regs[2][$i];

			if (SPFWInputCheck::isRightEMail($EMail))
				$this->EMailInBody[] = $EMail;
		}
	}
}
?>
