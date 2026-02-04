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
		<h2>__reserve_confirm1__ __wID__</h2>


		<h3>__reserve_confirm2__ <!--ご予約内容の確認--></h3><br>
		<center>
			<font color="red" size="6" style="background-color:yellow"><b>__reserve_confirm3__ <!--日程変更はまだ完了しておりません。--></b></font>
		</center>
		<br>
		<div id="step4">
			<img src="./images/__steppng__" alt="step">
		</div>

		<p>
				__reserve_confirm4__ <!--下記内容でよろしければ「予約を確定する」ボタンを押してください。--><br>
				__reserve_confirm5__ <!--修正する場合は「修正する」ボタンを押してください。-->
			</p>

		<form method="post" action="reserve_finish.php?wLang=__wLang__&rKey=__rKey__">

			<h4>__reserve_confirm6__ <!--ご予約内容--></h4>
			<div class="block-formsaigo">
				<table class="n-kakunin">
					<tr>
						<th scope="row">__reserve_confirm7__ <!--■第1希望--></th>
						<td>__date1Str____niChi1Str__ __wTime1__</td>
					</tr>
					<tr>
						<th scope="row">__reserve_confirm8__ <!--■第2希望--></th>
						<td>__date2Str____niChi2Str__ __wTime2__</td>
					</tr>
					<tr>
						<th scope="row">__reserve_confirm9__ <!--■第3希望--></th>
						<td>__date3Str____niChi3Str__ __wTime3__</td>
					</tr>
					<tr>
						<th scope="row">__reserve_confirm10__ <!--■ご 要 望--></th>
						<td>__wwUserMemo__</td>
					</tr>
				</table>
			</div>

			<div class="op-moushikomi">
				<input type="hidden" name="ticket" value="__ticket__">
				<input type="hidden" name="wUserMemo" value="__wUserMemo__">
				<input type="hidden" name="wSecondChoice" value="__wSecondChoice__">
				<input type="hidden" name="MyMenuCD" value="__MyMenuCD__">
				<input type="hidden" name="wTime" value="__wTime__">
				<input type="hidden" name="wDate" value="__wDate__">
				<input type="hidden" name="wID" value="__wID__">
				<input type="hidden" name="work" value="1">
				<input type="hidden" name="editReservationCD" value="__editReservationCD__">
				<input type="hidden" name="wTime1" value="__wTime1__">
				<input type="hidden" name="wTime2" value="__wTime2__">
				<input type="hidden" name="wTime3" value="__wTime3__">
				<input type="hidden" name="wTime1ok" value="__wTime1__">
				<input type="hidden" name="wTime2ok" value="__wTime2__">
				<input type="hidden" name="wTime3ok" value="__wTime3__">
				<input type="hidden" name="wDate1" value="__wDate1__">
				<input type="hidden" name="wDate2" value="__wDate2__">
				<input type="hidden" name="wDate3" value="__wDate3__">
				<input type="submit" name="submit_button" value="__reserve_confirm11__" class="finish-btn"><!--予約を確定する-->
			</div>
		</form>

		<div class="tophe">
			<form method="POST" action="reserve_form.php?wLang=__wLang__&rKey=__rKey__">
				<input type="hidden" name="wUserMemo" value="__wUserMemo__">
				<input type="hidden" name="wSecondChoice" value="__wSecondChoice__">
				<input type="hidden" name="wTime1" value="__wTime1__">
				<input type="hidden" name="wTime2" value="__wTime2__">
				<input type="hidden" name="wTime3" value="__wTime3__">
				<input type="hidden" name="wTime1ok" value="__wTime1__">
				<input type="hidden" name="wTime2ok" value="__wTime2__">
				<input type="hidden" name="wTime3ok" value="__wTime3__">
				<input type="hidden" name="wDate1" value="__wDate1__">
				<input type="hidden" name="wDate2" value="__wDate2__">
				<input type="hidden" name="wDate3" value="__wDate3__">
				<input type="hidden" name="editReservationCD" value="__editReservationCD__">
				<input type="hidden" name="MyMenuCD" value="__MyMenuCD__">
				<input type="hidden" name="Correction" value="1">
				<input type="hidden" name="wID" value="__wID__">
				<input type="hidden" name="Syusei" value="1">
				<input type="submit" value="__reserve_confirm12__" class="tophe-btn"><!--修正する-->
			</form>
		</div>

		__SFooter__

		__SCopyright__
	</table>
</body>
</html>
