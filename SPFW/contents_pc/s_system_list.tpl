<html>
<head>

<LINK REL="stylesheet" TYPE="text/css" HREF="./css/a5.css">
<script type="text/javascript" src="tools.js"></script>
</head>
<title>営業活動支援システム</title>
<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
__SHeader__
<hr size="__HRSize__" color="__HRColor__">
<center>《システム名管理》</center>
<hr size="__HRSize__" color="__HRColor__">
<br>


__IfErrorSystemName__
必須項目を入力してください<br>
<input type="button" value="もどる" class="button" onclick="javascript:history.back()">
__IfErrorSystemName__


<form action="s_system_detail.php" method="POST" name="mainform">
<input type=hidden name="rKey" value="__rKey__" >


__IfKanrishaFlg__
<font color=red >名称の変更は、すべての物件の登録データに反映されます。 </font><br>
<font color=red >登録直後に、ブラウザでの再読み込みは行わないでください。 </font><br>
<a href="#" onclick="javascript:move('s_system_detail.php?rKey=__rKey__')">＞＞＞　新規登録</a> <br>
</form>


<form action="s_system_detail.php" name="mainform2" method="POST">
<input type="hidden" name="editSystemCD" value="" >
<input type="hidden" name="work" value="" >

<table class="sampleTable"><tr style="color:#ffffff" bgcolor="#4169E1" >
<td>No</td>
<td>システム名</td>
<td>表示順</td>
<td>編集</td>
<td>削除</td>


</tr>

__SystemListLoop__
<tr>
<td>__SystemCD__</td>
<td>__SystemName__</td>
<td>__Sy001__</td>
<td><input type="button" value="編集" class="button" onclick="javascript:moveWithSystemCD( 's_system_detail.php?rKey=__rKey__', __SystemCD__ , 1 )"></td>
<td><input type="button" value="削除" class="button" onclick="javascript:moveWithKeyAndWork6( 's_system_list.php?rKey=__rKey__', __SystemCD__ , 2 , __SystemCD__ )"></td>

</tr>
__SystemListLoop__

</tr></table>
__IfKanrishaFlg__



__IfNonKanrishaFlg__
<table class="sampleTable"><tr style="color:#ffffff" bgcolor="#4169E1" >
<td>No</td>
<td>システム名</td>
<td>表示順</td>



</tr>

__SystemListLoop__
<tr>
<td>__SystemCD__</td>
<td>__SystemName__</td>
<td>__Sy001__</td>
</tr>
__SystemListLoop__

</tr></table>
__IfNonKanrishaFlg__





</form>

<hr size="__HRSize__" color="__HRColor__">
<a href="s_search.php__QUERY__">トップ</a><br>
<br>

<a href="logout.php__QUERY__">ログアウト</a>

__SFooter__
<hr size="__HRSize__" color="__HRColor__">
__SCopyright__
<br>
</body>
</html>
