<!DOCTYPE html>
<html lang="ja">
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
<script type="text/javascript" src="../tools.js"></script>

<link href="../css/jquery-ui-1.8.2.custom.css" rel="stylesheet" type="text/css" />
<style>
	font1 {font-size : 77%}
</style>


<script src="../js/jquery-1.4.2.js" type="text/javascript"></script>
<script src="../js/jquery.ui.core.js" type="text/javascript"></script>
<script src="../js/jquery.ui.datepicker.js" type="text/javascript"></script>
<script src="../js/jquery.ui.datepicker-ja.js" type="text/javascript"></script>
<script src="../js/jquery.numberPicker.js" type="text/javascript"></script>
<script type="text/javascript">
$(function() {
	$(".datepicker").datepicker({
		numberOfMonths: 2
	});
});
$(function() {
	var value1 = $("#SenyuStartDate").val();
	var value2 = $("#SenyuEndDate").val();
	$(".datepicker2").datepicker({
		numberOfMonths: 1,
		minDate: new Date(value1),
		maxDate: new Date(value2)
	});
});
function datacheck(num){
	if(document.getElementById("Otherwise"+num+"Id").value !==""){
		document.getElementById("Otherwise"+num+"FlgId").checked = true;
	}else{
		document.getElementById("Otherwise"+num+"FlgId").checked = false;
		document.getElementById("wDateS"+num+"Id").value = "";
		document.getElementById("wDateE"+num+"Id").value = "";
	}
}
</script>
</head>

<body>
__SHeader__

<div class="content-all"><!--content-all-->

<div class="left-yose">
<a href="../s_menu.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__">＜戻る</a>
</div>


<div class="top-menu left-yose">
<h5>__BukkenName__</h5>
<h6>全体工程表</h6>


<form action="s_zentaikoteihyo_Excel.php" method="POST" name="mainform">
<input type="hidden" name="editBukkenCD" value="__editBukkenCD__" >
<input type="hidden" name="rKey" value="__rKey__" >

実施日が1日の場合は、完了日の入力は不要です。<br>
__IfError____ErrorLoop__
<font color="red">__ErrorStrings__<br></font>
__ErrorLoop____IfError__

<table class="table table-bordered">
<tr><th colspan="3">日程<font color="red">　＊必須</font></th></tr>
<tr><td class="yb" >案内配布日</td>
	<td>
		<input type="hidden" name="wAnnaiDate" value="__wAnnaiDate__"  >
		__wAnnaiDate__
	</td>
	<td class="yb">専有部工事日より25日前</td>
</tr>
<tr><td class="yb">受付締切日</td>
	<td>
		<input type="hidden" name="wReceptionDate" value="__wReceptionDate__" >
		__wReceptionDate__
	</td>
	<td class="yb">専有部工事日より11日前</td>
</tr>
<tr><td class="yb">確定案内配布日</td>
	<td>
		<input type="text" name="wKakuteiDate" value="__wKakuteiDate__" style="width:100px" class="datepicker" >
	</td>
	<td class="yb">専有部工事日より7日前</td>
</tr>
<tr><td class="yb">共用部工事</td>
	<td>
		<input type="hidden" name="wKyoyoStartDate" value="__wKyoyoStartDate__" >
		__wKyoyoStartDate__ ～
	</td>
	<td>
		<input type="hidden" name="wKyoyoEndDate" value="__wKyoyoEndDate__" >
		__wKyoyoEndDate__
	</td>
</tr>
<tr><td class="yb">専有部工事</td>
	<td>
		<input type="hidden" id="SenyuStartDate" name="wSenyuStartDate" value="__wSenyuStartDate__" >
		__wSenyuStartDate__ ～
	</td>
	<td>
		<input type="hidden" id="SenyuEndDate" name="wSenyuEndDate" value="__wSenyuEndDate__" >
		__wSenyuEndDate__
	</td>
</tr>

<tr>
	<th colspan="3">
		その他、日程　
		※工程表に記載する場合はチェックを入れてください。<br>
		　　　　　　　　（チェック無の場合でもデータとして保存されます。）
	</th>
</tr>
<tr><td class="yb">
		<input type="checkbox" name="wOtherwise6Flg" id="Otherwise6FlgId" value="1" __wOtherwise6FlgChecked__>
		<input type="text" name="wOtherwise6" value="__wOtherwise6__" id="Otherwise6Id" list="wOtherwise6List" placeholder="テキスト入力もしくクリックでリストから選択" autocomplete="off" style="width:400px" onchange="datacheck(6)">
		<datalist id="wOtherwise6List">
			__OtherwiseLoop__
					__IfDate__<option >__OTHERWISEDATE__</option>__IfDate__
			__OtherwiseLoop__
		</datalist>
	</td>
	<td>
		<input type="text" name="wDateS6" id="wDateS6Id" value="__wDateS6__" style="width:100px" class="datepicker" >
	</td>
	<td>～
		<input type="text" name="wDateE6" id="wDateE6Id" value="__wDateE6__" style="width:100px" class="datepicker">
	</td>
</tr>
<tr><td class="yb">
		<input type="checkbox" name="wOtherwise7Flg" id="Otherwise7FlgId" value="1" __wOtherwise7FlgChecked__>
		<input type="text" name="wOtherwise7" value="__wOtherwise7__" id="Otherwise7Id" list="wOtherwise7List" placeholder="テキスト入力もしくクリックでリストから選択" autocomplete="off" style="width:400px" onchange="datacheck(7)">
		<datalist id="wOtherwise7List">
			__OtherwiseLoop__
					__IfDate__<option >__OTHERWISEDATE__</option>__IfDate__
			__OtherwiseLoop__
		</datalist>
	</td>
	<td>
		<input type="text" name="wDateS7" id="wDateS7Id" value="__wDateS7__" style="width:100px" class="datepicker">
	</td>
	<td>～
		<input type="text" name="wDateE7" id="wDateE7Id" value="__wDateE7__" style="width:100px" class="datepicker">
	</td>
</tr>
<tr><td class="yb">
		<input type="checkbox" name="wOtherwise8Flg" id="Otherwise8FlgId" value="1" __wOtherwise8FlgChecked__>
		<input type="text" name="wOtherwise8" value="__wOtherwise8__" id="Otherwise8Id" list="wOtherwise8List" placeholder="テキスト入力もしくクリックでリストから選択" autocomplete="off" style="width:400px" onchange="datacheck(8)">
		<datalist id="wOtherwise8List">
			__OtherwiseLoop__
					__IfDate__<option >__OTHERWISEDATE__</option>__IfDate__
			__OtherwiseLoop__
		</datalist>
	</td>
	<td>
		<input type="text" name="wDateS8" id="wDateS8Id" value="__wDateS8__" style="width:100px" class="datepicker">
	</td>
	<td>～
		<input type="text" name="wDateE8" id="wDateE8Id" value="__wDateE8__" style="width:100px" class="datepicker">
	</td>
</tr>
<tr><td class="yb">
		<input type="checkbox" name="wOtherwise9Flg"  id="Otherwise9FlgId" value="1" __wOtherwise9FlgChecked__>
		<input type="text" name="wOtherwise9" value="__wOtherwise9__" id="Otherwise9Id" list="wOtherwise9List" placeholder="テキスト入力もしくクリックでリストから選択" autocomplete="off" style="width:400px" onchange="datacheck(9)">
		<datalist id="wOtherwise9List">
			__OtherwiseLoop__
					__IfDate__<option >__OTHERWISEDATE__</option>__IfDate__
			__OtherwiseLoop__
		</datalist>
	</td>
	<td>
		<input type="text" name="wDateS9" id="wDateS9Id" value="__wDateS9__" style="width:100px" class="datepicker">
	</td>
	<td>～
		<input type="text" name="wDateE9" id="wDateE9Id" value="__wDateE9__" style="width:100px" class="datepicker">
	</td>
</tr>
<tr><td class="yb">
		<input type="checkbox" name="wOtherwise10Flg" id="Otherwise10FlgId" value="1" __wOtherwise10FlgChecked__>
		<input type="text" name="wOtherwise10" value="__wOtherwise10__" id="Otherwise10Id" list="wOtherwise10List" placeholder="テキスト入力もしくクリックでリストから選択" autocomplete="off" style="width:400px" onchange="datacheck(10)">
		<datalist id="wOtherwise10List">
			__OtherwiseLoop__
					__IfDate__<option >__OTHERWISEDATE__</option>__IfDate__
			__OtherwiseLoop__
		</datalist>
	</td>
	<td>
		<input type="text" name="wDateS10" id="wDateS10Id" value="__wDateS10__" style="width:100px" class="datepicker">
	</td>
	<td>～
		<input type="text" name="wDateE10" id="wDateE10Id" value="__wDateE10__" style="width:100px" class="datepicker">
	</td>
</tr>
<tr><th colspan="3">休工日(__wHolidaySuu__日)</th></tr>
<tr><td colspan="3">
__KyukoTable__
<input type="hidden" name="Holiday" value="__Holiday__">
</td>
</tr>
<tr><th colspan="3">備考</th></tr>
<tr><td colspan="3"><font1><textarea style="width:100%;" rows="10"  name="wKojiBiko" >__wKojiBiko__</textarea></font1></td></tr>
</table>

<input type="submit" value="登録＆ファイル出力"  class="btn btn-primary blue" >
<br>※作成後、印刷プレビューで確認ください。

</form><br>

<br>

<hr>
<input type="button" value="メニューへもどる" onclick="javascript:move('../s_menu.php?editBukkenCD=__editBukkenCD__&rKey=__rKey__' )"  class="btn btn-info"><br>

</div><!--content-all-->


__SFooter__
__SCopyright__

</body>
</html>
