<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>リニューアル支援</title>

<!-- BootstrapのCSS読み込み -->
<link href="./include/bootstrap/css/bootstrap.min.css" rel="stylesheet">

<!-- jQuery読み込み -->
<script src="./include/js/jquery-3.2.1.min.js"></script>

<!-- BootstrapのJS読み込み -->
<script src="./include/bootstrap/js/bootstrap.min.js"></script>

<link rel="stylesheet" type="text/css" href="./css/rnsien.css">
<script type="text/javascript" src="./tools.js"></script>

<link href="./css/jquery-ui-1.8.2.custom.css" rel="stylesheet" type="text/css" >
<script type="text/javascript" src="./js/tools_ajax.js"></script>
<script type="text/javascript" src="./js/ConnectedSelect.js"></script>
<script src="./js/jquery-1.4.2.js" type="text/javascript"></script>
<script src="./js/jquery.ui.core.js" type="text/javascript"></script>
<script src="./js/jquery.ui.datepicker.js" type="text/javascript"></script>
<script src="./js/jquery.ui.datepicker-ja.js" type="text/javascript"></script>

<script type="text/javascript">
$(function() {
	$("#HosyouSdate").datepicker({});
});
</script>

<script >
</script>


</head>

<body>
__SHeader__

<div class="content-all"><!--content-all-->

<div class="left-yose">
<a href="s_kansei_document.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__">＜戻る</a>
</div>


<div class="top-menu left-yose">

<h6>保証書</h6>

<form action="./doc/s_make_hosyousyo_EXCEL.php" method="POST">

<input type="hidden" name="editBukkenCD" value="__editBukkenCD__">

<input type="hidden" name="rKey" value="__rKey__" >


<table border=1 >
<tr><td bgcolor="palegreen" >物件名</td>
	<td><input type="text" name="BukkenName" value="__wBukkenName__"></td></tr>
<tr><td bgcolor="palegreen" >保証期間開始日</td>
	<td><input type="text" name="HosyouSdate" id="HosyouSdate"  value="__wHoliday1__" >
	</td>
</tr>
<tr><td bgcolor="palegreen" >営業所</td>
		<td><!--__selBusyoLoop__<option Value="__selBusyoCD__" __BusyoSelected__ > __selBusyoName__</option>__selBusyoLoop__ -->
			<select name="BusyoCD" id="SearchBusyoCD" style="margin-top:0.5em; padding:0 0.5em;">
				<optgroup label=""><option value="0">-</option></optgroup>
				__selBumonLoop__<optgroup label="__selBumonName__">__selBusyoBlock__</optgroup>__selBumonLoop__
			</select>
	</td>
</tr>

</table>
<input type="hidden" name="wColsBlock" value="__wColsBlock__" >
<input type="hidden" name="RowsLoop" value="__RowsLoop__" >
<!--<input type="hidden" name="Except4" value=1 >
<input type="hidden" name="Except9" value=1 >-->
<input type="hidden" name="rKey" value="__rKey__">
<br>
<input type="submit" onclick="javascript:checkmove('./doc/s_make_hosyousyo_EXCEL.php?editBukkenCD=__editBukkenCD__' )"value="システム保証書作成">

　


</form>
__IfRoomOK__

<br>

</div><!--content-all-->

<hr>

__SFooter__
__SCopyright__

</body>
</html>
