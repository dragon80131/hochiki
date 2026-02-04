<html>
<head>

<LINK REL="stylesheet" TYPE="text/css" HREF="./css/a5.css">
<script type="text/javascript" src="tools.js"></script>
</head>
<title>物件工程表管理</title>
<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
__SHeader__
<hr size="__HRSize__" color="__HRColor__">
<center>《完了物件一覧》</center>
<hr size="__HRSize__" color="__HRColor__">
<br>

<form action="s_form.php" metho="POST" >
<input type=hidden name="rKey" value="__rKey__" >


<a href="#" onclick="javascript:move('s_list.php')">＞＞＞　（活動中）物件一覧</a> <br>
</form>

<table border=1><tr style="color:#ffffff" bgcolor="#4169E1">
<td>物件CD</td>
<td>−</td>
<td>物件名</td>

<td>戸数</td>
<td>営業担当者名</td>
<td>物件備考</td>
<td>共有部開始日</td>
<td>共有部終了日</td>
<td>専有部開始日</td>
<td>専有部終了日</td>

<td>削除</td>


</tr>
<form action="s_form.php" name="mainform" method="POST" >
<input type="hidden" name="editBukkenCD" value="">
<input type="hidden" name="work" value="">
<input type="hidden" name="rKey" value="__rKey__">
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

<td><input type="button" value="削除" class="button" onclick="javascript:moveWithKeyAndWork( 's_finish.php', __BukkenCD__ , 2 ,__BukkenCD__ )"></td>

</tr>
__BukkenLoop__
</tr></table>

</form>

<hr size="__HRSize__" color="__HRColor__">
<a href="top.php__QUERY__">トップ</a><br>
__SFooter__
<hr size="__HRSize__" color="__HRColor__">
__SCopyright__
<br>
</body>
</html>
