<html>
<head>
<LINK REL="stylesheet" TYPE="text/css" HREF="./css/a5.css">
<script type="text/javascript" src="tools.js"></script>
__IfAjax__<script type="text/javascript" src="js/jquery-1.2.js"></script>
<script type="text/javascript" src="js/tools_ajax.js"></script>
__IfAjax__</head>
<title>営業活動支援システム</title>
</head>

<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
__SHeader__
<hr size="__HRSize__" color="__HRColor__">
<center>《管理レポート》</center>
<hr size="__HRSize__" color="__HRColor__">
<br>

本日から30日前：__Today30__<br>

<form action="s_form.php" method=POST name="mainform" >
<input type="hidden" name="SortKey" value="">
<input type="hidden" name="editBukkenCD" value="">
<input type="hidden" name="rKey" value="__rKey__">

<br>

	__IfToTop__<a href="#" onClick="javascript:changePage('s_report_nomove.php', 1, __AllPages__)">__IfToTop__&lt;&lt;__IfToTop__</a>__IfToTop__　
	__IfToPre__<a href="#" onClick="javascript:changePage('s_report_nomove.php', __PreviousPage__, __AllPages__)">__IfToPre__&lt;__IfToPre__</a>__IfToPre__　
	<input type="text" name="myPage" value="__myPage__" size="4" __IME_OFF__ class="form">/__AllPages__
	<input type="button" value="ページジャンプ" class="button" onclick="javascript:changePage('s_report_nomove.php', '0', __AllPages__)">　
	__IfToNext__<a href="#" onClick="javascript:changePage('s_report_nomove.php', __NextPage__, __AllPages__)">__IfToNext__&gt;__IfToNext__</a>__IfToNext__　
	__IfToLast__<a href="#" onClick="javascript:changePage('s_report_nomove.php', __LastPage__, __AllPages__)">__IfToLast__&gt;&gt;__IfToLast__</a>__IfToLast__

</form>

<table border=1><tr style="color:#ffffff" bgcolor="#4169E1">
<td>物件CD</td>
<td>詳細</td>
<td>物件名</td>
<td>折衝フェーズ</td>
<td>最終折衝日</td>
<td>入力日</td>
</tr>

__BukkenLoop__
<tr>
<td>__BukkenCD__</td>
<td><input type="button" value="詳細" class="button" onclick="javascript:moveWithKey('s_form.php', __BukkenCD__  )"</td-->
<td>__BukkenName__</td>
<td>__TaioPhase__</td>
<td bgcolor="__nomovecolor__">__TaioDate__</td>
<td>__TaioUpdated__</td>
</tr>
__BukkenLoop__
</tr></table>





<hr size="__HRSize__" color="__HRColor__">
<a href="#" onClick="history.back(); return false;">前に戻る</a><br>
<a href="s_search.php__QUERY__">トップ</a><br>
<br>

__SFooter__
__SCopyright__
</body>
</html>
