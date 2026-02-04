<?php
include_once 'SPFWShortURL.cls';

define('USER_NAME', 'souppot');
define('PASSWORD', 'wisdom1105');

/* Consumer key from twitter */
define('CONSUMER_KEY', NULL);
/* Consumer Secret from twitter */
define('CONSUMER_SECRET', NULL);
//Access Tokenの値
define('ACCESS_TOKEN', NULL);
//Access Token Secretの値
define('ACCESS_TOKEN_SECRET', NULL);

/**
 * Twitter URL
 */
define('TWITTER_DEFAULT_FORMAT', 'xml');
define('TWITTER_ALLOWED_FORMAT', 'json,xml,atom');
define('TWITTER_MAX_POST_LENGTH', 140);
define('TWITTER_URL_UPDATE', 'http://twitter.com/statuses/update.%%format%%');
define('TWITTER_URL_HOME_TIMELINE', 'http://api.twitter.com/1/statuses/home_timeline.%%format%%');
define('TWITTER_URL_MENTIONS', 'http://twitter.com/statuses/mentions.%%format%%');

define('TWITTER_URL_LIMIT_STATUS', 'http://twitter.com/account/rate_limit_status.%%format%%');

class Twitter {
	/**
	 * ID
	 */
	var $TwitterID;
	/**
	 * パスワード
	 */
	var $TwitterPW;

	function setTwitterID($TwitterID) {
		$this->TwitterID = $TwitterID;
	}

	function setTwitterPW($TwitterPW) {
		$this->TwitterPW = $TwitterPW;
	}

	function getLimitStatusURL($format=null) {
		if (!in_array($format, explode(",", TWITTER_ALLOWED_FORMAT))) $format = TWITTER_DEFAULT_FORMAT;
		return str_replace('%%format%%', $format, TWITTER_URL_LIMIT_STATUS);
	}

	function getUpdateURL($format=null) {
		if (!in_array($format, explode(",", TWITTER_ALLOWED_FORMAT))) $format = TWITTER_DEFAULT_FORMAT;
		return str_replace('%%format%%', $format, TWITTER_URL_UPDATE);
	}

	function getHomeTimelineURL($format=null, $since_id=0, $count=200,$page=1) {
		if (!in_array($format, explode(",", TWITTER_ALLOWED_FORMAT))) $format = TWITTER_DEFAULT_FORMAT;

		$query = "count=$count&page=$page";

		if ($since_id > 0) {
			$query .= "&since_id=$since_id";
		}

		return str_replace('%%format%%', $format, TWITTER_URL_HOME_TIMELINE) . "?" . $query;
	}

	function getMentionsURL($format=null, $since_id=0, $count=200,$page=1) {
		if (!in_array($format, explode(",", TWITTER_ALLOWED_FORMAT))) $format = TWITTER_DEFAULT_FORMAT;

		$query = "count=$count&page=$page";

		if ($since_id > 0) {
			$query .= "&since_id=$since_id";
		}

		return str_replace('%%format%%', $format, TWITTER_URL_MENTIONS) . "?" . $query;
	}

	function requestBasic($url, $param=null) {
		//$Command = "wget -S -O - --http-user " . $this->TwitterID . " --http-password " . $this->TwitterPW . " '" . $url . "' 2>&1";
		$Command = "wget -S -O - --http-user " . $this->TwitterID . " --http-password " . $this->TwitterPW . " --post-data '" . $param . "' '" . $url . "' 2>&1";

		$Result = shell_exec($Command);

		$Regs = explode("\n", $Result);
		for ($i = 0; $i < count($Regs); $i++) {
			if (strpos($Regs[$i], 'Status: ') == 2) {
				$Regs2 = explode(" ", $Regs[$i]);
				$Status = $Regs2[3];
			}
		}

		if ($Status == '200')
			return TRUE;
		else
			return FALSE;
	}

	function postMessage($message, $inreply=null, $username=null, $password=null) {
		if ($inreply!==null && is_numeric($inreply)) {
			$content["in_reply_to_status_id"] = $inreply;
			$query[] = "in_reply_to_status_id=$inreply";
		}

		$message = mb_convert_encoding($message, "UTF-8", mb_internal_encoding());

		$content["status"] = $message;
		$query[] = "status=" . urlencode($message);

		return Twitter::requestBasic(Twitter::getUpdateURL()."?".implode("&", $query), implode("&", $query), 'POST');
	}

	function getHomeTimeline($username=null, $password=null, $since_id=0, $count=200,$page=1) {
		return Twitter::requestBasic(Twitter::getHomeTimelineURL('xml',$since_id, $count,$page), null, 'POST');
	}

	function getMentions($username=null, $password=null, $since_id=0, $count=200,$page=1) {
		return Twitter::requestBasic(Twitter::getMentionsURL('xml',$since_id, $count,$page), null, 'GET');
	}

	function getLimitStatus($username=null, $password=null) {
		$res = Twitter::requestBasic(Twitter::getLimitStatusURL('json'), null, 'GET');
		$stat = json_decode($res);
		return $stat->remaining_hits;
	}

	function getStatusArray($xmlclass) {
		$ret = array();
		foreach ($xmlclass->status as $status) {
			$vars = get_object_vars($status);
			$tmp = array();
			foreach ($vars as $key=>$var) {
				if (is_object($var)) {
					$invars = get_object_vars($var);
					$tmp2 = array();
					foreach ($invars as $key2=>$var2) {
						$tmp2[$key2] = $var2;
					}
					$tmp[$key] = $tmp2;
				}
				else {
					$tmp[$key] = $var;
				}
			}
			$ret[] = $tmp;
		}
		return $ret;
	}

	function trimMessage($message, $addinfo) {
		$ret_message = mb_substr($message, 0, TWITTER_MAX_POST_LENGTH - mb_strlen($addinfo, 'UTF-8'), 'UTF-8') . $addinfo;

		return $ret_message;

	}

	function createMessage($header, $message, $name, $url) {
		$url = ShortURL::shortenURL($url);

		$fixed_length = mb_strlen($header."「」 from ".$name." ".$url, 'UTF-8');
		$trim_message = mb_substr($message, 0, TWITTER_MAX_POST_LENGTH - $fixed_length, "UTF-8");
		$ret_message = $header . "「" . $trim_message . "」 from ".$name." ".$url;

		return $ret_message;

	}

	function shortenURL($message) {
		if (preg_match_all("|https?://[-_.!~*'()a-zA-Z0-9;/?:@&=+$,%#]+|", $message, $matched)) {
			for ($i=0; $i<count($matched[0]); $i++) {
				$url = $matched[0][$i];

				if (!ShortURL::isShortenURL($url)) {
					$short_url = ShortURL::shortenURL($url);
					$message = str_replace($url, $short_url, $message);
				}
			}
		}

		return $message;
	}
}
?>