<html>
<head>
<title>折衝記録登録</title>
<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
__SHeader__
<hr size="__HRSize__" color="__HRColor__">
<center>登録内容確認</center>
<hr size="__HRSize__" color="__HRColor__">

<br>
<form action="s_taio_finish.php" metho="POST">


<table border=1><tr>
<tr><td bgcolor="#e3f0fb" >折衝日</td><td>__wTaioDate__</td></tr>
<tr><td bgcolor="#e3f0fb" >フェーズ</td><td>__wPhaseName__</td></tr>
<tr><td bgcolor="#e3f0fb" >担当者</td><td>__wTantoName__</td></tr>
<tr><td bgcolor="#e3f0fb" >折衝内容</td><td>__DispTaioNotes__</td></tr>
<tr><td bgcolor="#e3f0f0" >タイムスタンプ</td><td>__TimeStamp__</td></tr>





</tr>
</tr></table>
<br>






<!--ここに変数の値がhiddenでわたされる。-->
__HiddenValues__


<input type=submit value = "確　定" >
<input type=button value = "修　正" onClick="history.back();" >


</form>




<hr size="__HRSize__" color="__HRColor__">
<a href="top.php__QUERY__">トップ</a><br>
__SFooter__
<hr size="__HRSize__" color="__HRColor__">
__SCopyright__
<br>
</body>
</head>
</html>
