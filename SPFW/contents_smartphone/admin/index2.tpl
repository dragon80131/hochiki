<html>
<head>
<title>管理系メニュー</title>

<META http-equiv="Content-Type" content="text/html; charset=euc-jp">
<LINK REL="stylesheet" TYPE="text/css" HREF="DropDownMenu.css">
<LINK REL="stylesheet" TYPE="text/css" HREF="style.css">

<script type="text/javascript" src="DropDownMenu.js"></script>
<script type="text/javascript" src="tools.js"></script>
<SCRIPT language=JavaScript>
<!-- Hide script from old browser
//プログラム移動
function GoPage(pgAct) {
	document.fList.action = pgAct;
	document.fList.submit(true)
}
// end hiding -->
</SCRIPT>

</head>

<body>

<div class="content">
<h2 class="admin-title">__SAdminTitle__　管理システムメニュー__ExtraTitle__</h2>

__File::admin/menu.tpl__

<table class="common-list">
	<tr>
		<td nowrap class="common-list-title-right">
			<div id="clock3_form">　</div>
			<script language="JavaScript">clock(true);</script>
		</td>
	</tr>
	<tr>
		<td nowrap class="common-list-value">
			　
#<a href=https://www.489501.jp/008yotuyama/admin/list06.php>LIST</a><br>			
			<p>
			__AdminName__の前回ログインは__PreLogin__でした。
			<p>
__IfQR__			　
			<p>
			住人様利用のシステムのページ <b>__MyDomain__top.php</b> のＱＲコード<br />
			<a href=http://www.cman.jp/QRcode/>コード取得</a><br />
			表示用タグ<input type="text" value="&lt;img src=&quot;__TopQR__&quot;&gt;" size="80"><br />
__IfQR__
			　
		</td>
	</tr>
</table>

__IfDemo__<table class="common-list">
	<tr>
		<td nowrap class="common-list-value">
			<font color="red">このメニューはSoupPot!のシステム全体のメニューになります。<br />
			実際には導入していただいたシステムに応じたメニュー構成になります。</font><br />
			<br />
			Nsp.Co.Ltd. <a href="wwww.nespe.com">本システムに関する簡単お問い合わせはこちらから</a>
		</td>
	</tr>
</table>
<br>
__IfDemo__

<div class="footer-box">
	__SAdminCopyright__
</div>

</body>
</html>
