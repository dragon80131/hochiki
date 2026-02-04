<html>
<head>
<title>__InstituteTitle____ReservationName__</title>
</head>
<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
__SHeader__
<hr size="__HRSize__" color="__HRColor__">
<center>《__InstituteTitle____ReservationName__》</center>
<hr size="__HRSize__" color="__HRColor__">

<form method="post" action="reserve_confirm.php">

__IfDateError__<font color="red">ご希望の日付が正しくありません。</font><br>__IfDateError__
__IfHolidayError__<font color="red">ご希望の日付はお休みをいただいております。</font><br>__IfHolidayError__
__IfFullt__<font color="red">ご希望の時間は予約が埋まっております。他の時間をお選びください。</font><br>__IfFullt__

__IfError__<br>__IfError__



<table align="center"><tr><td align="left" width=400>

<table align="center"><tr><td align="left" width=300>
■__ReservationName__日<br>
__wYear__年__wMonth__月__wDay__日<br>
<br>






■ご希望工事時間帯<br>
選択できる時間のみ予約可能となっています。<br>

<select name="wTime">
__OKTimesLoop__<option value="__OKTimes__"__OKTimeSelected__>__OKTimesName__<br>
__OKTimesLoop__</select><br>
<br>



■ご要望（60文字以内）<br>
<textarea name="wMemo" cols="20" rows="3">__wMemo__</textarea><br>
<br>

<input type="hidden" name="wTempOKTimesName" value="__wTempOKTimesName__">


<input type="hidden" name="ticket" value=__ticket__>
<input type="submit" value="次へ">
__HiddenValues__
</form>
<br />
<br>

<form method="post" action="__FormFile__">
<input type="submit" value="戻る">
__HiddenValues__
</form>

</td></tr></table>


</td></tr></table>


<hr size="__HRSize__" color="__HRColor__">



<table align="center"><tr><td align="left" width=300>
<a href="top.php__QUERY__">■トップ</a><br>
</td></tr></table>

__SFooter__
<hr size="__HRSize__" color="__HRColor__">
__SCopyright__
<br>
</body>
</html>
