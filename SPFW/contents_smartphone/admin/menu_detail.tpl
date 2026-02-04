<html>
<head>
<title>__Menu__管理 − 詳細情報フォーム</title>

<META http-equiv="Content-Type" content="text/html; charset=euc-jp">
<LINK REL="stylesheet" TYPE="text/css" HREF="style.css">
<script type="text/javascript" src="tools.js"></script>
__IfAjax__<script type="text/javascript" src="js/jquery-1.4.2.js"></script>
<script type="text/javascript" src="js/tools_ajax.js"></script>
__IfAjax__</head>

<body>
<div class="content">
<h2 class="admin-title">__Menu__管理 − 詳細情報フォーム</h2>
<br />

__IfAdminSystem__<h2 class="navigation"><a href="#" onclick="javascript:move('client_list.php')">&lt;&lt;&lt; クライアントリストへ</a></h2>
__IfAdminSystem__<h2 class="navigation"><a href="#" onclick="javascript:location.href='index.php'">&lt;&lt;&lt; トップページ(メニュー)へ</a></h2>
<br />

<form method="POST" action="menu_list.php" name="mainform">

<table class="common-list" width="700">
	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">__Menu__コード</td>
		<td nowrap class="common-list-value-left">
			__wMenuCD__　(システムが決定しているので変更できません)
		</td>
	</tr>
__IfAdminSystem__	<tr>
		<td nowrap class="common-list-title" width="200">所有クライアント</td>
		<td nowrap class="common-list-value-left">
			<select name="wClientCD">
__ClientLoop__			<option value="__ClientCD__" class="form"__ClientSelected__>__ClientName__　
__ClientLoop__			</select>
		</td>
	</tr>
__IfAdminSystem__	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">__Menu__タイトル</td>
		<td nowrap class="common-list-value-left">
			<input type="text" name="wMenuName" value="__wMenuName__" size="50" __IME_ON__ class="form">
		</td>
	</tr>
	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">略称</td>
		<td nowrap class="common-list-value-left">
			<input type="text" name="wShortName" value="__wShortName__" size="50" __IME_ON__ class="form">
		</td>
	</tr>
<!--	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">所要ライン数</td>
		<td nowrap class="common-list-value-left">
			<input type="text" name="wLines" value="__wLines__" size="50" __IME_ON__ class="form">
		</td>
	</tr>-->
	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">所要時間区分</td>
		<td nowrap class="common-list-value-left">
<select name="wMinuteType">
__MinuteTypeLoop__			<option value="__MinuteTypeValue__"__MinuteTypeSelected__>__MinuteType____MinuteTypeLoop__
			</select>
		</td>
	</tr>

	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">初回登録者</td>
		<td nowrap class="common-list-value-left">
			__Creator__
		</td>
	</tr>
	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">初回登録日時</td>
		<td nowrap class="common-list-value-left">
			__Created__
		</td>
	</tr>
	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">最終更新者</td>
		<td nowrap class="common-list-value-left">
			__Updater__
		</td>
	</tr>
	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">最終更新日時</td>
		<td nowrap class="common-list-value-left">
			__Updated__
		</td>
	</tr>
	<tr id="blockAction">
		<td nowrap class="common-list-value-left" colspan="2">
			
			<input type="button" value="データ更新" class="button" onclick="javascript:moveWithWork('menu_list.php', 1)">　
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