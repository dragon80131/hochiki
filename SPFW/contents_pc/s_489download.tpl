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
<a href="s_menu.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__">＜メニュー</a>
</div>

<div class="top-menu left-yose">

<h6>予約センター更新ファイル</h6>

<!--
<table class="table table-borderless table-sm left-yose">
	<tr><td style="width:100px">物件CD</td><td><b>__editBukkenCD__</b></td></tr>
	<tr><td style="width:100px">物件名</td><td><b>__BukkenName__</b></td></tr>
</table>-->


__IfDelete__
<font color="red">更新ファイルを削除しました。 </font><br><br>
__IfDelete__
__IfNoFile__
<font color="red">更新ファイルはありません。 </font><br><br>
__IfNoFile__

<form action="s_finish.php" method="POST" name="mainform">
<input type="hidden" name="editFileCD" value="" >
<input type="hidden" name="work" value="" >


<table class="table table-bordered table-sm left-yose">
__Ifyotei__
	<tr><td colspan=3 bgcolor="pink">☆予定案内</td></tr>
	__FileLoop_yotei__
	<tr>
		<td>__FileKan_yotei__</td>
		<td>__Created_yotei__</td>
		<td><input type="button" value="削除" class="button" onclick="javascript:moveWithKeyAndWork11( 's_489download.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__', __FileCD_yotei__ , 2  )"></td>
	</tr>
	__FileLoop_yotei__
__Ifyotei__

__Ifketei__
	<tr><td colspan=3><br></td></tr>
	<tr><td colspan=3 bgcolor="lightblue">★決定案内</td></tr>
	__FileLoop_ketei__
	<tr>
		<td>__FileKan_ketei__</td>
		<td>__Created_ketei__</td>
		<td><input type="button" value="削除" class="button" onclick="javascript:moveWithKeyAndWork11( 's_489download.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__', __FileCD_ketei__ , 2  )"></td>
	</tr>
	__FileLoop_ketei__
__Ifketei__

__IfNosplit__
	<tr><td colspan=3><br></td></tr>
	<tr><td colspan=3 bgcolor="lightgrey">●未分類</td></tr>
	__FileLoop_nosplit__
	<tr>
		<td>__FileKan_nosplit__</td>
		<td>__Created_nosplit__</td>
		<td><input type="button" value="削除" class="button" onclick="javascript:moveWithKeyAndWork11( 's_489download.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__', __FileCD_nosplit__ , 2  )"></td>
	</tr>
	__FileLoop_nosplit__
__IfNosplit__
</table>






</div><!--content-all-->


__SFooter__
__SCopyright__

</body>
</html>
