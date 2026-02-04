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

<h6>完了報告書B</h6>


スマート工事くんから登録されたファイル
<table class="table table-bordered table-sm">
<tr><th>ファイル名</th>
	<th style="width:200px">登録日</th>
</tr>
__IfNokFile__
<tr><td colspan="3">登録ファイルはありません。 </td></tr>
__IfNokFile__
__kFileLoop__
	<tr>
		<td>__kFileKan__</td>
		<td>__kCreated__</td>
	</tr>
__kFileLoop__
</table>


</div>



</div><!--content-all-->

<hr>

__SFooter__
__SCopyright__

</body>
</html>
