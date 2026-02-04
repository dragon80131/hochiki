<html>

<head>
	<title>ReservatinSystem</title>

	<link href="./css/jquery-ui-1.8.2.custom.css" rel="stylesheet" type="text/css" />
	<script src="./js/jquery-1.4.2.js" type="text/javascript"></script>
	<script src="./js/jquery.ui.core.js" type="text/javascript"></script>
	<script src="./js/jquery.ui.datepicker.js" type="text/javascript"></script>
	__Ifjp__
	<script src="./js/jquery.ui.datepicker-ja.js" type="text/javascript"></script> __Ifjp__
	<script src="./js/jquery.numberPicker.js" type="text/javascript"></script>
	<script>
		// Hide script from old browser
		// プログラム移動
		function goPage(pgAct) {
			document.mainform.action = pgAct;
			document.mainform.submit(true)
		}
		function goPageWithValue(pgAct, val) {
			document.mainform.vDate.value = val;
			document.mainform.action = pgAct;
			document.mainform.submit(true)
		}
		// End hiding

		//休工日(曜日)
		const Holidays = __HolidayJS__;
		var HolidaysArray = [];
		for (let i = 0; i < Holidays.length; i++) {
			if (Holidays[i] == 't') {
				HolidaysArray.push(i);
			}
		}

		//休工日(単日)
		var NoWorkDays = __NoWorkDaysJS__;
		var NoWorkDaysEn = __NoWorkDaysEnJS__;

		//ページ開いたときはcheckOKDate1～3を非表示
		$(function () {
			$('.checkOKDate1').hide();
			$('.checkOKDate2').hide();
			$('.checkOKDate3').hide();
		});

		$(function () {
			$('.use-date-picker').datepicker({
				dateFormat: "yy-mm-dd",
				minDate: "__SenyuStartDateConvert__",
				maxDate: "__SenyuEndDateConvert__",
				beforeShowDay: function (date) {//休工日を選択不可にする
					DayOfWeekIndex = date.getDay();
					var dateFmt = 'yy-mm-dd';

					if (HolidaysArray.indexOf(DayOfWeekIndex) != -1) {
						return [false, 'ui-state-disabled'];
					} else if (NoWorkDays != null && NoWorkDays.indexOf($.datepicker.formatDate(dateFmt, date)) != -1) {
						return [false, 'ui-state-disabled'];
					} else {
						return [true, ''];
					}
				}
			});

			$('.use-date-picker-en').datepicker({
				dateFormat: "dd-mm-yy",
				minDate: "__DispSenyuStartDateConvert__",
				maxDate: "__DispSenyuEndDateConvert__",
				beforeShowDay: function (date) {//休工日を選択不可にする
					DayOfWeekIndex = date.getDay();
					var dateFmt = 'dd-mm-yy';

					if (HolidaysArray.indexOf(DayOfWeekIndex) != -1) {
						return [false, 'ui-state-disabled'];
					} else if (NoWorkDays != null && NoWorkDaysEn.indexOf($.datepicker.formatDate(dateFmt, date)) != -1) {
						return [false, 'ui-state-disabled'];
					} else {
						return [true, ''];
					}
				}
			});
		});

		function checkDate1() {
			var wDate1 = document.getElementById('wDate1').value;
			var wsenyuStartDate = __SenyuStartDateJS__;

			if (wDate1 == wsenyuStartDate) {
				$('.checkNGDate1').hide();
				$('.checkOKDate1').show();
			} else {
				$('.checkNGDate1').show();
				$('.checkOKDate1').hide();
			}
		}

		function checkDate2() {
			var wDate2 = document.getElementById('wDate2').value;
			var wsenyuStartDate = __SenyuStartDateJS__;

			if (wDate2 == wsenyuStartDate) {
				$('.checkNGDate2').hide();
				$('.checkOKDate2').show();
			} else {
				$('.checkNGDate2').show();
				$('.checkOKDate2').hide();
			}
		}

		function checkDate3() {
			var wDate3 = document.getElementById('wDate3').value;
			var wsenyuStartDate = __SenyuStartDateJS__;

			if (wDate3 == wsenyuStartDate) {
				$('.checkNGDate3').hide();
				$('.checkOKDate3').show();
			} else {
				$('.checkNGDate3').show();
				$('.checkOKDate3').hide();
			}
		}




		//日程選択の重複検出
		function TimeCheck(obj) {
			var id = obj.id;
			var idx = obj.selectedIndex;//selectのどのoptionを選択したか

			var wDate1 = document.getElementById('wDate1').value;
			var wTime1 = document.getElementById('wTime1').value;
			var wDate2 = document.getElementById('wDate2').value;
			var wTime2 = document.getElementById('wTime2').value;
			var wDate3 = document.getElementById('wDate3').value;
			var wTime3 = document.getElementById('wTime3').value;

			if (id == 'wDate1' || (id == 'wTime1' && idx > 0)) {
				if ((wDate1 + wTime1) == (wDate2 + wTime2)) {
					alert("__reserve_form12__"); //<!--第2希望日程と同じ日程です。異なる日付か時間帯をお選びください。-->
					document.getElementById("wDate1").value = '';
					document.getElementById("wTime1").selectedIndex = 0;
				}

				if ((wDate1 + wTime1) == (wDate3 + wTime3)) {
					alert("__reserve_form13__"); //<!--第3希望日程と同じ日程です。異なる日付か時間帯をお選びください。-->
					document.getElementById("wDate1").value = '';
					document.getElementById("wTime1").selectedIndex = 0;
				}
			}

			if (id == 'wDate2' || (id == 'wTime2' && idx > 0)) {
				if ((wDate1 + wTime1) == (wDate2 + wTime2)) {
					alert("__reserve_form11__"); //<!--第1希望日程と同じ日程です。異なる日付か時間帯をお選びください。-->
					document.getElementById("wDate2").value = '';
					document.getElementById("wTime2").selectedIndex = 0;
				}

				if ((wDate2 + wTime2) == (wDate3 + wTime3)) {
					alert("__reserve_form13__"); //<!--第3希望日程と同じ日程です。異なる日付か時間帯をお選びください。-->
					document.getElementById("wDate2").value = '';
					document.getElementById("wTime2").selectedIndex = 0;
				}
			}

			if (id == 'wDate3' || (id == 'wTime3' && idx > 0)) {
				if ((wDate1 + wTime1) == (wDate3 + wTime3)) {
					alert("__reserve_form11__"); //<!--第1希望日程と同じ日程です。異なる日付か時間帯をお選びください。-->
					document.getElementById("wDate3").value = '';
					document.getElementById("wTime3").selectedIndex = 0;
				}

				if ((wDate2 + wTime2) == (wDate3 + wTime3)) {
					alert("__reserve_form12__"); //<!--第2希望日程と同じ日程です。異なる日付か時間帯をお選びください。-->
					document.getElementById("wDate3").value = '';
					document.getElementById("wTime3").selectedIndex = 0;
				}
			}
		}

		function TimeCheckOK(obj) {
			var id = obj.id;
			var idx = obj.selectedIndex;//selectのどのoptionを選択したか

			var wDate1 = document.getElementById('wDate1').value;
			var wTime1 = document.getElementById('wTime1ok').value;
			var wDate2 = document.getElementById('wDate2').value;
			var wTime2 = document.getElementById('wTime2ok').value;
			var wDate3 = document.getElementById('wDate3').value;
			var wTime3 = document.getElementById('wTime3ok').value;

			if (id == 'wDate1' || (id == 'wTime1ok' && idx > 0)) {
				if ((wDate1 + wTime1) == (wDate2 + wTime2)) {
					alert("__reserve_form12__"); //<!--第2希望日程と同じ日程です。異なる日付か時間帯をお選びください。-->
					document.getElementById("wDate1").value = '';
					document.getElementById("wTime1ok").selectedIndex = 0;
				}

				if ((wDate1 + wTime1) == (wDate3 + wTime3)) {
					alert("__reserve_form13__"); //<!--第3希望日程と同じ日程です。異なる日付か時間帯をお選びください。-->
					document.getElementById("wDate1").value = '';
					document.getElementById("wTime1ok").selectedIndex = 0;
				}
			}

			if (id == 'wDate2' || (id == 'wTime2ok' && idx > 0)) {
				if ((wDate1 + wTime1) == (wDate2 + wTime2)) {
					alert("__reserve_form11__"); //<!--第1希望日程と同じ日程です。異なる日付か時間帯をお選びください。-->
					document.getElementById("wDate2").value = '';
					document.getElementById("wTime2ok").selectedIndex = 0;
				}

				if ((wDate2 + wTime2) == (wDate3 + wTime3)) {
					alert("__reserve_form13__"); //<!--第3希望日程と同じ日程です。異なる日付か時間帯をお選びください。-->
					document.getElementById("wDate2").value = '';
					document.getElementById("wTime2ok").selectedIndex = 0;
				}
			}

			if (id == 'wDate3' || (id == 'wTime3ok' && idx > 0)) {
				if ((wDate1 + wTime1) == (wDate3 + wTime3)) {
					alert("__reserve_form11__"); //<!--第1希望日程と同じ日程です。異なる日付か時間帯をお選びください。-->
					document.getElementById("wDate3").value = '';
					document.getElementById("wTime3ok").selectedIndex = 0;
				}

				if ((wDate2 + wTime2) == (wDate3 + wTime3)) {
					alert("__reserve_form12__"); //<!--第2希望日程と同じ日程です。異なる日付か時間帯をお選びください。-->
					document.getElementById("wDate3").value = '';
					document.getElementById("wTime3ok").selectedIndex = 0;
				}
			}
		}



		// 次へボタン 入力チェック
		function checkInput() {
			var error_flg = false;

			document.getElementById("wDate1Error").innerHTML = "";
			document.getElementById("wDate2Error").innerHTML = "";
			document.getElementById("wDate3Error").innerHTML = "";

			//document.getElementById("Error").style="";

			var wDate1 = document.getElementById("wDate1").value;
			var wDate2 = document.getElementById("wDate2").value;
			var wDate3 = document.getElementById("wDate3").value;

			var SenyuStartDate = new Date('__SenyuStartDateConvert__');
			var SenyuEndDate = new Date('__SenyuEndDateConvert__');

			var d1 = new Date(wDate1);
			var d2 = new Date(wDate2);
			var d3 = new Date(wDate3);

			//alert(  d1 + '>=' + SenyuStartDate + '&&' + d1 + '<= ' + SenyuEndDate  );
			if (d1 < SenyuStartDate || d1 > SenyuEndDate || document.getElementById("wTime1").value == "" && document.getElementById("wTime1ok").value == "") {
				error_flg = true;
				document.getElementById("wDate1Error").innerHTML = "__reserve_form9__";//<!--※第1希望を工期内（__SenyuStartDateConvert__～__SenyuEndDateConvert__）で選択お願いします。-->
			}
			if (d2 < SenyuStartDate || d2 > SenyuEndDate || document.getElementById("wTime2").value == "" && document.getElementById("wTime2ok").value == "") {
				error_flg = true;
				document.getElementById("wDate2Error").innerHTML = "<br>__reserve_form10__";//<!--※第2希望を工期内（__SenyuStartDateConvert__～__SenyuEndDateConvert__）で選択お願いします。-->
			}
			//if( d3 < SenyuStartDate || d3 > SenyuEndDate || document.getElementById("wTime3").value == ""){
			//	error_flg = true;
			//	document.getElementById("wDate3Error").innerHTML = "<br>※第3希望を工期内（__SenyuStartDateConvert__～__SenyuEndDateConvert__）で選択お願いします。";
			//}
			//休日チェック
			__ShopHolidayLoop__
			if (wDate1 == '__SetteiHoliday__' || wDate2 == '__SetteiHoliday__' || wDate3 == '__SetteiHoliday__') {
				error_flg = true;
				document.getElementById("wDateHolidayError").innerHTML = "__reserve_form14__ ";//※__SetteiHoliday__は休工日とさせていただいております。他の日程を選択お願いします。
			}
			__ShopHolidayLoop__

			if (error_flg) {
				//document.getElementById("Error").style="background-color:yellow"
				//window.scrollTo(0,0);
				return false;
			} else {
				document.mainform.method = "POST";
				//document.mainform.work.value = 1;
				document.mainform.target = "_self";
				document.mainform.action = "reserve_confirm.php?rKey=__rKey__";
				document.mainform.submit();
			}
		}
	</script>



	<script>
		// 次へボタン 入力チェック
		function dateToStr(date) {//11-8-2022

			var dateArray = date.split('-');
			format = dateArray[1] + "-" + dateArray[0] + "-" + dateArray[2];
			return format;//8-11-2022
		}
		function checkInputen() {//英語表記日付比較　dateToStrをいれて形変えただけ。　ほかの言語ときは　日本語か英語にどっちかに近いはず
			var error_flg = false;

			document.getElementById("wDate1Error").innerHTML = "";
			document.getElementById("wDate2Error").innerHTML = "";
			document.getElementById("wDate3Error").innerHTML = "";

			//document.getElementById("Error").style="";

			var wDate1 = dateToStr(document.getElementById("wDate1").value);
			var wDate2 = dateToStr(document.getElementById("wDate2").value);
			var wDate3 = dateToStr(document.getElementById("wDate3").value);

			var SenyuStartDate = new Date(dateToStr('__DispSenyuStartDateConvert__')); // 11-8-2022  8月11日
			var SenyuEndDate = new Date(dateToStr('__DispSenyuEndDateConvert__'));

			var d1 = new Date(wDate1);
			var d2 = new Date(wDate2);
			var d3 = new Date(wDate3);


			if (d1 < SenyuStartDate || d1 > SenyuEndDate || document.getElementById("wTime1").value == "" && document.getElementById("wTime1ok").value == "") {
				error_flg = true;
				document.getElementById("wDate1Error").innerHTML = "__reserve_form9__";//<!--※第1希望を工期内（__SenyuStartDateConvert__～__SenyuEndDateConvert__）で選択お願いします。-->
			}
			if (d2 < SenyuStartDate || d2 > SenyuEndDate || document.getElementById("wTime2").value == "" && document.getElementById("wTime2ok").value == "") {
				error_flg = true;
				document.getElementById("wDate2Error").innerHTML = "<br>__reserve_form10__";//<!--※第2希望を工期内（__SenyuStartDateConvert__～__SenyuEndDateConvert__）で選択お願いします。-->
			}
			//if( d3 < SenyuStartDate || d3 > SenyuEndDate || document.getElementById("wTime3").value == ""){
			//	error_flg = true;
			//	document.getElementById("wDate3Error").innerHTML = "<br>※第3希望を工期内（__SenyuStartDateConvert__～__SenyuEndDateConvert__）で選択お願いします。";
			//}
			//休日チェック
			__ShopHolidayLoop__
			if (wDate1 == '__SetteiHoliday__' || wDate2 == '__SetteiHoliday__' || wDate3 == '__SetteiHoliday__') {
				error_flg = true;
				document.getElementById("wDateHolidayError").innerHTML = "__reserve_form14__ ";//※__SetteiHoliday__は休工日とさせていただいております。他の日程を選択お願いします。
			}
			__ShopHolidayLoop__

			if (error_flg) {
				//document.getElementById("Error").style="background-color:yellow"
				//window.scrollTo(0,0);
				return false;
			} else {
				document.mainform.method = "POST";
				//document.mainform.work.value = 1;
				document.mainform.target = "_self";
				document.mainform.action = "reserve_confirm.php?rKey=__rKey__";
				document.mainform.submit();
			}
		}
	</script>



	<link rel="stylesheet" type="text/css" href="./css/common.css">
	<link rel="stylesheet" type="text/css" href="./css/style_okyakusama.css">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
	<!--最小限のビューポート設定-->
	<meta name="viewport" content="width=device-width">
</head>

<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
	<table>
		<div id="navi">
			<!--<a class="gengo" href="#.php">English</a>-->
			<a class="bar" href="top.php__QUERY__">__finish3__<!--予約TOP--></a>
			<a class="bar-migi" href="logout.php__QUERY__">__logout1__<!--ログアウト--></a>
		</div>

		__SHeader__

		<h1>__MansionName__</h1>
		<h2>__reserve_form1__ __wID__</h2>

		<h3>__reserve_form2__ <!--日程選択--></h3>

		<div id="step4">
			<img src="./images/__steppng__" alt="step">
		</div>


		__IfErr__<font color="red">__reserve_form2__ <!--日程を選択してください--></font><br>__IfErr__
		<!-- <p class="hissu"><font color="red">&nbsp;&nbsp; __reserve_form2__  *</font>は入力必須項目です。</p>-->

		<!--▽カレンダー-->
		<h4>__reserve_form3__ <!--１.日付確認-->
			<font color="red"></font>
		</h4>
		<div class="block-form">

			<p class="naka">__reserve_form4__ <!--X印は希望者が多く、希望された場合、希望にお応えにくい日程です。工事期間以外の日付をご希望のお客様は、工事予約受付センターまでご連絡お願いします。--></p>
			<p class="jikan2">__reserve_form5__ <!--専有部工事期間-->__DispSenyuStartDate__(__w4__)～__DispSenyuEndDate__(__w5__)</p>
			<p class="jikan3">__reserve_form6__ <!--現在の仮工事日程-->__IfNokara__ __DispwTimeFrom__(__weekday__)__IfNokara__</p>
			<!--未入力の時これ→<p class="naka3"><font color="red">時間帯の選択は必須です。</font></p>-->

			<!--▼カレンダーここから-->
			<table class="calendar">
				<tr>
					<td colspan="2" bgcolor="#EFF7FF" style="border-style: none; text-align: left;">
						<form action="reserve_form.php__QUERY__&wLang=__wLang__" method="post">
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
									<font size=3>__reserve_form7__ <!--＜＜ 前の月--></font>
								</a>
							</button>
							__HiddenValues__
						</form>
					</td>
					<td colspan="3" bgcolor="#EFF7FF" style="border-style: none; font-size: 18px;">
						__DispThisMonth__<!--2022年8月-->
					</td>
					<td colspan="2" bgcolor="#EFF7FF" style="border-style: none;  text-align: right;">
						<form action="reserve_form.php__QUERY__&wLang=__wLang__" method="post">
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
									<font size=3>__reserve_form8__ <!--次の月 ＞＞--></font>
								</a>
							</button>
							__HiddenValues__
						</form>
					</td>
				</tr>

				<!--▽曜日-->
				<tr>
					__DispWeek__
					<!--<th bgcolor="#ffc0cb">日</th>
					<th bgcolor="#ffffff">月</th>
					<th bgcolor="#ffffff">火</th>
					<th bgcolor="#ffffff">水</th>
					<th bgcolor="#ffffff">木</th>
					<th bgcolor="#ffffff">金</th>
					<th bgcolor="#bde0ff">土</th>-->
				</tr>
				<!--△曜日ー-->

				<!--▽日付-->
				__WeekLoop__
				<tr>
					__WeekdayLoop__
					<td bgcolor="__DayColor__" __ClassDisable__>
						__ThisDay__<br>
						__Jokyo__
					</td>
					__WeekdayLoop__
					__WeekdayBlock__
				</tr>
				__WeekLoop__
				<!--△日付-->

			</table>
			<!--▲カレンダー上ここまで-->
		</div>


		<form method="POST" action="reserve_confirm.php__QUERY__&wLang=__wLang__" name="mainform">

			<!--▽時間帯選択-->
			<h4>__reserve_form15__<!--２.希望日選択-->
				<font color="red"> *</font>
			</h4>
			<div class="block-form">
				<!--<p class="naka">ご希望の時間帯を選択してください。<br>空きのある時間帯のみ選択可能です。</p>-->
				<!--<p class="jikan">現在選択中の日付：__IfNokara____wYear__年__wMonth__月__wDay__日(__weekday__)__IfNokara__</p>-->
				<!--カレンダーで選択した日付表示-->
				<!--<select name="wTime" class="form-control">
						__OKTimesLoop__
						<option value="__OKTimes__" __OKTimeSelected__>__wOKTimeName__</option>
						__OKTimesLoop__
					</select>-->

				<p class="naka"><!--上記カレンダーの工事期間内の工事日を時間帯を第3希望までご入力をお願いします。--><br>
					<font color="red"><b><span id="wDateHolidayError" style="background-color:yellow"></span></b></font>
					<font color="red"><b><span id="wDate1Error" style="background-color:yellow"></span></b></font>
					<font color="red"><b><span id="wDate2Error" style="background-color:yellow"></span></b></font>
					<font color="red"><b><span id="wDate3Error" style="background-color:yellow"></span></b></font>
				</p>

				<div class="block-form-inline">







					<div class="block-form-item">
						<label>__reserve_form24__<!--第１希望--></label>
						<input type="text" name="wDate1" id="wDate1" value="__wDate1__" class="__usedatepicker__" onchange="checkDate1();" readonly><!--use-date-picker-en-->
					</div>
					<div class="block-form-item">
						<label>__reserve_form27__<!--時間帯--></label>
						<div class="checkNGDate1">
							<select name="wTime1" id="wTime1" onchange="TimeCheck(this);">
								<option value="">-</option>
								__TimeLoop__
								<option value="__wTime__" __wTime1Selected__>__wTime__</option>
								__TimeLoop__
							</select>
						</div>
						<div class="checkOKDate1">
							<select name="wTime1ok" id="wTime1ok" onchange="TimeCheckOK(this);">
								<option value="">-</option>
								__TimeOKLoop__
								<option value="__wTimeOK__" __wTime1Selected__>__wTimeOK__</option>
								__TimeOKLoop__
							</select>
						</div>
					</div>

					<div class="block-form-item">
						<label>__reserve_form25__<!--第２希望--></label>
						<input type="text" name="wDate2" id="wDate2" value="__wDate2__" class="__usedatepicker__" onchange="checkDate2();" readonly>
					</div>
					<div class="block-form-item">
						<label>__reserve_form27__<!--時間帯--></label>
						<div class="checkNGDate2">
							<select name="wTime2" id="wTime2" onchange="TimeCheck(this);">
								<option value="">-</option>
								__TimeLoop__
								<option value="__wTime__" __wTime2Selected__>__wTime__</option>
								__TimeLoop__
							</select>
						</div>
						<div class="checkOKDate2">
							<select name="wTime2ok" id="wTime2ok" onchange="TimeCheckOK(this);">
								<option value="">-</option>
								__TimeOKLoop__
								<option value="__wTimeOK__" __wTime1Selected__>__wTimeOK__</option>
								__TimeOKLoop__
							</select>
						</div>
					</div>

					<div class="block-form-item">
						<label>__reserve_form26__<!--第３希望（任意）--></label>
						<input type="text" name="wDate3" id="wDate3" value="__wDate3__" class="__usedatepicker__" onchange="checkDate3();" readonly>
					</div>
					<div class="block-form-item">
						<label>__reserve_form27__<!--時間帯--></label>
						<div class="checkNGDate3">
							<select name="wTime3" id="wTime3" onchange="TimeCheck(this);">
								<option value="">-</option>
								__TimeLoop__
								<option value="__wTime__" __wTime3Selected__>__wTime__</option>
								__TimeLoop__
							</select>
						</div>
						<div class="checkOKDate3">
							<select name="wTime3ok" id="wTime3ok" onchange="TimeCheckOK(this);">
								<option value="">-</option>
								__TimeOKLoop__
								<option value="__wTimeOK__" __wTime1Selected__>__wTimeOK__</option>
								__TimeOKLoop__
							</select>
						</div>
					</div>
				</div>
			</div>
			<!--△時間帯選択-->

			<!--▽ご要望-->
			<h4>__reserve_form19__<!--３.ご要望--></h4>
			<div class="block-formsaigo">
				<p class="naka">__reserve_form20__<!--工事についてご要望等ありましたら、ご記入ください。--></p>
				<p class="komoji">__reserve_form21__<!--(ご記入内容によっては、電話またはメールにてご連絡させていただく場合がございます。)--></p>
				<textarea name="wUserMemo" cols="50" rows="3" class="box">__wUserMemo__</textarea>
			</div>
			<!--△ご要望-->


			<input type="hidden" name="wID" value="__wID__">
			<input type="hidden" name="MansionName" value="__MansionName__">
			<input type="hidden" name="wDate" value="__wDate__">
			<input type="hidden" name="MyMenuCD" value="__MyMenuCD__">
			<input type="hidden" name="c" value="__wClientCD__">
			<input type="hidden" name="editReservationCD" value="__editReservationCD__">


			<div class="op-moushikomi">
				__checkInput__<!--input type button 次へ-->
			</div>

			<div class="tophe">
				<input type="button" value="__reserve_form23__" onClick="location.href='top.php__QUERY__'" class="tophe-btn"><!--戻る-->
			</div>
		</form>

		__SFooter__

		__SCopyright__

	</table>
	<script>
		$('.js-load-time').change(function () {
			var valDate = $(this).val();
			var boxLoad = $(this).attr('data-load');
			if (valDate != "") {
				var arrPath = location.pathname.split('/');
				var url = "./ajax/loadTime.php";
				alert(url);
				var nameInputClick = $(this).attr('name');

				$.ajax({
					url: url,
					type: 'post',
					data: ({
						valDate: valDate,
						clientCD: __TargetClientCD__,
						stylistCD: '__JsonStylistCD__',
						wakuPattern: __WakuPattern__,
						myMinuteType: '__wMyMinuteType__',
						jsonSTimeList: '__jsonSTimeList__',
						jsonETimeList: '__jsonETimeList__',
						wakuSuu: '__WakuSuu__',
						minuteunit: '__MINUTEUNIT__',
						jsonStylistList: '__jsonStylistList__',
						id: __wID__
					}),
					success: function (response) {
						alert("jsonStylistList");
						var data = JSON.parse(response);
						var htmlOption = '';
						var timeLoop = data[0].length;

						if (timeLoop > 0) {
							for (var i = 0; i < timeLoop; i++) {
								htmlOption += '<option value="' + data[0][i] + '">' + data[0][i] + '</option>'
							}

							for (var i = 1; i <= 3; i++) {
								var nameInput = 'wDate' + i;
								if (nameInputClick != nameInput && $('input[name="' + nameInput + '"]').val() == valDate) {
									$('input[name="' + nameInputClick + '"]').val('');
									alert('Date same.');
									return;
								}
							}
							$('#' + boxLoad).html(htmlOption);
						} else {
							$(this).val('');
							alert('Date full.');
							return;
						}
						if ($('.js-load-time').val() != '') {
							for (var i = 1; i <= 3; i++) {
								var timeSelected = $('#load-time' + i).attr('data-checked');
								if (timeSelected != "") {
									$('#load-time' + i).val(timeSelected);
								}
							}
						}
					},
					error: function (XMLHttpRequest, textStatus, errorThrown) {
						alert('error!!!');
						console.log("XMLHttpRequest : " + XMLHttpRequest.status);
						console.log("textStatus     : " + textStatus);
						console.log("errorThrown    : " + errorThrown);
					}
				});
			} else {
				$('#' + boxLoad).html('');
			}
		});
		if ($('.js-load-time').val() != '') {
			$('.js-load-time').trigger('change');
		}
	</script>
</body>

</html>