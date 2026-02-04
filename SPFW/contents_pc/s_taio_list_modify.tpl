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

<script>
function moveWithWorkWithBukkenCD(page, work, key) {
	document.mainform2.editBukkenCD.value = key;
	document.mainform2.work.value = work;
	document.mainform2.action = page;
	document.mainform2.submit(true);
}
</script>

<style>
body {
	background-color:lightyellow;
}
</style>
</head>

<body>
__SHeader__

<div class="content-all"><!--content-all-->

<div class="left-yose">
<a href="s_taio_list.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__">＜一覧へ</a>
</div>


<div class="top-menu left-yose">
<h5>__wBukkenName__</h5>
<h6>折衝記録一覧</h6>



<form action="s_taio_confirm.php" method="POST" name="mainform2">
<input type="hidden" name="editBukkenCD" value="__editBukkenCD__" >
<input type="hidden" name="editTaioCD" value="" >
<input type="hidden" name="work" value="" >
<input type="hidden" name="rKey" value="__rKey__">



__IfNoRecord__<!--↓登録なし-->
折衝記録は登録されておりません。<br>
__IfNoRecord__<!--↑登録なし-->


__IfRecord__<!--↓登録あり-->
<font color=red >添付ファイルを削除するには、修正ボタンをクリックし、削除したい添付ファイルのチェックボックスを選択します。<br>
ただし、添付ファイルが完全に削除されてしまうので、念のためパソコンに保存してください。問題なければ、<br>
パソコンのファイルを削除する。</font><br>
<table class="table table-bordered table-sm" style="font-size : 13px;">

__TaioListLoop__
<tr><td bgcolor="#e3f0fb">フェーズ</td><td>__Phase__</td>
	<td bgcolor="#e3f0fb">折衝日時</td><td colspan="3">__TaioDate__</td>
	<td bgcolor="#e3f0fb">登録日時</td><td>__Created__</td>
</tr>
<tr><td bgcolor="#e3f0fb">支店所属名</td><td>__SitenEigyoName__</td>
	<td bgcolor="#e3f0fb">折衝担当</td><td colspan="5">__TantoName__</td>
</tr>
<tr><td bgcolor="#e3f0fb" colspan=1>内容</td>
	<td colspan="7" width="650" >__DispTaioNotes__</td>
</tr>
<tr><td bgcolor="#e3f0fb" colspan="1">添付ファイル</td>
   <td colspan="7">
<!--	__LinesLoop__
		__FileGencho__ 
	__LinesLoop__
-->
	__LinesBlock__
    </td>
</tr>
<tr><td colspan="2">
		<input type="button" value="修　正" class="button" onclick="javascript:moveWithTaioCD('s_taio_form.php',__TaioCD__)">
	</td>
	<td colspan="6">
		<input type="button" value="削　除" class="button" onclick="javascript:moveWithKeyAndWork5('s_taio_list.php' , __TaioCD__ , 2 , __TaioCD__  )">
	</td>
<tr>
<tr><td colspan="8"><hr size="__HRSize__" color="__HRColor__"></td></tr>
__TaioListLoop__

</table>
__IfRecord__<!--↑登録あり-->

</form>







</div>

</div><!--content-all-->

<hr>

__SFooter__
__SCopyright__

</body>
</html>
