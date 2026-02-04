<html>
<head>
<title>顧客管理 − 顧客リスト</title>

<META http-equiv="Content-Type" content="text/html; charset=euc-jp">
<LINK REL="stylesheet" TYPE="text/css" HREF="style.css">
<script type="text/javascript" src="js/jquery-1.2.js"></script>
<script type="text/javascript" src="tools.js"></script>
</SCRIPT>

</head>

<body>

<div class="content">
<h2 class="admin-title">顧客管理 − 顧客リスト</h2>
<br />

__IfCreate__
<table class="common-list">
	<tr>
		<td nowrap class="common-list-value">
		『__wClientName__』 の新規登録を行いました。<br />
		</td>
	</tr>
</table>
<br />
__IfCreate__

__IfUpdate__
<table class="common-list" width="700">
	<tr>
		<td nowrap class="common-list-value">
		『__wClientName__』 の情報更新を行いました。<br />
		</td>
	</tr>
</table>
<br />
__IfUpdate__

__IfDelete__
<table class="common-list" width="700">
	<tr>
		<td nowrap class="common-list-value">
		『__wClientName__』 の削除を行いました。<br />
		</td>
	</tr>
</table>
<br />
__IfDelete__

<form method="POST" action="client_detail.php" name="mainform">

<table class="common-list">
	<tr>
		<td nowrap class="common-list-title-left" style="border:0px;priority">
			総件数：__AllRows__ データ<br />
		</td>
		<td nowrap class="common-list-title-right" style="border:0px;priority">
			1ページ当たり：
			<select name="cRowsPerPage" class="form" onChange="document.mainform.myPage.value=1;javascript:move('client_list.php')">
__RowsPerPageLoop__			<option value="__RowsPerPage__" __RowsPerPageSelected__>__RowsPerPage__
__RowsPerPageLoop__			</select>データ
		</td>
	</tr>
</table>

<h2 class="navigation"><a href="#" onclick="javascript:location.href='index.php'">&gt;&gt;&gt; トップページ(メニュー)へ</a></h2>
<h2 class="navigation"><a href="#" onclick="javascript:moveWithClientKey('client_detail.php', -1)">&gt;&gt;&gt; 新しい顧客を作成する</a></h2>
<br />

<table class="common-list" width="700" id="mainTable">
	<tr>
		<td nowrap class="common-list-title">
			顧客コード<br />
		</td>
		<td nowrap class="common-list-title">
			顧客名称<br />
		</td>
		<td nowrap class="common-list-title">
			トップページ<br />
		</td>
		<td nowrap class="common-list-title">
			詳細/編集
		</td>
		<td nowrap class="common-list-title">
			空メール
		</td>
		<td nowrap class="common-list-title">
			基本設問
		</td>
		<td nowrap class="common-list-title">
			拡張設問
		</td>
		<td nowrap class="common-list-title">
			削除
		</td>
	</tr>

__IfResults__
__ClientListLoop__
	<tr class="common-list">
		<td nowrap class="common-list-value">__ClientCD__</td>
		<td nowrap class="common-list-value">__ClientName__</td>
		<td nowrap class="common-list-value"><a href="__MainURLArr____ClientID__/top.php">__MainURLArr____ClientID__/top.php></a></td>
		<td nowrap class="common-list-value"><a href="#" onclick="javascript:moveWithClientKey('client_detail.php', __ClientCD__);">GO!</a></td>
		<td nowrap class="common-list-value"><a href="#" onclick="javascript:moveWithClientKey('emptymail_list.php', __ClientCD__);">GO!</a></td>
		<td nowrap class="common-list-value"><a href="#" onclick="javascript:moveWithClientKey('user_property_form.php', __ClientCD__);">GO!</a></td>
		<td nowrap class="common-list-value"><a href="#" onclick="javascript:moveWithClientKey('user_ex_property_list.php', __ClientCD__);">GO!</a></td>
		<td nowrap class="common-list-value"><a href="#" onclick="javascript:moveWithClientKeyAlert('client_list.php', __ClientCD__, 2);">GO!</a></td>
	</tr>
__ClientListLoop__
__IfResults__

__IfNoResults__
	<tr>
		<td nowrap class="common-list-value" colspan="8"><font color="red">該当する顧客は見付かりませんでした。</font></td>
	</tr>
__IfNoResults__
	<tr>
		<td nowrap class="common-list-value" colspan="10">
			__IfToTop__<a href="#" onClick="javascript:changePage('__myFileName__', 1)">__IfToTop__&lt;&lt;__IfToTop__</a>__IfToTop__　
			__IfToPre__<a href="#" onClick="javascript:changePage('__myFileName__', __PreviousPage__)">__IfToPre__&lt;__IfToPre__</a>__IfToPre__　
			<input type="text" name="myPage" value="__myPage__" size="4" __IME_OFF__ class="form">/__AllPages__
			<input type="button" value="ページジャンプ" class="button" onclick="javascript:changePage('__myFileName__', '0')">　
			__IfToNext__<a href="#" onClick="javascript:changePage('__myFileName__', __NextPage__)">__IfToNext__&gt;__IfToNext__</a>__IfToNext__　
			__IfToLast__<a href="#" onClick="javascript:changePage('__myFileName__', __LastPage__)">__IfToLast__&gt;&gt;__IfToLast__</a>__IfToLast__
			
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