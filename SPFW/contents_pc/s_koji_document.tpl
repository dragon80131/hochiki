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
<a href="./s_menu.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__">＜メニュー</a>
</div>


<div class="top-menu left-yose">
<h5>__wBukkenName__</h5>
<h6>その他資料</h6>


<form action=# method="POST" name="mainform" >
<input type="hidden" name="editBukkenCD" value="__editBukkenCD__">

<a href="#" class="square_btn" onclick="javascript:move('./s_kyutoki_form.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__') ">給湯器外し案内</a>
<br><br>

<a href="#" class="square_btn" onclick="#">☆施工計画書</a>
<br><br>

</form>

</div>



</div><!--content-all-->



__SFooter__
__SCopyright__

</body>
</html>
