<html>
<head>
<title>基本設問管理 − 設定完了</title>

<META http-equiv="Content-Type" content="text/html; charset=euc-jp">
<LINK REL="stylesheet" TYPE="text/css" HREF="style.css">

<SCRIPT language=JavaScript>
<!-- Hide script from old browser
// プログラム移動
function goPage(pgAct) {
	document.fList.action = pgAct;
	document.fList.submit(true)
}
// end hiding -->
</SCRIPT>

</head>

<body>
<div class="content">
<h2 class="admin-title">基本設問管理 − 設定完了__ExtraTitle__</h2>
<br />

__IfFromUser__<h2 class="navigation"><a href="#" onclick="javascript:window.location.href='user_set_menu.php'">&lt;&lt;&lt; システム設定メニューへもどる</a></h2>
<h2 class="navigation"><a href="#" onclick="javascript:window.location.href='user_search.php?rKey=__rKey__'">&lt;&lt;&lt; 検索フォームへもどる</a></h2>
__IfFromUser____IfFromClient__<h2 class="navigation"><a href="#" onclick="javascript:window.location.href='client_list.php'">&lt;&lt;&lt; 顧客リストへもどる</a></h2>
__IfFromClient__<h2 class="navigation"><a href="index.php">&lt;&lt;&lt; トップメニューへ</a></h2>
<br />

<form method="POST" action="user_search.php.php" name="fList">

<table class="common-list">
	<tr>
		<td nowrap class="common-list-value">
		ご指定の内容で設問を設定しました。<br />
		</td>
	</tr>
</table>
<br />

__HiddenValues__
</form>

<div class="footer-box">
	__SAdminCopyright__
</div>

</body>
</html>