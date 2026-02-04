<?php
define('VERSION', '2.0.1');
define('LOGIN', 'souppot');
define('API_KEY', 'R_7e35fe77921b264fcfda568c65727a2f');
define('FORMAT', 'json');

class ShortURL {

	function shortenURL($url) {
		if (empty($url)) {
			return null;
		}

		$input_url = strip_tags(trim($url));

		// 正しいURLであることを確認する
		if (phpversion() >= "5.2") {
			if(!filter_var($input_url, FILTER_VALIDATE_URL)){
				return null;
			}
		} else {
		}

		$url_enc = urlencode($url);
		$data = file_get_contents('http://api.bit.ly/shorten?version=' . VERSION . '&longUrl=' . $url_enc . '&login=' . LOGIN . '&apiKey=' . API_KEY . '&format=' . FORMAT);
		$myJson = new Services_JSON();
		$json = $myJson->decode($data);
		//$json = json_decode($data, true);
#print_r($json->results->$url->shortUrl);

		return $json["results"][$url]["shortUrl"];
	}

	function isShortenURL($url) {
		return (substr($url, 0, 14)=="http://bit.ly/");
	}
}
?>
