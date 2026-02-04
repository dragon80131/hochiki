<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- BootstrapのCSS読み込み -->
<link href="./include/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<script src="./include/js/jquery-3.2.1.min.js"></script>
<link rel="stylesheet" type="text/css" href="./css/rnsien.css">
<script type="text/javascript" src="tools.js"></script>

<script>
function moveWithWorkAndFileCD(page, key, work, msg) {
	if (work == 2){
		if (window.confirm(" 削除してもよろしいでしょうか？ ")) {
			document.fileform.editFileCD.value = key;
			document.fileform.work.value = work;
			document.fileform.action = page;
			document.fileform.submit(true);
			document.fileform.work.value = '';
		}
	}
}
</script>
</head>

<body>

<div class="left-yose">

<form action="s_489_file.php" method="POST" name="fileform">
<input type="hidden" name="rKey" value="__rKey__">
<input type="hidden" name="editBukkenCD" value="__editBukkenCD__">
<input type="hidden" name="editFileCD" value="" >
<input type="hidden" name="work" value="" >

<table class="table table-bordered table-sm">
<tr><td>
	<table class="table table-bordered table-sm">
		<tr><td class="yb" colspan="4">登録済ファイル　<font color="red">不要な資料は削除してください</font></td></tr>
			__FileLoop__
			<tr>
				<td style="width:30px">__FileNo__</td>
				<td>__File__</td>
				<td style="width:160px"><font size="2">__Created__</font></td>
				<td style="width:60px"><input type="button" value="削除" class="button" onclick="javascript:moveWithWorkAndFileCD('s_489_file.php',  __FileCD__ ,2 )"></td>
			</tr>
			__FileLoop__
	</table>
	</td>
</tr>
</table>

</form>

</div>

</body>
</html>
