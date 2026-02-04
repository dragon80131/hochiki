<html>
<head>
<title>__ReservationName__システム設定 − 完了メール(__Name__)設定フォーム</title>

<META http-equiv="Content-Type" content="text/html; charset=euc-jp">
<LINK REL="stylesheet" TYPE="text/css" HREF="style.css">
<script type="text/javascript" src="tools.js"></script>
__IfAjax__<script type="text/javascript" src="js/jquery-1.4.2.js"></script>
<script type="text/javascript" src="js/tools_ajax.js"></script>
__IfAjax__</head>

<body>
<div class="content">
<h2 class="admin-title">__ReservationName__システム設定 − メール(__Name__)設定フォーム__ExtraTitle__</h2>
<br />

<form method="POST" action="reserve_mail_end.php" name="mainform">

<h2 class="navigation"><a href="#" onclick="javascript:location.href='reserve_set_menu.php'">&lt;&lt;&lt; システム設定メニューへ</a></h2>
<h2 class="navigation"><a href="index.php">&lt;&lt;&lt; トップメニューへ</a></h2>
<br />

<table class="common-list">
__IfEdit__	<tr id="blockAction">
		<td nowrap class="common-list-value-left" colspan="2">
			<input type="button" value="新規登録時の登録完了メール設定をコピー" class="button" onclick="javascript:moveWithWork('reserve_mail_form.php', 1)">
		</td>
	</tr>
__IfEdit__
	<tr>
		<td nowrap class="common-list-title" width="200">メール発送の有無</td>
		<td nowrap class="common-list-value-left">
			<input type="checkbox" name="wSendFlg" value="t" size="50" class="form"__SendFlgChecked__>メールを送信する
		</td>
	</tr>
	<tr>
		<td nowrap class="common-list-title" width="200">FROMアドレス</td>
		<td nowrap class="common-list-value-left">
			<input type="text" name="wFromAddress" value="__wFromAddress__" size="50" __IME_ON__ class="form">
		</td>
	</tr>
	<tr>
		<td nowrap class="common-list-title" width="200">送信者名</td>
		<td nowrap class="common-list-value-left">
			<input type="text" name="wFromName" value="__wFromName__" size="50" __IME_ON__ class="form">
		</td>
	</tr>
	<tr>
		<td nowrap class="common-list-title" width="200">管理者宛BCC</td>
		<td nowrap class="common-list-value-left">
			<input type="text" name="wBcc" value="__wBcc__" size="50" __IME_ON__ class="form">　宛にBCCで同じ内容を送る
		</td>
	</tr>
	<tr>
		<td nowrap class="common-list-title" colspan="2">PC向け送信メール内容</td>
	</tr>
	<tr>
		<td nowrap class="common-list-title" width="200">送信内容(題名)</td>
		<td nowrap class="common-list-value-left">
			<input type="text" name="wSubject4pc" value="__wSubject4pc__" size="50" __IME_ON__ class="form"><br />
		</td>
	</tr>
	<tr>
		<td nowrap class="common-list-title" width="200">送信内容(本文)</td>
		<td nowrap class="common-list-value-left">
			<textarea name="wMessage4pc" __IME_ON__ class="form" cols="60" rows="15" id="Message1" onSelect="javascript:getLocationOfCursor('Message1');" onClick="javascript:getLocationOfCursor('Message1');" onKeyup="javascript:getLocationOfCursor('Message1');">__wMessage4pc__</textarea><br />
<!--			<select onChange="javascript:putSelectedItem(this, 'Message1')">
			<option value="">差し込み要素を入れる場合は選択</option>
			<option value="%%DOMAIN%%top.php?rKey=%%KEY%%">ログイン用URL</option>
			<option value="%%MAILADDRESS%%">送信者メールアドレス</option>
			<option value="%%DATE%%">日付(YYYY/MM/DD形式)</option>
			<option value="%%DATETIME%%">日時(YYYY/MM/DD hh:mm:ss形式)</option>
			<option value="%%KEY%%">会員識別キー</option>
			</select><br />
			! PCから空メールが来たときに返信するメールの本文です。-->
		</td>
	</tr>
	<tr>
		<td nowrap class="common-list-title" colspan="2">モバイル向け送信メール内容</td>
	</tr>
	<tr>
		<td nowrap class="common-list-title" width="200">送信内容(題名)</td>
		<td nowrap class="common-list-value-left">
			<input type="text" name="wSubject4i" value="__wSubject4i__" size="50" __IME_ON__ class="form"><br />
		</td>
	</tr>
	<tr>
		<td nowrap class="common-list-title" width="200">送信内容(本文)</td>
		<td nowrap class="common-list-value-left">
			<textarea name="wMessage4i" __IME_ON__ class="form" cols="60" rows="15" id="Message2" onSelect="javascript:getLocationOfCursor('Message2');" onClick="javascript:getLocationOfCursor('Message2');" onKeyup="javascript:getLocationOfCursor('Message2');">__wMessage4i__</textarea><br />
<!--			<select onChange="javascript:putSelectedItem(this, 'Message2')">
			<option value="">差し込み要素を入れる場合は選択</option>
			<option value="%%DOMAIN%%form.php?rKey=%%KEY%%">登録用URL</option>
			<option value="%%MAILADDRESS%%">送信者メールアドレス</option>
			<option value="%%DATE%%">日付(YYYY/MM/DD形式)</option>
			<option value="%%DATETIME%%">日時(YYYY/MM/DD hh:mm:ss形式)</option>
			<option value="%%KEY%%">会員識別キー</option>
			</select><br />
			! 携帯から空メールが来たときに返信するメールの本文です。-->
		</td>
	</tr>

	<tr id="blockAction">
		<td nowrap class="common-list-value-left" colspan="2">
			<input type="button" value="データ更新" class="button" onclick="javascript:move('reserve_mail_end.php')">　
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