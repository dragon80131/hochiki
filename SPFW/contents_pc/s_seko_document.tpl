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

<form action=# method="POST" name="mainform" >
<input type="hidden" name="editBukkenCD" value="__editBukkenCD__">

<div class="top-menu left-yose">
<h5>__wBukkenName__</h5>
<h6>その他資料</h6>

<input type="button" onclick="javascript:move('__filepath1__')" value="__filename1__" class="square_btn" ><br><br>
<input type="button" onclick="javascript:move('__filepath2__')" value="__filename2__" class="square_btn" ><br><br>


<h6>消防申請</h6>
<input type="hidden" name="filename3" value="shoubousyorui170.xls">
<input type="hidden" name="filedir3" value="__filedir3__">
<input type="button" onclick="javascript:move('__filepath3__')" value="__filename3__" class="square_btn" ><br><br>

<input type="hidden" name="filename4" value="shoubousyorui220.xls">
<input type="hidden" name="filedir4" value="__filedir4__">
<input type="button" onclick="javascript:move('__filepath4__')" value="__filename4__" class="square_btn" ><br><br>

<h6>産廃書類</h6>
<input type="hidden" name="filename5" value="sanpaikeitaishomen.xls">
<input type="hidden" name="filedir5" value="__filedir5__">
<input type="button" onclick="javascript:move('__filepath5__')" value="__filename5__" class="square_btn" ><br><br>

<input type="hidden" name="filename6" value="sanpaigaisansheet.xls">
<input type="hidden" name="filedir6" value="__filedir6__">
<input type="button" onclick="javascript:move('__filepath6__')" value="__filename6__" class="square_btn" ><br><br>


</form>

</div>



</div><!--content-all-->

<hr>

__SFooter__
__SCopyright__

</body>
</html>
