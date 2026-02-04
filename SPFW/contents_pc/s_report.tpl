<html>
<head>

<LINK REL="stylesheet" TYPE="text/css" HREF="./css/a5.css">
<script type="text/javascript" src="tools.js"></script>
__IfAjax__<script type="text/javascript" src="js/jquery-1.2.js"></script>
<script type="text/javascript" src="js/tools_ajax.js"></script>
__IfAjax__</head>


</head>
<title>リニューアル支援</title>
<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
__SHeader__
<hr size="__HRSize__" color="__HRColor__">
<center>《管理レポート》</center>
<hr size="__HRSize__" color="__HRColor__">
<br>




__IfAngle4__
<center>
__TodayMonth__月の折衝記録数<br>

<table border=1><tr style="color:#ffffff" bgcolor="#4169E1">
<tr><td>折衝記録数</td><td>担当者名</td></tr>
__TaioLoop__
<tr><td align=right >__CountTaioCD__</td><td>__LastName__</td></tr>
__TaioLoop__
</table>

</center>
__IfAngle4__


__IfAngle__



__IfSokai__ ◆　__TodayMonth60__月、__TodayMonth90__月決算の物件 __IfSokai__

<form action="s_form.php" method=POST name="mainform" >
<input type="hidden" name="SortKey" value="">
<input type="hidden" name="editBukkenCD" value="">
<input type="hidden" name="rKey" value="__rKey__">

<br>

	__IfToTop__<a href="#" onClick="javascript:changePage2('s_list.php', 1, __AllPages__)">__IfToTop__&lt;&lt;__IfToTop__</a>__IfToTop__　
	__IfToPre__<a href="#" onClick="javascript:changePage2('s_list.php', __PreviousPage__, __AllPages__)">__IfToPre__&lt;__IfToPre__</a>__IfToPre__　
	<input type="text" name="myPage" value="__myPage__" size="4" __IME_OFF__ class="form">/__AllPages__
	<input type="button" value="ページジャンプ" class="button" onclick="javascript:changePage2('s_list.php', '0', __AllPages__)">　
	__IfToNext__<a href="#" onClick="javascript:changePage2('s_list.php', __NextPage__, __AllPages__)">__IfToNext__&gt;__IfToNext__</a>__IfToNext__　
	__IfToLast__<a href="#" onClick="javascript:changePage2('s_list.php', __LastPage__, __AllPages__)">__IfToLast__&gt;&gt;__IfToLast__</a>__IfToLast__


<table border=1>
<tr style="color:#ffffff" bgcolor="#4169E1">
	<td>物件CD</td>
	<td>詳細</td>
	<td>物件名</td>
	<td>折衝フェーズ</td>
	<td>最終折衝日</td>
	__IfAngle3__
	<td>現場調査日</td>
	__IfAngle3__
	<td>決算月</td>
	<td>保留</td>
</tr>

</form>

__BukkenLoop__
<tr>
	<td>__BukkenCD__</td>
	<td><input type="button" value="詳細" class="button" onclick="javascript:moveWithKey('s_form.php', __BukkenCD__  )"</td-->
	<td>__BukkenName__</td>
	<td>__TaioPhase__</td>
	<td>__TaioUpdated__</td>
	__IfAngle32__
	<td>__ChosaDate__</td>
	__IfAngle32__
	<td>__Sokai__</td>
	<td>__BukkenStatus__</td>
</tr>
__BukkenLoop__
</tr>
</table>

__IfAngle__




__IfAngle6__
<center>
残工事のある物件一覧<br>
工事が完了し予約センターにて未工事部屋の日程調整を行う都度、情報は更新されます。

<table border=1>
<tr style="color:#ffffff" bgcolor="#4169E1">
<td>物件名</td>
<td>残部屋情報</td>
<td >管理会社</td>
<td >更新日時</td>
</tr>

__BukkenLoop__
<tr>
	<td><a href="./s_menu.php?rKey=__rKey__&editBukkenCD=__BukkenCD__">__BukkenName__</a></td>
	<td>__ZanHeya__</td>
	<td>__KanriGaisya__</td>
	<td>__Updated__</td>
</tr>
__BukkenLoop__
</table>

</center>
__IfAngle6__




<hr size="__HRSize__" color="__HRColor__">
<a href="#" onClick="history.back(); return false;">前に戻る</a><br>
<a href="s_search.php__QUERY__">トップ</a><br>
<hr size="__HRSize__" color="__HRColor__">

__SFooter__
__SCopyright__
</body>
</html>
