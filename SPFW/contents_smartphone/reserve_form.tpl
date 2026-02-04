<html>
<head>
<title>__InstituteTitle____ReservationName__</title>


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


</head>
<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
__SHeader__
<hr size="__HRSize__" color="__HRColor__">
<center>《__InstituteTitle____ReservationName__》</center>
<hr size="__HRSize__" color="__HRColor__">

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

<!--カレンダー--> 

<table align="center"><tr><td align="left" width=400>
<form method="post" action="reserve_form.php" name="mainform">
カレンダーからご希望日を選択し、『次へ』ボタンをクリックしてください。<br><br>
■現在選択中の__ReservationName__日：
__wDate__<br>
<br>





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
__WeekdayLoop__<td bgcolor="__DayColor__"><center>__IfOK1__<a href="reserve_form.php__QUERY__&wDate=__FullDate__">__IfOK1____ThisDay____IfOK2__</a>__IfOK2__</center></td>
__WeekdayLoop____WeekdayBlock__	</tr>
__WeekLoop__
</table>
<!--カレンダー--> 
</form>

__IfReserveOK____IfYouCan__<form method="post" action="reserve_info.php">


__IfStylist__■工事内容<br>
<select name="wStylistCD">
__StylistLoop__		<option value="__StylistCD__"__StylistSelected__>__StylistName__
__StylistLoop__		</select><br>
<br>
__IfStylist__
<br>



<input type="submit" value="次へ"><br>
__IfReserveOK__

__HiddenValues__

__MenuLoop__<input type="hidden" name="wMenuCD[]" value="__MenuCD__"__MenuChecked__>
__MenuLoop__
<br>
<input type="hidden" name="wGoYear" value="__wGoYear__">
<input type="hidden" name="wGoMonth" value="__wGoMonth__">



</form>__IfYouCan__



<form method="POST" action="reserve_top.php__QUERY__">
__COMMON_POST_QUERY__ <br>
<input type="submit" value="戻る">
</form>

</td></tr></table>
__SFooter__
<hr size="__HRSize__" color="__HRColor__">
__SCopyright__
<br>
</body>
</html>
