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
<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
__SHeader__

<div class="content-all"><!--content-all-->

<hr size="__HRSize__" color="__HRColor__">
<center>《まいこれ》</center>
<hr size="__HRSize__" color="__HRColor__">

<div class="left-yose">
<a href="s_search.php__QUERY__">＜＜　トップ</a><br>
</div>

物件情報を編集した、物件一覧です。<br><br>

<form action="s_mybukkenlist.php?rKey=__rKey__" method="POST" name="mainform">
<input type="hidden" name="editBukkenCD" value="">

<table class="table table-bordered table-striped table-sm">
<tr>
	<th nowrap>物件CD</th>
	<th nowrap>物件名</th>
	<th nowrap>最新折衝</th>
	<th nowrap>折衝日</th>
	<th nowrap>RN状況</th>
	<th nowrap>最終更新日</th>
</tr>

__myBukkenLoop__
<tr>
	<td>__BukkenCD__</td>
	<td><a href="#" onclick="javascript:moveWithKey('s_menu.php?rKey=__rKey__',__BukkenCD__ )">__BukkenName__</a></td>
	<td>__TaioPhaseName__</td>
	<td>__TaioUpdated__</td>
	<td>__RNstate__</td>
	<td>__Updated__</td>
</tr>
__myBukkenLoop__

</table>
</form>

<br>

<hr size="__HRSize__" color="__HRColor__">
<a href="s_search.php?rKey=__rKey__">トップ</a><br>
<hr size="__HRSize__" color="__HRColor__">
__SFooter__
__SCopyright__


</div><!--content-all-->

</body>
</html>
