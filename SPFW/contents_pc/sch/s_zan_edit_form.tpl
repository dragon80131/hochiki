<html>
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>リニューアル支援</title>

	<!-- BootstrapのCSS読み込み -->
	<link href="../include/bootstrap/css/bootstrap.min.css" rel="stylesheet">

	<!-- jQuery読み込み -->
	<script src="../include/js/jquery-3.2.1.min.js"></script>

	<!-- BootstrapのJS読み込み -->
	<script src="../include/bootstrap/js/bootstrap.min.js"></script>

	<link rel="stylesheet" type="text/css" href="../css/rnsien.css">
	<script type="text/javascript" src="./tools.js"></script>

<link href="css/jquery-ui-1.8.2.custom.css" rel="stylesheet" type="text/css" />
<script src="js/jquery-1.4.2.js" type="text/javascript"></script>
<script src="js/jquery.ui.core.js" type="text/javascript"></script>
<script src="js/jquery.ui.datepicker.js" type="text/javascript"></script>
<script src="js/jquery.ui.datepicker-ja.js" type="text/javascript"></script>
<script src="js/jquery.numberPicker.js" type="text/javascript"></script>

<script type="text/javascript" src="js/tools_ajax.js"></script>


</head>
<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
__SHeader__
<hr size="__HRSize__" color="__HRColor__">
<center>《残工事・現調編集フォーム》</center>
<hr size="__HRSize__" color="__HRColor__">
<br>

作業内容には、物件名、部屋番号や作業内容を記入します。<br>

__IfError__
 __ErrorLoop__
<font color="red" >__ErrorString__<br></font>
 __ErrorLoop__
__IfError__

<form action="s_zan_edit_confirm.php" method="POST">
<table class="table table-bordered table-sm"　width="100%">

<tr><td colspan="2" > __wSagyoDate__</td></tr>
<tr><td>作業分類</td>
<td>

<select name="editZanMenuCD">
__ZanMenuLoop__
 <option value="__ZanMenuCD__" __ZanMenuSelected__>__ZanMenuName__</option>
__ZanMenuLoop__
</select>

</td>
</tr>

<tr><td>作業内容</td><td><textarea cols=40 rows=5 name="editZanScheduleNotes" >__editZanScheduleNotes__</textarea></td></tr>
<tr><td>作業員</td><td>
__SagyoinListLoop__
<input type="checkbox" name="editSagyoinCD[]" value="__SagyoinCD__" __SagyoinChecked__>__SagyoinName__
<br>

__SagyoinListLoop__

</td></tr>
</table>


<br>

<input type=hidden name="rKey" value="__rKey__">

__ZanLoop__
<input type=hidden name="wZanScheduleCD[]" value="__wZanScheduleCD__">
__ZanLoop__

<input type=hidden name="editZanSagyoGroupCD" value="__editZanSagyoGroupCD__">
<input type=hidden name="wSagyoDate" value="__wSagyoDate__">

<input type=submit value = "確　認" class="btn btn-primary" >

</form>




<hr size="__HRSize__" color="__HRColor__">
<a href="s_plan.php__QUERY__">人工表</a><br>
__SFooter__
<hr size="__HRSize__" color="__HRColor__">
__SCopyright__
<br>


</body>

</html>
