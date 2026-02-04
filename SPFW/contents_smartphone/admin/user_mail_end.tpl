<html>
<head>
<title>会員管理 − メール設定完了</title>

<META http-equiv="Content-Type" content="text/html; charset=euc-jp">
<LINK REL="stylesheet" TYPE="text/css" HREF="style.css">
<script type="text/javascript" src="tools.js"></script>

</head>

<body>
<div class="content">
<h2 class="admin-title">会員管理 − メール設定完了__ExtraTitle__</h2>
<br />

<h2 class="navigation"><a href="#" onclick="javascript:window.location.href='user_set_menu.php'">&lt;&lt;&lt; システム設定メニューへもどる</a></h2>
<h2 class="navigation"><a href="#" onclick="javascript:window.location.href='user_search.php?rKey=__rKey__'">&lt;&lt;&lt; 検索フォームへもどる</a></h2>
<h2 class="navigation"><a href="index.php">&lt;&lt;&lt; トップメニューへ</a></h2>
<br />

<table class="common-list">
	<tr>
		<td nowrap class="common-list-value">
		以下の内容でメール原稿の設定を行いました。ご確認ください。<br />
		</td>
	</tr>
</table>
<br />

<form method="POST" action="user_mail_end.php" name="mainform">

<table class="common-list">
	<tr>
		<td nowrap class="common-list-title" width="200">登録完了メール発送の有無</td>
		<td nowrap class="common-list-value-left">
			__pSendFlg__
		</td>
	</tr>
	<tr>
		<td nowrap class="common-list-title" width="200">FROMアドレス</td>
		<td nowrap class="common-list-value-left">
			__wFromAddress__<br />
		</td>
	</tr>
	<tr>
		<td nowrap class="common-list-title" width="200">送信者名</td>
		<td nowrap class="common-list-value-left">
			__wFromName__
		</td>
	</tr>
	<tr>
		<td nowrap class="common-list-title" width="200">管理者宛BCC</td>
		<td nowrap class="common-list-value-left">
			__pBcc__
		</td>
	</tr>
	<tr>
		<td nowrap class="common-list-title" colspan="2">PC向け送信メール内容</td>
	</tr>
	<tr>
		<td nowrap class="common-list-title" width="200">送信内容(題名)</td>
		<td nowrap class="common-list-value-left">
			__wSubject4pc__
		</td>
	</tr>
	<tr>
		<td nowrap class="common-list-title" width="200">送信内容(本文)</td>
		<td class="common-list-value-left">
			__pMessage4pc__
		</td>
	</tr>
	<tr>
		<td nowrap class="common-list-title" colspan="2">モバイル向け送信メール内容</td>
	</tr>
	<tr>
		<td nowrap class="common-list-title" width="200">送信内容(題名)</td>
		<td nowrap class="common-list-value-left">
			__wSubject4i__
		</td>
	</tr>
	<tr>
		<td nowrap class="common-list-title" width="200">送信内容(本文)</td>
		<td class="common-list-value-left">
			__pMessage4i__
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