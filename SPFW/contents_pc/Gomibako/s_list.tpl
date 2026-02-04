<html>
<head>

<LINK REL="stylesheet" TYPE="text/css" HREF="./css/a5.css">
<script type="text/javascript" src="tools.js"></script>
__IfAjax__<script type="text/javascript" src="js/jquery-1.2.js"></script>
<script type="text/javascript" src="js/tools_ajax.js"></script>
__IfAjax__</head>


</head>
<title>営業活動支援システム</title>
<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
__SHeader__
<hr size="__HRSize__" color="__HRColor__">
<center>《折衝記録が更新されている物件一覧》</center>
<hr size="__HRSize__" color="__HRColor__">
<br>



<form action="s_list.php" method=POST name="mainform2" >
<input type="hidden" name="SortKey" value="">
<input type="hidden" name="rKey" value="__rKey__">



<br>





			__IfToTop__<a href="#" onClick="javascript:changePage2('s_list.php', 1, __AllPages__)">__IfToTop__&lt;&lt;__IfToTop__</a>__IfToTop__　
			__IfToPre__<a href="#" onClick="javascript:changePage2('s_list.php', __PreviousPage__, __AllPages__)">__IfToPre__&lt;__IfToPre__</a>__IfToPre__　
			<input type="text" name="myPage" value="__myPage__" size="4" __IME_OFF__ class="form">/__AllPages__
			<input type="button" value="ページジャンプ" class="button" onclick="javascript:changePage2('s_list.php', '0', __AllPages__)">　
			__IfToNext__<a href="#" onClick="javascript:changePage2('s_list.php', __NextPage__, __AllPages__)">__IfToNext__&gt;__IfToNext__</a>__IfToNext__　
			__IfToLast__<a href="#" onClick="javascript:changePage2('s_list.php', __LastPage__, __AllPages__)">__IfToLast__&gt;&gt;__IfToLast__</a>__IfToLast__
	






<table border=1><tr style="color:#ffffff" bgcolor="#4169E1">

<td >物件CD</td>

<td >詳細</td>
<td >
物件名</td>
<td>
<a href="#" onclick="javascript:moveWithSortKey('s_list.php', 'CAST( BukkenName AS BINARY )' )">▲</a><br>
<a href="#" onclick="javascript:moveWithSortKey('s_list.php', 'CAST( BukkenName AS BINARY ) desc' )">▼</a>
</td>

<td >営業担当

</td>

<td >最終折衝日</td>
<td >折衝フェーズ</td>
<td >保留</td>
</tr>



</form>

<form action="s_form.php" name="mainform" method="POST" >
<input type="hidden" name="wKensaku" value="__wKensaku__" >
<input type="hidden" name="wKensaku2" value="__wKensaku2__" >
<input type="hidden" name="editBukkenCD" value="">
<input type="hidden" name="work" value="">
<input type="hidden" name="rKey" value="__rKey__">

__BukkenLoop__
<tr>
<td id="__BukkenCD__">__BukkenCD__</td>
<td><input type="button" value="詳細" class="button" onclick="javascript:moveWithKey('s_form.php', __BukkenCD__  )"</td-->
<td colspan=2 >__BukkenName__</td>
<td  >__LastName__</td>


<td>__TaioUpdated__</td>
<td>__TaioPhase__</td>
</tr>
__BukkenLoop__

</tr></table>

</form>


<!--
<form action="./upfile/s_pic_file_OK.php" method="post">
<input type=hidden name=editPictureCD value=__editPictureCD__>
__HiddenValues__
<input type=submit value="全写真ダウンロード">
</form>
-->


<hr size="__HRSize__" color="__HRColor__">
<a href="top.php__QUERY__">トップ</a><br>
<br>

__SFooter__
<hr size="__HRSize__" color="__HRColor__">
__SCopyright__
<br>
</body>
</html>
