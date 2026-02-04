<html>

<head>
	<link rel="stylesheet" type="text/css" href="css/common.css">
	<link rel="stylesheet" type="text/css" href="css/style_okyakusama.css">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
	<!--最小限のビューポート設定-->
	<meta name="viewport" content="width=device-width">

	<title>
	日程予約
	</title>
</head>

<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
	<table>

		<div id="navi">
			<!--<a class="gengo" href="#.php">Englishi</a>-->
			<a class="bar" href="top.php__QUERY__&editBukkenCD=__editBukkenCD__&editBuildingCD=__editBuildingCD__">予約TOP</a>
			<a class="bar-migi" href="logout.php__QUERY__">ログアウト</a>
		</div>

		__SHeader__

		<h1>__MansionName__ __wBuildingName__</h1>
		<h2>__wID__号室様</h2>

		<h3>__PageTitle__</h3>

		<div id="">
			<img src="./images/step2.png" alt="step">
		</div>

		<p>
			__IfShowConfirmButton__
			以下の日程でよろしければ、「確定する」をクリックしてください。<br>
			__IfShowConfirmButton__
			変更の場合は「変更する」をクリックしてください。
		</p>

		<!--▽空日程で表示のするやつ？使わないかも
__IfNoReservation__現在お客様の予約はございません。__IfNoReservation__
-->



		<h4 class="login">工事日程</h4>
		<div class="block">
			<p class="menu2">

				__IfReservation__
				__DispReservationDate__ (__w2__) __Reservationtime__<br>
				__IfReservation__


				__IfOp__
				オプション：○○○<!--OPあるときのみ表示-->
				__IfOp__
			</p>
		</div>
		<!--ReservationLoop消してます-->

		<div class="op-moushikomi">
			__IfShowConfirmButton__
			<!--▽確定ボタン-->
			<form method="POST" action="kakutei.php__ReserveQuery__&wLang=__wLang__">
				<input type="hidden" name="wID" value=__wID__>
				<input type="hidden" name="flag" value="1">
				<input type="hidden" name="MansionName" value=__MansionName__>
				<input type="hidden" name="ReservationDateNen" value=__ReservationDateNen__>
				<input type="hidden" name="ReservationDateGetu" value=__ReservationDateGetu__>
				<input type="hidden" name="ReservationDateHi" value=__ReservationDateHi__>
				<input type="hidden" name="Reservationtime" value=__Reservationtime__>
				<input type="hidden" name="editBukkenCD" value=__editBukkenCD__>
				<input type="hidden" name="editBuildingCD" value=__editBuildingCD__>
				<!--<input type="hidden" name="ticket" value=__ticket__>-->
				<input type="submit" name="submit_button" value="確定する" class="finish-btn" style="margin-bottom:10px">
			</form>
			<!--△確定ボタン-->
			__IfShowConfirmButton__

			<!--▽変更ボタン-->
			<form method="POST" action="reserve_form_kakutei.php__ReserveQuery__&wLang=__wLang__">
				<input type="hidden" name="ticket" value=__ticket__>
				<input type="hidden" name="wID" value=__wID__>				
				<input type="hidden" name="editBukkenCD" value=__editBukkenCD__>
				<input type="hidden" name="editBuildingCD" value=__editBuildingCD__>
				<input type="submit" name="submit_button" value="変更する" class="finish-btn">
			</form>
			<!--△変更ボタン-->

			__IfShowDeclineButton__
			<!--▽辞退ボタン-->
			<form method="POST" action="kakutei.php__ReserveQuery__&wLang=__wLang__">
				<input type="hidden" name="wID" value=__wID__>
				<input type="hidden" name="flag" value="3">
				<input type="hidden" name="MansionName" value=__MansionName__>
				<input type="hidden" name="ReservationDateNen" value=__ReservationDateNen__>
				<input type="hidden" name="ReservationDateGetu" value=__ReservationDateGetu__>
				<input type="hidden" name="ReservationDateHi" value=__ReservationDateHi__>
				<input type="hidden" name="Reservationtime" value=__Reservationtime__>
				<input type="hidden" name="editBukkenCD" value=__editBukkenCD__>
				<input type="hidden" name="editBuildingCD" value=__editBuildingCD__>
				<!--<input type="hidden" name="ticket" value=__ticket__>-->
				<input type="submit" name="decline_button" value="辞退する" class="finish-btn black-btn" style="margin-bottom:10px">
			</form>
			<!--△辞退ボタン-->
			__IfShowDeclineButton__

			<form method="POST" action="">
			<input type="button" value=" 戻る " onClick="location.href='top.php__QUERY__&editBukkenCD=__editBukkenCD__&editBuildingCD=__editBuildingCD__'" class="tophe-btn">
			</form>

		</div>

		<!--ReservationLoop消してます-->


		__SFooter__

		__SCopyright__
	</table>
</body>

</html>