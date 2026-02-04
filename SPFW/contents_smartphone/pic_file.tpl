<html>
<head>
<!--<link rel="stylesheet" href="css/a5.css" type="text/css" /> -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=sjis" />
<meta id="viewport" name="viewport" content="width=320; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<title>一括ダウンロード</title>
<link rel="stylesheet" href="../stylesheets/iphone.css" />
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

<hr size="__HRSize__" color="__HRColor__">
<center>《完成図書作成支援サービス》</center>
<hr size="__HRSize__" color="__HRColor__">
<br>

<table align=center><tr><td>

__MansionName__
<ul>
<table><tr><td>




__IfErr1__
ファイル書き込みに失敗しました<br>
__IfErr1__

__IfErr2__
ファイルロックに失敗しました<br>
__IfErr2__

__IfNoErr__
<br>ダウンロードの準備ができました。ダウンロードをクリックしてください。
<br><br><a href=./dl/image.zip>ダウンロード</a>

__IfNoErr__



<br>
<br>


<br><br>
<form action=../p2.php>
__HiddenValues__
<input type=submit value=" もどる " >
<br>
</td></tr></table>
</ul>

</td></tr></table>

<hr size="__HRSize__" color="__HRColor__">
NetServicePowerhouse 


</body>
</html>
