<html>
<head>
<meta id="viewport" name="viewport" content="width=320; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<title>会員管理 − ポイント履歴リスト</title>

<META http-equiv="Content-Type" content="text/html; charset=euc-jp">
<LINK REL="stylesheet" TYPE="text/css" HREF="style.css">
<script type="text/javascript" src="tools.js"></script>
</head>

<body>

<div class="content">
<h2 class="admin-title">会員管理 − 会員検索結果リスト</h2>
<br />

<form method="POST" action="user_detail.php" name="mainform">

<table class="common-list">
	<tr>
		<td nowrap class="common-list-title-left" style="border:0px;priority">
			総件数：__AllRows__ データ<br />
		</td>
		<td nowrap class="common-list-title-right" style="border:0px;priority">
			1ページ当たり：
			<select name="cRowsPerPageP" class="form" onChange="document.mainform.myPageP.value=1;javascript:move('point_list.php')">
__RowsPerPageLoop__			<option value="__RowsPerPage__" __RowsPerPageSelected__>__RowsPerPage__
__RowsPerPageLoop__			</select>データ
		</td>
	</tr>
</table>

<h2 class="navigation"><a href="#" onclick="javascript:move('user_list.php')">&lt;&lt;&lt; 会員リストへもどる</a></h2>
<h2 class="navigation"><a href="#" onclick="javascript:window.location.href='user_search.php?rKey=__rKey__'">&lt;&lt;&lt; 検索フォームへもどる</a></h2>
<h2 class="navigation"><a href="index.php">&lt;&lt;&lt; トップメニューへ</a></h2>
<br />

<table class="common-list" width="700">
	<tr>
		<td nowrap class="common-list-title">
			記録コード<br />
		</td>
		<td nowrap class="common-list-title">
			付与日時<br />
		</td>
		<td nowrap class="common-list-title">
			付与ポイント<br />
		</td>
		<td nowrap class="common-list-title">
			項目<br />
		</td>
<!--		<td nowrap class="common-list-title">
			付与者<br />
		</td>-->
		<td nowrap class="common-list-title">
			備考<br />
		</td>
	</tr>

__IfResults__
__PointListLoop__
	<tr>
		<td nowrap class="common-list-value">__LogCD__</td>
		<td nowrap class="common-list-value">__Created__</td>
		<td nowrap class="common-list-value">__Points__</td>
		<td nowrap class="common-list-value">__Classification__</td>
<!--		<td nowrap class="common-list-value">__Operator__</td>-->
		<td nowrap class="common-list-value-left">__Notes__</td>
	</tr>
__PointListLoop__
__IfResults__

__IfNoResults__
	<tr>
		<td nowrap class="common-list-value" colspan="8"><font color="red">該当する会員の履歴は見付かりませんでした。</font></td>
	</tr>
__IfNoResults__
	<tr>
		<td nowrap class="common-list-value" colspan="10">
			__IfToTop__<a href="#" onClick="javascript:changePage2('point_list.php', 1, __AllPages__)">__IfToTop__&lt;&lt;__IfToTop__</a>__IfToTop__　
			__IfToPre__<a href="#" onClick="javascript:changePage2('point_list.php', __PreviousPage__, __AllPages__)">__IfToPre__&lt;__IfToPre__</a>__IfToPre__　
			<input type="text" name="myPageP" value="__myPageP__" size="4" __IME_OFF__ class="form">/__AllPages__
			<input type="button" value="ページジャンプ" class="button" onclick="javascript:changePage2('point_list.php', '0', __AllPages__)">　
			__IfToNext__<a href="#" onClick="javascript:changePage2('point_list.php', __NextPage__, __AllPages__)">__IfToNext__&gt;__IfToNext__</a>__IfToNext__　
			__IfToLast__<a href="#" onClick="javascript:changePage2('point_list.php', __LastPage__, __AllPages__)">__IfToLast__&gt;&gt;__IfToLast__</a>__IfToLast__
			
		</td>
	</tr>
</table>

__HiddenValues__
<div class="footer-box">
	SoupPot! Demonstration 2005
</div>
</form>

</body>
</html>