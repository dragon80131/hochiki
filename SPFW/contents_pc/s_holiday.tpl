<html>
<head>
<LINK REL="stylesheet" TYPE="text/css" HREF="css/a5.css">
<script type="text/javascript" src="tools.js"></script>

<link href="css/jquery-ui-1.8.2.custom.css" rel="stylesheet" type="text/css" />
<script src="js/jquery-1.4.2.js" type="text/javascript"></script>
<script src="js/jquery.ui.core.js" type="text/javascript"></script>
<script src="js/jquery.ui.datepicker.js" type="text/javascript"></script>
<script src="js/jquery.ui.datepicker-ja.js" type="text/javascript"></script>
<script src="js/jquery.numberPicker.js" type="text/javascript"></script>

<script type="text/javascript">
$(function() {
	$("#wHolidayDate").datepicker({});

});
</script>

<script type="text/javascript" src="js/tools_ajax.js"></script>


</head>
<title>物件工程表管理</title>
<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
__SHeader__
<hr size="__HRSize__" color="__HRColor__">
<center>《作業員登録管理》</center>
<hr size="__HRSize__" color="__HRColor__">
<br>

<form action="s_sagyoin_detail.php" method="POST" >
<input type=hidden name="rKey" value="__rKey__" >

<a href="#" onclick="javascript:move('s_plan.php?rKey=__rKey__')">＞＞＞　作業工程表</a> <br>

</form>

■新規休日設定<br>
<form action="s_holiday__QUERY__" >
<input type="hidden" name="editSagyoinCD" value="__editSagyoinCD__"  >
<input type="text" name="wHolidayDate" id="wHolidayDate" >
<input type=submit value="休日設定">
</form>
<br><br>
■設定済み休日一覧
<form action="s_sagyoin_list.php" name="mainform" method="POST">
<input type="hidden" name="editHolidayCD" value="" >
<input type="hidden" name="editSagyoinCD" value="__editSagyoinCD__"  >
<input type="hidden" name="work" value="1" >
<table class="sampleTable"><tr style="color:#ffffff" bgcolor="#4169E1" >
<td>No</td>
<td>作業員名</td>
<td>休日</td>
<td>削除</td>

</tr>

__HolidayListLoop__
<tr>
<td>__HolidayCD__</td>
<td>__SagyoinName__</td>
<td>__HolidayDate__</td>
<td><input type="button" value="削除" class="button" onclick="javascript:moveWithKeyAndWork4( 's_Holiday.php?rKey=__rKey__', __HolidayCD__ , 2 , __HolidayCD__ )"></td>
</tr>
__HolidayListLoop__

</tr></table>

</form>

<hr size="__HRSize__" color="__HRColor__">
<a href="s_list.php__QUERY__">物件一覧</a><br>
__SFooter__
<hr size="__HRSize__" color="__HRColor__">
__SCopyright__
<br>
</body>
</html>
