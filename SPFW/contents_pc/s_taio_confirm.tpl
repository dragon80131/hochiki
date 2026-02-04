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
<h5>__wBukkenName__</h5>
<h6>折衝記録登録</h6>


<form action="s_taio_finish.php" method="POST" name="mainform" >

<table class="table table-bordered table-sm">
<tr><td bgcolor="#e3f0fb">折衝日</td><td>__wTaioDate__</td></tr>
<tr><td bgcolor="#e3f0fb">フェーズ</td><td>__wPhaseName__</td></tr>
<tr><td bgcolor="#e3f0fb">支店所属名</td><td>__wSitenEigyoName__</td></tr>
<tr><td bgcolor="#e3f0fb">担当者</td><td>__wTantoName__</td></tr>
<tr><td bgcolor="#e3f0fb">折衝内容</td><td>__DispTaioNotes2__</td></tr>
<tr><td bgcolor="#e3f0fb">ファイル名</td><td> __FileName1__  __FileName2__ __FileName3__ </td></tr>

__IfModify__
<tr><td bgcolor="green" colspan="2">　</td></tr>
<tr><td bgcolor="#e3f0fb">登録日時</td>
	<td><font color="red">__wCreated__</font></td></tr>
__IfModify__

</table>

<br>

<!--ここに変数の値がhiddenでわたされる。-->
__HiddenValues__
<input type="hidden" name="wSitenEigyoName" value="__wSitenEigyoName__" >

__FileLoop__
<input type="hidden" name="image_name[]" value="__image_name__" >
<input type="hidden" name="image_name_kakuchoshi[]" value="__image_name_kakuchoshi__" >
__FileLoop__
__chkLoop__
<input type="hidden" name="chk[]" value="__chk__" >
__chkLoop__

<input type="submit" value = "確　定" class="btn btn-primary">
<!--<input type="button" value = "修　正" onClick="history.back();" >-->
</form>

<br><br>
<form action="s_taio_form.php" method="POST" >
__HiddenValues__
<input type="hidden" name="back" value="1" >
<input type="submit" value = "修　正" class="btn btn-info">
</form>

</div>


</div><!--content-all-->

<hr>

__SFooter__
__SCopyright__

</body>
</html>
