<html>
<head>
<title>共通設定完了</title>

<META http-equiv="Content-Type" content="text/html; charset=euc-jp">
<LINK REL="stylesheet" TYPE="text/css" HREF="style.css">
<script type="text/javascript" src="tools.js"></script>

</head>

<body>
<div class="content">
<h2 class="admin-title">共通設定完了__ExtraTitle__</h2>
<br />

<form method="POST" action="system_set_end.php" name="mainform">

<h2 class="navigation"><a href="index.php">&lt;&lt;&lt; トップメニューへ</a></h2>
__IfDemo__<h2 class="navigation"><font color="red">※本システムはデモですので、実際には変更は反映されていません。</font></h2>
__IfDemo__<br />

<table class="common-list">
	<tr>
		<td nowrap class="common-list-title" colspan="2">管理画面関連</td>
	</tr>
	<tr>
		<td nowrap class="common-list-title" width="200">操作系</td>
		<td class="common-list-value-left">
			__pAjax__
		</td>
	</tr>
	<tr>
		<td nowrap class="common-list-title" width="200">一覧表示件数</td>
		<td class="common-list-value-left">
			1ページ当たり：__pRowsPerPage__　データ
		</td>
	</tr>
	<tr>
		<td nowrap class="common-list-title" width="200">システムタイトル表示</td>
		<td class="common-list-value-left">
			__wAdminTitle__
		</td>
	</tr>
	<tr>
		<td nowrap class="common-list-title" width="200">著作権表示</td>
		<td class="common-list-value-left">
			__wAdminCopyright__
		</td>
	</tr>
	<tr>
		<td nowrap class="common-list-title" colspan="2">一般ユーザ画面関連</td>
	</tr>
	<tr>
		<td nowrap class="common-list-title" width="200">背景色</td>
		<td class="common-list-value-left">
			<font color="__wBackground__">■</font>　__wBackground__
		</td>
	</tr>
	<tr>
		<td nowrap class="common-list-title" width="200">文字色(標準)</td>
		<td class="common-list-value-left">
			<font color="__wTextColor__">■</font>　__wTextColor__
		</td>
	</tr>
	<tr>
		<td nowrap class="common-list-title" width="200">文字色(リンク)</td>
		<td class="common-list-value-left">
			<font color="__wLinkColor__">■</font>　__wLinkColor__
		</td>
	</tr>
	<tr>
		<td nowrap class="common-list-title" width="200">文字色(訪問済みリンク)</td>
		<td class="common-list-value-left">
			<font color="__wVLinkColor__">■</font>　__wVLinkColor__
		</td>
	</tr>
	<tr>
		<td nowrap class="common-list-title" width="200">文字色(選択中リンク)</td>
		<td class="common-list-value-left">
			<font color="__wALinkColor__">■</font>　__wALinkColor__
		</td>
	</tr>
	<tr>
		<td nowrap class="common-list-title" width="200">区切り線</td>
		<td class="common-list-value-left">
			<font color="__wHRColor__">■</font>　__wHRColor__　
			幅__wHRSize__ピクセル
		</td>
	</tr>
	<tr>
		<td nowrap class="common-list-title" width="200">ヘッダ</td>
		<td class="common-list-value-left">
			__wHeader__
		</td>
	</tr>
	<tr>
		<td nowrap class="common-list-title" width="200">フッタ</td>
		<td class="common-list-value-left">
			__wFooter__
		</td>
	</tr>
	<tr>
		<td nowrap class="common-list-title" width="200">著作権表示</td>
		<td class="common-list-value-left">
			__wCopyright__
		</td>
	</tr>

	<tr id="blockAction">
		<td nowrap class="common-list-value-left" colspan="2">
			<input type="button" value="もどる" class="button" onclick="javascript:history.back()">　　　
		</td>
	</tr>
</table>

__HiddenValues__
</form>

<div class="footer-box">
	__SAdminCopyright__
</div>

</body>
</html>