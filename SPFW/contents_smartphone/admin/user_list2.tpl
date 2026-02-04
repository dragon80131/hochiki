<html>
<head>
<title>部屋番号管理 − 部屋番号検索結果リスト</title>

<META http-equiv="Content-Type" content="text/html; charset=euc-jp">
<LINK REL="stylesheet" TYPE="text/css" HREF="style.css">
<script type="text/javascript" src="tools.js"></script>
__IfAjax__<script type="text/javascript" src="js/jquery-1.2.js"></script>
<script type="text/javascript" src="js/tools_ajax.js"></script>
__IfAjax__</head>

<body>

<div class="content">
<h2 class="admin-title">部屋番号管理 − 部屋番号検索結果リスト__ExtraTitle__</h2>
<br />

__IfCreate__
<table class="common-list">
	<tr>
		<td nowrap class="common-list-value">
		『__wLastName__ __wFirstName__』さん の新規登録を行いました。<br />
		</td>
	</tr>
</table>
<br />
__IfCreate__

__IfUpdate__
<table class="common-list" width="700">
	<tr>
		<td nowrap class="common-list-value">
		『__wLastName__ __wFirstName__』さん の情報更新を行いました。<br />
		</td>
	</tr>
</table>
<br />
__IfUpdate__

__IfDelete__
<table class="common-list" width="700">
	<tr>
		<td nowrap class="common-list-value">
		『__wLastName__ __wFirstName__』さん の削除を行いました。<br />
		</td>
	</tr>
</table>
<br />
__IfDelete__

__IfDemo__
<table class="common-list">
	<tr>
		<td nowrap class="common-list-value">SoupPot! Ads ... <a href="http://souppot.jp/inquiry.php">本システムに関する簡単お問い合わせはこちらから</a></td>
	</tr>
</table>
<br>
__IfDemo__

<form method="POST" action="user_detail.php" name="mainform">

<table class="common-list">
	<tr>
		<td nowrap class="common-list-title-left" style="border:0px;priority">
			総件数：__AllRows__ データ<br />
		</td>
		<td nowrap class="common-list-title-right" style="border:0px;priority">
			1ページ当たり：
			<select name="cRowsPerPage" class="form" onChange="document.mainform.myPage.value=1;javascript:move('user_list.php')">
__RowsPerPageLoop__			<option value="__RowsPerPage__" __RowsPerPageSelected__>__RowsPerPage__
__RowsPerPageLoop__			</select>データ
		</td>
	</tr>
</table>

<h2 class="navigation"><a href="#" onclick="javascript:window.location.href='user_search.php?rKey=__rKey__'">&lt;&lt;&lt; 検索フォームへもどる</a></h2>
<h2 class="navigation"><a href="index.php">&lt;&lt;&lt; トップメニューへ</a></h2>
<br />
__IfNew__<h2 class="navigation"><a href="#" onclick="javascript:moveWithUserKey('user_detail.php', -1)">&gt;&gt;&gt; 部屋番号を登録する</a></h2>__IfNew__
<h2 class="navigation"><a href="#" onclick="javascript:move('user_download.php')">&gt;&gt;&gt; この検索結果をCSVダウンロード</a></h2>
__IfNotOpenLog__<h2 class="navigation"><font color="red">※未開封リストは、配信時点の部屋番号データと現在の部屋番号データが異なるため、不正確な場合があります(開封リストは正確です)。</font></h2>
__IfNotOpenLog____IfDemo__<h2 class="navigation"><font color="red">※お試しで登録した方の情報は個人情報保護のため出ないようになっています</font></h2>
__IfDemo__<br />

<table class="common-list" width="700" id="mainTable">
	<tr>
		<td nowrap class="common-list-title">部屋番号コード</td>
__ItemListLoop__		<td nowrap class="common-list-title">__QuestionName__</td>
__ItemListLoop____IfOpen__		<td nowrap class="common-list-title">
			開封日時
		</td>
__IfOpen__		<td nowrap class="common-list-title">
			詳細/編集
		</td>
__IfPoint__		<td nowrap class="common-list-title">
			ポイント履歴
		</td>
__IfPoint____IfReserve__		<td nowrap class="common-list-title">
			予約
		</td>
__IfReserve__	</tr>

<!--	<tr>
		<td nowrap class="common-list-value">
__IfResults__
			<a href="#" onClick="javascript:changeSort('user_list.php', 1)">▲</a>
			<a href="#" onClick="javascript:changeSort('user_list.php', 2)">▼</a>
__IfResults__
		</td>
		<td nowrap class="common-list-value">
__IfResults__
			<a href="#" onClick="javascript:changeSort('user_list.php', 3)">▲</a>
			<a href="#" onClick="javascript:changeSort('user_list.php', 4)">▼</a>
__IfResults__
		</td>
		<td nowrap class="common-list-value">
__IfResults__
			<a href="#" onClick="javascript:changeSort('user_list.php', 5)">▲</a>
			<a href="#" onClick="javascript:changeSort('user_list.php', 6)">▼</a>
__IfResults__
		</td>
		<td nowrap class="common-list-value">
__IfResults__
			<a href="#" onClick="javascript:changeSort('user_list.php', 7)">▲</a>
			<a href="#" onClick="javascript:changeSort('user_list.php', 8)">▼</a>
__IfResults__
		</td>
		<td nowrap class="common-list-value">
__IfResults__
			<a href="#" onClick="javascript:changeSort('user_list.php', 9)">▲</a>
			<a href="#" onClick="javascript:changeSort('user_list.php', 10)">▼</a>
__IfResults__
		</td>
		<td nowrap class="common-list-value">
		</td>
__IfPoint__		<td nowrap class="common-list-value">
		</td>
__IfPoint__	</tr>-->

__IfResults__
__UserListLoop__
	<tr class="common-list">
		<td nowrap class="common-list-value">__UserCD__</td>
__Item__		<td nowrap class="common-list-value-left">__QuestionID__</td>__Item__
__IfOpenArr__		<td nowrap class="common-list-value-left">__Opened__</td>__IfOpenArr__
		<td nowrap class="common-list-value"><a href="#" onclick="javascript:moveWithUserKey('user_detail.php', __UserCD__);">GO!</a></td>
__IfPointArr__		<td nowrap class="common-list-value"><a href="#" onclick="javascript:moveWithUserKey('point_list.php', __UserCD__);">GO!</a></td>__IfPointArr__
__IfReserveArr1__		<td nowrap class="common-list-value"><a href="#" onclick="javascript:reserve(__UserCD__);">GO!</a></td>__IfReserveArr1__
__IfReserveArr2__		<td nowrap class="common-list-value"><a href="#" onclick="javascript:moveWithUserKey('reserve_detail.php', __UserCD__);">GO!</a></td>__IfReserveArr2__
	</tr>
__UserListLoop__
__IfResults__

__IfNoResults__
	<tr>
		<td nowrap class="common-list-value" colspan="__ColSpan__"><font color="red">該当する部屋番号は見付かりませんでした。</font></td>
	</tr>
__IfNoResults__
	<tr>
		<td nowrap class="common-list-value" colspan="__ColSpan__">
			__IfToTop__<a href="#" onClick="javascript:changePage('user_list.php', 1, __AllPages__)">__IfToTop__&lt;&lt;__IfToTop__</a>__IfToTop__　
			__IfToPre__<a href="#" onClick="javascript:changePage('user_list.php', __PreviousPage__, __AllPages__)">__IfToPre__&lt;__IfToPre__</a>__IfToPre__　
			<input type="text" name="myPage" value="__myPage__" size="4" __IME_OFF__ class="form">/__AllPages__
			<input type="button" value="ページジャンプ" class="button" onclick="javascript:changePage('user_list.php', '0', __AllPages__)">　
			__IfToNext__<a href="#" onClick="javascript:changePage('user_list.php', __NextPage__, __AllPages__)">__IfToNext__&gt;__IfToNext__</a>__IfToNext__　
			__IfToLast__<a href="#" onClick="javascript:changePage('user_list.php', __LastPage__, __AllPages__)">__IfToLast__&gt;&gt;__IfToLast__</a>__IfToLast__
			
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