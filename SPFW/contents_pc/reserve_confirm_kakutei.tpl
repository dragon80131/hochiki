<html>

<head>
	<link rel="stylesheet" type="text/css" href="css/common.css">
	<link rel="stylesheet" type="text/css" href="css/style_okyakusama.css">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
	<!--最小限のビューポート設定-->
	<meta name="viewport" content="width=device-width">
	<title>ご予約内容の確認</title>
</head>

<body>
	<table>

		<div id="navi">
			<!--<a class="gengo" href="#.php">Englishi</a>-->
			<a class="bar" href="top.php__QUERY__">予約TOP</a>
			<a class="bar-migi" href="logout.php__QUERY__">ログアウト</a>
		</div>

		__SHeader__

		<h1>__MansionName__ __wBuildingName__</h1>
		<h2>__wID__号室様</h2>

		__IfConfirm__
		<h3>ご変更内容の確認</h3>
		__IfConfirm__
		__IfNotConfirm__
		<h3>ご予約内容の確認</h3>
		__IfNotConfirm__

		__IfNotConfirm__
		<div id="">
			<img src="./images/step3.png" alt="step">
		</div>
		__IfNotConfirm__

		__IfConfirm__
		<p>
			下記内容でよろしければ「予約を変更する」ボタンを押してください。<br>
			修正する場合は「修正する」ボタンを押してください。
		</p>
		__IfConfirm__
		__IfNotConfirm__
		<p>
			下記内容でよろしければ「予約を確定する」ボタンを押してください。<br>
			修正する場合は「修正する」ボタンを押してください。
		</p>
		__IfNotConfirm__


<!--▽いるやつ？
__IfDateError__<font color=" red">ご希望の日付が正しくありません。</font><br>__IfDateError__
			__IfHolidayError__<font color="red">ご希望の日付はお休みをいただいております。</font><br>__IfHolidayError__
			__IfFullt__<font color="red">ご希望の時間は予約が埋まっております。他の時間をお選びください。</font><br>__IfFullt__
			__IfError__<br>__IfError__
			△いるやつ？-->


			<h4>ご予約内容</h4>
			<div class="block-formsaigo">
				<table class="n-kakunin">
					<tr>
						<th scope="row">■予約日</th>
						<td>__wYear__年__wMonth__月__wDay__日(__weekday__)</td>
					</tr>
					<tr>
						<th scope="row">■時間帯</th>
						<td>__wOKTimeName__</td>
					</tr>
					<!--<tr>
						<th scope="row">■ご要望</th>
						<td>__wwUserMemo__</td>
					</tr>-->
				</table>
			</div>

			<div class="op-moushikomi">
				<form method="post" action="reserve_finish_kakutei.php?editBukkenCD=__editBukkenCD__&editBuildingCD=__editBuildingCD__&wLang=__wLang__&rKey=__rKey__"">
				<input type="hidden" name="ticket" value="__ticket__">
				<input type="hidden" name="wUserMemo" value="__wUserMemo__">
				<input type="hidden" name="wSecondChoice" value="__wSecondChoice__">
				<input type="hidden" name="MyMenuCD" value="__MyMenuCD__">
				<input type="hidden" name="wTime" value="__wTime__">
				<input type="hidden" name="wDate" value="__wDate__">
				<input type="hidden" name="wID" value="__wID__">
				__IfConfirm__
				<input type="hidden" name="flag" value="2">
				<input type="submit" name="submit_button" value="予約を変更する" class="finish-btn">
				__IfConfirm__
				__IfNotConfirm__
				<input type="hidden" name="flag" value="1">
				<input type="submit" name="submit_button" value="予約を確定する" class="finish-btn">
				__IfNotConfirm__
				__HiddenValues__
				</form>

				<form method="POST" action="reserve_form_kakutei.php?wLang=__wLang__&rKey=__rKey__">
					<input type="hidden" name="wUserMemo" value="__wUserMemo__">
					<input type="hidden" name="wSecondChoice" value="__wSecondChoice__">
					<input type="hidden" name="MyMenuCD" value="__MyMenuCD__">
					<input type="hidden" name="wTime" value="__wTime__">
					<input type="hidden" name="wDate" value="__wDate__">
					<input type="hidden" name="Correction" value="1">
					<input type="hidden" name="wID" value="__wID__">
					<input type="hidden" name="Syusei" value="1">
					<input type="submit" value="修正する" class="tophe-btn">
					__HiddenValues__

				</form>

			</div>


		__SFooter__

		__SCopyright__
	</table>
</body>

</html>