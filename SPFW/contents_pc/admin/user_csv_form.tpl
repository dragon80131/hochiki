<html>
<head>
<title>会員管理 − CSV一括会員登録 − アップロードフォーム</title>

<META http-equiv="Content-Type" content="text/html; charset=euc-jp">
<LINK REL="stylesheet" TYPE="text/css" HREF="style.css">
<script type="text/javascript" src="tools.js"></script>
</SCRIPT>

</head>

<body>
<div class="content">
<h2 class="admin-title">会員管理 − CSV一括会員登録 − CSVアップロードフォーム__ExtraTitle__</h2>
<br />

<h2 class="navigation"><a href="#" onclick="javascript:window.location.href='user_search.php?rKey=__rKey__'">&lt;&lt;&lt; 検索フォームへもどる</a></h2>
<h2 class="navigation"><a href="index.php">&lt;&lt;&lt; トップメニューへ</a></h2>
<br />

<form enctype="multipart/form-data" method="POST" action="user_csv_end.php" name="mainform">

<table class="common-list" width="700">
	<tr>
		<td nowrap class="common-list-title" width="200">登録完了報告メール宛先</td>
		<td nowrap class="common-list-value-left">
			<input type="text" name="wReportTo" size="40" value="__wReportTo__">
		</td>
	</tr>
	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">ファイル指定</td>
		<td nowrap class="common-list-value-left">
			<input type="file" name="wFile" size="50">
		</td>
	</tr>
	<tr id="blockName">
		<td nowrap class="common-list-value-left" colspan="2">
			CSVファイルのデータは以下の形式で受け付けています。<br />
			空メールアカウントコード(必須),ID,パスワード,メールアドレス(必須),姓,名,姓カナ,名カナ,性別(男性or女性),生年月日(YYYY/mm/dd),<br />
			郵便番号(半角数字7桁),都道府県,住所1,住所2,住所3,電話番号&lt;&lt;改行&gt;&gt;
		</td>
	</tr>
	<tr id="blockAction">
		<td nowrap class="common-list-value-left" colspan="2">
			<input type="button" value="アップロード" class="button" onclick="javascript:move('user_csv_end.php')">　
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