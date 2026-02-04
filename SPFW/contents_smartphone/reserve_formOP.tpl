<html>
<head>
<title>オプション申し込み</title>
</head>
<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
__SHeader__
<hr size="__HRSize__" color="__HRColor__">
<center>《オプション申し込み》</center>
<hr size="__HRSize__" color="__HRColor__">

<form method="post" action="reserve_infoOP.php">

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



現在のオプション申込内容

<table align="center"><tr><td align="left" width=400>

<br>
__Ifmenu1____pMenuNameA1__<br>__Ifmenu1__
__Ifmenu2____pMenuNameA2__<br>__Ifmenu2__
__Ifmenu3____pMenuNameA3__ <br>__Ifmenu3__

__Ifmenu4__ __pMenuNameA4__   <br>__Ifmenu4__

__Ifmenu5____pMenuNameA5__<br>__Ifmenu5__
__Ifmenu6____pMenuNameA6__<br>__Ifmenu6__
__Ifmenu7____pMenuNameA7__<br>__Ifmenu7__


<br><font color=#FF0000 >
キャンセルする場合は、下記チェック欄を空欄のまま、「申込む」をクリックしてください。</font>
<br>
<hr size="__HRSize__" color="__HRColor__"><br>
オプション申し込みは、以下の希望のオプションにチェックをいれ、
申し込むボタンをクリックしてください。<br>

<input type="hidden" name="wMenuCD[]" value="1" Checked>
__Ifmenu1__<input type="checkbox" name="wMenuCD[]" value="2"__MenuChecked__>__MenuNameA1__<br>__Ifmenu1__
__Ifmenu2__<input type="checkbox" name="wMenuCD[]" value="3"__MenuChecked__>__MenuNameA2__<br>__Ifmenu2__
__Ifmenu3__<input type="checkbox" name="wMenuCD[]" value="4"__MenuChecked__>__MenuNameA3__<br>__Ifmenu3__
__Ifmenu4__<input type="checkbox" name="wMenuCD[]" value="5"__MenuChecked__>__MenuNameA4__<br>__Ifmenu4__
__Ifmenu5__<input type="checkbox" name="wMenuCD[]" value="6"__MenuChecked__>__MenuNameA5__<br>__Ifmenu5__
__Ifmenu6__<input type="checkbox" name="wMenuCD[]" value="7"__MenuChecked__>__MenuNameA6__<br>__Ifmenu6__
__Ifmenu7__<input type="checkbox" name="wMenuCD[]" value="8"__MenuChecked__>__MenuNameA7__<br>__Ifmenu7__

__IfKey2__<input type=text name="wR002" cols="30" rows="1" style="width:50px"  class="form">本<br />__IfKey2__
<br>
<input type="hidden" name="wTempOKTimesName" value="__wTempOKTimesName__">


</td></tr></table>

<input type="submit" value=" 申し込む ">

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
