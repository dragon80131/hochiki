<html>
<head>
<title>__Staff__管理 − __Staff__検索結果リスト</title>

<META http-equiv="Content-Type" content="text/html; charset=euc-jp">
<LINK REL="stylesheet" TYPE="text/css" HREF="style.css">
<script type="text/javascript" src="tools.js"></script>
__IfAjax__<script type="text/javascript" src="js/jquery-1.4.2.js"></script>
<script type="text/javascript" src="js/tools_ajax.js"></script>
__IfAjax__</head>

<body>

<div class="content">
<h2 class="admin-title">__Staff__管理 − __Staff__リスト__ExtraTitle__</h2>
<br />

__IfCreate__
<table class="common-list">
	<tr>
		<td nowrap class="common-list-value">
		『__wStylistName__』の新規登録を行いました。<br />
__IfDemo__		<font color="red">本システムはデモですので、実際には登録されていません。</font>
__IfDemo__		</td>
	</tr>
</table>
__IfCreate__

__IfUpdate__
<table class="common-list" width="700">
	<tr>
		<td nowrap class="common-list-value">
		『__wStylistName__』の情報更新を行いました。<br />
__IfDemo__		<font color="red">本システムはデモですので、実際には更新されていません。</font>
__IfDemo__		</td>
	</tr>
</table>
__IfUpdate__

__IfDelete__
<table class="common-list" width="700">
	<tr>
		<td nowrap class="common-list-value">
		『__wStylistName__』の削除を行いました。<br />
__IfDemo__		<font color="red">本システムはデモですので、実際には削除されていません。</font>
__IfDemo__		</td>
	</tr>
</table>
__IfDelete__

__IfDemo__
<table class="common-list">
	<tr>
		<td nowrap class="common-list-value">SoupPot! Ads ... <a href="http://souppot.jp/inquiry.php">本システムに関する簡単お問い合わせはこちらから</a></td>
	</tr>
</table>
__IfDemo__

__IfAdminSystem__<h2 class="navigation"><a href="#" onclick="javascript:move('client_list.php')">&lt;&lt;&lt; クライアントリストへ</a></h2>
__IfAdminSystem__<h2 class="navigation"><a href="index.php">&lt;&lt;&lt; トップメニューへ</a></h2>
<br />
<h2 class="navigation"><a href="#" onclick="javascript:moveWithStylistKey('stylist_detail.php', -1)">&gt;&gt;&gt; 新しい__Staff__を作成する</a></h2>
<h2 class="navigation"><a href="#" onclick="javascript:move('stylist_list.php')">&gt;&gt;&gt; 最新の情報に更新</a></h2>
<br />

<form method="POST" action="stylist_detail.php" name="mainform">

<table class="common-list">
	<tr>
		<td nowrap class="common-list-title-left" style="border:0px;priority">
			総件数：__AllRows__ データ<br />
		</td>
		<td nowrap class="common-list-title-right" style="border:0px;priority">
			1ページ当たり：
			<select name="cRowsPerPage" class="form" onChange="document.mainform.myPage.value=1;javascript:move('stylist_list.php')">
__RowsPerPageLoop__			<option value="__RowsPerPage__" __RowsPerPageSelected__>__RowsPerPage__
__RowsPerPageLoop__			</select>データ
		</td>
	</tr>
</table>

<table class="common-list" width="700" id="mainTable">
	<tr>
		<td nowrap class="common-list-title">__Staff__コード</td>
__IfClientName__		<td nowrap class="common-list-title">クライアント名</td>
__IfClientName__		<td nowrap class="common-list-title">__Staff__名称</td>
		<td nowrap class="common-list-title">稼動ライン数</td>

		<td nowrap class="common-list-title">詳細/編集</td>
		<td nowrap class="common-list-title">削除</td>
	</tr>

__IfResults__
__StylistListLoop__
	<tr class="common-list">
		<td nowrap class="common-list-value">__StylistCD__</td>
__IfClientNameArr__		<td nowrap  class="common-list-value">__ClientName__</td>
__IfClientNameArr__		<td nowrap class="common-list-value">__StylistName__</td>
		<td nowrap class="common-list-value">__NumberOfLines__</td>

		<td nowrap class="common-list-value"><a href="#" onclick="javascript:moveWithStylistKey('stylist_detail.php', __StylistCD__);">GO!</a></td>
		<td nowrap class="common-list-value"><a href="#" onclick="javascript:moveWithStylistKeyAlert('stylist_list.php', __StylistCD__, 2);">GO!</a></td>
	</tr>
__StylistListLoop__
__IfResults__

__IfNoResults__
	<tr>
		<td nowrap class="common-list-value" colspan="12"><font color="red">該当する__Staff__情報は見付かりませんでした。</font></td>
	</tr>
__IfNoResults__
	<tr>
		<td nowrap class="common-list-value" colspan="12">
			__IfToTop__<a href="#" onClick="javascript:changePage('stylist_list.php', 1, __AllPages__)">__IfToTop__&lt;&lt;__IfToTop__</a>__IfToTop__　
			__IfToPre__<a href="#" onClick="javascript:changePage('stylist_list.php', __PreviousPage__, __AllPages__)">__IfToPre__&lt;__IfToPre__</a>__IfToPre__　
			<input type="text" name="myPage" value="__myPage__" size="4" __IME_OFF__ class="form">/__AllPages__
			<input type="button" value="ページジャンプ" class="button" onclick="javascript:changePage('stylist_list.php', '0', __AllPages__)">　
			__IfToNext__<a href="#" onClick="javascript:changePage('stylist_list.php', __NextPage__, __AllPages__)">__IfToNext__&gt;__IfToNext__</a>__IfToNext__　
			__IfToLast__<a href="#" onClick="javascript:changePage('stylist_list.php', __LastPage__, __AllPages__)">__IfToLast__&gt;&gt;__IfToLast__</a>__IfToLast__
			
		</td>
	</tr>
</table>

__HiddenValues__
<div class="footer-box">
	__SAdminCopyright__
</div>
</form>

</body>
</html>