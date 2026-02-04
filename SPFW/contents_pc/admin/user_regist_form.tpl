<html>
<head>
<title>会員管理 − 一括会員登録フォーム</title>

<META http-equiv="Content-Type" content="text/html; charset=euc-jp">
<LINK REL="stylesheet" TYPE="text/css" HREF="style.css">
<script type="text/javascript" src="tools.js"></script>
</head>

<body>
<div class="content">
<h2 class="admin-title">会員管理 − 一括会員処理フォーム__ExtraTitle__</h2>
<br />

<h2 class="navigation"><a href="#" onclick="javascript:window.location.href='user_search.php?rKey=__rKey__'">&lt;&lt;&lt; 検索フォームへもどる</a></h2>
<h2 class="navigation"><a href="index.php">&lt;&lt;&lt; トップメニューへ</a></h2>
<br />

<form method="POST" action="user_list.php" name="mainform">

<table class="common-list" width="700">
	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">メールアドレス</td>
		<td nowrap class="common-list-value-left">
			メールアドレスだけが登録された状態の会員を一括して作成したり、メールアドレスを指定して一括して退会させたりできます。<br>
			一括処理したいメールアドレスを改行区切りで羅列して記入してください。<br>
__IfDemo__			<b><font color="red">セキュリティのため、本デモでは10人分以内に制限されています。<br>また、登録処理は行われますが、個人情報保護の観点から管理画面には出てきません。</font></b><br>__IfDemo__
			<textarea name="wEMailArray" cols="50" rows="30" __IME_ON__ class="form">__wEMailArray__</textarea>
		</td>
	</tr>
	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">区分</td>
		<td nowrap class="common-list-value-left">
			<input type="radio" name="wWork" value="1" class="form" checked>上のメールアドレスを一括して登録する<br>
			<input type="radio" name="wWork" value="2" class="form">上のメールアドレスを一括して退会する<br>
		</td>
	</tr>
	<tr id="blockAction">
		<td nowrap class="common-list-value-left" colspan="2">
			
			<input type="button" value="一括処理開始" class="button" onclick="javascript:moveWithWork('user_regist_end.php', 1)">　
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