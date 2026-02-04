<html>
<head>
<title>簡単ログイン登録</title>
<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
__SHeader__
<hr size="__HRSize__" color="__HRColor__">
<center>《簡単ログイン登録》</center>
<hr size="__HRSize__" color="__HRColor__">
<br>
簡単ログイン登録をします。登録するとIDやパスワードを入力せずにログインできるようになります。端末識別子の通知をONにして、登録ボタンを押してください。<br>
<br>

<hr size="__HRSize__" color="__HRColor__">
<br>
__IfUIDEmpty__端末識別子の通知を行ってください。<br>
<br>
__IfUIDEmpty__<form method="POST" action="easylogin_regist_finish.php" utn>
__HiddenValues__
__COMMON_POST_QUERY__
<center><input type="submit" value="簡単ログイン登録"></center>
</form>
<hr size="__HRSize__" color="__HRColor__">
<a href="top.php__QUERY__">トップ</a>
__SFooter__
<hr size="__HRSize__" color="__HRColor__">
__SCopyright__
<br>
</body>
</head>
</html>
