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
	<a class="bar" href="top.php__QUERY__">__finish3__<!--予約TOP--></a>
	<a class="bar-migi" href="logout.php__QUERY__">__logout1__<!--ログアウト--></a>
</div>
__SHeader__

<h1>__MansionName__ __wBuildingName__</h1>
<h2>__top1__ __wID__</h2>

__IfNOCustomerEdit__

<h3>__finish1__<!--登録完了--></h3>

__IfNittei__

<div id="step4">
<img src="./images/__steppng__" alt="step">
</div>

<p>
__finish5__<!--お客様情報の登録が完了いたしました。<br>

「次へ」ボタンを押し、日程予約へお進みください。
<br>※再度お客様情報を変更する場合は、予約システムTOPよりご変更ください。--><br>
</p>

__IfNoReservation__
<div class="op-moushikomi">
<a href="reserve_form_kakutei.php?rKey=__rKey__&wLang=__wLang__&editBukkenCD=__editBukkenCD__&editBuildingCD=__editBuildingCD__" class="finish-btn">__finish9__</a><!--次へ-->
</div>
__IfNoReservation__


__IfReservation__
<div class="op-moushikomi">
<a href="reserve_list.php?rKey=__rKey__&wLang=__wLang__&editBukkenCD=__editBukkenCD__&editBuildingCD=__editBuildingCD__" class="finish-btn">__finish9__</a><!--次へ-->
</div>
<!--△日程予約を選んだ場合の表示-->
__IfReservation__



__SFooter__

__SCopyright__

</table>
</body>
</head>
</html>
