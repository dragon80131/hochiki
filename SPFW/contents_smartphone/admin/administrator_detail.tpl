<html>
<head>
<title>管理者管理 − 詳細情報フォーム</title>

<META http-equiv="Content-Type" content="text/html; charset=euc-jp">
<LINK REL="stylesheet" TYPE="text/css" HREF="style.css">
<script type="text/javascript" src="tools.js"></script>
__IfAjax__<script type="text/javascript" src="js/jquery-1.4.2.js"></script>
<script type="text/javascript" src="js/tools_ajax.js"></script>
__IfAjax__</head>

<body>
<div class="content">
<h2 class="admin-title">管理者管理 − 詳細情報フォーム__ExtraTitle__</h2>
<br />

<h2 class="navigation"><a href="javascript:history.back();">&lt;&lt;&lt; ひとつ前へもどる</a></h2>
<form method="POST" action="administrator_list.php" name="mainform">

<table class="common-list" width="700">
	<tr id="blockName">
		<td nowrap class="common-list-title" colspan="2">管理データ</td>
	</tr>
	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">管理者コード</td>
		<td nowrap class="common-list-value-left">
			__wAdminCD__　(システムが決定しているので変更できません)
		</td>
	</tr>
	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">最終ログイン</td>
		<td nowrap class="common-list-value-left">
			__wLastLogin__
		</td>
	</tr>
	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">備考</td>
		<td nowrap class="common-list-value-left">
			<textarea name="wNotes" value="__wNotes__" cols="60" rows="5" __IME_ON__ class="form">__wNotes__</textarea>
		</td>
	</tr>

	<tr id="blockName">
		<td nowrap class="common-list-title" colspan="2">登録データ</td>
	</tr>
	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">ID</td>
		<td nowrap class="common-list-value-left">
			<input type="text" name="wID" value="__wID__" size="15" __IME_OFF__ class="form">
		</td>
	</tr>
	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">パスワード</td>
		<td nowrap class="common-list-value-left">
			<input type="text" name="wPasswd" value="__wPasswd__" size="15" __IME_OFF__ class="form">
		</td>
	</tr>
	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">姓名</td>
		<td nowrap class="common-list-value-left">
			<input type="text" name="wLastName" value="__wLastName__" size="10" __IME_ON__ class="form">
			<input type="text" name="wFirstName" value="__wFirstName__" size="10" __IME_ON__ class="form">
		</td>
	</tr>

	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">初回登録者</td>
		<td nowrap class="common-list-value-left">
			__Creator__
		</td>
	</tr>
	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">初回登録日時</td>
		<td nowrap class="common-list-value-left">
			__Created__
		</td>
	</tr>
	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">最終更新者</td>
		<td nowrap class="common-list-value-left">
			__Updater__
		</td>
	</tr>
	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">最終更新日時</td>
		<td nowrap class="common-list-value-left">
			__Updated__
		</td>
	</tr>
	<tr id="blockAction">
		<td nowrap class="common-list-value-left" colspan="2">
			
			<input type="button" value="データ更新" class="button" onclick="javascript:moveWithWork('administrator_list.php', 1)">　
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