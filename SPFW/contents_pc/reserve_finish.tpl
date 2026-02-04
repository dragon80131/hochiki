<html>
<head>
	<link rel="stylesheet" type="text/css" href="css/common.css">
	<link rel="stylesheet" type="text/css" href="css/style_okyakusama.css">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
	<!--最小限のビューポート設定-->
	<meta name="viewport" content="width=device-width">

	<title>ReservatinSystem</title>
</head>

<body>
	<table>
		<div id="navi">
			<!--<a class="gengo" href="#.php">Englishi</a>-->
			<a class="bar" href="top.php__QUERY__">__finish3__<!--予約TOP--></a>
			<a class="bar-migi" href="logout.php__QUERY__">__logout1__<!--ログアウト--></a>
		</div>

		__SHeader__

		<h1>__MansionName__</h1>
		<h2>__reserve_finish1__ __wID__</h2>
		<h3>__reserve_finish2__ <!--日程予約の完了--></h3><br>


		<div id="step4">
			<img src="./images/__steppng__" alt="step">
		</div>

		<p>__reserve_finish3__<!--日程のご予約を受け付けました。--></p>
		<p>
			__reserve_finish4__<!--日程調整後、<font color="red">__KetteiHaifuDate__（__w3__）頃</font>に決定通知を配布致しますので、<br>
				改めてご確認をお願いします。<br>
				多数の方の希望日程が重なった場合は、ご相談させていただく場合がございます。ご理解のほどよろしくお願いします。--><br>
			</p>

		__IfOp__
		<!--OPあるときのみ表示-->
		__IfDAYOPOPEN__
		<p><font color=red><b>
			__reserve_finish5__<!--※オプションを申込み希望のお客様は--><br>
			<a href="reserve_formOP.php__QUERY__&wLang=__wLang__&btnflg=2"><!--「オプション申込み」</a>へお進みください。-->
		</b></font></p>
		__IfDAYOPOPEN__
		__IfOp__

		<div class="yoyakutop">
			<a href="top.php?rKey=__rKey__" class="tophe-btn">__finish2__</a><!--予約システムTOPへ-->
		</div>
		<div class="out">
			<a href="logout.php?rKey=__rKey__" class="tophe-btn">__logout1__</a><!--ログアウト-->
		</div>

		__SFooter__
		__SCopyright__
	</table>
</body>
</html>
