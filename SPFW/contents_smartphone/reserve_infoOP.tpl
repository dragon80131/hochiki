<html>
<head>
<title>__InstituteTitle____ReservationName__</title>
</head>
<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
__SHeader__
<hr size="__HRSize__" color="__HRColor__">
<center>《オプション申し込み》</center>
<hr size="__HRSize__" color="__HRColor__">

<form method="post" action="reserve_finishOP.php">

__IfDateError__<font color="red">ご希望の日付が正しくありません。</font><br>__IfDateError__
__IfHolidayError__<font color="red">ご希望の日付はお休みをいただいております。</font><br>__IfHolidayError__
__IfFullt__<font color="red">ご希望の時間は予約が埋まっております。他の時間をお選びください。</font><br>__IfFullt__

__IfError__<br>__IfError__



<table align="center"><tr><td align="left" width=350>



■オプション内容
<br><br>
__Ifmenu0____MenuNameA1__<br>__Ifmenu0__
__Ifmenu1____MenuNameA2__<br>__Ifmenu1__
__Ifmenu2____MenuNameA3__<br>__Ifmenu2__
__Ifmenu3____MenuNameA4__<br>__Ifmenu3__
__Ifmenu4____MenuNameA5__<br>__Ifmenu4__
__Ifmenu5____MenuNameA6__<br>__Ifmenu5__
__Ifmenu6____MenuNameA7__<br>__Ifmenu6__
__Ifmenu7____MenuNameA8__<br>__Ifmenu7__
<br>



<input type="submit" value=" 確定 ">
__HiddenValues__
</form>
<br /><br />
<form method="post" action="reserve_top.php__QUERY__">
<input type="submit" value=" 修正 ">
__HiddenValues__
</form>

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
