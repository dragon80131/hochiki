<html>
<head>
<title>営業活動支援システム</title>
<LINK REL="stylesheet" TYPE="text/css" HREF="./css/a7.css">

<script type="text/javascript" src="tools.js"></script>
<link href="css/jquery-ui-1.8.2.custom.css" rel="stylesheet" type="text/css" />
<script src="js/jquery-1.4.2.js" type="text/javascript"></script>
<script src="js/jquery.ui.core.js" type="text/javascript"></script>
<script type="text/javascript" src="js/tools_ajax.js"></script>

<script type="text/javascript" src="js/ConnectedSelect.js"></script>

</head>

<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
__SHeader__


<hr size="__HRSize__" color="__HRColor__">
<center>《現場写真》</center>
<hr size="__HRSize__" color="__HRColor__">

<a href="s_menu.php__QUERY__&editBukkenCD=__editBukkenCD__ ">メニュー</a><br>
<a href="s_form.php__QUERY__&editBukkenCD=__editBukkenCD__ ">物件情報</a>

__IfError__
 __ErrorLoop__
<font color="red" >__ErrorString__<br></font>
 __ErrorLoop__
__IfError__
<br>
ダウンロードする写真を選択し、ページ下の［ダウンロード］ボタンをクリックしてください。
__wBukkenName__

<table border=1>
<tr><td bgcolor="green"></td></tr>
<td><B>◆現調シート</B>　
</td>
<tr>
<td>


<form action="s_pic_download.php" method="POST" >

<table><tr>__PicGenchoLoop____PicGencho____PicGenchoLoop__</tr></table></td>
</tr>

<tr><td bgcolor="green"></td></tr>
<td><B>◆共用部</B>　
</td>
<tr>
<td><table><tr>__PicKyoyoLoop____PicKyoyo____PicKyoyoLoop__</tr></table></td>
</tr>

<tr><td bgcolor="skyblue"></td></tr>
<td><B>◆専有部</B>　
</td>
<tr>
<td><table><tr>__PicSenyuLoop____PicSenyu____PicSenyuLoop__</tr></table></td>
</tr>

<tr><td bgcolor="green"></td></tr>
<td><B>◆図面</B>　
</td>
<tr>
<td><table><tr>__PicZumenLoop____PicZumen____PicZumenLoop__</tr></table></td>
</tr>

<tr><td bgcolor="green"></td></tr>
<td><B>◆部屋番号</B>　
</td>
<tr>
<td><table><tr>__PicHeyaLoop____PicHeya____PicHeyaLoop__</tr></table></td>
</tr>

</table>


<br>
<input type=hidden name="editBukkenCD" value="__editBukkenCD__" >
<input type=hidden name="rKey" value="__rKey__">
<input type=submit value = "  ダウンロード  " >
</form>


<hr size="__HRSize__" color="__HRColor__">


<a href="s_menu.php__QUERY__&editBukkenCD=__editBukkenCD__ ">メニュー</a>
<br><br>
<a href="s_search.php__QUERY__">トップ</a>
<br><br>
<a href="logout.php__QUERY__">ログアウト</a>
<hr size="__HRSize__" color="__HRColor__">

__SFooter__

__SCopyright__
<br>
</body>

</html>
