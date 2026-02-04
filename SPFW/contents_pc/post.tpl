<html>
<head>
<link rel="stylesheet" href="css/a5.css" type="text/css" />
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=sjis" />
<meta id="viewport" name="viewport" content="width=320; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<title>リニューアル支援</title>
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


<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">

<hr size="__HRSize__" color="__HRColor__">
<center>《現場調査写真アップロード》</center>
<hr size="__HRSize__" color="__HRColor__">
<br>

<table align=center><tr><td>


<ul>
<table><tr><td>

__IfOK2__
<br>画像をデータベースに保存しました。<br>
<a href='__image_path__' alt=''>__image_name__</a>
__IfOK2__

__IfNGUP__
<br>ファイル自体をサーバにアップできませんでした。
__IfNGUP__

__IfNGUP2__
<br>ファイルをサーバにアップできませんでした。
__IfNGUP2__

__IfNoFile__
<br>画像ファイルを選択してください。
__IfNoFile__

   <p> <a href=../s_pic.php__QUERY__&editBukkenCD=__BukkenCD__ >>>>現場写真へ戻る</a> </p>
   <p> <a href=../s_form.php__QUERY__&editBukkenCD=__BukkenCD__ >>>>物件情報へ戻る</a> </p>
<br>





</td></tr></table>
</ul>

</td></tr></table>

<hr size="__HRSize__" color="__HRColor__">


    </body>
    </html>


