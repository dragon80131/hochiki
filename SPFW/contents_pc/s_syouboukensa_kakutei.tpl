<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>リニューアル支援</title>

<!-- BootstrapのCSS読み込み -->
<link href="../include/bootstrap/css/bootstrap.min.css" rel="stylesheet">

<!-- jQuery読み込み -->
<script src="../include/js/jquery-3.2.1.min.js"></script>

<!-- BootstrapのJS読み込み -->
<script src="../include/bootstrap/js/bootstrap.min.js"></script>

<link rel="stylesheet" type="text/css" href="../css/rnsien.css">
<script type="text/javascript" src="../tools.js"></script>

<link href="../css/jquery-ui-1.8.2.custom.css" rel="stylesheet" type="text/css" >
<script type="text/javascript" src="../js/tools_ajax.js"></script>
<script type="text/javascript" src="../js/ConnectedSelect.js"></script>
<script src="../js/jquery-1.4.2.js" type="text/javascript"></script>
<script src="../js/jquery.ui.core.js" type="text/javascript"></script>
<script src="../js/jquery.ui.datepicker.js" type="text/javascript"></script>
<script src="../js/jquery.ui.datepicker-ja.js" type="text/javascript"></script>


</head>

<body>
__SHeader__

<div class="content-all"><!--content-all-->

<div class="left-yose">
<!--<a href="../s_doc.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__">＜戻る</a>-->
</div>


<div class="top-menu left-yose">

<h6>消防検査　入室しての火災感知器発報試験</h6>


<font color="red" >部屋構成をご確認ください。</font><br>
__IfOK__


<br>
<hr>

■登録済み部屋構成
 
<form action="s_syouboukensa_Excel.php" method="POST" name="mainform" >


<table border=1 >
	__RowsLoop__
	<tr>__ColsBlock__</tr>
	__RowsLoop__
</table>

<br>
<hr>


<input type="hidden" name="rKey" value="__rKey__" >
<input type="hidden" name="format" value="__format__"><br>
<input type="hidden" name="editBukkenCD" value="__editBukkenCD__" >
<input type="hidden" name="KaiRoom" value="__xKaiRoom__" >
<input type="button" value="も ど る" onclick="javascript:history.back();"><br><br>
<input type="submit" value= "出　力">


</form>
__IfRoomOK__

<br>

</div><!--content-all-->

<hr>

__SFooter__
__SCopyright__

</body>
</html>
