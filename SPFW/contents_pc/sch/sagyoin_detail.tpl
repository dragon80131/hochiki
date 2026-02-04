<html>
<head>
<title>__Sagyoin__管理 − 詳細情報フォーム</title>


<LINK REL="stylesheet" TYPE="text/css" HREF="style.css">
<script type="text/javascript" src="tools.js"></script>
__IfAjax__<script type="text/javascript" src="js/jquery-1.4.2.js"></script>
<script type="text/javascript" src="js/tools_ajax.js"></script>
__IfAjax__</head>

<body>
<div class="content">
<h2 class="admin-title">__Sagyoin__管理 − 詳細情報フォーム</h2>
<br />

<br />

<form method="POST" action="Sagyoin_list.php" name="mainform">

<table class="common-list" width="700">
	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">__Sagyoin__コード</td>
		<td nowrap class="common-list-value-left">
			__wSagyoinCD__　(システムが決定しているので変更できません)
		</td>
	</tr>
	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">作業員　氏名</td>
		<td nowrap class="common-list-value-left">
			<input type="text" name="wSagyoinName" value="__wSagyoinName__" size="50" __IME_ON__ class="form">
		</td>
	</tr>
	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">資格</td>
		<td nowrap class="common-list-value-left">
			<input type="text" name="wSa001" value="__wSa001__" size="50" __IME_ON__ class="form">
		</td>
	</tr>

	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">作業員備考</td>
		<td nowrap class="common-list-value-left">
			<input type="text" name="wSagyoinNotes" value="__wSagyoinNotes__" size="50" __IME_ON__ class="form">
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
			
			<input type="button" value="データ更新" class="button" onclick="javascript:moveWithWork('sagyoin_list.php', 1)">　
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