<html>
<head>
<title>スマート工事くん</title>
<LINK REL="stylesheet" TYPE="text/css" HREF="./css/a5.css">
<script type="text/javascript" src="tools.js"></script>
__IfAjax__<script type="text/javascript" src="js/jquery-1.2.js"></script>
<script type="text/javascript" src="js/tools_ajax.js"></script>
__IfAjax__
</head>

<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
__SHeader__
<hr size="__HRSize__" color="__HRColor__">
<center>《メニュー》</center>
<hr size="__HRSize__" color="__HRColor__">


__BukkenName__
<br>

<form action="seko_form_print.php" method="POST" name="mainform" >
<input type=hidden name="rKey" value="__rKey__" >

<table border="1" width="350">
<tr bgcolor="lightgrey"><td colspan="2">○物件情報</td></tr>
<tr><td bgcolor="lightgrey"></td>
	<td><a href="#" onclick="javascript:move('seko_form_print.php?editBukkenCD=__editBukkenCD__')">物件情報</a><a href="#" onclick="javascript:move('seko_smart_list.php?BukkenCD489=__BukkenCD489__')"><font color=white>.</font></a></td></tr>
<tr><td bgcolor="lightgrey"></td>
	<td><a href="#" onclick="javascript:move('seko_pic.php?editBukkenCD=__editBukkenCD__')">現場写真</a></td></tr>

<tr bgcolor="lightgrey"><td colspan="2">○工事情報</td></tr>
__Ifportal__
<tr><td bgcolor="lightgrey"></td>
	<td><a href="https://www2.489501.jp/__BukkenCD489__/" target="_blank" >ポータルサイト</a></td></tr>

<tr><td bgcolor="lightgrey"></td>
		<td><a href="#" onclick="javascript:move('seko_489kotei.php?BukkenCD489=__BukkenCD489__')">作業工程表</a></td></tr>


__Ifportal__
<tr><td bgcolor="lightgrey"></td>
	<td><a href="#" onclick="javascript:move('seko_489.php?editBukkenCD=__editBukkenCD__')">工事指示</a></td></tr>

__IfZanRenkei__
<tr bgcolor="lightgrey"><td colspan="2">○工事終了情報</td></tr>
__IfZan__
<tr><td bgcolor="lightgrey"></td>
	<td><a href="#" onclick="javascript:move('seko_zan.php?editBukkenCD=__editBukkenCD__')">残工事</a></td></tr>
__IfZan__

__IfNoZan__
<tr><td bgcolor="lightgrey"></td>
	<td>残工事なし</td></tr>
__IfNoZan__


__IfKanseitosho__
<tr><td bgcolor="lightgrey"></td>
	<td><a href="#" onclick="javascript:move('seko_kan.php?editBukkenCD=__editBukkenCD__')">写真台帳</a></td></tr>
__IfKanseitosho__

__IfZanRenkei__

</table>

</form>



<hr size="__HRSize__" color="__HRColor__">
<a href="seko_report.php__QUERY__">トップ</a><br><br>
<a href="logout.php__QUERY__">ログアウト</a><br>
<hr size="__HRSize__" color="__HRColor__">
__SFooter__
__SCopyright__
</body>
</html>
