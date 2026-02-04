<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>リニューアル支援</title>

<!-- BootstrapのCSS読み込み -->
<link href="./include/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<script src="./include/js/jquery-3.2.1.min.js"></script>
<link rel="stylesheet" type="text/css" href="./css/rnsien.css">
<script type="text/javascript" src="tools.js"></script>
<link href="css/jquery-ui-1.8.2.custom.css" rel="stylesheet" type="text/css" />
<script src="js/jquery-1.4.2.js" type="text/javascript"></script>
<script src="js/jquery.ui.core.js" type="text/javascript"></script>
<script src="js/jquery.ui.datepicker.js" type="text/javascript"></script>
<script src="js/jquery.ui.datepicker-ja.js" type="text/javascript"></script>
<script src="js/jquery.numberPicker.js" type="text/javascript"></script>

<script type="text/javascript" src="js/tools_ajax.js"></script>
<script type="text/javascript" src="js/ConnectedSelect.js"></script>

</head>

<body>
__SHeader__

<div class="content-all"><!--content-all-->

<div class="left-yose">
<a href="s_menu.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__">＜メニュー</a>
</div>

<div class="top-menu left-yose">
<h5>__BukkenName__</h5>
<h6>作成ファイル・更新ファイル一覧</h6>


__IfFileErrorMessage__<!--関連資料　エラー文言-->
<span style="background-color:yellow" >
<font size=5><b>__FileErrorMessage__</b></font>
</span><br><br>
__IfFileErrorMessage__



以下のファイルを登録しました。<br>
<table class="table-bordered">
<tr><td class="yb">アップロード資料</td>
	<td>
		__FileLoop__
		__image_name__<br>
		__FileLoop__
	</td>
</tr>
</table>


<br>

<form action="s_made_list.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__" method="POST" >
<input type="submit" value=" ファイル一覧へもどる " class="btn btn-primary">
</form>
<br>

<hr>
<input type="button" value="メニューへもどる" onclick="javascript:move('../s_menu.php?editBukkenCD=__editBukkenCD__&rKey=__rKey__' )"  class="btn btn-info"><br>

</div><!--content-all-->



__SFooter__
__SCopyright__

</body>
</html>
