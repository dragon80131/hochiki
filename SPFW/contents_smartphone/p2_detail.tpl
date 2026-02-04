<html>
<head>
<title>専有部装置 詳細情報フォーム</title>

<META http-equiv="Content-Type" content="text/html; charset=euc-jp">
<LINK REL="stylesheet" TYPE="text/css" HREF="style.css">
<script type="text/javascript" src="tools.js"></script>
__IfAjax__<script type="text/javascript" src="js/jquery-1.4.2.js"></script>
<script type="text/javascript" src="js/tools_ajax.js"></script>
__IfAjax__</head>

<body>
<div class="content">
<h2 class="admin-title">専有部装置管理 − 詳細情報フォーム</h2>
<br />


<form method="POST" action="p2_list.php" name="mainform">

<table class="common-list" width="700">
	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">追加装置コード</td>
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
		<td nowrap class="common-list-title" width="200">追加装置名</td>
		<td nowrap class="common-list-value-left">
			<input type="text" name="wMenuName" value="__wMenuName__" size="50" __IME_ON__ class="form">
		</td>
	</tr>


	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">施工前・後</td>
		<td nowrap class="common-list-value-left">

__ShortNameLoop__
<input type="radio" name="wShortName" value="__ShortNameValue__"__ShortNameChecked__>__ShortName__
__ShortNameLoop__




		</td>
	</tr>

	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">利用用途</td>
		<td nowrap class="common-list-value-left">
__NotesLoop__
<input type="radio" name="wNotes" value="__NotesValue__"__NotesChecked__>__Notes__
__NotesLoop__

		</td>
	</tr>

	<input type=hidden name="wMinuteType" value="60" >


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
			
			<input type="button" value="データ更新" class="button" onclick="javascript:moveWithWork('p2_list.php', 1)">　

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