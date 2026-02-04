<html>
<head>
<title>__InstituteTitle____ReservationName__</title>
</head>
<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
__SHeader__
<hr size="__HRSize__" color="__HRColor__">
<center>《__InstituteTitle____ReservationName__》</center>
<hr size="__HRSize__" color="__HRColor__">


<table align="center"><tr><td align="left" width=400>

__IfNoReservation__現在お客様の予約はございません。__IfNoReservation__

__IfReservation__お客様の予定の工事日は、以下の日程となっております。
工事日を変更する場合は、変更ボタン、変更が不要の方は、
確定ボタンをクリックしてください。<br>
<br>

</td></tr></table>


<table align="center"><tr><td align="left" width=300>

__ReservationLoop____ReservationDate__</a>
<br>
<br>
<FORM method="POST" action="reserve_form.php__ReserveQuery__">
<INPUT type="submit" name="submit" value=" 変更 " > 
</FORM><br>
<FORM method="POST" action="kakutei.php">
<INPUT type="submit" name="submit" value=" 確定 " >
</FORM>

__ReservationLoop__
__IfReservation__

</td></tr></table>


<hr size="__HRSize__" color="__HRColor__">
<table align="center"><tr><td align="left" width=300>
<a href="top.php__QUERY__">■もどる</a><br>
</td></tr></table>
__SFooter__
<hr size="__HRSize__" color="__HRColor__">
__SCopyright__
<br>
</body>
</html>
