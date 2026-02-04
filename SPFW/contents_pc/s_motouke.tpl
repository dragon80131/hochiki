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
<center>《元請け物件リスト》</center>
<hr size="__HRSize__" color="__HRColor__">


<form action="s_menu.php" name="mainform" method="POST" >
<input type="hidden" name="editBukkenCD" value="">
<input type="hidden" name="rKey" value="__rKey__">


３カ月経つと表示されません。
<br>

			__IfToTop__<a href="#" onClick="javascript:changePage2('s_search.php', 1, __AllPages__)">__IfToTop__&lt;&lt;__IfToTop__</a>__IfToTop__　
			__IfToPre__<a href="#" onClick="javascript:changePage2('s_search.php', __PreviousPage__, __AllPages__)">__IfToPre__&lt;__IfToPre__</a>__IfToPre__　
			<input type="text" name="myPage" value="__myPage__" size="4" __IME_OFF__ class="form">/__AllPages__
			<input type="button" value="ページジャンプ" class="button" onclick="javascript:changePage2('s_search.php', '0', __AllPages__)">　
			__IfToNext__<a href="#" onClick="javascript:changePage2('s_search.php', __NextPage__, __AllPages__)">__IfToNext__&gt;__IfToNext__</a>__IfToNext__　
			__IfToLast__<a href="#" onClick="javascript:changePage2('s_search.php', __LastPage__, __AllPages__)">__IfToLast__&gt;&gt;__IfToLast__</a>__IfToLast__
	
<table border=1><tr style="color:#ffffff" bgcolor="#4169E1">

<td>物件CD</td>
<td>物件CD489</td>
<td>物件名</td>
<td>依頼日</td>
<td>支店</td>
</tr>

__BukkenLoop__
<tr>
<td id="__BukkenCD__">__BukkenCD__</td>
<td >__BukkenCD489__</td>
<td><a href="#" onclick="javascript:moveWithKey('s_menu.php',__BukkenCD__ )">__BukkenName__</a></td>
<td>__IraiDate__</td>
<td>__ShozokuName__</td>
</tr>
__BukkenLoop__

</tr></table>


</form>



<hr size="__HRSize__" color="__HRColor__">
<a href="s_search.php__QUERY__">トップ</a><br>
<a href="logout.php__QUERY__">ログアウト</a><br>


<hr size="__HRSize__" color="__HRColor__">

__SFooter__

__SCopyright__

</td></tr>
</table>

<br>
</body>
</html>
