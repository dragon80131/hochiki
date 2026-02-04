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

<link href="css/jquery-ui-1.8.2.custom.css" rel="stylesheet" type="text/css" />
<script src="js/jquery-1.4.2.js" type="text/javascript"></script>
<script src="js/jquery.ui.core.js" type="text/javascript"></script>
<script src="js/jquery.ui.datepicker.js" type="text/javascript"></script>
<script src="js/jquery.ui.datepicker-ja.js" type="text/javascript"></script>
<script src="js/jquery.numberPicker.js" type="text/javascript"></script>

<script type="text/javascript">
$(function() {
	$("#wTaioDate").datepicker({});
});
</script>
<script type="text/javascript" src="js/tools_ajax.js"></script>


</head>

<body>
__SHeader__

<div class="content-all"><!--content-all-->

<div class="left-yose">
<a href="s_taio_list.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__">＜折衝記録一覧</a>
</div>


<div class="top-menu left-yose">
<h5>__wBukkenName__</h5>
<h6>折衝記録登録</h6>


<font size="5" color="red"><b>折衝記録はまとめて登録せず、<br>一折衝ずつ、フェーズを分けて記入してください。<br></b></font><br>

__IfError__
 __ErrorLoop__
<font color="red" >__ErrorString__<br></font>
 __ErrorLoop__
__IfError__


<form action="s_taio_confirm.php" method="POST" name="mainform" enctype="multipart/form-data"  >

<table class="table table-bordered table-sm">
	<tr><td bgcolor="#e3f0fb" >折衝フェーズ</td>
		<td><select name="wPhaseCD">
			__PhaseLoop__
			<option  value=__PhaseCD__  __PhaseCDSelected__  >__PhaseName__</option>
			__PhaseLoop__
			</select>

			<script type="text/javascript">
				var pulldownNo=1;
			</script></td></tr>
	<tr><td bgcolor="#e3f0fb" >折衝日</td>
		<td><input type=text name="wTaioDate" id="wTaioDate" value="__wTaioDate__" style="width:120px"></td></tr>
	<tr><td bgcolor="#e3f0fb" >担当者</td>
		<td>
			__IfNoKanrisya__
				<select name="wShozokuCD" >
					<option value="" >-</option>
				__ShozokuLoop__
					<option value="__ShozokuCD__"  __ShozokuSelected__ >__ShozokuName__</option>
				__ShozokuLoop__
				</select>
			__IfNoKanrisya__

			担当
			<select name="wTantoCD" >
				<option value="" >--</option>
				__TantoLoop__
				<option value="__TantoCD__"  __TantoSelected__ >__TantoSitenName__　__TantoSyozokuName__　__TantoUserCD__　__TantoName__</option>
				__TantoLoop__
			</select>
	</td><tr>
	<tr><td bgcolor="#e3f0fb">折衝内容</td>
		<td><textarea cols=60 rows=10 name="wTaioNotes" >__wTaioNotes__</textarea></td></tr>
	<tr><td bgcolor="#e3f0fb">関連資料</td>
		<td><table><tr>__FileGencho1__ __FileGencho2__ __FileGencho3__ __FileGencho4__</tr></table>

		<!--20160104 20Mに変更-->
		<input type="hidden" name="MAX_FILE_SIZE" value="20000000" />
		ファイル：<br />
		<input type="file" name="photo[]" size="30" /><br />
		<input type="file" name="photo[]" size="30" /><br />
		<input type="file" name="photo[]" size="30" /><br />
	</td></tr>

	__IfModify__
	<tr><td bgcolor="green" colspan="2">　</td></tr>
	<tr><td bgcolor="#e3f0fb">登録日時</td>
		<td><input type="text" name="wCreated" value="__wCreated__"><br>
			<font color="red">
			<span style="background-color:yellow">リニューアル支援は2016/1/12から開始しました。開始以前の日にちはいれないで！</span><br>
			修正する場合は必ず「0000-00-00 00:00:00」のフォーマットで修正してください。<br>
			例）「2016-01-21 10:44:28」</font>
		</td></tr>
	__IfModify__

</table>


<br>

<input type="hidden" name="rKey" value="__rKey__">
<input type="hidden" name="editTaioCD" value="__editTaioCD__">
<input type="hidden" name="editBukkenCD" value="__editBukkenCD__">

<input type="submit" value="確認画面へ" class="btn btn-primary">

</form>

</div>


</div><!--content-all-->

<hr>

__SFooter__
__SCopyright__

</body>
</html>
