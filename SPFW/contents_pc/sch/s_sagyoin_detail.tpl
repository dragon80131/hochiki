<html>
<head>

<LINK REL="stylesheet" TYPE="text/css" HREF="style.css">
<script type="text/javascript" src="tools.js"></script>
</head>
<title>物件工程表管理</title>
<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
__SHeader__
<hr size="__HRSize__" color="__HRColor__">
<center>《作業員情報入力フォーム》</center>
<hr size="__HRSize__" color="__HRColor__">
<br>


<form action="s_sagyoin_list.php" method="POST" name="mainform">
<input type=hidden name="rKey" value="__rKey__">

<a href="#" onclick="javascript:move('s_sagyoin_list.php')">＜＜＜　作業員一覧</a> <br><br>

<table border=1>
<tr><td  >作業員CD</td><td>(システムが決定しているので変更できません)</td></tr>
<tr><td  >作業員氏名</td><td><input type=text name="wSagyoinName" value="__wSagyoinName__"></td></tr>
<tr><td  >資格</td><td><input type=text name="wSa001" value="__wSa001__"></td></tr>
<tr><td  >メールアドレス</td><td><input type=text size='50' name="wSa002" value="__wSa002__"></td></tr>
<tr><td  >作業員備考</td><td><input type=text name="wSagyoinNotes" value="__wSagyoinNotes__"></td></tr>
</table>


<br>

<input type=hidden name="work" value="">
<input type=hidden name="editSagyoinCD" value="__editSagyoinCD__">


<input type="button" value="登　録"onclick="javascript:moveWithWork('s_sagyoin_list.php', 1 )">　

</form>




<hr size="__HRSize__" color="__HRColor__">
<a href="s_list.php__QUERY__">物件一覧</a><br>
__SFooter__
<hr size="__HRSize__" color="__HRColor__">
__SCopyright__
<br>


</body>

</html>
