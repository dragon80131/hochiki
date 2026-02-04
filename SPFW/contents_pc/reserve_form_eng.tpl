<html>
<head>
<title>Select desired installation date</title>
<SCRIPT language=JavaScript>
<!-- Hide script from old browser
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

// end hiding -->
</SCRIPT>
<link rel="stylesheet" type="text/css" href="css/common.css">
<link rel="stylesheet" type="text/css" href="css/style_okyakusama.css">
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
<!--最小限のビューポート設定-->
<meta name="viewport" content="width=device-width">

</head>

<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
<table>

<div id="navi">
<!--<a class="gengo" href="#.php">Englishi</a>-->
<a class="bar" href="top.php__QUERY__">HOME</a>
<a class="bar-migi" href="logout.php__QUERY__">Logout</a>
</div>

__SHeader__

<h1>__MansionName__</h1>
<h2>Room No __wID__</h2>

<h3>Select desired installation date</h3>


<div id="step">
<img src="./images/step2.png" alt="step">
</div>

<!--▽エラー赤文字必要？
__IfDateError__<font color="red">ご希望の日付が正しくありません。</font><br>__IfDateError__
__IfReserveDateError__<font color="red">ご希望の日付は予約できる期間から外れています。</font><br>__IfReserveDateError__
__IfMenuEmpty__<font color="red">メニューは必ず選択してください。</font><br>__IfMenuEmpty__
__IfHolidayError__<font color="red">ご希望の日付はお休みをいただいております。</font><br>__IfHolidayError__
__IfStaffHolidayError__<font color="red">ご希望の日付は__Staff__がお休みをいただいております。</font><br>__IfStaffHolidayError__
__IfStaffMenuError__<font color="red">ご希望のメニューはご指名の__Staff__が担当できませんため、他の__Staff__をお選び下さいませ。</font><br>__IfStaffMenuError__
__IfFull__<font color="red">ご希望の時間は予約が埋まっております。申し訳ございません。</font><br>__IfFull__
__IfAlready__<font color="red">すでに予約登録済みですので、新たなご予約はできません。修正・キャンセルをお願いします。</font><br>__IfAlready__
__IfStylistNotFound__<font color="red">ご希望のメニューを担当できるスタッフが当日はおりませんでした。</font><br>__IfStylistNotFound__
__IfError__<br>__IfError__
△エラー赤文字必要？-->
__IfErr__<font color="red">Please select an installation date</font><br>__IfErr__
<p class="hissu"><font color="red">&nbsp;&nbsp;*</font>	is a required field.</p>

<!--▽カレンダー--> 
<h4>１.Select the installation date<font color="red"> *</font></h4>
<div class="block-form">


<p class="naka">Please select your desired date from the calendar.<br>
Only the dates marked with ○ or △ can be selected.</p>
<p class="jikan2">Duration of the installation：__DispSenyuStartDate__(__w4__)～
__DispSenyuEndDate__(__w5__)</p>
<p class="jikan3">Selected date：__IfNokara____wYear__年__wMonth__月__wDay__日(__weekday__)__IfNokara__</p>
<!--未入力の時これ→<p class="naka3"><font color="red">時間帯の選択は必須です。</font></p>-->

<!--▼カレンダーここから-->
<table class="calendar" >
<tr>
<td colspan="2" bgcolor="#EFF7FF" style="border-style: none; text-align: left;">
<form action="reserve_form.php__QUERY__&wLang=__wLang__" name="mainform" method="post">
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
<a href="javascript:void(0)"><font size=3>＜＜ Previous Month</font></a>
__HiddenValues__
</button>
</form></td>
<td colspan="3" bgcolor="#EFF7FF" style="border-style: none; font-size: 18px;">__ThisYear__年__ThisMonth__月</td>
<td colspan="2" bgcolor="#EFF7FF" style="border-style: none;  text-align: right;">
<form action="reserve_form.php__QUERY__&wLang=__wLang__" name="mainform" method="post">
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
<a href="javascript:void(0)"><font size=3>Next Month ＞＞</font></a>
</button>
__HiddenValues__
</form></td>
</tr>
<div class="op-moushikomi">
<form method="POST" action="reserve_confirm.php__QUERY__&wLang=__wLang__">

<!--▽曜日-->
<tr>
<th bgcolor="#ffc0cb">Sun</th>
<th bgcolor="#ffffff">Mon</th>
<th bgcolor="#ffffff">Tue</th>
<th bgcolor="#ffffff">Wed</th>
<th bgcolor="#ffffff">Thu</th>
<th bgcolor="#ffffff">Fri</th>
<th bgcolor="#bde0ff">Sat</th>
</tr>
<!--△曜日ー-->

    <!--▽日付-->
__WeekLoop__
    <tr>
__WeekdayLoop__
    <td bgcolor="__DayColor__">
__IfOK1__
<a href="reserve_form.php__QUERY__&wDate=__FullDate__&wLang=__wLang__&Select=1">__IfOK1____ThisDay____IfOK2__</a>__IfOK2__<br>__IfOK1__
<a href="reserve_form.php__QUERY__&wDate=__FullDate__&wLang=__wLang__&Select=1">__IfOK1____Jokyo__
__IfOK2__</a>__IfOK2__</td>
__WeekdayLoop____WeekdayBlock__	
</tr>


__WeekLoop__
 

</table>

<div class="mikata">
<p class="hissu2">○…Available&emsp;△…few spots left&emsp;×…Not available&emsp;休…Holidays</p>
</div>

<!--▲カレンダー上ここまで--> 


</div>



<!--▽時間帯選択-->
<h4>２.Time selection<font color="red"> *</font></h4>
<div class="block-form">
<p class="naka">Please select your preferred time.<br>Only available time slots can be selected.</p>
<p class="jikan">Selected date：__IfNokara____wYear__年__wMonth__月__wDay__日(__weekday__)__IfNokara__</p><!--カレンダーで選択した日付表示-->

<select name="wTime">
__OKTimesLoop__
<option value="__OKTimes__"__OKTimeSelected__>__wOKTimeName__</option>
__OKTimesLoop__
</select>
</div>


<!--▽第二希望-->
<h4>３.Second option date</h4>
<div class="block-form">
<p class="naka">
Please enter any other dates staffs can visit during the installation duration.<br>
(記入例：4/1　9-12)</p>
<p class="daini">Second option date：</p>
<textarea name="wSecondChoice" cols="10" rows="1" style="width:200px">__wSecondChoice__</textarea>
</div>
<!--△第二希望-->


<!--▽ご要望-->
<h4>４.Request</h4>
<div class="block-formsaigo">
<p class="naka">If you have any requests for installation please fill in.</p>
<p class="komoji">
(Depending on the contents, we may contact you by phone or email.)</p>
<textarea name="wwMemo" cols="50" rows="3" class="box">__wwMemo__</textarea>
<input type="hidden" name="wMemo" value="__wMemo__">
</div>
<!--△ご要望-->
<input type=hidden name="wID" value="__wID__">
<input type=hidden name="MansionName" value="__MansionName__">
<input type=hidden name="wDate" value="__wDate__">

<input type="hidden" name="MyMenuCD" value="__MyMenuCD__">



__HiddenValues__
<input type="submit" value=" Next " class="finish-btn">

<div class="tophe">

<input type="button" value=" HOME " onClick="location.href='top.php__QUERY__&wLang_1'" class="tophe-btn">
</form>
</div>

<!--▽もともとあったもの
<form method="post" action="reserve_form.php" name="mainform">
カレンダーからご希望日を選択し、『次へ』ボタンをクリックしてください。<br><br>
■現在選択中の__ReservationName__日：
__wDate__<br>
<br>



<table>

<select name="wGoYear"> 
__YearLoop__	 <option value="__YearValue__"__YearSelected__>__YearValue__</option> 
__YearLoop__	</select>年
<select name="wGoMonth">
__MonthLoop__	 <option value="__MonthValue__"__MonthSelected__>__MonthValue__</option> 
__MonthLoop__	</select>月
<input type="submit" value="カレンダーを表示">
<input type="hidden" name="vCal" value="t">
__HiddenValues__

<br>
<font color="__OutOfTermColor__">■</font>予約期間外&nbsp;<font color="__HolidayColor__">■</font>予約がいっぱい<br>
<table border="1" cellpadding="0" cellspacing="0"> 
<tr> 
<td width="50" bgcolor="#FFE8E8"><font color="#A54545"><center>日</center></font></td> 
<td width="50" bgcolor="#FFFFFF"><center>月</center></td> 
<td width="50" bgcolor="#FFFFFF"><center>火</center></td> 
<td width="50" bgcolor="#FFFFFF"><center>水</center></td> 
<td width="50" bgcolor="#FFFFFF"><center>木</center></td> 
<td width="50" bgcolor="#FFFFFF"><center>金</center></td> 
<td width="50" bgcolor="#DBE9F4"><font color="#4E7CB5"><center>土</center></font></td> 
</tr> 
__WeekLoop__
<tr>
__WeekdayLoop__<td bgcolor="__DayColor__"><center>__IfOK1__<a href="reserve_form.php__QUERY__&wDate=__FullDate__&wLang=__wLang__">__IfOK1____ThisDay____IfOK2__</a>__IfOK2__</center></td>
__WeekdayLoop____WeekdayBlock__	</tr>
__WeekLoop__
</table>
<!--カレンダー 
</form>

__IfReserveOK____IfYouCan__<form method="post" action="reserve_info.php?wLang=__wLang__">
__IfStylist__■工事内容<br>
<select name="wStylistCD">
__StylistLoop__		<option value="__StylistCD__"__StylistSelected__>__StylistName__
__StylistLoop__		</select><br>
<br>
__IfStylist__
<br>
<input type="submit" value=" 次へ "><br>
__IfReserveOK__
__HiddenValues__


__MenuLoop__<input type="hidden" name="wMenuCD[]" value="__MenuCD__"__MenuChecked__>
__MenuLoop__<br>
<input type="hidden" name="wGoYear" value="__wGoYear__">
<input type="hidden" name="wGoMonth" value="__wGoMonth__">
</form>__IfYouCan__
△もともとあったもの-->



__SFooter__

__SCopyright__


</table>
</body>
</html>
