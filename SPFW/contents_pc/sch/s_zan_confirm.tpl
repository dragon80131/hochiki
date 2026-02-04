<html>
<head>
<title>残工事・現調 管理</title>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">


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
<center>《残工事・現調 確認》</center>
<hr size="__HRSize__" color="__HRColor__">

<br>
<form action="s_zan_finish.php" metho="POST">


<table border=1><tr>
<tr><td>作業日</td><td>__tYear__年 __tMonth__月 __editDateCD__日</td></tr>
<tr><tr><td>作業分類</td><td>__wZanMenuName__</td></tr>
<tr><tr><td>作業内容</td><td>__wZanScheduleNotes__</td></tr>

<tr>
<td>作業員</td>
<td>
__SagyoinLoop__
__wSagyoinName__
__SagyoinLoop__

</td></tr>



</tr>




</tr>
</tr></table>
<br>
<input type=hidden name="rKey" value="__rKey__" >
<input type=hidden name="editDateCD" value="__editDateCD__">
<input type=hidden name="tMonth" value="__tMonth__">
<input type=hidden name="tYear" value="__tYear__">

<!--ここに変数の値がhiddenでわたされる。-->
__HiddenValues__


<input type=submit value = "確　定"  class="btn btn-primary">



</form>




<hr size="__HRSize__" color="__HRColor__">
<a href="top.php__QUERY__">トップ</a><br>
__SFooter__
<hr size="__HRSize__" color="__HRColor__">
__SCopyright__
<br>
</body>
</html>
