<html>
<head>
<title>物件工程表管理</title>
<LINK REL="stylesheet" TYPE="text/css" HREF="style.css">
<script type="text/javascript" src="tools.js"></script>

</head>
<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
__SHeader__
<hr size="__HRSize__" color="__HRColor__">
<center>《営業担当情報入力フォーム》</center>
<hr size="__HRSize__" color="__HRColor__">


<form action="s_eigyotanto_list.php" method="POST" >
<input type="hidden" name="rKey" value="__rKey__" >
<a href="#" onclick="javascript:move('s_eigyotanto_list.php')">＜＜＜　営業担当一覧</a><br>
</form>


__IfError__
 __ErrorLoop__
<font color="red" >__ErrorString__<br></font>
 __ErrorLoop__
__IfError__

<form action="s_eigyotanto_finish.php" name="mainform" method="POST">
<table border=1 style="width:500px">
<tr><td style="color:#ffffff" bgcolor="#4169E1">営業担当CD</td>
	<td>__editEigyoTantoCD__</td></tr>
<tr><td style="color:#ffffff" bgcolor="#4169E1">担当者名　※必須</td>
	<td><input type="text" name="wTantoName" value="__wTantoName__"></td></tr>
<tr><td style="color:#ffffff" bgcolor="#4169E1">備考</td>
	<td><input type="text" name="wNote" value="__wNote__"></td></tr>
</table>

<br>
<input type="hidden" name="editEigyoTantoCD" value="__editEigyoTantoCD__">
<input type="hidden" name="rKey" value="__rKey__">
<input type="submit" value = "登　録" >

</form>



<hr size="__HRSize__" color="__HRColor__">
__SFooter__
<hr size="__HRSize__" color="__HRColor__">
__SCopyright__
</body>
</html>
