<html>
<head>

<LINK REL="stylesheet" TYPE="text/css" HREF="style.css">
<script type="text/javascript" src="tools.js"></script>
</head>
<title>営業活動支援システム</title>
<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
__SHeader__
<hr size="__HRSize__" color="__HRColor__">
<center>《宅配会社名称入力フォーム》</center>
<hr size="__HRSize__" color="__HRColor__">
<br>


<form action="s_tanto_list.php" method="POST" name="mainform">
<input type=hidden name="rKey" value="__rKey__">

<a href="#" onclick="javascript:move('s_tanto_list.php')">＜＜＜　宅配会社名称一覧</a> <br><br>

<table border=1>
<tr><td  >No</td><td>__editTakuhaiCD__(システムが決定しているので変更できません)</td></tr>
<tr><td  >宅配会社名称</td><td><input type=text name="wTakuhaiName" value="__wTakuhaiName__"><font color="red">※必須</font></td></tr>
<tr><td  >表示順</td><td><input type=text name="wTa001" value="__wTa001__"></td></tr>
</table>


<br>

<input type=hidden name="work" value="">
<input type=hidden name="editTakuhaiCD" value="__editTakuhaiCD__">


<input type="button" value="登　録"onclick="javascript:moveWithWork('s_takuhai_list.php', 1 )">　

</form>




<hr size="__HRSize__" color="__HRColor__">
<a href="s_search.php__QUERY__">トップ</a><br>
__SFooter__
<hr size="__HRSize__" color="__HRColor__">
__SCopyright__
<br>


</body>

</html>
