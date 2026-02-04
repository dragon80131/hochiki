<html>
<head>

<LINK REL="stylesheet" TYPE="text/css" HREF="./css/a5.css">
<script type="text/javascript" src="tools.js"></script>
<script type="text/javascript" src="js/jquery-1.4.2.js"></script>
<script type="text/javascript" src="js/tools_ajax.js"></script>
</head>
<title>営業活動支援システム</title>
<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
__SHeader__
<hr size="__HRSize__" color="__HRColor__">
<center>《折衝記録まとめ》</center>
<hr size="__HRSize__" color="__HRColor__">
<a href="s_search.php__QUERY__">＜＜トップ</a><br>
<br>

<form method="POST" action="s_taio_matome.php?SortBy=1&Order=__Order__&rKey=__rKey__" name="mainform">

<b>__sitenStr__</b><br>
<input type="button" value="札幌　　" class="button" onclick="javascript:changePage('s_matome_taio.php?SortBy=6&Order=2&siten=1&rKey=__rKey__', '0', __AllPages__)">
<input type="button" value="東北　　" class="button" onclick="javascript:changePage('s_matome_taio.php?SortBy=6&Order=2&siten=2&rKey=__rKey__', '0', __AllPages__)">
<input type="button" value="北関東　" class="button" onclick="javascript:changePage('s_matome_taio.php?SortBy=6&Order=2&siten=3&rKey=__rKey__', '0', __AllPages__)">
<input type="button" value="東京　　" class="button" onclick="javascript:changePage('s_matome_taio.php?SortBy=6&Order=2&siten=4&rKey=__rKey__', '0', __AllPages__)">
<input type="button" value="横浜　　" class="button" onclick="javascript:changePage('s_matome_taio.php?SortBy=6&Order=2&siten=5&rKey=__rKey__', '0', __AllPages__)">
<input type="button" value="名古屋　" class="button" onclick="javascript:changePage('s_matome_taio.php?SortBy=6&Order=2&siten=6&rKey=__rKey__', '0', __AllPages__)">
<input type="button" value="大阪　　" class="button" onclick="javascript:changePage('s_matome_taio.php?SortBy=6&Order=2&siten=7&rKey=__rKey__', '0', __AllPages__)">
<input type="button" value="中・四国" class="button" onclick="javascript:changePage('s_matome_taio.php?SortBy=6&Order=2&siten=8&rKey=__rKey__', '0', __AllPages__)">
<input type="button" value="九州　　" class="button" onclick="javascript:changePage('s_matome_taio.php?SortBy=6&Order=2&siten=9&rKey=__rKey__', '0', __AllPages__)">
　<input type="button" value="全国　" class="button" onclick="javascript:changePage('s_matome_taio.php?SortBy=6&Order=2&rKey=__rKey__', '0', __AllPages__)">
<br><br>

<table>
<tr><td colspan=5 bgcolor="sandybrown"><b>2016/1/12　〜　__Today__　までの受付実績/システム利用実績</b></td></tr>
<tr bgcolor="lightgrey">
	<td>支店　　</td>
	<td>システム利用日から<br>登録・更新された物件数</td>
	<td>システム利用日から<br>新規登録された物件数</td>
	<td>折衝記録登録物件数</td>
	<td>写真撮影物件数</td>
</tr>
<tr><td>札幌　　</td><td>__sitenUpBukkenCnt1__</td><td>__sitenNewBukkenCnt1__</td><td>__sitenTaioCnt1__</td><td>__sitenPicCnt1__</td></tr>
<tr><td>東北　　</td><td>__sitenUpBukkenCnt2__</td><td>__sitenNewBukkenCnt2__</td><td>__sitenTaioCnt2__</td><td>__sitenPicCnt2__</td></tr>
<tr><td>北関東　</td><td>__sitenUpBukkenCnt3__</td><td>__sitenNewBukkenCnt3__</td><td>__sitenTaioCnt3__</td><td>__sitenPicCnt3__</td></tr>
<tr><td>東京　　</td><td>__sitenUpBukkenCnt4__</td><td>__sitenNewBukkenCnt4__</td><td>__sitenTaioCnt4__</td><td>__sitenPicCnt4__</td></tr>
<tr><td>横浜　　</td><td>__sitenUpBukkenCnt5__</td><td>__sitenNewBukkenCnt5__</td><td>__sitenTaioCnt5__</td><td>__sitenPicCnt5__</td></tr>
<tr><td>名古屋　</td><td>__sitenUpBukkenCnt6__</td><td>__sitenNewBukkenCnt6__</td><td>__sitenTaioCnt6__</td><td>__sitenPicCnt6__</td></tr>
<tr><td>大阪　　</td><td>__sitenUpBukkenCnt7__</td><td>__sitenNewBukkenCnt7__</td><td>__sitenTaioCnt7__</td><td>__sitenPicCnt7__</td></tr>
<tr><td>中・四国</td><td>__sitenUpBukkenCnt8__</td><td>__sitenNewBukkenCnt8__</td><td>__sitenTaioCnt8__</td><td>__sitenPicCnt8__</td></tr>
<tr><td>九州　　</td><td>__sitenUpBukkenCnt9__</td><td>__sitenNewBukkenCnt9__</td><td>__sitenTaioCnt9__</td><td>__sitenPicCnt9__</td></tr>
<tr><td colspan=5 bgcolor="red"></td></tr>
<tr	bgcolor="lightgoldenrodyellow"><td>支店合計</td><td>__UpCnt__</td><td>__NewCnt__</td><td>__TaioCnt__</td><td>__PicCnt__</td></tr>
</table>



<br>
ソート順：<b>__SortStr__</b><br>

<table>
	<tr>
		<td nowrap class="common-list-title-left" style="border:0px;priority">
			総件数：__AllRows__ データ<br />
		</td>
		<td nowrap class="common-list-title-right" style="border:0px;priority">
			1ページ当たり：
			<select name="cRowsPerPage" class="form" onChange="document.mainform.myPage.value=1;javascript:move('s_matome_taio.php?SortBy=1&Order=__Order__&rKey=__rKey__')">
__RowsPerPageLoop__			<option value="__RowsPerPage__" __RowsPerPageSelected__>__RowsPerPage__
__RowsPerPageLoop__			</select>データ
		</td>
	</tr>
</table>

<table border=1>
	<tr bgcolor="lightgrey"><td colspan=11><b>動きのあった物件一覧 ※2016/1/12　〜　__Today__　までの実績</b>　※並び替えは全国の物件でしか並び替えできません。支店ごとの並び替えはできません）</td></tr>
	<tr bgcolor="lightgrey">
		<td></td>
		<td><!--BukkenCD-->
			<input type="button" value="昇順▼" class="button" onclick="javascript:changePage('s_matome_taio.php?SortBy=1&Order=1&rKey=__rKey__', '0', __AllPages__)"><br>
			<input type="button" value="降順▼" class="button" onclick="javascript:changePage('s_matome_taio.php?SortBy=1&Order=2&rKey=__rKey__', '0', __AllPages__)"></td>
		<td><!--BukkenName-->
			<!--
			<input type="button" value="昇順▼" class="button" onclick="javascript:changePage('s_matome_taio.php?SortBy=2&Order=1&rKey=__rKey__', '0', __AllPages__)"><br>
			<input type="button" value="降順▼" class="button" onclick="javascript:changePage('s_matome_taio.php?SortBy=2&Order=2&rKey=__rKey__', '0', __AllPages__)">--></td>
		<td><!--所属名--></td>
		<!--MukouFlg-->
		<!--
		<td>
			<input type="button" value="昇順▼" class="button" onclick="javascript:changePage('s_matome_taio.php?SortBy=3&Order=1&rKey=__rKey__', '0', __AllPages__)"><br>
			<input type="button" value="降順▼" class="button" onclick="javascript:changePage('s_matome_taio.php?SortBy=3&Order=2&rKey=__rKey__', '0', __AllPages__)"></td>
		-->
		<td><!--Created-->
			<input type="button" value="昇順▼" class="button" onclick="javascript:changePage('s_matome_taio.php?SortBy=4&Order=1&rKey=__rKey__', '0', __AllPages__)"><br>
			<input type="button" value="降順▼" class="button" onclick="javascript:changePage('s_matome_taio.php?SortBy=4&Order=2&rKey=__rKey__', '0', __AllPages__)"></td>
		<td><!--Creator-->
			<!--
			<input type="button" value="昇順▼" class="button" onclick="javascript:changePage('s_matome_taio.php?SortBy=5&Order=1&rKey=__rKey__', '0', __AllPages__)"><br>
			<input type="button" value="降順▼" class="button" onclick="javascript:changePage('s_matome_taio.php?SortBy=5&Order=2&rKey=__rKey__', '0', __AllPages__)">--></td>
		<td><!--Updated-->
			<input type="button" value="昇順▼" class="button" onclick="javascript:changePage('s_matome_taio.php?SortBy=6&Order=1&rKey=__rKey__', '0', __AllPages__)"><br>
			<input type="button" value="降順▼" class="button" onclick="javascript:changePage('s_matome_taio.php?SortBy=6&Order=2&rKey=__rKey__', '0', __AllPages__)"></td>
		<td><!--Updater-->
			<!--
			<input type="button" value="昇順▼" class="button" onclick="javascript:changePage('s_matome_taio.php?SortBy=7&Order=1&rKey=__rKey__', '0', __AllPages__)"><br>
			<input type="button" value="降順▼" class="button" onclick="javascript:changePage('s_matome_taio.php?SortBy=7&Order=2&rKey=__rKey__', '0', __AllPages__)">--></td>
		<td><!--写真--></td>
		<td><!--折衝--></td>
		<td><!--現調者--></td>
	</tr>
	<tr bgcolor="lightgrey">
		<td></td>
		<td>BukkenCD</td>
		<td>BukkenName</td>
		<td>所属名</td>
		<!--
		<td>MukouFlg</td>-->
		<td>Created</td>
		<td>Creator</td>
		<td>Updated</td>
		<td>Updater</td>
		<td>写真</td>
		<td>折衝</td>
		<td>現調者</td>
	</tr>

__IfResults__
__BukkenListLoop__
	<tr bgcolor=__upcolor__>
		<td>__No__</td>
		<td>__BukkenCD__</td>
		<td>__BukkenName__</td>
		<td>__ShozokuName__</td>
		<!--
		<td>__MukouFlg__</td>-->
		<td>__Created__</td>
		<td>__Creator__ __LastName__</td>
		<td>__Updated__</td>
		<td>__Updater__</td>
		<td>__tpic__</td>
		<td>__taio__</td>
		<td>__Researcher__</td>
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
			__IfToTop__<a href="#" onClick="javascript:changePage('s_matome_taio.php?SortBy=__SortBy__&Order=__Order__&rKey=__rKey__', 1, __AllPages__)">__IfToTop__&lt;&lt;__IfToTop__</a>__IfToTop__　
			__IfToPre__<a href="#" onClick="javascript:changePage('s_matome_taio.php?SortBy=__SortBy__&Order=__Order__&rKey=__rKey__', __PreviousPage__, __AllPages__)">__IfToPre__&lt;__IfToPre__</a>__IfToPre__　
			<input type="text" name="myPage" value="__myPage__" size="4" __IME_OFF__ class="form">/__AllPages__
			<input type="button" value="ページジャンプ" class="button" onclick="javascript:changePage('s_matome_taio.php?SortBy=__SortBy__&Order=__Order__&rKey=__rKey__', '0', __AllPages__)">　
			__IfToNext__<a href="#" onClick="javascript:changePage('s_matome_taio.php?SortBy=__SortBy__&Order=__Order__&rKey=__rKey__', __NextPage__, __AllPages__)">__IfToNext__&gt;__IfToNext__</a>__IfToNext__　
			__IfToLast__<a href="#" onClick="javascript:changePage('s_matome_taio.php?SortBy=__SortBy__&Order=__Order__&rKey=__rKey__', __LastPage__, __AllPages__)">__IfToLast__&gt;&gt;__IfToLast__</a>__IfToLast__
			
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
