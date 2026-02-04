<html>
<head>
<title>会員管理 − 一括会員登録フォーム</title>

<META http-equiv="Content-Type" content="text/html; charset=euc-jp">
<LINK REL="stylesheet" TYPE="text/css" HREF="style.css">
</head>

<body>
<div class="content">
<h2 class="admin-title">会員管理 − 一括会員処理完了__ExtraTitle__</h2>
<br />

<h2 class="navigation"><a href="#" onclick="javascript:window.location.href='user_search.php?rKey=__rKey__'">&lt;&lt;&lt; 検索フォームへもどる</a></h2>
<h2 class="navigation"><a href="index.php">&lt;&lt;&lt; トップメニューへ</a></h2>
<br />

<form method="POST" action="user_l.php" name="fList">

<table class="common-list" width="700">
	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">処理結果</td>
		<td nowrap class="common-list-value-left">
			__AllCount__ 件のデータを処理(__NameOfWork__)しました。正常処理は __Success__ 件、異常は __Failure__ 件でした。<br>
			異常データがある場合、下に詳細が表示されています。
		</td>
	</tr>
	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">異常データ</td>
		<td nowrap class="common-list-value-left">
__ErrorLoop__		__ErrorEMail__,__ErrorMessage__<br>
__ErrorLoop__		</td>
	</tr>
</table>

__HiddenValues__
</form>

<div class="footer-box">
	__SAdminCopyright__
</div>

</body>
</html>