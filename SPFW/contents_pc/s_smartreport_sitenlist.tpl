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
td.ptb2 {
 padding-top:2px;
 padding-bottom:2px;
}
</style>

</head>
<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
__SHeader__
<hr size="__HRSize__" color="__HRColor__">
<center>《修理センター　スマレポ　修理物件一覧》</center>
<hr size="__HRSize__" color="__HRColor__">

<div align="left">
<a href="s_search.php__QUERY__">＜＜　トップ</a><br>
<a href="s_smartreport_list.php__QUERY__">＜＜　戻る</a><br>
</div>

<table border="1" class="table" style="width:400px;font-size:80%">
<tr><th class="ptb5">支店</th><th class="ptb5">都道府県</th></tr>
<tr><td class="ptb2">札幌　</td><td class="ptb2">北海道</td></tr>
<tr><td class="ptb2">北関東</td><td class="ptb2">埼玉県</td></tr>
<tr><td class="ptb2">東京　</td><td class="ptb2">東京都</td></tr>
<tr><td class="ptb2">横浜　</td><td class="ptb2">神奈川県</td></tr>
<tr><td class="ptb2">名古屋</td><td class="ptb2">愛知県</td></tr>
<tr><td class="ptb2">大阪　</td><td class="ptb2">大阪府・京都府・兵庫県</td></tr>
</table>

__IfNoSelect__選択されていません<br>__IfNoSelect__
<br>


<form action="s_smartreport_detail.php?rKey=__rKey__" method=POST name="mainform">
<input type="hidden" name=editGenbaCD value="">
<input type="hidden" name=work value="">

<table border="1" class="table" style="width:1000px;">
<tr bgcolor="#0099cc" style="color:#ffffff">
	<td style="width:100px">受付番号</td>
	<td style="width:80px">県</td>
	<td>現場名</td>
	<td>マンション名</td>
	<td style="width:100px">依頼日</td>
	<td style="width:100px">報告書</td>
</tr>

__GenbaLoop__
<tr bgcolor="__bc__">
	<td class="ptb5">__UketukeNo__</td>
	<td class="ptb5" style="font-size:90%">__Pref__</td>
	<td class="ptb5" style="font-size:90%">__GenbaName__</td>
	<td class="ptb5" style="font-size:90%">__MansionName__</td>
	<td class="ptb5" style="font-size:90%">__Created__</td>
	<td class="ptb5"><input type="button" value="内容確認" class="btn btn-primary btn-sm" onclick="javascript:moveWithKey2('s_smartreport_detail.php?rKey=__rKey__', __GenbaCD__ )"></td>
</tr>
__GenbaLoop__

</table>
</form>

<br>

<hr size="__HRSize__" color="__HRColor__">
<a href="s_search.php?rKey=__rKey__">トップ</a><br>
<hr size="__HRSize__" color="__HRColor__">
__SFooter__
__SCopyright__
</body>
</html>
