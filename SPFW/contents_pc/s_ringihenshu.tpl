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
<script type="text/javascript" src="tools.js"></script>

</head>

<body>
__SHeader__

<div class="content-all"><!--content-all-->

<div class="left-yose">
<a href="s_menu.php__QUERY__&editBukkenCD=__editBukkenCD__ ">＜＜戻る</a>
</div>


<div class="top-menu left-yose">

<h6>稟議申請編集</h6>


<form action="s_menu.php" method="POST" name="mainform">
	<table style="width:400px;text-align:left" border=1>
			<tr><th>041.その他口座販売に関する稟議書</th>
				<td >
					<select name="SKH_Flg">
						<option value="0" __SKHSelected0__>不要</option>
						<option value="1" __SKHSelected1__>未完了</option>
						<option value="2" __SKHSelected2__>完了</option>
					</select>
				</td></tr>
			<tr><th>　.物件値引稟議申請</th>
				<td >
					<select name="BKN_Flg">
						<option value="1" __BKNSelected1__>未完了</option>
						<option value="2" __BKNSelected2__>完了</option>
					</select>
				</td></tr>
			<tr><th>07.営業協力費・口銭</th>
				<td >
					<select name="KHI_Flg">
						<option value="0" __KHISelected0__>不要</option>
						<option value="1" __KHISelected1__>未完了</option>
						<option value="2" __KHISelected2__>完了</option>
					</select>
				</td></tr>
			<tr><th>21.営業での他社製品仕入請書</th>
				<td >
					<select name="TSR_Flg1">
						<option value="0" __TSRSelected0__>不要</option>
						<option value="1" __TSRSelected1__>未完了</option>
						<option value="2" __TSRSelected2__>完了</option>
					</select>
				</td></tr>
			<tr><th>22.営業での外注工事請負</th>
				<td >
					<select name="TSR_Flg2">
						<option value="4" __TSRSelected4__>未完了</option>
						<option value="5" __TSRSelected5__>完了</option>
					</select>
				</td></tr>
		</table>
	<br>
	<input type="hidden" name="henshu" value="1">
	<input type="hidden" name="rKey" value="__rKey__">
	<input type="hidden" name="editBukkenCD" value="__editBukkenCD__">
	<input type="hidden" name="KenmeiNo" value="__KenmeiNo__">
	<input type="submit" value="登録" class="btn btn-primary">

</form>


</div>



</div><!--content-all-->

<hr>

__SFooter__
__SCopyright__

</body>
</html>
