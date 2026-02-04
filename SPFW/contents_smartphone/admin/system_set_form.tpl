<html>
<head>
<title>共通設定</title>

<META http-equiv="Content-Type" content="text/html; charset=euc-jp">
<LINK REL="stylesheet" TYPE="text/css" HREF="style.css">
<script type="text/javascript" src="tools.js"></script>

</head>

<body>
<div class="content">
<h2 class="admin-title">共通設定__ExtraTitle__</h2>
<br />

<form method="POST" action="system_set_end.php" name="mainform">

<h2 class="navigation"><a href="index.php">&lt;&lt;&lt; トップメニューへ</a></h2>
<br />

<table class="common-list">
	<tr>
		<td nowrap class="common-list-title" colspan="2">管理画面関連</td>
	</tr>
	<tr>
		<td nowrap class="common-list-title" width="200">入力支援</td>
		<td class="common-list-value-left">
			<input type="checkbox" name="wAjax" value="t"__AjaxChecked__>各種入力支援等を利用する<br />
			※これを利用すると、リスト画面やフォーム画面の色替え表示、日付・時刻の入力支援等が有効になります。
		</td>
	</tr>
	<tr>
		<td nowrap class="common-list-title" width="200">一覧表示件数</td>
		<td class="common-list-value-left">
			1ページ当たり：
			<select name="wRowsPerPage" class="form">
__RowsPerPageLoop__			<option value="__RowsPerPageValue__" __RowsPerPageSelected__>__RowsPerPage__
__RowsPerPageLoop__			</select>データ
		</td>
	</tr>
	<tr>
		<td nowrap class="common-list-title" width="200">システムタイトル表示</td>
		<td class="common-list-value-left">
			<input type="text" name="wAdminTitle" value="__wAdminTitle__" size="80" class="form">
		</td>
	</tr>
	<tr>
		<td nowrap class="common-list-title" width="200">著作権表示</td>
		<td class="common-list-value-left">
			<input type="text" name="wAdminCopyright" value="__wAdminCopyright__" size="80" class="form">
		</td>
	</tr>
	<tr>
		<td nowrap class="common-list-title" colspan="2">一般ユーザ画面関連</td>
	</tr>
	<tr>
		<td nowrap class="common-list-title" width="200">背景色</td>
		<td class="common-list-value-left">
			<input type="text" name="wBackground" value="__wBackground__" size="20" class="form">　(現在の設定⇒<font color="__wBackground__">■</font>)
		</td>
	</tr>
	<tr>
		<td nowrap class="common-list-title" width="200">文字色(標準)</td>
		<td class="common-list-value-left">
			<input type="text" name="wTextColor" value="__wTextColor__" size="20" class="form">　(現在の設定⇒<font color="__wTextColor__">■</font>)
		</td>
	</tr>
	<tr>
		<td nowrap class="common-list-title" width="200">文字色(リンク)</td>
		<td class="common-list-value-left">
			<input type="text" name="wLinkColor" value="__wLinkColor__" size="20" class="form">　(現在の設定⇒<font color="__wLinkColor__">■</font>)
		</td>
	</tr>
	<tr>
		<td nowrap class="common-list-title" width="200">文字色(訪問済みリンク)</td>
		<td class="common-list-value-left">
			<input type="text" name="wVLinkColor" value="__wVLinkColor__" size="20" class="form">　(現在の設定⇒<font color="__wVLinkColor__">■</font>)
		</td>
	</tr>
	<tr>
		<td nowrap class="common-list-title" width="200">文字色(選択中リンク)</td>
		<td class="common-list-value-left">
			<input type="text" name="wALinkColor" value="__wALinkColor__" size="20" class="form">　(現在の設定⇒<font color="__wALinkColor__">■</font>)
		</td>
	</tr>
	<tr>
		<td nowrap class="common-list-title" width="200">区切り線</td>
		<td class="common-list-value-left">
			色：<input type="text" name="wHRColor" value="__wHRColor__" size="20" class="form">　(現在の設定⇒<font color="__wHRColor__">■</font>)　
			幅：<input type="text" name="wHRSize" value="__wHRSize__" size="5" class="form"_IME_OFF>ピクセル
		</td>
	</tr>
	<tr>
		<td nowrap class="common-list-title" width="200">ヘッダ</td>
		<td class="common-list-value-left">
			<textarea name="wHeader" cols="50" rows="3" class="form">__wHeader__</textarea>
		</td>
	</tr>
	<tr>
		<td nowrap class="common-list-title" width="200">フッタ</td>
		<td class="common-list-value-left">
			<textarea name="wFooter" cols="50" rows="3" class="form">__wFooter__</textarea>
		</td>
	</tr>
	<tr>
		<td nowrap class="common-list-title" width="200">著作権表示</td>
		<td class="common-list-value-left">
			<input type="text" name="wCopyright" value="__wCopyright__" size="80" class="form">
		</td>
	</tr>

	<tr id="blockAction">
		<td nowrap class="common-list-value-left" colspan="2">
			<input type="button" value="データ更新" class="button" onclick="javascript:move('system_set_end.php')">　
			<input type="reset" value="フォームを元に戻す" class="button">　
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