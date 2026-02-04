<html>
<head>
<title>会員管理 − 動作設定完了</title>

<META http-equiv="Content-Type" content="text/html; charset=euc-jp">
<LINK REL="stylesheet" TYPE="text/css" HREF="style.css">
<script type="text/javascript" src="tools.js"></script>

</head>

<body>
<div class="content">
<h2 class="admin-title">会員管理 − 動作設定完了__ExtraTitle__</h2>
<br />

<h2 class="navigation"><a href="#" onclick="javascript:window.location.href='user_set_menu.php'">&lt;&lt;&lt; システム設定メニューへもどる</a></h2>
<h2 class="navigation"><a href="#" onclick="javascript:window.location.href='user_search.php?rKey=__rKey__'">&lt;&lt;&lt; 検索フォームへもどる</a></h2>
<h2 class="navigation"><a href="index.php">&lt;&lt;&lt; トップメニューへ</a></h2>
<br />

<table class="common-list">
	<tr>
		<td nowrap class="common-list-value">
		以下の内容で動作設定を行いました。ご確認ください。<br />
		</td>
	</tr>
</table>
<br />

<form method="POST" action="user_mail_end.php" name="mainform">

<table class="common-list">
	<tr>
		<td nowrap class="common-list-title" colspan="2">ログイン関連</td>
	</tr>
	<tr>
		<td nowrap class="common-list-title" width="200">ログイン動作</td>
		<td class="common-list-value-left">
			__pLoginFlg__<br />
			__pEasyLoginFlg__
		</td>
	</tr>
	<tr>
		<td nowrap class="common-list-title" width="200">セッションタイムアウト</td>
		<td class="common-list-value-left">
			__pTimeout__
		</td>
	</tr>
	<tr>
		<td nowrap class="common-list-title" width="200">ログインキー変更</td>
		<td class="common-list-value-left">
			__pKeyChangeFlg__
		</td>
	</tr>
	<tr>
		<td nowrap class="common-list-title" colspan="2">登録内容変更関連</td>
	</tr>
	<tr>
		<td nowrap class="common-list-title" width="200">登録内容変更時認証</td>
		<td class="common-list-value-left">
			__pChangeSecurityFlg__
		</td>
	</tr>
	<tr>
		<td nowrap class="common-list-title" colspan="2">登録完了画面内設定</td>
	</tr>
	<tr>
		<td nowrap class="common-list-title" width="200">リンク先・文言</td>
		<td class="common-list-value-left">
			リンク先　　__wLinkTo__<br />
			リンク文言　__wLinkMessage__<br />
		</td>
	</tr>


	<tr id="blockAction">
		<td nowrap class="common-list-value-left" colspan="2">
			<input type="button" value="もどる" class="button" onclick="javascript:move('user_search.php')">　　　
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