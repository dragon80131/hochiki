<html>
<head>
<title>顧客管理 − 顧客検索フォーム</title>

<META http-equiv="Content-Type" content="text/html; charset=euc-jp">
<LINK REL="stylesheet" TYPE="text/css" HREF="style.css">
<script type="text/javascript" src="tools.js"></script>
</head>

<body>
<div class="content">
<h2 class="admin-title">顧客管理 − 詳細情報フォーム</h2>
<br />

<h2 class="navigation"><a href="#" onclick="javascript:location.href='index.php'">&gt;&gt;&gt; トップページ(メニュー)へ</a></h2>
<br />

<form method="POST" action="client_list.php" name="mainform">

<table class="common-list" width="700">
	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">顧客コード</td>
		<td nowrap class="common-list-value-left">
			__wClientCD__　(システムが決定しているので変更できません)
		</td>
	</tr>
	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">名称</td>
		<td nowrap class="common-list-value-left">
			<input type="text" name="wClientName" value="__wClientName__" size="30" __IME_ON__ class="form">
			<font class="warning">※必須</font>
		</td>
	</tr>
	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">ＩＤ</td>
		<td nowrap class="common-list-value-left">
__IfID__			<input type="text" name="wID" value="__wID__" size="30" __IME_ON__ class="form">
__IfID____IfNoID__			__wID__<input type="hidden" name="wID" value="__wID__">
__IfNoID__			<font class="warning">※必須(一度設定したIDは変更できません)</font>
		</td>
	</tr>
	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">パスワード</td>
		<td nowrap class="common-list-value-left">
			<input type="text" name="wPasswd" value="__wPasswd__" size="30" __IME_ON__ class="form">
			<font class="warning">※必須</font>
		</td>
	</tr>
	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">メールアドレス</td>
		<td nowrap class="common-list-value-left">
			<input type="text" name="wEMail" value="__wEMail__" size="50" __IME_OFF__ class="form">
			<font class="warning">※必須</font><br>
		</td>
	</tr>
	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">備考</td>
		<td nowrap class="common-list-value-left">
			<textarea name="wNotes" value="__wNotes__" cols="60" rows="5" __IME_ON__ class="form">__wNotes__</textarea>
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
			
			<input type="button" value="データ更新" class="button" onclick="javascript:moveWithWork('client_list.php', 1)">　
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