<html>
<head>
<title>__Staff__管理 − 詳細情報フォーム</title>

<META http-equiv="Content-Type" content="text/html; charset=euc-jp">
<LINK REL="stylesheet" TYPE="text/css" HREF="style.css">
<script type="text/javascript" src="tools.js"></script>
__IfAjax__<script type="text/javascript" src="js/jquery-1.4.2.js"></script>
<script type="text/javascript" src="js/tools_ajax.js"></script>
__IfAjax__</head>

<body>
<div class="content">
<h2 class="admin-title">__Staff__管理 − 詳細情報フォーム</h2>
<br />

__IfAdminSystem__<h2 class="navigation"><a href="#" onclick="javascript:move('client_list.php')">&gt;&gt;&gt; クライアントリストへ</a></h2>
__IfAdminSystem__<h2 class="navigation"><a href="#" onclick="javascript:location.href='index.php'">&gt;&gt;&gt; トップページ(メニュー)へ</a></h2>
<br />

<form method="POST" action="stylist_list.php" name="mainform">

<table class="common-list" width="700">
	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">__Staff__コード</td>
		<td nowrap class="common-list-value-left">
			__wStylistCD__　(システムが決定しているので変更できません)
		</td>
	</tr>
	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">__Staff__名称</td>
		<td nowrap class="common-list-value-left">
			<input type="text" name="wStylistName" value="__wStylistName__" size="50" __IME_ON__ class="form">
		</td>
	</tr>
	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">稼動ライン数</td>
		<td nowrap class="common-list-value-left">
			<input type="text" name="wNumberOfLines" value="__wNumberOfLines__" size="5" __IME_OFF__ class="form">ライン
		</td>
	</tr>
	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">対応可能__Menu__</td>
		<td nowrap class="common-list-value-left">
__MenuLoop__			<input type="checkbox" name="wMenuCD[]" value="__MenuCD__"__MenuChecked__>__MenuName__<br />
__MenuLoop__		</td>
	</tr>
<!--	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">内自由ライン数</td>
		<td nowrap class="common-list-value-left">
			<input type="text" name="wNumberOfFreeLines" value="__wNumberOfFreeLines__" size="5" __IME_OFF__ class="form">ライン
		</td>
	</tr>
	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">自由枠フラグ</td>
		<td nowrap class="common-list-value-left">
__FreeFlgLoop__			<input type="radio" name="wFreeFlg" value="__FreeFlgValue__" __FreeFlgChecked__>__FreeFlg__　__FreeFlgLoop__
		</td>
	</tr>-->
	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">営業時間</td>
		<td nowrap class="common-list-value-left">
__WeekdayBlock__
		</td>
	</tr>
	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">昼休み</td>
		<td nowrap class="common-list-value-left">
__LunchTimeBlock__
		</td>
	</tr>
	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">夕方休み</td>
		<td nowrap class="common-list-value-left">
__EveningTimeBlock__
		</td>
	</tr>
	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">休日設定</td>
		<td nowrap class="common-list-value-left">
__WeekdayLoop__			<input type="checkbox" name="wHoliday[__Index__]" value="t"__HolidayChecked__>__WeekdayName__&nbsp;&nbsp;
__WeekdayLoop__		</td>
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
			
			<input type="button" value="データ更新" class="button" onclick="javascript:moveWithWork('stylist_list.php', 1)">　
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