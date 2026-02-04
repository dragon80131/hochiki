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

本当に削除しますか？</br>


<ul class="data">

<form action=p2Hd.php method=POST>

<table class="sampleTable" >

<tr>
__DLoop__
<td>

<a href=./view2.php?ID=__ID__&SekoStatus=__SekoStatus__&Device=__Device__&Eda=__Eda__ >
<img src=view.php?ID=__ID__&SekoStatus=__SekoStatus__&Device=__Device__&Eda=__Eda__ >

</td>
__DLoop__
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
