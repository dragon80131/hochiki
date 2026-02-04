<html>
<head>
<title>休日設定管理 − 詳細情報フォーム</title>

<META http-equiv="Content-Type" content="text/html; charset=euc-jp">
<LINK REL="stylesheet" TYPE="text/css" HREF="style.css">
<script type="text/javascript" src="tools.js"></script>

__IfAjax__<link href="css/jquery-ui-1.8.2.custom.css" rel="stylesheet" type="text/css" />
<script src="js/jquery-1.4.2.js" type="text/javascript"></script>
<script src="js/jquery.ui.core.js" type="text/javascript"></script>
<script src="js/jquery.ui.datepicker.js" type="text/javascript"></script>
<script src="js/jquery.ui.datepicker-ja.js" type="text/javascript"></script>
<script src="js/jquery.numberPicker.js" type="text/javascript"></script>

<script type="text/javascript">
$(function() {
	$("#wTarget").datepicker({});
});
</script>
<script type="text/javascript" src="js/tools_ajax.js"></script>
__IfAjax__</head>

<body>
<div class="content">
<h2 class="admin-title">休日設定管理 − 詳細情報フォーム</h2>
<br />

__IfAdminSystem__<h2 class="navigation"><a href="#" onclick="javascript:move('client_list.php')">&lt;&lt;&lt; クライアントリストへ</a></h2>
__IfAdminSystem__<h2 class="navigation"><a href="#" onclick="javascript:location.href='index.php'">&gt;&gt;&gt; トップページ(メニュー)へ</a></h2>
<br />

<form method="POST" action="calendar_list.php" name="mainform">

<table class="common-list" width="700">
	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">設定コード</td>
		<td nowrap class="common-list-value-left">
			__wCalendarCD__　(システムが決定しているので変更できません)
		</td>
	</tr>
	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">スタッフコード</td>
		<td nowrap class="common-list-value-left">
			<select name="wStylistCD">
__StylistLoop__			<option value="__StylistCD__"__StylistSelected__>__StylistName__
__StylistLoop__			</select>
		</td>
	</tr>
	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">設定対象日付</td>
		<td nowrap class="common-list-value-left">
__IfAjax__			<input type="text" name="wTarget" id="wTarget" value="__wTarget__" size="14" maxlength="10" __IME_OFF__ class="form">
__IfAjax____IfNoAjax__			<select name="wTargetYear" size="1">
__YearLoop__			<option value="__YearValue__"__YearSelected__>__YearValue__</option>
__YearLoop__			</select>
			年
			<select name="wTargetMonth" size="1">
__MonthLoop__			<option value="__MonthValue__"__MonthSelected__>__MonthValue__</option>
__MonthLoop__			</select>
			月
			<select name="wTargetDay" size="1">
__DayLoop__			<option value="__DayValue__"__DaySelected__>__DayValue__</option>
__DayLoop__			</select>
			日
__IfNoAjax__		</td>
	</tr>
	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">時間帯</td>
		<td nowrap class="common-list-value-left">
			<select name="wTimeFromHour">
__HoursLoop__			<option value="__HoursValue__"__TimeFromHoursSelected__>__HoursValue__
__HoursLoop__			</select>時
			<select name="wTimeFromMinute">
__MinutesLoop__			<option value="__MinutesValue__"__TimeFromMinutesSelected__>__MinutesValue__
__MinutesLoop__			</select>分
			〜
			<select name="wTimeToHour">
__HoursLoop__			<option value="__HoursValue__"__TimeToHoursSelected__>__HoursValue__
__HoursLoop__			</select>時
			<select name="wTimeToMinute">
__MinutesLoop__			<option value="__MinutesValue__"__TimeToMinutesSelected__>__MinutesValue__
__MinutesLoop__			</select>分<br />
			※　時間帯を選択していない場合には、全日休みの扱いになります。
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
			
			<input type="button" value="データ更新" class="button" onclick="javascript:moveWithWork('calendar_list.php', 1)">　
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