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
<a href="s_kansei_document.php__QUERY__&editBukkenCD=__editBukkenCD__ ">＜＜戻る</a>
</div>


<div class="top-menu left-yose">
<h5>__BukkenName__</h5>

<h6>工事完了確認書</h6>

※完了確認書は、iPad等のタブレット専用アプリから登録可能です。<br>

<!--
<form action="kan3.php" method="post">
__HiddenValues__
<input type="submit" value="PDFダウンロード" class="btn btn-primary">
</form>-->

<br>


<table align="center">
<tr>
	<td nowrap class="common-list-value" colspan="__ColSpan__">
		<form method="post" action="s_kan2.php" name="mainform">
		<input type="hidden" name="rKey" value=__rKey__ >
		<input type="hidden" name="editBukkenCD" value="__editBukkenCD__" >

		__IfToTop__<a href="#" onClick="javascript:changePage('s_kan2.php', 1, __AllPages__)">__IfToTop__&lt;&lt;__IfToTop__</a>__IfToTop__　
		__IfToPre__<a href="#" onClick="javascript:changePage('s_kan2.php', __PreviousPage__, __AllPages__)">__IfToPre__&lt;__IfToPre__</a>__IfToPre__　
		<input type="text" name="myPage" value="__myPage__" size="4" __IME_OFF__ class="formpjjp">/__AllPages__
		<input type="button" value="ページジャンプ" class="button" onclick="javascript:changePage('s_kan2.php', '0', __AllPages__)">　
		__IfToNext__<a href="#" onClick="javascript:changePage('s_kan2.php', __NextPage__, __AllPages__)">__IfToNext__&gt;__IfToNext__</a>__IfToNext__　
		__IfToLast__<a href="#" onClick="javascript:changePage('s_kan2.php', __LastPage__, __AllPages__)">__IfToLast__&gt;&gt;__IfToLast__</a>__IfToLast__

		</form>
	</td>
</tr>
</table>
<br>


<form action="#" method="post">
__HiddenValues__

<table class="table table-bordered table-sm">
<tr align="center">
	<th>部屋</th>
	<th>工事完了確認書</th>
	<th>受領日付</th>
</tr>
__RecoLoop__
<tr align="center" __KakuninColor__>
	<td>__ID__</td>
	<td>__Kakunisho__</td>
	<td>__KojiDateTime__</td>
</tr>
__RecoLoop__
</table>

</form>


スマート工事くんから登録されたファイル
<table class="table table-bordered table-sm">
<tr><th>ファイル名</th>
	<th style="width:200px">登録日</th>
</tr>
__IfNokFile__
<tr><td colspan="3">スマート工事くんから登録された工事確認書はありません。 </td></tr>
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
