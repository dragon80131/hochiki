<html>
<head>

<LINK REL="stylesheet" TYPE="text/css" HREF="./css/a5.css">
<script type="text/javascript" src="tools.js"></script>
</head>
<title>営業活動支援システム</title>
<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
__SHeader__
<hr size="__HRSize__" color="__HRColor__">
<center>《所属登録管理》</center>
<hr size="__HRSize__" color="__HRColor__">
<br>


__IfErrorEigyoshoName__
必須項目を入力してください<br>
<input type="button" value="もどる" class="button" onclick="javascript:history.back()">
__IfErrorEigyoshoName__


<form action="s_shozoku_detail.php" method="POST" >
<input type=hidden name="rKey" value="__rKey__" >


__IfKanrishaFlg__

<a href="#" onclick="javascript:move('s_shozoku_detail.php?rKey=__rKey__')">＞＞＞　新規登録</a> <br>
</form>


<form action="s_shozoku_list.php" name="mainform" method="POST">
<input type="hidden" name="editEigyoshoCD" value="" >
<input type="hidden" name="work" value="" >

<table class="sampleTable"><tr style="color:#ffffff" bgcolor="#4169E1" >
<td>所属CD</td>
<td>所属名</td>
<td>管轄支店</td>
<td>表示順</td>
<td>詳細/編集</td>
<td>削除</td>

</tr>

__EigyoshoListLoop__
<tr>
<td>__EigyoshoCD__</td>
<td>__EigyoshoName__</td>
<td>__SitenName__</td>
<td>__DispOrder__</td>
<td><input type="button" value="編集" class="button" onclick="javascript:moveWithEigyoshoCD( 's_shozoku_detail.php?rKey=__rKey__', __EigyoshoCD__  )"></td>
<td><input type="button" value="削除" class="button" onclick="javascript:moveWithKeyAndWorkEigyosho( 's_shozoku_list.php?rKey=__rKey__', __EigyoshoCD__ , 2 , __EigyoshoCD__ )"></td>
</tr>
__EigyoshoListLoop__

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
