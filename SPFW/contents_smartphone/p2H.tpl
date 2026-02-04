<html>
<head>
<link rel="stylesheet" href="css/a5.css" type="text/css" />
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=sjis" />
<meta id="viewport" name="viewport" content="width=320; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />

<!--<link rel="stylesheet" href="stylesheets/iphone.css" />-->
<link rel="apple-touch-icon" href="images/apple-touch-icon.png" />
<script type="text/javascript">
function clickclear(thisfield, defaulttext) {
if (thisfield.value == defaulttext) {
thisfield.value = "";
}
}
function clickrecall(thisfield, defaulttext) {
if (thisfield.value == "") {
thisfield.value = defaulttext;
}
}
</script>

<script type="text/javascript" charset="utf-8">
window.onload = function() {
setTimeout(function(){window.scrollTo(0, 1);}, 100);
}
</script>
</head>

<title>現場写真確認</title>
<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">

<hr size="__HRSize__" color="__HRColor__">
<center>《写真台帳作成サービス》</center>
<hr size="__HRSize__" color="__HRColor__">
<br>

<table align=center><tr><td>
__MansionName__</br></br>

・写真をクリックすると写真が拡大され確認できます。</br>
・削除したい写真の右のチェックを入れ、削除ボタンをクリックします。

<ul class="data">

<form action="p2d.php" method="POST">

<table class="sampleTable" >

<tr>
<td rowspan=2 >部屋</td>
<td colspan=4 align=center >室内親機</td>
<td colspan=4 align=center >玄関子機</td>
__OpLoop__
<td  colspan=2 align=center >__OpName__</td>
__OpLoop__



</tr>
<tr>
　　<td colspan=2 align=center >施工前</td><td colspan=2 align=center >施工後</td>
　　<td colspan=2 align=center >施工前</td><td colspan=2 align=center >施工後</td>
__OpLoop__
<td colspan=2 align=center >__OpStatus__</td>
__OpLoop__
</tr>

<tr>
  <td><center>__ID__</center></td>
  <td>__MaeOya__</td><td>__MaeOya2__</td><td>__GoOya__</td><td>__GoOya2__</td>
  <td>__MaeKo__</td><td>__MaeKo2__</td><td>__GoKo__</td><td>__GoKo2__</td>

  __IfOp3__<td>__Op3__</td><td>__Op32__</td>__IfOp3__ 
  __IfOp4__<td>__Op4__</td><td>__Op42__</td>__IfOp4__
  __IfOp5__<td>__Op5__</td><td>__Op52__</td>__IfOp5__
  __IfOp6__<td>__Op6__</td><td>__Op62__</td>__IfOp6__
  __IfOp7__<td>__Op7__</td><td>__Op72__</td>__IfOp7__
  __IfOp8__<td>__Op8__</td><td>__Op82__</td>__IfOp8__

</tr>

</table>
</ul>
__HiddenValues__
<input type=hidden name=ID value=__ID__>
<input type=submit value=" 削 除 ">
</form>
<br><br>
<INPUT type="button" value=" もどる "
onclick="history.back()">
</td></tr></table>

</td></tr></table>

<hr size="__HRSize__" color="__HRColor__">
NetServicePowerhouse 
<br>
</body>

</html>
