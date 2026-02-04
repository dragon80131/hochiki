<html>
<head>

<LINK REL="stylesheet" TYPE="text/css" HREF="./css/a5.css">
<script type="text/javascript" src="tools.js"></script>
<script type="text/javascript" src="js/jquery-1.4.2.js"></script>
<script type="text/javascript" src="js/tools_ajax.js"></script>
</head>
<title>リニューアル支援システム</title>
<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
__SHeader__
<hr size="__HRSize__" color="__HRColor__">
<center>《489依頼物件一覧》</center>
<hr size="__HRSize__" color="__HRColor__">
<a href="s_search.php__QUERY__">＜＜トップ</a>
<br><br>

<form method="POST" action="s_matome_irai.php?rKey=__rKey__" name="mainform">
<table>
	<tr>
		<td nowrap class="common-list-title-left" style="border:0px;priority">
			総件数：__AllRows__ データ<br />
		</td>
		<td nowrap class="common-list-title-right" style="border:0px;priority">
			1ページ当たり：
			<select name="cRowsPerPage" class="form" onChange="document.mainform.myPage.value=1;javascript:move('s_matome_irai.php?SortBy=1&Order=__Order__&rKey=__rKey__')">
__RowsPerPageLoop__			<option value="__RowsPerPage__" __RowsPerPageSelected__>__RowsPerPage__
__RowsPerPageLoop__			</select>データ
		</td>
	</tr>
</table>

<table border=1>
	<tr bgcolor="lightgrey">
		<td></td>
		<td>BukkenCD</td>
		<td>物件名</td>
		<td>件名No</td>
		<td>489物件番号</td>
		<td>所属名</td>
		<td>依頼日<font size="2"><br>（入力値）</font></td>
		<td>初回依頼日時<font size="2"><br>（Created）</font></td>
		<td>予定案内提供日<font size="2"><br>（入力値）</font></td>
		<td>受付開始日<font size="2"><br>（入力値）</font></td>
		<td>依頼データ更新日<font size="2"><br>（IraiUpdated）</font></td>
		<td>依頼履歴</td>
	</tr>

__IfResults__
__BukkenListLoop__
	<tr bgcolor=__upcolor__>
		<td>__No__</td>
		<td>__BukkenCD__</td>
		<td>__BukkenName__</td>
		<td>__KenmeiNo__</td>
		<td>__BukkenCD489__</td>
		<td>__ShozokuName__</td>
		<td>__IraiDate__</td>
		<td>__Created__</td>
		<td bgcolor="__YoteTekyoDateColor__">__YoteTekyoDate__</td>
		<td bgcolor="__YoteDateColor__">__YoteDate__</td>
		<td>__IraiUpdated__</td>
		<td><font size="2">__IraiHistory__</font></td>
	</tr>
__BukkenListLoop__
__IfResults__

__IfNoResults__
	<tr>
		<td nowrap class="common-list-value" colspan="12"><font color="red">該当する__Menu__情報は見付かりませんでした。</font></td>
	</tr>
__IfNoResults__
	<tr>
		<td nowrap class="common-list-value" colspan="12">
			__IfToTop__<a href="#" onClick="javascript:changePage('s_matome_irai.php?SortBy=__SortBy__&Order=__Order__&rKey=__rKey__', 1, __AllPages__)">__IfToTop__&lt;&lt;__IfToTop__</a>__IfToTop__　
			__IfToPre__<a href="#" onClick="javascript:changePage('s_matome_irai.php?SortBy=__SortBy__&Order=__Order__&rKey=__rKey__', __PreviousPage__, __AllPages__)">__IfToPre__&lt;__IfToPre__</a>__IfToPre__　
			<input type="text" name="myPage" value="__myPage__" size="4" __IME_OFF__ class="form">/__AllPages__
			<input type="button" value="ページジャンプ" class="button" onclick="javascript:changePage('s_matome_irai.php?SortBy=__SortBy__&Order=__Order__&rKey=__rKey__', '0', __AllPages__)">　
			__IfToNext__<a href="#" onClick="javascript:changePage('s_matome_irai.php?SortBy=__SortBy__&Order=__Order__&rKey=__rKey__', __NextPage__, __AllPages__)">__IfToNext__&gt;__IfToNext__</a>__IfToNext__　
			__IfToLast__<a href="#" onClick="javascript:changePage('s_matome_irai.php?SortBy=__SortBy__&Order=__Order__&rKey=__rKey__', __LastPage__, __AllPages__)">__IfToLast__&gt;&gt;__IfToLast__</a>__IfToLast__
			
		</td>
	</tr>
</table>


<br>






</form>

<hr size="__HRSize__" color="__HRColor__">
<a href="s_search.php__QUERY__">＜＜トップ</a><br>
<br>
<!--<a href="logout.php__QUERY__">ログアウト</a>-->

__SFooter__
<hr size="__HRSize__" color="__HRColor__">
__SCopyright__
<br>
</body>
</html>
