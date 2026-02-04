<html>
<head>
<link rel="stylesheet" type="text/css" href="css/common.css">
<link rel="stylesheet" type="text/css" href="css/style_okyakusama.css">
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
<!--最小限のビューポート設定-->
<meta name="viewport" content="width=device-width">

<title>Temporary installation date</title>
</head>

<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
<table>

<div id="navi">
<!--<a class="gengo" href="#.php">Englishi</a>-->
<a class="bar" href="top.php__QUERY__">TOP</a>
<a class="bar-migi" href="logout.php__QUERY__">Logout</a>
</div>

__SHeader__

<h1>__MansionName__</h1>
<h2>Room No __wID__</h2>

<h3>Temporary installation date</h3>

<div id="step">
<img src="./images/step2.png" alt="step">
</div>

<p>
If you are satisfied with the following installation dates, please click "OK".<br>
Click "Change" to change.
</p>

<!--▽空日程で表示のするやつ？使わないかも
__IfNoReservation__現在お客様の予約はございません。__IfNoReservation__
-->



<h4 class="login">Installation date</h4>
<div class="block">
<p class="menu2">

__IfReservation__
__DispReservationDate__ (__w2__)  __Reservationtime__<br>
__IfReservation__


__IfOp__
Option：○○○<!--OPあるときのみ表示-->
__IfOp__
</p>
</div>
<!--ReservationLoop消してます-->



<!--▽確定ボタン-->
<div class="op-moushikomi">
<form method="POST" action="kakutei.php__ReserveQuery__&wLang=__wLang__">
<input type="hidden" name="wID" value=__wID__>
<input type="hidden" name="MansionName" value=__MansionName__>
<input type="hidden" name="ReservationDateNen" value=__ReservationDateNen__>
<input type="hidden" name="ReservationDateGetu" value=__ReservationDateGetu__>
<input type="hidden" name="ReservationDateHi" value=__ReservationDateHi__>
<input type="hidden" name="Reservationtime" value=__Reservationtime__>
<!--<input type="hidden" name="ticket" value=__ticket__>-->
<input type="submit" name="submit_button" value=" OK " class="finish-btn">
</form>
</div>
<!--△確定ボタン-->

<!--▽変更ボタン-->
<div class="op-moushikomi">
<form method="POST" action="reserve_form.php__ReserveQuery__&wLang=__wLang__">
<input type="hidden" name="ticket" value=__ticket__>
<input type="hidden" name="wID" value=__wID__>
<input type="submit" name="submit_button" value="Change" class="finish-btn">

</form>
</div>
<!--△変更ボタン-->

<!--ReservationLoop消してます-->


__SFooter__

__SCopyright__
</table>
</body>
</html>
