<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>リニューアル支援</title>

<!-- BootstrapのCSS読み込み -->
<link href="./include/bootstrap/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" type="text/css" href="./css/rnsien.css">
<script type="text/javascript" src="tools.js"></script>

<!-- jQuery読み込み -->
<script src="./include/js/jquery-3.2.1.min.js"></script>

<!-- BootstrapのJS読み込み -->
<script src="./include/bootstrap/js/bootstrap.min.js"></script>

<!--datepicker-->
<link href="css/jquery-ui-1.12.1/jquery-ui.min.css" rel="stylesheet">
<link href="css/jquery-ui-1.12.1/jquery-ui.structure.min.css" rel="stylesheet">
<link href="css/jquery-ui-1.12.1/jquery-ui.theme.min.css" rel="stylesheet">
<script src="js/jquery-ui-1.12.1/jquery-ui.min.js"></script>
<script src="js/jquery-ui/datepicker-ja.js"></script>

<script type="text/javascript" src="js/tools_ajax.js"></script>
<script type="text/javascript" src="js/ConnectedSelect.js"></script>

</head>

<body>
__SHeader__

<div class="content-all"><!--content-all-->

<div class="top-menu left-yose">
<h5>__wBukkenName__</h5>
<h6>施工情報登録</h6>


__IfOK__
	施工情報を登録しました。
__IfOK__

<br><br>


<form action="s_menu.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__" method="POST">
<input type="submit" value="メニュー" class="btn btn-info">
</form>


</div>



</div><!--content-all-->

<hr>

__SFooter__
__SCopyright__

</body>
</html>
