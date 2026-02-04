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

</head>

<body>
__SHeader__

<div class="content-all"><!--content-all-->



<div class="top-menu left-yose">
<h5>__BukkenName__</h5>
<h6>折衝記録登録</h6>

<br>
折衝記録を登録しました。<br>
<br><br>

<form action="s_taio_list.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__" method="POST">
<input type="submit" value="折衝記録一覧へ" class="btn btn-info">
</form>

</div>

</div><!--content-all-->

<hr>

__SFooter__
__SCopyright__

</body>
</html>
