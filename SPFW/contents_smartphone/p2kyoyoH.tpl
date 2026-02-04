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


<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">


<br>
<table align=center><tr><td>
__MansionName__<br><br>

・写真をクリックすると写真が拡大され確認できます。</br>
・削除したい写真の右のチェックを入れ、削除ボタンをクリックします。

<ul class="data">

<form action=p2kyoyod.php method=POST>

<table class="sampleTable" >

<tr bgcolor="#dce0f5" ><td>装置名</td><td colspan=2 >施工前</td>
__IfSekoChu__
<td colspan=2 >施工中</td>
__IfSekoChu__
<td colspan=2 >施工後</td></tr>


__DLoop__
<tr><td bgcolor="#dce0f5" >__DeviceName__</td>
    <td>__MaeDevice1__</td>
    <td>__MaeDevice2__</td>
__IfSekoChu2__
    <td>__ChuDevice1__</td>
    <td>__ChuDevice2__</td>
__IfSekoChu2__
    <td>__GoDevice1__</td>
    <td>__GoDevice2__</td>
</tr>
__DLoop__


</table>
</ul>
__HiddenValues__
<input type=submit value=" 削 除 " >

</form>

<br><br>
<form action=p2kyoyo.php>
__HiddenValues__
<input type=submit value=" もどる " >
</td></tr></table>

<hr size="__HRSize__" color="__HRColor__">
NetServicePowerhouse 
<br>
</body>

</html>
