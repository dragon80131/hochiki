<html>
<head>

<LINK REL="stylesheet" TYPE="text/css" HREF="./css/a5.css">
<script type="text/javascript" src="tools.js"></script>
</head>
<title>営業活動支援システム</title>
<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
__SHeader__
<hr size="__HRSize__" color="__HRColor__">
<center>《営業担当登録管理》</center>
<hr size="__HRSize__" color="__HRColor__">
<br>

<form action="s_tanto_detail.php" method="POST" >
<input type=hidden name="rKey" value="__rKey__" >




__IfKanrishaFlg__

<a href="#" onclick="javascript:move('s_tanto_detail.php?rKey=__rKey__')">＞＞＞　新規登録</a> <br>
<a href="#" onclick="javascript:move('s_shozoku_list.php?rKey=__rKey__')">＞＞＞　所属名管理</a> <br>
</form>


<form action="s_tanto_list.php" name="mainform" method="POST">
<input type="hidden" name="editUserCD" value="" >
<input type="hidden" name="work" value="1" >



<table class="sampleTable"><tr style="color:#ffffff" bgcolor="#4169E1" >
<td>営業担当CD</td>
<td>営業担当名</td>
<td>所属</td>
<td>お知らせメールアドレス</td>
<td>表示順</td>

<td>ユーザ種別</td>
<td>詳細/編集</td>
<td>削除</td>


</tr>

__UserListLoop__
<tr>
<td>__UserCD__</td>
<td>__LastName__</td>
<td>__ShozokuName__</td>
<td>__EMail__</td>
<td>__Extra2__</td>

<td>__Extra3__</td>
<td><input type="button" value="編集" class="button" onclick="javascript:moveWithUserCD( 's_tanto_detail.php?rKey=__rKey__', __UserCD__  )"></td>
<td><input type="button" value="削除" class="button" onclick="javascript:moveWithKeyAndWork5( 's_tanto_list.php?rKey=__rKey__', __UserCD__ , 2 , __UserCD__ )"></td>

</tr>
__UserListLoop__

</tr></table>
__IfKanrishaFlg__



__IfNonKanrishaFlg__
<table class="sampleTable"><tr style="color:#ffffff" bgcolor="#4169E1" >
<td>営業担当CD</td>
<td>営業担当名</td>
<td>所属</td>
<td>お知らせメールアドレス</td>
<td>表示順</td>



</tr>

__UserListLoop__
<tr>
<td>__UserCD__</td>
<td>__LastName__</td>
<td>__ShozokuName__</td>
<td>__EMail__</td>
<td>__Extra2__</td>


</tr>
__UserListLoop__

</tr></table>
__IfNonKanrishaFlg__





</form>

<hr size="__HRSize__" color="__HRColor__">
<a href="s_list.php__QUERY__">物件一覧</a><br>
__SFooter__
<hr size="__HRSize__" color="__HRColor__">
__SCopyright__
<br>
</body>
</html>
