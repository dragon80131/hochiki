<html>
<head>
	<link rel="stylesheet" type="text/css" href="css/common.css">
	<link rel="stylesheet" type="text/css" href="css/style_okyakusama.css">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
	<!--最小限のビューポート設定-->
	<meta name="viewport" content="width=device-width">

	<title>ReservatinSystem</title>
</head>

<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
	<table>

		<div id="navi">
			<!--<a class="gengo" href="#.php">Englishi</a>-->
<a class="bar" href="top.php__QUERY__&editBukkenCD=__editBukkenCD__&editBuildingCD=__editBuildingCD__">__finish3__<!--予約TOP--></a>
<a class="bar-migi" href="logout.php__QUERY__">__logout1__<!--ログアウト--></a>
		</div>


		__SHeader__

		<h1>__MansionName__ __wBuildingName__</h1>
		<h2>__top1__<!--号室--> __wID__</h2>

		<!--▽日程確定したの場合の表示-->
		__IfModify__
		<h3>__kakutei1__</h3>

		<div id="step4">
			<img src="./images/__steppng__" alt="step">
		</div>
		<br>

		<h4 class="login">__kakutei2__<!--工事日程--></h4>
		<div class="block">
			<p class="naka">__kakutei3__<!--以下の内容で、ご予約を確定しました。--></p>
			<p class="menu">


		__DispReservationDate__
		__w2__  __Reservationtime__<br>


		</p>
		</div>
		__IfOp__
		<!--OPあるときのみ表示-->
		__IfDAYOPOPEN__
		<p><font color=red><b>
			※オプションを申込み希望のお客様は<br>
			<a href="reserve_formOP.php__QUERY__&wLang=__wLang__&btnflg=2">「オプション申込み」</a>へお進みください。
		</b></font></p>
		__IfDAYOPOPEN__
		__IfOp__


		<div class="op-moushikomi">
			<a href="top.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&editBuildingCD=__editBuildingCD__" class="tophe-btn">__finish2__<!--予約システムTOPへ--></a>
		</div>
		<!-- <div class="out">
			<a href="logout.php?rKey=__rKey__" class="tophe-btn">__logout1__</a>
		</div> -->
		__IfModify__
		<!--△日程確定したの場合の表示-->


		<!--▽お客様情報登録まだの場合の表示-->
		__IfNew__
		<h3>登録が完了していません。以下の手順でお進みください。</h3>
		<p>
			①「予約システムTOP」よりメニューの選択。<br>
			②お客様情報の登録。<br>
			③その後、案内に沿って予約を行ってください。
		</p>
		<div class="tophe">
			<a href="top.php?rKey=__rKey__" class="tophe-btn">予約システムTOPへ</a>
		</div>
		__IfNew__
		<!--△お客様情報登録まだの場合の表示-->


		<!--▽これなに-->
		<!--__IfNewForm__<a href="form.php">■新規登録する</a><br>__IfNewForm__
		__IfLogin__<a href="login_form.php__QUERY__">■ログイン</a><br>__IfLogin__
		__IfEasyLogin__<a href="easylogin_form.php__QUERY__">■簡単ログイン</a><br>__IfEasyLogin__
		__IfReminder__<a href="reminder_form.php__QUERY__">■パスワードを忘れた方</a><br>__IfReminder__
		__IfEasyLoginRegist__<a href="easylogin_regist_form.php__QUERY__">■簡単ログイン登録</a><br>__IfEasyLoginRegist__
		<!--△これなに-->


		__SFooter__

		__SCopyright__


	</table>
</body>
</head>
</html>
