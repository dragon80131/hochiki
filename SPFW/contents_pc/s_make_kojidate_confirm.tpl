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

</head>

<body>
__SHeader__

<div class="content-all"><!--content-all-->

<div class="left-yose">
<a href="s_make_kojidate.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&editBuildingCD=__editBuildingCD__">＜部屋一覧へ戻る</a>
</div>


<div class="top-menu left-yose">
<h5>__BukkenName__ __wBuildingName__</h5>
<h6>仮日程表 - 修正画面</h6>

日程と時間を変更し、[更新]ボタンを押してください。<br>
日程は専有部工事期間を登録してください。<br><br>

専有部期間：__SenyuStartDate__　～　__SenyuEndDate__<br>

<br>
<form action="#" method="POST" name="mainform">

<table class="table table-bordered table-sm" style="font-size:12px; width:400px;">
<tr bgcolor="lightgray">
	<th>部屋番号</th><th>日程</th><th>開始時間</th></tr>
<tr>
	<td>__wUserCD__</td>
	<td><input type="text" name="wTimeFromDate" value="__wTimeFromDate__" style="width:100px;"></td>
	<td>
		<select name="wTimeFromTime">
			__StartLoop__
			<option value="__StartTime__" __StartTimeSelected__>__StartTime__</option>
			__StartLoop__
		</select>
	</td>
</tr>
</table>

<br>
<input type="hidden" name="work" value="1">
<button type="button" onclick="javascript:move('s_make_kojidate.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&editKojiDateCD=__KojiDateCD__&editBuildingCD=__editBuildingCD__')">更新する</button></td>
</form>

</div>

</div><!--content-all-->
__SFooter__
__SCopyright__

</body>
</html>
