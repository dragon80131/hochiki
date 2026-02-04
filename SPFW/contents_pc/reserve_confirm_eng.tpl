<html>
<head>
<link rel="stylesheet" type="text/css" href="css/common.css">
<link rel="stylesheet" type="text/css" href="css/style_okyakusama.css">
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
<!--最小限のビューポート設定-->
<meta name="viewport" content="width=device-width">

<title>Confirmation of reservation</title>
</head>
<body>
<table>

<div id="navi">
<!--<a class="gengo" href="#.php">Englishi</a>-->
<a class="bar" href="top.php__QUERY__">HOME</a>
<a class="bar-migi" href="logout.php__QUERY__">Logout</a>
</div>

__SHeader__

<h1>__MansionName__</h1>
<h2>Room No __wID__</h2>


<h3>Confirmation of reservation</h3>

<div id="step">
<img src="./images/step3.png" alt="step">
</div>


<p>
If you are OK with the date below, please press the "Confirm reservation" button.<br>
If you want to change, press the "Previous Page" button.
</p>

<form method="post" action="reserve_finish.php?wLang=__wLang__&rKey=__rKey__"">

<!--▽いるやつ？
__IfDateError__<font color="red">ご希望の日付が正しくありません。</font><br>__IfDateError__
__IfHolidayError__<font color="red">ご希望の日付はお休みをいただいております。</font><br>__IfHolidayError__
__IfFullt__<font color="red">ご希望の時間は予約が埋まっております。他の時間をお選びください。</font><br>__IfFullt__
__IfError__<br>__IfError__
△いるやつ？-->


<h4>ご予約内容</h4>
<div class="block-formsaigo">
<table class="n-kakunin">
   <tr>
      <th scope="row">■予約日</th>
      <td>__wYear__年__wMonth__月__wDay__日(__weekday__)</td>
   </tr>
   <tr>
      <th scope="row">■時間帯</th>
      <td>__wOKTimeName__</td>
   </tr>
   <tr>
      <th scope="row">■第二希望</th>
      <td>__wSecondChoice__</td>
   </tr>
   <tr>
      <th scope="row">■ご要望</th>
      <td>__wwMemo__</td>
   </tr>
</table>
</div>

<div class="op-moushikomi">
<input type="hidden" name="ticket" value="__ticket__">
<input type="hidden" name="wMemo" value="__wMemo__">
<input type="hidden" name="wwMemo" value="__wwMemo__">
<input type="hidden" name="wSecondChoice" value="__wSecondChoice__">
<input type="hidden" name="MyMenuCD" value="__MyMenuCD__">
<input type="hidden" name="wTime" value="__wTime__">
<input type="hidden" name="wDate" value="__wDate__">
<input type="hidden" name="wID" value="__wID__">
<input type="hidden" name="work" value="1">
<input type="submit" name="submit_button" value="Confirm" class="finish-btn">
__HiddenValues__

</div>
</form>

<div class="tophe">
<form method="POST" action="reserve_form.php?wLang=__wLang__&rKey=__rKey__">
<input type="hidden" name="wMemo" value="__wMemo__">
<input type="hidden" name="wwMemo" value="__wwMemo__">
<input type="hidden" name="wSecondChoice" value="__wSecondChoice__">

<input type="hidden" name="MyMenuCD" value="__MyMenuCD__">
<input type="hidden" name="wTime" value="__wTime__">
<input type="hidden" name="wDate" value="__wDate__">
<input type="hidden" name="Correction" value="1">
<input type="hidden" name="wID" value="__wID__">
<input type="hidden" name="Syusei" value="1">
<input type="submit" value="Previous Page" class="tophe-btn">
__HiddenValues__

</form>
</div>


__SFooter__

__SCopyright__
</table>
</body>
</html>
