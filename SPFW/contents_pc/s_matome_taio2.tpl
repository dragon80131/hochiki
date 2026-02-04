<html>
<head>

<LINK REL="stylesheet" TYPE="text/css" HREF="./css/a5.css">
<script type="text/javascript" src="tools.js"></script>
<script type="text/javascript" src="js/jquery-1.4.2.js"></script>
<script type="text/javascript" src="js/tools_ajax.js"></script>
</head>
<title>営業活動支援システム</title>
<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
__SHeader__
<hr size="__HRSize__" color="__HRColor__">
<center>《折衝記録まとめ》</center>
<hr size="__HRSize__" color="__HRColor__">
<a href="s_search.php__QUERY__">＜＜トップ</a><br>
<br>

<form method="POST" action="s_taio_matome.php?SortBy=1&Order=__Order__&rKey=__rKey__" name="mainform">


<table border=1>
	<tr bgcolor="silver">
		<td>No</td>
		<td>物件名</td>
		<td>所属名</td>
		<td>担当名</td>
		<td>フェーズ</td>
		<td>折衝内容</td>
		<td>登録日</td>
	</tr>

__BukkenListLoop__
	<tr __BgColor__ >
		<td>__No__</td>
		<td>__BukkenName__</td>
		<td>__EigyoshoName__</td>
		<td>__LastName__</td>
		<td>__Phase__</td>
		<td>折衝日：__TaioDate__<br>__TaioNotes__</td>
		<td>__Created__</td>
	</tr>
__BukkenListLoop__

</table>


<br>



</form>

<hr size="__HRSize__" color="__HRColor__">
<a href="s_search.php__QUERY__">＜＜トップ</a><br>
<br>
<!--<a href="logout.php__QUERY__">ログアウト</a>-->

__SFooter__
<hr size="__HRSize__" color="__HRColor__">
__SCopyright__
<br>
</body>
</html>
