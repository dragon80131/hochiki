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

<script>
function moveWithKeyAndWork(page, key, work, msg) {
	if (work == 2){
		if (window.confirm(" 「残工事なし」に修正してもよろしいでしょうか？ ")) {
			document.mainform.editBukkenCD.value = key;
			document.mainform.work.value = work;
			document.mainform.action = page;
			document.mainform.submit(true);
			document.mainform.work.value = '';
		}
	}
}
</script>
</head>

<body>
__SHeader__

<div class="content-all"><!--content-all-->

<div class="left-yose">
<a href="s_menu.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__">＜メニュー</a>
</div>


<div class="top-menu left-yose">
<h5>__BukkenName__</h5>
<h6>残工事情報</h6>


__IfNoData__
データがありません。
__IfNoData__


<form action="s_zan.php" method="POST" name="mainform" >
<input type="hidden" name="editBukkenCD" value="__editBukkenCD__" >
<input type="hidden" name="work" value="" >
<input type="hidden" name="rKey" value="__rKey__">


__IfZanHeyaNasi__ 残工事情報はありません。<br><br> __IfZanHeyaNasi__

__IfZanRenkeiMi__
残工事部屋数：__ZanHeyaSuu__<br><br>
__IfZanRenkeiMi_Message__
	残連携処理がまだおこなわれておりません。<br>
	残工事修正したい場合は、残連携処理終了後に修正することが可能です。<br>
	残連携処理はネスペが行います。<br>
__IfZanRenkeiMi_Message__
<br><br> 
__IfZanRenkeiMi__


__IfZanHeya__<!--tBukkenMのZanHeyaがある場合-->
<table class="table table-bordered table-sm">
<tr><td nowrap bgcolor="lightgrey">残工事部屋</td>
	<td>__IfNoZan__残工事なし__IfNoZan__ 
		__IfZan__ __ZanHeyaDisp__ __IfZan__
	</td>
</tr>

__IfZan__
<tr><td nowrap bgcolor="lightgrey">機器保管場所</td>
	<td>__MansionMemo__</td></tr>
<!--	
<tr><td nowrap bgcolor="lightgrey">残工事修正</td>
	<td><input type="button" value="残工事なしに修正" class="button" onclick="javascript:moveWithKeyAndWork('s_zan.php?rKey=__rKey__', __BukkenCD__ ,2 )"></td></tr>
-->
__IfZan__


__IfPicExist__
<tr><td nowrap bgcolor="lightgrey">保管場所写真</td>
	<td>__HokanImage__</td></tr>
__IfPicExist__


</table>
__IfZanHeya__<!--tBukkenMのZanHeyaがある場合-->




<br>
</form>




</div><!--content-all-->


__SFooter__
__SCopyright__

</body>
</html>
