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
	<script type="text/javascript" src="../tools.js"></script>
</head>
<title>物件工程表管理</title>
<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
__SHeader__
<hr size="__HRSize__" color="__HRColor__">
<center>《物件一覧》</center>
<hr size="__HRSize__" color="__HRColor__">


__IfKanri__
<form action="s_form.php" method="POST">
<input type=hidden name="rKey" value="__rKey__" >
<a href="#" onclick="javascript:move('s_plan.php')">＞＞＞　人工表</a><br>
<a href="#" onclick="javascript:move('s_list_comp.php')">＞＞＞　完了物件</a><br><br>
<!--<a href="#" onclick="javascript:move('s_eigyotanto_list.php')">＞＞＞　営業担当管理</a><br>-->
<a href="#" onclick="javascript:move('s_sagyoin_list.php')">＞＞＞　作業員登録管理</a><br>
<!--<a href="#" onclick="javascript:move('s_form.php')">＞＞＞　物件新規登録</a><br>-->
</form>

<form action="s_form.php" name="mainform" method="POST" >
<input type="hidden" name="editBukkenCD" value="">
<input type="hidden" name="work" value="">
<input type="hidden" name="rKey" value="__rKey__">
<table border=1>
<tr style="color:#ffffff" bgcolor="#4169E1">
	<td>物件CD</td>
	<td>−</td>
	<td>物件名</td>
	<td>戸数</td>
	<td>営業担当</td>
	<td>物件備考</td>
	<td>共有部開始日</td>
	<td>共有部終了日</td>
	<td>専有部開始日</td>
	<td>専有部終了日</td>
	<td>削除</td>
</tr>
__BukkenLoop__
<tr><td>__BukkenCD__</td>
	<td><input type="button" value="詳細" class="button" onclick="javascript:moveWithKey('s_form.php', __BukkenCD__ )"></td>
	<td>__BukkenName__</td>
	<td>__Kosu__</td>
	<td>__TantoName__</td>
	<td>__BukkenNotes__</td>
	<td>__KyoyoStartDate__</td>
	<td>__KyoyoEndDate__</td>
	<td>__SenyuStartDate__</td>
	<td>__SenyuEndDate__</td>
	<td><input type="button" value="削除" class="button" onclick="javascript:moveWithKeyAndWork( 's_finish.php', __BukkenCD__ , 2 ,__BukkenCD__ )"></td>
</tr>
__BukkenLoop__
</table>
</form>
__IfKanri__



__IfROM__
<form action="s_form.php" method="POST" name="mainform">
<input type=hidden name="rKey" value="__rKey__" >
<a href="#" onclick="javascript:move('s_plan.php')">＞＞＞　作業工程表</a><br>
<a href="#" onclick="javascript:move('s_list_comp.php')">＞＞＞　完了物件</a><br>
</form>

<table border=1>
<tr style="color:#ffffff" bgcolor="#4169E1">
	<td>物件CD</td>
	<td>−</td>
	<td>物件名</td>
	<td>戸数</td>
	<td>営業担当</td>
	<td>物件備考</td>
	<td>共有部開始日</td>
	<td>共有部終了日</td>
	<td>専有部開始日</td>
	<td>専有部終了日</td>
</tr>
__BukkenLoop__
<tr>
	<td>__BukkenCD__</td>
	<td><input type="button" value="詳細" class="button" onclick="javascript:moveWithKey('s_form.php', __BukkenCD__ )"></td>
	<td>__BukkenName__</td>
	<td>__Kosu__</td>
	<td>__TantoName__</td>
	<td>__BukkenNotes__</td>
	<td>__KyoyoStartDate__</td>
	<td>__KyoyoEndDate__</td>
	<td>__SenyuStartDate__</td>
	<td>__SenyuEndDate__</td>
</tr>
__BukkenLoop__
</table>

__IfROM__


<hr size="__HRSize__" color="__HRColor__">
<a href="../logout.php__QUERY__">ログアウト</a><br>
__SFooter__
<hr size="__HRSize__" color="__HRColor__">
__SCopyright__
</body>
</html>
