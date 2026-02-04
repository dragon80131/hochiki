<html>
<head>
<title>物件工程表管理</title>
<LINK REL="stylesheet" TYPE="text/css" HREF="style.css">


<script type="text/javascript" src="tools.js"></script>

<link href="css/jquery-ui-1.8.2.custom.css" rel="stylesheet" type="text/css" />
<script src="js/jquery-1.4.2.js" type="text/javascript"></script>
<script src="js/jquery.ui.core.js" type="text/javascript"></script>
<script src="js/jquery.ui.datepicker.js" type="text/javascript"></script>
<script src="js/jquery.ui.datepicker-ja.js" type="text/javascript"></script>
<script src="js/jquery.numberPicker.js" type="text/javascript"></script>

<script type="text/javascript">
$(function() {
	$("#wBirthday").datepicker({defaultDate: '__DEFAULTDATE__'});
	$("#wJoined").datepicker({});
	$("#wWithdrawn").datepicker({});

	$("#wKyoyoStartDate").datepicker({});
	$("#wKyoyoEndDate").datepicker({});
	$("#wSenyuStartDate").datepicker({});
	$("#wSenyuEndDate").datepicker({});

	$("#wJoinedHour").numberPicker({startNum: 0, endNum: 23, step: 1});
	$("#wJoinedMinute").numberPicker({startNum: 0, endNum: 59, step: 1});
	$("#wJoinedSecond").numberPicker({startNum: 0, endNum: 59, step: 1});
	$("#wWithdrawnHour").numberPicker({startNum: 0, endNum: 23, step: 1});
	$("#wWithdrawnMinute").numberPicker({startNum: 0, endNum: 59, step: 1});
	$("#wWithdrawnSecond").numberPicker({startNum: 0, endNum: 59, step: 1});
});
</script>
<script type="text/javascript" src="js/tools_ajax.js"></script>


</head>
<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
__SHeader__
<hr size="__HRSize__" color="__HRColor__">
<center>《残工事・現調追加入力フォーム》</center>
<hr size="__HRSize__" color="__HRColor__">
<br>

以下の内容を削除します。<br>

<br>
作業日：__wSagyoDate__<br>
作業分類：__wZanMenuCD__<br>
作業内容：__wZanScheduleNotes__<br>
作業者：

__ZanLoop__

__SagyoinCD__ 
__SagyoinName__ 

__ZanLoop__


__IfError__
 __ErrorLoop__
<font color="red" >__ErrorString__<br></font>
 __ErrorLoop__
__IfError__

<form action="s_zan_del_finish.php" method="POST">
<!--
<table border=1>

<tr><td colspan="2" > __tYear__年 __tMonth__月 __editDateCD__日</td></tr>
<tr><td  >作業分類</td>
<td>


<select name="wZanMenuCD">

<option value=1 __ZanMenuCDselected1__ >残工事</option>
<option value=2 __ZanMenuCDselected2__ >現場調査</option>
<option value=3 __ZanMenuCDselected3__ >その他</option>
</select>

</td>
</tr>

<tr><td>作業内容</td><td><textarea cols=40 rows=5 name="wZanScheduleNotes" >__wZanScheduleNotes__</textarea></td></tr>
<tr><td>作業員</td><td>
__SagyoinListLoop__
<input type="checkbox" name="wSagyoinCD[]" value="__SagyoinCD__" __wSagyoinChecked__>__SagyoinName__
__SagyoinListLoop__

</td></tr>


</table>
-->

<br>

<input type=hidden name="rKey" value="__rKey__">
<input type=hidden name="editDateCD" value="__editDateCD__">
<input type=hidden name="tMonth" value="__tMonth__">
<input type=hidden name="tYear" value="__tYear__">
<input type="hidden" name="work" value="2">
<input type="hidden" name="editZanSagyoGroupCD" value="__editZanSagyoGroupCD__">

<input type=submit value = "削　除" >

</form>




<hr size="__HRSize__" color="__HRColor__">
<a href="s_list.php__QUERY__">物件一覧</a><br>
__SFooter__
<hr size="__HRSize__" color="__HRColor__">
__SCopyright__
<br>


</body>

</html>
