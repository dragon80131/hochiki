<html>
<head>
<title>部屋番号検索</title>

<META http-equiv="Content-Type" content="text/html; charset=euc-jp">
<LINK REL="stylesheet" TYPE="text/css" HREF="style.css">
<script type="text/javascript" src="tools.js"></script>

</head>

<body>
<div class="content">
<h2 class="admin-title">部屋番号検索</h2>
<br />



<h2 class="navigation"><a href="reserve_detail.php">&lt;&lt;&lt; トップメニューへ</a></h2>
<br />
<h2 class="navigation"><a href="index2.php">&lt;&lt;&lt; システム設定メニュー</a></h2>
<br />

<form method="POST" action="reserve_detail.php" name="mainform">

<table class="common-list" width="400">






__IfID__	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">部屋番号</td>
		<td nowrap class="common-list-value-left">
			<input type="text" name="sID" value="__sID__" size="20" __IME_OFF__ class="form">
		</td>
	</tr>__IfID__

</table>
__HiddenValues__
</form>


</body>
</html>