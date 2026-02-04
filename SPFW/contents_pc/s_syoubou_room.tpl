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

<link href="../css/jquery-ui-1.8.2.custom.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../js/tools_ajax.js"></script>
<script type="text/javascript" src="../js/ConnectedSelect.js"></script>
<script src="../js/jquery-1.4.2.js" type="text/javascript"></script>
<script src="../js/jquery.ui.core.js" type="text/javascript"></script>


</head>

<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">


<div class="content-all"><!--content-all-->

<hr size="__HRSize__" color="__HRColor__">
<center>《消防検査案内対象》</center>
<hr size="__HRSize__" color="__HRColor__">



部屋を選択して「確認」ボタンをクリックしてください。<br>
<form action="s_syouboukensa_kakutei.php" method="POST" name="mainform" >


<table border=1 >
	__RowsLoop__
	<tr>__ColsBlock__</tr>
	__RowsLoop__
</table>

<br>

<input type="hidden" name="rKey" value="__rKey__" >
<input type="hidden" name="editBukkenCD" value="__editBukkenCD__" >
<input type="hidden" name="work" value="1"><br>
<input type="hidden" name="format" value="__format__"><br>
<input type="button" value="も ど る" onclick="javascript:history.back();"><br><br>
<input type="submit" value= "確　定">
</form>
<br>


</div><!--content-all-->

<hr>

__SFooter__
__SCopyright__

</body>
</html>
