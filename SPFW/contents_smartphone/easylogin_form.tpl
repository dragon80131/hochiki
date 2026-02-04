<html>
<head>
<title>簡単ログイン</title>
<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
__SHeader__
<hr size="__HRSize__" color="__HRColor__">
<center>《簡単ログイン》</center>
<hr size="__HRSize__" color="__HRColor__">
<br>
簡単ログインをします。端末識別子の通知をONにして、ログインボタンを押してください。<br>
<br>

<hr size="__HRSize__" color="__HRColor__">
<br>
__IfError__<font color="red">__ErrorMessage__</font><br>__IfError__
__IfUIDEmpty__<font color="red">端末識別子の通知を行ってください。</font><br>
<br>
__IfUIDEmpty__<form method="POST" action="easylogin_finish.php" utn>
__HiddenValues__
__COMMON_POST_QUERY__
<center><input type="submit" value="簡単ログイン"></center>
</form>
<br>
<hr size="__HRSize__" color="__HRColor__">
<a href="top.php__QUERY__">トップ</a>
__SFooter__
<hr size="__HRSize__" color="__HRColor__">
__SCopyright__
<br>
</body>
</head>
</html>
