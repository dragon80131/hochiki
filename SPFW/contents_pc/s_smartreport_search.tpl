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

<script type="text/javascript" src="tools.js"></script>

<style>
td.ptb5 {
 padding-top:5px;
 padding-bottom:5px;
}
</style>

</head>
<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
__SHeader__
<hr size="__HRSize__" color="__HRColor__">
<center>《修理センター　スマレポ　修理物件検索》</center>
<hr size="__HRSize__" color="__HRColor__">

<div align="left" style="float:left">
<a href="s_search.php__QUERY__">＜＜　トップ</a></div>
<div align="right" style="float:right">
<a href="s_smartreport_list.php__QUERY__">一覧表示　＞＞</a></div>
<div style="clear:both">



__IfNoResults__
<font color="red">該当データはありません</font><br>
__IfNoResults__

<div align="center">
<form action="s_smartreport_search.php?rKey=__rKey__" method="POST">
<table border=1 class="table" style="width:800px">
<tr><td bgcolor="#0099cc" style="color:#ffffff">受付番号（完全一致）</td>
	<td><input type="number" name="sUketukeNo" value="__sUketukeNo__" size="10"><font size=2 color="gray">　※数字のみ</font></td></tr>
<tr><td bgcolor="#0099cc" style="color:#ffffff">現場名（部分一致）</td>
	<td><input type="text" name="sGenbaName" value="__sGenbaName__" size="30"></td></tr>
<tr><td bgcolor="#0099cc" style="color:#ffffff">マンション名（部分一致）</td>
	<td><input type="text" name="sMansionName" value="__sMansionName__" size=30></td></tr>
</table>

<input type="hidden" name="KensakuDisp" value="1">
<input type="hidden" name="rKey" value="__rKey__">
<input type="submit" name="search" value="検索" class="btn btn-primary btn-lg">
</form>
</div>

<br>


__IfResults__
＜検索結果＞　__AllRows__件<br>

<form action=gen_edit.php method=POST  name="mainform">
<input type="hidden" name="editGenbaCD" value="">
<input type="hidden" name="work" value="">
__HiddenValues__

<table border="1" class="table" style="width:1000px;">
<tr bgcolor="#0099cc" style="color:#ffffff">
	<td style="width:100px">受付番号</td>
	<td>現場名</td>
	<td>マンション名</td>
	<td style="width:100px">依頼日</td>
	<td style="width:100px">報告書</td>
</tr>

__Ifsearch__
__GenbaLoop__
<tr bgcolor="__bc__">
	<td class="ptb5">__UketukeNo__</td>
	<td class="ptb5" style="font-size:90%">__GenbaName__</td>
	<td class="ptb5" style="font-size:90%">__MansionName__</td>
	<td class="ptb5" style="font-size:90%">__Created__</td>
	<td class="ptb5"><input type="button" value="内容確認" class="btn btn-primary btn-sm" onclick="javascript:moveWithKey2('s_smartreport_detail.php?rKey=__rKey__', __GenbaCD__ )"></td>
</tr>
__GenbaLoop__
__Ifsearch__
</table>

</form>
__IfResults__

<br>

<hr size="__HRSize__" color="__HRColor__">
<a href="s_search.php?rKey=__rKey__">トップ</a><br>
<hr size="__HRSize__" color="__HRColor__">
__SFooter__
__SCopyright__
</body>
</html>
