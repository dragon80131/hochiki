<html>
<head>
<title>物件工程表管理</title>
<LINK REL="stylesheet" TYPE="text/css" HREF="./css/style.css">
<script type="text/javascript" src="tools.js"></script>

</head>
<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
__SHeader__
<hr size="__HRSize__" color="__HRColor__">
<center>《営業担当一覧》</center>
<hr size="__HRSize__" color="__HRColor__">



<form action="s_list.php" method="POST">
<input type="hidden" name="rKey" value="__rKey__" >
<a href="#" onclick="javascript:move('s_list.php')">＜＜＜　物件一覧</a><br>

<a href="#" onclick="javascript:move('s_eigyotanto_form.php')">＞＞＞　営業担当新規登録</a><br>
</form>



<form action="s_eigyotanto_form.php" name="mainform" method="POST" >
<input type="hidden" name="editEigyoTantoCD" value="">
<input type="hidden" name="work" value="">
<input type="hidden" name="rKey" value="__rKey__">
<table border=1 style="width:500px">
<tr style="color:#ffffff" bgcolor="#4169E1">
	<td>CD</td>
	<td>編集</td>
	<td>営業担当者名</td>
	<td>備考</td>
	<td>削除</td>
</tr>
__EigyoTantoLoop__
<tr>
	<td nowrap style="width:70px">__EigyoTantoCD__</td>
	<td nowrap style="width:70px"><input type="button" value="編集" class="button" onclick="javascript:moveWithEigyoTantoCD('s_eigyotanto_form.php', __EigyoTantoCD__ )"></td>
	<td nowrap style="width:100px">__TantoName__</td>
	<td>__Note__</td>
	<td nowrap style="width:70px"><input type="button" value="削除" class="button" onclick="javascript:moveWithEigyoTantoCDDel('s_eigyotanto_finish.php', __EigyoTantoCD__ , 2 )"></td>
</tr>
__EigyoTantoLoop__
</table>

</form>



<hr size="__HRSize__" color="__HRColor__">
__SFooter__
<hr size="__HRSize__" color="__HRColor__">
__SCopyright__
</body>
</html>
