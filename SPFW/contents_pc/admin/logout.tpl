<html>
<head>
<title>管理系ログイン</title>

<META http-equiv="Content-Type" content="text/html; charset=euc-jp">
<LINK REL="stylesheet" TYPE="text/css" HREF="style.css">
</head>

<body onload="javascript:window.document.mainform.wAdminID.focus();">
<div class="content">
<h2 class="admin-title">管理系ログイン</h2>
<br />

<table class="common-list" width="700">
	<tr>
		<td nowrap class="common-list-value">
		　
		<p>
		ログアウトしました。
		<p>
		　
		</td>
	</tr>
</table>

<form method="POST" action="index.php" name="mainform">

<table class="common-list" width="700">
	<tr id="blockName">
		<td nowrap class="common-list-title">管理者ＩＤ</td>
		<td nowrap class="common-list-value-left">
			<input type="text" name="wAdminID" value="" size="20" __IME_OFF__ class="form" maxlength="20">
		</td>
	</tr>
	<tr id="blockName">
		<td nowrap class="common-list-title">管理者パスワード</td>
		<td nowrap class="common-list-value-left">
			<input type="password" name="wAdminPasswd" value="" size="20" __IME_OFF__ class="form" maxlength="20">
		</td>
	</tr>
	<tr id="blockAction">
		<td nowrap class="common-list-value" colspan="2">
			<input type="submit" value="ログイン" class="button">
		</td>
	</tr>
</table>

</form>
<div class="footer-box">
	__SAdminCopyright__
</div>

</body>
</html>