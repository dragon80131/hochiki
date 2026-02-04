<html>

<head>
	<link rel="stylesheet" type="text/css" href="css/common.css">
	<link rel="stylesheet" type="text/css" href="css/style_okyakusama.css">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
	<!--最小限のビューポート設定-->
	<meta name="viewport" content="width=device-width">

	<title>ご予約の完了</title>
</head>

<body>
	<table>
		<div id="navi">
			<!--<a class="gengo" href="#.php">Englishi</a>-->
			<a class="bar" href="top.php__QUERY__&editBukkenCD=__editBukkenCD__&editBuildingCD=__editBuildingCD__">予約TOP</a>
			<a class="bar-migi" href="logout.php__QUERY__">ログアウト</a>
		</div>

		__SHeader__

		<h1>__MansionName__ __wBuildingName__</h1>
		<h2>__wID__号室様</h2>
		__IfDecline__
		<h3>予約辞退の完了</h3>
		__IfDecline__
		__IfConfirm__
		<h3>予約変更の完了</h3>
		__IfConfirm__
		__IfNotConfirm__
		<h3>日程予約の完了</h3>
		__IfNotConfirm__

		__IfNotConfirm__
		<div id="">
			<img src="./images/step4.png" alt="step">
		</div>
		<p>日程のご予約を確定致しました。<br>
			尚、ご予約のタイミングによっては、配布される<br>
			書面の日程がご予約内容と異なる場合がございます。</p>
		__IfNotConfirm__

		__IfConfirm__
		<p>日程のご予約を確定致しました。<br>
			尚、ご予約のタイミングによっては、配布される<br>
			書面の日程がご予約内容と異なる場合がございます。</p>
		__IfConfirm__

		__IfDecline__
		<p>辞退で受付いたしました。
		<br>次回のご協力よろしくお願いします。</p>
		__IfDecline__

		<div class="op-moushikomi">
			<a href="top.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&editBuildingCD=__editBuildingCD__" class="tophe-btn">予約システムTOPへ</a>
			<a href="logout.php?rKey=__rKey__" class="tophe-btn">ログアウト</a>
		</div>

		__SFooter__
		__SCopyright__
	</table>
</body>

</html>