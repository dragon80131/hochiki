<html>
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
	<script type="text/javascript" src="./tools.js"></script>
	
</head>
<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
__SHeader__
<hr size="__HRSize__" color="__HRColor__">
<center>《残工事・現調 更新》</center>
<hr size="__HRSize__" color="__HRColor__">
<br>
<br>



__IfError__
物件情報が更新できませんでした。
__IfError__

__IfOK__
残作業・現場調査　作業日を更新しました。

<br>

__IfOK__

__IfDeleteOK__

物件データを削除しました。<br>
__IfDeleteOK__

<br>

<hr size="__HRSize__" color="__HRColor__">
<form action="s_zan.php" method="POST">
<!--ここに変数の値がhiddenでわたされる。-->
__HiddenValues__
<input type=submit value = "残工事・現場調査一覧"  class="btn btn-primary">
</form>

__SFooter__
<hr size="__HRSize__" color="__HRColor__">
__SCopyright__
<br>
</body>

</html>
