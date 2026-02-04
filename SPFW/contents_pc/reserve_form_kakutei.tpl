<html>

<head>
	<title>日程予約</title>
	<link rel="stylesheet" type="text/css" href="css/common.css">
	<link rel="stylesheet" type="text/css" href="css/style_okyakusama.css">
	<!-- jQuery読み込み -->
	<script src="./js/jquery-3.4.1.min.js"></script>

	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
	<!--最小限のビューポート設定-->
	<meta name="viewport" content="width=device-width">

	<script>
		$(function () {
			$('.finish-btn').on('click', function () {
				let wTime = $('select[name="wTime"]').val();
				if (wTime == null) {
					alert('日程を選択してください。');
					return false;
				} else {
					$('form[name="mainform2"]').submit();
				}
			});
		});
	</script>

</head>

<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
	<table>

		<div id="navi">
			<!--<a class="gengo" href="#.php">English</a>-->
			<a class="bar" href="top.php__QUERY__">予約TOP</a>
			<a class="bar-migi" href="logout.php__QUERY__">ログアウト</a>
		</div>

		__SHeader__

		<h1>__MansionName__ __wBuildingName__</h1>
		<h2>__wID__号室様</h2>

		__IfReservation__
		__IfConfirm__
		<h3>現在の予約</h3>
		<p class="menu">
			__DispReservationDate__(__w1__)&nbsp;__Reservationtime__<br>
		</p>
		__IfConfirm__
		__IfReservation__


		__IfConfirm__
		<h3>日程変更</h3>
		__IfConfirm__
		__IfNotConfirm__
		<h3>日程選択</h3>
		__IfNotConfirm__

		__IfNotConfirm__
		<div style="text-align-center;margin:30px 0;">
			<img src="./images/step2.png" alt="step">
		</div>
		__IfNotConfirm__


		__IfErr__<font color="red">日程を選択してください</font><br>__IfErr__
		<p class="hissu">
			<font color="red">&nbsp;&nbsp;*</font>は入力必須項目です。
		</p>

		<!--▽カレンダー-->
		<h4>１.日付選択<font color="red"> *</font>
		</h4>
		<div class="block-form">


			<p class="naka">カレンダーよりご希望日を選択してください。<br>
				○印または、△印の日付のみ選択可能です。</p>
			<p class="jikan2">専有部工事期間：__DispSenyuStartDate__(__w4__)～
				__DispSenyuEndDate__(__w5__)</p>
			<p class="jikan3">現在選択中の日付：__IfNokara____wYear__年__wMonth__月__wDay__日(__weekday__)__IfNokara__</p>

			<!--▼カレンダーここから-->
			<table class="calendar">
				<tr>
					<td colspan="2" bgcolor="#EFF7FF" style="border-style: none; text-align: left;">
						<form action="reserve_form_kakutei.php__QUERY__&wLang=__wLang__" name="mainform" method="post">
							<input type="hidden" name="vCal" value="t">
							__IfYearback__
							<input type="hidden" name="wThisYear" value="__Yearminu__">
							<input type="hidden" name="wGoYear" value="__Yearminu__">
							<input type="hidden" name="wGoMonth" value="12">
							<input type="hidden" name="wThisMonth" value="12">
							__IfYearback__
							__IfYearover__
							<input type="hidden" name="wThisYear" value="__ThisYear__">
							<input type="hidden" name="wGoYear" value="__ThisYear__">
							<input type="hidden" name="wGoMonth" value="__Monthminu__">
							<input type="hidden" name="wThisMonth" value="__Monthminu__">
							__IfYearover__
							__IfNoover__
							<input type="hidden" name="wThisYear" value="__ThisYear__">
							<input type="hidden" name="wGoYear" value="__ThisYear__">
							<input type="hidden" name="wGoMonth" value="__Monthminu__">
							<input type="hidden" name="wThisMonth" value="__Monthminu__">
							__IfNoover__
							<button type="submit" style="border:0px;background-color:transparent;cursor:pointer;">
								<a href="javascript:void(0)">
									<font size=3>＜＜ 前の月</font>
								</a>

							</button>
							__HiddenValues__
						</form>
					</td>
					<td colspan="3" bgcolor="#EFF7FF" style="border-style: none; font-size: 18px;">
						__ThisYear__年__ThisMonth__月
					</td>
					<td colspan="2" bgcolor="#EFF7FF" style="border-style: none;  text-align: right;">
						<form action="reserve_form_kakutei.php__QUERY__&wLang=__wLang__" name="mainform" method="post">
							<input type="hidden" name="vCal" value="t">
							__IfYearback__
							<input type="hidden" name="wThisYear" value="__ThisYear__">
							<input type="hidden" name="wGoYear" value="__ThisYear__">
							<input type="hidden" name="wThisMonth" value="__Monthplus__">
							<input type="hidden" name="wGoMonth" value="__Monthplus__">
							__IfYearback__
							__IfYearover__
							<input type="hidden" name="wThisYear" value="__Yearplus__">
							<input type="hidden" name="wGoYear" value="__Yearplus__">
							<input type="hidden" name="wGoMonth" value="1">
							<input type="hidden" name="wThisMonth" value="1">
							__IfYearover__
							__IfNoover__
							<input type="hidden" name="wThisYear" value="__ThisYear__">
							<input type="hidden" name="wGoYear" value="__ThisYear__">
							<input type="hidden" name="wThisMonth" value="__Monthplus__">
							<input type="hidden" name="wGoMonth" value="__Monthplus__">
							__IfNoover__
							<button type="submit" style="border:0px;background-color:transparent;cursor:pointer;">
								<a href="javascript:void(0)">
									<font size=3>次の月 ＞＞</font>
								</a>
							</button>
							__HiddenValues__
						</form>
					</td>
				</tr>
					<form method="POST" name="mainform2" action="reserve_confirm_kakutei.php__QUERY__&editBukkenCD=__editBukkenCD__&editBuildingCD=__editBuildingCD__&wLang=__wLang__">

						<!--▽曜日-->
						<tr>
							<th bgcolor="#ffc0cb">日</th>
							<th bgcolor="#ffffff">月</th>
							<th bgcolor="#ffffff">火</th>
							<th bgcolor="#ffffff">水</th>
							<th bgcolor="#ffffff">木</th>
							<th bgcolor="#ffffff">金</th>
							<th bgcolor="#bde0ff">土</th>
						</tr>
						<!--△曜日ー-->

						<!--▽日付-->
						__WeekLoop__
						<tr>
							__WeekdayLoop__
							<td bgcolor="__DayColor__">
								__IfOK1__<a href="reserve_form_kakutei.php__QUERY__&editBukkenCD=__editBukkenCD__&editBuildingCD=__editBuildingCD__&wDate=__FullDate__&wLang=__wLang__&Select=1">__IfOK1__
									__ThisDay__
									__IfOK2__</a>__IfOK2__<br>
								__IfOK1__<a href="reserve_form_kakutei.php__QUERY__&editBukkenCD=__editBukkenCD__&editBuildingCD=__editBuildingCD__&wDate=__FullDate__&wLang=__wLang__&Select=1">__IfOK1__
									__Jokyo__
									__IfOK2__</a>__IfOK2__
							</td>
							__WeekdayLoop__
							__WeekdayBlock__
						</tr>
						__WeekLoop__
						<!--△日付-->

			</table>

			<div class="mikata">
				<p class="hissu2">○…空きあり&emsp;△…残りわずか&emsp;×…空きなし&emsp;休…休工日</p>
			</div>

			<!--▲カレンダー上ここまで-->
		</div>



		<!--▽時間帯選択-->
		<h4>２.時間帯選択<font color="red"> *</font>
		</h4>
		<div class="block-form">
			<p class="naka">ご希望の時間帯を選択してください。<br>空きのある時間帯のみ選択可能です。</p>
			<p class="jikan">現在選択中の日付：__IfNokara____wYear__年__wMonth__月__wDay__日(__weekday__)__IfNokara__</p>
			<!--カレンダーで選択した日付表示-->

			<select name="wTime">
				__OKTimesLoop__
				<option value="__OKTimes__" __OKTimeSelected__>__wOKTimeName__</option>
				__OKTimesLoop__
			</select>
		</div>

		__IfExtendedYoyakuEndDate__
		<!--▽ご要望-->
		<h4>３.ご要望</h4>
		<div class="block-formsaigo">
			<p class="naka">工事についてご要望等ありましたら、ご記入ください。</p>
			<p class="komoji">(ご記入内容によっては、電話またはメールにてご連絡させていただく場合がございます。)</p>
			<textarea name="wUserMemo" cols="50" rows="3" class="box">__wUserMemo__</textarea>
		</div>
		<!--△ご要望-->
		__IfExtendedYoyakuEndDate__

		<input type=hidden name="wID" value="__wID__">
		<input type=hidden name="editReservationCD" value="__editReservationCD__">
		<input type=hidden name="MansionName" value="__MansionName__">
		<input type=hidden name="wDate" value="__wDate__">

		<input type="hidden" name="MyMenuCD" value="__MyMenuCD__">



		__HiddenValues__
		<div class="op-moushikomi">
			<input type="button" value=" 次へ " class="finish-btn">
			__IfConfirm__
			<input type="button" value=" 戻る " onClick="location.href='reserve_list.php__QUERY__&editBukkenCD=__editBukkenCD__&editBuildingCD=__editBuildingCD__'" class="tophe-btn">
			__IfConfirm__
			__IfNotConfirm__
			<input type="button" value=" 戻る " onClick="location.href='top.php__QUERY__'" class="tophe-btn">
			__IfNotConfirm__
		</div>
		</form>

		__SFooter__

		__SCopyright__

	</table>
</body>

</html>