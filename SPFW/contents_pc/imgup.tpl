<html>
<head>
<link rel="stylesheet" href="css/a6.css" type="text/css" /> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=sjis" />
<meta id="viewport" name="viewport" content="width=320; initial-scale=1.0; maximum-scale=1.0; " />

<!--<link rel="stylesheet" href="../stylesheets/iphone.css" /> -->
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
<title>リニューアル支援</title>
<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">

<hr size="__HRSize__" color="__HRColor__">
<center>《現場調査写真アップロード》</center>
<hr size="__HRSize__" color="__HRColor__">
<br>

__IfNoPic__
<font color=red > 写真ファイルが選択されていません。 </font>
__IfNoPic__

<table align=center><tr><td>


<ul>
<table><tr><td>
<font color="red"><b>
※「㈱、㈲、Ⅰ、Ⅱ、Ⅲ、①、②、㍉、℡」のような環境依存文字は文字化けしますので使用しないでください。※
</b></font>
<br><br>



<form action="post.php" enctype="multipart/form-data" method="post">
<!--カメラ起動-->
ファイルを選択し、【アップロード】ボタンをクリックしてください。<br>
<p><input id="file_button" type="file" name="photo" accept="image/*; capture=camera"  ></p>
<p>メモ：<input  type="text" name="wMemo" value=__wMemo__  ></p>
<!--縮小（0はリサイズしない）-->
<input type="hidden" name="resize" value="75">
__HiddenValues__
<p><input type="submit" value="アップロード" ></p>
</form>


</td></tr></table>
</ul>

</td></tr></table>

<hr size="__HRSize__" color="__HRColor__">

<br>
</body>
</html>


