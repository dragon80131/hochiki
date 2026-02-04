<html>
<head>
<title>営業活動支援システム</title>
<LINK REL="stylesheet" TYPE="text/css" HREF="./css/a5.css">

<link href="d2b/css/dropzone.css" type="text/css" rel="stylesheet" />
<script src="d2b/dropzone.min.js"></script>

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
<center>《現場写真 削除画面》</center>
<hr size="__HRSize__" color="__HRColor__">

<a href="s_pic.php__QUERY__&editBukkenCD=__editBukkenCD__ ">＜＜現場写真</a><br>

__IfError__
 __ErrorLoop__
<font color="red" >__ErrorString__<br></font>
 __ErrorLoop__
__IfError__


<table class="sampleTable">
	<tr><td bgcolor="#e3f0fb">物件CD</td><td>__editBukkenCD__</td>
		<td bgcolor="#e3f0fb">物件名</td><td>__wBukkenName__</td></tr>
</table>
<br>

<b>
<font size="5" color="red">＜＜写真削除用ページです。＞＞</font><br>
<font size="4">削除したい写真にチェックを入れ、ページ下部の[写真削除]ボタンを押してください。</font></b><br><br>

<form action="s_picdelete.php" method="POST" name="mainform">
<table border=1>
<tr><td bgcolor="green"></td></tr>
<tr><td><B>◆現調シート</B>　
		<table><tr>__PicGenchoLoop____PicGencho____PicGenchoLoop__</tr></table>
	</td>
</tr>

<tr><td bgcolor="green"></td></tr>
<tr><td><font size="4"><B>◆共用部</B></font>　
		<table><tr>__PicKyoyoLoop____PicKyoyo____PicKyoyoLoop__</tr></table>
	</td>
</tr>

<tr><td bgcolor="skyblue"></td></tr>
<tr><td><font size="4"><B>◆専有部</B></font>　
		<table><tr>__PicSenyuLoop____PicSenyu____PicSenyuLoop__</tr></table>
	</td>
</tr>

<tr><td bgcolor="green"></td></tr>
<tr><td><font size="4"><B>◆図面</B></font>　
		<table><tr>__PicZumenLoop____PicZumen____PicZumenLoop__</tr></table>
	</td>
</tr>

<tr><td bgcolor="green"></td></tr>
<tr><td><font size="4"><B>◆部屋番号</B></font>　
		<table><tr>__PicHeyaLoop____PicHeya____PicHeyaLoop__</tr></table>
	</td>
</tr>

</table>


<br>
<input type=hidden name="editBukkenCD" value="__editBukkenCD__" >
<input type=hidden name="rKey" value="__rKey__">
<input type="hidden" name="PicDelete" value=1 >
削除したい写真にチェックをいれ、［写真削除］ボタンをクリックしてください。<br>
<input type=submit value = "  写真削除  " ><font color="red">※すぐに削除されます。</font>
</form>
<br>


<hr size="__HRSize__" color="__HRColor__">

<hr size="__HRSize__" color="__HRColor__">
</body>
</html>
