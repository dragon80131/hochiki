<html>
<head>

<LINK REL="stylesheet" TYPE="text/css" HREF="./css/a5.css">
<script type="text/javascript" src="tools.js"></script>
__IfAjax__<script type="text/javascript" src="js/jquery-1.2.js"></script>
<script type="text/javascript" src="js/tools_ajax.js"></script>
__IfAjax__</head>


</head>
<title>営業活動支援システム</title>
<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
__SHeader__
<hr size="__HRSize__" color="__HRColor__">
<center>《残工事のある物件一覧》</center>
<hr size="__HRSize__" color="__HRColor__">





[残工事をなしに更新]しますと[残工事のある物件一覧]から非表示となります。<br><br>

<form action="s_report_zan.php" name="mainform" method="POST" >
<input type=hidden name=editBukkenCD value="">
<input type=hidden name=rKey value="__rKey__">
<table border=1>
<tr><td  style="color:#ffffff" bgcolor="#4169E1">物件名</td>
	<td>__wBukkenName__</td>
</tr>

<tr><td style="color:#ffffff" bgcolor="#4169E1">残部屋完了</td>
	<td>

<input type="button" value="残工事なしに更新" class="button" onclick="javascript:moveWithKey('s_report_zan.php', __editBukkenCD__);">



	</td>
</tr>
</table>

<br>


</form>

<br>

<hr size="__HRSize__" color="__HRColor__">
<a href="#" onClick="history.back(); return false;">＜前に戻る</a><br>
<br>

__SFooter__
__SCopyright__
<br>
</body>
</html>
