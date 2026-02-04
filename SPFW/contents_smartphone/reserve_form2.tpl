<html>
<head>
<title>__InstituteTitle____ReservationName__</title>
</head>
<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
__SHeader__
<hr size="__HRSize__" color="__HRColor__">
<center>《__InstituteTitle____ReservationName__》</center>
<hr size="__HRSize__" color="__HRColor__">

<form method="post" action="reserve_info.php">

__IfDateError__<font color="red">ご希望の日付が正しくありません。</font><br>__IfDateError__
__IfReserveDateError__<font color="red">ご希望の日付は予約できる期間から外れています。</font><br>__IfReserveDateError__
__IfMenuEmpty__<font color="red">メニューは必ず選択してください。</font><br>__IfMenuEmpty__
__IfHolidayError__<font color="red">ご希望の日付はお休みをいただいております。</font><br>__IfHolidayError__
__IfStaffHolidayError__<font color="red">ご希望の日付は__Staff__がお休みをいただいております。</font><br>__IfStaffHolidayError__
__IfStaffMenuError__<font color="red">ご希望のメニューはご指名の__Staff__が担当できませんため、他の__Staff__をお選び下さいませ。</font><br>__IfStaffMenuError__
__IfFull__<font color="red">ご希望の日は予約が埋まっております。申し訳ございません。</font><br>__IfFull__
__IfAlready__<font color="red">お客様は現在ご予約をお持ちですので、新たなご予約はできません。</font><br>__IfAlready__
__IfStylistNotFound__<font color="red">ご希望のメニューを担当できるスタッフが当日はおりませんでした。</font><br>__IfStylistNotFound__
__IfError__<br>__IfError__

__IfYouCan____IfStylist__■__Designation__<br>
<select name="wStylistCD">
__StylistLoop__		<option value="__StylistCD__"__StylistSelected__>__StylistName__
__StylistLoop__		</select><br>
<br>
__IfStylist__
■__ReservationName__日<br>
<select name="wYear" size="1">
__YearLoop__			<option value="__YearValue__"__YearSelected__>__YearValue__</option>
__YearLoop__			</select>
年
<select name="wMonth" size="1">
__MonthLoop__			<option value="__MonthValue__"__MonthSelected__>__MonthValue__</option>
__MonthLoop__			</select>
月
<select name="wDay" size="1">
__DayLoop__			<option value="__DayValue__"__DaySelected__>__DayValue__</option>
__DayLoop__			</select>
日<br>
<br>

■__Menu__<br>
__MenuLoop__<input type="checkbox" name="wMenuCD[]" value="__MenuCD__"__MenuChecked__>__MenuName__<br>
__MenuLoop__<br>
<br>

<input type="submit" value="__ReservationName__">

__HiddenValues__
</form>__IfYouCan__

<hr size="__HRSize__" color="__HRColor__">
<a href="reserve_top.php__QUERY__">■もどる</a><br>
__SFooter__
<hr size="__HRSize__" color="__HRColor__">
__SCopyright__
<br>
</body>
</html>
