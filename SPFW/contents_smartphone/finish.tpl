<html>
<head>
<title>連絡先登録</title>
<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
__SHeader__
<hr size="__HRSize__" color="__HRColor__">
<center>《連絡先登録》</center>


<hr size="__HRSize__" color="__HRColor__">

__IfReservation__
<table align="center" ><tr><td align="left" width=400>
__IfNoOp__
<br>
お客様の連絡先の登録が完了しました。次に、<br>
「日程確定・日程変更へ」にお進みください。
<br>

<br>
<a href="reserve_top.php?rKey=__rKey__">日程確定・日程変更へ</a><br>
__IfNoOp__

__IfOp__
<br>
お客様の連絡先の登録が完了しました。次に、<br>
「日程確定・日程変更・オプション申し込みへ」にお進み
ください。
<br>
<br>
<a href="reserve_top.php?rKey=__rKey__">日程確定・日程変更・オプション申し込みへ</a><br>
__IfOp__


</td></tr></table>

__IfReservation__


__IfNoReservation__

<table align="center" ><tr><td align="left" width=400>
<br>
お客様の連絡先の登録が完了しました。次に、<br>
「工事日程の新規登録・変更へ」にお進みください。
<br>
</td></tr></table>
<br>
<a href="reserve_top.php?rKey=__rKey__">工事日程の新規登録・変更へ</a><br>

__IfNoReservation__


__SFooter__
<hr size="__HRSize__" color="__HRColor__">
__SCopyright__
<br>
</body>
</head>
</html>
