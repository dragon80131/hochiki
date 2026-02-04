<html>
<head>
<title>部屋番号検索</title>

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
	$("#sBirthday").datepicker({defaultDate: '__DEFAULTDATE__'});
	$("#sJoinedF").datepicker({});
	$("#sJoinedT").datepicker({});
	$("#sWithdrawnF").datepicker({});
	$("#sWithdrawnT").datepicker({});
__IfDate1__		$("#sQuestion1").datepicker({});
__IfDate1____IfDate2__		$("#sQuestion2").datepicker({});
__IfDate2____IfDate3__		$("#sQuestion3").datepicker({});
__IfDate3____IfDate4__		$("#sQuestion4").datepicker({});
__IfDate4____IfDate5__		$("#sQuestion5").datepicker({});
__IfDate5____IfDate6__		$("#sQuestion6").datepicker({});
__IfDate6____IfDate7__		$("#sQuestion7").datepicker({});
__IfDate7____IfDate8__		$("#sQuestion8").datepicker({});
__IfDate8____IfDate9__		$("#sQuestion9").datepicker({});
__IfDate9____IfDate10__		$("#sQuestion10").datepicker({});
__IfDate10__
	$("#sAgeF").numberPicker({startNum: 0, endNum: 100, step: 1});
	$("#sAgeT").numberPicker({startNum: 0, endNum: 100, step: 1});
});
</script>
<script type="text/javascript" src="js/tools_ajax.js"></script>
__IfAjax__</head>

<body>
<div class="content">
<h2 class="admin-title">部屋番号検索</h2>
<br />



<h2 class="navigation"><a href="index.php">&lt;&lt;&lt; トップメニューへ</a></h2>
__IfSetting__<br />
<h2 class="navigation"><a href="user_set_menu.php">&gt;&gt;&gt; システム設定メニュー</a></h2>__IfSetting__
<br />

<form method="POST" action="reserve_detail.php" name="mainform">

<table class="common-list" width="400">


__IfAdminSystem__	<tr>
		<td nowrap class="common-list-title" width="200">クライアント</td>
		<td nowrap class="common-list-value-left">
			<select name="sClientCD">
__ClientLoop__			<option value="__ClientCD__" class="form"__ClientSelected__>__ClientName__　
__ClientLoop__			</select>
		</td>
	</tr>__IfAdminSystem__



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