<?php
include_once _CLS_DIR . "SPFWMailSend.cls";
include_once _CLS_DIR . "SPFWMobile.cls";

class SPFWMail {
	var $To;
	var $Cc;
	var $Bcc;
	var $Subject;
	var $Message;
	var $From;
	var $FromName;
	var $ReplyTo;
	var $ReturnPath;
	var $HtmlFlg;
	var $Upfile;
	var $Err;

	function sendMail() {
		if (!$GLOBALS['IfEmoji']) {
			if ($this->HtmlFlg) {
				// メール送信クラス生成と各種共通(固定)パラメータセット
				$myMail = new SPFWMailSend();

				$myMail->IsSendmail();
				$myMail->SetLanguage("en", _LIBRARY_ROOT . "language/");
				$myMail->IsHTML(TRUE);
				$myMail->CharSet = "iso-2022-jp";
				$myMail->WordWrap = 200;

				$myMail->From = $this->From;
				$myMail->Sender = $this->ReturnPath;

				$myMail->FromName = mb_encode_mimeheader($this->FromName, "ISO-2022-JP", "Q");
				$myMail->FromName = str_replace("\r", NULL, $myMail->FromName);
				$myMail->FromName = str_replace("\n", NULL, $myMail->FromName);

				$myMail->AddReplyTo($this->ReplyTo, NULL);

				$myMail->ClearAllRecipients();

				$Message = stripslashes($this->Message);

				// メール送信準備
				$myMail->AddAddress($this->To, NULL); 
				$myMail->Subject = mb_convert_encoding($this->Subject, "JIS", "EUC-JP");
				$myMail->Body = mb_convert_encoding($this->Message, "JIS", "EUC-JP");

				$myMail->Send();
				unset ($myMail);
			}
			else {
				$HeaderLine = "From: " . mb_encode_mimeheader($this->FromName, "ISO-2022-JP", "Q") . " <" . $this->From . ">\n";
				$HeaderLine .= "Reply-To: " . $this->ReplyTo . "\n";
				if(!mb_send_mail($this->To, $this->Subject, $this->Message, $HeaderLine, "-f " . $this->ReturnPath)) {
					$this->Err = "メールの送信に失敗しました。システム管理者にご連絡ください。";
					return FALSE;
				}
			}
		}
		else {
			$HtmlNo = ($this->HtmlFlg) ? 1 : 0;
			$DecomeFlg = ($this->HtmlFlg) ? 1 : 0;

			/*if(!$GLOBALS['emoji_obj']->emoji_send_mail($this->To,$this->To,$this->FromName,$this->From,$this->Subject,$this->Message,$this->ReplyTo,$this->ReplyTo,$this->ReturnPath, $HtmlNo, NULL, 'JIS', NULL, NULL, 1)) {
				$this->Err = "メールの送信に失敗しました。システム管理者にご連絡ください。";
				return FALSE;
			}*/

			$TODATA[$this->To] = $this->To;
			$CCDATA = NULL;
			$BCCDATA = NULL;
			$from_name = $this->FromName;
			$from_add = $this->From;
			$subject = $this->Subject;
			$body_plain = $this->Message;
			if ($this->HtmlFlg)
				$body_html = $this->Message;
			$repry_name = NULL;
			$repry_to = $this->ReplyTo;
			$return_path = $this->ReturnPath;
			$upfile = $this->Upfile;

			$TempCarrier = SPFWMobile::getCarrierCodeByEMail($this->To);
			if ($TempCarrier == 1)
				$to_career = '';
			else if ($TempCarrier == 2)
				$to_career = 'DoCoMo';
			else if ($TempCarrier == 3)
				$to_career = 'SoftBank';
			else if ($TempCarrier == 4)
				$to_career = 'au';

			if ($DecomeFlg)
				$content_transfer_encoding = 'base64';
			else
				$content_transfer_encoding = NULL;
			$mail_code = NULL;

			$body_plain = str_replace("\r\n", "\n", $body_plain);

			if(!$GLOBALS['emoji_obj']->emoji_decome2($TODATA,$CCDATA,$BCCDATA,$from_name,$from_add,$subject,$body_plain,$body_html,$repry_name='',$repry_to='',$return_path,$to_career,$content_transfer_encoding,$mail_code='JIS',$upfile,$encode_pass='1', 'EUC-JP', $DecomeFlg, '')) {
				$this->Err = "メールの送信に失敗しました。システム管理者にご連絡ください。";
				$this->ErrCode = $GLOBALS['decome_obj']->error_code;
				return FALSE;
			}
		}

		return TRUE;
	}

	function checkMail() {
		$HtmlNo = ($this->HtmlFlg) ? 1 : 0;
		$DecomeFlg = ($this->HtmlFlg) ? 1 : 0;

		$TODATA[$this->To] = $this->To;
		$CCDATA = NULL;
		$BCCDATA = NULL;
		$from_name = $this->FromName;
		$from_add = $this->From;
		$subject = $this->Subject;
		$body_plain = $this->Message;
		if ($this->HtmlFlg)
			$body_html = $this->Message;
		$repry_name = NULL;
		$repry_to = $this->ReplyTo;
		$return_path = $this->ReturnPath;

		$TempCarrier = SPFWMobile::getCarrierCodeByEMail($this->To);
		if ($TempCarrier == 1)
			$to_career = '';
		else if ($TempCarrier == 2)
			$to_career = 'DoCoMo';
		else if ($TempCarrier == 3)
			$to_career = 'SoftBank';
		else if ($TempCarrier == 4)
			$to_career = 'au';

		$content_transfer_encoding = NULL;
		$mail_code = NULL;

		$body_plain = str_replace("\r\n", "\n", $body_plain);

		$GLOBALS['TestMode'] = TRUE;

		if(!$GLOBALS['emoji_obj']->emoji_decome2($TODATA,$CCDATA,$BCCDATA,$from_name,$from_add,$subject,$body_plain,$body_html,$repry_name='',$repry_to='',$return_path,$to_career,$content_transfer_encoding='',$mail_code='JIS',$UPFILE='',$encode_pass='1', '', $DecomeFlg, '')) {
			$this->Err = "メールの送信に失敗しました。システム管理者にご連絡ください。";
			$this->ErrCode = $GLOBALS['decome_obj']->error_code;
			return FALSE;
		}

		$GLOBALS['TestMode'] = FALSE;

		return TRUE;
	}

	function initialize() {
		$this->To = NULL;
		$this->Cc = NULL;
		$this->Bcc = NULL;
		$this->Subject = NULL;
		$this->Message = NULL;
		$this->From = NULL;
		$this->FromName = NULL;
		$this->ReplyTo = NULL;
		$this->ReturnPath = NULL;
		$this->HtmlFlg = NULL;
	}
}
?>
