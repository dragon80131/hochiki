<html>
<head>

<LINK REL="stylesheet" TYPE="text/css" HREF="./css/a5.css">
<script type="text/javascript" src="tools.js"></script>
</head>
<title>営業活動支援システム</title>
<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
__SHeader__
<hr size="__HRSize__" color="__HRColor__">
<center>《支店登録管理》</center>
<hr size="__HRSize__" color="__HRColor__">
<br>


__IfErrorSitenName__
必須項目を入力してください<br>
<input type="button" value="もどる" class="button" onclick="javascript:history.back()">
__IfErrorSitenName__


<form action="s_siten_detail.php" method="POST" >
<input type=hidden name="rKey" value="__rKey__" >


__IfKanrishaFlg__

<a href="#" onclick="javascript:move('s_siten_detail.php?rKey=__rKey__')">＞＞＞　新規登録</a> <br>
</form>


<form action="s_siten_list.php" name="mainform" method="POST">
<input type="hidden" name="editSitenCD" value="" >

<table class="sampleTable"><tr style="color:#ffffff" bgcolor="#4169E1" >
<td>支店CD</td>
<td>支店名</td>
<td>表示順</td>
<td>詳細/編集</td>
<td>削除</td>

</tr>

__SitenListLoop__
<tr>
<td>__SitenCD__</td>
<td>__SitenName__</td>
<td>__DispOrder__</td>
<td><input type="button" value="編集" class="button" onclick="javascript:moveWithSitenCD( 's_siten_detail.php?rKey=__rKey__', __SitenCD__  )"></td>
<td><input type="button" value="削除" class="button" onclick="javascript:moveWithKeyAndWork4( 's_siten_list.php?rKey=__rKey__', __SitenCD__ , 2 , __SitenCD__ )"></td>
</tr>
__SitenListLoop__

</tr></table>

__IfKanrishaFlg__

</form>

<hr size="__HRSize__" color="__HRColor__">
<a href="s_search.php__QUERY__">トップ</a><br>
__SFooter__
<hr size="__HRSize__" color="__HRColor__">
__SCopyright__
<br>
</body>
</html>
